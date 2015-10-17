<?php

/**
 * Upgrade to version 3.3
 */
class Bbpp_ThankMeLater_Upgrade3_3 {
	public function ishere() {
		if (get_option("bbpp_thankmelater_version") == "3.3") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 3.1
	 */
	public function up() {
		global $wpdb;
		
		// get the character set to use...
		$charset_sql = "";
		
		if (!empty($wpdb->charset)) {
			$charset_sql .= " CHARACTER SET {$wpdb->charset}";
		}
		
		if (!empty($wpdb->collate)) {
			$charset_sql .= " COLLATE {$wpdb->collate}";
		}
		
		// add track_email field to messages
		$wpdb->query("
			ALTER TABLE `{$wpdb->prefix}bbpp_thankmelater_messages`
			ADD `track_opens` INT UNSIGNED NOT NULL
		");
                
		// create opt outs table
		$wpdb->query("
			CREATE TABLE `{$wpdb->prefix}bbpp_thankmelater_opens` (
				`comment_id` INT UNSIGNED NOT NULL,
				`date_gmt` DATETIME NOT NULL,
				PRIMARY KEY (`comment_id`)
			) $charset_sql
		");

		// show installation screen
		update_option("bbpp_thankmelater_show_install_screen", true);
		
		// Update version number
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.3", NULL, TRUE);
	}
	
	/**
	 * Downgrade to 3.1
	 */
	public function down() {
		global $wpdb;
		
		update_option("bbpp_thankmelater_show_install_screen", false);
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}thankmelater_opens`");
		update_option("bbpp_thankmelater_version", "3.1");
	}
}