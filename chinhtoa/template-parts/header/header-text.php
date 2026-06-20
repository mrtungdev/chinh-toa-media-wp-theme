<?php
$headerBg = !empty($CTheader['bgcolor']) ? ct_normalize_hex($CTheader['bgcolor']) : '';
$headerBg = $headerBg !== '' ? $headerBg : 'transparent';
?>
<div class="header-text" style="background-color:<?php echo esc_attr($headerBg); ?>">
  <?php echo wp_kses_post($CTheader['text']); ?>
</div>