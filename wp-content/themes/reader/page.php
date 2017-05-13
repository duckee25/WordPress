<?php 

 get_header();
 $container_s = (r_option('sidebar_left_page'))?'container-fluid':'container';
 $blor_s = r_option('sidebar_left_page')?'col-md-14 col-sm-18':'col-md-18';
 ?>
 
 <!-- BODY CONTAINER - FULL WIDTH -->
<div class="<?php echo esc_attr($container_s) ?>">
    <div class="row">
		 <?php if(r_option('sidebar_left_page')): ?>
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
					</div>
                </article>
				<div class="social-share">
                    <div class="row">
                        <div class="col-sm-13 post-tags">
                            <p><strong></strong></p>
                        </div>
                        <div class="col-sm-11">
                            <div class="social-icons">
                                <p><strong><?php _e('Share This Page:','reader') ?></strong></p>
								<?php
								get_template_part( 'widgets/share-post');
								?>
                                
                            </div>
                        </div>
                    </div>
                </div> <!-- end of .social-share -->
              <!-- end of .social-share -->
				
				
			
					
				<?php endwhile; ?>
				<?php if(r_option('comments_page')): ?>
				<?php comments_template() ?>
				<?php endif; ?>
                <!-- PAGINATION -->
                
                <!-- /PAGINATION -->
            </div>
        </div>
        <!-- /END BLOG SECTION -->
 <?php get_sidebar() ?>
    </div>
</div> <!-- end of .container-fluid -->

<?php get_footer() ?>