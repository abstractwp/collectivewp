<?php
namespace PublishPress\Permissions;

class StatusesHooks 
{
    function __construct() 
    {
        // This script executes on plugin load.
        //
        require_once(PRESSPERMIT_STATUSES_ABSPATH . '/db-config.php');

        add_filter('presspermit_default_options', [$this, 'flt_default_statuses_options']);

        add_action('presspermit_maintenance_triggers', [$this, 'actMaintenanceTriggers']);

        add_action('init', [$this, 'actRegistrations'], 46);
        add_action('init', [$this, 'act_post_stati_prep'], 48);  // StatusesHooksAdmin::act_process_conditions() follows at priority 49

        add_action('presspermit_pre_init', [$this, 'act_version_check']);
        add_action('presspermit_pre_init', [$this, 'act_forceDistinctPostCaps']);

        add_action('presspermit_register_role_attributes', [$this, 'act_register_role_attributes']);
        add_action('wp_loaded', [$this, 'act_late_registrations']);  // PublishPress compat

        add_action('presspermit_roles_defined', [$this, 'act_roles_defined']);

        add_action('presspermit_post_filters', [$this, 'act_load_capability_filters']);
        add_action('presspermit_enable_status_mapping', [$this, 'act_enable_status_mapping']);

        add_filter('presspermit_pattern_roles', [$this, 'fltPatternRoles']);
        add_filter('presspermit_pattern_role_caps', [$this, 'flt_default_rolecaps']);
        add_filter('presspermit_exclude_arbitrary_caps', [$this, 'fltExcludeArbitraryCaps']);

        add_filter('rest_user_query', [$this, 'flt_rest_user_query'], 20, 2);  // todo: relocate?

        add_filter('get_terms', [$this, 'flt_publishpress_status_position'], 50, 4);

        add_filter('option_publishpress_custom_status_options', [$this, 'flt_enable_publishpress_statuses']);

        if (defined('REVISIONARY_VERSION')) { // note: no equivalent needed for PublishPress Revisions 3
            require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Revisionary/CapabilityFilters.php');
            new Statuses\Revisionary\CapabilityFilters();
        }

        add_filter('presspermit_get_post_statuses', [$this, 'fltGetPostStatuses'], 10, 5);
        add_filter('presspermit_order_statuses', [$this, 'fltOrderStatuses'], 10, 2);

        add_filter('user_has_cap', [$this, 'fltPublishPostsContext'], 100, 3);

        add_action('rest_api_init', [$this, 'actRestInit'], 1);

        add_filter('post_row_actions', [$this, 'fixPostRowActions'], 9, 2);
        add_filter('page_row_actions', [$this, 'fixPostRowActions'], 9, 2);

        add_action('presspermit_version_updated', [$this, 'pluginUpdated']);

        add_filter(
            'presspermit_get_post_statuses',
            function ($statuses, $args, $return, $operator, $params = []) {
                global $pagenow;
                
                if (empty($pagenow) || !in_array($pagenow, ['post.php', 'post-new.php'])) {
                    return $statuses;
                }

                /*
                 * Filter: 'presspermit_limit_editor_post_statuses'
                 * 
                 * ['post_status' => array of selectable status names]
                 * 
                 * Note: statuses may be further limited based on user capabilities / permissions
                 */
                if (!$valid_statuses = apply_filters('presspermit_limit_editor_post_statuses', [], $args)){
                    return $statuses;
                }
                
                if (current_user_can('administrator')) {
                    return $statuses;
                }

                if (!$post_id = PWP::getPostID()) {
                    $post_status = 'draft';
                } else {
                    if (!$post_status = get_post_field('post_status', $post_id)) {
                        return $statuses;
                    }
                }

                if (isset($valid_statuses[$post_status])) {
                    $statuses = array_intersect_key($statuses, array_fill_keys($valid_statuses[$post_status], true));
                }

                return $statuses;
            }
        , 100, 5);
    }

    // Account for list_* capability provision: don't display Preview link if post is not editable
    function fixPostRowActions($actions, $post) {
        $can_edit = current_user_can('edit_post', $post->ID);

        if (in_array($post->post_status, get_post_stati(['public' => true, 'private' => true], 'names', 'OR'))) {
            if (!$can_edit && !current_user_can('read_post', $post->ID)) {
                unset($actions['view']);
            }
        } elseif(!$can_edit && !defined('PUBLISHPRESS_REVISIONS_VERSIONS')) {  // todo: API?
            unset($actions['view']);
        }

        return $actions;
    }

