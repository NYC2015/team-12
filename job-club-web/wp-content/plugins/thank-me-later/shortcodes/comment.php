<?php

/**
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_comment($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"maxlength" => NULL,
		"attr" => 0
	), $atts));
	
	$message = strip_tags($comment->comment_content);
	
	if ($maxlength && strlen($message) > $maxlength) {
		$message = explode("<f>", wordwrap($message, $maxlength-3, "<f>", TRUE));
		$message = $message[0] . "...";
	}
	
	if ($attr) {
		return esc_attr($message);
	}
	
	if ($email_type == "html") {
		return esc_html($message);
	} else {
		return $message;
	}
}

Bbpp_ThankMeLater_Shortcoder::add("comment", "bbpp_thankmelater_shortcode_comment");