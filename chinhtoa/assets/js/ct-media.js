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

  $("#nav-mobile-toggler").on("click", function () {
    if (!$(this).hasClass("collapsed")) {
      $("#site-nav").removeClass("is-active");
    } else {
      $("#site-nav").addClass("is-active");
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
