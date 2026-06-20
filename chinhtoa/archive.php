<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CTMedia
 */

get_header();
$currentCategory = get_queried_object();
$archiveSettings = getCustomCategoryOption($currentCategory->term_id);
if (!isset($archiveSettings)) {
    $archiveSettings = default_GetDefaultCategory();
}
$itemColumnClass = 'col';
switch ($archiveSettings['columns']) {
    case 'c1':
        $itemColumnClass = 'col-12';
        break;
    case 'c2':
        $itemColumnClass = 'col-sm-6';
        break;
    case 'c3':
        $itemColumnClass = 'col-sm-6 col-md-4';
        break;
    case 'c4':
        $itemColumnClass = 'col-sm-6 col-md-3';
        break;
    default:
        $itemColumnClass = 'col-sm-6';
        break;
}
$numberColumn = (int) preg_replace('/\D/', '', (string) $archiveSettings['columns']);

?>

<div id="ct-content" class="ct-cats <?php echo esc_attr($archiveSettings['columns']); ?>">

  <?php if (have_posts()) : ?>
  <?php
        echo '<div class="row">';
        while (have_posts()) :
            the_post();
            echo "<div class='$itemColumnClass'>";
            if ($archiveSettings['display_style'] == 'c1') {
                include locate_template('template-parts/homepage/c_post-item.php', false, false);
            } else {
                include locate_template('template-parts/homepage/c_post-item-image.php', false, false);
            }
            echo "</div>";
        endwhile;
        echo '</div>';
        bootstrap_pagination();
    else :
        get_template_part('template-parts/category/content', 'none');

    endif;
    ?>

</div>
<?php if ($archiveSettings['sidebar']['action_show'] == 'y') : ?>
<div id="ct-sidebar" class="sidebar-<?php echo esc_attr($archiveSettings['sidebar']['y']['sidebar_pos']); ?>">
  <?php if (is_active_sidebar('ct-widget-archive')) : ?>
  <div class="sidebar-content">
    <?php dynamic_sidebar('ct-widget-archive'); ?>
  </div>
  <?php endif; ?>
</div>
<?php endif; ?>

<?php
get_footer();