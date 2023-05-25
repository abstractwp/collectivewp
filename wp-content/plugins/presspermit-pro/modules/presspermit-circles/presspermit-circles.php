<?php
/**
 * Plugin Name: PressPermit Circles
 * Plugin URI:  https://publishpress.com/press-permit
 * Description: Visibility Circles and Editorial Circles block access to content not authored by other group members
 * Author:      PublishPress
 * Author URI:  http://publishpress.com/
 * Version:     2.7
 * Text Domain: ppcc
 * Domain Path: /languages/
 * Min WP Version: 4.7
 */

/*
Copyright 2019 PublishPress

This file is part of PressPermit Circles.

PressPermit Circles is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

PressPermit Circles is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this plugin.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!defined('PRESSPERMIT_CIRCLES_FILE')) {
    define('PRESSPERMIT_CIRCLES_FILE', __FILE__);
    define('PRESSPERMIT_CIRCLES_ABSPATH', __DIR__);
    define('PRESSPERMIT_CIRCLES_CLASSPATH', __DIR__ . '/classes/Permissions/Circles');

    if (!defined('PRESSPERMIT_VERSION')) {
        return;
    }

    $ext_version = PRESSPERMIT_VERSION;

    if (is_admin()) {
        $module_title = esc_html__('Access Circles', 'presspermit-pro');
    } else {
        $module_title = 'Access Circles';
    }

    if (presspermit()->registerModule(
        'circles', $module_title, dirname(plugin_basename(__FILE__)), $ext_version, ['min_pp_version' => '2.7-beta']
    )) {
        define('PRESSPERMIT_CIRCLES_VERSION', $ext_version);

        define('PRESSPERMIT_CIRCLES_DB_VERSION', '1.0');
        
        class_alias('\PressShack\LibArray', '\PublishPress\Permissions\Circles\DB\Arr');

        require_once(__DIR__ . '/classes/Permissions/Circles.php');

        class_alias('\PublishPress\Permissions\Circles', '\PublishPress\Permissions\Circles\DB\Circles');

        if ( is_admin() ) {
            class_alias('\PublishPress\Permissions\Circles', '\PublishPress\Permissions\Circles\UI\Circles');
            class_alias('\PublishPress\Permissions\Circles', '\PublishPress\Permissions\Circles\UI\Handlers\Circles');
        }

        require_once(__DIR__ . '/classes/Permissions/CirclesHooks.php');
        new \PublishPress\Permissions\CirclesHooks();

        if (is_admin()) {
            require_once(__DIR__ . '/classes/Permissions/CirclesHooksAdmin.php');
            new \PublishPress\Permissions\CirclesHooksAdmin();
        }
    }
} else {
    add_action(
        'init',
        function()
        {
            do_action('presspermit_duplicate_module', 'circles', dirname(plugin_basename(__FILE__)));
        }
    );
    return;
}
