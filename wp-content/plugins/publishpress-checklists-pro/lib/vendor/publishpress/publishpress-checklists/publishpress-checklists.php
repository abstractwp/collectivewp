<?php
/**
 * Plugin Name: PublishPress Checklists
 * Plugin URI:  https://publishpress.com/
 * Description: Add support for checklists in WordPress
 * Author:      PublishPress
 * Author URI:  https://publishpress.com
 * Version: 2.9.0
 * Text Domain: publishpress-checklists
 * Domain Path: /languages
 * Requires at least: 5.5
 * Requires PHP: 7.2.5
 *
 * PublishPress Checklists plugin bootstrap file.
 *
 * @publishpress-checklists
 *
 * @link        https://publishpress.com/checklists/
 * @package     PublishPress\Checklists
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 *
 */

use PPVersionNotices\Module\MenuLink\Module;
use PublishPress\Checklists\Core\Autoloader;
use PublishPress\Checklists\Core\Plugin;

global $wp_version;

$min_php_version = '7.2.5';
$min_wp_version  = '5.5';

// If the PHP or WP version is not compatible, terminate the plugin execution.
$invalid_php_version = version_compare(phpversion(), $min_php_version, '<');
$invalid_wp_version = version_compare($wp_version, $min_wp_version, '<');

if ($invalid_php_version || $invalid_wp_version) {
    return;
}

$includeFileRelativePath = '/publishpress/instance-protection/include.php';

if (file_exists(__DIR__ . '/lib/vendor' . $includeFileRelativePath)) {
    require_once __DIR__ . '/lib/vendor' . $includeFileRelativePath;
} else if (defined('PPCH_LIB_VENDOR_PATH') && file_exists(PPCH_LIB_VENDOR_PATH . $includeFileRelativePath)) {
    require_once PPCH_LIB_VENDOR_PATH . $includeFileRelativePath;
}

if (class_exists('PublishPressInstanceProtection\\Config')) {
    $pluginCheckerConfig = new PublishPressInstanceProtection\Config();
    $pluginCheckerConfig->pluginSlug = 'publishpress-checklists';
    $pluginCheckerConfig->pluginName = 'PublishPress Checklists';

    $pluginChecker = new PublishPressInstanceProtection\InstanceChecker($pluginCheckerConfig);
}

if (!defined('PPCH_LOADED')) {
    if (! defined('PPCH_LIB_VENDOR_PATH')) {
        define('PPCH_LIB_VENDOR_PATH', __DIR__ . '/lib/vendor');
    }

    if (! defined('PUBLISHPRESS_CHECKLISTS_VENDOR_PATH')) {
        /**
         * @deprecated 2.9.0 Use PPCH_LIB_VENDOR_PATH instead.
         */
        define('PUBLISHPRESS_CHECKLISTS_VENDOR_PATH', PPCH_LIB_VENDOR_PATH);
    }

    $autoloadFilePath = PPCH_LIB_VENDOR_PATH . '/autoload.php';
    if (! class_exists('ComposerAutoloaderInitPPChecklists')
        && is_file($autoloadFilePath)
        && is_readable($autoloadFilePath)
    ) {
        require_once $autoloadFilePath;
    }

    add_action('plugins_loaded', function () {
        if (!defined('PPCH_LOADED')) {
            define('PPCH_LOADED', 1);
            define('PPCH_PATH_BASE', plugin_dir_path(__FILE__));
            define('PPCH_VERSION', '2.9.0');
            define('PPCH_FILE', __DIR__ . '/publishpress-checklists.php');
            define('PPCH_MODULES_PATH', PPCH_PATH_BASE . '/modules');
            define('PPCH_RELATIVE_PATH', 'publishpress-checklists');

            if (is_admin() && ! defined('PUBLISHPRESS_CHECKLISTS_SKIP_VERSION_NOTICES')) {
                $includesPath = __DIR__ . DIRECTORY_SEPARATOR . 'lib'  . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'publishpress' . DIRECTORY_SEPARATOR
                . 'wordpress-version-notices' . DIRECTORY_SEPARATOR . 'includes.php';

                if (file_exists($includesPath)) {
                    require_once $includesPath;
                }

                if (current_user_can('install_plugins')) {
                    add_filter(
                        \PPVersionNotices\Module\TopNotice\Module::SETTINGS_FILTER,
                        function ($settings) {
                        $settings['publishpress-checklists'] = [
                            'message' => 'You\'re using PublishPress Checklists Free. The Pro version has more features and support. %sUpgrade to Pro%s',
                            'link' => 'https://publishpress.com/links/checklists-banner',
                            'screens' => [
                                [
                                    'base' => 'toplevel_page_ppch-checklists',
                                    'id' => 'toplevel_page_ppch-checklists',
                                ],
                                [
                                    'base' => 'checklists_page_ppch-settings',
                                    'id' => 'checklists_page_ppch-settings',
                                ],
                            ]
                        ];

                        return $settings;
                    }
                    );

                    $manageChecklistsCap = apply_filters(
                        'publishpress_checklists_manage_checklist_cap',
                        'manage_checklists'
                    );
                    if (current_user_can($manageChecklistsCap)) {
                        add_filter(
                            Module::SETTINGS_FILTER,
                            function ($settings) {
                            $settings['publishpress-checklists'] = [
                                'parent' => 'ppch-checklists',
                                'label' => 'Upgrade to Pro',
                                'link' => 'https://publishpress.com/links/checklists-menu',
                            ];

                            return $settings;
                        }
                        );
                    }
                }
            }

            if (is_admin()) {
                if (! class_exists('PublishPress\\Checklists\\Core\\Autoloader')) {
                    require_once __DIR__ . '/core/Autoloader.php';
                }

                Autoloader::register();
                Autoloader::addNamespace('PublishPress\\Checklists\\Core\\', __DIR__ . '/core/');
                Autoloader::addNamespace('PublishPress\\Checklists\\Permalinks\\', __DIR__ . '/modules/permalinks/lib/');
                Autoloader::addNamespace('PublishPress\\Checklists\\Yoastseo\\', __DIR__ . '/modules/yoastseo/lib/');

                $plugin = new Plugin();
                $plugin->init();
            }
        }
    }, -10);
}
