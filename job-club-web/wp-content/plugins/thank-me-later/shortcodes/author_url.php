<?php

/**
 * Shortcode for post author's url
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_author_url($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	if ($attr) {
		return esc_attr($comment->comment_author_url);
	} else {
		return ($email_type == "html") ? esc_html($comment->comment_author_url) : $comment->comment_author_url;
	}
}

Bbpp_ThankMeLater_Shortcoder::add("author_url", "bbpp_thankmelater_shortcode_author_url");