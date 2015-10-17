<?php while ( $query->have_posts() ) {
	$query->the_post();
	$post_id = $query->post->ID;
	print_r($query->post);
	// Getting the project ID
	$project_id = get_post_meta($post_id, 'ign_project_id', true);
	$deck = new Deck($project_id);

	$the_deck = $deck->the_deck();
?>
<div class="ignitiondeck id-widget id-full" data-projectid="<?php echo (isset($project_id)? $project_id : ''); ?>">
	<div class="id-product-infobox">
		<div class="product-wrapper">
			<div class="pledge">
				<?php  if (!$custom || ($custom && isset($attrs['project_title']))) { ?>
					<h2 class="id-product-title"><a href="<?php echo getProjectURLfromType($project_id); ?>"><?php echo stripslashes(get_the_title($the_deck->post_id));?></a></h2>
				<?php } ?>
				<?php if (!$custom || ($custom && isset($attrs['project_bar']))) { ?>
				<div class="progress-wrapper">
					<div class="progress-percentage"> <?php echo number_format(apply_filters('id_percentage_raised', $the_deck->rating_per, $the_deck->p_current_sale, $the_deck->post_id, $the_deck->project->goal));?>% </div>
					<div class="progress-bar" style="width: <?php echo apply_filters('id_percentage_raised', $the_deck->rating_per, $the_deck->p_current_sale, $the_deck->post_id, $the_deck->project->goal); ?>%"> 
					</div>
					<!-- end progress bar --> 
				</div>
				<!-- end progress wrapper --> 
				<?php } ?>
			</div>
			
			<!-- end pledge -->
			
			<div class="clearing"></div>
			<?php if (!$custom || ($custom && isset($attrs['project_pledged']))) { ?>
				<div class="id-progress-raised"> <?php echo apply_filters('id_funds_raised', $the_deck->p_current_sale, $the_deck->post_id) ?> </div>
			<?php } ?>
			<?php if (!$custom || ($custom && isset($attrs['project_goal']))) { ?>
				<div class="id-product-funding"><?php _e('Pledged of', 'fivehundred'); ?> <?php echo apply_filters('id_project_goal', $the_deck->project->goal, $the_deck->post_id) ?> <?php _e('goal', 'fivehundred'); ?></div>
			<?php } ?>
			<?php if (!$custom || ($custom && isset($attrs['project_pledgers']))) { ?>
				<div class="id-product-total"><?php echo number_format(apply_filters('id_number_pledges', (($the_deck->p_count->p_number != "" || $the_deck->p_count->p_number != 0) ? $the_deck->p_count->p_number : '0'), $the_deck->post_id));?></div>
				<div class="id-product-pledges"><?php _e('Pledgers', 'fivehundred'); ?></div>
			<?php } ?>
			<?php if (!$custom || ($custom && isset($attrs['days_left']))) { ?>
				<?php if (isset($the_deck->days_left) && $the_deck->days_left > 0) { ?>
					<div class="id-product-days"><?php echo (($the_deck->days_left !== "" || $the_deck->days_left !== 0) ? $the_deck->days_left : '0'); ?></div>
					<div class="id-product-days-to-go"><?php _e('Days to go', 'fivehundred'); ?></div>
				<?php } ?>
			<?php } ?>
		</div>
		
		<!-- end product-wrapper -->	
		<?php if (!$custom || ($custom && isset($attrs['project_end']))) { ?>
			<?php if ($the_deck->item_fund_end !== '') { ?>	
			<div class="id-product-proposed-end"><?php echo ($the_deck->days_left > 0 ? __('Funded on', 'fivehundred') : __('Ended on', 'fivehundred')); ?>
				<div class="id-widget-date">
					<div class="id-widget-month"><?php echo $the_deck->month; ?></div>
					<div class="id-widget-day"><?php echo $the_deck->day; ?></div>
					<div class="id-widget-year"><?php echo $the_deck->year; ?></div>
				</div>
			</div>
			<?php } ?>
		<?php } ?>
		<div class="separator">&nbsp;</div>
		<?php if (!$custom || ($custom && isset($attrs['project_description']))) { ?>
			<!-- Project description -->
			<div class="id-product-description"><?php echo $the_deck->project_desc; ?></div>
			<!-- end id product description -->
		<?php } ?>
		<?php if (!$custom || ($custom && isset($attrs['project_levels']))) {
			$url = getPurchaseURLFromType($project_id, 'purchaseform');
			$level_invalid = getLevelLimitReached($project_id, $the_deck->post_id, 1);
		?>
		<!--Product Levels-->
			<div class="id-product-levels">
				<?php
				if ($the_deck->disable_levels !== "on") { ?>
					<?php foreach ($the_deck->level_data as $level) { 
						if (!is_id_licensed()) {
							$level->level_invalid = 1;
						}
						if (isset($the_deck->end_type) && $the_deck->end_type == 'closed') {
							if (isset($the_deck->days_left) && $the_deck->days_left > 0) {
					?>
							<a class="level-binding" <?php echo (!isset($level->level_invalid) || $level->level_invalid ? '' : 'href="'.apply_filters('id_level_'.$level->id.'_link', $url.'&level='.$level->id, $project_id).'"'); ?>>
					<?php
							}
							else { ?>
								<a class="level-binding" <?php echo (isset($level->level_invalid) && $level->level_invalid ? '' : ''); ?>>
							<?php 
							}
						}
						else { ?>
							<a class="level-binding" <?php echo (!isset($level->level_invalid) || $level->level_invalid ? '' : 'href="'.apply_filters('id_level_'.$level->id.'_link', $url.'&level='.$level->id, $project_id).'"'); ?>>
						<?php
						}
					?>
						<div class="level-group">
							<div class="id-level-title"><span><?php echo (isset($level->meta_title) ? strip_tags(stripslashes($level->meta_title)) : __('level', 'fivehundred').' '.($level->id)); ?>:</span> <?php echo (isset($level->meta_price) && $level->meta_price > 0 ? apply_filters('id_price_selection', $level->meta_price, $the_deck->post_id) : ''); ?></div>
							<div class="id-level-desc"><?php echo html_entity_decode(stripslashes($level->meta_desc)); ?></div>
						<?php echo (!empty($level->meta_limit) ? '<div class="id-level-counts"><span>'. __('Limit', 'fivehundred') .': '.$level->meta_count .' '.__('of', 'fivehundred').' '.$level->meta_limit.' '.__('taken', 'fivehundred').'</span></div>' : ''); ?>
						<?php echo do_action('id_after_level'); ?>
						</div>
							</a>
					<?php
					}
				} ?>
			</div>
			<!-- end product levels -->
		<?php } ?>
	</div>
</div>
<?php
}
?>