<?php
$settings = get_option('fivehundred_theme_settings');
if (!empty($settings['logo'])) {
	$logo = $settings['logo'];
}
?>

<div id="branding">
	<div id="site-title">
		<a href="<?php echo home_url(); ?>/" title="<?php bloginfo( 'name' ); ?>" rel="home">
		<?php if ( !is_singular() ) {
			echo '<h1>';
		} ?>
			<?php echo (!empty($logo) ? '<img id="logo" src="'.$logo.'"/>' : bloginfo( 'name' )); ?>
			<?php if ( !is_singular() ) {
				echo '</h1>';
			} ?>
		</a>
	</div>
</div>