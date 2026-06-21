<?php

/**
 * Trang "Giới Thiệu" của menu theme-options — Trung tâm Hướng dẫn.
 *
 * Hiển thị các mục hướng dẫn (nội dung Markdown trong docs/*.md) dạng "nhiều
 * trang": menu điều hướng bên trái + panel nội dung bên phải. Chuyển panel bằng
 * JS, hỗ trợ deep-link qua #guide-<slug> và phím Mũi tên. Logic render nằm ở
 * guide-helpers.php; CSS gói gọn trong khối <style> dưới đây (theo đúng pattern
 * của dashboard.php).
 *
 * @package chinhtoa
 */

if (!defined('ABSPATH')) {
  exit;
}

require_once CT_THEME_DIR . '/inc/admin/theme/guide-helpers.php';

$ct_guide_visible = ct_guide_visible_sections();
?>
<style>
  .ct-guide { max-width: 1100px; margin: 16px 0 32px; }
  .ct-guide-heading { font-size: 23px; margin: 6px 0 4px; }
  .ct-guide-intro { color: #646970; font-size: 14px; margin: 0 0 20px; }

  .ct-guide-layout { display: flex; align-items: flex-start; gap: 24px; }

  /* Menu điều hướng bên trái */
  .ct-guide-nav { flex: 0 0 248px; position: sticky; top: 46px; background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 6px; }
  .ct-guide-navlist { margin: 0; padding: 0; list-style: none; }
  .ct-guide-navgroup { padding: 12px 12px 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; color: #8c8f94; }
  .ct-guide-navgroup:first-child { padding-top: 6px; }
  .ct-guide-navlink { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 6px; color: #1d2327; text-decoration: none; font-size: 14px; line-height: 1.35; }
  .ct-guide-navlink .dashicons { flex: 0 0 auto; color: #2271b1; font-size: 18px; width: 18px; height: 18px; }
  .ct-guide-navlink:hover { background: #f6f7f7; }
  .ct-guide-navlink:focus-visible { outline: 2px solid #2271b1; outline-offset: 1px; }
  .ct-guide-navlink.is-active { background: #2271b1; color: #fff; font-weight: 600; }
  .ct-guide-navlink.is-active .dashicons { color: #fff; }

  /* Panel nội dung bên phải */
  .ct-guide-content { flex: 1 1 auto; min-width: 0; background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 8px 26px 24px; }
  .ct-guide-pane { display: none; }
  .ct-guide-pane.is-active { display: block; }
  .ct-guide-pane:focus { outline: none; }

  .ct-guide-body { color: #3c434a; font-size: 14px; line-height: 1.7; }
  .ct-guide-body > :first-child { margin-top: 0; }
  .ct-guide-body h2 { font-size: 18px; margin: 18px 0 10px; padding: 0; }
  .ct-guide-body h3 { font-size: 15px; margin: 18px 0 6px; }
  .ct-guide-body p { margin: 8px 0; }
  .ct-guide-body ul, .ct-guide-body ol { margin: 8px 0 8px 4px; padding-left: 22px; }
  .ct-guide-body li { margin: 4px 0; }
  .ct-guide-body a { color: #2271b1; text-decoration: none; }
  .ct-guide-body a:hover { text-decoration: underline; }
  .ct-guide-body code { background: #f6f7f7; padding: 1px 6px; border-radius: 4px; font-size: 13px; }
  .ct-guide-body blockquote { margin: 12px 0; padding: 8px 16px; border-left: 4px solid #2271b1; background: #f6f9fc; color: #50575e; border-radius: 0 4px 4px 0; }
  .ct-guide-body blockquote p { margin: 4px 0; }
  .ct-guide-body table { border-collapse: collapse; margin: 12px 0; width: 100%; }
  .ct-guide-body th, .ct-guide-body td { border: 1px solid #dcdcde; padding: 7px 11px; text-align: left; vertical-align: top; }
  .ct-guide-body th { background: #f6f7f7; }
  .ct-guide-body hr { border: 0; border-top: 1px solid #f0f0f1; margin: 16px 0; }

  @media (max-width: 782px) {
    .ct-guide-layout { flex-direction: column; }
    .ct-guide-nav { position: static; flex-basis: auto; width: 100%; }
  }
</style>

<div class="ct-guide">
  <h1 class="ct-guide-heading"><?php echo esc_html__('Hướng dẫn sử dụng', 'chinhtoa'); ?></h1>
  <p class="ct-guide-intro"><?php echo esc_html__('Xin chào các Admin, Cộng tác viên! Chọn một mục ở menu bên trái để xem hướng dẫn chi tiết.', 'chinhtoa'); ?></p>

  <?php if (!empty($ct_guide_visible)) : ?>
    <div class="ct-guide-layout">
      <nav class="ct-guide-nav" aria-label="<?php echo esc_attr__('Mục lục hướng dẫn', 'chinhtoa'); ?>">
        <?php ct_guide_render_nav($ct_guide_visible); ?>
      </nav>
      <div class="ct-guide-content">
        <?php
        $ct_guide_first = true;
        foreach ($ct_guide_visible as $ct_guide_section) {
          ct_guide_render_pane($ct_guide_section, $ct_guide_first);
          $ct_guide_first = false;
        }
        ?>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  (function () {
    var nav = document.querySelector('.ct-guide-nav');
    if (!nav) { return; }
    var links = [].slice.call(nav.querySelectorAll('.ct-guide-navlink'));
    var panes = [].slice.call(document.querySelectorAll('.ct-guide-pane'));

    function activate(slug, focusPane) {
      var paneId = 'guide-' + slug, matched = false;
      links.forEach(function (a) {
        var on = a.getAttribute('data-slug') === slug;
        a.classList.toggle('is-active', on);
        if (on) { a.setAttribute('aria-current', 'page'); matched = true; }
        else { a.removeAttribute('aria-current'); }
      });
      if (!matched) { return false; }
      panes.forEach(function (p) {
        var on = p.id === paneId;
        p.classList.toggle('is-active', on);
        if (on && focusPane) { p.focus(); }
      });
      if (window.history && history.replaceState) { history.replaceState(null, '', '#' + paneId); }
      return true;
    }

    links.forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        activate(a.getAttribute('data-slug'), false);
      });
    });

    nav.addEventListener('keydown', function (e) {
      if (e.key !== 'ArrowDown' && e.key !== 'ArrowUp') { return; }
      var idx = links.indexOf(document.activeElement);
      if (idx === -1) { return; }
      e.preventDefault();
      var next = e.key === 'ArrowDown' ? Math.min(links.length - 1, idx + 1) : Math.max(0, idx - 1);
      links[next].focus();
      activate(links[next].getAttribute('data-slug'), false);
    });

    function fromHash() {
      if (location.hash.indexOf('#guide-') !== 0) { return false; }
      return activate(location.hash.replace(/^#guide-/, ''), false);
    }
    fromHash();
    window.addEventListener('hashchange', fromHash);
  })();
</script>
