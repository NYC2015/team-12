<div class="entry-content">
	<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php 
		if ( has_post_thumbnail() ) {
			the_post_thumbnail('projectpage-large', array('class' => 'singlethumb'));
		} 
	?>
	<?php get_template_part( 'entry', 'meta' ); ?>
		<?php the_content(); ?>
</div>