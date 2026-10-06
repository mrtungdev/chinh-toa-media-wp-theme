<?php

/**
 * "Lời Chúa hôm nay": bài của từng ngày + lịch tháng.
 *
 * Nguồn dữ liệu là bài viết THƯỜNG trong một chuyên mục (VD "Suy niệm"), mỗi ngày
 * một bài, lấy theo NGÀY ĐĂNG (post_date, giờ địa phương của site). Hẹn giờ đăng
 * đúng ngày → WP-cron tự đăng → box trang chủ tự lên bài mới; save_post xoá cache
 * (ct_flush_post_caches, inc/query/common.php) nên không cần thao tác thêm.
 *
 * Thông tin ngày (ngày phụng vụ, lễ thánh, đoạn Tin Mừng) là meta nhập ở metabox
 * "Phân loại bài viết" (inc/post/post-kind.php, loại "Lời Chúa").
 *
 * Thành phần:
 *  - khối trang chủ temp7 (template-parts/homepage/c_daily-word.php);
 *  - widget lịch CT_LoiChua_Calendar_Widget (inc/loichua/calendar-widget.php);
 *  - 2 endpoint AJAX công khai, chỉ đọc: ct_daily_word (thẻ bài của 1 ngày) và
 *    ct_daily_calendar (bảng lịch 1 tháng) cho assets/js/daily-word.js.
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Hôm nay theo múi giờ của site, dạng Y-m-d. */
function ct_loichua_today()
{
    return wp_date('Y-m-d');
}

/** Chuẩn hoá chuỗi ngày Y-m-d; '' nếu sai định dạng hoặc ngày không tồn tại. */
function ct_loichua_sanitize_date($ymd)
{
    $ymd = is_string($ymd) ? trim($ymd) : '';
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $ymd, $m)) {
        return '';
    }
    return checkdate((int) $m[2], (int) $m[3], (int) $m[1]) ? $ymd : '';
}

/** Chuẩn hoá chuỗi tháng Y-m; '' nếu sai. */
function ct_loichua_sanitize_month($ym)
{
    $ym = is_string($ym) ? trim($ym) : '';
    if (!preg_match('/^(\d{4})-(\d{2})$/', $ym, $m)) {
        return '';
    }
    $month = (int) $m[2];
    return ($month >= 1 && $month <= 12 && (int) $m[1] >= 1970) ? $ym : '';
}

/** Tham số truy vấn chung: bài đã đăng, trong chuyên mục (0 = mọi chuyên mục). */
function ct_loichua_query_base($cat)
{
    $args = array(
        'post_type'              => 'post',
        'post_status'            => 'publish',
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );
    $cat = absint($cat);
    if ($cat > 0) {
        $args['cat'] = $cat;
    }
    return $args;
}

/**
 * Bài "Lời Chúa" của một ngày.
 *
 * @param string $ymd      Ngày Y-m-d (giờ địa phương).
 * @param int    $cat      Chuyên mục nguồn (0 = mọi chuyên mục).
 * @param bool   $fallback true = nếu ngày đó chưa có bài thì lấy bài gần nhất TRƯỚC đó
 *                         (dùng cho "hôm nay" khi chưa kịp đăng bài).
 * @return WP_Post|null
 */
function ct_get_loichua_for_date($ymd, $cat = 0, $fallback = false)
{
    $ymd = ct_loichua_sanitize_date($ymd);
    if ($ymd === '') {
        return null;
    }
    $cat = absint($cat);

    // Cache CHỈ ID (nhỏ) theo ngày; key có tiền tố trans_ để ct_flush_post_caches xoá.
    $trans = 'trans_lcday_' . $cat . '_' . str_replace('-', '', $ymd) . ($fallback ? '_f' : '');
    $id    = get_transient($trans);
    if (false === $id) {
        list($y, $m, $d) = array_map('intval', explode('-', $ymd));
        $args = ct_loichua_query_base($cat) + array(
            'posts_per_page' => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
            'date_query'     => array(array('year' => $y, 'month' => $m, 'day' => $d)),
        );
        $ids = get_posts($args);
        if (empty($ids) && $fallback) {
            $args['date_query'] = array(array('before' => $ymd . ' 23:59:59', 'inclusive' => true));
            $ids = get_posts($args);
        }
        $id = empty($ids) ? 0 : (int) $ids[0];
        set_transient($trans, $id, DAY_IN_SECONDS);
    }

    $id = (int) $id;
    return $id > 0 ? get_post($id) : null;
}

/**
 * Bản đồ ngày => ID bài của một tháng (bài mới nhất nếu một ngày có nhiều bài).
 *
 * @return array<int,int>
 */
function ct_loichua_month_map($year, $month, $cat = 0)
{
    $year  = (int) $year;
    $month = (int) $month;
    $cat   = absint($cat);

    $trans = sprintf('trans_lcmonth_%d_%04d%02d', $cat, $year, $month);
    $map   = get_transient($trans);
    if (is_array($map)) {
        return $map;
    }

    $map   = array();
    $query = new WP_Query(ct_loichua_query_base($cat) + array(
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'date_query'     => array(array('year' => $year, 'month' => $month)),
    ));
    foreach ($query->posts as $p) {
        // Thứ tự tăng dần → bài sau cùng trong ngày ghi đè = bài mới nhất.
        $map[(int) mysql2date('j', $p->post_date)] = (int) $p->ID;
    }
    set_transient($trans, $map, DAY_IN_SECONDS);
    return $map;
}

/** Chuyên mục đầu tiên trong chuỗi "id1,id2" của khối trang chủ. */
function ct_loichua_first_cat($cats)
{
    $first = strtok(ct_cats_str($cats), ',');
    return $first === false ? 0 : absint($first);
}

