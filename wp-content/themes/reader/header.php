<?php 
$onepage = is_page_template('template-one-page.php');

$feedburner = esc_attr(r_option('feedburner'));

if(!empty($feedburner)){
	$feed_url = esc_url(r_option('feedburner'));
}else{
	$feed_url = esc_url(get_bloginfo('rss2_url'));
}
if(r_option('word_limit')){
	r_word_limit();
}
if(!$onepage){
$body_id = 'header-top';
}else{
$body_id = 'header-normal';
}
$logo_img = r_option('logo_img');
if ($logo_img =="") {
$logo_img = get_template_directory_uri().'/images/logo.png';
}
$logotext = r_option('logo_text');
if($logotext == ''){
//$logotext = get_bloginfo('name');
}
if(r_option('style_page')){
$stylepage = is_page()&&!is_home();
}else{
$stylepage = false;
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>

    <!-- TITLE OF SITE -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="description" content="<?php bloginfo('description');?>"/>


	<?php if(r_option('responsive_mode')): ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<?php endif; ?>

    <!-- =========================
      FAV AND TOUCH ICONS  
    ============================== -->
	<?php
	$favicon = r_option('favicon');
	$favicon_iphone = r_option('favicon_iphone');
	$favicon_iphone_retina = r_option('favicon_iphone_retina');
	$favicon_ipad = r_option('favicon_ipad');
	$favicon_ipad_retina = r_option('favicon_ipad_retina');
	?>
	<?php if (!empty($favicon)) : ?>
	<link rel="shortcut icon" href="<?php echo esc_url($favicon); ?>" />
	<?php endif; ?>
	<?php if (!empty($favicon_iphone)) : ?>
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo esc_url($favicon_iphone); ?>" />
	<?php endif; ?>
	<?php if (!empty($favicon_iphone_retina)) : ?>
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo esc_url($favicon_iphone_retina); ?>" />
	<?php endif; ?>
	<?php if (!empty($favicon_ipad)) : ?>
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo esc_url($favicon_ipad); ?>" />
	<?php endif; ?>
	<?php if (!empty($favicon_ipad_retina)) : ?>
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo esc_url($favicon_ipad_retina); ?>" />
	<?php endif; ?>	

	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name')?>" href="<?php echo esc_url($feed_url) ?>" />
	<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name')?>" href="<?php bloginfo('atom_url');?>" />
    


<?php wp_head() ?>
</head>
<body id="<?php echo esc_attr($body_id) ?>" <?php body_class() ?>>

	<header class="header">
	
	<?php if(r_option('sidebar_s')=='right_s'||($stylepage)): ?>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="row">
                <div class="col-md-24">
                    
	<?php else: ?>
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-sm-24 logo_w">
	
	<?php endif; ?>
				
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only"><?php _e('Toggle navigation','reader') ?></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand <?php if($logotext=='') {echo'logo-img';} ?>" href="<?php echo home_url(); ?>/">
                            <!-- YOUR LOGO -->
                            <img src="<?php echo esc_url($logo_img); ?>" alt="<?php bloginfo('name'); ?>">
                            <h1><?php echo esc_attr($logotext) ?></h1>
                        </a>
                    </div>
                </div>
                <!-- Navigation Items -->
				<?php if(r_option('sidebar_s')=='right_s'||$stylepage): ?>
                <div class="col-md-24">
				<?php else: ?>
				<div class="col-md-14">
				<?php endif; ?>
                    <div class="collapse navbar-collapse main-navigation" id="bs-example-navbar-collapse-1">
                        <?php 
						$nav_args = array(
							'theme_location' => 'main-menu',
							'depth'             => 2,
							'container'         => '',
							'container_class'   => 'collapse navbar-collapse bs-navbar-collapse no-padding ',
							'container_id'      => 'topnav',
							'menu_class'        => 'nav navbar-nav uppercase',
							'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
							'walker'            => new wp_bootstrap_navwalker()
						);
						wp_nav_menu( $nav_args );

						?>

                        <!-- SEARCH -->
                        <form class="search-box navbar-form navbar-right" action="<?php echo home_url(); ?>" role="search">
                            <div class="input-group">
                                <input type="text" name="s" id="ss" class="form-control" placeholder="Click to Search">
                            </div><!-- /input-group -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /END FLUID CONTAINER -->
    </nav>
</header>