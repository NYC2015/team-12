<?php

/**
 * Date of the comment
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_date($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"format" => _x("d M Y", "date syntax", "bbpp-thankmelater"),
		"gmt" => 0,
		"attr" => 0
	), $atts));
	
	$time = strtotime($comment->comment_date_gmt . " GMT");
	
	if ($gmt) {
		$date = gmdate($format, $time);
	} else {
		$date = date($format, $time);
	}
	
	if ($attr) {
		return esc_attr($date);
	}
	
	return $date;
}

Bbpp_ThankMeLater_Shortcoder::add("date", "bbpp_thankmelater_shortcode_date");