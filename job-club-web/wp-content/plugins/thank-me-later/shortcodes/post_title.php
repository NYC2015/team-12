<?php

/**
 * Shortcode for post title
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_post_title($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	$post = get_post($comment->comment_post_ID);
	
	if ($attr) {
		return esc_attr($post->post_title);
	}
	
	return ($email_type == "html") ? esc_html($post->post_title) : $post->post_title;
}

Bbpp_ThankMeLater_Shortcoder::add("post_title", "bbpp_thankmelater_shortcode_post_title");