    function fltGetPostStatuses($statuses, $args, $return, $operator, $params = []) {
        $context = (!empty($params['context'])) ? $params['context'] : '';

        if (presspermit()->isAdministrator() || (is_multisite() && is_super_admin()) || !get_option('cme_custom_status_control')) {
            return $statuses;
        }
        
        $user = presspermit()->getUser();

        // Maintain default PublishPress behavior (Permissions add-on / Capabilities Pro) for statuses that do not have custom capabilities enabled
        foreach ($statuses as $status => $obj) {
            $_status = str_replace('-', '_', $status);

            if (!empty($obj->moderation) 
            && ($context != 'edit')
            && !in_array($status, ['draft', 'future']) 
            && !PPS::postStatusHasCustomCaps($status) 
            && empty($user->allcaps["status_change_{$_status}"])) {
                unset($statuses[$status]);
            }
        }

        return $statuses;
    }

    function fltPublishPostsContext($wp_sitecaps, $orig_reqd_caps, $args)
    {
        $user = presspermit()->getUser();
        $args = (array)$args;

        $post_id = PWP::getPostID();

        if (($args[1] != $user->ID) || !$post_id || (defined('ET_BUILDER_PLUGIN_VERSION') && !presspermit_empty_REQUEST('et_fb'))) {
            return $wp_sitecaps;
        }

        // If we are crediting edit_others_posts capability based on ownership of edit_others_{$status}_posts, 
        // don't honor publish_posts except for own posts
        if ($_post = get_post($post_id)) {
            if ($user->ID != $_post->post_author) {
                if ($type_obj = get_post_type_object($_post->post_type)) {
                    if (isset($type_obj->cap->publish_posts) && ($type_obj->cap->publish_posts == $args[0])) {
                        if (isset($type_obj->cap->edit_others_posts) && empty($user->allcaps[$type_obj->cap->edit_others_posts])) {
                            $cap_property = "edit_others_{$_post->post_status}_posts";
                            if (isset($type_obj->cap->$cap_property) && !empty($user->allcaps[$type_obj->cap->$cap_property])) {
                                unset($wp_sitecaps[$type_obj->cap->publish_posts]);
                            }
                        }
                    }
                }
            }
        }

        return $wp_sitecaps;
    }

    function actRestInit()
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/RESTFields.php');
        Statuses\RESTFields::registerRESTFields();

