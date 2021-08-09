<?php if (have_posts()) : ?>
    <div class="entries">
        <?php while(have_posts()): the_post() ?>
            <article id="post-<?php the_ID() ?>" <?php post_class('entry') ?>>
                <?php pf_post_thumbnail(); ?>
                <header>
                    <h1 class="h4 post-title"><a href="<?php the_permalink() ?>" title="<?php the_title() ?>"><?php the_title() ?></a></h1>
                    <?php if (in_array(get_post_type(), array('post', 'attachment'))): ?>
                        <span class="post-date">
                            <a href="<?= esc_url(get_permalink()) ?>" rel="bookmark">
                                <time class="entry-date" datetime="<?= esc_attr(get_the_date('c')) ?>"><?= get_the_date() ?></time>
                            </a>
                        </span>
                    <?php endif ?>
                    <?php edit_post_link(__('Edit', pf('textdomain'))) ?>
                </header>
                <div class="post-excerpt"><?php the_excerpt() ?></div>
            </article>
        <?php endwhile ?>
    </div>
<?php endif ?>
