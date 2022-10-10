=== OxyPowerPack ===
Contributors: Emmanuel Laborin
Requires at least: 4.7
Tested up to: 5.4
Requires PHP: 5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Missing Power Features for Oxygen Builder

== Description ==

OxyPowerPack
The Missing Power Features for Oxygen Builder:
Events & Actions
A powerful frontend interaction engine that allows you define automated actions for any element, to be performed when specific event happens. Manage classes, set element visibility, do page redirections, and much more, all performed on your visitor actions.


Easy Custom Attributes
OxyPowerPack allows effortless 3rd party JavaScript plugin integration by letting you add any amount of custom HTML attributes to any element, no prior configuration needed.


Maintenance Mode
Create your Coming Soon pages with Oxygen and activate them in a per role basis, great for membership sites!


Parallax Scroll Effect
Allows you to add smooth Parallax Scroll Effects to any element.


Additional Components
Countdown Timer, Contact Form 7, A/B Image Comparison, and more additional components soon. All easily customizable within Oxygen Builder.

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Oxygen > Maintenance Mode and fill the license field to activate automatic updates

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 2.0.3 =
* Fixed an issue with the interactivity engine that made impossible to isolate several power forms in a single page.
* Added a few starter Power Forms

= 2.0.2 Hotfix =
* Fixed an encoding issue with the email templates.

= 2.0.1 Hotfix =
* Fixed an issue that was showing the Form Submissions page in place of the OxyPowerPack settings page.
* Added Power Forms compatibility with Interactivity Engine. Now it is possible to run Interactivity Engine actions on successful Power Form submissions.
* Added settings for editing the notification email subjects for Power Form submisions.

= 2.0 "Chihuahua" =
* Added an enhancement that fixes most cases of a console error related to WP API, preventing the drawer to load.
* New element: Power Forms!

= 1.2.5 =

* Added a setting for Power Map popups to show them open by default on map load.
* Added address search to the initial map configurator. Works with MapBox API a MapBox token should be configured, otherwise the search box won't appear.
* Fixed an issue with the "CountDown Timer Finished" event in the Interactivity Engine that was preventing the execution of all actions in some setups.


= 1.2.46 =

* Added ability to activate the plugin with PHP version 5.x
* Updated the about window to reflect the new logo and the support email address.
* Refactored the inner workings of the custom attributes editor so it allows adding single quotes and other characters in the attribute value. Also, only true valid attribute names are allowed now.

= 1.2.45 =

Dummy release to fix a mistake with the version number, hence the jump from 1.2.3.

= 1.2.3 =

* Bug fix: It was impossible to set initial state for a map if no MapBox access token was configured.

= 1.2.2 =

* Added compatibility with Oxygen 3.1

= 1.2 =

* Bug fix: Bug related to admin menu parsing, causing JS console errors on some sites.
* Enhancement: Added some missing API features, fixing PHP Warning messages and improving the user experience with a better components display on the Oxygen's component list.
* Fix: Fixed a bug that was preventing the license deactivation.
* New feature: PowerMap component

= 1.1 =

* Bug fix: A weird 'oxyPowerpackTempSelector' css style was being output in frontend after using the "selector chooser" tool found in the Interactivity Engine and in the Popover UI. Thanks to Simon Kress for reporting this one.
* Bug fix: Popovers with 'click' trigger can now be closed by clicking again.
* Enhancement: Several stuff loaded from unpkg CDN is now loaded locally to lessen negative impact on site performance measuring tools.
* New feature: Now you can login again without leaving Oxygen Builder if the WordPress session expires while working in the builder. No more losing the latest changes.
* New feature: External SASS workflow via a Gulp script for PRO users who likes to code styles with SASS.

= 1.0.8.2 =

* Bug fix: fixed a bug that causes empty HTML attributes in frontend after removing the OxyPowerPack feature. This also caused the load of unneeded assets after a feature was not used anymore.
* Bug fix: 3rd party assets for popper and tippy JS libraries aren't redirections anymore, this will improve speed metrics.
* Bug fix: The maintenance mode window inside the Oxygen Builder was being rendered incorrectly on some browsers, specially if the WordPress installation had custom user roles.
* Enhancement: Updated the logo to meet the new website design.

