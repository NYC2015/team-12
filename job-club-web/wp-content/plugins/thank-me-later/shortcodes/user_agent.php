<?php

/**
 * Shortcode for comment author's User Agent
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_user_agent($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	if ($attr) {
		return esc_attr($comment->comment_agent);
	}
	
	return ($email_type == "html") ? esc_html($comment->comment_agent) : $comment->comment_agent;
}

Bbpp_ThankMeLater_Shortcoder::add("user_agent", "bbpp_thankmelater_shortcode_user_agent");