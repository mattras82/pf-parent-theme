<?php

namespace PublicFunction\Setup;

use PublicFunction\Core\RunableAbstract;

class ThemeSupport extends RunableAbstract
{
    public function supports()
    {
        // Adds the ability for plugins and themes to handle the themes title tag
        // instead of using wp_title
        add_theme_support('title-tag');

        // Adds featured images to posts
        add_theme_support('post-thumbnails');

        // Adds HTML5 markup for the theme
        add_theme_support('html5', [
            'caption',
            'comment-form',
            'comment-list',
            'gallery',
            'search-form'
        ]);

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

	    // Custom Image Sizes
	    $image_sizes = $this->get('theme.image.sizes');
	    if (is_array($image_sizes) && count($image_sizes) > 0) {
		    foreach ($image_sizes as $name => $size) {
			    $crop = isset($size['crop']) ? $size['crop'] : false;
			    add_image_size($name, $size['width'], $size['height'], $crop);
		    }
	    } else {
		    add_image_size('pf-preview-admin', 100, 100, true);
	    }

        // Get config to customize gutenberg settings
        $sass_config = pf()->config()['styles']['sass'];
        if (isset($sass_config['theme_palette']) && !empty($sass_config['theme_palette'])) {
            $palette = [];
            foreach ($sass_config['theme_palette'] as $color => $hex) {
                if ($hex === '$theme_color') $hex = $sass_config['theme_color'];
                $palette[] = [
                    'name' => __(ucwords($color), $this->get('textdomain')),
                    'slug' => $color,
                    'color' => $hex,
                ];
            }

            add_theme_support('editor-color-palette', $palette);
            add_theme_support('disable-custom-colors');
        }

        // Wide Alignment
        add_theme_support('align-wide');

        // Adds the frontend stylesheet to the editor
        add_theme_support('editor-styles');
        add_editor_style($this->get('theme_css') . 'theme.css');

        // Registers our menu's
        register_nav_menus([
            'top-bar' => __('Top Bar', $this->get('textdomain')),
            'footer-menu'  => __('Footer Menu', $this->get('textdomain')),
        ]);

        // Yoast Breadcrumbs
        add_theme_support('yoast-seo-breadcrumbs');
    }

    /**
     *  Registers default widget areas
     */
    public function widgetAreas()
    {
        register_sidebar([
            'id'            => 'default',
            'name'			=> __('Sidebar', $this->get('textdomain')),
            'description'	=> '',
            'before_widget' => '<div class="widget blog-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'	=> '<h4 class="widget-title">',
            'after_title'	=> '</h4>',
        ]);

        register_sidebar([
            'id'            => '404',
            'name'			=> __('404', $this->get('textdomain')),
            'description'	=> '',
            'before_widget' => '<div class="widget blog-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'	=> '<h4 class="widget-title">',
            'after_title'	=> '</h4>',
        ]);

        register_sidebars(4, [
          'name' => __('Footer %d', $this->get('textdomain')),
          'id' => 'footer',
          'description' => '',
          'before_widget' => '<div class="widget blog-widget %2$s">',
          'after_widget' => '</div>',
          'before_title' => '<h2 class="widget-title">',
          'after_title' => '</h2>'
        ]);
    }

    /**
     * Add support for buttons in the top-bar menu:
     * 1) In WordPress admin, go to Appearance -> Menus.
     * 2) Click 'Screen Options' from the top panel and enable 'CSS CLasses' and 'Link Relationship (XFN)'
     * 3) On your menu item, type 'has-form' in the CSS-classes field. Type 'button' in the XFN field
     * 4) Save Menu. Your menu item will now appear as a button in your top-menu
     *
     * @param string $ulClass
     * @return string
     */
    public function menuButtons($ulClass)
    {
        return preg_replace(
            ['/<a rel="button"/', '/<a title=".*?" rel="button"/'],
            ['<a rel="button" class="button"', '<a rel="button" class="button"'],
            $ulClass,
            1
        );
    }

