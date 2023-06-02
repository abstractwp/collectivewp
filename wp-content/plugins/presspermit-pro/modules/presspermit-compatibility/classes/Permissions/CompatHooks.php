<?php
namespace PublishPress\Permissions;

class CompatHooks
{
    function __construct() 
    {
        require_once(PRESSPERMIT_COMPAT_ABSPATH . '/db-config.php');
        
        /*
        if (class_exists('ACF')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/ACF.php');
            new Compat\ACF();
        }
        */

        if (function_exists('bbp_get_version')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/BBPress.php');
            new Compat\BBPress();
        }

        if (class_exists('BuddyPress', false)) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/BuddyPress.php');
            new Compat\BuddyPress();
        }

        if (defined('COAUTHORS_PLUS_VERSION')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/CoAuthors.php');
            new Compat\CoAuthors();
        }

        if (defined('EVENTS_CALENDAR_PRO_FILE') || class_exists('Tribe__Events__Pro__Main')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/TheEventsCalendar.php');
            new Compat\TheEventsCalendar();
        }

        if (class_exists('WooCommerce')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/WooCommerce.php');
            new Compat\WooCommerce();
        }

        if (defined('ICL_SITEPRESS_VERSION')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/WPML.php');
            new Compat\WPML();
        }

        if (defined('WPSEO_VERSION')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/YoastSEO.php');
            new Compat\YoastSEO();
        }

        if (defined('FL_BUILDER_VERSION')) {
            add_filter('presspermit_get_posts_operation', function($operation, $args) {
                if ((!defined('REST_REQUEST') || ! REST_REQUEST) && PWP::isFront() && (presspermit_is_REQUEST('fl_builder') || !presspermit_empty_REQUEST('page_id'))) {
                    return 'edit';
                }

                return $operation;
            }, 50, 2);
        }

        if (defined('ELEMENTOR_VERSION')) {
            add_filter('user_has_cap', [$this, 'fltElementorEditPostsCap'], 10, 3);
        }

        add_filter('presspermit_default_options', [$this, 'default_options']);

        if (is_multisite()) {
            $this->net_options();
        
            add_action('presspermit_pre_init', [$this, 'net_options']);
            add_action('presspermit_refresh_options', [$this, 'net_options']);
            add_filter('presspermit_netwide_options', [$this, 'netwide_options']);

            if (defined('PP_MULTISITE_ALLOW_UNFILTERED_HTML') && (!defined('DISALLOW_UNFILTERED_HTML') || !constant('DISALLOW_UNFILTERED_HTML'))) {
                add_filter('map_meta_cap', [$this, 'networkAllowUnfilteredHtml'], 10, 4);
            }
        }

        add_filter('presspermit_operations', [$this, 'operations']);

        add_filter('presspermit_meta_caps', [$this, 'flt_meta_caps']);
        add_filter('presspermit_exception_post_type', [$this, 'exception_post_type'], 10, 3);
        add_filter('presspermit_exception_clause', [$this, 'exception_clause'], 10, 4);
        add_filter('presspermit_additions_clause', [$this, 'additions_clause'], 20, 4);
        add_filter('presspermit_unrevisable_types', [$this, 'unrevisable_types']);
        add_filter('presspermit_has_post_additions', [$this, 'has_post_additions'], 10, 5);

        add_filter('presspermit_disabled_pattern_role_post_types', [$this, 'flt_disabled_pattern_role_post_types']);  // @todo: is this applied?
        add_filter('presspermit_default_direct_roles', [$this, 'flt_default_direct_roles']);

        add_filter('get_terms_args', [$this, 'fltGetTermsArgs'], 50, 2);

        add_filter('presspermit_unfiltered', [$this, 'fltPressPermitUnfiltered'], 10, 2);
        
        add_action('presspermit_deleted_group', [$this, 'deleted_group'], 10, 2);

        if (is_multisite()) {
            if (did_action('plugins_loaded'))
                $this->load_options();
            else
                add_action('plugins_loaded', [$this, 'load_options'], 8);  // register group type before BP Groups for UI order
        }

        // workaround for unexplained issue with Blackfyre theme's get_edit_user_link() call 
        if (defined('PP_UNFILTERED_EDIT_USERS_CAP') && PP_UNFILTERED_EDIT_USERS_CAP) {
            add_filter('user_has_cap', [$this, 'unfilter_edit_user'], 999, 3);
        }

