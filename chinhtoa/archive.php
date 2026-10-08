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
$currentTermId   = (is_object($currentCategory) && isset($currentCategory->term_id)) ? $currentCategory->term_id : 0;
$archiveSettings = getCustomCategoryOption($currentTermId);
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

  <header class="ct-archive-header">
    <?php if (function_exists('yoast_breadcrumb')) : ?>
    <?php yoast_breadcrumb('<p id="breadcrumbs">', '</p>'); ?>
    <?php endif; ?>
    <h1 class="ct-archive-header__title"><?php echo esc_html(wp_strip_all_tags(get_the_archive_title())); ?></h1>
    <?php $archiveDesc = get_the_archive_description(); ?>
    <?php if ($archiveDesc) : ?>
    <div class="ct-archive-header__desc"><?php echo wp_kses_post($archiveDesc); ?></div>
    <?php endif; ?>
  </header>

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