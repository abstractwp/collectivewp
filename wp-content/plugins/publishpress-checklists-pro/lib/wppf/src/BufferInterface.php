<?php
/**
 * @package     WPPF
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace WPPF;


interface BufferInterface
{
    public function start();

    /**
     * @return mixed
     */
    public function getClean();
}
