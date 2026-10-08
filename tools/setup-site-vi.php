<?php
/**
 * Thiết lập chuẩn cho một site dùng theme Chính Tòa Media: tiếng Việt, site Công giáo, SEO (Yoast).
 *
 * - Idempotent: chạy lại bao nhiêu lần cũng được, KHÔNG xoá nội dung.
 * - Giá trị riêng của site lấy từ chính site (tên, khẩu hiệu, logo header) hoặc biến môi trường.
 * - Chạy SAU seed demo (seed tạo lại trang chủ nên mất meta Yoast của trang chủ).
 *
 * Chạy (thư mục gốc WordPress, môi trường wp-cli của Local):
 *   source ~/Local\ Sites/<site>/app/.envrc && cd ~/Local\ Sites/<site>/app/public
 *   CT_HOME_DESC="…" CT_OG_IMAGE=/path/og.jpg [CT_BRAND=/path/brand.php] [CT_ICON=/path/icon.png] \
 *     wp eval-file /path/to/tools/setup-site-vi.php && wp rewrite flush && wp yoast index
 *   (`wp yoast index` chỉ chạy trên môi trường production; ở Local Yoast tự dựng chỉ mục khi trang được xem.)
 *
 * Gói tiếng Việt (cần mạng) cài riêng:
 *   wp language core install vi --activate && wp language plugin install --all vi
 *
 * Không thuộc gói theme (nằm ngoài thư mục chinhtoa/).
 */

if (!defined('ABSPATH') || !defined('WP_CLI')) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$homeDesc = trim((string) getenv('CT_HOME_DESC'));
$ogImage  = (string) getenv('CT_OG_IMAGE');
$brand    = (string) getenv('CT_BRAND');
$icon     = (string) getenv('CT_ICON');

/**
 * Đưa file vào Thư viện Media một lần: lần sau tìm lại theo meta _ct_setup_source (tên file + md5)
 * thay vì nạp trùng.
 */
function ct_setup_attach($path, $title)
{
    if (!is_readable($path)) {
        WP_CLI::warning('Không đọc được ' . $path);
        return 0;
    }
    $key   = basename($path) . ':' . md5_file($path);
    $found = get_posts(array(
        'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'fields' => 'ids',
        'meta_key' => '_ct_setup_source', 'meta_value' => $key,
    ));
    if ($found) {
        return (int) $found[0];
    }
    $tmp = wp_tempnam(basename($path));
    copy($path, $tmp);
    $id = media_handle_sideload(array('name' => basename($path), 'tmp_name' => $tmp), 0, $title);
    if (is_wp_error($id)) {
        @unlink($tmp);
        WP_CLI::warning('Không nạp được ' . basename($path) . ': ' . $id->get_error_message());
        return 0;
    }
    update_post_meta($id, '_wp_attachment_image_alt', $title);
    update_post_meta($id, '_ct_setup_source', $key);
    return (int) $id;
}

$siteName = get_option('blogname');
$oldCatBase = home_url('/category/');
$newCatBase = home_url('/');

/* ---------------------------------------------------------- 1. Cài đặt chung */

WP_CLI::log('1/7 Cài đặt chung (giờ Việt Nam, ngày d/m/Y)…');
update_option('timezone_string', 'Asia/Ho_Chi_Minh');
update_option('gmt_offset', '');
update_option('date_format', 'd/m/Y');
update_option('time_format', 'H:i');
update_option('start_of_week', 1);

/* ------------------------------------------------------- 2. Tắt bình luận */

WP_CLI::log('2/7 Tắt bình luận, pingback/trackback…');
update_option('default_comment_status', 'closed');
update_option('default_ping_status', 'closed');
update_option('default_pingback_flag', 0);
update_option('show_avatars', 0);
global $wpdb;
$closed = $wpdb->query("UPDATE {$wpdb->posts} SET comment_status = 'closed', ping_status = 'closed' WHERE comment_status <> 'closed' OR ping_status <> 'closed'");
WP_CLI::log("   đóng bình luận cho {$closed} bài/trang/media");

