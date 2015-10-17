<?php global $post; ?>
<?php get_header(); ?>
<div id="container">
	<div id="site-description">
		<h2><?php bloginfo( 'description' ) ?></h2>
	</div>
	<h2 class="entry-title"><?php single_cat_title(); ?></h2>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div id="content">
			<div id="category-grid">
				<?php 
					// Start the loop
					if ( have_posts() ) : while ( have_posts() ) : the_post();
						get_template_part('entry');
						endwhile;
						endif; 
					wp_reset_postdata();
					next_posts_link(__('&laquo; Older Entries', 'fivehundred'));
					previous_posts_link(__('Newer Entries &raquo;', 'fivehundred'));
				?>
				<?php get_template_part( 'nav', 'below' ); ?>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
<div class="clear"></div>
</div>
<?php get_footer(); ?>