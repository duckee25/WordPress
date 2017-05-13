<?php
class Bootstrap_Walker extends Walker_Nav_Menu
{           
    function start_lvl( &$output, $depth = 0, $args = array() )
    {
        $tabs = str_repeat("\t", $depth);
        // If we are about to start the first submenu, we need to give it a dropdown-menu class
        if ($depth == 0 || $depth == 1) { //really, level-1 or level-2, because $depth is misleading here (see note above)
            $output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n";
        } else {
            $output .= "\n{$tabs}<ul>\n";
        }
    }      
    function end_lvl( &$output, $depth = 0, $args = array() ) 
    {
        if ($depth == 0) { // This is actually the end of the level-1 submenu ($depth is misleading here too!)
            
            // we don't have anything special for Bootstrap, so we'll just leave an HTML comment for now
            $output .= '<!--.dropdown-->';
        }
        $tabs = str_repeat("\t", $depth);
        $output .= "\n{$tabs}</ul>\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) 
    {    
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        /* If this item has a dropdown menu, add the 'dropdown' class for Bootstrap */
        if ($item->hasChildren) {
            $classes[] = 'dropdown';
            // level-1 menus also need the 'dropdown-submenu' class
            if($depth == 1) {
                $classes[] = 'dropdown-submenu';
            }
        }

        /* This is the stock Wordpress code that builds the <li> with all of its attributes */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';
        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';            
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $item_output = $args->before;
                    
        /* If this item has a dropdown menu, make clicking on this link toggle it */
        if ($item->hasChildren && $depth == 0 && !of_get_option('menu_hover')) {
            $item_output .= '<a'. $attributes .' class="dropdown-toggle" data-toggle="dropdown">';
        } else {
            $item_output .= '<a'. $attributes .'>';
        }
        
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

        /* Output the actual caret for the user to click on to toggle the menu */            
        if ($item->hasChildren && $depth == 0) {
            $item_output .= '<i class="icon-angle-down"></i></a>';
        } else {
            $item_output .= '</a>';
        }

        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id );
        return;
    }
    
    /* Close the <li>
     * Note: the <a> is already closed
     * Note 2: $depth is "correct" at this level
     */        
    function end_el ( &$output, $item, $depth = 0, $args = array() )
    {
        $output .= '</li>';
        return;
    }
    
    /* Add a 'hasChildren' property to the item
     * Code from: http://wordpress.org/support/topic/how-do-i-know-if-a-menu-item-has-children-or-is-a-leaf#post-3139633 
     */
    function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        // check whether this item has children, and set $item->hasChildren accordingly
        $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);

        // continue with normal behavior
        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }        
}


/* Responsive menu */
class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = array() ){
        $indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
    }
     
    function end_lvl(&$output, $depth = 0, $args = array() ){
        $indent = str_repeat("\t", $depth); // don't output children closing tag
    }
     
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $url = '#' !== $item->url ? $item->url : '';
        if($depth == 1)
            $before = " - ";
        else if($depth > 1)
            $before = " -- ";
        else
            $before = '';
        $output .= '<option value="' . $url . '">' . $before . $item->title;
    }   
     
    function end_el(&$output, $item, $depth = 0, $args = array() ){
        $output .= "</option>\n"; // replace closing </li> with the option tag
    }
}