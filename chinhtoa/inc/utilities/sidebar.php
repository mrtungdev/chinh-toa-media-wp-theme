<?php
if (!defined('ABSPATH')) {
    exit;
}

function ct_register_sidebars()
{
    $bbSidebars = array(
        array(
            'name' => __('Trang Chủ', 'chinhtoa'),
            'description' => __('Các widget hiển thị cho trang chủ', 'chinhtoa'),
            'id' => 'ct-widget-homepage',
            'before_widget' => '<div id="%1$s" class="%2$s widget-item bg-white ct-shadow ct-bounding">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title"><span>',
            'after_title' => ' </span></h3>',
        ), array(
            'name' => __('Bài Viết Chi Tiết', 'chinhtoa'),
            'description' => __('Widget chi tiết bài viết', 'chinhtoa'),
            'id' => 'ct-widget-single',
            'before_widget' => '<div id="%1$s" class="%2$s widget-item bg-white ct-shadow ct-bounding">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title"><span>',
            'after_title' => ' </span></h3>',
        ), array(
            'name' => __('Chuyên Mục', 'chinhtoa'),
            'description' => __('Widget dành cho các chuyên mục, từ khoá, ...', 'chinhtoa'),
            'id' => 'ct-widget-archive',
            'before_widget' => '<div id="%1$s" class="%2$s widget-item bg-white ct-shadow ct-bounding">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title title"><span>',
            'after_title' => ' </span></h3>',
        ),
    );
    foreach ($bbSidebars as $sidebar) {
        $args = wp_parse_args($sidebar);
        register_sidebar($args);
    }
}
add_action('widgets_init', 'ct_register_sidebars');