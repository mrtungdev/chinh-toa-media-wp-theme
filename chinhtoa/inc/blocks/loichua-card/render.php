<?php

/**
 * Render dùng chung cho thẻ "Lời Chúa: Câu ghi nhớ".
 *
 * Một nguồn sự thật cho HTML thẻ — cả Gutenberg block (render_callback) lẫn classic
 * widget (CT_LoiChua_Card_Widget) đều gọi ct_loichua_card_render(), nên markup/CSS
 * không bao giờ lệch nhau. Màu bơm qua biến CSS nội tuyến --ct-lc-* giống mẫu nav ở
 * header.php; tái dùng ct_normalize_hex() (inc/utilities/enqueue.php).
 *
 * Đặt tên với hậu tố *_card / "loichua-card" để KHÔNG đụng hệ "Lời Chúa hôm nay"
 * (CPT ct_loichua, lớp CT_LoiChua_Widget, ct_render_loichua(), ct_get_loichua_for_date()).
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Khoá meta của thẻ (đặt riêng cho bài viết qua metabox inc/blocks/loichua-card/metabox.php).
 * Hậu tố _card để KHÔNG đụng meta của CPT "Lời Chúa hôm nay" (_ct_lc_gospel_ref, ...).
 */
if (!defined('CT_LC_CARD_META_QUOTE')) {
    define('CT_LC_CARD_META_QUOTE', '_ct_lc_card_quote');
}
if (!defined('CT_LC_CARD_META_CITATION')) {
    define('CT_LC_CARD_META_CITATION', '_ct_lc_card_citation');
}

/** Resolve post id cho nguồn 'current': context block (editor SSR) → loop → queried object. */
function ct_loichua_card_resolve_post_id($block = null)
{
    if ($block instanceof WP_Block && !empty($block->context['postId'])) {
        return (int) $block->context['postId'];
    }
    $id = get_the_ID();
    if ($id) {
        return (int) $id;
    }
    $q = get_queried_object_id();
    return $q ? (int) $q : 0;
}

/** Chọn bài cho chế độ Dynamic theo source: 'category' (mặc định) | 'current' | 'post'. */
function ct_loichua_card_dynamic_post_id(array $a, $block = null)
{
    $source = isset($a['source']) ? $a['source'] : 'category';

    if ($source === 'post') {
        return !empty($a['sourcePostId']) ? (int) $a['sourcePostId'] : 0;
    }
    if ($source === 'current') {
        return ct_loichua_card_resolve_post_id($block);
    }

    // 'category' — bài mới nhất trong chuyên mục (0 = mọi chuyên mục). Cache transient
    // theo chuyên mục (tái dùng ct_get_posts của inc/query/common.php).
    $cat  = isset($a['sourceCategory']) ? (int) $a['sourceCategory'] : 0;
    $args = array(
        'numberposts'      => 1,
        'post_status'      => 'publish',
        'suppress_filters' => false,
    );
    if ($cat > 0) {
        $args['category'] = $cat;
    }
    $posts = ct_get_posts($args, 'trans_ct_loichua_card_c' . $cat, HOUR_IN_SECONDS);
    return !empty($posts) ? (int) $posts[0]->ID : 0;
}

/**
 * Suy ra 3 trường hiển thị. Chế độ Dynamic chỉ điền trường còn TRỐNG (text nhập tay
 * luôn override): quote ← mô tả (excerpt), citation ← tiêu đề bài viết.
 *
 * @return array{label:string,quote:string,citation:string}
 */
