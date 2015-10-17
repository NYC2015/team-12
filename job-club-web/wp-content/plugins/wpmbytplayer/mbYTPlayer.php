<?php
/*
Plugin Name: mb.YTPlayer background video
Plugin URI: http://pupunzi.com/#mb.components/mb.YTPlayer/YTPlayer.html
Description: Play a Youtube video as background of your page. <strong>Go to settings > mbYTPlayer</strong> to activate the background video option for your homepage. Or use the short code following the reference in the settings panel.
Author: Pupunzi (Matteo Bicocchi)
Version: 2.0.3
Author URI: http://pupunzi.com
*/

define("MBYTPLAYER_VERSION", "2.0.3");

function isMobile(){
// Check the server headers to see if they're mobile friendly
    if (isset($_SERVER["HTTP_X_WAP_PROFILE"])) {
        return true;
    }

// If the http_accept header supports wap then it's a mobile too
    if (preg_match("/wap.|.wap/i", $_SERVER["HTTP_ACCEPT"])) {
        return true;
    }

    if (preg_match("/iphone|ipad/i", $_SERVER["HTTP_USER_AGENT"])) {
        return true;
    }
// None of the above? Then it's probably not a mobile device.
    return false;
}

function mbYTPlayer_install()
{
// add and update our default options upon activation
    update_option('mbYTPlayer_version', MBYTPLAYER_VERSION);

    add_option('mbYTPlayer_Home_is_active', 'true');
    add_option('mbYTPlayer_donate', 'false');
    add_option('mbYTPlayer_home_video_url', '');
    add_option('mbYTPlayer_show_controls', 'false');
    add_option('mbYTPlayer_show_videourl', 'false');
    add_option('mbYTPlayer_audio_volume', '50');
    add_option('mbYTPlayer_mute', 'false');
    add_option('mbYTPlayer_start_at', '0');
    add_option('mbYTPlayer_stop_at', '0');
    add_option('mbYTPlayer_ratio', '16/9');
    add_option('mbYTPlayer_loop', 'false');
    add_option('mbYTPlayer_opacity', '1');
    add_option('mbYTPlayer_quality', 'default');
    add_option('mbYTPlayer_add_raster', 'false');
    add_option('mbYTPlayer_track_ga', 'true');
    add_option('mbYTPlayer_stop_on_blur', 'false');
    add_option('mbYTPlayer_track_ga', 'false');
    add_option('mbYTPlayer_realfullscreen', 'true');
    add_option('mbYTPlayer_home_video_page', 'static');
}

register_activation_hook(__FILE__, 'mbYTPlayer_install');

$mbYTPlayer_donate = get_option('mbYTPlayer_donate');
$mbYTPlayer_Home_is_active = get_option('mbYTPlayer_Home_is_active');
$mbYTPlayer_home_video_url = get_option('mbYTPlayer_home_video_url');
$mbYTPlayer_version = get_option('mbYTPlayer_version');
$mbYTPlayer_show_controls = get_option('mbYTPlayer_show_controls');
$mbYTPlayer_show_videourl = get_option('mbYTPlayer_show_videourl');
$mbYTPlayer_ratio = get_option('mbYTPlayer_ratio');
$mbYTPlayer_audio_volume = get_option('mbYTPlayer_audio_volume');
$mbYTPlayer_mute = get_option('mbYTPlayer_mute');
$mbYTPlayer_start_at = get_option('mbYTPlayer_start_at');
$mbYTPlayer_stop_at = get_option('mbYTPlayer_stop_at');
$mbYTPlayer_loop = get_option('mbYTPlayer_loop');
$mbYTPlayer_opacity = get_option('mbYTPlayer_opacity');
$mbYTPlayer_quality = get_option('mbYTPlayer_quality');
$mbYTPlayer_add_raster = get_option('mbYTPlayer_add_raster');
$mbYTPlayer_track_ga = get_option('mbYTPlayer_track_ga');
$mbYTPlayer_realfullscreen = get_option('mbYTPlayer_realfullscreen');
$mbYTPlayer_home_video_page = get_option('mbYTPlayer_home_video_page');

$mbYTPlayer_stop_on_blur = get_option('mbYTPlayer_stop_on_blur');

