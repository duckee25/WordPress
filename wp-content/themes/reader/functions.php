<?php 
require_once 'framework/vafpress/bootstrap.php';

$r_widget_rp = 	get_template_directory() . '/widgets/recent-post.php';
include $r_widget_rp;
	
$r_widget_pp = 	get_template_directory() . '/widgets/popular-post.php';
include $r_widget_pp;
	
$r_widget_tf = 	get_template_directory() . '/widgets/twitter-feed.php';
include $r_widget_tf;

$r_widget_si = 	get_template_directory() . '/widgets/social-icons.php';
include $r_widget_si;

$r_widget_ad = 	get_template_directory() . '/widgets/advertisements.php';
include $r_widget_ad;

$r_widget_au = 	get_template_directory() . '/widgets/about-us.php';
include $r_widget_au;

$r_widget_jp = 	get_template_directory() . '/widgets/job-posts.php';
include $r_widget_jp;


require_once(get_template_directory() . '/include/wp_bootstrap_navwalker.php');
include(get_template_directory() . '/include/helper.php');



function r_get_image_id($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
	if(isset($attachment[0])){
        return $attachment[0]; 
		}
}



/**
 * Building Options
 */
function r_init_options(){
global $theme_options;
    $r_option_path  = get_template_directory() . '/framework/options/option.php';
    $theme_options = new VP_Option(array(
        'is_dev_mode'           => false,
        'option_key'            => 'r_option',
        'page_slug'             => 'r_option',
        'template'              => $r_option_path,
        'menu_page'             => array(								
									'position' => '100.4',
								),
        'use_auto_group_naming' => true,
        'use_exim_menu'         => false,
		'use_util_menu'         => true,    
        'minimum_role'          => 'edit_theme_options',
        'layout'                => 'fixed',
        'page_title'            => __( 'Theme Options', 'reader' ),
        'menu_label'            => __( 'Theme Options', 'reader' ),
    ));
}
add_action( 'after_setup_theme', 'r_init_options' );

function r_option($key) {
	return vp_option('r_option' . '.' . $key);
}
if ( ! isset( $content_width ) )
	$content_width = 600;

//directory of languages folder
load_theme_textdomain( 'reader', get_stylesheet_directory() . '/languages' );



function r_pagination($paged_navi = '',$pages=''){
	$range = 2;
    $showitems = 3;  
	$passtest = false;
	
	global $paged;
    if ( get_query_var('paged') ) {
		$paged = get_query_var('paged'); 
	} else if ( get_query_var('page') ) {
		$paged = get_query_var('page'); 
	}else if ( $paged_navi!='' ) {
		$paged = $paged_navi; 
	} else {
		$paged = 1; 
	}
	echo wp_kses_post($paged_navi);
	
	if($paged_navi<2) $range = 2;
	if($paged_navi==2) $range = 2;
     if($pages == ''){
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages){
             $pages = 1;
         }
     }   
	 
     if(1 != $pages){
		echo '<ul class="navigate-page">';
         if($paged > 2 && $paged > $range+1 && $showitems < $pages){
			echo "<li><a href='".get_pagenum_link(1)."'>Previous</a></li> ";
		}else{
			echo "<li class='disabled'><a href='".get_pagenum_link(1)."'>Previous</a></li> ";
		}
		$j=0;
		$linkpoints="";
         for ($i=1; $i <= $pages; $i++){
		 
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
			 
				if($j != 4){
					echo ($paged == $i)? "<li class='active'><a href='#'>".$i."</a></li> ":"<li><a href='".get_pagenum_link($i)."' class='' >".$i."</a></li> ";
					if($paged==1||$paged==2){
					$linkpoints = "<li><a href='".get_pagenum_link($i+1)."' class='' >...</a></li> ";
					}
				}else{
					$linkpoints = "<li><a href='".get_pagenum_link($i)."' class='' >...</a></li> ";
				}
				 $j++;				
             }
         }
		
		 if ($paged < $pages){
			if($paged < ($pages-2)){
				if($paged < ($pages-3)){
				echo wp_kses_post($linkpoints);
				}
				echo "<li><a href='".get_pagenum_link($pages-1)."'>".($pages-1)."</a></li> ";		 
			}
		 echo "<li><a href='".get_pagenum_link($pages)."'>Next</a></li> ";		 
		 }else{
		 echo "<li><a href='".get_pagenum_link($pages)."'>Next</a></li> ";	
		 }
		 echo '</ul>';
     }
	if($passtest){
	posts_nav_link();
	}
}




