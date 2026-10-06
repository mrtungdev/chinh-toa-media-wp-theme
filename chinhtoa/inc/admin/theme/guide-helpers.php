<?php

/**
 * Trung tâm Hướng dẫn (Help center) cho trang admin `theme-options`.
 *
 * Mỗi mục hướng dẫn là một file Markdown trong inc/admin/theme/docs/. File này
 * giữ "registry" (thứ tự + nhóm + tiêu đề + icon) và pipeline render: đọc .md ->
 * Parsedown -> wp_kses_post -> cache transient -> xuất ra giao diện nhiều trang
 * (menu trái + panel nội dung, do theme-guide.php lắp ráp).
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
 * Nhãn các nhóm mục (in làm tiêu đề nhóm trên menu trái).
 *
 * Khoá nhóm khớp với khoá 'group' trong ct_guide_sections(). Thứ tự hiển thị trên
 * menu do thứ tự các mục trong ct_guide_sections() quyết định; map này chỉ cấp
 * nhãn. Nhóm có nhãn '' (hoặc không khai báo) sẽ không in tiêu đề nhóm.
 *
 * @return array<string,string>
 */
function ct_guide_groups()
{
    return array(
        'batdau'   => __('Bắt đầu', 'chinhtoa'),
        'noidung'  => __('Bài viết & Nội dung', 'chinhtoa'),
        'giaodien' => __('Giao diện & Trang chủ', 'chinhtoa'),
        'trogiup'  => __('Trợ giúp', 'chinhtoa'),
    );
}

/**
 * Danh sách các mục hướng dẫn theo thứ tự hiển thị.
 *
 * Tiêu đề (translatable) + dashicon + nhóm nằm ở đây; nội dung nằm trong
 * docs/<file>. 'slug' là định danh ổn định dùng cho deep-link (#guide-<slug>),
 * nên giữ nguyên slug kể cả khi đổi tên file .md.
 *
 * @return array<int,array<string,string>> Mỗi phần tử: slug, title, icon, file, group.
 */
function ct_guide_sections()
{
    return array(
        array('slug' => 'bat-dau',      'title' => __('Bắt đầu nhanh', 'chinhtoa'),                        'icon' => 'dashicons-flag',              'file' => '01-bat-dau.md',               'group' => 'batdau'),
        array('slug' => 'bai-viet',     'title' => __('Viết & quản lý bài viết', 'chinhtoa'),              'icon' => 'dashicons-edit',              'file' => '02-viet-bai-viet.md',         'group' => 'noidung'),
        array('slug' => 'phan-loai',    'title' => __('Phân loại bài viết: Lời Chúa & Video', 'chinhtoa'),  'icon' => 'dashicons-tag',               'file' => '03-phan-loai-bai-viet.md',    'group' => 'noidung'),
        array('slug' => 'loichua-card', 'title' => __('Thẻ Lời Chúa & Box “5 phút”', 'chinhtoa'),          'icon' => 'dashicons-format-quote',      'file' => '04-the-loichua-box-5phut.md', 'group' => 'noidung'),
        array('slug' => 'loichua-hom-nay', 'title' => __('Lời Chúa hôm nay & Lịch', 'chinhtoa'),         'icon' => 'dashicons-calendar-alt',      'file' => '15-loi-chua-hom-nay.md',      'group' => 'noidung'),
        array('slug' => 'chuyen-muc',   'title' => __('Chuyên mục', 'chinhtoa'),                           'icon' => 'dashicons-category',          'file' => '05-chuyen-muc.md',            'group' => 'noidung'),
        array('slug' => 'media',        'title' => __('Thư viện hình ảnh', 'chinhtoa'),                    'icon' => 'dashicons-format-gallery',    'file' => '06-thu-vien-media.md',        'group' => 'noidung'),
        array('slug' => 'giao-dien',    'title' => __('Màu sắc & bố cục chung', 'chinhtoa'),               'icon' => 'dashicons-admin-customizer',  'file' => '07-mau-sac-bo-cuc.md',        'group' => 'giaodien'),
        array('slug' => 'header',       'title' => __('Header (đầu trang)', 'chinhtoa'),                   'icon' => 'dashicons-cover-image',       'file' => '08-header.md',                'group' => 'giaodien'),
        array('slug' => 'footer',       'title' => __('Footer (cuối trang)', 'chinhtoa'),                  'icon' => 'dashicons-editor-insertmore', 'file' => '09-footer.md',                'group' => 'giaodien'),
        array('slug' => 'trang-chu',    'title' => __('Bố cục Trang chủ', 'chinhtoa'),                     'icon' => 'dashicons-admin-home',        'file' => '10-trang-chu.md',             'group' => 'giaodien'),
        array('slug' => 'menu',         'title' => __('Menu điều hướng', 'chinhtoa'),                      'icon' => 'dashicons-menu-alt3',         'file' => '11-menu-dieu-huong.md',       'group' => 'giaodien'),
        array('slug' => 'widget',       'title' => __('Thanh bên & Widget', 'chinhtoa'),                   'icon' => 'dashicons-welcome-widgets-menus', 'file' => '16-thanh-ben-widget.md',  'group' => 'giaodien'),
        array('slug' => 'thong-bao',    'title' => __('Thanh thông báo', 'chinhtoa'),                      'icon' => 'dashicons-megaphone',         'file' => '12-thanh-thong-bao.md',       'group' => 'giaodien'),
        array('slug' => 'faq',          'title' => __('Câu hỏi thường gặp', 'chinhtoa'),                   'icon' => 'dashicons-editor-help',       'file' => '13-cau-hoi-thuong-gap.md',    'group' => 'trogiup'),
        array('slug' => 'ho-tro',       'title' => __('Hỗ trợ kỹ thuật', 'chinhtoa'),                      'icon' => 'dashicons-sos',               'file' => '14-ho-tro-ky-thuat.md',       'group' => 'trogiup'),
    );
}

