<?php
/**
 * Tạo dấu vân tay chỉ đọc cho trạng thái cần bảo toàn trên 5plc.
 *
 * Chạy bằng WP-CLI:
 * wp --path=".../5plc/app/public" eval-file fingerprint_5plc.php
 *
 * Tệp không gọi bất kỳ hàm ghi, cập nhật hoặc xóa dữ liệu nào.
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Tệp này phải được chạy bằng WP-CLI trong WordPress.\n");
    exit(2);
}

function handbook_is_list_array(array $value): bool
{
    if (function_exists('array_is_list')) {
        return array_is_list($value);
    }

    return array_keys($value) === range(0, count($value) - 1);
}

function handbook_stable_value($value)
{
    if (is_object($value)) {
        $value = get_object_vars($value);
    }

    if (!is_array($value)) {
        return $value;
    }

    if (!handbook_is_list_array($value)) {
        ksort($value, SORT_STRING);
    }

    foreach ($value as $key => $item) {
        $value[$key] = handbook_stable_value($item);
    }

    return $value;
}

function handbook_hash($value): string
{
    return hash('sha256', serialize(handbook_stable_value($value)));
}

function handbook_sorted_meta(int $object_id, string $kind = 'post'): array
{
    $meta = $kind === 'term'
        ? get_term_meta($object_id)
        : get_post_meta($object_id);
    ksort($meta, SORT_STRING);

    foreach ($meta as &$values) {
        if (is_array($values)) {
            sort($values, SORT_STRING);
        }
    }
    unset($values);

    return $meta;
}

$original_post_ids = [2, 3, 8, 20, 22, 24, 26, 28];
$original_posts = [];
foreach ($original_post_ids as $post_id) {
    $post = get_post($post_id, ARRAY_A);
    if (!$post) {
        $original_posts[(string) $post_id] = null;
        continue;
    }

    $taxonomies = get_object_taxonomies($post['post_type']);
    sort($taxonomies, SORT_STRING);
    $terms = [];
    foreach ($taxonomies as $taxonomy) {
        $term_ids = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
        if (is_wp_error($term_ids)) {
            $term_ids = [];
        }
        $term_ids = array_map('intval', $term_ids);
        sort($term_ids, SORT_NUMERIC);
        $terms[$taxonomy] = $term_ids;
    }

    $original_posts[(string) $post_id] = [
        'post' => $post,
        'meta' => handbook_sorted_meta($post_id),
        'terms' => $terms,
    ];
}

$original_category_ids = [1, 4, 5, 6, 7, 8, 9, 10];
$original_categories = [];
foreach ($original_category_ids as $term_id) {
    $term = get_term($term_id, 'category', ARRAY_A);
    $original_categories[(string) $term_id] = is_wp_error($term) || !$term
        ? null
        : [
            'term' => $term,
            'meta' => handbook_sorted_meta($term_id, 'term'),
        ];
}

$menu_term_id = 3;
$menu = wp_get_nav_menu_object($menu_term_id);
$menu_items = wp_get_nav_menu_items($menu_term_id, [
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'post_status' => 'any',
]);
$menu_state = [
    'term' => $menu ? get_object_vars($menu) : null,
    'locations' => get_nav_menu_locations(),
    'items' => [],
];
foreach ($menu_items ?: [] as $item) {
    $menu_state['items'][] = [
        'post' => get_post($item->ID, ARRAY_A),
        'meta' => handbook_sorted_meta((int) $item->ID),
    ];
}

if (!function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$active_plugins = array_flip((array) get_option('active_plugins', []));
$plugins = [];
foreach (get_plugins() as $basename => $data) {
    $plugins[$basename] = [
        'name' => $data['Name'] ?? '',
        'version' => $data['Version'] ?? '',
        'active' => isset($active_plugins[$basename]),
    ];
}
ksort($plugins, SORT_STRING);

$theme = wp_get_theme();
$theme_state = [
    'name' => $theme->get('Name'),
    'version' => $theme->get('Version'),
    'template' => get_option('template'),
    'stylesheet' => get_option('stylesheet'),
];

$settings_state = [
    'WPLANG' => get_option('WPLANG'),
    'timezone_string' => get_option('timezone_string'),
    'show_on_front' => get_option('show_on_front'),
    'page_on_front' => (int) get_option('page_on_front'),
    'wp_page_for_privacy_policy' => (int) get_option('wp_page_for_privacy_policy'),
];

$fingerprints = [
    'ct_settings' => handbook_hash(get_option('ct_settings')),
    'sidebars_widgets' => handbook_hash(get_option('sidebars_widgets')),
    'theme_mods_church' => handbook_hash(get_option('theme_mods_church')),
    'original_posts_pages' => handbook_hash($original_posts),
    'original_categories' => handbook_hash($original_categories),
    'menu_1' => handbook_hash($menu_state),
    'plugin_states' => handbook_hash($plugins),
    'active_theme' => handbook_hash($theme_state),
    'permanent_settings' => handbook_hash($settings_state),
];

echo wp_json_encode([
    'algorithm' => 'sha256(serialize(recursively-key-sorted-value))',
    'fingerprints' => $fingerprints,
    'summary' => [
        'original_post_page_ids' => $original_post_ids,
        'original_category_ids' => $original_category_ids,
        'menu_term_id' => $menu_term_id,
        'menu_item_count' => count($menu_items ?: []),
        'plugin_count' => count($plugins),
        'theme' => $theme_state,
        'settings' => $settings_state,
    ],
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