        add_action('init', [$this, 'loadInitFilters']);
    }

    public function loadInitFilters() {
        if (PWP::isFront()) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/PostFiltersFront.php');
            new Compat\PostFiltersFront();
        }
    }

    function default_options($def)
    {
        $new = [
            'topics_teaser' => 1,
            'tease_topic_replace_content' => esc_html__("[This topic requires additional permissions or membership.]", 'presspermit-pro'),
            'tease_topic_replace_other_content' => esc_html__("[...]", 'presspermit-pro'),
            'tease_topic_replace_content_anon' => esc_html__("[This topic requires site login.]", 'presspermit-pro'),
            'tease_topic_replace_other_content_anon' => esc_html__("[login required]", 'presspermit-pro'),
            'tease_reply_replace_content' => esc_html__("[This topic requires additional permissions or membership.]", 'presspermit-pro'),
            'tease_reply_replace_other_content' => esc_html__("[...]", 'presspermit-pro'),
            'tease_reply_replace_content_anon' => esc_html__("[This reply requires site login.]", 'presspermit-pro'),
            'tease_reply_replace_other_content_anon' => esc_html__("[login required]", 'presspermit-pro'),
            'forum_teaser_hide_author_link' => 1,
        ];

        if (is_multisite()) {
            $new['netwide_groups'] = 0;
        }

        return array_merge($def, $new);
    }

    function netwide_options($def)
    {
        $new = array_keys($this->default_options([]));
        return array_merge($def, $new);
    }

    function net_options()
    {
        global $wpdb;

        $pp = presspermit();

        if (empty($pp->net_options)) {
            $pp->net_options = [];
        }

        // TODO: site_id=0 ?
        foreach ($wpdb->get_results(
            "SELECT meta_key, meta_value FROM $wpdb->sitemeta WHERE meta_key LIKE 'presspermit_%'"
        ) as $row) {
            $pp->net_options[$row->meta_key] = $row->meta_value;
        }

        $pp->net_options = apply_filters('presspermit_net_options', $pp->net_options);
    }

    function load_options()
    {
        if (get_site_option('presspermit_netwide_groups')) {
            require_once(PRESSPERMIT_COMPAT_CLASSPATH . '/NetwideGroups.php');
            new Compat\NetwideGroups();
        }
    }

    function fltPressPermitUnfiltered($unfiltered, $args) {
        if (!empty($args['query_obj']) && !empty($args['query_obj']->tribe_is_event_venue)) {
            $unfiltered = true;
        }

        return $unfiltered;
    }

    // Work around Elementor requiring edit_posts capability regardless of what post type is being edited
    function fltElementorEditPostsCap($wp_sitecaps, $orig_reqd_caps, $args = [])
    {
        global $current_user;

        if ($args[1] != $current_user->ID) {
            return $wp_sitecaps;
        }

        if (('elementor' == presspermit_REQUEST_var('action'))) {
            if ($post_id = presspermit_REQUEST_var('post')) {
                if ('elementor_library' == get_post_field('post_type', $post_id)) {
                    return $wp_sitecaps;
                }
                
                if ('edit_posts' == reset($orig_reqd_caps)) {
                    if ($post_status = get_post_field('post_status', $post_id)) {
                        if ($status_obj = get_post_status_object($post_status)) {
                            $disable_filter = empty($status_obj->public) && empty($status_obj->private);
                        }
                    }
                    
                    if (!empty($disable_filter)) {
                        remove_filter('user_has_cap', [$this, 'fltElementorEditPostsCap'], 10, 3);
                    }

                    if (current_user_can('edit_post', $post_id)) {
                        $wp_sitecaps['edit_posts'] = true;

                        // This request occurs first. Set a transient to validate edit_posts grant for subsequent globals request
                        set_transient("editing_post-{$current_user->ID}", true, 30);
                    }

                    if (!empty($disable_filter)) {
                        add_filter('user_has_cap', [$this, 'fltElementorEditPostsCap'], 10, 3);
                    }
                }
            }
        } elseif ($is_elementor_globals = defined('REST_REQUEST') && REST_REQUEST 
        && strpos($_SERVER['REQUEST_URI'], 'elementor/') && strpos($_SERVER['REQUEST_URI'], '/globals')) {
            if (get_transient("editing_post-{$current_user->ID}")) {
                $wp_sitecaps['edit_posts'] = true;
            }
        }

        return $wp_sitecaps;
    }

    function flt_meta_caps($caps)
    {
        if (defined('PP_BBP_DELETE_VIA_MODERATION_EXCEPTION')) {
            return array_merge(
                $caps, 
                [
                    'read_forum' => 'read', 
                    'read_topic' => 'read', 
                    'read_reply' => 'read', 
                    'edit_forum' => 'edit', 
                    'edit_topic' => 'edit', 
                    'edit_reply' => 'edit', 
                    'delete_topic' => 'edit', 
                    'delete_reply' => 'edit'
                    ]
                );
        } else {
            return array_merge(
                $caps, 
                [
                    'read_forum' => 'read', 
                    'read_topic' => 'read', 
                    'read_reply' => 'read', 
                    'edit_forum' => 'edit', 
                    'edit_topic' => 'edit', 
                    'edit_reply' => 'edit', 
                    'delete_topic' => 'delete', 
                    'delete_reply' => 'delete'
                ]
            );
        }
    }

    public function networkAllowUnfilteredHtml($reqd_caps, $orig_cap, $user_id, $args)
    {
        if ('unfiltered_html' == $orig_cap && !in_array('unfiltered_html', $reqd_caps)) {
            $reqd_caps = array_diff($reqd_caps, ['do_not_allow']);
            $reqd_caps []= 'unfiltered_html';
        }

        return $reqd_caps;
    }

    function exception_post_type($post_type, $required_operation, $args)
    {
        if (in_array($post_type, ['topic', 'reply'], true))
            return 'forum';

        return $post_type;
    }

    function exception_clause($exc_clause, $operation, $post_type, $args)
    {
        // args array keys: ids, logic, src_table, via_item_source
        global $wpdb;

        if ((isset($args['via_item_source']) && ('post' == $args['via_item_source'])) 
        || (isset($args['src_table']) && ($args['src_table'] == $wpdb->posts))
        ) {
            switch ($post_type) {
                case 'topic' :
                    $exc_clause = "{$args['src_table']}.post_parent {$args['logic']} ('" . implode("','", $args['ids']) . "')";
                    break;

                case 'reply' :
                    $exc_clause = "{$args['src_table']}.post_parent IN ( SELECT ID FROM $wpdb->posts WHERE {$args['src_table']}.post_parent {$args['logic']} ('" . implode("','", $args['ids']) . "') )";
                    break;
            }
        }

        return $exc_clause;
    }

    function additions_clause($additions_clause, $operation, $post_type, $args)
    {
        // args array keys: _status, in_clause, src_table, via_item_source

        switch ($post_type) {
            case 'topic' :
                $additions_clause = "{$args['src_table']}.post_parent {$args['in_clause']}";
                break;

            case 'reply' :
                global $wpdb;
                $additions_clause = "{$args['src_table']}.post_parent IN ( SELECT ID FROM $wpdb->posts WHERE post_parent {$args['in_clause']} )";
                break;
        }

        return $additions_clause;
    }

    function has_post_additions($has_additions, $additional_ids, $post_type, $post_id, $args)
    {
        switch ($post_type) {
            case 'topic' :
                $forum_id = $post_id ? get_post_field('post_parent', $post_id) : 0;
                return (!$forum_id || in_array($forum_id, $additional_ids));
                break;

            case 'reply' :
                global $wpdb;
                $forum_id = $post_id ? get_post_field('post_parent', get_post_field('post_parent', $post_id)) : 0;
                return (!$forum_id || in_array($forum_id, $additional_ids));
                break;
        }

        return $has_additions;
    }

    function unrevisable_types($unrevisable_types)
    {
        return array_merge($unrevisable_types, ['forum']);
    }

    function flt_disabled_pattern_role_post_types($types)
    {
        if (class_exists('bbPress', false)) {
            $types = array_merge($types, ['forum', 'topic', 'reply']);
        }
        return $types;
    }

    function flt_default_direct_roles($roles)
    {
        if (class_exists('bbPress', false)) {
            $roles = ['bbp_participant', 'bbp_spectator', 'bbp_moderator'];
        }
        return $roles;
    }

    function operations($operations)
    {
        return (defined('PRESSPERMIT_COLLAB_VERSION')) 
        ? array_merge($operations, ['publish_topics', 'publish_replies']) 
        : $operations;
    }

    function fltGetTermsArgs($args, $taxonomies)
    {
        if (empty($pp_terms_filter) || apply_filters('presspermit_terms_skip_filtering', $taxonomies, $args)) {
            return $args;
        }

        if (!presspermit()->isContentAdministrator() && empty($args['required_operation'])) {
            global $plugin_page;

            // support Quick Post Widget plugin
            if (!empty($args['name']) && ('quick_post_cat' == $args['name'])) {
                $args['required_operation'] = 'edit';
                $args['post_type'] = 'post';

                // support Subscribe2 plugin
            } elseif (is_admin() && !empty($plugin_page) && ('s2' == $plugin_page)) {
                $args['required_operation'] = 'read';
            }
        }

        return $args;
    }

    function deleted_group($group_id, $agent_type)
    {
        if ('pp_net_group' == $agent_type) {
            global $wpdb;
            $pp = presspermit();

            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs ORDER BY blog_id");

            foreach ($blog_ids as $blog_id) {
                if (is_multisite()) { // avoid fatal error if pp_net_group is errantly retreived on a non-network installation
                    switch_to_blog($blog_id);
                }

                require_once(PRESSPERMIT_ABSPATH . '/library/api-legacy.php');
                ppc_delete_agent_permissions($group_id, $agent_type);

                if (is_multisite()) {
                    restore_current_blog();
                }
            }
        }
    }

    function unfilter_edit_user($wp_sitecaps, $orig_reqd_caps, $args)
    {
        global $current_user;

        if ($args[1] != $current_user->ID) {
            return $wp_sitecaps;
        }

        $args = (array)$args;
        $orig_cap = reset($args);
        if ('edit_user' == $orig_cap) {
            if (in_array('edit_users', $current_user->allcaps, true)) {
                $wp_sitecaps['edit_users'] = true;
            }
        }

        return $wp_sitecaps;
    }
}
