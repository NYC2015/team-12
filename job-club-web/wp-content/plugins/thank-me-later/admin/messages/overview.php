<?php

if (!defined("ABSPATH")) {
	exit;
}

$add_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=create";

?>

<div class="wrap">
	
	<h2>
		<?php
			echo _x("Messages", "noun", "bbpp-thankmelater");
		?>
		<a href="<?php echo esc_attr($add_url); ?>" class="add-new-h2">
			<?php echo __("Add New", "bbpp-thankmelater"); ?>
		</a>
	</h2>
	
	<?php if ($deleted): ?>
	<div id="message" class="updated">
		<p>
		<?php echo sprintf(_n("Message deleted.", "%d messages deleted.", count($deleted), "bbpp-thankmelater"), count($deleted)); ?>
		</p>
	</div>
	<?php endif; ?>
	
	<?php if ($edited): ?>
	<div id="message" class="updated">
		<p>
		<?php echo sprintf(_n("Message updated!", "%d messages updated!", count($edited), "bbpp-thankmelater"), count($edited)); ?>
		</p>
	</div>
	<?php endif; ?>
	
	<form id="messages-filter" action="" method="get">
		<input type="hidden" name="page" value="<?php echo esc_attr(stripslashes($_REQUEST["page"])); ?>" />
		
		<?php echo $message_list_table->display(); ?>
	</form>
	
	<hr />
	
	<p><?php echo sprintf(
			__("Created by %s", "bbpp-thankmelater"),
			'<a href="http://twitter.com/brendonboshell">@brendonboshell</a>'
		); ?> 
		- <a href="http://eepurl.com/tb8hf"><?php echo __("Mailing List", "bbpp-thankmelater"); ?></a>		
		- <a href="http://wordpress.org/support/view/plugin-reviews/thank-me-later"><?php echo __("Rate This Plugin", "bbpp-thankmelater"); ?></a>
		- <a href="http://wordpress.org/support/plugin/thank-me-later"><?php echo __("Help", "bbpp-thankmelater"); ?></a>
	</p>
	
</div>