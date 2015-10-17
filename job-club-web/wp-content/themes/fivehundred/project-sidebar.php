<?php
global $post;
$id = $post->ID;
$content = the_project_content($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
?>
<aside id="sidebar">
<h3 id="ign-levels-headline"><?php echo $content->name; ?> Support Levels</h3>
<div id="ign-product-levels" data-projectid="<?php echo $project_id; ?>">
	<?php do_action('id_before_levels', $project_id); ?>
	<?php get_template_part('loop', 'levels'); ?>
	<?php do_action('id_after_levels', $project_id); ?>
</div>
<div class="ign-supportnow mobile">
 	<a href="<?php the_permalink(); ?>?purchaseform=500&amp;prodid=<?php echo $project_id; ?>"><?php _e('Support Now', 'fivehundred'); ?></a>
</div>
<?php
$settings = getSettings();
?>
<?php if ($settings->id_widget_logo_on == 1) {
	echo '<div id="poweredbyID"><span><a href="http://www.ignitiondeck.com" title="Crowdfunding Wordpress Theme by IgnitionDeck"></a></span></div>';
} ?>
<?php if ( is_active_sidebar('projects-widget-area') ) : ?>
<div id="primary" class="widget-area">
	<ul class="sid">
		<?php dynamic_sidebar('projects-widget-area'); ?>
	</ul>
</div>
<?php endif; ?>
</aside>