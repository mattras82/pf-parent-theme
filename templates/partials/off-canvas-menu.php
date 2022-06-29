<div class="off-canvas position-left" id="off-canvas-menu" data-off-canvas data-transition="overlap">
  <div class="grid-container">
    <div class="cell off-canvas__navigation">
      <?php
      if (has_nav_menu('top-bar')) {
        wp_nav_menu([
          'container' => 'nav',
          'menu_class' => 'vertical menu accordion-menu',
          'theme_location' => 'top-bar',
          'depth' => pf_is_amp_endpoint() ? 1 : 5,
          'walker' => new \PublicFunction\Walkers\TopBarMenu('accordion'),
          'items_wrap' => '<ul id="%1$s" class="%2$s" data-accordion-menu>%3$s</ul>'
        ]);
      }
      ?>
    </div>
  </div>
</div>