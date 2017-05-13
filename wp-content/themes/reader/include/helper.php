<?php 
add_action('wp_ajax_blog_load_more', 'r_blog_load_more_a');
 add_action('wp_ajax_nopriv_blog_load_more', 'r_blog_load_more_a');
function r_blog_load_more_a() {
if(isset($_POST['offset'])&& isset($_POST['type_Style'])){
	
	if($_POST['type_Style']=='grid'){
	$offset = intval($_POST['offset']);
	?>
	<div class="row">
		<div class="col-md-12">

		<?php  
		$ret = '';
		$n_load_g = r_option('n_load_g');
		if($n_load_g == ''){
		$n_load_g = 3;
		}
		$my_query = new WP_Query(array('post_type' => 'post','offset' => esc_sql($offset), 'posts_per_page' => $n_load_g,'post_status' => array('publish') ));
		$count = 0;
		if($my_query->have_posts()):
		while ( $my_query->have_posts() ) : $my_query->the_post();
				
		
			
		$title_post = get_the_title();
		if($title_post==""){
			$title_post = '(Untitled)';
		}
		if($count%2==0):
		?>
			<article <?php post_class('blog-item'); ?>>
				<?php get_template_part( 'content', 'gallery' ); ?>
                <header>
                        <h2 class="title">
							<?php if(is_sticky()):?>
							<span class="sticky_label">Featured<i class="fa fa-chevron-right"></i></span>
							<?php endif; ?>
                            <a href="<?php the_permalink() ?>"><?php echo esc_html($title_post) ?></a>
                        </h2>
                        <div class="meta-info">                           
							<?php get_template_part('content','meta') ?>                                
                        </div>
                </header>
				<div class="post-body">
					<?php
					$words = explode(" ",get_the_content());
					$word_limit = r_option('word_limit');
					if(r_option('style_blog')=='grid'){
					 $word_limit = r_option('word_limit_grid');
					}
					if($word_limit == ""){
						$word_limit =40;
					}
					$post_content =  implode(" ",array_splice($words,0,$word_limit));
					echo apply_filters( 'the_content', $post_content); 
					?>
					<?php 
						wp_link_pages( array(
							'before'      => '<div class="pagination"><div class="navigate-page"><span class="page-links-title">' . __( 'Pages:', 'reader' ) . '</span>',
							'after'       => '</div></div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					?>
					<p><a href="<?php the_permalink() ?>" class="btn btn-prime btn-read-more btn-small"><?php _e('Read More','reader') ?></a></p>
				</div>
            </article>

		<?php else://even ?>
		<?php
		ob_start();  	
		?>
			<article <?php post_class('blog-item'); ?>>
			<?php get_template_part( 'content', 'gallery' ); ?>
                <header>
                    <h2 class="title">
						<?php if(is_sticky()):?>
						<span class="sticky_label">Featured<i class="fa fa-chevron-right"></i></span>
						<?php endif; ?>
                        <a href="<?php the_permalink() ?>"><?php echo esc_html($title_post) ?></a>
                    </h2>
					<div class="meta-info">                           
						<?php get_template_part('content','meta') ?>                                
                    </div>
                </header>
				<div class="post-body">
					<?php
					$words = explode(" ",get_the_content());
					$word_limit = r_option('word_limit');
					if(r_option('style_blog')=='grid'){
					 $word_limit = r_option('word_limit_grid');
					}
					if($word_limit == ""){
						$word_limit =40;
					}
					$post_content =  implode(" ",array_splice($words,0,$word_limit));
					echo apply_filters( 'the_content', $post_content); 
					?>
					<?php 
						wp_link_pages( array(
							'before'      => '<div class="pagination"><div class="navigate-page"><span class="page-links-title">' . __( 'Pages:', 'reader' ) . '</span>',
							'after'       => '</div></div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					?>
					<p><a href="<?php the_permalink() ?>" class="btn btn-prime btn-read-more btn-small"><?php _e('Read More','reader') ?></a></p>
				</div>
            </article>

			<?php 
			$ret .= ob_get_contents();
			ob_end_clean();
			?>
			<?php endif;//odd ?>
			<?php $count++; ?>
			<?php endwhile; ?>
		</div>	
			
		<div class="col-md-12">
		<?php echo ($ret) ?>			
		</div>
				
	</div> <!-- row end -->
		
	
	
	
	
	
	
	<div class="content-loader">
            <a class="jscroll-load-more btn btn-prime btn-small" href="contents/blog-post-set-2.html"><?php _e('Load','reader') ?></a>
    </div> 
	<?php 

	endif;

	if($count == 0){
		echo "##nopost##";
	}
	wp_reset_postdata();
	
	
	
	
	
	
	

	
	
	
	
	
	

	}else{//grid
	
	
	
    $offset = intval($_POST['offset']);
	$n_load = r_option('n_load');
	if($n_load == ''){
	$n_load = 3;
	}
	$my_query = new WP_Query(array('post_type' => 'post','offset' => esc_sql($offset), 'posts_per_page' => $n_load,'post_status' => array('publish') ));

	$count = 0;
	
	if($my_query->have_posts()):
	while ( $my_query->have_posts() ) : $my_query->the_post();
		
	

	?>
	
		
        <article class="blog-item">
		
			
            <header>
                <h2 class="title">
					<?php if(is_sticky()):?>
						<span class="sticky_label"><?php _e('Featured','reader') ?><i class="fa fa-chevron-right"></i></span>
					<?php endif; ?>
					<?php 
					$title_post = get_the_title();
					if($title_post==""){
					$title_post = '(Untitled)';
					}
					?>
                    <a href="<?php the_permalink() ?>"><?php echo esc_html($title_post) ?></a>
                </h2>
                <div class="meta-info">                           
					<?php get_template_part('content','meta') ?>                                
                </div>
            </header>
			<?php get_template_part( 'content', 'gallery' ); ?>
            <div class="post-body">
				
				<?php
				$words = explode(" ",get_the_content());
				$word_limit = r_option('word_limit');
				if($word_limit == ""||r_option('style_blog')=='grid'){
					$word_limit =40;
				}
				$post_content =  implode(" ",array_splice($words,0,$word_limit));
				echo apply_filters( 'the_content', $post_content); 
				?>
				<?php 
						wp_link_pages( array(
							'before'      => '<div class="pagination"><div class="navigate-page"><span class="page-links-title">' . __( 'Pages:', 'reader' ) . '</span>',
							'after'       => '</div></div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
						) );
					?>
			</div>
        </article>

       
		<hr>
				
				
		
	
	
	
	
	
	
	<?php  
	$count++;
	endwhile;
	 ?>
	<div class="content-loader">
            <a class="jscroll-load-more btn btn-prime btn-small" href="contents/blog-post-set-2.html"><?php _e('Load','reader') ?></a>
    </div> 
	<?php 

	endif;

	if($count == 0){
		echo "##nopost##";
	}
	wp_reset_postdata();
	}
}
    die();
}

function show_archive(){
 ?>
 <h4 class="title_archive"><?php if(is_category()){?>
				<?php printf(esc_attr(r_option('label_category')),str_replace('-',' ',get_query_var('category_name'))) ?>
				<?php }elseif(is_tag()){?>
				<?php printf(esc_attr(r_option('label_tag')),single_tag_title("", false)) ?>
				<?php }elseif(is_day()){?>
				<?php printf(esc_attr(r_option('label_time_day')),get_the_time('F jS, Y')) ?>
				<?php }elseif(is_month()){?>
				<?php printf(esc_attr(r_option('label_time_month')),get_the_time('F, Y')) ?>
				<?php }elseif(is_year()){?>
				<?php printf(esc_attr(r_option('label_time_year')),get_the_time('Y')) ?>
				<?php }elseif(is_author()){?>
				<?php printf(__('%s\' Post','reader'),get_the_author()) ?>
				<?php }?></h4>
			<?php if(is_post_type_archive('jobpost')): ?>
			<h4 class="title_archive"><?php echo esc_html(r_option('label_job')) ?></h4>
			<?php endif; ?>
<?php 
}

function show_search(){

echo '<h4 class="title_archive">';
 printf(esc_attr(r_option('label_search')),get_search_query());
 echo '</h4>';
}