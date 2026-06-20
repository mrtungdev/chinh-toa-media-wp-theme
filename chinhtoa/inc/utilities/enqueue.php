<?php
if (!defined('ABSPATH')) {
    exit;
}


function bb_enqueues()
{
    // Bootstrap 5 bundle (includes Popper) — self-hosted, no jQuery dependency.
    wp_enqueue_script('bs5', CT_THEME_JS_URI . '/bootstrap.bundle.min.js', array(), '5.3.8', true);
    wp_enqueue_script('swipeboxjs', CT_THEME_JS_URI . '/jquery.swipebox.min.js', array('jquery'), '1.5.1', true);
    wp_enqueue_script('ctjs', CT_THEME_JS_URI . '/ct-media.js', array('jquery', 'swipeboxjs'), THEME_VERSION, true);
    // lazysizes core auto-inits on the `.lazyload` class. (The blur-up plugin was
    // dropped: nothing in the theme emits the data-lowsrc it needs.)
    wp_enqueue_script('lazysizes', CT_THEME_JS_URI . '/lazysizes.min.js', array(), THEME_VERSION, true);
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Charm&family=Lobster&display=swap', array(), null);

    // Per-color stylesheet: each theme-{color}.css is a full, self-contained
    // build (Bootstrap + theme styles + the scheme's primary color baked in).
    // The active color scheme is stored in the `theme_data` option.
    $w         = gen_GetTheme();
    $themeName = !empty($w['theme']) ? $w['theme'] : 'white';

    if ($themeName === 'custom') {
        // theme-custom.css is built with a sentinel primary; swap it for the
        // admin-picked color and serve the tinted result (cached to uploads).
        $color = !empty($w['custom_color']) ? $w['custom_color'] : '#1565c0';
        $url   = ct_custom_theme_css_url($color);
        if ($url) {
            wp_enqueue_style('ctcss_custom', $url, array(), THEME_VERSION . '-' . substr(md5($color), 0, 8));
        } else {
            // Cache dir not writable / base missing: inline the tinted CSS.
            wp_register_style('ctcss_custom', false);
            wp_enqueue_style('ctcss_custom');
            wp_add_inline_style('ctcss_custom', ct_custom_theme_css_string($color));
        }
        return;
    }

    wp_enqueue_style('ctcss_' . $themeName, CT_THEME_CSS_URI . '/theme-' . $themeName . '.css', array(), THEME_VERSION);
}
add_action('wp_enqueue_scripts', 'bb_enqueues');

/** Sentinel primary baked into assets/css/theme-custom.css (see theme-custom.scss). */
function ct_custom_theme_sentinel()
{
    return array('hex' => '#c0ffee', 'rgb' => '192, 255, 238', 'rgb_min' => '192,255,238');
}

/** Normalize a user color to "#rrggbb" (lowercase); '' if invalid. */
function ct_normalize_hex($hex)
{
    $hex = ltrim(trim((string) $hex), '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
        return '';
    }
    return '#' . strtolower($hex);
}

/** "#rrggbb" -> "r, g, b" (separator configurable for the minified rgba form). */
function ct_hex_to_rgb($hex, $sep = ', ')
{
    $hex = ltrim(ct_normalize_hex($hex), '#');
    if ($hex === '') {
        return '';
    }
    return implode($sep, array(hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))));
}

/**
 * theme-custom.css contents with the sentinel primary replaced by $color (hex +
 * both rgb spacings). Empty string if the base file is missing.
 */
function ct_custom_theme_css_string($color)
{
    $hex = ct_normalize_hex($color);
    if ($hex === '') {
        $hex = '#1565c0';
    }
    $base = get_template_directory() . '/assets/css/theme-custom.css';
    if (!is_readable($base)) {
        return '';
    }
    $css = file_get_contents($base);
    $s   = ct_custom_theme_sentinel();
    $css = str_ireplace($s['hex'], $hex, $css);
    $css = str_replace($s['rgb'], ct_hex_to_rgb($hex, ', '), $css);
    $css = str_replace($s['rgb_min'], ct_hex_to_rgb($hex, ','), $css);
    return $css;
}

/**
 * Tinted stylesheet cached under uploads/chinhtoa-theme/. Returns its URL, or ''
 * if the base file is missing or the cache can't be written (caller falls back
 * to inlining the CSS).
 */
function ct_custom_theme_css_url($color)
{
    $hex = ct_normalize_hex($color);
    if ($hex === '') {
        $hex = '#1565c0';
    }
    $up = wp_upload_dir();
    if (!empty($up['error'])) {
        return '';
    }
    $dir  = trailingslashit($up['basedir']) . 'chinhtoa-theme';
    $ver  = defined('THEME_VERSION') ? THEME_VERSION : '0';
    $name = 'theme-custom-' . substr(md5($hex . '|' . $ver), 0, 10) . '.css';
    $file = $dir . '/' . $name;
    $url  = trailingslashit($up['baseurl']) . 'chinhtoa-theme/' . $name;

    if (is_readable($file)) {
        return $url;
    }
    $css = ct_custom_theme_css_string($color);
    if ($css === '') {
        return '';
    }
    if (!wp_mkdir_p($dir)) {
        return '';
    }
    if (file_put_contents($file, $css) === false) {
        return '';
    }
    return $url;
}

function bb_admin_enqueues()
{
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    // wp_enqueue_script('jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array(), '1.12.1', true);
    wp_enqueue_style('faadmin', get_template_directory_uri() . '/assets/fontawesome/css/font-awesome.min.css', array(), null);
    // wp_enqueue_style('faadmin', get_template_directory_uri() . '/assets/fontawesome/css/fontawesome.min.css', array(), null);
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=Dancing+Script:400&display=swap', array(), null);
    wp_enqueue_style('bt4css', CT_THEME_CSS_URI . '/admin.min.css', array(), null);
    wp_enqueue_script('bt4js', CT_THEME_JS_URI . '/admin.min.js', array('jquery'), null, true);
    // ('adminopt' removed — it depended on the Unyson 'fw' script which no longer exists.)
}
add_action('admin_enqueue_scripts', 'bb_admin_enqueues');

add_action('wp_footer', 'bbland_ajax_url');
function bbland_ajax_url()
{ ?>
<script>
ct_ajax_url = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";
ct_ajax_nonce = "<?php echo esc_js(wp_create_nonce('ct_homepage_tabs')); ?>";
</script>
<?php } ?>