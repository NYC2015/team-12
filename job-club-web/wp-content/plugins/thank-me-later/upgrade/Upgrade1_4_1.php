<?php

/**
 * Upgrade to version 1.4.1 (also version 1.4)
 */
class Bbpp_ThankMeLater_Upgrade1_4_1 {
	public function ishere() {
		// 1.4.1 and 1.4 identify as 1.4
		if (get_option("_tml_installed") == "1.4") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 1.3.1
	 */
	public function up() {
		global $wpdb;
		
		// set option to load automatically...
		delete_option('_tml_installed');
		add_option('_tml_installed','false',NULL,'yes');
		
		// delete data: do not carry it over from previous versions
		$wpdb->query("
			TRUNCATE TABLE `{$wpdb->prefix}thankmelater
		");
		$wpdb->query("
			TRUNCATE TABLE `{$wpdb->prefix}thankmelater_sent
		");
		
		// changes to thankmelater table
		$wpdb->query("
			ALTER TABLE `{$wpdb->prefix}thankmelater`
			ADD COLUMN `email_ID` INT UNSIGNED NOT NULL AFTER `id`
		");
		$wpdb->query("
			ALTER TABLE `{$wpdb->prefix}thankmelater`
			CHANGE COLUMN `comment_ID` `comment_ID` INT UNSIGNED NOT NULL UNIQUE
		");
		
		// changes to thankmelater_sent table
		$wpdb->query("
			ALTER TABLE `{$wpdb->prefix}thankmelater_sent`
			ADD COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
			ADD PRIMARY KEY(`id`)
		");
		$wpdb->query("
			ALTER TABLE `{$wpdb->prefix}thankmelater_sent`
			ADD COLUMN `actualsent` SMALLINT UNSIGNED NOT NULL AFTER `sent`
		");
		$wpdb->query("
			ALTER TABLE `{$wpdb->prefix}thankmelater_sent`
			ADD INDEX (`actualsent`)
		");
		
		// add `bases` to options
		$_TML_options = get_option("_tml_options");
		if (!is_array($_TML_options)) {
			$_TML_options = unserialize($_TML_options);
		}
		if (!isset($_TML_options["bases"])) {
			$_TML_options["bases"] = array(
				"sendafter" => 86400,
				"giveortake" => 3600,
				"updateInterval" => 60,
				"sendGap" => 604800
			);
		}
		delete_option('_tml_options');
		add_option('_tml_options', serialize($_TML_options), NULL, 'no');
		
		// indicate we are at version 1.4
		update_option("_tml_installed", "1.4");
	}

	/**
	 * Downgrade to 1.3.1
	 */
	public function down() {
		
	}
}