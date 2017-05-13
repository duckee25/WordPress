<?php
	//get theme options
	$footer_text = of_get_option('footer_text', '' );
?>
	</div><!-- #main .site-main -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<div class="row-fluid" id="footer-body">
				<?php dynamic_sidebar('footer-widgets'); ?>
			</div>
		</div><!-- .site-info -->
		<?php if(!empty($footer_text)){?>
		<div class="row-fluid" id="footer-bottom">
			<?php echo str_replace("{year}", date('Y'), $footer_text); ?>
		</div>	
		<?php } 
		?>
	</footer><!-- #colophon .site-footer -->
</div><!-- #page -->
<?php get_template_part( 'demo/customizer'); ?>
<?php wp_footer(); ?>
</body>
</html>