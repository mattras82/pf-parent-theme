<div class="grid-container">
  <div class="grid-x grid-margin-x grid-padding-y">
    <div class="cell">
      <header>
        <h1 class="page-title">
          <?php if ( have_posts() ) : ?>
            <?php printf( __( 'Search Results for: %s', pf('textdomain') ), '<span>' . esc_html(get_search_query(false)) . '</span>' ); ?>
          <?php else : ?>
            <?php _e( 'Nothing Found', pf('textdomain') ); ?>
          <?php endif; ?>
        </h1>
      </header>
      <?php pf_partial('loop')?>
    </div>
  </div>
</div>
