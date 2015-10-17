<?php

//error_reporting(E_ALL);
//@ini_set('display_errors', 1);

// Auto-Updates for Theme 500
/**************************************************/
$api_url = 'https://ignitiondeck.com/id/pluginserv/';

if(function_exists('wp_get_theme')){
    $theme_data = wp_get_theme(get_option('template'));
    $theme_version = $theme_data->Version;  
} else {
    $theme_data = get_theme_data( TEMPLATEPATH . '/style.css');
    $theme_version = $theme_data['Version'];
}    
$theme_base = get_option('template');
/**************************************************/
add_filter('pre_set_site_transient_update_themes', 'check_fivehundred_update');

function check_fivehundred_update($checked_data) {
	global $wp_version, $theme_version, $theme_base, $api_url;

	$request = array(
		'slug' => $theme_base,
		'version' => $theme_version 
	);

	// Start checking for an update
	$send_for_check = array(
		'body' => array(
			'action' => 'theme_update', 
			'request' => serialize($request),
			'api-key' => md5(get_bloginfo('url'))
		),
		'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
	);

	$raw_response = wp_remote_post($api_url, $send_for_check);

	if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
		$response = unserialize($raw_response['body']);

	// Feed the update data into WP updater
	if (!empty($response)) 
		$checked_data->response[$theme_base] = $response;

	return $checked_data;
}

// Take over the Theme info screen on WP multisite
add_filter('themes_api', 'fivehundred_api_call', 10, 3);

function fivehundred_api_call($def, $action, $args) {
	global $theme_base, $api_url, $theme_version, $api_url;
	
	if ($args->slug != $theme_base)
		return false;
	
	// Get the current version

	$args->version = $theme_version;
	$request_string = prepare_request($action, $args);
	$request = wp_remote_post($api_url, $request_string);

	if (is_wp_error($request)) {
		$res = new WP_Error('themes_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
	} else {
		$res = unserialize($request['body']);
		if ($res === false)
			$res = new WP_Error('themes_api_failed', __('An unknown error occurred'), $request['body']);
	}
	
	return $res;
}

if (is_admin())
	$current = get_transient('update_themes');
/**************************************************/

include 'classes/class-video-widget.php';
//include 'classes/class-music-widget.php';
include 'classes/class-content-widget.php';
include 'classes/class-content-level-widget.php';
include 'classes/class-content-wide-widget.php';
include 'classes/class-content-wide-custom-widget.php';
include 'classes/class-content-alert-widget.php';
//include 'classes/class-project-grid-widget.php';

/**
 * Register ignitiondeck domain for translation texts
 */

function fivehundred_init() {
	load_theme_textdomain('fivehundred', get_template_directory().'/languages/');
}

add_action('after_setup_theme', 'fivehundred_init');

function fivehundred_dequeue() {
	$disable_skins = false;
	if (isset($_GET['purchaseform'])) {
		remove_filter('the_content', 'wpautop');
		$disable_skins = true;
		
	}
	else if (isset($_GET['create_project'])) {
		$disable_skins = true;
	}
	else if (isset($_GET['edit_project'])) {
		$disable_skins = true;
	}
	else if (isset($_GEt['action']) && $_GET['action'] == 'register') {
		$disable_skins = true;
	}
	if ($disable_skins) {
		global $theme_base;
		if (isset($theme_base) && $theme_base == 'fivehundred') {
			$theme_name = getThemeFileName();
			if (!empty($theme_name)) {
				wp_dequeue_style($theme_name);
			}
		}
	}
}

add_action('wp_enqueue_scripts', 'fivehundred_dequeue');

function fivehundred_register_admin_menu() {
	add_theme_page('500 Settings', '500 Settings', 'manage_options', 'theme-settings', 'fivehundred_admin_menu', plugins_url( '/ignitiondeck/images/ignitiondeck-menu.png'));
}

add_action('admin_menu', 'fivehundred_register_admin_menu');

function fivehundred_admin_menu() {
	if (isset($_POST['submit-theme-settings'])) {
		$posts = array();
		foreach ($_POST as $k=>$v) {
			$posts[$k] = esc_attr($v);
		}
		do_action('fh_settings_submit', $posts);
		$logo = esc_attr($_POST['logo-input']);
		$about = stripslashes($_POST['about-us']);
		if (isset($_POST['choose-home'])) {
			$home = absint($_POST['choose-home']);
		}
		else {
			$home = null;
		}
		$home_projects = absint($_POST['home-projects']);
		$blog_page = absint($_POST['blog_page']);
		$custom_css = stripslashes($_POST['custom_css']);
		$ga = stripslashes($_POST['ga']);
		if (isset($_POST['twitter-button'])) {
			$twitter = absint($_POST['twitter-button']);
		}
		else {
			$twitter = 0;
		}
		if (isset($_POST['fb-button'])) {
			$fb = absint($_POST['fb-button']);
		}
		else {
			$fb = 0;
		}
		if (isset($_POST['g-button'])) {
			$google = absint($_POST['g-button']);
		}
		else {
			$google = 0;
		}
		if (isset($_POST['li-button'])) {
			$li = absint($_POST['li-button']);
		}
		else {
			$li = 0;
		}
		$twitter_via = esc_attr(str_replace('@', '', $_POST['twitter-via']));
		$fbname = esc_attr($_POST['fb-via']);
		$gname = esc_attr($_POST['g-via']);
		$liname = esc_attr($_POST['li-via']);
		
		$settings = array('logo' => $logo,
						'about' => esc_html(html_entity_decode($about)),
						'home' => $home,
						'home_projects' => $home_projects,
						'blog_page' => $blog_page,
						'custom_css' => $custom_css,
						'ga' => $ga,
						'twitter' => $twitter,
						'twitter_via' => $twitter_via,
						'fb' => $fb,
						'fb_via' => $fbname,
						'google' => $google,
						'g_via' => $gname,
						'li' => $li,
						'li_via' => $liname);
		update_option('fivehundred_theme_settings', apply_filters('fh_theme_settings', $settings, $posts));
		if (isset($_POST['choose-featured']) && $_POST['choose-featured'] > 0) {
			$project_id = absint($_POST['choose-featured']);
			$project = new ID_Project($project_id);
			$post_id = $project->get_project_postid();
			if (!empty($post_id)) {
				$options = array(
					'post_id' => $post_id,
					'project_id' => $project_id);
				update_option('fivehundred_featured', apply_filters('fh_featured', $options));
			}
		}
		else {
			delete_option('fivehundred_featured');
		}
		echo '<div class="updated fade below-h2" id="message"><p>'.__('Settings Saved', 'fivehundred').'</p></div>';
	}
	else {
		$settings = get_option('fivehundred_theme_settings');
		$logo = $settings['logo'];
		$about = html_entity_decode($settings['about']);
		$home_projects = $settings['home_projects'];
		$blog_page = $settings['blog_page'];
		$custom_css = stripslashes($settings['custom_css']);
		$ga = stripslashes($settings['ga']);
		$options = get_option('fivehundred_featured');
		if ($options) {
			$post_id = $options['post_id'];
			$project_id = $options['project_id'];
		}
	}
	// set up the project home page dropdown
	if (class_exists('ID_Project')) {
		$projects = ID_Project::get_all_projects();
		$levels = '<select id="choose-home" name="choose-home">'.
			apply_filters("home_grid_option", '<option value="">'.__("Grid Layout", "fivehundred").'</option>');
		foreach ($projects as $project) {
			$a_project = new ID_Project($project->id);
			$post_id = $a_project->get_project_postid();
			$selected = null;
			if (isset($_POST['choose-home']) && $_POST['choose-home'] == $project->id) {
				$selected = 'selected="selected"';
			}
			else if ( isset($settings['home']) && $settings['home'] == $project->id) {
				$selected = 'selected="selected"';
			}
			$post = get_post($post_id);
			if (!empty($post)) {
				$levels .= '<option value="'.$project->id.'" '.(isset($selected) ? $selected : '').'>'.__('Single Project', 'fivehundred').': '.stripslashes(get_the_title($post_id)).'</option>';
			}
		}
		$levels .='</select>';
	}
	else {
		$levels = null;
	}
	include 'templates/admin/_themeSettings.php';
}

/* A bunch of filters for category and archive pages */

add_action('init', 'custom_project_filters');

function custom_project_filters() {
	if (isset($_GET)) {
		if (isset($_GET['project_filter'])) {
			add_filter('project_query', 'apply_project_filters');
		}
		else if (isset($_GET['id_category'])) {
			add_filter('project_query', 'apply_project_category', 1);
		}
	}
}

function apply_project_filters($args) {
	$filter = $_GET['project_filter'];
	if (isset($_GET['order'])) {
		$order = $_GET['order'];
	}
	else {
		$order = 'DESC';
	}
	if ($filter == 'date') {
		$args['orderby'] = 'date';
	}
	else {
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = $filter;
	}
	$args['order'] = $order;
	return $args;
}

function apply_project_category($args) {
	$tax_slug = $_GET['id_category'];
	if (!empty($tax_slug)) {
		$tax_cat = get_term_by('slug', $tax_slug, 'project_category');
		if (!empty($tax_cat)) {
			$args['project_category'] = $tax_slug;
		}
	}
	return $args;
}

add_action('pre_get_posts', 'set_home_project_query');

function set_home_project_query($query) {
	if (is_home() && $query->is_main_query()) {
		$options = get_option('fivehundred_theme_settings');
		if (!empty($options)) {
			$home = $options['home'];
			if (!empty($home) && $home > 0) {
				$project_id = $home;
				if (class_exists('ID_Project')) {
					$project = new ID_Project($project_id);
					$post_id = $project->get_project_postid();
					if (isset($post_id) && $post_id > 0) {
						$query->set('p', $post_id);
						$query->set('post_type', 'ignition_product');
					}
				}
			}
		}
	}
	return;
}

//add_action('pre_get_posts', 'projects_archive_display');

function projects_archive_display($query) {
	if (is_post_type_archive('ignition_product')) {
		$query->set('posts_per_page', 9);
		return;
	}
}

add_filter('pre_get_posts', 'add_projects_to_cat');

function add_projects_to_cat($query) {
	if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
		$post_types = get_post_types();
		$post_types = array_merge($post_types, array('ignition_product'));
		$query->set('post_type', $post_types);
	}
	return $query;
}

