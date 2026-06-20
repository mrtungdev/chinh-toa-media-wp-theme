<?php
$title = get_the_title($post->ID);
$link = get_permalink($post->ID);
$image = getPostImage($post->ID);
$date = get_the_date('', $post->ID);
$views = intval(get_post_meta($post->ID, 'views', true));
$isShowPostThumb = isset($archiveSettings['post_thumb']) ? $archiveSettings['post_thumb'] : 'y';
$isNotThumbClass = $isShowPostThumb == 'y' ? 'has-thumb' : '';
$isShowPostMeta = isset($archiveSettings['post_meta']) ? $archiveSettings['post_meta'] : 'y';
?>
<div class="ct__post-item-image <?php echo $isNotThumbClass; ?>">
  <div class="ct__post-item-image-wrap">
    <?php if ($isShowPostThumb == 'y') : ?>
      <figure class="image is-16by9">
        <img class="image-thumb lazyload" src="<?php echo esc_url(CT_PLACEHOLDER); ?>" data-src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
      </figure>
    <?php endif; ?>
    <div class="ct__post-item-data">
      <div class="post-title">
        <a href="<?php echo esc_url($link); ?>" rel="bookmark"><?php echo esc_html($title); ?></a>
      </div>
      <?php if ($isShowPostMeta == 'y') : ?>
        <div class="post-info">
          <div class="post-meta post-date">
            <?php echo esc_html($date); ?>
          </div>
          <div class="post-meta post-views">
            <?php echo esc_html($views); ?> <?php esc_html_e('lượt xem', 'chinhtoa'); ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>