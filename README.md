![divi-accessibility](https://cloud.githubusercontent.com/assets/6676674/26787287/72430f40-49d7-11e7-89ec-a5bf07eb0f97.png)

[![wordpress.org version badge](https://img.shields.io/wordpress/plugin/v/accessible-divi.svg)](https://wordpress.org/plugins/accessible-divi/) [![wordpress.org download count badge](https://img.shields.io/wordpress/plugin/dt/accessible-divi.svg)](https://wordpress.org/plugins/accessible-divi/)

A WordPress plugin that improves Divi accessibility in accordance with WCAG 2.0 guidelines. While there are many great plugins dealing with improving WordPress theme accessibility, this was developed for issues _specifically_ found within Divi.

## Features

+ Adds appropriate ARIA attributes
+ Improved keyboard navigation in menus
+ Fixes missing & incorrectly assigned labels
+ Makes modules such as __Toggle__ & __Accordion__ focusable and keyboard interactive
+ Adds a visual outline to focusable elements for _keyboard only_ navigation
+ Ability to change _keyboard only_ outline color
+ Fixes Divi incorrectly using screen reader classes
+ Adds skip navigation link optimized for Divi markup
+ Hide icons from screen readers which can affect reading of text
+ Fix duplicate menu IDs
+ Tota11y integration


Development
-----------

The working js/css snippets are, by default, included minifed. They can be forcefully included in their full expanded state by enabling the developer mode option in plugin settings. They will also respect the WP core `SCRIPTS_DEBUG` define value.

To check js/css snippets for any errors, run the dedicated `npm run lint` script.

To build the minified versions of the js/css snippets, run `npm run build` script.

While working on snippets, it may be beneficial to have them automatically re-built on file change. This is what the `npm run watch` script does.

To package an intermediate (throwaway) plugin zip archive for testing, use the `npm run package` script.

To actually build a releaseable package, use the `npm run release [-y] [--version=X.X.X]`. This will lint everything, build the languages catalog file, and normalize version numbers accross files (main php file, package.json and readme.txt). It will then rebuild everything and produce a versioned zip archive.

The release version can either be supplied via a command line parameter (`--version=x.x.x`), or it will be inferred from the files that might be containing the version number (main php file and package.json). If the version number is being inferred, the highest one is the version that will be used.

If the final resolved release version is different than what's in package.json and/or main php file, they can optionally be updated to match. By default, the prerelease script will ask for user input whether to do this or not. This can be prevented using the command line flag `-y` - if this command line flag is set, the files will always be updated if necessary without any further input.


## Resources

+ [Web Content Accessibility Guidelines (WCAG) 2.0](https://www.w3.org/TR/WCAG20/)
+ [Tota11y](https://khan.github.io/tota11y/)
+ [Divi](https://www.elegantthemes.com/gallery/divi/)

## Support

If you would like to make a donation, we encourage you to consider donating to [Knowbility](https://knowbility.org/) and help support their mission to provide access to technology for people with disabilities.

## Credit

Plugin created by [CampusPress](https://campuspress.com). Plugin icon based off of [The Accessible Icon Project](http://accessibleicon.org/).
