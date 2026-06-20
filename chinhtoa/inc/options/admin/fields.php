<?php

/**
 * Field-rendering helpers for the native theme-options editor.
 *
 * Every field's `name` attribute encodes its full path inside the `ct_settings`
 * option, e.g. name="ct_settings[header_data][c_content][header_text]". WordPress
 * parses such names into a nested array on submit, so $_POST['ct_settings'] arrives
 * in the EXACT nested shape the front-end flatteners expect — no transformation.
 *
 * Conditional sub-options (Unyson's "multi-picker") are rendered as ordinary
 * fields with a data-ct-show attribute; admin-options.js shows/hides them based on
 * the controlling discriminator. Hidden fields still submit (matching Unyson), and
 * the flatteners only read the branch the discriminator selects.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Build a ct_settings[...] field name from a path array.
 *
 * @param array $path
 * @return string
 */
function ct_opt_name($path)
{
    $name = 'ct_settings';
    foreach ((array) $path as $p) {
        $name .= '[' . $p . ']';
    }
    return $name;
}

/**
 * Build a DOM id from a path array.
 *
 * @param array $path
 * @return string
 */
function ct_opt_id($path)
{
    return 'ct_' . implode('_', (array) $path);
}

/**
 * Read the current value at a path from the settings array.
 *
 * @param array $settings
 * @param array $path
 * @param mixed $default
 * @return mixed
 */
function ct_opt_val($settings, $path, $default = '')
{
    $v = $settings;
    foreach ((array) $path as $p) {
        if (is_array($v) && array_key_exists($p, $v)) {
            $v = $v[$p];
        } else {
            return $default;
        }
    }
    return $v;
}

/**
 * Open a field row. $show = [controlPath, value] makes the row conditional.
 */
function ct_field_open($label, $desc = '', $show = null)
{
    $attrs = '';
    if (is_array($show)) {
        $attrs = ' data-ct-show="' . esc_attr(ct_opt_name($show[0])) . '" data-ct-show-val="' . esc_attr($show[1]) . '"';
    }
    echo '<tr class="ct-field-row"' . $attrs . '>';
    echo '<th scope="row">' . esc_html($label) . '</th><td>';
    if ($desc) {
        echo '<p class="description" style="margin:0 0 6px;">' . wp_kses_post($desc) . '</p>';
    }
}

function ct_field_close()
{
    echo '</td></tr>';
}

function ct_field_text($settings, $path, $label, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    printf(
        '<input type="text" class="regular-text" id="%s" name="%s" value="%s">',
        esc_attr(ct_opt_id($path)),
        esc_attr(ct_opt_name($path)),
        esc_attr(ct_opt_val($settings, $path))
    );
    ct_field_close();
}

function ct_field_number($settings, $path, $label, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    printf(
        '<input type="number" class="small-text" id="%s" name="%s" value="%s">',
        esc_attr(ct_opt_id($path)),
        esc_attr(ct_opt_name($path)),
        esc_attr(ct_opt_val($settings, $path))
    );
    ct_field_close();
}

function ct_field_textarea($settings, $path, $label, $desc = '', $rows = 5, $show = null)
{
    ct_field_open($label, $desc, $show);
    printf(
        '<textarea class="large-text code" rows="%d" id="%s" name="%s">%s</textarea>',
        (int) $rows,
        esc_attr(ct_opt_id($path)),
        esc_attr(ct_opt_name($path)),
        esc_textarea(ct_opt_val($settings, $path))
    );
    ct_field_close();
}

function ct_field_editor($settings, $path, $label, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    wp_editor(
        ct_opt_val($settings, $path),
        ct_opt_id($path),
        array(
            'textarea_name' => ct_opt_name($path),
            'textarea_rows' => 8,
            'media_buttons' => true,
        )
    );
    ct_field_close();
}

/**
 * @param array $choices value => label
 */
function ct_field_select($settings, $path, $label, $choices, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    $current = ct_opt_val($settings, $path);
    printf('<select id="%s" name="%s">', esc_attr(ct_opt_id($path)), esc_attr(ct_opt_name($path)));
    foreach ($choices as $val => $text) {
        printf('<option value="%s"%s>%s</option>', esc_attr($val), selected($current, $val, false), esc_html($text));
    }
    echo '</select>';
    ct_field_close();
}

/**
 * Radio group. Acts as a discriminator: its name is used by conditional rows.
 *
 * @param array $choices value => label
 */
function ct_field_radio($settings, $path, $label, $choices, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    $current = ct_opt_val($settings, $path);
    $name    = ct_opt_name($path);
    echo '<span class="ct-radio-group" data-ct-name="' . esc_attr($name) . '">';
    foreach ($choices as $val => $text) {
        printf(
            '<label style="margin-right:14px;"><input type="radio" name="%s" value="%s"%s class="ct-discriminator"> %s</label>',
            esc_attr($name),
            esc_attr($val),
            checked($current, $val, false),
            esc_html($text)
        );
    }
    echo '</span>';
    ct_field_close();
}

/**
 * Two-state switch stored as $on/$off. Always submits a value (hidden + checkbox).
 */
