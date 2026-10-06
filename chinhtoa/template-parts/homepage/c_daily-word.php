<?php
/**
 * Khối trang chủ temp7 "Lời Chúa hôm nay": bài của hôm nay trong chuyên mục nguồn
 * (chưa có thì lấy bài gần nhất trước đó). Widget "Lịch Lời Chúa" cùng chuyên mục
 * thay nội dung .ct__daily-body khi bấm ngày (assets/js/daily-word.js).
 *
 * Expects $section (trusted config from home_GetSections()).
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
  exit;
}

$lcCat   = ct_loichua_first_cat(isset($section['cats']) ? $section['cats'] : '');
$lcToday = ct_loichua_today();
$cc      = ct_home_sec_color_attrs($section);
$wrapClass = trim('ct__post ct__daily ct-shadow ct-bounding ' . ($cc['drop_bg'] ? '' : 'bg-white') . ' ' . $cc['class']);
?>
<section class="<?php echo esc_attr($wrapClass); ?>" style="<?php echo esc_attr($cc['style']); ?>" data-ct-daily-cat="<?php echo esc_attr($lcCat); ?>">
  <?php if (!empty($section['title'])) : ?>
  <div class="ct__post-header bottom-line">
    <h2 class="ct__post-title ct__daily-heading">
      <span class="ct__daily-icon" aria-hidden="true">✚</span>
      <?php echo esc_html($section['title']); ?>
    </h2>
  </div>
  <?php endif; ?>
  <div class="ct__daily-body" aria-live="polite">
    <?php echo ct_loichua_daily_card_html(ct_get_loichua_for_date($lcToday, $lcCat, true), $lcToday); // phpcs:ignore — self-escaping ?>
  </div>
</section>
