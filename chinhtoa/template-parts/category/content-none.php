<?php
/**
 * Không có bài viết — dùng cho archive.php (chuyên mục rỗng) và search.php (không có kết quả).
 * Ô tìm kiếm của trang tìm kiếm đã nằm ở khối tiêu đề phía trên.
 *
 * @package CTMedia
 */
?>
<div class="ct-empty bg-white ct-shadow ct-bounding">
  <?php if (is_search()) : ?>
  <p><?php esc_html_e('Không tìm thấy bài viết phù hợp. Hãy thử từ khoá khác hoặc ngắn hơn.', 'chinhtoa'); ?></p>
  <?php else : ?>
  <p><?php esc_html_e('Chuyên mục này chưa có bài viết.', 'chinhtoa'); ?></p>
  <?php endif; ?>
  <p><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('« Về trang chủ', 'chinhtoa'); ?></a></p>
</div>
