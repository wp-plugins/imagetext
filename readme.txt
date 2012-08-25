=== Plugin Name ===
Contributors: flashpixx
Tags: spam, secure, imprint, impressum, image, latex, text, qr code
Requires at least: 2.7
Tested up to: 3.4
Stable tag: 0.55
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WCRMFYTNCJRAU
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html

With this plugin text can be pasted as a picture in an article or page to protect mailaddresses or postaddresse against automated crawler.



== Description ==

With this plugin text can be pasted as a picture in an article or page. This is useful for text with personal data in order to protect against automated crawler. Each image can be individually 
configured. Also the plugin can create latex formular and QR codes. The plugin uses the Google Chart API and the text content is protected, so it can't be found within the HTML code. The plugin
has got also a widget for the theme, that can create an qr code with the permalink of the blog.



== Installation ==

1.  Upload the folder to the "/wp-content/plugins/" directory
2.  Activate the plugin through the 'Plugins' menu in WordPress


    
== Frequently Asked Questions ==

= How can I use the plugin ? =
Add to your content oof a page or article <pre>[imgtext type="latex | text | qrcode"]your content[/imgtxt]</pre>

= How can I use the plugin within a template ? =
You can call the method / function: <pre>de\flashpixx\imagetext\link::get( "latex | text | qrcode", "your content" )</pre>

= Where can I find the image options ? =
Take a look on the administration page of the plugin. Within the brackets [] you can find the option name, that can be passed
to the imtxt-tag or as an associative array to the method / function call



== Upgrade Notice ==

= 0.5 =
On this version the underlaying object-oriantated structure of the plugin uses the PHP namespaces, which added in the PHP version
5.3.0. So the plugin needs a PHP version equal or newer than PHP 5.3.0



== Changelog == 

= 0.55 =
* fixing a syntax error

= 0.5 =
* adding namespaces (supports with PHP 5.3.0)
* add template function: de\flashpixx\imagetext\link::get(...) returns the link to the image (thanks to Al Almor)
* remove http(s) option field of the widget form

= 0.4 =

* fixing encoding error for eg hebrew (thanks to Hetz for reporting the problem)
* change encoding calls to the multibyte (mb) calls
* fixing hash problem (same text on the same page but different image types creates images with the last type only)
* adding HTML tag remove option for QR and text image types

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
