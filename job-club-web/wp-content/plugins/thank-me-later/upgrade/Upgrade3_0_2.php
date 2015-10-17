<?php

/**
 * Upgrade to version 3.0.2
 */
class Bbpp_ThankMeLater_Upgrade3_0_2 {
	public function ishere() {
		if (get_option("bbpp_thankmelater_version") == "3.0.2") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 3.0.1
	 */
	public function up() {
		global $wpdb;
		
		// in 3.0, we scheduled e-mails based on whether record in wp_tml_logs
		// existed -- BUT wp_tml_logs didn't exist until 2.1!
		// We need to fix this issue: if the send time is in the past, just
		// assume that it's already been sent and mark the schedule as such.
		$now_date_gmt = gmdate("Y-m-d H:i:s");
		$wpdb->query($wpdb->prepare("
			UPDATE `{$wpdb->prefix}bbpp_thankmelater_schedules`
			SET `sent` = 1
			WHERE `send_date_gmt` <= %s
		", $now_date_gmt));
		
		// Update version number
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.0.2", NULL, TRUE);
	}
	
	/**
	 * Downgrade to 3.0.1
	 */
	public function down() {
		update_option("bbpp_thankmelater_version", "3.0.1");
	}
}