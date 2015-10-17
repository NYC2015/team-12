<?php

/**
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_comment_url($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"attr" => 0
	), $atts));
	
	$url = get_comment_link($comment);
	
	if ($attr) {
		return esc_attr($url);
	} else {
		if ($email_type == "html") {
			return esc_html($url);
		} else {
			return $url;
		}
	}
}

Bbpp_ThankMeLater_Shortcoder::add("comment_url", "bbpp_thankmelater_shortcode_comment_url");