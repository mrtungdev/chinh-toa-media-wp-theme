<?php

function hot_GetData()
{
  $res = array();
  $opt = ct_get_option_setting('hot_picker');

  $isShow = $opt['action_show'];
  $res['is_show'] = $isShow;
  if ($isShow  == 'n') {
    return $res;
  }
  $pickerData = $opt['y']['hot_setting'];
  $fromtime = $pickerData['displaytime']['from'];
  $totime = $pickerData['displaytime']['to'];
  $today = current_time('Y/m/d H:i');
  $date1 = date_format(date_create($fromtime), 'Y/m/d H:i');
  $date2 = date_format(date_create($totime), 'Y/m/d H:i');
  if (!isBetweenDates($today, $date1, $date2)) {
    $res['is_show'] = 'n';
    return $res;
  }
  $res['fromtime'] = $fromtime;
  $res['totime'] = $totime;
  $res['title'] = $pickerData['title'];
  $res['noidung'] = $pickerData['noidung'];
  $res['islive'] = $pickerData['islive'];
  $res['classes'] = $pickerData['classes'];
  // return $opt;
  return $res;
}
