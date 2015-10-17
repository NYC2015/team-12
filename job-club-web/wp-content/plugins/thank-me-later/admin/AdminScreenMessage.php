<?php

/**
 * Handles the display of the 'messages' pane in the admin screens.
 */
class Bbpp_ThankMeLater_AdminScreenMessage {
	/**
	 * Show the user-requested page
	 */
	public function route() {
		$action = "index";
		
		if (!empty($_REQUEST["action"])) {
			$action = stripslashes($_REQUEST["action"]);
		}
		
		switch ($action) {
			case "create":
				$this->add();
				break;
			case "edit":
				$id = intval($_REQUEST["id"]);
				$this->edit($id);
				break;
			case "delete":
				$id = intval($_REQUEST["id"]);
				
				// make sure request is real
				check_admin_referer("bbpp_thankmelater_delete_message_" . $id);
				
				// delete the message
				$message = new Bbpp_ThankMeLater_Message($id);
				$message->delete();
				
				// go back to list of messages
				$_REQUEST["action"] = "index";
				$this->index(array($id));
				break;
			case "bbpp_thankmelater_message_preview":
				$data = stripslashes_deep($_POST);
				$from_name = $data["from_name"];
				$from_email = $data["from_email"];
				$subject = $data["subject"];
				$message = $data["message"];
				$this->preview($from_name, $from_email, $subject, $message);
				break;
			case "bbpp_thankmelater_message_targeting":
				$data = stripslashes_deep($_POST);
				$target_tags = isset($data["target_tags"]) ? $data["target_tags"] : array();
				$target_categories = isset($data["target_categories"]) ? $data["target_categories"] : array();
				$target_posts = isset($data["target_posts"]) ? $data["target_posts"] : array();
				$this->targeting($target_tags, $target_categories, $target_posts);
				break;
			case "targets":
				$id = intval($_REQUEST["id"]);
				$this->targets($id);
				break;
			case "index":
			default:
				$this->index();
				break;
		}
	}
	
	/**
	 * Show 'create a new message' page.
	 */
	public function add() {
		$this->edit(0);
	}
	
	/**
	 * Show 'edit message' page.
	 * 
	 * @param integer $id id of message to edit, NULL/0 to create new message
	 */
	public function edit($id = NULL) {
		if ($id) {
			// load message
			$message = new Bbpp_ThankMeLater_Message($id);
		} else {
			// create default message
			$blog_name = get_bloginfo("name");
			$message = new Bbpp_ThankMeLater_Message();
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
			$message->addMessage(array(
				"from_name" => $blog_name,
				"from_email" => get_bloginfo("admin_email"),
				"subject" => $message_subject,
				"message" => $message_message,
				"min_delay" => 30,
				"min_delay_unit" => "minutes",
				"target_tags" => array(),
				"target_categories" => array(),
				"target_posts" => array(),
				"max_sends_per_email" => 0,
				"track_opens" => 1
			));
		}
		$error = array();
		
		if ($_POST) {
			// save the changes to the database
			check_admin_referer("bbpp_thankmelater_edit_message_" . intval($id));
			$data = stripslashes_deep($_POST);
			
			if (!isset($data["target_tags"])) {
				$data["target_tags"] = array();
			}
			
			if (!isset($data["target_categories"])) {
				$data["target_categories"] = array();
			}
			
			if (!isset($data["target_posts"])) {
				$data["target_posts"] = array();
			}
			
			if (!isset($data["track_opens"])) {
				$data["track_opens"] = "0";
			}
			
			$message->setMessage($data);
			$error = $message->save();
			
			if (!$error) {
				// message updated! show the list of messages:
				$id = $message->getId();
				return $this->index(false, array($id));
			}
		}
		
		//$ab_sort = function ($a, $b) {
		//	return ($a->name < $b->name) ? -1 : 1;
		//};
		
		// get the tag targeting options
		$tag_options = get_tags(array(
			"number" => 200, 
			"orderby" => "count",
			"order" => "desc",
			"hide_empty" => false
		));
		usort($tag_options, array($this, "_cmp_name"));
		
		// get the category targeting options
		$category_options = get_categories(array(
			"number" => 200, 
			"orderby" => "count",
			"order" => "desc",
			"hide_empty" => false
		));
		usort($category_options, array($this, "_cmp_name"));
		
		// get the posts which may be targeted
		$post_options = get_posts(array(
			"numberposts" => 200, 
			"orderby" => "post_date",
			"order" => "desc",
			// Support: WP3.1 must have post_status as comma-delimited string; not array.
			//"post_status" => array("publish", "pending", "draft", "future", "private")
			"post_status" => "publish,pending,draft,future,private"
		));
		usort($post_options, array($this, "_cmp_post_title"));
		
		// show the edit form
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/messages/edit.php";
	}
	
	/**
	 * 
	 * @param type $a
	 * @param type $b
	 * @return type
	 */
	private function _cmp_name($a, $b) {
		return ($a->name < $b->name) ? -1 : 1;
	}
	
	/**
	 * 
	 * @param type $a
	 * @param type $b
	 * @return type
	 */
	private function _cmp_post_title($a, $b) {
		return ($a->post_title < $b->post_title) ? -1 : 1;
	}
	
	/**
	 * Show list of messages
	 * 
	 * @param array|null $deleted array of id's of messages which have been deleted (show message to user)
	 * @param array|null $edited array of id's of messages which have been edited
	 */
	public function index($deleted = NULL, $edited = NULL) {
		if (!class_exists("Bbpp_ThankMeLater_MessageListTable")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/messages/MessageListTable.php";
		}
		
		$message_list_table = new Bbpp_ThankMeLater_MessageListTable();
		$message_list_table->prepare_items();
		
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/messages/overview.php";
	}
	