    /**
     * Prints the logo on the login screen
     * @return bool|void
     */
    public function loginLogo()
    {
        $file = $ext = false;

        foreach(['png', 'jpg', 'jpeg', 'gif'] as $_ext) {
            if(file_exists($this->get('theme.path') . "/assets/images/logo.{$_ext}")) {
                $file = $this->get('assets.images') . "logo.{$_ext}";
                $file_path = $this->get('assets.images_path') . "logo.{$_ext}";
                $ext = $_ext;
                break;
            }
        }

        if(!$file) return;
        $imageSize = getimagesize($file_path);
        $width  = $imageSize[0];
        $height = $imageSize[1];
        ?>
        <!-- custom <?= $this->get('theme.short_name'); ?> login logo -->
        <style>
            .login h1 a {
                display: block;
                background-image: url("<?php pf_image('logo.' . $ext) ?>") !important;
                background-size: cover !important;
                width: 100% !important;
                height: 0 !important;
                padding-bottom: <?= ($height / $width) * 100 ?>% !important;
            }
        </style>
        <!-- /custom <?= $this->get('theme.short_name'); ?> login logo -->
        <?php
    }

    public function siteIconUrl($url) {
        if ($icon = $this->get('theme.icon')) {
            return $this->get('assets.images') . $icon['name'] . '.png';
        }
        return $url;
    }

    public function replaceIcons($meta_tags)
    {
        if ($icon = $this->get('theme.icon')) {
            $path = $this->get('assets.images') . $icon['name'];
            $new_icons = [
                sprintf('<link rel="icon" href="%s" sizes="32x32" />', esc_url( $path . '-32.png' )),
                sprintf('<link rel="icon" href="%s" sizes="192x192" />', esc_url( $path . '-192.png' )),
                sprintf('<link rel="apple-touch-icon" href="%s" />', esc_url( $path . '-192.png' )),
                sprintf('<meta name="msapplication-TileImage" content="%s" />', esc_url( $path . '-270.png' ))
            ];
            return $new_icons;
        }
        return $meta_tags;
    }

    public function maintenancePage() {
        if (!(get_page_by_path('scheduled-maintenance'))) {
            wp_insert_post([
                'post_type'     => 'page',
                'post_title'    => 'Scheduled Maintenance',
                'post_status'   => 'publish',
                'post_name'     => 'scheduled-maintenance',
                'post_content'  => 'The site is briefly unavailable for schedule maintenance. Check back soon.',
                'post_author'   => 1,
                'page_template' => 'templates/template-error-message.php',
            ]);
        }
    }

	public function relAttrMenuLinks($atts, $item)
	{
		if ($atts['target'] === '_blank') {
			$atts['rel'] = 'noopener';
		}
		if (!empty($item->classes)) {
			$classes = (array) $item->classes;
			$atts['class'] = '';
			foreach ($classes as $class) {
			    $atts['class'] .= esc_attr($class) . ' ';
            }
			$atts['class'] = trim($atts['class']);
		}
		return $atts;
	}
    
    public function ob_end_flush_all() {
        while (@ob_end_flush());
    }

    public function run()
    {
        $this->loader()->addAction('after_setup_theme', [$this, 'supports']);
        $this->loader()->addAction('widgets_init', [$this, 'widgetAreas']);
        $this->loader()->addFilter('wp_nav_menu', [$this, 'menuButtons']);
        $this->loader()->addAction('login_enqueue_scripts', [$this, 'loginLogo']);
        $this->loader()->addFilter('site_icon_meta_tags', [$this, 'replaceIcons']);
        $this->loader()->addFilter('get_site_icon_url', [$this, 'siteIconUrl']);
        $this->loader()->addAction('after_setup_theme', [$this, 'maintenancePage']);
	    $this->loader()->addFilter('nav_menu_link_attributes', [$this, 'relAttrMenuLinks'], 10, 2);
        if (remove_action('shutdown', 'wp_ob_end_flush_all')) {
            $this->loader()->addAction('shutdown', [$this, 'ob_end_flush_all']);
        }
    }
}
