<?php

/**
 * Upgrade to version 2.1
 */
class Bbpp_ThankMeLater_Upgrade2_1 {
	public function ishere() {
		if (get_option("_tml2.0_installed_version") == "2.1") {
			return TRUE;
		}
	}
	
	public function up() {
		global $wpdb;
		
		$wpdb->query("
			CREATE TABLE `{$wpdb->prefix}tml_log` (
				`ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`email` VARCHAR(255) NOT NULL,
				`comment_ID` INT UNSIGNED NOT NULL,
				`send_time` INT UNSIGNED NOT NULL,
				`subject` TEXT NOT NULL,
				`message` TEXT NOT NULL,
				PRIMARY KEY ( `ID` ) 
			)
		");
			
		// indicate we are at 2.1:
		update_option("_tml2.0_installed_version", "2.1");
	}

	/**
	 * 
	 */
	public function down() {
		
	}
}