function the_project_summary($id) {
	$post = get_post($id);
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$project = new ID_Project($project_id);
	$image_url = the_project_image($id, "1");
	$name = $post->post_title;
	$short_desc = html_entity_decode(get_post_meta($id, 'ign_project_description', true));
	$total = apply_filters('id_funds_raised', $project->get_project_raised(), $id);
	$goal = apply_filters('id_project_goal', $project->the_goal(), $id);
	$end = get_post_meta($id, 'ign_fund_end', true);
	$end_type = get_post_meta($id, 'ign_end_type', true);
	$days_left = $project->days_left();
	$pledgers = apply_filters('id_number_pledges', $project->get_project_orders(), $id);
	// ID Function
	// GETTING product default settings
	$default_prod_settings = getProductDefaultSettings();

	// Getting product settings and if they are not present, set the default settings as product settings
	$prod_settings = getProductSettings($project_id);
	if (empty($prod_settings)) {
		$prod_settings = $default_prod_settings;
	}
	$currency_code = $prod_settings->currency_code;
	//GETTING the currency symbols
	$cCode = setCurrencyCode($currency_code);

	if ($end !== '') {
		$show_dates = true;
	}
	else {
		$show_dates = false;
	}
	
	// percentage bar
	$percentage = apply_filters('id_percentage_raised', $project->percent(), apply_filters('id_funds_raised', $project->get_project_raised(), $id, true), $id, apply_filters('id_project_goal', $project->the_goal(), $id, true));
	$successful = get_post_meta($id, 'ign_project_success', true);
	
	$summary =  new stdClass;
	$summary->end_type = $end_type;
	$summary->image_url = $image_url;
	$summary->name = $name;
	$summary->short_description = $short_desc;
	$summary->total = $total;
	$summary->goal = $goal;
	$summary->pledgers = $pledgers;
	$summary->show_dates = $show_dates;
	if ($show_dates == true) {
		$summary->days_left = $days_left;
	}
	$summary->percentage = $percentage;
	$summary->successful = $successful;
	$summary->currency_code = $cCode;
	return $summary;
}

