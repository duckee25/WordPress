<?php
	$options = array(
		'disable_icons' => of_get_option('disable_icons', false),
		'disable_post_header' => of_get_option('disable_post_header', false),
		'status_icon' => of_get_option('status_icon', false),
		'header_art' => of_get_option('header_art', false),
		);

	if(is_sticky()){
		$post_icon = of_get_option('post_icon_edit', 'icon-pin');
	}else{
		$post_icon = of_get_option('status_icon', 'icon-book-1');
	}	
	$post_icon_color = '';

	// if it's a facebook status
	if( get_post_meta( $post->ID, 'bluth_facebook_status', true ) ){
		$status_class = 'bl_facebook';
		$post_icon = of_get_option('facebook_status_icon', 'icon-facebook-1');
		$post_icon_color = ' color: #3B5998; opacity: 1;';
	}
	else if( get_post_meta( $post->ID, 'bluth_twitter_status', true ) ){
		$status_class = 'bl_twitter';
		$post_icon = of_get_option('twitter_status_icon', 'icon-twitter-1');
		$post_icon_color = ' color: #1DCAFF; opacity: 1;';
	}
	else if( get_post_meta( $post->ID, 'bluth_google_status', true ) ){
		$status_class = 'bl_google';
		$post_icon = of_get_option('google_status_icon', 'icon-gplus-2');
		$post_icon_color = ' color: #CD3C2A; opacity: 1;';
	}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-title box"><?php
		if( of_get_option( 'author_front' ) ){ ?>
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-image bl_popover clearfix pull-left" data-trigger="hover" data-placement="top" data-content="<?php the_author(); ?>" title="<?php echo __('Author Name', 'bluth') ?>">
				<?php echo '<img src="' . get_avatar_url(get_avatar( get_the_author_meta( 'ID' ) , 100 ) ) . '">'; ?>
			</a><?php
		} ?>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div class="post-format-badge post-format-status">
			<i class="<?php echo $post_icon; ?>" style="<?php echo $post_icon_color; ?>"></i>
		</div>
		<div class="post-meta">
			<?php get_template_part( 'inc/meta-top' ); ?>
		</div>
	</div> 
	<div class="entry-container <?php echo $status_class; ?>">

		<div class="entry-content"><?php
			if( get_post_meta( $post->ID, 'bluth_facebook_status', true ) ){
				if(is_single() || ( is_front_page() && of_get_option('blog_layout', 'margin') ) ){
					echo str_replace( '&#039;', '\'', html_entity_decode( get_post_meta( $post->ID, 'bluth_facebook_status', true ) ) );
				}else{
					$facebook_store = str_replace( '&#039;', '\'', html_entity_decode( get_post_meta( $post->ID, 'bluth_facebook_status', true ) ) );
					$facebook_store = htmlentities($facebook_store);
					echo '<div class="facebook-store" data-code="' . $facebook_store . '"></div>';
				}
			}
			else if( get_post_meta( $post->ID, 'bluth_twitter_status', true ) ){
				// if(is_single() || ( is_front_page() && of_get_option('blog_layout', 'margin') ) ){
					echo html_entity_decode( get_post_meta( $post->ID, 'bluth_twitter_status', true ) );
				// }else{
					// $twitter_store = html_entity_decode( get_post_meta( $post->ID, 'bluth_twitter_status', true ) );
					// $twitter_store = htmlentities($twitter_store);
					// echo '<div class="twitter-store" data-code="' . $twitter_store . '"></div>';
				// }
			}
			else if( get_post_meta( $post->ID, 'bluth_google_status', true ) ){
				if(is_single() || ( is_front_page() && of_get_option('blog_layout', 'margin') ) ){
					echo html_entity_decode( get_post_meta( $post->ID, 'bluth_google_status', true ) );
				}else{
					$google_store = html_entity_decode( get_post_meta( $post->ID, 'bluth_google_status', true ) );
					$google_store = htmlentities($google_store);
					echo '<div class="google-store" data-code="' . $google_store . '"></div>';
				}
			}
			else{

			} ?>
		</div><!-- .entry-content -->
		<footer class="entry-meta clearfix">
			<?php get_template_part( 'inc/meta-bottom' ); ?>
		</footer><!-- .entry-meta -->
	</div><!-- .entry-container -->
</article><!-- #post-<?php the_ID(); ?> -->