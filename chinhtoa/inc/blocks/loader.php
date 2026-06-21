<?php

/**
 * Loader cho Block "Lời Chúa: Câu ghi nhớ" (chinhtoa/loichua-card).
 *
 * Block dynamic, render bằng PHP (ct_loichua_card_block_render) — dùng chung hàm
 * render với widget CT_LoiChua_Card_Widget. Hướng "no-build": editor script viết tay
 * bằng wp globals, đăng ký handle thủ công rồi block.json trỏ tới handle (không cần
 * @wordpress/scripts / webpack).
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once CT_THEME_DIR . '/inc/blocks/loichua-card/render.php';
require_once CT_THEME_DIR . '/inc/blocks/loichua-card/metabox.php';

add_action('init', function () {
    // Font Lora (serif nghiêng như ảnh mẫu, hỗ trợ tiếng Việt). Đặt làm dependency của
    // style nên chỉ tải khi thẻ thực sự xuất hiện (front + editor), không nạp toàn site.
    wp_register_style(
        'ct-loichua-card-fonts',
        'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&display=swap',
        array(),
        null
    );

    // CSS thẻ dùng chung (front + editor preview).
    wp_register_style(
        'ct-loichua-card',
        CT_THEME_CSS_URI . '/loichua-card.css',
        array('ct-loichua-card-fonts'),
        THEME_VERSION
    );

    // Editor script no-build (dùng wp globals + ServerSideRender).
    wp_register_script(
        'ct-loichua-card-editor',
        CT_THEME_JS_URI . '/loichua-card-block.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
        THEME_VERSION,
        true
    );
    if (function_exists('wp_set_script_translations')) {
        wp_set_script_translations('ct-loichua-card-editor', 'chinhtoa', CT_THEME_DIR . '/languages');
    }

    // Đăng ký từ metadata; bind renderer PHP (override mọi "render" trong block.json).
    register_block_type(CT_THEME_DIR . '/blocks/loichua-card', array(
        'render_callback' => 'ct_loichua_card_block_render',
    ));
});
