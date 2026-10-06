<?php
/**
 * Nạp dữ liệu mẫu "5 phút cho Lời Chúa" cho site thử nghiệm (5plc.local).
 *
 * CẢNH BÁO: XOÁ TOÀN BỘ nội dung hiện có (bài, trang, media, chuyên mục, thẻ, menu,
 * widget) rồi tạo lại. Giữ nguyên user, plugin, kit Elementor. Sao lưu DB trước khi chạy.
 *
 * Chạy (từ thư mục gốc WordPress, có môi trường wp-cli của Local):
 *   source ~/Local\ Sites/5plc/app/.envrc && cd ~/Local\ Sites/5plc/app/public
 *   CT_DEMO_DIR=/path/to/tools/demo wp eval-file /path/to/tools/demo/seed-5plc.php
 * Tuỳ chọn: CT_DEMO_LOGO=/path/logo.png (mặc định: assets/logo-5phut.png).
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
$data = require $CT_DIR . '/5plc-data.php';
// Logo dựng lại dạng vector theo logo gốc (nguồn: assets/logo-5phut.svg; xuất PNG: rsvg-convert -w 792).
$logo = getenv('CT_DEMO_LOGO') ?: $CT_DIR . '/assets/logo-5phut.png';

$tmpDir = trailingslashit(sys_get_temp_dir()) . 'ct-demo-5plc';
wp_mkdir_p($tmpDir);
$author = 1;
$now    = time();
$tz     = wp_timezone();

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

/** "Thứ Hai Tuần 27 TN" / "Chúa Nhật 27 Thường Niên – Năm A" (CN 13/9/2026 = CN 24 TN). */
function ct_demo_day_title(DateTimeImmutable $d)
{
    $base = new DateTimeImmutable('2026-09-13 00:00:00', $d->getTimezone());
    $days = (int) $base->diff($d->setTime(0, 0))->format('%a');
    $week = 24 + intdiv($days, 7);
    $dow  = (int) $d->format('w');
    if ($dow === 0) {
        return sprintf('Chúa Nhật %d Thường Niên – Năm A', $week);
    }
    $names = array(1 => 'Thứ Hai', 2 => 'Thứ Ba', 3 => 'Thứ Tư', 4 => 'Thứ Năm', 5 => 'Thứ Sáu', 6 => 'Thứ Bảy');
    return sprintf('%s Tuần %d TN', $names[$dow], $week);
}

function ct_demo_p($text, $raw = false)
{
    return "<!-- wp:paragraph -->\n<p>" . ($raw ? $text : esc_html($text)) . "</p>\n<!-- /wp:paragraph -->\n\n";
}

function ct_demo_h($text)
{
    return "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . esc_html($text) . "</h3>\n<!-- /wp:heading -->\n\n";
}

/** Nội dung block cho các bài thường: đoạn văn / tiêu đề phụ / hỏi-đáp (khối Details). */
function ct_demo_blocks(array $items)
{
    $html = '';
    foreach ($items as $it) {
        if (is_string($it)) {
            $html .= ct_demo_p($it);
        } elseif (isset($it['h'])) {
            $html .= ct_demo_h($it['h']);
        } elseif (isset($it['qa'])) {
            $n = 0;
            foreach ($it['qa'] as $qa) {
                $n++;
                $html .= "<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>" . esc_html('Câu ' . $n . ': ' . $qa[0]) . "</summary>"
                    . "<!-- wp:paragraph -->\n<p><strong>Đáp án:</strong> " . esc_html($qa[1]) . "</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->\n\n";
            }
        }
    }
    return $html;
}

/* ================================================================== wipe */

ct_demo_log('1/6 Xoá nội dung cũ…');
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

ct_demo_log('2/6 Tạo chuyên mục, trang, menu…');
$cats = array();
foreach ($data['categories'] as $slug => $c) {
    $r = wp_insert_term($c['name'], 'category', array('slug' => $slug, 'description' => $c['desc']));
    if (is_wp_error($r)) {
        WP_CLI::error('Không tạo được chuyên mục ' . $slug . ': ' . $r->get_error_message());
    }
    $cats[$slug] = (int) $r['term_id'];
}
update_option('default_category', $cats['suy-niem']);
if ($oldDefault && $oldDefault !== $cats['suy-niem']) {
    wp_delete_term($oldDefault, 'category');
}

