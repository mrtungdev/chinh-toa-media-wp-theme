<?php

/**
 * Native theme-options engine loader (replaces the Unyson framework).
 *
 * Loads the settings store, the read seam, the one-time migrator, the front-end
 * flatteners (relocated out of framework-customizations, shapes unchanged), and
 * — in admin — the native Settings-API editor.
 *
 * CT_OPTIONS_ENGINE:
 *   'native' (default) — read/write the `ct_settings` option + `ct_options` meta.
 *   'unyson'           — read from Unyson instead (rollback; only if framework loaded).
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('CT_OPTIONS_ENGINE')) {
    define('CT_OPTIONS_ENGINE', 'native');
}

require_once __DIR__ . '/defaults.php';
require_once __DIR__ . '/storage.php';
require_once __DIR__ . '/migrate.php';

// Front-end flatteners (read the store via the seam; return shapes are unchanged
// from the Unyson era, so all templates keep working).
require_once __DIR__ . '/flatteners/helper-general.php';
require_once __DIR__ . '/flatteners/helper-hot.php';
require_once __DIR__ . '/flatteners/helper-homepage.php';
require_once __DIR__ . '/flatteners/helper-ultilities.php';
require_once __DIR__ . '/flatteners/helper-default.php';
require_once __DIR__ . '/flatteners/helper-single-custom.php';

// Native admin editor (Settings API page + term/post meta boxes).
if (is_admin() && file_exists(__DIR__ . '/admin/admin-page.php')) {
    require_once __DIR__ . '/admin/admin-page.php';
}
