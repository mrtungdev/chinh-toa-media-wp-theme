<?php
/**
 * Bảng lịch một tháng cho "Lời Chúa hôm nay". Ngày có bài là link tới bài (data-date
 * để assets/js/daily-word.js thay box trang chủ tại chỗ). Nút « » đổi tháng qua AJAX;
 * không có JS thì link ?ct_cal=Y-m vẫn chạy.
 *
 * Expects (from ct_loichua_calendar_html()):
 *   $calYear, $calMonth int, $calCat int, $calSelected string Y-m-d|'',
 *   $calMap array<int day, int post_id>.
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
  exit;
}

$calFirst    = mktime(12, 0, 0, $calMonth, 1, $calYear);
$calDays     = (int) gmdate('t', $calFirst);
$calFirstDow = (int) gmdate('w', $calFirst); // 0 = Chủ nhật
$calStart    = (int) get_option('start_of_week', 1);
$calToday    = ct_loichua_today();
$calYm       = sprintf('%04d-%02d', $calYear, $calMonth);
$calPrevYm   = gmdate('Y-m', mktime(12, 0, 0, $calMonth - 1, 1, $calYear));
$calNextYm   = gmdate('Y-m', mktime(12, 0, 0, $calMonth + 1, 1, $calYear));
// Không có bài đã đăng ở tháng tương lai (bài hẹn giờ chưa hiện) → khoá nút ».
$calHasNext  = $calNextYm <= substr($calToday, 0, 7);

$calWeekdays = array(
  0 => array(__('CN', 'chinhtoa'), __('Chủ nhật', 'chinhtoa')),
  1 => array(__('Hai', 'chinhtoa'), __('Thứ Hai', 'chinhtoa')),
  2 => array(__('Ba', 'chinhtoa'), __('Thứ Ba', 'chinhtoa')),
  3 => array(__('Tư', 'chinhtoa'), __('Thứ Tư', 'chinhtoa')),
  4 => array(__('Năm', 'chinhtoa'), __('Thứ Năm', 'chinhtoa')),
  5 => array(__('Sáu', 'chinhtoa'), __('Thứ Sáu', 'chinhtoa')),
  6 => array(__('Bảy', 'chinhtoa'), __('Thứ Bảy', 'chinhtoa')),
);
$calOrder = array();
for ($i = 0; $i < 7; $i++) {
  $calOrder[] = ($calStart + $i) % 7;
}
$calLead = ($calFirstDow - $calStart + 7) % 7; // ô trống đầu tháng
?>
<div class="ct-lc-cal" data-cat="<?php echo esc_attr($calCat); ?>" data-month="<?php echo esc_attr($calYm); ?>">
  <div class="ct-lc-cal__head">
    <a class="ct-lc-cal__nav" href="<?php echo esc_url(ct_loichua_calendar_month_url($calPrevYm)); ?>" data-ct-cal-month="<?php echo esc_attr($calPrevYm); ?>" rel="nofollow" aria-label="<?php esc_attr_e('Tháng trước', 'chinhtoa'); ?>">&lsaquo;</a>
    <span class="ct-lc-cal__caption" aria-live="polite">
      <?php
      /* translators: 1: tháng, 2: năm */
      printf(esc_html__('Tháng %1$d Năm %2$d', 'chinhtoa'), (int) $calMonth, (int) $calYear);
      ?>
    </span>
    <?php if ($calHasNext) : ?>
      <a class="ct-lc-cal__nav" href="<?php echo esc_url(ct_loichua_calendar_month_url($calNextYm)); ?>" data-ct-cal-month="<?php echo esc_attr($calNextYm); ?>" rel="nofollow" aria-label="<?php esc_attr_e('Tháng sau', 'chinhtoa'); ?>">&rsaquo;</a>
    <?php else : ?>
      <span class="ct-lc-cal__nav is-disabled" aria-hidden="true">&rsaquo;</span>
    <?php endif; ?>
  </div>
  <table class="ct-lc-cal__table">
    <thead>
      <tr>
        <?php foreach ($calOrder as $dow) : ?>
          <th scope="col" class="<?php echo $dow === 0 ? 'is-sunday' : ''; ?>" title="<?php echo esc_attr($calWeekdays[$dow][1]); ?>"><?php echo esc_html($calWeekdays[$dow][0]); ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php
        $calCell = 0;
        for ($i = 0; $i < $calLead; $i++, $calCell++) {
          echo '<td class="is-pad"></td>';
        }
        for ($day = 1; $day <= $calDays; $day++, $calCell++) {
          if ($calCell > 0 && $calCell % 7 === 0) {
            echo '</tr><tr>';
          }
          $ymd     = sprintf('%s-%02d', $calYm, $day);
          $dow     = ($calFirstDow + $day - 1) % 7;
          $classes = array();
          if ($dow === 0) {
            $classes[] = 'is-sunday';
          }
          if ($ymd === $calToday) {
            $classes[] = 'is-today';
          }
          if ($ymd === $calSelected) {
            $classes[] = 'is-selected';
          }
          if (isset($calMap[$day])) {
            $classes[] = 'has-post';
            printf(
              '<td class="%s"><a href="%s" data-date="%s" title="%s"%s>%d</a></td>',
              esc_attr(implode(' ', $classes)),
              esc_url(get_permalink($calMap[$day])),
              esc_attr($ymd),
              esc_attr(get_the_title($calMap[$day])),
              $ymd === $calToday ? ' aria-current="date"' : '',
              (int) $day
            );
          } else {
            printf('<td class="%s"><span>%d</span></td>', esc_attr(implode(' ', $classes)), (int) $day);
          }
        }
        while ($calCell % 7 !== 0) {
          echo '<td class="is-pad"></td>';
          $calCell++;
        }
        ?>
      </tr>
    </tbody>
  </table>
  <p class="ct-lc-cal__hint"><span class="ct-lc-cal__dot" aria-hidden="true"></span><?php esc_html_e('Bấm vào ngày được tô màu để xem bài của ngày đó.', 'chinhtoa'); ?></p>
</div>
