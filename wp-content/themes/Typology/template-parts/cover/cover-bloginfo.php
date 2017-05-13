<?php 
	$cover_bg_img = typology_get_option('cover_bg_img'); 
	$cover_img_class = $cover_bg_img ? 'typology-cover-overlay' : '';
?>
<div class="typology-cover-item <?php echo esc_attr( $cover_img_class ); ?>">

	<div class="cover-item-container">
		
		<header class="entry-header">
			<h1 class="entry-title"><?php bloginfo( 'name' ); ?></h1>
			<p class="entry-desc"><?php bloginfo( 'description' ); ?></p>
		</header>

		<?php if( typology_get_option('front_page_cover_dropcap') ) : ?>
    			<div class="cover-letter"><?php echo typology_get_letter( get_bloginfo('name') ); ?></div>
    	<?php endif; ?>
	
	</div>

	<?php if( $cover_bg_img ) : ?>
		<div class="typology-cover-img">
			<img src="<?php echo esc_url( $cover_bg_img ); ?>" />
		</div>
	<?php endif; ?>

</div>