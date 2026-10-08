<?php
/**
 * Nạp dữ liệu mẫu "Hiệp Hội Nữ Tỳ Thừa Sai Thánh Giá" cho site thử nghiệm
 * (nutythuasaithanhgia.local). Cùng cách làm với seed-5plc.php.
 *
 * CẢNH BÁO: XOÁ TOÀN BỘ nội dung hiện có (bài, trang, media, chuyên mục, thẻ, menu,
 * widget) rồi tạo lại. Giữ nguyên user, plugin, kit Elementor. Sao lưu DB trước khi chạy.
 *
 * Chạy (từ thư mục gốc WordPress, có môi trường wp-cli của Local):
 *   source ~/Local\ Sites/nutythuasaithanhgia/app/.envrc && cd ~/Local\ Sites/nutythuasaithanhgia/app/public
 *   CT_DEMO_DIR=/path/to/tools/demo wp eval-file /path/to/tools/demo/seed-ntts.php
 * Tuỳ chọn: CT_DEMO_LOGO=/path/logo.png (mặc định: assets/logo-ntts.png).
 *
 * Ngoài dữ liệu, script chép ntts-brand.php vào wp-content/mu-plugins/ (ghi đè ct_brand
 * cho riêng site này) và đặt assets/icon-ntts.png làm Site Icon.
 *
 * Không thuộc gói theme (nằm ngoài thư mục chinhtoa/).
 */

if (!defined('ABSPATH') || !defined('WP_CLI')) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$CT_DIR = getenv('CT_DEMO_DIR') ?: __DIR__;
require_once $CT_DIR . '/lib-images.php';
$data = require $CT_DIR . '/ntts-data.php';
$logo = getenv('CT_DEMO_LOGO') ?: $CT_DIR . '/assets/logo-ntts.png';
$icon = $CT_DIR . '/assets/icon-ntts.png';

// Giờ Việt Nam trước khi tính ngày đăng bài.
update_option('timezone_string', 'Asia/Ho_Chi_Minh');
update_option('gmt_offset', '');

$tmpDir = trailingslashit(sys_get_temp_dir()) . 'ct-demo-ntts';
wp_mkdir_p($tmpDir);
$author = 1;
$now    = time();
$tz     = wp_timezone();
$site   = $data['site'];

/* ================================================================ helpers */

function ct_demo_log($msg)
{
    WP_CLI::log($msg);
}

/** Đưa file ảnh vào Thư viện Media (gắn với bài $parent nếu có). */
function ct_demo_attach($path, $title, $parent = 0)
{
    $tmp = wp_tempnam(basename($path));
    copy($path, $tmp);
    $id = media_handle_sideload(array('name' => basename($path), 'tmp_name' => $tmp), $parent, $title);
    if (is_wp_error($id)) {
        @unlink($tmp);
        WP_CLI::warning('Không đưa được ảnh ' . basename($path) . ': ' . $id->get_error_message());
        return 0;
    }
    update_post_meta($id, '_wp_attachment_image_alt', $title);
    return (int) $id;
}

/** Đoạn văn: $html là HTML tin cậy từ file dữ liệu (cho phép <strong>, <em>, <a>…). */
function ct_demo_p($html)
{
    return "<!-- wp:paragraph -->\n<p>" . wp_kses_post($html) . "</p>\n<!-- /wp:paragraph -->\n\n";
}

function ct_demo_h($text)
{
    return "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . esc_html($text) . "</h3>\n<!-- /wp:heading -->\n\n";
}

/** Nội dung block: đoạn văn / tiêu đề phụ / danh sách / trích dẫn / thơ / dòng ghi chú bài mẫu. */
function ct_demo_blocks(array $items, $note)
{
    $html = '';
    foreach ($items as $it) {
        if (is_string($it)) {
            $html .= ct_demo_p($it);
        } elseif (isset($it['h'])) {
            $html .= ct_demo_h($it['h']);
        } elseif (isset($it['list'])) {
            $html .= "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
            foreach ($it['list'] as $li) {
                $html .= "<!-- wp:list-item -->\n<li>" . wp_kses_post($li) . "</li>\n<!-- /wp:list-item -->";
            }
            $html .= "</ul>\n<!-- /wp:list -->\n\n";
        } elseif (isset($it['quote'])) {
            $html .= "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . esc_html($it['quote']) . "</p>\n<!-- /wp:paragraph -->"
                . (!empty($it['cite']) ? '<cite>' . esc_html($it['cite']) . '</cite>' : '') . "</blockquote>\n<!-- /wp:quote -->\n\n";
        } elseif (isset($it['verse'])) {
            $html .= "<!-- wp:verse -->\n<pre class=\"wp-block-verse\">" . esc_html($it['verse']) . "</pre>\n<!-- /wp:verse -->\n\n";
        } elseif (!empty($it['note'])) {
            $html .= ct_demo_p('<em>' . esc_html($note) . '</em>');
        }
    }
    return $html;
}

