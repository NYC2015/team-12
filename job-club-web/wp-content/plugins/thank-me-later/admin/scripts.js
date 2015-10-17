jQuery(document).ready(function() {
	"use strict";
	
	var Reference = this,
		$ = jQuery,
		$link_show = $("#bbpp-thankmelater-shortcode-reference-a-show"),
		$link_hide = $("#bbpp-thankmelater-shortcode-reference-a-hide"),
		$ref = $("#bbpp-thankmelater-shortcode-reference");
	
	$link_show.click(function() {
		$ref.show();
		$link_show.hide();
		$link_hide.show();
		
		return false;
	});
	
	$link_hide.click(function() {
		$ref.hide();
		$link_show.show();
		$link_hide.hide();
		
		return false;
	});
});

jQuery(document).ready(function () {
	"use strict";
		
	var Preview = {},
		$ = jQuery,
		$selector = $("#bbpp-thankmelater-message-preview-selector"),
		$selector_li = $selector.find("li"),
		$loader = $("#bbpp-thankmelater-message-preview-loading"),
		$text_preview = $("#bbpp-thankmelater-message-preview-text"),
		$html_preview = $("#bbpp-thankmelater-message-preview-html"),
		$message = $("#message.bbpp-thankmelater-message"),
		$from_name = $("#from_name"),
		$from_email = $("#from_email"),
		$subject = $("#subject"),
		objectL10n = bbpp_thankmelater_objectL10n;

	/**
	 *
	 */
	Preview.options = {
		/**
		 * Maximum to wait before forcing an update of message preview, in ms
		 */
		max_hold_time: 2500, 

		/**
		 * Time to wait after last keystroke before updating the message preview, in ms.
		 */
		key_hold_time: 750
	};

	/**
	 * The selected type of preview -- either "text" or "html"
	 */
	Preview.selected_type = "text";

	/**
	 * The last time we asked the server for an updated preview.
	 */
	Preview.last_download_time = null;

	/**
	 * The last time the user updated the message, from name, from email or
	 * subject field (i.e. the time of last keystroke)
	 */
	Preview.last_keystroke_time = 0;

	/**
	 * 
	 */
	Preview.check_timeout = null;

	/**
	 * The ID of the last request made to the server. Increments with
	 * each request.
	 */
	Preview.req_id = 0;

	/**
	 * The ID of the last response which is shown in the message preview.
	 */
	Preview.shown_id = 0;

	/**
	 *
	 */
	Preview.request = null;

	/**
	 * Boolean: true if something has changed since we asked the server for the
	 * preview (i.e. true if user presses key between download and response).
	 */
	Preview.has_changed = true;

	/**
	 * Gets the current local time, in ms since epoch.
	 */
	Preview.getTime = function () {
		return (new Date()).getTime();
	};

	/**
	 * Handles the keyup event for any field
	 */
	Preview.messageKeyUp = function () {
		Preview.last_keystroke_time = Preview.getTime();

		// if nothing has changed before *this* keystroke, our copy is 
		// up-to-date (bar *this* change); so the last download time can be 
		// regarded as just now.
		if (!Preview.has_changed) {
			Preview.last_download_time = Preview.last_keystroke_time;
		}

		Preview.has_changed = true;

		// show the loading screen
		$loader.show();

		// check if we should download yet...
		Preview.messageDownloadCheck();
	};

	/**
	 * Handle the setTimeout tick for calling messageDownloadCheck
	 */
	Preview.messageDownloadCheckCb = function () {
		Preview.messageDownloadCheck();
	};

	/**
	 * Check if we are able to request a new preview from the server yet.
	 * Checks if sufficient time has passed since last keystroke, or if
	 * significant time has passed since our last fresh copy.
	 */
	Preview.messageDownloadCheck = function () {
		var now_time = Preview.getTime(),
			wait_time,
			is_key_expired,
			is_max_expired;

		// we'll schedule the next request; cancel any existing calls
		if (Preview.check_timeout) {
			clearTimeout(Preview.check_timeout);
		}

		// if we've waited [keyHoldTime] since last key stroke, or
		// we've reached maximum wait time, then force a download....
		is_key_expired = (now_time >= Preview.last_keystroke_time + Preview.options.key_hold_time);
		is_max_expired = (now_time >= Preview.last_download_time + Preview.options.max_hold_time);
		if (is_key_expired || is_max_expired) {
			Preview.messageDownload();
			return;
		}

		// not downloading, call us back as soon as anything changes:
		wait_time = Math.max(
			Math.min(
				Preview.last_keystroke_time + Preview.options.key_hold_time - now_time, 
				Preview.last_download_time + Preview.options.max_hold_time - now_time
			), 
			50
		);
		Preview.check_timeout = setTimeout(Preview.messageDownloadCheckCb, wait_time);
	};

	/**
	 * Handle the response from the server.
	 * 
	 * req_id is the unique request ID for particular communication.
	 */
	Preview.messageDownloadCb = function (req_id) {
		return function (resp) {
			Preview.messageHandler(req_id, resp);
		};
	};

	/**
	 * Download the latest preview message from the server.
	 */
	Preview.messageDownload = function () {
		var handler;

		Preview.last_download_time = Preview.getTime();
		Preview.req_id++;

		// get function to handle the server's response
		handler = Preview.messageDownloadCb(Preview.req_id);

		// cancel any previous requests, they are out of date already...
		//if (this.request) {
		//	this.request.abort();
		//}

		// up to date, allow the loading screen to disappear on completion.
		// if something changes between the request being made and response
		// being heard, this will get changed to true, so we can pick it up.
		Preview.has_changed = false;

		// make a new request
		Preview.request = $.post(ajaxurl, {
			"action": "bbpp_thankmelater_message_preview",
			"from_name": $from_name.val(),
			"from_email": $from_email.val(),
			"subject": $subject.val(),
			"message": $message.val()
		}, handler, "json");
	};

	/**
	 * Handle the server's response
	 */
	Preview.messageHandler = function(req_id, resp) {			
		// if outdated, discard
		if (req_id < Preview.shown_id) {
			return;
		}

		Preview.shown_id = req_id;

		// hide the loading screen, if we are all up to date.
		if (!Preview.has_changed) {
			$loader.hide();
		}

		// write messages		
		$text_preview.empty();
		$html_preview.empty();
		Preview.messageHandlerHeaders(resp);
		Preview.messageHandlerText(resp.message.text);
		Preview.messageHandlerHtml(resp.message.html);
	};

	/**
	 * Write out the message headers to the preview panes.
	 */
	Preview.messageHandlerHeaders = function (resp) {
		var $headers,
			$header_from,
			$header_from_label,
			$header_from_val,
			$header_subject,
			$header_subject_label,
			$header_subject_val;

		// header->from->label
		$header_from_label = $(document.createElement("div"));
		$header_from_label.addClass("bbpp-thankmelater-message-preview-hname");
		$header_from_label.text(objectL10n.from);

		// header->from->value
		$header_from_val = $(document.createElement("div"));
		$header_from_val.addClass("bbpp-thankmelater-message-preview-hval");
		$header_from_val.text(resp.from);

		// header->from
		$header_from = $(document.createElement("li"));
		$header_from.append($header_from_label);
		$header_from.append($header_from_val);

		// header->subject->label
		$header_subject_label = $(document.createElement("div"));
		$header_subject_label.addClass("bbpp-thankmelater-message-preview-hname");
		$header_subject_label.text(objectL10n.subject);

		// header->subject->value
		$header_subject_val = $(document.createElement("div"));
		$header_subject_val.addClass("bbpp-thankmelater-message-preview-hval");
		$header_subject_val.text(resp.subject);

		// header->subject
		$header_subject = $(document.createElement("li"));
		$header_subject.append($header_subject_label);
		$header_subject.append($header_subject_val);

		// wrap up the headers into a header list
		$headers = $(document.createElement("ul"));
		$headers.addClass("bbpp-thankmelater-message-preview-headers");
		$headers.append($header_from);
		$headers.append($header_subject);

		// update the text and html previews
		$text_preview.append($headers);
		$html_preview.append($headers.clone());
	};

	/**
	 * Update the plain text message preview pane.
	 */
	Preview.messageHandlerText = function (message) {
		var $message;

		$message = $(document.createElement("pre"));
		$message.addClass("bbpp-thankmelater-message-preview-message-text");
		$message.text(message);

		$text_preview.append($message);
	};

	/**
	 *
	 */
	Preview.messageHandlerHtml = function (message) {
		var $iframe;

		$iframe = $(document.createElement("iframe"));
		$iframe.addClass("bbpp-thankmelater-message-preview-message-html");
		$html_preview.append($iframe);

		$($iframe[0].contentWindow.document).ready(function () {
			var document = $iframe[0].contentWindow.document;
			document.open();
			document.write(message);
			document.close();
		});
	};

	/**
	 * Switch between text/html preview panes
	 * 
	 * type is one of "text", "html".
	 */
	Preview.showPreview = function (type) {
		Preview.selected_type = type;

		if (type === "text") {
			$text_preview.show();
			$html_preview.hide();
		} else {
			$text_preview.hide();
			$html_preview.show();
		}
	};

	// enable update when message, from name, from email or subject changes
	$message.keyup(Preview.messageKeyUp);
	$from_name.keyup(Preview.messageKeyUp);
	$from_email.keyup(Preview.messageKeyUp);
	$subject.keyup(Preview.messageKeyUp);

	// hide the text preview by default
	$html_preview.empty();
	$text_preview.hide();
	$text_preview.empty();

	// enable the html/text selection
	$selector_li.each(function () {
		var $li = $(this);

		$li.find("a").click(function() {
			var $a = $(this);

			$selector_li.removeClass("current");
			$li.addClass("current");
			Preview.showPreview($a.data("type"));

			return false;
		});
	});

	if ($message.length) {
		// load the message initially
		Preview.messageKeyUp();
	}
});

