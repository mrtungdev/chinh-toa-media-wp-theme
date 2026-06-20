<?php

function gen_GetTheme()
{
	$res = array();
	$opt = ct_get_option_setting('theme_data');
	$res['theme'] = $opt['theme'];
	$res['custom_color'] = isset($opt['custom_color']) ? $opt['custom_color'] : '';
	// return $opt;
	return $res;
}

function gen_GetGeneral()
{
	$res = array();
	$opt = ct_get_option_setting('gen_data');
	$res['nav_style'] = $opt['nav_style'];
	// $res['main_color'] = $opt['main_color'];

	$gen_bg = (isset($opt['gen_bg']) && is_array($opt['gen_bg'])) ? $opt['gen_bg'] : array();
	$gen_bg_type = isset($gen_bg['action_show']) ? $gen_bg['action_show'] : 'c_color';
	$res['gen_bg_type'] = $gen_bg_type;
	if ($gen_bg_type === 'c_color') {
		$res['gen_bg_color'] = $gen_bg['c_color']['color'];
	} else {
		$gen_bg_image = $gen_bg['c_image']['image'];
		$res['gen_bg_image_url'] = $gen_bg_image['image_upload']['url'];
		$res['gen_bg_image_repeat'] = $gen_bg_image['image_repeat'];
		$res['gen_bg_image_size'] = $gen_bg_image['image_size'];
		$res['gen_bg_image_attachment'] = $gen_bg_image['image_attachment'];
		$res['gen_bg_image_position'] = $gen_bg_image['image_position'];
	}

	// return $opt;
	return $res;
}

function gen_GetHeader()
{
	$res = array();
	$opt = ct_get_option_setting('header_data');
	$type = $opt['action_show'];
	$res['type'] = $type;
	if ($type === 'c_content') {
		$res['text'] = $opt['c_content']['header_text'];
		$res['bgcolor'] = $opt['c_content']['bgcolor'];
	} else {
		$res['desktop'] = '';
		$res['tablet'] = '';
		$res['mobile'] = '';
		if (isset($opt['c_images']['gen_img_desktop']['url'])) {
			$res['desktop'] = $opt['c_images']['gen_img_desktop']['url'];
		}
		if (isset($opt['c_images']['gen_img_tablet']['url'])) {
			$res['tablet'] = $opt['c_images']['gen_img_tablet']['url'];
		}
		if (isset($opt['c_images']['gen_img_mobile']['url'])) {
			$res['mobile'] = $opt['c_images']['gen_img_mobile']['url'];
		}
		$res['link'] = $opt['c_images']['gen_lien_ket'];
		$res['target'] = $opt['c_images']['gen_is_blank'] == 1 ? '_blank' : 'self';
	}

	return $res;
}


function gen_GetFooter()
{
	$res = array();
	$opt = ct_get_option_setting('footer_data');
	$footerWidget = $opt['gen_widget'];
	$res['display_widget'] = $footerWidget['action_show'];
	if ($footerWidget['action_show'] == 'y') {
		$numberOfWidget = $footerWidget['y']['widget_setting']['number'];
		$res['widget_column'] = ltrim($numberOfWidget, 'c');
	}
	$res['text'] = $opt['gen_footer_text'];
	$res['bgcolor'] = $opt['gen_footer_bg_color'];
	$res['color'] = $opt['gen_footer_txt_color'];

	// return $opt;
	return $res;
}

function gen_GetTech()
{
	// $res = array();
	$opt = ct_get_option_setting('tech_data');
	return $opt;
	// return $res;
}

function ct_header_set()
{
	$footer = gen_GetTech();
	if (!empty($footer['headscripts'])) {
		echo $footer['headscripts'];
	}
}
add_action('wp_header', 'ct_header_set');

function ct_footer_set()
{
	$footerScript = '';
	$footer = gen_GetTech();
	if (!empty($footer['analytics'])) {
		$footerScript .= get_analytics_code($footer['analytics']);
	}
	if (!empty($footer['footerscripts'])) {
		$footerScript .= $footer['footerscripts'];
	}
	echo $footerScript;
}
add_action('wp_footer', 'ct_footer_set');

function get_analytics_code($id)
{
	return '<!-- Global site tag (gtag.js) - Google Analytics --><script async src="https://www.googletagmanager.com/gtag/js?id=' . $id . '"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag("js", new Date());gtag("config", "' . $id . '");</script>';
}


// function _action_theme_footer_wp_print_styles()
// {
// 	if (!defined('FW')) return; // prevent fatal error when the framework is not active

// 	$gen_footer_bg_color = fw_get_db_settings_option('gen_footer_bg_color');
// 	$gen_footer_txt_color = fw_get_db_settings_option('gen_footer_txt_color');

// 	echo '<style type="text/css">'
// 		. '.footer { '
// 		. 'background-color: ' . esc_html($gen_footer_bg_color) . '; '
// 		. 'color: ' . esc_html($gen_footer_txt_color) . '; '
// 		. '}'
// 		. '.footer a, .footer p, .footer span, .footer .title{ '
// 		. 'color: ' . esc_html($gen_footer_txt_color) . '; '
// 		. '}'
// 		. '</style>';
// }
// add_action('wp_print_styles', '_action_theme_footer_wp_print_styles');

// function bbland_gen_get_footer_info()
// {
// 	return ct_get_option_setting('gen_footer_text');
// }

add_action('widgets_init', '_action_theme_dynamic_footer_sidebar');
function _action_theme_dynamic_footer_sidebar()
{
	$w = gen_GetFooter();
	if ($w['display_widget'] == 'y') {
		if (function_exists('register_sidebar')) {
			for ($i = 1; $i <= $w['widget_column']; $i++) {
				register_sidebar(array(
					'name' => __('Cuối Trang - Cột ' . $i, 'chinhtoa'),
					'description' => __('Widget Cuối Trang (Footer)', 'chinhtoa'),
					'id' => 'ct-footer-' . $i,
					'before_widget' => '<div id="%1$s" class="%2$s widget-area widget-single">',
					'after_widget' => '</div>',
					'before_title' => '<h3 class="widget-title title"><span>',
					'after_title' => ' </span></h3>'
				));
			}
		}
	}
}

// add_action('fw_init', '_action_theme_dynamic_theme');
function _action_theme_dynamic_theme()
{
	if (!defined('FW')) return;
	$w = gen_GetTheme();
	switch ($w) {
		case 'black':
			
			break;
		case 'green':
			
			break;
		case 'red':
			
			break;
		case 'rose':
			
			break;
		case 'violet':
			
			break;
		case 'yellow':
			
			break;
		
		default:
			// WHHITE
			break;
	}
}