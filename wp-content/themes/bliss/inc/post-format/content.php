<?php
	$options = array(
		'disable_icons' => of_get_option('disable_icons', false),
		'disable_post_header' => of_get_option('disable_post_header', false),
		'standard_icon' => of_get_option('standard_icon', false),
		'header_art' => of_get_option('header_art', false),
		);

	if(is_sticky()){
		$post_icon = of_get_option('post_icon_edit', 'icon-pin');
	}else{
		$post_icon = of_get_option('standard_icon', 'icon-picture-1');
	}	
	if ( has_post_thumbnail() ) { $margin_class = ''; }else{ $margin_class = 'noimg'; }

	$featured_image_size = of_get_option('disable_crop') ? 'large' : 'gallery-large';
	$featured_image_size = of_get_option('blog_layout') == 'list' ? 'thumbnail' : $featured_image_size;
	$max_height = $featured_image_size == 'large' ? 'max-height:none;' : '';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-title box"><?php
		if( of_get_option( 'author_front' ) ){ ?>
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-image bl_popover clearfix pull-left" data-trigger="hover" data-placement="top" data-content="<?php the_author(); ?>" title="<?php echo __('Author Name', 'bluth') ?>">
				<?php echo '<img src="' . get_avatar_url(get_avatar( get_the_author_meta( 'ID' ) , 100 ) ) . '">'; ?>
			</a><?php
		} ?>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div class="post-meta">
			<?php get_template_part( 'inc/meta-top' ); ?>
		</div>
		<div class="post-format-badge post-format-standard">
			<i class="<?php echo $post_icon; ?>"></i>
		</div>
	</div>
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="entry-image" style="<?php echo $max_height; ?>">
		<?php 
		if(!of_get_option('post_image_lightbox')){ ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark">
				<?php the_post_thumbnail( $featured_image_size ); ?>
			</a><?php 
		}else{
			$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large', false, '' ); ?>
			<a href="<?php echo $src[0]; ?>" class="lightbox" title="<?php the_title(); ?>" rel="bookmark">
				<?php the_post_thumbnail( $featured_image_size ); ?>
			</a><?php 
		} ?>
	</div><?php 
	} ?>
	<div class="entry-container <?php echo $margin_class; ?>">
		<div class="entry-content">
			<?php 
				if(of_get_option('enable_excerpt')){
					the_excerpt();
				}else{
					the_content(__('Continue reading...', 'bluth')); 
				}
			?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'bluth' ), 'after' => '</div>' ) ); ?>
			<footer class="entry-meta clearfix">
				<?php get_template_part( 'inc/meta-bottom' ); ?>
			</footer><!-- .entry-meta -->
		</div><!-- .entry-content -->
	</div><!-- .entry-container -->
</article><!-- #post-<?php the_ID(); ?> -->