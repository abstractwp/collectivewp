<?php
/**
 * @package     WPPF
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace WPPF\Helper;


class Math implements MathInterface
{
    /**
     * @inheritDoc
     */
    public function calculateDiscount($regular, $sale)
    {
        $regular = $this->sanitizeFloat($regular);
        $sale    = $this->sanitizeFloat($sale);

        // Avoid division by 0
        if ($regular === 0.0) {
            return 0.0;
        }

        // If there is no sale price, the discount is 0
        if ($sale === 0.0) {
            return 0.0;
        }

        return (float)(($regular - $sale) / $regular * 100);
    }

    /**
     * @inheritDoc
     */
    public function sanitizeFloat($value)
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        return (float)$value;
    }
}
