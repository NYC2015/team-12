<?php

/**
 * Shortcode for paragraph
 * 
 * @param type $attr
 */
function bbpp_thankmelater_shortcode_p($atts, $content = NULL) {
	extract(Bbpp_ThankMeLater_Shortcoder::atts(array(
		"email_type" => NULL,
		"comment" => NULL,
		"force" => 0
	), $atts));
	
	// execute shortcodes inside...
	$content = trim(Bbpp_ThankMeLater_Shortcoder::apply($content));
	
	// empty paragraph?
	if (empty($content) && !$force) {
		return "";
	}	
	
	if ($email_type == "text") {
		return $content . "\n\n";
	} else {
		// elements which cannot appear in a <p>
		$noninline_els_regex = implode("|", array(
			// HTML 4 elements (http://www.w3.org/TR/html4/index/elements.html)
			"address", "applet", "area",
			"base", "basefont", "blockquote",
			"body", "button", "caption", "center",
			"col", "colgroup", "dd",
			"dir", "div", "dl", "dt", "fieldset",
			"form", "frame", "frameset", "h1", "h2",
			"h3", "h4", "h5", "h6", "head", "hr",
			"html", "iframe", "ins",
			"isindex", "legend", "li",
			"link", "map", "menu", "meta", "noframes", "noscript",
			"object", "ol", "optgroup", "option", "p", "param",
			"pre",
			"style",
			"table", "tbody", "td", "tfoot",
			"th", "thead", "title", "tr", "u",
			"ul",
			
			// Others (future proofing)
			"article", "aside", "audio", "bdi", "canvas", "command",
			"datalist", "details", "embed", "figcaption", "figure", "header",
			"hgroup", "keygen", "mark", "output", "progress", "rp",
			"rt", "ruby", "section", "source", "summary", "time",
			"track", "video"			
		));
		
		// if there's any non-inline elements involved, don't put in the paragraph:
		// up to the user to place [p] ... [/p] themselves (around elements which
		// are not block)
		// TODO may consider inserting a paragraph up to the point that the non-inline
		// element occurs
		if (preg_match("#</?($noninline_els_regex)( |>)#i", $content)) {
			return $content;
		} else {
			return "<p>" . nl2br($content) . "</p>";
		}
	}
}

Bbpp_ThankMeLater_Shortcoder::add("p", "bbpp_thankmelater_shortcode_p");
Bbpp_ThankMeLater_Shortcoder::add("_p", "bbpp_thankmelater_shortcode_p");