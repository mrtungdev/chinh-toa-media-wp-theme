<?php

/**
 * Native term-meta (category) and post-meta editors.
 *
 * Replace Unyson's term/post option boxes. Both store a nested array under meta
 * key `ct_options` in the exact shape the flatteners read:
 *   - category: ['cat_custom' => ['action_show','y'=>[columns,display_style,sidebar,
 *                post_thumb,post_exper,post_meta]], 'icon'=>['type','icon-class','url'],
 *                'iconcolor','backgroundcolor','textcolor']
 *   - post    : ['post_custom' => ['action_show','y'=>[sidebar,post_thumb,
 *                post_breadcrumb,post_title,post_author,post_info]]]
 *
 * The fields are rendered with a shared, width-robust stacked layout (ct_mb_*)
 * so the same controls look right in the narrow post side-column and on the
 * full-width category edit screen. Every control lives in a `.ct-field-row`
 * with an optional `data-ct-show`, which admin-options-native.js uses to reveal
 * sub-options. Hidden rows still submit (matching Unyson), so toggling a master
 * switch off never discards the values underneath.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ----------------------------------------------------------- shared helpers */

/** Recursively sanitize a meta payload (trim strings, sanitize keys). */
function ct_meta_sanitize($value, $key = '')
{
    if (is_array($value)) {
        $out = array();
        foreach ($value as $k => $v) {
            $sk       = is_string($k) ? sanitize_key($k) : $k;
            $out[$sk] = ct_meta_sanitize($v, is_string($k) ? $k : '');
        }
        return $out;
    }
    if (is_string($value)) {
        if ('url' === $key) {
            return esc_url_raw(trim($value));
        }
        return trim($value);
    }
    return $value;
}

/** Enqueue color-picker + media on category / post edit screens. */
function ct_meta_enqueue($hook)
{
    if (in_array($hook, array('edit-tags.php', 'term.php', 'post.php', 'post-new.php'), true)) {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('ct-admin-options', CT_THEME_JS_URI . '/admin-options-native.js', array('jquery', 'wp-color-picker', 'jquery-ui-sortable'), defined('THEME_VERSION') ? THEME_VERSION : false, true);
        wp_enqueue_style('ct-admin-options', CT_THEME_CSS_URI . '/admin-options-native.css', array(), defined('THEME_VERSION') ? THEME_VERSION : false);
    }
}
add_action('admin_enqueue_scripts', 'ct_meta_enqueue');

/* ------------------------------------------------ shared meta-box field UI */

/** Read $arr[$key] with a fallback, treating '' as unset. */
function ct_mb_get($arr, $key, $default)
{
    return (is_array($arr) && isset($arr[$key]) && '' !== $arr[$key]) ? $arr[$key] : $default;
}

/** data-ct-show attributes for a conditionally-revealed row. */
function ct_mb_show_attrs($show)
{
    if (!is_array($show)) {
        return '';
    }
    return ' data-ct-show="' . esc_attr($show[0]) . '" data-ct-show-val="' . esc_attr($show[1]) . '"';
}

/** Open a stacked field row (label on top, control below). */
function ct_mb_open($label, $show = null)
{
    echo '<div class="ct-field-row"' . ct_mb_show_attrs($show) . '>';
    if ('' !== $label) {
        echo '<span class="ct-mb-label">' . esc_html($label) . '</span>';
    }
    echo '<div class="ct-mb-control">';
}

/** Close a stacked field row, optionally printing a description beneath it. */
function ct_mb_close($desc = '')
{
    echo '</div>';
    if ('' !== $desc) {
        echo '<p class="ct-mb-desc description">' . esc_html($desc) . '</p>';
    }
    echo '</div>';
}

/** A light section heading inside a meta box. */
function ct_mb_section($title)
{
    echo '<div class="ct-mb-section">' . esc_html($title) . '</div>';
}

/** Toggle switch backed by hidden + checkbox, so it always submits $on/$off. */
function ct_mb_switch($name, $value, $label, $desc = '', $show = null, $on = 'y', $off = 'n')
{
    echo '<div class="ct-field-row ct-mb--toggle"' . ct_mb_show_attrs($show) . '>';
    echo '<div class="ct-mb-head"><span class="ct-mb-label">' . esc_html($label) . '</span>';
    printf('<input type="hidden" name="%s" value="%s">', esc_attr($name), esc_attr($off));
    printf(
        '<label class="ct-switch"><input type="checkbox" class="ct-switch-input" name="%s" value="%s"%s><span class="ct-switch-track"><span class="ct-switch-knob"></span></span></label>',
        esc_attr($name),
        esc_attr($on),
        checked($value, $on, false)
    );
    echo '</div>';
    if ('' !== $desc) {
        echo '<p class="ct-mb-desc description">' . esc_html($desc) . '</p>';
    }
    echo '</div>';
}

