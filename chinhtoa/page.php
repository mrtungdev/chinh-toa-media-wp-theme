<?php
/**
 * The template for displaying all pages
 *
 * Cùng khung với trang bài viết (single.php): thẻ nền trắng + thanh bên "Bài viết".
 * Thiết lập lấy từ hộp "Tuỳ chỉnh giao diện" của trang (nếu bật), mặc định theo
 * Thiết lập giao diện → Bài viết.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CTMedia
 */

get_header();
$postSettings = getCustomPostOption(get_the_ID());
if (!isset($postSettings)) {
    $postSettings = default_GetDefaultPost();
}
?>

<div id="ct-content" class="ct-single ct-page bg-white ct-shadow ct-bounding">
    <?php
    while (have_posts()) : the_post();
        include locate_template('template-parts/page/content-page.php');

        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
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
