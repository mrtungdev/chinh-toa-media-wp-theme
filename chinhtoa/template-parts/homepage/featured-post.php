<?php
$trendingArgs = array();
$trendingTrans = "trans_homepage_featured_trending";
if($homepageFeatured['show_tinhot']=='y'){
  $trendingArgs = array(
    'posts_per_page' => $homepageFeatured['c1_tinhot_num_post'],
    'meta_key'=>'views',
    'orderby' => 'views',
    'order' => 'DESC',
  );

  if (!empty($homepageFeatured['c1_tinhot_cats'])) {
    $tinhotCats = ct_cats_str($homepageFeatured['c1_tinhot_cats']);
    $trendingArgs['category'] = $tinhotCats;
  }
  if($homepageFeatured['c1_tinhot_num_date']=='day'){
    $today = getdate();
    $trendingArgs['date_query'] = array(array('year'  => $today['year'],'month' => $today['mon'],'day'   => $today['mday']));
  } else {
    $trendingArgs['date_query'] = array(array('year'  => date( 'Y' ), 'week' => date( 'W' )));
  }
}

$tieudiemArgs = array(
  'posts_per_page' => $homepageFeatured['c1_tieudem_num_post'],
);
if (!empty($homepageFeatured['c1_tieudem_cats'])) {
  $tieudiemCats = ct_cats_str($homepageFeatured['c1_tieudem_cats']);
  $tieudiemArgs['category'] = $tieudiemCats;
}

$tieudiemTrans = "trans_homepage_featured_tieudiem";

?>

