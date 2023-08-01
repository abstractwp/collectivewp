=== PublishPress Checklists Pro ===
Contributors: publishpress, andergmartins, stevejburge, pressshack, kevinb, deenison, ojopaul, olatechpro
Author: PublishPress, PressShack
Author URI: https://publishpress.com
Tags: checklists
Requires at least: 5.5
Requires PHP: 7.2.5
Tested up to: 6.2
Stable tag: 2.8.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Changelog ==

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

= [2.8.0] - 18 May 2022 =

* Changed: Replaced Pimple library with a prefixed version of the library to avoid conflicts with other plugins;
* Changed: Replaced Psr/Container library with a prefixed version of the library to avoid conflicts with other plugins;
* Changed: Change min PHP version to 7.2.5. If not compatible, the plugin will not execute;
* Changed: Change min WP version to 5.5. If not compatible, the plugin will not execute;
* Changed: Updated internal libraries to latest versions;

= [2.7.4] - 06 Mar 2022 =

* Fixed: Image alt tag function not working in Classic Editor, #471
* Fixed: Gutenberg Editor error when using Taxonomy Categories, #407
* Fixed: Internal link checker not working in Classic Editor, #278
* Fixed: Rank Math meta field missing when checklists is enabled, #470
* Fixed: Support for Rank Math and Classic Editor, #293
* Update: Use wp_kses_post filter instead esc_html to enable allowed tags in metabox label, #478
* Fixed: Submit Lock Affecting Content Update with Yoast, #423
* Update: German translation, #262
* Update: PRO_Checklists_ES-FR-IT_TranslationUpdate_October2022, #415
* Update: TRANSLATION UPDATES French-Spanish-Italian, #406

= [2.7.3] - 05 Jul 2022 =

* Fixed: Missing checklists settings menu in PHP 8.0 and Multisite, #387
* Fixed: Settings footer breaks for language other than English, #388
* Fixed: Missing translation for Min and Max, #389
* Added: Include new Free / Pro library, #377
* Fixed: Issue with Yoast SEO and post_content, #391
* Fixed: Clicking the Preview button will trigger the publishing pop-up, #378
* Fixed: Extra calls slowing down website, #385
* Fixed: Warning: Undefined array key "page", caused by "helper_settings_validate_and_save" function, #369
* Update: Most important buttons should be yellow only, #382
* Fixed: Issue with PHP 5.6, #386

= [2.7.2] - 27 Apr 2022 =

* Fixed: Fix Yoast SEO word count breaks in Pending Review, #345;
* Fixed: Run the WordPress VIP scans on Checklists, #354;
* Fixed: Missing translation string for "Featured image width between", #346;
* Added: BR translation for checklists Pro, #368;
* Fixed: Refactor Pro string translation to use direct wp __ function, #367;

= [2.7.1] - 20 Apr 2022 =

* Fixed: Fix incorrect text in settings, #344;
* Fixed: Allow links with # as valid URL check, #352;
* Fixed: Hide task not supported for post type, #199;
* Fixed: Fix bad footer image URL on Windows for the Pro plugin, #342;
* Fixed: Only load the checklists and resources in relevant pages, #129;
* Fixed: Fix method call is provided 2 parameters, but the method signature uses 1 parameters error, #179;
* Updated: Spanish and Italian translations, #348;

= [2.7.0] - 16 Feb 2022 =

* Added: Added capability "manage_checklists" to access Checklists screen, #173;
* Fixed: Fix tabs layout in the settings page, #317;
* Removed: Remove the icon from the admin heading;
* Fixed: Updated the Reviews library, fixing compatibility with our other plugins;
* Fixed: Add capability check before saving global checklists options (we already had a nonce check in place), #325;
* Fixed: Improved output escaping in the admin interface, #326;
* Fixed: Improved input sanitization, #324;
* Fixed: Fixed duplicated admin menu on PHP 8, #316;

= [2.6.2] - 15 Nov 2021 =

* Fixed: Can't update published posts if requirements changed
* Added: WordPress Reviews version 1.1.12

= [2.6.1] - 11 Nov 2021 =

