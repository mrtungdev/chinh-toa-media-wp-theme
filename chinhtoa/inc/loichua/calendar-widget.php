<?php

/**
 * Widget "Lịch Lời Chúa" — lịch tháng, ngày nào có bài (trong chuyên mục nguồn) thì
 * bấm được. Ở trang có khối "Lời Chúa hôm nay" (temp7) CÙNG chuyên mục, bấm ngày sẽ
 * thay nội dung khối tại chỗ; ở trang khác thì mở trang bài của ngày đó.
 *
 * Markup lịch: template-parts/loichua/calendar.php (dùng chung với endpoint AJAX).
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
  exit;
}

class CT_LoiChua_Calendar_Widget extends WP_Widget
{
  public function __construct()
  {
    $widget_ops = array(
      'classname'   => 'widget_ct_lc_calendar',
      'description' => __('Lịch tháng: bấm vào ngày để xem bài Lời Chúa của ngày đó.', 'chinhtoa'),
    );
    parent::__construct('ct_lc_calendar', __('Lịch Lời Chúa', 'chinhtoa'), $widget_ops);
  }

  /** Giá trị mặc định cho mọi instance. */
  protected function defaults()
  {
    return array(
      'title'    => __('Lịch', 'chinhtoa'),
      'category' => 0,
    );
  }

  public function widget($args, $instance)
  {
    $instance = wp_parse_args((array) $instance, $this->defaults());
    $title    = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

    // Trang chi tiết bài → đánh dấu ngày của bài và mở đúng tháng của bài.
    $selected = is_singular('post') ? get_the_date('Y-m-d', get_queried_object_id()) : '';

    // Tháng hiển thị: ?ct_cal=Y-m (đổi tháng khi không có JS) > tháng của bài > tháng hiện tại.
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only view param.
    $ym = ct_loichua_sanitize_month(isset($_GET['ct_cal']) ? sanitize_text_field(wp_unslash($_GET['ct_cal'])) : '');
    if ($ym === '') {
      $ym = $selected !== '' ? substr($selected, 0, 7) : substr(ct_loichua_today(), 0, 7);
    }
    list($y, $m) = array_map('intval', explode('-', $ym));

    echo $args['before_widget']; // phpcs:ignore — sidebar markup
    if ($title !== '') {
      echo $args['before_title'] . esc_html($title) . $args['after_title']; // phpcs:ignore — sidebar markup
    }
    echo ct_loichua_calendar_html($y, $m, absint($instance['category']), $selected); // phpcs:ignore — self-escaping
    echo $args['after_widget']; // phpcs:ignore — sidebar markup
  }

  public function update($new_instance, $old_instance)
  {
    $instance             = $this->defaults();
    $instance['title']    = sanitize_text_field(isset($new_instance['title']) ? $new_instance['title'] : '');
    $instance['category'] = isset($new_instance['category']) ? absint($new_instance['category']) : 0;
    return $instance;
  }

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
      <label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Chuyên mục nguồn:', 'chinhtoa'); ?></label>
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
      <small class="description"><?php esc_html_e('Chọn cùng chuyên mục với khối "Lời Chúa hôm nay" ở trang chủ để bấm ngày là đổi bài ngay trong khối.', 'chinhtoa'); ?></small>
    </p>
    <?php
  }
}

add_action('widgets_init', function () {
  register_widget('CT_LoiChua_Calendar_Widget');
});
