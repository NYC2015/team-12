<div class="entry-summary">
	<?php if (has_post_thumbnail()) { ?>
			<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-thumb' ); ?>
			
		    <h2 class="entry-title featuredimage" style="background-image: url('<?php echo $image[0]; ?>')">
		    <div class="headlinewrapper"><span class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span> <?php get_template_part( 'entry', 'meta' ); ?><div class="clear"></div></div></h2>
	<?php } else { ?>
			<h2 class="entry-title"><span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span> <?php get_template_part( 'entry', 'meta' ); ?><div class="clear"></div></h2>
	<?php }
	if ($post->post_type == 'ignition_product') {
		echo apply_filters('the_content', get_post_meta($post->ID, 'ign_project_long_description', true));
	}
	else {
		the_excerpt();
	}
	if(is_search()) {
		wp_link_pages();
	}
	?>
</div> 