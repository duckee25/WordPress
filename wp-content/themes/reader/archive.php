<?php 
 $is_nopc = wp_is_mobile();
 if(r_option('style_blog')=='classic' || ($is_nopc)){
	get_template_part('template','blog-classic');
 }else{
	get_template_part('template','blog-grid');
 }

 ?>