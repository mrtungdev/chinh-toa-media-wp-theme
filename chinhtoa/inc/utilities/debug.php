<?php
if (!defined('ABSPATH')) {
    exit;
}

function ct_console($data)
{
    if (is_array($data))
        $output = "<script>console.log('" . json_encode($data) . "');</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
    echo $output;
}

function bb_print_scripts_styles()
{
    $result = [];
    $result['scripts'] = [];
    $result['styles'] = [];
    // Print all loaded Scripts
    global $wp_scripts;
    foreach ($wp_scripts->queue as $script) :
        $result['scripts'][] =  $wp_scripts->registered[$script]->handle . ";" . $wp_scripts->registered[$script]->src . ";";
    endforeach;

    // Print all loaded Styles (CSS)
    global $wp_styles;
    foreach ($wp_styles->queue as $style) :
        $result['styles'][] =  $wp_styles->registered[$style]->handle . ";" . $wp_styles->registered[$style]->src . ";";
    endforeach;

    return $result;
}

function write_log($log, $name = '')
{
    if ($name != '') {
        error_log("===== START: " . $name);
    }
    if (is_array($log) || is_object($log)) {
        error_log(print_r($log, true));
    } else {
        error_log($log);
    }
    if ($name != '') {
        error_log("===== END: " . $name);
    }
}

function ctprint($arr, $label = '')
{
    if (WP_DEBUG === true) {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
        if ($label != '')
            echo '<strong>' . $label . '</strong>';
        echo '<p style="height: 10px;"></p>';
    }
}