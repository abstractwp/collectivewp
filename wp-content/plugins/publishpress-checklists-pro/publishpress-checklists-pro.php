<?php
/**
 * PublishPress Checklists Pro plugin.
 *
 * @link        https://publishpress.com/checklists/
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 *
 * @publishpress-checklists-pro
 * Plugin Name: PublishPress Checklists Pro
 * Plugin URI:  https://publishpress.com/
 * Version: 2.7.4
 * Description: Add support for checklists in WordPress
 * Author:      PublishPress
 * Author URI:  https://publishpress.com
 * Text Domain: publishpress-checklists-pro
 * Domain Path: /languages
 */

use PublishPress\ChecklistsPro\PluginServiceProvider;
use WPPF\Plugin\DIContainer;
use WPPF\Plugin\ServicesAbstract;

$includeFilebRelativePath = '/publishpress/publishpress-instance-protection/include.php';
if (file_exists(__DIR__ . '/vendor' . $includeFilebRelativePath)) {
    require_once __DIR__ . '/vendor' . $includeFilebRelativePath;
} else if (defined('PP_AUTHORS_VENDOR_PATH') && file_exists(PP_AUTHORS_VENDOR_PATH . $includeFilebRelativePath)) {
    require_once PP_AUTHORS_VENDOR_PATH . $includeFilebRelativePath;
}

if (class_exists('PublishPressInstanceProtection\\Config')) {
    $pluginCheckerConfig = new PublishPressInstanceProtection\Config();
    $pluginCheckerConfig->pluginSlug = 'publishpress-checklists-pro';
    $pluginCheckerConfig->pluginName = 'PublishPress Checklists Pro';
    $pluginCheckerConfig->isProPlugin = true;
    $pluginCheckerConfig->freePluginName = 'PublishPress Checklists';

    $pluginChecker = new PublishPressInstanceProtection\InstanceChecker($pluginCheckerConfig);
}

if (!defined('PPCHPRO_LOADED')) {
    define('PPCHPRO_VERSION', '2.7.4');

    define('PPCHPRO_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
    define('PPCHPRO_ITEM_ID', 6465);

    define('PUBLISHPRESS_CHECKLISTS_SKIP_VERSION_NOTICES', true);

    // Composer's autoload.
    $autoloadPath = PPCHPRO_PLUGIN_DIR_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    if (file_exists($autoloadPath)) {
        require_once $autoloadPath;
    }

    // Initialize the plugin.
    require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'publishpress' . DIRECTORY_SEPARATOR
        . 'publishpress-checklists' . DIRECTORY_SEPARATOR . 'publishpress-checklists.php';

    $container = new DIContainer();
    $container->register(new PluginServiceProvider());

    $pluginInitializer = $container->get(ServicesAbstract::PLUGIN_INITIALIZER);
    $pluginInitializer->init();

    define('PPCHPRO_LOADED', 1);
}
