<?php
/**
 * Plugin Name: PressPermit Sync Posts
 * Plugin URI:  https://publishpress.com/press-permit
 * Description: Create or synchronize posts to match users. Designed with Team/Staff plugins in mind, but has broad usage potential.
 * Author:      PublishPress
 * Author URI:  https://publishpress.com
 * Version:     2.7
 * Text Domain: ppsync
 * Domain Path: /languages/
 * Min WP Version: 4.7
 */

/*
Copyright © 2019 PublishPress.

This file is part of PressPermit Sync Posts.

PressPermit Sync Posts is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

PressPermit Sync Posts is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!defined('PRESSPERMIT_SYNC_FILE')) {
    define('PRESSPERMIT_SYNC_FILE', __FILE__);
    define('PRESSPERMIT_SYNC_ABSPATH', __DIR__);
    define('PRESSPERMIT_SYNC_CLASSPATH', __DIR__ . '/classes/Permissions/SyncPosts');

    if (!defined('PRESSPERMIT_VERSION')) {
        return;
    }

    $ext_version = PRESSPERMIT_VERSION;

    if (is_admin()) {
        $module_title = esc_html__('Sync Posts', 'presspermit-pro');
    } else {
        $module_title = 'Sync Posts';
    }

    if (presspermit()->registerModule(
        'sync', $module_title,  plugin_basename(__FILE__), $ext_version, 
        ['min_pp_version' => '2.7-beta']
    )) {
        define('PRESSPERMIT_SYNC_VERSION', $ext_version);

        class_alias('\PressShack\LibArray', '\PublishPress\Permissions\SyncPosts\Arr');

        class_alias('\PressShack\LibWP', '\PublishPress\Permissions\SyncPosts\PWP');

        require_once(__DIR__ . '/classes/Permissions/SyncPosts.php');
        \PublishPress\Permissions\SyncPosts::instance();
        class_alias('\PublishPress\Permissions\SyncPosts', '\PublishPress\Permissions\SyncPosts\UI\SyncPosts');

        require_once(__DIR__ . '/classes/Permissions/SyncHooks.php');
        new \PublishPress\Permissions\SyncHooks();

        if (is_admin()) {
            require_once(__DIR__ . '/classes/Permissions/SyncHooksAdmin.php');
            new \PublishPress\Permissions\SyncHooksAdmin();
        }
    }
} else {
    add_action(
        'init', 
        function()
        {
            do_action('presspermit_duplicate_module', 'sync', dirname(plugin_basename(__FILE__)));
        }
    );
    return;
}
