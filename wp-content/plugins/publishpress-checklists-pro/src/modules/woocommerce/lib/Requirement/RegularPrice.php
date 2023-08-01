<?php
/**
 * @package     PublishPress\WooCommerce
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\WooCommerce\Requirement;


use PublishPress\ChecklistsPro\Factory;
use WPPF\Plugin\ServicesAbstract;

class RegularPrice extends Base
{
    /**
     * The name of the requirement, in a slug format
     *
     * @var string
     */
    public $name = 'regular_price';

    /**
     * @inheritDoc
     */
    public function __construct($module, $post_type)
    {
        parent::__construct($module, $post_type);

        $container = Factory::getContainer();
    }

    /**
     * Initialize the language strings for the instance
     *
     * @return void
     */
    public function init_language()
    {
        $this->lang['label']          = esc_html__('Enter a "Regular price"', 'publishpress-checklists-pro');
        $this->lang['label_settings'] = esc_html__('Enter a "Regular price"', 'publishpress-checklists-pro');
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
        $product = wc_get_product($post->ID);

        $price = $product->get_regular_price();

        return !empty($price);
    }
}
