/**
 * Ẩn/hiện nhóm ô theo loại bài trong metabox "Phân loại bài viết".
 *
 * Plain DOM, no-build (giống hướng của loichua-card-block.js). Mỗi nhóm
 * .ct-pk-fields[data-kind] chỉ hiện khi data-kind khớp giá trị <select id="ct_post_kind">.
 */
(function () {
  function init() {
    var select = document.getElementById('ct_post_kind');
    if (!select) {
      return;
    }
    var groups = document.querySelectorAll('.ct-pk-fields[data-kind]');

    function sync() {
      var kind = select.value;
      for (var i = 0; i < groups.length; i++) {
        var g = groups[i];
        g.style.display = g.getAttribute('data-kind') === kind ? '' : 'none';
      }
    }

    select.addEventListener('change', sync);
    sync();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
