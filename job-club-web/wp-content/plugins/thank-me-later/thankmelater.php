<?php
/*
Plugin Name: Thank Me Later
Plugin URI: http://www.brendonboshell.co.uk/thank-me-later-wordpress-plugin/
Description: Send a 'thank you' email to your blog's commenters.
Author: Brendon Boshell
Version: 3.3.2
Author URI: http://www.brendonboshell.co.uk/
Text Domain: bbpp-thankmelater
Domain Path: /languages/
*/

/*  Copyright 2013 Brendon Boshell

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// when incrementing version: 
// (1) plugin header 
// (2) $version in class below (IF database or upgrade needed ONLY... otherwise keep at old version)
// (3) add Upgrade[x].php with up() { update_option("bbpp_thankmelater_version", [ver]) }
// (4) add entry to $upgraders in Upgrader.php
// (5) generate POT

define("BBPP_THANKMELATER_PLUGIN_PATH", plugin_dir_path(__FILE__));

require_once BBPP_THANKMELATER_PLUGIN_PATH . "Message.php";
require_once BBPP_THANKMELATER_PLUGIN_PATH . "Schedule.php";
require_once BBPP_THANKMELATER_PLUGIN_PATH . "ErrorHelper.php";

/**
 * 
 */
class Bbpp_ThankMeLater_Plugin {
	/**
	 * Version of plugin that is running
	 */
	public $version = "3.3";
	
	/**
	 * path to the plugin, relative to wp-content/plugins/, with trailing slash
	 * 
	 * @var string 
	 */
	private $plugin_rel_path = NULL;
	
	/**
	 * path to the languages directory, relative to $plugins_rel_path, with trailing slash
	 * 
	 * @var string
	 */
	private $languages_rel_path = "languages/";
	
	/**
	 * Initialise the plugin
	 */
    	public function __construct() {
		$this->plugin_rel_path = dirname(plugin_basename(__FILE__)) . "/";
	
		add_action("init", array($this, "init"));
		add_action("admin_menu", array($this, "admin_menu"));
		add_action("admin_init", array($this, "admin_init"));
		add_action("wp_insert_comment", array($this, "wp_insert_comment"), 10, 1);
		add_action("transition_comment_status", array($this, "transition_comment_status"), 10, 3);
		
		// the following hooks are "redundant", but I have reports that wp_insert_comment and
		// transition_comment_status do not work on some installations. We use the following
		// hooks to be on the safe side, although the above are needed for Disqus support
		add_action("comment_post", array($this, "comment_post"), 10, 2); 
		add_action("wp_set_comment_status", array($this, "comment_post"), 10, 2); 
		add_action("deleted_comment", array($this, "comment_post"), 10, 1); 
		
		// handle cron ticks
		add_action("bbpp_thankmelater_tick", array($this, "tick"), 10, 2);
		add_action("bbpp_thankmelater_control_tick", array($this, "control_tick"));
		
		// handle activation/de-activation of plugin
		register_activation_hook(__FILE__, array($this, "activate"));
		register_deactivation_hook(__FILE__, array($this, "deactivate"));
	}
    
	/**
	 * Hook for `init` action.
	 */
	public function init() {
		load_plugin_textdomain("bbpp-thankmelater", FALSE, $this->plugin_rel_path . $this->languages_rel_path);
                
		// do we need to upgrade?
		if (get_option("bbpp_thankmelater_version") != $this->version) {
			if ($this->upgrade() != $this->version) {			
				// error in upgrade, don't start sending e-mails...
				return;
			}
                        
			// activate the plugin.
			$this->activate();
		}
		
		// if WP-Cron not working, send e-mails on HTTP requests
		if ($this->is_legacy_mode() && !defined("DOING_CRON")) {
			$last_tick_time = get_option("bbpp_thankmelater_last_legacy_tick");
			
			// haven't run a legacy tick in 15 minutes: do it now.
			if ($last_tick_time < time() - 60*15) {
				$this->legacy_tick();
			}
		}
		
		// add custom status to create posts fo previewing:
		// note that name can be max. 20 characters long.
		register_post_status("bbpp-thankmelater-pv", array(
			"public" => false,
			"exclude_from_search" => true,
			"show_in_admin_all_list" => false,
			"show_in_admin_status_list" => false
		));
		
		// handle opt out link
		if (isset($_GET["bbpp-thankmelater-unsubscribe"])) {
			$this->opt_out();
		}
                
		// handle open tracking
		if (isset($_GET["bbpp-thankmelater-open"])) {
			$this->open();
		}
	}
	
