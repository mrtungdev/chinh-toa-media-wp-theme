<?php

/**
 * One-time migration of saved options from Unyson into the native store.
 *
 * Unyson kept theme settings in the wp_option `fw_theme_settings_options:{id}` as a
 * raw nested array, and term/post overrides in meta key `fw_options`. Because the
 * native store keeps the IDENTICAL nested shape, migration is a verbatim copy — no
 * transformation, so the flatteners produce identical output (golden master).
 *
 * The routine reads Unyson's data directly from the database, so it works even
 * after the framework is no longer loaded. It is idempotent (guarded by the
 * `ct_options_migrated` flag) and never overwrites data already in the native store.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('CT_MIGRATED_FLAG')) {
    define('CT_MIGRATED_FLAG', 'ct_options_migrated');
}

/**
 * Copy Unyson theme settings into `ct_settings` (verbatim).
 *
 * @return bool True if settings were imported.
 */
function ct_migrate_settings_from_unyson()
{
    global $wpdb;

    // Don't clobber values already saved through the native editor.
    $existing = get_option(CT_SETTINGS_OPTION, null);
    if (is_array($existing) && !empty($existing)) {
        return false;
    }

    // Unyson stores under `fw_theme_settings_options:{manifest-id}` (usually :default).
    $option_name = $wpdb->get_var(
        "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'fw_theme_settings_options:%' LIMIT 1"
    );
    if (!$option_name) {
        return false;
    }

    $value = get_option($option_name, null);
    if (!is_array($value) || empty($value)) {
        return false;
    }

    update_option(CT_SETTINGS_OPTION, $value);
    return true;
}

/**
 * Copy Unyson term/post `fw_options` meta into the native `ct_options` meta.
 *
 * @return int Number of meta rows copied.
 */
function ct_migrate_meta_from_unyson()
{
    global $wpdb;
    $copied = 0;

    // Term meta.
    $terms = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT term_id, meta_value FROM {$wpdb->termmeta} WHERE meta_key = %s",
            'fw_options'
        )
    );
    if ($terms) {
        foreach ($terms as $row) {
            if ('' === get_term_meta($row->term_id, CT_META_OPTIONS, true)) {
                update_term_meta($row->term_id, CT_META_OPTIONS, maybe_unserialize($row->meta_value));
                $copied++;
            }
        }
    }

    // Post meta.
    $posts = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s",
            'fw_options'
        )
    );
    if ($posts) {
        foreach ($posts as $row) {
            if ('' === get_post_meta($row->post_id, CT_META_OPTIONS, true)) {
                update_post_meta($row->post_id, CT_META_OPTIONS, maybe_unserialize($row->meta_value));
                $copied++;
            }
        }
    }

    return $copied;
}

/**
 * Run the full migration once. Safe to call repeatedly.
 *
 * @param bool $force Re-run even if the migrated flag is set.
 * @return array{settings:bool,meta:int}
 */
function ct_run_options_migration($force = false)
{
    if (!$force && get_option(CT_MIGRATED_FLAG)) {
        return array('settings' => false, 'meta' => 0);
    }
    $result = array(
        'settings' => ct_migrate_settings_from_unyson(),
        'meta'     => ct_migrate_meta_from_unyson(),
    );
    update_option(CT_MIGRATED_FLAG, 1);
    return $result;
}

/**
 * Auto-run the migration once, in admin, for logged-in admins. Cheap no-op after
 * the flag is set.
 */
function ct_maybe_auto_migrate_options()
{
    if (get_option(CT_MIGRATED_FLAG)) {
        return;
    }
    if (!is_admin() || !current_user_can('manage_options')) {
        return;
    }
    ct_run_options_migration();
}
add_action('admin_init', 'ct_maybe_auto_migrate_options');
