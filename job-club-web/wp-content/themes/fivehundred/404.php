<?php get_header(); ?>
<div id="container">
	<div id="site-description">
		<h1><?php bloginfo( 'description' ) ?></h1>
	</div>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div id="content">
			<?php //get_template_part( 'nav', 'above' ); ?>
			<div id="404-grid">
				<div id="post-0" class="post error404 not-found">
					<h1 class="entry-title">Not Found</h1>
					<div class="entry-content">
						<p>Nothing found for the requested page. Try a search instead?</p>
						<?php get_search_form(); ?>
					</div>
				</div>
				<?php get_template_part( 'nav', 'below' ); ?>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>