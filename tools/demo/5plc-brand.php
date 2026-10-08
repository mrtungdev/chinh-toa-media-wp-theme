<?php
/**
 * Plugin Name: Brand — 5 phút cho Lời Chúa
 * Description: Ghi đè thông tin thương hiệu của theme Chính Tòa Media (filter ct_brand) cho riêng site này.
 *
 * Mu-plugin do tools/setup-site-vi.php (CT_BRAND=…) chép vào wp-content/mu-plugins/. Theme dùng
 * chung thư mục với site khác, nên phần riêng của site đặt ở đây thay vì sửa brand-config.php.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('ct_brand', function ($brand) {
    $brand['keywords']         = '5 phút cho Lời Chúa, suy niệm Lời Chúa, Tin Mừng hằng ngày, giáo lý Công giáo';
    $brand['fb_profile_id']    = '';
    $brand['theme_color']      = '#2e3192';
    $brand['tile_color']       = '#2e3192';
    $brand['admin_menu_title'] = '5 PHÚT CHO LỜI CHÚA';
    // Tên menu theme ở cột trái trang quản trị (cũng là {{menu}} trong trang Hướng dẫn).
    $brand['admin_menu_label'] = '5 phút Lời Chúa';
    // Giữ features.loichua (box/khối "Lời Chúa hôm nay" là tính năng chính của site).
    return $brand;
});
