<?php

function default_GetDefaultCategory()
{
  $res = array();
  $opt = ct_get_option_setting('cat_data');
  // $isSidebar = $opt['sidebar']['action_show'];
  // if ($isSidebar  == 'y') {
  //   $res['sidebar'] = $opt['sidebar']['y']['sidebar_pos'];
  // }
  return $opt;
  // return $res;
}

function default_GetDefaultPost()
{
  $res = array();
  $opt = ct_get_option_setting('post_data');
  // $isSidebar = $opt['sidebar']['action_show'];
  // if ($isSidebar  == 'y') {
  //   $res['sidebar'] = $opt['sidebar']['y']['sidebar_pos'];
  // }
  return $opt;
  // return $res;
}
