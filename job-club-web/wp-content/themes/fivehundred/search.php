<?php get_header(); ?>
<div id="container">
<div id="content">
	<?php if ( have_posts() ) : ?>
		<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'fivehundred' ), '<span>' . get_search_query()  . '</span>' ); ?></h1>
		<?php //get_template_part( 'nav', 'above' ); ?>
		<?php while ( have_posts() ) : the_post() ?>
		<?php get_template_part( 'entry' ); ?>
		<?php endwhile; ?>
		<?php get_template_part( 'nav', 'below' ); ?>
		<?php else : ?>
		<div id="post-0" class="post no-results not-found">
		<h2 class="entry-title"><?php _e( 'Nothing Found', 'fivehundred' ) ?></h2>
		<div class="entry-content">
		<p><?php _e( 'Sorry, nothing matched your search. Please try again.', 'fivehundred' ); ?></p>
		<?php get_search_form(); ?>
		</div>
		</div>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<?php get_footer(); ?>