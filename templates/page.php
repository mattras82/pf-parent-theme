<div class="grid-container">
  <div class="grid-x grid-margin-x grid-padding-y">
    <?php while(have_posts()): the_post(); ?>
      <article <?php post_class('entry') ?> id="page-<?php the_ID() ?>">
        <header>
          <?php the_title('<h1 class="entry-title">', '</h1>') ?>
        </header>
        <?php the_content() ?>
      </article>
    <?php endwhile; ?>
  </div>
</div>
