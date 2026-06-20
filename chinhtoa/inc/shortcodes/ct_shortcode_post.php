<?php 
function ct_shortcode_postlist($atts = array()){
  $atts = shortcode_atts(array(
     'cats'     => '',
     'numposts' => '6',
    ), $atts, 'ct_postlist');

  $cats       = $atts['cats'];
  $numPerPage = empty($atts['numposts']) ? 10 : (int) $atts['numposts'];

  $ct_shortcodeArgs = array(
    'posts_per_page' => $numPerPage,
  );
  if (!empty($cats)) {
    $ct_shortcodeArgs['category'] = $cats;
  }
  $trans = "trans_ct_shortcode_postlist_" . $numPerPage . "cats" . $cats;
  $queryPosts = ct_get_posts($ct_shortcodeArgs, $trans);

  $output  = '<div class="ct-shortcode-postlist">';
  if (empty($queryPosts)) {
    $output .= '<div class="empty-post">' . esc_html__('Hiện chưa có bài viết nào ở danh mục này.', 'chinhtoa') . '</div>';
  }
  foreach ($queryPosts as $post) {
    $postTitle   = get_the_title($post->ID);
    $postLink    = get_permalink($post->ID);
    $postImage   = getPostImage($post->ID);
    $postExcerpt = get_the_excerpt($post->ID);
    $date        = get_the_date('', $post->ID);
    $views       = intval(get_post_meta($post->ID, 'views', true));

    $output .= '<div class="ct__post-item">';
      $output .= '<div class="ct__post-item-thumb">';
        $output .= '<a href="' . esc_url($postLink) . '" rel="bookmark">';
          $output .= '<figure class="image is-16by9">';
            $output .= '<img class="lazyload" src="' . esc_url(CT_PLACEHOLDER) . '" data-src="' . esc_url($postImage) . '" alt="' . esc_attr($postTitle) . '">';
          $output .= '</figure>';
        $output .= '</a>';
      $output .= '</div>';
      $output .= '<div class="ct__post-item-data">';
        $output .= '<div class="post-info">';
          $output .= '<div class="post-meta post-date">';
            $output .= esc_html($date);
          $output .= '</div>';
          $output .= '<div class="post-meta post-views">';
            $output .= '&nbsp;&nbsp;-&nbsp;&nbsp;' . esc_html($views) . ' ' . esc_html__('lượt xem', 'chinhtoa');
          $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="post-title">';
          $output .= '<a href="' . esc_url($postLink) . '" rel="bookmark">' . esc_html($postTitle) . '</a>';
        $output .= '</div>';
        $output .= '<div class="post-excerpt">';
          $output .= wp_kses_post($postExcerpt);
        $output .= '</div>';
      $output .= '</div>';
    $output .= '</div>';
  }
  $output .= '</div>';
  return $output;
}

add_shortcode('ct_postlist', 'ct_shortcode_postlist');

?>