//set up defaults if these fields are empty
if ($mbYTPlayer_version != MBYTPLAYER_VERSION) {
    $mbYTPlayer_version = MBYTPLAYER_VERSION;
}
if (empty($mbYTPlayer_Home_is_active)) {
    $mbYTPlayer_Home_is_active = false;
}
if (empty($mbYTPlayer_donate)) {
    $mbYTPlayer_donate = "false";
}
if (empty($mbYTPlayer_show_controls)) {
    $mbYTPlayer_show_controls = "false";
}
if (empty($mbYTPlayer_show_videourl)) {
    $mbYTPlayer_show_videourl = "false";
}
if (empty($mbYTPlayer_ratio)) {
    $mbYTPlayer_ratio = "auto";
}
if (empty($mbYTPlayer_audio_volume)) {
    $mbYTPlayer_audio_volume = "50";
}
if (empty($mbYTPlayer_mute)) {
    $mbYTPlayer_mute = "false";
}
if (empty($mbYTPlayer_start_at)) {
    $mbYTPlayer_start_at = 0;
}
if (empty($mbYTPlayer_stop_at)) {
    $mbYTPlayer_stop_at = 0;
}
if (empty($mbYTPlayer_loop)) {
    $mbYTPlayer_loop = "false";
}
if (empty($mbYTPlayer_opacity)) {
    $mbYTPlayer_opacity = "1";
}
if (empty($mbYTPlayer_quality)) {
    $mbYTPlayer_quality = "default";
}
if (empty($mbYTPlayer_add_raster)) {
    $mbYTPlayer_add_raster = "false";
}
if (empty($mbYTPlayer_track_ga)) {
    $mbYTPlayer_track_ga = "false";
}
if (empty($mbYTPlayer_stop_on_blur)) {
    $mbYTPlayer_stop_on_blur = "false";
}
if (empty($mbYTPlayer_realfullscreen)) {
    $mbYTPlayer_realfullscreen = "false";
}
if (empty($mbYTPlayer_home_video_page)) {
    $mbYTPlayer_home_video_page = "static";
}


//action link http://www.wpmods.com/adding-plugin-action-links

function mbYTPlayer_action_links($links, $file)
{
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    // check to make sure we are on the correct plugin
    if ($file == $this_plugin) {
        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/options-general.php?page=wpmbytplayer/mbYTPlayer-admin.php">Settings</a>';
        // add the link to the list
        array_unshift($links, $settings_link);
    }

    return $links;
}

add_filter('plugin_action_links', 'mbYTPlayer_action_links', 10, 2);

// define the shortcode function

add_shortcode('mbYTPlayer', 'mbYTPlayer_player_shortcode');
add_filter('widget_text', 'do_shortcode');

