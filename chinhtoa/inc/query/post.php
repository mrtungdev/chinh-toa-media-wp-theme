<?php
if (!defined('ABSPATH')) {
    exit;
}

function ct_delete_all_transient($post_id)
{
    global $wpdb;
    // Only clear transients created by this theme (prefixed "trans_"),
    // never the whole site's transients (would break other plugins' caches).
    $like         = $wpdb->esc_like('_transient_trans_') . '%';
    $like_timeout = $wpdb->esc_like('_transient_timeout_trans_') . '%';
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $wpdb->options WHERE option_name LIKE %s OR option_name LIKE %s",
            $like,
            $like_timeout
        )
    );
}
//Remove Transient after editpost
add_action('save_post', 'ct_delete_all_transient');
