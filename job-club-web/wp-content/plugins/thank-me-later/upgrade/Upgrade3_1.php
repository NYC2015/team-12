<?php

/**
 * Upgrade to version 3.1
 */
class Bbpp_ThankMeLater_Upgrade3_1 {
	public function ishere() {
		if (get_option("bbpp_thankmelater_version") == "3.1") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 3.0.7
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
		
		// create opt outs table
		$wpdb->query("
			CREATE TABLE `{$wpdb->prefix}bbpp_thankmelater_opt_outs` (
				`email` VARCHAR(255) NOT NULL,
				`date_gmt` DATETIME NOT NULL,
				PRIMARY KEY (`email`)
			) $charset_sql
		");
		
		// turn opt out link on by default
		update_option("bbpp_thankmelater_opt_out_level", "email");
		
		// Update version number
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.1", NULL, TRUE);
	}
	
	/**
	 * Downgrade to 3.0.7
	 */
	public function down() {
		delete_option("bbpp_thankmelater_opt_out_level");
		delete_option("bbpp_thankmelater_opt_out_form_type");
		delete_option("bbpp_thankmelater_opt_out_form_out_text");
		delete_option("bbpp_thankmelater_opt_out_form_out_text_custom");
		delete_option("bbpp_thankmelater_opt_out_form_in_text");
		delete_option("bbpp_thankmelater_opt_out_form_in_text_custom");
		
		update_option("bbpp_thankmelater_version", "3.0.7");
	}
}