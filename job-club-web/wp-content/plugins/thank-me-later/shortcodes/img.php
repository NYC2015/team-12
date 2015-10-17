<?php

/**
 * Shortcode for image
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_img($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"src" => "",
		"width" => 0,
		"height" => 0
	), $atts));
	
	if ($email_type == "text") {
		return "";
	}
	
	$tag = "<img";
	$tag .= " src=\"" . htmlspecialchars($src) . "\"";
	if ($width) {
		$tag .= " width=\"" . htmlspecialchars($width) . "\"";
	}
	if ($height) {
		$tag .= " height=\"" . htmlspecialchars($height) . "\"";
	}
	
	return $tag;
}

Bbpp_ThankMeLater_Shortcoder::add("img", "bbpp_thankmelater_shortcode_img");