// Adding specific CSS class
add_filter('body_class','r_addinr_portfolio_class');
function r_addinr_portfolio_class($classes) {
	if(r_option('style_page')){
	$stylepage = is_page()&&!is_home();
	}else{
	$stylepage = false;
	}
	
	$classes[] = 'style-blog-'.r_option('style_blog');
	
	if(r_option('ajax_posts')){
	$classes[] = 'loading-ajax-post';
	}
	if($stylepage){
	$classes[] = 'style-sidebar-right_s';
	}else{
		if(r_option('sidebar_s')==''){
			$classes[] = 'style-sidebar-both_s';
		}else{
			$classes[] = 'style-sidebar-'.r_option('sidebar_s');
		}
	}
	$classes[] = get_post_format();
	
	if(r_option('header_white')){
	$classes[] = 'header_white';
	}
	return $classes;
}



function r_register_menus() {
	register_nav_menus(array(
		'main-menu' => 'Main Menu',		
	));
}
add_action('init', 'r_register_menus');




	
function r_word_limit(){
	if ( !is_singular() ) {
        add_filter("the_content", "r_word_limit_op");
    }
}
function r_word_limit_op($content){
	$word_limit = r_option('word_limit');
	if(r_option('style_blog')=='grid'){
	 $word_limit = r_option('word_limit_grid');
	}
	if($word_limit == ""){
		$word_limit =40;
	}
	
    $words = explode(" ",$content);
    return implode(" ",array_splice($words,0,$word_limit));
	
}





add_action( 'add_meta_boxes', 'r_meta_box_add_po' );
function r_meta_box_add_po(){
	$jobboard_slug ='jobpost';
	if(r_option('jobboard_slug')!=''){
	$jobboard_slug = r_option('jobboard_slug');
	}
	add_meta_box( 'meta-box-one-page', __(' Details: ','reader'), 'r_meta_box_po', $jobboard_slug, 'normal', 'high' );
}

function r_meta_box_po( $post )
{
	$values = get_post_custom( $post->ID );
	$location = isset( $values['location'] ) ? ( $values['location'][0] ) : '';

    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	?>

	<div id="portfolio_metabox" style="display:block important">
		<p>
			<h3><label for="location"><?php _e('Location:','reader') ?> </label></h3>
			<input style="width:100%" type="location" name="location" id="location" value="<?php echo esc_attr($location) ?>"/>
		</p>
	</div>
    <?php
}









add_action( 'save_post', 'r_meta_box_save' );
function r_meta_box_save( $post_id ){
    if( defined( 'DOINr_AUTOSAVE' ) && DOINr_AUTOSAVE ) return;
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
    if(  ! current_user_can( 'edit_page', $post_id)) return;

	if( isset( $_POST['location'] ) )
        update_post_meta( $post_id, 'location',  $_POST['location'] );
}





/*-----------------------------------------------------------------------------------*/
// Theme setup
/*-----------------------------------------------------------------------------------*/
add_action( 'after_setup_theme', 'r_theme_setup' );

function r_theme_setup() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('post-formats', array('link', 'gallery', 'quote', 'video', 'audio'));
	set_post_thumbnail_size( 50, 50, true );	
}




/*-----------------------------------------------------------------------------------*/
/*	Queue Scripts
/*-----------------------------------------------------------------------------------*/ 





