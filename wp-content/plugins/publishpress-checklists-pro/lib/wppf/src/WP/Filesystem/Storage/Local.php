<?php
/**
 * @package     WPPF
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace WPPF\WP\Filesystem\Storage;


class Local implements StorageInterface
{
    /**
     * @inheritDoc
     */
    public function has($path)
    {
        global $wp_filesystem;

        return $wp_filesystem->exists($path);
    }
}
