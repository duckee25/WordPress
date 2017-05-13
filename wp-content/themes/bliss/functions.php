<?php
	global $bluthemes;
	$bluthemes = 'on';	

	// Define the version so we can easily replace it throughout the theme
	define( 'BLISS_VERSION', 3.01 );
	define( 'BLUTHEMES', true );	

	/*  Set the content width based on the theme's design and stylesheet  */
	if ( ! isset( $content_width ) ){
		$content_width = 740; /* pixels */
	}

	/*  Add Rss feed support to Head section  */
	add_theme_support( 'automatic-feed-links' );

	/*  Register main menu for Wordpress use  */
	if(!function_exists('blu_custom_theme_setup')){
		function blu_custom_theme_setup() {

			add_theme_support( 'infinite-scroll', array(        
			    'container' => '#main_columns',        
			    'footer' => false,      
			) );

			// load language
			load_theme_textdomain( 'bluth', get_template_directory() . '/inc/lang' );
			
			// load menu
			register_nav_menus( 
				array(
					'primary'	=>	'Primary Menu', // Register the Primary menu
					// Copy and paste the line above right here if you want to make another menu, 
					// just change the 'primary' to another name
				)
			);
		}
	}
	add_action( 'after_setup_theme', 'blu_custom_theme_setup' );

	/*  Add support for the multiple Post Formats  */
	add_theme_support( 'post-formats', array('gallery', 'image', 'link', 'quote', 'audio', 'video', 'status')); 
	
	/* Bluthcodes */
	// only load if there isn't a plugin already loaded
	if(!function_exists('bluth_pullquote')){
		include_once 'assets/plugins/bluthcodes/codes.php'; 
	}

	/*  Widgets  */
	include_once('inc/widgets/widgets.php');   // Register widget
	include_once "inc/widgets/bl_google_ads.php"; // Tabs: (Recent posts, Recent comments, Tags)
	include_once "inc/widgets/bl_tabs.php"; // Tabs: (Recent posts, Recent comments, Tags)
	include_once "inc/widgets/bl_socialbox.php"; // Social network links
	include_once "inc/widgets/bl_tweets.php"; // Display recent tweets
	include_once "inc/widgets/bl_googlebadge.php"; // Display recent tweets
	include_once "inc/widgets/bl_instagram.php"; // Display recent instagram images
	include_once "inc/widgets/bl_newsletter.php"; // Display recent instagram images
	include_once "inc/widgets/bl_likebox.php"; // Display a facebook likebox
	include_once "inc/widgets/bl_flickr2.php"; // Display a recent flickr images
	include_once "inc/widgets/bl_html.php"; // Display HTML
	include_once "inc/widgets/bl_author.php"; // Display Author Badge
	include_once "inc/widgets/bl_featured_post.php"; // Display Featured Post
	include_once "inc/widgets/bl_imagebox.php"; // Display Image Box
	include_once "inc/widgets/bl_category.php"; // Display Categories

	// include_once('inc/shortcodes.php'); // Load Shortcodes
	include_once('inc/theme-options.php'); // Load Theme Options Panel
	include_once('inc/custom-css.php'); // Load Theme Options Panel
	include_once('inc/meta-box.php'); // Load Meta Boxes
	
	/* Include the TGM_Plugin_Activation class  */
	include_once('inc/class-tgm-plugin-activation.php');
	add_action('tgmpa_register', 'bluth_register_required_plugins');

	/* Bootstrap type menu  */
	include_once('inc/bootstrap-walker.php');

