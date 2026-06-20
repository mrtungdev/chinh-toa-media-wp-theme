<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * (Removed) The legacy `bb_notifications` AJAX endpoint lived here.
 *
 * It was dead and broken: its only caller was in the non-enqueued
 * assets/js/chinhtoa.min.js, and the handler called bbland_notifi_gets_html(),
 * a function that does not exist anywhere in the theme (so any request would
 * fatal). It was also registered for nopriv without a nonce. Removed entirely
 * rather than hardened. The notification bar is rendered server-side from theme
 * options — see the "hot" section flatteners and template parts.
 */
