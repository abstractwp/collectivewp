<?php
/**
 * @package     PublishPress\\WooCommerce
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\FeaturedImageSize\Requirement;


class FeaturedImageWidth extends FeaturedImageSizeBase
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
    public $name = 'featured_image_width';

    /**
     * @var int
     */
    public $position = 103;

    /**
     * @var int
     */
    protected $thumbDataIndexForDimension = 1;

    /**
     * Initialize the language strings for the instance
     *
     * @return void
     */
    public function init_language()
    {
        $this->lang['label_settings']       = __('Featured image width', 'publishpress-checklists-pro');
        $this->lang['label_min_singular']   = __(
            'Minimum %spx for the featured image width',
            'publishpress-checklists-pro'
        );
        $this->lang['label_min_plural']     = __(
            'Minimum %spx for the featured image width',
            'publishpress-checklists-pro'
        );
        $this->lang['label_max_singular']   = __(
            'Maximum %spx for the featured image width',
            'publishpress-checklists-pro'
        );
        $this->lang['label_max_plural']     = __(
            'Maximum %spx for the featured image width',
            'publishpress-checklists-pro'
        );
        $this->lang['label_exact_singular'] = __('Featured image width of %spx', 'publishpress-checklists-pro');
        $this->lang['label_exact_plural']   = __('Featured image width of %spx', 'publishpress-checklists-pro');
        $this->lang['label_between']        = __(
            'Featured image width between %spx and %spx',
            'publishpress-checklists-pro'
        );
    }
}
