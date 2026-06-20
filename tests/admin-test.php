<?php

/**
 * Headless test for the native admin editor:
 *  (1) RENDER — section renderers emit inputs whose names encode the exact nested
 *      ct_settings path (so the browser form posts the right shape).
 *  (2) ROUND-TRIP — a posted nested payload, run through the real sanitizer, is
 *      read back by the flatteners with correct results (admin save -> front-end).
 *
 * Run: php tests/admin-test.php
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
define('ABSPATH', __DIR__ . '/');
define('CT_THEME_IMGS_URI', 'http://x/imgs');
define('CT_THEME_JS_URI', 'http://x/js');
define('CT_THEME_CSS_URI', 'http://x/css');
define('THEME_VERSION', '1.5.4');

$GLOBALS['__opts'] = array();
$GLOBALS['__term_meta'] = array();
$GLOBALS['__post_meta'] = array();
$GLOBALS['__can'] = true;

// --- WP mocks ---------------------------------------------------------------
function get_option($n, $d = false) { return array_key_exists($n, $GLOBALS['__opts']) ? $GLOBALS['__opts'][$n] : $d; }
function update_option($n, $v, $a = null) { $GLOBALS['__opts'][$n] = $v; return true; }
function get_term_meta($id, $k, $s = false) { $v = isset($GLOBALS['__term_meta'][$id][$k]) ? $GLOBALS['__term_meta'][$id][$k] : ''; return $s ? $v : array($v); }
function get_post_meta($id, $k, $s = false) { $v = isset($GLOBALS['__post_meta'][$id][$k]) ? $GLOBALS['__post_meta'][$id][$k] : ''; return $s ? $v : array($v); }
function update_term_meta($id, $k, $v) { $GLOBALS['__term_meta'][$id][$k] = $v; return true; }
function update_post_meta($id, $k, $v) { $GLOBALS['__post_meta'][$id][$k] = $v; return true; }
function add_action() {}
function add_filter() {}
function apply_filters($tag, $value = null) { return $value; }
function add_submenu_page() { return 'hook'; }
function add_meta_box() {}
function register_setting() {}
function settings_fields() {}
function submit_button() {}
function wp_nonce_field() {}
function wp_enqueue_media() {}
function wp_enqueue_style() {}
function wp_enqueue_script() {}
function register_sidebar() {}
function current_user_can($c) { return $GLOBALS['__can']; }
function current_time($f) { return date($f); }
function __($t, $d = 'default') { return $t; }
function _e($t, $d = 'default') { echo $t; }
function esc_html__($t, $d = 'default') { return $t; }
function esc_attr__($t, $d = 'default') { return $t; }
function esc_html($t) { return htmlspecialchars((string) $t, ENT_QUOTES); }
function esc_attr($t) { return htmlspecialchars((string) $t, ENT_QUOTES); }
function esc_url($t) { return $t; }
function esc_url_raw($t) { return $t; }
function esc_textarea($t) { return htmlspecialchars((string) $t, ENT_QUOTES); }
function sanitize_key($k) { return strtolower(preg_replace('/[^a-z0-9_\-]/i', '', (string) $k)); }
function sanitize_text_field($t) { return trim((string) $t); }
function wp_kses_post($t) { return $t; }
function wp_unslash($v) { return $v; }
function selected($a, $b, $e = true) { $r = ((string) $a === (string) $b) ? ' selected="selected"' : ''; if ($e) echo $r; return $r; }
function checked($a, $b, $e = true) { $r = ((string) $a === (string) $b) ? ' checked="checked"' : ''; if ($e) echo $r; return $r; }
function wp_editor($content, $id, $args = array()) { echo '<textarea name="' . esc_attr($args['textarea_name']) . '">' . esc_textarea($content) . '</textarea>'; }
function get_categories($args = array()) { return array(
    (object) array('term_id' => 1, 'name' => 'Cat 1'),
    (object) array('term_id' => 2, 'name' => 'Cat 2'),
    (object) array('term_id' => 3, 'name' => 'Cat 3'),
); }

$THEME = dirname(__DIR__) . '/chinhtoa';
require $THEME . '/inc/utilities/type.php';
require $THEME . '/inc/options/defaults.php';
require $THEME . '/inc/options/storage.php';
require $THEME . '/inc/options/flatteners/helper-general.php';
require $THEME . '/inc/options/flatteners/helper-hot.php';
require $THEME . '/inc/options/flatteners/helper-homepage.php';
require $THEME . '/inc/options/flatteners/helper-ultilities.php';
require $THEME . '/inc/options/flatteners/helper-default.php';
require $THEME . '/inc/options/flatteners/helper-single-custom.php';
require $THEME . '/inc/options/admin/fields.php';
require $THEME . '/inc/options/admin/sections.php';
require $THEME . '/inc/options/admin/admin-page.php';

$PASS = 0; $FAIL = 0;
function ok($label, $cond) { global $PASS, $FAIL; if ($cond) { $PASS++; echo "  PASS  $label\n"; } else { $FAIL++; echo "  FAIL  $label\n"; } }
function check($label, $got, $want) { global $PASS, $FAIL; $c = ($got === $want); if ($c) { $PASS++; echo "  PASS  $label\n"; } else { $FAIL++; echo "  FAIL  $label  got=" . var_export($got, true) . " want=" . var_export($want, true) . "\n"; } }
function render($fn, $s) { ob_start(); $fn($s); return ob_get_clean(); }
function has($hay, $needle) { return strpos($hay, $needle) !== false; }

// =========================================================== (1) RENDER =====
echo "RENDER: field names encode correct nested paths\n";

// Use a settings array with a couple home_sec rows incl. a temp6 with tabs.
$s = ct_get_settings(); // defaults
$s['home_sec'] = array(
    array('content_type' => array('picker' => 'temp1', 'temp1' => array('title' => 'A', 'cats' => '3', 'num_post' => 5))),
    array('content_type' => array('picker' => 'temp6', 'temp6' => array('title' => 'T', 'tab_list' => array(array('tab_title' => 'X', 'tab_cats' => '2'))))),
);

$g = render('ct_section_general', $s);
ok('general theme', has($g, 'name="ct_settings[theme_data][theme]"'));
ok('general nav_style', has($g, 'name="ct_settings[gen_data][nav_style]"'));
ok('general gen_bg discriminator', has($g, 'name="ct_settings[gen_data][gen_bg][action_show]"'));
ok('general bg color (conditional)', has($g, 'name="ct_settings[gen_data][gen_bg][c_color][color]"'));
ok('general conditional attr', has($g, 'data-ct-show="ct_settings[gen_data][gen_bg][action_show]"'));

$h = render('ct_section_header', $s);
ok('header discriminator', has($h, 'name="ct_settings[header_data][action_show]"'));
ok('header editor (c_content)', has($h, 'name="ct_settings[header_data][c_content][header_text]"'));
ok('header media url (c_images)', has($h, 'name="ct_settings[header_data][c_images][gen_img_desktop][url]"'));

$f = render('ct_section_footer', $s);
ok('footer widget switch', has($f, 'name="ct_settings[footer_data][gen_widget][action_show]"'));
ok('footer widget number', has($f, 'name="ct_settings[footer_data][gen_widget][y][widget_setting][number]"'));
ok('footer bg color', has($f, 'name="ct_settings[footer_data][gen_footer_bg_color]"'));

$hp = render('ct_section_homepage', $s);
ok('home sidebar', has($hp, 'name="ct_settings[home_gen][sidebar][action_show]"'));
ok('home featured nested tinhot cats', has($hp, 'name="ct_settings[home_featured][y][featured_type][c1][c1_tinhot][y][cats]"'));
ok('home featured c2 shortcode', has($hp, 'name="ct_settings[home_featured][y][featured_type][c2][shortcode]"'));
ok('5phut category', has($hp, 'name="ct_settings[show_5phutloichua][y][category]"'));
ok('home_sec row0 picker', has($hp, 'name="ct_settings[home_sec][0][content_type][picker]"'));
ok('home_sec row0 temp1 cats', has($hp, 'name="ct_settings[home_sec][0][content_type][temp1][cats]"'));
ok('home_sec row1 temp6 tab_title', has($hp, 'name="ct_settings[home_sec][1][content_type][temp6][tab_list][0][tab_title]"'));
ok('home_sec JS template placeholder', has($hp, 'name="ct_settings[home_sec][__I__][content_type][picker]"'));
ok('tab item JS template placeholder', has($hp, '__J__'));

$d = render('ct_section_default', $s);
ok('default cat columns', has($d, 'name="ct_settings[cat_data][columns]"'));
ok('default cat sidebar pos', has($d, 'name="ct_settings[cat_data][sidebar][y][sidebar_pos]"'));

$t = render('ct_section_tech', $s);
ok('tech headscripts', has($t, 'name="ct_settings[tech_data][headscripts]"'));

// Term meta render
ob_start(); ct_category_fields(null); $tm = ob_get_clean();
ok('term cat_custom', has($tm, 'name="ct_term[cat_custom][action_show]"'));
ok('term icon type', has($tm, 'name="ct_term[icon][type]"'));
ok('term iconcolor', has($tm, 'name="ct_term[iconcolor]"'));

// ===================================================== (2) ROUND-TRIP =======
echo "\nROUND-TRIP: posted payload -> sanitize -> flatteners\n";

$posted = array(
    'theme_data'  => array('theme' => 'green'),
    'gen_data'    => array('nav_style' => 'c1', 'gen_bg' => array('action_show' => 'c_color', 'c_color' => array('color' => '#abcdef'))),
    'header_data' => array('action_show' => 'c_content', 'c_content' => array('header_text' => '<b>Hi</b>', 'bgcolor' => '#111')),
    'footer_data' => array('gen_widget' => array('action_show' => 'y', 'y' => array('widget_setting' => array('number' => 'c2'))), 'gen_footer_text' => '  hello  ', 'gen_footer_bg_color' => '#222', 'gen_footer_txt_color' => '#ddd'),
    'tech_data'   => array('analytics' => ' G-1 ', 'headscripts' => '<script>1</script>'),
    'hot_picker'  => array('action_show' => 'y', 'y' => array('hot_setting' => array('displaytime' => array('from' => '2000-01-01 00:00', 'to' => '2999-01-01 00:00'), 'title' => 'H', 'noidung' => '<p>n</p>', 'islive' => 'y', 'classes' => 'c'))),
    'home_featured' => array('action_show' => 'y', 'y' => array('featured_type' => array('action_show' => 'c1', 'c1' => array('c1_tinhot' => array('action_show' => 'y', 'y' => array('cats' => '7', 'num_post' => '4', 'date' => '3')), 'c1_tieudem' => array('cats' => '8', 'num_post' => '2'))))),
    'show_5phutloichua' => array('action_show' => 'y', 'y' => array('title' => 'L', 'category' => '9', 'showinhomepage' => 'y', 'showinsingle' => 'n', 'showincat' => 'y')),
    'home_sec'    => array(
        array('content_type' => array('picker' => 'temp1', 'temp1' => array('title' => 'List', 'cats' => '3', 'num_post' => '6', 'is_display' => 'y', 'is_admin_only' => 'n', 'show_readmore' => array('action_show' => 'n')))),
        array('content_type' => array('picker' => 'temp6', 'temp6' => array('title' => 'Tabs', 'is_display' => 'y', 'is_admin_only' => 'n', 'tab_list' => array(array('tab_title' => 'A', 'tab_cats' => '1'))))),
    ),
    'cat_data'    => array('columns' => 'c4', 'display_style' => 'c1', 'sidebar' => array('action_show' => 'n')),
);

$clean = ct_settings_sanitize_callback($posted);
$GLOBALS['__opts']['ct_settings'] = $clean;

check('sanitize trims plain string', $clean['tech_data']['analytics'], 'G-1');
check('sanitize keeps raw script', $clean['tech_data']['headscripts'], '<script>1</script>');
check('rich field preserved (kses)', $clean['footer_data']['gen_footer_text'], '  hello  ');

check('theme', gen_GetTheme()['theme'], 'green');
check('gen bg color', gen_GetGeneral()['gen_bg_color'], '#abcdef');
check('header text', gen_GetHeader()['text'], '<b>Hi</b>');
check('footer cols', gen_GetFooter()['widget_column'], '2');
check('hot title', hot_GetData()['title'], 'H');
$hf = home_GetFeatured();
check('featured tinhot cats', $hf['c1_tinhot_cats'], '7');
check('featured tieudem cats', $hf['c1_tieudem_cats'], '8');
$GLOBALS['__can'] = true;
$secs = home_GetSections();
check('sections count', count($secs), 2);
check('sec0 type', $secs[0]['type'], 'temp1');
check('sec0 cats', $secs[0]['cats'], '3');
check('sec1 type', $secs[1]['type'], 'temp6');
check('sec1 tab title', $secs[1]['tab_list'][0]['tab_title'], 'A');
check('cat columns', default_GetDefaultCategory()['columns'], 'c4');

echo "\n==== $PASS passed, $FAIL failed ====\n";
exit($FAIL === 0 ? 0 : 1);