/** Khối Gallery (bấm ảnh mở cỡ lớn — theme gắn Swipebox cho link ảnh). */
function ct_demo_gallery(array $ids)
{
    $html = "<!-- wp:gallery {\"linkTo\":\"media\"} -->\n<figure class=\"wp-block-gallery has-nested-images columns-default is-cropped\">";
    foreach ($ids as $id) {
        $full  = wp_get_attachment_url($id);
        $large = wp_get_attachment_image_url($id, 'large');
        $alt   = get_post_meta($id, '_wp_attachment_image_alt', true);
        $html .= "<!-- wp:image {\"id\":{$id},\"sizeSlug\":\"large\",\"linkDestination\":\"media\"} -->\n"
            . '<figure class="wp-block-image size-large"><a href="' . esc_url($full) . '"><img src="' . esc_url($large) . '" alt="' . esc_attr($alt) . '" class="wp-image-' . $id . '"/></a></figure>'
            . "\n<!-- /wp:image -->";
    }
    return $html . "</figure>\n<!-- /wp:gallery -->\n\n";
}

/* ================================================================== wipe */

ct_demo_log('1/5 Xoá nội dung cũ…');
$types = array('post', 'page', 'attachment', 'wp_block', 'e-floating-buttons', 'wp_navigation', 'nav_menu_item', 'customize_changeset', 'oembed_cache');
$ids   = get_posts(array(
    'post_type'        => $types,
    'post_status'      => array('publish', 'future', 'draft', 'pending', 'private', 'trash', 'auto-draft', 'inherit'),
    'numberposts'      => -1,
    'fields'           => 'ids',
    'suppress_filters' => true,
));
foreach ($ids as $id) {
    if (get_post_type($id) === 'attachment') {
        wp_delete_attachment($id, true);
    } else {
        wp_delete_post($id, true);
    }
}
ct_demo_log('   đã xoá ' . count($ids) . ' bài/trang/media');

foreach (wp_get_nav_menus() as $m) {
    wp_delete_nav_menu($m->term_id);
}
foreach (get_terms(array('taxonomy' => 'post_tag', 'hide_empty' => false)) as $t) {
    wp_delete_term($t->term_id, 'post_tag');
}
// Chuyên mục mặc định không xoá được → đặt tạm một chuyên mục trung gian làm mặc
// định (để script chạy lại nhiều lần được), xoá hết, rồi xoá luôn chuyên mục tạm ở bước 2.
$tmpCat = wp_insert_term('ct-demo-tam', 'category', array('slug' => 'ct-demo-tam'));
$oldDefault = is_wp_error($tmpCat) ? (int) get_option('default_category') : (int) $tmpCat['term_id'];
update_option('default_category', $oldDefault);
foreach (get_terms(array('taxonomy' => 'category', 'hide_empty' => false)) as $t) {
    if ((int) $t->term_id !== $oldDefault) {
        wp_delete_term($t->term_id, 'category');
    }
}

// Widget: làm rỗng mọi sidebar + instance.
foreach (array('widget_block', 'widget_search', 'widget_nav_menu', 'widget_ct_postlist_widget', 'widget_ct_loichua_card', 'widget_ct_lc_calendar', 'widget_text', 'widget_custom_html', 'widget_media_image', 'widget_recent-posts', 'widget_categories', 'widget_calendar') as $opt) {
    update_option($opt, array('_multiwidget' => 1));
}

/* ======================================================= categories */

