=== Thank Me Later ===
Contributors: bbosh
Plugin URI: http://www.brendonboshell.co.uk/thank-me-later-wordpress-plugin/
Tags: comment, comments, email, newsletter, social
Requires at least: 3.1
Tested up to: 4.0
Stable tag: 3.3.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send a 'thank you' email to your blog's commenters.

== Description ==

Thank Me Later sends 'thank you' emails to your commenters. Simply write a message saying thanks and it will be emailed after a time of your choice -- 5 minutes, a day, a month, whenever!

*Latest Update (3.3): Email open tracking*.

Languages: Español (es_ES), Français (fr_FR), Italiano (it_IT), Polski (pl_PL), Türkçe (tr_TR), தமிழ் (ta_LK), 简体中文 (zh_CN), [Translate into your language](https://www.transifex.com/projects/p/thank-me-later/)
    
= Invite readers back to your blog =

Thank Me Later attracts readers back to your blog and asks them to check for replies to their comments. Other uses of the plugin include:

* Linking to your RSS feed to get more readers;
* Linking to your Twitter or Facebook pages to get more followers or likes; and
* Giving a discount for purchases.

= Features =

* **HTML emails**: write your email in text and it is changed into a HTML email with one click.
* **Shortcodes**: use shortcodes like [name] to make your emails more personal.
* **Targeting**: write emails for each category or post on your blog.

Follow me on Twitter: [@brendonboshell](http://twitter.com/brendonboshell)

= Descriptions in other languages = 

* **es_ES**: Enviar un correo electrónico de "gracias" a los comentaristas de su blog.
* **fr_FR**: Envoyer un E-mail de remerciement aux commentateurs de votre blog.
* **it_IT**: Manda una email di ringraziamento a chi commenta sul tuo blog.
* **pl_PL**: Wysyłaj podziękowania za komentarz
* **tr_TR**: Sitenize yorum yazanlara 'Teşekkürler' maili ilet.
* **ta_LK**: உங்கள் வலைப்பதிவின் ​கருத்தாளர்களுக்கு ​ஒரு 'நன்றி' மின்னஞ்சல் அனுப்பவும்.

= Translators =
* Español (es_ES, Spanish). Thanks to [Javier Tapia Torres](http://traduccionestapia.romhackhispano.org).
* Français (fr_FR, French). Thanks to Piexl.
* Italiano (it_IT, Italian). Thanks to [Aerendir (ecommercers.net)](http://ecommercers.net/).
* Polski (pl_PL, Polish). Thanks to Mateusz Jaworowicz.
* Türkçe (tr_TR, Turkish). Thanks to [adobewordpress.com](http://www.adobewordpress.com/yorumculariniza-tesekkur-postasi-yollayin).
* தமிழ (ta_LK; Tamil): Thanks to [Mads Phikamphon](http://www.findhold.dk/).
* 简体中文 (zh_CN, Simplified Chinese). Thanks to [@ichunxiao](http://twitter.com/ichunxiao).

== Installation ==

To install, click 'Plugins' > 'Add New' from WordPress. Search 'thank me later' and click 'Install Now'.

(Alternatively, copy the contents of the `thank-me-later` directory into `/wp-content/plugins/thank-me-later/` and activate from the "Plugins" menu).

To get started, click 'Thank Me Later' from Wordpress (it's beneath the 'Settings' menu; not inside of it).

== Frequently Asked Questions ==

= Should I use the default settings? =

No. The default settings are generic. You should take time to write a message which offers some value -- for example, you may ask people to join you on Twitter or offer them a discount on their next purchase.

= How do I email a commenter just once? =

When writing your message, look under 'Schedule'. Set 'Maximum send limit' to 1. It is strongly recommended you make this change.

= My emails aren't sending on time. How do I fix this? =

If you have recently installed Thank Me Later, please wait 12 hours. On some installations of Wordpress, Thank Me Later is unable to schedule email sends correctly. Thank Me Later is able to detect this issue and will revert to "Legacy Mode" to send emails after 12 hours.

If emails are still not sending on time, your blog may not be receiving enough visits to send the emails -- emails are only sent on page loads. Possibly, caching is preventing the scheduled events from running. If you know what you are doing, you can create a cron job to call WP-Cron at regular intervals -- see [here](http://bitswapping.com/2010/10/using-cron-to-trigger-wp-cron-php/) for more details.

= Why does this plugin send me (the author) an email? =

This is by design. Emails are sent to all commenters, regardless of whether they are authors or readers. If the messages are annoying you, you probably haven't configured Thank Me Later correctly. Ensure you set 'Maximum send limit' to 1 to limit the number of emails that are sent to a particular commenter.

== Changelog ==

= v3.2.3 =
* Added: it_IT translation

= v3.2.1 =
* Added: es_ES translation

= v3.1.1 =
* Bugfix: Some emails could not be removed from the unsubscribe list
* Added: tr_TR translation

= v3.1 =
* Added: Link to opt out automatically appended to emails
* Added: zh_CN translation

= v3.0.7 =
* Bugfix: changed email encoding to support old versions of Outlook
* Bugfix: fix excessive-paragraphing due to Windows-style line endings

= v3.0.6 =
* Bugfix: Files went missing in 3.0.5 release

= v3.0.5 =
* Bugfix: Fix 'out of memory' bug.

= v3.0.4 =
* Added: support for Disqus

= v3.0.3 =
* Bugfix: Email encoding fix
* Support for WP 3.1
* Bugfix: Workaround a mail() bug on old versions of PHP

= v3.0.2 =
* Bugfix: Mark old messages as sent, avoid sending twice

= v3.0.1 =
* Bugfix: Support for PHP 5.2.8+

= v3.0 =
* Addition of statistics panel and sent statistics for individual messages
* Removal of send probability
* Redesign of "Messages" panel
* Support for multipart messages
* Change from `<$VAR>` syntax to `[var]` in messages
* Removed support for (controversial) eval()ulation of code in messages.
* Support for shortcodes to be used in messages
* Move to HTML templates -- write just a "text" version of the message, and it will be placed in HTML layout (with HTML-specific shortcode output and HTML-only content available)
* "Restrict by tags" and "Restrict by categories" simplified.
* Live preview of text/HTML message when editing
* Remove message defaults
* Move "do not send more than [n] messages" to message-level.
* Removal of "comment gap" and "send gap" settings
* Changes to upgrade mechanism
* Use of WP_List_Table for displaying messages.

= v2.0.0.1 =
* Fixes a bug which displays ~'Sorry: you need to be an administrator' message for all users.

= v2.0 =
* Multiple messages
* 'Better' i18n support
* nl2br() functionality, to make writing HTML messages easier.
* Syntax highlighting for PHP (limited support).
* User restrictions (ie, only send to logged in users)
* Opt out page.
* Modularised interface (and code).

= v1.5.3 =
* Uses different mode of scheduling sends, which is hopefully more reliable than WP-CRON.

= v1.5.1 =
* Fixed some minor timing issues.
* Fixed menu formatting in WP 2.7.

= v1.5 =
* Uses WP-Cron to process queue at regular intervals.
* Comes with pre-installed "templates", which allow you to quickly place titles, excerpts and URLs into your emails dynamically.
* Allows you to restrict posts by tags and categories.

== Upgrade Notice ==

= 3.1 =
Link to opt out automatically appended to emails

= 3.0.5 =
Lots of new features, including HTML emails, post targeting and shortcodes.

= 3.0.4 =
Lots of new features, including HTML emails, post targeting and shortcodes.

= 3.0.1 =
Lots of new features, including HTML emails, post targeting and shortcodes.

= 3.0 =
Lots of new features, including HTML emails, post targeting and shortcodes.

== Screenshots ==

1. Example of default email sent by Thank Me Later