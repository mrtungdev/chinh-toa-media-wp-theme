<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Homepage dynamic sections, rendered over AJAX.
 *
 * SECURITY: the client never sends section configuration — only the section
 * INDEX (+ nonce). The server re-derives the section from the stored theme
 * options via home_GetSections(), so no request-controlled data reaches the
 * templates. home_GetSections() also enforces the per-section
 * current_user_can('manage_options') gate for admin-only sections.
 */

add_action('wp_ajax_nopriv_homepage_tabs_template_call', 'homepage_tabs_template_call');
add_action('wp_ajax_homepage_tabs_template_call', 'homepage_tabs_template_call');

function homepage_tabs_template_call()
{
    if (!(defined('DOING_AJAX') && DOING_AJAX)) {
        exit();
    }
    // Dies with a 403 on an invalid/expired nonce.
    check_ajax_referer('ct_homepage_tabs', 'nonce', true);

    $index    = isset($_REQUEST['index']) ? absint($_REQUEST['index']) : -1;
    $sections = function_exists('home_GetSections') ? home_GetSections() : array();

    if ($index < 0 || !isset($sections[$index]) || !is_array($sections[$index])) {
        wp_die('', '', array('response' => 400));
    }

    echo homepage_tabs_template_get($sections[$index]); // phpcs:ignore — template output, self-escaping
    wp_die();
}

/**
 * Render one homepage section to an HTML string.
 *
 * @param array $section Trusted section config from home_GetSections().
 * @return string
 */
function homepage_tabs_template_get($section)
{
    if (!is_array($section) || empty($section['type'])) {
        return '';
    }

    $templates = array(
        'temp0' => 'template-parts/homepage/c_static.php',
        'temp1' => 'template-parts/homepage/c_post-template1.php',
        'temp2' => 'template-parts/homepage/c_post-template2.php',
        'temp3' => 'template-parts/homepage/c_post-template3.php',
        'temp4' => 'template-parts/homepage/c_post-template4.php',
        'temp5' => 'template-parts/homepage/c_post-template5.php',
        'temp6' => 'template-parts/homepage/c_post-template6.php',
    );

    if (!isset($templates[$section['type']])) {
        return '';
    }

    $file = locate_template($templates[$section['type']], false, false);
    if (!$file) {
        return '';
    }

    ob_start();
    include $file; // $section is in scope for the template.
    return ob_get_clean();
}
