<?php if ( is_paged() ) { ?>
	<div class="pagination">
		<div class="nav-previous alignleft"><?php next_posts_link( __( 'Older Entries', 'fivehundred' ), $query->max_num_pages ); ?></div>
		<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer Entries', 'fivehundred' ) ); ?></div>
	</div>
<?php } ?> 