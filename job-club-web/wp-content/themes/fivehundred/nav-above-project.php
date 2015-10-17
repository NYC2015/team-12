<?php 
global $crowdfunding, $backer_list, $post;
$project_id = get_post_meta($post->ID, 'ign_project_id', true);
$updates = apply_filters('fivehundred_updates', do_shortcode( '[project_updates product="'.$project_id.'"]'));
$faqs = apply_filters('fivehundred_faq', do_shortcode( '[project_faq product="'.$project_id.'"]'));
?>
<ul class="content_tabs">
    <li id="description_tab" class="active"><span><?php _e('Description', 'fivehundred'); ?></span></li>
    <?php if (!empty($updates)) { ?>
    <li id="updates_tab"><span><?php _e('Updates', 'fivehundred'); ?></span></li>
    <?php } ?>
    <?php if (!empty($faqs)) { ?>
    <li id="faq_tab"><span><?php _e('FAQ', 'fivehundred'); ?></span></li>
    <?php } ?>
    <?php if ($post->comment_status == 'open') { ?>
    <li id="comments_tab"><span><?php _e('Comments', 'fivehundred'); ?></span></li>
    <?php } ?>
    <?php if ($crowdfunding) { ?>
    	<li id="backers_tab"><span><?php _e('Backers', 'fivehundred'); ?></span></li>
    <?php } ?>
</ul>