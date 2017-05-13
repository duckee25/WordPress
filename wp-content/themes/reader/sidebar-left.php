        <!-- =========================
             LEFT SIDEBAR - NAVIGATION 
        ============================== -->	
        <aside class="left-sidebar hidden-xs">
			<?php if(r_option('left_sidebar_fixed')): ?>
			<div class="col-md-4 col-sm-6 items">
			</div>
			<?php endif; ?>
            <div class="col-md-4 col-sm-6 items <?php if(r_option('left_sidebar_fixed')) echo 'left-sidebar-fixed' ?>">
                <nav class="left-navigation">
                    <ul>
						<?php dynamic_sidebar( 'left-sidebar' ); ?>
                    </ul>
                </nav> <!-- end of .left-navigation -->

             
            </div>
			
        </aside>
        <!-- /END LEFT SIDEBAR - NAVIGATION -->
