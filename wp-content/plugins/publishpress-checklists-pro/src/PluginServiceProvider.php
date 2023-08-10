<?php
/**
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro;

use PublishPress\Pimple\Container;
use PublishPress\Pimple\ServiceProviderInterface;
use PublishPress\Checklists\Core\Legacy\LegacyPlugin;
use PublishPress\WordPressEDDLicense\Container as EDDContainer;
use PublishPress\WordPressEDDLicense\Services as EDDServices;
use PublishPress\WordPressEDDLicense\ServicesConfig as EDDServicesConfig;
use stdClass;
use WPPF\Buffer;
use WPPF\BufferInterface;
use WPPF\Helper\Math;
use WPPF\Helper\MathInterface;
use WPPF\Module\TemplateLoader;
use WPPF\Module\TemplateLoaderInterface;
use WPPF\Plugin\PluginInitializerInterface;
use WPPF\Plugin\ServicesAbstract;
use WPPF\WP\Filesystem\Filesystem;
use WPPF\WP\Filesystem\Storage\Local;
use WPPF\WP\Filesystem\Storage\StorageInterface;
use WPPF\WP\HooksHandler;
use WPPF\WP\HooksHandlerInterface;
use WPPF\WP\PluginsHandler;
use WPPF\WP\PluginsHandlerInterface;
use WPPF\WP\SettingsHandler;
use WPPF\WP\SettingsHandlerInterface;
use WPPF\WP\Translator;
use WPPF\WP\TranslatorInterface;

class PluginServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container)
    {
        /**
         * @return string
         */
        $container[ServicesAbstract::PLUGIN_NAME] = static function () {
            return 'publishpress-checklists-pro';
        };

        /**
         * @param Container $c
         *
         * @return string
         */
        $container[ServicesAbstract::PLUGIN_TITLE] = static function (Container $c) {
            $translator = $c[ServicesAbstract::TRANSLATOR];

            return esc_html__('Checklists Pro', 'publishpress-checklists-pro');
        };

        /**
         * @return string
         */
        $container[ServicesAbstract::PLUGIN_VERSION] = static function () {
            return PPCHPRO_VERSION;
        };

        /**
         * @param Container $c
         *
         * @return string
         */
        $container[ServicesAbstract::PLUGIN_FILE] = static function (Container $c) {
            return $c[ServicesAbstract::PLUGIN_NAME] . DIRECTORY_SEPARATOR . $c[ServicesAbstract::PLUGIN_NAME] . '.php';
        };

        /**
         * @return string
         */
        $container[ServicesAbstract::PLUGIN_DIR_PATH] = static function () {
            return PPCHPRO_PLUGIN_DIR_PATH;
        };

        /**
         * @param Container $c
         *
         * @return string
         */
        $container[ServicesAbstract::MODULES_DIR_PATH] = static function (Container $c) {
            return $c[ServicesAbstract::PLUGIN_DIR_PATH] . 'src' . DIRECTORY_SEPARATOR . 'modules';
        };

        /**
         * @return string
         */
        $container[ServicesAbstract::TEXT_DOMAIN] = static function () {
            return 'publishpress-checklists-pro';
        };

        /**
         * @param Container $c
         *
         * @return TranslatorInterface
         */
        $container[ServicesAbstract::TRANSLATOR] = static function (Container $c) {
            return new Translator(
                $c[ServicesAbstract::TEXT_DOMAIN],
                $c[ServicesAbstract::PLUGIN_DIR_PATH] . DIRECTORY_SEPARATOR . 'languages',
                $c[ServicesAbstract::HOOKS_HANDLER]
            );
        };

        /**
         * @return HooksHandlerInterface
         */
        $container[ServicesAbstract::HOOKS_HANDLER] = static function () {
            return new HooksHandler();
        };

        /**
         * @return PluginsHandlerInterface
         */
        $container[ServicesAbstract::PLUGINS_HANDLER] = static function () {
            return new PluginsHandler();
        };

        /**
         * @param Container $c
         *
         * @return PluginInitializerInterface
         */
        $container[ServicesAbstract::PLUGIN_INITIALIZER] = static function (Container $c) {
            return new PluginInitializer(
                $c[ServicesAbstract::HOOKS_HANDLER],
                $c[ServicesAbstract::PLUGINS_HANDLER],
                $c[ServicesAbstract::TRANSLATOR],
                $c[ServicesAbstract::MODULES_DIR_PATH]
            );
        };

        /**
         * @return string
         */
        $container[ServicesAbstract::ACTIVE_STYLE_SHEET_PATH] = static function () {
            return STYLESHEETPATH;
        };

        /**
         * @return string
         */
        $container[ServicesAbstract::ACTIVE_THEME_PATH] = static function () {
            return TEMPLATEPATH;
        };

        /**
         * @return StorageInterface
         */
        $container[ServicesAbstract::FILESYSTEM] = static function () {
            return new Filesystem(new Local());
        };

        /**
         * @return BufferInterface
         */
        $container[ServicesAbstract::BUFFER] = static function () {
            return new Buffer();
        };

        /**
         * @param Container $c
         *
         * @return TemplateLoaderInterface
         */
        $container[ServicesAbstract::TEMPLATE_LOADER] = static function (Container $c) {
            return new TemplateLoader(
                $c[ServicesAbstract::FILESYSTEM],
                $c[ServicesAbstract::BUFFER],
                $c[ServicesAbstract::HOOKS_HANDLER],
                $c[ServicesAbstract::PLUGIN_NAME],
                $c[ServicesAbstract::MODULES_DIR_PATH],
                $c[ServicesAbstract::ACTIVE_STYLE_SHEET_PATH],
                $c[ServicesAbstract::ACTIVE_THEME_PATH]
            );
        };

        /**
         * @param Container $c
         *
         * @return string
         */
        $container[ServicesAbstract::LICENSE_KEY] = static function (Container $c) {
            $options = get_option('publishpress_checklists_settings_options');

            return isset($options->license_key) ? $options->license_key : '';
        };

        /**
         * @param Container $c
         *
         * @return string
         */
        $container[ServicesAbstract::LICENSE_STATUS] = static function (Container $c) {
            $options = get_option('publishpress_checklists_settings_options');

            return isset($options->license_status) ? $options->license_status : '';
        };

        /**
         * @param Container $c
         *
         * @return string
         */
        $container[ServicesAbstract::DISPLAY_BRANDING] = static function (Container $c) {
            $options = get_option('publishpress_checklists_settings_options');

            return isset($options->display_branding) ? $options->display_branding === 'on' : true;
        };

        /**
         * @param Container $c
         *
         * @return EDDContainer
         */
        $container[ServicesAbstract::EDD_CONNECTOR] = static function (Container $c) {
            $config = new EDDServicesConfig();
            $config->setApiUrl('https://publishpress.com');
            $config->setLicenseKey($c[ServicesAbstract::LICENSE_KEY]);
            $config->setLicenseStatus($c[ServicesAbstract::LICENSE_STATUS]);
            $config->setPluginVersion($c[ServicesAbstract::PLUGIN_VERSION]);
            $config->setEddItemId(PPCHPRO_ITEM_ID);
            $config->setPluginAuthor('PublishPress');
            $config->setPluginFile($c[ServicesAbstract::PLUGIN_FILE]);

            $eddContainer = new EDDContainer();
            $eddContainer->register(new EDDServices($config));

            return $eddContainer;
        };

        /**
         * @return LegacyPlugin
         */
        $container[ServicesAbstract::LEGACY_PLUGIN] = static function () {
            return \PublishPress\Checklists\Core\Factory::getLegacyPlugin();
        };

        /**
         * @param Container $c
         *
         * @return stdClass
         */
        $container[ServicesAbstract::MAIN_MODULE] = static function (Container $c) {
            $legacyPlugin = $c[ServicesAbstract::LEGACY_PLUGIN];

            return $legacyPlugin->woocommerce;
        };

        /**
         * @param Container $c
         *
         * @return MathInterface
         */
        $container[ServicesAbstract::MATH_HELPER] = static function (Container $c) {
            return new Math();
        };

        /**
         * @param Container $c
         *
         * @return SettingsHandlerInterface
         */
        $container[ServicesAbstract::SETTINGS_HANDLER] = static function (Container $c) {
            return new SettingsHandler();
        };
    }
}
