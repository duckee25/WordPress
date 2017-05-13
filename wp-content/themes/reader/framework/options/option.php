<?php
$imagedir = get_template_directory_uri().'/images/';

if(r_option('logo_panel')!=''){
$logo_panel = r_option('logo_panel');
}else{
$logo_panel = $imagedir.'logo-panel.png';
}
return array(
	'title' => __('The Reader Option Panel', 'reader'),
	'logo' => $logo_panel,
	'menus' => array(
		array(
			'title' => __('General Settings', 'reader'),
			'name' => 'general',
			'icon' => 'font-awesome:fa-home',
			'menus' => array(
				array(
					'title' => __('Logo', 'reader'),
					'name' => 'logo',
					'icon' => 'font-awesome:fa-angle-right',
					'controls' => array(
						array(
							'type' => 'upload',
							'name' => 'logo_img',
							'label' => __('Logo', 'reader'),
							'description' => __('Upload your own logo .', 'reader'),
							'default' => $imagedir . 'logo.png',
						),array(
							'type' => 'textbox',
							'name' => 'logo_text',
							'label' => __('Logo Text', 'reader'),
							'description' => __('The text of the logo .', 'reader'),
							'default' => 'The Reader',
						),array(
							'type' => 'upload',
							'name' => 'logo_alt_retina',
							'label' => __('Alternative Logo Image (Retina Version)', 'reader'),
							'description' => __('Please name your file following the  (e.g. logo-alt.png) with a suffix @2x (e.g. logo-alt@2x.png)', 'reader'),
						),array(
							'type' => 'upload',
							'name' => 'logo_panel',
							'label' => __('Logo for this admin pagel', 'reader'),
							'description' => __('Logo for this admin pagel', 'reader'),
							'default' => $imagedir . 'logo-panel.png',
						),array(
							'type' => 'slider',
							'name' => 'logo_height',
							'label' => __('Logo height (in pixels)', 'reader'),
							'description' => __('Choose height for the Logo', 'reader'),
							'default' => 26,
						),
					),
				),
				array(
					'title' => __('General', 'reader'),
					'name' => 'general',
					'icon' => 'font-awesome:fa-angle-right',
					'controls' => array(						
						array(
							'type' => 'toggle',
							'name' => 'responsive_mode',
							'label' => __('Responsive/mobile design', 'reader'),
							'description' => __('Enable this to adapt your page to differents screen sizes(mobile, tablet, desktop, etc).', 'reader'),
							'default' => '1',
						),
						array(
							'type' => 'textbox',
							'name' => 'feedburner',
							'label' => __('Feedburner URL', 'reader'),
							'description' => __('Enter your full FeedBurner URL to replace the default feed URL by Wordpress.', 'reader'),
							'default' => '',
						),
						array(
							'type' => 'textarea',
							'name' => 'analyticscode',
							'label' => __('Analytics Code', 'reader'),					
							'default' => '',
						)
					),
				),
				
				array(
					'type' => 'section',
					'title' => __('Custom Fonts', 'reader'),
					'icon' => 'font-awesome:fa-angle-right',
					'controls' => array(
						array(
							'label' => __('FontFamily url (body)', 'reader'),
							'description' => __('Enter the url of the Google font you want. ej: http:&#47;&#47;fonts.googleapis.com/css?family=Roboto:400,300,600,700', 'reader'),
							'name'=>'font_family_url1',
							'type' => 'textbox',
							'default' => "http://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700"
						),array(
							'label' => __('FontFamily name (body)', 'reader'),
							'description' => __('Enter the declaration of your google font.', 'reader'),
							'name'=>'font_family_name1',
							'type' => 'textbox',
							'default' => "Raleway"
						)
					),
				),
				array(
					'type' => 'section',
					'title' => __('Site Favicon', 'reader'),
					'icon' => 'font-awesome:fa-angle-right',
					'controls' => array(
						array(
							'type' => 'upload',
							'name' => 'favicon',
							'description' => __('Recommended: .ICO 64x64px size', 'reader'),
							'label' => __('General Site Icon', 'reader'),
							'default' => $imagedir . 'favicon.ico',
						),
						array(
							'type' => 'upload',
							'name' => 'favicon_iphone',
							'description' => __('Recommended: .PNG 60x60px size', 'reader'),
							'label' => __('Icon for iPhone', 'reader'),
							'default' => $imagedir . 'apple-touch-icon.png',
						),
						array(
							'type' => 'upload',
							'name' => 'favicon_iphone_retina',
							'description' => __('Recommended: .PNG 120x120px size', 'reader'),
							'label' => __('Icon for iPhone Retina', 'reader'),
							'default' => $imagedir . 'apple-touch-icon-114x114.png',
						),
						array(
							'type' => 'upload',
							'name' => 'favicon_ipad',
							'description' => __('Recommended: .PNG 76x76px size', 'reader'),
							'label' => __('Icon for iPad', 'reader'),
							'default' => $imagedir . 'apple-touch-icon-72x72.png',
						),
						array(
							'type' => 'upload',
							'name' => 'favicon_ipad_retina',
							'description' => __('Recommended: .PNG 152x152px size', 'reader'),
							'label' => __('Icon for iPad Retina', 'reader'),
						),
					),
				),				
				array(
					'title' => __('Color Scheme', 'reader'),
					'name' => 'color_scheme',
					'icon' => 'font-awesome:fa-angle-right',
					'controls' => array(
						array(
							'type' => 'toggle',
							'name' => 'header_white',
							'label' => __('Enable a boxed style, with header white, and widget boxes', 'reader'),
							'description' => __('Enable a boxed style.', 'reader'),
							'default' => '0',
						),
						array(
							'type' => 'toggle',
							'name' => 'custom_color',
							'label' => __('Enable Custom Color', 'reader'),
							'description' => __('Set your custom color scheme.', 'reader'),
							'default' => '1',
						),array(
							'name'      => 'color_pri',
							'label'     => __('Select Color', 'reader'),
							'type' => 'select',
							'description' => __('Select Color Scheme', 'reader'),
							'items' => array(
								array(
									'value' => 'lime',
									'label' => __('lime', 'reader'),
								),array(
									'value' => 'alizarin',
									'label' => __('alizarin', 'reader'),
								),array(
									'value' => 'green-sea',
									'label' => __('green-sea', 'reader'),
								),array(
									'value' => 'nephritis',
									'label' => __('nephritis', 'reader'),
								),array(
									'value' => 'anethyst',
									'label' => __('anethyst', 'reader'),
								),array(
									'value' => 'peter-river',
									'label' => __('peter-river', 'reader'),
								)
							),
							'default' => array(
								'lime',
							),
						),
					),	
				),
				array(
					'title' => __('404 Page', 'reader'),
					'name' => '404page',
					'icon' => 'font-awesome:fa-angle-right',
					'controls' => array(
						array(
							'type' => 'textbox',
							'name' => '404_title',
							'label' => __('Page Not Found Title', 'reader'),
							'default' => 'PAGE NOT FOUND',
						),
					),	
				),
			),
		),
		
		array(
			'type' => 'section',
			'title' => __('Title Labels', 'reader'),
			'icon' => 'font-awesome:fa-credit-card',
			'controls' => array(
				array(
					'type' => 'textbox',
					'name' => 'label_blog',
					'label' => __('Blog label', 'reader'),
					'default' => 'OUR BLOG',
				),array(
					'type' => 'textbox',
					'name' => 'label_category',
					'description' => __('%s is for the category name', 'reader'),
					'label' => __('Post Category Archive Title', 'reader'),
					'default' => 'Posts in Category: %s',
				),
				array(
					'type' => 'textbox',
					'name' => 'label_tag',
					'description' => __('%s is for the tag name', 'reader'),
					'label' => __('Post Tag Archive Title', 'reader'),
					'default' => 'Posts Tagged Under: %s',
				),
				array(
					'type' => 'textbox',
					'name' => 'label_time_year',
					'description' => __('%s is for the year', 'reader'),
					'label' => __('Yearly Archive Title', 'reader'),
					'default' => 'Posts in: %s',
				),
				array(
					'type' => 'textbox',
					'name' => 'label_time_month',
					'description' => __('%s is for the month', 'reader'),
					'label' => __('Monthly Archive Title', 'reader'),
					'default' => 'Posts in: %s',
				),
				array(
					'type' => 'textbox',
					'name' => 'label_time_day',
					'description' => __('%s is for the day', 'reader'),
					'label' => __('Daily Archive Title', 'reader'),
					'default' => 'Posts in: %s',
				),
				array(
					'type' => 'textbox',
					'name' => 'label_search',
					'description' => __('First %s is for total results found, the second %s is for the query', 'reader'),
					'label' => __('Search Result Title', 'reader'),
					'default' => 'Search Results for: %s',
				),array(
					'type' => 'textbox',
					'name' => 'label_job',
					'description' => __('Label for Job Posts archive', 'reader'),
					'label' => __('Label for Job Posts archive', 'reader'),
					'default' => 'Reader\'s Job Board.',
				),array(
					'type' => 'textbox',
					'name' => 'read_more_label',
					'description' => __('Label for the button "Read More"', 'reader'),
					'label' => __('Label for the button "Read More"', 'reader'),
					'default' => 'Read More',
				)
				,array(
					'type' => 'textbox',
					'name' => 'jobboard_s',
					'description' => __('Overwrite "Job board" (after installed jobpostingdex plugin)', 'reader'),
					'label' => __('Overwrite "Job board" ', 'reader'),
					'default' => 'Job Post',
				)
				,array(
					'type' => 'textbox',
					'name' => 'jobboard_p',
					'description' => __('Overwrite "Job board " (plural - after installed jobpostingdex plugin)', 'reader'),
					'label' => __('Overwrite "Job board" (plural)', 'reader'),
					'default' => 'Job Posts',
				)
				,array(
					'type' => 'textbox',
					'name' => 'jobboard_slug',
					'description' => __('Overwrite "Job board " slug (after installed jobpostingdex plugin) *WITHOUT SPACES', 'reader'),
					'label' => __('Overwrite "Job board" slug', 'reader'),
					'default' => 'jobpost',
				)
			),
		),
		
		array(
			'type' => 'section',
			'title' => __('Blog/Archive Settings', 'reader'),
			'icon' => 'font-awesome:fa-file-text',
			'controls' => array(
				array(
					'name' => 'style_blog',
					'label' => __('Blog Style', 'reader'),
					'description' => __('Choose The blog style', 'reader'),
					'type' => 'radiobutton',
					'items' => array(
						array(
							'value' => 'classic',
							'label' => __('Classic timeline', 'reader'),
						),
						array(
							'value' => 'grid',
							'label' => __('Grid', 'reader'),
						)						
					),
					'default' => array(
						'grid',
					),
				),
				array(
					'name' => 'sidebar_s',
					'label' => __('Sidebar placement', 'reader'),
					'description' => __('Choose if you want to show a Sidebar at the right side, or two at both sides', 'reader'),
					'type' => 'radiobutton',
					'items' => array(
						array(
							'value' => 'both_s',
							'label' => __('Both sidebar', 'reader'),
						),
						array(
							'value' => 'right_s',
							'label' => __('Right', 'reader'),
						)						
					),
					'default' => array(
						'right_s',
					),
				),
				array(
					'type' => 'toggle',
					'name' => 'ajax_posts',
					'label' => __('Load Post with ajax', 'reader'),
					'description' => __('Load Post when scroll down, this option will disable the navigation', 'reader'),
					'default' => '1',
				),
				array(
					'name'=>'word_limit',
					'type' => 'textbox', 
					'label' => __('Word Limit Excerpt', 'reader'),
					'description' => __('Set a limit number of words for each excerpt in blog.', 'reader'),
					'default' => '130',
				),array(
					'name'=>'word_limit_grid',
					'type' => 'textbox', 
					'label' => __('Word Limit Excerpt (grid)', 'reader'),
					'description' => __('Set a limit number of words for each excerpt in blog (for grid style).', 'reader'),
					'default' => '50',
				),array(
					'type' => 'toggle',
					'name' => 'auto_thumbnail',
					'label' => __('Show Post Thumbnail automaticaly', 'reader'),
					'description' => __('Show Post Thumbnail automaticaly, if not, you can place your thumbnail using this shortcode []', 'reader'),
					'default' => '1',
				),array(
					'type' => 'toggle',
					'name' => 'uppertitle_onoff',
					'label' => __('Uppercase Title Post', 'reader'),
					'default' => '0',
				),array(
					'type' => 'textbox',
					'name' => 'n_load',
					'label' => __('Load Post with ajax(clasic)', 'reader'),
					'description' => __('if Load Post Ajax is enabled, set a limit to load posts', 'reader'),
					'default' => '3',
				),array(
					'type' => 'textbox',
					'name' => 'n_load_g',
					'label' => __('Load Post with ajax(grid)', 'reader'),
					'description' => __('If Load Post Ajax is enabled, set a limit to load posts', 'reader'),
					'default' => '4',
				),array(
					'type' => 'toggle',
					'name' => 'show_read_more',
					'label' => __('Show Read More Button in Classic Blog', 'reader'),
					'description' => __('turn on if you want to show the Read Button in the classic blog style.', 'reader'),
					'default' => '0',
				)
				
			),
		),
		
		array(
			'type' => 'section',
			'title' => __('Single Post', 'reader'),
			'icon' => 'font-awesome:fa-file-text-o',
			'controls' => array(
				array(
					'type' => 'toggle',
					'name' => 'uppertitle_onoff',
					'label' => __('Uppercase Title Post', 'reader'),
					'default' => '0',
				),				
				array(
					'name' => 'meta_posts',
					'label' => __('Meta Elements in post', 'reader'),
					'description' => __('Choose the meta elements to show in each posts', 'reader'),
					'type' => 'checkbox',
					'items' => array(
						array(
							'value' => 'user',
							'label' => __('User', 'reader'),
						),
						array(
							'value' => 'date',
							'label' => __('Date', 'reader'),
						),
						array(
							'value' => 'category',
							'label' => __('Category', 'reader'),
						),
						array(
							'value' => 'comments',
							'label' => __('Comments', 'reader'),
						),			
					),
					'default' => array(
						'user',
						'date',
						'category',
						'comments',
					),
				),array(
					'type' => 'toggle',
					'name' => 'show_bio',
					'label' => __('Show Biography in post', 'reader'),
					'default' => '1',
				)
			),
		),
		array(
			'title' => __('Pages', 'reader'),
			'name' => 'pages',
			'icon' => 'font-awesome:fa-code',
			'controls' => array(
				array(
					'type' => 'toggle',
					'name' => 'style_page',
					'label' => __('Centered Header for pages', 'reader'),
					'description' => __('turn on if you want the header in pages be centered; turn off and the page will look like the general design', 'reader'),
					'default' => '1',
				),array(
					'type' => 'toggle',
					'name' => 'sidebar_left_page',
					'label' => __('Show Left Sidebar in Page', 'reader'),
					'description' => __('turn on if you want to show the left sidebar in pages, turn off to show only the right sidebar', 'reader'),
					'default' => '0',
				),array(
					'type' => 'toggle',
					'name' => 'comments_page',
					'label' => __('Show Comments section in Page', 'reader'),
					'description' => __('turn on if you want to enable comments in pages.', 'reader'),
					'default' => '0',
				)
			),
		),
		
		
				
				
				
				
		array(
			'type' => 'section',
            'title' => __('Share Post', 'reader'),
            'icon' => 'font-awesome:fa-share-square-o',
            'controls' => array(
				array(
					'label' => __('Facebook', 'reader'),
					'name'=>'facebook_share',
					'type' => 'toggle', 
					"default" => 1,
				),array(
					'label' => __('Twitter', 'reader'),
					'name'=>'twitter_share',
					'type' => 'toggle', 
					"default" => 1,
				),array(
					'label' => __('Linkedin', 'reader'),
					'name'=>'linkedin_share',
					'type' => 'toggle', 
					"default" => 0,
				),array(
					'label' => __('Pinterest', 'reader'),
					'name'=>'pinterest_share',
					'type' => 'toggle', 
					"default" => 1,
				),array(
					'label' => __('GooglePlus', 'reader'),
					'name'=>'googlep_share',
					'type' => 'toggle', 
					"default" => 1,
				),array(
					'label' => __('Reddit', 'reader'),
					'name'=>'reddit_share',
					'type' => 'toggle', 
					"default" => 0,
				),array(
					'label' => __('Mail', 'reader'),
					'name'=>'mail_share',
					'type' => 'toggle', 
					"default" => 0,
				),array(
					'label' => __('Tumblr', 'reader'),
					'name'=>'tumblr_share',
					'type' => 'toggle', 
					"default" => 0,
				)
			),
		),

		array(
			'title' => __('Footer', 'reader'),
			'icon' => 'font-awesome:fa-building',
			'controls' => array(	
				array(
					'name'=>'copyrights',
					'type' => 'textarea', 
					'label' => __('Copyrights', 'reader'),
					'description' => __('', 'reader'),
					'default' => 'All Right Reserved &copy; <a href="#">One Touch</a>'
				),
				array(
					'label' => __('Footer Style', 'reader'), 
					'description' => __('Choose how you want to order your portfolio items', 'reader'),
					'name'=>'footer_style',
					'type' => 'select',
					'items' => array(
						array(
							'value' => 'bluish-dark',
							'label' => __('Dark', 'reader'),
						),
						array(
							'value' => 'light',
							'label' => __('Light', 'reader'),
						),
					),
					'default' => array(
						'light',
					),
				),
				array(
					'type' => 'slider',
					'name' => 'width_foo1',
					'label' => __('Width footer widget 1', 'reader'),
					'min' => '0',
					'max' => '24',
					'step' => '1',
					'default' => '9',
				),
				array(
					'type' => 'slider',
					'name' => 'width_foo2',
					'label' => __('Width footer widget 2', 'reader'),
					'min' => '0',
					'max' => '24',
					'step' => '1',
					'default' => '6',
				),
				array(
					'type' => 'slider',
					'name' => 'width_foo3',
					'label' => __('Width footer widget 3', 'reader'),
					'min' => '0',
					'max' => '24',
					'step' => '1',
					'default' => '9',
				),				
			),
		),
		array(
			'title' => __('Custom Codes', 'reader'),
			'name' => 'custom_codes',
			'icon' => 'font-awesome:fa-code',
			'controls' => array(
				array(
					'type' => 'codeeditor',
					'name' => 'custom_css',
					'label' => __('Custom CSS', 'reader'),
					'description' => __('Write your custom css here.', 'reader'),
					'theme' => 'github',
					'mode' => 'css',
				),
				array(
					'type' => 'codeeditor',
					'name' => 'custom_js',
					'label' => __('Custom JavaScript', 'reader'),
					'description' => __('Write your custom js here.', 'reader'),
					'theme' => 'twilight',
					'mode' => 'javascript',
				),
			),
		),
		
    ),

);
?>