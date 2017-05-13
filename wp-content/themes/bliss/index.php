<?php
/**
 * The template for displaying the home/index page.
 * This template will also be called in any case where the Wordpress engine 
 * doesn't know which template to use (e.g. 404 error)
 */

$layout = of_get_option('side_bar');
$layout = (empty($layout)) ? 'right_side' : $layout;
$ad_posts_mode = of_get_option('ad_posts_mode', 'none');
$ad_posts_frequency = of_get_option('ad_posts_frequency', 'none');
$ad_posts_box = of_get_option('ad_posts_box', true);

get_header(); ?>


<?php
	// Advert above content
	$ad_content_placement 	= of_get_option('ad_content_placement', array('home' => true,'pages' => true,'posts' => true));
	$ad_content_mode 		= of_get_option('ad_content_mode', 'none');
	$ad_content_box 		= of_get_option('ad_content_box', true);
	$ad_content_padding 		= of_get_option('ad_content_padding', true);

	if($ad_content_mode != 'none' and $ad_content_placement['home'] == true and is_home()){
		echo '<div class="above_content'.(($ad_content_box) ? ' box' : '').(($ad_content_padding) ? ' pad15' : '').'">';
			if($ad_content_mode == 'image'){
				echo '<a href="'.of_get_option('ad_content_image_link').'" target="_blank"><img src="'.of_get_option('ad_content_image').'"></a>';
			}elseif($ad_content_mode == 'html'){
				echo apply_filters('shortcode_filter',do_shortcode(of_get_option('ad_content_code')));
			}elseif($ad_content_mode == 'google_ads'){
				blu_get_google_ads(); 
			}
		echo '</div>';
	}
?>
	<div id="primary" class="row <?php echo $layout; ?>">
		
		<?php if($layout == 'both_side'){ ?>
		<aside id="side-bar" class="span3 widget-area">
				<?php dynamic_sidebar( 'sidebar_left' ); ?>
		</aside>
		<?php } ?>	

		<div id="content" class="<?php echo of_get_option('blog_layout'); ?> <?php echo ($layout == 'single') ? 'span10 offset1' : ($layout == 'both_side' ? 'span6' : 'span8'); ?>" role="main">
			<div class="row-fluid">
				<div id="above-blog" class="widget-area">
					<?php dynamic_sidebar( 'above_blog' ); ?>
				</div>
			</div>
			<div id="main_columns" class="columns">
				<?php 

				if( have_posts() ){ 
					$x = 1;
					while ( have_posts() ){
						the_post(); 
						get_template_part( 'inc/post-format/content', get_post_format() );
						// advertising between posts
						if($ad_posts_mode != 'none'){
							// take into account ad frequency
							if (($x % $ad_posts_frequency) == 0){

								switch ($ad_posts_mode) {
									case 'image':
										echo '<article class="'.(($ad_posts_box) ? 'box' : '').' between_posts"><a target="_blank" href="'.of_get_option('ad_posts_image_link').'"><img src="'.of_get_option('ad_posts_image').'"></a></article>';
									break;
									case 'html':
										echo '<article class="'.(($ad_posts_box) ? 'box' : '').' between_posts">'.apply_filters('shortcode_filter',do_shortcode(of_get_option('ad_posts_code'))).'</article>';
									break;
									case 'google_ads':
										blu_get_google_ads();
										echo '<br>'; 
									break;
								}
							}
						}
						$x++;
					}

				}else{ ?>
				<article class="type-page box">
					<h1 class="title"><?php _e('Post not found', 'bluth'); ?></h1>
					<div class="the-content">
					<p class="lead"><?php _e('We could not find that post you were looking for.', 'bluth'); ?></p>
					<br>
					<h3><?php _e('Try searching', 'bluth') ?></h3>
					<?php echo get_search_form(); ?>
					<?php get_template_part( 'inc/recent-posts' ); ?>				
					</div>
				</article>
				<?php } 

				// kriesi_pagination(); 

				?> 
			</div><!-- .columns --><?php kriesi_pagination(); ?>
		</div><!-- #content -->

		<?php if($layout == 'left_side'){ ?>
		<aside id="side-bar" class="span4 widget-area">
				<?php dynamic_sidebar( 'sidebar_left'); ?>
		</aside>
		<?php } ?>	
		<?php if($layout == 'right_side'){ ?>
		<aside id="side-bar" class="span4 widget-area">
				<?php dynamic_sidebar( 'sidebar_right' ); ?>
		</aside>
		<?php } ?>		
		<?php if($layout == 'both_side'){ ?>
		<aside id="side-bar" class="span3 widget-area">
				<?php dynamic_sidebar( 'sidebar_right' ); ?>
		</aside>
		<?php } ?>		
	</div><!-- #primary -->
<?php get_footer(); ?>