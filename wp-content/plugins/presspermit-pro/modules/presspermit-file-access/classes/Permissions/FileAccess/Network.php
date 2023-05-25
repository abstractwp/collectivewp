<?php
namespace PublishPress\Permissions\FileAccess;

class Network {
    public static function msBlogsRewriting()
    {
        return is_multisite() && get_site_option('ms_files_rewriting');
    }

    public static function networkActivating() 
    {
        return isset($_SERVER['REQUEST_URI']) && strpos(esc_url_raw($_SERVER['REQUEST_URI']), 'network/plugins.php')
        && ( !presspermit_empty_REQUEST('activate') || presspermit_is_REQUEST('action', 'activate'));
    }
}