function the_project_content($id) {
	$post = get_post($id);
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$name = $post->post_title;
	$short_desc = html_entity_decode(get_post_meta($id, 'ign_project_description', true));
	$long_desc = get_post_meta($id, 'ign_project_long_description', true);
	
	$content = new stdClass;
	$content->name = $name;
	$content->short_description = $short_desc;
	$content->long_description = apply_filters('fh_project_content', html_entity_decode($long_desc), $project_id);
	return $content;
}
/**
This function is now  deprecated -> now pulled from the IgnitionDeck Deck class hDeck method
*/
function the_project_hDeck($id) {
	// deprecated -> now pulled from the IgnitionDeck Deck class hDeck method
// *payment button, *learn more,
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$goal = the_project_goal($id);

	$total = get_fund_total($id);
	// GETTING product default settings
	$default_prod_settings = getProductDefaultSettings();
	$end_type = get_post_meta($id, 'ign_end_type', true);

	// Getting product settings and if they are not present, set the default settings as product settings
	$prod_settings = getProductSettings($project_id);
	if (empty($prod_settings)) {
		$prod_settings = $default_prod_settings;
	}
	$currency_code = $prod_settings->currency_code;
	//GETTING the currency symbols
	$cCode = setCurrencyCode($currency_code);
	// date info
	$end_raw = get_post_meta($id, 'ign_fund_end', true);

	if ($end_raw !== '') {
		$show_dates = true;
		$end = str_replace('/', ' ', $end_raw);
		$end = explode(' ', $end);
		$end_string = $end[2].'-'.$end[0].'-'.$end[1];

		$month = $end[0];
		$day = $end[1];
		$year = $end[2];
		$end_date = strtotime($end_string);
		$now = time();
		// days left
		$dif = number_format(($end_date - $now)/86400);
		if ($dif < 0) {
			$dif = 0;
		}
	}
	else {
		$show_dates = false;
	}

	// percentage bar
	if ($total == 0) {
		$percentage = 0;
	}
	else {
		$percentage = $total / $goal * 100;
	}
	$pledges_count = get_backer_total($id);
	$button_url = null;

	$hDeck = new stdClass;
	$hDeck->end_type = $end_type;
	$hDeck->goal = $goal;
	$hDeck->total = $total;
	$hDeck->show_dates = $show_dates;
	if ($show_dates == true) {
		$hDeck->end = $end_raw;
		$hDeck->day = $day;
		$hDeck->month = apply_filters('id_end_month', date('F', mktime(0, 0, 0,$month, 10)));
		$hDeck->year = $year;
		$hDeck->days_left = $dif;
	}
	
	$hDeck->percentage = $percentage;
	$hDeck->pledges = $pledges_count;
	$hDeck->currency_code = $cCode;
	return $hDeck;
}

function the_project_video($id) {
	$video = get_post_meta($id, 'ign_product_video', true);
	return html_entity_decode($video);
}

/**
This function is now  deprecated -> now pulled from the IgnitionDeck Deck class the_deck method
*/
function the_levels($id) {
	global $wpdb;
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$level_count = get_post_meta($id, 'ign_product_level_count', true);

	// GETTING product default settings
	$default_prod_settings = getProductDefaultSettings();

	// Getting product settings and if they are not present, set the default settings as product settings
	$prod_settings = getProductSettings($project_id);
	if (empty($prod_settings)) {
		$prod_settings = $default_prod_settings;
	}
	$currency_code = $prod_settings->currency_code;
	//GETTING the currency symbols
	$cCode = setCurrencyCode($currency_code);
	$level_data = array();
	for ($i=1; $i <= $level_count; $i++) {
		$level_sales = $wpdb->prepare('SELECT COUNT(*) as count FROM '.$wpdb->prefix.'ign_pay_info WHERE product_id=%d AND product_level = %d', $project_id, $i);
		$return_sales = $wpdb->get_row($level_sales);
		$level_sales = $return_sales->count;
		if ($i == 1) {
			$level_title = html_entity_decode(get_post_meta($id, 'ign_product_title', true));
			$level_desc = html_entity_decode(get_post_meta($id, 'ign_product_details', true));
			$level_price = get_post_meta($id, 'ign_product_price', true);
			if ($level_price > 0) {
				$level_price = number_format($level_price, 0, '.', ',');
			}
			$level_limit = get_post_meta($id, 'ign_product_limit', true);
			$level_order = get_post_meta($id, 'ign_projectmeta_level_order', true);
			$level_data[] = array('id' => $i,
			'title' => $level_title,
			'description' => $level_desc,
			'price' => $level_price,
			'sold' => $level_sales,
			'limit' => $level_limit,
			'currency_code' => $cCode,
			'order' => $level_order);	
		}
		else {
			$level_title = html_entity_decode(get_post_meta($id, 'ign_product_level_'.$i.'_title', true));
			$level_desc = html_entity_decode(get_post_meta($id, 'ign_product_level_'.$i.'_desc', true));
			$level_price = get_post_meta($id, 'ign_product_level_'.$i.'_price', true);
			if ($level_price > 0) {
				$level_price = number_format($level_price, 0, '.', ',');
			}
			$level_limit = get_post_meta($id, 'ign_product_level_'.$i.'_limit', true);
			$level_order = get_post_meta($id, 'ign_product_level_'.$i.'_order', true);
			$level_data[] = array('id' => $i,
			'title' => $level_title,
			'description' => $level_desc,
			'price' => $level_price,
			'limit' => $level_limit,
			'sold' => $level_sales,
			'currency_code' => $cCode,
			'order' => $level_order);	
		}
		
	}
	return $level_data;
}

function fh_level_sort($a, $b) {
	return $a->meta_order == $b->meta_order ? 0 : ($a->meta_order > $b->meta_order) ? 1 : -1;
}

function have_projects() {
	global $wpdb;

	$proj_return = get_ign_projects();
	if ($proj_return) {
		return true;
	}
	else {
		return false;
	}
}
// not in use --
function the_project($id) {
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$project = get_ign_project($project_id);
	$pay_info = get_pay_info($project_id);
	$fund_total = array('fund_total' => get_fund_total($pay_info));
	$meta = get_post_meta($id);
	$the_project = array_merge( $project, $pay_info, $fund_total, $meta);
	return $the_project;
}

function get_ign_project($id) {
	global $wpdb;
	$proj_query = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_products WHERE id=%d', absint($id));
	$proj_return = $wpdb->get_row($proj_query);
	return $proj_return;
}

function get_ign_projects() {
	global $wpdb;
	$proj_query = 'SELECT * FROM '.$wpdb->prefix.'ign_products';
	$proj_return = $wpdb->get_results($proj_query);
	return $proj_return;
}

function get_pay_info($id) {
	global $wpdb;
	$pay_query = $wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'ign_pay_info WHERE product_id=%d', absint($id));
	$pay_return = $wpdb->get_results($pay_query);
	return $pay_return;
}

function get_fund_total($id) {
	$project_id = get_post_meta($id, 'ign_project_id', true);

	$pay_info = get_pay_info($project_id);
	$total = 0;
	foreach ($pay_info as $fund) {
		$total = $total + $fund->prod_price;
	}
	return $total;
}

function get_backer_total($id) {
	global $wpdb;
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$get_pledgers = $wpdb->prepare('SELECT COUNT(*) AS count FROM '.$wpdb->prefix.'ign_pay_info WHERE product_id=%d', $project_id);
	$return_pledgers = $wpdb->get_row($get_pledgers);
	return $return_pledgers->count;
}

