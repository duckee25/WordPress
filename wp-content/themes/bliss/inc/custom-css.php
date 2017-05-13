<?php
function bluth_custom_css() {
global $css_options;

	$css_options = array(
		'custom_color_picker'				=> of_get_option('custom_color_picker'),
		'disable_link_background' 	=> of_get_option('disable_link_background'),
		'disable_link_animation' 						=> of_get_option('disable_link_animation'),
		'predefined_theme'					=> of_get_option('predefined_theme'),
		'text_font_size'						=> of_get_option('text_font_size'),
		'text_font_spacing'					=> of_get_option('text_font_spacing'),
		'theme_color' 							=> of_get_option('theme_color'),
		'background_color' 					=> of_get_option('background_color'),
		'top_banner_color' 					=> of_get_option('top_banner_color'),
		'top_banner_font_color' 		=> of_get_option('top_banner_font_color'),
		'top_banner_social_color' 	=> of_get_option('top_banner_social_color'),
		'header_color' 							=> of_get_option('header_color'),
		'header_font_color' 				=> of_get_option('header_font_color'),
		'post_header_color' 				=> of_get_option('post_header_color'),
		'main_font_color' 				=> of_get_option('main_font_color'),
		'widget_header_color' 			=> of_get_option('widget_header_color'),
		'widget_header_font_color' 	=> of_get_option('widget_header_font_color'),
		'footer_color' 							=> of_get_option('footer_color'),
		'footer_header_color' 			=> of_get_option('footer_header_color'),
		'footer_font_color' 				=> of_get_option('footer_font_color'),
		'standard_post_color' 			=> of_get_option('standard_post_color'),
		'gallery_post_color' 				=> of_get_option('gallery_post_color'),
		'image_post_color' 					=> of_get_option('image_post_color'),
		'link_post_color' 					=> of_get_option('link_post_color'),
		'quote_post_color' 					=> of_get_option('quote_post_color'),
		'audio_post_color' 					=> of_get_option('audio_post_color'),
		'video_post_color' 					=> of_get_option('video_post_color'),
		'status_post_color' 				=> of_get_option('status_post_color'),
		'sticky_post_color' 				=> of_get_option('sticky_post_color'),
		'anchor_anim' 							=> of_get_option('anchor_anim'),
		'enable_border' 						=> of_get_option('enable_border'),
		'custom_css' 								=> html_entity_decode(of_get_option('custom_css')),
		);

// predefined color themes
if(!$css_options['custom_color_picker'])
{
	switch($css_options['predefined_theme']){
		case 'default';	
			$css_options['theme_color'] 							= '#45b0ee';
			$css_options['background_color'] 					= '#EDEDED';
			$css_options['top_banner_color'] 					= '#444444';
			$css_options['top_banner_font_color'] 		= '#FFFFFF';
			$css_options['top_banner_social_color']		= '#FFFFFF';
			$css_options['header_color'] 							= '#FFFFFF';
			$css_options['header_font_color'] 				= '#777777';
			$css_options['post_header_color'] 				= '#333333';
			$css_options['main_font_color'] 				= '#333333';
			$css_options['widget_header_color'] 			= '#FFFFFF';
			$css_options['widget_header_font_color'] 	= '#333333';
			$css_options['footer_color'] 							= '#333333';
			$css_options['footer_header_color'] 			= '#FFFFFF';
			$css_options['footer_font_color'] 				= '#FFFFFF';
		break;
		case 'blue';	
			$css_options['theme_color'] 							= '#2E78AD';
			$css_options['background_color'] 					= '#FFFFFF';
			$css_options['top_banner_color'] 					= '#323B44';
			$css_options['top_banner_font_color'] 		= '#FFFFFF';
			$css_options['top_banner_social_color']		= '#FFFFFF';
			$css_options['header_color'] 							= '#296FA3';
			$css_options['header_font_color'] 				= '#FFFFFF';
			$css_options['post_header_color'] 				= '#296FA3';
			$css_options['main_font_color'] 				= '#333333';
			$css_options['widget_header_color'] 			= '#FFFFFF';
			$css_options['widget_header_font_color'] 	= '#333333';
			$css_options['footer_color'] 							= '#323B44';
			$css_options['footer_header_color'] 			= '#EEEEEE';
			$css_options['footer_font_color'] 				= '#EEEEEE';
		break;
		case 'orange';	
			$css_options['theme_color'] 							= '#F77462';
			$css_options['background_color'] 					= '#FFFFFF';
			$css_options['top_banner_color'] 					= '#323B44';
			$css_options['top_banner_font_color'] 		= '#FFFFFF';
			$css_options['top_banner_social_color']		= '#FFFFFF';
			$css_options['header_color'] 							= '#E56B5B';
			$css_options['header_font_color'] 				= '#FFFFFF';
			$css_options['post_header_color'] 				= '#F77462';
			$css_options['main_font_color'] 				= '#333333';
			$css_options['widget_header_color'] 			= '#FFFFFF';
			$css_options['widget_header_font_color'] 	= '#333333';
			$css_options['footer_color'] 							= '#323B44';
			$css_options['footer_header_color'] 			= '#DDDDDD';
			$css_options['footer_font_color'] 				= '#EEEEEE';
		break;
		case 'green';	
			$css_options['theme_color'] 							= '#75A863';
			$css_options['background_color'] 					= '#FFFFFF';
			$css_options['top_banner_color'] 					= '#FFFFFF';
			$css_options['top_banner_font_color'] 		= '#333333';
			$css_options['top_banner_social_color']		= '#333333';
			$css_options['header_color'] 							= '#75A863';
			$css_options['header_font_color'] 				= '#FFFFFF';
			$css_options['post_header_color'] 				= '#333333';
			$css_options['main_font_color'] 				= '#333333';
			$css_options['widget_header_color'] 			= '#FFFFFF';
			$css_options['widget_header_font_color'] 	= '#333333';
			$css_options['footer_color'] 							= '#333333';
			$css_options['footer_header_color'] 			= '#EEEEEE';
			$css_options['footer_font_color'] 				= '#FFFFFF';
		break;
	}
}

if(!empty($css_options)){ ?>
<style type="text/css">
	<?php 

	echo (!empty($css_options['enable_border'])) ? '.box, .entry-container{ box-shadow: 0 0 0 1px rgba(0,0,0,0.05); }' : '';
	echo (!empty($css_options['text_font_size'])) ? '.entry-content p, .entry-content ul li, .entry-content p, .entry-content ol li{ font-size:' . $css_options['text_font_size'] . '; }' : '';
	echo (!empty($css_options['text_font_spacing'])) ? '.entry-content p, .entry-content ul li, .entry-content p, .entry-content ol li{ line-height:' . $css_options['text_font_spacing'] . '; }' : '';

	echo (!empty($css_options['theme_color'])) ? '.top-color, .widget_tag_cloud .tagcloud a:hover, .top-line, .nav-line{ background-color:' . $css_options['theme_color'] . '; }#side-bar .widget_recent_entries li:hover, #side-bar .widget_nav_menu div > ul > li:hover, #side-bar .widget_categories li:hover, #side-bar .widget_recent_comments li:hover, #side-bar .widget_meta li:hover, #side-bar .widget_pages li:hover, #side-bar .widget_archive li:hover, #bl_side_posts > ul li:hover, #bl_side_comments > ul li:hover{ border-left: 3px solid ' . $css_options['theme_color'] . '; } .site-footer #footer-body .widget_nav_menu a:hover, .site-footer #footer-body .widget_archive a:hover, .site-footer #footer-body .widget_tag_cloud a:hover, .site-footer #footer-body .widget_recent_entries a:hover, .site-footer #footer-body .widget_meta a:hover, .site-footer #footer-body .widget_categories a:hover, .site-footer #footer-body .widget_pages a:hover, #bl_side_tags .bl_tab_tag:hover{ background-color: ' . $css_options['theme_color'] . '; } .dropdown-menu{ border-top: 2px solid ' . $css_options['theme_color'] . '; } .bl_tabs ul li .tab_text a span, a{ color: ' . $css_options['theme_color'] . '; }' : '';
	echo (!empty($css_options['background_color'])) ? 'body{ background: '.$css_options['background_color']. '; } ' : '';
	echo (!empty($css_options['post_header_color'])) ? '.entry-title a{ color: '.$css_options['post_header_color']. '; } ' : '';
	echo (!empty($css_options['main_font_color'])) ? '.entry-content p, .entry-content ul li, .entry-content p, .entry-content ol li{ color: '.$css_options['main_font_color']. '; } ' : '';

	echo (!empty($css_options['top_banner_font_color'])) ? '#masthead .top-banner, #masthead .brand h1, #masthead .brand small{ color: ' . $css_options['top_banner_font_color'] . '; } ' : '';
	echo (!empty($css_options['top_banner_social_color'])) ? '#masthead .top-banner .top-banner-social a { color: ' . $css_options['top_banner_social_color'] . '; } ' : '';
	echo (!empty($css_options['header_color'])) ? 'header#masthead,.navbar-inverse .navbar-inner,.dropdown-menu,.widget-head{ background: '.$css_options['header_color']. '; } ' : '';
	echo (!empty($css_options['header_font_color'])) ? '.navbar-inverse .nav li.dropdown.open > .dropdown-toggle, .navbar-inverse .nav li.dropdown.active > .dropdown-toggle, .navbar-inverse .nav li.dropdown.open.active > .dropdown-toggle, div.navbar-inverse .nav-collapse .nav > li > a, .navbar-inverse .nav-collapse .dropdown-menu a,#masthead .bluth-navigation.shrunk .mini-logo h1, #masthead .bluth-navigation.shrunk .mini-logo img,#masthead .searchform input,.widget-head, .widget-head a, .navbar .brand, #masthead .searchform a{color:'.$css_options['header_font_color']. '; } ' : '';
	echo (!empty($css_options['widget_header_color'])) ? '.widget-head{ background: '.$css_options['widget_header_color']. '; } ' : '';
	echo (!empty($css_options['widget_header_font_color'])) ? '.widget-head{color:'.$css_options['widget_header_font_color'].';} ' : '';
	echo (!empty($css_options['footer_color'])) ? 'footer.site-footer{ background:'.$css_options['footer_color'].'; }' : '';
	echo (!empty($css_options['footer_header_color'])) ? '#footer-body h3{color: ' . $css_options['footer_header_color'] . '; }' : '';
	echo (!empty($css_options['footer_font_color'])) ? '#footer-body > div ul li a, footer.site-footer > * { color: ' . $css_options['footer_font_color'] . '; }' : '';

	// if there's a background image, display it in transparent, otherwise display it as selected color
	if( of_get_option('header_background') == 'image'){
		echo '#masthead .top-banner{ background: transparent; }';
		echo '#masthead .bluth-navigation{ background: rgba(0,0,0,0.4); }';
		echo '#masthead .bluth-navigation .navbar .nav > li > a, #masthead .searchform a{ color: #FFFFFF; }';
	}else{	
		echo (!empty($css_options['top_banner_color'])) ? '#masthead .top-banner{ background: ' . $css_options['top_banner_color'] . '; } ' : '';
	}
	// link animation
	if(!empty($css_options['disable_link_animation'])){
		echo 'a, a:hover, article.type-post p a, article.type-post p a:hover{ -webkit-transition: none!important;  -moz-transition: none!important;  -o-transition: none!important;  -ms-transition: none!important;  transition: none!important; transform: none!important; -ms-transform: none!important; -webkit-transform: none!important; }';
	}
	if(!empty($css_options['standard_post_color'])){
		echo '.post-format-standard{color: '.$css_options['standard_post_color'].'}';
		echo '.tab_standard{background-color:'.$css_options['standard_post_color'].';}';
		echo '.format-standard .post-meta ~ * a, .format-standard .post-meta a:hover, .format-standard .entry-title a:hover{color:'.$css_options['standard_post_color'].';}';
		
		if(empty($css_options['disable_link_background']))
			echo 'article.format-standard p a, article.format-standard p a:hover span:before, article.format-standard p a:focus span:before, .format-standard a.more-link, .format-standard a.more-link:hover{ background-color: ' . $css_options['standard_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-standard p a, article.format-standard p a:hover span:before, article.format-standard p a:focus span:before, .format-standard a.more-link, .format-standard a.more-link:hover{ color: ' . $css_options['standard_post_color'] . '; padding:0; margin:0; }';

	}
	if(!empty($css_options['gallery_post_color'])){
		echo '.post-format-gallery{color: '.$css_options['gallery_post_color'].'}';
		echo '.tab_gallery{background-color:'.$css_options['gallery_post_color'].';}';
		echo '.format-gallery .post-meta ~ * a, .format-gallery .post-meta a:hover, .format-gallery .entry-title a:hover{color:'.$css_options['gallery_post_color'].';}';
		
		if(empty($css_options['disable_link_background']))
			echo 'article.format-gallery p a, article.format-gallery p a:hover span:before, article.format-gallery p a:focus span:before, .format-gallery a.more-link, .format-gallery a.more-link:hover{ background-color: ' . $css_options['gallery_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-gallery p a, article.format-gallery p a:hover span:before, article.format-gallery p a:focus span:before, .format-gallery a.more-link, .format-gallery a.more-link:hover{ color: ' . $css_options['gallery_post_color'] . '; padding:0; margin:0; }';
	}
	if(!empty($css_options['image_post_color'])){
		echo '.post-format-image{color: '.$css_options['image_post_color'].'}';
		echo '.tab_image{background-color:'.$css_options['image_post_color'].';}';
		echo '.format-image .post-meta ~ * a, .format-image .post-meta a:hover, .format-image .entry-title a:hover{color:'.$css_options['image_post_color'].';}';
		
		if(empty($css_options['disable_link_background']))
			echo 'article.format-image p a, article.format-image p a:hover span:before, article.format-image p a:focus span:before, .format-image a.more-link, .format-image a.more-link:hover{ background-color: ' . $css_options['image_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-image p a, article.format-image p a:hover span:before, article.format-image p a:focus span:before, .format-image a.more-link, .format-image a.more-link:hover{ color: ' . $css_options['image_post_color'] . '; padding:0; margin:0; }';
	}
	if(!empty($css_options['link_post_color'])){
		echo '.post-format-link, .post-format-link a{color: '.$css_options['link_post_color'].'}';
		echo '.tab_link{background-color:'.$css_options['link_post_color'].';}';
		echo '.format-link .post-meta ~ * a, .format-link .post-meta a:hover, .format-link .entry-title a:hover{color:'.$css_options['link_post_color'].';}';

		if(empty($css_options['disable_link_background']))
			echo 'article.format-link p a, article.format-link p a:hover span:before, article.format-link p a:focus span:before, .format-link a.more-link, .format-link a.more-link:hover{ background-color: ' . $css_options['link_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-link p a, article.format-link p a:hover span:before, article.format-link p a:focus span:before, .format-link a.more-link, .format-link a.more-link:hover{ color: ' . $css_options['link_post_color'] . '; padding:0; margin:0; }';
	}
	if(!empty($css_options['quote_post_color'])){
		echo '.post-format-quote{color: '.$css_options['quote_post_color'].'}';
		echo '.tab_quote{background-color:'.$css_options['quote_post_color'].';}';
		echo '.format-quote .post-meta ~ * a, .format-quote .post-meta a:hover, .format-quote .entry-title a:hover{color:'.$css_options['quote_post_color'].';}';

		if(empty($css_options['disable_link_background']))
			echo 'article.format-quote p a, article.format-quote p a:hover span:before, article.format-quote p a:focus span:before, .format-quote a.more-link, .format-quote a.more-link:hover{ background-color: ' . $css_options['quote_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-quote p a, article.format-quote p a:hover span:before, article.format-quote p a:focus span:before, .format-quote a.more-link, .format-quote a.more-link:hover{ color: ' . $css_options['quote_post_color'] . '; padding:0; margin:0; }';
	}
	if(!empty($css_options['audio_post_color'])){
		echo '.post-format-audio{color: '.$css_options['audio_post_color'].'}';
		echo '.tab_audio{background-color:'.$css_options['audio_post_color'].';}';
		echo '.format-audio .post-meta ~ * a, .format-audio .post-meta a:hover, .format-audio .entry-title a:hover{color:'.$css_options['audio_post_color'].';}';

		if(empty($css_options['disable_link_background']))
			echo 'article.format-audio p a, article.format-audio p a:hover span:before, article.format-audio p a:focus span:before, .format-audio a.more-link, .format-audio a.more-link:hover{ background-color: ' . $css_options['audio_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-audio p a, article.format-audio p a:hover span:before, article.format-audio p a:focus span:before, .format-audio a.more-link, .format-audio a.more-link:hover{ color: ' . $css_options['audio_post_color'] . '; padding:0; margin:0; }';
	}
	if(!empty($css_options['video_post_color'])){
		echo '.post-format-video{color: '.$css_options['video_post_color'].'}';
		echo '.tab_video{background-color:'.$css_options['video_post_color'].';}';
		echo '.format-video .post-meta ~ * a, .format-video .post-meta a:hover, .format-video .entry-title a:hover{color:'.$css_options['video_post_color'].';}';

		if(empty($css_options['disable_link_background']))
			echo 'article.format-video p a, article.format-video p a:hover span:before, article.format-video p a:focus span:before, .format-video a.more-link, .format-video a.more-link:hover{ background-color: ' . $css_options['video_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-video p a, article.format-video p a:hover span:before, article.format-video p a:focus span:before, .format-video a.more-link, .format-video a.more-link:hover{ color: ' . $css_options['video_post_color'] . '; padding:0; margin:0; }';
	}

	if(!empty($css_options['status_post_color'])){
		echo '.post-format-status{color: '.$css_options['video_post_color'].'}';
		echo '.tab_status{background-color:'.$css_options['status_post_color'].';}';
		echo '.format-status .post-meta ~ * a, .format-status .post-meta a:hover, .format-status .entry-title a:hover{color:'.$css_options['status_post_color'].';}';

		if(empty($css_options['disable_link_background']))
			echo 'article.format-status p a, article.format-status p a:hover span:before, article.format-status p a:focus span:before, .format-status a.more-link, .format-status a.more-link:hover{ background-color: ' . $css_options['video_post_color'] . '; color: #FFFFFF;}';
		else
			echo 'article.format-status p a, article.format-status p a:hover span:before, article.format-status p a:focus span:before, .format-status a.more-link, .format-status a.more-link:hover{ color: ' . $css_options['video_post_color'] . '; padding:0; margin:0; }';
	}
	if(!empty($css_options['sticky_post_color'])){
		echo '.sticky .post-format-badge{color: '.$css_options['stiky_post_color'].'};';
		echo '.sticky .post-meta ~ * a, .sticky .post-meta a:hover, .sticky .entry-title a:hover{color: '.$css_options['sticky_post_color'].';}';
// 		echo '.sticky p a span, .sticky p a:hover span:before, .sticky p a:focus span:before, .more-link{ background-color: ' . $css_options['sticky_post_color'] . ';}';

	}
	echo (!empty($css_options['custom_css'])) ? $css_options['custom_css'] : '';

	?>
</style>
<?php }

}
add_action( 'wp_head', 'bluth_custom_css', 100 );
?>