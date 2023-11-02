<?php
/**
 * @package     PublishPress\\WooCommerce
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\WooCommerce\Requirement;


use PublishPress\ChecklistsPro\Factory;
use PublishPress\ChecklistsPro\HooksAbstract;
use stdClass;
use WPPF\Helper\MathInterface;
use WPPF\Plugin\ServicesAbstract;
use WPPF\WP\HooksHandlerInterface;

class Discount extends BaseFloatRange
{
    /**
     * The name of the requirement, in a slug format
     *
     * @var string
     */
    public $name = 'discount';

    /**
     * @var MathInterface
     */
    private $mathHelper;

    /**
     * @var HooksHandlerInterface
     */
    private $hooksHandler;

    /**
     * @inheritDoc
     */
    public function __construct($module, $post_type)
    {
        parent::__construct($module, $post_type);

        $container = Factory::getContainer();

        $this->mathHelper   = $container->get(ServicesAbstract::MATH_HELPER);
        $this->hooksHandler = $container->get(ServicesAbstract::HOOKS_HANDLER);
    }

    /**
     * Method to initialize the Requirement, adding filters and actions to
     * interact with the Add-on.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->hooksHandler->addFilter(HooksAbstract::FILTER_LOCALIZED_DATA, [$this, 'localize_data']);
    }

    /**
     * Initialize the language strings for the instance
     *
     * @return void
     */
    public function init_language()
    {
        $this->lang['label_settings']        = esc_html__('Discount for the "Sale price"', 'publishpress-checklists-pro');
        $this->lang['label_settings_params'] = esc_html__('Min %s%% Max %s%%', 'publishpress-checklists-pro');
        $this->lang['label_min_singular']    = esc_html__('Min %s%% discount for the "Sale price"', 'publishpress-checklists-pro');
        $this->lang['label_min_plural']      = esc_html__('Min %s%% discount for the "Sale price"', 'publishpress-checklists-pro');
        $this->lang['label_max_singular']    = esc_html__('Max %s%% discount for the "Sale price"', 'publishpress-checklists-pro');
        $this->lang['label_max_plural']      = esc_html__('Max %s%% discount for the "Sale price"', 'publishpress-checklists-pro');
        $this->lang['label_exact_singular']  = esc_html__('%s%% discount for the "Sale price"', 'publishpress-checklists-pro');
        $this->lang['label_exact_plural']    = esc_html__('%s%% discount for the "Sale price"', 'publishpress-checklists-pro');
        $this->lang['label_between']         = esc_html__('%s%% to %s%% discount for the "Sale price"', 'publishpress-checklists-pro');
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
        $product = $this->get_product($post->ID);

        $regular = $product->get_regular_price();
        $sale    = $product->get_sale_price();
        $status  = false;

        $discount = $this->mathHelper->calculateDiscount($regular, $sale);

        $min = $this->mathHelper->sanitizeFloat($option_value[0]);
        $max = $this->mathHelper->sanitizeFloat($option_value[1]);

        // Both same value = exact
        if ($min === $max) {
            $status = $discount === $min;
        }

        // Min not empty, max empty or < min = only min
        if ($min > 0 && $max < $min) {
            $status = $discount >= $min;
        }

        // Min not empty, max not empty and > min = both min and max
        if ($min > 0 && $max > $min) {
            $status = $discount >= $min && $discount <= $max;
        }

        // Min empty, max not empty and > min = only max
        if ($min === 0 && $max > $min) {
            $status = $discount <= $max;
        }

        return $status;
    }

    /**
     * Add data to the javascript script
     *
     * @param array $data
     *
     * @return array
     */
    public function localize_data($data)
    {
        $data['discount_min'] = $this->mathHelper->sanitizeFloat($this->get_option('min'));
        $data['discount_max'] = $this->mathHelper->sanitizeFloat($this->get_option('max'));

        return $data;
    }
}