/** Image-swatch radio. $choices = value => image-url. */
function ct_mb_image_radio($name, $value, $label, $choices, $desc = '', $show = null)
{
    ct_mb_open($label, $show);
    echo '<div class="ct-image-radio" data-ct-name="' . esc_attr($name) . '">';
    foreach ($choices as $val => $img) {
        printf(
            '<label class="ct-image-radio-item%s"><input type="radio" name="%s" value="%s"%s><img src="%s" alt="%s"></label>',
            ((string) $value === (string) $val ? ' selected' : ''),
            esc_attr($name),
            esc_attr($val),
            checked($value, $val, false),
            esc_url($img),
            esc_attr($val)
        );
    }
    echo '</div>';
    ct_mb_close($desc);
}

/** Plain select. $choices = value => label. */
function ct_mb_select($name, $value, $label, $choices, $desc = '', $show = null)
{
    ct_mb_open($label, $show);
    printf('<select class="ct-mb-input" name="%s">', esc_attr($name));
    foreach ($choices as $val => $text) {
        printf('<option value="%s"%s>%s</option>', esc_attr($val), selected($value, $val, false), esc_html($text));
    }
    echo '</select>';
    ct_mb_close($desc);
}

/** Single-line text input. */
function ct_mb_text($name, $value, $label, $desc = '', $show = null, $placeholder = '')
{
    ct_mb_open($label, $show);
    printf(
        '<input type="text" class="ct-mb-input" name="%s" value="%s" placeholder="%s">',
        esc_attr($name),
        esc_attr($value),
        esc_attr($placeholder)
    );
    ct_mb_close($desc);
}

/** Color picker (wp-color-picker, initialised by admin-options-native.js). */
function ct_mb_color($name, $value, $label, $desc = '', $show = null)
{
    ct_mb_open($label, $show);
    printf('<input type="text" class="ct-color-field" name="%s" value="%s" data-default-color="">', esc_attr($name), esc_attr($value));
    ct_mb_close($desc);
}

/** Media (image URL) picker with preview + clear. */
function ct_mb_media($name, $value, $label, $desc = '', $show = null)
{
    ct_mb_open($label, $show);
    echo '<div class="ct-media-field">';
    printf('<input type="text" class="ct-mb-input ct-media-url" name="%s" value="%s">', esc_attr($name), esc_attr($value));
    echo '<span class="ct-media-actions">';
    echo '<button type="button" class="button ct-media-btn">' . esc_html__('Chọn ảnh', 'chinhtoa') . '</button> ';
    echo '<button type="button" class="button ct-media-clear">' . esc_html__('Xoá', 'chinhtoa') . '</button>';
    echo '</span>';
    echo '<div class="ct-media-preview">' . ($value ? '<img src="' . esc_url($value) . '" style="max-width:120px;height:auto;">' : '') . '</div>';
    echo '</div>';
    ct_mb_close($desc);
}

/* -------------------------------------------------------------- CATEGORY ui */

