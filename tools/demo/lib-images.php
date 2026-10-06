<?php
/**
 * Tạo ảnh đại diện mẫu bằng Imagick (không tải ảnh từ ngoài).
 *  - ct_demo_banner_image(): nền gradient theo màu chuyên mục, nhãn nhỏ + ký hiệu mờ; chừa
 *    trống nửa dưới ảnh để tiêu đề đè lên (lưới mosaic) không bị chồng chữ.
 * Bài Suy niệm không cần ảnh: theme hiện câu Lời Chúa ở chỗ ảnh.
 *
 * Font hệ thống macOS có dấu tiếng Việt (Times New Roman, Arial).
 */

const CT_DEMO_W = 1280;
const CT_DEMO_H = 720;
const CT_DEMO_FONT_SANS_BOLD = '/System/Library/Fonts/Supplemental/Arial Bold.ttf';

/**
 * Banner gradient theo chuyên mục: nhãn nhỏ góc trên trái + ký hiệu mờ góc trên phải.
 * Nửa dưới để trống cho tiêu đề bài đè lên.
 *
 * @param array $theme ['from' => hex, 'to' => hex, 'glyph' => string]
 * @param int   $variant Lệch tông nhẹ theo thứ tự bài để các thẻ không giống hệt nhau.
 */
function ct_demo_banner_image($file, $label, $title, array $theme, $variant = 0)
{
    $W = CT_DEMO_W;
    $H = CT_DEMO_H;
    $img = new Imagick();
    $img->newPseudoImage($H, $W, 'gradient:' . $theme['from'] . '-' . $theme['to']);
    $img->rotateImage('none', -90); // gradient trái → phải
    $img->setImagePage(0, 0, 0, 0);
    $img->modulateImage(100, 100, 100 + (($variant % 5) - 2) * 3);

    // Ký hiệu mờ góc trên phải
    $g = new ImagickDraw();
    $g->setFont(CT_DEMO_FONT_SANS_BOLD);
    $g->setFontSize(300);
    $g->setFillColor('rgba(255,255,255,0.16)');
    $m = $img->queryFontMetrics($g, $theme['glyph']);
    $img->annotateImage($g, $W - 90 - $m['textWidth'], 80 + $m['ascender'] * 0.9, 0, $theme['glyph']);

    // Nhãn chuyên mục góc trên trái
    $l = new ImagickDraw();
    $l->setFont(CT_DEMO_FONT_SANS_BOLD);
    $l->setFontSize(30);
    $l->setFillColor('rgba(255,255,255,0.9)');
    $lab = function_exists('mb_strtoupper') ? mb_strtoupper($label, 'UTF-8') : strtoupper($label);
    $img->annotateImage($l, 80, 120, 0, $lab);
    $u = new ImagickDraw();
    $u->setFillColor('rgba(255,255,255,0.9)');
    $u->rectangle(80, 140, 136, 145);
    $img->drawImage($u);

    $img->setImageFormat('jpeg');
    $img->setImageCompressionQuality(86);
    $img->writeImage($file);
    return $file;
}