function the_project_image($id, $num) {
	if ($num == 1) {
		if (method_exists('ID_Project', 'get_project_thumbnail')) {
			$image = ID_Project::get_project_thumbnail($id, 'fivehundred_featured');
		}
		else {
			$project_id = get_post_meta($id, 'ign_project_id', true);
			global $wpdb;
			$url = get_post_meta($id, 'ign_product_image1', true);
			$sql = $wpdb->prepare('SELECT ID FROM '.$wpdb->prefix.'posts WHERE guid = %s', $url);
			$res = $wpdb->get_row($sql);
			if (isset($res->ID)) {
				$src = wp_get_attachment_image_src($res->ID, 'fivehundred_featured');
				$image = $src[0];
			} else {
				$image = $url;
			}
		}
	}
	else if ($num == 2) {
		$project_id = get_post_meta($id, 'ign_project_id', true);
		global $wpdb;
		$url = get_post_meta($id, 'ign_product_image2', true);
		$sql = $wpdb->prepare('SELECT ID FROM '.$wpdb->prefix.'posts WHERE guid = %s', $url);
		$res = $wpdb->get_row($sql);
		if (isset($res->ID)) {
			$src = wp_get_attachment_image_src($res->ID, '');
			$image = $src[0];
		} else {
			$image = $url;
		}
	}
	else {
		$key = 'ign_product_image'.$num;
		$image = get_post_meta($id, $key, true);
	}
	
	return $image;
}

function the_project_goal($id) {
	global $wpdb;
	$project_id = get_post_meta($id, 'ign_project_id', true);
	$goal_query = $wpdb->prepare('SELECT goal FROM '.$wpdb->prefix.'ign_products WHERE id=%d', $project_id);
	$goal_return = $wpdb->get_row($goal_query);
	if (!empty($goal_return->goal)) {
		$goal = $goal_return->goal;
	}
	else {
		$goal = 0;
	}
	return $goal;
}

add_action('init', 'fh_lightbox');

function fh_lightbox() {
	if (class_exists('IDF')) {
		add_action('id_after_levels', 'fh_level_select');
	}
}

function fh_level_select($project_id) {
	//ob_start();
	global $pwyw;
	$permalink_structure = get_option('permalink_structure');
	if (empty($permalink_structure)) {
		$prefix = '&';
	}
	else {
		$prefix = '?';
	}
	if (class_exists('Deck')) {
		//$project = new ID_Project($project_id);
		$deck = new Deck($project_id);
		$the_deck = $deck->the_deck();
		if (isset($the_deck) && $the_deck->project_type !== 'pwyw') {
			$post_id = $the_deck->post_id;
			if (function_exists('idc_checkout_image')) {
				$image = idc_checkout_image($post_id);
				$level_data = $the_deck->level_data;
				$action = get_permalink($post_id).$prefix.'purchaseform=500&prodid='.$project_id;
				include_once ID_PATH.'templates/_lbLevelSelect.php';
			}
			//$content = ob_get_contents();
			//ob_end_flush();
			//echo $content;
		}
	}
	return;
}

function fh_fa_shortcodes($attrs) {
	if (isset($attrs)) {
		$icon = 'fa fa-'.$attrs['type'];
		if (isset($attrs['size'])) {
			$size = 'fa-'.$attrs['size'];
		}
		else {
			$size = null;
		}

		$output = '<i class="'.$icon.' '.$size.'"></i>';
	}
	else {
		$output = '';
	}
	return $output;
}

add_shortcode('icon', 'fh_fa_shortcodes');

// This is below

add_action('after_setup_theme', 'fivehundred_setup');

function fivehundred_setup(){
	global $crowdfunding;
	global $backer_list;
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'menus' );
	register_nav_menus(
		array( 
			'main-menu' => __( 'Main Menu', 'fivehundred' ),
			'footer-menu' => __( 'Footer Menu', 'fivehundred' ) 
			)
	);
	add_theme_support( 'woocommerce' );
	add_filter('wp_nav_menu_items', 'fh_my_account_link', 5, 2);
	add_action('wp_head', 'fh_color_styles', 999);
	if ($crowdfunding) {
		add_action('fh_below_project', function() {
			echo '<div class="content_tab backers_tab">';
		});
		add_action('fh_below_project', 'mdid_backers_list');
		add_action('fh_below_project', function() {
			echo '</div>';
		});
	}
	add_filter('wp_title', 'fh_wp_title', 10, 2);
}

/** Wordpress Theme Customizer **/

