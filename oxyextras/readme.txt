=== OxyExtras ===
Contributors: David Browne, Gagan Goraya, Sridhar Katakam
Tags: oxygen builder
Requires at least: 4.7
Tested up to: 10.0.0
Requires PHP: 5.6
Stable tag: trunk
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Lightweight component library for Oxygen Builder

== Description ==

Current Components:

1. Adjacent Posts
2. Alert Box
3. Back to Top
4. Burger Trigger
5. Carousel Builder
6. Copyright Year
7. Counter
8. Fluent Form
9. Header Search
10. Login Form
11. Lottie Animation
12. Off Canvas
13. Post Modified Date
14. Post Terms
15. Reading Progress Bar
16. Reading Time
17. Read More / Less
18. Reusable Block
19. Slide Menu
20. Social Share

== Installation ==

1. Click on the download link in your purchase confirmation email if you have not already downloaded it after your purchase.

2. Download the plugin's zip file.

3. Go to Plugins > Add New in your WordPress admin. Click "Add New" button, then "Upload Plugin" button, then "Choose File", browse to and select the plugin's zip file.

4. Activate the plugin.

5. Enter the license key and activate your plugin license at Oxygen > OxyExtras > License.

Valid license key should be entered for the plugin to function and to receive automatic updates.

== Changelog ==

= 1.1.7 ( September 02, 2020 ) =
* [Carousel Builder] - Added support for Easy Posts, Woo Components or using custom elements as cells.
* [Carousel Builder] - Added the ability to turn off carousel functionality at any breakpoint.
* [Carousel Builder] - Added Scale & transition controls for page dots.
* [Carousel Builder] - Added support for parallax elements (using the Repeater).
* [Lottie] - JSON now lazy loaded (can be disabled).
* [Off Canvas] - Added 'staggered animation' option for inner elements using Oxygen's scroll animation.
* [Preloader] - Fixed a rare issue where some elements would appear before preloader.
* [Burger Trigger] - Fixed an issue with some unpredictable behavior when used with slide menu.
* [Fluent Form] - Fixed hover opacity for submit button.
* [General] - Fixed an issue with the text fields not allowing quotes.

= 1.1.6 ( August 17, 2020 ) =
* [Carousel Builder] - New component for visually building carousels (for use with repeater component).
* [Offcanvas] - Any elements inside the offcanvas can now make use of Oxygen's scroll animations, triggered when the offcanvas is opened (rather than on page load).
* [Slide Menu] - Added new menu alignment controls and focus controls for the sub menu buttons.
* [Lottie] - Added option to render animation as <canvas> instead of <svg> (to prevent rare cases of some animations flickering in Safari).

= 1.1.5 ( August 06, 2020 ) =
* [Fluent Form] - Fixed padding issue on phone/mobile field when flag is disabled.
* [Cart Counter] - Fixed issue with cart number not updating (present only in v1.1.4).