function ct_loichua_card_resolve_fields(array $a, $block = null)
{
    $label    = isset($a['label'])    ? trim((string) $a['label'])    : '';
    $quote    = isset($a['quote'])    ? trim((string) $a['quote'])    : '';
    $citation = isset($a['citation']) ? trim((string) $a['citation']) : '';

    $mode = isset($a['mode']) ? $a['mode'] : 'static';
    if ($mode === 'dynamic') {
        $pid = ct_loichua_card_dynamic_post_id($a, $block);
        if ($pid) {
            // Ưu tiên meta riêng của bài (metabox) → fallback excerpt/title.
            if ($quote === '') {
                $mq    = (string) get_post_meta($pid, CT_LC_CARD_META_QUOTE, true);
                $quote = ($mq !== '') ? trim($mq) : trim((string) get_the_excerpt($pid));
            }
            if ($citation === '') {
                $mc       = (string) get_post_meta($pid, CT_LC_CARD_META_CITATION, true);
                $citation = ($mc !== '') ? trim($mc) : trim((string) get_the_title($pid));
            }
        }
    }

    return array('label' => $label, 'quote' => $quote, 'citation' => $citation);
}

/** Chuỗi biến CSS nội tuyến (--ct-lc-*) từ 3 màu; '' nếu không màu nào hợp lệ. */
function ct_loichua_card_style_string(array $a)
{
    $vars = array();
    $bg = ct_normalize_hex(isset($a['bgColor'])     ? $a['bgColor']     : '');
    $tx = ct_normalize_hex(isset($a['textColor'])   ? $a['textColor']   : '');
    $ac = ct_normalize_hex(isset($a['accentColor']) ? $a['accentColor'] : '');
    if ($bg !== '') {
        $vars[] = '--ct-lc-bg: ' . $bg;
    }
    if ($tx !== '') {
        $vars[] = '--ct-lc-text: ' . $tx;
    }
    if ($ac !== '') {
        $vars[] = '--ct-lc-accent: ' . $ac;
    }
    return $vars ? implode('; ', $vars) . ';' : '';
}

/**
 * HÀM RENDER DÙNG CHUNG. Trả về HTML thẻ (đã escape, an toàn để echo).
 *
 * @param array         $a         label, quote, citation, mode, source, sourceCategory,
 *                                 sourcePostId, bgColor, textColor, accentColor.
 * @param WP_Block|null $block     Ngữ cảnh block (cho nguồn 'current' khi SSR trong editor).
 * @param string|null   $root_attr Thuộc tính thẻ gốc dựng sẵn (block path dùng
 *                                 get_block_wrapper_attributes); null = tự dựng (widget path).
 * @return string
 */
function ct_loichua_card_render(array $a, $block = null, $root_attr = null)
{
    $f = ct_loichua_card_resolve_fields($a, $block);

    // Không có gì để hiện (vd Dynamic mà nguồn rỗng) → render rỗng để ẩn thẻ.
    if ($f['label'] === '' && $f['quote'] === '' && $f['citation'] === '') {
        return '';
    }

    if ($root_attr === null) {
        $style     = ct_loichua_card_style_string($a);
        $root_attr = 'class="ct-loichua-card"' . ($style !== '' ? ' style="' . esc_attr($style) . '"' : '');
    }

    ob_start(); ?>
    <div <?php echo $root_attr; // block: get_block_wrapper_attributes() đã escape; widget: tự escape ở trên. ?>>
        <?php if ($f['label'] !== '') : ?>
            <span class="ct-loichua-card__label"><?php echo esc_html($f['label']); ?></span>
        <?php endif; ?>
        <?php if ($f['quote'] !== '') : ?>
            <blockquote class="ct-loichua-card__quote"><?php echo wp_kses_post($f['quote']); ?></blockquote>
        <?php endif; ?>
        <?php if ($f['citation'] !== '') : ?>
            <cite class="ct-loichua-card__cite"><?php echo esc_html($f['citation']); ?></cite>
        <?php endif; ?>
    </div>
    <?php
    return trim(ob_get_clean());
}

/** render_callback của block — tham số thứ 3 là WP_Block (mang context['postId']). */
function ct_loichua_card_block_render($attributes, $content, $block)
{
    $a = is_array($attributes) ? $attributes : array();

    $wrap = array('class' => 'ct-loichua-card');
    $style = ct_loichua_card_style_string($a);
    if ($style !== '') {
        $wrap['style'] = $style;
    }

    return ct_loichua_card_render($a, $block, get_block_wrapper_attributes($wrap));
}
