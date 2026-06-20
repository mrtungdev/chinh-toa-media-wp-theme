<?php
if (!defined('ABSPATH')) {
    exit;
}

function ct_get_post_thumb_img($postID, $srcset = false, $size = 'small', $class = 'lazyload blur-up')
{
    $res = '';
    if ($srcset) {
        $post = get_post($postID);
        if (!$post) {
            return '';
        }
        $title = isset($post->post_title) ? $post->post_title : 'hình ảnh';
        $post_thumbnail_id = get_post_thumbnail_id($post);
        if ($post_thumbnail_id) {
            $smallSrc  = wp_get_attachment_image_src($post_thumbnail_id, 'small');
            $mediumSrc = wp_get_attachment_image_src($post_thumbnail_id, 'medium');
            $largeSrc  = wp_get_attachment_image_src($post_thumbnail_id, 'large');
            $smallURL  = $smallSrc ? $smallSrc[0] : '';
            $mediumURL = $mediumSrc ? $mediumSrc[0] : '';
            $largeURL  = $largeSrc ? $largeSrc[0] : '';
            $res = '<img src="' . $smallURL . '"';
            $res .= ($mediumURL && $largeURL ? ' srcset="' : ''); // open srcset
            $res .= ($mediumURL ? $mediumURL . ' 690w' : '');
            $res .= ($mediumURL && $largeURL ? ', ' : '');
            $res .= ($largeURL ? $largeURL . ' 960w' : '');
            $res .= ($mediumURL && $largeURL ? '"' : ''); // close srcset
            $res .= ($class ? ' class="' . esc_attr($class) . '"' : '');
            $res .= ' title="' . $title . '" alt="' . $title . '"';
            $res .= ' sizes="auto" data-sizes="auto">';
            return $res;
        } else {
            return '';
        }
    } else {
        $res = get_the_post_thumbnail($postID, $size);
    }

    return $res;
}

function ct_get_terms($postID, $tax, $isTermMeta = false, $containerOpen = '', $containerClose = '', $class = '')
{
    $terms = wp_get_post_terms($postID, $tax);
    if (!$terms) {
        return;
    }
    //Open
    $html = isset($containerOpen) ? $containerOpen : '';
    foreach ($terms as $term) {
        $term_link = get_term_link($term);
        $term_style = '';
        $term_name = $term->name;
        $class = str_replace(' with-icon', '', $class);
        if ($isTermMeta) {
            $termMeta = ct_get_term_option($term->term_id, $tax);
            if ($termMeta) {
                // bb_print($termMeta, $term->name);
                $iconArr = array_key_exists('icon', $termMeta) ? $termMeta['icon'] : '';
                $iconcolor = array_key_exists('iconcolor', $termMeta) ? $termMeta['iconcolor'] : '';
                $backgroundcolor = array_key_exists('backgroundcolor', $termMeta) ? $termMeta['backgroundcolor'] : '';
                $textcolor = array_key_exists('textcolor', $termMeta) ? $termMeta['textcolor'] : '';
                if (is_array($iconArr) && isset($iconArr['type']) && $iconArr['type'] != 'none') {
                    $class .= ' with-icon';
                    if ($iconArr['type'] == 'icon-font') {
                        $iconHTML = "<div class='bb-loaihinh-icon icon-font'>";
                        $iconHTML .= "<i class='{$iconArr['icon-class']}' style='color: {$iconcolor}; fill: {$iconcolor};'></i>";
                        $iconHTML .= '</div>';
                        $term_name = $iconHTML . '<span>' . $term->name . '</span>';
                    } elseif ($iconArr['type'] == 'custom-upload') {
                        $iconHTML = "<div class='bb-loaihinh-icon custom-font'>";
                        $iconHTML .= "<img src='{$iconArr['url']}' alt='{$term->name}' style='color: {$iconcolor}; fill: {$iconcolor};'>";
                        $iconHTML .= '</div>';
                        $term_name = $iconHTML . '<span>' . $term->name . '</span>';
                    }
                }
                $term_style = "style='background-color: {$backgroundcolor}; color: {$textcolor};'";
            }
        }
        $html .= '<a href="' . esc_url($term_link) . '" title="' . $term->name . '" class="bb-term ' . $class . '" ' . $term_style . ' rel="tag">' . $term_name . '</a>';
    }
    //Close
    $html .= isset($containerOpen) ? $containerClose : '';
    return $html;
}

function ct_get_post_list_terms($postID, $taxonomy, $class = '')
{
    $terms = wp_get_post_terms($postID, $taxonomy, array("fields" => "ids"));
    $result = '';
    if ($terms) {
        $result = '<ul class="' . $class . '">';

        $terms = trim(implode(',', (array) $terms), ' ,');
        $result .= wp_list_categories('echo=0&separator=&title_li=&taxonomy=' . $taxonomy . '&include=' . $terms);

        $result .= '</ul>';
    }
    return wpautop(trim($result));
}
