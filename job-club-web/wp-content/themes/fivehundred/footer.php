</div>
</div>
<footer>
<ul class="footer_widgets">
		<?php if (dynamic_sidebar('footer-widget-area')) : ?><?php endif; ?>
	</ul>
	<div class="footerright">
		<nav id="menu-footer">
		
			<?php
			if ( has_nav_menu( 'footer-menu' ) ) {
			// Using wp_nav_menu() to display menu
			wp_nav_menu( array( 
				'menu' => 'footer-menu', // Select the menu to show by Name
				'container' => false, // Remove the navigation container div
				'theme_location' => 'footer-menu' 
				)
			);
			}
			?>
		</nav>
	</div>
	<div id="copyright">
		
	</div>
	<div class="clear"></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>