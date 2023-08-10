<?php
/**
 * @package     PublishPress\\WooCommerce
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\FeaturedImageSize\Requirement;


class FeaturedImageHeight extends FeaturedImageSizeBase
{
    /**
     * The priority for the action to load the requirement
     */
    const PRIORITY = 8;

    /**
     * The name of the requirement, in a slug format
     *
     * @var string
     */
    public $name = 'featured_image_height';

    /**
     * @var int
     */
    public $position = 104;

    /**
     * @var int
     */
    protected $thumbDataIndexForDimension = 2;

    /**
     * Initialize the language strings for the instance
     *
     * @return void
     */
    public function init_language()
    {
        $this->lang['label_settings']       = __('Featured image height', 'publishpress-checklists-pro');
        $this->lang['label_min_singular']   = __(
            'Minimum %spx for the featured image height',
            'publishpress-checklists-pro'
        );
        $this->lang['label_min_plural']     = __(
            'Minimum %spx for the featured image height',
            'publishpress-checklists-pro'
        );
        $this->lang['label_max_singular']   = __(
            'Maximum %spx for the featured image height',
            'publishpress-checklists-pro'
        );
        $this->lang['label_max_plural']     = __(
            'Maximum %spx for the featured image height',
            'publishpress-checklists-pro'
        );
        $this->lang['label_exact_singular'] = __('Featured image height of %spx', 'publishpress-checklists-pro');
        $this->lang['label_exact_plural']   = __('Featured image height of %spx', 'publishpress-checklists-pro');
        $this->lang['label_between']        = __(
            'Featured image height between %spx and %spx',
            'publishpress-checklists-pro'
        );
    }
}
