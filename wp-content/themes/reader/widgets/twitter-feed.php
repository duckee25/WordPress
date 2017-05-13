<?php
add_action('widgets_init', 'r_twitter_feed_widgets');

function r_twitter_feed_widgets()
{
	register_widget('r_twitter_feed');
}

class r_twitter_feed extends WP_Widget {
	
	function r_twitter_feed()
	{
		$widget_ops = array('classname' => 'Twitter Feed Slider', 'description' => 'Slideshow of twitter feed.');

		$control_ops = array('id_base' => 'twitter_feed-widget');

		$this->WP_Widget('twitter_feed-widget', __('(theme)Twitter Feed Slider','reader'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$number = esc_attr($instance['number']);
		$username = esc_attr($instance['username']);
		
		?>
	
		
		<!-- Twitter Sidebar -->
		<div class="widget-tweets">
			<?php 
			if($title) {
				echo wp_kses_post($before_title) . $title . $after_title;
			}
			?>
				<!-- Twitter Slider -->                   
				<div class="tweet" data-username="<?php echo esc_attr($username) ?>" data-count="<?php echo esc_attr($number) ?>"></div>
		</div><!-- Twitter Sidebar Ends -->
	
		
		
		
		<?php 
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['username'] = $new_instance['username'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'LATEST TWITTER FEED', 'number' => 2, 'username' => 'username');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','reader') ?></label>
			<input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
<p>
			<label for="<?php echo esc_attr($this->get_field_id('username')); ?>"><?php _e('Username:','reader') ?></label>
			<input class="widefat" style="width: 180px;" id="<?php echo esc_attr($this->get_field_id('username')); ?>" name="<?php echo esc_attr($this->get_field_name('username')); ?>" value="<?php echo esc_attr($instance['username']); ?>" />
		</p>	
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of tweets to show:','reader') ?></label>
			<input class="widefat" style="width: 30px;" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
		</p>
			
	<?php
	}
}
?>