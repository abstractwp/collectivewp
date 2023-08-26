=== PublishPress Planner Content Calendar and Notifications - Pro ===
Contributors: publishpress, andergmartins, stevejburge, pressshack, kevinb, olatechpro
Author: PublishPress
Author URI: https://publishpress.com
Tags: notifications, Editorial Calendar, workflow, statuses, permissions
Requires at least: 5.5
Requires PHP: 7.2.5
Tested up to: 6.3
Stable tag: 3.12.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

PublishPress Planner is the plugin for professional publishers. Get an editorial calendar, flexible permissions and notifications.

== Description ==

PublishPress Planner can help you create great content in WordPress. Here’s an overview of the publishing tools you’ll find in PublishPress Planner:


* There’s a beautiful [Editorial Calendar](https://publishpress.com/docs/calendar/) to give a clear picture of all your planned and published content.
* You can create [Notifications](https://publishpress.com/docs/notifications/) to keep your team up-to-date with what’s happening.
* You can write [Editorial Comments](https://publishpress.com/docs/editorial-comments/) to leave feedback for your site’s authors.
* There are [Custom Statuses](https://publishpress.com/docs/custom-statuses/) so you can see where content is in your publishing workflow.
* The [Content Overview](https://publishpress.com/docs/calendar/) screen allows you to drill down and analyze your site’s content.
* You can add [Metadata](https://publishpress.com/docs/editorial-metadata/) to give your team extra information about each post.

Interested in finding out more about PublishPress Planner?

* [Check out the premium add-ons](https://publishpress.com/pricing/) for access to all the PublishPress Planner features.

= WHO SHOULD USE PUBLISHPRESS PLANNER? =

PublishPress Planner is ideal for WordPress sites that publish high-quality content. With PublishPress Planner, you can collaborate much more effectively. This makes PublishPress Planner a great solution for any site with multiple users. PublishPress Planner is often used by companies and non-profits, universities and schools, plus by magazines, newspapers and blogs.

= PREMIUM ADD-ONS FOR PUBLISHPRESS =

* [Content Checklist](https://publishpress.com/addons/content-checklist/): Set high standards for all your published content.
* [Multiple Authors](https://publishpress.com/addons/multiple-authors-publishpress/): Easily assign multiple authors to one content item.
* [Reminders](http://publishpress.com/addons/reminders): Automatically send notifications before or after content is published.
* [Permissions](https://publishpress.com/addons/publishpress-permissions/): Control who gets to click the “Publish” button.
* [WooCommerce Checklist](https://publishpress.com/addons/woocommerce-checklist/): Set high standards for all your WooCommerce products.
* [Slack Notifications](https://publishpress.com/addons/publishpress-slack/): Get Slack updates for all content changes.

= EDITORIAL CALENDAR =

The calendar gives you a powerful overview of your publishing schedule. Using the Editorial Calendar, you can easily see when content is planned, and when it was published. You can also drag-and-drop content to a new publication date. By default, you see all the WordPress content you have planned for the next six weeks. If you need to drill down, you can filter the calendar by post status, categories, users or post types.

[Click here for more on the PublishPress Editorial Calendar](https://publishpress.com/docs/calendar/)


= NOTIFICATION WORKFLOWS =

Notifications keep you and your team up to date on changes to important content. Users can be subscribed to notifications for any post, either individually or by selecting user groups. PublishPress allows you to create powerful notification workflows based on post types, categories, status changes and much more.

* [Click here for more on PublishPress Notifications](https://publishpress.com/docs/notifications/)

= CONTENT OVERVIEW =

The Content Overview screen is a companion to the Calendar screen. Whereas the Calendar allows you to see content organized by dates, Content Overview allows you to drill down and see content organized by status, categories, or users. In the top-right corner is a “Print” button. Click this to get a printable overview of all your planned content.

* [Click here for more on the PublishPress Content Overview](https://publishpress.com/docs/content-overview/)

= CUSTOM STATUSES =
 tru
This feature allows you to create custom post statuses such as “In Progress” or “Pending Review”. You can define statuses to match the stages of your team’s publishing workflow.

By default, WordPress provides you with a very limited set of status choices: Draft and Pending Review. With PublishPress you’ll see a much wider range of options. When you first install PublishPress, you’ll see these extra statuses: Pitch, Assigned, and In Progress. You can then create more custom post statuses to define the stages of your publishing workflow.

* [Click here for more on the PublishPress Custom Statuses](https://publishpress.com/docs/custom-statuses/)

= EDITORIAL COMMENTS =

A very important feature in PublishPress is commenting. You can leave comments under each post you write. This is a private conversation between writers and editors and allows you to discuss what needs to be changed before publication.

* [Click here for more on PublishPress Editorial Comments](https://publishpress.com/docs/editorial-comments/)

= METADATA =

Metadata enables you to keep track of important requirements for your content. This feature allows you to create fields and store information about content items.

By default, PublishPress provide 4 examples of metadata, but you can add your own to meet your team’s needs.

* [Click here for more on PublishPress Editorial Metadata](https://publishpress.com/docs/editorial-metadata/)

= USER GROUPS =

For larger organizations with many people involved in the publishing process, user groups help keep your workflow organized and informed.

To find the user settings, go to the PublishPress link in your WordPress admin area, and click the “User Groups” tab. By default, PublishPress provides four user groups: Copy Editors, Photographers, Reporters and Section Editors.

* [Click here for more on PublishPress User Groups](https://publishpress.com/docs/user-groups/)

= IMPORTING FROM EDITFLOW =

PublishPress is based on the EditFlow plugin. It is easy for Edit Flow users to import your data and settings.

* [Click here for full instructions on moving from Edit Flow to PublishPress](https://publishpress.com/docs/migrate/)

= I FOUND A BUG, OR WANT TO CONTRIBUTE CODE =
Great! We’d love to hear from you! PublishPress [is available on Github](https://github.com/AllediaWordPress/PublishPress), and we welcome contributions from everyone.

= FAQ =

== Installation ==

You can install PublishPress through your WordPress admin area:

1. Access the “Plugins” page.
1. Click on the “Add New” button.
1. Search for “PublishPress”.
1. Install the PublishPress plugin.
1. Activate the PublishPress plugin.

= Where Can I Get Support? =

You can ask for help via [the PublishPress contact form](https://publishpress.com/contact/).

= Do I Need Coding Skills to Use PublishPress? =

Not at all. You can set up everything your team needs without any coding knowledge. We made it super easy.

== Screenshots ==

1. With Calendar you can see your posts over a customizable date range.

== Changelog ==

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

= [3.12.0] - 15 Aug 2023 =

* Changed: Replaced Pimple library with a prefixed version of the library to avoid conflicts with other plugins;
* Changed: Replaced Psr/Container library with a prefixed version of the library to avoid conflicts with other plugins;
* Changed: Change min PHP version to 7.2.5. If not compatible, the plugin will not execute;
* Changed: Change min WP version to 5.5. If not compatible, the plugin will not execute;
* Changed: Updated internal libraries to latest versions;
* Changed: Refactor some occurrences of "plugins_loaded" replacing it by a new action: "plublishpress_<name>_loaded" which runs after the requirements and libraries are loaded, but before the plugin is initialized;

= [3.11.0] - 20 June 2023 =

* Changed: Replaced Pimple library with a prefixed version of the library to avoid conflicts with other plugins;
* Changed: Replaced Psr/Container library with a prefixed version of the library to avoid conflicts with other plugins;
* Changed: Change min PHP version to 7.2.5. If not compatible, the plugin will not execute;
* Changed: Change min WP version to 5.5. If not compatible, the plugin will not execute;
* Changed: Updated internal libraries to latest versions;
* Changed: Refactor some occurrences of "plugins_loaded" replacing it by a new action: "plublishpress_<name>_loaded" which runs after the requirements and libraries are loaded, but before the plugin is initialized;

= [3.10.2] - 29 May 2023 =

* Fixed: Metadata reorder not working, #1228
* Fixed: Content Overview Date Filter not working for 1 day date range, #1225
* Fixed: Content Overview Modified Date Toggle not working, #1233
* Fixed: Dropdown Select metadata type update, #1214
* Fixed: Tiny typo in metadata settings, #1221
* Update: Add the new "PublishPress Planner" name in the footer, #1215
* Fixed: User profile notification channel setting not updating, #1218
* Update: ES-FR-IT Translation Updates January 2023, #1241
* Update: Italian translation Update December 6, #1217

= [3.10.1] - 02 Dec 2022 =

* Fixed: Fatal Error caused by 3.10.0 update, #1204

= [3.10.0] - 01 Dec 2022 =

* Update: Rename the plugin to "PublishPress Planner", #1077
* Update: Add Metadata top level menu link, #666
* Update: Add a Dropdown Select type to metadata, #564
* Update: Show and Add metadata on the Calendar, #284
* Update: Add Role filter for editorial metadata of User type, #801
* Update: Allow to choose post type where the metadata is displayed, #464
* Update: Notification threshold description, #1179
* Fixed: "User" metadata metabox doesn't scale well, #563
* Fixed: New Slack integration doesn't support private channels, #1065
* Update: A setting to change the name showing in editorial comments, #1162
* Fixed: Rating stars in PublishPress footer are unevenly spaced, #1150
* Update: PublishPress-PRO ES-FR-IT Translation Updates November 4, 2022, #1164
* Update: PublishPress-FREE-TranslationUpdates_FR-IT_November3_2022, #1163

= [3.9.0] - 3 Nov 2022 =

* Update: Add Editorial Comments menu, #319
* Update: Add file upload option to Editorial Comments, #757
* Fixed: Notification User Role Keeps Reverting, #1136
* Fixed: No Save Button on Editorial Comment Setting, #1134
* Fixed: Error on console when accessing Widget, #1135
* Fixed: Fatal error preventing user update, #1152
* Fixed: Editorial Notifications channel not updating when user is updated, #1151
* Fixed: PHP Notice: Undefined property warning on notification add/edit screen, #1147
* Fixed: Add editorial comments metabox priority filter, #1084
* Fixed: Extra "General" heading in "Slack" and "Reminders", #1122
* Fixed: Publishpress-v3.8.4-ES-FR-IT_TranslationUpdate-5_October2022, #1133
* Fixed: Press-ES-FR-IT-Translation-Update-September2022, #1124

= [3.8.4] - 3 Oct 2022 =

* Changed: Removed Twig dependency, refactoring the views for using plain PHP templates, #1125;

= [3.8.3] - 6 Sep 2022 =

* Fixed: Custom Status Conflict – PublishPress, #1105
* Update: Show Content Overview Post Types Content Together, #1062
* Update: Change to Content Overview "Start Date" and "End Date" filter, #1064
* Update: Update to Settings tabs #443
* Update: Publishpress-ES-FR-IT-translation-update_August2022, #1104

= [3.8.2] - 17 Aug 2022 =

* Update: Sticky filters on the Calendar, #1088
* Update: Allow users to choose whether or not to show the whole page title, #1089
* Fixed: Double slug on hierarchical page when in draft mode, #1087
* Fixed: Metadata box shows errors if there are no fields, #1085
* Fixed: Problem with html characters on calendar, #1037
* Fixed: Metadata filters don't work on Content Overview, #1070
* Fixed: Fix calendar time picker time picker, #914
* Update: Stop loading assets on non-PublishPress /wp-admin/ pages, #330

= [3.8.0] - 6 Jun 2022 =

* Update: Allow mentioning Slack users using the username instead of requiring the user IDs, #1045
* Update: Added new token {{!everyone}} {{!here}} and {{!subteam^abcde123456}} to enable “Everyone” , “Here” and “User group” mention in notification, #1044
* Fixed: Slack notifications referencing slack users by raw member IDs stopped working after v3.7.1, #1042
* Update: Improve content overview date filter, #969;
* Update: Move content overview from general settings to it own tab, #971;
* Added: Add support for more taxonomies in content overview screen, #970;
* Added: Add metadata filter option to content overview screen, #935;
* Fixed: Fix all users showing in dropdow on calendar and content overview screen, #1035;
* Fixed: Fix status permissions on calendar, #1038;
* Added: Add publishpress-instance-protection package, #1034;

= [3.7.2] - 24 mai 2022 =

* Fixed: Can't send notifications to different Slack channels, or to Slack users. Refactored the Slack integration to use OAuth token in the API instead of the incoming webhook URL. Requires re-authenticating to Slack, #1040;
* Added: Add mew filter before sending Slack messages, "publishpress_slack_message_channel";
* Changed: Deprecated filter "publishpress_slack_actions", replaced by "publishpress_slack_message_actions";
* Changed: Deprecated filter "publishpress_slack_text", replaced by "publishpress_slack_message_text";

= [3.7.1] - 21 apr 2022 =

* Fixed: Fix performance, removing automatic deactivation of Edit Flow, replacing it by a simple admin notice, #998;
* Fixed: Fix PHP error on customizer, due to a inline JS script added by mistake out of the head tag, #562;
* Fixed: Fix drag and drop of pending posts, #1015;
* Fixed: Fix integration with The Events Calendar for moving events in the calendar, #1016;
* Fixed: Updated POT file, pt_BR, es_ES, fr_FR and it_IT translations;
* Fixed: Fix warning about WP_User_Query being called with an argument that is deprecated, #1014;
* Fixed: Fix conflict with The Events Calendar query, forcing to display event items in the calendar based on the post_date, #1020;
* Fixed: Removed duplicated `event` param from the workflow action;
* Fixed: Change the action used to trigger post status transition notifications, from `transition_post_status` to `wp_after_insert_post`, #940, #671;
* Fixed: Fix the nonce check for the bulk actions on the notifications log, #1019;
* Fixed: Fix performance issue with file i/o usage when trying to disable deprecated plugins on every request, #808;
* Fixed: Fix fatal error on Slack settings tab, #1008;
* Added: Add debug option for Slack messages in the settings panel, #1007;
* Added: Add a search box to content overview screen #972;
* Added: Add filter "publishpress_calendar_data_args" allowing to customize the query of posts on the calendar, #1017;
* Added: Add select field for the custom fields API to the calendar popup, #1010;
* Changed: Remove the deprecated settings field: slack username, slack channel. Those information are automatically set when you create a new webhook URL;
* Changed: Updated the Slack API integration and webhook URL management, removing legacy config fields;

= [3.7.0] - 02 fev 2022 =

* Fixed: Fix the admin menu Debug Log that was not being displayed, #992;
* Fixed: Fix the fatal error while exporting the calendar as ICS file, #994;
* Fixed: Fix the post type filter in the calendar, #995;
* Fixed: Fix fatal error: [Unknown column 'following_users' in 'where clause'], #982;
* Fixed: Fix drag and drop of custom statuses for reordering, #986;
* Fixed: Fix the time displayed in the calendar items on Safari, #1001;
* Fixed: Fix moving items and date navigation on the calendar in Safari, #1002;
* Fixed: Fix input sanitization in all the modules, HTML and SQL scaping in all the plugin;
* Fixed: Fix an unopened script HTML tag in the editorial metadata module;
* Fixed: Fix metadata in the notifications body for new posts, #574;
* Fixed: Added better feedback on errors while ordering statuses in the admin;
* Fixed: Fix the debug button to only display it for those who has permissions. The debug info were still safe, but the button was displayed, #993;
* Fixed: Improved capability check on diverse functions and modules;
* Fixed: Only load admin assets and the admin menu action if the user has permission for seeing that;
* Fixed: Added nonce check for missed places;
* Fixed: Added cache to the user, author and category searches in the content_overview module;
* Fixed: Fix the capability check for the configure button on editorial metadata metabox. It was only looking for the capability manage_options, which is customizable;
* Fixed: Added a missed capability check before showing search results in the notifications log filters;
* Fixed: Fixed PHP warning about function not defined: esc_array_of_options;
* Fixed: Only look for default capabilities on the calendar module if in the admin;
* Fixed: Only add admin hooks if in the admin;
* Fixed: Fix the delete action for notification log items;
* Added: Add the value of the global constant DISABLE_WP_CRON to the debug info, #987;
* Added: Add the value of the global debug constants WP_DEBUG_DISPLAY, WP_DEBUG_LOGv, and WP_DEBUG to the debug info, #998;
* Changed: Removed not used and deprecated methods in the calendar module: save_post_notify_users, save_post_notify_roles, add_role_to_notify, handle_ajax_drag_and_drop;
* Changed: Improved error handling on Ajax requests on the notifications log;
* Changed: WP version updated to 5.9;
* Changed: Removed not used method remove_object_terms of the calendar module class;

= [3.6.3] - 18 Nov 2021 =

* Fixed: Fix notifications page that tuns the WordPress admin area purple, #966;
* Fixed: Fix "save draft" button has gone, #967;
* Changed: Show the reviews banner on all admin pages;

= [3.6.2] - 25 Oct 2021 =

* Fixed: Can't edit the post status in the classic editor when WP to Twitter is activated, #958;
* Fixed: Can't edit the post status in the block editor when Block Editor Colors is activated, #959;
* Fixed: Block editor crashing if we resize the window to a mobile device dimension, #960;
* Added: Add a review request banner, #949;

= [3.6.1] - 04 Oct 2021 =

* Fixed: Add constants to customize priority of main actions: action_init, action_init_after and action_ini_for_admin, #953;
* Fixed: Fix notification body on events triggered by Elementor pages, #951;
* Fixed: Refactor the settings GET var for fixing a compatibility issue with 3rd party plugin;

= [3.6.0] - 02 Sep 2021 =

* Added: Add support for notifications when post is trashed/untrashed posts, #939;
* Added: Add support for notifications when post is updated/saved, #483;
* Added: Add support on Notifications content for different data types in the meta fields, including support to ACF relationship fields for posts, link, taxonomy and user, #924;
* Removed: Remove the deprecated module "Roles". Please, use PublishPress Capabilities or other plugin for handling user roles, #925;
* Removed: Remove the deprecated module "User Groups", #926;
* Fixed: Stop creating unused user roles on install, #926;
* Fixed: Fixed spacing between fields in the calendar popup, #920;
* Fixed: Fixed PHP warning about not set configuration: duplicated_notification_threshold;

= [3.5.1] - 30 Aug 2021 =

* Fixed: Fix undefined property stdClass::$author, #931;
* Fixed: Notifications don't trigger for posts created in the frontend, #936;
* Fixed: PHP warnings about not set configuration;
* Fixed: Fix the order and orderby filter in the content overview;
* Added: Add Japanese translation files, #934;

= [3.5.0] - 5 Aug 2021 =

* Added: Add the option to edit and delete editorial comments, #277;
* Added: Add new capabilities to control who can edit or delete editorial comments: pp_delete_editorial_comment, pp_delete_others_editorial_comment, pp_edit_editorial_comment, pp_edit_others_editorial_comment, #277;
* Fixed: Fix long text on the posts attributes in the calendar popup, #917;

= [3.4.1] - 19 Jul 2021 =

* Fixed: Fix the visual feedback for the calendar items drag and drop, #881;
* Fixed: Fix the publishing time field in the calendar form to display the default publishing date, #882;
* Fixed: Fix post creation capability check before allowing to create posts in the calendar, #799;
* Fixed: Fix publishing capability check before allow to create posts with "publish", "future", "private" statuses, #825;
* Fixed: Fix edit other posts capability check before allow to set a different author for the post, #834;
* Fixed: Fix capabilities check before displaying the links in the footer of the items popup on the calendar, #887;
* Fixed: Fix post subscribe capability check before displaying the option to "Notify me" in the item popup on the calendar, #886;
* Fixed: Fix edit posts permission before allowing to move items in the calendar, #891;
* Fixed: Fix PHP warning: Invalid argument supplied for foreach(), #828;
* Fixed: Fix the default selected status in the post creation form on the calendar, #893;
* Fixed: Fix the "load more" behavior and links when the calendar is configured to display "All posts", #897;
* Fixed: Fix the drag and drop behavior in the calendar to trigger WP hooks after updating the post, #895;
* Fixed: Make the calendar string "Click to add" translatable, #883;
* Fixed: Fix border of calendar cells on Firefox, #901;
* Fixed: Fix date identified on clicking the calendar cell to create new posts, compensating the timezone, #903;
* Fixed: Fix the post type selection for new posts created on the calendar, #904;
* Fixed: Fix the format of the post date on the calendar popup, #905;
* Fixed: Fix the format of the date on the title of the post form on the calendar, #906;
* Fixed: Fix the JS error that the form in the calendar throws if the user can edit only one post type, #907;
* Fixed: Fix the translation for the private taxonomies: "pp_usergroup", "pp_notify_email", "pp_notify_role" and "pp_notify_user", #908;
* Fixed: Fix the click event over the "Click to add" label;

= [3.4.0] - 07 Jul 2021 =

* Changed: The calendar page has changed and is fully based on React now, #680;
* Changed: Minor improvements to the calendar UI, #680;
* Changed: The "Show 'n' more" option in the calendar cells now works independently and shows the number of visible or hidden posts, #680;
* Changed: The title field in the quick create form in the calendar is now focused automatically after the form is showed, #680;
* Added: Added async data loading to the calendar, with faster navigation and filtering without the need to reload the page, #680;
* Added: New quick create posts form in the calendar with specific fields per post type, #680;
* Added: Added the Post ID to the calendar item popup, #680;
* Added: Added the post status to the calendar item popup, #680;
* Added: Added the post publishing date to the calendar item popup, #680;
* Added: Added support to PublishPress Authors' multiple authors in the calendar, #680;
* Added: Added new fields for setting categories and tags when creating a post in the calendar, #680;
* Added: Added the number of editorial comments of a post in the calendar item popup, #680;
* Added: Type and create new post category or tag right in the quick create posts form in the calendar, #680;
* Added: Added a new button to the calendar navigation controls to refresh the calendar, #680;
* Fixed: Fixed support to PublishPress Authors for assigning guest authors to posts in the calendar, #680;
* Fixed: Fixed the Max visible posts per date setting in the calendar, #680;
* Fixed: Fixed the sorting option in the calendar for correctly sort items by publishing time or post status, #680;
* Fixed: Fixed a bug when dragging and dropping items in the calendar, removing the visual feedback of sorting items in the calendar cell, #680;
* Fixed: Fixed a bug after drag and drop an item in the calendar, showing the correct order of the moved item in the calendar cell, #680;
* Fixed: Fix calendar items ordering when sorted by status or time, #680;
* Fixed: Fix publishing date after publishing a post, using date_floating on custom statuses. Stops auto updating the publishing date with today's date, #741;
* Fixed: Fix adding or editing an user Role, #872;
* Fixed: Fix the scheduled time for cron events of scheduled posts that are moved to different dates in the calendar, #855;
* Fixed: Allow to publish a post with backdate in the calendar or post edit page, #715;

= [3.3.5] - 22 Jun 2021 =

* Fixed: Fix code style;

= [3.3.4] - 22 Jun 2021 =

* Fixed: Fix the license key management;
* Fixed: Fix the "Update now" notices when there is a most recent update available;

= [3.3.3] - 16 Jun 2021 =

* Fixed: Fix "Statuses" doesn't appear with a title in important areas, #846;
* Fixed: Fix links to posts with custom privacy are changed to preview links, #852;

= [3.3.2] - 27 May 2021 =

* Fixed: Fix the icons for statuses, #841;
* Fixed: Fix HTML syntax error in the custom status module for the admin pages;
* Changed: Add new background color for the PublishPress Debug button;

= [3.3.1] - 15 Apr 2021 =

* Fixed: Fix the editorial comments for non-admins, #827;
* Fixed: Check capability "edit_pp_notif_workflows" before displaying the "Active Notifications" list in the post edit page, not displaying it for those who can't edit notifications;
* Fixed: Fix PHP warning about undefined index "untrashed" after trashing a scheduled post, #831;

= [3.3.0] - 18 Mar 2021 =

* Added: Add support to Elementor edit links in the [psppno_post elementor_edit_link] shortcode, #794;
* Added: Add filters to customize the available fields in the notifications "shortcode" help text: publishpress_notifications_shortcode_post_fields, publishpress_notifications_shortcode_actor_fields, publishpress_notifications_shortcode_workflow_fields, publishpress_notifications_shortcode_edcomments_fields, publishpress_notifications_shortcode_receiver_fields;
* Fixed: Fix the value of the notification channel for authors identified by the email, #793;
* Fixed: Fixed the admin menu icon restoring the calendar dashicon, #802;
* Fixed: Fixed PHP Fatal error Uncaught ArgumentCountError: Too few arguments to function MA_Multiple_Authors::filter_workflow_receiver_post_authors, #814;
* Fixed: Fixed bug on WP 5.7 that breaks the toggle button on accordion of metaboxes, #816;
* Fixed: Fixed PHP notice: array to string conversion in debug.php:87, #813;
* Fixed: Fix notifications to user's custom slack channels, #335;

= [3.2.0] - 10 Feb 2021 =

* Added: Added new option to notify Network admins, adding a notification log to the main site in the network, #765;
* Added: Add option to rescheduled failed notifications in the notifications log. We only had that option for scheduled notifications, #786;
* Added: Added option to the notification workflow for avoiding notifying the user who triggered the action, #778;
* Added: Add the name of blog in the notification log content column, if in a multisite network;
* Fixed: Fix calendar picking up the wrong day, depending on the time and timezone, #572;
* Fixed: Fix styling for the error messages in the notifications log. The error lines were moved to the top of the screen due the "error" CSS class, #765;
* Fixed: Add sanitization and escape variables in some variables, increasing compatibility with WP VIP and more security, #773;
* Fixed: Fix PHP warning "Creating default object from empty value in publishpress-authors.php:772", correctly assigning the filter "pp_pre_insert_editorial_comment". (Allows PublishPress Revisions integration), #231;
* Fixed: Fixed timezone information in the calendar subscription and .ics file, #784;
* Fixed: Fixed role selection when adding a new user in a multisite, #788;

= [3.1.0] - 20 Jan 2021 =

* Added: Add shortcodes to the email notifications for the post content, excerpt and post type, #288
* Fixed: Fixed support to PHP 5.6, #772;

= [3.0.3] - 11 Jan 2021 =

* Fixed: Fix fatal error when "editor" or "author" user roles are missed in the site, #767;
* Fixed: Update the list of capabilities in the PublishPress Capabilities plugin;

= [3.0.2] - 07 Jan 2021 =

* Fixed: Fix JS warning: $(html) HTML text after last tag is ignored in the custom-status.js file, #754;
* Fixed: Fix JS warning: jQuery.fn.attr(‘selected’) might use property instead of attribute on custom-status.js, #753;
* Fixed: Fix JS warning: jQuery.fn.attr(‘multiple’) might use property instead of attribute on custom-status.js, #753;
* Fixed: Fix JS warning: jQuery.fn.click() event shorthand is deprecated on "publishpress/modules/calendar/lib/calendar.js", #761;
* Fixed: Fix JS warning: jQuery.fn.keydown() event shorthand is deprecated on "publishpress/modules/calendar/lib/calendar.js", #761;
* Fixed: Fix JS warning: jQuery.fn.mouseover() event shorthand is deprecated on "publishpress/modules/calendar/lib/calendar.js", #761;
* Fixed: Fix JS warning: jQuery.fn.mouseout() event shorthand is deprecated on "publishpress/modules/calendar/lib/calendar.js", #761;
* Fixed: Fix JS warning: jQuery.fn.change() event shorthand is deprecated on "publishpress/modules/calendar/lib/calendar.js", #761;
* Fixed: Fix JS warning: jQuery.isArray is deprecated; use Array.isArray on "publishpress/common/libs/select2/js/select2.min.js", #761;
* Fixed: Fix JS warning: jQuery.fn.click() event shorthand is deprecated on "publishpress/modules/content-overview/lib/content-overview.js", #761;
* Fixed: Fix JS warning: jQuery.fn.change() event shorthand is deprecated on "publishpress/modules/content-overview/lib/content-overview.js", #761;
* Fixed: Fix JS warning: jQuery.fn.click() event shorthand is deprecated on "publishpress/modules/notifications-log/assets/js/admin.js", #761;
* Fixed: Fix JS warning: jQuery.fn.click() event shorthand is deprecated on "publishpress/modules/editorial-metadata/lib/editorial-metadata-configure.js", #761;
* Fixed: Fix JS warning: jQuery.fn.keyup() event shorthand is deprecated on "publishpress/modules/custom-status/lib/custom-status-configure.js", #761;
* Fixed: Fix JS warning: jQuery.fn.click() event shorthand is deprecated on "publishpress/modules/custom-status/lib/custom-status-configure.js", #761;
* Fixed: Fix JS warning: jQuery.fn.keydown() event shorthand is deprecated on "publishpress/modules/custom-status/lib/custom-status-configure.js", #761;
* Fixed: Fix JS warning: jQuery.fn.mousedown() event shorthand is deprecated on "publishpress/modules/custom-status/lib/custom-status-configure.js", #761;
* Fixed: Fix JS warning: jQuery.fn.focus() event shorthand is deprecated on "publishpress/common/js/jquery-ui-timepicker-addon.js", #761;
* Fixed: Fix JS warning: jQuery.fn.bind() is deprecated on "publishpress/common/js/jquery-ui-timepicker-addon.js", #761;
* Fixed: Fix JS warning: jQuery.fn.bind() is deprecated on "publishpress/modules/custom-status/lib/custom-status.js", #761;
* Fixed: Fix JS warning: jQuery.fn.click() event shorthand is deprecated on "publishpress/modules/editorial-comments/lib/editorial-comments.js", #761;
* Fixed: Fix JS warning: jQuery.fn.bind() is deprecated on "publishpress/modules/improved-notifications/libs/opentip/downloads/opentip-jquery.js", #761;
* Fixed: Fix JS warning: jQuery.fn.click() event shorthand is deprecated on "publishpress/modules/improved-notifications/assets/js/multiple-select.js", #761;
* Fixed: Fix the post_id passed to the method "get_workflows_related_to_post" that lists the notification workflows related to the post being edited;
* Fixed: Fix PHP notice undefined variable "$action_args", #752;
* Changed: Removed the user field in the Roles page to avoid break big sites, #750;
* Added: Add capability to control who can view ("pp_view_editorial_metadata") or edit ("pp_edit_editorial_metadata") the editorial metadata, deprecating the capability "pp_editorial_metadata_user_can_edit", #758;

= [3.0.1] - 24 Nov 2020 =

* Fixed: Can't delete users because the plugin redirects to the Notifications Log page, #737;
* Fixed: Fixed the arguments "old_status" and "new_status" for the shortcode "psppno_post", #713;
* Fixed: Fixed the argument "author_ip" for the shortcode "psppno_edcomment", #713;
* Fixed: Fixed the option to always notify users who edited the content, #742;
* Fixed: Fixed bug in the notification filters that was triggering notifications for unselected post types, #743;
* Fixed: Updated the Italian language files;

= [3.0.0] - 16 Nov 2020 =

* Added: Added sortable columns to the Content Overview post list, #709;
* Added: Added post type filter to the Content Overview page, #727;
* Added: Added new filter "publishpress_notifications_schedule_delay_in_seconds", #650;
* Added: Added new filter "publishpress_notifications_scheduled_data", #650
* Added: Added to each notification log the source of the receiver (why is the user being notified? What group does he belongs to?), #650
* Added: Added info to the log about the current user when the notification was triggered, #650
* Added: Show the scheduled time of notifications in the log, #650
* Added: Added information about the cron task status of each scheduled notification. If not exists, show a failure message, #650
* Added: Added option to try again failed notifications. Add action "Try again" (Reschedule), and bulk option, #650
* Added: Display in the log the duplicated notifications that were skipped, #650
* Added: Added a settings field to configure the duplicated notification time threshold, in minutes, #650
* Added: Added to the log the icon for the channel used in the notification, #650
* Added: Add support to mention Slack users in notifications sent throw Slack. It requires to mention the User ID, not the user name, prefixed by a "@", #650
* Added: Added a new filter: "publishpress_slack_actions";
* Fixed: Minor fix to the style of the Content Overview post list, #709;
* Fixed: Fixed default notifications adding the "new" and "auto-draft" to the previous status field, and "post" to the Post Type field, #721;
* Fixed: Fixed support for multiple authors in the notifications, #650
* Fixed: Fixed Strict Standards notice: ..\Dependency_Injector define the same property ($container) in the composition of ..\Role, #726;
* Fixed: Fixed Strict Standards notice: ..\Dependency_Injector define the same property ($container) in the composition of ..\Follower, #726;
* Fixed: Fixed Slack notifications, #650
* Changed: Improved error messages for failed notifications adding more descriptive error messages, #650
* Changed: Refactored the filter "publishpress_notif_run_workflow_meta_query" to "publishpress_notifications_running_workflow_meta_query", #650
* Changed: Refactored the filter publishpress_notif_async_timestamp => publishpress_notifications_scheduled_time_for_notification, #650
* Changed: Refactored the action publishpress_enqueue_notification => publishpress_notifications_scheduled_notification, #650
* Changed: Refactored the action publishpress_cron_notify => publishpress_notifications_send_notification, #650
* Changed: Refactored the filter publishpress_notif_workflow_actions => publishpress_notifications_workflow_events, #650
* Changed: The notification's content is only fixed right before sending the message. Scheduled notifications now have dynamic preview for the content, #650
* Changed: The notification's list of receivers is only fixed right before sending the message. Scheduled notifications have dynamic receivers list, #650
* Changed: The popup now displays only the content of the notification, #650
* Changed: Refactored the Content Overview screen grouping posts by post type instead of by taxonomy, #709;
* Changed: Deprecated the filter "PP_Content_Overview_term_columns" and added a new one "publishpress_content_overview_columns", #709;
* Changed: Deprecated the filter "PP_Content_Overview_term_column_value" and added a new one "publishpress_content_overview_column_value", #709;
* Changed: Slack messages now display the content added in notification workflow. The "Notification Theme" option was refactored to display or not the line with action buttons, #650
* Removed: Removed the action "publishpress_notif_before_run_workflow", #650
* Removed: Removed the filter "publishpress_notif_workflow_receiver_post_authors", #650
* Removed: Removed the filter "publishpress_slack_attachments";

= [2.4.2] - 05 Nov 2020 =

* Fixed: Invalid assets paths for modules on Windows servers, #712;
* Fixed: Fixed error in the calendar: Error: selected user doesn't have enough permissions to be set as the post author, #704;
* Fixed: Fixed conflict with the plugin Visual Composer: pagenow is undefined, #692;
* Fixed: Method get_inner_information was ignoring the passed information fields to the first argument, #654;
* Fixed: Fixed broken text domain and update the .POT file, #670;

= [2.4.1] - 22 Oct 2020 =

* Fixed: Fix wrong assets URL. The URLs where pointing to the Free plugin, which is not installed;
* Changed: Updated the base plugin to v2.4.1;

= [2.4.0] - 22 Oct 2020 =

* Fixed: Fix PHP notice on Ajax call after clicking a filter without typing anything in the calendar or content overview, #693;
* Fixed: Fix JS error: No select2/compat/containerCss, #695;
* Fixed: Fix JS error: Failed to load resource: the server responded with a status of 404 () - select2.min.js, #696;
* Fixed: Fix JS error: notifications.js:2 Uncaught TypeError: $(...).pp_select2 is not a function, #696;
* Fixed: Fix PHP error: undefined property $default_pulish_time, #698;
* Fixed: Fixed assets loading when installed as dependency of the Pro plugin, #697;
* Added: Added option to sort calendar items by publishing date, #457;
* Added: Added option to show all posts, or specific number of posts, on a date in the calendar, #675;
* Changed: Updated the Twig library to 1.42.5;
* Changed: Updated base plugin to v2.4.0;

= [2.3.0] - 07 Oct 2020 =

* Fixed: Fixed performance and memory issue for the calendar and content overview pages adding filters with asynchronous data search, removing the bloat of rendering all the users/tags in fields for each calendar cell, and content overview filters, #674;
* Fixed: Fixed language domain loading and updated the POT file, #670;
* Fixed: Removed a not used JS library: remodal, #517;
* Fixed: Stop loading the Chosen JS library where it is not used, #330;
* Fixed: Fixed support to Cyrillic chars on post status, #439;
* Added: Added support for displaying editorial comments in post status transition notifications, #676;
* Changed: Updated the Select2 JS library to version 4.0.13. The library instance was refactored to pp_select2;
* Changed: Converted the select field for notifications in the post edit page from Chosen to Select2;
* Changed: Updated base plugin to v2.3.0;

= [2.2.1] - 13 Aug 2020 =

* Fixed: Fixed PHP warning about variable $key being used outside and inside the context;
* Added: Added new filter "publishpress_new_custom_status_args" to customize the post status arguments, #640;
* Fixed: Fixed a PHP Fatal error: Trait Dependency_Injector not found, #652;
* Fixed: Fixed PHP warning: Invalid argument supplied for foreadh in TopNotice/Module.php;
* Fixed: Fixed warnings about mixed content when the site uses HTTPS;
* Fixed: Fixed JS error related to jQuery "live" function being deprecated and not found;
* Fixed: Fixed DOM errors in the browser related to multiple elements using the same ID, #660;
* Fixed: Compatibility with WP 5.5;
* Changed: Updated base plugin to 2.2.1;

= [2.2.0] - 17 Jun 2020 =

* Removed: Fixed conflict with Gutenberg and other plugins keeping draft as the default status, always. Removed the option to set another status as default, #621;
* Removed: Removed the notice asking for reviews after a few weeks of usage, #637;
* Fixed: Protect the WordPress post statuses "Pending" and "Draft", blocking edition of those statuses;
* Fixed: Fix the post status selection and the "Save as" link for Gutenberg for posts in published statuses. For changing the status you have to unpublish the post first;
* Fixed: Fix the "Save as" button when the current status doesn't exist;
* Fixed: Fix compatibility with the Nested Page plugin, #623;
* Fixed: Fix the title of Editorial Meta meta box in the options panel for Gutenberg, #631;
* Fixed: Load languages from the relative path, #626;
* Fixed: Updated the PT-BR translation strings;
* Fixed: Fix a fatal syntax error in the Slack module;

= [2.1.0] - 28 May 2020 =

* Added: Added support to PublishPress Authors (requires at least 3.3.1), #610, #614;
* Added: Added the user email to the notifications log entries and details popup, #602;
* Added: Added option to choose which statuses can show the time in the calendar, #607;
* Added: Added option to select custom publish time in the calendar for all post statuses, #554;
* Added: Added "read only" label to calendar items you can't edit, #608, #615;
* Changed: Updated base plugin to 2.1.0;
* Changed: Removed debug statements from the Custom Status module;
* Fixed: PHP error related to the undefined "current_datetime" function;
* Fixed: Ajax calls are saying the Notification Workflow post type is not registered, #601;
* Fixed: Removed the selection from the calendar to avoid messing up with the drag-and-drop;
* Fixed: Added visual feedback and error messages when errors happens while dragging and dropping items in the calendar, #609;
* Fixed: Fixed compatibility with PHP < 7.3 removing the call to the function "array_key_first";

= [2.0.6] - 15 Apr 2020 =

* Fixed: Fixed the duplicated posts after publishing using another algorithm due to new reports of a similar issue (#546);

= [2.0.5] - 15 Apr 2020 =

* Fixed: Fixed duplicated posts after publishing from custom post statuses, a bug introduced by the fix for #546;
* Fixed: Fixes the metadata form in the settings to display the errors after a form submission; (#592)
* Fixed: Updated the build script to remove test files from the built package to avoid false positive on security warnings issued by some hosts;

= [2.0.4] - 08 Apr 2020 =

* Fixed: Wrong publish date when using custom statuses - Now the publish date is always updated when the post is published;
* Fixed: Fixed the error displayed on Windows servers when the constant DIRECTORY_SEPARATOR is not defined;

= [2.0.3] - 17 Mar 2019 =

* Fixed: Performance issue when looking for legacy plugins;
* Fixed: JS error related to undefined editor when subject or content is empty;
* Fixed: Permalinks for scheduled posts removing the preview param;
* Fixed: Not all custom posts were available for notifications;
* Changed: Updated base plugin to 2.0.4-alpha.1;

= [2.0.2] - 18 Feb 2019 =

* Fixed: Performance issue due to recursive check for plugin files;
* Fixed: Removed leftovers of Phing and the builder script from the built package;
* Changed: Base plugin updated to 2.0.2;

= [2.0.1] - 11 Feb 2019 =

* Update the plugin base to 2.0.1, fixing issues related to hidden publish status and notifications not sent for published posts;

= [2.0.0] - 06 Feb 2019 =

* First public release based on the free version 2.0.0;
