jQuery(function ($) {
  var windowWidth = window.innerWidth;
  var scrollTop = $("#ct-scrolltop");

  // FUNCTIONS
  function goToTop() {
    if (jQuery(this).scrollTop() > 200) {
      $(scrollTop).css("opacity", "1");
    } else {
      $(scrollTop).css("opacity", "0");
    }
  }

  function loadAjaxHomePage(parentEl, index) {
    jQuery.ajax({
      url: ct_ajax_url,
      type: 'post',
      data: {
        action: 'homepage_tabs_template_call',
        nonce: (typeof ct_ajax_nonce !== 'undefined' ? ct_ajax_nonce : ''),
        index: index,
      },
      beforeSend: function () {
        if ($(parentEl).children('.ct__post-content .ajax-loading-content').length <= 0) {
          jQuery(parentEl).children('.ct__post-content').empty();
          jQuery(parentEl).children('.ct__post-content').append('<div class="ajax-loading-content"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }
      },
      success: function (response) {
        $(parentEl).empty();
        $(parentEl).append(response);
      },
      error: function (e) {
        $(parentEl).empty();
        $(parentEl).append('<strong>Có lỗi trong quá trình tải dữ liệu, vui lòng thử lại.</strong>');
      },
    });
  }

  function addLightBoxImage() {
    $('#ct-content').find('a[href$=".gif"], a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"]').addClass('swipebox');
  }

  function addLightBoxGallery() {
    if (!$.fn.swipebox) return; // swipebox chỉ nạp ở trang chi tiết
    $('#ct-content').find('.wp-block-gallery').each(function (g) {
      if ($('a', this).length > 0) {
        $('a', this).attr('rel', function (i, attr) {
          return 'ct-gallery-' + (g + 1);
        });
      } else if ($('img', this).length > 0) {
        $('img', this).each(function(i, obj) {
            var imageSrc = $(obj).attr('src');
            $(obj).attr('href', imageSrc);
            $(obj).addClass('swipebox');
            $(obj).attr('rel', 'ct-gallery-' + (g + 1));
        });
      }
      $('.swipebox').swipebox();
    });
  }
  
  function addPhotonicLightBoxGallery() {
    if (!$.fn.swipebox) return;
    $('#ct-content').find('.photonic-stream').each(function (g) {
      $('a', this).attr('rel', function (i, attr) {
        // console.log("Attr: " + attr + g)
        // if (attr) {
        //   return attr + ' ct-photonic-gallery-' + (g + 1);
        // }
        return 'ct-photonic-gallery-' + (g + 1);
      });
      $('a', this).find('img').each(function (it, el) {
        var srcImage = $(el).attr('src');
        if(srcImage){
          $(this).closest('a').attr('href', srcImage);
          $(this).closest('a').attr('class', 'swipebox');
        }
      });
      $('.swipebox').swipebox();
    });
  }

  function homeFeaturedDynamicHeight() {
    if (windowWidth > 767) {
      var featuredNews = $('#ct-content .featured-homepage');
      if (featuredNews.length > 0) {
        var headlineNews = $(featuredNews).find('.headline-news');
        $(headlineNews).closest('.breaking-news-row').find('.other-news').css('height', headlineNews.height());
      }
    } else {
      $('#ct-content .featured-homepage .other-news').css('height', 'auto');
    }
  }

  function setTabDynamicHeight(tab) {
    var leftBox = $(tab).find('.ct__post-content .tab-data.active .ct__post-content-left .ct__post-item').height();
    $(tab).find('.ct__post-content').css('height', windowWidth > 767 ? leftBox : 'auto');
  }

  function sectionTabsDynamicHeight() {
    var tabList = $('.ct__post-tabs');
    if (tabList && tabList.length > 0) {
      $(tabList).each(function (i, tab) {
        setTabDynamicHeight(tab);
      });

    }
    if (windowWidth > 767) {
      var featuredNews = $('#ct-content .featured-homepage');
      if (featuredNews.length > 0) {
        var headlineNews = $(featuredNews).find('.headline-news');
        $(headlineNews).closest('.breaking-news-row').find('.other-news').css('height', headlineNews.height());
      }
    } else {
      $('#ct-content .featured-homepage .other-news').css('height', 'auto');
    }
  }

  function processMassTimeWidget() {
    if ($("#mass-times-widget").length > 0){
      if (!$("#mass-times-btn").hasClass("show")) {
        $("#mass-times-btn").addClass("show");
      }
    }
  }


  goToTop();
  var homepageFeatures = $('.homepage-dynamic-ajax');
  if (homepageFeatures.length > 0) {
    homepageFeatures.each(function (i, el) {
      var index = $(el).attr('data-ct-section');
      loadAjaxHomePage(el, index);
    });
  }
  setTimeout(() => {
    addLightBoxImage();
    addLightBoxGallery();
    addPhotonicLightBoxGallery();
    homeFeaturedDynamicHeight();
    sectionTabsDynamicHeight();
  }, 3000);
  
  setTimeout(() => {
    processMassTimeWidget();
  }, 5000);



  // CLICK
  $(scrollTop).on("click", function () {
    $("html, body").animate({ scrollTop: 0 }, 300);
    return false;
  });

  // Menu mobile: trạng thái theo sự kiện của Bootstrap collapse. (Trước đây dựa vào class
  // "collapsed" lúc click — Bootstrap 5 bắt click ở pha capture nên đổi class TRƯỚC, làm
  // .is-active bị đảo ngược và menu không hiện dạng lớp phủ.)
  var $siteNav = $("#site-nav");
  var $siteNavbar = $("#siteNavbar");
  $siteNavbar.on("show.bs.collapse", function () {
    $siteNav.addClass("is-active");
    $("html").addClass("ct-nav-open");
  });
  $siteNavbar.on("hidden.bs.collapse", function () {
    $siteNav.removeClass("is-active");
    $("html").removeClass("ct-nav-open");
  });
  function closeMobileNav() {
    if ($siteNavbar.hasClass("show")) {
      $("#nav-mobile-toggler").trigger("click");
    }
  }
  // Menu con trên mobile: chèn nút mũi tên cạnh mục cha để mở/thu (bấm chữ vẫn mở trang).
  // Nhánh chứa trang đang xem mở sẵn. Trên desktop nút bị ẩn bằng CSS (nav-menu.css).
  $("#ct-main-menu .menu-item-has-children").each(function () {
    var $li = $(this);
    var label = $.trim($li.children("a").first().text());
    var isCurrent = $li.is(".current-menu-ancestor, .current-menu-parent");
    var $btn = $('<button type="button" class="ct-submenu-toggle"></button>')
      .attr("aria-label", "Menu con: " + label)
      .attr("aria-expanded", isCurrent ? "true" : "false");
    $li.toggleClass("is-open", isCurrent).children("a").first().after($btn);
  });
  $(document).on("click", ".ct-submenu-toggle", function (e) {
    e.preventDefault();
    var $li = $(this).parent();
    var open = !$li.hasClass("is-open");
    $li.toggleClass("is-open", open);
    $(this).attr("aria-expanded", open ? "true" : "false");
  });

  // Desktop: menu con cấp 3+ của mục sát mép phải → mở sang trái để không tràn màn hình.
  $("#ct-main-menu").on("mouseenter focusin", "ul li.menu-item-has-children", function () {
    var $sub = $(this).children("ul");
    if (!$sub.length || window.innerWidth < 992) {
      return;
    }
    var right = $(this).offset().left + $(this).outerWidth() + $sub.outerWidth();
    $(this).toggleClass("opens-left", right > $(window).width() - 8);
  });

  // Chạm vào nền mờ (ngoài thẻ menu) hoặc bấm Esc → đóng menu.
  $siteNavbar.on("click", function (e) {
    if (e.target === this) {
      closeMobileNav();
    }
  });
  $(document).on("keydown", function (e) {
    if (e.key === "Escape") {
      closeMobileNav();
    }
  });

  $("#ct-post-sizes .post-text-size").on("click", function () {
    var item = this;
    var size = $(this).attr('data-size');
    $("#ct-post-sizes .post-text-size").removeClass('activated');
    $(item).addClass('activated');
    $('#ct-single-postcontent').removeClass('is-small is-normal is-medium is-large').addClass(size);
  });

  $(document).on("click", ".ct__post-tabs .ct__post-header a", function () {
    var dataTab = $(this).attr('data-tab');
    if (dataTab) {
      $(this).closest('.ct__post-header').find('a').removeClass('active');
      $(this).addClass("active");
      var contentTab = $(this).closest('.ct__post-tabs');
      $(contentTab).find('.ct__post-content .tab-data').removeClass('active');
      $(contentTab).find('.ct__post-content .tab-data.' + dataTab).addClass('active');
      setTabDynamicHeight(contentTab);
    }
  });

  // On Resize
  $(window).on("resize", function () {
    windowWidth = window.innerWidth;
    homeFeaturedDynamicHeight();
    sectionTabsDynamicHeight();
  });

  // On Scroll
  $(window).on("scroll", function () {
    goToTop();
  });
});
