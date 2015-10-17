<?php

/**
 * Shortcode for post ID
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_post_id($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	if ($attr) {
		return esc_attr($comment->comment_post_ID);
	}
	
	return ($email_type == "html") ? esc_html($comment->comment_post_ID) : $comment->comment_post_ID;
}

Bbpp_ThankMeLater_Shortcoder::add("post_id", "bbpp_thankmelater_shortcode_post_id");