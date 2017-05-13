<?php if( typology_get_option( 'archive_cover' ) ) : ?>
	
	<?php 
		$cover_bg_img = typology_get_option('cover_bg_img'); 
		$cover_img_class = $cover_bg_img ? 'typology-cover-overlay' : '';
	?>
	<div class="typology-cover-item <?php echo  esc_attr( $cover_img_class ); ?>">

		<div class="cover-item-container">

			<?php $cover = typology_get_archive_heading(); ?>
			
			<header class="entry-header">
				<?php if(!empty($cover['pre']) ): ?>
					<span class="entry-pre-title"><?php echo wp_kses_post( $cover['pre'] ); ?></span>
				<?php endif; ?>
				
				<?php if(!empty($cover['title']) ): ?>
					<h1 class="entry-title"><?php echo wp_kses_post( $cover['title'] ); ?></h1>
				<?php endif; ?>

				<?php if(!empty($cover['desc']) ): ?>
					<?php echo wp_kses_post( $cover['desc'] ); ?>
				<?php endif; ?>

			</header>

			<?php if( typology_get_option('archive_dropcap') ) : ?>
	    		<div class="cover-letter"><?php echo typology_get_letter( $cover['title'] ); ?></div>
	    	<?php endif; ?>
			
		
		</div>

		<?php if( $cover_bg_img ) : ?>
			<div class="typology-cover-img">
				<img src="<?php echo esc_url( $cover_bg_img ); ?>" />
			</div>
		<?php endif; ?>

	</div>
<?php endif; ?>