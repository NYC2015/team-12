<?php

/**
 * Handles the display of the 'statistics' admin screen
 */
class Bbpp_ThankMeLater_AdminScreenStatistics {
	/**
	 * 
	 */
	public function route() {
		$action = "index";
		
		if (!empty($_REQUEST["action"])) {
			$action = stripslashes($_REQUEST["action"]);
		}
		
		switch ($action) {
			case "index":
			default:
				$this->index();
				break;
		}
	}
	/**
	 * 
	 */
	public function index() {
		global $wpdb;
		
		$schedule = new Bbpp_ThankMeLater_Schedule();
		
		// get the total number of messages which are sent
		$total_num_sent = $schedule->findNum(1);
		$total_num_opened = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}bbpp_thankmelater_opens`");
		$total_num_scheduled = $schedule->findNum(0);
		
		$day_stats = array(
			"data" => array(), 
			"labels" => array()
		);
		$ind = 0;
		$day_start = mktime(0, 0, 0, date("m"), date("d")-30, date("Y"));
		$day_start_last = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		
		while ($day_start <= $day_start_last) {
			$day_start_gmt = gmdate("Y-m-d H:i:s", $day_start);
			$day_end_gmt = gmdate("Y-m-d H:i:s", $day_start + 86400 - 1);
			
			$day_stats["data"][] = array($ind, $schedule->findSentBetween($day_start_gmt, $day_end_gmt));
			$day_stats["labels"][] = array($ind, date_i18n("d", $day_start, false));
			
			$day_start += 86400;
			$ind++;
		}
		
		$open_stats = array(
			"data" => array(), 
			"labels" => array()
		);
		$ind = 0;
		$day_start = mktime(0, 0, 0, date("m"), date("d")-30, date("Y"));
		$day_start_last = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		
		while ($day_start <= $day_start_last) {
			$day_start_gmt = gmdate("Y-m-d H:i:s", $day_start);
			$day_end_gmt = gmdate("Y-m-d H:i:s", $day_start + 86400 - 1);
			$open_stats["data"][] = array($ind, (int)$wpdb->get_var($wpdb->prepare("
				SELECT COUNT(*) FROM `{$wpdb->prefix}bbpp_thankmelater_opens`
				WHERE `date_gmt` >= %s
				AND `date_gmt` <= %s
			", $day_start_gmt, $day_end_gmt)));
			$open_stats["labels"][] = array($ind, date_i18n("d", $day_start, false));
			
			$day_start += 86400;
			$ind++;
		}
		
		
		
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/statistics/index.php";
	}
}