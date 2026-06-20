<?php
// ctprint($section, 'Tem 6');
$contentArgs = array(
  'posts_per_page' => 10, //$section['num_post'],
);
if (!empty($section['cats'])) {
  $cats = ct_cats_str($section['cats']);
  $contentArgs['category'] = $cats;
}
$trans = "trans_" . vn_to_str($section['title']);

?>
<div class="ct__post ct__post-tabs ct__post-t6 bg-white ct-shadow ct-bounding">
  <?php if ($section['title'] != '') : ?>
  <div class="ct__post-header bottom-line">
    <h2 class="ct__post-title">
      <?php
        $tabMainId = "tab_main";
        $tabMainTitle = $section["title"];
        printf('<a data-tab="%s" class="active">%s</a>', esc_attr($tabMainId), esc_html($tabMainTitle));
      ?>
    </h2>
    <?php if (count($section['tab_list']) > 0) : ?>
    <div class="ct__post-header-tabs">
      <?php
        foreach ($section['tab_list'] as $tabIndex=>$tab) {
          $catsJoin = ct_cats_str($tab["tab_cats"]);
          $tabTitle = $tab["tab_title"];
          $tabHeaderId = "tab_".$tabIndex.$catsJoin;
          printf('<a data-tab="%s">%s</a>', esc_attr($tabHeaderId), esc_html($tabTitle));
        }
      ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <div class="ct__post-content">
    <div class="tab-data tab_main active">
      <?php 
      // ctprint($contentArgs, 'queryPosts');
      $queryPosts = ct_get_posts($contentArgs, $trans);
      if (empty($queryPosts)) {
        echo '<div class="empty-post">' . esc_html__('Hiện chưa có bài viết nào ở danh mục này.', 'chinhtoa') . '</div>';
      }
      $post2Stt = 0;
      foreach ($queryPosts as $post) {
        $post2Stt += 1;
        setup_postdata($post);
        if ($post2Stt == 1) {
          echo '<div class="ct__post-content-left">';
          include locate_template('template-parts/homepage/c_post-item.php', false, false);
          echo '</div>';
        } else {
          if ($post2Stt == 2) {
            echo '<div class="ct__post-content-right check-overflow">';
          }
          include locate_template('template-parts/homepage/c_post-item.php', false, false);
        }
      }
      if($post2Stt > 1){
        echo '</div>';
      }
      wp_reset_postdata(); 
      ?>
    </div>
    <?php 
        foreach ($section['tab_list'] as $tabIndex=>$tab) {
          $catsJoin = ct_cats_str($tab["tab_cats"]);
          $tabTitle = $tab["tab_title"];
          $tabContentId = "tab_".$tabIndex.$catsJoin;
          ?>
    <div class="tab-data <?php echo esc_attr($tabContentId); ?>">
      <?php 
          $tabContentArgs = array(
            'posts_per_page' => 10, //$section['num_post'],
          );
          if (!empty($catsJoin)) {
            $tabContentArgs['category'] = $catsJoin;
          }
          $tab_trans = "trans_" . vn_to_str($tabTitle);
          $tabQueryPosts = ct_get_posts($tabContentArgs, $tab_trans);
          // ctprint($tabQueryPosts, 'tabQueryPosts');
          // var_dump($tabQueryPosts);
          if (empty($tabQueryPosts)) {
            echo '<div class="empty-post">' . esc_html__('Hiện chưa có bài viết nào ở danh mục này.', 'chinhtoa') . '</div>';
          }
          $post2Stt = 0;
          foreach ($tabQueryPosts as $post) {
            $post2Stt += 1;
            setup_postdata($post);
            if ($post2Stt == 1) {
              echo '<div class="ct__post-content-left">';
              include locate_template('template-parts/homepage/c_post-item.php', false, false);
              echo '</div>';
            } else {
              if ($post2Stt == 2) {
                echo '<div class="ct__post-content-right check-overflow">';
              }
              include locate_template('template-parts/homepage/c_post-item.php', false, false);
            }
          }
          if($post2Stt > 1){
            echo '</div>';
          }
          wp_reset_postdata(); 
      ?>
    </div>
    <?php 
        }
      ?>

  </div>
</div>