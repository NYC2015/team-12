<?php

/**
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_htmlonly($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"apply_shortcodes" => 1 // apply shortcodes to the inner $content?
	), $atts));
	
	$apply_shortcodes = strtolower($apply_shortcodes);
	
	if ($email_type == "html") {
		if ($apply_shortcodes) {
			return Bbpp_ThankMeLater_Shortcoder::apply($content);
		} else {
			return $content;
		}
	}
	
	return "";
}

Bbpp_ThankMeLater_Shortcoder::add("htmlonly", "bbpp_thankmelater_shortcode_htmlonly");