/* ------------------------------------------------ 3. Thương hiệu riêng của site */

WP_CLI::log('3/7 Thương hiệu, biểu tượng site…');
if ($brand !== '') {
    wp_mkdir_p(WPMU_PLUGIN_DIR);
    // ntts-brand.php → ct-brand-ntts.php (cùng tên file seed-ntts.php chép, nên ghi đè chứ không nhân đôi).
    $dest = trailingslashit(WPMU_PLUGIN_DIR) . 'ct-brand-' . sanitize_title(preg_replace('/-brand$/', '', basename($brand, '.php'))) . '.php';
    if (copy($brand, $dest)) {
        WP_CLI::log('   mu-plugin → ' . $dest);
    } else {
        WP_CLI::warning('Không chép được ' . $brand);
    }
}
if ($icon !== '') {
    $iconId = ct_setup_attach($icon, 'Biểu tượng ' . $siteName);
    if ($iconId) {
        update_option('site_icon', $iconId);
    }
}

/* -------------------------------------------------------------- 4. Yoast SEO */

WP_CLI::log('4/7 Yoast SEO: bỏ /category/, tiêu đề + breadcrumb tiếng Việt, tổ chức, ảnh chia sẻ…');
if (!defined('WPSEO_VERSION')) {
    WP_CLI::warning('Yoast SEO chưa bật — bỏ qua bước SEO.');
} else {
    $ct      = get_option('ct_settings');
    $logoUrl = isset($ct['header_data']['c_brand']['logo']['url']) ? (string) $ct['header_data']['c_brand']['logo']['url'] : '';
    $logoId  = $logoUrl !== '' ? attachment_url_to_postid($logoUrl) : 0;

    $titles = (array) get_option('wpseo_titles', array());
    $titles = array_merge($titles, array(
        // URL chuyên mục không có /category/ (Yoast tự chuyển hướng 301 link cũ).
        'stripcategorybase'         => true,
        // Tiêu đề trang (thẻ <title>) — bỏ "Archives", "You searched for", "Author at"…
        'title-tax-category'        => '%%term_title%% %%page%% %%sep%% %%sitename%%',
        'title-tax-post_tag'        => '%%term_title%% %%page%% %%sep%% %%sitename%%',
        'title-search-wpseo'        => 'Tìm kiếm: %%searchphrase%% %%page%% %%sep%% %%sitename%%',
        'title-404-wpseo'           => 'Không tìm thấy trang %%sep%% %%sitename%%',
        'title-author-wpseo'        => '%%name%% %%page%% %%sep%% %%sitename%%',
        'title-archive-wpseo'       => 'Lưu trữ %%date%% %%page%% %%sep%% %%sitename%%',
        'metadesc-tax-category'     => '%%category_description%%',
        // Breadcrumb tiếng Việt.
        'breadcrumbs-enable'        => true,
        'breadcrumbs-home'          => 'Trang chủ',
        'breadcrumbs-archiveprefix' => 'Lưu trữ',
        'breadcrumbs-searchprefix'  => 'Kết quả tìm kiếm cho',
        'breadcrumbs-404crumb'      => 'Lỗi 404: Không tìm thấy trang',
        'breadcrumbs-prefix'        => '',
        // Site một tác giả: tắt trang tác giả / theo ngày / định dạng (chuyển về trang chủ).
        'disable-author'            => true,
        'disable-date'              => true,
        'disable-post_format'       => true,
        'noindex-tax-post_tag'      => true,
        // Thông tin tổ chức (Schema.org) cho Google.
        'company_or_person'         => 'company',
        'company_name'              => $siteName,
        'website_name'              => $siteName,
        'company_logo'              => $logoUrl,
        'company_logo_id'           => $logoId,
    ));
    update_option('wpseo_titles', $titles);

    if ($ogImage !== '') {
        $ogId = ct_setup_attach($ogImage, $siteName);
        if ($ogId) {
            $social = (array) get_option('wpseo_social', array());
            $social['og_default_image']    = wp_get_attachment_url($ogId);
            $social['og_default_image_id'] = $ogId;
            $social['opengraph']           = true;
            update_option('wpseo_social', $social);
        }
    }

    // Trang chủ tĩnh: "Tên site – Khẩu hiệu" thay cho "Trang chủ - Tên site".
    $front = (int) get_option('page_on_front');
    if ($front) {
        update_post_meta($front, '_yoast_wpseo_title', '%%sitename%% %%sep%% %%sitedesc%%');
        if ($homeDesc !== '') {
            update_post_meta($front, '_yoast_wpseo_metadesc', $homeDesc);
        }
    }
}

