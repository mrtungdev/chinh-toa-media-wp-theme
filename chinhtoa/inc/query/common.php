<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cached get_posts(). Results are stored in a `trans_*` transient so repeated
 * homepage/archive queries don't re-hit the DB. Caches are flushed on
 * publish/update/delete (see ct_flush_post_caches) so content stays fresh.
 *
 * @param array  $args     WP get_posts() args.
 * @param string $trans    Transient key (theme convention: "trans_...").
 * @param int    $expireIn TTL in seconds.
 * @return WP_Post[]
 */
function ct_get_posts($args, $trans, $expireIn = DAY_IN_SECONDS){
	if ( false === ( $latest = get_transient( $trans ) ) ) {
		$latest = get_posts( $args );
		set_transient( $trans , $latest, $expireIn );
		return $latest;
	}
	return $latest;
}

/**
 * Flush the theme's cached post queries when content changes, so the homepage
 * and archives reflect new/edited/deleted posts immediately instead of waiting
 * for the transient TTL to lapse.
 */
function ct_flush_post_caches(){
	global $wpdb;
	$like = $wpdb->esc_like( '_transient_trans_' ) . '%';
	$like_to = $wpdb->esc_like( '_transient_timeout_trans_' ) . '%';
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
		$like,
		$like_to
	) );
}
add_action( 'save_post', 'ct_flush_post_caches' );
add_action( 'deleted_post', 'ct_flush_post_caches' );
