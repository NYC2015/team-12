<?php

/**
 * Shortcode for ID of comment
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_id($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	if ($attr) {
		return esc_attr($comment->comment_ID);
	}
	
	return $comment->comment_ID;
}

Bbpp_ThankMeLater_Shortcoder::add("id", "bbpp_thankmelater_shortcode_id");