= 1.0.8.1 =
Hotfix - solves an issue with multiple popovers with different themes and animations on the same page

= 1.0.8 =
* New feature: Popovers! Now we can attach a popover to any component, with simple tooltip text or with rich Oxygen-designed content.
* Bug fix: The drawer was weirdly popping up when hidden and an OxyPowerPack modal (attributes, parallax, etc) was opened.
* Bug fix: Mouseenter / mouseleave events now aren't triggered by child elements

= 1.0.7 =
* Enhancement / new feature: Evergreen timer. Countdown timer can now be configured as evergreen by setting a fixed timeframe countdown relative to the visitor.
* Enhancement: License code enter and activation is now a 1 step procedure. Also, license is now hidden.
* Bug fix: Fixed an "oxypowerpackEvents is not defined" error message that appeared occasionally inside the editor. That error wasn't causing any issues, but fixed it anyways.
* Enhancement: Added margin and padding style inputs to the Contact Form 7 controls.

= 1.0.6.2 =
* Bug fix: Lazy Loaded images were not working when configured as "Image URL" instead of "Media Library".
* Enhancement: Added "Open Modal" action - Now Oxygen modals can be triggered to open on OxyPowerPack events. I.E. Mouse over an element, element overlapping other element, countdown reaching zero, etc.
* Enhancement: Added "Start Overlapping Element" and "Finish Overlapping Element" events.

= 1.0.6.1 =
* Bug fix: Some WP-ADMIN direct links (in the OxyPowerPack WordPress tab) were not working.
* Bug fix: Incompatibility with the Gutenberg Integration plugin that was causing some assets being loaded in wp-admin pages.

= 1.0.6 =
* Renamed the "Start" tab to "Interactivity".
* New feature: LAZY LOAD IMAGES - Now Image Component can be configured for lazy loading with a single click. Images not in the viewport won't be loaded on page load, improving the load performance.
* Enhancement: Custom Attributes and Parallax buttons added back to the OxyPowerPack drawer. Too many support request from users not finding the options in the advanced tab =\
* Enhancement: Now globally disabled options (in the settings page) won't show un inside the builder to avoid confusions.
* Enhancement: New "Enter Viewport" and "Exit Viewport" events in the Interactivity tab.
* Enhancement: New "Scroll To Top" and "Scroll To Element" actions in the Interactivity tab.
* Bug fix: Quotation marks aren't allowed in any field in the action settings - they break Oxygen shortcodes. Now they are silently removed but before the fix, horrendous things happened.
* Bug fix: After using the selector chooser, the chosen selector was being set automatically to any other action with a "selector" field, in the same event.

= 1.0.5.2 =
HOTFIX: restored the reposition arrows for the floating button (moving icon) that was gone on 1.0.5 by mistake due the UI refactor. This was only affecting sites that recently installed OxyPowerPack. Old sites that updated from previous version weren't affected.

= 1.0.5.1 =
HOTFIX: restored the parallax settings window that was gone on 1.0.5 by mistake due the UI refactor.

= 1.0.5 =
* Bug fix: Now components with parallax speed = 0 (disabled) won't output any HTML attribute.
* Enhancement: Cleaner UI by moving the custom attributes button to the Oxygen's Advanced tab, and the parallax button to Advanced > Effects.
* Bug fix: Now custom attributes (and parallax, and events) work if you set them to OxyPowerPack components. It wasn't working due a limitation of the Oxygen undocumented API, fixed by inheriting the classes locally and re-implementing some functionality.
* New feature: Text rotator. SPAN elements can be configured with an array of words or phrases and it will rotate between them, with a typewritter animation. Feature to be enhanced with more animations and other settings.

= 1.0.4 =
* Improved WordPress tab: Added ability to create posts and library elements from inside OxyPowerPack.
* Improved CountDown Timer: Added segment padding control.
* Improve Parallax: Added setting to disable parallax effect on mobile - evaluated server side (PHP).
* Improve drawer: The drawer doesn't appear open on page load anymore, unless it is docked.
* Improve floating button: Smaller size, mouse hover feedback, remembers position between sessions.

= 1.0.3 =
Fixes a PHP warning that appeared on new sites if there were no previous 3rd party design set added

= 1.0 =
First stable version
