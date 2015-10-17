<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		if(is_archive() || is_search() || is_home() || is_page_template('home.php')){
			get_template_part('entry','summary');
		} else {
			get_template_part('entry','content');
		}
		?>
		<?php 
		if ( is_single() ) {
			get_template_part( 'entry-footer', 'single' ); 
		} else {
			//get_template_part( 'entry-footer' ); 
		}
	?>
</div>