/**
 * Trả về các mục có nội dung đọc được (loại các mục thiếu/không đọc được file .md).
 *
 * Dùng chung cho cả menu trái và panel để menu không trỏ tới panel rỗng. Kết quả
 * markdown được cache transient nên gọi nhiều lần vẫn rẻ.
 *
 * @return array<int,array<string,string>>
 */
function ct_guide_visible_sections()
{
    return array_values(array_filter(ct_guide_sections(), function ($section) {
        return ct_guide_render_markdown($section['file']) !== '';
    }));
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
    // 'r2': phiên bản bộ hiển thị (token ảnh, kích thước ảnh). Đổi khi sửa pipeline render
    // để cache cũ tự hết hiệu lực.
    $key = 'ct_guide_html_' . md5($file . '|' . filemtime($path) . '|' . $ver . '|r2');

    $cached = get_transient($key);
    if ($cached !== false) {
        return $cached;
    }

    require_once CT_THEME_DIR . '/inc/vendor/Parsedown.php';
    $pd = new Parsedown();
    $pd->setSafeMode(true); // lớp bảo vệ thứ 2 cạnh wp_kses_post

    $md   = ct_guide_expand_tokens((string) file_get_contents($path));
    $html = wp_kses_post($pd->text($md));
    // Ảnh minh hoạ: tải lười + không chặn hiển thị chữ.
    $html = str_replace('<img ', '<img loading="lazy" decoding="async" ', $html);
    $html = ct_guide_size_images($html);
    set_transient($key, $html, WEEK_IN_SECONDS);

    return $html;
}

/**
 * Thay các token trong tệp .md trước khi parse:
 *  - {{img}} → URL thư mục ảnh hướng dẫn (assets/imgs/guide), VD
 *    `![Mô tả]({{img}}/bang-dieu-khien.webp)`.
 *
 * @param string $md Nội dung Markdown.
 * @return string
 */
function ct_guide_expand_tokens($md)
{
    $imgs = defined('CT_THEME_IMGS_URI') ? CT_THEME_IMGS_URI : get_template_directory_uri() . '/assets/imgs';
    return str_replace('{{img}}', esc_url_raw($imgs . '/guide'), $md);
}

