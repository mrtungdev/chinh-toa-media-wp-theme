<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package CTMedia
 */

get_header();
?>
<div id="ct-content" class="page-not-found">
	<div class="page-not-found-content">

		<h1><?php esc_html_e('KHÔNG TÌM THẤY!', 'chinhtoa'); ?></h1>
		<p><?php esc_html_e('Nội dung bạn cần tìm không có.', 'chinhtoa'); ?></p>
		<?php get_search_form(); ?>
		<p><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('« Về trang chủ', 'chinhtoa'); ?></a></p>
	</div>
</div>
<?php
get_footer();
