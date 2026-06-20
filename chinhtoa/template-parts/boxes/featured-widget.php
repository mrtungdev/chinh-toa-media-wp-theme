<?php $featuredData = hot_GetData(); ?>
<?php if ($featuredData['is_show'] == 'y') : ?>
  <div id="ct__featured__box" class="ct__featured__box ct-mt">
    <div class="container">
      <div class="ct__featured__box-row bg-white ct-shadow ct-bounding <?php echo esc_attr($featuredData['classes']); ?>">
        <?php if ($featuredData['title'] != '') : ?>
          <div class="featured__box-header">
            <h2 class="ct__featured__box-title"><?php echo esc_html($featuredData['title']); ?></h2>
          </div>
        <?php endif; ?>
        <div class="featured__box-content">
          <?php echo
            do_shortcode(str_replace(array("<iframe", "</iframe>"), array('<div class="iframe-container embed-responsive embed-responsive-16by9"><iframe', "</iframe></div>"), $featuredData['noidung']));
          ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>