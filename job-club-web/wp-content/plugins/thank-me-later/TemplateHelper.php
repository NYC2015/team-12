<?php

if (!class_exists("InStyle")) {
	require_once BBPP_THANKMELATER_PLUGIN_PATH . "lib/instyle/instyle.php";
}

/**
 * Some useful functions for handling email templates
 */
class Bbpp_ThankMeLater_TemplateHelper {
	/**
	 *
	 * @var array 
	 */
	public static $parts = array();

	/**
	 * Add content for a template-part. Content is already processed by Shortcoder.
	 * 
	 * @param string $name
	 * @param string $content
	 */
	public static function add_part($name, $content) {
		self::$parts[$name] = $content;
	}
	
	/**
	 * 
	 * @param type $name
	 * @return null
	 */
	public static function get_part($name) {
		if (isset(self::$parts[$name])) {
			return self::$parts[$name];
		}
		
		return NULL;
	}
	
	/**
	 * 
	 * @param type $html
	 * @return type
	 */
	public static function inline_css($html) {
		$instyle = new instyle();
		return $instyle->convert($html);
	}
}