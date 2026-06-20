<?php
// HEADER IMAGE
$desktop = $CTheader['desktop'];
$desktopSrcset = '';
$tablet = $CTheader['tablet'];
$tabletSrcset = '';
$mobile = $CTheader['mobile'];
$mobileSrcset = '';
$link = $CTheader['link'] ?: CT_HOME_URL;
$target = $CTheader['target'] ?: '_blank';
$defaultSrc = '';
if (!empty($mobile)) {
  $mobileSrcset = esc_url($mobile) . ' 768w,';
  $defaultSrc = $mobile;
}
if (!empty($tablet)) {
  $tabletSrcset = esc_url($tablet) . ' 960w,';
  if (empty($defaultSrc)) {
    $defaultSrc = $tablet;
  }
}
if (!empty($desktop)) {
  $desktopSrcset = esc_url($desktop) . ' 1920w';
  if (empty($defaultSrc)) {
    $defaultSrc = $desktop;
  }
}
?>
<div class="header-image">
  <a href="<?php echo esc_url($link); ?>" target="<?php echo esc_attr($target); ?>">
    <img data-sizes="auto" data-src="<?php echo esc_url($defaultSrc); ?>" data-srcset="<?php echo esc_attr($mobileSrcset . $tabletSrcset . $desktopSrcset); ?>" class="lazyload blur-up" />
  </a>
</div>