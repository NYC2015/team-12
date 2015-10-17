<?php

/**
 * Shortcode for comment author's email
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_email($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	if ($attr) {
		return esc_attr($comment->comment_author_email);
	}
	
	return ($email_type == "html") ? esc_html($comment->comment_author_email) : $comment->comment_author_email;
}

Bbpp_ThankMeLater_Shortcoder::add("email", "bbpp_thankmelater_shortcode_email");