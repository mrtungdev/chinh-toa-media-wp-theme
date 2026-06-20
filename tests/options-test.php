<?php

/**
 * Headless golden-master test for the native options engine.
 *
 * Mocks the handful of WordPress functions the storage seam + flatteners use,
 * loads a representative `ct_settings` payload (the shape Unyson would have
 * stored / the migrator copies verbatim), then runs every front-end flattener
 * and asserts the returned shapes/values. This proves the flatteners behave
 * correctly against the native store WITHOUT a running WordPress.
 *
 * Run: php tests/options-test.php
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('ABSPATH', __DIR__ . '/');
define('DAY_IN_SECONDS', 86400);

$GLOBALS['__opts'] = array();
$GLOBALS['__term_meta'] = array();
$GLOBALS['__post_meta'] = array();
$GLOBALS['__can'] = true; // current_user_can() return

// --- Minimal WP mocks -------------------------------------------------------
function get_option($name, $default = false) { return array_key_exists($name, $GLOBALS['__opts']) ? $GLOBALS['__opts'][$name] : $default; }
function update_option($name, $value, $autoload = null) { $GLOBALS['__opts'][$name] = $value; return true; }
function get_term_meta($id, $key, $single = false) { $v = isset($GLOBALS['__term_meta'][$id][$key]) ? $GLOBALS['__term_meta'][$id][$key] : ''; return $single ? $v : array($v); }
function get_post_meta($id, $key, $single = false) { $v = isset($GLOBALS['__post_meta'][$id][$key]) ? $GLOBALS['__post_meta'][$id][$key] : ''; return $single ? $v : array($v); }
function update_term_meta($id, $key, $value) { $GLOBALS['__term_meta'][$id][$key] = $value; return true; }
function update_post_meta($id, $key, $value) { $GLOBALS['__post_meta'][$id][$key] = $value; return true; }
function add_action() {}
function add_filter() {}
function apply_filters($tag, $value = null) { return $value; }
function register_sidebar() {}
function __($t, $d = 'default') { return $t; }
function esc_html($t) { return $t; }
function esc_attr($t) { return $t; }
function esc_url($t) { return $t; }
function current_user_can($cap) { return $GLOBALS['__can']; }
function current_time($fmt) { return date($fmt); }
function sanitize_key($k) { return strtolower(preg_replace('/[^a-z0-9_\-]/i', '', (string) $k)); }
function wp_kses_post($s) { return $s; }

// --- Load subsystem under test ---------------------------------------------
$THEME = dirname(__DIR__) . '/chinhtoa';
require $THEME . '/inc/utilities/type.php';            // seekkey, isBetweenDates
require $THEME . '/inc/options/defaults.php';
require $THEME . '/inc/options/storage.php';
require $THEME . '/inc/options/flatteners/helper-general.php';
require $THEME . '/inc/options/flatteners/helper-hot.php';
require $THEME . '/inc/options/flatteners/helper-homepage.php';
require $THEME . '/inc/options/flatteners/helper-ultilities.php';
require $THEME . '/inc/options/flatteners/helper-default.php';
require $THEME . '/inc/options/flatteners/helper-single-custom.php';

// --- Test framework ---------------------------------------------------------
$PASS = 0; $FAIL = 0;
function check($label, $got, $want) {
    global $PASS, $FAIL;
    $ok = ($got === $want);
    if ($ok) { $PASS++; echo "  PASS  $label\n"; }
    else { $FAIL++; echo "  FAIL  $label\n        got : " . var_export($got, true) . "\n        want: " . var_export($want, true) . "\n"; }
}

// --- Representative migrated settings (Unyson-shaped) ------------------------
$GLOBALS['__opts']['ct_settings'] = array(
    'theme_data'  => array('theme' => 'red'),
    'gen_data'    => array(
        'nav_style' => 'c2',
        'gen_bg'    => array('action_show' => 'c_color', 'c_color' => array('color' => '#101010')),
    ),
    'header_data' => array(
        'action_show' => 'c_content',
        'c_content'   => array('header_text' => '<h1>Hi</h1>', 'bgcolor' => '#222'),
    ),
    'footer_data' => array(
        'gen_widget'          => array('action_show' => 'y', 'y' => array('widget_setting' => array('number' => 'c3'))),
        'gen_footer_text'     => 'Footer here',
        'gen_footer_bg_color' => '#333',
        'gen_footer_txt_color' => '#eee',
    ),
    'tech_data'   => array('analytics' => 'G-XYZ', 'headscripts' => '', 'footerscripts' => ''),
    'hot_picker'  => array(
        'action_show' => 'y',
        'y'           => array('hot_setting' => array(
            'displaytime' => array('from' => '2000-01-01 00:00', 'to' => '2999-01-01 00:00'),
            'title' => 'HOT', 'noidung' => 'news', 'islive' => 'y', 'classes' => 'x',
        )),
    ),
    'home_gen'          => array('sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'left'))),
    'home_featured'     => array(
        'action_show' => 'y',
        'y'           => array('featured_type' => array(
            'action_show' => 'c1',
            'c1'          => array(
                'c1_tinhot'  => array('action_show' => 'y', 'y' => array('cats' => '5', 'num_post' => 4, 'date' => 7)),
                'c1_tieudem' => array('cats' => '8', 'num_post' => 3),
            ),
        )),
    ),
    'show_5phutloichua' => array('action_show' => 'y', 'y' => array('title' => '5p', 'category' => '9', 'showinhomepage' => 'y', 'showinsingle' => 'n', 'showincat' => 'y')),
    'home_sec'          => array(
        // temp0 static, visible
        array('content_type' => array('picker' => 'temp0', 'temp0' => array(
            'title' => 'Static', 'content' => 'body', 'is_display' => 'y', 'is_admin_only' => 'n',
            'show_readmore' => array('action_show' => 'n'),
            'default_style' => array('is_default_style' => 'y'),
        ))),
        // temp1 post list, visible, with readmore
        array('content_type' => array('picker' => 'temp1', 'temp1' => array(
            'title' => 'Latest', 'is_display' => 'y', 'is_admin_only' => 'n', 'cats' => '3', 'num_post' => 6,
            'show_readmore' => array('action_show' => 'y', 'y' => array('readmore_text' => 'More', 'readmore_link' => '/more', 'readmore_blank' => 'y')),
        ))),
        // temp6 with tab_list, visible
        array('content_type' => array('picker' => 'temp6', 'temp6' => array(
            'title' => 'Tabs', 'is_display' => 'y', 'is_admin_only' => 'n', 'cats' => '',
            'tab_list' => array(array('tab_title' => 'A', 'tab_cats' => '1'), array('tab_title' => 'B', 'tab_cats' => '2')),
        ))),
        // hidden (is_display n) — must be skipped
        array('content_type' => array('picker' => 'temp1', 'temp1' => array(
            'title' => 'Hidden', 'is_display' => 'n', 'is_admin_only' => 'n', 'cats' => '1', 'num_post' => 2,
            'show_readmore' => array('action_show' => 'n'),
        ))),
        // admin-only — skipped for non-admin
        array('content_type' => array('picker' => 'temp1', 'temp1' => array(
            'title' => 'AdminOnly', 'is_display' => 'y', 'is_admin_only' => 'y', 'cats' => '1', 'num_post' => 2,
            'show_readmore' => array('action_show' => 'n'),
        ))),
    ),
    'cat_data'    => array('columns' => 'c3', 'display_style' => 'c2', 'sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'right'))),
    'post_data'   => array('sidebar' => array('action_show' => 'n')),
);

// Term / post overrides (migrated to ct_options meta).
$GLOBALS['__term_meta'][12]['ct_options'] = array(
    'cat_custom' => array('action_show' => 'y', 'y' => array('columns' => 'c1', 'display_style' => 'c1', 'sidebar' => array('action_show' => 'n'))),
    'icon'       => array('type' => 'icon-font', 'icon-class' => 'fa fa-star'),
    'iconcolor'  => '#f00', 'backgroundcolor' => '#000', 'textcolor' => '#fff',
);
$GLOBALS['__post_meta'][34]['ct_options'] = array(
    'post_custom' => array('action_show' => 'y', 'y' => array('sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'left')))),
);

// --- Assertions -------------------------------------------------------------
echo "gen_GetTheme:\n";
check('theme', gen_GetTheme(), array('theme' => 'red', 'custom_color' => '#1565c0'));

echo "gen_GetGeneral:\n";
$g = gen_GetGeneral();
check('nav_style', $g['nav_style'], 'c2');
check('gen_bg_type', $g['gen_bg_type'], 'c_color');
check('gen_bg_color', $g['gen_bg_color'], '#101010');

echo "gen_GetHeader:\n";
$h = gen_GetHeader();
check('type', $h['type'], 'c_content');
check('text', $h['text'], '<h1>Hi</h1>');
check('bgcolor', $h['bgcolor'], '#222');

echo "gen_GetFooter:\n";
$f = gen_GetFooter();
check('display_widget', $f['display_widget'], 'y');
check('widget_column', $f['widget_column'], '3');
check('text', $f['text'], 'Footer here');
check('bgcolor', $f['bgcolor'], '#333');

echo "gen_GetTech:\n";
check('analytics', gen_GetTech()['analytics'], 'G-XYZ');

echo "hot_GetData (active window):\n";
$hot = hot_GetData();
check('is_show', $hot['is_show'], 'y');
check('title', $hot['title'], 'HOT');
check('islive', $hot['islive'], 'y');

echo "home_GetGeneral:\n";
$hg = home_GetGeneral();
check('showsidebar', $hg['showsidebar'], 'y');
check('sidebar', $hg['sidebar'], 'left');

echo "home_GetFeatured (c1 + tinhot):\n";
$hf = home_GetFeatured();
check('is_show', $hf['is_show'], 'y');
check('featured_type', $hf['featured_type'], 'c1');
check('show_tinhot', $hf['show_tinhot'], 'y');
check('c1_tinhot_cats', $hf['c1_tinhot_cats'], '5');
check('c1_tieudem_cats', $hf['c1_tieudem_cats'], '8');

echo "home_show_5phutloichua:\n";
$lc = home_show_5phutloichua();
check('is_show', $lc['is_show'], 'y');
check('title', $lc['title'], '5p');
check('cats', $lc['cats'], '9');

echo "ulti_show_5phutloichua:\n";
$ul = ulti_show_5phutloichua();
check('showinhomepage', $ul['showinhomepage'], 'y');
check('showincat', $ul['showincat'], 'y');

echo "home_GetSections (non-admin: 3 visible, admin-only skipped):\n";
$GLOBALS['__can'] = false;
$secs = home_GetSections();
check('count', count($secs), 3);
check('s0 type', $secs[0]['type'], 'temp0');
check('s0 title', $secs[0]['title'], 'Static');
check('s0 readmore', $secs[0]['readmore'], 'n');
check('s0 default_style', $secs[0]['default_style'], 'y');
check('s1 type', $secs[1]['type'], 'temp1');
check('s1 readmore', $secs[1]['readmore'], 'y');
check('s1 readmore_text', $secs[1]['readmore_text'], 'More');
check('s1 cats', $secs[1]['cats'], '3');
check('s2 type', $secs[2]['type'], 'temp6');
check('s2 tab_list count', count($secs[2]['tab_list']), 2);
check('s2 tab_list[0] title', $secs[2]['tab_list'][0]['tab_title'], 'A');

echo "home_GetSections (admin: admin-only now visible => 4):\n";
$GLOBALS['__can'] = true;
check('count', count(home_GetSections()), 4);

echo "default_GetDefaultCategory:\n";
$dc = default_GetDefaultCategory();
check('columns', $dc['columns'], 'c3');
check('display_style', $dc['display_style'], 'c2');
check('sidebar action_show', $dc['sidebar']['action_show'], 'y');

echo "getCustomCategoryOption (term 12 override):\n";
$cc = getCustomCategoryOption(12);
check('columns', $cc['columns'], 'c1');
check('sidebar action_show', $cc['sidebar']['action_show'], 'n');

echo "ct_get_term_option (whole bag for posttags icon):\n";
$tm = ct_get_term_option(12, 'category');
check('icon type', $tm['icon']['type'], 'icon-font');
check('iconcolor', $tm['iconcolor'], '#f00');

echo "getCustomPostOption (post 34 override):\n";
$pc = getCustomPostOption(34);
check('sidebar pos', $pc['sidebar']['y']['sidebar_pos'], 'left');

// --- Defaults merge: empty store must not error, returns safe 'n' branches ---
echo "fresh install (empty store) safe defaults:\n";
$GLOBALS['__opts']['ct_settings'] = array();
check('hot off', hot_GetData()['is_show'], 'n');
check('featured off', home_GetFeatured()['is_show'], 'n');
check('home sections empty', home_GetSections(), array());
check('cat default columns', default_GetDefaultCategory()['columns'], 'c2');

// --- Summary ----------------------------------------------------------------
echo "\n==== $PASS passed, $FAIL failed ====\n";
exit($FAIL === 0 ? 0 : 1);
