<?php
namespace PublishPress\Permissions\Statuses;

/**
 * Triggers class
 *
 * Deals with content, user or site changes which may require a 
 * corresponding permissions data update or other action. 
 * 
 * @package PressPermit
 * @author Kevin Behrens <kevin@agapetry.net>
 * @copyright Copyright (c) 2021, PublishPress
 *
 */
class Triggers
{
    function __construct() {
        // This script normally executes on plugin load, 
        // but can be bypassed for front end URLs if defined('PP_NO_FRONTEND_ADMIN')
        //
        add_action('save_post', [$this, 'actSavePost'], 10, 2);
        add_action('delete_post', [$this, 'actDeletePost'], 10, 3);

        add_filter('pre_post_status', [$this, 'fltPostStatus'], 20);
    }

    function actSavePost($post_id, $post)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if ('revision' == $post->post_type) return;

        if (!empty(presspermit()->flags['ignore_save_post'])) {
            return;
        }

        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/PostSave.php');
        return PostSave::actSavePost($post_id, $post);
    }

    function actDeletePost($object_id)
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/ItemDelete.php');
        ItemDelete::actDeletePost($object_id);
    }

    function fltPostStatus($status)
    {
        global $pagenow;
        if (in_array($status, ['inherit', 'trash']) 
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ('async-upload.php' == $pagenow)) {
            return $status;
        }

        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/PostSave.php');
        $status = PostSave::fltPostStatus($status);
        return PostSave::flt_force_visibility($status);
    }
}