function fivehundred_customize_register( $wp_customize ) {

	$colors = array();
	$colors[] = array(
		'slug'=>'fh_primary_color', 
		'default' => '',
		'priority'	=> 1,
		'label' => __('Primary Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_primary_light_color', 
		'default' => '',
		'priority'	=> 2,
		'label' => __('Primary Light Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_primary_dark_color', 
		'default' => '',
		'priority'	=> 3,
		'label' => __('Primary Dark Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_secondary_color', 
		'default' => '',
		'priority'	=> 4,
		'label' => __('Secondary Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_secondary_dark_color', 
		'default' => '',
		'priority'	=> 5,
		'label' => __('Secondary Dark Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_text_color', 
		'default' => '',
		'priority'	=> 6,
		'label' => __('Text Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_text_subtle_color', 
		'default' => '',
		'priority'	=> 7,
		'label' => __('Subtle Text Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_text_onprimary_color', 
		'default' => '',
		'priority'	=> 8,
		'label' => __('Text Color On Primary Background', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_container_background_color', 
		'default' => '',
		'priority'	=> 9,
		'label' => __('Container Background Color', 'fivehundred')
	);
	$colors[] = array(
		'slug'=>'fh_site_background_color', 
		'default' => '',
		'priority'	=> 10,
		'label' => __('Site Background Color', 'fivehundred')
	);

	foreach( $colors as $color ) {
		// SETTINGS
		$wp_customize->add_setting(
			$color['slug'], array(
				'default' => $color['default'],
				'type' => 'option', 
				'capability' => 
				'edit_theme_options'
			)
		);
		// CONTROLS
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				$color['slug'],
				array('label' => $color['label'], 
				'section' => 'colors',
				'settings' => $color['slug'],
				'priority' => $color['priority'])
			)
		);
	}
	return $wp_customize;

}
add_action( 'customize_register', 'fivehundred_customize_register' ); 

function fh_color_styles() {
	$primary_color = get_option('fh_primary_color');
	$primary_light_color = get_option('fh_primary_light_color');
	$primary_dark_color = get_option('fh_primary_dark_color');
	$secondary_color = get_option('fh_secondary_color');
	$secondary_dark_color = get_option('fh_secondary_dark_color');
	$text_color = get_option('fh_text_color');
	$text_subtle_color = get_option('fh_text_subtle_color');
	$text_onprimary_color = get_option('fh_text_onprimary_color');
	$site_background_color = get_option('fh_site_background_color');
	$container_background_color = get_option('fh_container_background_color');

	$customized = false;
	if (!empty($primary_color) || !empty($primary_light_color) || !empty($primary_dark_color) || !empty($secondary_color) || !empty($secondary_dark_color) || !empty($text_color) || !empty($text_subtle_color) || !empty($text_onprimary_color) || !empty($site_background_color) || !empty($container_background_color)) {
		$customized = true;
	}
	if ($customized) {
		// Convert Sidebar from Hex to RGB
		if ( !empty($primary_color) && $primary_color !== '#3B7BB3') {
			$hexs = str_replace("#", "", $primary_color);

			if (strlen($hexs) == 3) {
				$rs = hexdec(substr($hexs,0,1).substr($hexs,0,1));
				$gs = hexdec(substr($hexs,1,1).substr($hexs,1,1));
				$bs = hexdec(substr($hexs,2,1).substr($hexs,2,1));

			}
			else {
				$rs = hexdec(substr($hexs,0,2));
				$gs = hexdec(substr($hexs,2,2));
				$bs = hexdec(substr($hexs,4,2));
			}
		}

		if (!empty($site_background_color) && $site_background_color !== '#F1F4F7') {
			$hexs = str_replace("#", "", $site_background_color);

			if (strlen($hexs) == 3) {
				$rb = hexdec(substr($hexs,0,1).substr($hexs,0,1));
				$gb = hexdec(substr($hexs,1,1).substr($hexs,1,1));
				$bb = hexdec(substr($hexs,2,1).substr($hexs,2,1));
			}
			else {
				$rb = hexdec(substr($hexs,0,2));
				$gb = hexdec(substr($hexs,2,2));
				$bb = hexdec(substr($hexs,4,2));
			} 
		}
		$css =
		'<style>
		.ign-content-long, .ign-content-normal, .ign-content-alt, .ign-content-level, .ign-content-alert, .ign-video-headline, .ign-content-fullalt, #site-description, .entry-content, #comments, .ignitiondeck.id-purchase-form-full {padding-left: 20px !important; padding-right: 20px !important; box-sizing: border-box;}
		body {background-color: '.$site_background_color.';}
		body, .entry-content h1, .comment-content h1, .entry-content h2, .comment-content h2, .entry-content h3, .comment-content h3, .entry-content h4, .comment-content h4, .entry-content h5, .comment-content h5, .entry-content h6, .comment-content h6, #container .ign-content-level li, .ignition_project #site-description h1, #ign-product-levels .ign-level-title span, #ign-product-levels .ign-level-desc, .ignitiondeck form .form-row label, .ignitiondeck form .payment-type-selector a, .ignitiondeck form label.dd-option-text, .widget-area .widget-container h3, #content .ign-project-summary .ign-summary-desc, #content .ign-project-summary .ign-progress-percentage, #content .ign-project-summary .title h3, footer .footer-finalwrap a, #menu-header ul.defaultMenu li ul.children li a, #menu-header .menu ul li ul.children li a, #menu-header ul li ul.sub-menu li.current-menu-item a, #menu-header ul.defaultMenu li ul.children li.current-menu-item a, #menu-header .menu ul ul.children li.current-menu-item a, #menu-header .menu ul li a:active, #menu-header ul.menu li a, #menu-header ul.defaultMenu li a, #menu-header .menu ul li a, #content .ign-project-summary .ign-progress-raised, footer, .memberdeck .md-box-wrapper, .memberdeck form .form-row label, .ignitiondeck form .finaldesc, .ignitiondeck form .finaldesc p, #fivehundred .ignitiondeck.id-creatorprofile, .container .ign-content-alt h3   { color:  '.$text_color.';}
		#ign-hDeck-right, .ign-product-proposed-end, .ignition_project #site-description h2, .home #site-description h1, #site-description h1, .commentlist > li.bypostauthor .comment-meta, .comment-meta, .dd-desc, footer #site-description h1, .entry-content blockquote, .comment-content blockquote, #content .ign-project-summary .ign-summary-days, #content .ign-project-summary .title h3:hover, .memberdeck .md-profile .md-registered { color: '.$text_subtle_color.';}
		#container .ign-content-level .ign-content-text {border-top-color: '.$text_subtle_color.';}
		#container .constrained, #menu-header ul.menu ul.sub-menu, #menu-header div.menu ul.defaultMenu ul.children, #menu-header .menu ul ul.children, #content .ign-project-summary .ign-progress-bar, #menu-header ul.menu li:hover, ul.menu li:active,  #menu-header ul.defaultMenu li:hover,  #menu-header ul.defaultMenu li:active, #menu-header .menu ul li:active, #menu-header .menu li.createaccount, #menu-header .menu li.login, .memberdeck .dashboardmenu { background-color: '.$primary_color.'; }
		#container .constrained, #container .constrained h3, footer .footer-finalwrap a:hover, #menu-header ul ul.sub-menu a:hover, #menu-header ul.defaultMenu li ul.children a:hover, #menu-header ul.menu li a:hover, ul.menu li a:active, #menu-header ul.defaultMenu li a:hover, #menu-header ul.defaultMenu li a:active, #menu-header ul li ul.sub-menu li a, #menu-header ul.menu li:hover, ul.menu li:active,  #menu-header ul.defaultMenu li:hover,  #menu-header ul.defaultMenu li:active, #menu-header .menu ul li:active, #menu-header .menu li.createaccount, #menu-header .menu li.login, #menu-header ul.menu li:hover a, ul.menu li:active a,  #menu-header ul.defaultMenu li:hover a,  #menu-header ul.defaultMenu li:active a, #menu-header .menu ul li:active a, #menu-header .menu li.createaccount a, #menu-header .menu li.login a, .ign-supportnow a, .ign-supportnow a:hover, .memberdeck button, .memberdeck input[type="submit"], .memberdeck form .form-row input[type="submit"], .memberdeck .button, .memberdeck .md-dash-sidebar ul li.widget_nav_menu ul.menu li a, .memberdeck .md-dash-sidebar ul li.widget_nav_menu ul.menu li a:hover { color: '.$text_onprimary_color.'; }
		#menu-header ul.menu li a:hover, ul.menu li a:active, #menu-header ul.defaultMenu li a:hover,  #menu-header ul.defaultMenu li a:active, #menu-header .menu ul li a:active, #ign-hDeck-right .ign-progress-bar, .memberdeck .dashboardmenu li:hover {background-color: '.$primary_light_color.';}
		.title-wrap h2.entry-title, .single-post #content .title-wrap h2.entry-title, .memberdeck form .form-row input, .memberdeck form .form-row textarea  { color: '.$primary_dark_color.'; }
		a.comment-reply-link, .ignitiondeck form .main-btn, .ignitiondeck form input[type=submit] {background-color: '.$text_color.';}
		a.comment-reply-link:hover, a.comment-reply-link:focus, a.comment-reply-link:active, .ignitiondeck form .main-btn, .ignitiondeck form input[type=submit]:hover{background-color: '.$text_subtle_color.';}
		#container, .title-wrap, .ignitiondeck form .form-row input, .ignitiondeck form .form-row textarea, .ignitiondeck form .form-row select, .dd-options, .memberdeck form .form-row input, .memberdeck form .form-row textarea, .ignitiondeck .dd-select   { background-color:  '.$container_background_color.'; }
		a.comment-reply-link, a.comment-reply-link:hover, a.comment-reply-link:focus, a.comment-reply-link:active, a.comment-reply-link, .ignitiondeck form .main-btn, .ignitiondeck form input[type=submit], a.comment-reply-link, .ignitiondeck form .main-btn, .ignitiondeck form input[type=submit]:hover, .memberdeck .dashboardmenu a, .memberdeck .dashboardmenu a:hover { color:  '.$container_background_color.'; }
		.ignitiondeck.idc_lightbox .form-row.submit input[type=submit], #fivehundred .ignitiondeck.idc_lightbox .form-row.submit input[type=submit] {background-color: '.$primary_color.' !important;}
		.ignitiondeck.idc_lightbox .form-row.submit input[type=submit]:hover, #fivehundred .ignitiondeck.idc_lightbox .form-row.submit input[type=submit]:hover {background-color: '.$primary_light_color.' !important;}
		.ignitiondeck.idc_lightbox .form-row.submit input[type=submit], #fivehundred .ignitiondeck.idc_lightbox .form-row.submit input[type=submit] {color: '.$text_onprimary_color.' !important;}
		#home-sharing ul li.twitter-btn a, #home-sharing ul li.linkedin-btn a, #home-sharing ul li.facebook-btn a, #home-sharing ul li.gplus-btn a, #container .ign-content-alt h3, #container .ign-content-level h3 .amount, #ign-product-levels .ign-level-title .level-price, #ign-product-levels .ign-level-counts, .comment-meta .fn span, #container  .ign-video-headline h3, .ignitiondeck form .payment-type-selector a:hover, .ignitiondeck form .payment-type-selector a.active, .ignitiondeck form .payment-type-selector a.active:hover, .grid-header ul li a:hover, .grid-header ul li a.active, .grid-header ul li.filter_submenu:hover span, #content h2.entry-title a, #content .ign-project-summary .ign-summary-days strong, .ign-progress-raised strong, #container .ign-content-alt h3, #container .ign-content-level h3 .amount, #container .ign-content-alert h3, #site-title a, #ign-hDeck-right .ign-product-goal strong, #ign-hDeck-right .ign-product-supporters strong, #ign-hDeck-right .ign-product-proposed-end .ign-proposed-end, .ignitiondeck form .ign-checkout-price, .grid-header ul li.filter_submenu span, .filter_choice a, .memberdeck .md-profile .md-credits  { color: '.$primary_color.'; }
		.grid-header ul li a:hover, .grid-header ul li a.active, .grid-header ul li.filter_submenu:hover span {border-top-color: '.$primary_color.'; border-bottom-color: '.$primary_color.';}
		#home-sharing ul li.twitter-btn a:hover, #home-sharing ul li.linkedin-btn a:hover, #home-sharing ul li.facebook-btn a:hover, #home-sharing ul li.gplus-btn a:hover, #content h2.entry-title a:hover, #site-title a:hover { color: '.$primary_light_color.'; }
		.ignitiondeck form .payment-type-selector a:hover, .ignitiondeck form .payment-type-selector a.active, .entry-content blockquote, .comment-content blockquote, #content .ign-project-summary .ign-summary-container:hover, #menu-header ul.menu li:hover, ul.menu li:active,  #menu-header ul.defaultMenu li:hover,  #menu-header ul.defaultMenu li:active, #menu-header .menu ul li:active, #menu-header .menu li.createaccount, #menu-header .menu li.login, #menu-header ul.menu ul.sub-menu, #menu-header div.menu ul.defaultMenu ul.children, #menu-header .menu ul ul.children { border-color: '.$primary_color.'; }
		#container .ign-content-fullalt { border-top-color: '.$secondary_color.'; }
		body a, .ignitiondeck form .required-mark, .widget-area .widget-container a, #content .ign-project-summary .ign-summary-learnmore,  #menu-footer ul.menu li a, #menu-footer ul.defaultMenu li a, .memberdeck a  { color: '.$secondary_color.'; }
		body a:hover, .widget-area .widget-container a:hover, #content .ign-project-summary .ign-summary-learnmore:hover,  #menu-footer ul.menu li a:hover, ul.menu li a:active, #menu-footer ul.defaultMenu li a:hover, #menu-footer ul.defaultMenu li a:active, .memberdeck a:hover { color: '.$secondary_dark_color.'; }
		.memberdeck .md-dash-sidebar ul li.widget_nav_menu ul.menu li a { background-color: '.$secondary_color.'; }
		.memberdeck .md-dash-sidebar ul li.widget_nav_menu ul.menu li a:hover { background-color: '.$secondary_dark_color.'; }
		#container h3.product-dashed-heading, #container h3.product-dashed-heading1, #container #prodfaq, #container #produpdates, .ignitiondeck form .form-row input, .ignitiondeck form .form-row textarea, .ignitiondeck form .form-row select { color:  '.$text_color.'; }
		#menu-header ul.menu li.current-menu-item a, #menu-header ul.menu li.current_page_item a, #menu-header ul.menu li.current-menu-ancestor a, #menu-header .menu ul li.current-menu-ancestor a, .memberdeck button:hover, .memberdeck input[type="submit"]:hover, .memberdeck form .form-row input[type="submit"]:hover, .memberdeck .button:hover { color: '.$text_onprimary_color.'; background-color: '.$primary_dark_color.';}
		#menu-header ul.menu li.current-menu-item a:hover, #menu-header ul.menu li.current_page_item a:hover, #menu-header ul.menu li.current-menu-ancestor a:hover, #menu-header .menu ul li.current-menu-ancestor a:hover { color: '.$text_onprimary_color.'; background-color: '.$primary_color.';}
		#menu-footer ul.menu li, #menu-footer ul.defaultMenu li {border-right-color: '.$text_subtle_color.';}
		.ignitiondeck form .dd-option-description {border-left-color: '.$text_subtle_color.';}
		#container, .widget-area .widget-container h3, .entry-footer, header#header { border-bottom-color: '.$primary_light_color.'; }
		footer .footer-finalwrap, #ign-hDeck-right .ign-progress-wrapper { background-color:  '.$primary_dark_color.'; }
		.ignitiondeck form .form-row input, .ignitiondeck form .form-row textarea, .ignitiondeck form .form-row select, #content .ign-project-summary .ign-summary-container, #content .ign-project-summary .ign-summary-container .ign-summary-image, .ignitiondeck .id-purchase-form, .ignitiondeck .dd-select  { border-color: '.$text_subtle_color.';}
		.ign-supportnow a, .memberdeck button, .memberdeck input[type="submit"], .memberdeck form .form-row input[type="submit"], .memberdeck .button {background: '.$primary_color.'; background-color: '.$primary_color.' ;}';

			if (!empty($primary_color) && $primary_color !== '#3B7BB3') {
				$css .= '	#container .fullwindow-internal, #container .ign-content-alert, #ign-hDeck-wrapper #ign-hdeck-wrapperbg, .grid-header ul li a:hover, .grid-header ul li a.active, .grid-header ul li.filter_submenu:hover span, #content .ign-project-summary .ign-progress-wrapper, .grid-header ul li ul li a:hover, .ignitiondeck form .payment-type-selector a   {background-color: rgba(' . $rs . ',' . $gs . ', ' . $bs . ', .2);}' . "\n";
			}
			if (!empty($site_background_color) && $site_background_color !== '#F1F4F7') {
				$css .= '	#container .ign-content-alt, #container .ign-content-level .ign-content-text, #ign-product-levels a .level-group:hover .ign-level-desc, .title-wrap h2.entry-title, #container h3.product-dashed-heading, #container h3.product-dashed-heading1, #container .ign-content-video, .dd-option:hover, .dd-option-selected, #content .ign-project-summary .ign-summary-container:hover, .memberdeck .md-list-thin > li:hover:nth-child(odd), .memberdeck .md-box li:hover, .ignitiondeck .id-purchase-form {background-color: rgba(' . $rb . ',' . $gb . ', ' . $bb . ', .5) ;}' . "\n";
			}
			if (!empty($site_background_color) && $site_background_color !== '#F1F4F7') {
				$css .= '.commentlist > li.bypostauthor, #container #prodfaq, #container #produpdates, .entry-content blockquote, .comment-content blockquote, .wp-caption, .grid-header, #content .ign-project-summary .ign-summary-container, .memberdeck .md-box-wrapper, .memberdeck .md-list-thin > li:nth-child(odd), .memberdeck .md-profile li.myprojects:nth-child(even), .dd-selected, #fivehundred .ignitiondeck.id-creatorprofile, #ign-product-levels .ign-level-desc, #ign-product-levels .ign-level-counts {background-color: rgba(' . $rb . ',' . $gb . ', ' . $bb . ', .2);}' . "\n";
			}
			if (!empty($site_background_color) && $site_background_color !== '#F1F4F7') {
				$css .= '	#ign-product-levels .alt, .comment-meta .fn span {background-color: rgba(' . $rb . ',' . $gb . ', ' . $bb . ', .25);}' . "\n";
			}
			if (!empty($site_background_color) && $site_background_color !== '#F1F4F7') {
				$css .= ' .commentlist .commentarrow { border-color: transparent rgba(' . $rb . ',' . $gb . ', ' . $bb . ', .15) transparent transparent; }' . "\n";
			}
			if (!empty($primary_color) && $primary_color !== '#3B7BB3') {
				$css .= '#ign-product-levels .ign-level-desc, .memberdeck .md-box-wrapper, .memberdeck form .form-row input, .memberdeck form .form-row textarea, #fivehundred .ignitiondeck.id-creatorprofile, .memberdeck .md-box.half:nth-child(odd), .memberdeck .md-box.half:nth-child(4n+3), .memberdeck .md-box.half:nth-child(4n+4) { border-color: rgba(' . $rs . ',' . $gs . ', ' . $bs . ', .35); border-top-color: rgba(' . $rs . ',' . $gs . ', ' . $bs . ', .35); border-bottom-color: rgba(' . $rs . ',' . $gs . ', ' . $bs . ', .35); border-left-color: rgba(' . $rs . ',' . $gs . ', ' . $bs . ', .35); border-right-color: rgba(' . $rs . ',' . $gs . ', ' . $bs . ', .35); }' . "\n";
			}
		$css .= '</style>';
		echo apply_filters('fh_customization_style', (isset($css) ? $css : ''));
	}
}

function fh_wp_title($title, $sep) {
	global $page, $paged;

	if ( is_feed() ) {
		return $title;
	}

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	if (is_home() || is_front_page()) {
		$site_description = get_bloginfo( 'description', 'display' );
		$title .= $sep." ".$site_description;
	}

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'fivehundred' ), max( $paged, $page ) );

	return $title;
}


function fh_my_account_link($items, $args) {
	if (class_exists('ID_Member')) {
		$permalink_structure = get_option('permalink_structure');
		if (empty($permalink_structure)) {
			$prefix = '&';
		}
		else {
			$prefix = '?';
		}
		$durl = md_get_durl();
		if ($args->theme_location == 'main-menu') {
			if (is_user_logged_in()) {
				$items .= '<li class="createaccount buttonpadding"><a href="'.$durl.'">'.__('My Account', 'fivehundred').'</a></li>';
				$items .= '<li class="login right"><a href="'.wp_logout_url( home_url() ).'">'.__('Logout', 'fivehundred').'</a></li>';
			}
			else {
				$items .= '<li class="createaccount buttonpadding"><a href="'.$durl.$prefix.'action=register">'.__('Create Account', 'fivehundred').'</a></li>';
				$items .= '<li class="login right"><a href="'.$durl.'">'.__('Login', 'fivehundred').'</a></li>';
			}
		}
	}
	return $items;
}
// Image Sizes added and Allowing to select those image sizes in Media Insert Admin
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'projectpage-large', 640, 9999 ); // For Project Pages with Unlimited Height allowed
	add_image_size( 'single-thumb', 700, 105, true ); // For Single Posts (cropped)
	add_image_size( 'fivehundred_featured', 624, 360, true); // For 500 Featured Project
}

add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );  
function custom_image_sizes_choose( $sizes ) {  
    $custom_sizes = array(  
        'projectpage-large' => 'Project Page Full Width',
        'single-thumb' => 'Single Post Thumb',
        'fh_feature' => 'Fivehundred Feature'  
    );  
    return array_merge( $sizes, $custom_sizes );  
}


// for custom comments

if ( ! function_exists( 'fivehundred_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own fivehundred_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function fivehundred_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'fivehundred' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'fivehundred' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div class="commentarrow"></div>
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'fivehundred' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'fivehundred' ), get_comment_date(), get_comment_time() )
					);
				?>
			</div><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'fivehundred' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'fivehundred' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'fivehundred' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;


