<?php
/**
 * @package     WPPF
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace WPPF\WP;


class PluginsHandler implements PluginsHandlerInterface
{
    /**
     * @param string $pluginName
     * @param bool $silent
     * @param mixed $networkWide
     */
    public function deactivatePluginIfActive($pluginName, $silent = false, $networkWide = null)
    {
        $allPlugins = $this->getPlugins();

        foreach ($allPlugins as $pluginFile => $pluginData) {
            if (isset($pluginData['TextDomain']) && $pluginName === $pluginData['TextDomain'] && $this->isPluginActive(
                    $pluginFile
                )) {
                deactivate_plugins($pluginFile, $silent, $networkWide);
            }
        }
    }

    /**
     * @param string $pluginFolder
     *
     * @return array
     */
    public function getPlugins($pluginFolder = '')
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return get_plugins($pluginFolder);
    }

    /**
     * @param string $plugin
     *
     * @return bool
     */
    public function isPluginActive($plugin)
    {
        return is_plugin_active($plugin);
    }
}
