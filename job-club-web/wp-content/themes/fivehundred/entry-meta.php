<?php global $authordata; ?>
<div class="entry-meta">
<span class="meta-prep meta-prep-author"><?php _e('By', 'fivehundred'); ?> </span>
<span class="author vcard"><a class="url fn n" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php printf( __( 'View all articles by %s', 'fivehundred' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span>
<span class="meta-sep"> | </span>
<span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time( get_option( 'date_format' ) ); ?></abbr></span>
</div>