// TODO should neaten this up slightly. The objects above and below share
// almost everything in common. 
jQuery(document).ready(function () {
	"use strict";
		
	var Preview = {},
		$ = jQuery,
		$loader = $("#bbpp-thankmelater-targeting-loading"),
		$multiselect_tags = $("#bbpp-thankmelater-multiselect-tags"),
		$multiselect_categories = $("#bbpp-thankmelater-multiselect-categories"),
		$multiselect_posts = $("#bbpp-thankmelater-multiselect-posts"),
		$summary = $("#bbpp-thankmelater-targeting-summary"),
		objectL10n = bbpp_thankmelater_objectL10n;

	Preview.options = {
		max_hold_time: 4000,
		key_hold_time: 1000
	};
	Preview.selected_type = "text";
	Preview.last_download_time = null;
	Preview.last_keystroke_time = 0;
	Preview.check_timeout = null;
	Preview.req_id = 0;
	Preview.shown_id = 0;
	Preview.request = null;
	Preview.has_changed = true;
	Preview.getTime = function () {
		return (new Date()).getTime();
	};
	Preview.messageKeyUp = function () {
		Preview.last_keystroke_time = Preview.getTime();
		if (!Preview.has_changed) {
			Preview.last_download_time = Preview.last_keystroke_time;
		}
		Preview.has_changed = true;
		$loader.show();
		Preview.messageDownloadCheck();
	};
	Preview.messageDownloadCheckCb = function () {
		Preview.messageDownloadCheck();
	};
	Preview.messageDownloadCheck = function () {
		var now_time = Preview.getTime(),
			wait_time,
			is_key_expired,
			is_max_expired;
		if (Preview.check_timeout) {
			clearTimeout(Preview.check_timeout);
		}
		is_key_expired = (now_time >= Preview.last_keystroke_time + Preview.options.key_hold_time);
		is_max_expired = (now_time >= Preview.last_download_time + Preview.options.max_hold_time);
		if (is_key_expired || is_max_expired) {
			Preview.messageDownload();
			return;
		}
		wait_time = Math.max(
			Math.min(
				Preview.last_keystroke_time + Preview.options.key_hold_time - now_time, 
				Preview.last_download_time + Preview.options.max_hold_time - now_time
			), 
			50
		);
		Preview.check_timeout = setTimeout(Preview.messageDownloadCheckCb, wait_time);
	};
	Preview.messageDownloadCb = function (req_id) {
		return function (resp) {
			Preview.messageHandler(req_id, resp);
		};
	};
	Preview.messageDownload = function () {
		var handler;
		Preview.last_download_time = Preview.getTime();
		Preview.req_id++;
		handler = Preview.messageDownloadCb(Preview.req_id);
		Preview.has_changed = false;
		Preview.request = $.post(ajaxurl, {
			"action": "bbpp_thankmelater_message_targeting",
			"target_tags": Preview.getCheckedOptions($multiselect_tags, "target_tags"),
			"target_categories": Preview.getCheckedOptions($multiselect_categories, "target_categories"),
			"target_posts": Preview.getCheckedOptions($multiselect_posts, "target_posts")
		}, handler, "json");
	};
	Preview.messageHandler = function(req_id, resp) {
		if (req_id < Preview.shown_id) {
			return;
		}
		Preview.shown_id = req_id;
		if (!Preview.has_changed) {
			$loader.hide();
		}
		$summary.text(resp.summary);
	};
	Preview.getCheckedOptions = function($multiselect, name) {
		var vals = [];
		$multiselect.find("input[name='" + name + "[]']:checked").each(function () {
			vals.push($(this).val());
		});
		return vals;
	};
	
	$multiselect_tags.find("input[type=checkbox]").change(Preview.messageKeyUp);
	$multiselect_categories.find("input[type=checkbox]").change(Preview.messageKeyUp);
	$multiselect_posts.find("input[type=checkbox]").change(Preview.messageKeyUp);
	
	if ($multiselect_tags.length || $multiselect_categories.length || $multiselect_posts.length) {
		Preview.messageKeyUp();
	}
});