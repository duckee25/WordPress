<?php
	/**
	 * The template for displaying any single page.
	 *
	 */
	get_header(); 

$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$author_ID = get_the_author_meta('ID');

?>
	<div id="primary" class="row">

		<div id="content" class="span10 offset1" role="main"><?php
			if( of_get_option('author_box_image_'.$author_ID) ){ $author_image = 'background-image:url(' . of_get_option("author_box_image_".$author_ID) . '); background-size:cover;'; }else{ $author_image = 'background-image:none;'; } ?>
			<div class="author-meta box">
				<div class="author-header" style="height:300px; <?php echo $author_image; ?>">
					<div class="author-image" style="bottom:-240px;"><?php 
						if(!of_get_option('author_box_avatar_'.$author_ID)){
							echo '<img src="' . get_avatar_url(get_avatar( get_the_author_meta( 'ID' ) , 120 ) ) . '">'; 
						}else{
							echo '<img src="' . of_get_option('author_box_avatar_'.$author_ID) . '">'; 
						} ?>
					</div>
				</div>
				<div class="author-body">
					<h2 class="vcard author">
						<span class="fn">
							<?php echo $curauth->nickname; ?>
						</span>
						<small style="display:block;"><?php echo $curauth->first_name . ' ' . $curauth->last_name; ?></small>
					</h2><?php
					if(of_get_option('social_google_'.$author_ID)){ ?>
						<a href="<?php echo of_get_option('social_google_'.$author_ID); ?>?rel=author" style="color: #CE4231; font-size: 16px;"><i class="icon-gplus-1"></i></a><?php
					} ?>
					<p><?php echo $curauth->description; ?></p>
				</div>
			</div> <?php
				$orig_post = $post;
				global $post;

				$my_query = new wp_query( array(
				    'posts_per_page'=>8,
				    'author'=>$curauth->ID, 
				    'meta_key'=> 'wpb_post_views_count', 
					'orderby'=> 'meta_value_num' 
			    ));

				if($my_query->have_posts()){ ?>

			    	<div id="related-posts" class="row-fluid box" style="margin-bottom:25px;">
			    		<h3><?php echo __('Popular articles by', 'bluth') . ' ' . $curauth->nickname; ?></h3><?php 

			 			while($my_query->have_posts()){
						    $my_query->the_post(); 
						    $post_format = get_post_format();
						    $post_format = ($post_format) ? $post_format : 'standard';  ?>
							<a href="<?php echo get_permalink( $post->ID ); ?>" class="nav-previous" style="width: 49%;"> <?php 
								$post_format = (get_post_format( $post->ID )) ? get_post_format( $post->ID ) : 'standard';  ?>
								<div class="bgfallback">&nbsp;</div><?php 
								if( $post ){ ?>
									<span>&nbsp;</span>
									<div class="tab_icon tab_<?php echo $post_format; ?>"><i class="<?php echo get_post_icon( $post->ID ); ?>"></i></div>
									<div class="bgimage" style="background-image: url('<?php echo get_post_image( $post->ID, 'small' ); ?>');"></div>
									<h5 style="padding-top: 25px;"><?php echo get_the_title( $post->ID ); ?></h5><?php 
									echo the_excerpt(); 
								} ?>
							</a> <?php
						}
		    		echo '</div>';
				}  

				wp_reset_query();

				$my_query2 = new wp_query( array(
				    'posts_per_page'=>50,
				    'offset'=>1,
				    'author'=>$curauth->ID, 
			    ));

				// the rest of the authors articles
				if($my_query2->have_posts()){ ?>

			    	<div class="row-fluid box pad25">
			    		<h3 style="text-align: center;"><?php echo __('All articles by', 'bluth') . ' ' . $curauth->nickname; ?></h3><?php 

			 			while($my_query2->have_posts()){
						    $my_query2->the_post(); ?><?php 
								if( $post ){ ?>
									<span>&nbsp;</span>
									<a href="<?php echo get_permalink( $post->ID ); ?>">
									<h4><?php echo get_the_title( $post->ID ); ?></h4></a> <?php 
									the_excerpt(); 
								} ?>
							<?php
						}
		    		echo '</div>';
				}  ?>


		</div><!-- #content .site-content -->

	</div><!-- #primary .content-area -->
<?php get_footer(); ?>