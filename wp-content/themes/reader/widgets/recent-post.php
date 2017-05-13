<?php
add_action('widgets_init', 'r_recent_post_thumbnail_widget');

function r_recent_post_thumbnail_widget()
{
	register_widget('r_Recent_Post_Thumbnail');
}

class r_Recent_Post_Thumbnail extends WP_Widget {
	
	function r_Recent_Post_Thumbnail()
	{
		$widget_ops = array('classname' => 'Recent Post Thumbnail', 'description' => 'Displays post with thumbnail.');

		$control_ops = array('id_base' => 'recent_post-widget');

		$this->WP_Widget('recent_post-widget', __('(theme)Recent Post Thumbnail','reader'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$number = esc_attr($instance['number']);
		$random = isset($instance['random']) ? 'true' : 'false';


		?>
		
		 <div class="widget-popular-article">
			<?php 
			if($title) {
				echo wp_kses_post($before_title) . $title . $after_title;
			}
			?>
			<?php
			$rand = '';
			if($random == 'true'){
				$rand = 'rand';
			}
			$args = array(
				'post_type' => '',
				'posts_per_page' => $number,
				'ignore_sticky_posts' => 1,
				'orderby' => $rand
			);			
			$portfolio = new WP_Query(esc_sql($args));
			
			if($portfolio->have_posts()):
			while($portfolio->have_posts()): $portfolio->the_post(); 
			if(has_post_thumbnail()):?>
			
					 <div class="popular-article clearfix">
                        <div class="thumb">
                           <?php the_post_thumbnail() ?>
                        </div>
                        <div class="article-link">
                             <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                        </div>
                    </div>

				
		
		
		<?php endif;?>	
		<?php endwhile; ?>	
		<?php endif;?>		

			
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
		$instance['random'] = $new_instance['random'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Recent Post', 'number' => 4, 'random' => 'off');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','reader') ?></label>
			<input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of items to show:','reader') ?></label>
			<input class="widefat" style="width: 30px;" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['random'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('random')); ?>" name="<?php echo esc_attr($this->get_field_name('random')); ?>" /> 
			<label for="<?php echo esc_attr($this->get_field_id('random')); ?>"><?php _e('Random Ordered','reader') ?></label>
		</p>
	<?php
	}
}
?>