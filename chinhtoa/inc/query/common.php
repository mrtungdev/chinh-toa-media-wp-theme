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
	ct_prime_post_caches( $latest );
	return $latest;
}

/**
 * Bài đọc lại từ transient không kèm cache meta/chuyên mục/ảnh đại diện → mỗi thẻ bài
 * lại tự truy vấn (lượt xem, loại bài, permalink, ảnh). Nạp sẵn một lần cho cả danh sách.
 *
 * @param WP_Post[] $posts
 */
function ct_prime_post_caches( $posts ){
	if ( empty( $posts ) || ! is_array( $posts ) ) {
		return;
	}
	update_post_caches( $posts, 'post', true, true );
	$thumbIds = array();
	foreach ( $posts as $p ) {
		$thumbId = (int) get_post_meta( $p->ID, '_thumbnail_id', true ); // đã có trong cache meta
		if ( $thumbId ) {
			$thumbIds[] = $thumbId;
		}
	}
	if ( $thumbIds ) {
		_prime_post_caches( $thumbIds, false, true );
	}
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
// Lưu Thiết lập giao diện (đổi chuyên mục, số bài…) → trang chủ cập nhật ngay.
add_action( 'add_option_ct_settings', 'ct_flush_post_caches' );
add_action( 'update_option_ct_settings', 'ct_flush_post_caches' );
// Đổi tên / xoá chuyên mục → khối trang chủ, box 5 phút không hiện danh sách cũ.
add_action( 'created_category', 'ct_flush_post_caches' );
add_action( 'edited_category', 'ct_flush_post_caches' );
add_action( 'delete_category', 'ct_flush_post_caches' );
