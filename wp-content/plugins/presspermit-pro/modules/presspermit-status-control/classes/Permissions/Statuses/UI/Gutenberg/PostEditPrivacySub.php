<?php

namespace PublishPress\Permissions\Statuses\UI\Gutenberg;

// This class is only used with PublishPress inactive. It supplies the Post Status dropdown (for custom types).
class PostEditPrivacySub
{
    function __construct() 
    {
        add_action('admin_enqueue_scripts', [$this, 'actEnqueueScripts'], 11);
        add_action('enqueue_block_editor_assets', [$this, 'actEnqueueBlockEditorAssets'], 11);
    }

    /**
     * Enqueue Javascript resources that we need in the admin:
     * - Primary use of Javascript is to manipulate the post status dropdown on Edit Post and Manage Posts
     * - jQuery Sortable plugin is used for drag and dropping custom statuses
     * - We have other custom code for JS niceties
     */
    public function actEnqueueScripts()
    {
        wp_enqueue_style(
            'publishpress-custom_privacy-sub-block',
            PRESSPERMIT_STATUSES_URLPATH . '/common/lib/custom-privacy-sub-block-editor.css', 
            false,
            PRESSPERMIT_STATUSES_VERSION, 
            'all'
        );
    }

    /**
     * Enqueue Gutenberg assets.
     */
    public function actEnqueueBlockEditorAssets()
    {
        $statuses = $this->mergeCoreStatuses($this->getCustomPrivacyStatuses());

        $post_id = PWP::getPostID();

        $attributes = PPS::attributes();

        wp_enqueue_script(
            'presspermit-custom-privacy-sub-block',
            PRESSPERMIT_STATUSES_URLPATH . '/common/lib/custom-privacy-sub-block.min.js',
            ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-hooks'],
            PRESSPERMIT_STATUSES_VERSION,
            true
        );

        //$default_privacy = presspermit()->getTypeOption('default_privacy', PWP::findPostType());

        if ($type_obj = get_post_type_object(PWP::findPostType())) {
            $caption = sprintf(__('Sub-%s', 'presspermit-pro'), esc_html($type_obj->labels->name));
        } else {
            $caption = esc_html__('Subpage Privacy', 'presspermit-pro');
        }

        wp_localize_script(
            'presspermit-custom-privacy-sub-block',
            'PPCustomPrivacySub',
            ['statuses' => $statuses, 'caption' => $caption] //, 'defaultPrivacy' => $default_privacy]
        );
    }

    private function mergeCoreStatuses($statuses) 
    {
        return array_merge(
            [(object)['slug' => '', 'name' => esc_html__('(manual)', 'presspermit-pro')]],
            [(object)['slug' => 'publish', 'name' => esc_html__('Public')]],
            [(object)['slug' => 'private', 'name' => esc_html__('Private')]],
            $statuses
        );
    }

    /**
     * Get all post statuses as an ordered array
     *
     * @param array|string $statuses
     * @param array        $args
     * @param bool         $only_basic_info
     *
     * @return array $statuses All of the statuses
     */
    private function getCustomPrivacyStatuses($args = [], $only_basic_info = false)
    {
        global $post;
        $post_type = PWP::findPostType();
        if ( ! $type_obj = get_post_type_object($post_type) ) {
            return [];
        }

        $attributes = PPS::attributes();
        $is_administrator = presspermit()->isContentAdministrator();

        $statuses = PWP::getPostStatuses(['private' => true, '_builtin' => false, 'post_type' => $post_type], 'object');

        foreach($statuses as $_status => $status_obj) {
            if (!$is_administrator && ($_status != $post->post_status)) {
                if (empty($type_obj->cap->set_posts_status)) {
                    $set_status_cap = $type_obj->cap->publish_posts;
                } else {
                    $_caps = $attributes->getConditionCaps(
                        $type_obj->cap->set_posts_status, 
                        $post->post_type, 
                        'post_status', 
                        $_status
                    );

                    if (!$set_status_cap = reset($_caps)) {
                        $set_status_cap = $type_obj->cap->set_posts_status;
                    }
                }

                if (!current_user_can($set_status_cap)) {
                    unset($statuses[$_status]);
                }
            }
        }

        // compat with js usage of term properties
        foreach($statuses as $key => $status_obj) {
            if (!isset($status_obj->slug)) {
                $statuses[$key]->slug = $status_obj->name;
                $statuses[$key]->name = $status_obj->label;
                $statuses[$key]->description = '-';
                $statuses[$key]->color = '';
                $statuses[$key]->icon = '';
            }
        }

        $statuses = apply_filters('pp_custom_privacy_sub_list', array_values($statuses), $post);

        if (!$statuses) {
            return [];
        }

        // compat with js usage of term properties
        foreach($statuses as $key => $status_obj) {
            if (!isset($status_obj->slug)) {
                $statuses[$key]->slug = $status_obj->name;
                $statuses[$key]->name = $status_obj->label;
                $statuses[$key]->description = '-';
                $statuses[$key]->color = '';
                $statuses[$key]->icon = '';
            }
        }

        return $statuses;
    }
}
