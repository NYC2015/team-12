<?php
	$settings = get_option('fivehundred_theme_settings');
	$home_projects = $settings['home_projects'];
	$options = get_option('fivehundred_featured');
	$posts_per_page = get_option('posts_per_page');
	if (!empty($options)) {
		$featured_proj = $options['project_id'];
		$featured_post = $options['post_id'];
	}
	if (is_home() || is_front_page() || is_page_template('page-grid-template.php')) {
		$project_count = $home_projects;
	}
	else if (is_archive()) {
		$project_count = (!empty($posts_per_page) ? $posts_per_page : 9);
	}
	else {
		$project_count = 1;
	}
	if (is_home() || is_front_page() || is_archive() || is_page_template('page-grid-template.php')) {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$args = array('post_type' => 'ignition_product', 'posts_per_page' => $project_count, 'paged' => $paged);
		if (!empty($options)) {
			$args['post__not_in'] = array($featured_post);
		}
		$newargs = apply_filters('project_query', $args);
		$query = new WP_Query($newargs);
		if ( $query->have_posts() ){
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part('project');
			}
		}
	}
	else {
		if ( have_posts() ){
			while ( have_posts() ) {
				the_post();
				get_template_part('project');
			}
		}
	}
	wp_reset_postdata();
?>
	