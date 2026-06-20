<?php


function getCustomPostOption($postId)
{
  $bga = ct_get_post_option($postId, 'post_custom');
  if (empty($bga) || $bga['action_show'] == 'n') {
    return;
  }
  return $bga['y'];
}


function getCustomCategoryOption($catId)
{
  $bga = ct_get_term_option($catId, 'category', 'cat_custom');
  if (empty($bga) || $bga['action_show'] == 'n') {
    return;
  }
  return $bga['y'];
}
