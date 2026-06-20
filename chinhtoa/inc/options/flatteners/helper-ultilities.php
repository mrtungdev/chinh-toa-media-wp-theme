<?php


function ulti_show_5phutloichua()
{
  $res = array();
  $opt = ct_get_option_setting('show_5phutloichua');
  $isShow = $opt['action_show'];
  $res['is_show'] = $isShow;
  if ($isShow == 'y') {
    $res['title'] = $opt['y']['title'];
    $res['cats'] = $opt['y']['category'];
    $res['showinhomepage'] = $opt['y']['showinhomepage'];
    $res['showinsingle'] = $opt['y']['showinsingle'];
    $res['showincat'] = $opt['y']['showincat'];
  }
  return $res;
  // return $opt;
}
function ulti_gioithanhle()
{
  $res = array();
  $opt = ct_get_option_setting('ulti_giothanhle');

  // return $res;
  return $opt;
}