/** Render the category fields (context: 'add' | 'edit'). */
function ct_category_fields($term = null)
{
    $is_edit = ($term && isset($term->term_id));
    $meta = $is_edit ? get_term_meta($term->term_id, CT_META_OPTIONS, true) : array();
    $meta = is_array($meta) ? $meta : array();
    $cc   = isset($meta['cat_custom']) && is_array($meta['cat_custom']) ? $meta['cat_custom'] : array();
    $y    = isset($cc['y']) && is_array($cc['y']) ? $cc['y'] : array();
    $sb   = isset($y['sidebar']) && is_array($y['sidebar']) ? $y['sidebar'] : array();
    $icon = isset($meta['icon']) && is_array($meta['icon']) ? $meta['icon'] : array();

    $imgs        = defined('CT_THEME_IMGS_URI') ? CT_THEME_IMGS_URI : '';
    $mName       = 'ct_term[cat_custom][action_show]';
    $sbName      = 'ct_term[cat_custom][y][sidebar][action_show]';
    $iName       = 'ct_term[icon][type]';
    $showY       = array($mName, 'y');

    $master      = ct_mb_get($cc, 'action_show', 'n');
    $sidebar_on  = ct_mb_get($sb, 'action_show', 'n');
    $sidebar_pos = ct_mb_get(isset($sb['y']) && is_array($sb['y']) ? $sb['y'] : array(), 'sidebar_pos', 'right');
    $icon_type   = ct_mb_get($icon, 'type', 'none');

    wp_nonce_field('ct_term_meta', 'ct_term_nonce');

    // Render as one grouped cell so the box looks identical on the Add and Edit
    // term screens and so the conditional-reveal engine (.ct-meta-box) is active.
    if ($is_edit) {
        echo '<tr class="form-field"><th scope="row">' . esc_html__('Tuỳ chỉnh giao diện chuyên mục', 'chinhtoa') . '</th><td>';
    } else {
        echo '<div class="form-field"><label>' . esc_html__('Tuỳ chỉnh giao diện chuyên mục', 'chinhtoa') . '</label>';
    }

    echo '<div class="ct-meta-box ct-meta-box--term">';

    ct_mb_switch(
        $mName,
        $master,
        __('Tuỳ chỉnh riêng chuyên mục này', 'chinhtoa'),
        __('Tắt: dùng bố cục chung trong Tuỳ Chọn Giao Diện. Bật để đặt riêng cho chuyên mục này.', 'chinhtoa')
    );

    ct_mb_image_radio('ct_term[cat_custom][y][columns]', ct_mb_get($y, 'columns', 'c2'), __('Số cột danh sách', 'chinhtoa'), array(
        'c1' => $imgs . '/layouts/one-column.png',
        'c2' => $imgs . '/layouts/two-column.png',
        'c3' => $imgs . '/layouts/three-column.png',
        'c4' => $imgs . '/layouts/four-column.png',
    ), '', $showY);

    ct_mb_image_radio('ct_term[cat_custom][y][display_style]', ct_mb_get($y, 'display_style', 'c1'), __('Kiểu trình bày', 'chinhtoa'), array(
        'c1' => $imgs . '/options/category-style-1.jpg',
        'c2' => $imgs . '/options/category-style-2.jpg',
    ), '', $showY);

    ct_mb_switch($sbName, $sidebar_on, __('Hiển thị thanh bên (sidebar)', 'chinhtoa'), '', $showY);
    ct_mb_image_radio('ct_term[cat_custom][y][sidebar][y][sidebar_pos]', $sidebar_pos, __('Vị trí thanh bên', 'chinhtoa'), array(
        'left'  => $imgs . '/layouts/left-sidebar.jpg',
        'right' => $imgs . '/layouts/right-sidebar.jpg',
    ), '', array($sbName, 'y'));

    ct_mb_switch('ct_term[cat_custom][y][post_thumb]', ct_mb_get($y, 'post_thumb', 'y'), __('Hình đại diện', 'chinhtoa'), '', $showY);
    ct_mb_switch('ct_term[cat_custom][y][post_exper]', ct_mb_get($y, 'post_exper', 'n'), __('Mô tả ngắn', 'chinhtoa'), '', $showY);
    ct_mb_switch('ct_term[cat_custom][y][post_meta]', ct_mb_get($y, 'post_meta', 'n'), __('Ngày & lượt xem', 'chinhtoa'), '', $showY);

    // Icon & colours — category branding, independent of the custom-layout toggle.
    ct_mb_section(__('Biểu tượng & màu sắc', 'chinhtoa'));
    ct_mb_select($iName, $icon_type, __('Loại biểu tượng', 'chinhtoa'), array(
        'none'          => __('Không', 'chinhtoa'),
        'icon-font'     => 'Font Awesome',
        'custom-upload' => __('Ảnh tải lên', 'chinhtoa'),
    ));
    ct_mb_text('ct_term[icon][icon-class]', ct_mb_get($icon, 'icon-class', ''), __('Mã icon (Font Awesome)', 'chinhtoa'), '', array($iName, 'icon-font'), 'fa fa-newspaper-o');
    ct_mb_media('ct_term[icon][url]', ct_mb_get($icon, 'url', ''), __('Ảnh biểu tượng', 'chinhtoa'), '', array($iName, 'custom-upload'));
    ct_mb_color('ct_term[iconcolor]', ct_mb_get($meta, 'iconcolor', ''), __('Màu biểu tượng', 'chinhtoa'));
    ct_mb_color('ct_term[backgroundcolor]', ct_mb_get($meta, 'backgroundcolor', ''), __('Màu nền', 'chinhtoa'));
    ct_mb_color('ct_term[textcolor]', ct_mb_get($meta, 'textcolor', ''), __('Màu chữ', 'chinhtoa'));

    echo '</div>'; // .ct-meta-box

    if ($is_edit) {
        echo '</td></tr>';
    } else {
        echo '</div>';
    }
}

