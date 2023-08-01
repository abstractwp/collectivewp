<?php

use PublishPress\Checklists\Core\Legacy\LegacyPlugin;
use PublishPress\Checklists\Core\Legacy\Module;
use PublishPress\ChecklistsPro\Factory;
use PublishPress\ChecklistsPro\HooksAbstract as PPCHPROHooksAbstract;
use PublishPress\WordPressEDDLicense\Container as EDDContainer;
use PublishPress\WordPressEDDLicense\Setting\Field\License_key;
use WPPF\Plugin\ServicesAbstract;
use WPPF\WP\HooksHandlerInterface;
use WPPF\WP\SettingsHandlerInterface;

/**
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

/**
 * Class class PPCH_ProSettings extends Module
 *
 * @todo Refactor this module and all the modules system to use DI.
 */
class PPCH_ProSettings extends Module
{
    const OPTIONS_GROUP_NAME = 'publishpress_checklists_settings_options';

    const LICENSE_STATUS_VALID = 'valid';

    const LICENSE_STATUS_INVALID = 'invalid';

    const METADATA_TAXONOMY = 'pp_prosettings_meta';

    const METADATA_POSTMETA_KEY = "_pp_prosettings_meta";

    const SETTINGS_SLUG = 'pp-prosettings-prosettings';

    public $module_name = 'prosettings';

    /**
     * Instance for the module
     *
     * @var stdClass
     */
    public $module;

    /**
     * @var LegacyPlugin
     */
    private $legacyPlugin;

    /**
     * @var HooksHandlerInterface
     */
    private $hooksHandler;

    /**
     * @var string
     */
    private $pluginFile;

    /**
     * @var string
     */
    private $pluginVersion;

    /**
     * @var SettingsHandlerInterface
     */
    private $settingsHandler;

    /**
     * @var EDDContainer
     */
    private $eddConnector;

    /**
     * @var string
     */
    private $licenseKey;

    /**
     * @var string
     */
    private $licenseStatus;

    /**
     * @var \Alledia\EDD_SL_Plugin_Updater
     */
    private $updateManager;

    /**
     * Construct the PPCH_WooCommerce class
     *
     * @todo: Fix to inject the dependencies in the constructor as params.
     */
    public function __construct()
    {
        $container = Factory::getContainer();

        $this->legacyPlugin    = $container->get(ServicesAbstract::LEGACY_PLUGIN);
        $this->hooksHandler    = $container->get(ServicesAbstract::HOOKS_HANDLER);
        $this->pluginFile      = $container->get(ServicesAbstract::PLUGIN_FILE);
        $this->pluginVersion   = $container->get(ServicesAbstract::PLUGIN_VERSION);
        $this->settingsHandler = $container->get(ServicesAbstract::SETTINGS_HANDLER);
        $this->eddConnector    = $container->get(ServicesAbstract::EDD_CONNECTOR);
        $this->licenseKey      = $container->get(ServicesAbstract::LICENSE_KEY);
        $this->licenseStatus   = $container->get(ServicesAbstract::LICENSE_STATUS);
        $this->updateManager   = $this->eddConnector['update_manager'];

        $this->module_url = $this->getModuleUrl(__FILE__);

        // Register the module with PublishPress
        $args = [
            'title'           => esc_html__('Pro Settings', 'publishpress-checklists-pro'),
            'module_url'      => $this->module_url,
            'icon_class'      => 'dashicons dashicons-feedback',
            'slug'            => 'prosettings',
            'default_options' => [
                'enabled'        => 'on',
                'license_key'    => '',
                'license_status' => '',
            ],
            'options_page'    => false,
            'autoload'        => true,
        ];

        // Apply a filter to the default options
        $args['default_options'] = $this->hooksHandler->applyFilters(
            PPCHPROHooksAbstract::FILTER_WOOCOMMERCE_DEFAULT_OPTIONS,
            $args['default_options']
        );

        $this->module = $this->legacyPlugin->register_module($this->module_name, $args);
    }

    /**
     * Initialize the module. Conditionally loads if the module is enabled
     */
    public function init()
    {
        $this->setHooks();
    }

