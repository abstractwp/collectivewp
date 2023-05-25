<?php
namespace PublishPress\Permissions\Compat;

class WooCommerce
{
    function __construct() 
    {
        add_filter('woocommerce_product_is_visible', [$this, 'woo_visibility_fix'], 10, 2);
        add_filter('woocommerce_is_purchasable', [$this, 'woo_visibility_fix'], 10, 2);
    }

    function woo_visibility_fix($visible, $product)
    {
        if (!$visible && !empty($product->post) && ($product->post->post_status !== 'publish')) {
            $visible = current_user_can('read_post', $product->id);
        }

        return $visible;
    }
}
