<?php if (in_array("user", r_option('meta_posts'))): ?>
 <?php if(!(r_option('style_blog')=='grid'&&is_home())): ?>
	<?php echo get_avatar( get_the_author_meta('email'), '90' ); ?>
 <?php endif; ?>
<?php endif; ?>
<ul>
                
	<?php if (in_array("user", r_option('meta_posts'))): ?>
	<li> <?php the_author_posts_link(); ?></li>
	<?php endif; ?>
	
	
	<?php if (in_array("date", r_option('meta_posts'))): ?>
	<li> <?php the_time(__('jS M Y','reader')) ?></li>
	<?php endif; ?>
	
	
	
	
	<?php if (in_array("category", r_option('meta_posts'))&&!is_page()): ?>
	<?php 
		
		$the_cat = get_the_category();
		$cat_name = '';
		$cat_link ='';
		if(count($the_cat)>0){
		$cat_name = $the_cat[0]->cat_name;
		$cat_link = get_category_link( $the_cat[0]->cat_ID );
		}
		
	?>
		<?php if($cat_name != ''): ?>
		 <li><a href="<?php echo esc_attr($cat_link) ?>"><?php echo esc_attr($cat_name) ?></a></li>
		<?php endif; ?>
	<?php endif; ?>
	
	<?php if(is_singular( 'jobpost' )): ?>
		<li><a href="<?php echo get_post_type_archive_link( 'jobpost' ); ?>"><?php _e('Job Posts','reader') ?></a></li>
	
	<?php endif; ?>
	
	
	
	<?php if (in_array("comments", r_option('meta_posts'))): ?>
	<li class="comments"><?php comments_popup_link(__('0 Comments','reader'), __('1 Comment','reader'), __('% Comments','reader'), 'comments-link', __('off','reader')); ?></li>
	<?php endif; ?>
</ul>