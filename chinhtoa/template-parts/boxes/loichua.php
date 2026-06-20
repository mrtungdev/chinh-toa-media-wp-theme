<?php
$loiChuaData = ulti_show_5phutloichua();
// ctprint(is_front_page(), 'is_front_page');
// ctprint(is_archive(), 'is_archive');
// ctprint($loiChuaData, 'loiChuaData');
if (!isset($loiChuaData['is_show']) || $loiChuaData['is_show'] != 'y') {
  return;
}

if (is_front_page() && is_page_template()) {
  $isShowFront = $loiChuaData['showinhomepage'];
  if (!isset($isShowFront) || $isShowFront != 'y') {
    return;
  }
} else if (is_archive()) {
  $isShowCats = $loiChuaData['showincat'];
  if (!isset($isShowCats) || $isShowCats != 'y') {
    return;
  }
} else if(is_singular()){
  $isShowSingle = $loiChuaData['showinsingle'];
  if (!isset($isShowSingle) || $isShowSingle != 'y') {
    return;
  }
}

$cats = $loiChuaData['cats'];
$loichuaArgs = array(
  'numberposts' => 1,
);
if (!empty($cats[0])) {
  $loichuaArgs['category'] = $cats[0];
}

$trans = "trans_loichua";
$expireEndDate = strtotime('23:59:59') - time() + 1;
$loichuaQuery = ct_get_posts($loichuaArgs, $trans, $expireEndDate);
if (empty($loichuaQuery)) {
  return;
}
$loichuaexcerpt = get_the_excerpt($loichuaQuery[0]->ID);
$loichualink = get_permalink($loichuaQuery[0]->ID);
?>

<div id="ct__loichua" class="ct-mt">
  <div class="container">
    <div class="ct__loichua">
      <div class="ct__loichua-leftside"></div>
      <div class="ct__loichua-content <?php echo !empty($loiChuaData['title']) ? ' with-title' : '' ?>">
        <?php if (!empty($loiChuaData['title'])) : ?>
        <div class="ct__loichua-title">
          <?php echo esc_html($loiChuaData['title']); ?>
        </div>
        <?php endif; ?>
        <div class="ct__loichua-excerpt">
          <?php echo wp_kses_post($loichuaexcerpt); ?>
        </div>
        <div class="ct__loichua-readfull">
          <a href="<?php echo esc_url($loichualink); ?>"><?php esc_html_e('Suy niệm »', 'chinhtoa'); ?></a>
        </div>
      </div>
      <div class="ct__loichua-rightside"></div>
    </div>
  </div>
</div>