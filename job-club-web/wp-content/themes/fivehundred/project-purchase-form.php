<?php
global $post;
$id = $post->ID;
$content = the_project_content($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
?>
<div id="site-description">
	<h1><?php echo $content->name; ?> </h1>
	<h2><?php echo $content->short_description; ?></h2> 
</div>
<div class="entry-content">
	<?php
	echo apply_filters('the_content', do_shortcode('[project_purchase_form]'));
	?>
</div>