ct_demo_log('2/5 Tạo chuyên mục, trang, menu…');
$cats = array();
foreach ($data['categories'] as $slug => $c) {
    $args = array('slug' => $slug, 'description' => $c['desc']);
    if ($c['parent'] !== '') {
        $args['parent'] = $cats[$c['parent']];
    }
    $r = wp_insert_term($c['name'], 'category', $args);
    if (is_wp_error($r)) {
        WP_CLI::error('Không tạo được chuyên mục ' . $slug . ': ' . $r->get_error_message());
    }
    $cats[$slug] = (int) $r['term_id'];
}
update_option('default_category', $cats['tin-hiep-hoi']);
if ($oldDefault && $oldDefault !== $cats['tin-hiep-hoi']) {
    wp_delete_term($oldDefault, 'category');
}

/* ============================================================= pages */

$home = wp_insert_post(array(
    'post_type'    => 'page',
    'post_status'  => 'publish',
    'post_title'   => 'Trang chủ',
    'post_name'    => 'trang-chu',
    'post_author'  => $author,
    'post_content' => '',
), true);
update_post_meta($home, '_wp_page_template', 'page-homepage.php');

$contactHtml = '';
foreach ($data['contact']['content'] as $p) {
    $contactHtml .= ct_demo_p($p);
}
$contact = wp_insert_post(array(
    'post_type'    => 'page',
    'post_status'  => 'publish',
    'post_title'   => $data['contact']['title'],
    'post_name'    => 'lien-lac',
    'post_author'  => $author,
    'post_content' => $contactHtml,
), true);

update_option('show_on_front', 'page');
update_option('page_on_front', $home);
update_option('page_for_posts', 0);
update_option('blogname', $site['name']);
update_option('blogdescription', $site['desc']);
update_option('date_format', 'd/m/Y');
update_option('time_format', 'H:i');
update_option('permalink_structure', '/%postname%/');

/* ============================================================= menus */

$mainMenu = wp_create_nav_menu('Menu chính');
$pos = 0;
wp_update_nav_menu_item($mainMenu, 0, array(
    'menu-item-title' => 'Trang chủ', 'menu-item-object' => 'page', 'menu-item-object-id' => $home,
    'menu-item-type' => 'post_type', 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos,
));
$catItems = array(); // slug => ID mục menu (để gắn menu con)
foreach ($data['categories'] as $slug => $c) {
    $catItems[$slug] = wp_update_nav_menu_item($mainMenu, 0, array(
        'menu-item-title' => $c['name'], 'menu-item-object' => 'category', 'menu-item-object-id' => $cats[$slug],
        'menu-item-type' => 'taxonomy', 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos,
        'menu-item-parent-id' => $c['parent'] !== '' ? $catItems[$c['parent']] : 0,
    ));
}
wp_update_nav_menu_item($mainMenu, 0, array(
    'menu-item-title' => 'Liên lạc', 'menu-item-object' => 'page', 'menu-item-object-id' => $contact,
    'menu-item-type' => 'post_type', 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos,
));
set_theme_mod('nav_menu_locations', array('primary' => $mainMenu));

$linksMenu = wp_create_nav_menu('Liên kết');
$pos = 0;
foreach ($data['links'] as $l) {
    wp_update_nav_menu_item($linksMenu, 0, array(
        'menu-item-title' => $l['title'], 'menu-item-url' => $l['url'], 'menu-item-type' => 'custom',
        'menu-item-status' => 'publish', 'menu-item-target' => '_blank', 'menu-item-position' => ++$pos,
    ));
}

/* ============================================== other category posts */

