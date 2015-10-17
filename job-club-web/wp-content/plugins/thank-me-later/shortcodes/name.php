<?php

/**
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_name($atts) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	if ($attr) {
		return esc_attr($comment->comment_author);
	}
	
	return ($email_type == "html") ? esc_html($comment->comment_author) : $comment->comment_author;
}

Bbpp_ThankMeLater_Shortcoder::add("name", "bbpp_thankmelater_shortcode_name");