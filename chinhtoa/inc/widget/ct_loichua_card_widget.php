<?php

/**
 * Widget "Lời Chúa: Câu ghi nhớ" (CT_LoiChua_Card_Widget).
 *
 * Thẻ câu Kinh Thánh static/dynamic, màu nền/chữ/nhấn tuỳ chỉnh. Dùng chung
 * ct_loichua_card_render() với block chinhtoa/loichua-card (markup/CSS không lệch).
 * Theo khuôn mẫu CT_PostList_Widget (inc/widget/ct_postlist_widget.php).
 *
 * KHÁC widget "Lời Chúa hôm nay" (CT_LoiChua_Widget, inc/loichua/widget.php): cái đó
 * hiển thị bộ bài đọc theo ngày từ CPT ct_loichua; cái này là thẻ câu ghi nhớ tự do.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once CT_THEME_DIR . '/inc/blocks/loichua-card/render.php';

class CT_LoiChua_Card_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname'   => 'ct_loichua_card_widget',
            'description' => __('Thẻ Lời Chúa (câu ghi nhớ): nhãn, câu Kinh Thánh, trích dẫn — nhập tay hoặc tự động từ bài viết. Màu tuỳ chỉnh.', 'chinhtoa'),
        );
        parent::__construct('ct_loichua_card', __('Lời Chúa: Câu ghi nhớ', 'chinhtoa'), $widget_ops);
    }

    /** Giá trị mặc định cho mọi instance. */
    protected function defaults()
    {
        return array(
            'title'           => '',
            'mode'            => 'static',
            'label'           => __('CÂU GHI NHỚ', 'chinhtoa'),
            'quote'           => '',
            'citation'        => '',
            'source'          => 'category',
            'source_category' => 0,
            'source_post_id'  => 0,
            'bg_color'        => '',
            'text_color'      => '',
            'accent_color'    => '',
        );
    }

    public function widget($args, $instance)
    {
        $instance = wp_parse_args((array) $instance, $this->defaults());

        // Widget không có ngữ cảnh block → null; nguồn 'current' tự fallback về loop.
        $html = ct_loichua_card_render(array(
            'label'          => $instance['label'],
            'quote'          => $instance['quote'],
            'citation'       => $instance['citation'],
            'mode'           => $instance['mode'],
            'source'         => $instance['source'],
            'sourceCategory' => $instance['source_category'],
            'sourcePostId'   => $instance['source_post_id'],
            'bgColor'        => $instance['bg_color'],
            'textColor'      => $instance['text_color'],
            'accentColor'    => $instance['accent_color'],
        ), null);

        if ($html === '') {
            return; // không có nội dung → ẩn widget (không hiện khung rỗng).
        }

        echo isset($args['before_widget']) ? $args['before_widget'] : '';

        if (!empty($instance['title'])) {
            $before_title = isset($args['before_title']) ? $args['before_title'] : '';
            $after_title  = isset($args['after_title']) ? $args['after_title'] : '';
            echo $before_title . esc_html(apply_filters('widget_title', $instance['title'], $instance, $this->id_base)) . $after_title;
        }

        echo $html; // đã escape trong ct_loichua_card_render().

        echo isset($args['after_widget']) ? $args['after_widget'] : '';
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $this->defaults();

        $instance['title']    = sanitize_text_field(isset($new_instance['title']) ? $new_instance['title'] : '');
        $instance['mode']     = (isset($new_instance['mode']) && $new_instance['mode'] === 'dynamic') ? 'dynamic' : 'static';
        $instance['label']    = sanitize_text_field(isset($new_instance['label']) ? $new_instance['label'] : '');
        $instance['quote']    = isset($new_instance['quote']) ? wp_kses_post($new_instance['quote']) : '';
        $instance['citation'] = sanitize_text_field(isset($new_instance['citation']) ? $new_instance['citation'] : '');

        $source = isset($new_instance['source']) ? $new_instance['source'] : 'category';
        $instance['source']          = in_array($source, array('category', 'current', 'post'), true) ? $source : 'category';
        $instance['source_category'] = isset($new_instance['source_category']) ? absint($new_instance['source_category']) : 0;
        $instance['source_post_id']  = isset($new_instance['source_post_id']) ? absint($new_instance['source_post_id']) : 0;

        // Tái dùng bộ chuẩn hoá hex của theme (inc/utilities/enqueue.php).
        $instance['bg_color']     = ct_normalize_hex(isset($new_instance['bg_color']) ? $new_instance['bg_color'] : '');
        $instance['text_color']   = ct_normalize_hex(isset($new_instance['text_color']) ? $new_instance['text_color'] : '');
        $instance['accent_color'] = ct_normalize_hex(isset($new_instance['accent_color']) ? $new_instance['accent_color'] : '');

        return $instance;
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, $this->defaults());
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Tiêu đề (tuỳ chọn, hiện phía trên thẻ):', 'chinhtoa'); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('mode')); ?>"><?php esc_html_e('Chế độ:', 'chinhtoa'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('mode')); ?>"
                name="<?php echo esc_attr($this->get_field_name('mode')); ?>">
                <option value="static" <?php selected($instance['mode'], 'static'); ?>><?php esc_html_e('Nhập tay (Static)', 'chinhtoa'); ?></option>
                <option value="dynamic" <?php selected($instance['mode'], 'dynamic'); ?>><?php esc_html_e('Tự động từ bài viết (Dynamic)', 'chinhtoa'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('label')); ?>"><?php esc_html_e('Nhãn (vd CÂU GHI NHỚ):', 'chinhtoa'); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('label')); ?>"
                name="<?php echo esc_attr($this->get_field_name('label')); ?>" value="<?php echo esc_attr($instance['label']); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('quote')); ?>"><?php esc_html_e('Câu Lời Chúa:', 'chinhtoa'); ?></label>
            <textarea class="widefat" rows="3" id="<?php echo esc_attr($this->get_field_id('quote')); ?>"
                name="<?php echo esc_attr($this->get_field_name('quote')); ?>"><?php echo esc_textarea($instance['quote']); ?></textarea>
            <small class="description"><?php esc_html_e('Chế độ Dynamic: để trống = lấy từ metabox bài viết (hoặc mô tả).', 'chinhtoa'); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('citation')); ?>"><?php esc_html_e('Trích dẫn (vd Mt 6, 33):', 'chinhtoa'); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('citation')); ?>"
                name="<?php echo esc_attr($this->get_field_name('citation')); ?>" value="<?php echo esc_attr($instance['citation']); ?>">
            <small class="description"><?php esc_html_e('Chế độ Dynamic: để trống = lấy từ metabox bài viết (hoặc tiêu đề).', 'chinhtoa'); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('source')); ?>"><?php esc_html_e('Dynamic — lấy từ:', 'chinhtoa'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('source')); ?>"
                name="<?php echo esc_attr($this->get_field_name('source')); ?>">
                <option value="category" <?php selected($instance['source'], 'category'); ?>><?php esc_html_e('Mới nhất trong chuyên mục', 'chinhtoa'); ?></option>
                <option value="current" <?php selected($instance['source'], 'current'); ?>><?php esc_html_e('Bài đang xem', 'chinhtoa'); ?></option>
                <option value="post" <?php selected($instance['source'], 'post'); ?>><?php esc_html_e('Bài viết cụ thể', 'chinhtoa'); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('source_category')); ?>"><?php esc_html_e('Chuyên mục (cho "Mới nhất trong chuyên mục"):', 'chinhtoa'); ?></label>
            <?php
            wp_dropdown_categories(array(
                'show_option_all' => __('Tất cả chuyên mục', 'chinhtoa'),
                'hide_empty'      => false,
                'selected'        => $instance['source_category'],
                'name'            => $this->get_field_name('source_category'),
                'id'              => $this->get_field_id('source_category'),
                'class'           => 'widefat',
                'orderby'         => 'name',
            ));
            ?>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('source_post_id')); ?>"><?php esc_html_e('ID bài viết (cho "Bài viết cụ thể"):', 'chinhtoa'); ?></label>
            <input class="tiny-text" type="number" min="0" step="1" id="<?php echo esc_attr($this->get_field_id('source_post_id')); ?>"
                name="<?php echo esc_attr($this->get_field_name('source_post_id')); ?>" value="<?php echo esc_attr($instance['source_post_id']); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('bg_color')); ?>"><?php esc_html_e('Màu nền:', 'chinhtoa'); ?></label><br>
            <input class="ct-widget-color-field" type="text" id="<?php echo esc_attr($this->get_field_id('bg_color')); ?>"
                name="<?php echo esc_attr($this->get_field_name('bg_color')); ?>" value="<?php echo esc_attr($instance['bg_color']); ?>" data-default-color="">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text_color')); ?>"><?php esc_html_e('Màu chữ (câu):', 'chinhtoa'); ?></label><br>
            <input class="ct-widget-color-field" type="text" id="<?php echo esc_attr($this->get_field_id('text_color')); ?>"
                name="<?php echo esc_attr($this->get_field_name('text_color')); ?>" value="<?php echo esc_attr($instance['text_color']); ?>" data-default-color="">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('accent_color')); ?>"><?php esc_html_e('Màu nhấn (nhãn + trích dẫn):', 'chinhtoa'); ?></label><br>
            <input class="ct-widget-color-field" type="text" id="<?php echo esc_attr($this->get_field_id('accent_color')); ?>"
                name="<?php echo esc_attr($this->get_field_name('accent_color')); ?>" value="<?php echo esc_attr($instance['accent_color']); ?>" data-default-color="">
            <small class="description"><?php esc_html_e('Để trống = dùng màu mặc định của thẻ.', 'chinhtoa'); ?></small>
        </p>
        <?php
    }
}

add_action('widgets_init', function () {
    register_widget('CT_LoiChua_Card_Widget');
});

/** CSS front-end của thẻ (handle đăng ký trong inc/blocks/loader.php; kéo theo font Lora). */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('ct-loichua-card');
});

/** JS khởi tạo wp-color-picker cho 3 ô màu (tái dùng widget-admin.js sẵn có). */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'widgets.php' || $hook === 'customize.php') {
        wp_enqueue_script('ct-widget-admin', CT_THEME_JS_URI . '/widget-admin.js', array('jquery', 'wp-color-picker'), THEME_VERSION, true);
    }
});