function r_scripts() {
	$font_family_url1 = r_option('font_family_url1');
	if($font_family_url1!=''){
	wp_register_style( 'googlefont1', $font_family_url1);
	}else{
	wp_register_style( 'googlefont1', 'http://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700');
	}
	wp_enqueue_style( 'googlefont1' );
	if(r_option('style_page')){
	$stylepage = is_page()&&!is_home();
	}else{
	$stylepage = false;
	}
	
	
	//ENQUEUE CSS
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_register_style( 'flaticon', get_template_directory_uri() . '/css/flaticon.css');
	wp_register_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css');
	wp_register_style( 'nprogress', get_template_directory_uri() . '/css/nprogress.css');
	wp_register_style( 'mediaelementplayer', get_template_directory_uri() . '/css/mediaelementplayer.css');
	wp_register_style( 'style', get_template_directory_uri() . '/style.css');
	if(r_option('sidebar_s')=='right_s'||($stylepage)){
	wp_register_style( 'responsive', get_template_directory_uri() . '/css/blog_style2.css');
	}else{
	wp_register_style( 'responsive', get_template_directory_uri() . '/css/responsive.css');
	}
	
	
	
    wp_enqueue_style( 'bootstrap' );
    wp_enqueue_style( 'flaticon' );
    wp_enqueue_style( 'fontawesome' );
    wp_enqueue_style( 'nprogress' );
    wp_enqueue_style( 'mediaelementplayer' );
    wp_enqueue_style( 'style' );
	if(r_option('header_white')){
	wp_register_style( 'header_white', get_template_directory_uri() . '/css/header-white.css');	
	wp_enqueue_style( 'header_white' );	
	}
    wp_enqueue_style( 'responsive' );

	
	
	if(r_option('custom_color') && r_option('color_pri')){
	$path_color = get_template_directory_uri() . '/css/colors/'.r_option('color_pri').'.css';    
	wp_register_style( 'custom_color', $path_color);
    wp_enqueue_style( 'custom_color' );	
	}else{
		wp_register_style( 'custom_color', get_template_directory_uri() . '/css/colors/nephritis.css');
		wp_enqueue_style( 'custom_color' );	
	}	
	
    $logo_height = esc_attr(r_option('logo_height'));
	$font_family_name1 = esc_attr(r_option('font_family_name1'));
	$font_family_name2 = esc_attr(r_option('font_family_name2'));
	$custom_css =  r_option('custom_css');
	if(r_option('font_family_name1')!='Raleway'){
	$custom_css .= 'h1,h2,h3,h4,body { font-family: '.r_option('font_family_name1').', sans-serif;}';
	}
    wp_add_inline_style( 'style', $custom_css );
	
	
	
	//ENQUEUE JAVSCRIPTS
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('script2', get_template_directory_uri().'/js/bootstrap.min.js', array(), '', true);
	wp_enqueue_script('script3', get_template_directory_uri().'/js/mediaelement-and-player.min.js', array(), '', true);
	wp_enqueue_script('script4', get_template_directory_uri().'/js/tweetie.min.js', array(), '', true);
	wp_enqueue_script('script5', get_template_directory_uri().'/js/jquery.fitvids.js', array(), '', true);
	wp_enqueue_script('script6', get_template_directory_uri().'/js/jquery.jscroll.js', array(), '', true);
	wp_enqueue_script('script7', get_template_directory_uri().'/js/custom.js', array(), '', true);
	

	
	//INSERT JAVSCRIPT VARS
	wp_localize_script( 'jquery', 'templateUrl', get_template_directory_uri() );
	wp_localize_script( 'script2', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
	$styleblog = r_option('style_blog');
	
	wp_localize_script( 'script2', 'styleblogjs', $styleblog );

	
}
add_action( 'wp_enqueue_scripts', 'r_scripts' );




	
 function ieimport() {
	echo '<!--[if lt IE 9]>';
	echo '<script src="'. get_template_directory_uri().'/js/html5shiv.js"></script>';
	echo '<script src="'. get_template_directory_uri().'/js/respond.min.js"></script>';
	echo '<![endif]-->';
}
add_action('wp_head', 'ieimport' );



function r_add_image_sizes() {
	add_image_size('blog-post-thumbnail', 800, 405, true);	
	add_image_size('portfolio-thumbnail', 390, 285, true);	
	add_image_size('blog-thumbnail-vertical', 260, 350, true);		
}
add_action('init', 'r_add_image_sizes');





function r_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);	
	$width_co = 24;
