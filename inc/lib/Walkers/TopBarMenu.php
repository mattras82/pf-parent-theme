<?php

namespace PublicFunction\Walkers;

class TopBarMenu extends \Walker_Nav_Menu
{private $menu_type;
  private $remove_ids;

  public function __construct($menuType = 'dropdown', $removeIDs = false)
  {
    $this->menu_type = $menuType;
    $this->remove_ids = $removeIDs;
  }

  /**
   * @inheritdoc
   */
  function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
    if ($element->current || $element->current_item_ancestor)
      $element->classes[] = 'active';

    if (array_search('menu-item-has-children', $element->classes, true) !== false)
      $element->classes[] = "is-$this->menu_type-submenu-parent";

    if ($this->remove_ids)
      $element->ID = '';

    parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }

  /**
   * @inheritdoc
   */
  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $output .= "\n<ul class=\"is-$this->menu_type-submenu nested menu\" data-submenu>\n";
  }
}
