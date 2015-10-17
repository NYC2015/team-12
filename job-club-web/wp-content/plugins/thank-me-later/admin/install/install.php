<?php

if (!defined("ABSPATH")) {
	exit;
}

$url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=install";
$continue_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=continue";

?>

	
	<div id="bbpp-thankmelater-install">
	
		<h2 class="bbpp-thankmelater-install-message">
			<?php
				echo __("Install Thank Me Later 3.3", "bbpp-thankmelater");
			?>
		</h2>

		<h3><?php echo __("Email open tracking", "bbpp-thankmelater"); ?></h3>
		
		<p><?php echo __("This version of Thank Me Later introduces email open tracking.", "bbpp-thankmelater"); ?></p>
		
		<ul>
			<li><?php echo __("View number of opened Thank Me Later emails from the 'Stats' page.", "bbpp-thankmelater"); ?></li>
			<li><?php echo __("You can enable and disable tracking from each message's options page.", "bbpp-thankmelater"); ?></li>
			<li><?php echo __("You should notify commenters that your emails may contain tracking code.", "bbpp-thankmelater"); ?></li>
		</ul>
		
		<p><?php echo __("Please decide whether you would like to enable email tracking.", "bbpp-thankmelater"); ?></p>
		
		
		<form id="bbpp_thankmelater_install" action="<?php echo esc_attr($url); ?>" method="post">
		
			<?php wp_nonce_field("bbpp_thankmelater_install"); ?>
			<?php submit_button(__("Enable Email Open Tracking", "bbpp-thankmelater"), "primary", "submit", false); ?>
			<a class="button" href="<?php echo esc_attr($continue_url); ?>">Do not enable</a> 
		</form>
	
	</div>