	/**
	 * Generate a message preview (called with AJAX)
	 * 
	 * @param string $from_name
	 * @param string $from_email
	 * @param string $subject
	 * @param string $body
	 */
	public function preview($from_name, $from_email, $subject, $body) {
		// create a virtual Thank Me Later message
		$message = new Bbpp_ThankMeLater_Message();
		$message->addMessage(array(
			"from_name" => $from_name,
			"from_email" => $from_email,
			"subject" => $subject,
			"message" => $body
		));
		
		$post_id = get_option("bbpp_thankmelater_preview_post_id");
		$comment = null;
		$current_user = wp_get_current_user();
		
		// create a virtual post
		if (!$post_id || !get_post($post_id)) {
			$post_id = wp_insert_post(array(
				"post_author" => $current_user->ID,
				"post_content" => __("This is a sample post created by Thank Me Later.", "bbpp-thankmelater"),
				"post_status" => "bbpp-thankmelater-pv",
				"post_title" => __("Sample post title", "bbpp-thankmelater"),
				"post_type" => "post",
				"post_date_gmt" => gmdate("Y-m-d H:i:s")
			));
			update_option("bbpp_thankmelater_preview_post_id", $post_id);
		} else {
			$comment_id = get_option("bbpp_thankmelater_preview_comment_id");
			$comment = get_comment($comment_id);
		}
		
		// create a virtual comment
		if (!$comment || !get_post($comment->comment_post_ID)) {
			$comment_id = wp_insert_comment(array(
				"comment_post_ID" => $post_id,
				"comment_author" => _x("John Doe", "placeholder name", "bbpp-thankmelater"),
				"comment_author_email" => _x("john.doe@example.com", "placeholder e-mail", "bbpp-thankmelater"),
				"comment_content" => __("This is a sample comment used by Thank Me Later. It is not displayed on any blog posts.", "bbpp-thankmelater"),
				"comment_author_IP" => "127.0.0.1",
				"comment_approved" => "1",
				"comment_agent" => "Firefox",
				"comment_date" => date("Y-m-d H:i:s"),
				"comment_date_gmt" => gmdate("Y-m-d H:i:s"),
				"user_id" => $current_user->ID
			));
			$comment = get_comment($comment_id);
			update_option("bbpp_thankmelater_preview_comment_id", $comment_id);
		} else {
			$comment = get_comment($comment_id);
		}
		
		$response = array(
			"from" => $message->getParsedFrom($comment),
			"subject" => $message->getParsedSubject($comment)
		);
		
		$response["message"] = array(
			"text" => $message->getParsedPlainMessage($comment),
			"html" => $message->getParsedHtmlMessage($comment),
		);
		
		echo json_encode($response);
		exit;
	}
	
	/**
	 * Generate a summary of the targeted messages (call with AJAX)
	 * 
	 * @param array $target_tags
	 * @param array $target_categories
	 * @param array $target_posts
	 */
	public function targeting($target_tags, $target_categories, $target_posts) {
		// create a virtual Thank Me Later message
		$message = new Bbpp_ThankMeLater_Message();
		$message->addMessage(array(
			"target_tags" => $target_tags,
			"target_categories" => $target_categories,
			"target_posts" => $target_posts
		));
		
		$posts = $message->getTargets();
		$count = count($posts);
		$sep = _x(", ", "separator in list of post names", "bbpp-thankmelater");
		$max_show = 4;
		
		if ($count == 0) {
			// restrictions returns no posts.
			$summary = __("Targets 0 posts", "bbpp-thankmelater");
		} elseif ($count <= $max_show) {
			// show all posts
			$names = "";
			$post = get_post($posts[$count-1]);
			$last_name = $post->post_title;
			
			for ($i = 0; $i < $count-1; $i++) {
				if ($i > 0) {
					$names .= $sep;
				}
				$post = get_post($posts[$i]);
				$names .= $post->post_title;
			}
			
			/* translators: %2$s will be replaced with post name. In plural version, the first %s is a seperated list of post titles, the second %s is the title of the last post */
			$t_summary = _n("Targets %2\$s", "Targets %s and %s", $count, "bbpp-thankmelater");
			
			$summary = sprintf(
				$t_summary,
				$names,
				$last_name
			);
		} else {
			// show first $max_show posts, followed by 'and [n] other posts'
			$names = "";
			
			for ($i = 0; $i < $max_show; $i++) {
				if ($i > 0) {
					$names .= $sep;
				}
				$post = get_post($posts[$i]);
				$names .= $post->post_title;
			}
			
			$summary = sprintf(
				_n("Targets %s and %s%d other post%s", "Targets %s and %s%d other posts%s", $count - $max_show, "bbpp-thankmelater"),
				$names,
				"",
				$count - $max_show,
				""
			);
		}
		
		if (!$target_categories && !$target_tags && !$target_posts) {
			$summary = __("Targets all posts", "bbpp-thankmelater");
		}
		
		$response = array(
			"summary" => $summary
		);
		
		echo json_encode($response);
		exit;
	}
	
	/**
	 * Get a full list of all posts targeted by a message
	 * 
	 * @param type $id
	 */
	public function targets($id) {
		// create a virtual Thank Me Later message
		$message = new Bbpp_ThankMeLater_Message($id);
		
		$posts = $message->getTargets();
		$count = count($posts);
		
		require_once BBPP_THANKMELATER_PLUGIN_PATH . "admin/messages/targets.php";
	}
}