	/**
	 * Hook for plugin activation. Shedules any regular WP-Cron calls.
	 */
	public function activate() {
		// ensure that we call the control_tick function every hour to ensure
		// that WP-Cron is working
		wp_clear_scheduled_hook("bbpp_thankmelater_control_tick");
		wp_schedule_event(time() + 60, "hourly", "bbpp_thankmelater_control_tick");
	}
	
	/**
	 * Hook for plugin deactivation. Remove any WP-Cron calls.
	 */
	public function deactivate() {
		wp_clear_scheduled_hook("bbpp_thankmelater_control_tick");
	}
	
	/**
	 * If WP-Cron not working, this is called every 15 minutes to process the
	 * scheduled sends.
	 */
	public function legacy_tick() {
		// get the schedules which are due to be processed, and process them
		update_option("bbpp_thankmelater_last_legacy_tick", time());
		
		$schedule = new Bbpp_ThankMeLater_Schedule();
		$schedule->readDue();
		$schedule->process();
	}
	
	/**
	 * WP-Cron hook to send a particuar e-mail to a comment.
	 */
	public function tick($schedule_id, $time) {
		$schedule = new Bbpp_ThankMeLater_Schedule($schedule_id);
		
		if (!$schedule->getSent()) {
			$schedule->process();
		}
	}
	
	/**
	 * Called every hour to make sure that WP-Cron is working.
	 */
	public function control_tick() {
		update_option("bbpp_thankmelater_last_control_tick", time());
		
		// process due e-mails now (this is so that e-mails which failed
		// can be tried again).
		$schedule = new Bbpp_ThankMeLater_Schedule();
		$schedule->readDue();
		$schedule->process();
	}
	