* Fixed: Fix the license key management;
* Fixed: Fix the "Update now" notices when there is a most recent update available;
* Fixed: Skip the comply of requirements when "Include pre-publish checklist" is disabled
* Fixed: Preferences Panel box is broken
* Fixed: Changed ID of span where full slug is picked up from with Classic Editor
* Fixed: Border width for buttons
* Added: Ask for plugin review support


= [2.6.0] - 22 Apr 2021 =

* Added: Added drag-and-drop support for sorting the checklists requirements, #172;
* Fixed: Fixed default position of items in the checklist;
* Changed: Added support for displaying unit text in the checklist requirements settings page;
* Fixed: Added respective unit text in the featured image size requirement;

= [2.5.0] - 08 Apr 2021 =

* Added: Support for checking the width and height of the featured image, #109;
* Added: Added Italian translation. Huge thanks to Simone Bianchelli and Angelo Giammarresi for sharing the translation files;
* Fixed: Fixed support to PHP 5.6, #240;
* Fixed: Fixed some class names to match the filename, #241;
* Fixed: Fixed some strings that were not being translated;
* Fixed: Fixed detection of the Block Editor when the Classic Editor plugin is installed and the user can select which editor to use, #239;
* Fixed: Fixed a CSS conflict with the class "warning" and some themes, #243;
* Fixed: Fixed pre-publishing panel and warning when required items are unchecked, #252;
* Fixed: Fixed link validation for "tel:" and "mailto:" links, #246
* Fixed: Fixed WPBakery compatibility, #237;

= [2.4.2] - 22 Oct 2020 =

* Fixed: Remove unexistent dependencies for met-box.js, #231;
* Changed: Updated the base plugin to v2.4.2;

= [2.4.1] - 08 Oct 2020 =

* Fixed: Fix JS error Uncaught TypeError: Cannot read property 'doAction' of undefined, #224;
* Fixed: Fix broken menu item if the user doesn't have permissions to see the menu, #226;
* Changed: Updated base plugin to v2.4.1;

= [2.4.0] - 22 Sep 2020 =

* Added: Added a new task for validating links in the content, #200;
* Added: Added a new task for checking the number of external links, #201;
* Added: Added form validation for required fields in the checklists page, #175;
* Added: Added a new task for requiring approval for specific roles, #104;
* Added: Added new field for custom tasks to select which role can check/uncheck the box, #104;
* Removed: The option "Recommended: show only in the sidebar" were removed and current settings fallback to "Recommended: show in the sidebar and before publishing", which was renamed to just: "Recommended", #195.
* Changed: Changed the order of tasks in the settings page, #223;
* Changed: Updated base plugin to v2.4.0;

= [2.3.2] - 20 Aug 2020 =

* Fixed: Fixed warnings related to missed dependencies for scripts when the post type is not selected to use checklists, #208;
* Changed: Updated the base plugin to v2.3.2;

= [2.3.1] - 14 Aug 2020 =

* Fixed: Fixed compatibility with WP 5.5;
* Fixed: Fixed Gutenberg and Classic Editor detection, #203, #202;
* Fixed: Fixed invalid selector in jQuery, #197;
* Fixed: Fixed the publishing button that was stuck sometimes making impossible to publish a post, #191;
* Changed: Updated the base plugin to 2.3.1;

= [2.3.0] - 06 Aug 2020 =

* Added: Added new task for checking if all the images in the post has an "alt" attribute, #164;
* Fixed: Fixed the verification for custom taxonomies in the post editor page, #114;
* Fixed: Fixed style for unchecked custom tasks, #184;
* Fixed: Updated language files;
* Changed: Hide Yoast SEO tasks if Yoast's plugin is not activated, #164;
* Changed: Updated translation strings;
* Changed: Changed the algorithm of the Yoast SEO readability and SEO analysis verification, considering the selected score as the minimum score, #169;
* Changed: Change the label of the "Add custom item" button to "Add custom task", #181;
* Updated: Update base plugin to 2.3.0;

= [2.2.0] - 21 Jul 2020 =

