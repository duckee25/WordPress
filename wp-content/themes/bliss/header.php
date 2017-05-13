<!DOCTYPE html>
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php
	// check if Yoast SEO plugin is active
	if ( of_get_option('seo_plugin') ){
		echo '<title>';
		wp_title('');
		echo '</title>';
	}else{

		global $page, $paged;

		// add title tag
		echo '<title>';
		bloginfo('name');
		wp_title(' - ', true, 'left');
		echo '</title>';

		if (is_single() || is_page() ){ 
			if ( have_posts() ) {
				while ( have_posts() ){ 
					the_post();
					echo '<meta name="description" content="';
					echo strip_tags( get_the_excerpt() );
					echo '" />';
				} 
			}
		}elseif(is_home()){ 
				echo '<meta name="description" content="';
				bloginfo('description');
				echo '" />';
		}
	}

/* Apple touch icon */
echo of_get_option('apple_touch_icon') ? '<link rel="apple-touch-icon" href="' . of_get_option('apple_touch_icon') . '" />' : ''; ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php

//render google tracking code if present
$google_analytics = of_get_option('google_analytics', false);
if($google_analytics){
	echo (strpos($google_analytics, '<script') === false) ? '<script>'.of_get_option('google_analytics').'</script>' : of_get_option('google_analytics');
}

wp_head(); 
global $css_options;
// var_dump($css_options);
?>
</head>
<body <?php body_class(); ?>>
<div class="bl_search_overlay"></div>
	<script type="text/javascript">
	<?php if(!of_get_option('disable_fixed_header')){ ?>

		var y;
		y = jQuery(window).scrollTop();
		if(jQuery(window).width() > 979){
			// Shrink menu on scroll
			var didScroll = false;
			jQuery(window).scroll(function() {
			    didScroll = true;
			});
			setInterval(function() {
			    if ( didScroll ) {
			        didScroll = false;
			        y = jQuery(window).scrollTop();
			        if(y > jQuery('#masthead .top-banner').height() ){
			        	jQuery('#masthead .bluth-navigation').addClass('fixed');
			        	jQuery('#main').css('padding-top', 71);
			        }else{
			        	jQuery('#masthead .bluth-navigation').removeClass('fixed');
			        	jQuery('#main').css('padding-top', '');
			        }
			        if(y > ( jQuery('#masthead .top-banner').height()+100 ) ){
			        	jQuery('#masthead .bluth-navigation').addClass('shrunk');
			        }else{
			        	jQuery('#masthead .bluth-navigation').removeClass('shrunk');
			        }

					changeHeader();
			    }
			}, 50);
		}
		
	<?php } ?>
	jQuery(function() {
		// if the page is in mobile mode, then don't make the header transparent!
		if( jQuery(window).width() > 979 )
			changeHeader();
		else{
			jQuery('#masthead .bluth-navigation .navbar .nav > li > a, #masthead .searchform a').animate({ color: '<?php echo $css_options["header_font_color"]; ?>'}, 10);
			jQuery('#masthead .bluth-navigation').animate({ backgroundColor: '<?php echo $css_options["header_color"]; ?>'}, 100);
		}

		// listen to resize!
	    var didresize = false;
	    jQuery(window).resize( function() {

			// if the user refreshes the page while the sticky header is active, then add some padding for a friend.
			// if( y > jQuery('#masthead').height() )
				// jQuery('#main').css('padding-top', (/*jQuery('#masthead').height()+*/25));
			// else
				// jQuery('#main').css('padding-top', (/*jQuery('#masthead').height()+*/25));
	        // didresize = true;
	    });
	    setInterval(function() {
	        if ( didresize ) {
	            didresize = false;
	        }
	    }, 3000);



		<?php if( of_get_option('menu_hover') ){ ?>

			jQuery('.navbar .nav li').mouseover(function(){
				jQuery( this ).addClass('open');
			});
			jQuery('.navbar .nav li').mouseout(function(){
				jQuery( this ).removeClass('open');
			});
		<?php } ?>
		resetNavLine(250);

		jQuery('#masthead .nav li').mouseover(function(){
			jQuery('.nav-line').stop();
			jQuery('.nav-line').animate({
				left : jQuery(this).offset().left-jQuery('.bluth-navigation .container .navbar').offset().left,
				width: jQuery(this).width()
			}, 250);
		});
		jQuery('#masthead .nav li').mouseout(function(){
			resetNavLine(250);
		});
	});

	// change the color of the header if it's transparent
	function changeHeader(){
		var y;
		y = jQuery(window).scrollTop();
		
		jQuery('#masthead .bluth-navigation').stop();
		<?php if( of_get_option('header_background') == 'image' ){ ?>
			if(y > 30){
				jQuery('#masthead .bluth-navigation .navbar .nav > li > a, #masthead .searchform a').animate({ color: '<?php echo $css_options["header_font_color"]; ?>'}, 10);
				jQuery('#masthead .bluth-navigation').animate({ backgroundColor: '<?php echo $css_options["header_color"]; ?>'}, 100);
			}else if(y < 500){ 
				jQuery('#masthead .bluth-navigation .navbar .nav > li > a, #masthead .searchform a').animate({ color: '#FFFFFF' }, 10); 
				jQuery('#masthead .bluth-navigation').animate({ backgroundColor: 'rgba(0,0,0,0.3)' }, 100);  
			}
		<?php } ?>
	}
	function resetNavLine(time){
		// didScroll = true;
		jQuery('.nav-line').stop();
		if(jQuery('.nav').children('li').hasClass('current-menu-item')){
			jQuery('.nav-line').animate({
				left : jQuery('.current-menu-item').offset().left-jQuery('.bluth-navigation .container .navbar').offset().left,
				width: jQuery('.current-menu-item').width()
			}, time);
		}else if(jQuery('.nav').children('li').hasClass('current-menu-ancestor')){
			jQuery('.nav-line').animate({
					left : jQuery('.current-menu-ancestor').offset().left-jQuery('.bluth-navigation .container .navbar').offset().left,
					width: jQuery('.current-menu-ancestor').width()
			}, time);
		}else{
			jQuery('.nav-line').animate({
				width : 0
			});
		}
	}
	</script>
