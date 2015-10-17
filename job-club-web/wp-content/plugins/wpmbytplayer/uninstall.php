<?php

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();

// Uninstall all the mbYTPlayer settings
delete_option('mbYTPlayer_version');
//delete_option('mbYTPlayer_donate');

delete_option('mbYTPlayer_home_video_url');
delete_option('mbYTPlayer_show_controls');
delete_option('mbYTPlayer_show_videourl');
delete_option('mbYTPlayer_mute');
delete_option('mbYTPlayer_start_at');
delete_option('mbYTPlayer_stop_at');
delete_option('mbYTPlayer_ratio');
delete_option('mbYTPlayer_loop');
delete_option('mbYTPlayer_opacity');
delete_option('mbYTPlayer_quality');
delete_option('mbYTPlayer_add_raster');
delete_option('mbYTPlayer_track_ga');
delete_option('mbYTPlayer_stop_onclick');
delete_option('mbYTPlayer_stop_on_blur');
delete_option('mbYTPlayer_track_ga');
delete_option('mbYTPlayer_realfullscreen');
