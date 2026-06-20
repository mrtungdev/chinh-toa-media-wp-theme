<?php

function home_GetGeneral()
{
  $res = array();
  $opt = ct_get_option_setting('home_gen');
  $isSidebar = $opt['sidebar']['action_show'];
  $res['showsidebar'] = $isSidebar;
  if ($isSidebar  == 'y') {
    $res['sidebar'] = $opt['sidebar']['y']['sidebar_pos'];
  }
  return $res;
}

function home_GetFeatured()
{
  $res = array();
  $opt = ct_get_option_setting('home_featured');
  $isShow = $opt['action_show'];
  $res['is_show'] = $isShow;
  if ($isShow == 'n') {
    return $res;
  }
  $type = $opt['y']['featured_type'];
  $typeNo = $type['action_show'];
  $res['featured_type'] = $typeNo;
  if ($typeNo == 'c2') {
    $res['shortcode'] = $type['c2']['shortcode'];
  } else if ($typeNo == 'c1') {
    $c1_tinhot = $type['c1']['c1_tinhot'];
    $c1_tinhot_show = $c1_tinhot['action_show'];
    $res['show_tinhot'] = $c1_tinhot_show;
    if($c1_tinhot_show == 'y'){
      $res['c1_tinhot_cats']=$c1_tinhot['y']['cats'];
      $res['c1_tinhot_num_post']=$c1_tinhot['y']['num_post'];
      $res['c1_tinhot_num_date']=$c1_tinhot['y']['date'];
    }

    $c1_tieudem = $type['c1']['c1_tieudem'];
    $res['c1_tieudem_cats']=$c1_tieudem['cats'];
    $res['c1_tieudem_num_post']=$c1_tieudem['num_post'];
  }
  // return $opt;
  return $res;
}

function home_show_5phutloichua()
{
  $res = array();
  $opt = ct_get_option_setting('show_5phutloichua');
  $isShow = $opt['action_show'];
  $res['is_show'] = $isShow;
  if ($isShow == 'n') {
    return $res;
  }
  $res['title'] = $opt['y']['title'];
  $res['cats'] = $opt['y']['category'];
  return $res;
}

function home_GetSections()
{
  $res = array();
  $opt = ct_get_option_setting('home_sec');
  // echo ctprint($opt, 'home_GetSections');
  if (!is_array($opt)) {
    return $res;
  }
  foreach ($opt as $section) {
    if (!isset($section['content_type']['picker'])) {
      continue;
    }
    $type = $section['content_type']['picker'];
    $data = (isset($section['content_type'][$type]) && is_array($section['content_type'][$type])) ? $section['content_type'][$type] : array();
    switch ($type) {
      case 'temp6':

        $resPost = array();
        $postData = $data;
        $isPostDisplay = isset($postData['is_display']) ? $postData['is_display'] : 'n';
        $isPostAdmin = isset($postData['is_admin_only']) ? $postData['is_admin_only'] : 'n';
        $isCanAccessPost = true;
        if ($isPostAdmin == 'y') {
          if (!current_user_can('manage_options')) {
            $isCanAccessPost = false;
          }
        }
        if ($isPostDisplay == 'y' && $isCanAccessPost) {
          $resPost['type'] = $type;
          $resPost['title'] = isset($postData['title']) ? $postData['title'] : '';
          $resPost['is_display'] = $isPostDisplay;
          $resPost['is_admin_only'] = $isPostAdmin;
          $resPost['cats'] = isset($postData['cats']) ? $postData['cats'] : '';
          $resPost['tab_list'] = (isset($postData['tab_list']) && is_array($postData['tab_list'])) ? $postData['tab_list'] : array();
          array_push($res, $resPost);
        }
        break;

      case 'temp0':
        $resStatic = array();
        $resStatic['type'] = $type;
        $staticData = $data;
        $isStaticDisplay = isset($staticData['is_display']) ? $staticData['is_display'] : 'n';
        $isStaticAdmin = isset($staticData['is_admin_only']) ? $staticData['is_admin_only'] : 'n';
        $isCanAccessStatic = true;
        if ($isStaticAdmin == 'y') {
          if (!current_user_can('manage_options')) {
            $isCanAccessStatic = false;
          }
        }
        if ($isStaticDisplay == 'y' && $isCanAccessStatic) {
          $resStatic['title'] = isset($staticData['title']) ? $staticData['title'] : '';
          $resStatic['content'] = isset($staticData['content']) ? $staticData['content'] : '';
          $resStatic['is_display'] = $isStaticDisplay;
          $resStatic['is_admin_only'] = $isStaticAdmin;
          $isshow_readmore = isset($staticData['show_readmore']['action_show']) ? $staticData['show_readmore']['action_show'] : 'n';
          $resStatic['readmore'] = $isshow_readmore;
          $resStatic['default_style'] = isset($staticData['default_style']['is_default_style']) ? $staticData['default_style']['is_default_style'] : 'y';
          if ($isshow_readmore == 'y') {
            $resStatic['readmore_text'] = isset($staticData['show_readmore']['y']['readmore_text']) ? $staticData['show_readmore']['y']['readmore_text'] : '';
            $resStatic['readmore_link'] = isset($staticData['show_readmore']['y']['readmore_link']) ? $staticData['show_readmore']['y']['readmore_link'] : '';
            $resStatic['readmore_blank'] = isset($staticData['show_readmore']['y']['readmore_blank']) ? $staticData['show_readmore']['y']['readmore_blank'] : 'n';
          }
          if ($resStatic['default_style'] == 'n') {
            $resStatic['bgcolor'] = isset($staticData['default_style']['n']['bgcolor']) ? $staticData['default_style']['n']['bgcolor'] : '';
            $resStatic['textcolor'] = isset($staticData['default_style']['n']['textcolor']) ? $staticData['default_style']['n']['textcolor'] : '';
          }
          array_push($res, $resStatic);
        }
        break;
      default:
        $resPost = array();
        $postData = $data;
        $isPostDisplay = isset($postData['is_display']) ? $postData['is_display'] : 'n';
        $isPostAdmin = isset($postData['is_admin_only']) ? $postData['is_admin_only'] : 'n';
        $isCanAccessPost = true;
        if ($isPostAdmin == 'y') {
          if (!current_user_can('manage_options')) {
            $isCanAccessPost = false;
          }
        }
        if ($isPostDisplay == 'y' && $isCanAccessPost) {
          $resPost['type'] = $type;
          $resPost['title'] = isset($postData['title']) ? $postData['title'] : '';
          $resPost['is_display'] = $isPostDisplay;
          $resPost['is_admin_only'] = $isPostAdmin;
          $isshow_readmore = isset($postData['show_readmore']['action_show']) ? $postData['show_readmore']['action_show'] : 'n';
          $resPost['readmore'] = $isshow_readmore;
          if ($isshow_readmore == 'y') {
            $resPost['readmore_text'] = isset($postData['show_readmore']['y']['readmore_text']) ? $postData['show_readmore']['y']['readmore_text'] : '';
            $resPost['readmore_link'] = isset($postData['show_readmore']['y']['readmore_link']) ? $postData['show_readmore']['y']['readmore_link'] : '';
            $resPost['readmore_blank'] = isset($postData['show_readmore']['y']['readmore_blank']) ? $postData['show_readmore']['y']['readmore_blank'] : 'n';
          }
          $resPost['cats'] = isset($postData['cats']) ? $postData['cats'] : '';
          $resPost['num_post'] = isset($postData['num_post']) ? $postData['num_post'] : '';
          array_push($res, $resPost);
        }
        break;

    }
  }

  // return $opt;
  return $res;
}