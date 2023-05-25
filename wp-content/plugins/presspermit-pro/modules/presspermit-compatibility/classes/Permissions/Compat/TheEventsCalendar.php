<?php
namespace PublishPress\Permissions\Compat;

class TheEventsCalendar {
    function __construct() {
        add_filter('presspermit_has_post_cap_vars', [$this, 'fltHasPostCapVars'], 10, 4);
        add_filter('presspermit_teaser_readable_posts', [$this, 'fltTeaserReadablePosts'], 10, 4);
    }

    function fltHasPostCapVars($modify_vars, $wp_sitecaps, $pp_reqd_caps, $post_cap_args) {
        global $wpdb;
        
        if (empty($post_cap_args['post_id']) || !is_scalar($post_cap_args['post_id'])) {
            return $modify_vars;
        }

        $post_id = $post_cap_args['post_id'];
        
        // The Events Manager Pro: Events Manager screen passes invalid post ID with 'edit_post' capability check
        if ('tribe_events' == \PublishPress\Permissions\PostFilters::postTypeFromCaps($post_cap_args['orig_reqd_caps'])) {
            $_post = get_post($post_id);

            if ($_post && is_object($_post) && is_a($_post, 'WP_Post') && !empty($_post->post_type) && ('tribe_events' == $_post->post_type) && !empty($_post->guid)) {
                global $wpdb;
                static $event_actual_ids;

                if (!isset($event_guids)) {
                    $event_actual_ids = [];

                    global $wp_query;

                    if (!empty(presspermit()->listed_ids['tribe_events'])) {
                        $queried_event_ids = presspermit()->listed_ids['tribe_events'];
                    } else {
                        if ($post_id > 1000000) {
                            if ($_post = get_post($post_id)) {
                                if ($actual_post_id = $wpdb->get_var(
                                    $wpdb->prepare(
                                        "SELECT ID FROM $wpdb->posts WHERE guid = %s",
                                        $_post->guid
                                    )
                                )) {
                                    $modify_vars['post_id'] = $actual_post_id;
                                    return $modify_vars;
                                }
                            }
                        }

                        $queried_event_ids = [$post_id];
                    }

                    $id_csv = implode("','", array_keys($queried_event_ids));

                    $results = $wpdb->get_results(
                        "SELECT ID, guid FROM $wpdb->posts WHERE ID IN ('$id_csv')"
                    );

                    foreach($results as $row) {
                        $event_actual_ids[$row->guid] = $row->ID;
                    }
                }

                if (!in_array($post_id, $event_actual_ids) && isset($event_actual_ids[$_post->guid])) {
                    $modify_vars['post_id'] = $event_actual_ids[$_post->guid];
                }
            }
        }

        return $modify_vars;
    }

    function fltTeaserReadablePosts($readable_posts, $request, $filtered_request, $args) {
        global $wpdb;
        
        $post_ids = (!empty($args['post_ids'])) ? $args['post_ids'] : [];

        if (!$readable_posts_fake_ids = array_diff($readable_posts, $post_ids)) {
            return $readable_posts;
        }

        $id_csv = implode("','", $post_ids);

        $results = $wpdb->get_results(
            "SELECT ID, guid FROM $wpdb->posts WHERE ID IN ('$id_csv') AND post_type = 'tribe_events'"
        );

        $event_actual_ids = [];

        foreach($results as $row) {
            $event_actual_ids[$row->guid] = $row->ID;
        }

        foreach($readable_posts_fake_ids as $fake_id) {
            if ($_post = get_post($fake_id)) {
                if (!empty($event_actual_ids[$_post->guid])) {
                    $readable_posts []= $event_actual_ids[$_post->guid];
                    $readable_posts = array_diff($readable_posts, [$fake_id]);
                }
            }
        }

        return $readable_posts;
    }
}
