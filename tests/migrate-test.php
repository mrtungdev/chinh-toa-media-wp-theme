<?php

/**
 * Headless test for the Unyson -> native data migration.
 * Mocks $wpdb + the option/meta APIs, seeds Unyson-shaped data, runs the
 * migrator, and asserts a verbatim copy into the native store.
 *
 * Run: php tests/migrate-test.php
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
define('ABSPATH', __DIR__ . '/');

$GLOBALS['__opts'] = array();
$GLOBALS['__term_meta'] = array();
$GLOBALS['__post_meta'] = array();

function get_option($n, $d = false) { return array_key_exists($n, $GLOBALS['__opts']) ? $GLOBALS['__opts'][$n] : $d; }
function update_option($n, $v, $a = null) { $GLOBALS['__opts'][$n] = $v; return true; }
function get_term_meta($id, $k, $s = false) { $v = isset($GLOBALS['__term_meta'][$id][$k]) ? $GLOBALS['__term_meta'][$id][$k] : ''; return $s ? $v : array($v); }
function get_post_meta($id, $k, $s = false) { $v = isset($GLOBALS['__post_meta'][$id][$k]) ? $GLOBALS['__post_meta'][$id][$k] : ''; return $s ? $v : array($v); }
function update_term_meta($id, $k, $v) { $GLOBALS['__term_meta'][$id][$k] = $v; return true; }
function update_post_meta($id, $k, $v) { $GLOBALS['__post_meta'][$id][$k] = $v; return true; }
function add_action() {}
function is_admin() { return true; }
function current_user_can($c) { return true; }
function maybe_unserialize($v) { if (is_string($v)) { $u = @unserialize($v); return ($u !== false || $v === 'b:0;') ? $u : $v; } return $v; }

// $wpdb stub
class WPDB_Mock {
    public $options = 'wp_options';
    public $termmeta = 'wp_termmeta';
    public $postmeta = 'wp_postmeta';
    public function prepare($q, ...$a) { return $q; }
    public function get_var($q) {
        if (strpos($q, 'fw_theme_settings_options') !== false) {
            foreach (array_keys($GLOBALS['__opts']) as $name) {
                if (strpos($name, 'fw_theme_settings_options:') === 0) return $name;
            }
        }
        return null;
    }
    public function get_results($q) {
        if (strpos($q, 'termmeta') !== false) return $GLOBALS['__fw_term_rows'];
        if (strpos($q, 'postmeta') !== false) return $GLOBALS['__fw_post_rows'];
        return array();
    }
}
$GLOBALS['wpdb'] = new WPDB_Mock();

$THEME = dirname(__DIR__) . '/chinhtoa';
require $THEME . '/inc/options/defaults.php';
require $THEME . '/inc/options/storage.php';
require $THEME . '/inc/options/migrate.php';

$PASS = 0; $FAIL = 0;
function check($l, $g, $w) { global $PASS, $FAIL; if ($g === $w) { $PASS++; echo "  PASS  $l\n"; } else { $FAIL++; echo "  FAIL  $l\n    got=" . var_export($g, true) . "\n    want=" . var_export($w, true) . "\n"; } }

// Seed Unyson data
$fwSettings = array(
    'theme_data'  => array('theme' => 'violet'),
    'gen_data'    => array('nav_style' => 'c1'),
    'home_sec'    => array(array('content_type' => array('picker' => 'temp1', 'temp1' => array('title' => 'X', 'cats' => '2', 'is_display' => 'y', 'is_admin_only' => 'n', 'show_readmore' => array('action_show' => 'n'))))),
);
$GLOBALS['__opts']['fw_theme_settings_options:default'] = $fwSettings;

$o1 = (object) array('term_id' => 7, 'meta_value' => serialize(array('icon' => array('type' => 'icon-font', 'icon-class' => 'fa fa-x'))));
$GLOBALS['__fw_term_rows'] = array($o1);
$o2 = (object) array('post_id' => 21, 'meta_value' => serialize(array('post_custom' => array('action_show' => 'y', 'y' => array('sidebar' => array('action_show' => 'n'))))));
$GLOBALS['__fw_post_rows'] = array($o2);

// Run migration
$res = ct_run_options_migration(true);

check('settings imported flag', $res['settings'], true);
check('ct_settings == fw settings (verbatim)', get_option('ct_settings'), $fwSettings);
check('term meta copied', get_term_meta(7, 'ct_options', true), array('icon' => array('type' => 'icon-font', 'icon-class' => 'fa fa-x')));
check('post meta copied', get_post_meta(21, 'ct_options', true), array('post_custom' => array('action_show' => 'y', 'y' => array('sidebar' => array('action_show' => 'n')))));
check('meta copied count', $res['meta'], 2);

// Idempotency: a second (non-forced) run must not re-import / not clobber edits
$GLOBALS['__opts']['ct_settings']['theme_data']['theme'] = 'edited';
$res2 = ct_run_options_migration(false); // flag already set
check('second run no-op (settings)', $res2['settings'], false);
check('native edit preserved', get_option('ct_settings')['theme_data']['theme'], 'edited');

// After migration, the native seam reads the migrated data correctly
require $THEME . '/inc/options/flatteners/helper-homepage.php';
$GLOBALS['__opts']['ct_settings'] = $fwSettings; // reset to migrated
$secs = home_GetSections();
check('flattener reads migrated section', $secs[0]['type'], 'temp1');
check('flattener reads migrated cats', $secs[0]['cats'], '2');

echo "\n==== $PASS passed, $FAIL failed ====\n";
exit($FAIL === 0 ? 0 : 1);
