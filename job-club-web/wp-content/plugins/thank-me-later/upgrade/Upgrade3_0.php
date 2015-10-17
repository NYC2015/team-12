<?php

/**
 * Upgrade to version 3.0
 */
class Bbpp_ThankMeLater_Upgrade3_0 {
	public function ishere() {
		if (get_option("bbpp_thankmelater_version") == "3.0") {
			return TRUE;
		}
	}
	
	/**
	 * Upgrade from 2.1
	 */
	public function up() {
		// databaase tables
		$this->_create_tables();
		
		// Copy messages
		$this->_copy_messages();
		
		// Copy history, queue into schedule
		$this->_copy_schedule();
		
		// Remove 2.1 crons
		$this->_remove_crons();
		 
		// Remove 2.1 options
		$this->_remove_options(); 
		
		// Remove 2.1 tables
		$this->_remove_tables();
		
		// Update version number
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.0", NULL, TRUE);
	}
	
	/**
	 * Fresh install
	 */
	public function up_from_0() {
		global $wpdb;
		
		// database tables
		$this->_create_tables();
		
		$blog_name = get_bloginfo("name");
		
		// insert a default message:
		/*$message = new Bbpp_ThankMeLater_Message();*/
		$message_subject = sprintf(
			_x("%s - %s", "post title - blog name", "bbpp-thankmelater"),
			"[post_title]",
			$blog_name
		);
		$message_message = "[t_simple]\n\n";
		$message_message .= "[t_part name=\"main\"]\n\n";
		$message_message .= sprintf(__("Hi %s,", "bbpp-thankmelater"), "[name]");

		/* translators: the string "[post_title]" will be replaced with the blog post's title. Please do not remove this string. */
		$t_message_thanks = __("Thank you for your comment on [post_title]. Please check back soon for a response.", "bbpp-thankmelater");

		$message_message .= "\n\n" . sprintf($t_message_thanks, $blog_name);
		$message_message .= "\n\n";
		$message_message .= "[htmlonly]\n<a href=\"[comment_url attr=1]\">";
		$message_message .= __("Return to your comment.", "bbpp-thankmelater");
		$message_message .= "</a>\n[/htmlonly]";
		$message_message .= "\n\n[textonly]\n";

		/* translators: the string "[comment_url]" will be replaced with the URL to the user's comment. Please do not remove this string. */
		$t_message_link = __("Return to your comment: [comment_url]", "bbpp-thankmelater");

		$message_message .= $t_message_link;
		$message_message .= "\n[/textonly]";
		$message_message .= "\n\n" . sprintf(
			__("You posted this comment on %s: %s", "bbpp-thankmelater"),
			"[date format=\"d M\"]",
			"[comment maxlength=200]"
		);
		$message_message .= "\n\n" . __("Thank you!", "bbpp-thankmelater") . "\n\n";			
		$message_message .= "[/t_part]\n\n";
		$message_message .= "[/t_simple]";
		/*$message->addMessage(array(
			"from_name" => $blog_name,
			"from_email" => get_bloginfo("admin_email"),
			"subject" => $message_subject,
			"message" => $message_message,
			"min_delay" => 30,
			"min_delay_unit" => "minutes",
			"target_tags" => array(),
			"target_categories" => array(),
			"target_posts" => array(),
			"max_sends_per_email" => 0
		));
		$message->save();*/
		// write this message
		$wpdb->insert(
			$wpdb->prefix . "bbpp_thankmelater_messages",
			array(
				"from_name" => $blog_name,
				"from_email" => get_bloginfo("admin_email"),
				"subject" => $message_subject,
				"message" => $message_message,
				"min_delay" => 30,
				"min_delay_unit" => "minutes",
				"target_tags" => implode(",", array()),
				"target_categories" => implode(",", array()),
				"target_posts" => implode(",", array()),
				"max_sends_per_email" => 0
			)
		);
		
		// indicate we are at 3.0
		delete_option("bbpp_thankmelater_version");
		add_option("bbpp_thankmelater_version", "3.0", NULL, TRUE);
	}
	
