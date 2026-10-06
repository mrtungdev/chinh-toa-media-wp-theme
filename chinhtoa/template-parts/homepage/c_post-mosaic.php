<?php
/**
 * Khối trang chủ temp8 "Lưới ảnh mosaic": bài đầu là ô lớn (2×2), các bài sau là ô
 * nhỏ; tiêu đề đè lên ảnh (tái dùng thẻ c_post-item-image.php + kiểu overlay của t5).
 * CSS lưới: assets/css/home-sections.css.
 *
 * Expects $section (trusted config from home_GetSections()).
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
  exit;
}

$contentArgs = array(
  'posts_per_page' => max(1, (int) $section['num_post']),
);
if (!empty($section['cats'])) {
  $contentArgs['category'] = ct_cats_str($section['cats']);
}
// Key gồm loại khối + chuyên mục + số bài (tránh trùng key theo tiêu đề của t1–t6).
$trans = 'trans_mosaic_' . md5(wp_json_encode($contentArgs));

$cc = ct_home_sec_color_attrs($section);
$archiveSettings = isset($section['card']) ? $section['card'] : array();
$wrapClass = trim('ct__post ct__post-t5 ct__post-mosaic ct-shadow ct-bounding ' . ($cc['drop_bg'] ? '' : 'bg-white') . ' ' . $cc['class']);
?>
<div class="<?php echo esc_attr($wrapClass); ?>" style="<?php echo esc_attr($cc['style']); ?>">
  <?php include locate_template('template-parts/homepage/_section-header.php', false, false); ?>
  <div class="ct__post-content">
    <?php $queryPosts = ct_get_posts($contentArgs, $trans);
    if (empty($queryPosts)) {
      echo '<div class="empty-post">' . esc_html__('Hiện chưa có bài viết nào ở danh mục này.', 'chinhtoa') . '</div>';
    }
    foreach ($queryPosts as $post) {
      setup_postdata($post);
      include locate_template('template-parts/homepage/c_post-item-image.php', false, false);
    }
    wp_reset_postdata(); ?>
  </div>
</div>
