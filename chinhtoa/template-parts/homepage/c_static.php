<?php 

$tempClasses = '';
$tempStyle = '';
if ($section['default_style'] == 'y') {
  $tempClasses = 'bg-primary text-white';
} else {
  $bgcolor   = !empty($section['bgcolor']) ? ct_normalize_hex($section['bgcolor']) : '';
  $textcolor = !empty($section['textcolor']) ? ct_normalize_hex($section['textcolor']) : '';
  $bgcolor   = $bgcolor !== '' ? $bgcolor : '#FFF';
  $textcolor = $textcolor !== '' ? $textcolor : '#000';
  $tempStyle = "background-color: {$bgcolor}; color: {$textcolor}";
}

?>

<div class="c__static ct-shadow ct-bounding <?php echo esc_attr($tempClasses); ?>" style="<?php echo esc_attr($tempStyle); ?>">
  <?php if ($section['title'] != '') : ?>
  <div class="c__static-header <?php echo !empty($section['content']) ? 'bottom-line' : ''; ?>">
    <h2 class="c__static-title">
      <?php echo esc_html($section['title']); ?>
    </h2>
    <?php if ($section['readmore'] == 'y') : ?>
    <div class="c__static-readmore">
      <a href="<?php echo esc_url($section['readmore_link']); ?>"
        target="<?php echo $section['readmore_blank'] == 1 ? '_blank' : '_self'; ?>">
        <?php echo esc_html($section['readmore_text']); ?>
      </a>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <?php if (!empty($section['content'])) : ?>
  <div class="c__static-content">
    <?php echo do_shortcode($section['content']); ?>
  </div>
  <?php endif; ?>
</div>