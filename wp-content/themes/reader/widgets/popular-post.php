<?php
add_action('widgets_init', 'r_popular_post_thumbnail_widget');

function r_popular_post_thumbnail_widget()
{
	register_widget('r_Popular_Post_Thumbnail');
}

class r_Popular_Post_Thumbnail extends WP_Widget {
	
	function r_Popular_Post_Thumbnail()
	{
		$widget_ops = array('classname' => 'Popular Post Thumbnail', 'description' => 'Displays post with thumbnail.');

		$control_ops = array('id_base' => 'popular_post-widget');

		$this->WP_Widget('popular_post-widget', __('(theme)Popular Post Thumbnail','reader'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$number = $instance['number'];
		$orderby = $instance['orderby'];

		if(!$orderby) {
			$orderby = 'Highest Comments';
		}

	
		?>
		 <div class="widget-popular-article">
			<?php 
			if($title) {
				echo wp_kses_post($before_title) . $title . $after_title;
			}
			?>
		<?php
		

		
		if($orderby == 'Highest Comments') {
			$order_string = '&orderby=comment_count';
		} else {
			$order_string = '&key=views&orderby=meta_value_num';
		}
		$popular_posts = new WP_Query(esc_sql( $order_string.'&order=DESC'.'&ignore_sticky_posts=1&posts_per_page='.$number));
		
		if($popular_posts->have_posts()):
		?>		
		
		<?php while($popular_posts->have_posts()): $popular_posts->the_post(); 
		if(has_post_thumbnail()):?>
		<!-- Post 1 -->
		
					 <div class="popular-article clearfix">
                        <div class="thumb">
                           <?php the_post_thumbnail() ?>
                        </div>
                        <div class="article-link">
                             <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                        </div>
                    </div>
		<?php endif; ?>
		<?php endwhile; ?>			
		<?php endif; ?>
		<hr class="small"/>
		<?php wp_reset_postdata(); ?>
		</div>













		

		<?php 
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['orderby'] = $new_instance['orderby'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => __('Popular Posts','reader'), 'number' => 2, 'orderby' => 'Highest Comments');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','reader') ?></label>
			<input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('orderby')); ?>"><?php _e('Popular Posts Order By:','reader') ?></label> 
			<select id="<?php echo esc_attr($this->get_field_id('orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('orderby')); ?>" class="widefat" style="width:100%;">
				<option <?php if ($instance['orderby'] == 'Highest Comments') echo 'selected="selected"'; ?>><?php _e('Highest Comments','reader') ?></option>
				<option <?php if ($instance['orderby'] == 'Highest Views') echo 'selected="selected"'; ?>><?php _e('Highest Views','reader') ?></option>
			</select>
		</p>
		
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of items to show:','reader') ?></label>
			<input class="widefat" style="width: 30px;" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
		</p>
		
		
	<?php
	}
}
?>