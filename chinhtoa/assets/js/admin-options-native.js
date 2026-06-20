/**
 * Native theme-options editor behaviour.
 * - Tab switching
 * - Conditional sub-options (data-ct-show), cascading
 * - wp-color-picker + wp.media pickers (incl. on cloned rows)
 * - home_sec repeater (sortable, add/remove) + per-row template groups
 * - temp6 tab_list nested repeater
 */
(function ($) {
  'use strict';

  window.__ctSecSeq = window.__ctSecSeq || 100000;
  window.__ctTabSeq = window.__ctTabSeq || 100000;

  function cssEsc(s) { return s.replace(/(["\\\]\[])/g, '\\$1'); }

  // --- Tabs (two-level: top-level groups + per-group sub-tabs) --------------
  function initTabs() {
    var $wrap = $('.ct-options-wrap');
    if (!$wrap.length) return;
    // Top-level tabs.
    $wrap.on('click', '.ct-tabs > .ct-tab', function (e) {
      e.preventDefault();
      var tab = $(this).data('tab');
      $wrap.find('.ct-tabs > .ct-tab').removeClass('is-active');
      $(this).addClass('is-active');
      $wrap.find('.ct-tab-panel').hide().filter('[data-tab="' + tab + '"]').show();
    });
    // Sub-tabs, scoped to their group panel.
    $wrap.on('click', '.ct-subtab', function (e) {
      e.preventDefault();
      var $panel = $(this).closest('.ct-tab-panel');
      var sub = $(this).data('subtab');
      $panel.find('.ct-subtab').removeClass('is-active');
      $(this).addClass('is-active');
      $panel.find('.ct-subtab-panel').hide().filter('[data-subtab="' + sub + '"]').show();
    });
  }

  // --- Conditional rows -----------------------------------------------------
  function controlValue($scope, name) {
    var $els = $scope.find('[name="' + cssEsc(name) + '"]');
    if (!$els.length) return null;
    var first = $els.get(0);
    if (first.type === 'radio') {
      var $checked = $els.filter(':checked');
      return $checked.length ? $checked.val() : null;
    }
    // switch = hidden(off) + checkbox(on), same name
    var $cb = $els.filter(':checkbox');
    if ($cb.length) {
      if ($cb.is(':checked')) return $cb.val();
      var $hidden = $els.filter('[type=hidden]');
      return $hidden.length ? $hidden.val() : null;
    }
    return $els.val();
  }

  function refreshConditionals($scope) {
    var $rows = $scope.find('.ct-field-row[data-ct-show]');
    for (var pass = 0; pass < 6; pass++) {
      $rows.each(function () {
        var $row = $(this);
        var name = $row.attr('data-ct-show');
        var want = String($row.attr('data-ct-show-val'));
        var match = String(controlValue($scope, name)) === want;
        // cascade: controller's own row must be visible
        var $ctrl = $scope.find('[name="' + cssEsc(name) + '"]').first();
        var $ctrlRow = $ctrl.closest('.ct-field-row');
        var parentVisible = !$ctrlRow.length || !$ctrlRow.is('[data-ct-show]') || $ctrlRow.css('display') !== 'none';
        $row.css('display', (match && parentVisible) ? '' : 'none');
      });
    }
  }

  // --- Pickers --------------------------------------------------------------
  function initColor($scope) {
    $scope.find('.ct-color-field').each(function () {
      if (!$(this).data('wpColorPickerInit')) {
        $(this).wpColorPicker();
        $(this).data('wpColorPickerInit', true);
      }
    });
  }

  function initMedia() {
    $(document).on('click', '.ct-media-btn', function (e) {
      e.preventDefault();
      var $btn = $(this);
      var $url = $btn.closest('.ct-media-field').find('.ct-media-url');
      var frame = wp.media({ title: 'Chọn ảnh', multiple: false, library: { type: 'image' } });
      frame.on('select', function () {
        var att = frame.state().get('selection').first().toJSON();
        $url.val(att.url).trigger('change');
        $btn.closest('.ct-media-field').find('.ct-media-preview').html('<img src="' + att.url + '" style="max-width:160px;height:auto;">');
      });
      frame.open();
    });
    $(document).on('click', '.ct-media-clear', function (e) {
      e.preventDefault();
      var $f = $(this).closest('.ct-media-field');
      $f.find('.ct-media-url').val('').trigger('change');
      $f.find('.ct-media-preview').empty();
    });
  }

  // --- Image-swatch radio selection styling ---------------------------------
  function initImageRadio() {
    $(document).on('change', '.ct-image-radio input[type=radio]', function () {
      var $g = $(this).closest('.ct-image-radio');
      $g.find('.ct-image-radio-item').removeClass('selected');
      $(this).closest('.ct-image-radio-item').addClass('selected');
    });
  }

  // --- Color-box radio (theme color) ----------------------------------------
  function initColorRadio() {
    $(document).on('change', '.ct-color-radio input[type=radio]', function () {
      var $g = $(this).closest('.ct-color-radio');
      $g.find('.ct-color-radio-item').removeClass('selected');
      $(this).closest('.ct-color-radio-item').addClass('selected');
    });
    // Live-preview the "Tự chọn" box swatch as the color picker changes.
    $(document).on('change', 'input[name="ct_settings[theme_data][custom_color]"]', function () {
      var v = $(this).val();
      if (v) $('.ct-custom-swatch').css('background', v);
    });
  }

  // --- Richer widgets (taxonomy / slider / datetime) ------------------------
  // Each visible widget drives a hidden/named input so the submitted shape is
  // the same string/number the plain fields produced. Idempotent so they can be
  // re-run on cloned repeater rows.
  function initTaxonomy($scope) {
    $scope.find('.ct-taxonomy').each(function () {
      var $sel = $(this);
      if ($sel.data('ctTaxInit')) return;
      $sel.data('ctTaxInit', true);
      $sel.on('change', function () {
        var vals = $sel.val() || [];
        $sel.closest('.ct-taxonomy-field').find('.ct-taxonomy-val').val(vals.join(',')).trigger('change');
      });
    });
  }

  function initSlider($scope) {
    $scope.find('.ct-slider-field').each(function () {
      var $f = $(this);
      if ($f.data('ctSliderInit')) return;
      $f.data('ctSliderInit', true);
      var $range = $f.find('.ct-slider');
      var $num = $f.find('.ct-slider-val');
      $range.on('input change', function () { $num.val($range.val()); });
      $num.on('input change', function () { if ($num.val() !== '') $range.val($num.val()); });
    });
  }

  function initDatetime($scope) {
    $scope.find('.ct-datetime-field').each(function () {
      var $f = $(this);
      if ($f.data('ctDtInit')) return;
      $f.data('ctDtInit', true);
      var $hidden = $f.find('.ct-datetime-val');
      var $date = $f.find('.ct-datetime-date');
      var $time = $f.find('.ct-datetime-time');
      function sync() {
        var d = $date.val(), t = $time.val();
        $hidden.val(d ? (t ? d + ' ' + t : d) : '').trigger('change');
      }
      $date.on('change', sync);
      $time.on('change', sync);
    });
  }

  // --- home_sec repeater ----------------------------------------------------
  function applyTemplateGroup($row) {
    var tpl = $row.find('.ct-sec-picker').val();
    $row.find('.ct-tpl-group').each(function () {
      var $g = $(this);
      var match = $g.data('tpl') === tpl;
      // Toggle a class (not inline display) so the grid layout in CSS survives.
      $g.toggleClass('is-hidden', !match);
      // only the selected template's inputs submit
      $g.find(':input').prop('disabled', !match);
    });
  }

  // Header summary = the active template's block title (or a placeholder).
  function updateSecSummary($row) {
    var $t = $row.find('.ct-tpl-group:not(.is-hidden) input[name$="[title]"]').first();
    var v = $t.length ? ($t.val() || '').replace(/^\s+|\s+$/g, '') : '';
    $row.find('.ct-sec-summary').text(v || '(Khối chưa đặt tiêu đề)');
  }

  // Swap the demo image to match the selected template (tempN -> ...-N.png).
  function updateSecDemo($row) {
    var n = ($row.find('.ct-sec-picker').val() || '').replace('temp', '');
    var $img = $row.find('.ct-sec-demo-img');
    if ($img.length && $img.data('base')) {
      $img.attr('src', $img.data('base') + n + '.png').show();
    }
  }

  function initRepeater() {
    var $rep = $('#ct-home-sec');
    if (!$rep.length) return;
    var $list = $rep.find('.ct-sec-list');

    if ($.fn.sortable) {
      $list.sortable({ handle: '.ct-sec-drag', items: '> .ct-sec-row', cursor: 'move' });
    }

    // init existing rows
    $list.find('.ct-sec-row').each(function () { applyTemplateGroup($(this)); updateSecSummary($(this)); });

    // collapse / expand a block
    $rep.on('click', '.ct-sec-toggle', function (e) {
      e.preventDefault();
      var $row = $(this).closest('.ct-sec-row');
      var collapsed = $row.toggleClass('is-collapsed').hasClass('is-collapsed');
      $(this).attr('aria-expanded', collapsed ? 'false' : 'true');
    });

    // picker change → swap template group + demo image + header summary
    $rep.on('change', '.ct-sec-picker', function () {
      var $row = $(this).closest('.ct-sec-row');
      applyTemplateGroup($row);
      updateSecDemo($row);
      updateSecSummary($row);
    });

    // live header summary as the title is typed
    $rep.on('input', 'input[name$="[title]"]', function () {
      updateSecSummary($(this).closest('.ct-sec-row'));
    });

    // add section (new blocks start expanded)
    $rep.on('click', '.ct-sec-add', function (e) {
      e.preventDefault();
      var idx = window.__ctSecSeq++;
      var html = $('#tmpl-ct-sec-row').html().replace(/__I__/g, idx);
      var $row = $(html);
      $list.append($row);
      applyTemplateGroup($row);
      initColor($row);
      initTaxonomy($row);
      initSlider($row);
      initDatetime($row);
      updateSecSummary($row);
    });

    // remove section
    $rep.on('click', '.ct-sec-remove', function (e) {
      e.preventDefault();
      $(this).closest('.ct-sec-row').remove();
    });

    // add tab (temp6)
    $rep.on('click', '.ct-tab-add', function (e) {
      e.preventDefault();
      var $row = $(this).closest('.ct-sec-row');
      var rowIdx = $row.data('index');
      var j = window.__ctTabSeq++;
      var html = $('#tmpl-ct-tab-item').html().replace(/__I__/g, rowIdx).replace(/__J__/g, j);
      var $item = $(html);
      $(this).closest('.ct-tablist').find('.ct-tablist-items').append($item);
      initTaxonomy($item);
    });

    // remove tab
    $rep.on('click', '.ct-tab-remove', function (e) {
      e.preventDefault();
      $(this).closest('.ct-tab-item').remove();
    });
  }

  // --- Boot -----------------------------------------------------------------
  $(function () {
    var $scope = $('.ct-options-wrap');
    initTabs();
    initColor($(document));
    initMedia();
    initImageRadio();
    initColorRadio();
    initTaxonomy($(document));
    initSlider($(document));
    initDatetime($(document));
    initRepeater();
    if ($scope.length) {
      refreshConditionals($scope);
      $scope.on('change', ':input', function () { refreshConditionals($scope); });
    }
    // Per-post / per-term meta boxes reuse the same conditional-reveal engine,
    // but live outside .ct-options-wrap (post & term edit screens).
    $('.ct-meta-box').each(function () {
      var $mb = $(this);
      refreshConditionals($mb);
      $mb.on('change', ':input', function () { refreshConditionals($mb); });
    });
  });
})(jQuery);
