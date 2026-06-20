<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CTMedia
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="all" />
  <?php if (ct_brand('keywords')) : ?>
  <meta name="keywords" content="<?php echo esc_attr(ct_brand('keywords')); ?>" />
  <?php endif; ?>
  <?php if (ct_brand('fb_profile_id')) : ?>
  <meta property="fb:profile_id" content="<?php echo esc_attr(ct_brand('fb_profile_id')); ?>" />
  <?php endif; ?>
  <meta property="og:type" content="website" />
  <meta property="og:locale" content="<?php echo esc_attr(ct_brand('og_locale', 'en_US')); ?>" />
  <meta property="og:image:alt" content="<?php echo esc_attr(wp_get_document_title()); ?>" />
  <?php if (ct_brand('favicon_dir')) : ?>
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(ct_brand_favicon_uri('apple-touch-icon.png')); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url(ct_brand_favicon_uri('favicon-32x32.png')); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url(ct_brand_favicon_uri('favicon-16x16.png')); ?>">
  <link rel="mask-icon" href="<?php echo esc_url(ct_brand_favicon_uri('safari-pinned-tab.svg')); ?>" color="<?php echo esc_attr(ct_brand('mask_icon_color', '#5bbad5')); ?>">
  <link rel="shortcut icon" href="<?php echo esc_url(ct_brand_favicon_uri('favicon.ico')); ?>">
  <meta name="msapplication-config" content="<?php echo esc_url(ct_brand_favicon_uri('browserconfig.xml')); ?>">
  <?php endif; ?>
  <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr(ct_brand('name')); ?>">
  <meta name="application-name" content="<?php echo esc_attr(ct_brand('name')); ?>">
  <meta name="msapplication-TileColor" content="<?php echo esc_attr(ct_brand('tile_color', '#da532c')); ?>">
  <meta name="theme-color" content="<?php echo esc_attr(ct_brand('theme_color', '#ffffff')); ?>">
  <?php wp_head(); ?>

  <?php //if (is_front_page() && is_page_template()) { ?>


  <?php //} 
    $generalSite = gen_GetGeneral();
    if ($generalSite['gen_bg_type'] == 'c_color') {
      $bgColor = strtoupper($generalSite['gen_bg_color']);
      if($bgColor == '#FFF' || $bgColor == '#FFFFFF'){
        echo '<style>.ct-bounding{border: 1px solid #f5f2f2;}</style>';
      }
    }
  ?>
</head>

<?php

$ctAppStyle = '';
if ($generalSite['gen_bg_type'] == 'c_color') {
  $bg = ct_normalize_hex($generalSite['gen_bg_color']);
  if ($bg !== '') {
    $ctAppStyle = 'background-color: ' . $bg;
  }
} else {
  $position = str_replace('_', ' ', $generalSite['gen_bg_image_position']);
  $imgUrl   = esc_url($generalSite['gen_bg_image_url']);
  $ctAppStyle  = 'background: #faf8f2 url(' . $imgUrl . ') ' . $generalSite['gen_bg_image_repeat'] . ' ' . $generalSite['gen_bg_image_attachment'] . ' ' . $position . ';';
  $ctAppStyle .= 'background-size: ' . $generalSite['gen_bg_image_size'];
} ?>