add_action('comment_form_before', 'fivehundred_enqueue_comment_reply_script');

function fivehundred_scripts() {
	//wp_register_style('open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans');
	wp_register_style('fivehundred', get_stylesheet_uri());
	wp_register_script('fivehundred-js', get_bloginfo('template_url').'/js/fivehundred.js');
	wp_register_script( 'bootstrap-js', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '3.0.1', true );
	wp_register_style( 'bootstrap-css', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), '3.0.1', 'all' );

	wp_enqueue_style( 'bootstrap-css' );
	wp_enqueue_style('fivehundred');
	wp_enqueue_script('jQuery');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
	//wp_enqueue_style('open-sans');
	wp_enqueue_script( 'bootstrap-js' );
	wp_enqueue_script('fivehundred-js');
}

add_action('wp_enqueue_scripts', 'fivehundred_scripts');

function fivehundred_admin_scripts() {
	wp_register_script('fivehundred-admin-js', get_bloginfo('template_url').'/js/fivehundred-admin.js');
	if (isset($_GET['page'])) {
		if ($_GET['page'] == 'theme-settings') {
			wp_enqueue_script('fivehundred-admin-js');
		}
	}
	else {
		global $pagenow;
		if (isset($pagenow) && $pagenow = 'widgets.php') {
			wp_enqueue_media();
			wp_enqueue_script('fivehundred-admin-js');
		}
	}
	wp_register_style('fh-style', get_bloginfo('template_url').'/admin-style.css');
	wp_enqueue_style('fh-style');
}

