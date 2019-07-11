<?php

namespace PublicFunction\Template;

class Template
{
    /**
     * @var array|Template[]
     */
    public static $instances = [];

    /**
     * @var Wrapper
     */
    protected $wrapper;

    public function __construct(Wrapper $wrapper)
    {
        $this->wrapper = $wrapper;
        self::$instances[$wrapper->slug()] = $this;
    }

    /**
     * Returns the path of the main template file
     * @return string
     */
    public function layout()
    {
        return $this->wrapper->wrap();
    }

    /**
     * Includes the main template file
     */
    public function main()
    {
        include $this->wrapper->unwrap();
    }

    /**
     * Returns an instance by slug
     * @param $slug
     * @return Template|mixed
     */
    public static function getInstance($slug)
    {
        if(empty($slug))
            throw new \RuntimeException('Cannot look for a template with the an empty `$slug` parameter');

        if(!isset(self::$instances[$slug]))
            throw new \RuntimeException('That template instance does not exist');

        return self::$instances[$slug];
    }
}