	/**
	 * returns TRUE if WP-Cron is *not* working as expected.
	 * 
	 * @return boolean
	 */
	private function is_legacy_mode() {
		$last_control_tick = get_option("bbpp_thankmelater_last_control_tick");
		
		// WP-Cron has never run successfully
		if (!$last_control_tick) {
			return TRUE;
		}
		
		// WP-Cron has run successfully in the past, but hasn't run okay
		// for the past 12 hours
		if ($last_control_tick < time() - 3600*12) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Hook called after comment is posted.
	 */
	public function comment_post($id, $status = NULL) {
		global $wpdb;
		
		$comment = get_comment($id);
		$status = $status ? (string)$status : $comment->comment_approved;
		
		$is_live = TRUE;
		
		// comment deleted?
		if (!$comment) {
			$is_live = FALSE;
		} else {
			// don't process pingbacks and other non-comments...
			if ($comment->comment_type !== "") {
				$is_live = FALSE;
			}

			// make sure comment is approved (i.e. not deleted & not spam):
			//if ($comment->comment_approved !== "1") {
			//	$is_live = FALSE;
			//}
			if ($status !== "approve" && $status !== "1") {
				$is_live = FALSE;
			}
			
			$post = get_post($comment->comment_post_ID);
			if (!$post) {
				// post deleted
				$is_live = FALSE;
			} else {
				if ($post->post_status == "bbpp-thankmelater-pv") {
					// not a real post -- used for previews
					$is_live = FALSE;
				}
			}
			
			// check whether user has opted out of emails
			$opt_out_row = $wpdb->get_row($wpdb->prepare("
				SELECT `email` FROM `{$wpdb->prefix}bbpp_thankmelater_opt_outs`
				WHERE `email` = %s
			", $comment->comment_author_email));
				
			if ($opt_out_row !== NULL) {
				$is_live = FALSE;
			}
		}
		
		if ($is_live) {		
			// schedule the messages to be sent for this comment.
			$message = new Bbpp_ThankMeLater_Message();
			$message->scheduleMessages($comment);
		} else {
			// remove any scheduled mails, this comment has been spammed or trashed
			$schedule = new Bbpp_ThankMeLater_Schedule();
			$schedule->readUnsent($id);
			$schedule->delete();
		}
	}
	
	/**
	 * Deal with a change in comment status
	 */
	public function transition_comment_status($status, $null, $comment) {
		if ($status == "approved") {
			$status = "1";
		}
		$this->comment_post($comment->comment_ID, $status);
	}
	
	/**
	 * Deal with insertion of comment
	 * 
	 * (Hooked to wp_insert_comment. The previous comment_post hook did not
	 * work with Disqus).
	 */
	public function wp_insert_comment($id, $comment = NULL) {
		$this->comment_post($id);
	}
        
	public function install_notice() {
		echo "<div class='updated fade'><p><strong>" 
			. __("Thank Me Later Installation.", "bbpp-thankmelater")
			. "</strong> "
			. sprintf(__("Your installation of Thank Me Later is not complete. %sComplete Install%s", "bbpp-thankmelater"), "<a href=\"admin.php?page=bbpp_thankmelater_install\">", "</a>")
			."</p></div>";
	}
	
	/**
	 * 
	 */
	public function admin_init() {
		wp_register_style("bbpp_thankmelater_admin_styles", plugins_url("admin/styles.css?" . $this->version, __FILE__));
		wp_register_script("bbpp_thankmelater_admin_scripts", plugins_url("admin/scripts.js", __FILE__));
		wp_register_script("bbpp_thankmelater_flot", plugins_url("admin/jquery.flot.js", __FILE__));
		wp_register_script("bbpp_thankmelater_statistics", plugins_url("admin/statistics.js", __FILE__));
		add_action("wp_ajax_bbpp_thankmelater_message_preview", array($this, "admin_message_preview"));
		add_action("wp_ajax_bbpp_thankmelater_message_targeting", array($this, "admin_message_targeting"));
                
		if (get_option("bbpp_thankmelater_show_install_screen")) {
			add_action("admin_notices", array($this, "install_notice"));
		}
	}
	
	/**
	 * Hook for `admin_menu` action.
	 */
	public function admin_menu() {
		add_menu_page(
			__("Thank Me Later", "bbpp-thankmelater"), 
			__("Thank Me Later", "bbpp-thankmelater"), 
			"moderate_comments",
			"bbpp_thankmelater",
			array($this, "admin_screen_message"),
			""
		);
		$page_message = add_submenu_page(
			"bbpp_thankmelater",
			_x("Messages", "noun", "bbpp-thankmelater"),
			_x("Messages", "noun", "bbpp-thankmelater"),
			"moderate_comments",
			"bbpp_thankmelater",
			array($this, "admin_screen_message")
		);
		$page_statistics = add_submenu_page(
			"bbpp_thankmelater",
			__("Stats", "bbpp-thankmelater"),
			__("Stats", "bbpp-thankmelater"),
			"moderate_comments",
			"bbpp_thankmelater_statistics",
			array($this, "admin_screen_statistics")
		);
		$page_optout = add_submenu_page(
			"bbpp_thankmelater",
			__("Opt out", "bbpp-thankmelater"),
			__("Opt out", "bbpp-thankmelater"),
			"moderate_comments",
			"bbpp_thankmelater_opt_out",
			array($this, "admin_screen_opt_out")
		);
		
		if (get_option("bbpp_thankmelater_show_install_screen") || $_REQUEST["page"] == "bbpp_thankmelater_install") {
			$page_install = add_submenu_page(
				"bbpp_thankmelater",
				__("Install", "bbpp-thankmelater"),
				__("Install", "bbpp-thankmelater"),
				"moderate_comments",
				"bbpp_thankmelater_install",
				array($this, "admin_screen_install")
			);
			add_action("admin_print_styles-{$page_install}", array($this, "admin_print_styles"));
		}
		
		add_action("admin_print_styles-{$page_statistics}", array($this, "admin_print_styles"));
		add_action("admin_print_styles-{$page_message}", array($this, "admin_print_styles"));
		add_action("admin_enqueue_scripts", array($this, "admin_enqueue_scripts"));
	}
	
	/**
	 * 
	 */
	public function admin_print_styles() {
		wp_enqueue_style("bbpp_thankmelater_admin_styles");
	}
	
	/**
	 * 
	 */
	public function admin_enqueue_scripts($hook) {
		if (!isset($_GET["page"]) || !in_array($_GET["page"], array(
			"bbpp_thankmelater", 
			"bbpp_thankmelater_statistics"
		))) {
			return;
		}
		
		// add scripts to our admin pages
		wp_enqueue_script("bbpp_thankmelater_admin_scripts");
		wp_localize_script("bbpp_thankmelater_admin_scripts", "bbpp_thankmelater_objectL10n", array(
			"from" => _x("From", "'From' header in e-mail client, preceding from name and e-mail address", "bbpp-thankmelater"),
			"subject" => _x("Subject", "noun", "bbpp-thankmelater")
		));
		wp_enqueue_script("bbpp_thankmelater_flot");
		wp_enqueue_script("bbpp_thankmelater_statistics");
	}
	
	/**
	 * Hook for ajax call to show message preview.
	 */
	public function admin_message_preview() {
		$_GET["action"] = "preview";
		$this->admin_screen_message();
	}
	
	/**
	 * Hook for ajax call to get targeting summary.
	 */
	public function admin_message_targeting() {
		$_GET["action"] = "targeting";
		$this->admin_screen_message();
	}
	
	/**
	 * Show the 'statistics' screen
	 */
	public function admin_screen_statistics() {
		if (!class_exists("Bbpp_ThankMeLater_AdminScreenStatistics")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/AdminScreenStatistics.php";
		}
		$admin_screen_statistics = new Bbpp_ThankMeLater_AdminScreenStatistics();
		$admin_screen_statistics->route();
	}
	
	/**
	 * Show the 'messages' screen
	 */
	public function admin_screen_message() {
		if (!class_exists("Bbpp_ThankMeLater_AdminScreenMessage")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/AdminScreenMessage.php";
		}
		$admin_screen_message = new Bbpp_ThankMeLater_AdminScreenMessage();
		$admin_screen_message->route();
	}
	
	/**
	 * Show the 'opt out' settings screen
	 */
	public function admin_screen_opt_out() {
		if (!class_exists("Bbpp_ThankMeLater_AdminScreenOptOut")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/AdminScreenOptOut.php";
		}
		$admin_screen_opt_out = new Bbpp_ThankMeLater_AdminScreenOptOut();
		$admin_screen_opt_out->route();
	}
        
        /**
         * 
         */
	public function admin_screen_install() {
		if (!class_exists("Bbpp_ThankMeLater_AdminScreenInstall")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/AdminScreenInstall.php";
		}
		$admin_screen_install = new Bbpp_ThankMeLater_AdminScreenInstall();
		$admin_screen_install->route();
	}
        
        /**
         * 
         * @param type $code
         */
        private function _parse_code($code_base64) {
            $code = @base64_decode($code_base64);
		
            if (!$code) {
                    return;
            }

            @list($comment_id_str, $email) = explode("-", $code, 2);
            $comment_id = (int)$comment_id_str;

            if (!$comment_id) {
                    return;
            }

            $comment = get_comment($comment_id);

            if ($comment->comment_author_email != $email) {
                    exit;
            }
            
            return array($comment_id, $email);
        }
	
	/**
	 * 
	 */
	public function opt_out() {
		global $wpdb;
		
		$code_base64 = isset($_GET["bbpp-thankmelater-unsubscribe"]) ? $_GET["bbpp-thankmelater-unsubscribe"] : "";
                list($comment_id, $email) = $this->_parse_code($code_base64);
		
		$date_gmt = gmdate("Y-m-d H:i:s");
		
		// unsubscribe user:
		$wpdb->query($wpdb->prepare("
			INSERT IGNORE INTO `{$wpdb->prefix}bbpp_thankmelater_opt_outs`
			(`email`, `date_gmt`)
			VALUES
			(%s, %s)
		", $email, $date_gmt));
			
		// show success message
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "/opt_out_success.php";
		exit;
	}
        
        /**
	 * 
	 */
	public function open() {
		global $wpdb;
		
		$code_base64 = isset($_GET["bbpp-thankmelater-open"]) ? $_GET["bbpp-thankmelater-open"] : "";
		list($comment_id, $email) = $this->_parse_code($code_base64);
		$date_gmt = gmdate("Y-m-d H:i:s");
		
		// unsubscribe user:
		$wpdb->query($wpdb->prepare("
			INSERT IGNORE INTO `{$wpdb->prefix}bbpp_thankmelater_opens`
			(`comment_id`, `date_gmt`)
			VALUES
			(%d, %s)
		", $comment_id, $date_gmt));
			
		
			
		header("Content-Type: image/png");
		echo file_get_contents(BBPP_THANKMELATER_PLUGIN_PATH . "/imgs/pixel.gif");
		exit;
	}
	
	/**
	 * Upgrade plugin
	 */
	public function upgrade() {
		if (!class_exists("Bbpp_ThankMeLater_Upgrader")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "upgrade/Upgrader.php";
		}
		$upgrader = new Bbpp_ThankMeLater_Upgrader();
		$upgrader->upgrade($this->version);
	}
}

new Bbpp_ThankMeLater_Plugin();