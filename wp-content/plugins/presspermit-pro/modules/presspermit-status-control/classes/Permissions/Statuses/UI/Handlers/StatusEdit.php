<?php
namespace PublishPress\Permissions\Statuses\UI\Handlers;

class StatusEdit
{
    public static function handleRequest()
    {
        // This script executes on plugin load if is_admin(), for POST requests with our presspermit-status-edit or presspermit-status-new admin.php URL
        //

        $action = presspermit_REQUEST_key('action');

        $attribute = 'post_status';
        $attrib_type = presspermit_REQUEST_key('attrib_type');

        PPS::attributes();

        if (!current_user_can("pp_define_{$attribute}") && (!$attrib_type || !current_user_can("pp_define_{$attrib_type}"))) {
            wp_die(esc_html__('You are not permitted to do that.', 'presspermit-pro'));
        }

        switch ($action) {
            case 'update':
                if ($status = presspermit_REQUEST_key('status')) {
                    check_admin_referer('pp-update-condition_' . $status);

                    require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/UI/Handlers/StatusSave.php');
                    $return_array = StatusSave::save($status);
                    $retval = $return_array['retval'];
                    $redirect = $return_array['redirect'];

                    do_action('presspermit_trigger_cache_flush');
                }
                break;
            case 'createcondition':
                if ($status_name = presspermit_REQUEST_key('status_name')) {
                    check_admin_referer('pp-create-condition', '_wpnonce_pp-create-condition');

                    $status = sanitize_key(str_replace(' ', '_', $status_name));
                    require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/UI/Handlers/StatusSave.php');
                    $return_array = StatusSave::save($status, true);
                    $retval = $return_array['retval'];
                    $redirect = $return_array['redirect'];

                    do_action('presspermit_trigger_cache_flush');
                }
                break;
        } // end switch

        if (!empty($retval) && is_wp_error($retval)) {
            presspermit()->admin()->errors = $retval;
        } elseif ($redirect) {
            wp_redirect(esc_url_raw(add_query_arg('update', 1, $redirect)));
            exit;
        }
    }
}
