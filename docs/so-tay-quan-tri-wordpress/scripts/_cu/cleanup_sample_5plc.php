<?php
/**
 * Kiểm tra và xóa đúng dữ liệu [TÀI LIỆU MẪU] trên 5plc.
 *
 * Mặc định chỉ kiểm tra, không ghi dữ liệu:
 *   wp --path=".../5plc/app/public" eval-file cleanup_sample_5plc.php
 *
 * Chỉ sau khi người dùng xác nhận xóa, đặt đồng thời hai biến sau:
 *   HANDBOOK_CLEANUP_MODE=apply
 *   HANDBOOK_CLEANUP_TOKEN=DELETE_DOCUMENTATION_SAMPLES_20260905
 *
 * Tệp sẽ dừng trước khi xóa nếu bất kỳ ID, loại nội dung, tiêu đề, tác giả,
 * quan hệ bản sửa đổi, chuyên mục, media hoặc tài khoản nào lệch biên bản.
 */

if (!defined('ABSPATH') || !defined('WP_CLI') || !WP_CLI) {
    fwrite(STDERR, "Tệp này chỉ được chạy bằng WP-CLI trong WordPress.\n");
    exit(2);
}

$apply = getenv('HANDBOOK_CLEANUP_MODE') === 'apply';
$token = (string) getenv('HANDBOOK_CLEANUP_TOKEN');
$required_token = 'DELETE_DOCUMENTATION_SAMPLES_20260905';
$prefix = '[TÀI LIỆU MẪU]';
$errors = [];

$expected_posts = [
    40 => [
        'post_type' => 'post',
        'post_status' => 'draft',
        'post_parent' => 0,
        'post_author' => 2,
        'post_title' => '[TÀI LIỆU MẪU] Bài viết hướng dẫn',
    ],
    41 => [
        'post_type' => 'revision',
        'post_status' => 'inherit',
        'post_parent' => 40,
        'post_author' => 2,
        'post_title' => '[TÀI LIỆU MẪU] Bài viết hướng dẫn',
    ],
    42 => [
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'post_parent' => 0,
        'post_title' => '[TÀI LIỆU MẪU] Ảnh minh họa',
    ],
    43 => [
        'post_type' => 'page',
        'post_status' => 'draft',
        'post_parent' => 0,
        'post_author' => 2,
        'post_title' => '[TÀI LIỆU MẪU] Trang hướng dẫn',
    ],
    44 => [
        'post_type' => 'revision',
        'post_status' => 'inherit',
        'post_parent' => 43,
        'post_author' => 2,
        'post_title' => '[TÀI LIỆU MẪU] Trang hướng dẫn',
    ],
    45 => [
        'post_type' => 'page',
        'post_status' => 'draft',
        'post_parent' => 0,
        'post_author' => 2,
        'post_title' => '[TÀI LIỆU MẪU] Trang Elementor',
    ],
    46 => [
        'post_type' => 'revision',
        'post_status' => 'inherit',
        'post_parent' => 45,
        'post_author' => 2,
        'post_title' => '[TÀI LIỆU MẪU] Trang Elementor',
    ],
];

$actual_posts = [];
foreach ($expected_posts as $post_id => $expected) {
    $post = get_post($post_id, ARRAY_A);
    if (!$post) {
        $errors[] = "Không tìm thấy post ID {$post_id}.";
        continue;
    }

    $actual_posts[(string) $post_id] = [
        'post_type' => $post['post_type'],
        'post_status' => $post['post_status'],
        'post_parent' => (int) $post['post_parent'],
        'post_author' => (int) $post['post_author'],
        'post_title' => $post['post_title'],
    ];

    foreach ($expected as $field => $expected_value) {
        $actual_value = $actual_posts[(string) $post_id][$field] ?? null;
        if ($actual_value !== $expected_value) {
            $errors[] = "Post ID {$post_id}: {$field} không khớp biên bản.";
        }
    }
}

global $wpdb;
$like = $wpdb->esc_like($prefix) . '%';
$sample_post_ids = array_map(
    'intval',
    $wpdb->get_col(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_title LIKE %s ORDER BY ID",
            $like
        )
    )
);
$expected_sample_post_ids = array_keys($expected_posts);
sort($sample_post_ids, SORT_NUMERIC);
sort($expected_sample_post_ids, SORT_NUMERIC);
if ($sample_post_ids !== $expected_sample_post_ids) {
    $errors[] = 'Danh sách post mang tiền tố mẫu không khớp chính xác các ID 40–46.';
}

$user_owned_post_ids = array_map(
    'intval',
    $wpdb->get_col(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_author = %d ORDER BY ID",
            2
        )
    )
);
$expected_user_owned_post_ids = [40, 41, 43, 44, 45, 46];
if ($user_owned_post_ids !== $expected_user_owned_post_ids) {
    $errors[] = 'Tài khoản ID 2 đang sở hữu nội dung ngoài sáu post/revision mẫu đã ghi.';
}

