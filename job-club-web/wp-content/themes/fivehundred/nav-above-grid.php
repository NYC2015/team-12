<?php // no content here ?>
<?php
if (isset($_GET['project_filter'])) {
	$filter = $_GET['project_filter'];
}
if (isset($_GET['order'])) {
	$order = $_GET['order'];
}
else {
	$order = 'DESC';
}
?>
<div class="ignitiondeck grid-header">
	<ul class="filter-menu" data-order="<?php echo $order; ?>">
		<li><strong><?php _e('Sort By', 'fivehundred'); ?>:</strong></li>
		<li class="filter_choice"><a href="?project_filter=date<?php echo ($order == 'ASC' ? '&amp;order=DESC' : '&amp;order=ASC'); ?>" class="<?php echo (isset($filter) && $filter == 'date' ? 'active' : ''); ?>"><?php echo ($order == 'DESC' ? __('Oldest', 'fivehundred') : __('Newest', 'fivehundred')); ?></a></li>
		<li class="filter_choice"><a href="?project_filter=ign_fund_raised<?php echo ($order == 'ASC' ? '&amp;order=DESC' : '&amp;order=ASC'); ?>" class="<?php echo (isset($filter) && $filter == 'ign_fund_raised' ? 'active' : ''); ?>"><?php _e('Amount Raised', 'fivehundred'); ?></a></li>
		<li class="filter_choice"><a href="?project_filter=ign_days_left<?php echo ($order == 'ASC' ? '&amp;order=DESC' : '&amp;order=ASC'); ?>" class="<?php echo (isset($filter) && $filter == 'ign_days_left' ? 'active' : ''); ?>"><?php _e('Days Left', 'fivehundred'); ?></a></li>
		<li class="filter_choice"><a href="?project_filter=ign_fund_goal<?php echo ($order == 'ASC' ? '&amp;order=DESC' : '&amp;order=ASC'); ?>" class="<?php echo (isset($filter) && $filter == 'ign_fund_goal' ? 'active' : ''); ?>"><?php _e('Goal Amount', 'fivehundred'); ?></a></li>
		<?php 
		$args = array('type' => 'ignition_project', 'hide_empty' => 1, 'taxonomy' => 'project_category');
		$cats = get_categories($args);
		if (!empty($cats)) { ?>
		<li class="filter_submenu"><span><?php _e('Category', 'fivehundred'); ?></span>
			<ul class="filter-dd">
				<li class="filter_choice"><a href="?id_category="><?php _e('All Categories', 'fivehundred'); ?></a></li>
				<?php
				foreach ($cats as $cat) {
					echo '<li class="filter_choice"><a href="?id_category='.$cat->slug.'">'.$cat->name.'</a></li>';
				}
				?>
			</ul>
		</li>
		<?php } ?>
	</ul>
</div>