<?php
add_action('widgets_init', 'r_social_icons_widgets');

function r_social_icons_widgets()
{
	register_widget('r_social_icons');
}

class r_social_icons extends WP_Widget {
	
	function r_social_icons()
	{
		$widget_ops = array('classname' => 'Social Icons', 'description' => 'Show a list of icons and followers number.');

		$control_ops = array('id_base' => 'social_icons-widget');

		$this->WP_Widget('social_icons-widget', __('(theme)Social Icons','reader'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		
		$text_label = esc_attr($instance['text_label']);

		$twitter = esc_attr($instance['twitter']);
		$facebook = esc_attr($instance['facebook']);
		$flickr = esc_attr($instance['flickr']);
		$linkedin = esc_attr($instance['linkedin']);
		$google_plus = esc_attr($instance['google_plus']);
		$vimeo = esc_attr($instance['vimeo']);
		$pinterest = esc_attr($instance['pinterest']);
		$tumblr = esc_attr($instance['tumblr']);
		$youtube = esc_attr($instance['youtube']);
		$mail = esc_attr($instance['mail']);
		//$youtube = esc_attr($instance['youtube']);
		
		if($title) {
			echo wp_kses_post($before_title) . $title . $after_title;
		}
		?>
		<div class="widget-subscribe">
		<div class="social-icons clearfix">
			<?php if($text_label): ?>
			<p><?php echo esc_attr($text_label) ?>:</p>
			<?php endif; ?>	
						
			<ul>
		
						
				<?php if($facebook): ?>
				<li><a href="<?php echo esc_url($facebook) ?>"><i class="fa fa-facebook"></i></a></li>
				<?php endif; ?>
				
				<?php if($twitter): ?>
				<li><a href="<?php echo esc_url($twitter) ?>"><i class="fa fa-twitter"></i></a></li>
				<?php endif; ?>
				
				<?php if($flickr): ?>
				<li><a href="<?php echo esc_url($flickr) ?>"><i class="fa fa-flickr"></i></a></li>
				<?php endif; ?>
				
				<?php if($vimeo): ?>
				<li><a href="<?php echo esc_url($vimeo) ?>"><i class="fa fa-vimeo-square"></i></a></li>
				<?php endif; ?>
				
				<?php if($google_plus): ?>
				<li><a href="<?php echo esc_url($google_plus) ?>"><i class="fa fa-google-plus"></i></a></li>
				<?php endif; ?>
					
				<?php if($linkedin): ?>
				<li><a href="<?php echo esc_url($linkedin) ?>"><i class="fa fa-linkedin"></i></a></li>
				<?php endif; ?>
				
				<?php if($pinterest): ?>
				<li><a href="<?php echo esc_url($pinterest) ?>"><i class="fa fa-pinterest"></i></a></li>
				<?php endif; ?>			
				
				<?php if($mail): ?>
				<li><a href="<?php echo esc_url($mail) ?>"><i class="fa fa-envelope-o"></i></a></li>
				<?php endif; ?>	
				
				<?php if($tumblr): ?>
				<li><a href="<?php echo esc_url($tumblr) ?>"><i class="fa fa-tumblr"></i></a></li>
				<?php endif; ?>	
				
				<?php if($youtube): ?>
				<li><a href="<?php echo esc_url($youtube) ?>"><i class="fa fa-youtube"></i></a></li>
				<?php endif; ?>	
				
				
				
						
		</ul>
		</div>
		<hr class="small"/>
		</div>
		
		
		<?php 
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		

		
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text_label'] = $new_instance['text_label'];
		$instance['twitter'] = $new_instance['twitter'];
		$instance['flickr'] = $new_instance['flickr'];
		$instance['facebook'] = $new_instance['facebook'];
		$instance['tumblr'] = $new_instance['tumblr'];
		$instance['youtube'] = $new_instance['youtube'];
		$instance['linkedin'] = $new_instance['linkedin'];
		$instance['mail'] = $new_instance['mail'];
		$instance['google_plus'] = $new_instance['google_plus'];
		$instance['pinterest'] = $new_instance['pinterest'];
		$instance['vimeo'] = $new_instance['vimeo'];
		//$instance['youtube'] = $new_instance['youtube'];
		
		
		return $instance;
	}

	function form($instance)
	{
		
		$defaults = array('title' => '', 'text_label' => 'Socials');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>

			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('text_label')); ?>">Label text:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('text_label')); ?>" name="<?php echo esc_attr($this->get_field_name('text_label')); ?>" value="<?php echo esc_attr($instance['text_label']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>">Twitter link:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" value="<?php echo esc_url($instance['twitter']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>">Facebook link:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" value="<?php echo esc_url($instance['facebook']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>">Linkedin link:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" value="<?php echo esc_url($instance['linkedin']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('flickr')); ?>">Facebook text:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('flickr')); ?>" name="<?php echo esc_attr($this->get_field_name('flickr')); ?>" value="<?php echo esc_url($instance['flickr']); ?>" />
		</p>
		
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('tumblr')); ?>">Tumblr:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('tumblr')); ?>" name="<?php echo esc_attr($this->get_field_name('tumblr')); ?>" value="<?php echo esc_url($instance['tumblr']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>">Youtube:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" value="<?php echo esc_url($instance['youtube']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('google_plus')); ?>">Google Plus link:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('google_plus')); ?>" name="<?php echo esc_attr($this->get_field_name('google_plus')); ?>" value="<?php echo esc_url($instance['google_plus']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('mail')); ?>">mail:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('mail')); ?>" name="<?php echo esc_attr($this->get_field_name('mail')); ?>" value="<?php echo esc_url($instance['mail']); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>">Pinterest link:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" value="<?php echo esc_url($instance['pinterest']); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('vimeo')); ?>">vimeo:</label>
			<input class="widefat" style="width: 150px;" id="<?php echo esc_attr($this->get_field_id('vimeo')); ?>" name="<?php echo esc_attr($this->get_field_name('vimeo')); ?>" value="<?php echo esc_url($instance['vimeo']); ?>" />
		</p>
		
		
		
	<?php
	}
}
?>