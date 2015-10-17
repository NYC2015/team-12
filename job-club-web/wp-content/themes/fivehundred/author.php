<?php get_header(); ?>
<div id="container">
	<div id="content">
		<?php the_post(); ?>
			<h1 class="page-title author">
				Posts by <?php echo $authordata->display_name; ?>:
			</h1>
			<?php $authordesc = $authordata->user_description; ?>
		<?php rewind_posts(); ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'entry' ); ?>
		<?php endwhile; ?>
			<?php get_template_part( 'nav', 'below' ); ?>
	</div>
	<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<?php get_footer(); ?>