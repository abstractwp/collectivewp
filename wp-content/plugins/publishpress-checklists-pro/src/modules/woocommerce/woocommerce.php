<?php

use Alledia\EDD_SL_Plugin_Updater;
use PublishPress\Checklists\Core\Legacy\LegacyPlugin;
use PublishPress\Checklists\Core\Legacy\Module;
use PublishPress\ChecklistsPro\Factory;
use PublishPress\ChecklistsPro\HooksAbstract as PPCHPROHooksAbstract;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\Backorder;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\Crosssell;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\Discount;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\Downloadable;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\Image;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\ManageStock;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\RegularPrice;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\SalePrice;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\SalePriceScheduled;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\Sku;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\SoldIndividually;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\Upsell;
use PublishPress\ChecklistsPro\WooCommerce\Requirement\VirtualCheckbox;
use WPPF\Plugin\ServicesAbstract;
use WPPF\WP\HooksAbstract as WPHooksAbstract;
use WPPF\WP\HooksHandlerInterface;

/**
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

/**
 * Class PPCH_WooCommerce
 *
 * @todo Refactor this module and all the modules system to use DI.
 */
#[\AllowDynamicProperties]
class PPCH_WooCommerce extends Module
{
    const METADATA_TAXONOMY = 'pp_woocommerce_meta';

    const METADATA_POSTMETA_KEY = "_pp_woocommerce_meta";

    const SETTINGS_SLUG = 'pp-woocommerce-prosettings';

    const POST_META_PREFIX = 'pp_woocommerce_custom_item_';

    public $module_name = 'woocommerce';

    /**
     * Instance for the module
     *
     * @var stdClass
     */
    public $module;

    /**
     * List of requirements, filled with instances of requirement classes.
     * The list is indexed by post types.
     *
     * @var array
     */
    private $requirements = [];

    /**
     * List of post types which supports checklist
     *
     * @var array
     */
    private $post_types = [];

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
     * Construct the PPCH_WooCommerce class
     *
     * @todo: Fix to inject the dependencies in the constructor as params.
     */
    public function __construct()
    {
        $container = Factory::getContainer();

        $this->legacyPlugin  = $container->get(ServicesAbstract::LEGACY_PLUGIN);
        $this->hooksHandler  = $container->get(ServicesAbstract::HOOKS_HANDLER);
        $this->pluginFile    = $container->get(ServicesAbstract::PLUGIN_FILE);
        $this->pluginVersion = $container->get(ServicesAbstract::PLUGIN_VERSION);

        $this->module_url = $this->getModuleUrl(__FILE__);

        // Register the module with PublishPress
        $args = [
            'title'                => esc_html__(
                'WooCommerce Support',
                'publishpress-checklists-pro'
            ),
            'short_description'    => esc_html__(
                'Define tasks that must be complete before a WooCommerce product is published',
                'publishpress-checklists-pro'
            ),
            'extended_description' => esc_html__(
                'Define tasks that must be complete before a WooCommerce product is published',
                'publishpress-checklists-pro'
            ),
            'module_url'           => $this->module_url,
            'icon_class'           => 'dashicons dashicons-feedback',
            'slug'                 => 'woocommerce',
            'default_options'      => [
                'enabled' => 'on',
            ],
            'options_page'         => false,
            'autoload'             => true,
        ];

        // Apply a filter to the default options
        $args['default_options'] = $this->hooksHandler->applyFilters(
            PPCHPROHooksAbstract::FILTER_WOOCOMMERCE_DEFAULT_OPTIONS,
            $args['default_options']
        );

        $this->module = $this->legacyPlugin->register_module($this->module_name, $args);

        $this->hooksHandler->addAction(PPCHPROHooksAbstract::ACTION_CHECKLIST_LOAD_ADDONS, [$this, 'actionLoadAddons']);
    }

    /**
     * Initialize the module. Conditionally loads if the module is enabled
     */
    public function init()
    {
        $this->hooksHandler->addAction(WPHooksAbstract::ACTION_ADMIN_INIT, [$this, 'loadUpdater']);
    }

    /**
     * Action triggered before load requirements. We use this
     * to load the filters.
     */
    public function actionLoadAddons()
    {
        $this->hooksHandler->addFilter(
            PPCHPROHooksAbstract::FILTER_POST_TYPE_REQUIREMENTS,
            [$this, 'filterPostTypeRequirements'],
            10,
            2
        );
        $this->hooksHandler->addAction(
            PPCHPROHooksAbstract::ACTION_CHECKLIST_ENQUEUE_SCRIPTS,
            [$this, 'enqueueAdminScripts']
        );
    }

    /**
     * Load default editorial metadata the first time the module is loaded
     *
     * @since 0.7
     */
    public function install()
    {
    }

    /**
     * Upgrade our data in case we need to
     *
     * @since 0.7
     */
    public function upgrade($previous_version)
    {
    }

    /**
     * Enqueue scripts and stylesheets for the admin pages.
     */
    public function enqueueAdminScripts()
    {
        $scriptHandle = 'pp-woocommerce-admin';

        wp_enqueue_script(
            $scriptHandle,
            plugins_url('/src/modules/woocommerce/assets/js/meta-box.js', $this->pluginFile),
            ['jquery', 'pp-checklists-requirements'],
            $this->pluginVersion,
            true
        );

        $localized_data = $this->hooksHandler->applyFilters(PPCHPROHooksAbstract::FILTER_LOCALIZED_DATA, []);
        wp_localize_script(
            $scriptHandle,
            'PPCH_WooCommerce',
            $localized_data
        );
    }

    /**
     * Set the requirements list for the given post type
     *
     * @param array $requirements
     * @param string $postType
     *
     * @return array
     */
    public function filterPostTypeRequirements($requirements, $postType)
    {
        $classes = [];

        if ($postType === 'product') {
            $classes = [
                VirtualCheckbox::class,
                Downloadable::class,
                RegularPrice::class,
                SalePrice::class,
                SalePriceScheduled::class,
                Discount::class,
                Sku::class,
                ManageStock::class,
                SoldIndividually::class,
                Backorder::class,
                Upsell::class,
                Crosssell::class,
                Image::class,
            ];
        }

        if (!empty($classes)) {
            $requirements = array_merge($requirements, $classes);
        }

        return $requirements;
    }

    /**
     * @return EDD_SL_Plugin_Updater
     */
    public function loadUpdater()
    {
        $container = Factory::getContainer();

        return $container->get(ServicesAbstract::EDD_CONNECTOR)['update_manager'];
    }
}
