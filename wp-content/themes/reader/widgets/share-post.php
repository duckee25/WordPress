<ul>
				<?php if(r_option('facebook_share')): ?>
				<li><a target="_blank" class="" href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php the_title(); ?>"><i class="fa fa-facebook"></i></a></li>
				<?php endif; ?>
				
				<?php if(r_option('twitter_share')): ?>
				<li><a target="_blank" class="" href="http://twitter.com/home?status=<?php the_title(); ?> <?php the_permalink(); ?>"><i class="fa fa-twitter"></i></a></li>
				<?php endif; ?>
				
				<?php if(r_option('linkedin_share')): ?>
				<li><a target="_blank" class="" href="http://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>"><i class="fa fa-linkedin"></i></a></li>
				<?php endif; ?>
				
				<?php if(r_option('googlep_share')): ?>
				<li><a target="_blank" class="" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" 
				onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a></li>
				<?php endif; ?>
				
				<?php if(r_option('pinterest_share')): ?>
				<?php 
					$pinterest_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );					
				?>
				<li><a class="" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;description=<?php echo urlencode($post->post_title); ?>&amp;media=<?php echo esc_url($pinterest_src[0]) ?>"><i class="fa fa-pinterest"></i></a></li>
				<?php endif; ?>
				
				<?php if(r_option('reddit_share')): ?>
				<li><a class="" target="_blank" href="http://reddit.com/submit?url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>"><i class="fa fa-reddit"></i></a></li>
				<?php endif; ?>
				
				<?php if(r_option('mail_share')): ?>
				<li><a class="" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>" ><i class="fa fa-envelope-o"></i></a></li>
				<?php endif; ?>
				
				<?php if(r_option('tumblr_share')): ?>
				<li><a target="_blank" href="http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink()); ?>&amp;name=<?php echo urlencode($post->post_title); ?>&amp;description=<?php echo urlencode(get_the_excerpt()); ?>"><i class="fa fa-tumblr"></i></a></li>
				<?php endif; ?>
		
</ul>
