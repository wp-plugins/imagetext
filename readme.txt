=== Plugin Name ===
Contributors: flashpixx
Tags: spam, secure, imprint, impressum, image, latex, text, qr code
Requires at least: 2.7
Tested up to: 3.3.1
Stable tag: trunk
License: GPLv3

With this plugin text can be pasted as a picture in an article or page to protect mailaddresses or postaddresse against automated crawler.

== Description ==

With this plugin text can be pasted as a picture in an article or page. This is useful for text with personal data in order to protect against automated crawler. Each image can be individually 
configured. Also the plugin can create latex formular and QR codes. The plugin uses the Google Chart API and the text content is protected, so it can't be found within the HTML code. The plugin
has got also a widget for the theme, that can create an qr code with the permalink of the blog.

== Installation ==

1.  Upload the folder to the "/wp-content/plugins/" directory
2.  Activate the plugin through the 'Plugins' menu in WordPress
3.  Use in your content the tag [imgtxt] your image content [/imgtxt] to create images (options see at the plugin admin panel).


== Changelog == 

= 0.35 =

* fixing some array errors

= 0.3 =

* fully rewritten
* uses Google Chart API
* create text, latex and QR codes


= 0.2 =

* adding translation (english, german)
* fixing some problems with the  wordpress codex
* change installation instruction
* add install and remove hooks for all options