	/**
	 * 
	 */
	private function _copy_messages() {
		global $wpdb;
		
		// read in the message defaults
		$default_restrict_by_tags_type = get_option("_tml2.0_restrict_by_tags_type");
		if ($default_restrict_by_tags_type === false) {
			$default_restrict_by_tags_type = 0;
		}
		$default_restrict_by_tags_slugs = maybe_unserialize(get_option("_tml2.0_restrict_by_tags_slugs"));
		if ($default_restrict_by_tags_slugs === false) {
			$default_restrict_by_tags_slugs = array("no-tml");
		}
		$default_restrict_by_cats_type = get_option("_tml2.0_restrict_by_cats_type");
		if ($default_restrict_by_cats_type === false) {
			$default_restrict_by_cats_type = 0;
		}
		$default_restrict_by_cats_slugs = maybe_unserialize(get_option("_tml2.0_restrict_by_cats_slugs"));
		if ($default_restrict_by_cats_slugs === false) {
			$default_restrict_by_cats_slugs = array("no-tml");
		}
		$default_send_after = get_option("_tml2.0_send_after");
		if ($default_send_after === false) {
			$default_send_after = 1800;
		}
		$default_max_messages = get_option("_tml2.0_max_messages");
		if ($default_max_messages === false) {
			$default_max_messages = 0;
		}
		
		// read in the tags we have
		$tag_options = get_tags(array(
			"number" => 200, 
			"orderby" => "count",
			"order" => "desc",
			"hide_empty" => false
		));
		// ...and the categories
		$category_options = get_categories(array(
			"number" => 200, 
			"orderby" => "count",
			"order" => "desc",
			"hide_empty" => false
		));
		
		$messages = maybe_unserialize(get_option("_tml2.0_messages"));
		
		foreach ($messages as $data) {
			// convert <$ID> to [id] for example...
			$from_name = $this->_replace_message_tags($data["from_name"]);
			$from_email = $this->_replace_message_tags($data["from_email"]);
			$subject = $this->_replace_message_tags($data["message_subject"]);
			$message = $this->_replace_message_tags($data["message_body"]);
			$min_delay = 0;
			$min_delay_unit = "minutes";
			$target_tags = array();
			$target_categories = array();
			
			if ($data["use_html"]) {
				$message = "[htmlonly]\n{$message}\n[/htmlonly]\n\n[textonly]\n" . strip_tags($message) . "\n[/textonly]";
			}
			
			if ($data["send_after_use_default"]) {
				$min_delay = $default_send_after/60;
			} else {
				$min_delay = $data["send_after"]/60;
			}
			
			// restrict_by_tags_type: 0 = "Include all excluding", 1 = "Include only"
			$orig_restrict_by_tags_type = $data["restrict_by_tags_type"];
			$orig_restrict_by_tags_slugs = $data["restrict_by_tags_slugs"];
			
			if ($data["restrict_by_tags_use_default"]) {
				$orig_restrict_by_tags_type = $default_restrict_by_tags_type;
				$orig_restrict_by_tags_slugs = $default_restrict_by_tags_slugs;
			}
			
			if ($orig_restrict_by_tags_type) {
				// "Include only"
				foreach ($orig_restrict_by_tags_slugs as $slug) {
					if ($term = get_term_by("slug", $slug, "post_tag")) {
						$target_tags[] = $term->term_id;
					}
				}
			} else {
				// "Include all excluding"
				$num = count($orig_restrict_by_tags_slugs);
				
				// if array("no-tml"), just target everything...
				if ($num > 1 || (($num == 1) && $orig_restrict_by_tags_slugs[0] != "no-tml")) {
					// "Include all excluding"
					foreach ($tag_options as $term) {
						if (!in_array($term->slug, $orig_restrict_by_tags_slugs)) {
							$target_tags[] = $term->term_id;
						}
					}
				}
			}
			
			// now do the same for categories
			$orig_restrict_by_cats_type = $data["restrict_by_cats_type"];
			$orig_restrict_by_cats_slugs = $data["restrict_by_cats_slugs"];
			
			if ($data["restrict_by_cats_use_default"]) {
				$orig_restrict_by_cats_type = $default_restrict_by_cats_type;
				$orig_restrict_by_cats_slugs = $default_restrict_by_cats_slugs;
			}
			
			if ($orig_restrict_by_cats_type) {
				// "Include only"
				foreach ($orig_restrict_by_cats_slugs as $slug) {
					if ($term = get_term_by("slug", $slug, "category")) {
						$target_categories[] = $term->term_id;
					}
				}
			} else {
				// "Include all excluding"
				$num = count($orig_restrict_by_cats_slugs);
				
				// if array("no-tml"), just target everything...
				if ($num > 1 || (($num == 1) && $orig_restrict_by_cats_slugs[0] != "no-tml")) {
					// "Include all excluding"
					foreach ($category_options as $term) {
						if (!in_array($term->slug, $orig_restrict_by_cats_slugs)) {
							$target_categories[] = $term->term_id;
						}
					}
				}
			}
			
			$weeks_r = round($min_delay/1440/7, 2)-($min_delay/1440/7);
			$days_r = round($min_delay/1440, 2)-($min_delay/1440);
			$hours_r = round($min_delay/60, 2)-($min_delay/60);
			
			if ($weeks_r >= -0.0001 && $weeks_r <= 0.0001) {
				$min_delay /= 1440*7;
				$min_delay_unit = "weeks";
			} elseif ($days_r >= -0.0001 && $days_r <= 0.0001) {
				$min_delay /= 1440;
				$min_delay_unit = "days";
			} elseif ($hours_r >= -0.0001 && $hours_r <= 0.0001) {
				$min_delay /= 60;
				$min_delay_unit = "hours";
			}
			
			// write this message
			$wpdb->insert(
				$wpdb->prefix . "bbpp_thankmelater_messages",
				array(
					"from_name" => $from_name,
					"from_email" => $from_email,
					"subject" => $subject,
					"message" => $message,
					"min_delay" => $min_delay,
					"min_delay_unit" => $min_delay_unit,
					"target_tags" => implode(",", $target_tags),
					"target_categories" => implode(",", $target_categories),
					"target_posts" => "",
					"max_sends_per_email" => $default_max_messages
				)
			);
		}
	}
	
