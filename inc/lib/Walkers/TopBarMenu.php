<?php

namespace PublicFunction\Walkers;

class TopBarMenu extends \Walker_Nav_Menu
{
  /**
   * @inheritdoc
   */
  function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
    if ($element->current || $element->current_item_ancestor)
      $element->classes[] = 'active';

    if (array_search('menu-item-has-children', $element->classes, true) !== false)
      $element->classes[] = 'is-dropdown-submenu-parent';

    parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }

  /**
   * @inheritdoc
   */
  function start_lvl( &$output, $depth = 0, $args = array() ) {
    $output .= "\n<ul class=\"is-dropdown-submenu menu\">\n";
  }
}
