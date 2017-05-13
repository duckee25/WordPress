<?php
add_action('widgets_init', 'r_advertisements_widget');

function r_advertisements_widget()
{
	register_widget('r_Advertisements_w');
}

class r_Advertisements_w extends WP_Widget {
	
	function r_Advertisements_w()
	{
		$widget_ops = array('classname' => 'Advertisements_Widget', 'description' => 'Displays post with thumbnail.');

		$control_ops = array('id_base' => 'advertisements_widget');

		$this->WP_Widget('advertisements_widget', __('(theme)Advertisements','reader'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		$img1 = esc_attr($instance['img1']);
		$name1 = esc_attr($instance['name1']);
		$link1 = esc_attr($instance['link1']);
		$img2 = esc_attr($instance['img2']);
		$name2 = esc_attr($instance['name2']);
		$link2 = esc_attr($instance['link2']);
		$img3 = esc_attr($instance['img3']);
		$name3 = esc_attr($instance['name3']);
		$link3 = esc_attr($instance['link3']);
		$img4 = esc_attr($instance['img4']);
		$name4 = esc_attr($instance['name4']);
		$link4 = esc_attr($instance['link4']);
	


		?>
		
		 <div class="widget-ads">

			<?php 
			if($title) {
				echo wp_kses_post($before_title) . $title . $after_title;
			}
			?>
			<?php if($img1||$link1||$name1): ?>
				<figure>
                    <img src="<?php echo esc_url($img1) ?>" alt="" class="img-responsive">
                    <figcaption><a href="<?php echo esc_url($link1) ?>"><?php echo esc_html($name1) ?></a></figcaption>
                </figure>
			<?php endif; ?>
			
			<?php if($img2||$link2||$name2): ?>
				<figure>
                    <img src="<?php echo esc_url($img2) ?>" alt="" class="img-responsive">
                    <figcaption><a href="<?php echo esc_url($link2) ?>"><?php echo esc_html($name2) ?></a></figcaption>
                </figure>
			<?php endif; ?>
			
			<?php if($img3||$link3||$name3): ?>
				<figure>
                    <img src="<?php echo esc_url($img3) ?>" alt="" class="img-responsive">
                    <figcaption><a href="<?php echo esc_url($link3) ?>"><?php echo esc_html($name3) ?></a></figcaption>
                </figure>
			<?php endif; ?>
			
			<?php if($img4||$link4||$name4): ?>
				<figure>
                    <img src="<?php echo esc_url($img4) ?>" alt="" class="img-responsive">
                    <figcaption><a href="<?php echo esc_url($link4) ?>"><?php echo esc_html($name4) ?></a></figcaption>
                </figure>
			<?php endif; ?>
			
		<hr class="small" />
		</div>
			


		<?php 
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['img1'] = $new_instance['img1'];
		$instance['link1'] = $new_instance['link1'];
		$instance['name1'] = $new_instance['name1'];
		
		$instance['img2'] = $new_instance['img2'];
		$instance['link2'] = $new_instance['link2'];
		$instance['name2'] = $new_instance['name2'];
		
		$instance['img3'] = $new_instance['img3'];
		$instance['link3'] = $new_instance['link3'];
		$instance['name3'] = $new_instance['name3'];
		
		$instance['img4'] = $new_instance['img4'];
		$instance['link4'] = $new_instance['link4'];
		$instance['name4'] = $new_instance['name4'];

		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => __('Advertisements','reader'), 'number' => 4, 'random' => 'off');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','reader') ?></label>
			<input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('img1')); ?>"><?php _e('Image-1 link:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('img1')); ?>" name="<?php echo esc_attr($this->get_field_name('img1')); ?>" value="<?php echo esc_attr($instance['img1']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('link1')); ?>"><?php _e('Ad-1 url:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('link1')); ?>" name="<?php echo esc_attr($this->get_field_name('link1')); ?>" value="<?php echo esc_attr($instance['link1']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('name1')); ?>"><?php _e('Ad-1 :','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('name1')); ?>" name="<?php echo esc_attr($this->get_field_name('name1')); ?>" value="<?php echo esc_attr($instance['name1']); ?>" />
		</p>
		
		
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('img2')); ?>"><?php _e('Image-2 link:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('img2')); ?>" name="<?php echo esc_attr($this->get_field_name('img2')); ?>" value="<?php echo esc_attr($instance['img2']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('link2')); ?>"><?php _e('Ad-2 url:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('link2')); ?>" name="<?php echo esc_attr($this->get_field_name('link2')); ?>" value="<?php echo esc_attr($instance['link2']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('name2')); ?>"><?php _e('Ad-2 title:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('name2')); ?>" name="<?php echo esc_attr($this->get_field_name('name2')); ?>" value="<?php echo esc_attr($instance['name2']); ?>" />
		</p>
		
		
		
		
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('img3')); ?>"><?php _e('Image-3 link:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('img3')); ?>" name="<?php echo esc_attr($this->get_field_name('img3')); ?>" value="<?php echo esc_attr($instance['img3']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('link3')); ?>"><?php _e('Ad-3 url:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('link3')); ?>" name="<?php echo esc_attr($this->get_field_name('link3')); ?>" value="<?php echo esc_attr($instance['link3']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('name3')); ?>"><?php _e('Ad-3 title:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('name3')); ?>" name="<?php echo esc_attr($this->get_field_name('name3')); ?>" value="<?php echo esc_attr($instance['name3']); ?>" />
		</p>
		
		
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('img4')); ?>"><?php _e('Image-4 link:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('img4')); ?>" name="<?php echo esc_attr($this->get_field_name('img4')); ?>" value="<?php echo esc_attr($instance['img4']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('link4')); ?>"><?php _e('Ad-4 url:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('link4')); ?>" name="<?php echo esc_attr($this->get_field_name('link4')); ?>" value="<?php echo esc_attr($instance['link4']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('name4')); ?>"><?php _e('Ad-4 title:','reader') ?></label>
			<input class="widefat" style="width: 179px;" id="<?php echo esc_attr($this->get_field_id('name4')); ?>" name="<?php echo esc_attr($this->get_field_name('name4')); ?>" value="<?php echo esc_attr($instance['name4']); ?>" />
		</p>
		
		
		
		
	<?php
	}
}
?>