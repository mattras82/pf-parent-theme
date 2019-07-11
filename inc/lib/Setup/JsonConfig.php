<?php

namespace PublicFunction\Setup;

use PublicFunction\Core\JsonConfig as JsonConfigBase;

class JsonConfig extends JsonConfigBase
{
    protected function errorTitle()
    {
        return 'Missing main JSON config file:';
    }

    protected function errorMessage()
    {
        $msg  = '<h3 style="color:#666;font-size:16px;">';
        $msg .= 'This theme requires the <strong><code>config.json</code></strong> file to run.</h3>';
        $msg .= '<p>It is used for configuration within main PHP files, JavaScript files as well as a few SCSS/CSS files. ';
        $msg .= 'The file should be located in the /config directory of the theme.</p>';

        return $msg;
    }
}
