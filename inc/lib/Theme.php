<?php

namespace PublicFunction;

use PublicFunction\Core\Container;
use PublicFunction\Setup\JsonConfig;
use PublicFunction\Core\Loader;
use PublicFunction\Core\SingletonTrait;
use PublicFunction\Setup\ScriptsAndStyles;
use PublicFunction\Setup\TemplateSupport;
use PublicFunction\Setup\ThemeSupport;
use PublicFunction\Template\Template;

class Theme
{
    use SingletonTrait;

    /**
     * Flag used to check for singleton instance
     * @var bool
     */
    protected $started = false;

    /**
     * Used to store the config.json file wrapped in a JsonConfig object
     * @var JsonConfig
     */
    protected $config;

    /**
     * Storage for all
     * @var Container
     */
    protected $container;

    /**
     * Used to enqueue actions and filters for the theme.
     * @var Loader
     */
    protected $loader;

    protected function __construct()
    {
        $_theme_dir = trailingslashit(get_stylesheet_directory_uri());
        $_theme_path = trailingslashit(get_theme_root() . DIRECTORY_SEPARATOR . get_stylesheet());
        $this->config = new JsonConfig($_theme_path . 'config/config.json');
        $this->container = new Container([
            // General
            // -------------------------------------
            'debug' => $this->config['debug'],
            'theme' => [
                'name' => $this->config['theme']['name'],
                'short_name' => $this->config['theme']['short_name'],
                'directory' => $_theme_dir,
                'path' => $_theme_path,
                'version' => $this->config['version'],
                'parent_version' => '1.2.0',
                'config_path' => $_theme_path . 'config/',
                'color'     => $this->config['styles']['sass']['theme_color'],
                'icon'      => $this->config['styles']['icon'],
	            'image'     => $this->config['styles']['image'],
                'breakpoints' => $this->config['styles']['sass']['theme_breakpoints'],
                'build' => $this->config['build'] ?: $this->config['version']
            ],

            'env' => [
                'production' => $this->config['env']['production'],
                'development' => $this->config['env']['development']
            ],

            'textdomain' => $this->config['theme']['short_name'],

            // Asset paths and directories
            // -------------------------------------
            'assets' => [
                'dir' => trailingslashit($_theme_dir . 'assets'),
                'path' => trailingslashit($_theme_path . 'assets'),

                'images' => trailingslashit($_theme_dir . 'assets/images'),
                'images_path' => trailingslashit($_theme_path . 'assets/images'),
            ],

            // Core and theme support
            // -------------------------------------

            'loader' => function () {
                return new Loader();
            },

            'template_support' => function (Container &$c) {
                return new TemplateSupport($c);
            },

            'theme_support' => function (Container &$c) {
                return new ThemeSupport($c);
            },

            'tag_manager' => function (Container &$c) {
                return new Setup\GoogleTagManager($c);
            },

            // Stylesheets and Script registration
            // -------------------------------------
            'front_end_assets' => function (Container &$c) {
                $assets = new ScriptsAndStyles($c);

                // Stylesheets

                $assets->style('main', $c->get('assets.dir') . 'theme.css');

                $dependencies = ['jquery'];

                // Scripts
                $assets->script('main', $c->get('assets.dir') . 'theme.js', $dependencies);
                return $assets;
            },

            'offline_page'          => $this->config['offline_page']
        ]);
    }

