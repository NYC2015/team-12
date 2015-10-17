<?php

if (!defined("ABSPATH")) {
	exit;
}

$overview_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=overview";

?>

<div class="wrap">
	
	<h2>
		<?php
			echo _x("Target Posts", "noun", "bbpp-thankmelater");
		?>
	</h2>
	
	<p><a href="<?php echo esc_attr($overview_url); ?>"><?php echo __("Return to messages", "bbpp-thankmelater"); ?></a></p>
	
	<p><?php echo __("The following posts are targeted by this message:", "bbpp-thankmelater"); ?></p>
	
	<ul>
		<?php foreach ($posts as $id): ?>
		<li><?php 
		
		$post = get_post($id);
		
		echo sprintf(
			__("%s (ID: %d)", "bbpp-thankmelater"),
			esc_html($post->post_title),
			esc_html($id)
		);
		
		?></li>
		<?php endforeach; ?>
	</ul>
	
</div>