	/**
	 * 
	 */
	private function _copy_schedule() {
		global $wpdb;
		
		$message_ids = array();
		
		$messages = maybe_unserialize(get_option("_tml2.0_messages"));
		
		foreach ($messages as $data) {
			$message_ids[$data["uid"]] = count($message_ids) + 1;
		}
		
		unset($messages);
		
		// read the emails that have been sent or scheduled ever
		$offset = 0;
		$limit = 100;
		$has_results = true;
		
		while ($has_results) {
			$results = $wpdb->get_results("
				SELECT 
					`{$wpdb->prefix}tml_history`.comment_ID, 
					`{$wpdb->prefix}tml_history`.send_time, 
					`{$wpdb->prefix}tml_history`.message_uid, 
					`{$wpdb->prefix}tml_log`.`ID`
				FROM `{$wpdb->prefix}tml_history`
				LEFT JOIN `{$wpdb->prefix}tml_log`
				ON `{$wpdb->prefix}tml_history`.`comment_ID` = `{$wpdb->prefix}tml_log`.`comment_ID`
				LIMIT {$limit} OFFSET {$offset}
			");
		
			if (!$results) {
				$has_results = false;
			}
			
			foreach ($results as $result) {
				$comment = get_comment($result->comment_ID);				
				$message_id = $message_ids[$result->message_uid];
				$comment_id = $result->comment_ID;
				$send_date_gmt = gmdate("Y-m-d H:i:s", $result->send_time);
				$sent = ($result->ID === NULL) ? 0 : 1;
				
				// this is just a test e-mail use by Thank Me Later 2.0
				if ($comment->comment_content === "This is just a sample comment that I've written") {
					continue;
				}

				if (!$sent) {
					// comment deleted?
					if (!$comment) {
						continue;
					}
					
					// don't process pingbacks and other non-comments...
					if ($comment->comment_type !== "") {
						continue;
					}

					// make sure comment is approved (i.e. not deleted & not spam):
					if ($comment->comment_approved != "1") {
						continue;
					}
				}
				
				// insert into schedule
				$wpdb->insert(
					$wpdb->prefix . "bbpp_thankmelater_schedules",
					array(
						"message_id" => $message_id,
						"comment_id" => $comment_id,
						"send_date_gmt" => $send_date_gmt,
						"sent" => $sent
					)
				);
				
				// Note: we do not schedule these sends with WP Cron -- messages will be
				// sent in the hourly check done by the plugin.
			}
						
			$offset += $limit;
		}
	}
	
	/**
	 * Remove 2.1 crons
	 */
	private function _remove_crons() {
		wp_clear_scheduled_hook("_tml_pQSched");
		wp_clear_scheduled_hook("_tml_singleUpdate");
	}
	
	/**
	 * Remove 2.x options that are not needed in 3.x
	 */
	private function _remove_options() {
		// remove 2.0.0.2 options
		delete_option("_tml2.0_installed_version");
		delete_option("_tml2.0_last_wpcron_tick");
		delete_option("_tml2.0_last_pseudo_tick");
		delete_option("_tml2.0_max_messages");
		delete_option("_tml2.0_unique_messages_only");
		delete_option("_tml2.0_unique_unless_all_sent");
		delete_option("_tml2.0_comment_gap");
		delete_option("_tml2.0_send_gap");
		delete_option("_tml2.0_allow_opt_out");
		delete_option("_tml2.0_show_opt_out");
		delete_option("_tml2.0_restrict_by_users");
		delete_option("_tml2.0_restrict_by_users_type");
		delete_option("_tml2.0_messages");
		delete_option("_tml2.0_from_name");
		delete_option("_tml2.0_from_email");
		delete_option("_tml2.0_message_subject");
		delete_option("_tml2.0_use_html");
		delete_option("_tml2.0_nl2br");
		delete_option("_tml2.0_message_body");
		delete_option("_tml2.0_send_after");
		delete_option("_tml2.0_send_after_plus_minus");
		delete_option("_tml2.0_restrict_by_tags_type");
		delete_option("_tml2.0_restrict_by_tags_slugs");
		delete_option("_tml2.0_restrict_by_cats_type");
		delete_option("_tml2.0_restrict_by_cats_slugs");
		delete_option("_tml2.0_syntax_highlighting");
		delete_option("_tml2.0_promote");
		delete_option("_tml2.0_uniq_id_cur");
	}
	
	/**
	 * Remove 2.x tables that are not needed in 3.x
	 */
	private function _remove_tables() {
		global $wpdb;
		
		// remove 2.0.0.2 tables 
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}tml_emails`");
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}tml_queue`");
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}tml_history`");
		
		// remove 2.1 tables
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}tml_log`");
	}
	
	/**
	 * 
	 * @param type $text
	 */
	private function _replace_message_tags($text) {
		// if message has PHP code, place into a [snip] shortcode, so it won't
		// be sent to the user -- PHP code is no longer evaluated in 3.0, so
		// the blogger must rewrite in terms of shortcodes.
		$text = preg_replace('/<\?php((.|\n|\r)*?)\?>/i', '[snip]$0[/snip]', $text);
		
		$replace = array(
			'<$ID>', '<$POST_ID>', '<$AUTHOR>', '<$AUTHOR_EMAIL>', '<$AUTHOR_URL>', 
			'<$AUTHOR_IP>', '<$DATE>', '<$DATE_GMT>', '<$CONTENT>', '<$AGENT>', 
			'<$PARENT>', '<$USER_ID>'
		);
		$with = array(
			'[id]', '[post_id]', '[name]', '[email]', '[author_url]',
			'[ip]', '[date]', '[date gmt=1]', '[comment]', '[user_agent]',
			'', ''
		);
		
		if (function_exists("str_ireplace")) {
			$text = str_ireplace($replace, $with, $text);
		} else {
			$text = str_replace($replace, $with, $text);
		}
				
		return $text;
	}
	
	/**
	 * 
	 */
	private function _create_tables() {
		global $wpdb;
		
		// get the character set to use...
		$charset_sql = "";
		
		if (!empty($wpdb->charset)) {
			$charset_sql .= " CHARACTER SET {$wpdb->charset}";
		}
		
		if (!empty($wpdb->collate)) {
			$charset_sql .= " COLLATE {$wpdb->collate}";
		}
		
		// create messages table
		$wpdb->query("
			CREATE TABLE `{$wpdb->prefix}bbpp_thankmelater_messages` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`from_name` VARCHAR(255) NOT NULL,
				`from_email` VARCHAR(100) NOT NULL,
				`subject` VARCHAR(255) NOT NULL,
				`message` LONGTEXT NOT NULL,
				`min_delay` DOUBLE UNSIGNED NOT NULL,
				`min_delay_unit` ENUM('minutes','hours','days','weeks') NOT NULL,
				`target_tags` LONGTEXT NOT NULL,
				`target_categories` LONGTEXT NOT NULL,
				`target_posts` LONGTEXT NOT NULL,
				`max_sends_per_email` INT UNSIGNED NOT NULL,
				PRIMARY KEY (`id`)
			) $charset_sql
		");
			
		// create schedule table
		$wpdb->query("
			CREATE TABLE `{$wpdb->prefix}bbpp_thankmelater_schedules` (
				`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`message_id` INT UNSIGNED NOT NULL,
				`comment_id` INT UNSIGNED NOT NULL,
				`send_date_gmt` DATETIME NOT NULL,
				`sent` TINYINT(1) NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`),
				INDEX `sent_send_date_gmt` (`sent`, `send_date_gmt`)
			) $charset_sql
		");
	}
	
	/**
	 * 
	 */
	public function down() {
		
	}

	/**
	 * Uninstall Thank Me Later 3
	 * *AND* every version previous (since we didn't do that in previous
	 * versions!)
	 */
	public function down_to_0() {
		global $wpdb;
		
		// remove 1.3.1 options
		delete_option("_tml_options");
		delete_option("_tml_nextUpdate");
		delete_option("_tml_installed");
		delete_option("_tml_promote");
		
		// remove 1.3.1 tables
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}thankmelater`");
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}thankmelater_sent`");
		
		// remove 1.3.1 crons
		wp_clear_scheduled_hook("_TML_processQueuehook");
		
		// remove 1.5.3.1 crons
		wp_clear_scheduled_hook("_tml_pQSched");
		
		// remove 2.0.0.2 options
		$this->_remove_options();
		
		// remove 2.x tables
		$this->_remove_tables();
		
		// remove 2.1 crons
		$this->_remove_crons();
		
		// remove 3.0 tables
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}bbpp_thankmelater_messages`");
		$wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}bbpp_thankmelater_schedules`");
		
		// remove 3.0 options
		delete_option("bbpp_thankmelater_version");
		delete_option("bbpp_thankmelater_last_legacy_tick");
		delete_option("bbpp_thankmelater_last_control_tick");
		delete_option("bbpp_thankmelater_preview_post_id");
		delete_option("bbpp_thankmelater_preview_comment_id");
		
		// remove 3.0 crons
		wp_clear_scheduled_hook("bbpp_thankmelater_tick");
		wp_clear_scheduled_hook("bbpp_thankmelater_control_tick");
	}
}