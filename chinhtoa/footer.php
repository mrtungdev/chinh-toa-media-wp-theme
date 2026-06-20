<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CTMedia
 */

$footerData = gen_GetFooter();
$numberWd   = ($footerData['display_widget'] == 'y') ? $footerData['widget_column'] : 0;
$footerBg   = ct_normalize_hex($footerData['bgcolor']);
$footerFg   = ct_normalize_hex($footerData['color']);
$footerStyle = 'padding: 2rem 0;';
if ($footerBg !== '') {
  $footerStyle .= ' background-color: ' . $footerBg . ';';
}
if ($footerFg !== '') {
  $footerStyle .= ' color: ' . $footerFg . ';';
}
?>
</div>
</div>
<div id="ct-scrolltop">
  <svg class="ct-icon ct-icon-arrow-up" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" style="vertical-align:-0.125em"><path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5"/></svg>
  <span><?php esc_html_e('ĐẦU TRANG', 'chinhtoa'); ?></span>
</div>
<?php 
  include locate_template('template-parts/part/float-social-sharing.php', false, false);
?>

<?php 
// ctprint(home_GetFeatured(), 'home_GetFeatured')
?>


<footer id="site-footer" class="site-footer" style="<?php echo esc_attr($footerStyle); ?>">
  <div class="container">
    <?php if ($footerData['display_widget'] == 'y') {
      echo '<div class="row footer-widget">';
      for ($i = 1; $i <= $numberWd; $i++) {  ?>
    <div class="col widget-area">
      <?php if (is_active_sidebar('ct-footer-' . $i)) : ?>
      <?php dynamic_sidebar('ct-footer-' . $i); ?>
      <?php endif; ?>
    </div>
    <?php }
      echo '</div>';
    } ?>
    <?php if (!empty($footerData['text'])) : ?>
    <div class="footer-content" style="padding: 1rem 0;">
      <?php echo wp_kses_post($footerData['text']); ?>
    </div>
    <?php endif; ?>
  </div>
</footer><!-- #colophon -->

</div><!-- #ct-app -->
<?php wp_footer(); ?>


</body>

</html>