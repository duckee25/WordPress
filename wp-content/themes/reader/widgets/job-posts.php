<?php
add_action('widgets_init', 'r_job_post_w');

function r_job_post_w()
{

	register_widget('r_Job_post_c');

}

class r_Job_post_c extends WP_Widget {
	
	function r_Job_post_c()
	{
		$widget_ops = array('classname' => 'Job_post_class', 'description' => 'Displays post with thumbnail.');

		$control_ops = array('id_base' => 'job_post-widget');

		$this->WP_Widget('job_post-widget', __('(theme)Job Posts','reader'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{	if(post_type_exists( 'jobpost' )){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$number = esc_attr($instance['number']);
		$random = isset($instance['random']) ? 'true' : 'false';


		?>
		
		 <div class="widget-readers-job-board">

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
			if($number == ''){
				$number = 4;
			}
			
			$args = array(
				'post_type' => '',
				'posts_per_page' => $number,
				'ignore_sticky_posts' => 1,
				'post_type' => 'jobpost',
				'orderby' => $rand
			);			
			$portfolio = new WP_Query(esc_sql($args));
			
			if($portfolio->have_posts()):
			while($portfolio->have_posts()): $portfolio->the_post(); 
			$location = '';
			$location = esc_attr(get_post_meta( get_the_ID() , 'location', true ));
			?>
			
			<p><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - <?php echo esc_attr($location) ?></p>
			
			
		
		
			<?php endwhile; ?>	
			 <div><a href="<?php echo get_post_type_archive_link( 'jobpost' ) ?>" class="btn btn-fullwd btn-prime"><?php _e('View All Jobs','reader') ?></a></div>

		<?php endif;?>		

			
		<hr class="small"/>
		<?php wp_reset_postdata(); ?>
		</div>

		<?php 
		}
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
	if(post_type_exists( 'jobpost' )){
		$defaults = array('title' => __('READER\'S JOB BOARD','reader'), 'number' => 4, 'random' => 'off');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:','reader') ?></label>
			<input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of job posts to show:','reader') ?></label>
			<input class="widefat" style="width: 30px;" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($instance['number']); ?>" />
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['random'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('random')); ?>" name="<?php echo esc_attr($this->get_field_name('random')); ?>" /> 
			<label for="<?php echo esc_attr($this->get_field_id('random')); ?>"><?php _e('Random Ordered','reader') ?></label>
		</p>
	<?php
	}else{
	?>
	<p><?php _e('You need to install the JobPosting-Dex plugin','reader') ?></p>
	<?php 
	}
	}
}
?>