<?php

if (!defined("ABSPATH")) {
	exit;
}

?>

<script type="text/javascript">
var bbpp_thankmelater_sends_data = <?php echo json_encode($day_stats); ?>;
var bbpp_thankmelater_opens_data = <?php echo json_encode($open_stats); ?>;
</script>


<div class="wrap">
	
	<h2>
		<?php
			echo _x("Stats", "noun", "bbpp-thankmelater");
		?>
	</h2>
	
	<div id="bbpp-thankmelater-stats-summary">
		<ul>
			<li>
				<h3 class="bbpp-thankmelater-stats-descriptor"><?php echo __("total emails sent", "bbpp-thankmelater"); ?></h3>
				<div class="bbpp-thankmelater-stats-num"><?php echo number_format_i18n($total_num_sent); ?></div>
			</li>
			<li>
				<h3 class="bbpp-thankmelater-stats-descriptor"><?php echo __("total opens", "bbpp-thankmelater"); ?></h3>
				<div class="bbpp-thankmelater-stats-num"><?php echo number_format_i18n($total_num_opened); ?></div>
			</li>
			<li>
				<h3 class="bbpp-thankmelater-stats-descriptor"><?php echo __("due to be sent", "bbpp-thankmelater"); ?></h3>
				<div class="bbpp-thankmelater-stats-num"><?php echo number_format_i18n($total_num_scheduled); ?></div>
			</li>
		</ul>
	</div>
	
	<h3><?php echo __("Emails sent in the last 30 days", "bbpp-thankmelater"); ?></h3>

	<div id="bbpp-thankmelater-sends-graph">
		
	</div>
	
	<h3><?php echo __("Emails opened in the last 30 days", "bbpp-thankmelater"); ?></h3>

	<div id="bbpp-thankmelater-opens-graph" class="bbpp-thankmelater-stats-graph">
		
	</div>
	
</div>