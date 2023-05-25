<?php
namespace PublishPress\Permissions\SyncPosts;

/**
 * UserSync class
 *
 * @package PressPermit
 * @author Kevin Behrens
 * @copyright Copyright (c) 2019, PublishPress
 *
 */
class UserSync
{
    var $log = [];

    function newUser($user_id, $role_name = '', $blog_id = '')
    {
        if ($post_types = presspermit()->getOption('sync_posts_to_users_types')) {
            if ($post_types = array_diff($post_types, [false, 0, '0'])) {
                if (!$role_name) {
                    $user = new \WP_User($user_id);
                    $role_name = $user->get_role();
                }

                $this->syncPostsToUsers(
                    ['sync_user_id' => $user_id, 'new_user_role' => $role_name, 'post_types' => array_keys($post_types)]
                );
            }
        }
    }

    function syncPostsToUsers($args = [])
    {
        global $wpdb;

        $pp = presspermit();

        $defaults = ['sync_user_id' => 0, 'post_types' => [], 'new_user_role' => ''];
        $args = array_merge($defaults, (array)$args);

        $post_types = (array)$args['post_types'];
        $sync_user_id = $args['sync_user_id'];
        $new_user_role = $args['new_user_role'];

        if (!$post_types) return;

        // note: only support postmeta and users columns for now

        foreach ($post_types as $post_type) {
            if (!post_type_exists($post_type)) continue;
            $post_type_obj = get_post_type_object($post_type);

            $pp = presspermit();

            $post_field = $pp->getTypeOption('sync_posts_to_users_post_field', $post_type);
            $user_field = $pp->getTypeOption('sync_posts_to_users_user_field', $post_type);
            $role = $pp->getTypeOption('sync_posts_to_users_role', $post_type);

            if (!$role) {
                $this->log[] = sprintf(esc_html__('%s: Skipping because no role is selected', 'presspermit-pro'), esc_html($post_type_obj->label));
                do_action('presspermit_sync_post_to_users_no_role_sected', $post_type, $post_field, $user_field);
                continue;
            }

            if (!$post_field || !$user_field) continue;

            // new user registration specifies selected role for new user
            if ($role && $new_user_role && ($role != '(any)') && ($role != $new_user_role)) continue;

            if ($pp->getOption('sync_posts_to_users_apply_permissions')) {
                // turn on PP filtering for this post type
                $filtered_post_types = $pp->getOption('enabled_post_types');
                if (empty($filtered_post_types[$post_type])) {
                    $filtered_post_types[$post_type] = 1;
                    $pp->updateOption('enabled_post_types', $filtered_post_types);

                    $this->log[] = sprintf(
                        esc_html__('%s: Enabled PressPermit filtering (Core > Filtered Post Types)', 'presspermit-pro'), 
                        esc_html($post_type_obj->label)
                    );
                }

                // ensure this role has a supplemental author role for the post type
                if ($group_id = $pp->groups()->getMetagroup('wp_role', $role, ['cols' => 'id'])) {
                    $supplemental_roles = $pp->getRoles($group_id, 'pp_group');

                    $pp_role_name = "author:post:{$post_type}";

                    if (empty($supplemental_roles[$pp_role_name])) {
                        $pp->assignRoles([$pp_role_name => [$group_id => true]], 'pp_group');

                        $this->log[] = sprintf(
                            esc_html__('%1$s: Assigned supplemental %2$s Author role to [WP %3$s] group', 'presspermit-pro'), 
                            esc_html($post_type_obj->label), 
                            esc_html($post_type_obj->labels->singular_name), 
                            ucwords($role)
                        );
                    }
                }
            }

            if ($post_parent = $pp->getTypeOption('sync_posts_to_users_post_parent', $post_type)) {
                $parent_obj = get_post($post_parent);
                if (empty($parent_obj) || ('trash' == $parent_obj->post_status)) {
                    $post_parent = 0;
                }
            }

            $user_search_args = (!$role || '(any)' == $role || $new_user_role) ? [] : ['role' => $role];

            $user_search_args['fields'] = ['ID', 'user_login', 'user_email', 'user_nicename', 'display_name'];

            if ($sync_user_id) {
                $user_search_args['search'] = $sync_user_id;
                $user_search_args['search_columns'] = ['ID'];
            }
            $user_query = new \WP_User_Query($user_search_args);

            $users = apply_filters(
                'presspermit_sync_posts_to_users_user_results', 
                $user_query->get_results(), 
                $post_type, 
                $user_field, 
                $role
            );

            $users_display_name = [];
            $users_nicename = [];
            foreach ($users as $u) {
                $users_display_name[$u->ID] = $u->display_name;
                $users_nicename[$u->ID] = $u->user_nicename;
            }

            $users_by_field = [];

            if ($users 
            && !in_array($user_field, ['ID', 'user_login', 'user_email', 'user_nicename', 'display_name'], true)
            ) {
                $user_id_csv = implode(',', array_map('intval', array_keys($users_nicename)));

                $results = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id, meta_value FROM $wpdb->usermeta WHERE user_id IN ('$user_id_csv') AND meta_key = %s", 
                        $user_field
                    )
                );

                foreach ($results as $row) {
                    $users_by_field[$row->meta_value] = $row->user_id;
                }
            } else {
                foreach ($users as $u) {
                    $field_val = ('display_name' == $user_field) 
                    ? apply_filters('presspermit_sync_posts_to_users_post_title', $u->$user_field, $u->ID, compact('post_type', 'user_field', 'post_field'))
                    : $u->$user_field;

                    $users_by_field[$field_val] = $u->ID;
                }
            }

