=== Divi Accessibility ===
Contributors: campuspress, JoeFusco, alexstine
Tags: divi, accessibility, accessible, navigation, wcag, a11y, section508, focus, labels, aria
Requires at least: 3.5.0
Tested up to: 5.3.2
Stable tag: trunk
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Improve Divi accessibility in accordance with WCAG 2.0 guidelines.

== Description ==

A WordPress plugin that improves Divi accessibility in accordance with WCAG 2.0 guidelines. While there are many great plugins dealing with improving WordPress theme accessibility, this was developed for issues specifically found within Divi.

* Adds appropriate ARIA attributes
* Improved keyboard navigation in menus
* Fixes missing & incorrectly assigned labels
* Makes modules such as Toggle & Accordion focusable and keyboard interactive
* Adds a visual outline to focusable elements for keyboard only navigation
* Ability to change keyboard only outline color
* Fixes Divi incorrectly using screen reader classes
* Adds skip navigation link optimized for Divi markup
* Hide icons from screen readers which can affect reading of text
* Fix duplicate menu IDs
* Tota11y integration

= Contribute on GitHub =

[https://github.com/campuspress/divi-accessibility/](https://github.com/campuspress/divi-accessibility/)

= Support =

If you would like to make a donation, we encourage you to consider donating to [Knowbility](https://knowbility.org/) and help support their mission to provide access to technology for people with disabilities.

= Credit =

Plugin created by [CampusPress](https://campuspress.com). Plugin icon based off of [The Accessible Icon Project](http://accessibleicon.org/).


== Installation ==

1. Upload 'divi-accessibility' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the new menu item "Accessibility" under the Divi menu for settings


== Screenshots ==

1. Divi Accessibility settings page


== Changelog ==

= 1.2.6 =
* Social links now have text, thanks to maco.
* Full jQuery support.

= 1.2.5 =
* Update WordPress compatibility for 5.2.3.
* Fix undefined variable.
* Add main role to main content.
* Add option to enable mobile menu Aria enhancements.

= 1.2.4 =
* Fix keyboard navigation with submenus.
* Fix submenu highlight on mouse hover.
* Fix CSS warnings.

= 1.2.3 =
* Submenu item highlighting when mouse moves over links.

= 1.2.2 =
* Landmark refactoring for navigation.
* Add better support for submenus.

= 1.2.1 =
* Add better aria attribute support to navigation menus, comment forms, etc.
* CSS fixes.

= 1.2 =
* Add option to remove id attribute from list items in navigation menus

= 1.1 =
* Add option to hide decorative icons from screen readers
* Add hidden submit button to search
* Add alert role to success/error form message
* Increase color contrast of skiplink to meet WCAG AAA
* Fix contact module form not validating properly when using captcha
* Fix error with label not being added to search form
* Prevent spacebar from scrolling page when toggles have focus
* Remove up and down keys from changing tabs

= 1.0.4 =
* Fix contact module form not validating properly when using captcha

= 1.0.3 =
* Add missing quote in viewport tag causing issues in head

= 1.0.2 =
* Fix Divi viewport meta tag to make theme more accessibile

= 1.0.1 =
* Improve ARIA for Tab module

= 1.0.0 =
* Initial Release
