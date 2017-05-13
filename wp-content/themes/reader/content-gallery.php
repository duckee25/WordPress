<?php 
$t_bussy=false;
if(!is_singular()): 
?>
<?php 


//audio
$au = '';
preg_match('/\[audio_iframe.*url=.(.*).\]/', $post->post_content, $au);
if(count($au)>0){
$t_bussy = true;
$new_au = '<div class="audio-player"><audio src="'.$au[1].'"  class="mejs-player" data-mejsoptions=\'{"alwaysShowControls": true}\'></audio></div>';

if(r_option('style_blog')=='grid'){
$post->post_content = preg_replace('/(^([\r\n]))*(\[audio_iframe.*?\])([\r\n])*/m', '', $post->post_content);
$idau= r_get_image_id($au[1]);
$titleau=get_the_title($idau);
 ?>
 <p><em><strong><?php _e('Track:','reader') ?> <?php echo esc_html($titleau); ?></strong></em></p>
 <div class="audio-player">
  <audio src="<?php echo esc_html($au[1]) ?>" width="100%" class="mejs-player" data-mejsoptions='{"alwaysShowControls": true}'></audio>
 </div>
 <?php 
}else{
$post->post_content = preg_replace('/(^([\r\n]))*(\[audio_iframe.*?\])([\r\n])*/m', $new_au, $post->post_content);
}
setup_postdata($post); 
}



//video_iframe
$vi = '';
preg_match('/\[video_iframe.*id_video=.(.*).\]/', $post->post_content, $vi);
if(count($vi)>0){
$t_bussy = true;
$new_vi = '<div class="video-player"><iframe width="560" height="315" src="//www.youtube.com/embed/'.$vi[1].'" allowfullscreen></iframe></div>';

if(r_option('style_blog')=='grid'){
$post->post_content = preg_replace('/(^([\r\n]))*(\[video_iframe.*?\])([\r\n])*/m', '', $post->post_content);

 ?>
 <div class="video-player">
	<div class="fluid-width-video-wrapper" style="padding-top: 75%;"><iframe src="//www.youtube.com/embed/<?php echo esc_html($vi[1]) ?>" allowfullscreen="" ></iframe></div>
 </div>
 
 <?php 
}else{
$post->post_content = preg_replace('/(^([\r\n]))*(\[video_iframe.*?\])([\r\n])*/m', $new_vi, $post->post_content);
}
setup_postdata($post); 
}






if(preg_match('/\[featured_image\]/', $post->post_content)){
$t_bussy = true;
$new_img = '<div class="the-post-thumbnail"><a href="'.get_the_permalink().'">'.get_the_post_thumbnail(get_the_id(),'blog-post-thumbnail').'</a></div>';

if(r_option('style_blog')=='grid'){
$post->post_content = preg_replace('/(^([\r\n]))*(\[featured_image\])([\r\n])*/m', '', $post->post_content);

 ?>
<div class="the-post-thumbnail hola"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail('portfolio-thumbnail') ?></a></div>
 <?php 
}else{
$post->post_content = preg_replace('/(^([\r\n]))*(\[featured_image\])([\r\n])*/m', $new_img, $post->post_content);
}
setup_postdata($post); 
}

 ?>

 
 
 
 
 
 
 
 
 
 
 
 
 
<?php endif; ?>



<?php
preg_match('/\[gallery.*ids=.(.*).\]/', $post->post_content, $ids);
$gal_ids = Array();
if(!empty($ids[1])){
$gal_ids = explode(',', str_replace(' ', '', $ids[1]));
}
if (count($gal_ids) > 1) : 
ob_start(); ?>
<div class="html5-image-gallery clearfix">

	<?php foreach ($gal_ids as $gal_id) : ?>
	<div class="item"><?php echo wp_get_attachment_image($gal_id, 'medium'); ?><a href="<?php echo get_attachment_link($gal_id) ?>"><span class="overlay"></span></a></div>
	<?php endforeach; ?>		

</div>
<?php 
$ret = ob_get_contents();
ob_end_clean();

if(!is_singular() && r_option('style_blog')=='grid'){
$post->post_content = preg_replace('/(^([\r\n]))*(\[gallery.*?\])([\r\n])*/m', '', $post->post_content);
setup_postdata($post); 
 ?>
<div class="the-post-thumbnail"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail('portfolio-thumbnail', array('class' => 'content')) ?></a></div>
<?php 
}else{
$post->post_content = preg_replace('/(^([\r\n]))*(\[gallery.*?\])([\r\n])*/m', $ret, $post->post_content);
setup_postdata($post); 
}

else:	
?>

<?php if(r_option('auto_thumbnail')&&(!($t_bussy))): ?>
	<div class="the-post-thumbnail"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail('blog-post-thumbnail', array('class' => 'content')) ?></a></div>
<?php endif; ?>

<?php endif; ?>

