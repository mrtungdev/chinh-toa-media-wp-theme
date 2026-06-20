<?php

/**
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
    exit;
}
// Main Core CONS
$theme = wp_get_theme();
define('THEME_VERSION', $theme->get( 'Version' ));
define('CT_HOME_URL', esc_url(home_url('/')));
define('CT_THEME_DIR', get_template_directory());
define('CT_THEME_URI', get_template_directory_uri());
define('CT_THEME_IMGS_URI', get_template_directory_uri() . '/assets/imgs');
define('CT_THEME_CSS_URI', get_template_directory_uri() . '/assets/css');
define('CT_THEME_JS_URI', get_template_directory_uri() . '/assets/js');
define('CT_NAME', get_bloginfo('name'));
define('CT_NO_IMAGE', get_template_directory_uri() . '/assets/imgs/icons/no-image.jpg');
define('CT_PLACEHOLDER', get_template_directory_uri() . '/assets/imgs/ct-image-skeleton.jpg');

final class CT_SETUP_THEME
{
    public function __construct()
    {
        // Unyson removed — the theme now uses its own native options engine
        // (inc/options/). The legacy framework/ directory is no longer loaded.
        require_once CT_THEME_DIR . '/inc/tgma/tgma.php';

        self::includes_funcs();

        add_action('after_setup_theme', array('CT_SETUP_THEME', 'theme_setup'), 5);
    }

    public static function includes_funcs()
    {
        require_once CT_THEME_DIR . '/inc/brand/brand-config.php';
        require_once CT_THEME_DIR . '/inc/utilities/admin_menu.php';
        require_once CT_THEME_DIR . '/inc/utilities/dashboard.php';
        
        require_once CT_THEME_DIR . '/inc/shortcodes/ct_shortcode_post.php';
        require_once CT_THEME_DIR . '/inc/utilities/debug.php';
        require_once CT_THEME_DIR . '/inc/utilities/type.php';
        require_once CT_THEME_DIR . '/inc/utilities/action.php';
        require_once CT_THEME_DIR . '/inc/utilities/filter.php';
        require_once CT_THEME_DIR . '/inc/utilities/sidebar.php';

        require_once CT_THEME_DIR . '/inc/post/ajax.php';
        require_once CT_THEME_DIR . '/inc/post/posttype.php';
        require_once CT_THEME_DIR . '/inc/post/posttags.php';
        require_once CT_THEME_DIR . '/inc/post/taxonomy.php';
        require_once CT_THEME_DIR . '/inc/post/postsloop.php';

        require_once CT_THEME_DIR . '/inc/options/loader.php';

        require_once CT_THEME_DIR . '/inc/query/common.php';
        require_once CT_THEME_DIR . '/inc/query/post.php';
        require_once CT_THEME_DIR . '/inc/query/ajax.php';

        require_once CT_THEME_DIR . '/inc/utilities/enqueue.php';
        require_once CT_THEME_DIR . '/inc/utilities/github-updater.php';
    }

    public static function theme_setup()
    {

        load_theme_textdomain('chinhtoa', CT_THEME_DIR . '/languages');

        // if (!isset($content_width)) {
        //     $content_width = 900;
        // }

        register_nav_menus(array('primary' => __('Menu Chính', 'chinhtoa')));
        add_theme_support('menus');
        add_theme_support('title-tag');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_theme_support('responsive-embeds');
        add_image_size('large', 960, '', true);
        add_image_size('medium', 690, '', true);
        add_image_size('small', 320, '', true);

        remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
        remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
        remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
        remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
        remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        remove_action('wp_head', 'rel_canonical');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        remove_action('wp_head', 'wp_oembed_add_discovery_links'); // Remove oEmbed discovery links.
        remove_action('wp_head', 'wp_oembed_add_host_js'); // Remove oEmbed-specific JavaScript from the front-end and back-end.
        remove_action('rest_api_init', 'wp_oembed_register_route'); // Remove the REST API endpoint.
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10); // Don't filter oEmbed results.
        add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
        add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
        add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
        add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
        add_filter('embed_oembed_discover', '__return_false'); // Turn off oEmbed auto discovery.

    }
}

new CT_SETUP_THEME();