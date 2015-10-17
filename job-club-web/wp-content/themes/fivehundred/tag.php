<?php get_header(); ?>
<div id="container">
	<div id="content">
		<?php the_post(); ?>
		<h1 class="page-title"><?php _e( 'Tag Archives:', 'fivehundred' ) ?> <span><?php single_tag_title() ?></span></h1>
		<?php rewind_posts(); ?>
		<?php //get_template_part( 'nav', 'above' ); ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'entry' ); ?>
		<?php endwhile; ?>
		<?php get_template_part( 'nav', 'below' ); ?>
	</div>
	<?php get_sidebar(); ?>
<div class="clear"></div>
</div>
<?php get_footer(); ?>