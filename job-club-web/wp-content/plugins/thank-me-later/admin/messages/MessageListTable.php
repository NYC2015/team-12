<?php

if (!class_exists("WP_List_Table")) {
	require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
}

/**
 * Handle display of messages table
 * Thanks to Matt Van Andel for his excellent example in "Custom List Table Example"
 */
class Bbpp_ThankMeLater_MessageListTable extends WP_List_Table {
	/**
	 * Messages to display per table page
	 *
	 * @var type 
	 */
	public $per_page = 5;
	
	/**
	 * 
	 */
	public function __construct() {
		parent::__construct(array(
			"singular" => "message",
			"plural" => "messages",
			"ajax" => FALSE
		));
	}
	
	/**
	 * Format 'subject'
	 * 
	 * @param array $data array of field values for a particular message
	 * @return string formatted version
	 */
	public function column_subject($data) {
		$t_edit = _x("Edit", "verb", "bbpp-thankmelater");
		$t_delete = _x("Delete", "verb", "bbpp-thankmelater");
		
		// get secure URL for delete action
		$delete_url = wp_nonce_url(sprintf(
			"?page=%s&action=delete&id=%s",
			urlencode(stripslashes($_REQUEST['page'])),
			urlencode($data["id"])
		), "bbpp_thankmelater_delete_message_" . $data["id"]);
		
        $actions = array(
			"edit" => sprintf(
				"<a href=\"?page=%s&action=edit&id=%s\">{$t_edit}</a>",
				urlencode(stripslashes($_REQUEST['page'])),
				urlencode($data["id"])
			),
            "delete" => "<a href=\"" . esc_attr($delete_url) . "\">{$t_delete}</a>"
        );
		
        return $data['subject'] . $this->row_actions($actions);
	}
	
	/**
	 * Format 'targeting' value
	 */
	public function column_targeting($data) {
		$target_tags = $data["target_tags"];
		$target_categories = $data["target_categories"];
		$target_posts = $data["target_posts"];
		
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
		$max_show = 2;
		
		if ($count == 0) {
			$summary = __("Targets 0 posts", "bbpp-thankmelater");
		} elseif ($count <= $max_show) {
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
			
			$summary = sprintf(
				_n("Targets %2\$s", "Targets %s and %s", $count, "bbpp-thankmelater"),
				esc_html($names),
				esc_html($last_name)
			);
		} else {
			$names = "";
			
			for ($i = 0; $i < $max_show; $i++) {
				if ($i > 0) {
					$names .= $sep;
				}
				$post = get_post($posts[$i]);
				$names .= $post->post_title;
			}
			
			$view_all_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
				. "&action=targets"
				. "&id=" . $data["id"];
			
			$summary = sprintf(
				_n("Targets %s and %s%d other post%s", "Targets %s and %s%d other posts%s.", $count-$max_show, "bbpp-thankmelater"),
				esc_html($names),
				"<a href=\"{$view_all_url}\">",
				$count - $max_show,
				"</a>"
			);
		}
		
		if (!$target_categories && !$target_tags && !$target_posts) {
			$summary = __("Targets all posts", "bbpp-thankmelater");
		}
		
		return $summary;
	}
	
	/**
	 * Format the checkbox
	 * 
	 * @param array $record array of field values for a particular message
	 * @return string html for the checkbox
	 */
	public function column_cb($record) {
		return "<input type=\"checkbox\" name=\"id[]\" value=\"" . $record["id"] . "\">";
	}
	
	/**
	 * Get columns that are to be displayed in the table
	 * 
	 * @return array
	 */
	public function get_columns() {
		return array(
			"cb" => "<input type=\"checkbox\" />",
			"subject" => _x("Subject", "noun", "bbpp-thankmelater"),
			"targeting" => _x("Targeting", "noun", "bbpp-thankmelater")
		);
	}
	
	/**
	 * 
	 * @return type
	 */
	public function get_sortable_columns() {
		return array(
			"subject" => array("subject", FALSE)
		);
	}
	
	/**
	 * 
	 * @return type
	 */
	public function get_bulk_actions() {
		return array(
			"index-delete" => _x("Delete", "verb", "bbpp-thankmelater")
		);
	}
	
	/**
	 * 
	 */
	public function process_bulk_action() {
		// TODO: Do we need to check the nonce here. I've assumed it's done automatically for me!? Have a look...
		if ($this->current_action() === "index-delete") {
			foreach ($_REQUEST["id"] as $id) {
				$id = intval($id);

				// delete the message
				$message = new Bbpp_ThankMeLater_Message($id);
				$message->delete();
			}
		}
	}
	
	/**
	 * 
	 * @global type $wpdb
	 */
	public function prepare_items() {		
		if (!class_exists("Bbpp_ThankMeLater_Message")) {
			require_once BBPP_THANKMELATER_PLUGIN_PATH . "Message.php";
		}
		
		// we'll store the messages in this object:
		$message = new Bbpp_ThankMeLater_Message();
		
		// set the table headers
		$this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
		
		// do the bulk actions, if any
		$this->process_bulk_action();
		
		// get the number of results
		$current_page = $this->get_pagenum();
		$total_items = $message->countMessages();
		$this->set_pagination_args(array(
			"total_items" => $total_items,
			"per_page" => $this->per_page,
			"total_pages" => ceil($total_items / $this->per_page)
		));
		
		$orderby = NULL;
		$order = NULL;
		$sortable_vals = array_keys($this->get_sortable_columns());
		
		if (!empty($_REQUEST["orderby"]) && in_array(stripslashes($_REQUEST["orderby"]), $sortable_vals)) {
			$orderby = stripslashes($_REQUEST["orderby"]);
			
			if (!empty($_REQUEST["order"]) && in_array(stripslashes($_REQUEST["order"]), array("asc", "desc"))) {
				$order = stripslashes($_REQUEST["order"]);
			}
		}
		
		// get the results
		$this->items = $message->readMessages(
			$this->per_page, 
			($current_page - 1)*$this->per_page,
			$orderby,
			$order
		);
	}
}