            if (in_array($post_field, ['post_title', 'post_name'], true)) {
                // $post_field is a column in posts table, but alias it as meta_value for results processing 
                $posts = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT ID, post_author, post_title, post_title, post_name as meta_value FROM $wpdb->posts 
                        WHERE post_type = '%s' AND post_status != 'trash'",

                        $post_type
                    )
                );
            } else {
                $posts = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT p.ID, p.post_author, p.post_title, pm.meta_value FROM $wpdb->posts AS p
                        LEFT JOIN $wpdb->postmeta AS pm ON p.ID = pm.post_id 
                        WHERE post_type = '%s' AND post_status != 'trash' AND pm.meta_key = '%s'",

                        $post_type, 
                        $post_field
                    )
                );
            }

            $posts_by_field = [];
            $posts_title = [];
            $posts_author = [];
            foreach ($posts as $p) {
                $posts_by_field[$p->meta_value] = $p->ID;
                $posts_title[$p->ID] = $p->post_title;
                $posts_author[$p->ID] = $p->post_author;
            }

            // === update post_author value of existing posts ===

            if ($matched_posts = array_intersect_key($posts_by_field, $users_by_field)) {

                // make sure we don't sync on a non-unique post match
                if ('post_title' == $post_field) {

                    foreach (array_keys($matched_posts) as $post_title) {
                        // make sure we don't sync on a non-unique post match
                        $post_title_instances = array_intersect($posts_title, [$post_title]);
                        if (count($post_title_instances) > 1) {
                            $this->log[] = sprintf(
                                esc_html__('Ambiguous %1$s match (%2$s)', 'presspermit-pro'), 
                                esc_html($post_type_obj->labels->singular_name), 
                                esc_html($post_title)
                            );
                            
                            do_action(
                                'presspermit_sync_post_to_users_ambiguous_post_match', 
                                $post_type, 
                                $post_field, 
                                $post_title, 
                                $post_title_instances
                            );
                            
                            unset($matched_posts[$post_title]);
                        }
                    }
                }

                // make sure we don't sync on a non-unique user match
                if ('display_name' == $user_field) {

                    foreach ($matched_posts as $display_name => $post_id) {
                        if ($sync_user_id) {
                            $user_search_args['search'] = $display_name;
                            $user_search_args['search_columns'] = ['display_name'];
                            $display_name_query = new \WP_User_Query($user_search_args);
                            $display_name_users = $display_name_query->get_results();

                            if (count($display_name_users) > 1) {
                                $this->log[] = sprintf(
                                    esc_html__('Ambiguous user match for %1$s (%2$s, post id %3$s', 'presspermit-pro'), 
                                    esc_html($post_type_obj->labels->singular_name), 
                                    esc_html($display_name), 
                                    (int) $post_id
                                );
                                
                                do_action(
                                    'presspermit_sync_post_to_users_ambiguous_user_match', 
                                    $post_type, 
                                    $user_field, 
                                    $display_name_users, 
                                    compact('post_id', 'sync_user_id')
                                );
                               
                                return;
                            }
                        } else {
                            if (count(array_intersect($users_display_name, [$display_name])) > 1) {
                                // WP_User_Query for do_action argument consistent with user_register case
                                $user_search_args['search'] = $display_name;
                                $user_search_args['search_columns'] = ['display_name'];
                                $display_name_query = new \WP_User_Query($user_search_args);
                                $display_name_users = $display_name_query->get_results();

                                $this->log[] = sprintf(
                                    esc_html__('Ambiguous user match for %1$s (%2$s, $post id %3$s)', 'presspermit-pro'), 
                                    esc_html($post_type_obj->labels->singular_name), 
                                    esc_html($display_name), 
                                    (int) $post_id
                                );
                                
                                do_action(
                                    'presspermit_sync_post_to_users_ambiguous_user_match', 
                                    $post_type, 
                                    $user_field, 
                                    $display_name_users, 
                                    compact('post_id')
                                );
                                
                                unset($matched_posts[$display_name]);
                            }
                        }
                    }
                }
            }

            if ( empty( $allow_field_update ) ) {
                $allow_field_update = apply_filters( 
                    'presspermit_sync_posts_to_users_allow_non_unique_field_sync', 
                    false, 
                    $post_type, 
                    $post_field, 
                    $user_field 
                );
            }

            $update_count = 0;

            foreach ($matched_posts as $field_value => $post_id) {
                if (!$field_value) continue;

                do_action('presspermit_sync_posts_to_users_matched_post', $post_id, $users_by_field[$field_value]);

                $author_id = (isset($posts_author[$post_id])) ? $posts_author[$post_id] : 0;
                if ($author_id != $users_by_field[$field_value]) {
                    if ($author_id) {
                        if (!isset($administrator_ids)) {
                            $user_query = new \WP_User_Query(['role' => 'administrator', 'fields' => 'ID']);
                            $administrator_ids = $user_query->get_results();
                        }

                        // if this post already has a non-Administrator author set, don't re-sync
                        if (!in_array($author_id, $administrator_ids)) {
                            do_action(
                                'presspermit_sync_posts_to_users_skip_post_sync', 
                                $post_id, 
                                $post_type, 
                                $post_field, 
                                $user_field
                            );
                            
                            continue;
                        }
                    }

                    wp_update_post(['ID' => $post_id, 'post_author' => $users_by_field[$field_value]]);
                    
                    do_action('presspermit_sync_posts_to_users_updated_post', $post_id, $users_by_field[$field_value]);
                    $update_count++;
                }
            }

            // create new posts for unmatched users
            $post_status = apply_filters('presspermit_sync_posts_to_users_post_status', 'draft', $post_type);
            $post_content = apply_filters('presspermit_sync_posts_to_users_post_content', '', $post_type);

            $unmatched_users = array_diff_key($users_by_field, $matched_posts);

            $insert_count = 0;
            foreach ($unmatched_users as $field_value => $user_id) {
                if (!$field_value) continue;

                $post_title = !empty($users_display_name[$user_id]) 
                ? $users_display_name[$user_id] 
                : $users_nicename[$user_id];
                
                $post_title = apply_filters('presspermit_sync_posts_to_users_post_title', $post_title, $user_id, compact('post_type', 'user_field', 'post_field'));

                $post_arr = [
                    'post_type' => $post_type, 
                    'post_author' => $user_id, 
                    'post_name' => $users_nicename[$user_id], 
                    'post_title' => $post_title, 
                    'post_content' => $post_content, 
                    'post_parent' => $post_parent, 
                    'post_status' => $post_status
                ];
                
                $post_arr = apply_filters(
                    'presspermit_sync_posts_to_users_insert_post_data', 
                    $post_arr, 
                    $post_type, 
                    $user_id, 
                    $field_value, 
                    $post_field
                );

                if ('nickname' == $user_field) {
                    $post_arr['post_name'] = $field_value;
                }

                if (empty($post_arr)) continue;

                // make sure we don't sync on a non-unique user match
                if ('display_name' == $user_field) {
                    foreach (array_keys($unmatched_users) as $display_name) {
                        if ($sync_user_id) {
                            $user_search_args['search'] = $display_name;
                            $user_search_args['search_columns'] = ['display_name'];
                            $display_name_query = new \WP_User_Query($user_search_args);
                            $display_name_users = $display_name_query->get_results();

                            if (count($display_name_users) > 1) {
                                $this->log[] = sprintf(
                                    esc_html__('Ambiguous user match for %1$s (%2$s)', 'presspermit-pro'), 
                                    esc_html($post_type_obj->labels->singular_name), 
                                    esc_html($display_name)
                                );
                                
                                do_action(
                                    'presspermit_sync_post_to_users_ambiguous_user_match', 
                                    $post_type, 
                                    $user_field, 
                                    $display_name_users, 
                                    compact('sync_user_id')
                                );
                                
                                return;
                            }
                        } else {
                            if (count(array_intersect($users_display_name, [$display_name])) > 1) {
                                // WP_User_Query for do_action argument consistent with user_register case
                                $user_search_args['search'] = $display_name;
                                $user_search_args['search_columns'] = ['display_name'];
                                $display_name_query = new \WP_User_Query($user_search_args);
                                $display_name_users = $display_name_query->get_results();

                                $this->log[] = sprintf(
                                    esc_html__('Ambiguous user match for %1$s (%2$s)', 'presspermit-pro'), 
                                    esc_html($post_type_obj->labels->singular_name), 
                                    esc_html($display_name)
                                );
                                
                                do_action(
                                    'presspermit_sync_post_to_users_ambiguous_user_match', 
                                    $post_type, 
                                    $user_field, 
                                    $display_name_users, 
                                    []
                                );
                                
                                continue;
                            }
                        }
                    }
                }

                if ($post_id = wp_insert_post($post_arr)) {
                    if (!in_array($post_field, ['post_title', 'post_name'])) {
                    	add_post_meta($post_id, $post_field, $field_value);
                    }

					add_post_meta($post_id, '_pp_sync_author_id', $user_id);
                    do_action('presspermit_sync_posts_to_users_added_post', $post_id, $user_id, $post_type);
                    $insert_count++;
                }
            }

            if ($insert_count) $this->log[] = sprintf(
                esc_html__('%1$s: %2$s posts created', 'presspermit-pro'), 
                esc_html($post_type_obj->label), 
                (int) $insert_count
            );

            if ($update_count) $this->log[] = sprintf(
                esc_html__('%1$s: %2$s posts updated', 'presspermit-pro'), 
                esc_html($post_type_obj->label), 
                (int) $update_count
            );

            if ($role) {
                global $wp_roles;
                $role_label = (!empty($wp_roles) && !empty($wp_roles->role_names[$role])) 
                ? $wp_roles->role_names[$role] 
                : $role;

                $this->log[] = sprintf(esc_html__('%1$s: Synchronization done for %2$s role.', 'presspermit-pro'), esc_html($post_type_obj->label), esc_html($role_label));
            } else
                $this->log[] = sprintf(esc_html__('%s: Synchronization done.', 'presspermit-pro'), esc_html($post_type_obj->label));

            do_action('presspermit_sync_posts_to_users_done', $post_type, $update_count, $insert_count);
        }
    }
}
