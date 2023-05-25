<?php
namespace PublishPress\Permissions\Statuses\UI;

class PostEdit
{
    private static function isPostTypeEnabled($post_type = '') {
        global $post;

        $post_type = (!empty($post)) ? $post->post_type : get_post_field('post_type', PWP::getPostID());

        return in_array($post_type, presspermit()->getEnabledPostTypes(), true);
    }

    // Filter the statuses included in Gutenberg editor status dropdown. 
    //
    // Note: PressPermit customization of per-type status availability, ordering and branch relationships (Permissions > Post Statuses)
    // will be applied even if permissions filtering is disabled for the post type.
    public static function flt_publishpress_status_list($status_terms, $post)
    {
        if (!$post || !is_object($post) || empty($post->ID)) {
            if ($post_id = PWP::getPostID()) {
                $post = get_post($post_id);
            }
        }

        if (!$post) {
            if ($rest_post_type = defined('REST_REQUEST') && apply_filters('presspermit_rest_post_type', '')) {
                $post_type = $rest_post_type;
                $post_status = 'draft';
            } else {
                return $status_terms;
            }
        } else {
            $post_type = $post->post_type;
            $post_status = $post->post_status;
        }

        if ('auto-draft' == $post_status)
            $post_status = 'draft';

        if (!$post_status_obj = get_post_status_object($post_status)) {
            $post_status_obj = get_post_status_object('draft');
        }

        $status_slug = (!empty($post_status_obj->slug)) ? $post_status_obj->slug : $post_status_obj->name;

        $all_moderation_statuses = PWP::getPostStatuses(['moderation' => true, 'internal' => false], 'object');

        $moderation_statuses = PWP::getPostStatuses(['moderation' => true, 'internal' => false, 'post_type' => $post_type], 'object');
        unset($moderation_statuses['future']);

        // Only filter moderation statuses
        $other_statuses = [];

        if (empty($moderation_statuses[$post_status])) {
            // for Gutenberg, always include original post status in dropdown
            $other_statuses[$post_status] = true;
        }

        foreach ($status_terms as $key => $status_term) {
            if (!empty($status_term->slug)) {
	            if (!isset($all_moderation_statuses[$status_term->slug]) && ('draft' != $status_term->slug) && !empty($status_term->slug)) {
	                $other_statuses[$status_term->slug] = true;
	            }
	        }
        }

        // If PressPermit permissions filtering is not enabled for this post type, don't impose access limits.
        // Note, though that status ordering and workflow branch relationships are still applied
        if (self::isPostTypeEnabled()) {
            if (!presspermit()->isContentAdministrator()) {
                $moderation_statuses = PPS::filterAvailablePostStatuses($moderation_statuses, $post_type, $post_status);
            }

            $moderation_statuses = apply_filters('presspermit_available_moderation_statuses', $moderation_statuses, $moderation_statuses, $post);
        }

        // Don't exclude the current status, regardless of other arguments
        $_args = ['include_status' => $status_slug];

        if (!empty($post_status_obj->status_parent)) {
            if (defined('PRESSPERMIT_RESTRICT_WORKFLOW_BRANCH_SELECTION')) { // legacy behavior < v2.8.8
                // If current status is a workflow branch child, only offer other statuses in that branch
                $_args['status_parent'] = $post_status_obj->status_parent;
            } else {
                // If current status is a workflow branch child, also offer other statuses in that branch
                if ($status_children = PPS::getStatusChildren($post_status_obj->status_parent, $moderation_statuses)) {
                    $moderation_statuses = array_merge($moderation_statuses, $status_children);
                }
            }
        } elseif ($status_children = PPS::getStatusChildren($status_slug, $moderation_statuses)) {
            if (defined('PRESSPERMIT_RESTRICT_WORKFLOW_BRANCH_SELECTION')) { // legacy behavior < v2.8.8
                // If current status is a workflow branch parent, only offer other statuses in that branch
                $moderation_statuses = array_merge([$status_slug => $post_status_obj], $status_children);
            } else {
                // If current status is a workflow branch parent, also offer other statuses in that branch
                $moderation_statuses = array_merge($moderation_statuses, $status_children);
            }
        } else {
            // If current status is in main workflow with no branch children, only display other main workflow statuses 
            $_args['status_parent'] = '';
        }

        $moderation_statuses = PPS::orderStatuses($moderation_statuses, $_args);

        $type_obj = get_post_type_object($post_type);
        $can_publish = ($type_obj) ? current_user_can($type_obj->cap->publish_posts) : false;

        $return = [];

        foreach (array_merge(['draft' => true], $moderation_statuses, $other_statuses) as $status => $status_obj) {
            $found = false;

            foreach ($status_terms as $status_term) {
                if (!empty($status_term->slug) && ($status_term->slug == $status)) {
                    if ('pending' == $status) {
                        // Alternate item to allow use of "Save as Pending" button
                        //
                        // This will allow different behavior from the Submit button, 
                        // which may default to next/highest available workflow status.
                        $return[]= (object)['slug' => '_pending', 'name' => esc_html__('Pending')];
                    }

                    $return[]= $status_term;

                    $found = true;
                    break;
                }
            }

            if (!$found && is_object($status_obj)) {
                // don't insert statues which PublilshPress excluded if status has default capabilities
                if (!empty($status_obj->capability_status)) {
                    $return[]= (object)['slug' => $status_obj->name, 'name' => $status_obj->label];
                }
            }
        }

        return $return;
    }
}
