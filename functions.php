<?php

if (!function_exists('pf')) {
    // Main functionality for this theme.
    require_once "inc/theme.php";
}

if (!is_admin() && !function_exists('pf_partial')) {
	PublicFunction\Theme::stop(
		'This theme requires the <a href="https://github.com/mattras82/pf-wp-toolkit" target="_blank" rel="noopener">PublicFunction Wordpress Toolkit Plugin</a> in order to function properly. Please install and activate that plugin, or try using a different theme.',
		null,
		'Missing PF WP Toolkit'
	);
}

// Don't place any code after this line
pf_start();
