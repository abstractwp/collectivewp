<?php
/**
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro;

use PublishPress\ChecklistsPro\HooksAbstract as PPCHPROHooksAbstract;
use WPPF\Plugin\PluginInitializerInterface;
use WPPF\WP\HooksAbstract as WPHooksAbstract;
use WPPF\WP\HooksHandlerInterface;
use WPPF\WP\PluginsHandlerInterface;
use WPPF\WP\TranslatorInterface;

#[\AllowDynamicProperties]
class PluginInitializer implements PluginInitializerInterface
{
    const FREE_PLUGIN_NAME = 'publishpress-checklists';

    /**
     * @var HooksHandlerInterface
     */
    private $hooksHandler;

    /**
     * @var PluginsHandlerInterface
     */
    private $pluginsHandler;

    /**
     * @var string
     */
    private $modulesDirPath;

    /**
     * @inheritDoc
     */
    public function __construct(
        HooksHandlerInterface $hooksHandler,
        PluginsHandlerInterface $pluginsHandler,
        TranslatorInterface $translator,
        $modulesDirPath
    ) {
        $this->hooksHandler   = $hooksHandler;
        $this->pluginsHandler = $pluginsHandler;
        $this->translator     = $translator;
        $this->modulesDirPath = $modulesDirPath;
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->setHooks();
    }

    private function setHooks()
    {
        $this->hooksHandler->addAction(WPHooksAbstract::ACTION_PLUGINS_LOADED, [$this, 'loadTextDomain']);
        $this->hooksHandler->addFilter(PPCHPROHooksAbstract::FILTER_MODULES_DIRS, [$this, 'filterModulesDirs']);
    }

    /**
     * Load the text domain.
     */
    public function loadTextDomain()
    {
        load_plugin_textdomain(
            'publishpress-checklists-pro',
            false,
            plugin_basename(PPCHPRO_PLUGIN_DIR_PATH) . '/languages/'
        );
    }

    /**
     * @param array $dirs
     *
     * @return array
     */
    public function filterModulesDirs($dirs)
    {
        $dirs['woocommerce']         = rtrim($this->modulesDirPath, DIRECTORY_SEPARATOR);
        $dirs['prosettings']         = rtrim($this->modulesDirPath, DIRECTORY_SEPARATOR);
        $dirs['featured-image-size'] = rtrim($this->modulesDirPath, DIRECTORY_SEPARATOR);

        return $dirs;
    }
}