add_action('admin_enqueue_scripts', 'fivehundred_admin_scripts');

function fivehundred_enqueue_comment_reply_script() {
	if (get_option('thread_comments')) { 
		wp_enqueue_script('comment-reply'); 
	}
}


// Need to set our widgets array
add_action( 'widgets_init', 'fivehundred_widgets_init' );

function fivehundred_widgets_init() {
	register_widget('Fh_Video_Widget');
    //register_widget('Fh_Music_Widget');
    register_widget('Fh_Content_Level_Widget');
    register_widget('Fh_Content_Fullalt_Widget');
    register_widget('Fh_Content_Fullalt_Bgimage_Widget');
    register_widget('Fh_Content_Alert_Widget');
    register_widget('Fh_Content_Widget');
    //register_widget('Fh_Project_Grid_Widget');
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
			'name' => __('Sidebar Widget Area', 'fivehundred'),
			'id' => 'primary-widget-area',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => "</li>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
		register_sidebar(array(
			'name' => __('Projects Sidebar Area', 'fivehundred'),
			'id' => 'projects-widget-area',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => "</li>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
			'description' => __( 'This sidebar is located below the Levels on each Project page.', 'fivehundred' )
		));
		register_sidebar(array(
			'name' => __('Home Sidebar Area', 'fivehundred'),
			'id' => 'home-widget-area',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => "</li>",
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
			'description' => __( 'This sidebar is located on the Home Page, to the right of About Us', 'fivehundred' )
		));
		$sidebar = register_sidebar(array(
			'name' => __('Home Top Content Widgets', 'fivehundred'),
			'description' => __('This is a widget area on Project Grid home, directly above the Project Grid', 'fivehundred'),
			'id' => 'home-top-content-widget-area',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		));
		$sidebar = register_sidebar(array(
			'name' => __('Home Content Widgets', 'fivehundred'),
			'description' => __('This is a widget area on Project Grid home and Single Project home, below Featurd Projects or the Project Deck.', 'fivehundred'),
			'id' => 'home-content-widget-area',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		));
		$sidebar = register_sidebar(array(
			'name' => __('Top of Footer', 'fivehundred'),
			'description' => __('This is a widget area at top of the footer, on every page of the site.', 'fivehundred'),
			'id' => 'footer-widget-area',
			'before_widget' => '<li id="%1$s" class="footer-widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		));
		do_action('fivehundred_widgets_init');
	}
}

$preset_widgets = array (
	'primary-aside'  => array( 'search', 'pages', 'categories', 'archives' ),
);

// add Google Analytics Code to footer
add_action('wp_footer', 'fh_ga_code');

function fh_ga_code() {
	$settings = get_option('fivehundred_theme_settings');
	if (is_array($settings) && isset($settings['ga'])) {
		$ga = stripslashes($settings['ga']);
		echo str_replace('"', '', $ga);
	}
}

add_action('wp_head', 'fh_custom_css');

function fh_custom_css() {
	$settings = get_option('fivehundred_theme_settings');
	if (is_array($settings) && isset($settings['custom_css'])) {
		$custom_css = stripslashes($settings['custom_css']);
		echo '<style>';
		echo str_replace('"', '', $custom_css);
		echo '</style>';
	}
}

function widont($str = '')
{
	$str = rtrim($str);
	$space = strrpos($str, ' ');
	if ($space !== false)
	{
		$str = substr($str, 0, $space).'&nbsp;'.substr($str, $space + 1);
	}
	return $str;
}

add_filter('the_title', 'widont');

function fivehundred_admin_notice(){
	if (!is_plugin_active('ignitiondeck-crowdfunding/ignitiondeck.php')) {
	    echo '<div class="updated">
	       <p>'.__('This theme requires the', 'fivehundred').' <a href="http://ignitiondeck.com/id?r=500">'.__('IgnitionDeck WordPress Crowdfunding Plugin', 'fivehundred').'</a>.</p>
	    </div>';
	}
}

add_action('admin_notices', 'fivehundred_admin_notice');

function fh_font_awesome() {
	wp_register_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
	wp_enqueue_style('font-awesome');
	//wp_register_style('classic-green', DIRNAME(get_bloginfo('stylesheet_url')).'/style-green.css');
	//wp_enqueue_style('classic-green');
}
add_action('wp_enqueue_scripts', 'fh_font_awesome');

/**
* Required by WordPress
**/

if ( ! isset( $content_width ) ) {
	$content_width = 960;
}

/**
* WooCommerce
*/

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_action('woocommerce_before_main_content', 'fivehundred_wc_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'fivehundred_wc_wrapper_end', 10);

function fivehundred_wc_wrapper_start() {
	echo '<div id="container">';
	echo '<article id="content">';
}

function fivehundred_wc_wrapper_end() {
  echo '</article>';
  get_sidebar();
  echo '</div>';
}
?>