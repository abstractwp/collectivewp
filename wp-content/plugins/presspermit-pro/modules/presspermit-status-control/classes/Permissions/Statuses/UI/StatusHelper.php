<?php
namespace PublishPress\Permissions\Statuses\UI;

class StatusHelper
{
    public static function getUrlProperties(&$url, &$referer, &$redirect)
    {
        $url = apply_filters( 'presspermit_permits_base_url', 'admin.php' );

        if (presspermit_empty_REQUEST() && presspermit_SERVER_var('REQUEST_URI')) {
            $referer = '<input type="hidden" name="wp_http_referer" value="' . esc_attr(stripslashes(esc_url_raw(presspermit_SERVER_var('REQUEST_URI')))) . '" />';
       
        } elseif ($wp_http_referer = presspermit_REQUEST_var('wp_http_referer')) {
            $redirect = esc_url_raw(remove_query_arg(['wp_http_referer', 'updated', 'delete_count'], stripslashes(esc_url_raw($wp_http_referer))));
            $referer = '<input type="hidden" name="wp_http_referer" value="' . esc_attr($redirect) . '" />';
        } else {
            $redirect = "$url?page=presspermit-statuses";
            $referer = '';
        }
    }
}
