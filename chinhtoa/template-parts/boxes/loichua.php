<?php
$loiChuaData = ulti_show_5phutloichua();
// ctprint(is_front_page(), 'is_front_page');
// ctprint(is_archive(), 'is_archive');
// ctprint($loiChuaData, 'loiChuaData');
if (!isset($loiChuaData['is_show']) || $loiChuaData['is_show'] != 'y') {
  return;
}

// Chỉ hiện ở đúng nơi được bật: Trang chủ / trang Chuyên mục (và lưu trữ) / trang Bài viết.
// Các trang khác (tìm kiếm, danh sách blog…) không hiện box.
if (is_front_page()) {
  $showKey = 'showinhomepage';
} else if (is_archive()) {
  $showKey = 'showincat';
} else if (is_singular()) {
  $showKey = 'showinsingle';
} else {
  return;
}
if (!isset($loiChuaData[$showKey]) || $loiChuaData[$showKey] != 'y') {
  return;
}

// Chuyên mục lưu dạng "12,15": lấy đủ ID (trước đây chỉ lấy ký tự đầu nên ID ≥ 10 bị sai).
$catIds = array_filter(array_map('absint', explode(',', ct_cats_str($loiChuaData['cats']))));
$loichuaArgs = array(
  'numberposts' => 1,
);
if (!empty($catIds)) {
  $loichuaArgs['category'] = implode(',', $catIds);
}

$trans = 'trans_loichua_' . md5(wp_json_encode($loichuaArgs));
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