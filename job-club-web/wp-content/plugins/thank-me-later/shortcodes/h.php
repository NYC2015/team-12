<?php

/**
 * Shortcode for <h[0-6]>
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_h($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"level" => 1
	), $atts));
	
	if ($email_type == "text") {
		return str_repeat("*", $level) . " " . $content;
	}
	
	return "<h{$level}>" . $content . "</h{$level}>";
}

for ($i = 1; $i <= 6; $i++) {
	Bbpp_ThankMeLater_Shortcoder::add("h{$i}", create_function("\$atts,\$content", "\$atts[\"level\"]={$i};return bbpp_thankmelater_shortcode_h(\$atts, \$content);"));
}