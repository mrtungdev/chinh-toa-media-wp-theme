<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ct_set_value_post_meta($val, $postID, $metaKey){
	$metaVal = get_post_meta( $postID, $metaKey, true );
	$noMeta  = ( empty( $metaVal ) ) ? 0 : $metaVal;
	if ( $metaVal != $val ){
		update_post_meta( $postID, $metaKey, $val );
	}
}
