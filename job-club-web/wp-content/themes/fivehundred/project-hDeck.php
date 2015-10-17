<?php
global $post;
$id = $post->ID;
//$hDeck = the_project_hDeck($id);
$project_id = get_post_meta($id, 'ign_project_id', true);
if (class_exists('Deck')) {
	$new_hdeck = new Deck($project_id);
	if (method_exists($new_hdeck, 'hDeck')) {
		$hDeck = $new_hdeck->hDeck();
	}
	else {
		$hDeck = the_project_hDeck($id);
	}
	$permalinks = get_option('permalink_structure');
	$summary = the_project_summary($id);
	do_action('fh_hDeck_before');
	$video = the_project_video($id);
}
?>
<?php if (isset($hDeck)) { ?>
<div id="ign-hDeck-wrapper">
	<div id="ign-hdeck-wrapperbg">
		<div id="ign-hDeck-header">
			<div id="ign-hDeck-left">
				<div class="video <?php echo (!empty($video) ? 'hasvideo' : ''); ?>" style="background-image: url(<?php echo $summary->image_url; ?>)"><?php echo $video; ?> </div>
				<div id="ign-hDeck-social">
					<?php get_template_part('project', 'social'); ?>
				</div>
			</div>
			<div id="ign-hDeck-right">
				<div class="internal">
					<div class="ign-product-goal" style="clear: both;">
						<div class="ign-goal"><?php _e('Goal', 'fivehundred'); ?></div> <strong><?php echo $hDeck->goal; ?> </strong>
					</div>
					<?php //if (isset($hDeck->show_dates) && $hDeck->show_dates == true) { ?>
					<?php //if (isset($hDeck->days_left) && $hDeck->days_left > 0) { ?>
					<div class="ign-days-left">
						<strong><?php echo $hDeck->days_left; ?> <?php _e('Days Left', 'fivehundred'); ?></strong>
					</div>
					<?php //} ?>
					<div class="ign-progress-wrapper" style="clear: both;">
						<div class="ign-progress-percentage">
										<?php echo $hDeck->percentage; ?>%
						</div> <!-- end progress-percentage -->
						<div style="width: <?php echo $hDeck->percentage; ?>%" class="ign-progress-bar">
						
						</div><!-- end progress bar -->
					</div>
					
					<div class="ign-progress-raised">
						<strong><?php echo $hDeck->total; ?></strong>
						<div class="ign-raised">
							<?php _e('Raised', 'fivehundred'); ?>
						</div>
					</div>
					<div class="ign-product-supporters" style="clear: both;">
						<strong><?php echo $hDeck->pledges; ?></strong>
						<div class="ign-supporters">
							<?php _e('Supporters', 'fivehundred'); ?>
						</div>
					</div>
					<div id="hDeck-right-bottom">
						<div class="ign-supportnow" data-projectid="<?php echo $project_id; ?>">
							<?php if ($hDeck->end_type == 'closed' && $hDeck->days_left <= 0) {?>
							<a href="" class=""><?php _e('Project Closed', 'fivehundred'); ?></a>
							<?php }else {?>
							<?php if (function_exists('is_id_licensed') && is_id_licensed()) { ?>
								<?php if (empty($permalinks) || $permalinks == '') { ?>
									<a href="<?php the_permalink(); ?>&purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>"><?php _e('Support Now', 'fivehundred'); ?></a>
								<?php }
								else { ?>
							 		<a href="<?php the_permalink(); ?>?purchaseform=500&amp;prodid=<?php echo (isset($project_id) ? $project_id : ''); ?>"><?php _e('Support Now', 'fivehundred'); ?></a>
								<?php } ?>
							<?php } ?>
						<?php }?>
						</div>
						<?php //if (isset($hDeck->show_dates) && $hDeck->show_dates == true) { ?>
						<?php //if (isset($hDeck->days_left) && $hDeck->days_left > 0) { ?>
						<div class="ign-product-proposed-end"><span><?php _e('Project Ends', 'fivehundred'); ?>:</span>
							<div id="ign-widget-date">
								<div id="ign-widget-month"><?php _e($hDeck->month, 'fivehundred'); ?></div>
								<div id="ign-widget-day"><?php _e($hDeck->day, 'fivehundred'); ?></div>
								<div id="ign-widget-year"><?php _e($hDeck->year, 'fivehundred'); ?></div>
							</div>
							<div class="clear"></div>
						</div>
						<?php //} ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>
<?php } 
else { ?>
<div id="ign-hDeck-wrapper">
	<div id="ign-hdeck-wrapperbg">
		<div id="ign-hDeck-header">
			<div id="ign-hDeck-left">
			</div>
			<div id="ign-hDeck-right">
				<div class="internal">
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>