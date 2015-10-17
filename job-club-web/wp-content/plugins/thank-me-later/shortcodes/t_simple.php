<?php

/**
 * Shortcode for simple template
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_t_simple($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"background_color" => "#F6F6F6",
		"page_background_color" => "#FFFFFF"
	), $atts));
	
	if (!preg_match("#^\#[0-F]{6}$#i", $background_color)) {
		$background_color = "#F6F6F6";
	}
	
	if (!preg_match("#^\#[0-F]{6}$#i", $page_background_color)) {
		$page_background_color = "#FFFFFF";
	}
	
	// execute shortcodes inside...
	$content = trim(Bbpp_ThankMeLater_Shortcoder::apply($content));
	$main = Bbpp_ThankMeLater_TemplateHelper::get_part("main");
	
	if (!$main) {
		$main = $content;
	}
	
	if ($email_type == "text") {
		$output = "";
		
		$output .= Bbpp_ThankMeLater_TemplateHelper::get_part("header") . "\n\n";
		$output .= $main . "\n\n";
		$output .= Bbpp_ThankMeLater_TemplateHelper::get_part("footer");
		
		return $output;
	}
	
	$output = "";
	$output .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\""
		. " \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
	$output .= "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
	$output .= "<head>\n";
	$output .= "<style type=\"text/css\">\n";
	$output .= "body { width: 100%; margin: 0px; padding: 0px;"
		. "font-family: arial, sans-serif; font-size: 14px; line-height: 18px;"
		. "color: #333333; background: {$background_color};}\n";
	$output .= "#wrap { padding-top: 10px; }\n";
	$output .= "#header-banner { padding: 0px; }\n";
	$output .= "#header { padding-top: 5px; padding-bottom: 5px;"
		. " padding-left: 20px; padding-right: 20px; }\n";
	$output .= "h1 { font-size: 32px; line-height: 41px; margin: 0px;"
		. " letter-spacing: -1px;}\n";
	$output .= "h2 { font-size: 18px; line-height: 23px; margin: 0px;"
		. " letter-spacing: -1px;}\n";
	$output .= "a { color: #0000FF; }\n";
	$output .= "p { padding-top: 0px; padding-bottom: 18px; margin: 0px; }\n";
	$output .= "#header-banner-table { background-color: " . $page_background_color . "; }\n";
	$output .= "#header-table { background-color: " . $page_background_color . "; }\n";
	$output .= "#content-table { background-color: " . $page_background_color . ";"
		. " padding-bottom: 20px; }\n";
	$output .= "#main { padding-left: 20px; padding-right: 20px; }\n";
	$output .= "#footer { padding-top: 5px; padding-bottom: 5px;"
		. " padding-left: 20px; padding-right: 20px; }\n";
	$output .= "#footer-table { background-color: " . $page_background_color . "; padding-bottom: 20px; }\n";
	$output .= "</style>\n";
	$output .= "</head>\n";
	$output .= "<body>\n";
	
	// center
	$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" "
		. "width=\"100%\">\n";
	$output .= "<tr>\n";
	$output .= "<td align=\"center\" id=\"wrap\">\n";
	
	// header banner
	$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\""
	. " width=\"600\" id=\"header-banner-table\">\n";
	$output .= "<tr>\n";
	$output .= "<td id=\"header-banner\">\n";
		
	if ($header_banner = Bbpp_ThankMeLater_TemplateHelper::get_part("header_banner")) {
		$output .= $header_banner . "\n";
	} else {
		$output .= "<img src=\"" . plugins_url('imgs/thank-you-banner.png' , dirname(__FILE__)) . "\" width=\"600\" height=\"75\">";
	}
		
	// end header banner
	$output .= "</td>\n";
	$output .= "</tr>\n";
	$output .= "</table>\n";
	
	// header
	$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\""
		. " width=\"600\" id=\"header-table\">\n";
	$output .= "<tr>\n";
	$output .= "<td id=\"header\">\n";
	
	$output .= Bbpp_ThankMeLater_TemplateHelper::get_part("header") . "\n";
	
	// end header
	$output .= "</td>\n";
	$output .= "</tr>\n";
	$output .= "</table>\n";
	
	// main
	$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\""
		. " width=\"600\" id=\"content-table\">\n";
	$output .= "<tr>\n";
	$output .= "<td id=\"main\" valign=\"top\">\n";

	$output .= $main;

	// end main
	$output .= "</td>\n";
	$output .= "</tr>\n";
	$output .= "</table>\n";
	
	// footer
	$output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\""
		. " width=\"600\" id=\"footer-table\">\n";
	$output .= "<tr>\n";
	$output .= "<td id=\"footer\">\n";
	
	$output .= Bbpp_ThankMeLater_TemplateHelper::get_part("footer") . "\n";
	
	// end footer
	$output .= "</td>\n";
	$output .= "</tr>\n";
	$output .= "</table>\n";
	
	// end center
	$output .= "</td>\n";
	$output .= "</tr>\n";
	$output .= "</table>\n";
	
	$output .= "</body>\n";
	$output .= "</html>";
	
	return Bbpp_ThankMeLater_TemplateHelper::inline_css($output);
}

Bbpp_ThankMeLater_Shortcoder::add("t_simple", "bbpp_thankmelater_shortcode_t_simple");
Bbpp_ThankMeLater_Shortcoder::add("template_simple", "bbpp_thankmelater_shortcode_t_simple");