<?php
/**
 * @package     PublishPress\Reminders\
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (c) 2022 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.3
 */

namespace PublishPress\Addon\Reminders;

defined('ABSPATH') or die('No direct script access allowed.');

if (!defined('PP_PRO_REMINDERS_LOADED')) {
    require_once __DIR__ . '/../includes.php';
}

/**
 * Class Factory
 */
abstract class Factory
{
    /**
     * @var Container
     */
    protected static $container = null;

    /**
     * @return Container
     */
    public static function get_container()
    {
        if (static::$container === null) {
            $module   = PublishPress()->reminders;
            $services = new Services($module);

            static::$container = new Container();
            static::$container->register($services);
        }

        return static::$container;
    }
}
