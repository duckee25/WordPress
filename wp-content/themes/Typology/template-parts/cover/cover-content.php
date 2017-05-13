<?php 
	$cover_bg_img = typology_get_option('cover_bg_img'); 
	$cover_img_class = $cover_bg_img ? 'typology-cover-overlay' : '';
?>
<div class="typology-cover-item <?php echo esc_attr( $cover_img_class ); ?>">
	<div class="cover-item-container">
	    <header class="entry-header">
	       
	        <?php if( have_posts() ): ?>

				<?php while( have_posts() ) : the_post(); ?>

					 <?php the_content(); ?>

				<?php endwhile; ?>

			<?php endif; ?>
			
	    </header>

	   <?php if( typology_get_option('front_page_cover_dropcap') ) : ?>
	    	<div class="cover-letter"><?php echo typology_get_letter( wp_strip_all_tags( get_the_content() ) ); ?></div>
	   <?php endif; ?>
	</div>

	<?php if( $cover_bg_img ) : ?>
		<div class="typology-cover-img">
			<img src="<?php echo esc_url( $cover_bg_img ); ?>" />
		</div>
	<?php endif; ?>

</div>

