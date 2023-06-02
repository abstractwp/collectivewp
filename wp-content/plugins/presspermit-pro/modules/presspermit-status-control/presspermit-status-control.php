<?php
/**
 * Plugin Name: PressPermit Statuses
 * Plugin URI:  https://publishpress.com/press-permit
 * Description: Your custom post statuses registered and implemented. Workflow statuses (also requires Collaborative Publishing module) allow unlimited steps between pending and published, each with distinct capability requirements and role assignments.
 * Author:      PublishPress
 * Author URI:  https://publishpress.com
 * Version:     2.7
 * Text Domain: pps
 * Domain Path: /languages/
 * Min WP Version: 4.7
 */

/*
Copyright 2019 PublishPress

This file is part of PressPermit Statuses.

PressPermit Statuses is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

PressPermit Statuses is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!defined('PRESSPERMIT_STATUSES_FILE')) {
    define('PRESSPERMIT_STATUSES_FILE', __FILE__);
    define('PRESSPERMIT_STATUSES_ABSPATH', __DIR__);
    define('PRESSPERMIT_STATUSES_CLASSPATH', __DIR__ . '/classes/Permissions/Statuses');

    if (!defined('REVISIONARY_VERSION') && defined('RVY_VERSION')) {
        define('REVISIONARY_VERSION', RVY_VERSION);
    }

    if (!defined('PRESSPERMIT_VERSION')) {
        return;
    }

    $ext_version = PRESSPERMIT_VERSION;

    if (is_admin()) {
        $module_title = esc_html__('Status Control', 'presspermit-pro');
    } else {
        $module_title = 'Status Control';
    }

    if (presspermit()->registerModule(
        'status-control', $module_title, dirname(plugin_basename(__FILE__)), $ext_version, ['min_pp_version' => '2.7-beta']
    )) {
        define('PRESSPERMIT_STATUSES_VERSION', $ext_version);

        define('PRESSPERMIT_STATUSES_DB_VERSION', '1.0');

        class_alias('\PressShack\LibArray', '\PublishPress\Permissions\Statuses\Arr');
        class_alias('\PressShack\LibArray', '\PublishPress\Permissions\Statuses\UI\Arr');

        class_alias('\PressShack\LibWP', '\PublishPress\Permissions\Statuses\PWP');
        class_alias('\PressShack\LibWP', '\PublishPress\Permissions\Statuses\DB\PWP');
        class_alias('\PressShack\LibWP', '\PublishPress\Permissions\Statuses\UI\PWP');
        class_alias('\PressShack\LibWP', '\PublishPress\Permissions\Statuses\UI\Dashboard\PWP');
        class_alias('\PressShack\LibWP', '\PublishPress\Permissions\Statuses\UI\Gutenberg\PWP');
        class_alias('\PressShack\LibWP', '\PublishPress\Permissions\Statuses\UI\Handlers\PWP');

        require_once(__DIR__ . '/classes/Permissions/Statuses.php');
        class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\PPS');
        class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\Statuses\PPS');
        
        if (is_admin()) {
            class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\Statuses\UI\PPS');
            class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\Statuses\UI\Handlers\PPS');
            class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\Statuses\UI\Dashboard\PPS');
            class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\Statuses\UI\Gutenberg\PPS');
        }

        if (defined('PUBLISHPRESS_REVISIONS_VERSION')) {
            class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\Statuses\Revisions\PPS');

        } elseif (defined('REVISIONARY_VERSION')) {
            class_alias('\PublishPress\Permissions\Statuses', '\PublishPress\Permissions\Statuses\Revisionary\PPS');
        }

        require_once(__DIR__ . '/classes/Permissions/StatusesHooks.php');
        new \PublishPress\Permissions\StatusesHooks();

        if (is_admin()) {
            require_once(__DIR__ . '/classes/Permissions/StatusesHooksAdmin.php');
            new \PublishPress\Permissions\StatusesHooksAdmin();
        }
    }

} else {
    add_action(
        'init', 
        function()
        {
            do_action('presspermit_duplicate_module', 'pp-custom-post-status', dirname(plugin_basename(__FILE__)));
        }
    );
    return;
}
