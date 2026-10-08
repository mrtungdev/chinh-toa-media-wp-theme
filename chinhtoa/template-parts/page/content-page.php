<?php
/**
 * Template part for displaying page content in page.php
 *
 * Cùng cấu trúc .post-header / .post-content với template-parts/post/content.php để dùng
 * chung CSS trang bài viết (#ct-content.ct-single); trang không có tác giả, ngày, lượt xem.
 *
 * Expects $postSettings from page.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CTMedia
 */
$title = get_the_title();
$isShowPostThumb = isset($postSettings['post_thumb']) ? $postSettings['post_thumb'] : 'y';
$post_breadcrumb = isset($postSettings['post_breadcrumb']) ? $postSettings['post_breadcrumb'] : 'y';
$isShowPostTitle = isset($postSettings['post_title']) ? $postSettings['post_title'] : 'y';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="post-header">
    <?php if ($isShowPostThumb == 'y' && has_post_thumbnail()) : ?>
    <div class="ct-single-thumb">
      <?php echo get_the_post_thumbnail(get_the_ID(), 'large', array('loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async', 'alt' => $title)); ?>
    </div>
    <?php endif; ?>
    <?php if ($post_breadcrumb == 'y' && function_exists('yoast_breadcrumb')) : ?>
    <?php yoast_breadcrumb('<p id="breadcrumbs">', '</p>'); ?>
    <?php endif; ?>
    <?php if ($isShowPostTitle == 'y') : ?>
    <h1><?php echo esc_html($title); ?></h1>
    <?php endif; ?>
  </div>
  <div class="post-content" id="ct-single-postcontent">
    <?php
    the_content();
    wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('Trang:', 'chinhtoa'),
        'after'  => '</div>',
    ));
    ?>
  </div>
  <?php edit_post_link(esc_html__('Sửa trang', 'chinhtoa'), '<p class="ct-edit-link">', '</p>'); ?>
</article><!-- #post-<?php the_ID(); ?> -->
