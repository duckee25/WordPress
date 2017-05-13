<?php 

 get_header();
 $container_s = (r_option('sidebar_s')=='right_s')?'container':'container-fluid';
 $blor_s = (r_option('sidebar_s')=='right_s')?'col-md-18':'col-md-14 col-sm-18';
 ?>
 
 <!-- BODY CONTAINER - FULL WIDTH -->
<div class="<?php echo esc_attr($container_s) ?>">
    <div class="row">
		
        <?php if(r_option('sidebar_s')!='right_s'): ?>
		<?php get_template_part('sidebar-left') ?>
		<?php endif; ?>

        
        <!-- =========================
             BLOG SECTION 
        ============================== -->
        <div class="<?php echo esc_attr( $blor_s) ?>">
            <div class="blog-style-one">
                <!-- GENERAL BLOG POST -->
				
				<?php
						
						while ( have_posts() ) : the_post();
						$title_post = get_the_title();
						if($title_post==""){
							$title_post = '(Untitled)';
						}
						?>
                <article class="blog-item">
					
                    <header>
                        <h2 class="title">
                            <a href="<?php the_permalink() ?>"><?php echo esc_html($title_post) ?></a>
                        </h2>
                        <div class="meta-info">                           
							<?php get_template_part('content','meta') ?>                                
                        </div>
                    </header>
					<?php get_template_part( 'content', 'gallery' ); ?>
					<div class="post-body">
					<?php the_content() ?>
					<?php 
						wp_link_pages( array(
							'before'      => '<div class="pagination"><div class="navigate-page"><span class="page-links-title">' . __( 'Pages:', 'reader' ) . '</span>',
							'after'       => '</div></div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					?>	
					<?php 
					$jobboard_slug ='jobpost';
					if(r_option('jobboard_slug')!=''){
					$jobboard_slug = r_option('jobboard_slug');
					}

					?>
					<?php if(is_singular($jobboard_slug )): ?>
						<div class="location">
						<?php $location = esc_attr(get_post_meta( get_the_ID() , 'location', true )); ?>
						<?php if($location!=''): ?>
						<?php _e('Location:','reader') ?> <?php echo esc_html($location) ?>
						<?php endif; ?>
						</div>
					<?php endif; ?>
					</div><!-- post-body end -->
                </article>

                <div class="social-share">
                    <div class="row">
                        <div class="col-sm-13 post-tags">
                            <p><strong><?php _e('Tags:','reader') ?> </strong><?php the_tags('',', '); ?></p>
                        </div>
                        <div class="col-sm-11">
                            <div class="social-icons">
                                <p><strong><?php _e('Share This Post:','reader') ?></strong></p>
								<?php
								get_template_part( 'widgets/share-post');
								?>
                                
                            </div>
                        </div>
                    </div>
                </div> <!-- end of .social-share -->
				
				
				<?php if(r_option('show_bio')): ?>
				 <div class="about-author">
                    <div class="row">
                        <div class="col-sm-4 hidden-xs">
                            <div class="thumb">
                                <?php echo get_avatar( get_the_author_meta('email'), '74' ); ?>
                            </div>
                        </div>
                        <div class="col-sm-20">
                            <div class="desc">
                                <h5><?php _e('About This Author','reader') ?></h5>
                                <p><?php echo esc_html(get_the_author_meta( 'description' )) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
				
				
				<?php endif; ?>
					
				<?php endwhile; ?>
				<?php comments_template() ?>

                <!-- PAGINATION -->
                
                <!-- /PAGINATION -->
            </div>
        </div>
        <!-- /END BLOG SECTION -->

       <?php get_sidebar() ?>

    </div>
</div> <!-- end of .container-fluid -->

<?php get_footer() ?>