<?php

namespace PublicFunction\Setup;

use PublicFunction\Core\RunableAbstract;
use PublicFunction\Template\Template;
use PublicFunction\Template\Wrapper;

class TemplateSupport extends RunableAbstract
{
  /**
   * Returns the correct filename redirected to `/templates/`
   * @param string $main
   * @return string
   */
  public function templateWrapper($main)
  {
    if (!is_string($main) && !(is_object($main) && method_exists($main, '__toString')))
      return $main;

    return ( new Template( new Wrapper($main) ) )->layout();
  }

  /**
   * Returns the array of files to look for in the hierarchy of the current request. Prepends `/templates/` to each file name.
   * @param array $templates
   * @return array
   */
  public function templateHierarchy($templates) {
    return array_map(function($template) { return strpos($template, 'templates/') !== false ? $template : "templates/{$template}"; }, $templates);
  }

  /**
   * Returns the main theme path
   * @return string
   */
  public function templateDirectory()
  {
    return $this->get('theme.path');
  }

  /**
   * Returns the main theme directory name
   * @param $stylesheet
   * @return string
   */
  public function template($stylesheet)
  {
    return dirname($stylesheet);
  }

  /**
   * Returns the contents of our searchform.php in the custom location.
   * @param string $form
   * @return string
   */
  public function searchform($form) {
    $template = locate_template('/templates/partials/searchform.php');
    if ('' != $template) {
      ob_start();
      require($template);
      $form = ob_get_clean();
    }
    return $form;
  }

    /**
     * Returns the contents of our comments.php file.
     * @return string
     */
    public function commentsTemplate() {
        return STYLESHEETPATH . '/templates/comments.php';
    }

  /**
   * @inheritdoc
   */
  public function run()
  {
    $this->loader()->addFilter('template', [$this, 'template'], PHP_INT_MAX);
    $this->loader()->addFilter('template_directory', [$this, 'templateDirectory'], PHP_INT_MAX, 3);
    $this->loader()->addFilter('template_include', [$this, 'templateWrapper'], PHP_INT_MAX);

    $this->loader()->addFilter('index_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('404_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('archive_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('author_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('category_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('tag_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('taxonomy_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('date_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('embed_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('home_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('frontpage_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('page_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('paged_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('search_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('single_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('singular_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);
    $this->loader()->addFilter('attachment_template_hierarchy', [$this, 'templateHierarchy'], PHP_INT_MAX);

    $this->loader()->addFilter('comments_template', [$this, 'commentsTemplate']);
    $this->loader()->addFilter('get_search_form', [$this, 'searchForm'], PHP_INT_MAX);
  }
}
