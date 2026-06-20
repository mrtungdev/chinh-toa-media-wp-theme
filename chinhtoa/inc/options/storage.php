<?php

/**
 * Native theme-options storage seam (replaces Unyson's fw_get_db_* readers).
 *
 * Front-end code never reads options directly — it goes through the flatteners
 * (gen_Get..., home_Get..., etc.), which call ct_get_option_setting(),
 * ct_get_term_option() and ct_get_post_option(). Those are the whole coupling surface,
 * so swapping the backend here leaves every flattener and template unchanged.
 *
 * Storage:
 *   - Settings : single wp_option `ct_settings` (nested array, same shape Unyson used).
 *   - Term meta: term meta key `ct_options`.
 *   - Post meta: post meta key `ct_options`.
 *
 * CT_OPTIONS_ENGINE ('native' default) lets the reads fall back to Unyson during a
 * transition/rollback window, if the framework still happens to be loaded.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('CT_SETTINGS_OPTION')) {
    define('CT_SETTINGS_OPTION', 'ct_settings');
}
if (!defined('CT_META_OPTIONS')) {
    define('CT_META_OPTIONS', 'ct_options');
}

/**
 * Is $arr a sequential list (0..n-1 keys) rather than an associative map?
 *
 * @param array $arr
 * @return bool
 */
function ct_is_list($arr)
{
    if (!is_array($arr)) {
        return false;
    }
    if (function_exists('array_is_list')) { // PHP 8.1+
        return array_is_list($arr);
    }
    $i = 0;
    foreach ($arr as $k => $_) {
        if ($k !== $i++) {
            return false;
        }
    }
    return true;
}

/**
 * Recursively merge $override over $default. Associative keys merge; sequential
 * lists are replaced wholesale (so repeaters like home_sec aren't index-merged).
 *
 * @param mixed $default
 * @param mixed $override
 * @return mixed
 */
function ct_array_merge_deep($default, $override)
{
    if (!is_array($default) || !is_array($override)) {
        return $override;
    }
    if (ct_is_list($default) || ct_is_list($override)) {
        return $override;
    }
    $out = $default;
    foreach ($override as $k => $v) {
        if (array_key_exists($k, $out) && is_array($out[$k]) && is_array($v)) {
            $out[$k] = ct_array_merge_deep($out[$k], $v);
        } else {
            $out[$k] = $v;
        }
    }
    return $out;
}

/**
 * The full settings array (defaults deep-merged under stored values).
 *
 * @return array
 */
function ct_get_settings()
{
    $stored = get_option(CT_SETTINGS_OPTION, array());
    if (!is_array($stored)) {
        $stored = array();
    }
    return ct_array_merge_deep(ct_settings_defaults(), $stored);
}

/**
 * Read a theme-settings option by top-level id (Unyson fw_get_db_settings_option
 * replacement). Returns the nested sub-array/scalar with defaults merged in.
 *
 * @param string $k Option id.
 * @param mixed  $v Fallback if the key has neither stored value nor default.
 * @param string $m Kept for signature compatibility ('theme-settings').
 * @return mixed
 */
function ct_get_option_setting($k, $v = '', $m = 'theme-settings')
{
    // Optional rollback path: read from Unyson if explicitly selected and present.
    if (defined('CT_OPTIONS_ENGINE') && 'unyson' === CT_OPTIONS_ENGINE && function_exists('fw_get_db_settings_option')) {
        return fw_get_db_settings_option($k);
    }

    if ('theme-settings' !== $m) {
        return $v;
    }

    $stored   = get_option(CT_SETTINGS_OPTION, array());
    $stored   = is_array($stored) ? $stored : array();
    $defaults = ct_settings_defaults();

    $hasStored  = array_key_exists($k, $stored);
    $hasDefault = array_key_exists($k, $defaults);

    if (!$hasStored) {
        return $hasDefault ? $defaults[$k] : $v;
    }

    $value   = $stored[$k];
    $default = $hasDefault ? $defaults[$k] : null;

    if (is_array($default) && is_array($value)) {
        return ct_array_merge_deep($default, $value);
    }
    return $value;
}

/**
 * Read a term-level option (Unyson fw_get_db_term_option replacement).
 *
 * @param int         $term_id
 * @param string      $taxonomy Unused (kept for signature compatibility).
 * @param string|null $key      Sub-key, or null for the whole bag.
 * @param mixed       $default
 * @return mixed
 */
function ct_get_term_option($term_id, $taxonomy = '', $key = null, $default = null)
{
    if (defined('CT_OPTIONS_ENGINE') && 'unyson' === CT_OPTIONS_ENGINE && function_exists('fw_get_db_term_option')) {
        return fw_get_db_term_option($term_id, $taxonomy, $key);
    }

    $all = get_term_meta($term_id, CT_META_OPTIONS, true);
    $all = is_array($all) ? $all : array();

    if (null === $key) {
        return empty($all) ? $default : $all;
    }
    return array_key_exists($key, $all) ? $all[$key] : $default;
}

/**
 * Read a post-level option (Unyson fw_get_db_post_option replacement).
 *
 * @param int         $post_id
 * @param string|null $key
 * @param mixed       $default
 * @return mixed
 */
function ct_get_post_option($post_id, $key = null, $default = null)
{
    if (defined('CT_OPTIONS_ENGINE') && 'unyson' === CT_OPTIONS_ENGINE && function_exists('fw_get_db_post_option')) {
        return fw_get_db_post_option($post_id, $key);
    }

    $all = get_post_meta($post_id, CT_META_OPTIONS, true);
    $all = is_array($all) ? $all : array();

    if (null === $key) {
        return empty($all) ? $default : $all;
    }
    return array_key_exists($key, $all) ? $all[$key] : $default;
}

/**
 * Settings keys whose string value may legitimately contain HTML. On save they
 * are run through wp_kses_post(). Single source of truth for the admin
 * sanitizer (ct_settings_sanitize_walk) — filterable so child themes can extend.
 *
 * @return string[]
 */
function ct_rich_text_keys()
{
    return apply_filters('ct_rich_text_keys', array('header_text', 'gen_footer_text', 'content', 'noidung'));
}

/**
 * Settings keys that hold raw scripts/markup an administrator intentionally
 * injects (head/footer scripts). Stored verbatim — the settings page is
 * manage_options-only. Filterable.
 *
 * @return string[]
 */
function ct_raw_script_keys()
{
    return apply_filters('ct_raw_script_keys', array('headscripts', 'footerscripts'));
}

/**
 * Persist the full settings array.
 *
 * @param array $settings
 * @return bool
 */
function ct_update_settings($settings)
{
    if (!is_array($settings)) {
        return false;
    }
    return update_option(CT_SETTINGS_OPTION, $settings);
}
