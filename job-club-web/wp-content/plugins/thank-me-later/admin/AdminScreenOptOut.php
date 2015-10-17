<?php

/**
 * Handles the display of the 'opt out' pane in the admin screens.
 */
class Bbpp_ThankMeLater_AdminScreenOptOut {
	/**
	 * Show the user-requested page
	 */
	public function route() {
		global $wpdb;
		
		$action = "options";
		
		if (!empty($_REQUEST["action"])) {
			$action = stripslashes($_REQUEST["action"]);
		}
		
		switch ($action) {
			case "delete":
				$email = $_REQUEST["id"];
				
				// make sure request is real
				check_admin_referer("bbpp_thankmelater_opt_out_delete");
				
				// delete the opt out
				$wpdb->query($wpdb->prepare("
					DELETE FROM `{$wpdb->prefix}bbpp_thankmelater_opt_outs`
					WHERE `email` = %s
				", $email));
				
				$this->options();
				break;
			case "options":
			default:
				$this->options();
				break;
		}
	}
	
	/**
	 * Show opt out options page
	 * 
	 */
	public function options() {
		global $wpdb;
		
		$errors = array();
		$success = false;
		$opt_out_level = get_option("bbpp_thankmelater_opt_out_level", "disabled");
		$opt_out_form_type = get_option("bbpp_thankmelater_opt_out_form_type", "out");
		$opt_out_form_out_text = get_option("bbpp_thankmelater_opt_out_form_out_text", "1");
		$opt_out_form_out_text_custom = get_option("bbpp_thankmelater_opt_out_form_out_text_custom", "");
		$opt_out_form_in_text = get_option("bbpp_thankmelater_opt_out_form_in_text", "1");
		$opt_out_form_in_text_custom = get_option("bbpp_thankmelater_opt_out_form_in_text_custom", "");
		
		if ($_POST) {
			check_admin_referer("bbpp_thankmelater_opt_out_options");
			$data = stripslashes_deep($_POST);
			$opt_out_level = isset($data["bbpp_thankmelater_opt_out_level"]) ? $data["bbpp_thankmelater_opt_out_level"] : NULL;
			$opt_out_form_type = isset($data["bbpp_thankmelater_opt_out_form_type"]) ? $data["bbpp_thankmelater_opt_out_form_type"] : NULL;
			$opt_out_form_out_text = isset($data["bbpp_thankmelater_opt_out_form_out_text"]) ? $data["bbpp_thankmelater_opt_out_form_out_text"] : NULL;
			$opt_out_form_out_text_custom = isset($data["bbpp_thankmelater_opt_out_form_out_text_custom"]) ? $data["bbpp_thankmelater_opt_out_form_out_text_custom"] : NULL;
			$opt_out_form_in_text = isset($data["bbpp_thankmelater_opt_out_form_in_text"]) ? $data["bbpp_thankmelater_opt_out_form_in_text"] : NULL;
			$opt_out_form_in_text_custom = isset($data["bbpp_thankmelater_opt_out_form_in_text_custom"]) ? $data["bbpp_thankmelater_opt_out_form_in_text_custom"] : NULL;
			
			$error = new WP_Error();
			
			if (!in_array($opt_out_level, array("disabled", "email", "form"))) {
				$error->add("opt_out_level", __("You must select an option.", "bbpp-thankmelater"));
			}
			
			if ($opt_out_level == "form") {
				if (!in_array($opt_out_form_type, array("out", "in"))) {
					$error->add("opt_out_form_type", __("You must select an option.", "bbpp-thankmelater"));
				}
				
				if ($opt_out_form_type == "out") {
					if (!in_array($opt_out_form_out_text, array("1", "custom"))) {
						$error->add("opt_out_form_out_text", __("You must select an option.", "bbpp-thankmelater"));
					}
					
					if ($opt_out_form_out_text == "custom" && empty($opt_out_form_out_text_custom)) {
						$error->add("opt_out_form_out_text", __("This must not be blank.", "bbpp-thankmelater"));
					}
				} elseif ($opt_out_form_type == "in") {
					if (!in_array($opt_out_form_in_text, array("1", "custom"))) {
						$error->add("opt_out_form_in_text", __("You must select an option.", "bbpp-thankmelater"));
					}
					
					if ($opt_out_form_in_text == "custom" && empty($opt_out_form_in_text_custom)) {
						$error->add("opt_out_form_in_text", __("This must not be blank.", "bbpp-thankmelater"));
					}
				}
			}
			
			if ($error->get_error_codes()) {
				$errors[] = $error;
			} else {
				update_option("bbpp_thankmelater_opt_out_level", $opt_out_level);
				update_option("bbpp_thankmelater_opt_out_form_type", $opt_out_form_type);
				update_option("bbpp_thankmelater_opt_out_form_out_text", $opt_out_form_out_text);
				update_option("bbpp_thankmelater_opt_out_form_out_text_custom", $opt_out_form_out_text_custom);
				update_option("bbpp_thankmelater_opt_out_form_in_text", $opt_out_form_in_text);
				update_option("bbpp_thankmelater_opt_out_form_in_text_custom", $opt_out_form_in_text_custom);
				$success = true;
			}
		}
		
		// get a list of the most recent opt outs
		$opt_out_results = $wpdb->get_results("
			SELECT `email`, `date_gmt`
			FROM `{$wpdb->prefix}bbpp_thankmelater_opt_outs`
			ORDER BY `date_gmt` DESC
			LIMIT 100
		");
		
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/opt-out/options.php";
	}
}