<?php
/**
 * Template Name: Error Message
 */
?>
<div class="offline-container">
    <a href="/" class="margin-bottom-1">
        <img src="<?php pf_option('header.logo') ?>" alt="<?php bloginfo('sitename') ?>">
    </a>
    <?php while (have_posts()) : the_post() ?>
    <div><?php the_content() ?></div>
    <?php endwhile ?>
    <a href="javascript:window.history.back()" class="button large primary">Go Back</a>
</div>
