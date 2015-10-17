<?php

/**
 * Upgrade to version 3.0.5
 */
class Bbpp_ThankMeLater_Upgrade3_0_5 {
	public function ishere() {
		if (get_option("bbpp_thankmelater_version") == "3.0.5") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 3.0.4
	 */
	public function up() {		
		// Update version number
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.0.5", NULL, TRUE);
	}
	
	/**
	 * Downgrade to 3.0.4
	 */
	public function down() {
		update_option("bbpp_thankmelater_version", "3.0.4");
	}
}