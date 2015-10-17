<?php

/**
 * Message
 * 
 * A message is an e-mail (i.e. from name, from email, subject line & message)
 * that is sent to a commenter, along with all of the targeting and scheduling
 * options (i.e. the message delay, target tags/categories/posts and max sends
 * per e-mail).
 */
class Bbpp_ThankMeLater_Message {
	/**
	 * Collection of messages data that have been read into this object.
	 * 
	 * @var array 
	 */
	private $data = array();
	
	/**
	 * What message we are get/set-ting individual values for. Index of $data.
	 *
	 * @var integer 
	 */
	private $pointer = 0;
	
	/**
	 * 
	 * @param integer|null $id id of the message to load, if any.
	 */
	public function __construct($id = NULL) {
		if ($id !== NULL) {
			$this->readMessage($id);
		}
	}
	
	/**
	 * Get the number of messages currently held in this object.
	 * 
	 * @return integer The number of messages
	 */
	public function numMessages() {
		return count($this->data);
	}
	
	/**
	 * Select a message held in this object which we wish to manipulate.
	 * 
	 * @param integer $pointer Index of the message data to select.
	 * @return boolean true if selected, false otherwise.
	 */
	public function selectMessage($pointer) {
		if (isset($this->data[$pointer])) {
			$this->pointer = $pointer;
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Add an array $data representing data of message to the collection.
	 * 
	 * @param array $data array with keys from_name, from_email, etc with respective values.
	 */
	public function addMessage($data) {
		$this->data[] = $data;
	}
	
	/**
	 * Get all of the messages data in the object
	 * 
	 * @return array
	 */
	public function getMessages() {
		return $this->data;
	}
	
	/**
	 * Get the data of a specific message which has been selected with
	 * selectMessage() or the first data, if not explicitly selected.
	 * 
	 * @return array|null array of data or null if no data available.
	 */
	public function getMessage() {
		if (isset($this->data[$this->pointer])) {
			return $this->data[$this->pointer];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Overwrite/create the (currently selected) message data fields to the
	 * values in $data.
	 * 
	 * @param array $data array of all or subset of the fields to update (e.g. array("from_name" => "test"))
	 */
	public function setMessage($data) {
		$this->data[$this->pointer] = array_merge($this->data[$this->pointer], $data);
	}
	
	/**
	 * Get the ID of the currently selected message
	 * 
	 * @return string|null the id of the selected message, null if message or field not available.
	 */
	public function getId() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["id"])) {
			return $this->data[$this->pointer]["id"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the unparsed from name of the message
	 * 
	 * @return string|null From name of message, null if message or field not available.
	 */
	public function getFromName() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["from_name"])) {
			return $this->data[$this->pointer]["from_name"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the from name of message after shortcodes have been applied.
	 * 
	 * @param object $comment the result of get_comment($id, OBJECT) for a particular comment.
	 * @return string|null The From Name after shortcodes applied, null if not available.
	 */
	public function getParsedFromName($comment) {
		if (!class_exists("Bbpp_ThankMeLater_Shortcoder")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "Shortcoder.php";
		}
		
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["from_name"])) {
			$from_name = $this->data[$this->pointer]["from_name"];
		} else {
			return NULL;
		}
		
		$from_name_parsed = Bbpp_ThankMeLater_Shortcoder::apply($from_name, array(
			"email_type" => "text",
			"comment" => $comment
		));
		
		return $from_name_parsed;
	}
	
	/**
	 * Get unprased From Email
	 * 
	 * @return array|null From email, null if message or field not available.
	 */
	public function getFromEmail() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["from_email"])) {
			return $this->data[$this->pointer]["from_email"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the from email after shortcodes have been applied.
	 * 
	 * @param object $comment the result of get_comment($id, OBJECT) for a particular comment.
	 * @return string|null the from e-mail after shortcodes applied, null if not available.
	 */
	public function getParsedFromEmail($comment) {
		if (!class_exists("Bbpp_ThankMeLater_Shortcoder")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "Shortcoder.php";
		}
		
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["from_email"])) {
			$from_email = $this->data[$this->pointer]["from_email"];
		} else {
			return NULL;
		}
		
		$from_email_parsed = Bbpp_ThankMeLater_Shortcoder::apply($from_email, array(
			"email_type" => "text",
			"comment" => $comment
		));
		
		// if no from e-mail, use the admin's email.
		if (empty($from_email_parsed)) {
			$from_email_parsed = get_bloginfo("admin_email");
		}
		
		return $from_email_parsed;
	}
	
