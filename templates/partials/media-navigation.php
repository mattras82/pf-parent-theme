<?php
$media = new WP_Query([
    'post_type' => 'pf-media',
    'posts_per_page' => -1,
    'no_found_rows' => true,
    'post_status' => 'publish',
    'ignore_sticky_posts' => true,
]);

if ($media->have_posts()) : ?>
<div class="media-navigation">
    <h2>Media Suites</h2>
    <ul>
        <?php foreach ($media->posts as $post) : ?>
            <?php
            $post_title = get_the_title($post->ID);
            $title = (!empty($post_title)) ? $post_title : __('(no title)');
            ?>
            <li>
                <a href="<?php the_permalink($post->ID); ?>"><?php echo $title; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif;
