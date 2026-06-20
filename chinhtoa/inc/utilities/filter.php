<?php
if (!defined('ABSPATH')) {
  exit;
}

function _bb_filter_packs_list($current_packs)
{
  /**
   * $current_packs is an array of pack names.
   * You should return which one you would like to show in the picker.
   */
  return array('font-awesome');
}

// (Unyson icon-pack filter removed — native options engine handles icons.)

// function _bb_add_custom_packs_list($default_packs)
// {
//     return array(
//         'far' => array(
//             'name' => 'far', // same as key
//             'title' => 'Font Awesome Regular',
//             'css_class_prefix' => 'far',
//             'css_file' => CT_THEME_DIR . '/assets/fontawesome/css/regular.min.css',
//             'css_file_uri' => CT_THEME_URI . '/assets/fontawesome/css/regular.min.css',
//         ),
//     );
// }
// add_filter('fw:option_type:icon-v2:packs', '_bb_add_custom_packs_list');

function defer_parsing_of_js($url)
{
  if (is_admin()) {
    return $url;
  }
  if (false === strpos($url, '.js')) {
    return $url;
  }

  if (strpos($url, 'jquery.js')) {
    return $url;
  }

  // write_log($url, 'URL');
  return $url . "' defer='defer";
}
// add_filter('clean_url', 'defer_parsing_of_js', 11, 1);

function bb_custom_excerpts($length = 60)
{
  echo wp_trim_words(esc_html(get_the_excerpt()), $length);
}

function bb_subcategory_hierarchy()
{
  $category = get_queried_object();

  $parent_id = $category->category_parent;

  $templates = array();

  if ($parent_id == 0) {
    // Use default values from get_category_template()
    $templates[] = "category-{$category->slug}.php";
    $templates[] = "category-{$category->term_id}.php";
    $templates[] = 'category.php';
  } else {
    // Create replacement $templates array
    $parent = get_category($parent_id);

    // Current first
    $templates[] = "category-{$category->slug}.php";
    $templates[] = "category-{$category->term_id}.php";

    // Parent second
    $templates[] = "category-{$parent->slug}.php";
    $templates[] = "category-{$parent->term_id}.php";
    $templates[] = 'category.php';
  }
  return locate_template($templates);
}

add_filter('category_template', 'bb_subcategory_hierarchy');


function remove_footer_admin()
{
  $credit = ct_brand('admin_footer_credit');
  if (!empty($credit)) {
    echo wp_kses_post($credit);
  }
}

add_filter('admin_footer_text', 'remove_footer_admin');

function change_footer_version()
{
  return '';
}
add_filter('update_footer', 'change_footer_version', 9999);


function findKey($array, $keySearch)
{
  foreach ($array as $key => $item) {
    if ($key == $keySearch) {
      return true;
    } elseif (is_array($item) && findKey($item, $keySearch)) {
      return true;
    }
  }
  return false;
}

add_filter('excerpt_length', function ($length) {
  return 20;
});

add_filter('the_content', function ($content) {
  return str_replace(array("<iframe", "</iframe>"), array('<div class="iframe-container embed-responsive embed-responsive-16by9"><iframe', "</iframe></div>"), $content);
});

add_filter('embed_oembed_html', function ($html, $url, $attr, $post_id) {
  if (strpos($html, 'youtube.com') !== false || strpos($html, 'youtu.be') !== false) {
    return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
  } else {
    return $html;
  }
}, 10, 4);

add_filter('embed_oembed_html', function ($code) {
  return str_replace('<iframe', '<iframe class="embed-responsive-item" ', $code);
});

function getPostImage($id)
{
  $image = get_the_post_thumbnail_url($id);
  return !empty($image) ? $image : CT_NO_IMAGE;
}