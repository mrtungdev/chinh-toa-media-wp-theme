<?php
// ctprint($section, 'Tem 6');
$contentArgs = array(
  'posts_per_page' => 10, //$section['num_post'],
);
if (!empty($section['cats'])) {
  $cats = ct_cats_str($section['cats']);
  $contentArgs['category'] = $cats;
}
// Khoá cache theo đúng tham số truy vấn: hai khối/tab trùng tên không còn dùng chung bài.
$trans = 'trans_t6_' . md5(wp_json_encode($contentArgs));

// Nhãn tab đầu: tiêu đề khối; để trống thì dùng tên chuyên mục đầu tiên.
$tabMainTitle = $section['title'];
if ($tabMainTitle === '' && !empty($section['cats'])) {
  $firstCat = get_category(absint(explode(',', ct_cats_str($section['cats']))[0]));
  $tabMainTitle = ($firstCat && !is_wp_error($firstCat)) ? $firstCat->name : '';
}
if ($tabMainTitle === '') {
  $tabMainTitle = __('Mới nhất', 'chinhtoa');
}
$tabList = (isset($section['tab_list']) && is_array($section['tab_list'])) ? $section['tab_list'] : array();

$cc = ct_home_sec_color_attrs($section);
$archiveSettings = isset($section['card']) ? $section['card'] : array();
$wrapClass = trim('ct__post ct__post-tabs ct__post-t6 ct-shadow ct-bounding ' . ($cc['drop_bg'] ? '' : 'bg-white') . ' ' . $cc['class']);
?>
<div class="<?php echo esc_attr($wrapClass); ?>" style="<?php echo esc_attr($cc['style']); ?>">
  <div class="ct__post-header bottom-line">
    <h2 class="ct__post-title">
      <?php
        $tabMainId = "tab_main";
        printf('<a data-tab="%s" class="active">%s</a>', esc_attr($tabMainId), esc_html($tabMainTitle));
      ?>
    </h2>
    <?php if (count($tabList) > 0) : ?>
    <div class="ct__post-header-tabs">
      <?php
        foreach ($tabList as $tabIndex=>$tab) {
          $tabHeaderId = "tab_" . absint($tabIndex);
          printf('<a data-tab="%s">%s</a>', esc_attr($tabHeaderId), esc_html(ct_home_tab_label($tab)));
        }
      ?>
    </div>
    <?php endif; ?>
  </div>
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
        foreach ($tabList as $tabIndex=>$tab) {
          $catsJoin = ct_cats_str($tab["tab_cats"]);
          $tabContentId = "tab_" . absint($tabIndex);
          ?>
    <div class="tab-data <?php echo esc_attr($tabContentId); ?>">
      <?php 
          $tabContentArgs = array(
            'posts_per_page' => 10, //$section['num_post'],
          );
          if (!empty($catsJoin)) {
            $tabContentArgs['category'] = $catsJoin;
          }
          $tab_trans = 'trans_t6tab_' . md5(wp_json_encode($tabContentArgs));
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