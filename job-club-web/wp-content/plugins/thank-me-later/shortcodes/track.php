<?php

/**
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_track($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"url" => FALSE
	), $atts));
	
	$code = base64_encode($comment->comment_ID . "-" . $comment->comment_author_email);
	
	$track_url = get_site_url() . "/?bbpp-thankmelater-open=" . urlencode($code);
	
	if ($url) {
		return $track_url;
	}
	
	if ($email_type == "html") {
		return "<img src=\"" . $track_url . "\" width=\"1\" height=\"1\">";
	}
}

Bbpp_ThankMeLater_Shortcoder::add("track", "bbpp_thankmelater_shortcode_track");