$term = get_term(11, 'category');
$term_state = null;
if (is_wp_error($term) || !$term) {
    $errors[] = 'Không tìm thấy chuyên mục mẫu ID 11.';
} else {
    $term_state = [
        'term_id' => (int) $term->term_id,
        'name' => $term->name,
        'slug' => $term->slug,
        'count' => (int) $term->count,
    ];
    if ($term->name !== '[TÀI LIỆU MẪU] Chuyên mục minh họa' || (int) $term->count !== 0) {
        $errors[] = 'Chuyên mục ID 11 không còn đúng tên mẫu hoặc không còn rỗng.';
    }
}

$sample_terms = get_terms([
    'taxonomy' => 'category',
    'hide_empty' => false,
    'search' => $prefix,
]);
$sample_term_ids = is_wp_error($sample_terms)
    ? []
    : array_values(array_map(static fn($item) => (int) $item->term_id, $sample_terms));
sort($sample_term_ids, SORT_NUMERIC);
if ($sample_term_ids !== [11]) {
    $errors[] = 'Danh sách chuyên mục mang tiền tố mẫu không chỉ gồm ID 11.';
}

$user = get_user_by('id', 2);
$user_state = null;
if (!$user) {
    $errors[] = 'Không tìm thấy tài khoản mẫu ID 2.';
} else {
    $roles = array_values($user->roles);
    sort($roles, SORT_STRING);
    $user_state = [
        'ID' => (int) $user->ID,
        'user_login' => $user->user_login,
        'roles' => $roles,
    ];
    if ($user->user_login !== 'tai_lieu_editor' || $roles !== ['editor']) {
        $errors[] = 'Tài khoản ID 2 không còn đúng tên đăng nhập hoặc vai trò Biên tập viên.';
    }
}

$attached_file = (string) get_post_meta(42, '_wp_attached_file', true);
$attachment_metadata = wp_get_attachment_metadata(42);
$attachment_sizes = is_array($attachment_metadata) && isset($attachment_metadata['sizes'])
    ? array_keys((array) $attachment_metadata['sizes'])
    : [];
sort($attachment_sizes, SORT_STRING);
$expected_attachment_sizes = ['large', 'medium', 'medium_large', 'small', 'thumbnail'];
if ($attached_file !== '2026/09/screenshot.png') {
    $errors[] = 'Media ID 42 không còn trỏ tới tệp 2026/09/screenshot.png.';
}
if ($attachment_sizes !== $expected_attachment_sizes) {
    $errors[] = 'Các kích thước sinh kèm của media ID 42 không khớp năm tệp đã ghi.';
}

$report = [
    'mode' => $apply ? 'apply' : 'dry-run',
    'preflight_ok' => !$errors,
    'errors' => $errors,
    'targets' => [
        'posts' => $actual_posts,
        'sample_post_ids' => $sample_post_ids,
        'user_owned_post_ids' => $user_owned_post_ids,
        'category' => $term_state,
        'user' => $user_state,
        'attachment_file' => $attached_file,
        'attachment_sizes' => $attachment_sizes,
    ],
];

if ($errors) {
    echo wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
    fwrite(STDERR, "Kiểm tra trước khi xóa không đạt; không có dữ liệu nào bị thay đổi.\n");
    exit(1);
}

if (!$apply) {
    echo wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
    exit(0);
}

if (!hash_equals($required_token, $token)) {
    fwrite(STDERR, "Thiếu token xác nhận chính xác; không có dữ liệu nào bị thay đổi.\n");
    exit(2);
}

foreach ([40, 43, 45] as $post_id) {
    if (!wp_delete_post($post_id, true)) {
        fwrite(STDERR, "Không thể xóa post mẫu ID {$post_id}; dừng thao tác.\n");
        exit(1);
    }
}

if (!wp_delete_attachment(42, true)) {
    fwrite(STDERR, "Không thể xóa media mẫu ID 42; dừng thao tác.\n");
    exit(1);
}

$deleted_term = wp_delete_term(11, 'category');
if (is_wp_error($deleted_term) || !$deleted_term) {
    fwrite(STDERR, "Không thể xóa chuyên mục mẫu ID 11; dừng thao tác.\n");
    exit(1);
}

if (!function_exists('wp_delete_user')) {
    require_once ABSPATH . 'wp-admin/includes/user.php';
}
if (!wp_delete_user(2, 1)) {
    fwrite(STDERR, "Không thể xóa tài khoản mẫu ID 2; dừng thao tác.\n");
    exit(1);
}

$remaining_target_ids = [];
foreach (array_keys($expected_posts) as $post_id) {
    if (get_post($post_id)) {
        $remaining_target_ids[] = $post_id;
    }
}
$remaining_term = get_term(11, 'category');
$remaining_user = get_user_by('id', 2);

$report['deleted'] = [
    'post_and_revision_ids' => [40, 41, 43, 44, 45, 46],
    'attachment_id' => 42,
    'category_id' => 11,
    'user_id' => 2,
];
$report['post_delete_verification'] = [
    'remaining_target_ids' => $remaining_target_ids,
    'category_exists' => !is_wp_error($remaining_term) && (bool) $remaining_term,
    'user_exists' => (bool) $remaining_user,
];

echo wp_json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;

if ($remaining_target_ids || (!is_wp_error($remaining_term) && $remaining_term) || $remaining_user) {
    fwrite(STDERR, "Có mục tiêu vẫn còn sau thao tác; cần kiểm tra thủ công.\n");
    exit(1);
}
