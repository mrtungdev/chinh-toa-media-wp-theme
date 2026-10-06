<?php
/**
 * Thẻ bài "Lời Chúa" của một ngày: ảnh trái, thông tin ngày + trích suy niệm phải.
 *
 * Expects (from ct_loichua_daily_card_html()):
 *   $lcPost WP_Post|null  Bài cần hiện; null = chưa có bài.
 *   $lcDate string        Ngày đang xem Y-m-d ('' nếu không rõ).
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
  exit;
}

if (!$lcPost) :
  $lcLabel = $lcDate !== '' ? wp_date('d/m/Y', strtotime($lcDate . ' 12:00:00')) : '';
  ?>
  <p class="ct__daily-empty">
    <?php
    if ($lcLabel !== '') {
      /* translators: %s: ngày dạng dd/mm/yyyy */
      printf(esc_html__('Chưa có bài cho ngày %s.', 'chinhtoa'), esc_html($lcLabel));
    } else {
      esc_html_e('Chưa có bài.', 'chinhtoa');
    }
    ?>
  </p>
  <?php
  return;
endif;

$lcLink     = get_permalink($lcPost);
$lcTitle    = get_the_title($lcPost);
$lcDayTitle = (string) get_post_meta($lcPost->ID, CT_LC_META_DAY_TITLE, true);
$lcSaint    = (string) get_post_meta($lcPost->ID, CT_LC_META_SAINT, true);
$lcGospel   = (string) get_post_meta($lcPost->ID, CT_LC_META_GOSPEL_REF, true);
$lcExcerpt  = get_the_excerpt($lcPost);
$lcThumb    = has_post_thumbnail($lcPost)
  ? get_the_post_thumbnail($lcPost, 'medium', array('class' => 'ct__daily-thumb', 'loading' => 'lazy', 'alt' => $lcTitle))
  : '';
// Không có ảnh → thẻ câu ghi nhớ (giấy da) của chính bài này.
$lcCard = ($lcThumb === '' && function_exists('ct_post_kind_loichua_html')) ? ct_post_kind_loichua_html($lcPost->ID) : '';
?>
<article class="ct__daily-card" data-date="<?php echo esc_attr(get_the_date('Y-m-d', $lcPost)); ?>">
  <?php if ($lcThumb !== '' || $lcCard !== '') : ?>
  <div class="ct__daily-media">
    <?php if ($lcThumb !== '') : ?>
      <a href="<?php echo esc_url($lcLink); ?>" tabindex="-1" aria-hidden="true"><?php echo $lcThumb; // core markup, escaped by WP ?></a>
    <?php else : ?>
      <?php echo $lcCard; // self-escaping render ?>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <div class="ct__daily-info">
    <p class="ct__daily-meta">
      <time datetime="<?php echo esc_attr(get_the_date('Y-m-d', $lcPost)); ?>"><?php echo esc_html(get_the_date('d/m/Y', $lcPost)); ?></time>
      <?php if ($lcDayTitle !== '') : ?>
        <span class="ct__daily-dayname"><?php echo esc_html($lcDayTitle); ?></span>
      <?php endif; ?>
    </p>
    <h3 class="ct__daily-title"><a href="<?php echo esc_url($lcLink); ?>"><?php echo esc_html($lcTitle); ?></a></h3>
    <?php if ($lcSaint !== '') : ?>
      <p class="ct__daily-saint"><?php echo esc_html($lcSaint); ?></p>
    <?php endif; ?>
    <?php if ($lcGospel !== '') : ?>
      <p class="ct__daily-gospel"><?php esc_html_e('Tin Mừng:', 'chinhtoa'); ?> <strong><?php echo esc_html($lcGospel); ?></strong></p>
    <?php endif; ?>
    <?php if ($lcExcerpt !== '') : ?>
      <p class="ct__daily-excerpt"><?php echo esc_html(wp_strip_all_tags($lcExcerpt)); ?></p>
    <?php endif; ?>
    <a class="ct__daily-more" href="<?php echo esc_url($lcLink); ?>">
      <?php esc_html_e('Đọc tiếp', 'chinhtoa'); ?> <span aria-hidden="true">→</span>
      <span class="visually-hidden"><?php echo esc_html($lcTitle); ?></span>
    </a>
  </div>
</article>
