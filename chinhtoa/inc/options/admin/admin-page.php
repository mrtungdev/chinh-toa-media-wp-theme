<?php

/**
 * Native theme-options admin editor (Settings API).
 *
 * Replaces Unyson's options panel. Registers a single `ct_settings` option, a
 * submenu page under the theme menu, a tabbed form whose field names encode the
 * full nested path, and a recursive sanitizer. Saving reproduces the exact data
 * shape the front-end flatteners read (proven by tests/options-test.php).
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/fields.php';
require_once __DIR__ . '/sections.php';

/** Register the option + sanitizer. */
function ct_register_settings()
{
    register_setting('ct_settings_group', CT_SETTINGS_OPTION, array(
        'type'              => 'array',
        'sanitize_callback' => 'ct_settings_sanitize_callback',
        'default'           => array(),
    ));
}
add_action('admin_init', 'ct_register_settings');

/**
 * Recursive sanitizer. Receives the unslashed $_POST['ct_settings'] tree.
 * Plain strings are trimmed; explicit rich-HTML fields go through wp_kses_post;
 * raw script fields are kept verbatim (page is manage_options-only, matching the
 * previous behavior where admins could inject head/footer scripts).
 *
 * @param mixed $input
 * @return array
 */
function ct_settings_sanitize_callback($input)
{
    if (!is_array($input)) {
        return array();
    }
    return ct_settings_sanitize_walk($input);
}

function ct_settings_sanitize_walk($value, $key = '')
{
    // Single source of truth lives in storage.php (filterable).
    static $raw_keys  = null;
    static $rich_keys = null;
    if (null === $raw_keys) {
        $raw_keys  = ct_raw_script_keys();
        $rich_keys = ct_rich_text_keys();
    }

    if (is_array($value)) {
        $out = array();
        foreach ($value as $k => $v) {
            $sk        = is_string($k) ? sanitize_key($k) : $k;
            $out[$sk]  = ct_settings_sanitize_walk($v, is_string($k) ? $k : '');
        }
        return $out;
    }
    if (is_string($value)) {
        if (in_array($key, $raw_keys, true)) {
            return trim($value);
        }
        if (in_array($key, $rich_keys, true)) {
            return wp_kses_post($value);
        }
        return trim($value);
    }
    return $value;
}

/** Add the settings submenu under the theme menu (priority after the menu is built). */
function ct_add_settings_menu()
{
    $hook = add_submenu_page(
        'theme-options',
        __('Thiết lập giao diện', 'chinhtoa'),
        __('Thiết lập giao diện', 'chinhtoa'),
        'manage_options',
        'ct-theme-settings',
        'ct_render_settings_page'
    );
    if ($hook) {
        add_action('load-' . $hook, 'ct_settings_page_loaded');
    }
}
add_action('admin_menu', 'ct_add_settings_menu', 11);

/** Flag used to scope asset loading to our page only. */
function ct_settings_page_loaded()
{
    add_action('admin_enqueue_scripts', 'ct_settings_enqueue');
}

function ct_settings_enqueue()
{
    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script(
        'ct-admin-options',
        CT_THEME_JS_URI . '/admin-options-native.js',
        array('jquery', 'wp-color-picker', 'jquery-ui-sortable'),
        defined('THEME_VERSION') ? THEME_VERSION : false,
        true
    );
    wp_enqueue_style(
        'ct-admin-options',
        CT_THEME_CSS_URI . '/admin-options-native.css',
        array(),
        defined('THEME_VERSION') ? THEME_VERSION : false
    );
}

/** Render the tabbed settings page. */
function ct_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
    $s = ct_get_settings();

    // Two-level IA mirroring the old Unyson panel: top-level groups, each with
    // ordered sub-tabs. "Thiết lập chung" gathers general/header/footer/tech as
    // sub-tabs; the rest are single-panel. Sub-panels reuse the existing
    // ct_section_* renderers unchanged.
    $groups = array(
        'setup'    => __('Thiết lập chung', 'chinhtoa'),
        'homepage' => __('Trang chủ', 'chinhtoa'),
        'hot'      => __('Thông báo', 'chinhtoa'),
        'default'  => __('Mặc định', 'chinhtoa'),
    );
    $subs = array(
        'setup'    => array(
            'general' => __('Màu & Hiển thị', 'chinhtoa'),
            'header'  => __('Header', 'chinhtoa'),
            'footer'  => __('Cuối trang', 'chinhtoa'),
            'tech'    => __('Kỹ thuật', 'chinhtoa'),
        ),
        'homepage' => array('homepage' => __('Trang chủ', 'chinhtoa')),
        'hot'      => array('hot' => __('Thanh thông báo', 'chinhtoa')),
        'default'  => array('default' => __('Mặc định', 'chinhtoa')),
    );
    $renderers = array(
        'general'  => 'ct_section_general',
        'header'   => 'ct_section_header',
        'footer'   => 'ct_section_footer',
        'homepage' => 'ct_section_homepage',
        'hot'      => 'ct_section_hot',
        'default'  => 'ct_section_default',
        'tech'     => 'ct_section_tech',
    );
    ?>
    <div class="wrap ct-options-wrap">
        <h1><?php echo esc_html__('Thiết lập giao diện', 'chinhtoa'); ?></h1>
        <nav class="ct-tabs">
            <?php $first = true; foreach ($groups as $key => $label) : ?>
                <a href="#" class="ct-tab<?php echo $first ? ' is-active' : ''; ?>" data-tab="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></a>
            <?php $first = false; endforeach; ?>
        </nav>
        <form action="options.php" method="post">
            <?php settings_fields('ct_settings_group'); ?>
            <?php $firstGroup = true; foreach ($groups as $gkey => $glabel) : $gsubs = $subs[$gkey]; ?>
                <div class="ct-tab-panel" data-tab="<?php echo esc_attr($gkey); ?>"<?php echo $firstGroup ? '' : ' style="display:none"'; ?>>
                    <?php if (count($gsubs) > 1) : ?>
                        <div class="ct-subtabs">
                            <?php $firstSub = true; foreach ($gsubs as $skey => $slabel) : ?>
                                <a href="#" class="ct-subtab<?php echo $firstSub ? ' is-active' : ''; ?>" data-subtab="<?php echo esc_attr($skey); ?>"><?php echo esc_html($slabel); ?></a>
                            <?php $firstSub = false; endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php $firstSub = true; foreach ($gsubs as $skey => $slabel) : ?>
                        <div class="ct-subtab-panel" data-subtab="<?php echo esc_attr($skey); ?>"<?php echo $firstSub ? '' : ' style="display:none"'; ?>>
                            <?php call_user_func($renderers[$skey], $s); ?>
                        </div>
                    <?php $firstSub = false; endforeach; ?>
                </div>
            <?php $firstGroup = false; endforeach; ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/* ----------------------------------------------------------------------------
 * Native term-meta (category) + post-meta editors, replacing Unyson's term/post
 * option boxes. Both store under meta key `ct_options` in the same nested shape
 * (cat_custom / post_custom) the flatteners read.
 * ------------------------------------------------------------------------- */
require_once __DIR__ . '/term-post-meta.php';
