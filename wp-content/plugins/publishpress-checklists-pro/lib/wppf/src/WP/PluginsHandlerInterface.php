<?php
/**
 * @package     WPPF
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace WPPF\WP;


interface PluginsHandlerInterface
{
    /**
     * @param string $pluginFolder
     *
     * @return array
     */
    public function getPlugins($pluginFolder = '');

    /**
     * @param string $plugin
     *
     * @return bool
     */
    public function isPluginActive($plugin);

    /**
     * @param string $pluginName
     * @param bool $silent
     * @param mixed $networkWide
     *
     * @return mixed
     */
    public function deactivatePluginIfActive($pluginName, $silent = false, $networkWide = null);
}
