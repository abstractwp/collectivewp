<?php
/**
 * @package     PublishPress\\WooCommerce
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\FeaturedImageSize\Requirement;

use PublishPress\Checklists\Core\Requirement\Base_counter;
use stdClass;


class FeaturedImageSizeBase extends Base_counter
{
    /**
     * @var int
     */
    protected $thumbDataIndexForDimension = 0;

    public function __construct($module, $post_type)
    {
        parent::__construct($module, $post_type);

        $this->setUnitText('px');
    }

    /**
     * Returns the current status of the requirement.
     *
     * @param stdClass $post
     * @param mixed $option_value
     *
     * @return mixed
     */
    public function get_current_status($post, $option_value)
    {
        $thumbId = get_post_thumbnail_id($post->ID);

        if (empty($thumbId)) {
            return false;
        }

        $thumbData      = wp_get_attachment_image_src($thumbId, 'full');
        $thumbDimension = (int)$thumbData[$this->thumbDataIndexForDimension];

        $minValue = $option_value[0];
        $maxValue = $option_value[1];

        // Both same value = exact
        if ($minValue === $maxValue) {
            return $thumbDimension === $minValue;
        }

        // Min not empty, max empty or < min = only min
        if ($minValue > 0 && $maxValue < $minValue) {
            return $thumbDimension >= $minValue;
        }

        // Min not empty, max not empty and > min = both min and max
        if ($minValue > 0 && $maxValue > $minValue) {
            return $thumbDimension >= $minValue && $thumbDimension <= $maxValue;
        }

        // Min empty, max not empty and > min = only max
        if ($minValue === 0 && $maxValue > $minValue) {
            return $thumbDimension <= $maxValue;
        }
    }
}