function ct_field_switch($settings, $path, $label, $desc = '', $on = 'y', $off = 'n', $show = null)
{
    ct_field_open($label, $desc, $show);
    $current = ct_opt_val($settings, $path, $off);
    $name    = ct_opt_name($path);
    printf('<input type="hidden" name="%s" value="%s">', esc_attr($name), esc_attr($off));
    printf(
        '<label class="ct-switch"><input type="checkbox" class="ct-discriminator" data-ct-name="%s" data-on="%s" data-off="%s" name="%s" value="%s"%s> %s</label>',
        esc_attr($name),
        esc_attr($on),
        esc_attr($off),
        esc_attr($name),
        esc_attr($on),
        checked($current, $on, false),
        esc_html__('Bật', 'chinhtoa')
    );
    ct_field_close();
}

function ct_field_color($settings, $path, $label, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    printf(
        '<input type="text" class="ct-color-field" id="%s" name="%s" value="%s" data-default-color="">',
        esc_attr(ct_opt_id($path)),
        esc_attr(ct_opt_name($path)),
        esc_attr(ct_opt_val($settings, $path))
    );
    ct_field_close();
}

/**
 * Media (image) picker storing a URL string at $path (or a {url:...} sub-key when
 * $url_subkey is true, matching some Unyson image shapes).
 */
function ct_field_media($settings, $path, $label, $desc = '', $url_subkey = false, $show = null)
{
    ct_field_open($label, $desc, $show);
    $value_path = $url_subkey ? array_merge((array) $path, array('url')) : (array) $path;
    $url        = ct_opt_val($settings, $value_path);
    $name       = ct_opt_name($value_path);
    $id         = ct_opt_id($value_path);
    echo '<div class="ct-media-field">';
    printf('<input type="text" class="regular-text ct-media-url" id="%s" name="%s" value="%s">', esc_attr($id), esc_attr($name), esc_attr($url));
    echo ' <button type="button" class="button ct-media-btn">' . esc_html__('Chọn ảnh', 'chinhtoa') . '</button>';
    echo ' <button type="button" class="button ct-media-clear">' . esc_html__('Xoá', 'chinhtoa') . '</button>';
    echo '<div class="ct-media-preview" style="margin-top:8px;">' . ($url ? '<img src="' . esc_url($url) . '" style="max-width:160px;height:auto;">' : '') . '</div>';
    echo '</div>';
    ct_field_close();
}

/**
 * Image-swatch radio (Unyson eImagesPicker): radio backed by a preview image.
 *
 * @param array  $choices value => image-url
 * @param string $variant Size hint added as a `ct-image-radio--{variant}` class:
 *                        'wide' for full-width layout previews (e.g. menu styles),
 *                        'icon' for small count icons, '' for the default size.
 */
function ct_field_image_radio($settings, $path, $label, $choices, $desc = '', $show = null, $variant = '')
{
    ct_field_open($label, $desc, $show);
    $current = ct_opt_val($settings, $path);
    $name    = ct_opt_name($path);
    $cls     = 'ct-image-radio' . ($variant ? ' ct-image-radio--' . $variant : '');
    echo '<div class="' . esc_attr($cls) . '" data-ct-name="' . esc_attr($name) . '">';
    foreach ($choices as $val => $img) {
        printf(
            '<label class="ct-image-radio-item%s"><input type="radio" name="%s" value="%s"%s class="ct-discriminator"><img src="%s" alt="%s"></label>',
            ($current === $val ? ' selected' : ''),
            esc_attr($name),
            esc_attr($val),
            checked($current, $val, false),
            esc_url($img),
            esc_attr($val)
        );
    }
    echo '</div>';
    ct_field_close();
}

/**
 * Color-swatch radio: a labelled box per choice showing the color block + its
 * name. Replaces the image-swatch picker for the theme color selector.
 *
 * @param array $choices value => array('name' => 'Label', 'color' => '#hex')
 *                       or array('name' => 'Label', 'gradient' => true) for a
 *                       rainbow "pick your own" swatch. Optional 'swatch_class'.
 */
function ct_field_color_radio($settings, $path, $label, $choices, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    $current = ct_opt_val($settings, $path);
    $name    = ct_opt_name($path);
    $rainbow = 'linear-gradient(90deg,#e53935,#fb8c00,#fdd835,#43a047,#1e88e5,#8e24aa)';
    echo '<div class="ct-color-radio" data-ct-name="' . esc_attr($name) . '">';
    foreach ($choices as $val => $opt) {
        $bg = !empty($opt['gradient']) ? $rainbow : (isset($opt['color']) ? $opt['color'] : '#ffffff');
        $sc = isset($opt['swatch_class']) ? ' ' . $opt['swatch_class'] : '';
        printf(
            '<label class="ct-color-radio-item%s"><input type="radio" name="%s" value="%s"%s class="ct-discriminator"><span class="ct-color-swatch%s" style="background:%s"></span><span class="ct-color-name">%s</span></label>',
            ($current === $val ? ' selected' : ''),
            esc_attr($name),
            esc_attr($val),
            checked($current, $val, false),
            esc_attr($sc),
            esc_attr($bg),
            esc_html($opt['name'])
        );
    }
    echo '</div>';
    ct_field_close();
}

