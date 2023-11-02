<?php
/**
 * @package     WPPF
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace WPPF\WP\Filesystem;


use WPPF\WP\Filesystem\Storage\StorageInterface;

interface FilesystemInterface
{
    /**
     * FilesystemInterface constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage);
}