/**
 * Ảnh hướng dẫn được chụp ở độ nét gấp đôi (2x). Gắn width/height = một nửa kích thước
 * thật để ảnh nhỏ (một ô, một nút) không bị phóng to quá cỡ trên màn hình; ảnh lớn vẫn
 * co theo khung nhờ CSS max-width: 100%.
 *
 * @param string $html HTML đã sanitize.
 * @return string
 */
function ct_guide_size_images($html)
{
    $dir = CT_THEME_DIR . '/assets/imgs/guide/';
    return preg_replace_callback('#<img ([^>]*?)src="([^"]+/guide/([a-z0-9-]+\.webp))"#', function ($m) use ($dir) {
        $file = $dir . $m[3];
        $size = is_readable($file) ? @getimagesize($file) : false;
        if (!$size || empty($size[0])) {
            return $m[0];
        }
        $w = (int) round($size[0] / 2);
        $h = (int) round($size[1] / 2);
        return '<img ' . $m[1] . 'width="' . $w . '" height="' . $h . '" src="' . $m[2] . '"';
    }, $html);
}

/**
 * In menu điều hướng bên trái cho trang hướng dẫn.
 *
 * Gom các mục theo 'group' (in tiêu đề nhóm mỗi khi nhóm đổi). Mục đầu tiên được
 * đánh dấu is-active + aria-current; JS sẽ điều chỉnh theo #hash khi tải trang.
 * Mỗi liên kết có id="guidetab-<slug>" để panel tham chiếu qua aria-labelledby.
 *
 * @param array $sections Danh sách mục đã lọc (thường là ct_guide_visible_sections()).
 * @return void
 */
function ct_guide_render_nav($sections)
{
    $groups  = ct_guide_groups();
    $current = null;
    $first   = true;

    echo '<ul class="ct-guide-navlist">';
    foreach ($sections as $section) {
        $group = isset($section['group']) ? $section['group'] : '';
        if ($group !== $current) {
            $current = $group;
            $label   = isset($groups[$group]) ? $groups[$group] : '';
            if ($label !== '') {
                echo '<li class="ct-guide-navgroup">' . esc_html($label) . '</li>';
            }
        }
        $slug = $section['slug'];
        ?>
        <li>
            <a id="guidetab-<?php echo esc_attr($slug); ?>" class="ct-guide-navlink<?php echo $first ? ' is-active' : ''; ?>" href="#guide-<?php echo esc_attr($slug); ?>" data-slug="<?php echo esc_attr($slug); ?>"<?php echo $first ? ' aria-current="page"' : ''; ?>>
                <span class="dashicons <?php echo esc_attr($section['icon']); ?>"></span>
                <span class="ct-guide-navtext"><?php echo esc_html($section['title']); ?></span>
            </a>
        </li>
        <?php
        $first = false;
    }
    echo '</ul>';
}

/**
 * In một panel nội dung (một "trang" hướng dẫn).
 *
 * Body dùng lại HTML từ ct_guide_render_markdown() (file .md đã có sẵn tiêu đề
 * `## ...`, nên panel không in lại tiêu đề — icon + tên nằm ở menu trái). Bỏ qua
 * lặng nếu file .md thiếu, để các mục còn lại vẫn hiển thị bình thường.
 *
 * @param array $section Một phần tử của ct_guide_sections().
 * @param bool  $active  Panel đang mở (hiển thị) hay ẩn.
 * @return void
 */
function ct_guide_render_pane($section, $active = false)
{
    $html = ct_guide_render_markdown($section['file']);
    if ($html === '') {
        return;
    }
    ?>
    <section class="ct-guide-pane<?php echo $active ? ' is-active' : ''; ?>" id="guide-<?php echo esc_attr($section['slug']); ?>" tabindex="-1" aria-labelledby="guidetab-<?php echo esc_attr($section['slug']); ?>">
        <div class="ct-guide-body"><?php echo $html; // đã qua wp_kses_post ?></div>
    </section>
    <?php
}
