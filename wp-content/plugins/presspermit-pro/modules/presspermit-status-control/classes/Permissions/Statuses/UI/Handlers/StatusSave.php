<?php
namespace PublishPress\Permissions\Statuses\UI\Handlers;

class StatusSave
{
    public static function save($status, $new = false)
    {
        $pp = presspermit();

        if ($new) {
            check_admin_referer('pp-create-condition', '_wpnonce_pp-create-condition');
        } else {
            check_admin_referer('pp-update-condition_' . $status);
        }

        $arr_return = ['retval' => false, 'redirect' => ''];

        if (strlen($status) > 20)
            $status = substr($status, 0, 20);

        $status_obj = get_post_status_object($status);

        if ($new && $status_obj || in_array($status, ['public', 'password'])) {
            $errors = new \WP_Error();
            $errors->add('status_name', __('<strong>ERROR</strong>: That status name is already registered. Please choose another one.', 'presspermit-pro'));
            $arr_return['retval'] = $errors;
            return $arr_return;
        }

        if ($status_obj || $new) {
            if (presspermit_empty_REQUEST('status_label') && !in_array($status, ['pending', 'future', 'draft']) && empty($status_obj->pp_custom)) {
                $errors = new \WP_Error();
                $errors->add('status_label', __('<strong>ERROR</strong>: Please enter a label for the status.', 'presspermit-pro'));
                $arr_return['retval'] = $errors;
            } else {
                $custom_conditions = (array)get_option("presspermit_custom_conditions_post_status");

                if ($status_label = presspermit_REQUEST_var('status_label')) {
                    $custom_conditions[$status]['label'] = sanitize_text_field($status_label);
                }

                $custom_conditions[$status]['save_as_label'] = sanitize_text_field(presspermit_REQUEST_var('status_save_as_label'));
                $custom_conditions[$status]['publish_label'] = sanitize_text_field(presspermit_REQUEST_var('status_publish_label'));

                if ($new) {
                    if ($attrib_type = presspermit_REQUEST_key('attrib_type')) {
                        $custom_conditions[$status][$attrib_type] = true;
                    }
                } else {
                    $attrib_type = (!empty($status_obj->private)) ? 'private' : 'moderation';
                }

                if (!empty($status_obj->pp_custom)) {
                    $custom_conditions[$status]['publishpress'] = true;
                }

                $pp->updateOption("custom_conditions_post_status", $custom_conditions);

                if (presspermit_SERVER_var('REQUEST_URI')) {
                    $page = ('private' == $attrib_type) ? 'presspermit-visibility-statuses' : 'presspermit-statuses';

                    $arr_return['redirect'] = str_replace('presspermit-status-new', "{$page}&attrib_type=$attrib_type", esc_url_raw(presspermit_SERVER_var('REQUEST_URI')));
                    $arr_return['redirect'] = str_replace('presspermit-status-edit', "{$page}&attrib_type=$attrib_type", $arr_return['redirect']);
                }
            }

            // === store status post types ===
            if (!$status_post_types = $pp->getOption('status_post_types'))
                $status_post_types = [];

            if (!presspermit_empty_REQUEST('pp_status_all_types')) {
                $status_post_types[$status] = [];

            } elseif (presspermit_is_REQUEST('pp_status_post_types')) {
                if (!isset($status_post_types[$status]))
                    $status_post_types[$status] = [];

                if ($add_types = array_filter(array_map('intval', presspermit_REQUEST_var('pp_status_post_types')))) {
                    $status_post_types[$status] = array_unique(array_merge($status_post_types[$status], array_map('sanitize_key', array_keys($add_types))));
                }

                if ($remove_types = array_diff(array_map('intval', presspermit_REQUEST_var('pp_status_post_types')), ['1', true, 1])) {
                    $status_post_types[$status] = array_diff($status_post_types[$status], array_keys($remove_types));
                }
            }

            $pp->updateOption('status_post_types', $status_post_types);

            // === store capability status ===
            if ('moderation' == $attrib_type) {

                if (presspermit_is_REQUEST('status_capability_status')) {
                    if (!$capability_status = $pp->getOption('status_capability_status'))
                        $capability_status = [];

                    if (!$status_capability_status = presspermit_REQUEST_key('status_capability_status')) {
                        $pp->deleteOption("custom_{$status}_caps");
                    }

                    if ($status == $status_capability_status) {
                        unset($capability_status[$status]);
                    } else {
                        $capability_status[$status] = $status_capability_status;
                    }

                    $pp->updateOption("status_capability_status", $capability_status);
                    $pp->updateOption("custom_{$status}_caps", true);
                }

                // === store status order ===
                if (!$status_order = $pp->getOption('status_order'))
                    $status_order = [];

                if (presspermit_is_REQUEST('status_order') && (presspermit_REQUEST_int('status_order') === '')) {
                    unset($status_order[$status]);
                } else {
                    $status_order[$status] = presspermit_REQUEST_int('status_order');
                }
                $pp->updateOption("status_order", $status_order);

                // === store status parent ===
                if (!$status_parent = $pp->getOption('status_parent'))
                    $status_parent = [];

                if (!presspermit_empty_REQUEST('status_parent') || !empty($status_parent[$status])) {  // don't store value if no entry and not already stored
                    if ($_status_parent = presspermit_REQUEST_key('status_parent')) {
                        $status_parent[$status] = $_status_parent;
                        $status_obj = get_post_status_object($status_parent[$status]);
                    } else {
                        unset($status_parent[$status]);
                    }

                    // don't allow status grandchildren
                    if (presspermit_empty_REQUEST('status_parent') 
                    || ( !empty($status_obj) && empty($status_obj->status_parent) && ($status_parent[$status] != $status))
                    ) {
                        // If this status is being set to a parent but already has children, move its children also
                        if (!empty($status_parent[$status])) {
                            global $wp_post_statuses;
                            foreach ($wp_post_statuses as $_status => $_status_obj) {
                                if (!empty($_status_obj->status_parent) && ($_status_obj->status_parent == $status)) {
                                    $wp_post_statuses[$_status]->status_parent = $status_parent[$status];
                                }
                            }
                        }
                        
                        $pp->updateOption("status_parent", $status_parent);
                    }
                }
            }
        } else {
            $errors = new \WP_Error();
            $errors->add('condition_name', __('<strong>ERROR</strong>: The specified status does not exist.', 'presspermit-pro'));
            $arr_return['retval'] = $errors;
        }

        return $arr_return;
    }
}
