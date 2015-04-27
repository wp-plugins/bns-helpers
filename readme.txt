=== BNS Helpers ===
Contributors: cais
Donate link: http://buynowshop.com
Tags: child-pages, shortcodes, text widgets, bns login, plugin-only
Requires at least: 3.6
Tested up to: 4.2
Stable tag: 0.3
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

A collections of shortcodes and other helpful functions

== Description ==
A collection of shortcodes and helper functions to provide some additional output and compatibilities.

The current version of BNS Helpers includes the following:

* Allows Text Widgets to parse shortcode structures
* Turns on the `dashicons` option in BNS Login (https://wordpress.org/plugins/bns-login) replacing its text output
* Adds the `[ child_pages ]` shortcode to provide a list of child-pages of the current page
* Adds the `[ dropdown_child_pages ]` shortcode to provide a drop-down list of child-pages of the current page
* Adds the `[ tool_tip ]` shortcode to provide easy hover effect balloon text wrapped by the shortcode

== Installation ==

This section describes how to install the plugin and get it working.

1. Go to the "Plugins" menu in the Administration Panels ("dashboard").
2. Click the 'Add New' link.
3. Click the "Upload" link.
4. Browse for the bns-helpers.zip file on your computer; upload; and, install accordingly.
5. Activate.

-- or -

1. Go to the "Plugins" menu in the Administration Panels ("dashboard").
2. Click the 'Add New' link.
3. Search for BNS Helpers.
4. Install.
5. Activate.

Please read this article for further assistance: http://wpfirstaid.com/2009/12/plugin-installation/

----

= Usage =
The "child-pages" shortcodes will work as is without any additional parameters in any page.
For additional details of what parameters are available, please have a look at the following functions relevant to the shortcode:

* `[ child_pages ]` - https://developer.wordpress.org/reference/functions/wp_list_pages/
* `[ dropdown_child_pages ]` - https://developer.wordpress.org/reference/functions/wp_dropdown_pages/
* `[ dropdown_child_pages ]` (advanced) - https://developer.wordpress.org/reference/functions/get_pages/

The `[ tool_tip ]` shortcode has one parameter `character` which is set to an exclamation mark (!) as the default.
An example usage is: `[ tool_tip character=?]This is the tool tip text![ /tool_tip ]` (with no spaces inside the square brackets)

== Frequently Asked Questions ==
Q: Where can I get support for this plugin?
You can look for support for BNS Helpers in many places:

* The WordPress community: https://wordpress.org/support/plugin/bns-helpers/
* The BNS Helpers GitHub repository: https://github.com/Cais/bns-helpers/
* THe BNS Helpers home page: http://buynowshop.com/plugins/bns-helpers/

Q: How do I turn on the Text Widget option to parse shortcodes?
Just by activating this plugin it will automatically be turned on.

Q: How does the BNS Login integration work?
If you have BNS Login (https://wordpress.org/plugins/bns-login) installed this plugin will change the text to use `dashicons` making BNS Login less obtrusive.

== Screenshots ==
1. Use the shortcode in a Text widget to create an automated list of child-pages of the current page (if they exist).
2. You can exclude pages from one place and show them in another.
3. Shortcode displays output exactly where you place it and uses your theme's styles to display the output.

== Other Notes ==

= Copyright 2015  Edward Caissie  (email : edward.caissie@gmail.com) =

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 2,
  as published by the Free Software Foundation.

  You may NOT assume that you can use any other version of the GPL.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

  The license for this software can also likely be found here:
  http://www.gnu.org/licenses/gpl-2.0.html

= Screenshot Source =
Screenshots were captured using the latest version of Opus Primus (https://wordpress.org/themes/opus-primus) as the baseline

== Upgrade Notice ==
Please stay current with your WordPress installation, your active theme, and your plugins.

== Changelog ==
= 0.3 =
* Released 2015
* Provide (temporary?) support against stored XSS issue with too long comments

= 0.2 =
* Released April 2015
* Added `[ tool_tip ]` shortcode
* Minor code clean-up

= 0.1 =
* Released January 2015
* Initial Release.