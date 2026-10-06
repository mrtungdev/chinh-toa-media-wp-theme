<?php
/**
 * Header kiểu "Logo + khẩu hiệu": logo bên trái, câu khẩu hiệu (thường là một câu
 * Lời Chúa) + trích dẫn bên phải, nền gradient dọc. Màu bơm qua biến CSS --ct-hb-*
 * (giống mẫu --ct-nav-* ở header.php); CSS ở assets/css/header-brand.css.
 *
 * Expects $CTheader from gen_GetHeader() (type c_brand).
 *
 * @package chinhtoa
 */
if (!defined('ABSPATH')) {
  exit;
}

$hbVars = array();
$hbFrom = ct_normalize_hex($CTheader['bg_from']);
$hbTo   = ct_normalize_hex($CTheader['bg_to']);
$hbText = ct_normalize_hex($CTheader['slogan_color']);
if ($hbFrom !== '') $hbVars[] = '--ct-hb-from: ' . $hbFrom;
if ($hbTo !== '')   $hbVars[] = '--ct-hb-to: ' . $hbTo;
if ($hbText !== '') $hbVars[] = '--ct-hb-text: ' . $hbText;
$hbStyle = $hbVars ? implode('; ', $hbVars) . ';' : '';
?>
<div class="header-brand"<?php if ($hbStyle) : ?> style="<?php echo esc_attr($hbStyle); ?>"<?php endif; ?>>
  <div class="container header-brand__inner">
    <a class="header-brand__logo" href="<?php echo esc_url(CT_HOME_URL); ?>" rel="home">
      <?php if (!empty($CTheader['logo'])) : ?>
        <img src="<?php echo esc_url($CTheader['logo']); ?>" alt="<?php echo esc_attr(CT_NAME); ?>">
      <?php else : ?>
        <span class="header-brand__name"><?php echo esc_html(CT_NAME); ?></span>
      <?php endif; ?>
    </a>
    <?php if (!empty($CTheader['slogan']) || !empty($CTheader['slogan_ref'])) : ?>
    <div class="header-brand__slogan">
      <?php if (!empty($CTheader['slogan'])) : ?>
        <p class="header-brand__slogan-text"><?php echo esc_html($CTheader['slogan']); ?></p>
      <?php endif; ?>
      <?php if (!empty($CTheader['slogan_ref'])) : ?>
        <p class="header-brand__slogan-ref"><?php echo esc_html($CTheader['slogan_ref']); ?></p>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>
