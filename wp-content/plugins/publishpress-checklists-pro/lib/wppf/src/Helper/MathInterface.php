<?php
/**
 * @package     WPPF
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace WPPF\Helper;


interface MathInterface
{
    /**
     * Sanitizes a float value. If it is a string with ',' as
     * decimal separator, we convert to '.', and to a float value.
     *
     * @param mixed $value
     *
     * @return float
     */
    public function sanitizeFloat($value);

    /**
     * Calculates the discount based on given prices
     *
     * @param float $regular
     * @param float $sale
     *
     * @return float
     */
    public function calculateDiscount($regular, $sale);
}
