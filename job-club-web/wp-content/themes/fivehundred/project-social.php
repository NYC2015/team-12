<?php
// This is an ID native function
global $post;
$post_id = $post->ID;
$project_id = get_post_meta($post_id, 'ign_project_id', true);
if (empty($project_id)) {
	$settings = get_option('fivehundred_theme_settings');
	if (isset($settings['home']) && $settings['home'] > 0) {
		$project_id = $settings['home'];
	}
}
// Social settings from IDSocial
$social_settings = get_option('idsocial_settings');
if (!empty($social_settings)) {
	if (isset($social_settings['social_checks'])) {
		$social_checks = $social_settings['social_checks'];
		$settings = (object) $social_checks;
		if (isset($settings->prod_page_fb) && $settings->prod_page_fb) {
			echo '<div id="fb-root"></div><div id="share-fb" class="fb-like social-share social-button" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>';
		}
		if (isset($settings->prod_page_twitter) && $settings->prod_page_twitter) {
			//$post_output .= '<div style="float:right;"><a href="http://twitter.com/share" target="_new" class="twitter-share-button button twitter" data-count="vertical">tweet</a></div>';
			echo '<div id="share-twitter" class="social-share social-button"><a href="https://twitter.com/share" class="twitter-share-button">'.__('Tweet', 'fivehundred').'</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>';
		}
		if (isset($settings->prod_page_linkedin) && $settings->prod_page_linkedin) {
			
			echo '<div id="share-linkedin" class="social-share social-button"><script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
			<script type="IN/Share"></script></div>';
		}
		if (isset($settings->prod_page_google) && $settings->prod_page_google) {
			
			echo '<div id="share-google" class="social-share social-button"><script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script><g:plusone size="medium"></g:plusone></div>';
		}
		if (isset($settings->prod_page_pinterest) && $settings->prod_page_pinterest) {
			
			echo '<div id="share-pinterest" class="social-share social-button"><a href="http://pinterest.com/pin/create/button/?url='.currentPageURL().'&media='.ID_Project::get_project_thumbnail($post_id).'" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a><script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script></div>';
		}
	}
}
?>
<div id="share-embed" class="social-share"><i class="fa fa-code"></i></div>
<div class="embed-box social-share" style="display: none;">
	<code>&#60;iframe frameBorder="0" scrolling="no" src="<?php echo home_url(); ?>/?ig_embed_widget=1&product_no=<?php echo (isset($project_id) ? $project_id : ''); ?>" width="214" height="366"&#62;&#60;/iframe&#62;</code>
</div>
<div class="clear"></div>