    private function setHooks()
    {
        $this->hooksHandler->addAction(
            PPCHPROHooksAbstract::ACTION_CHECKLISTS_REGISTER_SETTINGS,
            [$this, 'registerSettings']
        );
        $this->hooksHandler->addAction(
            PPCHPROHooksAbstract::ACTION_ADMIN_ENQUEUE_SCRIPTS,
            [$this, 'enqueueAdminScripts']
        );
        $this->hooksHandler->addAction(
            PPCHPROHooksAbstract::ACTION_ADMIN_ENQUEUE_SCRIPTS,
            [$this, 'enqueueAdminScripts']
        );

        $this->hooksHandler->addFilter(
            PPCHPROHooksAbstract::FILTER_VALIDATE_MODULE_SETTINGS,
            [$this, 'validateModuleSettings']
        );
        $this->hooksHandler->addFilter(
            PPCHPROHooksAbstract::FILTER_DISPLAY_BRANDING,
            [$this, 'filterDisplayBranding']
        );
    }

    /**
     * Enqueue scripts and stylesheets for the admin pages.
     */
    public function enqueueAdminScripts()
    {
        wp_enqueue_style(
            'ppch_prosettings_admin',
            plugins_url('/src/modules/prosettings/assets/css/admin.css', $this->pluginFile),
            [],
            $this->pluginVersion
        );
    }

    public function registerSettings()
    {
        $this->settingsHandler->addField(
            'license_key',
            esc_html__('License key:', 'publishpress-checklists-pro'),
            [$this, 'settingsLicenseKeyOption'],
            self::OPTIONS_GROUP_NAME,
            self::OPTIONS_GROUP_NAME . '_general'
        );

        $this->settingsHandler->addField(
            'display_branding',
            esc_html__('Display PublishPress branding in the admin:', 'publishpress-checklists-pro'),
            [$this, 'settingsBrandingOption'],
            self::OPTIONS_GROUP_NAME,
            self::OPTIONS_GROUP_NAME . '_general'
        );
    }

    public function settingsLicenseKeyOption()
    {
        $container = Factory::getContainer();

        $value  = isset($container[ServicesAbstract::LICENSE_KEY]) ? $container[ServicesAbstract::LICENSE_KEY] : '';
        $status = isset($container[ServicesAbstract::LICENSE_STATUS]) ? $container[ServicesAbstract::LICENSE_STATUS] : self::LICENSE_STATUS_INVALID;

        echo new License_key(
            [
                'options_group_name' => self::OPTIONS_GROUP_NAME,
                'name' => 'license_key',
                'id' => self::OPTIONS_GROUP_NAME . '_license_key',
                'value' => $value,
                'class' => '',
                'license_status' => $status,
                'link_more_info' => '',
            ]
        );
    }

    /**
     * Branding options
     *
     * @since 0.7
     */
    public function settingsBrandingOption()
    {
        $id = self::OPTIONS_GROUP_NAME . '_display_branding';

        $options = get_option('publishpress_checklists_settings_options');

        $displayBranding = isset($options->display_branding) ? $options->display_branding : 'on';

        echo '<label for="' . $id . '">';
        echo '<input id="' . $id . '" name="'
            . self::OPTIONS_GROUP_NAME . '[display_branding]"';
        checked($displayBranding, 'on');
        echo ' type="checkbox" value="on" /></label>';
    }

    public function validateModuleSettings($options)
    {
        if (isset($options['license_key'])) {
            if ($this->licenseKey !== $options['license_key'] || empty($this->licenseStatus) || $this->licenseStatus !== self::LICENSE_STATUS_VALID) {
                $options['license_status'] = $this->validateLicenseKey($options['license_key']);
            }
        }

        if (!isset($options['display_branding'])) {
            $options['display_branding'] = 'off';
        }

        return $options;
    }

    public function validateLicenseKey($licenseKey)
    {
        $licenseManager = $this->eddConnector['license_manager'];

        return $licenseManager->validate_license_key($licenseKey, PPCHPRO_ITEM_ID);
    }

    public function filterDisplayBranding($display)
    {
        $container = Factory::getContainer();

        return $container->get(ServicesAbstract::DISPLAY_BRANDING);
    }

    private function hasValidLicenseKeySet()
    {
        $container = Factory::getContainer();

        return !empty($container->get(ServicesAbstract::LICENSE_KEY)) && $container->get(
                ServicesAbstract::LICENSE_STATUS
            ) === self::LICENSE_STATUS_VALID;
    }
}