function mbYTPlayer_player_shortcode($atts)
{
    STATIC $i = 1;
    $elId = "body";
    $style = "";
    extract(shortcode_atts(array(
        'url' => '',
        'showcontrols' => '',
        'printurl' => '',
        'mute' => '',
        'ratio' => '',
        'loop' => '',
        'opacity' => '',
        'quality' => '',
        'addraster' => '',
        'isinline' => '',
        'playerwidth' => '',
        'playerheight' => '',
        'autoplay' => '',
        'gaTrack' => '',
        'stopmovieonblur' => '',
        'realfullscreen' => 'true',
        'startat' => '',
        'stopat' => '',
        'volume' =>''
    ), $atts));
    // stuff that loads when the shortcode is called goes here

    if (empty($url) || ((is_home() || is_front_page()) && !empty($mbYTPlayer_home_video_url) && empty($isInline))) // || (empty($id) && (is_home() || is_front_page()))
        return false;

    if (empty($startat)) {
        $startat = 0;
    }
    if (empty($stopat)) {
        $stopat = 0;
    }
    if (empty($isinline)) {
        $isinline = "false";
    }
    if (empty($ratio)) {
        $ratio = "auto";
    }
    if (empty($showcontrols)) {
        $showcontrols = "true";
    }
    if (empty($printurl)) {
        $printurl = "true";
    }
    if (empty($opacity)) {
        $opacity = "1";
    }
    if (empty($mute)) {
        $mute = "false";
    }
    if (empty($loop)) {
        $loop = "false";
    }
    if (empty($quality)) {
        $quality = "default";
    }
    if (empty($addraster)) {
        $addraster = "false";
    };
    if (empty($stopmovieonblur)) {
        $stopmovieonblur = "false";
    };
    if (empty($gaTrack)) {
        $gaTrack = "false";
    };
    if (empty($realfullscreen)) {
        $realfullscreen = "true";
    };
    if (empty($autoplay)) {
        $autoplay = "false";
    };
    if (empty($volume)) {
        $volume = "50";
    };
    if ($isinline == "true") {
        if (empty($playerwidth)) {
            $playerwidth = "300";
        };
        if (empty($playerheight)) {
            $playerheight = "220";
        };

        $unitWidth = strrpos($playerwidth, "%") ? "" : "px";
        $unitHeight = strrpos($playerheight, "%") ? "" : "px";

        $startat = $startat > 0 ? $startat : 1;

        $elId = "self";
        $style = " style=\"width:" . $playerwidth . $unitWidth . "; height:" . $playerheight . $unitHeight . "; position:relative\"";
    };

    /*
     * If multiple URL are inserted than choose one randomly
     * */

    $vids = explode(',', $url);
    $n = rand(0, count($vids)-1);
    $mbYTPlayer_home_video_url_revised = $vids[$n];


    $mbYTPlayer_player_shortcode = '<div id="playerVideo' . $i . '" ' . $style . ' class="mbYTPMovie' . ($isinline ? " inline_YTPlayer" : "") . '" data-property="{videoURL:\'' . $mbYTPlayer_home_video_url_revised . '\', opacity:' . $opacity . ', autoPlay:' . $autoplay . ', containment:\'' . $elId . '\', startAt:' . $startat . ', stopAt:' . $stopat . ', mute:' . $mute . ', vol:' . $volume. ', optimizeDisplay:true, showControls:' . $showcontrols . ', printUrl:' . $printurl . ', loop:' . $loop . ', addRaster:' . $addraster . ', quality:\'' . $quality . '\', realfullscreen:' . $realfullscreen . ', ratio:\'' . $ratio . '\', gaTrack:' . $gaTrack . ', stopMovieOnBlur:' . $stopmovieonblur . '}"></div>';

    $i++; //increment static variable for unique player IDs
    return $mbYTPlayer_player_shortcode;
}

//ends the mbYTPlayer_player_shortcode function

// scripts to go in the header and/or footer
function mbYTPlayer_init()
{
    global $mbYTPlayer_version;

    load_plugin_textdomain('mbYTPlayer', false, basename( dirname( __FILE__ ) ) . '/languages/' );

    if (isset($_COOKIE['ytpdonate']) && $_COOKIE['ytpdonate'] !== "false") {
        update_option('mbYTPlayer_donate', "true");
        echo '
            <script type="text/javascript">
                expires = "; expires= -10000";
                document.cookie = "ytpdonate=false" + expires + "; path=/";
            </script>
        ';
    }

    if (!is_admin()) {

        wp_enqueue_script('jquery');
        wp_enqueue_script('mb.YTPlayer', plugins_url('/js/jquery.mb.YTPlayer.min.js', __FILE__), array('jquery'), $mbYTPlayer_version, true, 1000);
        wp_enqueue_style('mb.YTPlayer_css', plugins_url('/css/mb.YTPlayer.css', __FILE__), array( ), $mbYTPlayer_version, 'screen' );

    }
}
add_action('wp_enqueue_scripts', 'mbYTPlayer_init');

