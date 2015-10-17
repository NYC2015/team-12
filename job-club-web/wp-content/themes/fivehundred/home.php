<?php
/*
Template Name: Blog Page
*/
?>
<?php get_header(); ?>
<div id="container" class="blog">
	<div id="content">
		<?php get_template_part( 'loop', 'blog' ); ?>
		<?php get_template_part( 'nav', 'below' ); ?>
	</div>
	<?php get_sidebar(); ?>
	<div class="clear"></div>
</div>
<?php get_footer(); ?>