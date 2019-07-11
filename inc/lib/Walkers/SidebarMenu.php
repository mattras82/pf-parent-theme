<?php

namespace PublicFunction\Walkers;

class SidebarMenu extends \Walker_Nav_Menu
{
    /**
     * @inheritdoc
     */
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $t = "\t";
        $n = "\n";

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        }

        $indent = str_repeat( $t, $depth );
        $output .= "{$n}{$indent}<ul class=\"nested vertical menu\">";
    }
}