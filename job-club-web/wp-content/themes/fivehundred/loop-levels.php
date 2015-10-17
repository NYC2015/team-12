<?php
global $post;
$id = $post->ID;
$project_id = get_post_meta($id, 'ign_project_id', true);
if (class_exists('Deck')) {
	$deck = new Deck($project_id);
	$the_deck = $deck->the_deck();
	$levels = $the_deck->level_data;
	//$levels = the_levels($id);
	$type = get_post_meta($id, 'ign_project_type', true);
	$end_type = get_post_meta($id, 'ign_end_type', true);
	$project = new ID_Project($project_id);
	$days_left = $project->days_left();
	$permalink_structure = get_option('permalink_structure');
	if (empty($permalink_structure)) {
		$url_suffix = '&';
	}
	else {
		$url_suffix = '?';
	}
	$url = get_permalink($id).$url_suffix.'purchaseform=500&prodid='.$project_id;//getPurchaseURLfromType($project_id, 'purchaseform');
	/*$custom_order = get_post_meta($id, 'custom_level_order', true);
	if ($custom_order) {
		usort($levels, 'fh_level_sort');
	}*/

	foreach ($levels as $level) {
		$level_invalid = getLevelLimitReached($project_id, $id, $level->id);
		if (!function_exists('is_id_licensed') || !is_id_licensed()) {
			$level_invalid = 1;
		}

		?>
		<?php if (empty($type) || $type == 'level-based') {
			if ($end_type == 'closed' && $days_left <= '0') { ?>
				<a class="level-binding">
			<?php
			} 
			else {
			?>
				<a class="level-binding" <?php echo (isset($level_invalid) && $level_invalid ? '' : 'href="'.apply_filters('id_level_'.$level->id.'_link', $url.'&level='.$level->id, $project_id).'"'); ?>>
		<?php 
			}
		} ?>
			<div class="level-group">
				<div class="ign-level-title">
					<span> <?php echo $level->meta_title ?></span>
					<div class="level-price">
						<?php if ($type !== 'pwyw' && $level->meta_price > 0) { ?>
							<?php echo apply_filters('id_price_selection', $level->meta_price, $id); ?>
						<?php } ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="ign-level-desc">
					<?php echo $level->meta_short_desc; ?>
				</div>
			
				<?php if ($level->meta_limit !== '' && $level->meta_limit > 0) { ?>
				<div class="ign-level-counts">
					<span><?php _e('Limit', 'fivehundred'); ?>: <?php echo $level->meta_count; ?> of <?php echo $level->meta_limit; ?> <?php _e('taken', 'fivehundred'); ?>.</span>
				</div>
				<?php } ?>
				<?php echo do_action('id_after_level'); ?>
			</div>
		<?php if (empty($type) || $type == 'level-based') { ?>
			</a>
		<?php } ?>
	<?php }
	}
?>