<?php

/**
 * Template Name: Trang Chủ
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CTMedia
 */

get_header();
$homepageGeneral = home_GetGeneral();
$homepageSection = home_GetSections();
$homepageFeatured = home_GetFeatured();
?>
<div id="ct-content">
  <?php
  // Trang chủ không có tiêu đề hiển thị → <h1> ẩn cho SEO / trình đọc màn hình.
  $ctTagline = get_bloginfo('description');
  printf('<h1 class="visually-hidden">%s</h1>', esc_html(get_bloginfo('name') . ($ctTagline !== '' ? ' – ' . $ctTagline : '')));

  if($homepageFeatured['is_show'] == 'y'){
    if($homepageFeatured['featured_type'] == 'c1'){
      include locate_template('template-parts/homepage/featured-post.php', false, false);
    } else if($homepageFeatured['featured_type'] == 'c2'){
      echo '<div class="featured-homepage featured-shortode">';
      echo do_shortcode($homepageFeatured['shortcode']);
      echo '</div>';
    }
  }
  // Các khối render ngay phía server (cùng hàm với AJAX ở inc/post/ajax.php) để nội dung
  // và link bài có sẵn trong HTML (SEO), không tốn 1 request admin-ajax/khối và không lỗi
  // nonce hết hạn khi trang được page-cache. home_GetSections() đã lọc khối "chỉ admin".
  foreach ($homepageSection as $index => $section) {
    printf('<div class="homepage-section" data-ct-section="%d">', (int) $index);
    echo homepage_tabs_template_get($section); // phpcs:ignore — template output, self-escaping
    echo '</div>';
  }
  ?>
</div>
<?php if ($homepageGeneral['showsidebar'] == 'y') : ?>
<div id="ct-sidebar" class="sidebar-<?php echo $homepageGeneral['sidebar']; ?>">
  <?php if (is_active_sidebar('ct-widget-homepage')) : ?>
  <div class="sidebar-content">
    <?php dynamic_sidebar('ct-widget-homepage'); ?>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php get_footer();