<?php
/**
 * Plugin Name: PublishPress Pro
 * Plugin URI: https://publishpress.com/
 * Description: PublishPress helps you plan and publish content with WordPress. Features include a content calendar, notifications, and custom statuses.
 * Author: PublishPress
 * Author URI: https://publishpress.com
 * Version: 3.9.0
 * Text Domain: publishpress-pro
 * Domain Path: /languages
 *
 * Copyright (c) 2022 PublishPress
 *
 * ------------------------------------------------------------------------------
 * Based on Edit Flow
 * Author: Daniel Bachhuber, Scott Bressler, Mohammad Jangda, Automattic, and
 * others
 * Copyright (c) 2009-2016 Mohammad Jangda, Daniel Bachhuber, et al.
 * ------------------------------------------------------------------------------
 *
 * GNU General Public License, Free Software Foundation <http://creativecommons.org/licenses/GPL/2.0/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     PublishPress
 * @category    Core
 * @author      PublishPress
 * @copyright   Copyright (c) 2022 PublishPress. All rights reserved.
 */

namespace PublishPressPro;

use WPPF2\Plugin\ServicesAbstract;
use PublishPressInstanceProtection;

$includeFileRelativePath = '/publishpress/publishpress-instance-protection/include.php';
if (file_exists(__DIR__ . '/vendor' . $includeFileRelativePath)) {
    require_once __DIR__ . '/vendor' . $includeFileRelativePath;
}

if (class_exists('PublishPressInstanceProtection\\Config')) {
    $pluginCheckerConfig = new PublishPressInstanceProtection\Config();
    $pluginCheckerConfig->pluginSlug     = 'publishpress-pro';
    $pluginCheckerConfig->pluginName     = 'PublishPress Pro';
    $pluginCheckerConfig->freePluginName = 'PublishPress';
    $pluginCheckerConfig->isProPlugin    = true;

    $pluginChecker = new PublishPressInstanceProtection\InstanceChecker($pluginCheckerConfig);
}

if (! defined('PUBLISHPRESS_PRO_ACTION_LOAD_BASE_PLUGIN')) {
    define('PUBLISHPRESS_PRO_ACTION_LOAD_BASE_PLUGIN', 'publishpress_pro_load_base_plugin');
}

if (! defined('PUBLISHPRESS_PRO_ACTION_HALT')) {
    define('PUBLISHPRESS_PRO_ACTION_HALT', 'publishpress_pro_halt');
}

function checkupRanSuccessfully()
{
    return require 'checkup.php';
}

if (checkupRanSuccessfully() && ! defined('PUBLISHPRESS_PRO_LOADED')) {
    define('PUBLISHPRESS_PRO_VERSION', '3.9.0');

    define('PUBLISHPRESS_PRO_DIR_PATH', plugin_dir_path(__FILE__));

    define('PUBLISHPRESS_PRO_PLUGIN_URL', plugins_url('/', __FILE__));

    define('PUBLISHPRESS_PRO_ITEM_ID', 49742);

    define('PUBLISHPRESS_SKIP_VERSION_NOTICES', true);

    // Composer's autoload.
    $autoloadPath = PUBLISHPRESS_PRO_DIR_PATH . '/vendor/autoload.php';
    if (file_exists($autoloadPath)) {
        require_once $autoloadPath;
    }

    // Initialize the free plugin.
    if (defined('PUBLISHPRESS_FREE_PLUGIN_PATH')) {
        require_once PUBLISHPRESS_FREE_PLUGIN_PATH . '/publishpress.php';

        do_action(PUBLISHPRESS_PRO_ACTION_LOAD_BASE_PLUGIN);
    } else {
        require_once PUBLISHPRESS_PRO_DIR_PATH . '/vendor/publishpress/publishpress/publishpress.php';
    }

    // Initialize the framework
    require_once PUBLISHPRESS_PRO_DIR_PATH . '/lib/wppf2/includes.php';

    // Initialize the Slack module - migrated from the Slack plugin.
    require_once PUBLISHPRESS_PRO_DIR_PATH . '/modules/slack/includes.php';

    // Initialize the Reminders module - migrated from the Reminders plugin.
    require_once PUBLISHPRESS_PRO_DIR_PATH . '/modules/reminders/includes.php';

    $container = Factory::getContainer();

    $pluginInitializer = $container->get(ServicesAbstract::PLUGIN_INITIALIZER);
    $pluginInitializer->init();

    define('PUBLISHPRESS_PRO_LOADED', 1);
}