ct_demo_log('3/5 Tạo bài theo chuyên mục (kèm ảnh)…');
$nOther  = 0;
$nImages = 0;
$postIds = array(); // slug => [ID bài theo thứ tự trong dữ liệu]
foreach ($data['posts'] as $slug => $list) {
    foreach ($list as $i => $p) {
        list($when, $title, $excerpt, $items) = $p;
        $opts   = isset($p[4]) ? $p[4] : array();
        $dt     = new DateTimeImmutable($when . ':00', $tz);
        $isPub  = $dt->getTimestamp() <= $now;
        $postId = wp_insert_post(array(
            'post_type'     => 'post',
            'post_status'   => $isPub ? 'publish' : 'future',
            'post_title'    => $title,
            'post_content'  => ct_demo_blocks($items, $data['note']),
            'post_excerpt'  => $excerpt,
            'post_date'     => $dt->format('Y-m-d H:i:s'),
            'post_date_gmt' => gmdate('Y-m-d H:i:s', $dt->getTimestamp()),
            'post_author'   => $author,
            'post_category' => array($cats[$slug]),
        ), true);
        if (is_wp_error($postId)) {
            WP_CLI::warning($title . ': ' . $postId->get_error_message());
            continue;
        }
        update_post_meta($postId, 'views', $isPub ? wp_rand(30, 700) : 0);
        $postIds[$slug][] = $postId;

        if (!empty($opts['gallery'])) {
            // Album: N ảnh, ảnh đầu làm ảnh đại diện, nối khối Gallery vào cuối bài.
            $label   = trim(preg_replace('/^Album:\s*/u', '', $title));
            $gallery = array();
            for ($k = 0; $k < (int) $opts['gallery']; $k++) {
                $tone = $data['gallery_tones'][($i + $k) % count($data['gallery_tones'])];
                $img  = ct_demo_banner_image($tmpDir . '/' . $slug . '-' . ($i + 1) . '-' . ($k + 1) . '.jpg', $label, $title, $tone, $k);
                $att  = ct_demo_attach($img, $label . ' – ảnh ' . ($k + 1), $postId);
                if ($att) {
                    $gallery[] = $att;
                }
            }
            if ($gallery) {
                set_post_thumbnail($postId, $gallery[0]);
                wp_update_post(array('ID' => $postId, 'post_content' => get_post_field('post_content', $postId) . ct_demo_gallery($gallery)));
                $nImages += count($gallery);
            }
        } else {
            $img = ct_demo_banner_image($tmpDir . '/' . $slug . '-' . ($i + 1) . '.jpg', $data['categories'][$slug]['name'], $title, $data['banners'][$slug], $i);
            $att = ct_demo_attach($img, $title, $postId);
            if ($att) {
                set_post_thumbnail($postId, $att);
                $nImages++;
            }
        }
        $nOther++;
    }
}
ct_demo_log("   {$nOther} bài, {$nImages} ảnh");

/* ------------------------------------- menu cấp 3: Hội dòng → Các Cộng đoàn → từng cộng đoàn */
foreach ($postIds['cac-cong-doan'] as $pid) {
    wp_update_nav_menu_item($mainMenu, 0, array(
        'menu-item-title' => get_post_field('post_title', $pid),
        'menu-item-object' => 'post', 'menu-item-object-id' => $pid, 'menu-item-type' => 'post_type',
        'menu-item-parent-id' => $catItems['cac-cong-doan'], 'menu-item-status' => 'publish',
    ));
}

/* ========================================================== settings */

ct_demo_log('4/5 Cấu hình theme + widget…');
$logoUrl = '';
$iconUrl = '';
if ($logo !== '' && is_readable($logo)) {
    $logoId  = ct_demo_attach($logo, 'Logo ' . $site['name']);
    $logoUrl = $logoId ? wp_get_attachment_url($logoId) : '';
} else {
    WP_CLI::warning('Không có file logo (CT_DEMO_LOGO) — header sẽ hiện tên website.');
}
if (is_readable($icon)) {
    $iconId = ct_demo_attach($icon, 'Biểu tượng ' . $site['name']);
    if ($iconId) {
        update_option('site_icon', $iconId);
        $iconUrl = wp_get_attachment_image_url($iconId, 'thumbnail');
    }
}

// Mu-plugin ghi đè ct_brand (keywords, màu trình duyệt, tiêu đề menu quản trị, favicon).
wp_mkdir_p(WPMU_PLUGIN_DIR);
if (!copy($CT_DIR . '/ntts-brand.php', trailingslashit(WPMU_PLUGIN_DIR) . 'ct-brand-ntts.php')) {
    WP_CLI::warning('Không chép được ntts-brand.php vào ' . WPMU_PLUGIN_DIR);
}

