<?php

/**
 * Section renderers for the native theme-options editor.
 *
 * Each function echoes the fields for one settings tab, using the helpers in
 * fields.php so every input's name encodes its path in `ct_settings`. The nested
 * shapes here mirror EXACTLY what the front-end flatteners read (verified by
 * tests/options-test.php), so saving the form reproduces the Unyson data shape.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Image base for swatch pickers. */
function ct_opt_img($file)
{
    return (defined('CT_THEME_IMGS_URI') ? CT_THEME_IMGS_URI : '') . $file;
}

/** Short guiding description shown right under a section <h2>. */
function ct_section_intro($text)
{
    echo '<p class="ct-section-desc">' . wp_kses_post($text) . '</p>';
}

/* ------------------------------------------------------------------ General */
function ct_section_general($s)
{
    echo '<h2>' . esc_html__('Mẫu & Hiển thị chung', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Chọn màu chủ đạo, kiểu thanh menu và nền cho toàn bộ website.', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';

    $customColor = ct_opt_val($s, array('theme_data', 'custom_color'), '');
    ct_field_color_radio($s, array('theme_data', 'theme'), __('Màu giao diện', 'chinhtoa'), array(
        'white'  => array('name' => __('Trắng', 'chinhtoa'),      'color' => '#ffffff'),
        'black'  => array('name' => __('Đen', 'chinhtoa'),        'color' => '#000000'),
        'green'  => array('name' => __('Xanh lá', 'chinhtoa'),    'color' => '#38bf26'),
        'red'    => array('name' => __('Đỏ', 'chinhtoa'),         'color' => '#9c0000'),
        'rose'   => array('name' => __('Hồng', 'chinhtoa'),       'color' => '#ff36ad'),
        'violet' => array('name' => __('Tím', 'chinhtoa'),        'color' => '#9d00ba'),
        'yellow' => array('name' => __('Vàng', 'chinhtoa'),       'color' => '#a9a50f'),
        'blue'   => array('name' => __('Xanh dương', 'chinhtoa'), 'color' => '#1565c0'),
        'custom' => ($customColor !== ''
            ? array('name' => __('Tự chọn', 'chinhtoa'), 'color' => $customColor, 'swatch_class' => 'ct-custom-swatch')
            : array('name' => __('Tự chọn', 'chinhtoa'), 'gradient' => true, 'swatch_class' => 'ct-custom-swatch')),
    ), __('Màu nhấn dùng cho liên kết, nút bấm và các điểm nổi bật trên website. Chọn "Tự chọn" để dùng màu riêng.', 'chinhtoa'));

    // Custom color (only when "Tự chọn" is selected).
    ct_field_color($s, array('theme_data', 'custom_color'), __('Màu tự chọn', 'chinhtoa'), __('Bấm để chọn một màu bất kỳ. Chỉ áp dụng khi đã chọn ô "Tự chọn" ở trên.', 'chinhtoa'), array(array('theme_data', 'theme'), 'custom'));

    ct_field_image_radio($s, array('gen_data', 'nav_style'), __('Kiểu thanh menu', 'chinhtoa'), array(
        'c1' => ct_opt_img('/layouts/nav-style-2.png'),
        'c2' => ct_opt_img('/layouts/nav-style-3.png'),
        'c3' => ct_opt_img('/layouts/nav-style-4.png'),
    ), __('Chọn bố cục thanh menu chính ở đầu trang.', 'chinhtoa'), null, 'wide');

    ct_field_color($s, array('gen_data', 'nav_bg_color'), __('Màu nền thanh menu', 'chinhtoa'), __('Màu nền của thanh menu chính. Để trống để dùng màu mặc định của kiểu menu.', 'chinhtoa'));
    ct_field_color($s, array('gen_data', 'nav_text_color'), __('Màu chữ thanh menu', 'chinhtoa'), __('Màu chữ các mục trên thanh menu. Để trống để dùng mặc định.', 'chinhtoa'));
    ct_field_color($s, array('gen_data', 'nav_accent_color'), __('Màu nhấn thanh menu', 'chinhtoa'), __('Gạch chân mục đang chọn (kiểu menu 3) và màu nền mục active (kiểu 1, 2). Để trống để dùng màu giao diện.', 'chinhtoa'));

    ct_field_radio($s, array('gen_data', 'gen_bg', 'action_show'), __('Nền trang', 'chinhtoa'), array(
        'c_color' => __('Dùng màu nền', 'chinhtoa'),
        'c_image' => __('Dùng hình nền', 'chinhtoa'),
    ), __('Chọn nền cho website là một màu đơn sắc hoặc một hình ảnh.', 'chinhtoa'));
    ct_field_color($s, array('gen_data', 'gen_bg', 'c_color', 'color'), __('Màu nền', 'chinhtoa'), '', array(array('gen_data', 'gen_bg', 'action_show'), 'c_color'));
    ct_field_media($s, array('gen_data', 'gen_bg', 'c_image', 'image', 'image_upload'), __('Hình nền', 'chinhtoa'), __('Tải lên ảnh dùng làm nền cho trang.', 'chinhtoa'), true, array(array('gen_data', 'gen_bg', 'action_show'), 'c_image'));
    ct_field_select($s, array('gen_data', 'gen_bg', 'c_image', 'image', 'image_repeat'), __('Cách lặp hình nền', 'chinhtoa'), array(
        'no-repeat' => __('Không lặp', 'chinhtoa'),
        'repeat'    => __('Lặp cả hai chiều', 'chinhtoa'),
        'repeat-x'  => __('Lặp theo chiều ngang', 'chinhtoa'),
        'repeat-y'  => __('Lặp theo chiều dọc', 'chinhtoa'),
    ), '', array(array('gen_data', 'gen_bg', 'action_show'), 'c_image'));

    echo '</tbody></table>';
}

/* ------------------------------------------------------------------- Header */
function ct_section_header($s)
{
    echo '<h2>' . esc_html__('Header (đầu trang)', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Tùy chỉnh phần đầu website: tự soạn nội dung, hoặc dùng ảnh banner rộng riêng cho từng kích thước màn hình.', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';

    ct_field_radio($s, array('header_data', 'action_show'), __('Kiểu Header', 'chinhtoa'), array(
        'c_content' => __('Tự nhập nội dung', 'chinhtoa'),
        'c_images'  => __('Ảnh banner rộng', 'chinhtoa'),
    ), __('Chọn cách hiển thị phần đầu trang.', 'chinhtoa'));
    ct_field_editor($s, array('header_data', 'c_content', 'header_text'), __('Nội dung Header', 'chinhtoa'), __('Soạn nội dung hiển thị ở đầu trang (chữ, hình, mã HTML).', 'chinhtoa'), array(array('header_data', 'action_show'), 'c_content'));
    ct_field_color($s, array('header_data', 'c_content', 'bgcolor'), __('Màu nền Header', 'chinhtoa'), '', array(array('header_data', 'action_show'), 'c_content'));

    $imgShow = array(array('header_data', 'action_show'), 'c_images');
    ct_field_media($s, array('header_data', 'c_images', 'gen_img_desktop'), __('Ảnh cho Máy tính', 'chinhtoa'), __('Banner hiển thị trên màn hình máy tính (khổ rộng).', 'chinhtoa'), true, $imgShow);
    ct_field_media($s, array('header_data', 'c_images', 'gen_img_tablet'), __('Ảnh cho Máy tính bảng', 'chinhtoa'), __('Banner cho màn hình tầm trung. Để trống sẽ dùng ảnh máy tính.', 'chinhtoa'), true, $imgShow);
    ct_field_media($s, array('header_data', 'c_images', 'gen_img_mobile'), __('Ảnh cho Điện thoại', 'chinhtoa'), __('Banner cho màn hình nhỏ. Để trống sẽ dùng ảnh máy tính.', 'chinhtoa'), true, $imgShow);
    ct_field_text($s, array('header_data', 'c_images', 'gen_tieu_de'), __('Mô tả ảnh', 'chinhtoa'), __('Văn bản thay thế (alt) cho banner — tốt cho SEO.', 'chinhtoa'), $imgShow);
    ct_field_text($s, array('header_data', 'c_images', 'gen_lien_ket'), __('Liên kết khi bấm', 'chinhtoa'), __('Đường dẫn mở ra khi người dùng bấm vào banner.', 'chinhtoa'), $imgShow);
    ct_field_switch($s, array('header_data', 'c_images', 'gen_is_blank'), __('Mở liên kết ở tab mới', 'chinhtoa'), '', '1', '0', $imgShow);

    echo '</tbody></table>';
}

/* ------------------------------------------------------------------- Footer */
function ct_section_footer($s)
{
    echo '<h2>' . esc_html__('Cuối trang (Footer)', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Cấu hình khu vực cuối website: các cột widget, dòng bản quyền và màu sắc.', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';

    ct_field_switch($s, array('footer_data', 'gen_widget', 'action_show'), __('Hiển thị cột Widget', 'chinhtoa'), __('Bật để hiện các cột widget (liên hệ, liên kết, fanpage…) ở cuối trang.', 'chinhtoa'));
    ct_field_image_radio($s, array('footer_data', 'gen_widget', 'y', 'widget_setting', 'number'), __('Số cột Widget', 'chinhtoa'), array(
        'c1' => ct_opt_img('/layouts/one-column.png'),
        'c2' => ct_opt_img('/layouts/two-column.png'),
        'c3' => ct_opt_img('/layouts/three-column.png'),
        'c4' => ct_opt_img('/layouts/four-column.png'),
    ), __('Số cột chia khu vực widget ở cuối trang.', 'chinhtoa'), array(array('footer_data', 'gen_widget', 'action_show'), 'y'), 'icon');
    ct_field_editor($s, array('footer_data', 'gen_footer_text'), __('Nội dung cuối trang', 'chinhtoa'), __('Dòng bản quyền / thông tin hiển thị ở đáy trang.', 'chinhtoa'));
    ct_field_color($s, array('footer_data', 'gen_footer_bg_color'), __('Màu nền cuối trang', 'chinhtoa'));
    ct_field_color($s, array('footer_data', 'gen_footer_txt_color'), __('Màu chữ cuối trang', 'chinhtoa'));

    echo '</tbody></table>';
}

/* --------------------------------------------------------------------- Tech */
function ct_section_tech($s)
{
    echo '<h2>' . esc_html__('Kỹ thuật', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Mã theo dõi, đoạn script chèn thêm và shortcode cho Bảng Điều Khiển. <strong>Dành cho người rành kỹ thuật</strong> — nhập sai có thể ảnh hưởng website.', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';
    ct_field_text($s, array('tech_data', 'analytics'), __('Mã Google Analytics', 'chinhtoa'), __('Ví dụ: G-XXXXXXXXXX hoặc UA-XXXXXX-X. Để trống nếu không dùng.', 'chinhtoa'));
    ct_field_textarea($s, array('tech_data', 'headscripts'), __('Mã chèn vào <head>', 'chinhtoa'), __('Đoạn mã đặt trong thẻ &lt;head&gt; (mã xác minh, pixel quảng cáo…).', 'chinhtoa'), 5);
    ct_field_textarea($s, array('tech_data', 'footerscripts'), __('Mã chèn cuối trang', 'chinhtoa'), __('Đoạn mã đặt trước thẻ đóng &lt;/body&gt; (chat, thống kê…).', 'chinhtoa'), 5);
    ct_field_text($s, array('tech_data', 'template_admin_shortcode'), __('Shortcode cho Quản trị viên', 'chinhtoa'), __('Nội dung (dạng shortcode) hiển thị trên Bảng Điều Khiển cho Quản trị viên.', 'chinhtoa'));
    ct_field_text($s, array('tech_data', 'template_editor_shortcode'), __('Shortcode cho Biên tập viên', 'chinhtoa'), __('Tương tự, áp dụng cho vai trò Biên tập viên (Editor).', 'chinhtoa'));
    ct_field_text($s, array('tech_data', 'template_else_shortcode'), __('Shortcode cho vai trò khác', 'chinhtoa'), __('Áp dụng cho các vai trò còn lại (cộng tác viên, tác giả…).', 'chinhtoa'));
    echo '</tbody></table>';
}

/* ---------------------------------------------------------------------- Hot */
function ct_section_hot($s)
{
    echo '<h2>' . esc_html__('Thanh thông báo nổi bật', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Dải thông báo hiển thị nổi bật trên đầu website, chỉ xuất hiện trong khoảng thời gian bạn đặt. Phù hợp để báo sự kiện, thánh lễ, tin khẩn.', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';
    ct_field_switch($s, array('hot_picker', 'action_show'), __('Bật thanh thông báo', 'chinhtoa'), __('Bật/tắt dải thông báo trên website.', 'chinhtoa'));
    $show = array(array('hot_picker', 'action_show'), 'y');
    ct_field_datetime($s, array('hot_picker', 'y', 'hot_setting', 'displaytime', 'from'), __('Bắt đầu hiển thị', 'chinhtoa'), __('Thời điểm thanh thông báo bắt đầu xuất hiện.', 'chinhtoa'), $show);
    ct_field_datetime($s, array('hot_picker', 'y', 'hot_setting', 'displaytime', 'to'), __('Kết thúc hiển thị', 'chinhtoa'), __('Thời điểm tự động ẩn thanh thông báo.', 'chinhtoa'), $show);
    ct_field_text($s, array('hot_picker', 'y', 'hot_setting', 'title'), __('Tiêu đề', 'chinhtoa'), __('Dòng chữ chính của thông báo.', 'chinhtoa'), $show);
    ct_field_textarea($s, array('hot_picker', 'y', 'hot_setting', 'noidung'), __('Nội dung', 'chinhtoa'), __('Nội dung chi tiết (không bắt buộc).', 'chinhtoa'), 4, $show);
    ct_field_switch($s, array('hot_picker', 'y', 'hot_setting', 'islive'), __('Đang phát trực tiếp', 'chinhtoa'), __('Bật khi đang có livestream để hiển thị nhãn "Trực tiếp".', 'chinhtoa'), 'y', 'n', $show);
    ct_field_text($s, array('hot_picker', 'y', 'hot_setting', 'classes'), __('Class CSS (nâng cao)', 'chinhtoa'), __('Tùy chọn — thêm class để tự tùy biến giao diện thanh thông báo.', 'chinhtoa'), $show);
    echo '</tbody></table>';
}

/* ----------------------------------------------------------------- Homepage */
function ct_section_homepage($s)
{
    echo '<h2>' . esc_html__('Trang chủ', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Cấu hình thanh bên, khối tin nổi bật và box "5 phút" ở đầu trang chủ. Các khối nội dung bên dưới được quản lý ở phần "Khối nội dung trang chủ".', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';

    // Sidebar
    ct_field_switch($s, array('home_gen', 'sidebar', 'action_show'), __('Hiển thị thanh bên', 'chinhtoa'), __('Bật để hiện cột thanh bên (sidebar) ở trang chủ.', 'chinhtoa'));
    ct_field_select($s, array('home_gen', 'sidebar', 'y', 'sidebar_pos'), __('Vị trí thanh bên', 'chinhtoa'), array(
        'left' => __('Bên trái', 'chinhtoa'), 'right' => __('Bên phải', 'chinhtoa'),
    ), '', array(array('home_gen', 'sidebar', 'action_show'), 'y'));

    // Featured (nested multi-picker)
    ct_field_switch($s, array('home_featured', 'action_show'), __('Hiển thị khối Nổi bật', 'chinhtoa'), __('Khối tin nổi bật nằm ngay đầu trang chủ.', 'chinhtoa'));
    $fShow = array(array('home_featured', 'action_show'), 'y');
    ct_field_radio($s, array('home_featured', 'y', 'featured_type', 'action_show'), __('Kiểu khối Nổi bật', 'chinhtoa'), array(
        'c1' => __('Tin nổi bật + tiêu điểm', 'chinhtoa'),
        'c2' => __('Tự dựng bằng Shortcode', 'chinhtoa'),
    ), __('Chọn cách dựng khối nổi bật.', 'chinhtoa'), $fShow);
    $c1Show = array(array('home_featured', 'y', 'featured_type', 'action_show'), 'c1');
    ct_field_switch($s, array('home_featured', 'y', 'featured_type', 'c1', 'c1_tinhot', 'action_show'), __('Bật khối "Tin Hot"', 'chinhtoa'), '', 'y', 'n', $c1Show);
    $tinhotShow = array(array('home_featured', 'y', 'featured_type', 'c1', 'c1_tinhot', 'action_show'), 'y');
    ct_field_taxonomy($s, array('home_featured', 'y', 'featured_type', 'c1', 'c1_tinhot', 'y', 'cats'), __('Tin Hot — Chuyên mục', 'chinhtoa'), __('Lấy bài từ (các) chuyên mục này.', 'chinhtoa'), $tinhotShow);
    ct_field_slider($s, array('home_featured', 'y', 'featured_type', 'c1', 'c1_tinhot', 'y', 'num_post'), __('Tin Hot — Số bài', 'chinhtoa'), __('Số bài hiển thị trong khối.', 'chinhtoa'), 1, 30, $tinhotShow);
    ct_field_number($s, array('home_featured', 'y', 'featured_type', 'c1', 'c1_tinhot', 'y', 'date'), __('Tin Hot — Trong vòng (ngày)', 'chinhtoa'), __('Chỉ lấy bài đăng trong số ngày gần đây.', 'chinhtoa'), $tinhotShow);
    ct_field_taxonomy($s, array('home_featured', 'y', 'featured_type', 'c1', 'c1_tieudem', 'cats'), __('Tiêu điểm — Chuyên mục', 'chinhtoa'), __('Lấy bài cho khối tiêu điểm.', 'chinhtoa'), $c1Show);
    ct_field_slider($s, array('home_featured', 'y', 'featured_type', 'c1', 'c1_tieudem', 'num_post'), __('Tiêu điểm — Số bài', 'chinhtoa'), '', 1, 30, $c1Show);
    ct_field_text($s, array('home_featured', 'y', 'featured_type', 'c2', 'shortcode'), __('Shortcode', 'chinhtoa'), __('Nhập shortcode để tự dựng khối nổi bật.', 'chinhtoa'), array(array('home_featured', 'y', 'featured_type', 'action_show'), 'c2'));

    // 5-phút Lời Chúa box
    ct_field_switch($s, array('show_5phutloichua', 'action_show'), __('Hiển thị box "5 phút"', 'chinhtoa'), __('Box "5 phút Lời Chúa" — chọn nơi hiển thị bên dưới.', 'chinhtoa'));
    $lcShow = array(array('show_5phutloichua', 'action_show'), 'y');
    ct_field_text($s, array('show_5phutloichua', 'y', 'title'), __('Tiêu đề box', 'chinhtoa'), '', $lcShow);
    ct_field_taxonomy($s, array('show_5phutloichua', 'y', 'category'), __('Chuyên mục nguồn', 'chinhtoa'), __('Chuyên mục chứa bài "5 phút".', 'chinhtoa'), $lcShow);
    ct_field_switch($s, array('show_5phutloichua', 'y', 'showinhomepage'), __('Hiện ở Trang chủ', 'chinhtoa'), '', 'y', 'n', $lcShow);
    ct_field_switch($s, array('show_5phutloichua', 'y', 'showinsingle'), __('Hiện ở trang Bài viết', 'chinhtoa'), '', 'y', 'n', $lcShow);
    ct_field_switch($s, array('show_5phutloichua', 'y', 'showincat'), __('Hiện ở trang Chuyên mục', 'chinhtoa'), '', 'y', 'n', $lcShow);

    echo '</tbody></table>';

    // Homepage section builder (repeater)
    ct_section_home_sec($s);
}

/* --------------------------------------------- Homepage section builder ---- */
function ct_home_sec_templates()
{
    return array(
        'temp0' => __('Tĩnh / HTML', 'chinhtoa'),
        'temp1' => __('Danh sách bài 1', 'chinhtoa'),
        'temp2' => __('Danh sách bài 2', 'chinhtoa'),
        'temp3' => __('Danh sách bài 3', 'chinhtoa'),
        'temp4' => __('Danh sách bài 4', 'chinhtoa'),
        'temp5' => __('Danh sách bài 5', 'chinhtoa'),
        'temp6' => __('Tabs chuyên mục', 'chinhtoa'),
    );
}

/**
 * Render one home_sec row. $i is the index (or '__I__' for the JS template).
 */
function ct_render_home_sec_row($i, $row = array())
{
    $base   = array('home_sec', $i, 'content_type');
    $picker = ct_opt_val($row, array('content_type', 'picker'), 'temp1');
    $ct     = isset($row['content_type']) && is_array($row['content_type']) ? $row['content_type'] : array();
    $wrapS  = array('content_type' => $ct); // local settings root for ct_opt_val within the row

    echo '<div class="ct-sec-row postbox" data-index="' . esc_attr($i) . '">';
    echo '<div class="ct-sec-head">';
    echo '<span class="ct-sec-drag dashicons dashicons-move" title="' . esc_attr__('Kéo để sắp xếp', 'chinhtoa') . '"></span>';
    echo '<button type="button" class="ct-sec-toggle" aria-expanded="true" title="' . esc_attr__('Thu gọn / Mở rộng', 'chinhtoa') . '"><span class="dashicons dashicons-arrow-up-alt2"></span></button>';
    // Template picker
    echo '<select class="ct-sec-picker" name="' . esc_attr(ct_opt_name(array_merge($base, array('picker')))) . '">';
    foreach (ct_home_sec_templates() as $val => $label) {
        printf('<option value="%s"%s>%s</option>', esc_attr($val), selected($picker, $val, false), esc_html($label));
    }
    echo '</select>';
    echo '<span class="ct-sec-summary"></span>';
    echo '<button type="button" class="button-link-delete ct-sec-remove">' . esc_html__('Xoá khối', 'chinhtoa') . '</button>';
    echo '</div><div class="ct-sec-body">';

    // Demo preview of the selected template's layout (updated via JS on change).
    $demoBase = (defined('CT_THEME_IMGS_URI') ? CT_THEME_IMGS_URI : '') . '/options/home-template-';
    $demoN    = (int) str_replace('temp', '', $picker);
    echo '<div class="ct-sec-demo">';
    echo '<span class="ct-sec-demo-label">' . esc_html__('Bố cục mẫu khối:', 'chinhtoa') . '</span>';
    echo '<img class="ct-sec-demo-img" data-base="' . esc_attr($demoBase) . '" src="' . esc_url($demoBase . $demoN . '.png') . '" alt="" onerror="this.style.display=\'none\'">';
    echo '</div>';

    // Common fields per template group. Inputs in non-selected groups are disabled by JS.
    foreach (array_keys(ct_home_sec_templates()) as $tpl) {
        $disabled = ($tpl === $picker) ? '' : ' disabled';
        $g        = isset($ct[$tpl]) && is_array($ct[$tpl]) ? $ct[$tpl] : array();
        echo '<div class="ct-tpl-group' . ($tpl === $picker ? '' : ' is-hidden') . '" data-tpl="' . esc_attr($tpl) . '">';
        $p = array_merge($base, array($tpl));

        // Shared
        ct_sec_input($p, 'title', __('Tiêu đề khối', 'chinhtoa'), isset($g['title']) ? $g['title'] : '', 'text', $disabled);
        ct_sec_input($p, 'is_display', __('Hiển thị khối', 'chinhtoa'), isset($g['is_display']) ? $g['is_display'] : 'y', 'yn', $disabled);
        ct_sec_input($p, 'is_admin_only', __('Chỉ hiện cho Admin', 'chinhtoa'), isset($g['is_admin_only']) ? $g['is_admin_only'] : 'n', 'yn', $disabled);

        if ('temp0' === $tpl) {
            ct_sec_input($p, 'content', __('Nội dung (HTML)', 'chinhtoa'), isset($g['content']) ? $g['content'] : '', 'textarea', $disabled);
            // default_style
            ct_sec_input(array_merge($p, array('default_style')), 'is_default_style', __('Dùng style mặc định', 'chinhtoa'), ct_opt_val($g, array('default_style', 'is_default_style'), 'y'), 'yn', $disabled);
            ct_sec_input(array_merge($p, array('default_style', 'n')), 'bgcolor', __('Màu nền', 'chinhtoa'), ct_opt_val($g, array('default_style', 'n', 'bgcolor'), ''), 'text', $disabled);
            ct_sec_input(array_merge($p, array('default_style', 'n')), 'textcolor', __('Màu chữ', 'chinhtoa'), ct_opt_val($g, array('default_style', 'n', 'textcolor'), ''), 'text', $disabled);
        } elseif ('temp6' === $tpl) {
            ct_sec_input($p, 'cats', __('Chuyên mục mặc định', 'chinhtoa'), isset($g['cats']) ? $g['cats'] : '', 'taxonomy', $disabled);
            ct_render_tab_list($p, isset($g['tab_list']) && is_array($g['tab_list']) ? $g['tab_list'] : array(), $disabled);
        } else {
            ct_sec_input($p, 'cats', __('Chuyên mục', 'chinhtoa'), isset($g['cats']) ? $g['cats'] : '', 'taxonomy', $disabled);
            ct_sec_input($p, 'num_post', __('Số bài hiển thị', 'chinhtoa'), isset($g['num_post']) ? $g['num_post'] : 6, 'slider', $disabled);
        }

        // show_readmore (temp0..temp5)
        if ('temp6' !== $tpl) {
            ct_sec_input(array_merge($p, array('show_readmore')), 'action_show', __('Hiện nút "Xem thêm"', 'chinhtoa'), ct_opt_val($g, array('show_readmore', 'action_show'), 'n'), 'yn', $disabled);
            ct_sec_input(array_merge($p, array('show_readmore', 'y')), 'readmore_text', __('Chữ trên nút', 'chinhtoa'), ct_opt_val($g, array('show_readmore', 'y', 'readmore_text'), ''), 'text', $disabled);
            ct_sec_input(array_merge($p, array('show_readmore', 'y')), 'readmore_link', __('Link của nút', 'chinhtoa'), ct_opt_val($g, array('show_readmore', 'y', 'readmore_link'), ''), 'text', $disabled);
            ct_sec_input(array_merge($p, array('show_readmore', 'y')), 'readmore_blank', __('Mở ở tab mới', 'chinhtoa'), ct_opt_val($g, array('show_readmore', 'y', 'readmore_blank'), 'n'), 'yn', $disabled);
        }

        echo '</div>';
    }

    echo '</div></div>';
}

/** A single labelled input inside a section row. */
function ct_sec_input($pathPrefix, $key, $label, $value, $type, $disabled = '')
{
    $name = ct_opt_name(array_merge((array) $pathPrefix, array($key)));
    $wide = in_array($type, array('textarea', 'taxonomy'), true) ? ' ct-sec-field--wide' : '';
    echo '<p class="ct-sec-field' . $wide . '"><label>' . esc_html($label) . '<br>';
    if ('textarea' === $type) {
        printf('<textarea class="large-text" rows="4" name="%s"%s>%s</textarea>', esc_attr($name), $disabled, esc_textarea($value));
    } elseif ('number' === $type) {
        printf('<input type="number" class="small-text" name="%s" value="%s"%s>', esc_attr($name), esc_attr($value), $disabled);
    } elseif ('slider' === $type) {
        ct_slider_control($name, $value, 0, 30, $disabled);
    } elseif ('taxonomy' === $type) {
        ct_taxonomy_control($name, $value, $disabled);
    } elseif ('yn' === $type) {
        printf('<select name="%s"%s><option value="y"%s>Có</option><option value="n"%s>Không</option></select>', esc_attr($name), $disabled, selected($value, 'y', false), selected($value, 'n', false));
    } else {
        printf('<input type="text" class="regular-text" name="%s" value="%s"%s>', esc_attr($name), esc_attr($value), $disabled);
    }
    echo '</label></p>';
}

/** temp6 tab_list nested repeater. */
function ct_render_tab_list($pathPrefix, $tabs, $disabled = '')
{
    echo '<div class="ct-tablist" data-name="' . esc_attr(ct_opt_name(array_merge((array) $pathPrefix, array('tab_list')))) . '">';
    echo '<p><strong>' . esc_html__('Danh sách Tabs', 'chinhtoa') . '</strong></p><div class="ct-tablist-items">';
    if (empty($tabs)) {
        $tabs = array();
    }
    $j = 0;
    foreach ($tabs as $tab) {
        ct_render_tab_item($pathPrefix, $j++, $tab, $disabled);
    }
    echo '</div><button type="button" class="button ct-tab-add">' . esc_html__('+ Thêm Tab', 'chinhtoa') . '</button></div>';
}

function ct_render_tab_item($pathPrefix, $j, $tab = array(), $disabled = '')
{
    $base = array_merge((array) $pathPrefix, array('tab_list', $j));
    echo '<div class="ct-tab-item">';
    printf('<input type="text" class="ct-tab-title" placeholder="%s" name="%s" value="%s"%s>', esc_attr__('Tên Tab', 'chinhtoa'), esc_attr(ct_opt_name(array_merge($base, array('tab_title')))), esc_attr(isset($tab['tab_title']) ? $tab['tab_title'] : ''), $disabled);
    ct_taxonomy_control(ct_opt_name(array_merge($base, array('tab_cats'))), isset($tab['tab_cats']) ? $tab['tab_cats'] : '', $disabled);
    echo ' <button type="button" class="button-link-delete ct-tab-remove">×</button></div>';
}

function ct_section_home_sec($s)
{
    $sections = ct_opt_val($s, array('home_sec'), array());
    if (!is_array($sections)) {
        $sections = array();
    }
    echo '<h2>' . esc_html__('Khối nội dung trang chủ', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Thêm và sắp xếp các khối nội dung hiển thị lần lượt trên trang chủ. Kéo biểu tượng ✥ để đổi thứ tự; chọn mẫu khối ở ô bên trái mỗi khối.', 'chinhtoa'));
    echo '<div id="ct-home-sec" class="ct-repeater">';
    echo '<div class="ct-sec-list">';
    $i = 0;
    foreach ($sections as $row) {
        ct_render_home_sec_row($i++, $row);
    }
    echo '</div>';
    echo '<button type="button" class="button button-secondary ct-sec-add">' . esc_html__('+ Thêm khối', 'chinhtoa') . '</button>';
    // JS template for new rows.
    echo '<script type="text/html" id="tmpl-ct-sec-row">';
    ct_render_home_sec_row('__I__');
    echo '</script>';
    echo '<script type="text/html" id="tmpl-ct-tab-item">';
    ct_render_tab_item(array('home_sec', '__I__', 'content_type', 'temp6'), '__J__');
    echo '</script>';
    echo '</div>';
}

/* ------------------------------------------------------------------ Default */
function ct_section_default($s)
{
    echo '<h2>' . esc_html__('Trang Chuyên mục', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Bố cục mặc định cho trang danh sách bài theo chuyên mục (áp dụng khi chuyên mục không có thiết lập riêng).', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';
    ct_field_image_radio($s, array('cat_data', 'columns'), __('Số cột danh sách', 'chinhtoa'), array(
        'c1' => ct_opt_img('/layouts/one-column.png'),
        'c2' => ct_opt_img('/layouts/two-column.png'),
        'c3' => ct_opt_img('/layouts/three-column.png'),
        'c4' => ct_opt_img('/layouts/four-column.png'),
    ), __('Số cột xếp các bài viết trong danh sách.', 'chinhtoa'), null, 'icon');
    ct_field_image_radio($s, array('cat_data', 'display_style'), __('Kiểu trình bày', 'chinhtoa'), array(
        'c1' => ct_opt_img('/options/category-style-1.jpg'),
        'c2' => ct_opt_img('/options/category-style-2.jpg'),
    ), __('Cách trình bày mỗi bài trong danh sách.', 'chinhtoa'));
    ct_field_switch($s, array('cat_data', 'sidebar', 'action_show'), __('Hiển thị thanh bên', 'chinhtoa'));
    ct_field_select($s, array('cat_data', 'sidebar', 'y', 'sidebar_pos'), __('Vị trí thanh bên', 'chinhtoa'), array(
        'left' => __('Bên trái', 'chinhtoa'), 'right' => __('Bên phải', 'chinhtoa'),
    ), '', array(array('cat_data', 'sidebar', 'action_show'), 'y'));
    echo '</tbody></table>';

    echo '<h2>' . esc_html__('Trang Bài viết', 'chinhtoa') . '</h2>';
    ct_section_intro(__('Bố cục mặc định cho trang đọc chi tiết một bài viết.', 'chinhtoa'));
    echo '<table class="form-table"><tbody>';
    ct_field_switch($s, array('post_data', 'sidebar', 'action_show'), __('Hiển thị thanh bên', 'chinhtoa'));
    ct_field_select($s, array('post_data', 'sidebar', 'y', 'sidebar_pos'), __('Vị trí thanh bên', 'chinhtoa'), array(
        'left' => __('Bên trái', 'chinhtoa'), 'right' => __('Bên phải', 'chinhtoa'),
    ), '', array(array('post_data', 'sidebar', 'action_show'), 'y'));
    echo '</tbody></table>';
}
