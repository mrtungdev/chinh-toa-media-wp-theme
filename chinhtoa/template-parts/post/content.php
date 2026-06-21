<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CTMedia
 */
$title = get_the_title($post->ID);
$isShowPostThumb = isset($postSettings['post_thumb']) ? $postSettings['post_thumb'] : 'y';
$post_breadcrumb = isset($postSettings['post_breadcrumb']) ? $postSettings['post_breadcrumb'] : 'y';
$isShowPostMeta = isset($postSettings['post_info']) ? $postSettings['post_info'] : 'y';
$isShowPostTitle = isset($postSettings['post_title']) ? $postSettings['post_title'] : 'y';
$isShowPostAuthor = isset($postSettings['post_author']) ? $postSettings['post_author'] : 'y';
?>
<div class="post-header">
  <?php
	$ctKind = function_exists('ct_post_kind') ? ct_post_kind($post->ID) : 'default';
	// Thẻ câu ghi nhớ (loại "Lời Chúa") — đặt trên cùng bài.
	if ($ctKind === 'loichua' && function_exists('ct_post_kind_loichua_html')) {
		echo ct_post_kind_loichua_html($post->ID); // đã escape trong hàm render.
	}
	// Trình phát video (loại "Video (audio) Lời Chúa") — thay ảnh đại diện tĩnh.
	$ctVideo = ($ctKind === 'media' && function_exists('ct_post_kind_video_html')) ? ct_post_kind_video_html($post->ID) : '';
	if ($ctVideo !== '') {
		echo $ctVideo; // wp_oembed_get trả HTML từ provider đã biết.
	} elseif ($isShowPostThumb == 'y') {
		$image = getPostImage($post->ID);
	?>
  <div class="ct-single-thumb">
    <img class="lazyload" src="<?php echo esc_url(CT_PLACEHOLDER); ?>" data-src="<?php echo esc_url($image); ?>"
      alt="<?php echo esc_attr($title); ?>">
  </div>
  <?php } ?>
  <?php if ($post_breadcrumb == 'y') : ?>
  <?php 
	if ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
	}
	?>
  <?php endif; ?>
  <?php if ($isShowPostTitle == 'y') : ?>
  <h1><?php echo esc_html($title); ?></h1>
  <?php endif; ?>
  <div class="post-info">
    <?php if ($isShowPostAuthor == 'y') : ?>
    <div class="post-meta post-author">
      <svg class="ct-icon ct-icon-author" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align:-0.125em"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/></svg>
      <?php echo esc_html(get_the_author()); ?>
    </div>
    <?php endif; ?>
    <?php if ($isShowPostMeta == 'y'){ 
				$date = get_the_date('', $post->ID);
				$views = intval(get_post_meta($post->ID, 'views', true));
			?>
    <div class="post-meta post-date">
      <svg class="ct-icon ct-icon-calendar" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align:-0.125em"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z"/></svg>
      <?php echo esc_html($date); ?>
    </div>
    <div class="post-meta post-views">
      <svg class="ct-icon ct-icon-eye" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align:-0.125em"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
      <?php echo esc_html($views); ?> <?php esc_html_e('lượt xem', 'chinhtoa'); ?>
    </div>
    <div class="post-text-sizes">
      <div class="text-size-list" id="ct-post-sizes">
        <a class="post-text-size is-small" data-size="is-small" data-bs-toggle="tooltip" title="<?php esc_attr_e('Đọc với cỡ chữ NHỎ', 'chinhtoa'); ?>">A</a>
        <a class="post-text-size is-normal activated" data-size="is-normal" data-bs-toggle="tooltip" title="<?php esc_attr_e('Đọc với cỡ chữ VỪA', 'chinhtoa'); ?>">A</a>
        <a class="post-text-size is-medium" data-size="is-medium" data-bs-toggle="tooltip" title="<?php esc_attr_e('Đọc với cỡ chữ KHÁ LỚN', 'chinhtoa'); ?>">A</a>
        <a class="post-text-size is-large" data-size="is-large" data-bs-toggle="tooltip" title="<?php esc_attr_e('Đọc với cỡ chữ LỚN', 'chinhtoa'); ?>">A</a>
      </div>
    </div>
    <?php } ?>
  </div>
</div>
<div class="post-content" id="ct-single-postcontent">

  <?php	
	the_content();
	wp_link_pages( array(
		'before' => '<div class="page-links">' . esc_html__( 'Trang:', 'chinhtoa' ),
		'after'  => '</div>',
	) );
	?>
</div>