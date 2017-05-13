<!-- =========================
     FOOTER SECTION 
============================== -->



<footer class="footer <?php echo esc_attr(r_option('footer_style')) ?>">
	<?php if(!r_option('ajax_posts')||!is_home()): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-14 col-md-offset-4">
                <div class="footer-widgets row">
                    <div class="col-footer col-sm-<?php echo esc_attr(r_option('width_foo1')) ?>">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
                   <div class="col-footer col-sm-<?php echo esc_attr(r_option('width_foo2')) ?> ">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
                    <div class="col-footer col-sm-<?php echo esc_attr(r_option('width_foo3')) ?> ">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
                </div> <!-- end of .footer-widgets -->

            </div>
            
        </div>
    </div>
	<?php endif; ?>
    <!-- /END CONTAINER -->

    <hr class="fullwd">
    <div class="container-fluid">
        <p class="copyright"><?php echo wp_kses_post(r_option('copyrights')) ?></p>
    </div>

</footer>
<!-- /END FOOTER SECTION -->

<!-- ANALYTICS -->
<?php echo stripslashes((r_option('analyticscode')));  ?>
<script type="text/javascript">
<?php echo stripslashes(esc_js(r_option('custom_js')));?>
</script>
<?php wp_footer(); ?>
</body>
</html>