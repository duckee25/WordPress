<?php
	/**
	 * The template for displaying any single post.
	 *
	 */

	// Count view
	wpb_set_post_views(get_the_ID());

	$layout = of_get_option('post_side_bar');
	$layout = (empty($layout)) ? 'right_side' : $layout;
	$post_layout = get_post_meta( $post->ID, 'bluth_post_layout', true );
	$right_sidebar = ( !get_post_meta( $post->ID, 'bluth_post_right_sidebar', true ) ) ? 'sidebar-right' : get_post_meta( $post->ID, 'bluth_post_right_sidebar', true );
	$left_sidebar = ( !get_post_meta( $post->ID, 'bluth_post_left_sidebar', true ) ) ? 'sidebar-left' : get_post_meta( $post->ID, 'bluth_post_left_sidebar', true );

	// set content width
	if($post_layout == 'single_column'){
		$content_class = 'span10 offset1';
	}
	else if($post_layout == 'both_side'){
		$content_class = 'span6';
	}
	else if($post_layout == 'right_side' || $post_layout == 'left_side'){
		$content_class = 'span8';
	}
	else{
		if($layout == 'single'){
			$content_class = 'span10 offset1';
		}
		else{
			$content_class = 'span8';
		}
	}
	if(empty($post_layout)){ $post_layout = $layout; }
	
	get_header();

	// Advert above content
	$ad_content_placement 	= of_get_option('ad_content_placement', array('home' => true,'pages' => true,'posts' => true));
	$ad_content_mode 		= of_get_option('ad_content_mode', 'none');
	$ad_content_box 		= of_get_option('ad_content_box', true);	
	$ad_content_padding 	= of_get_option('ad_content_padding', true);

	if($ad_content_mode != 'none' and $ad_content_placement['posts'] == true){
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
	<div id="primary" class="row <?php echo $post_layout; ?>">
		<?php if( $post_layout == 'both_side' ){ ?>
			<aside id="side-bar" class="span3 widget-area">
					<?php dynamic_sidebar( $left_sidebar ); ?>
			</aside>
		<?php }	?>

		<div id="content" class="<?php echo $content_class; ?>" role="main">

			<?php if ( have_posts() ){
			// Do we have any posts in the databse that match our query?
			?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php 
						get_template_part( 'inc/post-format/single', get_post_format() ); 
						$enable_rtl 		= of_get_option('enable_rtl', false);

					

						// Advert above content
						$ad_below_post_mode 		= of_get_option('ad_below_post_mode', 'none');
						$ad_below_post_box 			= of_get_option('ad_below_post_box', true);
						$ad_below_post_padding 		= of_get_option('ad_below_post_padding', true);

						if($ad_below_post_mode != 'none'){
							echo '<div style="margin-top: 30px;" class="above_content'.(($ad_below_post_box) ? ' box' : '').(($ad_below_post_padding) ? ' pad15' : '').'">';
								if($ad_below_post_mode == 'image'){
									echo '<a href="'.of_get_option('ad_below_post_image_link').'" target="_blank"><img src="'.of_get_option('ad_below_post_image').'"></a>';
								}elseif($ad_below_post_mode == 'html'){
									echo apply_filters('shortcode_filter',do_shortcode(of_get_option('ad_below_post_code')));
								}elseif($ad_below_post_mode == 'google_ads'){
									blu_get_google_ads(); 
								}
							echo '</div>';
						}
						
						if(!of_get_option('disable_pagination')){
							if($enable_rtl){
								$next_post = get_adjacent_post( false, '', true ); 
								$prev_post = get_adjacent_post( false, '', false ); 
							}else{
								$next_post = get_adjacent_post( false, '', false ); 
								$prev_post = get_adjacent_post( false, '', true ); 
							}
							?>
							<div class="single-pagination clearfix">
								<div class="box clearfix">
									<?php if( $prev_post ): ?>
										<a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="nav-previous">
											<div class="bgfallback">&nbsp;</div>
											<?php $post_format = (get_post_format( $prev_post->ID )) ? get_post_format( $prev_post->ID ) : 'standard';  ?>
												<span>&nbsp;</span>
												<div class="tab_icon tab_<?php echo $post_format; ?>"><i class="<?php echo get_post_icon( $prev_post ); ?>"></i></div>
												<div class="bgimage" style="background-image: url('<?php echo get_post_image( $prev_post->ID, 'small' ); ?>');"></div>
												<h5><i class="icon-angle-double-left"></i> <?php echo get_the_title( $prev_post->ID ); ?></h5>
										</a>
									<?php endif; ?>
									<?php if( $next_post ): ?>
										<a href="<?php echo get_permalink( $next_post->ID ); ?>" class="nav-next" style="float:left;">
											<div class="bgfallback">&nbsp;</div>
											<?php $post_format = (get_post_format( $next_post->ID )) ? get_post_format( $next_post->ID ) : 'standard';  ?>
												<span>&nbsp;</span>
												<div class="tab_icon tab_<?php echo $post_format; ?>"><i class="<?php echo get_post_icon( $next_post ); ?>"></i></div>
												<div class="bgimage" style="background-image: url('<?php echo get_post_image( $next_post->ID, 'small' ); ?>');"></div>
												<h5><?php echo get_the_title( $next_post->ID ); ?> <i class="icon-angle-double-right"></i></h5>
										</a>
									<?php endif; ?>
								</div>
							</div><?php
						}

					 	if(of_get_option('author_box')){ 
					 		$author_ID = get_the_author_meta('ID');
							if( of_get_option('author_box_image_'.$author_ID) ){ $author_image = 'background-image:url(' . of_get_option("author_box_image_".$author_ID) . '); background-size:cover;'; }else{ $author_image = 'background-image:none;'; } ?>
							<div class="author-meta box">
								<div class="author-header" style="<?php echo $author_image; ?>">
									<div class="author-image">
										<?php 
											if(!of_get_option('author_box_avatar_'.$author_ID)){
												echo '<img src="' . get_avatar_url(get_avatar( get_the_author_meta( 'ID' ) , 120 ) ) . '">'; 
											}else{
												echo '<img src="' . of_get_option('author_box_avatar_'.$author_ID) . '">'; 
											}

										?>
									</div>
								</div>
								<div class="author-body">
									<span class="vcard author">
										<h2>
											<span class="fn">
												<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?></a>
											</span>
										</h2>
										<p><?php the_author_meta('description'); ?></p>
									</span>
								</div>
							</div> <?php
						} 
					// show related posts by tag
					if(!of_get_option('disable_related_posts')){ 
					 	get_template_part( 'inc/related-posts' );
					}
					endwhile; // OK, let's stop the post loop once we've displayed it 


					// If comments are open or we have at least one comment, load up the default comment template provided by Wordpress
					if ( comments_open() )
						comments_template( '', true );
				
				 }else{ // Well, if there are no posts to display and loop through, let's apologize to the reader (also your 404 error) ?>
				
				<article class="post error">
					<h1 class="404"><?php _e('Page not found', 'bluth'); ?></h1>
				</article>

			<?php } // OK, I think that takes care of both scenarios (having a post or not having a post to show) ?>

		</div><!-- #content .site-content -->

		<?php if( $post_layout == 'left_side' ){ ?>
			<aside id="side-bar" class="span4 widget-area">
					<?php dynamic_sidebar( $left_sidebar ); ?>
			</aside>
		<?php } ?>	
		<?php if($post_layout == 'right_side' || empty( $post_layout )){ ?>
			<aside id="side-bar" class="span4 widget-area">
					<?php dynamic_sidebar( $right_sidebar ); ?>
			</aside>
		<?php } ?>	
		<?php if($post_layout == 'both_side'){ ?>
			<aside id="side-bar" class="span3 widget-area">
					<?php dynamic_sidebar( $right_sidebar ); ?>
			</aside>
		<?php }	?>
	</div><!-- #primary .content-area -->
<?php get_footer(); // This fxn gets the footer.php file and renders it ?>
