<?php
/**
 * Shared homepage section header: title + optional "read more" link.
 *
 * Expects $section (trusted config from home_GetSections()). Used by the
 * c_post-template{1..5} layouts to avoid duplicating the header markup.
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
    exit;
}
if (empty($section['title'])) {
    return;
}
?>
<div class="ct__post-header bottom-line">
  <h2 class="ct__post-title">
    <?php echo esc_html($section['title']); ?>
  </h2>
  <?php if (!empty($section['readmore']) && $section['readmore'] === 'y') : ?>
  <div class="ct__post-readmore">
    <a href="<?php echo esc_url($section['readmore_link']); ?>"
      target="<?php echo (isset($section['readmore_blank']) && $section['readmore_blank'] == 1) ? '_blank' : '_self'; ?>">
      <?php echo esc_html($section['readmore_text']); ?>
    </a>
  </div>
  <?php endif; ?>
</div>
