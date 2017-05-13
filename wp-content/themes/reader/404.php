<?php 

 get_header();

 ?>
 
 <!-- BODY CONTAINER - FULL WIDTH -->
<div class="container">
    <div class="row">
		
 
        <!-- =========================
             BLOG SECTION 
        ============================== -->
        <div class="col-md-24">
            <div class="blog-style-one">
                <!-- GENERAL BLOG POST -->
				
				
                <article class="blog-item ">
					
                    
					<div class="post-body">
					
					<div class="box-404">
					<h5 class="bigger">404</h5>
					<h1><?php echo esc_attr(r_option('404_title')); ?></h1>
					<a href="<?php echo home_url(); ?>" class="btn btn-prime btn-lg"><?php _e('BACK HOME','reader') ?> <i class="fa icon-left fa-home"></i></a>
				</div>
					
					</div>
                </article>
				<div class="social-share">
                   
                </div> <!-- end of .social-share -->
              <!-- end of .social-share -->		
				
                <!-- /PAGINATION -->
            </div>
        </div>
        <!-- /END BLOG SECTION -->
    </div>
</div> <!-- end of .container-fluid -->

<?php get_footer() ?>


