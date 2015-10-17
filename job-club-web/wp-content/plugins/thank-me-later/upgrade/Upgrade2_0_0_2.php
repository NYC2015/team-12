<?php

/**
 * Upgrade to version 2.0.0.2 (also 2.0)
 */
class Bbpp_ThankMeLater_Upgrade2_0_0_2 {
	public function ishere() {
		if (get_option("_tml2.0_installed_version") == "2.0") {
			return TRUE;
		}
		
		if (get_option("_tml2.0_installed_version") == "2.0.0.1") {
			return TRUE;
		}
		
		if (get_option("_tml2.0_installed_version") == "2.0.0.2") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 1.5.3.1
	 */
	public function up() {
		global $wpdb;
		
		// load 1.5.3.1 options
		$old_opts = get_option("_tml_options");
		if ($old_opts !== FALSE && !is_array($old_opts)) {
			$old_opts = unserialize($old_opts);
		}
		
		// reformat the restriction arrays
		$tag_slugs = $old_opts["restrict_tagsArr"];
		if (!is_array($tag_slugs)) {
			$tag_slugs = array();
		}
		$tag_slugs = array_flip($tag_slugs);

		$cat_slugs = $old_opts["restrict_categoriesArr"];
		if (!is_array($cat_slugs)) {
			$cat_slugs = array();
		}
		$cat_slugs = array_flip($cat_slugs);

		$opts = array(
			"max_messages"     => $old_opts["maxSend"],
			"comment_gap"      => $old_opts["sendGap"],
			"messages"         => array(array(
					"from_name"                     => $old_opts["message_from"],
					"from_email"                    => $old_opts["message_fromemail"],
					"message_subject"               => $old_opts["message_subject"],
					"use_html"                      => ($old_opts["html"] == "true") ? 1 : 0,
					"nl2br"                         => 0,
					"message_body"                  => $old_opts["message_message"],
					"send_after_use_default"        => 0,
					"send_after"                    => $old_opts["sendafter"],
					"send_after_plus_minus"         => $old_opts["giveortake"],
					"restrict_by_tags_use_default"  => 0,
					"restrict_by_tags_type"         => $old_opts["restrict_tagsType"],
					"restrict_by_tags_slugs"        => $tag_slugs,
					"restrict_by_cats_use_default"  => 0,
					"restrict_by_cats_type"         => $old_opts["restrict_categoriesType"],
					"restrict_by_cats_slugs"        => $cat_slugs,
					"restrict_by_users_use_default" => 1,
					"restrict_by_users"             => 0,
					"restrict_by_users_type"        => "logged-in",
					"prob"                          => 1,
					"uid"                           => 0					
				))
		);
		
		$message = $opts["messages"][0];

		foreach ($opts as $name => $val) {
			$autoload = "no";

			$opt_name = "_tml2.0_" . $name;
			$opt_val = (is_object($val) || is_array($val)) ? serialize($val) : $val;

			if (get_option($opt_name) !== false) {
				update_option($opt_name, $opt_val);
			} else {
				add_option($opt_name, $opt_val, "", $autoload);
			}
		}

		// remove the 1.5.3.1 options
		delete_option("_tml_options");
		delete_option("_tml_installed");
		delete_option("_tml_promote");
		
		// create the database tables
		$wpdb->query("CREATE TABLE `{$wpdb->prefix}tml_emails` (
			`ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`email` VARCHAR(255) NOT NULL UNIQUE,
			`subscribed` BOOLEAN,
			PRIMARY KEY (`ID`)
		)");
		$wpdb->query("CREATE TABLE `{$wpdb->prefix}tml_queue` (
			`ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`email_ID` INT UNSIGNED NOT NULL,
			`comment_ID` INT UNSIGNED NOT NULL UNIQUE,
			`send_time` INT UNSIGNED NOT NULL ,
			`message_uid` INT UNSIGNED NOT NULL,
			PRIMARY KEY ( `ID` ) ,
			INDEX ( `send_time` )
		)");
		$wpdb->query("CREATE TABLE `{$wpdb->prefix}tml_history` (
			`comment_ID` INT UNSIGNED NOT NULL,
			`email_ID` INT UNSIGNED NOT NULL,						
			`time` INT UNSIGNED NOT NULL ,
			`send_time` INT UNSIGNED NOT NULL,
			`message_uid` INT UNSIGNED NOT NULL,
			`use_as_unique` BOOLEAN,
			PRIMARY KEY ( `comment_ID` ),
			INDEX ( `email_ID` )
		)");
		
		// copy data from 1.5.3.1 into 2.0
		$i = 0;
		$rpq = 100;
		while ($results_set = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}thankmelater_sent` LIMIT {$i}, {$rpq}")) {
			foreach ($results_set as $row) { // for each e-mail
				$wpdb->insert($wpdb->prefix . "tml_emails", array(
					"email" => $row->email,
					"subscribed" => true
				), array("%s", "%b"));
				$email_id = $wpdb->insert_id;
				
				###		Messages in the queue		###
				$queued_items = $wpdb->get_results($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}thankmelater` WHERE email_ID = %d", $row->id));

				foreach ($queued_items as $item) {
					$comment = get_comment($item->comment_ID, OBJECT); // comment object
					
					if ($comment->comment_type != "") { // is it /really/ a comment?
						return;
					}
					
					$status = wp_get_comment_status($item->comment_ID);
					if (in_array($status, array("hold", "spam", "delete"))) {
						continue;
					}
					
					$send_time = (int)(time() + $message["send_after"] - $message["send_after_plus_minus"] + mt_rand(0, $message["send_after_plus_minus"]*2));
					
					$wpdb->insert( 
						$wpdb->prefix . "tml_queue", 
						array(
							"email_ID"    => $email_id,
							"comment_ID"  => $item->comment_ID,
							"send_time"   => $send_time,
							"message_uid" => $message["uid"]
						),
						array("%d", "%d", "%d", "%d")
					);
					
					$wpdb->insert( 
						$wpdb->prefix . "tml_history",
						array(
							"comment_ID"  => $item->comment_ID,
							"email_ID"    => $email_id,
							"time"        => time(),
							"send_time"   => $send_time,
							"message_uid" => $message["uid"],
							"use_as_unique"=> true,
						),
						array("%d", "%d", "%d", "%d", "%d", "%b") 
					);
				}
			}
			
			$i += $rpq;
		}
		
		// drop the 1.5.3 tables
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}thankmelater`");
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}thankmelater_sent`");
		
		// indicate we are at 2.0.0.2:
		update_option("_tml2.0_installed_version", "2.0.0.2");
	}

	/**
	 * Downgrade to 1.4.1
	 */
	public function down() {
		
	}
}