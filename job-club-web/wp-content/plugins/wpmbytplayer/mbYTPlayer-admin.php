<?php

// create the admin menu
function add_mbYTPlayer_option_page() {
    // hook in the options page function
    add_options_page('mbYTPlayer', 'mb.YTPlayer', 'manage_options', __FILE__, 'mbYTPlayer_options_page');
    add_action( 'admin_init', 'register_YTPlayerSettings' );
}
// hook in the action for the admin options page
add_action('admin_menu', 'add_mbYTPlayer_option_page');

function register_YTPlayerSettings() {
    //register YTPlayer settings
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_Home_is_active' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_donate' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_version' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_home_video_url' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_show_controls' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_show_videourl' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_start_at' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_stop_at' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_audio_volume' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_mute' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_ratio' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_loop' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_opacity' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_quality' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_add_raster' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_track_ga' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_realfullscreen' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_stop_on_blur' );
    register_setting( 'YTPlayer-settings-group', 'mbYTPlayer_home_video_page' );
}

function mbYTPlayer_options_page() { 	// Output the options page
    ?>

    <!-- DONATE POPUP-->
    <style>
        #donate{ position: fixed; top: 20%; left: 0; width: 100%; height: 100%; padding: 30px; text-align: center; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; z-index: 10000; }
        #donateContent{ position: relative; margin: 30px auto; background: rgba(77, 71, 61, 0.88); color:white; padding: 30px; text-align: center; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; width: 450px; border-radius: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.5) }
        #donate h2{ font-size: 30px; line-height: 33px; }
        #donate p{ margin: 30px; font-size: 16px; line-height: 22px; display: block; float: none; }
        #donate p#follow{ margin: 30px; font-size: 16px; line-height: 33px; }
        #donate p#timer{ padding: 5px; font-size: 20px; line-height: 33px; background: #231d0c; border-radius: 30px; color: #ffffff; width: 30px; margin: auto; }
        #donateTxt{display:none;}
        hr{border: none; height: 1px; background: #dfd490}
    </style>

    <div id="donate" style="display: none">
        <div id="donateContent">
            <h2>mb.YTPlayer</h2>
            <p ><?php _e('If you like it and you are using it then you should consider a donation <br> (€15,00 or more) :-)', 'mbYTPlayer'); ?></p>
            <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=V6ZS8JPMZC446&lc=GB&item_name=mb%2eideas&item_number=MBIDEAS&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted" target="_blank" onclick="donate()">
                    <img border="0" alt="PayPal" src="https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif">
                </a></p>
            <p id="timer">&nbsp;</p>
            <br>
            <br>
            <button onclick="donate()"><?php _e('I already donate', 'mbYTPlayer'); ?></button>
        </div>
    </div>
    <script type="text/javascript">

        function donate() {
            jQuery("input[name=mbYTPlayer_donate]").val("true");
            jQuery("#optionsForm").submit();
        }

        jQuery(function () {

            if (<?php echo get_option('mbYTPlayer_donate');?>) {
                jQuery("#donate").remove();
                jQuery("#inlineDonate").remove();
                jQuery("#donateTxt").show()
            } else {
                jQuery("#donate").show();
                var timer = 5;
                var closeDonate = setInterval(function () {
                    timer--;
                    jQuery("#timer").html(timer);
                    if (timer == 0) {
                        clearInterval(closeDonate);
                        jQuery("#donate").fadeOut(600, jQuery(this).remove)
                    }
                }, 1000)
            }
        });
    </script>
    <!-- END DONATE POPUP-->

    <div class="wrap" style="width:800px">
    <style>
        #wpwrap{background: #ebf2f4 url("<?php echo plugins_url( 'images/bgnd.jpg', __FILE__ );?>") no-repeat fixed;}
        .form-table th{ font-weight: bold!important; border-bottom: 1px solid gray; }
        .form-table td{ border-bottom: 1px solid gray; }
        .submit{ text-align: right; }
    </style>

    <a href="http://pupunzi.com"><img style="margin-top:30px;" src="<?php echo plugins_url( 'images/logo.png', __FILE__ );?>" alt="Made by Pupunzi" /></a>
    <h2><?php _e('mb.YTPlayer Settings', 'mbYTPlayer'); ?></h2>
    <p><?php printf( __( 'You’re using mb.YTPlayer v. %s', 'mbYTPlayer' ), get_option('mbYTPlayer_version') ); ?> <?php _e('by', 'mbYTPlayer'); ?> <a href="http://pupunzi.com">Pupunzi</a>.</p>

    <div id="share" style="position: absolute; left:650px; top:20px">
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wordpress.org/extend/plugins/wpmbytplayer/" data-text="I'm using the mb.YTPlayer WP plugin for background videos" data-via="pupunzi" data-hashtags="HTML5,wordpress,plugin">Tweet</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/it_IT/all.js#xfbml=1";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
        <div class="fb-like" data-href="http://wordpress.org/extend/plugins/wpmbytplayer/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-font="arial"></div>
    </div>

    <div class="updated fade" style="position: relative;">
        <p style="line-height: 1.4em;"><?php _e('Thanks for downloading mb.YTPlayer!', 'mbYTPlayer'); ?></p>
        <p id="inlineDonate" style="position: relative; display:block;top:0;margin-right: -10px">
            <?php _e('If you like it and you are using it<br>then you should consider a donation (€15,00 or more) :-)', 'mbYTPlayer'); ?><br><br>
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=V6ZS8JPMZC446&lc=GB&item_name=mb%2eideas&item_number=MBIDEAS&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted" target="_blank" onclick="donate()"><img border="0" alt="PayPal" src="https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif"></a>
            <br><br><i><?php _e('If you donate, the start popup will nevermore display', 'mbYTPlayer'); ?>.</i><br><br>
        </p>
        <hr>
        <p><?php _e('Don’t forget to follow me on twitter', 'mbYTPlayer'); ?>: <a href="https://twitter.com/pupunzi">@pupunzi</a></p>
        <p><?php _e('Visit my site', 'mbYTPlayer'); ?>: <a href="http://pupunzi.com">http://pupunzi.com</a></p>
        <p><?php _e('Visit my blog', 'mbYTPlayer'); ?>: <a href="http://pupunzi.open-lab.com">http://pupunzi.open-lab.com</a></p>
        <p id="donateTxt">Paypal: <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=V6ZS8JPMZC446&lc=GB&item_name=mb%2eideas&item_number=MBIDEAS&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted" target="_blank"><?php _e('donate', 'mbYTPlayer'); ?></a></p>
    </div>

    <div class="highlight fade" style="padding: 10px; margin: 0">
        <!-- Begin MailChimp Signup Form -->
        <form action="http://pupunzi.us6.list-manage2.com/subscribe/post?u=4346dc9633&amp;id=91a005172f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
            <label for="mce-EMAIL" style="font-weight: bold"><?php _e('Subscribe to my mailing list<br>to stay in touch', 'mbYTPlayer'); ?>.</label>
            <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="<?php _e('your email address', 'mbYTPlayer'); ?>" required>
            <input type="submit" value="<?php _e('Subscribe', 'mbYTPlayer'); ?>" name="subscribe" id="mc-embedded-subscribe" class="button">
        </form>
        <!--End mc_embed_signup-->
    </div>

    <form id="optionsForm" method="post" action="options.php">

        <?php settings_fields( 'YTPlayer-settings-group' ); ?>
        <?php do_settings_sections( 'YTPlayer-settings-group' ); ?>

        <h2><?php _e('Reference', 'mbYTPlayer'); ?></h2>

        <p><?php _e('You can add a mb.YTPlayer to any of your posts via the shortcode editor available by clicking the button placed on the button-bar of the post content editor', 'mbYTPlayer'); ?></p>
        <br>
        <h2><?php _e('Home page background video properties', 'mbYTPlayer'); ?>:</h2>
        <p><?php _e('These settings are used only for the Home istance of the mb.YTPlayer component', 'mbYTPlayer'); ?>.</p>
        <br>
        <br>
        <input type="hidden" name="mbYTPlayer_donate" value="<?php echo esc_attr( get_option('mbYTPlayer_donate') ); ?>" />
        <table class="form-table">

            <tr valign="top">
                <th scope="row"><?php _e('activate the background video', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="checkbox" name="mbYTPlayer_Home_is_active" value="true" <?php if (get_option('mbYTPlayer_Home_is_active') =="true") {echo' checked="checked"'; } ?>/>
                    <p><?php _e('Check to activate the background video for the Homepage or the blog index page', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"> <?php _e('The Youtube vudeo url is:', 'mbYTPlayer'); ?></th>
                <td>
                    <textarea  name="mbYTPlayer_home_video_url" style="width:70%" value="<?php echo esc_attr( get_option('mbYTPlayer_home_video_url') ); ?>"><?php echo esc_attr( get_option('mbYTPlayer_home_video_url') ); ?></textarea>
                    <!--                    <input type="text" name="mbYTPlayer_home_video_url" style="width:70%" value="--><?php //echo esc_attr( get_option('mbYTPlayer_home_video_url') ); ?><!--"/>-->
                    <p><?php _e('Copy and paste here the URL of the Youtube video you want as your homepage background. Adding more then one URL comma separated it will be chosen one randomly each time you reach the page.', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('The page where to show the background video is:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="radio" name="mbYTPlayer_home_video_page" value="static" <?php if (get_option('mbYTPlayer_home_video_page') =="static") {echo' checked'; }?> /> static Homepage <br>
                    <input type="radio" name="mbYTPlayer_home_video_page" value="blogindex" <?php if (get_option('mbYTPlayer_home_video_page') =="blogindex") {echo' checked'; }?>/> blog index Homepage <br>
                    <input type="radio" name="mbYTPlayer_home_video_page" value="both" <?php if (get_option('mbYTPlayer_home_video_page') =="both") {echo' checked'; }?>/> both <br>
                    <p><?php _e('Choose on which page you want the background video to be shown', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Set the opacity:', 'mbYTPlayer'); ?></th>
                <td>
                    <select name="mbYTPlayer_opacity">
                        <option value=".3" <?php if (get_option('mbYTPlayer_opacity')==".3") {echo' selected'; }?> >0.3</option>
                        <option value=".5" <?php if (get_option('mbYTPlayer_opacity')==".5") {echo' selected'; }?>>0.5</option>
                        <option value=".8" <?php if (get_option('mbYTPlayer_opacity')==".8") {echo' selected'; }?>>0.8</option>
                        <option value="1" <?php if (get_option('mbYTPlayer_opacity')=="1") {echo' selected'; }?>>1</option>
                    </select>
                    <p><?php _e('Set the opacity of the background video', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Set the quality:', 'mbYTPlayer'); ?></th>
                <td>
                    <select name="mbYTPlayer_quality">
                        <option value="default" <?php if (get_option('mbYTPlayer_quality') =="default") {echo' selected'; }?> ><?php _e('default', 'mbYTPlayer'); ?></option>
                        <option value="small" <?php if (get_option('mbYTPlayer_quality')=="small") {echo' selected'; }?> ><?php _e('small', 'mbYTPlayer'); ?></option>
                        <option value="medium" <?php if (get_option('mbYTPlayer_quality')=="medium") {echo' selected'; }?> ><?php _e('medium', 'mbYTPlayer'); ?></option>
                        <option value="large" <?php if (get_option('mbYTPlayer_quality')=="large") {echo' selected'; }?> ><?php _e('large', 'mbYTPlayer'); ?></option>
                        <option value="hd720" <?php if (get_option('mbYTPlayer_quality')=="hd720") {echo' selected'; }?> ><?php _e('hd720', 'mbYTPlayer'); ?></option>
                        <option value="hd1080" <?php if (get_option('mbYTPlayer_quality')=="hd1080") {echo' selected'; }?> ><?php _e('hd1080', 'mbYTPlayer'); ?></option>
                        <option value="highres" <?php if (get_option('mbYTPlayer_quality')=="highres") {echo' selected'; }?> ><?php _e('highres', 'mbYTPlayer'); ?></option>
                    </select>
                    <p><?php _e('Set the quality of the background video ("default" YouTube selects the appropriate playback quality)', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Set the aspect ratio:', 'mbYTPlayer'); ?></th>
                <td>
                    <select name="mbYTPlayer_ratio">
                        <option value="auto" <?php if (get_option('mbYTPlayer_ratio')=="auto") {echo' selected'; }?> ><?php _e('auto', 'mbYTPlayer'); ?></option>
                        <option value="4/3" <?php if (get_option('mbYTPlayer_ratio')=="4/3") {echo' selected'; }?> ><?php _e('4/3', 'mbYTPlayer'); ?></option>
                        <option value="16/9" <?php if (get_option('mbYTPlayer_ratio')=="16/9") {echo' selected'; }?>><?php _e('16/9', 'mbYTPlayer'); ?></option>
                    </select>
                    <p><?php _e('Set the aspect-ratio of the background video. If "auto" the plug in will try to retrieve the aspect ratio from Youtube. If you have problems on viewing the background video try setting this manually.', 'mbYTPlayer'); ?>
                    </p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('The video should start at:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="text" name="mbYTPlayer_start_at" style="width:10%" value="<?php echo esc_attr( get_option('mbYTPlayer_start_at') ); ?>"/>
                    <p><?php _e('Set the seconds the video should start at', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('The video should stop at:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="text" name="mbYTPlayer_stop_at" style="width:10%" value="<?php echo esc_attr( get_option('mbYTPlayer_stop_at') ); ?>"/>
                    <p><?php _e('Set the seconds the video should stop at', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Show the control bar:', 'mbYTPlayer'); ?></th>
                <td>
                    <input id="mbYTPlayer_show_controls" onclick="videoUrlControl()" type="checkbox" name="mbYTPlayer_show_controls" value="true" <?php if (get_option('mbYTPlayer_show_controls') =="true") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Check to show controls at the bottom of the page', 'mbYTPlayer'); ?>.</p>
                    <div id="videourl" style="display: none;">
                        <input id="mbYTPlayer_show_videourl"  type="checkbox" name="mbYTPlayer_show_videourl" value="true" <?php if (get_option('mbYTPlayer_show_videourl') =="true") {echo' checked="checked"'; } ?>/>
                        <p><?php _e('Check to show the link to the original YouTube® video', 'mbYTPlayer'); ?>.</p>
                    </div>
                    <script>
                        function videoUrlControl(){
                            if (jQuery("#mbYTPlayer_show_controls").is(":checked")){
                                jQuery("#videourl").show();
                            }else{
                                jQuery("#mbYTPlayer_show_videourl").attr("checked",false).val(false);
                                jQuery("#videourl").hide();
                            }
                        }
                        videoUrlControl();
                    </script>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('The full screen behavior is:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="radio" name="mbYTPlayer_realfullscreen" value="true" <?php if (get_option('mbYTPlayer_realfullscreen') =="true") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Full screen containment is the screen', 'mbYTPlayer'); ?></p>
                    <input type="radio" name="mbYTPlayer_realfullscreen" value="false" <?php if (get_option('mbYTPlayer_realfullscreen') =="false") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Full screen containment is the browser window', 'mbYTPlayer'); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Set the audio volume:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="text" name="mbYTPlayer_audio_volume" value="50" style="width:10%" />
                    <p><?php _e('Set the volume for the video (from 0 to 100)', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Mute the video:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="checkbox" name="mbYTPlayer_mute" value="true" <?php if (get_option('mbYTPlayer_mute') =="true") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Check to mute the audio of the video', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('The video should loop:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="checkbox" name="mbYTPlayer_loop" value="true" <?php if (get_option('mbYTPlayer_loop') =="true") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Check to loop the video once ended', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Add the raster image:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="checkbox" name="mbYTPlayer_add_raster" value="true" <?php if (get_option('mbYTPlayer_add_raster') =="true") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Check to add a raster effect to the video', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Track the video views on Google Analytics', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="checkbox" name="mbYTPlayer_track_ga" value="true" <?php if (get_option('mbYTPlayer_track_ga') =="true") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Check to track this video on Google Analytics if played', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Pause the player if the windows blur:', 'mbYTPlayer'); ?></th>
                <td>
                    <input type="checkbox" name="mbYTPlayer_stop_on_blur" value="true" <?php if (get_option('mbYTPlayer_stop_on_blur') =="true") {echo' checked="checked"'; }?>/>
                    <p><?php _e('Check to pause the player once the window blur', 'mbYTPlayer'); ?>.</p>
                </td>
            </tr>

        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
    </form>
    </div>
<?php } ?>
