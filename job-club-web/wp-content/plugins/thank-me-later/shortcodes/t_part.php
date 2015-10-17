<?php

if (!class_exists("Bbpp_ThankMeLater_TemplateHelper")) {
	require_once BBPP_THANKMELATER_PLUGIN_PATH . "TemplateHelper.php";
}

/**
 * Shortcode for a template part (e.g. the sidebar, content, etc)
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_t_part($atts, $content = NULL) {	
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"name" => "main"
	), $atts));
	
	$content = trim(Bbpp_ThankMeLater_Shortcoder::apply($content));
	
	Bbpp_ThankMeLater_TemplateHelper::add_part($name, $content);
	
	// Show no output. The template must explicity load this part!
	return "";
}

Bbpp_ThankMeLater_Shortcoder::add("t_part", "bbpp_thankmelater_shortcode_t_part");