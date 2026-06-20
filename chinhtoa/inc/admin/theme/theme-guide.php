<?php

/**
 * Trang "Giới Thiệu" của menu theme-options — Trung tâm Hướng dẫn.
 *
 * Hiển thị các mục hướng dẫn (nội dung Markdown trong docs/*.md) dạng accordion
 * collapse/expand. Logic render nằm ở guide-helpers.php; CSS gói gọn trong khối
 * <style> dưới đây (theo đúng pattern của dashboard.php).
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
  exit;
}

require_once CT_THEME_DIR . '/inc/admin/theme/guide-helpers.php';
?>
<style>
  .ct-guide { max-width: 900px; margin: 16px 0 32px; }
  .ct-guide-heading { font-size: 23px; margin: 6px 0 4px; }
  .ct-guide-intro { color: #646970; font-size: 14px; margin: 0 0 20px; }

  .ct-guide-section { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; margin-bottom: 12px; overflow: hidden; transition: border-color .15s, box-shadow .15s; }
  .ct-guide-section[open] { border-color: #2271b1; box-shadow: 0 1px 6px rgba(0, 0, 0, .06); }

  .ct-guide-summary { list-style: none; cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 14px 18px; font-size: 15px; font-weight: 600; color: #1d2327; user-select: none; }
  .ct-guide-summary::-webkit-details-marker { display: none; }
  .ct-guide-summary:hover { background: #f6f7f7; }
  .ct-guide-summary:focus-visible { outline: 2px solid #2271b1; outline-offset: -2px; }
  .ct-guide-summary .dashicons { color: #2271b1; font-size: 22px; width: 22px; height: 22px; }
  .ct-guide-title { flex: 1; }
  .ct-guide-summary::after { content: ""; flex: 0 0 auto; width: 8px; height: 8px; border-right: 2px solid #646970; border-bottom: 2px solid #646970; transform: rotate(45deg); transition: transform .15s ease; margin-right: 4px; }
  .ct-guide-section[open] > .ct-guide-summary::after { transform: rotate(-135deg); }

  .ct-guide-body { padding: 4px 22px 18px; color: #3c434a; font-size: 14px; line-height: 1.7; border-top: 1px solid #f0f0f1; }
  .ct-guide-body h2 { font-size: 17px; margin: 18px 0 8px; padding: 0; }
  .ct-guide-body h3 { font-size: 15px; margin: 16px 0 6px; }
  .ct-guide-body p { margin: 8px 0; }
  .ct-guide-body ul, .ct-guide-body ol { margin: 8px 0 8px 4px; padding-left: 22px; }
  .ct-guide-body li { margin: 4px 0; }
  .ct-guide-body a { color: #2271b1; text-decoration: none; }
  .ct-guide-body a:hover { text-decoration: underline; }
  .ct-guide-body code { background: #f6f7f7; padding: 1px 6px; border-radius: 4px; font-size: 13px; }
  .ct-guide-body blockquote { margin: 12px 0; padding: 8px 16px; border-left: 4px solid #2271b1; background: #f6f9fc; color: #50575e; border-radius: 0 4px 4px 0; }
  .ct-guide-body blockquote p { margin: 4px 0; }
  .ct-guide-body table { border-collapse: collapse; margin: 12px 0; }
  .ct-guide-body th, .ct-guide-body td { border: 1px solid #dcdcde; padding: 6px 10px; text-align: left; }
  .ct-guide-body hr { border: 0; border-top: 1px solid #f0f0f1; margin: 16px 0; }
</style>

<div class="ct-guide">
  <h1 class="ct-guide-heading"><?php echo esc_html__('Hướng dẫn sử dụng', 'chinhtoa'); ?></h1>
  <p class="ct-guide-intro"><?php echo esc_html__('Xin chào các Admin, Cộng tác viên! Nhấp vào từng mục bên dưới để xem hướng dẫn chi tiết.', 'chinhtoa'); ?></p>

  <?php
  $ct_guide_first = true;
  foreach (ct_guide_sections() as $ct_guide_section) {
    ct_guide_render_section($ct_guide_section, $ct_guide_first);
    $ct_guide_first = false;
  }
  ?>
</div>
