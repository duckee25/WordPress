<?php $cover_query = typology_get_front_page_cover_posts(); ?>

<?php if( $cover_query->have_posts() ) : ?>
<div class="typology-slider-wrapper-fixed">
<div class="typology-slider-wrapper <?php echo esc_attr( typology_get_front_page_cover_class() ); ?>">
			<?php while( $cover_query->have_posts() ) : $cover_query->the_post(); ?>
				
				<?php 
					$cover_bg_img = typology_get_option('front_page_cover_posts_fimg')  && has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'typology-cover' ) : '';
					$cover_bg_img = empty( $cover_bg_img ) ? typology_get_option('cover_bg_img') : $cover_bg_img;
					$cover_img_class = $cover_bg_img ? 'typology-cover-overlay' : '';
				?>

				<div class="typology-cover-item <?php echo esc_attr( $cover_img_class ); ?>">

					<div class="cover-item-container">
						<article <?php post_class(); ?>>
						    <header class="entry-header">
						        <?php the_title( sprintf( '<h2 class="entry-title h1"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						        <?php if( $meta = typology_meta_display('cover') ) : ?> 
	                				<div class="entry-meta"><?php echo typology_get_meta_data( $meta ); ?></div>
	            				<?php endif; ?>
						    </header>
						    <?php if( $buttons = typology_buttons_display('cover') ) : ?>      
						        <div class="entry-footer">
						            <?php echo typology_get_buttons_data( $buttons ); ?>
						        </div>
    						<?php endif; ?>

						    <?php if( typology_get_option('front_page_cover_dropcap') ) : ?>
	    						<div class="cover-letter"><?php echo typology_get_letter(); ?></div>
	    					<?php endif; ?>
						</article>
					</div>

					<?php if( $cover_bg_img ) : ?>
						<div class="typology-cover-img">
							<img src="<?php echo esc_url( $cover_bg_img ); ?>" />
						</div>
					<?php endif; ?>

				</div>

			<?php endwhile; ?>
</div>
</div>

<?php endif; ?>

<?php wp_reset_postdata(); ?>