= 1.1.4 ( August 05, 2020 ) =
* [Toggle Switch] New component for switching content or toggling classes.
* [Content Switcher] New component allowing to switch between two elements (for use with the toggle switch).
* [Burger Trigger] - Added 'scale' changing size of burger independent of size of the button.
* [Cart counter] - Accessibility improvement -  Dropdown cart now can be accessed by keyboard.
* [Counter] - Number fields now accept dynamic data.
* [Off Canvas] - Accessibility improvement - Now able to change the focus when offcanvas opened to any selector inside.
* [Off Canvas] - Trigger can now be from inside dynamically added content.
* [Slide Menu] - Added site navigation schema markup option.
* [Fluent Form] - Colour controls added for Invalid input state.
* [Fluent Form] - Label Typography font weight issue fixed.
* [Fluent Form] - Smart UI checkbox issue fixed on iPhone.
* [Fluent Form] - Fixed issue with form dropdown not appearing in Oxygen if a form name contained disallowed words.
* [Fluent Form] - Added Form ID control which accepts dynamic data.
* [Social Share] - Added Support for Pinterest, WhatsApp & Telegram.
* [Social Share] - Fixed issue with email share link when post titles contained certain characters.
* [Header Search] - Fixed a W3C Validator issue.
* [Lottie] - ACF field can now be used to get the JSON URL.
* [Lottie] - Cursor position control can now be relative to any container element.
* [Lottie] - Added MouseOver control (similar to hover but with frame control & reverse animation when use stops hovering.
* [General] Small performance improvements for users on Oxygen v3.4+ (less inline JS output where possible).
* [General] In-builder performance improvements (loading less JS).
* [General] Added support ready for Oxygen v3.5 (new preset code).

= 1.1.3 ( June 25, 2020 ) =
* Added checkboxes in the plugin's settings page so that only selected components can be added to the Oxygen editor.

= 1.1.2 ( June 24, 2020 ) =
* [Alert Box] - Can now be used as a header notification bar, with 'SlideUp' close option added.
* [Fluent Form] - Compatibility with v3.6.0.
* [Header Search] - More control over icon positioning.
* [Mini Cart] - Fixed scrollbar issue, more controls for positioning of inner elements.
* [Off Canvas] - Added z-index controls for both backdrop & inner content.

= 1.1.1 ( June 18, 2020 ) =
* [Off Canvas] - Fixed an issue with chosen selector not triggering Off Canvas.
* [Preloader] - Fixed slight glitchy animation on iPhones.

= 1.1 ( June 18, 2020 ) =
* [Cart Counter] - New component for displaying Woocommerce Cart Count.
* [Mini Cart] - New component for displaying Woocommerce Mini Cart.
* [Preloader] - New component for building preloaders to hide FOUC or FOUT.
* [Fluent Form] - Added style controls for Payment Summary and Checkable Grids.
* [Fluent Form] - Added "General Styles" for overall form typography, button transitions.
* [Off Canvas] - Slide from Top/Button now available.
* [Off Canvas] - Can now be triggered from inside a modal if click trigger is also a trigger for closing the modal.
* [Slide Menu] - Prevent issue with browsers auto-scrolling with hashlinks & sub menu items collapsing height.

= 1.0.9 ( June 13, 2020 ) =
* [Fluent Form] Added support for Smart UI styling, added more style options and renamed controls to match official Fluent Forms.
* [Fluent Form] Fixed code issue if FF not active.
* [Reading Time] Added "Text After (Singular)" and "Text After (Plurarl)" settings for customizable After text.
* [Off Canvas] Menu items with hash links inside Off Canvas can now open submenu by clicking entire menu item.
* Fixed license activation/deactivation issue especially after site has been migrated to a new location.
* A few other general code polishes.

= 1.0.8 ( June 10, 2020 ) =
* [Adjacent Posts] Fixed an issue with the next post showing even if empty.

= 1.0.7 ( June 09, 2020 ) =
* [Fluent Form] Added a check to ensure that there will be no errors if Fluent Forms is not active.

= 1.0.6 ( June 09, 2020 ) =
* [Fluent Form] Added dropdown for selecting form by name, instead of ID
* [Lottie Animation] Added Click Animation Trigger with optional reverse second clicks.
* [Lottie Animation] Easier controls with sliders for frames and speed & width / height.
* [Back to Top] Added ability to include any element inside button to build manually.
* [Back to Top] Added option to be visible only when scrolling up.
* [Off Canvas] Fixed issue with iPhone 5/6 with backdrop.
* [Off Canvas] Added auto close if any hash links clicked from inside off canvas.
* [Adjacent Posts] Prev/next posts can now be split across two components for more flexible positioning.
* [Alert Box] Now can add divs inside without issue.

= 1.0.5 ( June 05, 2020 ) =
* [Fluent Form] Added more focus styles to forms (& more style options for multi step forms).
* [Lottie Animation] Added Top/Bottom offset controls to scroll animations.
* [Lottie Animation] Added cursor position based animation.
* [Lottie Animation] Added the ability for sync scrolling to any container.
* [Lottie Animation] Added the ability to toggle loop on/off.
* [Lottie Animation] Fixed an issue with scrolling not working with multiple animations.

= 1.0.4 ( June 04, 2020 ) =
* Fixed - Button hover issue in Back to Top.
* Fixed - Off Canvas builder visibility causing elements to be unclickable.
* New - Option to add custom aria label to Burger Trigger button.
* Fixed - Prevent duplicate IDs on search icons.
* Edit - Removed the ability to change Slide Menu type in media queries to prevent issues.

= 1.0.3 ( June 03, 2020 ) =
* Fixed duplicate ID on icons in the Adjacent Posts component.
* Fixed a bug in the Slide Menu component causing it not to function properly when hidden by default.
* Moved "Open / Close Trigger selector" setting in Off Canvas component to the Primary tab for easier access.
* Changed container elements' tags from "span" to "div" in Back to Top component's output.
* Added a screenshot under the License Key form showing where the extras added by the plugin can be found.

= 1.0.2 ( June 02, 2020 ) =
* Fixed Burger Trigger and Off Canvas components not appearing.

= 1.0.1 ( June 02, 2020 ) =
* Added a link to Settings under the plugin name.

= 1.0.0 ( June 02, 2020 ) =
* Initial release