<nav id="menu-header" class="menu">
	<?php 
	// Using wp_nav_menu() to display menu
	wp_nav_menu( array( 
		'menu' => 'main-menu', // Select the menu to show by Name
		'class' => '',
		'container' => false, // Remove the navigation container div
		'theme_location' => 'main-menu' 
		)
	);
	?>
	<div class="clear"></div>
</nav>