/* ============================================================= pages */

$home = wp_insert_post(array(
    'post_type'   => 'page',
    'post_status' => 'publish',
    'post_title'  => 'Trang chủ',
    'post_name'   => 'trang-chu',
    'post_author' => $author,
    'post_content' => '',
), true);
update_post_meta($home, '_wp_page_template', 'page-homepage.php');

$contactHtml = '';
foreach ($data['contact']['content'] as $p) {
    $contactHtml .= ct_demo_p($p, true);
}
$contact = wp_insert_post(array(
    'post_type'    => 'page',
    'post_status'  => 'publish',
    'post_title'   => $data['contact']['title'],
    'post_name'    => 'lien-he',
    'post_author'  => $author,
    'post_content' => $contactHtml,
), true);

update_option('show_on_front', 'page');
update_option('page_on_front', $home);
update_option('page_for_posts', 0);
update_option('blogname', '5 phút cho Lời Chúa');
update_option('blogdescription', 'Lời Chúa là ngọn đèn soi cho con bước');
update_option('date_format', 'd/m/Y');
update_option('time_format', 'H:i');

/* ============================================================= menus */

$mainMenu = wp_create_nav_menu('Menu chính');
$pos = 0;
wp_update_nav_menu_item($mainMenu, 0, array(
    'menu-item-title' => 'Trang chủ', 'menu-item-object' => 'page', 'menu-item-object-id' => $home,
    'menu-item-type' => 'post_type', 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos,
));
$catItems = array(); // slug => ID mục menu (để gắn menu con sau khi có bài)
foreach ($data['categories'] as $slug => $c) {
    $catItems[$slug] = wp_update_nav_menu_item($mainMenu, 0, array(
        'menu-item-title' => $c['name'], 'menu-item-object' => 'category', 'menu-item-object-id' => $cats[$slug],
        'menu-item-type' => 'taxonomy', 'menu-item-status' => 'publish', 'menu-item-position' => ++$pos,
    ));
}
wp_update_nav_menu_item($mainMenu, 0, array(
    'menu-item-title' => 'Liên hệ', 'menu-item-object' => 'page', 'menu-item-object-id' => $contact,
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

/* ================================================== daily posts (SN) */

ct_demo_log('3/6 Tạo ' . count($data['daily']) . ' bài Suy niệm theo ngày (không ảnh — hiện câu Lời Chúa)…');
$nPub = 0;
$nFut = 0;
foreach ($data['daily'] as $ymd => $d) {
    list($saint, $gospel, $title, $quote, $cite, $excerpt, $paras, $prayer) = $d;
    $dt       = new DateTimeImmutable($ymd . ' 05:00:00', $tz);
    $isPub    = $dt->getTimestamp() <= $now;
    $dayTitle = ct_demo_day_title($dt);

    $content  = ct_demo_h('Tin Mừng: ' . $gospel);
    $content .= ct_demo_p('Mời bạn đọc đoạn Tin Mừng ' . $gospel . ' trong sách Kinh Thánh trước khi suy niệm.');
    $content .= ct_demo_h('Suy niệm');
    foreach ($paras as $p) {
        $content .= ct_demo_p($p);
    }
    $content .= ct_demo_h('Cầu nguyện');
    $content .= ct_demo_p('<em>' . esc_html($prayer) . '</em>', true);

    $postId = wp_insert_post(array(
        'post_type'     => 'post',
        'post_status'   => $isPub ? 'publish' : 'future',
        'post_title'    => $title,
        'post_content'  => $content,
        'post_excerpt'  => $excerpt,
        'post_date'     => $dt->format('Y-m-d H:i:s'),
        'post_date_gmt' => gmdate('Y-m-d H:i:s', $dt->getTimestamp()),
        'post_author'   => $author,
        'post_category' => array($cats['suy-niem']),
    ), true);
    if (is_wp_error($postId)) {
        WP_CLI::warning($ymd . ': ' . $postId->get_error_message());
        continue;
    }
    update_post_meta($postId, '_ct_post_kind', 'loichua');
    update_post_meta($postId, '_ct_lc_card_quote', $quote);
    update_post_meta($postId, '_ct_lc_card_citation', $cite);
    update_post_meta($postId, '_ct_lc_day_title', $dayTitle);
    update_post_meta($postId, '_ct_lc_saint', $saint);
    update_post_meta($postId, '_ct_lc_gospel_ref', $gospel);
    update_post_meta($postId, 'views', $isPub ? wp_rand(60, 900) : 0);
    // Không gắn ảnh đại diện: theme tự hiện câu Lời Chúa ở chỗ ảnh (box trang chủ, thẻ bài,
    // lưới ảnh) và chỉ hiện thẻ câu ghi nhớ ở trang chi tiết. Có ảnh thật thì đặt ảnh đại diện.
    $isPub ? $nPub++ : $nFut++;
}
ct_demo_log("   {$nPub} bài đã đăng, {$nFut} bài hẹn giờ");

/* ============================================== other category posts */

ct_demo_log('4/6 Tạo bài cho các chuyên mục khác (kèm ảnh)…');
$nOther  = 0;
$postIds = array(); // slug => [ID bài theo thứ tự trong dữ liệu]
foreach ($data['posts'] as $slug => $list) {
    foreach ($list as $i => $p) {
        list($when, $title, $excerpt, $items) = $p;
        $dt     = new DateTimeImmutable($when . ':00', $tz);
        $isPub  = $dt->getTimestamp() <= $now;
        $postId = wp_insert_post(array(
            'post_type'     => 'post',
            'post_status'   => $isPub ? 'publish' : 'future',
            'post_title'    => $title,
            'post_content'  => ct_demo_blocks($items),
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
        $img = ct_demo_banner_image($tmpDir . '/' . $slug . '-' . ($i + 1) . '.jpg', $data['categories'][$slug]['name'], $title, $data['banners'][$slug], $i);
        $att = ct_demo_attach($img, $title, $postId);
        if ($att) {
            set_post_thumbnail($postId, $att);
        }
        $nOther++;
    }
}
ct_demo_log("   {$nOther} bài");

/* ------------------------------------------- menu con (thử menu 2–3 cấp) */
// Giáo lý → 3 bài; Huấn quyền → 2 nhóm (liên kết về chuyên mục), mỗi nhóm 3 văn kiện.
$menuPost = function ($postId, $parent) use ($mainMenu) {
    // Tên ngắn trong menu: phần trước dấu ":" (VD "Dei Verbum").
    $short = trim(explode(':', get_post_field('post_title', $postId))[0]);
    return wp_update_nav_menu_item($mainMenu, 0, array(
        'menu-item-title' => $short,
        'menu-item-object' => 'post', 'menu-item-object-id' => $postId, 'menu-item-type' => 'post_type',
        'menu-item-parent-id' => $parent, 'menu-item-status' => 'publish',
    ));
};
$menuGroup = function ($title, $parent) use ($mainMenu, $cats) {
    return wp_update_nav_menu_item($mainMenu, 0, array(
        'menu-item-title' => $title, 'menu-item-url' => get_category_link($cats['huan-quyen']), 'menu-item-type' => 'custom',
        'menu-item-parent-id' => $parent, 'menu-item-status' => 'publish',
    ));
};
foreach (array_slice($postIds['giao-ly'], 0, 3) as $pid) {
    $menuPost($pid, $catItems['giao-ly']);
}
$hq = $postIds['huan-quyen']; // [Dei Verbum, Lumen Gentium, Sacrosanctum Concilium, Evangelii Gaudium, Laudato Si', Dilexit Nos]
$g1 = $menuGroup('Công đồng Vaticanô II', $catItems['huan-quyen']);
foreach (array_slice($hq, 0, 3) as $pid) {
    $menuPost($pid, $g1);
}
$g2 = $menuGroup('Đức Thánh Cha Phanxicô', $catItems['huan-quyen']);
foreach (array_slice($hq, 3, 3) as $pid) {
    $menuPost($pid, $g2);
}

/* ========================================================== settings */

ct_demo_log('5/6 Cấu hình theme + widget…');
$logoUrl = '';
if ($logo !== '' && is_readable($logo)) {
    $logoId  = ct_demo_attach($logo, 'Logo 5 phút cho Lời Chúa');
    $logoUrl = $logoId ? wp_get_attachment_url($logoId) : '';
} else {
    WP_CLI::warning('Không có file logo (CT_DEMO_LOGO) — header sẽ hiện tên website.');
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
/* 5PLC — tinh chỉnh riêng của site (không thuộc theme). */
.ct-shadow.ct-bounding,
#ct-sidebar .widget-item { border-radius: 10px; }
#ct-sidebar .widget_nav_menu ul { list-style: none; margin: 0; padding: 0; }
#ct-sidebar .widget_nav_menu li a {
  display: flex; align-items: center; gap: .55rem;
  padding: .6rem 0; border-bottom: 1px solid #eef1f6;
  color: #1f2a44; text-decoration: none; transition: color .15s ease, padding-left .15s ease;
}
#ct-sidebar .widget_nav_menu li:last-child a { border-bottom: 0; }
#ct-sidebar .widget_nav_menu li a::before { content: "›"; color: #2e3192; font-size: 1.2rem; line-height: 1; font-weight: 700; }
#ct-sidebar .widget_nav_menu li a:hover { color: #2e3192; padding-left: .25rem; }
#site-footer a { color: inherit; }
/* Hỏi – đáp (khối Details) trong bài Vui học Kinh Thánh. */
.post-content .wp-block-details { margin: 0 0 .75rem; padding: .8rem 1rem; border: 1px solid #e3e8f2; border-radius: 10px; background: #f8f9fc; }
.post-content .wp-block-details summary { cursor: pointer; font-weight: 600; color: #1f2a44; }
.post-content .wp-block-details[open] { background: #fff; border-color: #c9cff0; }
.post-content .wp-block-details[open] summary { margin-bottom: .5rem; color: #2e3192; }
.post-content .wp-block-details p { margin: 0; }
/* Thẻ câu Lời Chúa (sidebar + đầu bài Suy niệm): tông xanh, chữ đứng, bỏ in hoa giãn chữ cho dễ đọc. */
.ct-loichua-card, .ct-lc-thumb { --ct-lc-bg: #2b2f7a; --ct-lc-text: #ffffff; --ct-lc-accent: #f2c14e; }
.ct-loichua-card { padding: 1.75rem 1.75rem 1.6rem; border-radius: 12px; box-shadow: 0 6px 18px rgba(20, 24, 60, .12); }
.ct-loichua-card .ct-loichua-card__label { margin-bottom: .75rem; font-size: .85rem; font-weight: 600; letter-spacing: .01em; text-transform: none; }
.ct-loichua-card .ct-loichua-card__quote { font-size: 1.3rem; font-style: normal; font-weight: 500; line-height: 1.6 !important; }
.ct-loichua-card .ct-loichua-card__cite { margin-top: 1rem; font-size: .9rem; font-weight: 600; letter-spacing: .01em; text-transform: none; }
.ct-loichua-card .ct-loichua-card__cite::before { content: "— "; }
</style>
CSS;

update_option('ct_settings', array(
    'theme_data'  => array('theme' => 'custom', 'custom_color' => '#2e3192'),
    'gen_data'    => array(
        'nav_style'        => 'c4',
        'nav_bg_color'     => '#3f4199',
        'nav_text_color'   => '#ffffff',
        'nav_accent_color' => '#f2c14e',
        'gen_bg'           => array(
            'action_show' => 'c_color',
            'c_color'     => array('color' => '#eef2f8'),
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
            'slogan'       => 'Lời Chúa là ngọn đèn soi cho con bước',
            'slogan_ref'   => 'Tv 119,105',
            'bg_from'      => '#3b3c97',
            'bg_to'        => '#ffffff',
            'slogan_color' => '#d0101b',
        ),
    ),
    'footer_data' => array(
        'gen_widget'           => array('action_show' => 'n', 'y' => array('widget_setting' => array('number' => 'c1'))),
        'gen_footer_text'      => '<p style="text-align:center">© 2026 <strong>5 phút cho Lời Chúa</strong> — Lời Chúa là ngọn đèn soi cho con bước (Tv 119,105).</p>',
        'gen_footer_bg_color'  => '#2e3192',
        'gen_footer_txt_color' => '#ffffff',
    ),
    'home_gen'          => array('sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'right'))),
    'home_featured'     => array('action_show' => 'n'),
    'show_5phutloichua' => array('action_show' => 'n'),
    'home_sec'          => array(
        $sec('temp7', 'Lời Chúa hôm nay', $cats['suy-niem'], 1, $card('y', 'y', 'y', 'n')),
        $sec('temp8', 'Bài viết mới', '', 5, $card('y', 'n', 'y', 'n')),
        $sec('temp4', 'Từ vựng', $cats['tu-vung'], 4, $card('y', 'y', 'y', 'n'), $catLink('tu-vung')),
        $sec('temp3', 'Giáo lý', $cats['giao-ly'], 5, $card('y', 'y', 'y', 'n'), $catLink('giao-ly')),
        $sec('temp5', 'Vui học Kinh Thánh', $cats['vui-hoc-kinh-thanh'], 4, $card('y', 'n', 'y', 'n'), $catLink('vui-hoc-kinh-thanh')),
        $sec('temp1', 'Huấn quyền', $cats['huan-quyen'], 4, $card('y', 'y', 'y', 'n'), $catLink('huan-quyen')),
    ),
    'cat_data'    => array('columns' => 'c2', 'display_style' => 'c1', 'sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'right'))),
    'post_data'   => array('sidebar' => array('action_show' => 'y', 'y' => array('sidebar_pos' => 'right'))),
    'hot_picker'  => array('action_show' => 'n'),
    'tech_data'   => array(
        'analytics' => '', 'headscripts' => '', 'footerscripts' => $siteCss,
        'template_admin_shortcode' => '', 'template_editor_shortcode' => '', 'template_else_shortcode' => '',
    ),
));

// Widget: Trang Chủ (2), Bài viết (3), Chuyên mục (4).
$sn = $cats['suy-niem'];
update_option('widget_ct_lc_calendar', array(
    2 => array('title' => 'Lịch Lời Chúa', 'category' => $sn),
    3 => array('title' => 'Lịch Lời Chúa', 'category' => $sn),
    4 => array('title' => 'Lịch Lời Chúa', 'category' => $sn),
    '_multiwidget' => 1,
));
update_option('widget_nav_menu', array(
    2 => array('title' => 'Liên kết', 'nav_menu' => $linksMenu),
    3 => array('title' => 'Liên kết', 'nav_menu' => $linksMenu),
    4 => array('title' => 'Liên kết', 'nav_menu' => $linksMenu),
    '_multiwidget' => 1,
));
$postlist = array('title' => 'Bài xem nhiều', 'style' => 'numbered', 'category' => 0, 'number' => 5, 'orderby' => 'views', 'bg_color' => '', 'show_image' => 0, 'show_desc' => 0, 'show_meta' => 1);
update_option('widget_ct_postlist_widget', array(2 => $postlist, 3 => $postlist, 4 => $postlist, '_multiwidget' => 1));
update_option('widget_ct_loichua_card', array(
    2 => array('title' => '', 'mode' => 'dynamic', 'label' => 'Câu Lời Chúa hôm nay', 'quote' => '', 'citation' => '', 'source' => 'category', 'source_category' => $sn, 'source_post_id' => 0, 'bg_color' => '', 'text_color' => '', 'accent_color' => ''),
    '_multiwidget' => 1,
));
update_option('sidebars_widgets', array(
    'wp_inactive_widgets' => array(),
    // Trang chủ đã có box "Lời Chúa hôm nay" → không đặt thêm thẻ câu Lời Chúa (tránh trùng).
    'ct-widget-homepage'  => array('ct_lc_calendar-2', 'nav_menu-2', 'ct_postlist_widget-2'),
    'ct-widget-single'    => array('ct_lc_calendar-3', 'nav_menu-3', 'ct_postlist_widget-3'),
    'ct-widget-archive'   => array('ct_lc_calendar-4', 'ct_loichua_card-2', 'nav_menu-4', 'ct_postlist_widget-4'),
    'array_version'       => 3,
));

/* ============================================================ finish */

ct_demo_log('6/6 Dọn cache…');
if (function_exists('ct_flush_post_caches')) {
    ct_flush_post_caches();
}
wp_cache_flush();
flush_rewrite_rules(false);
foreach (glob($tmpDir . '/*') ?: array() as $f) {
    @unlink($f);
}

WP_CLI::success(sprintf('Xong: %d chuyên mục, %d bài Suy niệm (%d hẹn giờ), %d bài khác, 2 trang, 2 menu.', count($cats), $nPub + $nFut, $nFut, $nOther));
