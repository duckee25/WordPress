<?php
	$options = array(
		'disable_icons' => of_get_option('disable_icons', false),
		'disable_post_header' => of_get_option('disable_post_header', false),
		'header_art' => of_get_option('header_art', false),
		);

	if(is_sticky()){
		$post_icon = of_get_option('post_icon_edit', 'icon-pin');
	}else{
		$post_icon = of_get_option('quote_icon', 'icon-quote-left');
	}	
	$quote_class = '';
	if(!has_post_thumbnail()){ $quote_class = ' no-image '; }else{ $quote_class = ' bgimg '; }

	$featured_image_size = of_get_option('disable_crop') ? 'large' : 'gallery-large';
	$max_height = $featured_image_size == 'large' ? 'max-height:none;' : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-image <?php echo $quote_class; ?>" style="<?php echo $max_height; ?>">
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
		<div class="quote-area">
			<h1 class="quote-text"><?php echo get_the_content() ?></h1>
			<div class="quote-author">
				<a href="<?php echo get_post_meta($post->ID, '_format_quote_source_url', true); ?>" target="_blank">
					<?php if(get_post_meta($post->ID, '_format_quote_source_name', true) != ""){ 
						echo '- ' . get_post_meta($post->ID, '_format_quote_source_name', true); 
					} ?>
				</a>
			</div>
		</div>
			<?php the_post_thumbnail( $featured_image_size ); ?>
		</a>
	</div>
	<footer class="entry-meta clearfix box" style="">
		<?php get_template_part( 'inc/meta-bottom' ); ?>
	</footer>
</article><!-- #post-<?php the_ID(); ?> -->
 