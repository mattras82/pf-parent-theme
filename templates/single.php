<div class="grid-container">
    <div class="grid-x grid-margin-x grid-padding-y">
        <div class="cell medium-auto medium-order-1">
            <article <?php post_class('entry') ?> id="post-<?php the_ID(); ?>">
                <?php while (have_posts()): the_post(); ?>
                    <header class="entry-header">
                        <div class="entry-meta font-bold text-uppercase">
                            <?php if (in_array(get_post_type(), array('post', 'attachment'))): ?>
                                <span class="post-date">
                            <a href="<?= esc_url(get_permalink()) ?>" rel="bookmark">
                                <time class="entry-date" datetime="<?= esc_attr(get_the_date('c')) ?>"><?= get_the_date() ?></time>
                            </a>
                        </span>
                            <?php endif ?>
                        </div>
                        <?php the_title('<h1 class="entry-title h2">', '</h1>'); ?>
                    </header>

                    <?php pf_post_thumbnail('large'); ?>

                    <div class="entry-content">
                        <?php the_content() ?>
                    </div>

                    <?php comments_template(); ?>
                <?php endwhile ?>
            </article>
        </div>
        <aside class="cell medium-3">
            <?php pf_partial('sidebar') ?>
        </aside>
    </div>
</div>
