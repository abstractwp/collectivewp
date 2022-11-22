<?php

use Alledia\EDD_SL_Plugin_Updater;
use PublishPress\EDD_License\Core\Setting\Field\License_key;
use PublishPressPro\Factory;
use WPPF2\Plugin\ServicesAbstract;

/**
 * @package     PublishPressPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

/**
 * Class class PP_ProSettings extends Module
 *
 * @todo Refactor this module and all the modules system to use DI.
 */
class PP_ProSettings extends PP_Module
{
    const OPTIONS_GROUP_NAME = 'publishpress_prosettings_options';

    const LICENSE_STATUS_VALID = 'valid';

    const LICENSE_STATUS_INVALID = 'invalid';

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
     * @var string
     */
    private $pluginFile;

    /**
     * @var string
     */
    private $pluginVersion;

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
     * @var bool
     */
    private $displayBranding = true;

    /**
     * @var EDD_SL_Plugin_Updater
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

        $this->legacyPlugin    = $container[ServicesAbstract::LEGACY_PLUGIN];
        $this->eddConnector    = $container[ServicesAbstract::EDD_CONNECTOR];
        $this->licenseKey      = $container[ServicesAbstract::LICENSE_KEY];
        $this->licenseStatus   = $container[ServicesAbstract::LICENSE_STATUS];
        $this->displayBranding = $container[ServicesAbstract::DISPLAY_BRANDING];
        $this->updateManager   = $this->eddConnector['update_manager'];

        $this->module_url = $this->get_module_url(__FILE__);

        // Register the module with PublishPress
        $args = [
            'title'           => __('Pro Settings', 'publishpress-pro'),
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
        add_action('publishpress_register_settings_before', [$this, 'registerSettings'], 10, 2);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
        add_filter('publishpress_validate_module_settings', [$this, 'validateModuleSettings'], 10, 2);
        add_filter('publishpress_show_footer', [$this, 'filterDisplayBranding'], 11);
    }

    /**
     * Enqueue scripts and stylesheets for the admin pages.
     */
    public function enqueueAdminScripts()
    {
        if (isset($_GET['page']) && $_GET['page'] === 'pp-modules-settings') {
            wp_enqueue_style(
                'publishpress_prosettings_admin',
                PUBLISHPRESS_PRO_PLUGIN_URL . 'modules/prosettings/assets/css/admin.css',
                [],
                PUBLISHPRESS_PRO_VERSION
            );
        }
    }

    public function registerSettings($page, $section)
    {
        add_settings_field(
            'license_key',
            __('License key:', 'publishpress-pro'),
            [$this, 'settingsLicenseKeyOption'],
            $page,
            $section
        );

        add_settings_field(
            'display_branding',
            __('Display PublishPress branding in the admin:', 'publishpress-pro'),
            [$this, 'settingsBrandingOption'],
            $page,
            $section
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

        echo '<input name="publishpress_module_name[]" type="hidden" value="prosettings" />';
    }

    /**
     * Branding options
     *
     * @since 0.7
     */
    public function settingsBrandingOption()
    {
        $id = self::OPTIONS_GROUP_NAME . '_display_branding';

        echo '<label for="' . $id . '">';
        echo '<input id="' . $id . '" name="'
            . self::OPTIONS_GROUP_NAME . '[display_branding]"';
        checked($this->displayBranding, true);
        echo ' type="checkbox" value="on" /></label>';
    }

    public function validateModuleSettings($options, $moduleName)
    {
        if ($moduleName === 'prosettings') {
            if (isset($options['license_key'])) {
                if ($this->licenseKey !== $options['license_key'] || empty($this->licenseStatus) || $this->licenseStatus !== self::LICENSE_STATUS_VALID) {
                    $options['license_status'] = $this->validateLicenseKey($options['license_key']);
                }
            }

            if (!isset($options['display_branding'])) {
                $options['display_branding'] = 'off';
            }
        }

        return $options;
    }

    public function validateLicenseKey($licenseKey)
    {
        $licenseManager = $this->eddConnector['license_manager'];

        return $licenseManager->validate_license_key($licenseKey, PUBLISHPRESS_PRO_ITEM_ID);
    }

    public function filterDisplayBranding($shouldDisplay = true)
    {
        global $current_screen;

        $conditions = [
            $current_screen->base === 'toplevel_page_pp-calendar',
            $current_screen->base === 'publishpress_page_pp-content-overview',
            $current_screen->base === 'publishpress_page_pp-notif-log',
            $current_screen->base === 'publishpress_page_pp-manage-roles',
            $current_screen->base === 'publishpress_page_pp-modules-settings',
            $current_screen->base === 'post' && $current_screen->post_type === 'psppnotif_workflow',
            $current_screen->base === 'edit' && $current_screen->post_type === 'psppnotif_workflow',
        ];

        if (in_array(true, $conditions)) {
            $shouldDisplay = true;
        }

        if ($shouldDisplay) {
            return $this->displayBranding;
        }

        return $shouldDisplay;
    }
}
