<?php
$contentArgs = array(
  'posts_per_page' => $section['num_post'],
);
if (!empty($section['cats'])) {
  $cats = ct_cats_str($section['cats']);
  $contentArgs['category'] = $cats;
}
$trans = "trans_" . vn_to_str($section['title']);

?>
<div class="ct__post ct__post-t5 bg-white ct-shadow ct-bounding">
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