/*	function put_author_beneath($content){
		$html = '<small style="display: block; font-size: 12px; margin: 5px 0;"">';
		$html .= 'By ' . get_the_author();
		$html .= '</small>';
		return $content . $html;
	}
	add_action('the_title', 'put_author_beneath');*/

	/*  Register required plugins  */
	if(!function_exists('bluth_register_required_plugins')){
		function bluth_register_required_plugins() {
			$plugins = array(
				array(
					'name'     				=> 'CF Post Formats', // The plugin name
					'slug'     				=> 'wp-post-formats', // The plugin slug (typically the folder name)
					'source'   				=> 'http://bluthemes.com/themes/assets/plugins/cf-post-formats.zip', //get_template_directory_uri() . '/inc/plugins/cf-post-formats.zip', // The plugin source
					'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
					'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
					'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
					'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
					// 'external_url' 			=> 'https://github.com/crowdfavorite/wp-post-formats/archive/master.zip', // If set, overrides default API URL and points to an external URL
				)
			);
		
		
			/**
			 * Array of configuration settings. Amend each line as needed.
			 * If you want the default strings to be available under your own theme domain,
			 * leave the strings uncommented.
			 * Some of the strings are added into a sprintf, so see the comments at the
			 * end of each line for what each argument will be.
			 */
			$config = array(
				'domain'       		=> 'bluth',         	// Text domain - likely want to be the same as your theme.
				'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
				'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
				'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
				'menu'         		=> 'install-required-plugins', 	// Menu slug
				'has_notices'      	=> false,                       	// Show admin notices or not
				'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
				'message' 			=> '',							// Message to output right before the plugins table
				'strings'      		=> array(
					'page_title'                       			=> __( 'Install Required Plugins', 'bluth' ),
					'menu_title'                       			=> __( 'Install Plugins', 'bluth' ),
					'installing'                       			=> __( 'Installing Plugin: %s', 'bluth' ), // %1$s = plugin name
					'oops'                             			=> __( 'Something went wrong with the plugin API.', 'bluth' ),
					'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
					'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
					'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
					'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
					'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
					'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
					'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
					'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
					'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
					'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
					'return'                           			=> __( 'Return to Required Plugins Installer', 'bluth' ),
					'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'bluth' ),
					'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'bluth' ), // %1$s = dashboard link
					'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
				)
			);
			tgmpa($plugins, $config);
		}
	}

	/* Enqueue Styles and Scripts  */
	if(!function_exists('bliss_assets')){
		function bliss_assets()  { 
			$protocol 			= is_ssl() ? 'https' : 'http';
			$disable_responsive = of_get_option('disable_responsive', false);

			$enable_rtl 		= of_get_option('enable_rtl', false);

			// add theme css
			wp_enqueue_style( 'bluth-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );
			wp_enqueue_style( 'bluth-style', get_stylesheet_uri(), array('bluth-bootstrap') );
			// if disable responsive
			if(!$disable_responsive){
				wp_enqueue_style( 'bluth-responsive', get_template_directory_uri() . '/assets/css/style-responsive.css' );
			}
			// if RTL enabled
			if($enable_rtl){
				wp_enqueue_style( 'bluth-rtl', get_template_directory_uri() . '/assets/css/style-rtl.css' );
			}
			// Add SEO Support
			add_action( 'wp_head', 'blu_add_open_graph_tags',99); 

			wp_enqueue_style( 'bluth-fontello', get_template_directory_uri() . '/assets/css/fontello.css' );
			wp_enqueue_style( 'bluth-nivo', get_template_directory_uri() . '/assets/css/nivo-slider.css' );
			wp_enqueue_style( 'bluth-magnific', get_template_directory_uri() . '/assets/css/magnific-popup.css' );
			wp_enqueue_style( 'bluth-snippet', get_template_directory_uri() . '/assets/css/jquery.snippet.min.css' );
				
			// add theme scripts
			wp_enqueue_script( 'bluth-jquery-ui', $protocol.'://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', array('jquery'), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-snippet', get_template_directory_uri() . '/assets/js/jquery.snippet.min.js', array('jquery'), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-nivo', get_template_directory_uri() . '/assets/js/jquery.nivo.slider.pack.js', array(), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-timeago', get_template_directory_uri() . '/assets/js/jquery.timeago.js', array('jquery'), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-magnific', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.js', array('jquery'), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-theme', get_template_directory_uri() . '/assets/js/theme.min.js', array('jquery'), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), BLISS_VERSION, true );
			wp_enqueue_script( 'bluth-retinajs', get_template_directory_uri() . '/assets/js/retina.js', array('jquery'), BLISS_VERSION, true );

			// Localize Script
		    wp_localize_script( 'bluth-theme', 'blu', array( 

		    	// Variable
		    	'site_url' => get_site_url(),
		    	'ajaxurl' => admin_url( 'admin-ajax.php' ),

		    	// Locale
		    	'locale' => array(
			    	'no_search_results' => __( 'No results match your search.', 'bluth' ),
			    	'searching' => __( 'Searching...', 'bluth'),
			    	'search_results' => __( 'Search Results', 'bluth'),
			    	'see_all' => __( 'see all', 'bluth'),
			    	'loading' => __( 'Loading...', 'bluth'),
			    	'no_more_posts' => __( 'No more posts', 'bluth'),
			    	'see_more_articles' => __( 'See more articles', 'bluth'),
			    	'no_email_provided' => __( 'No email provided', 'bluth'),
			    	'thank_you_for_subscribing' => __( 'Thank you for subscribing!', 'bluth'),
		    	)
		    ));

			$fonts = array();
			$fonts['heading_font'] 	= of_get_option('heading_font', false);
			$fonts['text_font'] 	= of_get_option('text_font', false);
			$fonts['menu_font'] 	= of_get_option('menu_font', false);
			$fonts['brand_font'] 	= of_get_option('brand_font', false);

			// defaults
			$heading_font 	= 'Crete+Round:400,400italic';
			$text_font 		= 'Lato:400,700,400italic';

			// empty font array
			$font_names 	= array();
			$font_subset 	= array();
			$subset_array 	= array();

			foreach ($fonts as $key => $value) {
				if($value){
					$selected_font = explode('&subset=', $value);
					$font_names[] = str_replace(' ', '+', $selected_font[0]);
					if(count($selected_font) > 1){
						$font_subset = explode(',', $selected_font[1]);
						array_fill_keys($font_subset, $font_subset);
						$subset_array = array_merge($subset_array, $font_subset);
					}
				}
			}
			$subset_array = array_unique($subset_array);

			wp_enqueue_style( 'bluth-googlefonts', $protocol.'://fonts.googleapis.com/css?family='.(!empty($font_names) ? implode('|', $font_names) : $text_font.'|'.$heading_font) . (!empty($subset_array) ? '&subset='.implode(',', $subset_array) : '')  );	

		    if ( is_singular() && get_option( 'thread_comments' ) )
		        wp_enqueue_script( 'comment-reply' );			
		}
	}
	add_action( 'wp_enqueue_scripts', 'bliss_assets' ); // Register this fxn and allow Wordpress to call it automatcally in the header


	/* 
	 * Outputs the selected option panel styles inline into the <head>
	 */
	if(!function_exists('options_typography_styles')){ 
		function options_typography_styles() {

			$output = '';
			$heading_font 		= of_get_option('heading_font', false);
			$text_font 			= of_get_option('text_font', false);
			$menu_font 			= of_get_option('menu_font', false);
			$brand_font 		= of_get_option('brand_font', false);

			if($heading_font){
				$selected_font = explode(':', $heading_font);
				$output .= 'h1,h2,h3,h4,h5{font-family: "'.str_replace('+', ' ', $selected_font[0]).'",serif;} .widget_calendar table > caption{font-family: "'.str_replace('+', ' ', $selected_font[0]).'",serif;} ';
			}

			if($text_font){
				$selected_font = explode(':', $text_font);
				$output .= 'body{font-family: "'.str_replace('+', ' ', $selected_font[0]).'",Helvetica,sans-serif;} ';
			}

			if($menu_font){
				$selected_font = explode(':', $menu_font);
				$output .= '.navbar .nav > li > a{font-family: "'.str_replace('+', ' ', $selected_font[0]).'",Helvetica,sans-serif;} ';
			}

			if($brand_font){
				$selected_font = explode(':', $brand_font);
				$output .= '.brand-text h1, .mini-text h1{font-family: "'.str_replace('+', ' ', $selected_font[0]).'",Helvetica,sans-serif;} ';
			}

		     if ( $output != '' ) {
				$output = "\n<style>\n" . $output . "</style>\n";
				echo $output;
		     }
		}
	}
	add_action('wp_head', 'options_typography_styles');

	#
	#	TWITTER WIDGET
	#
	
	if(!function_exists('blu_get_twitter_feed')){
		function blu_get_twitter_feed($user_id) {
			// Cache the results if they haven't been cached
			delete_transient('blu_get_twitter_feed_'.$user_id);
			if(($cache = get_transient('blu_get_twitter_feed_'.$user_id)) === false){
				if(!of_get_option('twitter_api_key')){
					$return_array['twitter'] = 'Configure your Twitter API key in Theme Options';
				}else{
					if(!class_exists('TwitterApiClient')){
						require_once('inc/twitter-api.php');
					}
					$Client = new TwitterApiClient;
					$Client->set_oauth (of_get_option('twitter_api_key'), of_get_option('twitter_api_secret'), of_get_option('twitter_access_token'), of_get_option('twitter_access_token_secret'));
					try {
	                    $args = array('screen_name' => $user_id);
	                    $bl_data['user'] = @$Client->call( 'users/show', $args, 'GET' );
	                    $bl_data['tweets'] = @$Client->call( 'statuses/user_timeline', array('screen_name' => $user_id, 'count' => 4) , 'GET' );
	                    
	                    set_transient( 'blu_get_twitter_feed_'.$user_id, $bl_data, 3600);
	                    return $bl_data;
	                }catch( TwitterApiException $Ex ){ 
	                	return $Ex; 
	                }
				}
			}else{
				return $cache;
			}
		}
	}

	// Google ads
	function blu_get_google_ads(){ 
		global $google_ads_count;
		$google_ads_count++;
		?>
		<article style="text-align: center;">
			<div id="google-ads-<?php echo $google_ads_count; ?>" class="google-ads"></div>
		       
			<script type="text/javascript">

			    /* Calculate the width of available ad space */
			    ad = jQuery('#google-ads-<?php echo $google_ads_count; ?>')[0];
			     
			    if (ad.getBoundingClientRect().width) {
			   	 	adWidth = ad.getBoundingClientRect().width; // for modern browsers
			    } else {
			    	adWidth = ad.offsetWidth; // for old IE
			    }
			     
			    /* Replace ca-pub-XXX with your AdSense Publisher ID */
			    google_ad_client = "<?php echo of_get_option('google_publisher_id', '0'); ?>";
			     
			    /* Replace 1234567890 with the AdSense Ad Slot ID */
			    google_ad_slot = "<?php echo of_get_option('google_ad_unit_id', '0'); ?>";
			    /* Do not change anything after this line */
			    if ( adWidth >= 728 )
			    	google_ad_size = ["728", "90"]; /* Leaderboard 728x90 */
			    else if ( adWidth >= 468 )
			    	google_ad_size = ["468", "60"]; /* Banner (468 x 60) */
			    else if ( adWidth >= 336 )
			    	google_ad_size = ["336", "280"]; /* Large Rectangle (336 x 280) */
			    else if ( adWidth >= 300 )
			    	google_ad_size = ["300", "250"]; /* Medium Rectangle (300 x 250) */
			    else if ( adWidth >= 250 )
			    	google_ad_size = ["250", "250"]; /* Square (250 x 250) */
			    else if ( adWidth >= 200 )
			    	google_ad_size = ["200", "200"]; /* Small Square (200 x 200) */
			    else if ( adWidth >= 180 )
			    	google_ad_size = ["180", "150"]; /* Small Rectangle (180 x 150) */
			    else
			    	google_ad_size = ["125", "125"]; /* Button (125 x 125) */
			     

			    document.write (
			    '<ins class="adsbygoogle" style="display:inline-block;width:'
			    + google_ad_size[0] + 'px;height:'
			    + google_ad_size[1] + 'px" data-ad-client="'
			    + google_ad_client + '" data-ad-slot="'
			    + google_ad_slot + '"></ins>'
			    );
			    (adsbygoogle = window.adsbygoogle || []).push({});

			</script>
			 
			<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js">
			</script>
		</article><?php
	}


	/*  Attach a class to linked images' parent anchors  */
	if(!function_exists('give_linked_images_class')){
		function give_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
		  $classes = 'lightbox'; // separated by spaces, e.g. 'img image-link'

		  // check if there are already classes assigned to the anchor
		  if ( preg_match('/<a.*? class=".*?">/', $html) ) {
		    $html = preg_replace('/(<a.*? class=".*?)(".*?>)/', '$1 ' . $classes . '$2', $html);
		  } else {
		    $html = preg_replace('/(<a.*?)>/', '$1 class="' . $classes . '" >', $html);
		  }
		  return $html;
		}
	}
	add_filter('image_send_to_editor','give_linked_images_class',10,8);


	/*  Custom Pagination ( thanks to kriesi.at )  */
	if(!function_exists('kriesi_pagination')){ 
		function kriesi_pagination($pages = '', $range = 2){  
		     $showitems = ($range * 2)+1;  

		     global $paged;
		     if(empty($paged)) $paged = 1;

		     if($pages == '')
		     {
		         global $wp_query;
		         $pages = $wp_query->max_num_pages;
		         if(!$pages)
		         {
		             $pages = 1;
		         }
		     }   

		     if(1 != $pages)
		     {
		         echo "<div class='pagination'>";
				echo get_previous_posts_link( '<i class="icon-left-open-1"></i> '.__('Previous Page', 'bluth') );
		         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a class='box' href='".get_pagenum_link(1)."'>&laquo;</a>";
		         if($paged > 1 && $showitems < $pages) echo "<a class='box' href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

		         for ($i=1; $i <= $pages; $i++)
		         {
		             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
		             {
		                 echo ($paged == $i)? "<span class='current box'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive box' >".$i."</a>";
		             }
		         }

		         if ($paged < $pages && $showitems < $pages) echo "<a class='box' href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
		         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a class='box' href='".get_pagenum_link($pages)."'>&raquo;</a>";
				echo get_next_posts_link( __('Next Page', 'bluth').' <i class="icon-right-open-1"></i>' );
		        echo "</div>\n";
		     }
		}
	}

  	// Add open graph meta tags  
	if(!function_exists('blu_add_open_graph_tags')){ 
		function blu_add_open_graph_tags() {
			// only run this function if the user is not using an seo plugin
			if ( !of_get_option('seo_plugin') ){

				// add title tag
				echo '<title>';
				bloginfo('name');
				wp_title(' - ', true, 'left');
				echo '</title>';

				// og:site_name
				echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />';

				// og:title
				echo '<meta property="og:title" content="';
				bloginfo('name');
				wp_title(' - ', true, 'left');
				echo '" />';

				if(of_get_option('facebook_app_id')){
					echo '<meta property="fb:app_id" content="' . of_get_option('facebook_app_id') . '" />';
				}

				// og:image
				if($ogimage = get_post_image(get_the_ID(), 'share', false )){
					echo '<meta property="og:image" content="' . get_post_image(get_the_ID(), 'share', true ) . '" />';
				}else{
					echo '<meta property="og:image" content="' . of_get_option('social_share_image') . '" />';
				}

				if (is_single() || is_page() ){ 
					if ( have_posts() ) {
						while ( have_posts() ){ 
							the_post();
							echo '<meta property="og:description" content="' . mb_substr(strip_tags(get_the_excerpt()), 0, 155) . '" />';
						} 
					// og:type
					echo '<meta property="og:type" content="article" />'; 
					// og:url
					echo '<meta property="og:url" content="'.get_permalink().'"/>';	
					}
				}elseif(is_home()){ 
					echo '<meta property="og:description" content="';
					bloginfo('description');
					echo '" />';
					
					// og:url
					echo '<meta property="og:url" content="' . get_home_url() . '" />';					
				}else{
					// og:type
					echo '<meta property="og:type" content="website" />';
				}
			}
		}
	}

	/*  Post Thumbnails  */
	if ( function_exists( 'add_image_size' ) ) {

		add_theme_support( 'post-thumbnails' );

		add_image_size( 'gallery-large', 870, 400, true );		// Large Blog Image
		add_image_size( 'featured_post', 400, 250, true );		// Featured Widget Image
		add_image_size( 'standard', 700, 300, true );			// Standard Blog Image
		add_image_size( 'small', 194, 150, true ); 				// Related posts image
		add_image_size( 'mini', 80, 80, true ); 				// sidebar widget image
	}


	/**
	 * Template for comments and pingbacks.
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 */
	if(!function_exists('bluth_comment')){
		function bluth_comment( $comment, $args, $depth ) {
		    $GLOBALS['comment'] = $comment;
		    switch ( $comment->comment_type ) :
		        case 'pingback' :
		        case 'trackback' :
		    ?>
		    <li class="post pingback">
		        <p><?php _e( 'Pingback:', 'bluth' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'bluth' ), ' ' ); ?></p>
		    <?php
		            break;
		        default :
		    ?>
		    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		        <article id="comment-<?php comment_ID(); ?>" class="comment">
		            <div>
		                <div class="comment-author">
		                    <?php echo get_avatar( $comment, 45 ); ?>
		                    <?php printf( __( '%s', 'bluth' ), sprintf( '<cite class="commenter">%s</cite>', get_comment_author_link() ) ); ?>
		                	<small class="muted">&nbsp;&nbsp;•&nbsp;&nbsp;<time class="timeago" datetime="<?php comment_time( 'c' ); ?>"></time></small>
		                	<?php if ($comment->user_id == get_queried_object()->post_author){ ?>
		                	&nbsp;&nbsp;<span class="label label-success"><?php _e('Author', 'bluth'); ?></span>
		                	<?php } ?>
		                </div><!-- .comment-author .vcard -->
		                <?php if ( $comment->comment_approved == '0' ) : ?>
		                    <em><?php _e( 'Your comment is awaiting moderation.', 'bluth' ); ?></em>
		                    <br />
		                <?php endif; ?>
		            </div>
		 
		            <div class="comment-content"><?php comment_text(); ?></div>
		 
		            <div class="reply">
		                <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		                <?php edit_comment_link( __( '(Edit)', 'bluth' ), '&nbsp;&nbsp;' ); ?>
		            </div><!-- .reply -->
		        </article><!-- #comment-## -->
		 
		    <?php
		            break;
		    endswitch;
		}
	}

	// add span tag around categories post count
	if(!function_exists('cat_count_span')){ 
		function cat_count_span($links) {
			return str_replace(array('</a> (',')'), array('</a> <span class="badge">','</span>'), $links);
		}
	}
	add_filter('wp_list_categories', 'cat_count_span');

	// add span tag around archives post count
	if(!function_exists('archive_count_no_brackets')){ 
		function archive_count_no_brackets($links) {
		  	return str_replace(array('</a>&nbsp;(',')'), array('</a> <span class="badge">','</span>'), $links);
		}
	}
	add_filter('get_archives_link', 'archive_count_no_brackets');

	// Replaces the excerpt "more" text by a link
	if(!function_exists('new_excerpt_more')){ 
		function new_excerpt_more($more) {
			if( !is_single() ){
		    	global $post;
	    		return false;

		    	/*if( !of_get_option('show_continue_reading') ){
		    	}else{
					return '<br /><a class="moretag" href="'. get_permalink($post->ID) . '">' . __('Continue reading...', 'bluth') . '</a>';
		    	}*/
			}
		}
	}
	add_filter('excerpt_more', 'new_excerpt_more');

	// Manual excerpt
	function excerpt_read_more_link($output) {
		if( !is_single() ){
	    	global $post;

	    	if( !of_get_option('show_continue_reading') ){
	    		return false;
	    	}else{
				return substr($output,0,-5) . '... <br /><a class="moretag" href="'. get_permalink($post->ID) . '">' . __('Continue reading...', 'bluth') . '</a>';
	    	}
		}
	}
	add_filter('the_excerpt', 'excerpt_read_more_link');

	// Excerpt length
	if(!function_exists('new_excerpt_length')){ 
		function new_excerpt_length($length) {
			return of_get_option('excerpt_length', '55');
		}
	}
	add_filter('excerpt_length', 'new_excerpt_length');


	//remove empty p tags
	add_filter('the_content', 'remove_empty_p', 20, 1);
	function remove_empty_p($content){
		$content = force_balance_tags($content);
		return preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
	}

	// get gravatar URL
	function get_avatar_url( $get_avatar ) {
	    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
	    return $matches[1];
	}

	// Get the post image
	if(!function_exists('get_post_image')){
		function get_post_image( $post_id, $size = 'small', $author_fallback = true, $thumbnail_id = false ) {
			// get thumbnail id if not present
			if($thumbnail_id){ return wp_get_attachment_image_src( $thumbnail_id, $size ); }

			// if there's a custom thumbnail present then always fetch that if this is a thumbnail image
			$custom_thumbnail = get_post_meta( $post_id, 'bluth_custom_thumbnail', true);
			if($custom_thumbnail && !empty($custom_thumbnail['gallery_src']) && ( $size == 'small' || $size == 'thumbnail' || $size == 'mini' || $size == 'share' )){
				$custom_thumbnail = wp_get_attachment_image_src( $custom_thumbnail['gallery_id'], $size );
				return $custom_thumbnail[0];
			}

			// if the post has a featured image, always display that
			$thumb_id = get_post_thumbnail_id( $post_id );
			$thumb_url = wp_get_attachment_image_src( $thumb_id, $size, false );
			if($thumb_url[0]){
				return $thumb_url[0];
			}

			if(has_post_format('gallery', $post_id)){
				// if it's a gallery post and doesn't have a featured image, then display the first image in the gallery
				$images = get_post_meta( $post_id, 'blu_gallery', true );
				if(!empty($images[0]['gallery_src'])){
					$first_gallery_image = wp_get_attachment_image_src( $images[0]['gallery_id'], $size );
					return $first_gallery_image[0];
				}
			}else if ( get_children(array('numberposts' => 1, 'post_parent' =>  $post_id, 'post_type' => 'attachment', 'post_status' => null, 'post_mime_type' => 'image' )) ){ 
				
				$args = array(
					'numberposts' => 1, 
					'order' => 'ASC', 
					'post_parent' =>  $post_id, 
					'post_type' => 'attachment', 
					'post_status' => null, 
					'post_mime_type' => 'image' );

				$images = get_children( $args );

				// else display the first image in the post
				// $image = array_reverse($image);
				$image = current($images);
				$image = wp_get_attachment_image_src($image->ID, $size);
				return $image[0];
			}else if($first_image = catch_that_image()){
				// else try again to get the first image in the post
				return $first_image;
			}else if( $author_fallback ){
				// if it can't find anything and the author fallback is enabled then display the authors image
				return get_avatar_url( get_avatar( get_the_author_meta( 'ID' ) , $size ));
			}else{
				return false;
			} 
		}
	}
	// Get the first image
	if(!function_exists('catch_that_image')){
		function catch_that_image() {
			global $post, $posts;
			$first_img = '';
			ob_start();
			ob_end_clean();
			$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
			$first_img = isset($matches[1][0]) ? $matches[1][0] : false;

			if(empty($first_img))
				return false;

			return $first_img;
		}
	}
	// get the post icon
	function get_post_icon( $post_id ) {

		$post_format = (get_post_format( $post_id )) ? get_post_format( $post_id ) : 'standard'; 	

		$icon = of_get_option($post_format.'_icon', false);
		$icon_default = array('standard' => 'icon-calendar-3', 'audio' => 'icon-volume-up', 'video' => 'icon-videocam','quote' => 'icon-quote-left', 'link' => 'icon-link', 'image' => 'icon-picture-1', 'gallery' => 'icon-picture');
		$icon = ($icon !== false) ? $icon : $icon_default[$post_format];

		return $icon;

	}

	function hrw_enqueue()
	{
	  wp_enqueue_media();
		wp_enqueue_style( 'cdlayout-style', get_template_directory_uri() . '/assets/css/style-admin.css', array(), NULL, 'all' );   
	  wp_enqueue_script('hrw',  get_template_directory_uri() . '/assets/js/admin-script.js', array( 'jquery' ), BLISS_VERSION, true);
	}

	// get post view count
	function wpb_set_post_views($postID) {
	    $count_key = 'wpb_post_views_count';
	    $count = get_post_meta($postID, $count_key, true);
	    if($count==''){
	        $count = 0;
	        delete_post_meta($postID, $count_key);
	        add_post_meta($postID, $count_key, '0');
	    }else{
	        $count++;
	        update_post_meta($postID, $count_key, $count);
	    }
	}
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


	function wpb_get_post_views($postID){
		$count_key = 'wpb_post_views_count';
		$count = get_post_meta($postID, $count_key, true);
		if($count==''){
		    delete_post_meta($postID, $count_key);
		    add_post_meta($postID, $count_key, '0');
		    return '0';
		}
		return $count;
	}

	// favicon
 	function bluth_favicon() {
  		if(of_get_option('favicon')){
	   		echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.of_get_option('favicon').'" />';
	  	}
 	}
 	add_action('wp_head', 'bluth_favicon');

	// useful truncate function
	function bl_truncate($string, $limit, $break=".", $pad="..."){

	  	if(strlen($string) <= $limit) return $string;

	  		if(false !== ($breakpoint = strpos($string, $break, $limit))) {
	    		if($breakpoint < strlen($string) - 1) {
	      		$string = substr($string, 0, $breakpoint) . $pad;
			}
	  	}
	 	return $string;
	}
	
	/*

		MAILCHIMP

	*/
	// MailChimp Widget
	if(!function_exists('blu_ajax_mailchimp')){
		function blu_ajax_mailchimp(){  
			$options = get_option('widget_bl_newsletter');
			foreach($options as $option){
				if(is_array($option) and in_array($_POST['list'], $option)){
					$options = $option;
				}
			}

			if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $_POST['email'])) {
				echo json_encode(array("error" => __('Email address is invalid','bluth'))); 
				die();
			}
			else if(!isset($options['list_id'])){
				echo json_encode(array("error" => "No mailing list selected"));
				 die();
			}
			else if(!isset($options['api_key'])){
				echo json_encode(array("error" => "API key not defined")); 
				die();
			}
			else if(!isset($_POST['email'])){ 
				echo json_encode(array("error" => __('No email address provided','bluth'))); 
				die();
			} 

			require_once(get_template_directory().'/inc/mailchimp/MCAPI.class.php');

			$api = new MCAPI($options['api_key']);

			$list_id = $options['list_id'];

			if($api->listSubscribe($list_id, $_POST['email'], '') === true) {
				echo json_encode(array("status" => 'ok'));
			}else{
				echo json_encode(array("error" => 'Error: ' . $api->errorMessage));
			}
		    die();
		} 
	} 
	add_action( 'wp_ajax_blu_ajax_mailchimp', 'blu_ajax_mailchimp' );
	add_action( 'wp_ajax_nopriv_blu_ajax_mailchimp', 'blu_ajax_mailchimp' );  
/*	add_filter( 'the_title', 'blu_modified_title');
	function blu_modified_title ($title) {
	  global $post;
	  if( in_the_loop() && !is_page() ){
	    $title = $title." <small style='font-size:14px; margin: 5px 0; display:block;'>".get_the_author().'</small>';
	  }
	  return $title;
	}*/