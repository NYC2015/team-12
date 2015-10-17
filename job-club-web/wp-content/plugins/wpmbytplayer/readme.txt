=== mb.YTPlayer for background videos ===

Contributors: pupunzi
Tags: video player, youtube, full background, video, HTML5, flash, mov, jquery, pupunzi, mb.components, cover video, embed, embed videos, embed youtube, embedding, plugin, shortcode, video cover, video HTML5, youtube, youtube embed, youtube player, youtube videos
Requires at least: 3.0
Tested up to: 4.2
Stable tag:  2.0.3
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DSHAHSJJCQ53Y
License: GPLv2 or later

Play any Youtube video as background of your page or as custom player inside an element of the page.

== Description ==

A Chrome-less Youtube® video player that let you play any YouTube® video as background of your WordPress® page or post.
You can activate it for your home page from the settings panel or on any post or page using the short code as described in the Reference section of the settings.

[youtube http://www.youtube.com/watch?v=lTW937ld02Y]

**From version 1.0 the player is using the Youtube® iframe API displaying the video using the HTML5 VIDEO tag for all the browsers that support it.**

**From version 1.7.6 the plug in is internationalized; available in English, Italian and Spanish (thanks to Andrew Kurtis http://www.webhostinghub.com ).**


The mb.YTPlayer doesn't work on any mobile devices (iOs, Android, Windows, etc.) due to restrictions applied by the vendors on media controls via javascript.
Adding a background image to the body as mobile devices fallback is a good practice and it will also prevent unwanted white flickering on desktop browsers when the video is buffering.



note:
If you doesn't want ADs on your background video and you are the owner of it you can disable this on your Youtube channel as explained here: http://candidio.com/blog/how-to-remove-ads-from-your-youtube-videos .


Links:/Users/mbicocchi/Dropbox/wordpress/My-Game/wordpress/wp-content/themes/myGame/templates/urban-1/css/intro.css

* demo: http://pupunzi.com/mb.components/mb.YTPlayer/demo/demo_background.html
* video: http://www.youtube.com/watch?v=lTW937ld02Y
* pupunzi blog: http://pupunzi.open-lab.com
* pupunzi site: http://pupunzi.com

This plug in has been tested successfully on:

* Chrome 11+, Firefox 7+, Opera 9+    on Mac OsX, Windows and Linux
* Safari 5+    on Mac OsX
* IE7+    on Windows (via Adobe Flash player)

== Installation ==

Extract the zip file and upload the contents to the wp-content/plugins/ directory of your WordPress installation, and then activate the plugin from the plugins page.

== Screenshots ==

1. The settings panel.
2. You can add a video as background or targeted to a DOM element in any page or post by inserting a shortcode generated via the editor button.
3. The shortcode editor.

== To set your homepage background video: ==

1. Go to the mbYTPlayer settings panel (you can find it under the "settings" section of the WP backend.
2. set the complete YT video url
3. set all the other parameters as you need.

To remove the video just leave the url blank.

You can also set it by placing a shortcode in the home page via the YTPlayer shortcode window. 
You can open it by clicking on the YTPlayer button in the top toolbar of the page editor.

== To set a video as background of a post or a page: ==
Use the editor button or write the below shortcode into the content of your post or page:

[mbYTPlayer url="http://www.youtube.com/watch?v=V2rifmjZuKQ" ratio="4/3" mute="false" loop="true" showcontrols="true" opacity=1]

* @ url = the YT url of the video you want as background
* @ ratio = the aspect ratio of the video 4/3 or 16/9
* @ mute = a boolean to mute the video
* @ loop = a boolean to loop the video on its end
* @ showcontrols = a boolean to show or hide controls and progression of the video
* @ opacity = a value from 0 to 1 that set the opacity of the background video
* @ id = The ID of the element in the DOM where you want to target the player (default is the BODY)
* @ quality:
  * small: Player height is 240px, and player dimensions are at least 320px by 240px for 4:3 aspect ratio.
  * medium: Player height is 360px, and player dimensions are 640px by 360px (for 16:9 aspect ratio) or 480px by 360px (for 4:3 aspect ratio).
  * large: Player height is 480px, and player dimensions are 853px by 480px (for 16:9 aspect ratio) or 640px by 480px (for 4:3 aspect ratio).
  * hd720: Player height is 720px, and player dimensions are 1280px by 720px (for 16:9 aspect ratio) or 960px by 720px (for 4:3 aspect ratio).
  * hd1080: Player height is 1080px, and player dimensions are 1920px by 1080px (for 16:9 aspect ratio) or 1440px by 1080px (for 4:3 aspect ratio).
  * highres: Player height is greater than 1080px, which means that the player's aspect ratio is greater than 1920px by 1080px.
  * default: YouTube selects the appropriate playback quality.

== What about mobile ==

The mb.YTPlayer doesn't work on any mobile devices (iOs, Android, Windows, etc.) due to restrictions applied by the vendors on media controls via javascript.
Adding a background image to the body as mobile devices fallback is a good practice and it will also prevent unwanted white flickering on desktop browsers when the video is buffering.

== Changelog ==

= 2.0.3 =
* Bug fix: The loop on webkit browsers randomly failed.
* Feature: The video now stops just 1.5 seconds from the end (it was 3 sec.).

= 2.0.2 =
* Bugfix: If the autoPlay option was set to false the player was hidden.

= 2.0.1 =
* Bugfix: If the control-bar was not displayed there were several javascript errors due to a missed code condition.

= 2.0.0 =
* Bugfix: updated to the new Google API 3 fixing the display of the poster-frame if used as in-line player.

= 1.9.8 =
* Added the volume slider on the player control.
* Bugfix: removed the "stopMovieOnClick" option.

= 1.9.7 =
* Added: An option to set the volume of the video.

= 1.9.6 =
* Added: An option to choose on which page the background video should be shown (static Home or blog index or both).
* Added: An option to deactivate the video without removing the video URL from the settings.

= 1.9.5 =
* Fix: Something changed in the YT API that was preventing Firefox to get .getVideoLoadedFraction() at video load.
* Added: uninstall.php to clean the settings when the plugin is deleted.

= 1.9.4 =
* Feature: You can now specify the width and height of an in-line player as percentage of its container.
* It has been updated to jquery.mb.YTPlayer 2.7.6 (http://pupunzi.open-lab.com/mb-jquery-components/jquery-mb-ytplayer/).

= 1.9.3 =
* Bugfix: The "stopAt" parameter was not persisted.

= 1.9.2 =
* Update: Updated the mbYTPlayer-admin.php using the Wordpress "Settings API".

= 1.9.1 =
* Bug fix: Fixed a bug on the settings window (the raster image could not be removed) introduced with one of the previous updates.

= 1.9.0 =
* Bug fix: Chrome problems on start playing the video.

= 1.8.9 =
* Bug fix: Fixed a conflict with the Bootstrap framework.
* Bug fix: Something is changed in the Youtube API that was preventing the auto-play of the video; now the "can play state" is more accurate and should speed up the start of the video.

= 1.8.8 =
* bug fix: The mute/unmute randomly didn't apply.

= 1.8.7 =
* New feature: Added support for Google Analytic Universal event tracking; before this update the "play" event was tracked only if the standard version of GA was present on the page; now it works also if the latest Universal GA is installed.

= 1.8.6 =
* bugfix: Fixed a bug that prevents the player to run correctly within certain environments.

= 1.8.5 =
* bugfix: Fixed a conflict with the default mediaelementjs; The default WP media player now works fine together with the YTPlayer.

= 1.8.4 =
* bugfix: the shortcode editor didn't consider the video url after last update. solved.

= 1.8.3 =
* New feature: You can now add a comma separated list of YT videos; every time you load the page one of them will be randomly chosen (thanx to Giampaolo D'Amico).

= 1.8.2 =
* Bug fix: Updated to solve a problem with the "YTPEnd" event that was not firing anymore.

= 1.8.1 =
* Major update: Updated to solve issue compatibilities with the latest 3.9 Wordpress release.

= 1.8.0 =
* Bugfix: Removed a blocking bug introduced with the 1.7.9 release.

= 1.7.9 =
* Feature: Added the possibility choose which video should be tracked by Google Analytics checking the apposite checkbox both in the preferences window and in the edit window.

= 1.7.8 =
* Bugfix: With the latest Chrome release something was lost with the aspect ratio.

= 1.7.7 =
* bugfox for the startAt behavior.

= 1.7.6 =
* Added internationalization. In addition to English are now available the Italian translation and the Spanish translation (thanks to Andrew Kurtis http://www.webhostinghub.com ).
  If you want to translate the YTPlayer plugin in your language here is the .POT file: http://pupunzi.open-lab.com/wp-translations/mbYTPlayer.pot
  Send me the translation once finished; it'll be available with the next update.

= 1.7.5 =
* fixed bug on the shortcode editor window where the "autoplay" option where shown only if "is inline" was checked.
* Added the "stopAt" option to set when the video should stop.

= 1.7.4 =
* fixed bug that prevented the player to start muted.

= 1.7.3 =
* fixed a vulnerability issue in the TinyMCE popup.
* fixed bug that prevented the correct behavior of the switch between the two full-screen modality.

= 1.7.2 =
* Bug fix: Better detection of the video availability to check when to start the video.

= 1.7.1 =
* Bug fix:
If the default Wordpress audio player was instanced in a page together with the YTPlayer, the YTPlayer didn't work.
That was for a conflict with the "mediaelement".

= 1.7.0 =
* Bug fix:
Solved a problem in the TinyMCE editor that prevented the fullscreen option to be checked.

= 1.6.9 =
* Bug fix:
added wp_enqueue_script('yt_api_player', '//www.youtube.com/player_api', false, $mbYTPlayer_version, false) in mbYTPlayer.php to solve audio short-code incompatibility.

= 1.6.8 =
* Feature: Added _GA event track to get statistics if GA Analytics is active (under the Events section): _gaq.push(['_trackEvent', 'YTPlayer', 'Play', (YTPlayer.title || YTPlayer.videoID.toString())]);.

= 1.6.7 =
* Feature: "autoplay = false" works also for background videos.

= 1.6.6 =
* Bug fix: the fullscreen method switcher didn't work from the TinyMCE editor.

= 1.6.5 =
* New feature: You can choose if the fullscreen behavior should be contained into the browser window or cover all the screen.

...

= 0.1 =
* First release

== Frequently Asked Questions ==

= I'm using the plug in as background video and I can see the control bar on the bottom but the video doesn't display =
 Your theme is probably using a wrapper for the content and it probably has a background color or image. You should check the CSS and remove that background to let the video that is behind do display correctly.

= Everything is working fine on my desktop but it doesn't work on any mobile devices =
Due to restrictions adopted by browser vendors and Youtube team this plugin can't work on touch devices.

= I would have an image on the background before the video starts and after the video end; how can I do? =
The simplest way is to add an image as background of the body via CSS.

= I set the video quality to hd1080 but it doesn't display at this quality; why? =
The video quality option is just a suggestion for the Youtube API; the video is served by Youtube with the quality that best fits the bandwidth and the display size according to that setting.

= The video stops some seconds before the real end; why? =
To prevent the display of the "play" button provided by the Youtube API the video intentionally stops some seconds before the end; if you are the owner of the video I can suggest to make it a little bit longer (about 3/4 seconds).
