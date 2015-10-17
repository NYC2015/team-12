<?php

/**
 * Upgrade to version 3.0.7
 */
class Bbpp_ThankMeLater_Upgrade3_0_7 {
	public function ishere() {
		if (get_option("bbpp_thankmelater_version") == "3.0.7") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 3.0.6
	 */
	public function up() {		
		// Update version number
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.0.7", NULL, TRUE);
	}
	
	/**
	 * Downgrade to 3.0.6
	 */
	public function down() {
		update_option("bbpp_thankmelater_version", "3.0.6");
	}
}