<?php
if (!defined('ABSPATH')) {
  exit;
}
add_action('admin_menu', 'remove_menus', 999);
add_action('admin_bar_menu', 'remove_wp_logo', 999);
add_action('admin_menu', 'chinhtoa_theme_options');

/**
 * The native WP dashboard (index.php) is intentionally bypassed: its menu item
 * is removed (see remove_menus()) and every visit is redirected to the theme's
 * own intro page, keeping the wp-admin landing page free of other plugins'
 * dashboard widgets to avoid conflicts.
 *
 * Hooked on load-index.php so the redirect runs before any dashboard output,
 * and targets admin.php (the top-level chinhtoa-intro page) so it never loops
 * back onto index.php.
 */
add_action('load-index.php', 'chinhtoa_redirect_dashboard_to_intro');
function chinhtoa_redirect_dashboard_to_intro()
{
  if (wp_doing_ajax() || is_network_admin() || !current_user_can('read')) {
    return;
  }
  wp_safe_redirect(admin_url('admin.php?page=chinhtoa-intro'));
  exit;
}

function remove_wp_logo($wp_admin_bar)
{
  $wp_admin_bar->remove_node('wp-logo');
}

function remove_menus()
{
  remove_menu_page('index.php');
  remove_menu_page('fw-extensions');
  remove_menu_page('tools.php');
}

function _action_theme_custom_fw_settings_menu($data)
{
  add_submenu_page('theme-options', 'Thiết lập giao diện', 'Thiết lập giao diện', $data['capability'], $data['slug'], $data['content_callback']);
}

function chinhtoa_theme_options()
{
  $themeIcon  = ct_brand('admin_menu_icon');
  $menuTitle  = ct_brand('admin_menu_title');
  $menuLabel  = ct_brand('admin_menu_label');
  // The theme menu (intro/guide page) is visible to anyone who can write posts
  // (edit_posts). The "Thiết lập giao diện" submenu added in admin-page.php keeps
  // its own 'manage_options' cap, so only admins see/open the settings page.
  add_menu_page($menuTitle, $menuLabel, 'edit_posts', 'theme-options', 'chinhtoa_theme_intro', $themeIcon, 2);
  add_menu_page('Bảng Điều Khiển', 'Bảng Điều Khiển', 'read', 'chinhtoa-intro', 'chinhtoa_dashboard_page', $themeIcon, 0);
  // add_submenu_page('index.php', 'Bảng Điều Khiển', 'Bảng Điều Khiển', 'read', 'chinhtoa-intro', 'chinhtoa_dashboard_page', '', 0);
  // add_submenu_page('theme-options', 'Thiết lập giao diện', 'Thiết lập giao diện', 'manage_options', 'fw-settings', 'chinhtoa_theme_settings');
  // add_submenu_page('theme-options', 'FAQ page title', 'FAQ menu label', 'manage_options', 'theme-op-faq', 'wps_theme_func_faq');
}


function chinhtoa_dashboard_page()
{
  require_once CT_THEME_DIR . '/inc/admin/theme/dashboard.php';
}

function chinhtoa_theme_intro()
{
  // Guide page — open to anyone who can write posts (defense-in-depth; the menu
  // cap already gates it).
  if (!current_user_can('edit_posts')) {
    wp_die(esc_html__('Bạn không có quyền truy cập trang này.', 'chinhtoa'));
  }
  require_once CT_THEME_DIR . '/inc/admin/theme/theme-guide.php';
}
function chinhtoa_theme_settings()
{
  // Redirect to Framework Settings 
}
function wps_theme_func_faq()
{
  echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
        <h2>FAQ</h2></div>';
}