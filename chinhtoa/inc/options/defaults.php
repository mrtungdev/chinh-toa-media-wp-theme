<?php

/**
 * Default values for the native theme-settings store (`ct_settings`).
 *
 * These mirror the nested shapes the front-end flatteners (gen_Get..., home_Get...,
 * hot_GetData, default_Get...) expect. They are deep-merged UNDER the stored values
 * by the read seam (see storage.php) so that, on a fresh or partially-populated
 * install, the flatteners never dereference a missing key under PHP 8.x.
 *
 * The discriminator keys (action_show / picker) default to the "off"/safe branch
 * so flatteners take their early-return path and never touch deeper optional keys.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return array Default theme settings, keyed by top-level option id.
 */
function ct_settings_defaults()
{
    return array(
        // tab-general --------------------------------------------------------
        'theme_data'  => array(
            'theme'        => 'white',
            'custom_color' => '#1565c0',
        ),
        'gen_data'    => array(
            'nav_style' => 'c1',
            'gen_bg'    => array(
                'action_show' => 'c_color',
                'c_color'     => array('color' => '#ffffff'),
                'c_image'     => array(
                    'image' => array(
                        'image_upload'     => array('url' => ''),
                        'image_repeat'     => 'no-repeat',
                        'image_size'       => 'auto',
                        'image_attachment' => 'scroll',
                        'image_position'   => 'center_center',
                    ),
                ),
            ),
        ),
        'header_data' => array(
            'action_show' => 'c_content',
            'c_content'   => array('header_text' => '', 'bgcolor' => ''),
            'c_images'    => array(
                'gen_img_desktop' => array('url' => ''),
                'gen_img_tablet'  => array('url' => ''),
                'gen_img_mobile'  => array('url' => ''),
                'gen_tieu_de'     => '',
                'gen_lien_ket'    => '',
                'gen_is_blank'    => 0,
            ),
        ),
        'footer_data' => array(
            'gen_widget'          => array(
                'action_show' => 'n',
                'y'           => array('widget_setting' => array('number' => 'c1')),
            ),
            'gen_footer_text'     => '',
            'gen_footer_bg_color' => '#4f4f4f',
            'gen_footer_txt_color' => '#ffffff',
        ),
        'tech_data'   => array(
            'analytics'                => '',
            'headscripts'              => '',
            'footerscripts'            => '',
            'template_admin_shortcode' => '',
            'template_editor_shortcode' => '',
            'template_else_shortcode'  => '',
        ),

        // tab-hot ------------------------------------------------------------
        'hot_picker'  => array(
            'action_show' => 'n',
            'y'           => array(
                'hot_setting' => array(
                    'displaytime' => array('from' => '', 'to' => ''),
                    'title'       => '',
                    'noidung'     => '',
                    'islive'      => 'n',
                    'classes'     => '',
                ),
            ),
        ),

        // tab-homepage -------------------------------------------------------
        'home_gen'          => array(
            'sidebar' => array('action_show' => 'n', 'y' => array('sidebar_pos' => 'right')),
        ),
        'home_featured'     => array(
            'action_show' => 'n',
            'y'           => array(
                'featured_type' => array(
                    'action_show' => 'c1',
                    'c1'          => array(
                        'c1_tinhot'  => array(
                            'action_show' => 'n',
                            'y'           => array('cats' => '', 'num_post' => '', 'date' => ''),
                        ),
                        'c1_tieudem' => array('cats' => '', 'num_post' => ''),
                    ),
                    'c2'          => array('shortcode' => ''),
                ),
            ),
        ),
        'show_5phutloichua' => array(
            'action_show' => 'n',
            'y'           => array(
                'title'         => '',
                'category'      => '',
                'showinhomepage' => 'n',
                'showinsingle'  => 'n',
                'showincat'     => 'n',
            ),
        ),
        // Repeatable homepage sections (list). Stored value replaces this default.
        'home_sec'          => array(),

        // tab-default --------------------------------------------------------
        'cat_data'    => array(
            'columns'       => 'c2',
            'display_style' => 'c1',
            'sidebar'       => array('action_show' => 'n', 'y' => array('sidebar_pos' => 'right')),
        ),
        'post_data'   => array(
            'sidebar' => array('action_show' => 'n', 'y' => array('sidebar_pos' => 'right')),
        ),

        // tab-ultilities -----------------------------------------------------
        'ulti_giothanhle' => array(),
    );
}