/* ------------------------------------------- 5. CSS riêng của site lên <head> */

WP_CLI::log('5/7 Chuyển CSS riêng của site từ cuối trang lên <head> (hết nháy giao diện)…');
$ct = get_option('ct_settings');
if (is_array($ct) && !empty($ct['tech_data']['footerscripts'])) {
    $foot = (string) $ct['tech_data']['footerscripts'];
    if (preg_match_all('#<style\b[^>]*>.*?</style>#is', $foot, $m)) {
        $head = isset($ct['tech_data']['headscripts']) ? (string) $ct['tech_data']['headscripts'] : '';
        foreach ($m[0] as $block) {
            if (strpos($head, $block) === false) {
                $head .= ($head !== '' ? "\n" : '') . $block;
            }
            $foot = str_replace($block, '', $foot);
        }
        $ct['tech_data']['headscripts']   = $head;
        $ct['tech_data']['footerscripts'] = trim($foot);
        update_option('ct_settings', $ct);
        WP_CLI::log('   đã chuyển ' . count($m[0]) . ' khối <style>');
    }
}

/* ------------------------------------- 6. Link cứng có /category/ → link mới */

WP_CLI::log('6/7 Đổi link cứng /category/… sang link mới…');
$fixed = 0;
$swap  = function ($value) use ($oldCatBase, $newCatBase, &$fixed) {
    if (is_string($value) && strpos($value, $oldCatBase) !== false) {
        $fixed++;
        return str_replace($oldCatBase, $newCatBase, $value);
    }
    return $value;
};
$walk = function ($data) use (&$walk, $swap) {
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = $walk($v);
        }
        return $data;
    }
    return $swap($data);
};
// Nút "Xem thêm" của khối trang chủ + widget HTML.
foreach (array('ct_settings', 'widget_custom_html', 'widget_text') as $opt) {
    $val = get_option($opt);
    if (is_array($val)) {
        update_option($opt, $walk($val));
    }
}
// Mục menu kiểu "Liên kết tự tạo".
foreach ($wpdb->get_results($wpdb->prepare("SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_menu_item_url' AND meta_value LIKE %s", '%' . $wpdb->esc_like($oldCatBase) . '%')) as $row) {
    update_post_meta($row->post_id, '_menu_item_url', $swap($row->meta_value));
}
// Link trong nội dung bài/trang.
$fixed += (int) $wpdb->query($wpdb->prepare(
    "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s) WHERE post_content LIKE %s",
    $oldCatBase, $newCatBase, '%' . $wpdb->esc_like($oldCatBase) . '%'
));
WP_CLI::log("   đã sửa {$fixed} link");

/* ---------------------------------------------------------------- 7. Dọn */

WP_CLI::log('7/7 Dọn cache, làm mới đường dẫn…');
// Quy tắc URL mới (bỏ /category/) chỉ có hiệu lực khi Yoast nạp lại → xoá để WP dựng lại ở request sau.
delete_option('rewrite_rules');
if (function_exists('ct_flush_post_caches')) {
    ct_flush_post_caches();
}
wp_cache_flush();

WP_CLI::success('Xong. Tiếp theo: wp rewrite flush && wp yoast index');
