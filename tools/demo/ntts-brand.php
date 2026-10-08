<?php
/**
 * Plugin Name: Brand — Hiệp Hội Nữ Tỳ Thừa Sai Thánh Giá
 * Description: Ghi đè thông tin thương hiệu của theme Chính Tòa Media (filter ct_brand) cho riêng site này.
 *
 * Mu-plugin do tools/demo/seed-ntts.php chép vào wp-content/mu-plugins/. Theme dùng chung
 * thư mục với site khác, nên phần riêng của site đặt ở đây thay vì sửa brand-config.php.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_filter('ct_brand', function ($brand) {
    $brand['keywords']         = 'Hiệp Hội Nữ Tỳ Thừa Sai Thánh Giá, Nữ Tỳ Thừa Sai Thánh Giá, Đà Nẵng';
    $brand['fb_profile_id']    = '';
    $brand['theme_color']      = '#7a1f35';
    $brand['tile_color']       = '#7a1f35';
    $brand['admin_menu_title'] = 'HIỆP HỘI NỮ TỲ THỪA SAI THÁNH GIÁ';
    // Tên menu theme ở cột trái trang quản trị (cũng là {{menu}} trong trang Hướng dẫn).
    $brand['admin_menu_label'] = 'Nữ Tỳ Thừa Sai';
    // Bỏ bộ favicon Chính Tòa của theme → WordPress dùng Site Icon (Cài đặt → Tổng quan).
    $brand['favicon_dir']      = '';
    // Box "5 phút Lời Chúa" thuộc site khác — tắt hẳn ở site này.
    $brand['features']['loichua'] = false;
    return $brand;
});
