<?php

/* Load admin scripts and styles */
add_action( 'admin_enqueue_scripts', 'typology_load_admin_scripts' );


/**
 * Load scripts and styles in admin
 *
 * It just wrapps two other separate functions for loading css and js files in admin
 *
 * @since  1.0
 */

function typology_load_admin_scripts() {
	typology_load_admin_css();
	typology_load_admin_js();
}


/**
 * Load admin css files
 *
 * @since  1.0
 */

function typology_load_admin_css() {
	
	global $pagenow, $typenow;

	//Load small admin style tweaks
	wp_enqueue_style( 'typology-global', get_template_directory_uri() . '/assets/css/admin/global.css', false, TYPOLOGY_THEME_VERSION, 'screen, print' );
}


/**
 * Load admin js files
 *
 * @since  1.0
 */

function typology_load_admin_js() {

	global $pagenow, $typenow;

	wp_enqueue_script( 'typology-global', get_template_directory_uri().'/assets/js/admin/global.js', array( 'jquery' ), TYPOLOGY_THEME_VERSION );

	if( $pagenow == 'widgets.php' ){
		wp_enqueue_script( 'typology-widgets', get_template_directory_uri().'/assets/js/admin/widgets.js', array( 'jquery', 'jquery-ui-sortable'), TYPOLOGY_THEME_VERSION );
	}

}

?>