<?php

/**
 * Phân loại bài viết (post kind) + hiển thị khối theo-loại ở đầu trang chi tiết.
 *
 * Một metabox "Phân loại bài viết" với DROPDOWN chọn loại. Tuỳ loại mà:
 *  - admin hiện đúng ô nhập (Lời Chúa → câu ghi nhớ; Video → link);
 *  - mặt trước (single.php → template-parts/post/content.php) hiện đúng khối ở ĐẦU bài.
 *
 * Loại độc lập với chuyên mục WordPress (chuyên mục vẫn dùng cho trang chủ/lưu trữ).
 * Loại "Lời Chúa" tái dùng nguyên 2 khoá meta của thẻ câu ghi nhớ
 * (_ct_lc_card_quote / _ct_lc_card_citation) nên block + widget không đổi.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Khoá meta loại bài + link video. */
if (!defined('CT_POST_KIND_META')) {
    define('CT_POST_KIND_META', '_ct_post_kind');
}
if (!defined('CT_POST_VIDEO_META')) {
    define('CT_POST_VIDEO_META', '_ct_post_video_url');
}
// 2 khoá meta thẻ câu ghi nhớ (khai báo gốc ở inc/blocks/loichua-card/render.php).
// Guard để file này không phụ thuộc thứ tự include.
if (!defined('CT_LC_CARD_META_QUOTE')) {
    define('CT_LC_CARD_META_QUOTE', '_ct_lc_card_quote');
}
if (!defined('CT_LC_CARD_META_CITATION')) {
    define('CT_LC_CARD_META_CITATION', '_ct_lc_card_citation');
}

/** Map slug => nhãn cho dropdown loại bài viết. NGUỒN DUY NHẤT. */
function ct_post_kinds()
{
    return array(
        'default' => __('Mặc định (bài viết thường)', 'chinhtoa'),
        'loichua' => __('Lời Chúa (câu ghi nhớ)', 'chinhtoa'),
        'media'   => __('Video (audio) Lời Chúa', 'chinhtoa'),
    );
}

/** Loại nội dung gắn metabox (mặc định: bài viết). Lọc được để mở rộng. */
function ct_post_kind_post_types()
{
    return (array) apply_filters('ct_post_kind_post_types', array('post'));
}

/** Chuẩn hoá loại theo whitelist; rỗng/không hợp lệ => 'default'. */
function ct_post_kind_sanitize($val)
{
    $val = sanitize_key((string) $val);
    return array_key_exists($val, ct_post_kinds()) ? $val : 'default';
}

/** Đăng ký meta (single, string). */
function ct_post_kind_register_meta()
{
    $auth = function () {
        return current_user_can('edit_posts');
    };
    foreach (ct_post_kind_post_types() as $pt) {
        register_post_meta($pt, CT_POST_KIND_META, array(
            'single'            => true,
            'type'              => 'string',
            'show_in_rest'      => false,
            'sanitize_callback' => 'ct_post_kind_sanitize',
            'auth_callback'     => $auth,
        ));
        register_post_meta($pt, CT_POST_VIDEO_META, array(
            'single'            => true,
            'type'              => 'string',
            'show_in_rest'      => false,
            'sanitize_callback' => 'esc_url_raw',
            'auth_callback'     => $auth,
        ));
    }
}
add_action('init', 'ct_post_kind_register_meta');

/**
 * Loại của bài. Fallback: chưa set mà bài đã có câu ghi nhớ (dữ liệu cũ trước khi có
 * dropdown) => 'loichua' để thẻ tự hiện, khỏi cần migrate.
 */
function ct_post_kind($post_id)
{
    $post_id = (int) $post_id;
    $kind    = (string) get_post_meta($post_id, CT_POST_KIND_META, true);
    if ($kind !== '' && array_key_exists($kind, ct_post_kinds())) {
        return $kind;
    }
    $quote    = (string) get_post_meta($post_id, CT_LC_CARD_META_QUOTE, true);
    $citation = (string) get_post_meta($post_id, CT_LC_CARD_META_CITATION, true);
    if ($quote !== '' || $citation !== '') {
        return 'loichua';
    }
    return 'default';
}

