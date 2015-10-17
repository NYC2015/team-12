<?php

/**
 * 
 */
class Bbpp_ThankMeLater_ErrorHelper {
	/**
	 * Display a list of errors
	 * 
	 * @param array $error array of \WP_Error, one for each row of data
	 * @param string $code the field name/reference to show errors for
	 */
	public static function show_error($error, $code, $row = 0) {
		if (isset($error[$row]) && $error[$row]->get_error_messages($code)) {
			echo "<ul>";
			foreach ($error[$row]->get_error_messages($code) as $message) {
				echo "<li><strong>";
				echo esc_html($message);
				echo "</strong></li>";
			}
			echo "</ul>";
		}
	}
}