<?php

/**
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_opt_out($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"url" => TRUE
	), $atts));
	
	$code = base64_encode($comment->comment_ID . "-" . $comment->comment_author_email);
	
	$unsub_url = get_site_url() . "/?bbpp-thankmelater-unsubscribe=" . $code;
	
	if ($url) {
		return $unsub_url;
	}
	
	$t_html = __("We sent you this email because you left a comment on our blog. %sUnsubscribe from future emails%s.", "bbpp-thankmelater");
	$t_text = __("We sent you this email because you left a comment on our blog. Click this URL to unsubscribe from future emails: %s", "bbpp-thankmelater");
	
	if ($email_type == "html") {
		return "<center><small>" . sprintf(
			$t_html,
			"<a href=\"" . esc_attr($unsub_url) . "\">",
			"</a>"
		) . "</small></center>";
	} else {
		return sprintf(
			$t_text,
			$unsub_url
		);
	}
}

Bbpp_ThankMeLater_Shortcoder::add("opt_out", "bbpp_thankmelater_shortcode_opt_out");