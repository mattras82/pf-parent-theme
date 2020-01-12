<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="<?= pf('theme.color') ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <?php if (pf('env.production')) : ?>
    <link rel="manifest" href="<?= pf('theme.directory') . 'assets/manifest.json' ?>">
    <?php endif ?>
    <script>
        window.Promise || document.write(
            '<script src="//cdn.jsdelivr.net/npm/es6-promise/dist/es6-promise.auto.min.js"><\/script>'
        )
    </script>
    <noscript><style>img[data-src].no-js{display:none;}</style></noscript>
    <?php pf('tag_manager')->head() ?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php pf('tag_manager')->body() ?>
<?php do_action('pf.after_body') ?>
<div class="off-canvas-wrapper" role="document">
    <a class="show-on-focus" href="#main">Skip to content</a>
    <?php pf_partial('off-canvas-menu'); ?>

    <div class="off-canvas-content" data-off-canvas-content>
        <?php do_action('get_header') ?>
        <?php pf_partial('header') ?>

        <main role="main" id="main"<?php pf_content_classes() ?>>
            <?php pf()->template()->main(); ?>
        </main>

        <?php do_action('get_footer') ?>
        <?php pf_partial('footer') ?>
    </div>
</div>
<?php wp_footer() ?>
</body>
</html>
