<?php global $wp_query; ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div class="pagination">
		<div class="nav-previous alignleft"><?php next_posts_link( __( 'Older Entries', 'fivehundred' ), $wp_query->max_num_pages ); ?></div>
		<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer Entries', 'fivehundred' ) ); ?></div>
	</div>
<?php endif; ?>