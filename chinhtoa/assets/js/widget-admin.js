/**
 * Khởi tạo wp-color-picker cho ô màu nền của widget Chính Tòa.
 * Bind cả khi widget được kéo vào (widget-added) hoặc lưu lại (widget-updated)
 * để hoạt động trên màn hình Widgets cổ điển và Customizer.
 */
(function ($) {
  function init(ctx) {
    $('.ct-widget-color-field', ctx).each(function () {
      var $el = $(this);
      if (!$el.data('wpcpInit')) {
        $el.wpColorPicker();
        $el.data('wpcpInit', true);
      }
    });
  }
  $(document).on('widget-added widget-updated', function (e, widget) {
    init(widget);
  });
  $(function () {
    init(document);
  });
})(jQuery);
