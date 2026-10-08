<!-- search -->
<form class="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" role="search">
	<div class="form-group mb-0">
    <input class="form-control" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Nhập nội dung tìm kiếm', 'chinhtoa'); ?>" aria-label="<?php esc_attr_e('Nhập nội dung tìm kiếm', 'chinhtoa'); ?>">
  </div>
</form>
<!-- /search -->