/**
 * HTML thẻ bài của một ngày (ảnh trái, thông tin phải). Dùng chung cho khối trang chủ
 * (render lần đầu) và endpoint AJAX (khi bấm ngày trên lịch).
 *
 * @param WP_Post|null $post Bài cần hiện; null = thông báo chưa có bài.
 * @param string       $ymd  Ngày đang xem (để ghi trong thông báo trống).
 * @return string
 */
function ct_loichua_daily_card_html($post, $ymd = '')
{
    $file = locate_template('template-parts/loichua/daily-card.php', false, false);
    if (!$file) {
        return '';
    }
    $lcPost = ($post instanceof WP_Post) ? $post : null;
    $lcDate = ct_loichua_sanitize_date($ymd);
    ob_start();
    include $file;
    return ob_get_clean();
}

/**
 * HTML lịch một tháng.
 *
 * @param int    $year
 * @param int    $month
 * @param int    $cat      Chuyên mục nguồn.
 * @param string $selected Ngày đang chọn Y-m-d ('' = không).
 * @return string
 */
function ct_loichua_calendar_html($year, $month, $cat = 0, $selected = '')
{
    $file = locate_template('template-parts/loichua/calendar.php', false, false);
    if (!$file) {
        return '';
    }
    $calYear     = (int) $year;
    $calMonth    = (int) $month;
    $calCat      = absint($cat);
    $calSelected = ct_loichua_sanitize_date($selected);
    $calMap      = ct_loichua_month_map($calYear, $calMonth, $calCat);
    ob_start();
    include $file;
    return ob_get_clean();
}

/**
 * URL không-JS để chuyển tháng (?ct_cal=Y-m). Trong AJAX thì dựa trên trang gọi
 * (referer cùng host) thay vì admin-ajax.php.
 */
function ct_loichua_calendar_month_url($ym)
{
    if (!wp_doing_ajax()) {
        return add_query_arg('ct_cal', $ym); // URL hiện tại
    }
    $ref = wp_get_referer();
    return add_query_arg('ct_cal', $ym, $ref ? $ref : home_url('/'));
}

/* ------------------------------------------------------------------- AJAX */

/*
 * Endpoint công khai, CHỈ ĐỌC bài đã đăng → không dùng nonce: nonce hết hạn trên
 * trang bị cache sẽ làm hỏng lịch, trong khi dữ liệu trả về vốn ai cũng xem được.
 * Mọi input đều được chuẩn hoá chặt (regex ngày/tháng, absint).
 */
add_action('wp_ajax_ct_daily_word', 'ct_ajax_daily_word');
add_action('wp_ajax_nopriv_ct_daily_word', 'ct_ajax_daily_word');
function ct_ajax_daily_word()
{
    // phpcs:disable WordPress.Security.NonceVerification.Recommended -- public read-only endpoint.
    $ymd = ct_loichua_sanitize_date(isset($_GET['date']) ? sanitize_text_field(wp_unslash($_GET['date'])) : '');
    $cat = isset($_GET['cat']) ? absint($_GET['cat']) : 0;
    // phpcs:enable
    if ($ymd === '') {
        wp_die('', '', array('response' => 400));
    }
    echo ct_loichua_daily_card_html(ct_get_loichua_for_date($ymd, $cat), $ymd); // phpcs:ignore — template output, self-escaping
    wp_die();
}

add_action('wp_ajax_ct_daily_calendar', 'ct_ajax_daily_calendar');
add_action('wp_ajax_nopriv_ct_daily_calendar', 'ct_ajax_daily_calendar');
function ct_ajax_daily_calendar()
{
    // phpcs:disable WordPress.Security.NonceVerification.Recommended -- public read-only endpoint.
    $ym       = ct_loichua_sanitize_month(isset($_GET['ym']) ? sanitize_text_field(wp_unslash($_GET['ym'])) : '');
    $cat      = isset($_GET['cat']) ? absint($_GET['cat']) : 0;
    $selected = isset($_GET['selected']) ? sanitize_text_field(wp_unslash($_GET['selected'])) : '';
    // phpcs:enable
    if ($ym === '') {
        wp_die('', '', array('response' => 400));
    }
    list($y, $m) = array_map('intval', explode('-', $ym));
    echo ct_loichua_calendar_html($y, $m, $cat, $selected); // phpcs:ignore — template output, self-escaping
    wp_die();
}

/* ---------------------------------------------------------------- Assets */

/** Trang có khối "Lời Chúa hôm nay" (temp7) hoặc có widget lịch thì nạp CSS + JS. */
function ct_loichua_daily_enqueue()
{
    $need = is_active_widget(false, false, 'ct_lc_calendar', true);
    if (!$need && (is_front_page() || is_page_template('page-homepage.php')) && function_exists('home_GetSections')) {
        foreach (home_GetSections() as $sec) {
            if (isset($sec['type']) && $sec['type'] === 'temp7') {
                $need = true;
                break;
            }
        }
    }
    if (!$need) {
        return;
    }
    $deps = array();
    if (wp_style_is('ct-loichua-card', 'registered')) {
        $deps[] = 'ct-loichua-card'; // thẻ giấy da dùng khi bài không có ảnh đại diện.
    }
    wp_enqueue_style('ct-daily-word', CT_THEME_CSS_URI . '/daily-word.css', $deps, THEME_VERSION);
    wp_enqueue_script('ct-daily-word', CT_THEME_JS_URI . '/daily-word.js', array(), THEME_VERSION, true);
}
add_action('wp_enqueue_scripts', 'ct_loichua_daily_enqueue', 20);
