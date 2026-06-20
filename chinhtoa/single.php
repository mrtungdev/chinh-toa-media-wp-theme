<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package CTMedia
 */

get_header();
$postID = get_the_ID();
$postSettings = getCustomPostOption($postID);
if (!isset($postSettings)) {
    $postSettings = default_GetDefaultPost();
}
// var_dump($postSettings);
?>

<div id="ct-content" class="ct-single bg-white ct-shadow ct-bounding">
    <?php 
    while ( have_posts() ) : the_post();
        include( locate_template('template-parts/post/content.php') );
    endwhile;
    ?>
</div>

<?php if ($postSettings['sidebar']['action_show'] == 'y') : ?>
    <div id="ct-sidebar" class="sidebar-<?php echo esc_attr($postSettings['sidebar']['y']['sidebar_pos']); ?>">
        <?php if (is_active_sidebar('ct-widget-single')) : ?>
            <div class="sidebar-content">
                <?php dynamic_sidebar('ct-widget-single'); ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php

get_footer();