function mbYTPlayer_player_head()
{
    global $mbYTPlayer_home_video_url, $mbYTPlayer_show_controls, $mbYTPlayer_ratio, $mbYTPlayer_show_videourl, $mbYTPlayer_start_at, $mbYTPlayer_stop_at, $mbYTPlayer_mute, $mbYTPlayer_loop, $mbYTPlayer_opacity, $mbYTPlayer_quality, $mbYTPlayer_add_raster, $mbYTPlayer_track_ga,$mbYTPlayer_realfullscreen, $mbYTPlayer_stop_on_blur, $mbYTPlayer_home_video_page, $mbYTPlayer_Home_is_active, $mbYTPlayer_audio_volume;

    /*    if (isMobile())
            return false;*/

    echo '
	<!-- mbYTPlayer -->
	<script type="text/javascript">


    function onYouTubePlayerAPIReady() {
    	if(ytp.YTAPIReady)
		    return;
	    ytp.YTAPIReady=true;
	    jQuery(document).trigger("YTAPIReady");
    }

    jQuery.mbYTPlayer.rasterImg ="' . plugins_url('/images/', __FILE__) . 'raster.png";
	jQuery.mbYTPlayer.rasterImgRetina ="' . plugins_url('/images/', __FILE__) . 'raster@2x.png";

	jQuery(function(){
        jQuery(".mbYTPMovie").YTPlayer()
	});

	</script>
	<!-- end mbYTPlayer -->
	';

    $canShowMovie = is_front_page() && !is_home(); // A static page set as home;

    if ($mbYTPlayer_home_video_page == "blogindex")
        $canShowMovie = is_home(); // the blog index page;

    else if ($mbYTPlayer_home_video_page == "both")
        $canShowMovie = is_front_page() || is_home(); // A static page set as home;

    if ($canShowMovie && !isMobile() && $mbYTPlayer_Home_is_active) {

        if (empty($mbYTPlayer_home_video_url))
            return false;

        $vids = explode(',', $mbYTPlayer_home_video_url);
        $n = rand(0, count($vids)-1);
        $mbYTPlayer_home_video_url_revised = $vids[$n];

        $mbYTPlayer_start_at = $mbYTPlayer_start_at > 0 ? $mbYTPlayer_start_at : 1;
        $mbYTPlayer_player_homevideo = '<div id=\"bgndVideo_home\" data-property=\"{videoURL:\'' . $mbYTPlayer_home_video_url_revised . '\', opacity:' . $mbYTPlayer_opacity . ', autoPlay:true, containment:\'body\', startAt:' . $mbYTPlayer_start_at . ', stopAt:' . $mbYTPlayer_stop_at . ', mute:' . $mbYTPlayer_mute . ', vol:' . $mbYTPlayer_audio_volume . ', optimizeDisplay:true, showControls:' . $mbYTPlayer_show_controls . ', printUrl:' . $mbYTPlayer_show_videourl . ', loop:' . $mbYTPlayer_loop . ', addRaster:' . $mbYTPlayer_add_raster . ', quality:\'' . $mbYTPlayer_quality . '\', ratio:\'' . $mbYTPlayer_ratio . '\', realfullscreen:\'' . $mbYTPlayer_realfullscreen . '\', gaTrack:\'' . $mbYTPlayer_track_ga . '\', stopMovieOnBlur:\'' . $mbYTPlayer_stop_on_blur . '\'}\"></div>';
        echo '
	<!-- mbYTPlayer Home -->
	<script type="text/javascript">

	jQuery(function(){
	    var homevideo = "' . $mbYTPlayer_player_homevideo . '";
	    jQuery("body").prepend(homevideo);
	    jQuery("#bgndVideo_home").YTPlayer();
    });

	</script>
	<!-- end mbYTPlayer Home -->
        ';
    }

};
// ends mbYTPlayer_player_head function

add_action('wp_footer', 'mbYTPlayer_player_head',20);

// TinyMCE Button ***************************************************

// Set up our TinyMCE button
function setup_ytplayer_button()
{
    if (get_user_option('rich_editing') == 'true' && current_user_can('edit_posts')) {
        add_filter('mce_external_plugins', 'add_ytplayer_button_script');
        add_filter('mce_buttons', 'register_ytplayer_button');
    }
}

// Register our TinyMCE button
function register_ytplayer_button($buttons)
{
    array_push($buttons, '|', 'YTPlayerbutton');
    return $buttons;
}

// Register our TinyMCE Script
function add_ytplayer_button_script($plugin_array)
{
    $plugin_array['YTPlayer'] = plugins_url('ytptinymce/tinymceytplayer.js', __FILE__);
    return $plugin_array;
}

add_action('admin_init', 'setup_ytplayer_button');

if (is_admin()) {
    require('mbYTPlayer-admin.php');
}
