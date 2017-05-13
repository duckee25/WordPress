<?php


get_header();
$title_page =  get_the_title();
$desc = esc_attr(get_post_meta( get_the_ID() , 'desc', true ));


if($title_page==""){
	$title_page = '(Untitled)';
}
the_post();?>

	<div id="page-<?php the_ID(); ?>" class="page-default-w">
		<div class="container content-section">
			
			<!-- Title & Desc Row Begins -->
			<div class="row title-row">
				<div class="col-md-12 header text-center">
					<!-- Title --> 
					<h2 class="title capitalize"><?php echo esc_html($title_page)?></h2>
					<!-- Description --> 
					<p class="desc"><?php echo esc_html($desc) ?></p>
				</div>
			</div><!-- Title & Desc Row Ends -->
			
			        <div class="inner portfolio">
            <div class="col-xs-8">
                <!-- Flex Slider -->
                <!-- Slides -->
				<?php get_template_part( 'content', 'gallery' ); ?>                        
                <!-- End Slides -->
		
            </div>
            <div class="col-xs-4">
                <h1 class="project-header bold dark condensed no-padding uppercase"><?php the_title() ?></h1>
                <div class="project-desc light"><?php the_content() ?>
			</div>
               
            </div>
            <div class="clear"></div>
         </div><!-- End Inner Portfolio -->
			
		</div><!-- Container Ends -->

	</div>
<?php get_footer(); ?>