* Added: Add support to Yoast SEO readability and SEO analysis pass task in the checklists - #86;
* Added: Add new task for checking the limit of chars in the excerpt test - #150;
* Added: Add new task for checking the number of internal links in the text - #52;
* Fixed: Remove not used transient for checking data migration;
* Fixed: JS error message related to missed PP_Checklists object;
* Fixed: Enqueue scripts only when required - #106;
* Fixed: Fixed translation support adding French and British English translations;
* Changed: Updated the PHP min requirement from 5.4 to 5.6;
* Changed: Updated the WordPress tested up to version to 5.4;
* Changed: Updated the label and text for some tasks;
* Changed: Updated the base plugin to 2.2.0;
* Changed: Removed the option to disable the WooCommerce integration module - #137;

= [2.1.0] - 07 May 2020 =

* Added: Add permalink validation rule for the checklists - #115;
* Added: Add option to select user roles to skip specific requirements - #131;
* Added: Add a menu link to upgrade to the Pro plan;
* Changed: Improve UI for custom items in the checklist, removing the "X" icon - #126;
* Removed: Remove the option to hide the Publish Button due to conflicts with Gutenberg;
* Fixed: Fixed the tabs for post types in the Checklists admin page. If you have too many post types the second line of tabs was overlaying the first line - #132;
* Fixed: Fixed the checklist warning popup when you are updating a published post and has unchecked required tasks in the checklist, for the classic editor - #124;
* Fixed: Fixed the list of available post types for the checklists to display any post type that has the show_ui = true. Non public post types are now recognized - #127;
* Fixed: Fixed the list of post types in the Checklists page hiding the tabs of post types that are not selected in the settings - #136;
* Fixed: Fixed the error displayed on Windows servers when the constant DIRECTORY_SEPARATOR is not defined;
* Fixed: Fixed empty checklists on fresh installs due to no post type being selected. Posts is selected by default now - #140;
* Fixed: Fixed warning icon on Gutenberg moving it from the side to over the publish button - #138;
* Fixed: Fixed and error in the EDD update library, updating it;

= [2.0.2] - 18 Mar 2020 =

* Fixed: Fix Checklist for custom hierarchical taxonomies when using Gutenberg;
* Fixed: Small improvements to the UI;
* Fixed: Fix compatibility with Rank Math fixing error in Gutenberg;

= [2.0.1] - 07 Feb 2020 =

* Fixed: Fixed syntax error on some environments;
* Fixed: Fixed the item_id in the license validation process;
* Changed: Updated the base plugin to the version 2.0.1;

= [2.0.0] - 16 Dec 2019 =

* First public release of the Pro package. This release includes the Checklists v2.0.1 + the modules from the old PublishPress WooCommerce Checklists plugin v1.1.7.

= [1.4.7.1] - 12 Dec 2019 =

* Transitional package for the standalone plugin PublishPress Checklists Pro v2.0.0.

= [1.4.7] - 21 Jul 2019 =

* Fixed: A JS error was preventing to block the post save action when displaying a popup with missed requirements on Classic Editor

= [1.4.6] - 20 jun 2019 =

* Avoid JS white screen on Gutenberg "New Post" access by Author with Multiple Authors plugin active and "Remove author from new posts" setting enabled;
* Change minimum required version of PublishPress to 1.20.0;

= [1.4.5] - 22 Feb 2019 =

* Fixed the pre-publishing check to avoid blocking save when not publishing;

= [1.4.4] - 12 Feb 2019 =

* Fixed JS error that was preventing the Preview button to work properly in the classic editor;

= [1.4.3] - 11 Feb 2019 =

* Changed the label for checklist options in the settings panel;
* Fixed translation to PT-BR (thanks to Dionizio Bach);
* Fixed bug when word-count script was not loaded;
* Fixed JS error if an editor is not found;

= [1.4.2] - 30 Jan 2019 =

* Removed license key field from the settings tab;
* Fixed the checklist for the block editor;

= [1.4.1] - 24 Jan 2019 =

* Disable post types by default, if Gutenberg is installed;

=[1.4.0] - 14 Jan 2019 =

* Fixed the TinyMCE plugin to count words to not load in the front-end when TinyMCE is initialized;
* Fixed the assets loading to load tinymce-pp-checklist-requirements.js only in the admin;
* Added better support for custom post types and custom taxonomies which use WordPress default UI;
* Fixed conflict between custom taxonomies and tags in the checklist while counting items;
* Update POT file and fixed translations loading the text domain;
* Updated PT-BT language files;

