<?php
/*
Plugin Name: bl Featured Post
Description: Display posts tagged with "featured"
Author: Ivar Rafn
Version: 1
Author URI: http://www.bluth.is/
*/
class bl_featured_post extends WP_Widget {

	function bl_featured_post(){
		$widget_ops = array('classname' => 'bl_featured_post', 'description' => 'Display posts tagged with "featured" or the selected tag in Theme Options' );
		$this->WP_Widget('bl_featured_post', 'Bluth Featured Post', $widget_ops);
	}


	function widget( $args, $instance ) {
		extract($args);
		$title 	= apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text 	= apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		$post_offset 	= (isset($instance['post_offset']) and is_numeric($instance['post_offset'])) ? $instance['post_offset'] : 0;
		$tag_to_get = !of_get_option('featured_tag') ? 'featured' : of_get_option('featured_tag');
		echo $before_widget;
	    global $post;

	    // if it's not a slider
	    if( empty($instance['slider']) || !$instance['slider'] ){

	    	if($instance['order'] == 'popular'){
			    $args = array(
				'tag' 					=> $tag_to_get,
				'numberposts' 			=> 1,
				'offset' 				=> $post_offset, 
				'meta_key' 				=> 'wpb_post_views_count', 
				'orderby' 				=> 'wpb_post_views_count', 
	        	'ignore_sticky_posts'	=> 1, 
	        	'order' 				=> 'DESC' );
			}else{
			    $args = array(
				'tag' 					=> $tag_to_get,
				'numberposts' 			=> 1,
				'offset' 				=> $post_offset, 
	        	'ignore_sticky_posts'	=> 1, 
	        	'order' 				=> 'DESC' );
			}

		    $myposts = get_posts( $args );

			if( has_post_format( 'video', $myposts[0]->ID ) && $instance['video'] ){
				echo !empty($title) ? '<h3 class="widget-head">'.$title.'<a class="scale" href="' . get_permalink( $myposts[0]->ID ) . '">' . $myposts[0]->post_title . '</a></h3>' : '<a class="scale" href="' . get_permalink( $myposts[0]->ID ) . '"><h3>' . $myposts[0]->post_title . '</h3></a>';
			}else{
				echo !empty($title) ? '<h3 class="widget-head">'.$title.'</h3>' : '';
			}
			echo '<div class="widget-body">';
	        if( has_post_format( 'video', $myposts[0]->ID ) && $instance['video'] ){
        		$video_content = get_post_meta($myposts[0]->ID, '_format_video_embed', true);
				$video_content = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?wmode=opaque"$3>', $video_content);
				$video_content = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\?(.*?)\?(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?$3&$4"$5>', $video_content);
				echo $video_content;
	        }else{
        		echo '<img src="' . get_post_image( $myposts[0]->ID, 'featured_post' ) . '">';
        		echo '<div class="featured_post_overlay"></div>';
	        }
        	echo '<h4><a class="scale" href="' . get_permalink( $myposts[0]->ID ) . '">' . $myposts[0]->post_title . '</a></h4>';
        	if(!empty($instance['excerpt']) && $instance['excerpt']){
        		echo '<p>' . bl_truncate($myposts[0]->post_content, 65, ' ') . '</p>';
			}
			echo '</div>';

	    }else{
	    	if($instance['order'] == 'popular'){
			    $args = array(
				'tag' 					=> $tag_to_get,
				'numberposts' 			=> 5,
				'offset' 				=> $post_offset, 
				'meta_key' 				=> 'wpb_post_views_count', 
				'orderby' 				=> 'wpb_post_views_count', 
	        	'ignore_sticky_posts'	=> 1, 
	        	'order' 				=> 'DESC' );
			}else{
			    $args = array(
				'tag' 					=> $tag_to_get,
				'numberposts' 			=> 5,
				'offset' 				=> $post_offset, 
	        	'ignore_sticky_posts'	=> 1, 
	        	'order' 				=> 'DESC' );
			}

		    $myposts = get_posts( $args );
		    $views_array = array();

		    echo '<ul class="featured_gallery clearfix">';
		    foreach( $myposts as $post ){
		    	echo '<li>';

				if( has_post_format( 'video', $post->ID ) && $instance['video'] ){
					echo !empty($title) ? '<h3 class="widget-head">'.$title.'<a class="scale" href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></h3>' : '<a class="scale" href="' . get_permalink( $post->ID ) . '"><h3>' . $post->post_title . '</h3></a>';
				}else{
					echo !empty($title) ? '<h3 class="widget-head">'.$title.'</h3>' : '';
				}
				echo '<div class="widget-body">';
		        if( has_post_format( 'video', $post->ID ) && $instance['video'] ){
	        		$video_content = get_post_meta($post->ID, '_format_video_embed', true);
					$video_content = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?wmode=opaque"$3>', $video_content);
					$video_content = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\?(.*?)\?(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?$3&$4"$5>', $video_content);
					echo $video_content;
		        }else{
	        		echo '<img src="' . get_post_image( $post->ID, 'featured_post' ) . '">';
	        		echo '<div class="featured_post_overlay"></div>';
		        }
	        	echo '<h4><a class="scale" href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></h4>';
	        	if(!empty($instance['excerpt']) && $instance['excerpt']){
	        		echo '<p>' . bl_truncate($post->post_content, 65, ' ') . '</p>';
				}
				echo '</div>';
		    	echo '</li>';
		    }

			echo '</ul>';
			echo '<div class="left_arrow featured_box_arrow visible-desktop"><i class="icon-left-open-1"></i></div>';;
			echo '<div class="right_arrow featured_box_arrow visible-desktop"><i class="icon-right-open-1"></i></div>'; ?>
			<script type="text/javascript">
				jQuery(function(){

				    total_images = <?php echo count($myposts)-1; ?>,
		      		li_index = 0;
		      		li_width = '100%';
		      		li_height = jQuery( '.featured_gallery li' ).eq(0).height();
		      		jQuery( '.featured_gallery').closest( '.bl_featured_post' ).css('height', li_height);
		      		jQuery('.featured_box_arrow').click(function(){
		      			if(jQuery(this).hasClass('left_arrow') && li_index > 0){
	      					li_height = jQuery( this ).siblings('.featured_gallery').children('li').eq(li_index-1).height();
	      					jQuery( this ).closest('.bl_featured_post').animate({height: li_height}, 150, 'swing')
		      				jQuery( this ).siblings('.featured_gallery').animate({left: '+='+li_width}, 150, 'swing');
		      				li_index--;
		      			}else if(jQuery(this).hasClass('right_arrow') && li_index < total_images){
	      					li_height = jQuery( this ).siblings('.featured_gallery').children('li').eq(li_index+1).height();
	      					jQuery( this ).closest('.bl_featured_post').animate({height: li_height}, 150, 'swing')
		      				jQuery( this ).siblings('.featured_gallery').animate({left: '-='+li_width}, 150, 'swing');
		      				li_index++;
		      			}
		  			});
		  		});
		    </script><?php
	    } 
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['post_offset'] 	= strip_tags($new_instance['post_offset']);
		$instance['order'] 			= $new_instance['order'];

		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) );
		$instance['video'] = isset($new_instance['video']);
		$instance['slider'] = isset($new_instance['slider']);
		$instance['excerpt'] = isset($new_instance['excerpt']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'order' => '' ) );
		$title = strip_tags($instance['title']);
		$text = esc_textarea($instance['text']);
		$order = $instance['order'];
		$post_offset = empty($instance['post_offset']) ? 0 : $instance['post_offset'];
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bluth'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('video'); ?>" name="<?php echo $this->get_field_name('video'); ?>" type="checkbox" <?php checked(isset($instance['video']) ? $instance['video'] : 0); ?> />&nbsp;
			<label for="<?php echo $this->get_field_id('video'); ?>"><?php _e('Video Posts show the video instead of an image', 'bluth'); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('slider'); ?>" name="<?php echo $this->get_field_name('slider'); ?>" type="checkbox" <?php checked(isset($instance['slider']) ? $instance['slider'] : 0); ?> />&nbsp;
			<label for="<?php echo $this->get_field_id('slider'); ?>"><?php _e('Featured Posts display in slider', 'bluth'); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" <?php checked(isset($instance['excerpt']) ? $instance['excerpt'] : 0); ?> />&nbsp;
			<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Display the excerpt', 'bluth'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_offset'); ?>">Post Offset:</label>
			<select id="<?php echo $this->get_field_id('post_offset'); ?>" name="<?php echo $this->get_field_name('post_offset'); ?>">
				<?php 
					$i = 0;
					while ($i <= 10) {
						echo ($i == $post_offset) ? '<option value="'.$i.'" selected="">'.$i.'</option>' : '<option value="'.$i.'">'.$i.'</option>';
						$i++;
					}
				?>
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order By:', 'bluth'); ?></label>
			<select style="width:216px" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
			  	<option value="date" <?php echo ($instance['order'] == 'date') ? 'selected=""' : ''; ?>>Date</option> 
			  	<option value="popular" <?php echo ($instance['order'] == 'popular') ? 'selected=""' : ''; ?>>Popularity(View count)</option> 
			</select>

		</p>
<?php
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("bl_featured_post");') );