        register_post_status(
            '_pending', 
            [
                'label'                     => esc_html__('Pending'),
                'label_count'               => false,
                'exclude_from_search'       => true,
                'public'                    => false,
                'internal'                  => false,
                'protected'                 => true,
                'private'                   => false,
                'publicly_queryable'        => false,
                'show_in_admin_status_list' => false,
                'show_in_admin_all_list'    => false,
            ]
        );
    }

    function flt_default_statuses_options($def = [])
    {
        $new = [
            'privacy_statuses_enabled' => 0,
            'custom_privacy_edit_caps' => 0,    // previous default was intended to be 1, but now set to 0 to maintain behavior because this filter was not applied
            'supplemental_cap_moderate_any' => 0,
            'moderation_statuses_default_by_sequence' => 0,
            'draft_reading_exceptions' => 0,
        ];

        return array_merge($def, $new);
    }

    function flt_enable_publishpress_statuses($pubp_options) {
        $pubp_options->enabled = 'on';

        if (PWP::isWP5()) {
            $pubp_options->always_show_dropdown = 'on';
        }

        return $pubp_options;
    }

    function actMaintenanceTriggers()
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Triggers.php');
        new Statuses\Triggers();
    }

    // Register default custom stati; Additional labels in status registration
    function actRegistrations()
    {
        global $wp_post_statuses;

        if ($privacy_statuses_enabled = presspermit()->getOption('privacy_statuses_enabled')) {
            // custom private stati
            register_post_status('member', [
                'label' => _x('Member', 'post'),
                'private' => true,
                'label_count' => _n_noop('Member <span class="count">(%s)</span>', 'Member <span class="count">(%s)</span>'),
                'pp_builtin' => true,
            ]);

            register_post_status('premium', [
                'label' => _x('Premium', 'post'),
                'private' => true,
                'label_count' => _n_noop('Premium <span class="count">(%s)</span>', 'Premium <span class="count">(%s)</span>'),
                'pp_builtin' => true,
            ]);

            register_post_status('staff', [
                'label' => _x('Staff', 'post'),
                'private' => true,
                'label_count' => _n_noop('Staff <span class="count">(%s)</span>', 'Staff <span class="count">(%s)</span>'),
                'pp_builtin' => true,
            ]);
        }

        $custom_stati = (array)get_option('presspermit_custom_conditions_post_status');
        $native_moderation = false;

        foreach ($custom_stati as $status => $status_args) {
            if (isset($wp_post_statuses[$status])) {
                foreach(['label', 'save_as_label', 'publish_label'] as $property) {
                    if (!empty($status_args[$property])) {
                        $wp_post_statuses[$status]->$property = $status_args[$property];
                    }
                }
            }
            
            if (!empty($status_args['moderation'])) {
                if (defined('PP_NO_MODERATION'))
                    continue;

                $status_args['protected'] = true;
                $native_moderation = true;
            }

            if (!isset($wp_post_statuses[$status]) && $status && empty($status_args['publishpress']) 
            && !in_array($status, ['pitch', 'in-progress', 'assigned'])
            ) {
                register_post_status($status, $status_args);
            }

            if (!isset($wp_post_statuses[$status])) {
                continue;
            }

            if (!empty($status_args['post_type'])) {
                $wp_post_statuses[$status]->post_type = (array)$status_args['post_type'];
            }
        }

        if ($native_moderation) {
            $disable_native_moderation_statuses = get_option('ppperm_disable_native_moderation_statuses');
        }

        if ($native_moderation && (false === $disable_native_moderation_statuses)) {
            if (defined('PUBLISHPRESS_VERSION') && version_compare(PUBLISHPRESS_VERSION, '1.19', '>=') && PWP::isBlockEditorActive()) {
                // Previous behavior for custom visibility statuses: 
                // Once PublishPress and Gutenberg were active, the option setting was hard-defaulted by option storage, based on whether any PressPermit-defined statuses were assigned.

                // if (!PPS::customStatiUsed(['ignore_moderation_statuses' => true])) {
                //     $privacy_statuses_enabled = 0;
                // } else {
                //    $privacy_statuses_enabled = 1; // setting to integer value maintains status availability but prevents the customStatiUsed() query from executing again
                // }

                // presspermit()->updateOption('privacy_statuses_enabled', $privacy_statuses_enabled);
                
                if ($native_moderation) {
                    // If custom Visibility statuses are disabled, also disable PressPermit-defined moderation statuses in favor of PublishPress statuses
                    // This is a somewhat inconsistent criteria, but maintain it to avoid triggering edge case legacy issues.
                    update_option('ppperm_disable_native_moderation_statuses', !$privacy_statuses_enabled);
                }
            }
        }

        if ($native_moderation && $disable_native_moderation_statuses && !defined('PRESSPERMIT_LEGACY_MODERATION_STATUSES')) {
            foreach (array_intersect_key($wp_post_statuses, $custom_stati) as $post_status => $status_obj) {
                if (!in_array($post_status, ['draft', 'pending', 'future']) && empty($status_obj->_builtin) && empty($status_obj->private)) {
                    unset($wp_post_statuses[$post_status]);
                }
            }
        }
    }

    function act_post_stati_prep()
    {
        global $wp_post_statuses;

        $pp = presspermit();

        // set default properties
        foreach (array_keys($wp_post_statuses) as $status) {
            if (!isset($wp_post_statuses[$status]->moderation))
                $wp_post_statuses[$status]->moderation = false;
        }

        // apply PP-stored status config
        // @todo: does this cause extra query because not included in presspermit_default_options array?
        if ($stati_post_types = (array)$pp->getOption('status_post_types')) {
            foreach ($stati_post_types as $status => $types) {
                if (isset($wp_post_statuses[$status])) {
                    $wp_post_statuses[$status]->post_type = $types;
                }
            }
        }

        if (defined('PRESSPERMIT_COLLAB_VERSION')) {
            $status_cap_status = array_intersect_key((array)$pp->getOption('status_capability_status'), $wp_post_statuses);
            
            foreach ($status_cap_status as $status => $cap_status) {
                $wp_post_statuses[$status]->capability_status = $cap_status;
            }

            $stati_order = array_intersect_key((array)$pp->getOption('status_order'), $wp_post_statuses);
            foreach ($stati_order as $status => $order) {
                $wp_post_statuses[$status]->order = $order;
            }

            $stati_parent = array_intersect_key((array)$pp->getOption('status_parent'), $wp_post_statuses);
            foreach ($stati_parent as $status => $parent) {
                $wp_post_statuses[$status]->status_parent = $parent;
            }

            if (isset($wp_post_statuses['pending']) && !isset($wp_post_statuses['pending']->order))
                $wp_post_statuses['pending']->order = 10;

            if (isset($wp_post_statuses['approved']) && !isset($wp_post_statuses['approved']->order))
                $wp_post_statuses['approved']->order = $wp_post_statuses['pending']->order + 8;

            foreach (PWP::getPostStatuses(['moderation' => true]) as $status) {
                if (!isset($wp_post_statuses[$status]->status_parent)) {
                    $wp_post_statuses[$status]->status_parent = '';
                }

                if (!isset($wp_post_statuses[$status]->order)) {
                    $wp_post_statuses[$status]->order = ($wp_post_statuses[$status]->status_parent) 
                    ? 0 
                    : $wp_post_statuses['pending']->order + 4;

                    $wp_post_statuses[$status]->order = 0;
                }
            }
        }
    }

    function act_version_check()
    {
        $ver = get_option('pps_version');
        $pp_ver = get_option('presspermitpro_version');

        if (!empty($ver['version'])) {
            // These maintenance operations only apply when a previous version of PPCS was installed 
            if (version_compare(PRESSPERMIT_STATUSES_VERSION, $ver['version'], '!=')) {
                require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Updated.php');
                new Statuses\Updated($ver['version']);
                update_option(
                    'pps_version', 
                    ['version' => PRESSPERMIT_STATUSES_VERSION, 'db_version' => PRESSPERMIT_STATUSES_DB_VERSION]
                );

                // pp_attributes table was not created in previous 2.7-beta versions
                $force_db_update = version_compare($ver['version'], '2.7-beta3', '<');
            }
        } else {
            // first execution after install
            require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Updated.php');
            Statuses\Updated::populateRoles();
        }

        if (!empty($force_db_update) || !is_array($ver) || empty($ver['db_version']) 
        || version_compare(PRESSPERMIT_STATUSES_DB_VERSION, $ver['db_version'], '!=') 
        || ($pp_ver && version_compare($pp_ver['version'], '3.2.7', '<'))
        ) {
            require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/DB/DatabaseSetup.php');
            $db_ver = (is_array($ver) && isset($ver['db_version'])) ? $ver['db_version'] : '';
            new Statuses\DB\DatabaseSetup($db_ver);
            update_option('pps_version', ['version' => PRESSPERMIT_STATUSES_VERSION, 'db_version' => PRESSPERMIT_STATUSES_DB_VERSION]);
        }
    }

    function act_forceDistinctPostCaps()
    {
        global $wp_post_types;

        $pp = presspermit();

        $generic_caps = ['post' => ['set_posts_status' => 'set_posts_status'], 'page' => ['set_posts_status' => 'set_posts_status']];

        // post types which are enabled for PP filtering must have distinct type-related cap definitions
        foreach (array_intersect(get_post_types(['public' => true, 'show_ui' => true], 'names', 'or'), $pp->getEnabledPostTypes()) as $post_type) {
            if ('post' == $post_type) {
                $type_caps['set_posts_status'] = 'set_posts_status';
            } else {
                $type_caps['set_posts_status'] = str_replace('_post', "_$post_type", 'set_posts_status');
            }

            $wp_post_types[$post_type]->cap = (object)array_merge((array)$wp_post_types[$post_type]->cap, $type_caps);

            $plural_type = \PublishPress\Permissions\Capabilities::getPlural($post_type, $wp_post_types[$post_type]);

            $pp->capDefs()->all_type_caps = array_merge($pp->capDefs()->all_type_caps, array_fill_keys($type_caps, true));

            foreach (PWP::getPostStatuses(['moderation' => true, 'post_type' => $post_type]) as $status_name) {
                $cap_property = "set_{$status_name}_posts";
                $wp_post_types[$post_type]->cap->$cap_property = str_replace("_posts", "_{$plural_type}", $cap_property);
            }
        }
    }

    function act_load_capability_filters()
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/CapabilityFilters.php');
        Statuses\CapabilityFilters::instance();
    }

    function act_enable_status_mapping($enable)
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/CapabilityFilters.php');

         // for perf, instead of removing/adding 'map_meta_cap' filter here
        Statuses\CapabilityFilters::instance()->do_status_cap_map = $enable;
    }

    // For optimal flexibility with custom moderation stati (including PublishPress Statuses), dynamically insert a Submitter role 
    // containing the 'set_posts_status' capability.
    //
    // With default Contributor rolecaps, a "Page Contributor - Assigned" role enables the user to edit their own pages 
    // which have been set to assigned status.  
    //
    // "Page Submitter - Assigned" role enables setting their other pages to the Approved status
    //
    // These supplemental roles may be assigned individually or in conjunction
    // Note that the set_posts_status capability is granted implicitly for the 'pending' status, 
    // even if custom capabilities are enabled.
    function flt_default_rolecaps($caps)
    {
        if (defined('PRESSPERMIT_COLLAB_VERSION') && !isset($caps['submitter'])) {
            $caps['submitter'] = array_fill_keys(['read', PRESSPERMIT_READ_PUBLIC_CAP, 'set_posts_status'], true);
        }

        return $caps;
    }

    function fltPatternRoles($roles)
    {
        if (defined('PRESSPERMIT_COLLAB_VERSION')) {
            if (!isset($roles['submitter']))
                $roles['submitter'] = (object)[];

            if (!isset($roles['submitter']->labels))
                $roles['submitter']->labels = (object)[
                    'name' => esc_html__('Submitters', 'presspermit'), 
                    'singular_name' => esc_html__('Submitter', 'presspermit')
                ];
        }

        return $roles;
    }

    function act_register_role_attributes()
    {
        $attributes = PPS::attributes();

        do_action('presspermit_status_registrations');

        // Restriction of read access will be accomplished by post status setting (either private or a custom status registered with private=true) 
        //
        // force_visibility attribute does not impose condition caps, but affects the post_status of published posts.
        PPS::registerAttribute(
            'force_visibility', 
            'post',
            [
                'label' => esc_html__('Force Visibility', 'presspermit-pro'), 
                'default' => 'none', 
                'suppress_item_edit_ui' => ['object' => true]
            ]
        );

        // note: post_status is NOT stored to the attributes table
        PPS::registerAttribute('post_status', 'post', ['label' => esc_html__('Post Status', 'presspermit-pro')]);

        if (!defined('PPS_CUSTOM_PRIVACY_EDIT_CAPS')) {
            define('PPS_CUSTOM_PRIVACY_EDIT_CAPS', presspermit()->getOption('custom_privacy_edit_caps'));
        }

        // register each custom post status as an attribute condition with mapped caps
        PPS::registerConditions(get_post_stati([], 'object'));

        if (is_user_logged_in() && presspermit()->getOption('draft_reading_exceptions') 
        && (PWP::isFront() || (defined('REST_REQUEST') && REST_REQUEST))
        ) {
            global $wp_post_statuses;
            $wp_post_statuses['draft']->private = true;
            $wp_post_statuses['draft']->protected = false;

            $status_obj = get_post_status_object('draft');

            PPS::registerCondition('post_status', 'draft', [
                'label' => $status_obj->label,
                'metacap_map' => ['read_post' => 'read_draft_posts'],
            ]);
        }

        $attributes->process_status_caps();
    }

    // late registration of statuses for PublishPress compat (PublishPress hooks to 'init' action at priority 1000)
    function act_late_registrations()
    {
        global $wp_post_statuses, $pagenow;

        $pp = presspermit();

        // @todo: does this cause extra query because not included in presspermit_default_options array?
        if ($stati_post_types = (array)$pp->getOption('status_post_types')) {
            foreach ($stati_post_types as $status => $types) {
                if (isset($wp_post_statuses[$status])) {
                    $wp_post_statuses[$status]->post_type = $types;
                }
            }
        }

        // late execution to ensure pending and approved status, if present, have appropriate default order
        if (defined('PRESSPERMIT_COLLAB_VERSION')) {
            $status_cap_status = array_intersect_key((array)$pp->getOption('status_capability_status'), $wp_post_statuses);
            foreach ($status_cap_status as $status => $cap_status) {
                $wp_post_statuses[$status]->capability_status = $cap_status;
            }

            $stati_order = array_intersect_key((array)$pp->getOption('status_order'), $wp_post_statuses);
            foreach ($stati_order as $status => $order) {
                $wp_post_statuses[$status]->order = $order;
            }

            if (isset($wp_post_statuses['pending']) && !isset($wp_post_statuses['pending']->order)) {
                $wp_post_statuses['pending']->order = 10;
            }

            if (isset($wp_post_statuses['approved']) && !isset($wp_post_statuses['approved']->order))
                $wp_post_statuses['approved']->order = $wp_post_statuses['pending']->order + 8;

            foreach (PWP::getPostStatuses(['moderation' => true]) as $status) {
                if (empty($wp_post_statuses[$status]->order)) {
                    $wp_post_statuses[$status]->order = 0;
                }
            }

            $stati_parent = array_intersect_key((array)$pp->getOption('status_parent'), $wp_post_statuses);
            foreach ($stati_parent as $status => $parent) {
                $wp_post_statuses[$status]->status_parent = $parent;
            }
        }

        // PublishPress compat (mark EF stati as moderation)
        if ((defined('PUBLISHPRESS_VERSION') && class_exists('PP_Custom_Status')) 
        && defined('PRESSPERMIT_COLLAB_VERSION') && !defined('PP_NO_MODERATION')
        ) {
            $ef_stati = [];
            $ef_terms = get_terms('post_status', ['hide_empty' => false]);

            foreach ($ef_terms as $term) {
                if (is_object($term))
                    $ef_stati[$term->slug] = true;
            }

            $default_status_order = PPS::defaultStatusOrder();

            foreach (get_post_stati(['public' => false, 'private' => false], 'names') as $status) {
                if (array_key_exists($status, $ef_stati) && !in_array($status, ['draft', 'pending'])) {
                    $wp_post_statuses[$status]->moderation = true;
                    $wp_post_statuses[$status]->pp_custom = true;

                    if (!isset($wp_post_statuses[$status]->order) && isset($default_status_order[$status])) {
                        $wp_post_statuses[$status]->order = $default_status_order[$status];
                    }
                }
            }
        }

        $custom_stati = array_intersect_key((array)get_option("presspermit_custom_conditions_post_status"), $wp_post_statuses);
        foreach ($custom_stati as $status => $status_args) {
            if (!empty($status_args['post_type'])) {
                $wp_post_statuses[$status]->post_type = (array)$status_args['post_type'];
            }
            if (! isset($wp_post_statuses[$status]->capability_status)) {
                $wp_post_statuses[$status]->capability_status = $status;
            }
        }

        if (in_array($pagenow, ['edit.php', 'post.php', 'post-new.php'])
            || (is_admin() && PWP::isAjax('inline-save'))
            || (in_array(presspermitPluginPage(), ['presspermit-status-edit', 'presspermit-status-new'], true))) {

            require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/UI/Dashboard/PostAdmin.php');
            Statuses\UI\Dashboard\PostAdmin::set_status_labels();
        }

        do_action('presspermit_registrations');

        do_action('presspermit_conditions_loaded');

        PPS::attributes()->process_status_caps();
    }

    // Mirror ordering defined by PressPermit back to PublishPress.
    //
    // PublishPress does not natively support branching, but position sequence 
    // is flattened considering branch parent and child order.
    function flt_publishpress_status_position($terms, $taxonomies, $query_vars, $term_query = false)
    {
        global $publishpress;

        $pp = presspermit();

        $taxonomies = (array)$taxonomies;

        if ('post_status' != reset($taxonomies)) {
            return $terms;
        }

        $status_order_arr = array_merge(PPS::defaultStatusOrder(), (array)$pp->getOption('status_order'));
        $status_parent_arr = (array)$pp->getOption('status_parent');

        foreach ($terms as $k => $term_obj) {
            $status = $term_obj->slug;

            if (!empty($status_parent_arr[$status])) {
                $parent_status = $status_parent_arr[$status];
                $parent_order = (!empty($status_order_arr[$parent_status])) ? $status_order_arr[$parent_status] : 0;
                $status_order = (!empty($status_order_arr[$status])) ? $status_order_arr[$status] : 500;
                $terms[$k]->position = (1000 * $parent_order) + $status_order + 1;

            } elseif (isset($status_order_arr[$status])) {
                // Allow space between top level statuses for branch statuses
                $terms[$k]->position = 1000 * $status_order_arr[$status];
            }

            // term description may contain encoded copy of other position property, overriding filtering 
            if (!empty($publishpress->custom_status)) {
                if (!empty($terms[$k]->description)) {
                    $descript = $publishpress->custom_status->get_unencoded_description($terms[$k]->description);
                    if (is_array($descript)) {
                        unset($descript['position']);
                    }
                    $terms[$k]->description = $publishpress->custom_status->get_encoded_description($descript);
                }
            }
        }

        return $terms;
    }

    function act_roles_defined()
    {
        if ('presspermit-statuses' != presspermitPluginPage()) {
            if ($disabled = (array)presspermit()->getOption('disabled_post_status_conditions')) {
                $attributes = PPS::attributes();
                
                $attributes->attributes['post_status']->conditions = array_diff_key(
                    $attributes->attributes['post_status']->conditions, 
                    $disabled
                );

                global $wp_post_statuses;
                $disabled = array_diff_key($disabled, get_post_stati(['_builtin' => true]));

                foreach ($wp_post_statuses as $k => $status_obj) {
                    if (!empty($status_obj->pp_custom)) {
                        unset($wp_post_statuses[$k]);
                    }
                }

                $wp_post_statuses = array_diff_key($wp_post_statuses, $disabled);
            }
        }
    }

    function fltExcludeArbitraryCaps($caps)
    {
        $excluded = ['pp_define_post_status', 'pp_define_moderation', 'pp_define_privacy'];

        if (!presspermit()->getOption('supplemental_cap_moderate_any'))
            $excluded [] = 'pp_moderate_any';

        return array_merge($caps, $excluded);
    }

    function fltOrderStatuses($statuses, $args = []) {
        return PPS::orderStatuses($statuses, $args);
    }

    // Gutenberg: filter post author dropdown 
    function flt_rest_user_query($prepared_args, $request)
    {
        if (isset($prepared_args['who']) && ('authors' == $prepared_args['who'])) {
            if ($post_type = PWP::findPostType()) {
                if ($type_obj = get_post_type_object($post_type)) {
                    if (!current_user_can($type_obj->cap->edit_others_posts)) {
                        global $current_user;
                        $prepared_args['include'] = $current_user->ID;
                    }
                }
            }
        }

        return $prepared_args;
    }

    public function pluginUpdated($prev_version) {
        if (version_compare($prev_version, '3.6.1', '<')) {
            add_action('wp_loaded', function() {
                global $wp_roles;

                // For each status, if "edit_others_{$status_name}_attachments" was set to a role due to past bug, add type-specific status capability if other editing capabilities are present for the post type

                $statuses = get_post_stati(['moderation' => true], 'names', 'or');

                foreach($statuses as $status_name) {
                    $check_cap = "edit_others_{$status_name}_attachments";

                    foreach($wp_roles->roles as $role_name => $_role) {
                        if (!empty($_role['capabilities'][$check_cap])) {
                            $role = @get_role($role_name);
                        
                            // For each post type that has custom capabilities enabled, if the user has "edit_{$status_name}_pages" and edit_others_pages, also set "edit_others_{$status_name}_pages"

                            if (PPS::postStatusHasCustomCaps($status_name)) {
                                foreach(get_post_types(['public' => true], 'object') as $post_type => $type_obj) {
                                    $edit_posts_status_cap = str_replace('edit_', "edit_{$status_name}_", $type_obj->cap->edit_posts);
                                    
                                    if (!empty($type_obj->cap->edit_others_posts) && !empty($_role['capabilities'][$edit_posts_status_cap]) && !empty($_role['capabilities'][$type_obj->cap->edit_others_posts])) {
                                        $edit_others_posts_status_cap = str_replace('edit_others_', "edit_others_{$status_name}_", $type_obj->cap->edit_others_posts);
                                        $role->add_cap($edit_others_posts_status_cap);
                                    }
                                }
                            }
                        }
                    }
                }
            }, 50);
        }
    }
}
