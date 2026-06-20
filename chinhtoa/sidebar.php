<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CTMedia
 */
?>
<div id="ct-sidebar" class="sidebar-right">
  <?php if (is_active_sidebar('dynamic_sidebar')) : ?>
  <div class="sidebar-content">
    <?php dynamic_sidebar('ct-widget-homepage'); ?>
  </div>
  <?php endif; ?>
</div>