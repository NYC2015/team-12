<?php

/**
 * Schedule
 * 
 * Represents a collection of schedules -- events where a "Thank You" message
 * is sent to a particular comment.
 * 
 */
class Bbpp_ThankMeLater_Schedule {
	/**
	 * Collection of schedules' data
	 *
	 * @var array 
	 */
	private $data = array();
	
	/**
	 * The index of $data for which we are modifying.
	 *
	 * @var integer 
	 */
	private $pointer = 0;
	
	/**
	 * 
	 * @param integer $id id of the message to load, if any
	 */
	public function __construct($id = NULL) {
		if ($id !== NULL) {
			$this->readSchedule($id);
		}
	}
	
	/**
	 * Add a schedule event to this object.
	 * 
	 * @param array $data array of data with keys "message_id", "comment_id", etc.
	 */
	public function addSchedule($data) {
		$this->data[] = $data;
	}
	
	/**
	 * Get all of the schedule events' data
	 * 
	 * @return array
	 */
	public function getSchedules() {
		return $this->data;
	}
	
	/**
	 * Select a particular schedule event
	 * 
	 * @param integer $pointer Index of the schedule event to select
	 * @return boolean True if selected, false otherwise.
	 */
	public function selectSchedule($pointer) {
		if (isset($this->data[$pointer])) {
			$this->pointer = $pointer;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Overwrite/create the (currently selected) schedule event data fields to the
	 * values in $data.
	 * 
	 * @param array $data array of all or subset of fields to update
	 */
	public function setSchedule($data) {
		$this->data[$this->pointer] = array_merge($this->data[$this->pointer], $data);
	}
	
	/**
	 * Get the ID of the current schedule event.
	 * 
	 * @return string|null the id of the selected schedule event, null if not available
	 */
	public function getId() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["id"])) {
			return $this->data[$this->pointer]["id"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the ID of the message for the selected schedule event.
	 * 
	 * @return string|null the id of the selected schedule event's message, null if not available
	 */
	public function getMessageId() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["message_id"])) {
			return $this->data[$this->pointer]["message_id"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the ID of the comment for the selected schedule event
	 * 
	 * @return string|null id of comment, null if not available.
	 */
	public function getCommentId() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["comment_id"])) {
			return $this->data[$this->pointer]["comment_id"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Alias of getSendTime()
	 * 
	 * @return integer|null
	 * @see $this::getSendTime
	 */
	public function getSendDateGmt() {
		return $this->getSendTime();
	}
	
	/**
	 * Get the send time as seconds from GMT/UTC
	 * 
	 * @return integer|null The seconds from GMT/UTC which schedule event takes place, null if not available
	 */
	public function getSendTime() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["send_date_gmt"])) {
			return (int)$this->data[$this->pointer]["send_date_gmt"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get whether the message was sent
	 * 
	 * @return boolean|null true if message is sent, false if not, null if not available.
	 */
	public function getSent() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["sent"])) {
			return $this->data[$this->pointer]["sent"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the number of schedule events for a particular message and email address
	 * 
	 * @global type $wpdb
	 * @param integer $message_id id of the message
	 * @param string $email commenter's email address
	 * @return integer Number of schedule events (both sent and unsent).
	 */
	public function getNumSchedules($message_id, $email) {		
		global $wpdb;
		
		$sql = $wpdb->prepare("
			SELECT COUNT(*) FROM `{$wpdb->comments}`
			JOIN `{$wpdb->prefix}bbpp_thankmelater_schedules`
			ON `{$wpdb->comments}`.`comment_ID` = `{$wpdb->prefix}bbpp_thankmelater_schedules`.`comment_id`
			WHERE `{$wpdb->prefix}bbpp_thankmelater_schedules`.`message_id` = %d
			AND `{$wpdb->comments}`.`comment_author_email` = %s
		", $message_id, $email);
			
		return (int)$wpdb->get_var($sql);
	}
	
	/**
	 * Find the schedule event ID for a particular message and comment
	 * 
	 * @global type $wpdb
	 * @param integer $message_id id of message
	 * @param integer $comment_id id of comment
	 * @return integer|boolean ID of event or false if no event exists.
	 */
	public function findScheduleId($message_id, $comment_id) {
		global $wpdb;
		
		$id = $wpdb->get_var($wpdb->prepare("
			SELECT id FROM `{$wpdb->prefix}bbpp_thankmelater_schedules`
			WHERE message_id = %d
			AND comment_id = %s
		", $message_id, $comment_id));
			
		return $id ? $id : false;
	}
	
	/**
	 * Find the total number of schedule events which are sent or unsent for all time.
	 * 
	 * @global type $wpdb
	 * @param integer $sent "1" for events which are sent, "0" for events which are unsent. 
	 * @return integer Number of such events.
	 */
	public function findNum($sent = NULL) {
		global $wpdb;
		
		$where = "1=1";
		if ($sent !== NULL) {
			$where .= " AND " . $wpdb->prepare("`sent` = %d", $sent);
		}
		
		return (int)$wpdb->get_var("
			SELECT COUNT(*) FROM `{$wpdb->prefix}bbpp_thankmelater_schedules`
			WHERE {$where}
		");
	}
	
	/**
	 * Find the number of sent schedule events between two times
	 * 
	 * @global type $wpdb
	 * @param string $date_from_gmt GMT date in form Y-m-d H:i:s
	 * @param string $date_to_gmt  GMT date in form Y-m-d H:i:s
	 * @return integer Number of sent schedules between the two times, inclusive.
	 */
	public function findSentBetween($date_from_gmt, $date_to_gmt) {
		global $wpdb;
		
		$where = "1=1";
		$where .= " AND " . $wpdb->prepare("`send_date_gmt` >= %s", $date_from_gmt);
		$where .= " AND " . $wpdb->prepare("`send_date_gmt` <= %s", $date_to_gmt);
		$where .= " AND `sent` = 1";
		
		return intval($wpdb->get_var("
			SELECT COUNT(*) FROM `{$wpdb->prefix}bbpp_thankmelater_schedules`
			WHERE {$where}
		"));
	}
	
	/**
	 * Process the loaded schedule events. i.e. sent the emails to the commenters.
	 */
	public function process() {
		// mark the processing messages as sent *before* we try to send them.
		// this helps prevent race condition, since time is not tied up in trying
		// to send e-mail before marking it as sent
		foreach ($this->data as $pid => $data) {
			$this->selectSchedule($pid);
			$this->setSchedule(array(
				"sent" => 1
			));
		}
		$this->save();
		
		// go through each schedule event...
		foreach ($this->data as $pid => $data) {
			$this->selectSchedule($pid);
			$message_id = $data["message_id"];
			$comment_id = $data["comment_id"];
			$comment = get_comment($comment_id);			
			$message = new Bbpp_ThankMeLater_Message($message_id);
			
			// try to send email
			if ($comment && $message->send($comment)) {
			} else {
				// email counldn't be sent... mark as such...
				$this->setSchedule(array(
					"sent" => 0
				));
				
				// we've tried for at least 3 hours, this mail is not going to
				// be sent, get rid of it.
				// (otherwise, we'll keep trying at the regular calls to process --
				// hourly if WP-Cron works, every 15 minutes in legacy mode).
				if ($this->getSendTime() < time() - 3600*3) {
					$sched = new Bbpp_ThankMeLater_Schedule($data["id"]);
					$sched->delete();
				}
			}
		}
		
		$this->save();
	}
	
	/**
	 * Read a particular schedule event (clearing all others).
	 * 
	 * @global type $wpdb
	 * @param integer $id ID of the schedule event to load
	 * @return array Data of all loaded schedule events
	 */
	public function readSchedule($id) {
		global $wpdb;
		
		$where = $wpdb->prepare("`id` = %d", $id);
		return $this->_readSchedules(NULL, NULL, $where);
	}
	
	/**
	 * Read all unsent events for a particular comment
	 * 
	 * @global type $wpdb
	 * @param integer $comment_id ID of comment
	 * @return array Data of all unsent schedules for this comment.
	 */
	public function readUnsent($comment_id) {
		global $wpdb;
		
		$where = $wpdb->prepare("`comment_id` = %d AND `sent` = 0", $comment_id);
		return $this->_readSchedules(NULL, NULL, $where);
	}
	
	/**
	 * Read all the schedules which are now due (i.e. the send time has passed
	 * the current time, and message is still marked as unsent).
	 * 
	 * @global type $wpdb
	 * @return array Data of all loaded schedules
	 */
	public function readDue() {
		global $wpdb;
		
		$now_date_gmt = gmdate("Y-m-d H:i:s");		
		$where = $wpdb->prepare("`sent` = 0 AND `send_date_gmt` <= %s", $now_date_gmt);
		
		return $this->_readSchedules(NULL, NULL, $where);
	}
	
	/**
	 * Read schedules
	 * 
	 * @param integer|null $limit number of schedule events to read
	 * @param integer|null $offset Schedule event number to start reading from
	 * @param string|null $orderby Set to "send_date_gmt" or null to order results.
	 * @param type|null $order The order direction: "asc" or "desc"
	 * @return array Data of all loaded schedules
	 */
	public function readSchedules($limit = NULL, $offset = NULL, $orderby = NULL, $order = NULL) {			
		return $this->_readSchedules($limit, $offset, "", $orderby, $order);
	}
	
	/**
	 * Delete all unsent schedules for a particular message id (we do this when
	 * we delete, deactivate, etc a message).
	 * 
	 * @global type $wpdb
	 * @param integer $id id of the message to delete unsent schedules for.
	 * @return integer|false the number of rows deleted or false if error
	 */
	public function deleteUnsentByMessageId($id) {
		global $wpdb;
		
		return $wpdb->query($wpdb->prepare("
			DELETE FROM `{$wpdb->prefix}bbpp_thankmelater_schedules`
			WHERE `message_id` = %d
			AND `sent` = 0
		", $id));
	}
	
	/**
	 * Convert database fields into representation for this object.
	 * 
	 * @param array $data array of data from database
	 * @return array Data for an individual schedule event
	 */
	private function _translateRow($data) {
		return array(
			"id" => $data["id"],
			"message_id" => $data["message_id"], 
			"comment_id" => $data["comment_id"],
			"send_date_gmt" => strtotime($data["send_date_gmt"] . " GMT"),
			"sent" => (bool)$data["sent"]
		);
	}
	
	/**
	 * Add a row of database data to our object
	 * 
	 * @param array $data Array of database fields indexed by field name
	 * @return type
	 */
	private function _readRow($data) {
		$this->addSchedule($this->_translateRow($data));
	}
	
	/**
	 * Read the results from the database and return the records
	 * 
	 * @global type $wpdb
	 * @param integer|null $limit
	 * @param integer|null $offset
	 * @param string $where
	 * @param integer|null $orderby
	 * @param integer|null $order
	 * @return array Data for schedule events.
	 */
	private function _readSchedules($limit = NULL, $offset = NULL, $where = "", $orderby = NULL, $order = NULL) {
		global $wpdb;
		
		// clear existing results
		$this->data = array();
		
		// construct order sql
		$order_sql = "";
		if (!empty($orderby) && in_array($orderby, array("send_date_gmt"))) {
			$order_sql .= " ORDER BY `{$orderby}`";
			if ($order == "desc") {
				$order_sql .= " DESC";
			} else {
				$order_sql .= " ASC";
			}
		}
		
		// apply limits
		$limit_sql = "";		
		if ($limit !== NULL) {
			$limit_sql .= " LIMIT " . intval($limit);
			if ($offset !== NULL) {
				$limit_sql .= " OFFSET " . intval($offset);
			}
		}
		
		// construct where sql
		$where_sql = "";
		if (!empty($where)) {
			$where_sql .= " WHERE " . $where;
		}
		
		// construct query
		$query = "
			SELECT *
			FROM `{$wpdb->prefix}bbpp_thankmelater_schedules`
			{$where_sql}
			{$order_sql}
			{$limit_sql}
		";
			
		// get results
		$results = $wpdb->get_results($query, "ARRAY_A");
		
		// add results to object
		foreach ($results as $data) {
			$this->_readRow($data);
		}
		
		// return the results
		return $this->getSchedules();
	}

	/**
	 * Makes sure the data added to the collection is valid. If not, returns
	 * an array of WP_Error's for each data record.
	 * 
	 * @return array array of errors
	 */
	public function validate() {
		$errors = array();
		
		foreach ($this->data as $rid => $data) {
			$row_errors = $this->validateRow($data);
			
			if ($row_errors->get_error_codes()) {
				$errors[$rid] = $row_errors;
			}
		}
		
		return $errors;
	}
	
	/**
	 * Validate the row of data.
	 * 
	 * @param type $data
	 * @return \WP_Error
	 */
	protected function validateRow($data) {
		$errors = new WP_Error();
		
		/* TODO: This function currently performs no validation! */

		return $errors;
	}
	
	/**
	 * Save this object to the database
	 * 
	 * @global type $wpdb
	 * @return array Array of \WP_Error, if any.
	 */
	public function save() {
		global $wpdb;
		
		$errors = $this->validate();
		
		if ($errors) {
			return $errors;
		}
		
		// insert the rows into table:
		foreach ($this->data as $pid => $data) {
			$wdata = array(
				"message_id" => $data["message_id"], 
				"comment_id" => $data["comment_id"],
				"send_date_gmt" => gmdate("Y-m-d H:i:s", $data["send_date_gmt"]),
				"sent" => (bool)$data["sent"]
			);
			
			if (!empty($data["id"])) {
				$wpdb->update(
					$wpdb->prefix . "bbpp_thankmelater_schedules",
					$wdata,
					array(
						"id" => $data["id"]
					)
				);
				$id = $data["id"];
			} else {
				$wpdb->insert(
					$wpdb->prefix . "bbpp_thankmelater_schedules", 
					$wdata
				);
				$id = $wpdb->insert_id;
				$this->data[$pid]["id"] = $id;
			}
			
			if (!$wdata["sent"]) {
				// schedule wpcron for this event
				wp_schedule_single_event(
					$data["send_date_gmt"], //=time()+$message_delay
					"bbpp_thankmelater_tick",
					array($id, $data["send_date_gmt"])
				);
			}
		}
		
		return $errors;
	}
	
	/**
	 * Delete the schedule events
	 * 
	 * @global type $wpdb
	 */
	public function delete() {
		global $wpdb;
		
		foreach ($this->data as $data) {
			$wpdb->query($wpdb->prepare("
				DELETE FROM `{$wpdb->prefix}bbpp_thankmelater_schedules`
				WHERE `id` = %d
			", $data["id"]));
		}
	}
}