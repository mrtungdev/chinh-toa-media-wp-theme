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

  if($homepageFeatured['is_show'] == 'y'){
    if($homepageFeatured['featured_type'] == 'c1'){
      include locate_template('template-parts/homepage/featured-post.php', false, false);
    } else if($homepageFeatured['featured_type'] == 'c2'){
      echo '<div class="featured-homepage featured-shortode">';
      echo do_shortcode($homepageFeatured['shortcode']);
      echo '</div>';
    }
  }
  // ctprint($homepageSection, 'homepageSection');
  
  // Each section is rendered over AJAX. We emit only its INDEX; the AJAX
  // handler re-reads the section config from options server-side (see
  // inc/post/ajax.php) so no section data round-trips through the browser.
  foreach (array_keys($homepageSection) as $index) {
    printf('<div class="homepage-dynamic-ajax" data-ct-section="%d"></div>', (int) $index);
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