<?php

/**
 * Trung tâm Hướng dẫn (Help center) cho trang admin `theme-options`.
 *
 * Mỗi mục hướng dẫn là một file Markdown trong inc/admin/theme/docs/. File này
 * giữ "registry" (thứ tự + tiêu đề + icon) và pipeline render: đọc .md ->
 * Parsedown -> wp_kses_post -> cache transient -> bọc trong <details> accordion.
 *
 * Parsedown chỉ được nạp khi cache miss (lazy require), nên không ảnh hưởng các
 * trang admin khác hay front-end.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Danh sách các mục hướng dẫn theo thứ tự hiển thị.
 *
 * Tiêu đề (translatable) + dashicon nằm ở đây; nội dung nằm trong docs/<file>.
 *
 * @return array<int,array<string,string>> Mỗi phần tử: slug, title, icon, file.
 */
function ct_guide_sections()
{
    return array(
        array('slug' => 'giao-dien',  'title' => __('Thiết lập giao diện', 'chinhtoa'),  'icon' => 'dashicons-admin-customizer', 'file' => '01-thiet-lap-giao-dien.md'),
        array('slug' => 'bai-viet',   'title' => __('Quản lý bài viết', 'chinhtoa'),     'icon' => 'dashicons-edit',             'file' => '02-quan-ly-bai-viet.md'),
        array('slug' => 'chuyen-muc', 'title' => __('Chuyên mục', 'chinhtoa'),           'icon' => 'dashicons-category',         'file' => '03-chuyen-muc.md'),
        array('slug' => 'menu',       'title' => __('Menu điều hướng', 'chinhtoa'),      'icon' => 'dashicons-menu-alt3',        'file' => '04-menu-dieu-huong.md'),
        array('slug' => 'thong-bao',  'title' => __('Thanh thông báo', 'chinhtoa'),      'icon' => 'dashicons-megaphone',        'file' => '05-thanh-thong-bao.md'),
        array('slug' => 'media',      'title' => __('Thư viện hình ảnh', 'chinhtoa'),    'icon' => 'dashicons-format-gallery',   'file' => '06-thu-vien-media.md'),
        array('slug' => 'trang-chu',  'title' => __('Bố cục Trang chủ', 'chinhtoa'),     'icon' => 'dashicons-admin-home',       'file' => '07-trang-chu.md'),
        array('slug' => 'faq',        'title' => __('Câu hỏi thường gặp', 'chinhtoa'),   'icon' => 'dashicons-editor-help',      'file' => '08-cau-hoi-thuong-gap.md'),
        array('slug' => 'ho-tro',     'title' => __('Hỗ trợ kỹ thuật', 'chinhtoa'),      'icon' => 'dashicons-sos',              'file' => '09-ho-tro-ky-thuat.md'),
    );
}

/**
 * Đọc một file docs/*.md, parse sang HTML đã sanitize, có transient cache.
 *
 * Cache key gồm filemtime + THEME_VERSION nên sửa file .md (hoặc cập nhật theme)
 * sẽ tự làm mới, không cần xoá cache thủ công. Parsedown chỉ nạp khi cache miss.
 *
 * @param string $file Tên file trong inc/admin/theme/docs/.
 * @return string HTML đã sanitize ('' nếu file thiếu/không đọc được).
 */
function ct_guide_render_markdown($file)
{
    // basename() chặn path traversal (dù $file luôn đến từ registry tin cậy).
    $path = CT_THEME_DIR . '/inc/admin/theme/docs/' . basename($file);
    if (!is_readable($path)) {
        return '';
    }

    $ver = defined('THEME_VERSION') ? THEME_VERSION : '0';
    $key = 'ct_guide_html_' . md5($file . '|' . filemtime($path) . '|' . $ver);

    $cached = get_transient($key);
    if ($cached !== false) {
        return $cached;
    }

    require_once CT_THEME_DIR . '/inc/vendor/Parsedown.php';
    $pd = new Parsedown();
    $pd->setSafeMode(true); // lớp bảo vệ thứ 2 cạnh wp_kses_post

    $html = wp_kses_post($pd->text((string) file_get_contents($path)));
    set_transient($key, $html, WEEK_IN_SECONDS);

    return $html;
}

/**
 * Echo một mục hướng dẫn dạng <details> accordion.
 *
 * Bỏ qua lặng (không cảnh báo) nếu file .md tương ứng thiếu, để các mục còn lại
 * vẫn hiển thị bình thường.
 *
 * @param array $section Một phần tử của ct_guide_sections().
 * @param bool  $open    Mở sẵn (expanded) hay không.
 * @return void
 */
function ct_guide_render_section($section, $open = false)
{
    $html = ct_guide_render_markdown($section['file']);
    if ($html === '') {
        return;
    }
    ?>
    <details class="ct-guide-section" id="guide-<?php echo esc_attr($section['slug']); ?>"<?php echo $open ? ' open' : ''; ?>>
        <summary class="ct-guide-summary">
            <span class="dashicons <?php echo esc_attr($section['icon']); ?>"></span>
            <span class="ct-guide-title"><?php echo esc_html($section['title']); ?></span>
        </summary>
        <div class="ct-guide-body"><?php echo $html; // đã qua wp_kses_post ?></div>
    </details>
    <?php
}
