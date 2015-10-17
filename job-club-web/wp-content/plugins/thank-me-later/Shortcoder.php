<?php

/**
 * Collection of functions to parse shortcodes in text.
 * 
 * The code in this class is very much based on the shortcode code in
 * /wp-includes/shortcodes.php from the Wordpress project.
 */
class Bbpp_ThankMeLater_Shortcoder {
	/**
	 *
	 * @var type 
	 */
	public static $tags = array();
	
	/**
	 *
	 * @var type 
	 */
	private static $passatt = array();
	
	/**
	 * Read in the shortcodes from the directory shortcodes/
	 * 
	 * TODO add a hook somewhere so others can add their own shortcodes to the plugin.
	 */
	public static function init() {
		// load the shortcode files
		$dirh = opendir(BBPP_THANKMELATER_PLUGIN_PATH . "shortcodes");
		
		if ($dirh) {
			while (($file = readdir($dirh)) !== FALSE) {
				if ($file != "." && $file != ".." && preg_match("#\.php$#", $file)) {
					require_once BBPP_THANKMELATER_PLUGIN_PATH . "shortcodes/$file";
				}
			}
			closedir($dirh);
		}
	}
	
	/**
	 * Add a shortcode
	 * 
	 * @param string $tag Tag name of shortcode
	 * @param callable $func Function which handles the shortcode
	 */
	public static function add($tag, $func) {
		if (is_callable($func)) {
			self::$tags[$tag] = $func;
		}
	}
	
	/**
	 * Evaluate the shortcodes on some text
	 * 
	 * @param string $content Text containing shortcodes (or none)
	 * @param array $passatt Attributes to be passed to the shortcode handler callbacks.
	 * @return string The transformed text.
	 */
	public static function apply($content, $passatt = NULL) {
		if ($passatt !== NULL) {
			self::$passatt = $passatt;
		}
		
		$pattern = self::getRegex();
		
		// we want to split the message into nice paragraphs first. Don't want
		// to interfere with shortcode tags or the content between them, so
		// translate the tags into keys...
		$keysuffix = uniqid();
		$keyed = array();
		
		// replace \r\n style line breaks with \n
		$content = str_replace("\r\n", "\n", $content);
		
		preg_match_all(
			"/$pattern/s",
			$content,
			$match
		);
		
		foreach ($match[0] as $match) {
			$key = md5($match . $keysuffix);
			$keyed[$key] = $match;
			$content = str_replace($match, $key, $content);
		}
		
		// split by two or more new lines/carriage returns
		$lines = preg_split("#[\n\r]{2,}#", $content, NULL, PREG_SPLIT_DELIM_CAPTURE);
		
		// reconstruct as a sequence of paragraphs:
		$trans_content = "";
		
		if (count($lines) > 1) {
			foreach ($lines as $line) {
				$trans_content .= "[_p]" . trim($line) . "[/_p]";
			}
		} else {
			// [_p] will call us back... when we get to one line after evaluating
			// shortcodes inside the [_p], we stop adding [_p]'s.
			$trans_content = $lines[0];
		}
		
		// replace back the tag keys with the actual tags:
		foreach ($keyed as $key => $match) {
			$trans_content = str_replace($key, $match, $trans_content);
		}
		
		// evaluate the shortcodes (including the paragraphs we just created).
		return trim(preg_replace_callback(
			"/$pattern/s", 
			array("Bbpp_ThankMeLater_Shortcoder", "applyTag"), 
			$trans_content
		));
	}
	
	/**
	 * 
	 * @param type $pairs
	 * @param type $atts
	 */
	public function atts($pairs, $atts) {
		$atts = (array)$atts;
		$out = array();
		foreach($pairs as $name => $default) {
				if ( array_key_exists($name, $atts) )
						$out[$name] = $atts[$name];
				else
						$out[$name] = $default;
		}
		return $out;
	}
	
	/**
	 * 
	 */
	private static function applyTag($m) {
		if ($m[1] == "[" && $m[6] == "]") {
			return substr($m[0], 1, -1);
		}
		
		$tag = $m[2];
		$attr = self::$passatt;
		$pa = self::parseAtts($m[3]);
		if (is_array($pa)) {
			$attr = array_merge($attr, $pa);
		}
		
		if (isset($m[5])) {
			return $m[1] . call_user_func(self::$tags[$tag], $attr, $m[5], $tag) . $m[6];
		} else {
			return $m[1] . call_user_func(self::$tags[$tag], $attr, NULL, $tag) . $m[6];
		}
	}
	
	/**
	 * 
	 * @param type $text
	 */
	private static function parseAtts($text) {
		$atts = array();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
		if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
			foreach ($match as $m) {
				if (!empty($m[1]))
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				elseif (!empty($m[3]))
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				elseif (!empty($m[5]))
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				elseif (isset($m[7]) and strlen($m[7]))
					$atts[] = stripcslashes($m[7]);
				elseif (isset($m[8]))
					$atts[] = stripcslashes($m[8]);
			}
		} else {
				$atts = ltrim($text);
			}
		return $atts;
	}
	
	/**
	 * 
	 */
	private static function getRegex() {
		$tagnames = array_keys(self::$tags);
		$tagregexp = implode("|", array_map("preg_quote", $tagnames));
		
		return
	                  '\\['                              // Opening bracket
	                . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
	                . "($tagregexp)"                     // 2: Shortcode name
	                . '(?![\\w-])'                       // Not followed by word character or hyphen
	                . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
	                .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
	                .     '(?:'
	                .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
	                .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
	                .     ')*?'
	                . ')'
	                . '(?:'
	                .     '(\\/)'                        // 4: Self closing tag ...
	                .     '\\]'                          // ... and closing bracket
	                . '|'
	                .     '\\]'                          // Closing bracket
	                .     '(?:'
	                .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
	                .             '[^\\[]*+'             // Not an opening bracket
	                .             '(?:'
	                .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
	                .                 '[^\\[]*+'         // Not an opening bracket
	                .             ')*+'
	                .         ')'
	                .         '\\[\\/\\2\\]'             // Closing shortcode tag
					.     ')?'
	                . ')'
	                . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}
}

// initialize the shortcoder
Bbpp_ThankMeLater_Shortcoder::init();