<?php
if (!defined('ABSPATH')) {
  exit;
}
$current_user = wp_get_current_user();
$techData = gen_GetTech();
// var_dump($current_user);
// if (user_can( $current_user, 'administrator' )) {
//   echo 'ADMIN';
// } else if(user_can( $current_user,'editor')) {
//   echo 'EDITOR';
// } else {
//   echo 'ANY';
// }

// var_dump($techData);

require_once CT_THEME_DIR . '/inc/admin/theme/templates/loi-chua.php';
?>
<h1 class="display-4 mt-0">Xin chào, <?php echo $current_user->display_name; ?>!</h1>
<p class="lead">Chào mừng bạn đã đến với Bảng Điều Khiển.</p>

<?php
// Intro / quick-start guide cards. Self-contained inline styles + WordPress
// .button + dashicons so they render correctly regardless of which admin CSS
// is loaded. Links point at real admin screens (and the optional brand support
// URL); each card opens its target so editors can find their way around fast.
$ct_intro_cards = array(
  array(
    'icon'    => 'dashicons-welcome-learn-more',
    'title'   => __('Hướng dẫn sử dụng', 'chinhtoa'),
    'desc'    => __('Bắt đầu nhanh và tìm hiểu cách quản trị website.', 'chinhtoa'),
    'url'     => admin_url('admin.php?page=theme-options'),
    'label'   => __('Xem hướng dẫn', 'chinhtoa'),
    'primary' => true,
  ),
  array(
    'icon'  => 'dashicons-admin-customizer',
    'title' => __('Thiết lập giao diện', 'chinhtoa'),
    'desc'  => __('Đổi màu, header, footer, trang chủ và các tuỳ chọn khác.', 'chinhtoa'),
    'url'   => admin_url('admin.php?page=ct-theme-settings'),
    'label' => __('Mở thiết lập', 'chinhtoa'),
  ),
  array(
    'icon'  => 'dashicons-edit',
    'title' => __('Viết bài mới', 'chinhtoa'),
    'desc'  => __('Soạn và đăng một bài viết mới lên website.', 'chinhtoa'),
    'url'   => admin_url('post-new.php'),
    'label' => __('Viết bài', 'chinhtoa'),
  ),
  array(
    'icon'  => 'dashicons-category',
    'title' => __('Chuyên mục', 'chinhtoa'),
    'desc'  => __('Quản lý các chuyên mục phân loại bài viết.', 'chinhtoa'),
    'url'   => admin_url('edit-tags.php?taxonomy=category'),
    'label' => __('Quản lý', 'chinhtoa'),
  ),
  array(
    'icon'  => 'dashicons-menu-alt3',
    'title' => __('Menu điều hướng', 'chinhtoa'),
    'desc'  => __('Sắp xếp menu hiển thị trên website.', 'chinhtoa'),
    'url'   => admin_url('nav-menus.php'),
    'label' => __('Chỉnh menu', 'chinhtoa'),
  ),
);
$ct_support = ct_brand('support_url');
if (!empty($ct_support)) {
  $ct_intro_cards[] = array(
    'icon'     => 'dashicons-sos',
    'title'    => __('Hỗ trợ kỹ thuật', 'chinhtoa'),
    'desc'     => __('Liên hệ đội ngũ kỹ thuật khi cần trợ giúp.', 'chinhtoa'),
    'url'      => $ct_support,
    'label'    => __('Liên hệ', 'chinhtoa'),
    'external' => true,
  );
}
?>
<style>
  .ct-intro-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 16px; margin: 20px 0; }
  .ct-intro-card { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 18px 18px 20px; display: flex; flex-direction: column; transition: border-color .15s, box-shadow .15s; }
  .ct-intro-card:hover { border-color: #2271b1; box-shadow: 0 1px 6px rgba(0, 0, 0, .06); }
  .ct-intro-card .dashicons { font-size: 30px; width: 30px; height: 30px; color: #2271b1; }
  .ct-intro-card h3 { margin: 12px 0 6px; font-size: 16px; }
  .ct-intro-card p { margin: 0 0 16px; color: #646970; font-size: 13px; line-height: 1.5; flex: 1; }
  .ct-intro-card .button { align-self: flex-start; }
</style>
<div class="ct-intro-cards">
  <?php foreach ($ct_intro_cards as $card) : ?>
    <div class="ct-intro-card">
      <span class="dashicons <?php echo esc_attr($card['icon']); ?>"></span>
      <h3><?php echo esc_html($card['title']); ?></h3>
      <p><?php echo esc_html($card['desc']); ?></p>
      <a href="<?php echo esc_url($card['url']); ?>"<?php echo !empty($card['external']) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?> class="button <?php echo !empty($card['primary']) ? 'button-primary' : ''; ?>"><?php echo esc_html($card['label']); ?></a>
    </div>
  <?php endforeach; ?>
</div>

<div class="p-3">
  <?php 
    $emptyText = '';
    // $emptyText = '<p>Tính năng này đang được phát triển.</p>';

    if (user_can( $current_user, 'administrator' )) {
      if (!empty($techData['template_admin_shortcode'])) {
        echo do_shortcode($techData['template_admin_shortcode']);
      } else {
        echo $emptyText;
      }
    } else if(user_can( $current_user,'editor')) {
      if (!empty($techData['template_editor_shortcode'])) {
        echo do_shortcode($techData['template_editor_shortcode']);
      } else {
        echo $emptyText;
      }
    } else {
      if (!empty($techData['template_else_shortcode'])) {
        echo do_shortcode($techData['template_else_shortcode']);
      } else {
        echo $emptyText;
      }
    }

  ?>
  
</div>