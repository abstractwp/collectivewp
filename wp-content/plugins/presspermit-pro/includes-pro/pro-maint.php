<?php
namespace PublishPress\Permissions;

class PressPermitMaint {
    public static function adminRedirectCheck() {
        if (!presspermit_empty_REQUEST('presspermit_refresh_updates') && check_admin_referer('presspermit_refresh_updates') && current_user_can('pp_manage_settings') && current_user_can('update_plugins') && presspermit_SERVER_var('HTTP_HOST')) {
            presspermit()->keyStatus(true);
            set_transient('presspermit-pro-refresh-update-info', true, 86400);

            $opt_val = get_option('presspermit_edd_key');
            if (is_array($opt_val) && !empty($opt_val['license_key'])) {
                $plugin_slug = basename(PRESSPERMIT_PRO_FILE, '.php'); // 'presspermit-pro';
                $plugin_relpath = basename(dirname(PRESSPERMIT_PRO_FILE)) . '/' . basename(PRESSPERMIT_PRO_FILE);
                $license_key = $opt_val['license_key'];
                $beta = false;

                delete_option(md5(serialize($plugin_slug . $license_key . $beta)));
                delete_option('edd_api_request_' . md5($plugin_slug . $license_key . $beta));
                delete_option(md5('edd_plugin_' . $plugin_relpath . '_' . $beta . '_version_info'));
            }

            delete_site_transient('update_plugins');
            delete_option('_site_transient_update_plugins');
            wp_update_plugins();

            $url = admin_url('update-core.php');
            wp_redirect(esc_url_raw($url));
            exit;
        }
    }

    public static function callHome($request_topic, $request_vars = [], $post_vars = false)
    {
        $request_vars = array_merge((array)$request_vars, ['PPServerRequest' => $request_topic]);

        $args = [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
                'User-Agent' => 'WordPress/' . get_bloginfo("version"),
                'Referer' => get_bloginfo("url")
            ],
        ];

        $timeout = in_array($request_topic, ['update-check', 'changelog'], true) ? 8 : 30;
        
        $body = (false !== $post_vars) ? $post_vars : array_merge($request_vars, ['url' => site_url()]);

        try {
	        $server_response = wp_remote_post(
	            'https://publishpress.com/',
	            [
	            'timeout'   => $timeout,
	            'sslverify' => true,
	            'body'      => $body,
	            ]
	        );
	
	        $const = 'PRESSPERMIT_DEBUG_' . strtoupper(str_replace('-', '_', $request_topic));
	        if (is_admin() && defined($const) && constant($const)) {
	            if (defined('PRESSPERMIT_DEBUG') && ('var_dump' !== constant($const))) {
	                pp_dump($server_response);
	                pp_backtrace_dump();
	            } else {
	                var_dump($server_response);
	                die('--- PP TEST ---');
	            }
	        }
	
	        // Is the response an error?
	        if (is_wp_error($server_response) || 200 !== wp_remote_retrieve_response_code($server_response)) {
                if (is_object($server_response) && method_exists($server_response, 'get_error_message')) {
	            	$message = $server_response->get_error_message();
                }
                
	            if (empty($message)) {  // @todo: replace this with a library string ?
                    //throw new \Exception('An error occurred.');
                    return '';
	            } else {
	                if (defined('PRESSPERMIT_DEBUG')) {
	                    error_log('Key activation connection error: ' . $message);
	                }
	
	                throw new \Exception($message);
	            }
	        }
	
	        $json_response = wp_remote_retrieve_body($server_response);
	
	        // Convert data response to an object.
	        $data = json_decode($json_response);
	
	        // Do we have empty data? Throw an error.
	        if (empty($data) || ! is_object($data)) {  // @todo: replace this with a library string ?
	            throw new \Exception('An error occurred.');
	        }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $json_response;
    }
}
