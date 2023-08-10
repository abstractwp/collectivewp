<?php

/**
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

use PublishPress\Checklists\Core\Legacy\LegacyPlugin;
use PublishPress\Checklists\Core\Legacy\Module;
use PublishPress\ChecklistsPro\Factory;
use PublishPress\ChecklistsPro\FeaturedImageSize\Requirement\FeaturedImageHeight;
use PublishPress\ChecklistsPro\FeaturedImageSize\Requirement\FeaturedImageWidth;
use PublishPress\ChecklistsPro\HooksAbstract as PPCHPROHooksAbstract;
use WPPF\Plugin\ServicesAbstract;
use WPPF\WP\HooksHandlerInterface;

/**
 * Class PPCH_WooCommerce
 *
 * @todo Refactor this module and all the modules system to use DI.
 */
#[\AllowDynamicProperties]
class PPCH_Featured_Image_Size extends Module
{
    const SETTINGS_SLUG = 'pp-featured-image-size-prosettings';

    const POST_META_PREFIX = 'pp_featured_image_size_custom_item_';

    public $module_name = 'featured_image_size';

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
                'Featured Image Size Support',
                'publishpress-checklists-pro'
            ),
            'short_description'    => esc_html__(
                'Define tasks that verify the featured image size',
                'publishpress-checklists-pro'
            ),
            'extended_description' => esc_html__(
                'Define tasks that verify the featured image size',
                'publishpress-checklists-pro'
            ),
            'module_url'           => $this->module_url,
            'icon_class'           => 'dashicons dashicons-feedback',
            'slug'                 => 'featured-image-size',
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

    public function enqueueAdminScripts()
    {
        $scriptHandle = 'pp-featured-image-size-admin';

        wp_enqueue_script(
            $scriptHandle,
            plugins_url('/src/modules/featured-image-size/assets/js/meta-box.js', $this->pluginFile),
            ['jquery', 'pp-checklists-requirements'],
            $this->pluginVersion,
            true
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
        if (!post_type_supports($postType, 'thumbnail')) {
            return $requirements;
        }

        $requirements[] = FeaturedImageWidth::class;
        $requirements[] = FeaturedImageHeight::class;

        return $requirements;
    }
}
