<?php

/**
 * Đăng ký meta "Lời Chúa: Câu ghi nhớ" cho bài viết.
 *
 * Khoá meta (_ct_lc_card_quote / _ct_lc_card_citation) khai báo ở render.php; file này
 * đăng ký chúng với WordPress (sanitize + quyền) để get_post_meta dùng nhất quán → nguồn
 * cho thẻ/block/widget "Lời Chúa: Câu ghi nhớ" ở chế độ Dynamic.
 *
 * GIAO DIỆN NHẬP (ô Câu Lời Chúa + Trích dẫn) đã chuyển sang metabox hợp nhất
 * "Phân loại bài viết" — xem inc/post/post-kind.php — nơi 2 ô này CHỈ hiện khi chọn loại
 * "Lời Chúa", và cũng là nơi LƯU 2 khoá meta này. Block/widget không đổi vì vẫn đọc đúng key.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Loại nội dung gắn meta thẻ (mặc định: bài viết). Lọc được để mở rộng (vd thêm 'page'). */
function ct_loichua_card_meta_post_types()
{
    return (array) apply_filters('ct_loichua_card_meta_post_types', array('post'));
}

/** Đăng ký meta (single, string) — chuẩn hoá khi lưu, sẵn sàng cho get_post_meta. */
function ct_loichua_card_register_meta()
{
    $auth = function () {
        return current_user_can('edit_posts');
    };
    foreach (ct_loichua_card_meta_post_types() as $pt) {
        register_post_meta($pt, CT_LC_CARD_META_QUOTE, array(
            'single'            => true,
            'type'              => 'string',
            'show_in_rest'      => false,
            'sanitize_callback' => 'wp_kses_post',
            'auth_callback'     => $auth,
        ));
        register_post_meta($pt, CT_LC_CARD_META_CITATION, array(
            'single'            => true,
            'type'              => 'string',
            'show_in_rest'      => false,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => $auth,
        ));
    }
}
add_action('init', 'ct_loichua_card_register_meta');
