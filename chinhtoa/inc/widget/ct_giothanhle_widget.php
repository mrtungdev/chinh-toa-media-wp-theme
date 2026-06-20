<?php

class CT_GioThanhLe_Widget extends WP_Widget
{
  /**
   * Sets up the widgets name etc
   */
  public function __construct()
  {
    $widget_ops = array(
      'classname'   => 'ct_giothanhle_widget',
      'description' => __('Hiển thị Giờ Thánh Lễ của giáo xứ.', 'chinhtoa'),
    );

    parent::__construct('ct_giothanhle_widget', __('Giờ Thánh Lễ', 'chinhtoa'), $widget_ops);
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget($args, $instance)
  {

    $before_widget = isset($args['before_widget']) ? $args['before_widget'] : '';
    $after_widget  = isset($args['after_widget']) ? $args['after_widget'] : '';
    $arr = ulti_gioithanhle();
    $langs = array();
    $currentLang = '';
    echo $before_widget;

?>
<div class="ct-giothanhle-widget">
  <?php
      foreach ($arr as $key => $lang) {

        $langKey = $lang['language'];
        $langs[$langKey] = getCountryItem($langKey);
        if ($key == 0) {
          $currentLang = $langKey;
        }
      ?>
  <div class="giothanhle-item<?php echo $currentLang == $langKey ? ' current' : '' ?>"
    data-lang="<?php echo esc_attr($langKey); ?>">
    <?php if ($lang['title']) : ?>
    <h3 class="widget-title">
      <?php echo esc_html($lang['title']); ?>
    </h3>
    <?php endif; ?>
    <?php if ($lang['desc']) : ?>
    <span class="widget-desc">
      <?php echo esc_html($lang['desc']); ?>
    </span>
    <?php endif; ?>
    <div class="giothanhle-content">

    </div>
  </div>
  <?php } ?>
  <div class="giothanhle-langs">
    <div class="dropdown">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <?php echo esc_html($currentLang); ?>
      </a>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <?php
            foreach ($langs as $key => $l) {
              printf(
                '<a class="dropdown-item" value="%s" %s>%s</a>',
                esc_attr($key),
                $key == $currentLang ? 'selected' : '',
                esc_html($l)
              );
            } ?>
      </div>
    </div>

  </div>
</div>
<?php
    echo $after_widget;
  }


  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update($new_instance, $old_instance)
  {

    $instance = $old_instance;

    // $instance['title']             = strip_tags($new_instance['title']);

    return $instance;
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form($instance)
  {
  ?>

<p>
  <?php esc_html_e('Chỉ cần kéo widget này vào đây là xong. Nội dung Giờ Thánh Lễ được thiết lập tại phần Thiết lập giao diện.', 'chinhtoa'); ?>
</p>

<?php
  }
}
add_action('widgets_init', function () {
  register_widget('CT_GioThanhLe_Widget');
});

?>