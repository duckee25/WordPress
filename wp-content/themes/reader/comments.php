<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if (!empty($post->post_password)) { // if there's a password
		if ( post_password_required()) {  // and it doesn't match the cookie
			?>

			<p class="nocomments"><?php _e('Enter the password to view comments.','reader') ?></p>

			<?php
			return;
		}
	}

	/* This variable is for alternating comment background */
	$oddcomment = ' alt ';
	global $style;
	
	


?>

<!-- You can start editing here. -->
<!-- Comment Section Begins -->
<section class="comments-area">

<?php if ($comments) : ?>

			
	<!-- Title -->
	<div class="title-accent" data-animated="fadeInUp">
		<h3><?php comments_number(__('0 Comments','reader') ,__('1 Comment','reader'),__('% Comments','reader')) ?></h3>
	</div>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p><?php _e('You are not signed in.','reader') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('Sign in','reader') ?></a> <?php _e('to post comments.','reader') ?></p>
	<?php else: ?>
		<?php if ( $user_ID ) : ?>
		<p><?php _e('Good! you are signed in. You can post comments.','reader') ?></p>
		<?php else: ?>
		<p><?php _e('You can post comments in this post.','reader') ?></p>
		<?php endif; ?>
	<?php endif; ?>
	 <div class="comments">
	<ul>
	<hr class="small">
		<?php wp_list_comments( array( 'callback' => 'r_comment', 'style' => 'ul' ) ); ?>
	</ul>
	</div>
						
					
					
					


	<?php paginate_comments_links();  ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p><?php _e('You are not signed in.','reader') ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('Sign in','reader') ?></a> <?php _e('to post comments.','reader') ?></p>

<?php else : ?>

	<!-- Leave a Comment -->
	<div class="comment-form animated" data-animation="fadeInUp" data-animation-delay="300">
		<h3 id="respond"><?php _e('Post A Reply','reader') ?></h3>
		<p class="form-message" style="display: none;"></p>

		<?php if ( $user_ID ) : ?>
		
		<p><?php _e('Logged in as','reader') ?> <a href="<?php echo esc_url(get_option('siteurl')); ?>/wp-admin/profile.php"><?php echo esc_html($user_identity); ?></a>. <a href="<?php echo esc_url(get_option('siteurl')); ?>/wp-login.php?action=logout" title="Log out of this account"><?php _e('Log out','reader') ?> &raquo;</a></p>
		
		<?php endif; ?>

		<!-- Form Begins -->
		<form role="form" name="contactform" class="form-horizontal" id="contactform" method="post" action="<?php echo esc_url(get_option('siteurl')); ?>/wp-comments-post.php">
			
			<?php if ( !$user_ID ) : ?>
			<!-- Field 1 -->		
			<div class="comment-input ">
				<input type="text" name="author" class="input-name form-input" placeholder="<?php _e('Full Name','reader') ?>" />
			</div>

			<!-- Field 2 -->
			<div class="comment-input ">
				<input type="email" name="email" class="input-email form-input" placeholder="<?php _e('Email','reader') ?>"/>
			</div>
			<div class="comment-input ">
				<input type="text" name="url" class="input-email form-input" placeholder="<?php _e('Website (optional)','reader') ?>"/>
			</div>
			<?php endif; ?>
			<!-- Field 3 -->
			<div class="textarea-message comment-input ">
				<textarea name="comment" class="textarea-message form-input" placeholder="<?php _e('Write your comment here','reader') ?>" rows="8" ></textarea>
			</div>

			<!-- Button -->
			<button class="btn btn-prime btn-mid" type="submit" value="Send Now"><?php _e('Post Comment','reader') ?></button>

			<?php echo get_comment_id_fields( $id ) ?>
			<?php do_action('comment_form', $post->ID); ?>
		</form><!-- Form Ends -->	
	</div>


<?php if(r_option('classic_form')){
	comment_form();
} ?>

<?php endif; // If registration required and not logged in ?>

<?php else: ?>
<h3 class="nocomments post"><?php _e('Comments are closed','reader') ?></h3>
<?php endif; // if you delete this the sky will fall on your head ?>

</section><!-- Comment Section Ends -->	