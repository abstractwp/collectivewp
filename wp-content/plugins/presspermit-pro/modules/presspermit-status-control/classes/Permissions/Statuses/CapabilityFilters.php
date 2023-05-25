<?php
namespace PublishPress\Permissions\Statuses;

/**
 * Additional metacap mapping for PP-defined conditions
 *
 * @package PressPermit
 * @author Kevin Behrens
 * @copyright Copyright (c) PublishPress
 *
 */

class CapabilityFilters
{
    var $do_status_cap_map = false;
    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new CapabilityFilters();
        }

        return self::$instance;
    }

    private function __construct()
    {
        // register early so other filters have a chance to review status/condition caps we append
        add_filter('map_meta_cap', [$this, 'fltMapStatusCaps'], 2, 4);
    }

    function fltMapStatusCaps($caps, $meta_cap, $user_id, $wp_args, $args = [])
    {
        global $current_user;

        // PostFilters::generate_where_clause selectively enables this (rather than repeatedly adding/removing filter)
        if (empty($args['force']) && (!$this->do_status_cap_map || (($user_id == $current_user->ID) && presspermit()->isContentAdministrator()))) {
            return $caps;
        }

        $attributes = PPS::attributes();

        $meta_cap = str_replace('_page', '_post', $meta_cap);

        if (isset($args['post'])) {
            $post = $args['post'];
        } else {
            if (empty($wp_args[0]))
                return $caps;

            if (!$post = get_post($wp_args[0]))
                return $caps;
        }

        if (in_array($post->post_status, ['public', 'private']))
            return $caps;

        if (defined('PUBLISHPRESS_REVISIONS_VERSION') && in_array($post->post_status, ['draft', 'pending']) && function_exists('rvy_in_revision_workflow') && rvy_in_revision_workflow($post->ID)) {
            return $caps;
        }

        // @todo: collapse condition_metacap_map and condition_cap_map arrays to post_status only
        if (isset($attributes->attributes['post_status']->conditions[$post->post_status])) {
            $map_caps = (isset($attributes->condition_metacap_map[$post->post_type][$meta_cap])) 
            ? $attributes->condition_metacap_map[$post->post_type][$meta_cap]['post_status'] 
            : [];

            if ($custom_mapped_caps = array_intersect_key($attributes->condition_cap_map, array_fill_keys($caps, true))) {
                foreach(array_keys($custom_mapped_caps) as $_mapped_cap) {
                    foreach($custom_mapped_caps[$_mapped_cap]['post_status'] as $_status => $status_cap) {
                        if (!isset($map_caps[$_status])) {
                            $map_caps[$_status] = (array) $status_cap;
                        } else {
                            $map_caps[$_status] = array_merge((array) $map_caps[$_status], (array) $status_cap);
                        }
                    }
                }
            }

            if (isset($map_caps[$post->post_status])) {
                $caps = array_merge($caps, (array)$map_caps[$post->post_status]);

                // If mapping a status-specific edit_others_{$status}_posts requirement, don't also require edit_others_posts
                foreach(['edit_others_posts', 'delete_others_posts'] as $base_cap) {
                    if (!empty($attributes->condition_cap_map[$base_cap])) {
                        if ($type_obj = get_post_type_object($post->post_type)) {
                            if (!empty($type_obj->cap->$base_cap)) {
                                $caps = array_diff($caps, [$type_obj->cap->$base_cap]);
                            }
                        }
                    }
                }

                if (!empty($wp_args[0])) {
                    $post_status = false;

                    if (is_scalar($wp_args[0])) {
                        if ($_post = get_post($wp_args[0])) {
                            $post_status = $_post->post_status;
                            $post_type = $_post->post_type;
                        }
                    } else {
                        $wp_args[0] = (object)$wp_args[0];

                        if (!empty($wp_args[0]->post_status)) {
                            $post_status = $wp_args[0]->post_status;
                            $post_type = $wp_args[0]->post_type;
                        }
                    }

                    if ($post_status) {
                        $status_obj = get_post_status_object($post_status);

                        if ($status_obj->private) {
                            if ($type_obj = get_post_type_object($post_type)) {
                                if (0 === strpos($meta_cap, 'read_')) {
                                    $caps = array_diff($caps, [$type_obj->cap->read_private_posts]);

                                    if (!PPS_CUSTOM_PRIVACY_EDIT_CAPS) {
                                        $user = presspermit()->getUser();

                                        // Extend Custom Privacy Edit Caps exemption so basic Editor role for the post type 
                                        // also enables reading posts with a custom privacy status.
                                        if (($user_id == $user->ID) && !empty($user->allcaps[$type_obj->cap->edit_private_posts]) 
                                        && !empty($user->allcaps[$type_obj->cap->edit_others_posts])
                                        ) {
                                            $caps[] = $type_obj->cap->edit_private_posts;
                                            $caps = array_diff($caps, [$map_caps[$post->post_status]]);
                                        }
                                    }
                                } elseif (PPS_CUSTOM_PRIVACY_EDIT_CAPS) {
                                    if (0 === strpos($meta_cap, 'edit_'))
                                        $caps = array_diff($caps, [$type_obj->cap->edit_private_posts]);
                                    elseif (0 === strpos($meta_cap, 'delete_'))
                                        $caps = array_diff($caps, [$type_obj->cap->delete_private_posts]);
                                }
                            }
                        }
                    }
                }

                if (('draft' == $post->post_status) && in_array("read_draft_{$post_type}s", $caps, true)) {
                    if ($type_obj = get_post_type_object($post_type)) {
                        $caps = array_diff($caps, [$type_obj->cap->edit_others_posts]);
                    }
                }

                $caps = apply_filters('presspermit_map_status_caps', array_unique($caps), $meta_cap, $user_id, $post->ID);
            }
        }

        return $caps;
    }
}
