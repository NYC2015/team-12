<?php

global $bbpp_thankmelater_error;

if (!defined("ABSPATH")) {
	exit;
}

if ($id) {
	$edit_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
		. "&action=edit"
		. "&id=" . urlencode($id);
} else {
	$edit_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
		. "&action=create";
}

$add_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=create";

?>

<div class="wrap">
	
	<h2>
		<?php
			if ($id) {
				echo __("Edit Message", "bbpp-thankmelater");
			} else {
				echo __("New Message", "bbpp-thankmelater");
			}
		?>
		<a href="<?php echo esc_attr($add_url); ?>" class="add-new-h2">
			<?php echo __("Add New", "bbpp-thankmelater"); ?>
		</a>
	</h2>
	
	<?php if ($error): ?>
	<div id="message" class="error">
		<p>
		<?php echo __("Your message was not saved. Correct the errors below.", "bbpp-thankmelater"); ?>
		</p>
	</div>
	<?php endif; ?>
	
	<form id="messages-edit" action="<?php echo esc_attr($edit_url); ?>" method="post">
		<?php wp_nonce_field("bbpp_thankmelater_edit_message_" . intval($id)); ?>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="from_name"><?php echo __("From Name", "bbpp-thankmelater"); ?></label></th>
				<td>
					<input name="from_name" id="from_name" type="text" class="regular-text" 
						   value="<?php echo esc_attr($message->getFromName()); ?>" />
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "from_name"); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="from_email"><?php echo __("From Email", "bbpp-thankmelater"); ?></label></th>
				<td>
					<input name="from_email" id="from_email" type="text" class="regular-text" 
						   value="<?php echo esc_attr($message->getFromEmail()); ?>" />
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "from_email"); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="subject"><?php echo _x("Subject", "noun", "bbpp-thankmelater"); ?></label></th>
				<td>
					<input name="subject" id="subject" type="text" class="regular-text" 
						   value="<?php echo esc_attr($message->getSubject()); ?>" />
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "subject"); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="message"><?php echo _x("Message", "noun", "bbpp-thankmelater"); ?></label></th>
			</tr>
			<tr valign="top">
				<td colspan="2">
					<textarea name="message" id="message" autocomplete="off" rows="10" cols="109"
						class="bbpp-thankmelater-message code"><?php
						echo esc_textarea($message->getMessageBody()); 
					?></textarea>
					<div class="bbpp-thankmelater-message-preview">
						<div class="bbpp-thankmelater-message-preview-inside">
							<h3 class="bbpp-thankmelater-message-preview-title">
								<?php echo _x("Preview", "noun", "bbpp-thankmelater"); ?>
							</h3>
							<ul class="bbpp-thankmelater-message-preview-selector" id="bbpp-thankmelater-message-preview-selector">
								<li class="current"><!--
									--><a href="#" data-type="html"><?php echo _x("HTML", "noun", "bbpp-thankmelater"); ?></a><!--
								--></li><!--
								--><li><!--
									--><a href="#" data-type="text"><?php echo _x("Text", "noun", "bbpp-thankmelater"); ?></a><!--
								--></li>
							</ul>
							<div id="bbpp-thankmelater-message-preview-wrap">
								<div class="bbpp-thankmelater-message-preview-preview" id="bbpp-thankmelater-message-preview-text">
									<?php echo __("Enable Javascript to see this preview.", "bbpp-thankmelater"); ?>
								</div>
								<div class="bbpp-thankmelater-message-preview-preview" id="bbpp-thankmelater-message-preview-html">
								</div>
								<div id="bbpp-thankmelater-message-preview-loading">
									<div id="bbpp-thankmelater-message-preview-loading-bg"></div>
									<div id="bbpp-thankmelater-message-preview-loading-loader"></div>
								</div>
							</div>
						</div>
					</div>
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "message"); ?>
					<p>
						<a href="#" id="bbpp-thankmelater-shortcode-reference-a-show"><?php echo __("Show shortcode list", "bbpp-thankmelater"); ?></a>
						<a href="#" id="bbpp-thankmelater-shortcode-reference-a-hide"><?php echo __("Hide shortcode list", "bbpp-thankmelater"); ?></a>
					</p>
					<div id="bbpp-thankmelater-shortcode-reference">
						<?php require_once BBPP_THANKMELATER_PLUGIN_PATH . "/admin/messages/shortcode_reference.php"; ?>
					</div>
				</td>
			</tr>
		</table>
		
		<h3><?php echo __("When to send emails", "bbpp-thankmelater"); ?></h3>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="min_delay"><?php echo __("Send after time", "bbpp-thankmelater"); ?></label></th>
				<td colspan="2">
					<input name="min_delay" id="min_delay" type="text" class="small-text" 
						   value="<?php echo esc_attr($message->getMinDelay()); ?>" />
					<select name="min_delay_unit" id="min_delay_unit">
						
						<?php $min_delay_unit = $message->getMinDelayUnit(); ?>
						
						<option value="minutes"<?php if ($min_delay_unit == "minutes") { ?> selected="selected"<?php } ?>><?php echo __("minutes", "bbpp-thankmelater"); ?></option>
						<option value="hours"<?php if ($min_delay_unit == "hours") { ?> selected="selected"<?php } ?>><?php echo __("hours", "bbpp-thankmelater"); ?></option>
						<option value="days"<?php if ($min_delay_unit == "days") { ?> selected="selected"<?php } ?>><?php echo __("days", "bbpp-thankmelater"); ?></option>
						<option value="weeks"<?php if ($min_delay_unit == "weeks") { ?> selected="selected"<?php } ?>><?php echo __("weeks", "bbpp-thankmelater"); ?></option>
					</select>
					<p class="description"><?php echo __("The email will be sent this amount of time after the comment is posted.", "bbpp-thankmelater"); ?></p>
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "min_delay"); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="max_sends_per_email"><?php echo __("Send limit", "bbpp-thankmelater"); ?></label></th>
				<td colspan="2">
					<input name="max_sends_per_email" id="max_sends_per_email" type="text" class="small-text" 
						   value="<?php echo esc_attr($message->getMaxSendsPerEmail()); ?>" />
					<p class="description"><?php echo __("The email will be sent no more than this number of times. Enter '0' for no limit.", "bbpp-thankmelater"); ?></p>
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "max_sends_per_email"); ?>
				</td>
			</tr>
		</table>
		
		<h3 id="bbpp-thankmelater-targeting-stitle"><?php echo _x("Targeting", "noun", "bbpp-thankmelater"); ?></h3>
		
		<div id="bbpp-thankmelater-targeting-summary-wrap">
			<div id="bbpp-thankmelater-targeting-summary">
				61236
			</div>
			
			<div id="bbpp-thankmelater-targeting-loading">
				<div id="bbpp-thankmelater-targeting-loading-bg"></div>
				<div id="bbpp-thankmelater-targeting-loading-loader"></div>
			</div>
		</div>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="target_tags"><?php echo _x("Tags", "noun", "bbpp-thankmelater"); ?></label></th>
				<td colspan="2">
					<?php if ($tag_options): ?>
					<div class="bbpp-thankmelater-multiselect" id="bbpp-thankmelater-multiselect-tags">
						<ul>
							<?php 
							
							$target_terms = $message->getTargetTags();
							
							foreach ($tag_options as $tag): ?>
							<li>
								<label>
									<input 
										type="checkbox" 
										name="target_tags[]" 
										value="<?php echo esc_attr($tag->term_id); ?>"
										<?php if (in_array($tag->term_id, $target_terms)) { ?> checked="checked"<?php } ?>>
									<?php echo esc_html($tag->name); ?>
								</label>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<p class="description"><?php echo __("The email will be sent for posts with any of the chosen tags.", "bbpp-thankmelater"); ?></p>
					<?php else: ?>
						<em><?php echo __("Your blog does not have any tags.", "bbpp-thankmelater"); ?></em>
					<?php endif; ?>
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "target_tags"); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="target_tags"><?php echo _x("Categories", "noun", "bbpp-thankmelater"); ?></label></th>
				<td colspan="2">
					<?php if ($category_options): ?>
					<div class="bbpp-thankmelater-multiselect" id="bbpp-thankmelater-multiselect-categories">
						<ul>
							<?php 
							
							$target_terms = $message->getTargetCategories();
							
							foreach ($category_options as $tag): ?>
							<li>
								<label>
									<input 
										type="checkbox" 
										name="target_categories[]" 
										value="<?php echo esc_attr($tag->term_id); ?>"
										<?php if (in_array($tag->term_id, $target_terms)) { ?> checked="checked"<?php } ?>>
									<?php echo esc_html($tag->name); ?>
								</label>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<p class="description"><?php echo __("The email will be sent for posts in any of the chosen categories.", "bbpp-thankmelater"); ?></p>
					<?php else: ?>
						<em><?php echo __("Your blog does not have any categories.", "bbpp-thankmelater"); ?></em>
					<?php endif; ?>
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "target_categories"); ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="target_posts"><?php echo _x("Posts", "noun", "bbpp-thankmelater"); ?></label></th>
				<td colspan="2">
					<?php if ($post_options): ?>
					<div class="bbpp-thankmelater-multiselect" id="bbpp-thankmelater-multiselect-posts">
						<ul>
							<?php 
							
							$target_posts = $message->getTargetPosts();
							
							foreach ($post_options as $post): ?>
							<li>
								<label>
									<input 
										type="checkbox" 
										name="target_posts[]" 
										value="<?php echo esc_attr($post->ID); ?>"
										<?php if (in_array($post->ID, $target_posts)) { ?> checked="checked"<?php } ?>>
									<?php echo esc_html($post->post_title); ?>
								</label>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<p class="description"><?php echo __("The email will be sent for any chosen posts.", "bbpp-thankmelater"); ?></p>
					<?php else: ?>
						<em><?php echo __("Your blog does not have any posts.", "bbpp-thankmelater"); ?></em>
					<?php endif; ?>
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "target_categories"); ?>
				</td>
			</tr>
		</table>
		
		<h3><?php echo _x("Tracking", "noun", "bbpp-thankmelater"); ?></h3>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="track_opens"><?php echo __("Track opens", "bbpp-thankmelater"); ?></label></th>
				<td colspan="2">
					<label>
						<input type="checkbox" name="track_opens" value="1"<?php if ($message->getTrackOpens()) { echo " checked=\"checked\""; } ?>>
						<?php echo __("Track number of times email is opened.", "bbpp-thankmelater"); ?>
					</label>
					<p class="description"><?php echo __("You can view open statistics on the 'Stats' page.", "bbpp-thankmelater"); ?></p>
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($error, "track_opens"); ?>
				</td>
			</tr>
		</table>
		
		<?php
		
			submit_button(__("Save Message", "bbpp-thankmelater"));
		
		?>
	</form>
	
</div>