<body <?php body_class(); ?> style="<?php echo esc_attr($ctAppStyle); ?>">
  <?php wp_body_open(); ?>
  <div id="ct-app" class="site">
    <nav id="site-nav" class="navbar bg-dark <?php echo esc_attr('nav-style-' . $generalSite['nav_style']); ?>">
      <div class="container">
        <a class="navbar-brand" href="<?php echo esc_url(CT_HOME_URL); ?>">
          <?php echo esc_html(CT_NAME); ?>
        </a>
        <button id="nav-mobile-toggler" class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
          data-bs-target="#siteNavbar" aria-controls="siteNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <svg class="menu-open" xmlns="http://www.w3.org/2000/svg" width="97" height="20" viewBox="0 0 97 20">
            <path fill="currentColor" fill-rule="evenodd"
              d="M739.166667,21 C739.626904,21 740,21.4477153 740,22 C740,22.5522847 739.626904,23 739.166667,23 L720.833333,23 C720.373096,23 720,22.5522847 720,22 C720,21.4477153 720.373096,21 720.833333,21 L739.166667,21 Z M694.247784,5 L694.247784,14.6969697 L694.255569,15.0138843 C694.276328,15.4358494 694.338603,15.8554637 694.442396,16.2727273 C694.572138,16.7943067 694.784769,17.2607897 695.080291,17.6721763 C695.375812,18.0835629 695.765036,18.4141414 696.247962,18.6639118 C696.730887,18.9136823 697.332743,19.0385675 698.053527,19.0385675 C698.759896,19.0385675 699.358147,18.9136823 699.848281,18.6639118 C700.338414,18.4141414 700.731242,18.0835629 701.026764,17.6721763 C701.322285,17.2607897 701.534917,16.7943067 701.664658,16.2727273 C701.768451,15.8554637 701.830727,15.4358494 701.851485,15.0138843 L701.85927,14.6969697 L701.85927,5 L704,5 L704,14.8292011 L703.992719,15.1676505 C703.963593,15.841401 703.847091,16.4962613 703.643212,17.1322314 C703.405353,17.8741965 703.041356,18.5316804 702.551223,19.1046832 C702.061089,19.677686 701.441215,20.1368228 700.691599,20.4820937 C699.941983,20.8273646 699.062626,21 698.053527,21 C697.044429,21 696.165071,20.8273646 695.415456,20.4820937 C694.66584,20.1368228 694.045965,19.677686 693.555831,19.1046832 C693.065698,18.5316804 692.701702,17.8741965 692.463843,17.1322314 C692.259964,16.4962613 692.143461,15.841401 692.114336,15.1676505 L692.107054,14.8292011 L692.107054,5 L694.247784,5 Z M646.330025,5 L650.914215,16.9889807 L650.957462,16.9889807 L655.476781,5 L658.806806,5 L658.806806,20.6033058 L656.666076,20.6033058 L656.666076,7.55647383 L656.622829,7.55647383 L651.541297,20.6033058 L650.179015,20.6033058 L645.119107,7.55647383 L645.07586,7.55647383 L645.07586,20.6033058 L643,20.6033058 L643,5 L646.330025,5 Z M672.343141,5 L672.343141,6.91735537 L664.666785,6.91735537 L664.666785,11.5895317 L671.889046,11.5895317 L671.889046,13.4628099 L664.666785,13.4628099 L664.666785,18.6639118 L672.667494,18.6639118 L672.667494,20.6033058 L662.526055,20.6033058 L662.526055,5 L672.343141,5 Z M678.311237,5 L686.311946,17.4738292 L686.355193,17.4738292 L686.355193,5 L688.495923,5 L688.495923,20.6033058 L685.792981,20.6033058 L677.705778,7.86501377 L677.662531,7.86501377 L677.662531,20.6033058 L675.521801,20.6033058 L675.521801,5 L678.311237,5 Z M739.166667,12 C739.626904,12 740,12.4477153 740,13 C740,13.5522847 739.626904,14 739.166667,14 L720.833333,14 C720.373096,14 720,13.5522847 720,13 C720,12.4477153 720.373096,12 720.833333,12 L739.166667,12 Z M739.166667,3 C739.626904,3 740,3.44771525 740,4 C740,4.55228475 739.626904,5 739.166667,5 L720.833333,5 C720.373096,5 720,4.55228475 720,4 C720,3.44771525 720.373096,3 720.833333,3 L739.166667,3 Z"
              transform="translate(-643 -3)" />
          </svg>
          <svg class="menu-close" xmlns="http://www.w3.org/2000/svg" width="97" height="20" viewBox="0 0 97 20">
            <path fill="currentColor" fill-rule="evenodd"
              d="M694.247784,32 L694.247784,41.6969697 L694.255569,42.0138843 C694.276328,42.4358494 694.338603,42.8554637 694.442396,43.2727273 C694.572138,43.7943067 694.784769,44.2607897 695.080291,44.6721763 C695.375812,45.0835629 695.765036,45.4141414 696.247962,45.6639118 C696.730887,45.9136823 697.332743,46.0385675 698.053527,46.0385675 C698.759896,46.0385675 699.358147,45.9136823 699.848281,45.6639118 C700.338414,45.4141414 700.731242,45.0835629 701.026764,44.6721763 C701.322285,44.2607897 701.534917,43.7943067 701.664658,43.2727273 C701.768451,42.8554637 701.830727,42.4358494 701.851485,42.0138843 L701.85927,41.6969697 L701.85927,32 L704,32 L704,41.8292011 L703.992719,42.1676505 C703.963593,42.841401 703.847091,43.4962613 703.643212,44.1322314 C703.405353,44.8741965 703.041356,45.5316804 702.551223,46.1046832 C702.061089,46.677686 701.441215,47.1368228 700.691599,47.4820937 C699.941983,47.8273646 699.062626,48 698.053527,48 C697.044429,48 696.165071,47.8273646 695.415456,47.4820937 C694.66584,47.1368228 694.045965,46.677686 693.555831,46.1046832 C693.065698,45.5316804 692.701702,44.8741965 692.463843,44.1322314 C692.259964,43.4962613 692.143461,42.841401 692.114336,42.1676505 L692.107054,41.8292011 L692.107054,32 L694.247784,32 Z M646.330025,32 L650.914215,43.9889807 L650.957462,43.9889807 L655.476781,32 L658.806806,32 L658.806806,47.6033058 L656.666076,47.6033058 L656.666076,34.5564738 L656.622829,34.5564738 L651.541297,47.6033058 L650.179015,47.6033058 L645.119107,34.5564738 L645.07586,34.5564738 L645.07586,47.6033058 L643,47.6033058 L643,32 L646.330025,32 Z M672.343141,32 L672.343141,33.9173554 L664.666785,33.9173554 L664.666785,38.5895317 L671.889046,38.5895317 L671.889046,40.4628099 L664.666785,40.4628099 L664.666785,45.6639118 L672.667494,45.6639118 L672.667494,47.6033058 L662.526055,47.6033058 L662.526055,32 L672.343141,32 Z M721.756787,30.3013712 L730,38.545 L738.243464,30.3013712 C738.645302,29.8995429 739.296783,29.8995429 739.698621,30.3013712 C740.10046,30.7031996 740.10046,31.3546637 739.698621,31.7567432 L731.455,40 L739.698621,48.2430057 C740.10046,48.644834 740.10046,49.2962981 739.698621,49.6981264 C739.496949,49.8985383 739.233744,50 738.971796,50 C738.708341,50 738.445136,49.899794 738.24472,49.6981264 L730,41.454 L721.756787,49.6981264 C721.555115,49.8985383 721.291659,50 721.028455,50 C720.765,50 720.501545,49.899794 720.301379,49.6981264 C719.89954,49.2962981 719.89954,48.644834 720.301379,48.2430057 L728.545,39.999 L720.301379,31.7567432 C719.89954,31.3546637 719.89954,30.7031996 720.301379,30.3013712 C720.703217,29.8995429 721.354698,29.8995429 721.756787,30.3013712 Z M678.311237,32 L686.311946,44.4738292 L686.355193,44.4738292 L686.355193,32 L688.495923,32 L688.495923,47.6033058 L685.792981,47.6033058 L677.705778,34.8650138 L677.662531,34.8650138 L677.662531,47.6033058 L675.521801,47.6033058 L675.521801,32 L678.311237,32 Z"
              transform="translate(-643 -30)" />
          </svg>
          <!-- <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span>
          <span></span> -->
        </button>
        <div class="collapse navbar-collapse" id="siteNavbar">
          <?php
          wp_nav_menu(array(
            'theme_location'  => 'primary',
            'depth'           => 3, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'div',
            'container_class' => '',
            'container_id'    => 'ct-main-menu',
            'menu_class'      => 'ct-main-menu'
          ));
          ?>
        </div>
      </div>
    </nav>
    <div id="site-header" class="header-content">
      <?php
      $CTheader = gen_GetHeader();
      if ($CTheader['type'] == 'c_content') {
        include locate_template('template-parts/header/header-text.php', false, false);
      } else {
        include locate_template('template-parts/header/header-image.php', false, false);
      }
      ?>
    </div>

    <?php if ( !is_404() ):  ?>
    <?php if ( ct_brand_feature('loichua') ) : ?>
    <?php include locate_template('template-parts/boxes/loichua.php', false, false); ?>
    <?php endif; ?>
    <?php include locate_template('template-parts/boxes/featured-widget.php', false, false); ?>
    <?php endif;  ?>
    <div id="site-content" class="ct-mt">
      <div class="container">