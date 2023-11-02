<?php
/**
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro;

use PublishPress\Psr\Container\ContainerInterface;
use PublishPress\Checklists\Core\Legacy\LegacyPlugin;
use WPPF\Plugin\DIContainer;
use WPPF\Plugin\ServicesAbstract;

/**
 * Class Factory
 */
abstract class Factory
{
    /**
     * @var ContainerInterface
     */
    protected static $container = null;

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        if (static::$container === null) {
            static::$container = new DIContainer();
            static::$container->register(new PluginServiceProvider());
        }

        return static::$container;
    }

    /**
     * @return LegacyPlugin
     */
    public static function getLegacyPlugin()
    {
        return static::$container->get(ServicesAbstract::LEGACY_PLUGIN);
    }
}
