<?php

/**
 * Centralized white-label configuration.
 *
 * This is the SINGLE place that holds brand-specific identity (name, author,
 * URLs, favicons, admin labels, social IDs, optional features). To rebrand the
 * theme for another client, edit the defaults below (and swap the favicon/logo
 * image assets) — no other PHP file should contain hardcoded brand strings.
 *
 * Values can also be overridden without editing this file by hooking the
 * `ct_brand` filter from a child theme or mu-plugin:
 *
 *     add_filter('ct_brand', function ($brand) {
 *         $brand['name']   = 'Acme Media';
 *         $brand['author'] = 'Acme';
 *         return $brand;
 *     });
 *
 * See REBRAND.md for the full rebranding checklist.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default brand values. Defaults reflect the current "Chính Tòa Media" brand so
 * existing installs are unchanged; override per client to white-label.
 *
 * @return array
 */
function ct_brand_defaults()
{
    return array(
        // --- Identity ---
        'name'                => 'Chính Tòa Media',
        'tagline'             => '',
        'author'              => 'ToiLaTung',
        'author_uri'          => 'https://toilatung.com',
        'theme_uri'           => 'https://toilatung.com',
        'support_url'         => '',

        // --- Admin UI ---
        'admin_menu_title'    => 'TRUYỀN THÔNG GXCT ĐÀ NẴNG',
        'admin_menu_label'    => 'Chính Tòa Media',
        // Dashicon name (e.g. 'dashicons-admin-appearance') or a data:image/svg+xml URI. '' => WP default.
        'admin_menu_icon'     => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzMiIgaGVpZ2h0PSIyNiI+PHBhdGggZD0iTTIzLjIyMyAxMi4zMzRMMTcuNiA4Ljk2VjYuNEgyMGEuOC44IDAgMCAwIC44LS44VjRhLjguOCAwIDAgMC0uOC0uOGgtMi40Vi44YS44LjggMCAwIDAtLjgtLjhoLTEuNmEuOC44IDAgMCAwLS44Ljh2Mi40SDEyYS44LjggMCAwIDAtLjguOHYxLjZhLjguOCAwIDAgMCAuOC44aDIuNHYyLjU2bC01LjYyMyAzLjM3NEExLjYgMS42IDAgMCAwIDggMTMuNzA2VjI1LjZoNC44di00LjhhMy4yIDMuMiAwIDAgMSA2LjQgMHY0LjhIMjRWMTMuNzA2YTEuNiAxLjYgMCAwIDAtLjc3Ny0xLjM3MnpNMCAxOS43OThWMjQuOGEuOC44IDAgMCAwIC44LjhoNS42VjE2TC45NyAxOC4zMjdBMS42IDEuNiAwIDAgMCAwIDE5Ljc5OHptMzEuMDMtMS40N0wyNS42IDE2djkuNmg1LjZhLjguOCAwIDAgMCAuOC0uOHYtNS4wMDJhMS42IDEuNiAwIDAgMC0uOTY5LTEuNDcxeiIgZmlsbD0iY3VycmVudENvbG9yIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48L3N2Zz4=',
        // Admin footer credit (printed via admin_footer_text). '' => no credit line.
        'admin_footer_credit' => 'Giao diện được thiết kế bởi: <strong>TRUYỀN THÔNG GIÁO XỨ CHÍNH TÒA ĐÀ NẴNG</strong>',

        // --- Assets ---
        // Folder (relative to theme root) holding the favicon set. '' => skip favicon <head> output.
        'favicon_dir'         => 'assets/imgs/favicons',
        'logo'                => 'assets/imgs/logo/logo.png',

        // --- <head> meta / social ---
        'keywords'            => 'Giáo Xứ Chính Tòa Đà Nẵng',
        'og_locale'           => 'vi_VN',
        'fb_profile_id'       => '568237836678072',
        'theme_color'         => '#ffffff',
        'tile_color'          => '#da532c',
        'mask_icon_color'     => '#5bbad5',

        // --- Optional brand-specific features ---
        'features'            => array(
            // "Lời Chúa" daily-gospel box (religious). Set false for a generic build.
            'loichua' => true,
        ),
    );
}

/**
 * Read a white-label brand value. Supports dot-notation for nested keys,
 * e.g. ct_brand('features.loichua').
 *
 * @param string|null $key     Brand key, or null for the whole array.
 * @param mixed       $default Returned when the key is absent.
 * @return mixed
 */
function ct_brand($key = null, $default = '')
{
    $brand = apply_filters('ct_brand', ct_brand_defaults());

    if (null === $key) {
        return $brand;
    }

    if (false !== strpos($key, '.')) {
        $value = $brand;
        foreach (explode('.', $key) as $part) {
            if (is_array($value) && array_key_exists($part, $value)) {
                $value = $value[$part];
            } else {
                return $default;
            }
        }
        return $value;
    }

    return array_key_exists($key, $brand) ? $brand[$key] : $default;
}

/**
 * Whether an optional brand feature is enabled.
 *
 * @param string $feature Feature key under 'features' (e.g. 'loichua').
 * @return bool
 */
function ct_brand_feature($feature)
{
    return (bool) ct_brand('features.' . $feature, false);
}

/**
 * Build a theme-relative favicon URL, or '' when favicons are disabled.
 *
 * @param string $file File name within the favicon folder.
 * @return string
 */
function ct_brand_favicon_uri($file)
{
    $dir = ct_brand('favicon_dir');
    if (empty($dir)) {
        return '';
    }
    return get_theme_file_uri('/' . trim($dir, '/') . '/' . ltrim($file, '/'));
}
