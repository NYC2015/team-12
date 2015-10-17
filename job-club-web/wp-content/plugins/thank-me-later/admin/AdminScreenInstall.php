<?php

/**
 * Handles the display of the installation screen
 */
class Bbpp_ThankMeLater_AdminScreenInstall {
	/**
	 * Show the user-requested page
	 */
	public function route() {
		global $wpdb;
		
		$action = "install";
		
		if (!empty($_REQUEST["action"])) {
			$action = stripslashes($_REQUEST["action"]);
		}
		
		switch ($action) {
			case "continue":
				update_option("bbpp_thankmelater_show_install_screen", false);
				$this->done();
				break;
			case "install":
			default:
				$this->install();
				break;
		}
	}
	
	/**
	 * 
	 */
	public function done() {
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/install/done.php";
	}
	
	/**
	 * Show opt out options page
	 * 
	 */
	public function install() {
		global $wpdb;
		
		$errors = array();
		$success = false;
		
		if ($_POST) {
			check_admin_referer("bbpp_thankmelater_install");
			$data = stripslashes_deep($_POST);
			
			$error = new WP_Error();
			
			if ($error->get_error_codes()) {
				$errors[] = $error;
			} else {
				update_option("bbpp_thankmelater_show_install_screen", false);
				
				// enable email tracking
				$wpdb->query("
					UPDATE `{$wpdb->prefix}bbpp_thankmelater_messages`
					SET `track_opens` = 1
				");
				
				$success = true;
				return $this->done();
			}
		}
		
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/install/install.php";
	}
}