<?php

if (!defined("ABSPATH")) {
	exit;
}

$url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=options";

?>

<script type="text/javascript">
jQuery(document).ready(function () {
	jQuery("#bbpp-thankmelater-opt-out-form").hide();
	jQuery("#bbpp-thankmelater-opt-out-form-message").hide();
	
	var update_view = function() {
		var opt_out_level = jQuery("#bbpp_thankmelater_opt_out input[name=bbpp_thankmelater_opt_out_level]:checked").val(),
			duration = 200,
			opt_out_form_type;
		
		if (opt_out_level == "form") {
			opt_out_form_type = jQuery("#bbpp_thankmelater_opt_out input[name=bbpp_thankmelater_opt_out_form_type]:checked").val();
			
			jQuery("#bbpp-thankmelater-opt-out-form").show(duration);
			jQuery("#bbpp-thankmelater-opt-out-form-message").show(duration);
			
			if (opt_out_form_type == "out") {
				jQuery("#bbpp-thankmelater-opt-out-form-out").show(duration);
				jQuery("#bbpp-thankmelater-opt-out-form-in").hide(duration);
			} else if (opt_out_form_type == "in") {
				jQuery("#bbpp-thankmelater-opt-out-form-in").show(duration);
				jQuery("#bbpp-thankmelater-opt-out-form-out").hide(duration);
			}
		} else {
			jQuery("#bbpp-thankmelater-opt-out-form").hide(duration);
			jQuery("#bbpp-thankmelater-opt-out-form-message").hide(duration);
		}
	};
	
	jQuery("#bbpp_thankmelater_opt_out input").click(function() {
		update_view();
	});
	
	update_view();
});
</script>

<div class="wrap">
	<h2>
		<?php
			echo _x("Opt out options", "noun", "bbpp-thankmelater");
		?>
	</h2>
	
	<?php if ($errors): ?>
	<div id="message" class="error">
		<p>
		<?php echo __("Your options were not saved. Correct the errors below.", "bbpp-thankmelater"); ?>
		</p>
	</div>
	<?php endif; ?>
	
	<?php if ($success): ?>
	<div id="message" class="updated">
		<p>
		<?php echo __("Your options were saved.", "bbpp-thankmelater"); ?>
		</p>
	</div>
	<?php endif; ?>
	
	<form id="bbpp_thankmelater_opt_out" action="<?php echo esc_attr($url); ?>" method="post">
		<?php wp_nonce_field("bbpp_thankmelater_opt_out_options"); ?>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php echo __("Level", "bbpp-thankmelater"); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php echo __("Level", "bbpp-thankmelater"); ?></span></legend>
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_level"
								value="disabled"
								<?php if ($opt_out_level == "disabled") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Disabled", "bbpp-thankmelater"); ?></span>
						</label><br />
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_level"
								value="email"
								<?php if ($opt_out_level == "email") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Show opt out link in email (recommended)", "bbpp-thankmelater"); ?></span>
						</label><br />
						
						<!--<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_level"
								value="form"
								<?php if ($opt_out_level == "form") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Give readers a choice on the comment form", "bbpp-thankmelater"); ?></span>
						</label>-->
					</fieldset>
					
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($errors, "opt_out_level"); ?>
				</td>
			</tr>
			<tr valign="top" id="bbpp-thankmelater-opt-out-form">
				<th scope="row"><?php echo __("Comment form choice", "bbpp-thankmelater"); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php echo __("Comment form choice", "bbpp-thankmelater"); ?></span></legend>
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_form_type"
								value="out"
								<?php if ($opt_out_form_type == "out") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Ask readers to opt out (recommended)", "bbpp-thankmelater"); ?></span>
						</label><br />
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_form_type"
								value="in"
								<?php if ($opt_out_form_type == "in") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Ask readers to opt in", "bbpp-thankmelater"); ?></span>
						</label>
					</fieldset>
					
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($errors, "opt_out_form_type"); ?>
				</td>
			</tr>
			<tr valign="top" id="bbpp-thankmelater-opt-out-form-message">
				<th scope="row"><?php echo __("Choice message", "bbpp-thankmelater"); ?></th>
				<td>
					<fieldset id="bbpp-thankmelater-opt-out-form-out">
						<legend class="screen-reader-text"><span><?php echo __("Choice message", "bbpp-thankmelater"); ?></span></legend>
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_form_out_text"
								value="1"
								<?php if ($opt_out_form_out_text == "1") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Do not email me about this comment", "bbpp-thankmelater"); ?></span>
						</label><br />
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_form_out_text"
								value="custom"
								<?php if ($opt_out_form_out_text == "custom") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Custom:", "bbpp-thankmelater"); ?></span>
						</label>
						
						<input type="text"
							   name="bbpp_thankmelater_opt_out_form_out_text_custom"
							   class="regular-text"
							   value="<?php echo esc_attr($opt_out_form_out_text_custom); ?>"
							   />
					</fieldset>
					
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($errors, "opt_out_form_out_text"); ?>
					
					<fieldset id="bbpp-thankmelater-opt-out-form-in">
						<legend class="screen-reader-text"><span><?php echo __("Choice message", "bbpp-thankmelater"); ?></span></legend>
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_form_in_text"
								value="1"
								<?php if ($opt_out_form_in_text == "1") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("You can email me about this comment", "bbpp-thankmelater"); ?></span>
						</label><br />
						
						<label>
							<input 
								type="radio" 
								name="bbpp_thankmelater_opt_out_form_in_text"
								value="custom"
								<?php if ($opt_out_form_in_text == "custom") { ?> checked="checked"<?php } ?>
								/>
							
							<span><?php echo __("Custom:", "bbpp-thankmelater"); ?></span>
						</label>
						
						<input type="text"
							   name="bbpp_thankmelater_opt_out_form_in_text_custom"
							   class="regular-text"
							   value="<?php echo esc_attr($opt_out_form_in_text_custom); ?>"
							   />
					</fieldset>
					
					<?php Bbpp_ThankMeLater_ErrorHelper::show_error($errors, "opt_out_form_in_text"); ?>
				</td>
			</tr>
		</table>
		
		<?php
		
			submit_button(__("Save Options", "bbpp-thankmelater"));
		
		?>
	</form>
	
	<?php if ($opt_out_results): ?>
	
	<h3><?php echo __("Opt out list", "bbpp-thankmelater"); ?></h3>
	
	<p><?php echo __("The following email addresses have opted out of your thank you emails:", "bbpp-thankmelater"); ?></p>
	
	<ul>
		<?php foreach ($opt_out_results as $row): ?>
		<li>
			<?php echo esc_html($row->email); ?> (<a href="<?php 
				
				$delete_url = wp_nonce_url(sprintf(
					"?page=%s&action=delete&id=%s",
					urlencode(stripslashes($_REQUEST['page'])),
					urlencode($row->email)
				), "bbpp_thankmelater_opt_out_delete");
				
				echo $delete_url;
			
			?>"><?php echo __("Remove email", "bbpp-thankmelater"); ?></a>)
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php endif; ?>
</div>