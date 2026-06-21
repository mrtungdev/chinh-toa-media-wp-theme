<?php

/**
 * Widget "Danh Sách Bài Viết" — một widget, hai kiểu hiển thị:
 *   - numbered : danh sách đánh số thứ tự 1–X (mẫu "Bài xem nhiều").
 *   - audio    : danh sách có icon play ở đầu mỗi mục (mẫu "Audio mới nhất").
 *
 * Có thiết lập: tiêu đề, kiểu hiển thị, chuyên mục, số bài, cách sắp xếp và
 * màu nền (wp-color-picker). Theo khuôn mẫu CT_GioThanhLe_Widget.
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
  exit;
}

class CT_PostList_Widget extends WP_Widget
{
  public function __construct()
  {
    $widget_ops = array(
      'classname'   => 'ct_postlist_widget',
      'description' => __('Danh sách bài viết kiểu số thứ tự hoặc audio, có chọn màu nền.', 'chinhtoa'),
    );
    parent::__construct('ct_postlist_widget', __('Danh Sách Bài Viết', 'chinhtoa'), $widget_ops);
  }

  /** Giá trị mặc định cho mọi instance. */
  protected function defaults()
  {
    return array(
      'title'      => '',
      'style'      => 'numbered',
      'category'   => 0,
      'number'     => 5,
      'orderby'    => 'auto',
      'bg_color'   => '',
      'show_image' => 0,
      'show_desc'  => 1,
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   */
  public function widget($args, $instance)
  {
    $instance = wp_parse_args((array) $instance, $this->defaults());

    $style    = ($instance['style'] === 'audio') ? 'audio' : 'numbered';
    $category = absint($instance['category']);
    $number   = max(1, absint($instance['number']));

    // Suy ra cách sắp xếp: "auto" => numbered theo lượt xem, audio theo ngày.
    $orderby = $instance['orderby'];
    if ($orderby === 'auto' || !in_array($orderby, array('views', 'date'), true)) {
      $orderby = ($style === 'numbered') ? 'views' : 'date';
    }

    $query_args = array(
      'posts_per_page'   => $number,
      'post_status'      => 'publish',
      'suppress_filters' => false,
    );
    if ($category > 0) {
      $query_args['cat'] = $category;
    }
    if ($orderby === 'views') {
      $query_args['meta_key'] = 'views';
      $query_args['orderby']  = 'meta_value_num';
      $query_args['order']    = 'DESC';
    } else {
      $query_args['orderby'] = 'date';
      $query_args['order']   = 'DESC';
    }

    $trans = 'trans_ct_postlist_' . $style . '_c' . $category . '_n' . $number . '_' . $orderby;
    $posts = ct_get_posts($query_args, $trans, HOUR_IN_SECONDS);

    if (empty($posts)) {
      return;
    }

    echo isset($args['before_widget']) ? $args['before_widget'] : '';

    // Tô lại màu nền card theo id widget (selector #id thắng class .bg-white).
    $bg = ct_normalize_hex($instance['bg_color']);
    if ($bg !== '' && !empty($args['widget_id'])) {
      printf('<style>#%s{background-color:%s}</style>', esc_attr($args['widget_id']), esc_attr($bg));
    }

    if (!empty($instance['title'])) {
      $before_title = isset($args['before_title']) ? $args['before_title'] : '';
      $after_title  = isset($args['after_title']) ? $args['after_title'] : '';
      echo $before_title . esc_html(apply_filters('widget_title', $instance['title'], $instance, $this->id_base)) . $after_title;
    }

    $show_image = !empty($instance['show_image']);
    $show_desc  = !empty($instance['show_desc']);

    if ($style === 'audio') {
      $this->render_audio($posts, $show_image, $show_desc);
    } else {
      $this->render_numbered($posts, $show_image, $show_desc);
    }

    echo isset($args['after_widget']) ? $args['after_widget'] : '';
  }

  /** Ảnh thumbnail của bài (tái dùng getPostImage + lazyload của theme). */
  protected function render_thumb($id, $link)
  {
    $image = getPostImage($id);
    ?>
    <a class="ct-postlist__thumb" href="<?php echo esc_url($link); ?>" aria-hidden="true" tabindex="-1">
      <img class="lazyload" src="<?php echo esc_url(CT_PLACEHOLDER); ?>" data-src="<?php echo esc_url($image); ?>"
        alt="<?php echo esc_attr(get_the_title($id)); ?>">
    </a>
    <?php
  }

  /** Kiểu số thứ tự: số 1..X + (ảnh) + tiêu đề + (mô tả). */
  protected function render_numbered($posts, $show_image, $show_desc)
  {
    echo '<ol class="ct-postlist ct-postlist--numbered">';
    $i = 0;
    foreach ($posts as $post) {
      $i++;
      $id   = $post->ID;
      $link = get_permalink($id);
      $sub  = $show_desc ? wp_trim_words(get_the_excerpt($post), 12, '…') : '';
      ?>
      <li class="ct-postlist__item">
        <span class="ct-postlist__rank" aria-hidden="true"><?php echo esc_html($i); ?></span>
        <?php if ($show_image) {
          $this->render_thumb($id, $link);
        } ?>
        <div class="ct-postlist__body">
          <a class="ct-postlist__title" href="<?php echo esc_url($link); ?>"><?php echo esc_html(get_the_title($id)); ?></a>
          <?php if ($sub !== '') : ?>
          <span class="ct-postlist__sub"><?php echo esc_html($sub); ?></span>
          <?php endif; ?>
        </div>
      </li>
      <?php
    }
    echo '</ol>';
  }

  /** Kiểu audio: nút play + (ảnh) + tiêu đề + (mô tả) + ngày. */
  protected function render_audio($posts, $show_image, $show_desc)
  {
    echo '<ul class="ct-postlist ct-postlist--audio">';
    foreach ($posts as $post) {
      $id   = $post->ID;
      $link = get_permalink($id);
      $sub  = $show_desc ? wp_trim_words(get_the_excerpt($post), 12, '…') : '';
      ?>
      <li class="ct-postlist__item">
        <a class="ct-postlist__play" href="<?php echo esc_url($link); ?>" aria-label="<?php echo esc_attr(get_the_title($id)); ?>">
          <svg class="ct-icon ct-icon-play" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z"/></svg>
        </a>
        <?php if ($show_image) {
          $this->render_thumb($id, $link);
        } ?>
        <div class="ct-postlist__body">
          <a class="ct-postlist__title" href="<?php echo esc_url($link); ?>"><?php echo esc_html(get_the_title($id)); ?></a>
          <?php if ($sub !== '') : ?>
          <span class="ct-postlist__sub"><?php echo esc_html($sub); ?></span>
          <?php endif; ?>
          <span class="ct-postlist__meta"><?php echo esc_html(get_the_date('d.m', $id)); ?></span>
        </div>
      </li>
      <?php
    }
    echo '</ul>';
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   */
  public function update($new_instance, $old_instance)
  {
    $instance = $this->defaults();

    $instance['title']    = sanitize_text_field(isset($new_instance['title']) ? $new_instance['title'] : '');
    $instance['style']    = (isset($new_instance['style']) && $new_instance['style'] === 'audio') ? 'audio' : 'numbered';
    $instance['category'] = isset($new_instance['category']) ? absint($new_instance['category']) : 0;
    $instance['number']   = isset($new_instance['number']) ? max(1, absint($new_instance['number'])) : 5;

    $orderby = isset($new_instance['orderby']) ? $new_instance['orderby'] : 'auto';
    $instance['orderby'] = in_array($orderby, array('auto', 'views', 'date'), true) ? $orderby : 'auto';

    // Tái dùng bộ chuẩn hóa hex của theme (inc/utilities/enqueue.php).
    $instance['bg_color'] = ct_normalize_hex(isset($new_instance['bg_color']) ? $new_instance['bg_color'] : '');

    $instance['show_image'] = empty($new_instance['show_image']) ? 0 : 1;
    $instance['show_desc']  = empty($new_instance['show_desc']) ? 0 : 1;

    return $instance;
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   */
  public function form($instance)
  {
    $instance = wp_parse_args((array) $instance, $this->defaults());
    ?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Tiêu đề:', 'chinhtoa'); ?></label>
      <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
        name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php esc_html_e('Kiểu hiển thị:', 'chinhtoa'); ?></label>
      <select class="widefat" id="<?php echo esc_attr($this->get_field_id('style')); ?>"
        name="<?php echo esc_attr($this->get_field_name('style')); ?>">
        <option value="numbered" <?php selected($instance['style'], 'numbered'); ?>><?php esc_html_e('Số thứ tự (1–X)', 'chinhtoa'); ?></option>
        <option value="audio" <?php selected($instance['style'], 'audio'); ?>><?php esc_html_e('Audio (icon play)', 'chinhtoa'); ?></option>
      </select>
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Chuyên mục:', 'chinhtoa'); ?></label>
      <?php
      wp_dropdown_categories(array(
        'show_option_all' => __('Tất cả chuyên mục', 'chinhtoa'),
        'hide_empty'      => false,
        'selected'        => $instance['category'],
        'name'            => $this->get_field_name('category'),
        'id'              => $this->get_field_id('category'),
        'class'           => 'widefat',
        'orderby'         => 'name',
      ));
      ?>
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Số bài hiển thị (X):', 'chinhtoa'); ?></label>
      <input class="tiny-text" type="number" min="1" step="1" id="<?php echo esc_attr($this->get_field_id('number')); ?>"
        name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($instance['number']); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php esc_html_e('Sắp xếp:', 'chinhtoa'); ?></label>
      <select class="widefat" id="<?php echo esc_attr($this->get_field_id('orderby')); ?>"
        name="<?php echo esc_attr($this->get_field_name('orderby')); ?>">
        <option value="auto" <?php selected($instance['orderby'], 'auto'); ?>><?php esc_html_e('Tự động theo kiểu', 'chinhtoa'); ?></option>
        <option value="views" <?php selected($instance['orderby'], 'views'); ?>><?php esc_html_e('Xem nhiều nhất', 'chinhtoa'); ?></option>
        <option value="date" <?php selected($instance['orderby'], 'date'); ?>><?php esc_html_e('Mới nhất', 'chinhtoa'); ?></option>
      </select>
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('bg_color')); ?>"><?php esc_html_e('Màu nền:', 'chinhtoa'); ?></label><br>
      <input class="ct-widget-color-field" type="text" id="<?php echo esc_attr($this->get_field_id('bg_color')); ?>"
        name="<?php echo esc_attr($this->get_field_name('bg_color')); ?>" value="<?php echo esc_attr($instance['bg_color']); ?>"
        data-default-color="">
      <small class="description"><?php esc_html_e('Để trống = dùng nền mặc định của giao diện.', 'chinhtoa'); ?></small>
    </p>
    <p>
      <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_image')); ?>"
        name="<?php echo esc_attr($this->get_field_name('show_image')); ?>" value="1" <?php checked($instance['show_image'], 1); ?>>
      <label for="<?php echo esc_attr($this->get_field_id('show_image')); ?>"><?php esc_html_e('Hiện ảnh thu nhỏ', 'chinhtoa'); ?></label>
    </p>
    <p>
      <input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('show_desc')); ?>"
        name="<?php echo esc_attr($this->get_field_name('show_desc')); ?>" value="1" <?php checked($instance['show_desc'], 1); ?>>
      <label for="<?php echo esc_attr($this->get_field_id('show_desc')); ?>"><?php esc_html_e('Hiện mô tả (tóm tắt)', 'chinhtoa'); ?></label>
    </p>
    <?php
  }
}

add_action('widgets_init', function () {
  register_widget('CT_PostList_Widget');
});

/** CSS front-end của widget — hook riêng để không dính `return` sớm trong bb_enqueues(). */
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style('ct-postlist', CT_THEME_CSS_URI . '/widget-postlist.css', array(), THEME_VERSION);
});

/** JS khởi tạo wp-color-picker cho ô màu trong form widget (màn hình Widgets cổ điển + Customizer). */
add_action('admin_enqueue_scripts', function ($hook) {
  if ($hook === 'widgets.php' || $hook === 'customize.php') {
    wp_enqueue_script('ct-widget-admin', CT_THEME_JS_URI . '/widget-admin.js', array('jquery', 'wp-color-picker'), THEME_VERSION, true);
  }
});