/* ----------------------------------------------------------------------------
 * Richer controls (parity with Unyson's category picker / slider / datetime).
 *
 * Each is split into a low-level *_control() that renders a widget bound to a
 * single named input, and a *_field() wrapper that lays it out as a form row.
 * The control fns are reused by the home_sec repeater (sections.php) so they
 * must work inside cloned rows — they key off DOM proximity / classes, never
 * ids, and accept Unyson's $disabled string used to gate non-selected groups.
 *
 * Storage shapes are unchanged from the plain text/number fields they replace:
 *   - taxonomy → comma-separated term-ID string (read via ct_cats_str())
 *   - slider   → integer string
 *   - datetime → "YYYY-MM-DD HH:MM" string
 * so the front-end flatteners and tests/options-test.php are unaffected.
 * ------------------------------------------------------------------------- */

/**
 * Category multi-select. The visible <select multiple> has NO name (never
 * submits); admin-options.js mirrors its selection into the hidden CSV input
 * that does submit. Pre-selection is rendered server-side.
 *
 * @param string $name      Submitting input name (full ct_settings[...] path).
 * @param string $value_csv Current comma-separated term IDs.
 * @param string $disabled  ' disabled' when inside a non-selected template group.
 */
function ct_taxonomy_control($name, $value_csv, $disabled = '')
{
    $value_csv = (string) $value_csv;
    $selected  = array_filter(array_map('trim', explode(',', $value_csv)), 'strlen');
    $cats      = get_categories(array('hide_empty' => false, 'orderby' => 'name'));
    echo '<span class="ct-taxonomy-field">';
    printf('<input type="hidden" class="ct-taxonomy-val" name="%s" value="%s"%s>', esc_attr($name), esc_attr($value_csv), $disabled);
    echo '<select multiple class="ct-taxonomy" size="6"' . $disabled . '>';
    foreach ($cats as $c) {
        printf(
            '<option value="%d"%s>%s</option>',
            (int) $c->term_id,
            in_array((string) $c->term_id, $selected, true) ? ' selected' : '',
            esc_html($c->name)
        );
    }
    echo '</select>';
    echo '<span class="ct-taxonomy-hint description">' . esc_html__('Giữ Ctrl/Cmd để chọn nhiều chuyên mục.', 'chinhtoa') . '</span>';
    echo '</span>';
}

function ct_field_taxonomy($settings, $path, $label, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    ct_taxonomy_control(ct_opt_name($path), ct_opt_val($settings, $path), '');
    ct_field_close();
}

/**
 * Range slider mirrored to a number input. The number input carries the name
 * and submits an integer string; the range only drives it via JS.
 */
function ct_slider_control($name, $value, $min = 0, $max = 30, $disabled = '')
{
    $num   = ($value === '' || $value === null) ? '' : (int) $value;
    $range = ($num === '') ? (int) $min : $num;
    echo '<span class="ct-slider-field">';
    printf('<input type="range" class="ct-slider" min="%d" max="%d" value="%d"%s>', (int) $min, (int) $max, $range, $disabled);
    printf('<input type="number" class="ct-slider-val small-text" name="%s" min="%d" max="%d" value="%s"%s>', esc_attr($name), (int) $min, (int) $max, esc_attr($num), $disabled);
    echo '</span>';
}

function ct_field_slider($settings, $path, $label, $desc = '', $min = 0, $max = 30, $show = null)
{
    ct_field_open($label, $desc, $show);
    ct_slider_control(ct_opt_name($path), ct_opt_val($settings, $path), $min, $max, '');
    ct_field_close();
}

/**
 * Native date + time inputs combined (via JS) into a hidden
 * "YYYY-MM-DD HH:MM" string — the format the front-end already parses. The
 * stored value is split server-side so the pickers show the current value.
 */
function ct_datetime_control($name, $value, $disabled = '')
{
    $value = (string) $value;
    $date  = '';
    $time  = '';
    if ($value !== '' && preg_match('/^(\d{4}-\d{2}-\d{2})[ T]?(\d{2}:\d{2})?/', $value, $m)) {
        $date = $m[1];
        $time = isset($m[2]) ? $m[2] : '';
    }
    echo '<span class="ct-datetime-field">';
    printf('<input type="hidden" class="ct-datetime-val" name="%s" value="%s"%s>', esc_attr($name), esc_attr($value), $disabled);
    printf('<input type="date" class="ct-datetime-date" value="%s"%s>', esc_attr($date), $disabled);
    printf('<input type="time" class="ct-datetime-time" value="%s"%s>', esc_attr($time), $disabled);
    echo '</span>';
}

function ct_field_datetime($settings, $path, $label, $desc = '', $show = null)
{
    ct_field_open($label, $desc, $show);
    ct_datetime_control(ct_opt_name($path), ct_opt_val($settings, $path), '');
    ct_field_close();
}
