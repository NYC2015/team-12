<?php
/*
Template Name: Fullwidth Page
*/
?>

<?php get_header(); ?>
<div id="container">
	<div id="site-description">
		<h1><?php the_title(); ?></h1>
	</div>
	
	<article id="content" class="fullwidth">
		<?php the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<div class="entry-content">
		<?php 
		if ( has_post_thumbnail() ) {
		the_post_thumbnail();
		} 
		?>
		<?php the_content(); ?>
		<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'fivehundred' ) . '&after=</div>') ?>
		</div>
		</div>
	
	</article>
<div class="clear"></div>
</div>
<?php get_footer(); ?>