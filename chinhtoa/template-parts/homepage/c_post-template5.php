<?php
$contentArgs = array(
  'posts_per_page' => $section['num_post'],
);
if (!empty($section['cats'])) {
  $cats = ct_cats_str($section['cats']);
  $contentArgs['category'] = $cats;
}
$trans = "trans_" . vn_to_str($section['title']);

$cc = ct_home_sec_color_attrs($section);
$archiveSettings = isset($section['card']) ? $section['card'] : array();
$wrapClass = trim('ct__post ct__post-t5 ct-shadow ct-bounding ' . ($cc['drop_bg'] ? '' : 'bg-white') . ' ' . $cc['class']);
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
    ?>
    <?php }
    wp_reset_postdata(); ?>
  </div>
</div>
<?php
?>