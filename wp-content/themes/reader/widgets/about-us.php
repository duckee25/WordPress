<?php
add_action('widgets_init', 'r_about_us_widget_f');

function r_about_us_widget_f()
{
	register_widget('r_About_us_w');
}

class r_About_us_w extends WP_Widget {
	
	function r_About_us_w()
	{
		$widget_ops = array('classname' => 'About_us', 'description' => 'logo+title+text.');

		$control_ops = array('id_base' => 'about_us-widget');

		$this->WP_Widget('about_us-widget', __('(theme)About Us','reader'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);		
		$logo_url = $instance['logo_url'];
		$text_about = ($instance['text_about']);

		
		
		?>
		<div class="widget_text">
		<?php if($logo_url==''){$logo_url = get_template_directory_uri() . '/images/logo-light-footer.png';} ?>
		<div class="logo">
        <img src="<?php echo esc_url($logo_url) ?>" alt="The Reader">
        <?php 
			if($title) {
				echo wp_kses_post($before_title) . $title . $after_title;
			}
		?>
        </div>

		<div class="textwidget">
			<?php echo esc_html($text_about) ?>
			
		</div>
		</div>
		
		
		<?php 
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['logo_url'] = $new_instance['logo_url'];
		$instance['text_about'] = $new_instance['text_about'];
		
		
		
		return $instance;
	}

	function form($instance)
	{
		
		$defaults = array('title' => 'The Reader', 'logo_url' => '', 'text_about' => '');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','reader') ?></label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('logo_url')); ?>"><?php _e('Logo url:','reader') ?></label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('logo_url')); ?>" name="<?php echo esc_attr($this->get_field_name('logo_url')); ?>" value="<?php echo esc_attr($instance['logo_url']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('text_about')); ?>"><?php _e('Text :','reader') ?></label>
			<textarea name="<?php echo esc_attr($this->get_field_name('text_about')); ?>" id="<?php echo esc_attr($this->get_field_id('text_about')); ?>" cols="30" rows="10"><?php echo esc_textarea($instance['text_about']); ?></textarea>
		</p>
		
		
		
		
	<?php
	}
}
?>