/** Gắn metabox "Phân loại bài viết" (cột phải, ưu tiên cao để nằm trên). */
function ct_post_kind_add_meta_box()
{
    foreach (ct_post_kind_post_types() as $pt) {
        add_meta_box(
            'ct_post_kind_box',
            __('Phân loại bài viết', 'chinhtoa'),
            'ct_post_kind_render_meta_box',
            $pt,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'ct_post_kind_add_meta_box');

/** Nội dung metabox: dropdown loại + các nhóm ô ẩn/hiện theo loại (JS toggle). */
function ct_post_kind_render_meta_box($post)
{
    wp_nonce_field('ct_post_kind_meta', 'ct_post_kind_nonce');
    $kind     = ct_post_kind($post->ID);
    $quote    = get_post_meta($post->ID, CT_LC_CARD_META_QUOTE, true);
    $citation = get_post_meta($post->ID, CT_LC_CARD_META_CITATION, true);
    $video    = get_post_meta($post->ID, CT_POST_VIDEO_META, true);
    ?>
    <p style="margin-top:0">
        <label for="ct_post_kind"><strong><?php esc_html_e('Loại bài viết', 'chinhtoa'); ?></strong></label>
        <select id="ct_post_kind" name="<?php echo esc_attr(CT_POST_KIND_META); ?>" class="widefat">
            <?php foreach (ct_post_kinds() as $slug => $label) : ?>
                <option value="<?php echo esc_attr($slug); ?>" <?php selected($kind, $slug); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <div class="ct-pk-fields" data-kind="loichua">
        <p>
            <label for="ct_lc_card_quote"><strong><?php esc_html_e('Câu Lời Chúa', 'chinhtoa'); ?></strong></label>
            <textarea id="ct_lc_card_quote" name="<?php echo esc_attr(CT_LC_CARD_META_QUOTE); ?>" rows="4" class="widefat"
                placeholder="<?php esc_attr_e('VD: Anh em đừng lo lắng về ngày mai…', 'chinhtoa'); ?>"><?php echo esc_textarea($quote); ?></textarea>
        </p>
        <p>
            <label for="ct_lc_card_citation"><strong><?php esc_html_e('Trích dẫn', 'chinhtoa'); ?></strong></label>
            <input type="text" id="ct_lc_card_citation" name="<?php echo esc_attr(CT_LC_CARD_META_CITATION); ?>"
                value="<?php echo esc_attr($citation); ?>" class="widefat"
                placeholder="<?php esc_attr_e('VD: Mt 6, 34', 'chinhtoa'); ?>">
        </p>
        <p class="description">
            <?php esc_html_e('Hiện thẻ câu ghi nhớ ở đầu bài; cũng làm nguồn cho thẻ/block "Lời Chúa: Câu ghi nhớ" (Dynamic). Để trống = lấy mô tả + tiêu đề.', 'chinhtoa'); ?>
        </p>
    </div>

    <div class="ct-pk-fields" data-kind="media">
        <p>
            <label for="ct_post_video_url"><strong><?php esc_html_e('Link video/audio', 'chinhtoa'); ?></strong></label>
            <input type="url" id="ct_post_video_url" name="<?php echo esc_attr(CT_POST_VIDEO_META); ?>"
                value="<?php echo esc_attr($video); ?>" class="widefat"
                placeholder="https://www.youtube.com/watch?v=…">
        </p>
        <p class="description">
            <?php esc_html_e('Dán link YouTube/Vimeo… — trình phát sẽ hiện ở đầu bài (thay ảnh đại diện).', 'chinhtoa'); ?>
        </p>
    </div>
    <?php
}

/** Lưu meta (nonce + autosave + revision + quyền + sanitize). */
function ct_post_kind_save_meta($post_id)
{
    if (!isset($_POST['ct_post_kind_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ct_post_kind_nonce'])), 'ct_post_kind_meta')) {
        return; // nonce chỉ có trên màn hình có metabox này.
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

    $kind = isset($_POST[CT_POST_KIND_META]) ? ct_post_kind_sanitize(wp_unslash($_POST[CT_POST_KIND_META])) : 'default';
    ct_set_value_post_meta($kind, $post_id, CT_POST_KIND_META);

    $video = isset($_POST[CT_POST_VIDEO_META]) ? esc_url_raw(wp_unslash($_POST[CT_POST_VIDEO_META])) : '';
    ct_set_value_post_meta($video, $post_id, CT_POST_VIDEO_META);

    // Ô câu ghi nhớ luôn có trong form (ẩn bằng JS với loại khác) → lưu nguyên trạng,
    // không phá dữ liệu; hiển thị mặt trước do loại quyết định.
    $quote    = isset($_POST[CT_LC_CARD_META_QUOTE]) ? wp_kses_post(wp_unslash($_POST[CT_LC_CARD_META_QUOTE])) : '';
    $citation = isset($_POST[CT_LC_CARD_META_CITATION]) ? sanitize_text_field(wp_unslash($_POST[CT_LC_CARD_META_CITATION])) : '';
    ct_set_value_post_meta($quote, $post_id, CT_LC_CARD_META_QUOTE);
    ct_set_value_post_meta($citation, $post_id, CT_LC_CARD_META_CITATION);
}
add_action('save_post', 'ct_post_kind_save_meta');

/**
 * HTML trình phát cho bài loại 'media' (chỉ link oEmbed: YouTube/Vimeo…).
 * '' nếu trống hoặc không nhúng được.
 */
function ct_post_kind_video_html($post_id)
{
    $url = trim((string) get_post_meta((int) $post_id, CT_POST_VIDEO_META, true));
    if ($url === '') {
        return '';
    }
    $embed = wp_oembed_get($url);
    if (!$embed) {
        return '';
    }
    return '<div class="ct-single-video">' . $embed . '</div>';
}

/** HTML thẻ câu ghi nhớ cho bài loại 'loichua' — tái dùng render dùng chung của thẻ. */
function ct_post_kind_loichua_html($post_id)
{
    if (!function_exists('ct_loichua_card_render')) {
        return '';
    }
    return ct_loichua_card_render(array(
        'mode'         => 'dynamic',
        'source'       => 'post',
        'sourcePostId' => (int) $post_id,
    ));
}

/** Nạp CSS mặt trước theo loại bài — chỉ trang chi tiết. */
function ct_post_kind_enqueue()
{
    if (!is_singular(ct_post_kind_post_types())) {
        return;
    }
    $kind = ct_post_kind(get_queried_object_id());
    if ($kind !== 'media' && $kind !== 'loichua') {
        return; // chỉ loại có khối top riêng mới cần CSS.
    }
    wp_enqueue_style('ct-post-kind', CT_THEME_CSS_URI . '/post-kind.css', array(), THEME_VERSION);
    if ($kind === 'loichua' && wp_style_is('ct-loichua-card', 'registered')) {
        // Handle do inc/blocks/loader.php register ở init (kèm font Lora).
        wp_enqueue_style('ct-loichua-card');
    }
}
add_action('wp_enqueue_scripts', 'ct_post_kind_enqueue');

/** Nạp JS ẩn/hiện ô theo loại — chỉ màn hình soạn/sửa bài. */
function ct_post_kind_admin_enqueue($hook)
{
    if ($hook !== 'post.php' && $hook !== 'post-new.php') {
        return;
    }
    $screen = get_current_screen();
    if ($screen && !in_array($screen->post_type, ct_post_kind_post_types(), true)) {
        return;
    }
    wp_enqueue_script('ct-post-kind-metabox', CT_THEME_JS_URI . '/post-kind-metabox.js', array(), THEME_VERSION, true);
}
add_action('admin_enqueue_scripts', 'ct_post_kind_admin_enqueue');
