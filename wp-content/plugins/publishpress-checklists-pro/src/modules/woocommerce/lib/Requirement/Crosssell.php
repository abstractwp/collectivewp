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
use WPPF\Plugin\ServicesAbstract;
use WPPF\WP\HooksHandlerInterface;

class Crosssell extends BaseFloatRange
{
    /**
     * The name of the requirement, in a slug format
     *
     * @var string
     */
    public $name = 'crosssell';

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
        $this->lang['label_settings']        = esc_html__(
            'Select some products for "Cross-sells"',
            'publishpress-checklists-pro'
        );
        $this->lang['label_settings_params'] = esc_html__('Min %s Max %s', 'publishpress-checklists-pro');
        $this->lang['label_min_singular']    = esc_html__(
            'Minimum of %s products for "Cross-sells"',
            'publishpress-checklists-pro'
        );
        $this->lang['label_min_plural']      = esc_html__(
            'Minimum of %s products for "Cross-sells"',
            'publishpress-checklists-pro'
        );
        $this->lang['label_max_singular']    = esc_html__(
            'Maximum of %s products for "Cross-sells"',
            'publishpress-checklists-pro'
        );
        $this->lang['label_max_plural']      = esc_html__(
            'Maximum of %s products for "Cross-sells"',
            'publishpress-checklists-pro'
        );
        $this->lang['label_exact_singular']  = esc_html__('%s products for "Cross-sells"', 'publishpress-checklists-pro');
        $this->lang['label_exact_plural']    = esc_html__('%s products for "Cross-sells"', 'publishpress-checklists-pro');
        $this->lang['label_between']         = esc_html__(
            '%s to %s products for "Cross-sells"',
            'publishpress-checklists-pro'
        );
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

        $ids = $product->get_cross_sell_ids();

        $count = (int)count($ids);

        $min = (int)$option_value[0];
        $max = (int)$option_value[1];

        // Both same value = exact
        if ($min === $max) {
            $status = $count === $min;
        }

        // Min not empty, max empty or < min = only min
        if ($min > 0 && $max < $min) {
            $status = $count >= $min;
        }

        // Min not empty, max not empty and > min = both min and max
        if ($min > 0 && $max > $min) {
            $status = $count >= $min && $count <= $max;
        }

        // Min empty, max not empty and > min = only max
        if ($min === 0 && $max > $min) {
            $status = $count <= $max;
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
        $data['crosssell_min'] = (int)$this->get_option('min');
        $data['crosssell_max'] = (int)$this->get_option('max');

        return $data;
    }
}