?>
 <li>
                                <div class="row">
									<?php if(get_avatar( $comment, 128 )): ?>	
									<?php $width_co = 20; ?>									
                                    <div class="col-sm-4">
                                        <div class="thumb">
                                            <?php echo get_avatar( $comment, 128 ); ?>
                                        </div>
                                    </div>
									<?php endif; ?>
                                    <div class="col-sm-<?php echo esc_attr($width_co) ?>">
                                        <p><?php comment_text() ?></p>
                                        <p>
                                            <span class="author-comment"><?php comment_author_link()	?></span>
                                            <span><?php echo sprintf(__('%s ago','reader'), human_time_diff(get_comment_date('U'))) ?> </span>
                                            <?php comment_reply_link(array_merge( $args, array('reply_text'=>__('Reply','reader'),'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                                        </p>
                                    </div>
                                </div>
                                <hr class="small">
                            </li>



<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
<?php
}




/**
 * Sidebar widgets
 */
if(function_exists('register_sidebar')){
	register_sidebar(array(
	'name'=>'Default sidebar',
	'id'=>'sidebar-1',
	'before_widget'=>'<div class="widget %2$s" id="%1$s">',
	'after_widget'=>'</div><hr class="small">',
	'before_title'=>'<h5 class="sr_w_title">',
	'after_title'=>'</h5>'
	));
}

if(function_exists('register_sidebar')){
	register_sidebar(array(
	'name'=>'Left Sidebar',
	'id'=>'left-sidebar',
	'before_widget'=>'<li class="widget %2$s" id="%1$s">',
	'after_widget'=>'</li>',
	'before_title'=>'<h4>',
	'after_title'=>'</h4>'
	));
}
if(function_exists('register_sidebar')){
	register_sidebar(array(
	'name'=>'Footer 1',
	'id'=>'footer-1',
	'before_widget'=>'<div class="widget %2$s" id="%1$s">',
	'after_widget'=>'</div>',
	'before_title'=>'<h5>',
	'after_title'=>'</h5>'
	));
}

if(function_exists('register_sidebar')){
	register_sidebar(array(
	'name'=>'Footer 2',
	'id'=>'footer-2',
	'before_widget'=>'<div class="widget %2$s" id="%1$s">',
	'after_widget'=>'</div>',
	'before_title'=>'<h5>',
	'after_title'=>'</h5>'
	));
}

if(function_exists('register_sidebar')){
	register_sidebar(array(
	'name'=>'Footer 3',
	'id'=>'footer-3',
	'before_widget'=>'<div class="widget %2$s" id="%1$s">',
	'after_widget'=>'</div>',
	'before_title'=>'<h5>',
	'after_title'=>'</h5>'
	));
}




add_filter('get_avatar','r_gravatar_class');

function r_gravatar_class($class) {
    $class = str_replace("class='avatar", "class='avatar thumb", $class);
    return $class;
}









function r_wp_title( $title, $sep ) {
    global $paged, $page;

    if ( is_feed() ) {
        return $title;
    }

    $title .= get_bloginfo( 'name', 'display' );

    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) ) {
        $title = "$title $sep $site_description";
    }

    if ( $paged >= 2 || $page >= 2 ) {
        $title = "$title $sep " . sprintf( __( 'Page %s', 'reader' ), max( $paged, $page ) );
    }

    return $title;
}
add_filter( 'wp_title', 'r_wp_title', 10, 2 );







/**
 * TGM-Plugin-Activation
 */
require_once dirname( __FILE__ ) . '/include/class-tgm-plugin-activation.php';

function r_plugin_activation() {

	$plugins = array(
		array(
			'name'     => 'ShortcodesDex',
			'slug'     => 'shortcodesdex',
			'source'   				=> get_stylesheet_directory() . '/include/libs/shortcodesdex.zip',
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.2.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented. If the plugin version is higher than the plugin version installed , the user will be notified to update the plugin
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),array(
			'name'     => 'Job Posting dex',
			'slug'     => 'jobposting-dex',
			'source'   				=> get_stylesheet_directory() . '/include/libs/jobposting-dex.zip',
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.0.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented. If the plugin version is higher than the plugin version installed , the user will be notified to update the plugin
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),	array(
			'name'     => 'mailchimp',
			'slug'     => 'mailchimp',
			'required' => false,
		),		
	);

	$theme_text_domain = 'reader';

	$config = array(
        'domain'            => $theme_text_domain,           // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                           // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'themes.php',         // Default parent menu slug
        'parent_url_slug'   => 'themes.php',         // Default parent URL slug
        'menu'              => 'install-required-plugins',   // Menu slug
        'has_notices'       => true,                         // Show admin notices or not
        'is_automatic'      => false,            // Automatically activate plugins after installation or not
        'message'           => '',               // Message to output right before the plugins table
        'strings'           => array(
            'page_title'                                => __( 'Install Required Plugins', $theme_text_domain ),
            'menu_title'                                => __( 'Install Plugins', $theme_text_domain ),
            'installing'                                => __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
            'oops'                                      => __( 'Something went wrong with the plugin API.', $theme_text_domain ),
            'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
            'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
            'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
            'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return'                                    => __( 'Return to Required Plugins Installer', $theme_text_domain ),
            'plugin_activated'                          => __( 'Plugin activated successfully.', $theme_text_domain ),
            'complete'                                  => __( 'All plugins installed and activated successfully. %s', $theme_text_domain ) // %1$s = dashboard link
        )
    );

	tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'r_plugin_activation');



 //show_admin_bar(false)

?>