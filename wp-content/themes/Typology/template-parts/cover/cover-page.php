<?php if( typology_get_option( 'page_cover' ) ) : ?>

	<?php 
		$cover_bg_img = typology_get_option('page_fimg') == 'cover' && has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'typology-cover' ) : '';
		$cover_bg_img = empty( $cover_bg_img ) ? typology_get_option('cover_bg_img') : $cover_bg_img; 
		$cover_img_class = $cover_bg_img ? 'typology-cover-overlay' : '';
	?>
	<div class="typology-cover-item <?php echo esc_attr( $cover_img_class ); ?>">

		<div class="cover-item-container">
		    <header class="entry-header">
		        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		    </header>
		    <?php if( typology_get_option('page_dropcap') ) : ?>
		    	<div class="cover-letter"><?php echo typology_get_letter(); ?></div>
		    <?php endif; ?>
		</div>

	<?php if( $cover_bg_img ) : ?>
		<div class="typology-cover-img">
			<img src="<?php echo esc_url( $cover_bg_img ); ?>" />
		</div>
	<?php endif; ?>

	</div>

<?php endif; ?>