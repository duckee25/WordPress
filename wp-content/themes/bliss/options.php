<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = $themename;
	update_option( 'optionsframework', $optionsframework_settings );
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'bluth_admin'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */

function optionsframework_options() {


	$background_mode = array(
		'image' => __('Image', 'bluth_admin'),
		'pattern' => __('Pattern', 'bluth_admin'),
		'color' => __('Solid Color', 'bluth_admin')
	);


	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/assets/img/';

	$options = array();


	$options[] = array(
		'name' => __('Theme Options', 'bluth_admin'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Background', 'bluth_admin'),
		'desc' => __('What kind of background do you want?', 'bluth_admin'),
		'id' => 'background_mode',
		'std' => 'image',
		'type' => 'radio',
		'options' => $background_mode);

	$options[] = array(
		'name' => __('Background Image', 'bluth_admin'),
		'desc' => __('Upload your background image here.', 'bluth_admin'),
		'id' => 'background_image',
		'std' => get_template_directory_uri() . '/assets/img/bg.jpg',
		'class' => 'background_image',
		'type' => 'upload');

	$options[] = array(
	  'name' => __('Favicon', 'bluth_admin'),
	  'desc' => __('Upload a favicon. Favicons are the icons that appear in the tabs of the browser and left of the address bar. (16x16 pixels)', 'bluth_admin'),
	  'id' => 'favicon',
	  'type' => 'upload');

	$options[] = array(
	  'name' => __('Apple touch icon', 'bluth_admin'),
	  'desc' => __('Icons that appear on your homescreen when you press "Add to home screen" on your device (114x114 pixels (PNG file))', 'bluth_admin'),
	  'id' => 'apple_touch_icon',
	  'type' => 'upload');

	$options[] = array(
		'name' => __('Show stripe overlay', 'bluth_admin'),
		'desc' => __('Uncheck this to remove the stripe overlay that covers the background image', 'bluth_admin'),
		'id' => 'show_stripe',
		'std' => '1',
		'class' => 'background_image',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __("Select a background pattern", 'bluth_admin'),
		'desc' => __("Select a background pattern from the list or upload your own below.", 'bluth_admin'),
		'id' => "background_pattern",
		'std' => "brick_wall.jpg",
		'type' => "images",
		'class' => "hide background_pattern",
		'options' => array(
			'az_subtle.png' => $imagepath . '/pattern/sample/az_subtle_50.png',
			'cloth_alike.png' => $imagepath . '/pattern/sample/cloth_alike_50.png',
			'cream_pixels.png' => $imagepath . '/pattern/sample/cream_pixels_50.png',
			'gray_jean.png' => $imagepath . '/pattern/sample/gray_jean_50.png',
			'grid.png' => $imagepath . '/pattern/sample/grid_50.png',
			'light_noise_diagonal.png' => $imagepath . '/pattern/sample/light_noise_diagonal_50.png',
			'light_paper.png' => $imagepath . '/pattern/sample/light_paper_50.png',
			'noise_lines.png' => $imagepath . '/pattern/sample/noise_lines_50.png',
			'pw_pattern.png' => $imagepath . '/pattern/sample/pw_pattern_50.png',
			'shattered.png' => $imagepath . '/pattern/sample/shattered_50.png',
			'squairy_light.png' => $imagepath . '/pattern/sample/squairy_light_50.png',
			'striped_lens.png' => $imagepath . '/pattern/sample/striped_lens_50.png',
			'textured_paper.png' => $imagepath .'/pattern/sample/textured_paper_50.png')
	);

	$options[] = array(
		'name' => __('Upload Pattern', 'bluth_admin'),
		'desc' => __('Upload a new pattern here. If this feature is used it will overwrite the selection above.', 'bluth_admin'),
		'id' => 'background_pattern_custom',
		'class' => 'background_pattern',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Background Color', 'bluth_admin'),
		'desc' => __('Select the background color ( Only works if the custom color option is chosen )', 'bluth_admin'),
		'id' => 'background_color',
		'std' => '#E9F0F4',
		'class' => "hide background_color",
		'type' => 'color' );


	$options[] = array(
		'name' => __('Facebook App Id', 'bluth_admin'),
		'desc' => __('Insert you Facebook app id here. If you don\'t have one for your webpage you can create it <a target="_blank" href="https://developers.facebook.com/apps">here</a>', 'bluth_admin'),
		'id' => 'facebook_app_id',
		'type' => 'text');

	$options[] = array(
		'name' => __('Enable Facebook comments for posts', 'bluth_admin'),
		'desc' => __('Check this to use Facebook comments instead of regular wordpress comments for posts. Requires a Facebook app id in the field above.', 'bluth_admin'),
		'id' => 'facebook_comments',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable Responsive CSS', 'bluth_admin'),
		'desc' => __('Check this to disable responsive css. Responsive css enable the webpage to adapt to every screen size allowing mobile users to browse the website more easily.', 'bluth_admin'),
		'id' => 'disable_responsive',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __("Blog Setup", 'bluth_admin'),
		'desc' => __("Select the blog setup you want, left sidebar, right sidebar or no sidebar. Default: Right sidebar", 'bluth_admin'),
		'id' => "side_bar",
		'std' => "right_side",
		'type' => "images",
		'options' => array(
			'single' => $imagepath . '1col.png',
			'left_side' => $imagepath . '2cl.png',
			'right_side' => $imagepath . '2cr.png',
			'both_side' => $imagepath . '2cb.png')
	);

	$options[] = array(
		'name' => __('Blog Layout', 'bluth_admin'),
		'desc' => __('Select the default blog layout.', 'bluth_admin'),
		'id' => 'blog_layout',
		'std' => 'margin',
		'type' => 'images',
		'options' => array(
			'margin' => $imagepath . 'blm.png',
			'twocolumn' => $imagepath . 'bl2.png',
			'threecolumn' => $imagepath . 'bl3.png',
		));

	$options[] = array(
		'name' => __('Right-to-Left Language', 'bluth_admin'),
		'desc' => __('Check this if your language is written in a Right-to-Left direction', 'bluth_admin'),
		'id' => 'enable_rtl',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Footer text', 'bluth_admin'),
		'desc' => __('{year} will be replaced with the current year', 'bluth_admin'),
		'id' => 'footer_text',
		'std' => 'Copyright {year} · Theme design by bluthemes · <a href="http://www.bluthemes.com">www.bluthemes.com</a>',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Google Analytics', 'bluth_admin'),
		'desc' => __('Add your Google Analytics tracking code here. Google Analytics is a free web analytics service more info here: <a href="http://www.google.com/analytics/">Google Analytics</a>', 'bluth_admin'),
		'id' => 'google_analytics',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Featured Tag', 'bluth_admin'),
		'desc' => __('The tag that the featured posts widget will use to fetch posts', 'bluth_admin'),
		'id' => 'featured_tag',
		'std' => 'featured',
		'type' => 'text');

	$options[] = array(
		'name' => __('SEO plugin support', 'bluth_admin'),
		'desc' => __('Check this to give an SEO plugin control of meta description, title and open graph tags.', 'bluth_admin'),
		'id' => 'seo_plugin',
		'std' => '0',
		'type' => 'checkbox');


	$options[] = array(
		'name' => __('Header & Menu', 'bluth_admin'),
		'type' => 'heading');


	$options[] = array(
		'name' => __('Header Type', 'bluth_admin'),
		'desc' => __('Choose your header type', 'bluth_admin'),
		'id' => "header_type",
		'std' => "header_normal",
		'type' => "images",
		'options' => array(
			'header_normal' => $imagepath . 'headerlayout1.png',
			'header_big_logo' => $imagepath . 'headerlayout2.png'
		));


	$options[] = array(
		'name' => __('Header Background', 'bluth_admin'),
		'desc' => __('Choose your header background, to choose a color go to the Colors & Fonts page.', 'bluth_admin'),
		'id' => 'header_background',
		'std' => 'color',
		'type' => 'radio',
		'options' => array(
			'color' 	=> __('Color', 'bluth_admin'),
			'image' 	=> __('Image', 'bluth_admin')
		));

	$options[] = array(
		'name' => __('Header Background Image', 'bluth_admin'),
		'desc' => __('Upload your header background image here', 'bluth_admin'),
		'id' => 'header_background_image',
		'class' => 'header_background_image hide',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Logo', 'bluth_admin'),
		'desc' => __('Upload your logo here. Remove the image to show the name of the website in text instead. (Recommended: x90 height for normal heading)', 'bluth_admin'),
		'id' => 'logo',
		'type' => 'upload');
	
	$options[] = array(
		'name' => __('Mini Logo', 'bluth_admin'),
		'desc' => __('Upload your mini logo here. Logo that appears in the header when the user scrolls down on your website.', 'bluth_admin'),
		'id' => 'minilogo',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Enable menu hover', 'bluth_admin'),
		'desc' => __('Check this to show the menus sub-items when hovered over the top item.', 'bluth_admin'),
		'id' => 'menu_hover',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable header description', 'bluth_admin'),
		'desc' => __('Check this to disable the description showing up in the header.', 'bluth_admin'),
		'id' => 'disable_description',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable Sticky Header', 'bluth_admin'),
		'desc' => __('Check this to disable the sticky header feature. (The header won\'t stay fixed at the top of the window when you scroll down)', 'bluth_admin'),
		'id' => 'disable_fixed_header',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Show search in header', 'bluth_admin'),
		'desc' => __('Uncheck this to remove the search input from the header', 'bluth_admin'),
		'id' => 'show_search_header',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Posts & Pages', 'bluth_admin'),
		'type' => 'heading');


	$options[] = array(
		'name' => __("Post Setup", 'bluth_admin'),
		'desc' => __("Select the default post setup you want, left sidebar, right sidebar or no sidebar. Default: Right sidebar", 'bluth_admin'),
		'id' => "post_side_bar",
		'std' => "right_side",
		'type' => "images",
		'options' => array(
			'single' => $imagepath . '1col.png',
			'left_side' => $imagepath . '2cl.png',
			'right_side' => $imagepath . '2cr.png',
			'both_side' => $imagepath . '2cb.png')
	);

	$options[] = array(
		'name' => __('Enable soft border on all boxed areas', 'bluth_admin'),
		'desc' => __('Check this to enable a soft grey border on all boxed areas ( posts, pages, sidebars etc.).', 'bluth_admin'),
		'id' => 'enable_border',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable cropping of featured images', 'bluth_admin'),
		'desc' => __('Check this to disable cropped images, so they appear as they are in the post.', 'bluth_admin'),
		'id' => 'disable_crop',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Enable page comments', 'bluth_admin'),
		'desc' => __('Check this to enable comments on all pages as well as posts.', 'bluth_admin'),
		'id' => 'enable_page_comments',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Show year in post date', 'bluth_admin'),
		'desc' => __('Check this to display the year of the post below the post title.', 'bluth_admin'),
		'id' => 'enable_show_year',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable share buttons at the bottom of posts', 'bluth_admin'),
		'desc' => __('Check this to remove the "Share" button in the post footer.', 'bluth_admin'),
		'id' => 'disable_share_story',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Show share buttons on:', 'bluth_admin'),
		'desc' => __('Where do you want the share buttons to appear?', 'bluth_admin'),
		'id' => 'share_buttons_position',
		'class' => 'disable_share_story',
		'std' => array(
			'pages' 	=> '0',
			'single' 	=> '1',
			'blog' 		=> '0'
		),
		'type' => 'multicheck',
		'options' => array(
			'pages' 	=> __('Pages', 'bluth_admin'),
			'single' 	=> __('Posts', 'bluth_admin'),
			'blog' 		=> __('Front page', 'bluth_admin')));

	$options[] = array(
		'name' => __('Disable share buttons:', 'bluth_admin'),
		'desc' => __('Check to disable the specific share button', 'bluth_admin'),
		'id' => 'share_buttons_disabled',
		'class' => 'disable_share_story',
		'std' => array(
			'facebook' 	=> '0',
			'googleplus'=> '0',
			'twitter'	=> '0',
			'pinterest'	=> '0',
			'reddit'	=> '0',
			'linkedin'	=> '0',
			'delicious'	=> '0',
			'email' 	=> '0',
		),
		'type' => 'multicheck',
		'options' => array(
			'facebook' 	=> 'facebook',
			'googleplus'=> 'googleplus',
			'twitter'	=> 'twitter',
			'pinterest'	=> 'pinterest',
			'reddit'	=> 'reddit',
			'linkedin'	=> 'linkedin',
			'delicious'	=> 'delicious',
			'email' 	=> 'email'));

	$options[] = array(
		'name' => __('Disable the tags for posts', 'bluth_admin'),
		'desc' => __('Check this to remove the post tags on all posts', 'bluth_admin'),
		'id' => 'disable_footer_post',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable next/previous buttons', 'bluth_admin'),
		'desc' => __('Check this to remove the Next/Previous buttons at the bottom of each post.', 'bluth_admin'),
		'id' => 'disable_pagination',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable Related Posts', 'bluth_admin'),
		'desc' => __('Related articles are show below each post when you view it. Check this to disable that feature.', 'bluth_admin'),
		'id' => 'disable_related_posts',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Enable posts excerpt (post summary)', 'bluth_admin'),
		'desc' => __('Check this to only show the post excerpt or the summary of a post in the browse page. The default behavior is to show the whole post but you can provide a cut-off point by adding the <a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank">More</a> tag.', 'bluth_admin'),
		'id' => 'enable_excerpt',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Exerpt Length', 'bluth_admin'),
		'desc' => __('How many words would you like to show in the post summary. Default: 55 words', 'bluth_admin'),
		'id' => 'excerpt_length',
		'std' => '55',
		'class' => 'hide',
		'type' => 'text');

	$options[] = array(
		'name' => __('Show Continue Reading link', 'bluth_admin'),
		'desc' => __('Uncheck this to hide the Continue Reading link that appears below the post conent.', 'bluth_admin'),
		'id' => 'show_continue_reading',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Open featured post images in lightbox', 'bluth_admin'),
		'desc' => __('Check this to open featured post images in a lightbox instead of linking to the post itself.', 'bluth_admin'),
		'id' => 'post_image_lightbox',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Standard Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the standard post type. (Default: icon-calendar-3)', 'bluth_admin'),
		'id' => 'standard_icon',
		'std' => 'icon-calendar-3',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Standard Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the standard post icon and links', 'bluth_admin'),
		'id' => 'standard_post_color',
		'std' => '#556270',
		'class' => 'header_art_icon',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Gallery Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the gallery post type. (Default: icon-picture)', 'bluth_admin'),
		'id' => 'gallery_icon',
		'std' => 'icon-picture',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Gallery Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the gallery post icon and links', 'bluth_admin'),
		'id' => 'gallery_post_color',
		'std' => '#4ECDC4',
		'class' => 'header_art_icon',
		'type' => 'color' );
	
	$options[] = array(
		'name' => __('Image Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the image post type. (Default: icon-picture-1)', 'bluth_admin'),
		'id' => 'image_icon',
		'std' => 'icon-picture-1',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Image Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the image post icon and links', 'bluth_admin'),
		'id' => 'image_post_color',
		'std' => '#C7F464',
		'class' => 'header_art_icon',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Link Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the link post type. (Default: icon-link)', 'bluth_admin'),
		'id' => 'link_icon',
		'std' => 'icon-link',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Link Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the link post icon and links', 'bluth_admin'),
		'id' => 'link_post_color',
		'std' => '#FF6B6B',
		'class' => 'header_art_icon',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Quote Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the quote post type. (Default: icon-quote-left)', 'bluth_admin'),
		'id' => 'quote_icon',
		'std' => 'icon-quote-left',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Quote Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the quote post icon and links', 'bluth_admin'),
		'id' => 'quote_post_color',
		'std' => '#C44D58',
		'class' => 'header_art_icon',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Audio Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the audio post type. (Default: icon-volume-up)', 'bluth_admin'),
		'id' => 'audio_icon',
		'std' => 'icon-volume-up',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Audio Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the audio post icon and links', 'bluth_admin'),
		'id' => 'audio_post_color',
		'std' => '#5EBCF2',
		'class' => 'header_art_icon',
		'type' => 'color' );	

	$options[] = array(
		'name' => __('Video Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the video post type. (Default: icon-videocam)', 'bluth_admin'),
		'id' => 'video_icon',
		'std' => 'icon-videocam',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Video Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the video post icon and links', 'bluth_admin'),
		'id' => 'video_post_color',
		'std' => '#A576F7',
		'class' => 'header_art_icon',
		'type' => 'color' );	

	$options[] = array(
		'name' => __('Status Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the status post type. (Default: icon-book-1)', 'bluth_admin'),
		'id' => 'status_icon',
		'std' => 'icon-book-1',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Status Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the status post icon and links', 'bluth_admin'),
		'id' => 'status_post_color',
		'std' => '#556270',
		'class' => 'header_art_icon',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Facebook Status Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the facebook status post type. (Default: icon-facebook-1)', 'bluth_admin'),
		'id' => 'facebook_status_icon',
		'std' => 'icon-facebook-1',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Twitter Status Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the twitter status post type. (Default: icon-twitter-1)', 'bluth_admin'),
		'id' => 'twitter_status_icon',
		'std' => 'icon-twitter-1',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Google+ Status Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for the google status post type. (Default: icon-gplus-2)', 'bluth_admin'),
		'id' => 'google_status_icon',
		'std' => 'icon-gplus-2',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Sticky Post Icon', 'bluth_admin'),
		'desc' => __('Select an icon for sticky posts. (Default: icon-pin)', 'bluth_admin'),
		'id' => 'sticky_icon',
		'std' => 'icon-pin',
		'class' => 'header_art_icon post_icon_edit',
		'type' => 'text');

	$options[] = array(
		'name' => __('Sticky Post Color', 'bluth_admin'),
		'desc' => __('Select the color for the sticky post icon and links', 'bluth_admin'),
		'id' => 'sticky_post_color',
		'std' => '#90DB91',
		'class' => 'header_art_icon',
		'type' => 'color' );		



	
	$options[] = array(
		'name' => __('Users', 'bluth_admin'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Show author on front page', 'bluth_admin'),
		'desc' => __('Check this to show the author on the front page in the title area.', 'bluth_admin'),
		'id' => 'author_front',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Enable author box', 'bluth_admin'),
		'desc' => __('Uncheck this to remove the author box below each post.', 'bluth_admin'),
		'id' => 'author_box',
		'std' => '1',
		'type' => 'checkbox');
	
	$users = get_users( array('who' => 'authors') );
	
	foreach($users as $user){

			$options[] = array(
				'name' => __('User: '.$user->user_nicename, 'bluth_admin'),
				'type' => 'info');

			$options[] = array(
				'name' => __('Author Cover for '.$user->user_nicename, 'bluth_admin'),
				'desc' => __('Upload a cover for the author box', 'bluth_admin'),
				'id' => 'author_box_image_'.$user->ID,
				'type' => 'upload');

			$options[] = array(
				'name' => __('Author Box Avatar for '.$user->user_nicename, 'bluth_admin'),
				'desc' => __('Upload a custom avatar for the author box (will use gravatar if nothing is set) (120x120)', 'bluth_admin'),
				'id' => 'author_box_avatar_'.$user->ID,
				'type' => 'upload');

			$options[] = array(
				'name' => __('Twitter Username', 'bluth_admin'),
				'desc' => __('Twitter Username for this user (to use with twitter widgets)', 'bluth_admin'),
				'id' => 'author_twitter_username_'.$user->ID,
				'std' => '',
				'type' => 'text');

			$options[] = array(
				'name' => __($user->user_nicename.' Google+ profile link', 'bluth_admin'),
				'desc' => __('Google+ profile link, needed for Google Authorship Verification', 'bluth_admin'),
				'id' => 'social_google_'.$user->ID,
				'std' => '',
				'type' => 'text');
		
	}

	$options[] = array(
		'name' => __('Colors & Fonts', 'bluth_admin'),
		'type' => 'heading');


	$options[] = array(
		'name' => __('Color Theme', 'bluth_admin'),
		'desc' => __('Choose a predefined color theme', 'bluth_admin'),
		'id' => 'predefined_theme',
		'std' => 'default',
		'type' => 'images',
		'options' => array(
			'default' => $imagepath . '/colorthemes/default.png',
			'blue' => $imagepath . '/colorthemes/blue.png',
			'orange' => $imagepath . '/colorthemes/orange.png',
			'green' => $imagepath . '/colorthemes/green.png',
		));

	$options[] = array(
		'name' => __('Disable link animations', 'bluth_admin'),
		'desc' => __('Check this to remove the link animations for each post.', 'bluth_admin'),
		'id' => 'disable_link_animation',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Disable background for links in posts and pages', 'bluth_admin'),
		'desc' => __('Check this to disable the background for links and instead use the color on the text.', 'bluth_admin'),
		'id' => 'disable_link_background',
		'std' => '0',
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => __('Custom Color Theme', 'bluth_admin'),
		'desc' => __('Check this to make your own color theme', 'bluth_admin'),
		'id' => 'custom_color_picker',
		'std' => '0',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Main Theme Color', 'bluth_admin'),
		'desc' => __('Select the theme\'s main color', 'bluth_admin'),
		'id' => 'theme_color',
		'class' => 'hide custom_color',
		'std' => '#45b0ee',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Top Banner Color', 'bluth_admin'),
		'desc' => __('Select the color for the top header that includes the logo', 'bluth_admin'),
		'id' => 'top_banner_color',
		'class' => 'hide custom_color',
		'std' => '#ffffff',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Top Banner Font Color', 'bluth_admin'),
		'desc' => __('Select the color for the top header that includes the logo', 'bluth_admin'),
		'id' => 'top_banner_font_color',
		'class' => 'hide custom_color',
		'std' => '#999999',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Top Banner Social Buttons', 'bluth_admin'),
		'desc' => __('Select the color for the social buttons in the top banner', 'bluth_admin'),
		'id' => 'top_banner_social_color',
		'class' => 'hide custom_color',
		'std' => '#ffffff',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Top Header Color', 'bluth_admin'),
		'desc' => __('Select the color for the top header that includes the menu', 'bluth_admin'),
		'id' => 'header_color',
		'class' => 'hide custom_color',
		'std' => '#ffffff',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Top Header Font Color', 'bluth_admin'),
		'desc' => __('Select the color for the top header menu links', 'bluth_admin'),
		'id' => 'header_font_color',
		'class' => 'hide custom_color',
		'std' => '#333333',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Post Header Color', 'bluth_admin'),
		'desc' => __('Select the color for the top header of each post', 'bluth_admin'),
		'id' => 'post_header_color',
		'class' => 'hide custom_color',
		'std' => '#444444',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Body Font Color', 'bluth_admin'),
		'desc' => __('Select the color for the font on each post', 'bluth_admin'),
		'id' => 'main_font_color',
		'class' => 'hide custom_color',
		'std' => '#333333',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Widget Header Color', 'bluth_admin'),
		'desc' => __('Select the default color for the top header of each widget', 'bluth_admin'),
		'id' => 'widget_header_color',
		'class' => 'hide custom_color',
		'std' => '#ffffff',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Widget Header Font Color', 'bluth_admin'),
		'desc' => __('Select the color for the heading font in each widget', 'bluth_admin'),
		'id' => 'widget_header_font_color',
		'class' => 'hide custom_color',
		'std' => '#717171',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Footer Color', 'bluth_admin'),
		'desc' => __('Select the default color for the footer', 'bluth_admin'),
		'id' => 'footer_color',
		'class' => 'hide custom_color',
		'std' => '#FFFFFF',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Footer Header Color', 'bluth_admin'),
		'desc' => __('Select the default color for the footer headers', 'bluth_admin'),
		'id' => 'footer_header_color',
		'class' => 'hide custom_color',
		'std' => '#333333',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Footer Font Color', 'bluth_admin'),
		'desc' => __('Select the default color for the footer font', 'bluth_admin'),
		'id' => 'footer_font_color',
		'class' => 'hide custom_color',
		'std' => '#333333',
		'type' => 'color' );


	$options[] = array(
		'name' => __('Heading font', 'bluth_admin'),
		'desc' => __('Select a font type for all heading', 'bluth_admin'),
		'id' => 'heading_font',
		'std' => 'Merriweather:400,400italic,700,900',
		'type' => 'text');

	$options[] = array(
		'name' => __('Main font', 'bluth_admin'),
		'desc' => __('Select a font type for normal text', 'bluth_admin'),
		'id' => 'text_font',
		'std' => 'Lato:400,700,400italic',
		'type' => 'text');

	$options[] = array(
		'name' => __('Main font size', 'bluth_admin'),
		'desc' => __('The size of the text in posts', 'bluth_admin'),
		'id' => 'text_font_size',
		'std' => '18px',
		'type' => 'select',
		'options' => array(
			'12px' => '12px',
			'14px' => '14px',
			'16px' => '16px',
			'18px' => '18px',
			'20px' => '20px',
			'22px' => '22px',
			'24px' => '24px',
		));

	$options[] = array(
		'name' => __('Main font line height', 'bluth_admin'),
		'desc' => __('The spacing between each line of the text in posts', 'bluth_admin'),
		'id' => 'text_font_spacing',
		'std' => '2',
		'type' => 'select',
		'options' => array(
			'1.5' 	=> '1.5',
			'1.6' 	=> '1.6',
			'1.7' 	=> '1.7',
			'1.8' 	=> '1.8',
			'1.9' 	=> '1.9',
			'2' 	=> '2',
			'2.1' 	=> '2.1',
			'2.2' 	=> '2.2',
			'2.3' 	=> '2.3',
			'2.4' 	=> '2.4',
			'2.5' 	=> '2.5',
		));

	$options[] = array(
		'name' => __('Menu links font', 'bluth_admin'),
		'desc' => __('Select a font type for the menu items in the header', 'bluth_admin'),
		'id' => 'menu_font',
		'std' => 'Lato:400,700,400italic',
		'type' => 'text');

	$options[] = array(
		'name' => __('Brand font', 'bluth_admin'),
		'desc' => __('Select a font type for the brand. If you use text instead of a logo in your header this setting changes the font family for that text.', 'bluth_admin'),
		'id' => 'brand_font',
		'std' => 'Merriweather:400,400italic,700,900',
		'type' => 'text');





	$options[] = array(
		'name' => __('Advertising', 'bluth_admin'),
		'type' => 'heading');


	$options[] = array(
		'name' => __('Google Publisher ID', 'bluth_admin'),
		'desc' => __('Found in the top right corner of your <a href="https://www.google.com/adsense/" target="_blank">adsense account</a>.', 'bluth_admin'),
		'id' => 'google_publisher_id',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Google Ad unit ID', 'bluth_admin'),
		'desc' => __('Found in your Ad Units area under <strong>ID</strong> <a href="https://www.google.com/adsense/app#myads-springboard" target="_blank">here</a>.', 'bluth_admin'),
		'id' => 'google_ad_unit_id',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Ad spot #1 - Above the header.', 'bluth_admin'),
		'desc' => __('Select what kind of ad you want added above the top menu. <a target="_blank" href="http://bluth.is/wordpress/bliss/wp-content/uploads/2013/08/adspace_top.jpg">See Example</a>', 'bluth_admin'),
		'id' => 'ad_header_mode',
		'std' => 'none',
		'type' => 'radio',
		'options' => array(
			'none' => __('None', 'bluth_admin'),
			'html' => __('Shortcode or HTML code like Adsense', 'bluth_admin'),
			'image' => __('Image with a link', 'bluth_admin'),
			'google_ads' => __('Google Ads', 'bluth_admin')
		));

	$options[] = array(
		'name' => __('Add Shortcode or HTML code here', 'bluth_admin'),
		'desc' => __('Insert a shortcode provided by this theme or any plugin. You can also add advertising code from any provider or use plain html. To add Adsense just paste the embed code here that they provide and save.', 'bluth_admin'),
		'id' => 'ad_header_code',
		'class' => 'hide ad_header_code',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Upload Image', 'bluth_admin'),
		'desc' => __('Upload an image to add above the header menu and add a link for it in the input box below', 'bluth_admin'),
		'id' => 'ad_header_image',
		'class' => 'hide ad_header_image',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image link', 'bluth_admin'),
		'desc' => __('Add a link to the image', 'bluth_admin'),
		'id' => 'ad_header_image_link',
		'class' => 'hide ad_header_image',
		'std' => '',
		'type' => 'text');


	$options[] = array(
		'name' => __('Ad spot #2 - Between posts', 'bluth_admin'),
		'desc' => __('Here you can add advertising between posts. <a target="_blank" href="http://bluth.is/wordpress/bliss/wp-content/uploads/2013/08/adspace_between.jpg">See Example</a>', 'bluth_admin'),
		'id' => 'ad_posts_mode',
		'std' => 'none',
		'type' => 'radio',
		'options' => array(
			'none' => __('None', 'bluth_admin'),
			'html' => __('Shortcode or HTML code like Adsense', 'bluth_admin'),
			'image' => __('Image with a link', 'bluth_admin'),
			'google_ads' => __('Google Ads', 'bluth_admin')
		));

	$options[] = array(
		'name' => __('Add Shortcode or HTML code here', 'bluth_admin'),
		'desc' => __('Insert a shortcode provided by this theme or any plugin. You can also add advertising code from any provider or use plain html. To add Adsense just paste the embed code here that they provide and save.', 'bluth_admin'),
		'id' => 'ad_posts_code',
		'class' => 'hide ad_posts_code',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Upload Image', 'bluth_admin'),
		'desc' => __('Upload an image to add between posts and add a link for it in the input box below', 'bluth_admin'),
		'id' => 'ad_posts_image',
		'class' => 'hide ad_posts_image',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image link', 'bluth_admin'),
		'desc' => __('Add a link to the image', 'bluth_admin'),
		'id' => 'ad_posts_image_link',
		'class' => 'hide ad_posts_image',
		'std' => '',
		'type' => 'text');	

	$options[] = array(
		'name' => __('Display Frequency', 'bluth_admin'),
		'desc' => __('How often do you want the ad to appear?', 'bluth_admin'),
		'id' => 'ad_posts_frequency',
		'std' => 'one',
		'type' => 'select',
		'class' => 'mini hide ad_posts_options', //mini, tiny, small
		'options' => array(
			'1' => __('Between every post', 'bluth_admin'),
			'2' => __('Every 2th posts', 'bluth_admin'),
			'3' => __('Every 3th post', 'bluth_admin'),
			'4' => __('Every 4th post', 'bluth_admin'),
			'5' => __('Every 5th post', 'bluth_admin')
		));

	$options[] = array(
		'name' => __('Add white background', 'bluth_admin'),
		'desc' => __('Check this to wrap the ad content in a white box', 'bluth_admin'),
		'id' => 'ad_posts_box',
		'std' => '1',
		'class' => 'hide ad_posts_options',
		'type' => 'checkbox');



	$options[] = array(
		'name' => __('Ad spot #3 - Above the content.', 'bluth_admin'),
		'desc' => __('Select what kind of ad you want added above the main container. <a target="_blank" href="http://bluth.is/wordpress/bliss/wp-content/uploads/2013/08/adspace_above_content.jpg">See Example</a>', 'bluth_admin'),
		'id' => 'ad_content_mode',
		'std' => 'none',
		'type' => 'radio',
		'options' => array(
			'none' => __('None', 'bluth_admin'),
			'html' => __('Shortcode or HTML code like Adsense', 'bluth_admin'),
			'image' => __('Image with a link', 'bluth_admin'),
			'google_ads' => __('Google Ads', 'bluth_admin')
		));

	$options[] = array(
		'name' => __('Add Shortcode or HTML code here', 'bluth_admin'),
		'desc' => __('Insert a shortcode provided by this theme or any plugin. You can also add advertising code from any provider or use plain html. To add Adsense just paste the embed code here that they provide and save.', 'bluth_admin'),
		'id' => 'ad_content_code',
		'class' => 'hide ad_content_code',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Upload Image', 'bluth_admin'),
		'desc' => __('Upload an image to add above the header menu and add a link for it in the input box below', 'bluth_admin'),
		'id' => 'ad_content_image',
		'class' => 'hide ad_content_image',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image link', 'bluth_admin'),
		'desc' => __('Add a link to the image', 'bluth_admin'),
		'id' => 'ad_content_image_link',
		'class' => 'hide ad_content_image',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Add white background', 'bluth_admin'),
		'desc' => __('Check this to wrap the ad content in a white box', 'bluth_admin'),
		'id' => 'ad_content_box',
		'std' => '1',
		'class' => 'hide ad_content_options',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Add padding', 'bluth_admin'),
		'desc' => __('Add padding to the banner container', 'bluth_admin'),
		'id' => 'ad_content_padding',
		'class' => 'hide ad_content_options',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Banner placement', 'bluth_admin'),
		'desc' => __('Where do you want the banner to appear?', 'bluth_admin'),
		'id' => 'ad_content_placement',
		'class' => 'hide ad_content_options',
		'std' => array(
			'home' => '1',
			'pages' => '1',
			'posts' => '1'
		),
		'type' => 'multicheck',
		'options' => array(
			'home' => __('Frontpage', 'bluth_admin'),
			'pages' => __('Pages', 'bluth_admin'),
			'posts' => __('Posts', 'bluth_admin')
		));




	$options[] = array(
		'name' => __('Ad spot #4 - Below Each Post.', 'bluth_admin'),
		'desc' => __('Select what kind of ad you want added below each post.', 'bluth_admin'),
		'id' => 'ad_below_post_mode',
		'std' => 'none',
		'type' => 'radio',
		'options' => array(
			'none' => __('None', 'bluth_admin'),
			'html' => __('Shortcode or HTML code like Adsense', 'bluth_admin'),
			'image' => __('Image with a link', 'bluth_admin'),
			'google_ads' => __('Google Ads', 'bluth_admin')
		));

	$options[] = array(
		'name' => __('Add Shortcode or HTML code here', 'bluth_admin'),
		'desc' => __('Insert a shortcode provided by this theme or any plugin. You can also add advertising code from any provider or use plain html. To add Adsense just paste the embed code here that they provide and save.', 'bluth_admin'),
		'id' => 'ad_below_post_code',
		'class' => 'hide ad_below_post_code',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Upload Image', 'bluth_admin'),
		'desc' => __('Upload an image to add above the header menu and add a link for it in the input box below', 'bluth_admin'),
		'id' => 'ad_below_post_image',
		'class' => 'hide ad_below_post_image',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image link', 'bluth_admin'),
		'desc' => __('Add a link to the image', 'bluth_admin'),
		'id' => 'ad_below_post_image_link',
		'class' => 'hide ad_below_post_image',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Add white background', 'bluth_admin'),
		'desc' => __('Check this to wrap the ad content in a white box', 'bluth_admin'),
		'id' => 'ad_below_post_box',
		'std' => '1',
		'class' => 'hide ad_below_post_options',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Add padding', 'bluth_admin'),
		'desc' => __('Add padding to the banner container', 'bluth_admin'),
		'id' => 'ad_below_post_padding',
		'class' => 'hide ad_below_post_options',
		'std' => '1',
		'type' => 'checkbox');







	$options[] = array(
		'name' => __('Social', 'bluth_admin'),
		'type' => 'heading');

		$options[] = array(
			'name' => __('Twitter API Options', 'bluth_admin'),
			'type' => 'info');

			$options[] = array(
				'name' => '',
				'desc' => '',
				'id' => 'cust_not',
				'type' => 'twitter_notification');

			$options[] = array(
				'name' => __('Twitter API key', 'bluth_admin'),
				'desc' => __('Insert you twitter API key here', 'bluth_admin'),
				'id' => 'twitter_api_key',
				'type' => 'text');

			$options[] = array(
				'name' => __('Twitter API Secret', 'bluth_admin'),
				'desc' => __('Insert you twitter API secret here', 'bluth_admin'),
				'id' => 'twitter_api_secret',
				'type' => 'text');

			$options[] = array(
				'name' => __('Twitter Access Token', 'bluth_admin'),
				'desc' => __('Insert you twitter Access Token', 'bluth_admin'),
				'id' => 'twitter_access_token',
				'type' => 'text');

			$options[] = array(
				'name' => __('Twitter Access Token Secret', 'bluth_admin'),
				'desc' => __('Insert you twitter Access Token Secret here', 'bluth_admin'),
				'id' => 'twitter_access_token_secret',
				'type' => 'text');

	$options[] = array(
		'name' => __('Social Sharing image', 'bluth_admin'),
		'desc' => __('The image that gets shared if there is no image in the post (or if it\'s the front page).', 'bluth_admin'),
		'id' => 'social_share_image',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Facebook', 'bluth_admin'),
		'desc' => __('Your facebook link', 'bluth_admin'),
		'id' => 'social_facebook',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Twitter', 'bluth_admin'),
		'desc' => __('Your twitter link', 'bluth_admin'),
		'id' => 'social_twitter',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Google+', 'bluth_admin'),
		'desc' => __('Your google+ link', 'bluth_admin'),
		'id' => 'social_google',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('LinkedIn', 'bluth_admin'),
		'desc' => __('Your LinkedIn link', 'bluth_admin'),
		'id' => 'social_linkedin',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Youtube', 'bluth_admin'),
		'desc' => __('Your youtube link', 'bluth_admin'),
		'id' => 'social_youtube',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('RSS', 'bluth_admin'),
		'desc' => __('Your RSS feed', 'bluth_admin'),
		'id' => 'social_rss',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Flickr', 'bluth_admin'),
		'desc' => __('Your Flickr link', 'bluth_admin'),
		'id' => 'social_flickr',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Vimeo', 'bluth_admin'),
		'desc' => __('Your vimeo link', 'bluth_admin'),
		'id' => 'social_vimeo',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Pinterest', 'bluth_admin'),
		'desc' => __('Your pinterest link', 'bluth_admin'),
		'id' => 'social_pinterest',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Dribbble', 'bluth_admin'),
		'desc' => __('Your dribbble link', 'bluth_admin'),
		'id' => 'social_dribbble',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Tumblr', 'bluth_admin'),
		'desc' => __('Your tumblr link', 'bluth_admin'),
		'id' => 'social_tumblr',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Instagram', 'bluth_admin'),
		'desc' => __('Your instagram link', 'bluth_admin'),
		'id' => 'social_instagram',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Viadeo', 'bluth_admin'),
		'desc' => __('Your viadeo link', 'bluth_admin'),
		'id' => 'social_viadeo',
		'std' => '',
		'type' => 'text');

	$options[] = array(
	      'name' => __('XING', 'bluth'),
	      'desc' => __('Your XING link', 'bluth'),
	      'id' => 'social_xing',
	      'std' => '',
	      'type' => 'text'); 

	$options[] = array(
		'name' => __('Custom CSS', 'bluth_admin'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Add Custom CSS rules here', 'bluth_admin'),
		'desc' => __('Here you can overwrite specific css rules if you want to customize your theme a little. Write into this box just like you would do in a regular css file. Example: body{ color: #444; }', 'bluth_admin'),
		'id' => 'custom_css',
		'class' => 'custom_css',
		'std' => '',
		'type' => 'textarea');



	return $options;
}