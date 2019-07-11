<?php

namespace PublicFunction\Template;

class Wrapper
{
    protected $slug;
    protected $template;
    protected $wrapper;

    public function __construct($template, $layout = 'templates/layouts/base.php')
    {
        $this->slug = sanitize_title(basename($layout, '.php'));
        $this->wrapper = [$layout];
        $this->template = $template;
        $str = substr($layout, 0, -4);

        array_unshift($this->wrapper, sprintf($str . '-%s.php', basename($template, '.php')));
    }

    /**
     * Get the wrapper template file
     * @return string
     */
    public function wrap()
    {
        $wrappers = apply_filters('pf_template_wrap_' . $this->slug, $this->wrapper) ?: $this->wrapper;
        return locate_template($wrappers);
    }

    /**
     * Get the slug
     * @return string
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function unwrap()
    {
        $template = apply_filters('pf_template_unwrap_' . $this->slug, $this->template) ?: $this->template;
        return locate_template($template) ?: $template;
    }
}
