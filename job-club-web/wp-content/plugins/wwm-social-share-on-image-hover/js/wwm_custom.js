function execute_wwmfun(e){var t=jQuery('meta[property="og:image"]').attr("content");var n=jQuery('meta[name="og:description"]').attr("content");var r=jQuery('meta[name="description"]').attr("content");var i=WWWM_FilterData(jQuery('meta[name="og:title"]').attr("content"));if(jQuery.trim(i)=="")var s=WWWM_FilterData(jQuery("title").text());else var s=i;var o=WWWM_FilterData(n);var u=jQuery(e).parent().prev("img").attr("src");var a=document.location.href;var f=WWWM_FilterData(r);var l="";if(jQuery.trim(u)=="")u=t;if(jQuery.trim(n)=="")o=s;if(jQuery.trim(r)=="")r=s;if(jQuery(e).hasClass("wwm_facebook")){wwm_fb_share(s,a,u,f,o)}if(jQuery(e).hasClass("wwm_twitter")){var l="http://twitter.com/home?status="+escape(s)+"+"+encodeURIComponent(a);wwm_common_share(l)}if(jQuery(e).hasClass("wwm_gplus")){var l="https://plus.google.com/share?url="+encodeURIComponent(a);wwm_common_share(l)}if(jQuery(e).hasClass("wwm_pinit")){var l="http://pinterest.com/pin/create/bookmarklet/?media="+encodeURIComponent(u)+"&url="+encodeURIComponent(a)+"& is_video=false&description="+o;wwm_common_share(l)}if(jQuery(e).hasClass("wwm_tumblr")){var l="http://www.tumblr.com/share/photo?source="+encodeURIComponent(u)+"&caption="+o+"&clickthru="+encodeURIComponent(a);wwm_common_share(l)}if(jQuery(e).hasClass("wwm_linked")){var l="http://www.linkedin.com/shareArticle?mini=true&url="+encodeURIComponent(a)+"&title="+s+"&source="+encodeURIComponent(a);wwm_common_share(l)}}function WWWM_FilterData(e){if(jQuery.trim(e)!="")return e.replace(/[^\w\sàâäôéèëêïîçùûüÿæœÀÂÄÔÉÈËÊÏÎŸÇÙÛÜÆŒ]/g,"");else return""}function wwm_fb_share(e,t,n,r,i){FB.ui({method:"feed",name:e,link:t,picture:n,caption:r,description:i},function(e){if(e&&e.post_id){}else{}})}function wwm_common_share(e){window.open(e,"","menubar=no,toolbar=no,resizable=yes,scrollbars=no,height=400,width=600");return false}jQuery(document).ready(function(e){jQuery(".wwm_socialshare_imagewrapper").hover(function(){if(jQuery(this).find(".wwm_social_share").length==0){jQuery(this).find("img").after(jQuery(".wwm_social_share:eq(0)").clone())}jQuery(this).find(".wwm_social_share").show()},function(){jQuery(".wwm_social_share").hide()});var t=!!jQuery.fn.on;if(t){jQuery(".wwm_socialshare_imagewrapper").on("click",".wwm_social_share li",function(e){e.preventDefault();execute_wwmfun(this);})}else{jQuery(".wwm_socialshare_imagewrapper .wwm_social_share li").live("click",function(e){e.preventDefault();execute_wwmfun(this);})}})