    /**
     * Runs the app
     */
    protected function _run()
    {
        foreach ($this->container->getRunables() as $name => $runable) {
            if ($name != 'loader')
                $runable->run();
        }

        $this->loader()->run();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // API
    //
    // Methods here are used throughout the theme. You can use these methods
    // by calling pf()->filter() or pf('theme.path') which is the same as
    // Theme::getInstance()->container()->get('theme.path'). Passing a string
    // to the pf() wrapper function returns an object from the container while
    // using the method pointer `->` returns one of the following methods.
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param string $offset
     * @return mixed|null
     */
    public function get($offset)
    {
        return $this->container->get($offset);
    }

    /**
     * Returns the container
     * @return Container
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * Returns the loader
     * @return Loader
     */
    public function loader()
    {
        return $this->container->get('loader');
    }

	/**
	 * Returns the config file
	 * @since 1.0.2
	 * @return \PublicFunction\Setup\JsonConfig
	 */
    public function config()
    {
    	return $this->config;
    }

    /**
     * Returns the wrapped template
     * @param string $layout
     * @return Template
     */
    public function template($layout = 'base')
    {
        return Template::getInstance($layout);
    }

	/**
	 * Adds a script to the theme.
	 * @param string $handle
	 * @param string $source
	 * @param array|string $dependencies
	 * @param null|int|string $version
	 * @param string $screen
	 * @param bool $admin
	 * @param callable $blocker
	 * @return mixed
	 */
	public function style($handle, $source, $dependencies = [], $version = 'asset', $screen = 'all', $admin = false, callable $blocker = null)
	{
		return self::getInstance()->get($admin ? 'admin_assets' : 'front_end_assets')
			->style($handle, $source, $dependencies, $version, $screen, $blocker);
	}

	/**
	 * Adds a script to the theme
	 * @param string $handle
	 * @param string $source
	 * @param array|string $dependencies
	 * @param null|string|int $version
	 * @param bool $footer
	 * @param bool $admin
	 * @param callable|null $blocker
	 * @param bool $defer
	 * @return mixed
	 */
	public function script($handle, $source, $dependencies = [], $version = null, $footer = true, $admin = false, callable $blocker = null, $defer = false)
	{
		return self::getInstance()->get($admin ? 'admin_assets' : 'front_end_assets')
			->script($handle, $source, $dependencies, $version, $footer, $blocker, $defer);
	}

    /**
     * @param string $handle
     * @param array|string|object $object
     * @param array $data
     * @param bool $admin
     */
    public function localize($handle, $object, $data = [], $admin = false)
    {
        return self::getInstance()->get($admin ? 'admin_assets' : 'front_end_assets')
            ->localize($handle, $object, $data);
    }

    /**
     * Adds an action to the theme
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @param int $args
     * @return $this
     */
    public function action($hook, callable $callback, $priority = 10, $args = 1)
    {
        $instance = self::getInstance();
        $instance->loader()->addAction($hook, $callback, $priority, $args);
        return $instance;
    }

    /**
     * Adds a filter
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @param int $args
     * @return $this
     */
    public function filter($hook, callable $callback, $priority = 10, $args = 1)
    {
        $instance = self::getInstance();
        $instance->loader()->addFilter($hook, $callback, $priority, $args);
        return $instance;
    }

	/**
	 * Adds a shortcode
	 * @param string $hook
	 * @param callable $callback
	 * @return $this
	 */
	public function shortcode($hook, callable $callback)
	{
		$instance = self::getInstance();
		$instance->loader()->addShortcode($hook, $callback);
		return $instance;
	}

    /**
     * Returns prefixed string with short namespace
     * @param string $name
     * @return string
     */
    public function prefix($name = '')
    {
        return $this->get('front_end_assets')->prefix($name);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Static API
    //
    // Primarily used to start and stop the theme
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Starts the theme
     * @return Theme
     */
    public static function start()
    {
        $instance = self::getInstance();

        if (!$instance->started) {
            $instance->_run();
            $instance->started = true;
        }

        return $instance;
    }

    /**
     * Kills the application and redirects to a wordpress error page with a message
     * @param string $error
     * @param string $subtitle
     * @param string $title
     */
    public static function stop($error, $subtitle = '', $title = '')
    {
        $title = $title ?: __('PublicFunction Theme - Error', self::getInstance()->get('textdomain'));
        $message = "<h1>{$title}";

        if ($subtitle)
            $message .= "<br><small>{$subtitle}</small>";

        $message .= "</h1>";
        $message .= "<p>{$error}</p>";

        wp_die($message);
    }
}
