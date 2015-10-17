<?php

/**
 * Shortcode for comment author's IP address
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_ip($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	if ($attr) {
		return esc_attr($comment->comment_author_IP);
	}
	
	return ($email_type == "html") ? esc_html($comment->comment_author_IP) : $comment->comment_author_IP;
}

Bbpp_ThankMeLater_Shortcoder::add("ip", "bbpp_thankmelater_shortcode_ip");