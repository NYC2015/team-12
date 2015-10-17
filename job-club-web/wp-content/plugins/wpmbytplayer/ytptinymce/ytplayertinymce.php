<?php
require('../../../../wp-blog-header.php');

$plugin_version =get_option('mbYTPlayer_version');
$includes_url = includes_url();
$plugins_url = plugins_url();
$charset = get_option('blog_charset');
$donate = get_option('mbYTPlayer_donate');

if (!headers_sent()) {
    header('Content-Type: text/html; charset='.$charset);
}

if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
    ?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset; ?>" />
        <title><?php _e('Add a shortcode for mb.YTPlayer', 'mbYTPlayer'); ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $plugins_url.'/wpmbytplayer/ytptinymce/bootstrap-1.4.0.min.css?v='.$plugin_version; ?>"/>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
        <script type="text/javascript" src="<?php echo $includes_url.'js/tinymce/tiny_mce_popup.js?v='.$plugin_version; ?>"></script>
        <style>
            fieldset span.label{
                display: inline-block;
                width: 100px;
            }
            fieldset label {
                margin: 0;
                padding: 3px!important;
                border-top: 1px solid #dcdcdc;
                border-bottom: 1px solid #f9f9f9;
                display: block;
            }
            .actions{
                text-align: right;
            }

            #inlinePlayer, #controlBox{
                display: none;
                background: #fff;
                padding: 5px;
            }
        </style>

    </head>
    <body>
    <!-- DONATE POPUP-->
    <style>
        #donate{ position: fixed; top: 0; left: 0; width: 100%; height: 100%; padding: 30px; text-align: center; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; z-index: 10000; }
        #donateContent{ position: relative; margin: 30px auto; background: rgba(77, 71, 61, 0.88); color:white; padding: 30px; text-align: center; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; width: 450px; border-radius: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.5) }
        #donate h2{ font-size: 30px; line-height: 33px; color: #ffffff; }
        #donate p{ margin: 30px; font-size: 16px; line-height: 22px; display: block; float: none; }
        #donate p#follow{ margin: 30px; font-size: 16px; line-height: 33px; }
        #donate p#timer{ padding: 5px; font-size: 20px; line-height: 33px; background: #231d0c; border-radius: 30px; color: #ffffff; width: 30px; margin: auto; }
        #donate button{padding: 5px;border-radius: 3px;background: #ffffff}
    </style>

    <div id="donate" style="display: none">
        <div id="donateContent">
            <h2>mb.YTPlayer</h2>
            <p ><?php _e('If you like it and you are using it then you should consider a donation <br> (€15,00 or more) :-)', 'mbYTPlayer'); ?></p>
            <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=V6ZS8JPMZC446&lc=GB&item_name=mb%2eideas&item_number=MBIDEAS&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted" target="_blank" onclick="donate();">
                    <img border="0" alt="PayPal" src="https://www.paypalobjects.com/en_US/IT/i/btn/btn_donateCC_LG.gif">
                </a></p>
            <p id="timer">&nbsp;</p>
            <br>
            <br>
            <button onclick="donate()"><?php _e('I already donate', 'mbYTPlayer'); ?></button>
        </div>
    </div>
    <script type="text/javascript">

        $.mbCookie = {
            set:function (name, value, days, domain) {
                if (!days) days = 7;
                domain = domain ? "; domain=" + domain : "";
                var date = new Date(), expires;
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toGMTString();
                document.cookie = name + "=" + value + expires + "; path=/" + domain;
            },
            get:function (name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ')
                        c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) == 0)
                        return unescape(c.substring(nameEQ.length, c.length));
                }
                return null;
            },
            remove:function (name) {
                $.mbCookie.set(name, "", -1);
            }
        };

        function donate() {
            $.mbCookie.set("ytpdonate", true);
            self.location.reload();
        }

        jQuery(function () {
            var hasDonate = <?php echo $donate ?> ;
            if (hasDonate || $.mbCookie.get("ytpdonate") === "true" ) {
                jQuery("#donate").remove();
                jQuery("#inlineDonate").remove()
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

    <!--END DONATE POPUP-->

    <form class="form-stacked" action="#">
        <fieldset>
            <legend><?php _e('mb.YTPlayer video parameters:', 'mbYTPlayer'); ?></legend>



            <label>
                <span class="label"><?php _e('Video url', 'mbYTPlayer'); ?> <span style="color:red">*</span>: </span>
                <textarea type="text" name="url" class="span5"></textarea><br>
                <span class="help-inline"><?php _e('YouTube video URLs (comma separated)', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('Opacity', 'mbYTPlayer'); ?>:</span>
                <select name="opacity">
                    <option value="1">1</option>
                    <option value=".8">0.8</option>
                    <option value=".5">0.5</option>
                    <option value=".3">0.3</option>
                </select>
                <span class="help-inline"><?php _e('YouTube video opacity', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('Quality', 'mbYTPlayer'); ?>:</span>
                <select name="quality">
                    <option value="default"><?php _e('auto detect', 'mbYTPlayer'); ?></option>
                    <option value="small"><?php _e('small', 'mbYTPlayer'); ?></option>
                    <option value="medium" selected="selected"><?php _e('medium', 'mbYTPlayer'); ?></option>
                    <option value="large"><?php _e('large', 'mbYTPlayer'); ?></option>
                    <option value="hd720"><?php _e('hd720', 'mbYTPlayer'); ?></option>
                    <option value="hd1080"><?php _e('hd1080', 'mbYTPlayer'); ?></option>
                    <option value="highres"><?php _e('highres', 'mbYTPlayer'); ?></option>
                </select>
                <span class="help-inline"><?php _e('YouTube video quality', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('Aspect ratio', 'mbYTPlayer'); ?>:</span>
                <select name="ratio">
                    <option value="auto" selected="selected"><?php _e('auto detect', 'mbYTPlayer'); ?></option>
                    <option value="4/3"><?php _e('4/3', 'mbYTPlayer'); ?></option>
                    <option value="16/9"><?php _e('16/9', 'mbYTPlayer'); ?></option>
                </select>
                <span class="help-inline"><?php _e('YouTube video aspect ratio'); ?>.</span>
                <br><span class="help-inline"> <?php _e('If "auto" the plug in will try to get it from Youtube', 'mbYTPlayer'); ?>.</span>
            </label>

            <label>
                <span class="label"><?php _e('Is inline', 'mbYTPlayer'); ?>: </span>
                <input type="checkbox" name="isinline" value="true" onchange="isInline()" />
                <span class="help-inline"><?php _e('Show the player inline', 'mbYTPlayer'); ?></span><br>
            </label>

            <div id="inlinePlayer" style="">
                <span class="label"><?php _e('Player width', 'mbYTPlayer'); ?> *: </span>
                <input type="text" name="playerwidth" class="span5" style="width: 60px" onblur="suggestedHeight()"/> px
                <span class="help-inline"><?php _e('Set the width of the inline player', 'mbYTPlayer'); ?></span><br><br>
                <span class="label"><?php _e('Aspect ratio', 'mbYTPlayer'); ?>:</span>
                <select name="inLine_ratio" style="width: 60px" onchange="suggestedHeight()">
                    <option value="4/3"><?php _e('4/3', 'mbYTPlayer'); ?></option>
                    <option value="16/9"><?php _e('16/9', 'mbYTPlayer'); ?></option>
                </select>
                <span class="help-inline"><?php _e('To get the suggested height for the player', 'mbYTPlayer'); ?></span><br><br>

                <span class="label"><?php _e('Player height', 'mbYTPlayer'); ?> *: </span>
                <input type="text" name="playerheight" class="span5" style="width: 60px" /> px
                <span class="help-inline"><?php _e('Set the height of the inline player', 'mbYTPlayer'); ?></span>
                <br>
                <span class="help-inline">* Add % to the unit if the width is set as percentage.</span>
            </div>

            <label>
                <span class="label"><?php _e('Show controls', 'mbYTPlayer'); ?>:</span>
                <input type="checkbox" name="showcontrols" value="true" onchange="showControlBox()"/>
                <span class="help-inline"><?php _e('show controls for this player', 'mbYTPlayer'); ?></span><br>
            </label>

            <div id="controlBox">
                <span class="label"><?php _e('full screen', 'mbYTPlayer'); ?>:</span>
                <input type="radio" name="realfullscreen" value="true" checked/>
                <span class="help-inline"><?php _e('Full screen containment is the screen', 'mbYTPlayer'); ?></span><br>

                <span class="label"></span>
                <input type="radio" name="realfullscreen" value="false"/>
                <span class="help-inline"><?php _e('Full screen containment is the browser window', 'mbYTPlayer'); ?></span><br><br>

                <input type="checkbox" name="printurl" value="true" checked/>
                <span class="help-inline"><?php _e('show the link to the original YouTube® video', 'mbYTPlayer'); ?>.</span>
            </div>

            <label>
                <span class="label"><?php _e('Autoplay', 'mbYTPlayer'); ?>: </span>
                <input type="checkbox" name="autoplay" value="true" checked/>
                <span class="help-inline"><?php _e('The player starts on page load', 'mbYTPlayer'); ?></span><br>
            </label>

            <label>
                <span class="label"><?php _e('Start at', 'mbYTPlayer'); ?>: </span>
                <input type="text" name="startat" class="span5" style="width: 60px" /> sec.
                <span class="help-inline"><?php _e('Set the seconds you want the player starts at', 'mbYTPlayer'); ?></span><br>
            </label>

            <label>
                <span class="label"><?php _e('stop at', 'mbYTPlayer'); ?>: </span>
                <input type="text" name="stopat" class="span5" style="width: 60px" /> sec.
                <span class="help-inline"><?php _e('Set the seconds you want the player stops at', 'mbYTPlayer'); ?></span><br>
            </label>

            <label>
                <span class="label"><?php _e('Audio volume', 'mbYTPlayer'); ?>:</span>
                <input type="text" name="volume" value="50" style="width: 60px"/>
                <span class="help-inline"><?php _e('Set the audio volume (from 0 to 100)', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('Mute video', 'mbYTPlayer'); ?>:</span>
                <input type="checkbox" name="mute" value="true"/>
                <span class="help-inline"><?php _e('mute the audio of the video', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('Loop video', 'mbYTPlayer'); ?>:</span>
                <input type="checkbox" name="loop" value="true"/>
                <span class="help-inline"><?php _e('loop the video once ended', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('Add raster', 'mbYTPlayer'); ?>:</span>
                <input type="checkbox" name="addraster" value="true"/>
                <span class="help-inline"><?php _e('add a raster effect', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('pause on window blur', 'mbYTPlayer'); ?>:</span>
                <input type="checkbox" name="stopmovieonblur" value="true"/>
                <span class="help-inline"><?php _e('pause the player on window blur', 'mbYTPlayer'); ?></span>
            </label>

            <label>
                <span class="label"><?php _e('Add Google Analytics', 'mbYTPlayer'); ?>:</span>
                <input type="checkbox" name="gaTrack" value="true"/>
                <span class="help-inline"><?php _e('add the event "play" on Google Analytics track', 'mbYTPlayer'); ?></span>
            </label>

        </fieldset>

        <div class="actions">
            <input type="submit" value="Insert shortcode" class="btn primary"/>
            or
            <input class="btn" type="reset" value="Reset settings"/>
        </div>
    </form>

    <script type="text/javascript">

        function isInline(){
            var inlineBox = jQuery('#inlinePlayer');
            if(!$("[name=isinline]").is(":checked")){
                inlineBox.slideUp();
                $("[name=showcontrols]").removeAttr("checked");
                $("[name=autoplay]").attr("checked", "checked");
            }else{
                inlineBox.slideDown();
                $("[name=showcontrols]").attr("checked","checked");
                $("[name=autoplay]").removeAttr("checked");
            }
            showControlBox();
        }

        function showControlBox(){
            var controlBox = jQuery('#controlBox');
            if(!$("[name=showcontrols]").is(":checked")){
                controlBox.slideUp();
            }else{
                controlBox.slideDown();
            }
        }

        function suggestedHeight(){
            var width = parseFloat(jQuery("[name=playerwidth]").val());
            var margin = (width*10)/100;
            width = width + margin;
            var ratio = jQuery("[name=inLine_ratio]").val();
            var suggestedHeight = "";
            if(width)
                if(ratio == "16/9"){
                    suggestedHeight = (width*9)/16;
                }else{
                    suggestedHeight = (width*3)/4;
                }
            jQuery("[name=playerheight]").val(Math.floor(suggestedHeight));
        }

        // tinyMCEPopup.onInit.add(function(ed) {

        var ed = top.tinymce.activeEditor;

        var form = document.forms[0],

            isEmpty = function(value) {
                return (/^\s*$/.test(value));
            },

            encodeStr = function(value) {
                return value.replace(/\s/g, "%20")
                    .replace(/"/g, "%22")
                    .replace(/'/g, "%27")
                    .replace(/=/g, "%3D")
                    .replace(/\[/g, "%5B")
                    .replace(/\]/g, "%5D")
                    .replace(/\//g, "%2F");
            },

            insertShortcode = function(e){
                var sc = "[mbYTPlayer ",
                    inputs = form.elements, input, inputName, inputValue,
                    l = inputs.length, i = 0;

                for ( ; i < l; i++) {
                    input = inputs[i];
                    inputName = input.name;
                    inputValue = input.value;
                    // Video URL validation
                    if (inputName == "url" && (isEmpty(inputValue) || (inputValue.toLowerCase().indexOf("youtube")==-1) && inputValue.toLowerCase().indexOf("youtu.be")==-1)){
                        alert("a valid Youtube video URL is required");
                        return false;
                    }
                    // inputs of type "checkbox", "radio" and "text"
                    if (
                        ((input.type == "text" || input.type == "textarea") && !isEmpty(inputValue) && inputValue != input.defaultValue)
                        || input.type == "select-one"
                        || input.type =="checkbox"
                        || input.type =="radio"
                        ) {

                        if (input.type =="checkbox") {
                            if(!input.checked)
                                inputValue = false;
                        }

                        if (inputName =="realfullscreen" && !input.checked)
                            continue;

                        if (inputName =="inLine_ratio")
                            continue;

                        sc += ' ' + inputName + '="' + inputValue + '"';
                    }
                }
                sc += "]";

                ed.execCommand('mceInsertContent', 0, sc);
                tinyMCEPopup.close();

                return false;
            };

        form.onsubmit = insertShortcode;

        // });
    </script>
    </body>
    </html>
<?php }