function ct_category_add_fields()
{
    ct_category_fields(null);
}
function ct_category_edit_fields($term)
{
    ct_category_fields($term);
}
add_action('category_add_form_fields', 'ct_category_add_fields');
add_action('category_edit_form_fields', 'ct_category_edit_fields');

/** Save category meta. */
function ct_save_category_meta($term_id)
{
    if (!isset($_POST['ct_term_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ct_term_nonce'])), 'ct_term_meta')) {
        return;
    }
    if (!current_user_can('manage_categories')) {
        return;
    }
    $data = isset($_POST['ct_term']) ? ct_meta_sanitize(wp_unslash($_POST['ct_term'])) : array();
    update_term_meta($term_id, CT_META_OPTIONS, $data);
}
add_action('created_category', 'ct_save_category_meta');
add_action('edited_category', 'ct_save_category_meta');

/* ------------------------------------------------------------------ POST ui */

function ct_post_meta_box()
{
    add_meta_box('ct_post_options', __('Tuỳ chỉnh giao diện bài viết', 'chinhtoa'), 'ct_render_post_meta_box', 'post', 'side', 'default');
}
add_action('add_meta_boxes', 'ct_post_meta_box');

function ct_render_post_meta_box($post)
{
    $meta = get_post_meta($post->ID, CT_META_OPTIONS, true);
    $meta = is_array($meta) ? $meta : array();
    $pc   = isset($meta['post_custom']) && is_array($meta['post_custom']) ? $meta['post_custom'] : array();
    $y    = isset($pc['y']) && is_array($pc['y']) ? $pc['y'] : array();
    $sb   = isset($y['sidebar']) && is_array($y['sidebar']) ? $y['sidebar'] : array();

    $master      = ct_mb_get($pc, 'action_show', 'n');
    $sidebar_on  = ct_mb_get($sb, 'action_show', 'n');
    $sidebar_pos = ct_mb_get(isset($sb['y']) && is_array($sb['y']) ? $sb['y'] : array(), 'sidebar_pos', 'right');

    $imgs   = defined('CT_THEME_IMGS_URI') ? CT_THEME_IMGS_URI : '';
    $mName  = 'ct_post[post_custom][action_show]';
    $sbName = 'ct_post[post_custom][y][sidebar][action_show]';
    $showY  = array($mName, 'y'); // revealed when the master switch is on

    wp_nonce_field('ct_post_meta', 'ct_post_nonce');
    echo '<div class="ct-meta-box">';

    ct_mb_switch(
        $mName,
        $master,
        __('Tuỳ chỉnh riêng bài này', 'chinhtoa'),
        __('Tắt: dùng thiết lập chung. Bật để đặt riêng cho bài này.', 'chinhtoa')
    );

    ct_mb_switch($sbName, $sidebar_on, __('Thanh bên (sidebar)', 'chinhtoa'), '', $showY);
    ct_mb_image_radio('ct_post[post_custom][y][sidebar][y][sidebar_pos]', $sidebar_pos, __('Vị trí thanh bên', 'chinhtoa'), array(
        'left'  => $imgs . '/layouts/left-sidebar.jpg',
        'right' => $imgs . '/layouts/right-sidebar.jpg',
    ), '', array($sbName, 'y'));

    ct_mb_switch('ct_post[post_custom][y][post_thumb]', ct_mb_get($y, 'post_thumb', 'y'), __('Hình đại diện', 'chinhtoa'), '', $showY);
    ct_mb_switch('ct_post[post_custom][y][post_breadcrumb]', ct_mb_get($y, 'post_breadcrumb', 'n'), __('Menu điều hướng', 'chinhtoa'), '', $showY);
    ct_mb_switch('ct_post[post_custom][y][post_title]', ct_mb_get($y, 'post_title', 'y'), __('Tiêu đề bài viết', 'chinhtoa'), '', $showY);
    ct_mb_switch('ct_post[post_custom][y][post_author]', ct_mb_get($y, 'post_author', 'y'), __('Tác giả', 'chinhtoa'), '', $showY);
    ct_mb_switch('ct_post[post_custom][y][post_info]', ct_mb_get($y, 'post_info', 'y'), __('Ngày & lượt xem', 'chinhtoa'), '', $showY);

    echo '</div>';
}

function ct_save_post_meta($post_id)
{
    if (!isset($_POST['ct_post_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ct_post_nonce'])), 'ct_post_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id)) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    $data = isset($_POST['ct_post']) ? ct_meta_sanitize(wp_unslash($_POST['ct_post'])) : array();
    update_post_meta($post_id, CT_META_OPTIONS, $data);
}
add_action('save_post', 'ct_save_post_meta');