<div class="featured-homepage featured-posts bg-white ct-shadow ct-bounding">
  <!-- TRENDING -->
  <?php if ($homepageFeatured['show_tinhot'] == 'y') : ?>
  <?php $trendingQueryPosts = ct_get_posts($trendingArgs, $trendingTrans); 
  ?>
  <?php if (!empty($trendingQueryPosts)) : ?>
  <div class="trending-posts">
    <div class="trending-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
        <path fill="currentColor"
          d="M16.0001988,3.52649756 C16.4216437,3.52649756 16.7635709,3.18502321 16.7635709,2.76324878 C16.7635709,2.34187185 16.4216437,2 16.0001988,2 C7.17768526,2 0,9.17771871 0,18 C0,19.8103306 0.300180183,21.5860767 0.891793528,23.2775473 C1.17209418,24.0805486 1.52197304,24.8704316 1.93188796,25.6265249 C2.02889972,25.8054114 2.12988753,25.9831051 2.23485119,26.1592089 C2.54854937,26.6875203 2.89524751,27.2007255 3.26898175,27.6912719 C3.26937931,27.6912719 3.26937931,27.6916694 3.26937931,27.6916694 C6.32525313,31.7007131 10.965124,34 15.9998012,34 C24.8223147,34 32,26.8222813 32,18 C32,14.3523069 30.7400385,10.7837213 28.4519103,7.95175036 C26.1979748,5.16191702 23.0411133,3.18939601 19.5629993,2.3983204 C19.1518916,2.30529951 18.7427719,2.56210087 18.6493383,2.97314219 C18.5559048,3.38418341 18.8131453,3.79323713 19.224253,3.88705302 C22.3691868,4.60220125 25.2246754,6.38669277 27.2643101,8.91097914 C29.3337641,11.4722354 30.4728583,14.7001416 30.4728583,18 C30.4728583,25.98072 23.9802199,32.4735024 15.9998012,32.4735024 C11.673231,32.4735024 7.66950359,30.5951949 4.90983411,27.2973242 L10.0792942,22.3644314 L11.5312916,23.9294889 C12.2437722,24.698303 13.2540473,25.150687 14.3040815,25.1713583 C15.3533205,25.1904395 16.3806921,24.7762181 17.1186184,24.038411 L21.98432,19.2299437 L23.2939803,20.5389948 C23.6621482,20.9075008 24.2108219,21.0164228 24.6915077,20.8172626 C25.1725912,20.6181023 25.4835062,20.1529976 25.4835062,19.6322393 L25.4835062,12.9430793 C25.4835062,12.0935676 24.7920979,11.4022709 23.9424489,11.4022709 L17.2534013,11.4022709 C16.7325588,11.4022709 16.2677766,11.7131357 16.0681866,12.1937439 C15.8689942,12.6747497 15.9779338,13.2233347 16.3461017,13.5914433 L17.6486053,14.894134 L14.4416475,18.053666 L13.0238429,16.5235907 C12.3117599,15.755174 11.3428341,15.3147159 10.2959806,15.2833114 C9.24753684,15.2499192 8.25396037,15.6315436 7.49694975,16.3542448 L1.9819842,21.6174812 C1.67941853,20.4423961 1.52634645,19.2303412 1.52634645,18 C1.52674412,10.01928 8.01938245,3.52649756 16.0001988,3.52649756 Z M8.55175497,17.4585704 C9.01057333,17.0200999 9.61292162,16.7911253 10.2498602,16.8090139 C10.8852084,16.8280951 11.4728459,17.0952322 11.9046282,17.561132 L13.8571908,19.6688117 C13.9975399,19.8202689 14.1935516,19.9081219 14.3999006,19.9128922 C14.6054544,19.915675 14.8058395,19.838555 14.9533453,19.6934582 L18.8958439,15.8092375 C19.1411567,15.567542 19.2767348,15.2447514 19.2779275,14.9000968 C19.279518,14.5558399 19.1459279,14.2322542 18.9026029,13.9885711 L17.8426291,12.9287684 L23.9428465,12.9287684 C23.9507983,12.9287684 23.9571597,12.9351288 23.9571597,12.9430793 L23.9571597,19.0431067 L22.8928124,17.9785336 C22.3958253,17.4816269 21.5843449,17.4792417 21.0841771,17.9733658 L16.0423433,22.9559492 C15.5926694,23.4055505 14.9684538,23.6551964 14.3331055,23.6448608 C13.6965646,23.6325374 13.0834814,23.358245 12.6509039,22.8919476 L10.672498,20.7588263 C10.5337392,20.6089593 10.340113,20.5211062 10.1361496,20.5151433 C9.93258367,20.5083854 9.7333913,20.5847102 9.58548796,20.7254342 L3.98663108,26.0681756 C3.3886563,25.1801039 2.890874,24.2300181 2.50242898,23.2318318 L8.55175497,17.4585704 Z"
          transform="translate(0 -2)" />
      </svg>
    </div>
    <?php
          foreach ($trendingQueryPosts as $trendingPost) {
            $tinhotLink = get_permalink($trendingPost->ID);
            $tinhotTitle = get_the_title($trendingPost->ID);
            echo '<a href="' . esc_url($tinhotLink) . '" rel="bookmark" class="trending-post">' . esc_html($tinhotTitle) . '</a>';
          }
      ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>
  <!-- / TRENDING -->
  <?php 
    $tieudiemQueryPosts = ct_get_posts($tieudiemArgs, $tieudiemTrans);
    $headlinePost = array_shift($tieudiemQueryPosts);
    
    $tieudiemPostsLenght = count($tieudiemQueryPosts);
    $tieudiemPostRow2 = array_splice($tieudiemQueryPosts, 0, 4);
    // $tieudiemPostRow2 = array_splice($tieudiemQueryPosts, $tieudiemPostsLenght - 4, $tieudiemPostsLenght);
    // $tieudiemPostRow2Lenght = count($tieudiemPostRow2);

    $headlinetitle = get_the_title($headlinePost->ID);
    $headlinelink = get_permalink($headlinePost->ID);
    $headlineimage = getPostImage($headlinePost->ID);
    $headlineexcerpt = get_the_excerpt($headlinePost->ID);
  ?>
  <div class="breaking-news">
    <!-- ROW 1 -->
    <div class="breaking-news-row">
      <div class="headline-news">
        <div class="headline-thumb">
          <a href="<?php echo esc_url($headlinelink); ?>" rel="bookmark">
            <figure class="image is-16by9">
              <img class="lazyload" src="<?php echo esc_url(CT_PLACEHOLDER); ?>" data-src="<?php echo esc_url($headlineimage); ?>"
                alt="<?php echo esc_attr($headlinetitle); ?>">
            </figure>
          </a>
        </div>
        <div class="headline-data">
          <div class="headline-title">
            <a href="<?php echo esc_url($headlinelink); ?>" rel="bookmark"><?php echo esc_html($headlinetitle); ?></a>
          </div>
          <div class="headline-excerpt">
            <?php echo wp_kses_post($headlineexcerpt); ?>
          </div>
        </div>
      </div>
      <div class="other-news">
          <?php
          foreach ($tieudiemQueryPosts as $post) {
            setup_postdata($post);
            include locate_template('template-parts/homepage/c_post-item.php', false, false);
            // $tdPostLink =get_permalink($post->ID);
            // $tdPostTitle = get_the_title($post->ID);
            // echo '<li><a href="' . $tdPostLink . '" rel="bookmark" class="trending-post"><span>'. $tdPostTitle .'</span></a></li>';
          }
          ?>
      </div>
    </div>
    <!-- / ROW 1 -->
    <div class="breaking-news-row row-box box-4">
      <?php
        foreach ($tieudiemPostRow2 as $post) {
          setup_postdata($post);
          include locate_template('template-parts/homepage/c_post-item.php', false, false);
        }
        wp_reset_postdata();
      ?>
    </div>
  </div>

</div>