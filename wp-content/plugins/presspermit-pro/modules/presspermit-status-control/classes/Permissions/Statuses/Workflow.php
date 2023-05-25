<?php
namespace PublishPress\Permissions\Statuses;

class Workflow {
    public static function orderStatuses($statuses = false, $args = [])
    {
        if (false === $statuses) {
            global $wp_post_statuses;
            $statuses = $wp_post_statuses;
        }

        $defaults = [
            'min_order' => 0, 
            'status_parent' => false, 
            'omit_status' => [], 
            'include_status' => [], 
            'require_order' => false
        ];
        
        $args = array_merge($defaults, (array)$args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        // convert integer keys to slugs
        foreach ($statuses as $status => $obj) {
            if (is_numeric($status)) {
                $statuses[$obj->name] = $obj;
                unset($statuses[$status]);
            }
        }

        $include_status = (array)$include_status;

        if (!empty($omit_status)) {
            $statuses = array_diff_key(
                $statuses, 
                array_fill_keys(array_diff((array)$omit_status, $include_status), true)
            );
        }

        if (!empty($min_order)) {
            foreach ($statuses as $status => $status_obj) {
                if (!in_array($status, $include_status, true) 
                && (empty($status_obj->order) || ($status_obj->order < $min_order))
                ) {
                    unset($statuses[$status]);
                }
            }
        }

        $moderation_order = [];

        $main_order = [];
        foreach ($statuses as $status => $status_obj) {
            if ($require_order && empty($status_obj->order)) {
                unset($statuses[$status]);
                continue;
            }

            if (!PWP::isBlockEditorActive()) {  // with Gutenberg, child statuses don't reload dynamically after saving post to parent status
	            if (false !== $status_parent) {
	                $_parent = (isset($status_obj->status_parent)) ? $status_obj->status_parent : '';
	
	                if (($_parent != $status_parent) && ($require_order || ($status != $status_parent))) {
	                    unset($statuses[$status]);
	                    continue;
	                }
	            }
            }

            if (empty($status_obj->status_parent)) {
                $display_order = (!empty($status_obj->order)) ? $status_obj->order * 10000 : 1000000;

                while (isset($main_order[$display_order])) {
                    $display_order = $display_order + 100;
                }
                $main_order[$display_order] = $status;
            }
        }

        foreach ($statuses as $status => $status_obj) {
            $k = array_search($status, $main_order);
            if (false === $k) {
                $k = array_search($status_obj->status_parent, $main_order);
                if (false === $k) {
                    $k = 1000000;
                } else {
                    $order = (!empty($status_obj->order)) ? $status_obj->order : 100;
                    $k = $k + 1 + $order;
                }
            }

            $moderation_order[$k][$status] = $status_obj;
        }

        ksort($moderation_order);

        $statuses = [];
        foreach (array_keys($moderation_order) as $_order_key) {
            foreach ($moderation_order[$_order_key] as $status => $status_obj)
                $statuses[$status] = $status_obj;
        }

        return $statuses;
    }

    public static function getNextStatusObject($post_id = 0, $args = [])
    {
        global $wp_post_statuses;

        $pp = presspermit();

        $defaults = ['moderation_statuses' => [], 'can_set_status' => [], 'force_main_channel' => false, 'post_type' => '', 'default_by_sequence' => null, 'skip_current_status_check' => false];

        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $post_type = sanitize_key($post_type);

        if (is_object($post_id)) {
            $post = $post_id;
            $post_id = $post->ID;
        } else {
            if (!$post_id) {
                $post_id = PWP::getPostID();
            }

            if ($post_id) {
                $post = get_post($post_id);
            }
        }

        if (empty($post)) {
            $post_status = 'draft';
            $post_type = PWP::findPostType();
        } else {
            $post_type = $post->post_type;
            $post_status = $post->post_status;
        }

        if (!$post_status_obj = get_post_status_object($post_status)) {
            $post_status_obj = get_post_status_object('draft');
        }

        $is_administrator = $pp->isContentAdministrator();
        if (!$type_obj = get_post_type_object($post_type)) {
            return $post_status_obj;
        }

        if (empty($moderation_statuses)) {
            $moderation_statuses = PWP::getPostStatuses(['moderation' => true], 'object');
        }

        if (empty($can_set_status)) {
            $can_set_status = PPS::getUserStatusPermissions('set_status', $type_obj->name, $moderation_statuses);
        }

        if ('auto-draft' == $post_status)
            $post_status = 'draft';

        if (!empty($post_status_obj->public) || !empty($post_status_obj->private) || ('future' == $post_status_obj->name)) {
            if (!$skip_current_status_check) {
                return $post_status_obj;
            }
        }

        if (is_null($default_by_sequence)) {
            $default_by_sequence = $pp->getOption('moderation_statuses_default_by_sequence');
        }

        if (current_user_can($type_obj->cap->publish_posts) 
        && (!$default_by_sequence || apply_filters('presspermit_editor_default_publish', false, $post))
        ) {
            if (!empty($post) && !empty($post->post_date_gmt) && time() < strtotime($post->post_date_gmt . ' +0000')) {
                return get_post_status_object('future');
            } else {
                return get_post_status_object('publish');
            }
        } else {
            if (empty($moderation_statuses)) {
                $moderation_statuses = PWP::getPostStatuses(
                    ['moderation' => true, 'internal' => false, 'post_type' => $type_obj->name]
                    , 'object'
                );
                
                unset($moderation_statuses['future']);
            }

            // Don't default to another moderation status of equal or lower order
            $status_order = (!empty($post_status_obj->order)) ? $post_status_obj->order : 0;
            $_args = ['min_order' => $status_order + 1, 'omit_status' => 'future', 'require_order' => true];

            if (!$force_main_channel) {
                if (!empty($post_status_obj->status_parent)) {
                    // If current status is a Workflow branch child, only offer other statuses in that branch
                    $_args['status_parent'] = $post_status_obj->status_parent;

                } elseif ($status_children = PPS::getStatusChildren($post_status_obj->name, $moderation_statuses)) {
                    // If current status is a Workflow branch parent, only offer other statuses in that branch
                    $_args['status_parent'] = $post_status_obj->name;
                    unset($_args['min_order']);
                    $moderation_statuses = $status_children;
                } else {
                    $_args['status_parent'] = '';  // don't default from a main channel into a branch status
                }
            }

            $_post = (!empty($post)) ? $post : get_post($post_id);

            $moderation_statuses = PPS::orderStatuses($moderation_statuses, $_args);

            $moderation_statuses = apply_filters(
                'presspermit_editpost_next_status_priority_order', 
                $moderation_statuses, 
                ['post' => $_post]
            );

            // If this user cannot set any further progression steps, return current post status
            if (!$moderation_statuses) {
                if ((!empty($post_status_obj->status_parent) || !empty($status_children)) && !$force_main_channel) {
                    $args['force_main_channel'] = true;
                    return self::getNextStatusObject($post_id, $args);
                }
            } else {
                if (!$default_by_sequence || (defined('PRESSPERMIT_CHILD_STATUSES_ALWAYS_SEQUENCED') && (!empty($status_children) || !empty($post_status_obj->status_parent)))) {

                    // Defaulting to highest order that can be set by the user...
                    $moderation_statuses = array_reverse($moderation_statuses);
                }

                foreach ($moderation_statuses as $_status_obj) {
                    if (!empty($can_set_status[$_status_obj->name]) && ($_status_obj->name != $post_status_obj->name)) {
                        $post_status_obj = $_status_obj;
                        break;
                    }
                }
            }

            // If logic somehow failed, default to draft
            if (empty($post_status_obj)) {
                if (defined('PP_LEGACY_PENDING_STATUS') || !empty($can_set_status['pending'])) {
                    $post_status_obj = get_post_status_object('pending');
                } else {
                    $post_status_obj = get_post_status_object('draft');
                }
            }

            $override_status = apply_filters(
                'presspermit_workflow_progression', 
                $post_status_obj->name, 
                $post_id, 
                compact('moderation_statuses')
            );

            if (($override_status != $post_status_obj->name) && $can_set_status[$override_status]) {
                $post_status_obj = get_post_status_object($override_status);
            }

            if (($post_status_obj->name == $post_status) && current_user_can($type_obj->cap->publish_posts)) {
                $post_status_obj = get_post_status_object('publish');
            }
        }

        if (empty($post_status_obj) || ('auto-draft' == $post_status_obj->name)) {
            return get_post_status_object('draft');
        }

        return $post_status_obj;
    }

    public static function getUserStatusPermissions($perm_name, $post_type, $check_statuses, $args = [])
    {
        global $wp_post_statuses;

        $defaults = ['reset' => false, 'force_refresh' => false];
        $args = array_merge($defaults, (array)$args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $user = presspermit()->getUser();

        $return = [];

        $check_statuses = (array)$check_statuses;

        $elem = reset($check_statuses);
        if (is_object($elem)) {
            $check_statuses = array_fill_keys(array_keys($check_statuses), true);
        } else {
            $check_statuses = array_fill_keys((array)$check_statuses, true);
        }

        $refresh_statuses = [];

        if (!isset($user->cfg[$perm_name])) {
            $user->cfg[$perm_name] = [];
        }

        if (!isset($user->cfg[$perm_name][$post_type]) || $reset) {
            $user->cfg[$perm_name][$post_type] = [];
        }

        if ($force_refresh) {
            $refresh_statuses = $check_statuses;

        } elseif (!$refresh_statuses = array_diff_key($check_statuses, $user->cfg[$perm_name][$post_type])) {
            // requested values already cached
            return $user->cfg[$perm_name][$post_type];
        }

        if (presspermit()->isContentAdministrator()) {
            // This function will probably not get called for Administrator, but return valid response if it is.
            $user->cfg[$perm_name][$post_type] = array_merge($user->cfg[$perm_name][$post_type], $check_statuses);
            return $user->cfg[$perm_name][$post_type];
        }

        switch ($perm_name) {
            case 'set_status':
                if (!$type_obj = get_post_type_object($post_type)) {
                    return [];
                }

                if (!isset($type_obj->cap->set_posts_status)) {
                    $type_obj->cap->set_posts_status = $type_obj->cap->publish_posts;
                }

                $attributes = PPS::attributes();

                $moderate_any = PPS::havePermission('moderate_any');

                foreach (array_keys($check_statuses) as $_status) {
                    if ($moderate_any && !empty($wp_post_statuses[$_status]) 
                    && !empty($wp_post_statuses[$_status]->moderation)
                    ) {
                        // The pp_moderate_any capability allows a non-Administrator to set any moderation status
                        $return[$_status] = true;
                        continue;
                    }

                    $check_caps = $attributes->getConditionCaps(
                        $type_obj->cap->set_posts_status, 
                        $post_type, 
                        'post_status', 
                        $_status
                    );
                    
                    $return[$_status] = !array_diff($check_caps, array_keys($user->allcaps));
                }

                // Append cache for the statuses that were checked
                $user->cfg['set_status'][$post_type] = array_merge($user->cfg['set_status'][$post_type], $return);

                break;
            default:
        } // end switch

        return $return;
    }
}
