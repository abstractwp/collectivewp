<?php
/**
 * @package     PublishPress\\WooCommerce
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\WooCommerce\Requirement;

use PublishPress\Checklists\Core\Requirement\Base_simple;


class Base extends Base_simple
{
    /**
     * Returns the value of the given option. The option name should
     * be in the short form, without the name of the requirement as
     * the prefix.
     *
     * @param string $option_name
     *
     * @return mixed
     */
    public function get_option($option_name)
    {
        $options     = $this->module->options;
        $option_name = sprintf('%s_%s', $this->name, $option_name);

        if (isset($options->{$option_name}) && isset($options->{$option_name}[$this->post_type])) {
            return $options->{$option_name}[$this->post_type];
        }

        return null;
    }

    /**
     * Returns the product by the id
     *
     * @param int $id
     *
     * @return WC_Product
     */
    protected function get_product($id)
    {
        return wc_get_product($id);
    }
}
