=== Rich Reviews by Starfish ===
Contributors: starfishwp, silvercolt45, thefiddler, nuanced-media, hiren1612
Donate link: https://starfish.reviews/
Tags: reviews, testimonials, ratings, rich snippets, ratings schema
Requires at least: 4.5
Requires PHP: 5.6
Tested up to: 5.7
Stable tag: 1.9.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed user reviews and rich snippet (aka Schema) ratings anywhere on your site.

== Description ==

[Rich Reviews](https://starfish.reviews/rich-reviews/?utm_source=rich_reviews_plugin&utm_medium=wordpress_org_page) empowers you to easily capture user ratings, reviews, and testimonials for your business, website, or individual products/pages and display them on your WordPress page or post with a shortcode.

With Google My Business emphasizing the importance of testimonials, reviews are becoming integral for the success of any business, product, or service online. It includes review schema, also known as structured data, or rich snippets to let Google and other search engines display overall ratings stars in their search results or elsewhere.

[](http://coderisk.com/wp/plugin/rich-reviews/RIPS-BKYua9zarY)

= Rich Reviews Features =

* Three types of reviews: per-page or per-post, category, or global reviews allow you to customize to your needs. Whether you want users to review products, categories, or your entire website, Rich Reviews gives you the control.
* Moderated submissions allows you to choose which reviews are added to your site.
* Built completely around shortcodes, so you can include any of the three key features on any page, post, sidebar, footer, or widget on your site.
* Simple design allows compatibility across themes.
* Includes external stylesheet for ease of customization.
* Shows aggregate reviews microformat (hReview-aggregate) Schema so that site ratings can be displayed in Google results with rich snippets.
* Minimalist, lightweight, and efficient code means that your site won't be slowed down, and your users won't have any trouble leaving a review.

= Need More Reviews? =

Need to get more reviews on Google, Facebook, TrusPilot, Amazon, TripAdvisor, or any online review platform? Check out our review genration and marketing plugin [Starfish Reviews](https://starfish.reviews/?utm_source=rich_reviews_plugin&utm_medium=wordpress_org_page). It even works great with Rich Reviews.

= Check Out Our Other Plugins =
* [Starfish Review Generation](https://wordpress.org/plugins/starfish-reviews/) - generate more customer reviews on online review platforms like Google, TrustPilot, Facebook, and more.
* [Plugin Reviews](https://wordpress.org/plugins/plugin-reviews/) - display your plugin's WordPress.org repo reviews on your website with a simple shortcode.
* [Satisfaction Reports from Help Scout](https://wordpress.org/plugins/happiness-reports-for-help-scout/) - display your overall Help Scout satisfaction ratings stats on WordPress.

= NOTICE: SECURITY UPDATE – UPDATE TO VERSION 1.8 OR ABOVE! =

> Hi, I’m Tevya with the Starfish Reviews team. We didn’t create this plugin, but recently took it over from the previous owners/dvelopers who had discontinued development on it. It had several major security vulnerabilities in 1.6.5 and previous versions, that went unresolved for several years. Those vulnerabilities were being exploited to install malware on some WordPress websites using this plugin.
>
> Matt has worked hard to fix all those vulnerabilites as quickly as possible in version 1.8. He completely re-wrote the options interface (where the worst vulnerabilities existed) in just a few days! We will continue actively developing this plugin into the future. You can rest assured that it is now secure, in new hands, being actively updated, and that we’ll do all we can to prevent any future vulnerabilities from being introduced.

== Installation ==

1. You can download and install the Rich Reviews plugin through the built-in Wordpress plugin installer. Alternately, download the zip file and upload the '/rich-reviews/' folder to your '../wp-content/plugins/' folder.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. View the instructions page in your the Wordpress backend to see detailed explanations and examples of how and where to use the shortcodes which enable reviews to be shown, submitted, or averaged.
4. Periodically check the Rich Reviews menu in the Wordpress control panel to see if there are any pending reviews which need to be approved (or deleted).

== Screenshots ==

1. Show any number of reviews for your whole site, for a specific page or post, or for any custom category. Display these reviews in any post, page, sidebar, or widget!
2. A simple shortcode allows you to post an aggregate review score on any page.
3. A simple and clean review submission form can be placed anywhere on your site, and will not bog down your site.
4. Admin menus allow you to approve the submitted reviews, so YOU control what reviews are real, and which reviews are unwarranted (or even spam). You can also view every review you have already approved, and choose to push it back to a "pending" status, or even delete it.
5. Added options allow for you to customize the star color to match our site.
6. One user shows the versatility of Rich Reviews by placing our shortcodes into various tabs on one of his pages.

== Changelog ==

= 1.9.5 2020-13-03 =
* Fixes
    * Update "Tested up to" value for WP 5.7.

= 1.9.4 2020-11-06 =
* Fixes
    * Reviews: Admin tables failing to sort list of reviews by selected column

= 1.9.3 2020-28-09 =
* Fixes
    * Site Icon: updated schema snippet to reflect itemprop image outside of a wrapping div itemprop

= 1.9.2 2020-28-09 =
* Enhancements
    * Added Honey-pot protection on the review form to combat bot submissions.
* Fixes
    * Site Icon: Removed the <link> tag in favor of <a> tag to support AMP usage.

= 1.9.1 2020-14-08 =
* Fixes
    * Update Readme.

= 1.9.0 2020-27-04 =
* Fixes
    * Fixed Content Breaking.

= 1.8.9 2020-22-04 =
* Fixes
    * Fixed Pending Review issue.

= 1.8.8 2020-07-04 =
* Fixes
    * Added Translation Strings.

= 1.8.7 2020-20-03 =
* Fixes
    * Added Product option for Structured(Schema) in snippet shortcode and Individual reviews.

= 1.8.6 2020-17-01 =
* Fixes
    * Fixed Post/page title link showing when option is unchecked
    * Resolved security vulnerabilities
    * Fixed third-party email sending issue

= 1.8.5 2019-12-07 =
* Fixes
    * Fixed notices for Structured(Schema) fields in snippet shortcode

= 1.8.4 2019-12-04 =
* Fixes
    * Fixed Errors in Structured (Schema) Data
    * Fixed Reviews not Display in Widget 
    * Fixed Failing to Edit Approved Reviews

= 1.8.3 2019-10-23 =
* Fixes
	* Saving User option admin data
	* Show review star rating
	* Fixed Security issue

= 1.8.2 2019-10-10 =
* Enhancements
    * None
* Fixes
    * Failing to return all reviews when content excerpt was set to more than 0
    * Administration Approved Reviews failing to fully load/return all reviews (see issue above)
    * Saving User options where all options were unchecked

= 1.8.1 2019-10-08 =
* Enhancements
    * None
* Fixes
    * Fixed existing installs from working with new version's option management (cleaned up old options so the new ones could be loaded, requires administrators to reset their option selections)

= 1.8.0 2019-09-27 =
* Enhancements
    * Updated plugin option management to use Wordpress Settings API
    * Updated Google Schema integration for pulling in external reviews
* Fixes
    * Resolved security vulnerabilities

= 1.6.5 =
* hotfix: removed obsolete support for 1.0 - 1.2 version updates
* Added Rich Markup options for author and subject fallbacks
* Fixed minor overlooked bugs

= 1.6.4 =
* hotfix: global post for snippet query
* hotfix: Fixed non-categorized item reviewed schema,  and item reviewed and author defaults
* hotfix: Widget dependency on deprecated code.
* Added "all" value for both snippet and show shortcodes
* Altered and fixed aggregate category query
* Uppdated Rich Snippet output for Schema.org markup
* Fixed random order bug
* Added dynamic response message for name field dependency and approval requirement.
* Refactored file structure and option handling
* Refactored i18n string formatting

= 1.6.3 =
* hotfix: fixing longstanding global reviews show issue
* added option for email notifications, and accompanying field for admin emaail
* added option for scroll back to form after submission

= 1.6.2 =
* hotfix: scroll after submission
* updated rich formatting

= 1.6.1 =
* Updated layout of user opttions
* Made form fields editable
* Added Rounding for Aggregate Rating

= 1.6.0 =
* Updated rich snippet format to microdata
* Added option to display reviews at full width
* Added option to display date along with reviews

= 1.5.12 =
* Correcting star display issue.

= 1.5.11 =
* Fixing issue with stars not displaying.

= 1.5.10 =
* Fixing issue with extra div creation.
* Adding link to new plugins website on dashboard.

= 1.5.9 =
* Hot fix: Correcting the correspondence with Google Rich Snippets.

= 1.5.8 =
* Hotfix

= 1.5.7 =
* Added options admin page.
* Option to edit the color of stars.
* Option to edit form input title of previous "Review Title" input.
* Option to allow various user roles to manage reviews.
* Option to display stars in snippet instead of numerical value.
* Option controlling the order in which reviews are displayed.
* Option to skip the pending process for reviews.
* Adding the ability to add and edit reviews from the WordPress dashboard.
* Fixed minor css issues.

= 1.5.6 =
* Removing a debug dump function that was accidentally left it.

= 1.5.5 =
* Correcting admin page errors.

= 1.5.4 =
* Removing foxytech links

= 1.5.3 =
* Bug fix: correcting popular display issue

= 1.5.2 =
* Introducing new dashboard styles

= 1.5.1 =
* Database bug fix correcting possible site crashes
* Added option for credit line

= 1.5.0 =
* Big update! Complete rewrite of plugin to make it easier to update in the future, and to add more features
* We are now branching to add Nuanced Media as a developer of this plugin
* Everything about the plugin should function better, and with the rewrite and addition of Nuanced Media as a contributer, the updates should come much faster

= 1.4.2 =
* Hotfix: vertical-align: top on the review submission form

= 1.4.1 =
* Changed display order so that reviews are displayed newest-first
* Changed version numbering, and changed version checking function to compensate

= 1.4 =
* Some much needed tweaks, such as better displaying data from the database (no more "rnrn"!)
* VASTLY improved admin menus - now the tried and true WordPress menus you know and love are in Rich Reviews! This means that now you can approve/delete bulk items with much greater ease!
* Added scss files for easier css maintenance
* Much better css! I am no longer providing the majority of the styles - instead I will allow whatever theme you currently have installed to do the leg work (as it should be!)
* Much better javascript!

= 1.3 =
* Added the much-requested feature of per-page/per-product reviews, as well as adding optional review categories to go along with it
* Revamped the backend instructions page to be more pretty and to be much more informative, and to give detailed examples and explanations for each shortcode
* Cleaned up the CSS pretty much everywhere
* The stars in ratings are now orange/yellow when reviews are displayed

= 1.2 =
* Fixed a large bug where the MySQL database was not created properly if plugin was installed fresh, and hence no reviews could be sent, stored, approved, nor displayed! (Thanks, Mik!)

= 1.1 =
* Altered CSS to make the reviews and the review form more pretty (Thanks, Andrew!)
* New, altered CSS allows reviews to properly be displayed and stacked vertically (for example, in a sidebar)
* Integrated/fixed support for non-English characters (Thanks, IvicaD!)
* Split menu up into three parts: instructions, pending reviews, and approved reviews
* New "Approved Reviews" admin menu allows you to view every review you have ever previously approved, and change its status (to pending, or mark it for deletion) at will

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.6.0 =
We updated the "rich snippets" of this plugin to use microdata instead of microformat - microdata is the new hotness and works much better with search engines. This is a big change for us, as the way we rendered meta data had remained the same for the last couple years, and slowly started to lose its applicability. We hope that this change will give greater compatibility between the metadata/rich snippet markup and search result summaries.

= 1.3 =
This adds the MUCH requested feature of having per-page/per-product reviews, as well as adding optional categories for further customization! The instructions page has also been overhauled to provide useful, relevant explanations and examples for each of the shortcodes.

= 1.2 =
This version is essential, as it fixes a bug where the MySQL database was not created unless the plugin had been installed at version 1.0

= 1.1 =
This version pretties up the reviews and the form, and fixes the CSS to allow vertical stacking of reviews in, say, a sidebar. Also implements an "approved reviews" menu to view and change the status of previously-approved reviews.

= 1.0 =
Initial release
