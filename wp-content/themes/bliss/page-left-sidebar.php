<?php 
	/* Template name: Page: Left sidebar */ 
	$hide_title = get_post_meta( $post->ID, 'bluth_page_hide_title', 'off' );
	$post_subtitle = get_post_meta( $post->ID, 'bluth_page_subtitle', 'off' );

	get_header(); 

	// Advert above content
	$ad_content_placement 	= of_get_option('ad_content_placement', array('home' => true,'pages' => true,'posts' => true));
	$ad_content_mode 		= of_get_option('ad_content_mode', 'none');
	$ad_content_box 		= of_get_option('ad_content_box', true);	
	$ad_content_padding 	= of_get_option('ad_content_padding', true);	

	if($ad_content_mode != 'none' and $ad_content_placement['pages'] == true){
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
	<div id="primary" class="row left_side">

		<aside id="side-bar" class="span4 widget-area">
				<?php dynamic_sidebar( 'sidebar_left'); ?>
		</aside>

		<div id="content" class="span8" role="main">

			<?php if(have_posts()){ 
			
				while ( have_posts() ) : the_post(); 
				?>
				<article class="type-page">
					<?php 
					if ( has_post_thumbnail() ) { ?>
						<div class="entry-image">
							<?php the_post_thumbnail('gallery-large');
							 
							if($hide_title != 'on'){ ?>
								<h1 class="title"><?php the_title(); echo '<small>'.$post_subtitle.'</small>'; ?></h1><?php
						 	} ?>
						</div><?php 
					}else{ 
						if($hide_title != 'on'){ ?>
							<h1 class="title"><?php the_title(); echo '<small>'.$post_subtitle.'</small>'; ?></h1><?php
					 	}
					} ?>
					<div class="the-content">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
						<footer class="entry-meta clearfix">
							<?php get_template_part( 'inc/meta-bottom' ); ?>
						</footer><!-- .entry-meta -->	
					</div><!-- the-content -->
				</article>
				<?php endwhile; ?>

			<?php }else{ ?>
				
			<article class="type-page box">
				<h1 class="title"><?php _e('Post not found', 'bluth'); ?></h1>
				<p class="lead"><?php _e('We could not find that post you were looking for.', 'bluth'); ?></p>
				<br>
				<h3><?php _e('Try searching', 'bluth') ?></h3>
				<?php echo get_search_form(); ?>
				<?php get_template_part( 'inc/recent-posts' ); ?>				
			</article>

			<?php } 
				// If comments are open or we have at least one comment, load up the default comment template provided by Wordpress
				if ( of_get_option('enable_page_comments') && (comments_open() || '0' != get_comments_number()) )
					comments_template( '', true );
			?>

		</div><!-- #content .site-content -->

	</div><!-- #primary .content-area -->
<?php get_footer(); ?>