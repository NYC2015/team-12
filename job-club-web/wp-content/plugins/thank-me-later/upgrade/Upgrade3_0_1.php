<?php

/**
 * Upgrade to version 3.0.1
 */
class Bbpp_ThankMeLater_Upgrade3_0_1 {
	public function ishere() {
		if (get_option("bbpp_thankmelater_version") == "3.0.1") {
			return TRUE;
		}
	}
	
	public function up() {
		// Update version number
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.0.1", NULL, TRUE);
	}
	
	public function down() {
		update_option("bbpp_thankmelater_version", "3.0");
	}
}