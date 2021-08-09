<div class="grid-container">
  <div class="grid-x grid-margin-x grid-padding-y">
    <section class="cell">
      <h1 class="entry-title"><?= __('Page Not Found', pf('textdomain')) ?></h1>
      <p>The page you requested does not exist. Browse our <a href="<?= get_post_type_archive_link('post'); ?>">blog</a> or search below.</p>
    </section>
    <section class="cell">
      <?php dynamic_sidebar('404'); ?>
    </section>
  </div>
</div>