= [1.3.8] - 18 Apr 2018 =

*Fixed:*

* Fixed wrong reference to a legacy EDD library's include file;
* Fixed PHP warning about undefined property and constant;

= [1.3.7] - 21 Feb 2018 =

*Fixed:*

* Fixed support for custom post types;

= [1.3.6] - 07 Feb 2018 =

*Fixed:*

* Fixed error about class EDD_SL_Plugin_Updater being loaded twice;

= [1.3.5] - 06 Feb 2018 =

*Fixed:*

* Fixed saving action for custom items on the checklist;
* Fixed license validation and automatic update;

= [1.3.4] - 26 Jan 2018 =

*Changed:*

* Changed plugin headers, fixing author and text domain;

= [1.3.3] - 26 Jan 2018 =

*Fixed:*

* Fixed JS error when the checklist is empty (no requirements are selected);
* Fixed compatibility with PHP 5.4 (we will soon require min 5.6);
* Fixed custom requirements;
* Fixed the requirement of tags;
* Fixed PHP Fatal error on some PHP on the featured image requirement;
* Fixed category count in the checklist;

*Added:*

* Added action to load plugins' script files;

*Changed:*

* Rebranded to PublishPress;

= [1.3.2] - 31 Aug 2017 =

*Fixed:*

* Fixed EDD integration and updates;

*Changed:*

* Removed Freemius integration;

= [1.3.1] - 13 Jul 2017 =

*Fixed:*

* Fixed support for custom post types allowing to use custom items as requirements;

= [1.3.0] - 12 Jul 2017 =

*Added:*

* Added support for setting specific requirements for each post type, instead of global only;

*Fixed:*

* Fixed the delete button for custom items in the settings. It was remocing wrong items, in an odd pattern;
* Fixed PHP warning in the settings page about undefined index in array;
* Fixed the menu slug in the Freemius integration;

*Changed:*

* Changed the required minimun version of PublishPress to 1.6.0;
* Imprived extensibility for add-ons;

= [1.2.1] - 21 jun 2017 =

*Added:*

* Added pt-BR translations

*Fixed:*

* Fixed PHP warnings after install and activate
* Fixed PHP warnings about wrong index type
* Fixed the license and update checker

*Changed:*

* Removed English language files
* Updated Tested Up to 4.8

= [1.2.0] - 06 jun 2017 =

*Added:*

* Added the option to hide the Publish button if the checklist is not completed
* Added the option to add custom items for the checklist
* Added POT file and English PO files

*Fixed:*

* Fixes the mask for numeric input fields in the settings tab on Firefox
* Fixes the license key validation
* Fixes the update system

*Changed:*

* The warning icon in the publish box now appears even for published content

= [1.1.2] - 23 May 2017 =

*Fixed:*

* Fixes the word count feature

*Changed:*

* Displays empty value in the max fields when max is less than min
* Improves the min and max fields for value equal 0. Displays empty fields.

= [1.1.1] - 18 May 2017 =

*Fixed:*

* Removed .DS_Store file from the package
* Fixed the "Hello Dolly" message in the Freemius opt-in dialog
* Increased the minimum WordPress version to 4.6

*Changed:*

* Improved settings merging the checkbox and the action list for each requirement
* Changed order for Categories and Tags to stay together in the list
* Changed code to use correct language domain

= [1.1.0] - 11 May 2017 =

*Added:*

* Added "Excerpt has text" as requirement
* Added option to set "max" value for the number of categories, tags and words - now you can have min, max or an interval for each requirement.

*Changed:*

* Improved the JavaScript code for better readability

= [1.0.1] - 03 May 2017 =

*Fixed:*

* Fixed the name of plugin's main file
* Fixed WordPress-EDD-License-Integration library in the vendor dir

= [1.0.0] - 27 Apr 2017 =

*Added:*

* Added requirement for minimum number of words
* Added requirement for featured image
* Added requirement for minimum number of tags
* Added requirement for minimum number of categories
* Added Freemius integration for feedback and contact form
* Added option to display a warning icon in the publish box
* Added checklist to the post form
* Added option to select specific post types
