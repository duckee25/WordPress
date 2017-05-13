<?php
/*
Plugin Name: bl flickr
Description: Displays flickr images in a nice box carousel
Author: Ivar Rafn
Version: 1
Author URI: http://www.bluth.is/
*/

add_action( 'widgets_init', 'bl_flickr2' );
function bl_flickr2() {
	register_widget( 'bl_flickr2_widget' );
}

class bl_flickr2_widget extends WP_Widget {
	function bl_flickr2_widget() {
		$widget_ops = array( 'classname' => 'bl_flickr', 'description' => __( 'Displays recent Flickr photos in a widget', 'bluth' ) );

		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'flickr-photos' );

		$this->WP_Widget( 'flickr-photos', __( 'Bluth Flickr', 'bluth' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters( 'widget_title', $instance['title'] );
		$user_id = $instance['user_id'];
		$username = $instance['username'];
		$num = $instance['num'];

		$params = array(
			'api_key'  => '07a5b8a2ef5251509df92b7735679bd4',
			'method'   => 'flickr.photos.search',
			'user_id'  => $user_id,
			'per_page' => $num,
			'format'   => 'php_serial',
		);
		$encoded_params = array();

		echo $before_widget;

		// Display the widget title
		echo !empty($instance['title']) ? '<h3 class="widget-head">'.$instance['title'].'<a href="https://www.flickr.com/photos/' . $username . '" target="_blank">' . $username . '</a></h3>' : '' ?>
  		<div class="widget-body"> 
  			<div class="flickr-images-container">
  				<ul class="flickr-images clearfix flickr-slider">
			      	<?php
					foreach ( $params as $k => $v ) { $encoded_params[] = urlencode( $k ) . '=' . urlencode( $v ); }
					# call the API and decode the response
					$url = "https://api.flickr.com/services/rest/?" . implode( '&', $encoded_params );
					$rsp = file_get_contents( $url );
					$rsp_obj = unserialize( $rsp );

					# display the photo title (or an error if it failed)
					if ( $rsp_obj['stat'] == 'ok' ) {
						$isfirst = true;
						foreach ( $rsp_obj['photos']['photo'] as $photo ) { if($isfirst){ $isfirst = false; echo '<li><img src="https://farm' . $photo['farm'] .'.staticflickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'] . '.jpg" alt="fail"></li>';  }else{ echo '<li><img style="display:none;" src="https://farm' . $photo['farm'] .'.staticflickr.com/' . $photo['server'] . '/' . $photo['id'] . '_' . $photo['secret'] . '.jpg" alt="fail"></li>'; }}} 
					else { echo "Call failed!"; } 
					?>
				</ul>
			</div>
			<!-- <div class="left_arrow flickr_arrow visible-desktop"><i class="icon-left-open-1"></i></div> -->
			<!-- <div class="right_arrow flickr_arrow visible-desktop"><i class="icon-right-open-1"></i></div> -->
		</div>

		<script type="text/javascript">
			jQuery(window).load(function() {
			    jQuery('.flickr-slider').nivoSlider({
			        effect: 'fade', // Specify sets like: 'fold,fade,sliceDown'
			        slices: 15, // For slice animations
			        boxCols: 8, // For box animations
			        boxRows: 4, // For box animations
			        animSpeed: 350, // Slide transition speed
			        pauseTime: 8000, // How long each slide will show
			        startSlide: 0, // Set starting Slide (0 index)
			        directionNav: true, // Next & Prev navigation
			        controlNav: false, // 1,2,3... navigation
			        controlNavThumbs: false, // Use thumbnails for Control Nav
			        pauseOnHover: true, // Stop animation while hovering
			        manualAdvance: true, // Force manual transitions
			        randomStart: false, // Start on a random slide
			        prevText: '<i class="icon-left-open-1"></i>', // Prev directionNav text
			        nextText: '<i class="icon-right-open-1"></i>', // Next directionNav text
			        beforeChange: function(){}, // Triggers before a slide transition
			        afterChange: function(){ }, // Triggers after a slide transition
			        slideshowEnd: function(){}, // Triggers after all slides have been shown
			        lastSlide: function(){}, // Triggers when last slide is shown
			        afterLoad: function(){} // Triggers when slider has loaded
			    });
		    });
		</script>
		<?php echo $after_widget;
	}

	//Update the widget

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['user_id'] = $new_instance['user_id'];
		$instance['username'] = $new_instance['username'];
		$instance['num'] = strip_tags( $new_instance['num'] );

		return $instance;
	}


	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => '', 'num' => '9', 'user_id' => '', 'username' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults );

		$html = "";
		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title:', 'bluth' ) . '</label>';
		$html .= '<input id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" style="width:100%;" />';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id( 'user_id' ) . '">' . __( 'Flickr ID:', 'bluth' ) . '</label>';
		$html .= '<small>You can find your ID <a href="http://idgettr.com/" target="_blank">here</a></small>';
		$html .= '<input id="' . $this->get_field_id( 'user_id' ) . '" name="' . $this->get_field_name( 'user_id' ) . '" value="' . $instance['user_id'] . '" style="width:100%;" />';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id( 'username' ) . '">' . __( 'Flickr Username:', 'bluth' ) . '</label>';
		$html .= '<input id="' . $this->get_field_id( 'username' ) . '" name="' . $this->get_field_name( 'username' ) . '" value="' . $instance['username'] . '" style="width:100%;" />';
		$html .= '</p>';

		$html .= '<p>';
		$html .= '<label for="' . $this->get_field_id( 'num' ) . '">' . __( 'Photos Count:', 'bluth' ) . '</label>';
		$html .= '<select name="' . $this->get_field_name( 'num' ) . '" id="' . $this->get_field_id( 'num' ) . '" class="widefat">';
		$options = array( '1', '2', '3', '4', '5', '6', '7', '8', '9' );
		foreach ( $options as $option ) {
			$html .= '<option value="' . $option . '" id="' . $option . '"';
			$html .= $instance['num'] == $option ? ' selected' : '';
			$html .= '>';
			$html .= $option;
			$html .= '</option>';
		}
		$html .= '</select>';
		$html .= '</p>';

		echo $html;

	}
}