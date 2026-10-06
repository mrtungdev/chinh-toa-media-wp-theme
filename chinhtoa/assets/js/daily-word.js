/**
 * "Lời Chúa hôm nay" + widget "Lịch Lời Chúa".
 *
 * - Bấm ngày trên lịch: nếu trang có khối .ct__daily cùng chuyên mục → tải thẻ bài của
 *   ngày đó (AJAX ct_daily_word) và thay tại chỗ; không có khối → để link mở trang bài.
 * - Bấm ‹ ›: tải bảng lịch tháng khác (AJAX ct_daily_calendar); lỗi thì mở link ?ct_cal=.
 *
 * Event uỷ quyền trên document vì khối trang chủ được nạp sau qua AJAX.
 * Endpoint: inc/loichua/daily.php. URL AJAX: biến global ct_ajax_url (inc/utilities/enqueue.php).
 */
(function () {
  'use strict';

  function request(params) {
    var base = window.ct_ajax_url || '/wp-admin/admin-ajax.php';
    var url = new URL(base, window.location.href);
    Object.keys(params).forEach(function (k) {
      url.searchParams.set(k, params[k]);
    });
    return fetch(url.toString(), { credentials: 'same-origin' }).then(function (res) {
      if (!res.ok) {
        throw new Error('HTTP ' + res.status);
      }
      return res.text();
    });
  }

  function selectedDate(cal) {
    var td = cal.querySelector('td.is-selected a[data-date]');
    return td ? td.getAttribute('data-date') : '';
  }

  function loadMonth(cal, ym, fallbackUrl) {
    cal.setAttribute('aria-busy', 'true');
    request({
      action: 'ct_daily_calendar',
      ym: ym,
      cat: cal.getAttribute('data-cat') || '0',
      selected: cal.getAttribute('data-selected') || selectedDate(cal)
    }).then(function (html) {
      var tmp = document.createElement('div');
      tmp.innerHTML = html.trim();
      var fresh = tmp.firstElementChild;
      if (!fresh) {
        throw new Error('empty');
      }
      if (cal.getAttribute('data-selected')) {
        fresh.setAttribute('data-selected', cal.getAttribute('data-selected'));
      }
      cal.replaceWith(fresh);
    }).catch(function () {
      // Lỗi mạng / admin-ajax bị chặn → dùng link không-JS (?ct_cal=Y-m).
      cal.removeAttribute('aria-busy');
      if (fallbackUrl) {
        window.location.href = fallbackUrl;
      }
    });
  }

  function markSelected(cal, ymd) {
    cal.setAttribute('data-selected', ymd);
    cal.querySelectorAll('td.is-selected').forEach(function (td) {
      td.classList.remove('is-selected');
    });
    var link = cal.querySelector('a[data-date="' + ymd + '"]');
    if (link) {
      link.parentNode.classList.add('is-selected');
    }
  }

  function loadDay(box, cal, link) {
    var ymd = link.getAttribute('data-date');
    var body = box.querySelector('.ct__daily-body') || box;
    body.setAttribute('aria-busy', 'true');
    request({ action: 'ct_daily_word', date: ymd, cat: cal.getAttribute('data-cat') || '0' })
      .then(function (html) {
        body.innerHTML = html;
        body.removeAttribute('aria-busy');
        markSelected(cal, ymd);
        // Mobile: thanh bên nằm dưới nội dung → cuộn lên để thấy bài vừa đổi.
        var top = box.getBoundingClientRect().top;
        if (top < 0 || top > window.innerHeight * 0.6) {
          box.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      })
      .catch(function () {
        // Lỗi mạng → mở trang bài như link thường.
        window.location.href = link.href;
      });
  }

  document.addEventListener('click', function (e) {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
      return; // giữ hành vi mở tab mới của trình duyệt.
    }
    var nav = e.target.closest('.ct-lc-cal [data-ct-cal-month]');
    if (nav) {
      e.preventDefault();
      loadMonth(nav.closest('.ct-lc-cal'), nav.getAttribute('data-ct-cal-month'), nav.href);
      return;
    }
    var link = e.target.closest('.ct-lc-cal a[data-date]');
    if (!link) {
      return;
    }
    var cal = link.closest('.ct-lc-cal');
    var box = document.querySelector('.ct__daily[data-ct-daily-cat="' + (cal.getAttribute('data-cat') || '0') + '"]');
    if (!box) {
      return; // trang không có khối → mở trang bài.
    }
    e.preventDefault();
    loadDay(box, cal, link);
  });
})();
