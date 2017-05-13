<?php
	/* a template for displaying the header area */

	$header_class = 'infinite-' . infinite_get_option('general', 'header-side-align', 'left') . '-align';
?>	
<header class="infinite-header-wrap infinite-header-style-side <?php echo esc_attr($header_class); ?>" >
	<?php

		echo infinite_get_logo(array('padding' => false));

		$navigation_class = '';
		if( infinite_get_option('general', 'enable-main-navigation-submenu-indicator', 'disable') == 'enable' ){
			$navigation_class = 'infinite-navigation-submenu-indicator ';
		}
	?>
	<div class="infinite-navigation clearfix infinite-pos-middle <?php echo esc_attr($navigation_class); ?>" >
	<?php
		// print main menu
		if( has_nav_menu('main_menu') ){
			echo '<div class="infinite-main-menu" id="infinite-main-menu" >';
			wp_nav_menu(array(
				'theme_location'=>'main_menu', 
				'container'=> '', 
				'menu_class'=> 'sf-vertical'
			));
			echo '</div>';
		}

		// menu right side
		$enable_search = (infinite_get_option('general', 'enable-main-navigation-search', 'enable') == 'enable')? true: false;
		$enable_cart = (infinite_get_option('general', 'enable-main-navigation-cart', 'enable') == 'enable' && class_exists('WooCommerce'))? true: false;
		if( $enable_search || $enable_cart ){
			echo '<div class="infinite-main-menu-right-wrap clearfix" >';

			// search icon
			if( $enable_search ){
				echo '<div class="infinite-main-menu-search" id="infinite-top-search" >';
				echo '<i class="fa fa-search" ></i>';
				echo '</div>';
				infinite_get_top_search();
			}

			// cart icon
			if( $enable_cart ){
				echo '<div class="infinite-main-menu-cart" id="infinite-main-menu-cart" >';
				echo '<i class="fa fa-shopping-cart" ></i>';
				infinite_get_woocommerce_bar();
				echo '</div>';
			}

			echo '</div>'; // infinite-main-menu-right-wrap
		}
	?>
	</div><!-- infinite-navigation -->
	<?php
		// social network
		$top_bar_social = infinite_get_option('general', 'enable-top-bar-social', 'enable');
		if( $top_bar_social == 'enable' ){
			echo '<div class="infinite-header-social infinite-pos-bottom" >';
			get_template_part('header/header', 'social');
			echo '</div>';
			
			infinite_set_option('general', 'enable-top-bar-social', 'disable');
		}
	?>
</header><!-- header -->