<?php 

	// Facebook Javascript SDK
	if(of_get_option('facebook_app_id')){ ?>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=<?php echo of_get_option('facebook_app_id'); ?>";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<?php }	


	// background image or pattern
	switch (of_get_option('background_mode')) {
		case 'image':
			if(of_get_option('background_image')){

				echo '<div class="bl_background">'.(of_get_option('show_stripe') ? '<div id="stripe"></div>' : ''). '<img src="'.of_get_option('background_image').'"></div>';
			}
		break;
		case 'pattern':
			if( of_get_option('background_pattern_custom') ){

				echo '<div style="background-image: url(\''.of_get_option('background_pattern_custom').'\')" id="background_pattern"></div>';
			
			}elseif (of_get_option('background_pattern')) {

				echo '<div style="background-image: url(\''.get_template_directory_uri() . '/assets/img/pattern/large/'.of_get_option('background_pattern').'\')" id="background_pattern"></div>';
			}
		break;
	}
?>
<?php
	// Advert above header
	$ad_header_mode = of_get_option('ad_header_mode', 'none');

	if($ad_header_mode != 'none'){
		echo '<div class="above_header">';
			if($ad_header_mode == 'image'){
				echo '<a href="'.of_get_option('ad_header_image_link').'" target="_blank"><img src="'.of_get_option('ad_header_image').'"></a>';
			}elseif($ad_header_mode == 'html'){
				echo apply_filters('shortcode_filter',do_shortcode(of_get_option('ad_header_code')));
			}elseif($ad_header_mode == 'google_ads'){
				blu_get_google_ads();
			}
		echo '</div>';
	}
?>

<div id="page" class="site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" role="banner" class="<?php echo !of_get_option( 'header_type' ) ? 'header_normal' : of_get_option( 'header_type' ); ?>">
		<div class="image-overflow">
			<div class="header-background-image"><?php
				// if the background image is selected as the background then display it
				if( of_get_option( 'header_background' ) == 'image' ){
					echo '<img src="' . of_get_option( 'header_background_image' ) . '">';
				}?>
			</div>
		</div>

		<div class="row-fluid top-banner">
			<div class="container">
				<div class="banner-overlay"></div>
				<?php 
				$logo = of_get_option('logo', '' );
				if ( !empty( $logo ) ) { ?>
					<a class="brand brand-image" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo $logo; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><h1><?php if(!of_get_option('disable_description')){ ?><small><?php bloginfo( 'description' ); ?></small><?php } ?></h1></a>
				<?php }else{ ?>
					<a class="brand brand-text" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><h1><?php bloginfo( 'name' ); ?><?php if(!of_get_option('disable_description')){ ?><small><?php bloginfo( 'description' ); ?></small><?php } ?></h1></a>
				<?php } 
				if(of_get_option('disable_description')){ $top_banner_fix = 'style="top:15px;"'; }else{ $top_banner_fix = ''; }
				?>
				<div class="top-banner-social pull-right" <?php echo $top_banner_fix; ?>><?php
					echo (!of_get_option('social_facebook')) 	? '' : '<a target="_blank" href="' . of_get_option('social_facebook') . '"><i class="icon-facebook-1"></i></a>';
					echo (!of_get_option('social_twitter')) 	? '' : '<a target="_blank" href="' . of_get_option('social_twitter') . '"><i class="icon-twitter-1"></i></a>';
					echo (!of_get_option('social_google')) 		? '' : '<a target="_blank" href="' . of_get_option('social_google') . '"><i class="icon-gplus-1"></i></a>';
					echo (!of_get_option('social_linkedin')) 	? '' : '<a target="_blank" href="' . of_get_option('social_linkedin') . '"><i class="icon-linkedin-1"></i></a>';
					echo (!of_get_option('social_youtube')) 	? '' : '<a target="_blank" href="' . of_get_option('social_youtube') . '"><i class="icon-youtube"></i></a>';
					echo (!of_get_option('social_rss')) 		? '' : '<a target="_blank" href="' . of_get_option('social_rss') . '"><i class="icon-rss-1"></i></a>';
					echo (!of_get_option('social_flickr')) 		? '' : '<a target="_blank" href="' . of_get_option('social_flickr') . '"><i class="icon-flickr-1"></i></a>';
					echo (!of_get_option('social_vimeo')) 		? '' : '<a target="_blank" href="' . of_get_option('social_vimeo') . '"><i class="icon-vimeo-1"></i></a>';
					echo (!of_get_option('social_pinterest')) 	? '' : '<a target="_blank" href="' . of_get_option('social_pinterest') . '"><i class="icon-pinterest-1"></i></a>';
					echo (!of_get_option('social_dribbble')) 	? '' : '<a target="_blank" href="' . of_get_option('social_dribbble') . '"><i class="icon-dribbble-1"></i></a>';
					echo (!of_get_option('social_tumblr')) 		? '' : '<a target="_blank" href="' . of_get_option('social_tumblr') . '"><i class="icon-tumblr-1"></i></a>';
					echo (!of_get_option('social_instagram')) 	? '' : '<a target="_blank" href="' . of_get_option('social_instagram') . '"><i class="icon-instagram-1"></i></a>'; 
					echo (!of_get_option('social_viadeo')) 		? '' : '<a target="_blank" href="' . of_get_option('social_viadeo') . '"><i class="icon-viadeo"></i></a>'; 
					echo (!of_get_option('social_xing'))      	? '' : '<a target="_blank" href="' . of_get_option('social_xing') . '"><i class="icon-xing"></i></a>'; ?>
				</div>
			</div>
		</div>
		<div class="row-fluid bluth-navigation">
			<div class="container">
				<div class="mini-logo">
				<?php	
				$minilogo = of_get_option('minilogo', '' );
				if ( !empty( $minilogo ) ) { ?>
					<a class="mini mini-image" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo $minilogo; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
				<?php }else{ ?>
					<a class="mini mini-text" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><h1><?php bloginfo( 'name' ); ?></h1></a>
				<?php } ?>
				</div>
				<div class="navbar navbar-inverse">
				  <div class="navbar-inner">
				    <?php if(of_get_option('show_search_header')){ ?>
						<div class="visible-tablet visible-phone bl_search">
							<?php echo get_search_form(); ?>
						</div>
					<?php } ?>
				    <!-- Responsive Navbar Part 1: Button for triggering responsive navbar (not covered in tutorial). Include responsive CSS to utilize. -->
				    <!-- <label for="mobile-menu"><button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button"><i class="icon-menu-1"></i></button></label> -->
					<?php
						if ( has_nav_menu( 'primary' ) ) {
							wp_nav_menu( array( 
								'container' => 'div',
								'container_class' => 'nav-collapse collapse',
								'theme_location' => 'primary',
								'menu_class' => 'nav visible-desktop',
								'walker' => new Bootstrap_Walker(),									
								) );
						}
						if ( has_nav_menu( 'primary' ) ) {
							wp_nav_menu( array(
								'walker' => new Walker_Nav_Menu_Dropdown(),
								'theme_location' => 'primary',
								'items_wrap' => '<div id="mobile-menu" class="pull-right visible-tablet visible-phone mobile-menu"><form style="margin-top: 15px; margin-bottom: 5px;"><select style="max-width:150px;" onchange="if (this.value) window.location.href=this.value"><option>-- Menu --</option>%3$s</select></form></div>',
							) );	
						}
					?>
				  </div><!-- /.navbar-inner -->
					<div class="nav-line"></div>
				</div>
				<?php if(of_get_option('show_search_header')){ ?>
				<div class="bl_search visible-desktop nav-collapse collapse">
					<?php echo get_search_form(); ?>
				</div>
				<?php } ?>

			</div>
		</div>

<!-- 	This file is part of a WordPress theme for sale at ThemeForest.net.
		See: http://themeforest.net/item/bliss-personal-minimalist-wordpress-blog-theme/5423780
		Copyright 2013 Bluthemes 	-->

	</header><!-- #masthead .site-header -->
	<div id="main" class="container">