$card = function ($thumb, $exper, $date, $views) {
    return array('post_thumb' => $thumb, 'post_exper' => $exper, 'post_date' => $date, 'post_views' => $views, 'post_author' => 'n');
};
$sec = function ($picker, $title, $cats, $num, $cardCfg, $readmoreLink = '') {
    return array('content_type' => array(
        'picker' => $picker,
        $picker  => array(
            'title'         => $title,
            'is_display'    => 'y',
            'is_admin_only' => 'n',
            'cats'          => (string) $cats,
            'num_post'      => (string) $num,
            'card'          => $cardCfg,
            'default_style' => array('is_default_style' => 'y', 'n' => array('bgcolor' => '', 'textcolor' => '', 'accentcolor' => '')),
            'show_readmore' => $readmoreLink !== ''
                ? array('action_show' => 'y', 'y' => array('readmore_text' => 'Xem thêm »', 'readmore_link' => $readmoreLink, 'readmore_blank' => 'n'))
                : array('action_show' => 'n', 'y' => array('readmore_text' => '', 'readmore_link' => '', 'readmore_blank' => 'n')),
        ),
    ));
};
$catLink = function ($slug) use ($cats) {
    return get_category_link($cats[$slug]);
};

$siteCss = <<<'CSS'
<style>
/* NTTS — tinh chỉnh riêng của site (không thuộc theme). Bảng màu đỏ rượu – vàng kim. */
.ct-shadow.ct-bounding,
#ct-sidebar .widget-item { border-radius: 10px; }
#ct-sidebar .widget_nav_menu ul { list-style: none; margin: 0; padding: 0; }
#ct-sidebar .widget_nav_menu li a {
  display: flex; align-items: center; gap: .55rem;
  padding: .6rem 0; border-bottom: 1px solid #f1e7e3;
  color: #3a1a22; text-decoration: none; transition: color .15s ease, padding-left .15s ease;
}
#ct-sidebar .widget_nav_menu li:last-child a { border-bottom: 0; }
#ct-sidebar .widget_nav_menu li a::before { content: "›"; color: #7a1f35; font-size: 1.2rem; line-height: 1; font-weight: 700; }
#ct-sidebar .widget_nav_menu li a:hover { color: #7a1f35; padding-left: .25rem; }
#site-footer a { color: inherit; }
/* Khẩu hiệu header: theme để màu navy cố định → đổi sang đỏ rượu đậm. */
.header-brand__slogan-ref { color: #5a1526; }
/* Menu con (kiểu c4): theme để nền hover xanh nhạt + chữ navy cố định → đổi sang tông đỏ rượu. */
@media (min-width: 992px) {
  #site-nav.navbar.nav-style-c4 #siteNavbar .ct-main-menu ul li a { color: #3a1a22; }
  #site-nav.navbar.nav-style-c4 #siteNavbar .ct-main-menu ul li:hover { background-color: #f8eeea; }
  #site-nav.navbar.nav-style-c4 #siteNavbar .ct-main-menu ul li:hover > a { color: #7a1f35; }
}
/* Menu điện thoại (assets/css/nav-menu.css): đổi các biến màu navy/xanh nhạt sang tông đỏ rượu. */
@media (max-width: 991.98px) {
  #site-nav#site-nav.navbar { --ct-mnav-text: #3a1a22; --ct-mnav-muted: #6b5a5e; --ct-mnav-line: #f3e9e5; --ct-mnav-active-bg: #f8eeea; }
}
/* Trang bài viết: nút cỡ chữ đang chọn (theme dùng xanh Bootstrap mặc định), trích dẫn, thơ. */
#ct-content.ct-single .post-text-sizes .text-size-list .post-text-size.activated { background-color: #7a1f35; }
#ct-content.ct-single .post-content blockquote.wp-block-quote { border-left: 4px solid #d9a441; background-color: #fbf6ef; padding: .9rem 1.2rem .9rem 3em; border-radius: 0 10px 10px 0; }
#ct-content.ct-single .post-content .wp-block-quote cite { color: #7a1f35; font-style: normal; font-weight: 600; }
#ct-content.ct-single .post-content .wp-block-verse { font-family: inherit; font-style: italic; font-size: 1.1rem; line-height: 1.8; background-color: #fbf6ef; border-left: 4px solid #d9a441; padding: 1rem 1.25rem; white-space: pre-wrap; text-align: left; }
/* Widget Giới thiệu + Ơn gọi (Custom HTML). */
.ntts-btn { display: inline-block; padding: .45rem 1rem; border-radius: 999px; background: #7a1f35; color: #fff !important; font-size: .9rem; font-weight: 600; text-decoration: none; }
.ntts-btn:hover { background: #5a1526; }
.ntts-btn--light { background: #d9a441; color: #3a1a22 !important; }
.ntts-btn--light:hover { background: #e8c27a; }
.ntts-about { text-align: center; }
.ntts-about img { display: block; margin: 0 auto .75rem; width: 64px; height: 64px; }
.ntts-about p { text-align: justify; color: #3a1a22; }
.ntts-cta { margin: -0.25rem; padding: 1.4rem 1.25rem; border-radius: 10px; background: linear-gradient(160deg, #7a1f35 0%, #5a1526 100%); color: #fff; }
.ntts-cta p { margin: 0 0 .75rem; }
.ntts-cta__kicker { font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #e8c27a; }
.ntts-cta__title { font-family: "Charm", cursive; font-size: 1.7rem; line-height: 1.2; }
.ntts-cta__link { margin-left: .5rem; color: #fff !important; font-size: .9rem; }
</style>
CSS;

update_option('ct_settings', array(
    'theme_data'  => array('theme' => 'custom', 'custom_color' => '#7a1f35'),
    'gen_data'    => array(
        'nav_style'        => 'c4',
        'nav_bg_color'     => '#6b1a2e',
        'nav_text_color'   => '#ffffff',
        'nav_accent_color' => '#d9a441',
        'gen_bg'           => array(
            'action_show' => 'c_color',
            'c_color'     => array('color' => '#f7f2ec'),
            'c_image'     => array('image' => array('image_upload' => array('url' => ''), 'image_repeat' => 'no-repeat')),
        ),
    ),
    'header_data' => array(
        'action_show' => 'c_brand',
        'c_content'   => array('header_text' => '', 'bgcolor' => ''),
        'c_images'    => array(
            'gen_img_desktop' => array('url' => ''), 'gen_img_tablet' => array('url' => ''), 'gen_img_mobile' => array('url' => ''),
            'gen_tieu_de' => '', 'gen_lien_ket' => '', 'gen_is_blank' => '0',
        ),
        'c_brand'     => array(
            'logo'         => array('url' => $logoUrl),
            'slogan'       => $site['slogan'],
            'slogan_ref'   => $site['slogan_ref'],
            // Nền sáng (không dùng đỏ đậm → trắng) để chữ logo đỏ không chìm ở nửa trên.
            'bg_from'      => '#f3e4df',
            'bg_to'        => '#ffffff',
            'slogan_color' => '#7a1f35',
        ),
    ),
    'footer_data' => array(
        'gen_widget'           => array('action_show' => 'n', 'y' => array('widget_setting' => array('number' => 'c1'))),
        'gen_footer_text'      => '<p style="text-align:center">© 2026 <strong>' . esc_html($site['name']) . '</strong> — ' . esc_html($site['desc']) . '<br>Liên lạc: <a href="mailto:' . esc_attr($site['email']) . '">' . esc_html($site['email']) . '</a> (địa chỉ mẫu)</p>',
        'gen_footer_bg_color'  => '#5a1526',
        'gen_footer_txt_color' => '#ffffff',
    ),
    'home_gen'          => array('sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'right'))),
    'home_featured'     => array('action_show' => 'n'),
    'show_5phutloichua' => array('action_show' => 'n'),
    'home_sec'          => array(
        $sec('temp8', 'Tin mới', $cats['tin-giao-hoi'] . ',' . $cats['tin-hiep-hoi'], 5, $card('y', 'n', 'y', 'n')),
        $sec('temp1', 'Tin Hiệp hội NTTSTG', $cats['tin-hiep-hoi'], 4, $card('y', 'y', 'y', 'n'), $catLink('tin-hiep-hoi')),
        $sec('temp4', 'Tin Giáo Hội', $cats['tin-giao-hoi'], 4, $card('y', 'y', 'y', 'n'), $catLink('tin-giao-hoi')),
        $sec('temp3', 'Hội dòng & Các Cộng đoàn', $cats['hoi-dong'] . ',' . $cats['cac-cong-doan'], 4, $card('y', 'y', 'y', 'n'), $catLink('hoi-dong')),
        $sec('temp3', 'Linh đạo', $cats['linh-dao'], 4, $card('y', 'y', 'y', 'n'), $catLink('linh-dao')),
        $sec('temp4', 'Cầu nguyện', $cats['cau-nguyen'], 4, $card('y', 'y', 'y', 'n'), $catLink('cau-nguyen')),
        $sec('temp5', 'Ơn gọi', $cats['on-goi'], 4, $card('y', 'n', 'y', 'n'), $catLink('on-goi')),
        $sec('temp4', 'Tuỳ bút/Chia sẻ/Văn Hoá', $cats['tuy-but-chia-se-van-hoa'], 4, $card('y', 'y', 'y', 'n'), $catLink('tuy-but-chia-se-van-hoa')),
        $sec('temp1', 'Tư liệu', $cats['tu-lieu'], 4, $card('y', 'y', 'y', 'n'), $catLink('tu-lieu')),
        $sec('temp5', 'Media', $cats['media'], 4, $card('y', 'n', 'y', 'n'), $catLink('media')),
    ),
    'cat_data'    => array('columns' => 'c2', 'display_style' => 'c1', 'sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'right'))),
    'post_data'   => array('sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'right'))),
    'hot_picker'  => array('action_show' => 'n'),
    'tech_data'   => array(
        'analytics' => '', 'headscripts' => '', 'footerscripts' => $siteCss,
        'template_admin_shortcode' => '', 'template_editor_shortcode' => '', 'template_else_shortcode' => '',
    ),
));

// Widget (không dùng Lịch/Thẻ Lời Chúa — tính năng của site khác):
//   Trang chủ: Giới thiệu, Ơn gọi, Bài xem nhiều, Liên kết
//   Bài viết:  Bài mới, Ơn gọi, Liên kết
//   Chuyên mục: Giới thiệu, Bài xem nhiều, Liên kết
$widgetHtml = function ($key) use ($data, $iconUrl, $catLink, $contact) {
    $w = $data['widgets'][$key];
    return array('title' => $w['title'], 'content' => strtr($w['content'], array(
        '{icon}'     => esc_url($iconUrl),
        '{hoi_dong}' => esc_url($catLink('hoi-dong')),
        '{on_goi}'   => esc_url($catLink('on-goi')),
        '{lien_lac}' => esc_url(get_permalink($contact)),
    )));
};
update_option('widget_custom_html', array(
    2 => $widgetHtml('about'),
    3 => $widgetHtml('vocation'),
    4 => $widgetHtml('vocation'),
    5 => $widgetHtml('about'),
    '_multiwidget' => 1,
));
$links = array('title' => 'Liên kết', 'nav_menu' => $linksMenu);
update_option('widget_nav_menu', array(2 => $links, 3 => $links, 4 => $links, '_multiwidget' => 1));
$postlist = array('style' => 'numbered', 'category' => 0, 'number' => 5, 'bg_color' => '', 'show_image' => 0, 'show_desc' => 0, 'show_meta' => 1);
update_option('widget_ct_postlist_widget', array(
    2 => array('title' => 'Bài xem nhiều', 'orderby' => 'views') + $postlist,
    3 => array('title' => 'Bài mới', 'orderby' => 'date') + $postlist,
    4 => array('title' => 'Bài xem nhiều', 'orderby' => 'views') + $postlist,
    '_multiwidget' => 1,
));
update_option('sidebars_widgets', array(
    'wp_inactive_widgets' => array(),
    'ct-widget-homepage'  => array('custom_html-2', 'custom_html-3', 'ct_postlist_widget-2', 'nav_menu-2'),
    'ct-widget-single'    => array('ct_postlist_widget-3', 'custom_html-4', 'nav_menu-3'),
    'ct-widget-archive'   => array('custom_html-5', 'ct_postlist_widget-4', 'nav_menu-4'),
    'array_version'       => 3,
));

/* ============================================================ finish */

ct_demo_log('5/5 Dọn cache…');
if (function_exists('ct_flush_post_caches')) {
    ct_flush_post_caches();
}
wp_cache_flush();
flush_rewrite_rules(false);
foreach (glob($tmpDir . '/*') ?: array() as $f) {
    @unlink($f);
}

WP_CLI::success(sprintf('Xong: %d chuyên mục, %d bài (%d ảnh), 2 trang, 2 menu.', count($cats), $nOther, $nImages));
