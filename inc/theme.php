<?php
/**
 * PSR4 AutoLoader
 * http://www.php-fig.org/psr/psr-4
 */
spl_autoload_register(function($class) {
    $prefix = 'PublicFunction\\';
    $d = DIRECTORY_SEPARATOR;
    $base = __DIR__ . "{$d}lib{$d}";

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0)
        return;

    $relative_class = substr($class, $len);
    $file = $base . str_replace('\\', $d, $relative_class) . '.php';

    if (file_exists($file))
        require $file;

    return;
});

/**
 * This theme requires at least 5.5.12
 */
if(!version_compare('5.5.12', phpversion(), '<=')) {
    PublicFunction\Theme::stop(
        sprintf(__( 'You must be using PHP 5.5.12 or greater, currently running %s' ), phpversion()),
        __('Invalid PHP Version', 'aod')
    );
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Debug
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(!function_exists('pre')) {
    function pre($arg) {
        echo '<pre>';
        if(is_bool($arg) || is_null($arg)) {
            var_dump($arg);
        } else {
            print_r($arg);
        }
        echo '</pre>';
    }
}

if(!function_exists('dd')) {
    function dd() {
        foreach( func_get_args() as $arg )
            pre($arg);
        die();
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Template Helpers
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Returns an instance of the theme
 * @param null|string $name
 * @param null|string|callable $value
 * @return \PublicFunction\Theme|mixed
 */
function pf( $name = null, $value = null ) {
    $instance = \PublicFunction\Theme::getInstance();
    $container = $instance->container();

    if( !empty($value) )
        return $container->set($name, $value);

    if( !empty($name) ) {
        return $container->get($name);
    }

    return $instance;
}

/**
 * Starts the theme
 * @return void
 */
function pf_start() {
    PublicFunction\Theme::start();
}
