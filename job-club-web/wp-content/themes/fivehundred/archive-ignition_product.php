<?php global $post; ?>
<?php get_header(); ?>
<div id="container">
	<div id="site-description">
		<h1><?php echo apply_filters('project_archive_title', __('All Projects', 'fivehundred')); ?></h1>
	</div>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div id="content">
			<?php get_template_part( 'nav', 'above-grid' ); ?>
			<div id="project-grid">
				<?php 
				if (is_archive('ignition_product')) {
					get_template_part('loop', 'project');
				}
				else {
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					$posts_per_page = get_option('posts_per_page');
					$query = new WP_Query(
						array(
							'paged' => $paged,
							'posts_per_page' => (!empty($posts_per_page) ? $posts_per_page : 9)
						)
					);
					// Start the loop
					if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
						get_template_part('entry');
						endwhile;
						endif; 
					wp_reset_postdata();
				}
				?>
			</div>
			<div style="clear: both;"></div>
			<?php get_template_part( 'nav', 'below' ); ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>