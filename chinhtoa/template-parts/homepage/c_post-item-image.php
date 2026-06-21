<?php
$title = get_the_title($post->ID);
$link = get_permalink($post->ID);
$image = getPostImage($post->ID);
$date = get_the_date('', $post->ID);
$views = intval(get_post_meta($post->ID, 'views', true));

$showThumb   = isset($archiveSettings['post_thumb'])  ? $archiveSettings['post_thumb']  : 'y';
$metaGroup   = isset($archiveSettings['post_meta'])   ? $archiveSettings['post_meta']   : 'y'; // tương thích archive.php
$showDate    = isset($archiveSettings['post_date'])   ? $archiveSettings['post_date']   : $metaGroup;
$showViews   = isset($archiveSettings['post_views'])  ? $archiveSettings['post_views']  : $metaGroup;
$showAuthor  = isset($archiveSettings['post_author']) ? $archiveSettings['post_author'] : 'n';
$showInfo    = ($showAuthor === 'y' || $showDate === 'y' || $showViews === 'y');
$isNotThumbClass = $showThumb == 'y' ? 'has-thumb' : '';
?>
<div class="ct__post-item-image <?php echo $isNotThumbClass; ?>">
  <div class="ct__post-item-image-wrap">
    <?php if ($showThumb == 'y') : ?>
      <figure class="image is-16by9">
        <img class="image-thumb lazyload" src="<?php echo esc_url(CT_PLACEHOLDER); ?>" data-src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
      </figure>
    <?php endif; ?>
    <div class="ct__post-item-data">
      <div class="post-title">
        <a href="<?php echo esc_url($link); ?>" rel="bookmark"><?php echo esc_html($title); ?></a>
      </div>
      <?php if ($showInfo) : ?>
        <div class="post-info">
          <?php if ($showAuthor === 'y') : ?>
          <div class="post-meta post-author">
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author"><?php echo esc_html(get_the_author()); ?></a>
          </div>
          <?php endif; ?>
          <?php if ($showDate === 'y') : ?>
          <div class="post-meta post-date">
            <?php echo esc_html($date); ?>
          </div>
          <?php endif; ?>
          <?php if ($showViews === 'y') : ?>
          <div class="post-meta post-views">
            <?php echo esc_html($views); ?> <?php esc_html_e('lượt xem', 'chinhtoa'); ?>
          </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
