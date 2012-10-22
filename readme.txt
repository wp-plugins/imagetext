=== Plugin Name ===
Contributors: flashpixx
Tags: spam, secure, imprint, impressum, image, latex, text, qr code
Requires at least: 3.2
Tested up to: 3.4.2
Stable tag: 0.55
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WCRMFYTNCJRAU
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html


With this plugin text can be pasted as a picture in an article or page to protect mailaddresses or postaddresse against automated crawler.


== Description ==

With this plugin text can be pasted as a picture in an article or page. This is useful for text with personal data in order to protect against automated crawler. Each image can be individually 
configured. Also the plugin can create latex formular and QR codes. The plugin uses the Google Chart API and the text content is protected, so it can't be found within the HTML code. The plugin
has got also a widget for the theme, that can create an qr code with the permalink of the blog.

= Features =

* creating QR codes and LaTeX formular
* plugin call can be used within your templates
* generate images with a free size, color, content
* free definition for the image tag



== Installation ==

1.  Upload the folder to the "/wp-content/plugins/" directory
2.  Activate the plugin through the 'Plugins' menu in WordPress and take a look to the administration interface



== Requirements ==

* Wordpress 3.2 or newer
* PHP 5.3.0 or newer 



== Shortcode ==
Add to your content of a page or article <pre>[imgtext type="latex | text | qrcode"]your content[/imgtxt]</pre>

    
    
== Frequently Asked Questions ==

= How can I use the plugin within a template ? =
You can call the method / function: <pre>de\flashpixx\imagetext\link::get( "latex | text | qrcode", "your content" )</pre>
The method returns a string with the full image tag. The third parameter is optional and can be an associative array with
options. The option names are shown in the administration interface

= Where can I find the image options ? =
Take a look on the administration page of the plugin. Within the brackets [] you can find the option name, that can be passed
to the imtxt-tag or as an associative array to the method / function call

= Can I add an image to a static HTML page ? =
No you can not do this, because the image is created dynamically and a session value is passed to the image generation function.
You need the session value, that is unique within the browser session and you need also read access to the session. The session
can read only by the webserver (virtual host). So you can not pass any images between different domains or static pages.



== Upgrade Notice ==

= 0.5 =
On this version the underlaying object-orientated structure of the plugin uses the PHP namespaces, which added in the PHP version
5.3.0. So the plugin needs a PHP version equal or newer than PHP 5.3.0



== Changelog == 

= 0.6 =

* change language domain to "imagetext"

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