	/**
	 * From header after shortcodes have been applied, in form of
	 * "FROM NAME" <FROM E-MAIL>. Warning: This is a *representation* of the header;
	 * it shouldn't be used in actual e-mails (just pass details to PHPMailer and it will deal with the headers).
	 * 
	 * @param object $comment the result of get_comment($id, OBJECT) for a particular comment.
	 * @return string representation of header in form "<from name>" <from email>
	 */
	public function getParsedFrom($comment) {		
		$from_name_parsed = $this->getParsedFromName($comment);
		$from_email_parsed = $this->getParsedFromEmail($comment);
		
		if (!empty($from_name_parsed)) {
			return "\"" . $from_name_parsed . "\" <" . $from_email_parsed . ">";
		}
		
		if (empty($from_name_parsed)) {
			return $from_email_parsed;
		}
	}
	
	/**
	 * Get the unparsed message body (with all the shortcodes unevaluated).
	 * 
	 * @param boolean $include_opt_out true if we should add opt out link IF the user has turned that option on
	 * @return array|null The unparsed message body, null if message or field not available.
	 */
	public function getMessageBody($include_opt_out = FALSE, $include_tracking_code = FALSE) {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["message"])) {
			$message = $this->data[$this->pointer]["message"];
			
			if ($include_opt_out && get_option("bbpp_thankmelater_opt_out_level") == "email") {
				if (!preg_match("#\[opt_out([^\]]*?)\]#", $message)) {
					$message .= "\n\n[opt_out url=0]";
				}
			}
			
			if ($include_tracking_code && $this->getTrackOpens()) {
				$message .= "\n\n[track]";
			}
			
			return $message;
		} else {
			return NULL;
		}
	}
	
	/**
	 * Apply the shortcodes to the message for a particular comment.
	 * Return the resulting *plaintext* message
	 * 
	 * @param object $comment the result of get_comment($id, OBJECT) for a particular comment.
	 * @return string|null the plaintext message, or null if not available.
	 */
	public function getParsedPlainMessage($comment) {
		if (!class_exists("Bbpp_ThankMeLater_Shortcoder")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "Shortcoder.php";
		}
		
		$content = $this->getMessageBody(TRUE, TRUE);
		
		if ($content === NULL) {
			return NULL;
		}
		
		return Bbpp_ThankMeLater_Shortcoder::apply($content, array(
			"email_type" => "text",
			"comment" => $comment
		));
	}
	
	/**
	 * Get the parsed HTML message for a particular $comment
	 * 
	 * @param object $comment the result of get_comment($id, OBJECT) for a particular comment.
	 * @return string|null the HTML message, or null if not available.
	 */
	public function getParsedHtmlMessage($comment) {
		if (!class_exists("Bbpp_ThankMeLater_Shortcoder")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "Shortcoder.php";
		}
		
		$content = $this->getMessageBody(TRUE, TRUE);
		
		if ($content === NULL) {
			return NULL;
		}
		
		return Bbpp_ThankMeLater_Shortcoder::apply($content, array(
			"email_type" => "html",
			"comment" => $comment
		));
	}
	
	/**
	 * Get the (unparsed) subject
	 * 
	 * @return string|null the subject, or null if not available
	 */
	public function getSubject() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["subject"])) {
			return $this->data[$this->pointer]["subject"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the subject of message after shortcodes applied
	 * 
	 * @param object $comment the result of get_comment($id, OBJECT) for a particular comment.
	 * @return string|null the parsed subject, or null if not available.
	 */
	public function getParsedSubject($comment) {
		if (!class_exists("Bbpp_ThankMeLater_Shortcoder")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "Shortcoder.php";
		}
		
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["subject"])) {
			$subject = $this->data[$this->pointer]["subject"];
		} else {
			return NULL;
		}
		
		$subject_parsed = Bbpp_ThankMeLater_Shortcoder::apply($subject, array(
			"email_type" => "text",
			"comment" => $comment
		));
		
		return $subject_parsed;
	}
	
	/**
	 * Get the message's minimum delay (in certain time units, determined by
	 * getMinDelayUnit).
	 * 
	 * @return string|null the number of time units to delay message for, null if not available
	 */
	public function getMinDelay() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["min_delay"])) {
			return $this->data[$this->pointer]["min_delay"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the units of the minimum delay time, as a string -- e.g. minutes, etc
	 * 
	 * @return string|null one of "minutes", "hours", "days", "weeks"; null if not available.
	 */
	public function getMinDelayUnit() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["min_delay_unit"])) {
			return $this->data[$this->pointer]["min_delay_unit"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the minimum delay time in seconds
	 * 
	 * @return integer|null the number of seconds to delay, or null if not available.
	 */
	public function getDelaySeconds() {
		$min_delay = $this->getMinDelay();
		$min_delay_unit = $this->getMinDelayUnit();
		$multiplier = 1;
		
		if ($min_delay === NULL || $min_delay_unit === NULL) {
			return NULL;
		}
		
		if ($min_delay_unit == "minutes") {
			$multiplier = 60;
		} elseif ($min_delay_unit == "hours") {
			$multiplier = 60 * 60;
		} elseif ($min_delay_unit == "days") {
			$multiplier = 60*60*24;
		} elseif ($min_delay_unit == "weeks") {
			$multiplier = 60*60*24*7;
		}
		
		return $min_delay * $multiplier;
	}
	
	/**
	 * Get the targeted tag ids
	 * 
	 * @return array|null array of tag ids to target, null if not available.
	 */
	public function getTargetTags() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["target_tags"])) {
			return $this->data[$this->pointer]["target_tags"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the targeted category ids
	 * 
	 * @return array|null array of category ids to target, null if not available.
	 */
	public function getTargetCategories() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["target_categories"])) {
			return $this->data[$this->pointer]["target_categories"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the targeted post ids
	 * 
	 * @return array|null array of post ids to target, null if not available.
	 */
	public function getTargetPosts() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["target_posts"])) {
			return $this->data[$this->pointer]["target_posts"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the maximum number of times a message can be sent to an individual
	 * person.
	 * 
	 * @return integer|null max number of times message can be sent (0 for unlimited), null if not available.
	 */
	public function getMaxSendsPerEmail() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["max_sends_per_email"])) {
			return (int)$this->data[$this->pointer]["max_sends_per_email"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Get the targeted posts based on tag, category and post targeting.
	 * 
	 * @global type $post
	 * @return array Array of posts with ID, post_title and post_date fields
	 */
	public function getTargets() {		
		$posts = array();
		
		$tags = $this->getTargetTags();
		$categories = $this->getTargetCategories();
		$target_posts = $this->getTargetPosts();
		
		// firstly, find the posts which match the tags or categories conditions
		$args = array();
		$args["nopaging"] = true; // get *all* results TODO this may use a lot of memory for blogs with many posts... work around this...
		$args["fields"] = "ids"; // only get ids now --> prevent memory exhaustion.
		$args["orderby"] = "date";
		$args["order"] = "DESC";
		
		if ($tags || $categories) {
			$tax_query = array(
				"relation" => "OR"
			);
			if ($tags) {
				$tax_query[] = array(
					"taxonomy" => "post_tag",
					"field" => "term_id",
					"terms" => $tags
				);
			}
			if ($categories) {
				$tax_query[] = array(
					"taxonomy" => "category",
					"field" => "term_id",
					"terms" => $categories
				);
			}
			$args["tax_query"] = $tax_query;
		}
		
		// Either:
		//  * targeting tags or categories
		//  * not targeting tags or categories, but not targeting posts -> get all posts (which the below will do)
		if ($tags || $categories || !$target_posts) {
			// Support: WP3.1 must have post_status as comma-delimited string; not array.
			//$args["post_status"] = array("publish", "pending", "draft", "future", "private");
			$args["post_status"] = "publish,pending,draft,future,private";
			
			$query = new WP_Query($args);
			
			$posts = array_merge($posts, $query->get_posts());

			wp_reset_query();
			wp_reset_postdata();
		}
		
		if ($target_posts) {
			// now get the posts which are specifically targeted
			$query = new WP_Query(array(
				"post__in" => $target_posts, 
				"nopaging" => true,
				"fields" => "ids", // only get ids now --> prevent memory exhaustion.
				"orderby" => "date",
				"order" => "DESC",
				// Support: WP3.1 must have post_status as comma-delimited string; not array.
				//"post_status" => array("publish", "pending", "draft", "future", "private")
				"post_status" => "publish,pending,draft,future,private"
			));
			
			$posts = array_merge($posts, $query->get_posts());

			wp_reset_query();
			wp_reset_postdata();
		}
		
		return $posts;
	}
	
	/**
	 * Get whether or not opens should be tracked
	 * 
	 * @return integer|null max number of times message can be sent (0 for unlimited), null if not available.
	 */
	public function getTrackOpens() {
		if (isset($this->data[$this->pointer], $this->data[$this->pointer]["track_opens"])) {
			return $this->data[$this->pointer]["track_opens"];
		} else {
			return NULL;
		}
	}
	
	/**
	 * 
	 * @param type $a
	 * @param type $b
	 */
	private function _cmp_post_date($a, $b) {
		return ($a["post_date"] < $b["post_date"]) ? 1 : -1;
	}
	
	/**
	 * Read in a particular message
	 * 
	 * @global type $wpdb
	 * @param integer $id id of the message to read
	 * @return array The messages' data (e.g. $data)
	 */
	public function readMessage($id) {
		global $wpdb;
		
		$where = $wpdb->prepare("`id` = %d", $id);
		return $this->_readMessages(NULL, NULL, $where);
	}
	
	/**
	 * Read in a collection of messages
	 * 
	 * @param integer|null $limit the number of messages to read
	 * @param integer|null $offset the message number to start reading from
	 * @param string|null $orderby set to "subject" to order by subject. NULL for unordered.
	 * @param string|null $order one of "desc" and "asc".
	 * @return array The messages' data (e.g. $data).
	 */
	public function readMessages($limit = NULL, $offset = NULL, $orderby = NULL, $order = NULL) {			
		return $this->_readMessages($limit, $offset, "", $orderby, $order);
	}
	
	/**
	 * Send mail for a particular $comment
	 * Inspired by code from the core for wp_mail function.
	 * 
	 * @global PHPMailer $phpmailer
	 * @param object $comment result of get_comment
	 * @return boolean TRUE if all messages sent okay, FALSE if any individual send gives error.
	 */
	public function send($comment) {
		global $phpmailer;
		
		// get PHPMailer and SMTP classes, if not already available.
		if (!is_object($phpmailer) || !is_a($phpmailer, "PHPMailer")) {
			require_once  ABSPATH . WPINC . "/class-phpmailer.php";
			require_once  ABSPATH . WPINC . "/class-smtp.php";
			$phpmailer = new PHPMailer(TRUE);
			
		}
		
		$num_messages = $this->numMessages();
		
		// send each message that has been loaded into this object:
		for ($mid = 0; $mid < $num_messages; $mid++) {
			$this->selectMessage($mid);
			$recipient_email = $comment->comment_author_email;
			$from_name = $this->getParsedFromName($comment);
			$from_email = $this->getParsedFromEmail($comment);
			$recipient_name = $comment->comment_author;
			$body_html = $this->getParsedHtmlMessage($comment);
			$body_plain = $this->getParsedPlainMessage($comment);

			// clear any previous PHPMailer settings
			$phpmailer->ClearAddresses();
			$phpmailer->ClearAllRecipients();
			$phpmailer->ClearAttachments();
			$phpmailer->ClearBCCs();
			$phpmailer->ClearCCs();
			$phpmailer->ClearCustomHeaders();
			$phpmailer->ClearReplyTos();
			
			// set from and subject
			$phpmailer->From = $from_email;
			$phpmailer->FromName = $from_name;
			$phpmailer->Subject = $this->getParsedSubject($comment);
			
			// set recipient
			try {
				if ((version_compare(PHP_VERSION, "5.2.11", ">=") && version_compare(PHP_VERSION, "5.3", "<"))
					|| version_compare(PHP_VERSION, "5.3.1", ">=")) {
					$phpmailer->AddAddress($recipient_email, $recipient_name);
				} else {
					// Support: PHP <5.2.11 and PHP 3.0. mail() function on
					// Windows has bug; doesn't deal with recipient name
					// correctly. See https://bugs.php.net/bug.php?id=28038
					$phpmailer->AddAddress(trim($recipient_email));
				}
			} catch (phpmailerException $e) {
				return FALSE;
			}
			
			// body HTML needs to be cut off at reasonable line length
			$body_html_wrapped = wordwrap($body_html, 900, "\n", TRUE);
			
			// add HTML body and alternative plain text.
			$phpmailer->Body = $body_html_wrapped;
			$phpmailer->isHTML(true);
			$phpmailer->AltBody = $body_plain;
			$phpmailer->Encoding = "8bit";
			$phpmailer->WordWrap = 80; // word wrap the plain text message
			$phpmailer->CharSet = "UTF-8";

			// try to send
			try {
				$phpmailer->Send();
			} catch (phpmailerException $e) {
				var_dump($e);
				return FALSE;
			}
			
			try {
			} catch (Exception $ex) {

			}
		}
		
		return TRUE;
	}
	
	/**
	 * Find targeted messages and schedule them to be sent to a particular commenter
	 * 
	 * @global type $wpdb
	 * @param object $comment result of get_comment
	 * @return array Array of \WP_Error objects, if any
	 */
	public function scheduleMessages($comment) {
		global $wpdb;
		
		// time of comment
		$comment_time = strtotime($comment->comment_date_gmt . " GMT");
		
		// load the post
		$post_id = $comment->comment_post_ID;
		$post = get_post($post_id);
		$post_tags_full = get_the_tags($post->ID);
		$post_categories_full = get_the_category($post->ID);
		
		$post_tags_full = is_array($post_tags_full) ? $post_tags_full : array();
		$post_categories_full = is_array($post_categories_full) ? $post_categories_full : array();
		
		// post tags and category ids
		$post_tags = array_map(array($this, "_getPostTermId"), $post_tags_full);
		$post_categories = array_map(array($this, "_getPostTermId"), $post_categories_full);
		
		// we're going to schedule any e-mails which match our criteria...
		$schedule = new Bbpp_ThankMeLater_Schedule();
		
		// read in all messages
		$this->readMessages();
		$num_messages = $this->numMessages();
		
		for ($mid = 0; $mid < $num_messages; $mid++) {
			$this->selectMessage($mid);
			$data = $this->getMessage();
			
			// number of these messages sent/waiting to be sent to a particular commenter
			$num_schedules = $schedule->getNumSchedules($data["id"], $comment->comment_author_email);
			
			$targeted = FALSE;
			
			// no restrictions => target all posts
			if (!$data["target_tags"] && !$data["target_categories"] && !$data["target_posts"]) {
				$targeted = TRUE;
			}
			
			// we are in a targeted tag
			if ($data["target_tags"] && array_intersect($data["target_tags"], $post_tags)) {
				$targeted = TRUE;
			}
			
			// we are in a targeted categories
			if ($data["target_categories"] && array_intersect($data["target_categories"], $post_categories)) {
				$targeted = TRUE;
			}
			
			// we are a targeted post
			if ($data["target_posts"] && in_array($post->ID, $data["target_posts"])) {
				$targeted = TRUE;
			}
			
			// make sure we have not exceeded the send limit
			if ($data["max_sends_per_email"] > 0 && $num_schedules >= $data["max_sends_per_email"]) {
				$targeted = FALSE;
			}
			
			// make sure we haven't already scheduled an e-mail (don't send twice!)
			if ($schedule->findScheduleId($data["id"], $comment->comment_ID)) {
				$targeted = FALSE;
			}
			
			if ($targeted) {
				// send in future or now, if send time has passed -- this may
				// happen if comment has been on hold for approval for some time.
				// It's important we do this, as otherwise the scheduler may
				// believe that the message has failed to be sent and delete it.s
				$send_date_gmt = max($comment_time + $this->getDelaySeconds(), time());
				
				// schedule the send of this message to the commenter.
				$schedule->addSchedule(array(
					"message_id" => $data["id"],
					"comment_id" => $comment->comment_ID,
					"send_date_gmt" => $send_date_gmt,
					"sent" => 0
				));
			}
		}
		
		// add to schedule
		return $schedule->save();
	}
	
	/**
	 * Get the *total* number of messages that are available (not just in this object,
	 * the number of messages in the table)
	 * 
	 * @global type $wpdb
	 * @return integer The number of messages that have been created.
	 */
	public function countMessages() {
		global $wpdb;
		
		return (int)$wpdb->get_var("
			SELECT COUNT(*) FROM `{$wpdb->prefix}bbpp_thankmelater_messages`
		");
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
			$row_errors = $this->_validateRow($data);
			
			if ($row_errors->get_error_codes()) {
				$errors[$rid] = $row_errors;
			}
		}
		
		return $errors;
	}
	
	/**
	 * Validate a particular data record $data
	 * 
	 * @param array $data the particular data record to check
	 * @return \WP_Error collection of errors
	 */
	private function _validateRow($data) {
		$errors = new WP_Error();
		
		if (strlen($data["from_name"]) > 255) {
			$errors->add("from_name", __("This must be fewer than 250 letters long.", "bbpp-thankmelater"));
		}

		if (empty($data["from_name"])) {
			$errors->add("from_name", __("This must not be blank.", "bbpp-thankmelater"));
		}
		
		if (is_email($data["from_email"]) === FALSE) {
			$errors->add("from_email", __("This must be a real email address.", "bbpp-thankmelater"));
		}
		
		if (strlen($data["subject"]) > 255) {
			$errors->add("subject", __("This must be fewer than 250 letters long.", "bbpp-thankmelater"));
		}

		if (empty($data["subject"])) {
			$errors->add("subject", __("This must not be blank.", "bbpp-thankmelater"));
		}
		
		if (empty($data["message"])) {
			$errors->add("message", __("This must not be blank.", "bbpp-thankmelater"));
		}
		
		if (!is_numeric($data["min_delay"])) {
			$errors->add("min_delay", __("This must be a number.", "bbpp-thankmelater"));
		}
		
		if (!in_array($data["min_delay_unit"], array("minutes", "hours", "days", "weeks"))) {
			$errors->add("min_delay_unit", __("This must be a real length of time.", "bbpp-thankmelater"));
		}
		
		if (!is_array($data["target_tags"])) {
			$errors->add("target_tags", __("You must select some tags.", "bbpp-thankmelater"));
		} else {
			// check the provided terms actually exist:
			$invalid_terms = $this->_validateGetInvalidTerms($data["target_tags"], "post_tag");
			
			if ($invalid_terms) {
				$errors->add("target_tags", __("You must select some tags.", "bbpp-thankmelater"));
			}
		}
		
		if (!is_array($data["target_categories"])) {
			$errors->add("target_categories", __("You must select some categories.", "bbpp-thankmelater"));
		} else {
			// check the provided terms actually exist:
			$invalid_terms = $this->_validateGetInvalidTerms($data["target_categories"], "category");
			
			if ($invalid_terms) {
				$errors->add("target_categories", __("You must select some categories.", "bbpp-thankmelater"));
			}
		}
		
		if (!is_array($data["target_posts"])) {
			$errors->add("target_posts", __("You must select some posts.", "bbpp-thankmelater"));
		} else {
			// check all the posts are valid
			$valid = TRUE;
			
			foreach ($data["target_posts"] as $post) {
				if (get_post($post) === null) {
					$valid = FALSE;
				}
			}
			
			if (!$valid) {
				$errors->add("target_posts", __("You must select some posts.", "bbpp-thankmelater"));
			}
		}
		
		if (!is_numeric($data["max_sends_per_email"])) {
			$errors->add("max_sends_per_email", __("This must be a number.", "bbpp-thankmelater"));
		}
		
		if (!in_array($data["track_opens"], array("0", "1"))) {
			$errors->add("track_opens", __("Select an option.", "bbpp-thankmelater"));
		}

		return $errors;
	}
	
	/**
	 * Get the terms of a taxonomatic condition that are invalid.
	 * 
	 * @param array $terms array of term ids to check
	 * @param string $taxonomy one of "category", "post_tag"
	 */
	private function _validateGetInvalidTerms($terms, $taxonomy = "category") {
		$invalid_terms = array();
		
		foreach ($terms as $term) {
			$r = get_term($term, $taxonomy);
			if ($r === NULL || is_wp_error($r)) {
				$invalid_terms[] = $term;
			}
		}
		
		return $invalid_terms;
	}
	
	/**
	 * Convert the result from the database into the representation stored in this
	 * object (e.g. unserialize certain values, transform others, etc).
	 * 
	 * @param array $data array of database values indexed by field name.
	 * @return array the resultant Array representing an individual message's data.
	 */
	private function _translateRow($data) {
		if (!empty($data["target_tags"])) {
			$target_tags = explode(",", $data["target_tags"]);
		} else {
			$target_tags = array();
		}
		
		if (!empty($data["target_categories"])) {
			$target_categories = explode(",", $data["target_categories"]);
		} else {
			$target_categories = array();
		}
		
		if (!empty($data["target_posts"])) {
			$target_posts = explode(",", $data["target_posts"]);
		} else {
			$target_posts = array();
		}
		
		return array(
			"id" => $data["id"],
			"from_name" => $data["from_name"], 
			"from_email" => $data["from_email"],
			"subject" => $data["subject"],
			"message" => $data["message"],
			"min_delay" => $data["min_delay"],
			"min_delay_unit" => $data["min_delay_unit"],
			"target_tags" => $target_tags,
			"target_categories" => $target_categories,
			"target_posts" => $target_posts,
			"max_sends_per_email" => $data["max_sends_per_email"],
			"track_opens" => (string)$data["track_opens"]
		);
	}
	
	/**
	 * Add a row of data representing a message to our object
	 * 
	 * @param array $data array of database values indexed by field name.
	 */
	private function _readRow($data) {
		$this->addMessage($this->_translateRow($data));
	}
	
	/**
	 * Read the results from the database and return the records
	 * 
	 * @global type $wpdb
	 * @param integer|null $limit the number of messages to read
	 * @param integer|null $offset the message number to start reading from
	 * @param string $where the WHERE clause to use in the query
	 * @param string|null $orderby set to "subject" to order by subject. NULL for unordered.
	 * @param string|null $order one of "desc" and "asc".
	 * @return array The messages' data (e.g. $data).
	 */
	private function _readMessages($limit = NULL, $offset = NULL, $where = "", $orderby = NULL, $order = NULL) {
		global $wpdb;
		
		// clear existing results
		$this->data = array();
		
		// construct order sql
		$order_sql = "";
		if (!empty($orderby) && in_array($orderby, array("subject"))) {
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
			FROM `{$wpdb->prefix}bbpp_thankmelater_messages`
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
		
		// select the 1st message
		$this->selectMessage(0);
		
		// return the results
		return $this->getMessages();
	}
	
	/**
	 * Get the ID of a term object (e.g. the category or tag id).
	 * 
	 * @param object $obj result of get_the_tags or get_the_categories
	 * @return integer the term id
	 */
	private function _getPostTermId($obj) {
		return (int)$obj->term_id;
	}
	
	/**
	 * Save this object to the database
	 * 
	 * @global type $wpdb
	 * @return array collection of \WP_Error if any
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
				"from_name" => $data["from_name"], 
				"from_email" => $data["from_email"],
				"subject" => $data["subject"],
				"message" => $data["message"],
				"min_delay" => $data["min_delay"],
				"min_delay_unit" => $data["min_delay_unit"],
				"target_tags" => implode(",", $data["target_tags"]),
				"target_categories" => implode(",", $data["target_categories"]),
				"target_posts" => implode(",", $data["target_posts"]),
				"max_sends_per_email" => $data["max_sends_per_email"],
				"track_opens" => $data["track_opens"]
			);
			
			if (!empty($data["id"])) {
				// update message
				$wpdb->update(
					$wpdb->prefix . "bbpp_thankmelater_messages",
					$wdata,
					array(
						"id" => $data["id"]
					)
				);
			} else {
				// new message
				$wpdb->insert(
					$wpdb->prefix . "bbpp_thankmelater_messages", 
					$wdata
				);
				$id = $wpdb->insert_id;
				$this->data[$pid]["id"] = $id;
			}
		}
		
		return $errors;
	}
	
	/**
	 * Delete the message(s)
	 * 
	 * @global type $wpdb
	 */
	public function delete() {
		global $wpdb;
		
		foreach ($this->data as $data) {
			$wpdb->query($wpdb->prepare("
				DELETE FROM `{$wpdb->prefix}bbpp_thankmelater_messages`
				WHERE `id` = %d
			", $data["id"]));
			
			// remove unsent messages from the schedule
			$schedule = new Bbpp_ThankMeLater_Schedule();
			$schedule->deleteUnsentByMessageId($data["id"]);
		}
	}
}