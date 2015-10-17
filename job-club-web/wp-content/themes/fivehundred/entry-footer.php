<?php global $post; 
if ( 'post' == $post->post_type ) : ?>
	<div class="entry-footer">	
	<a href="<?php the_permalink(); ?>"><?php _e('Read More...', 'fivehundred'); ?></a>	
	</div>
<?php endif; ?>