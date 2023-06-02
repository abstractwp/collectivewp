<?php
namespace PublishPress\Permissions;

class TeaserHooksAdmin
{
    function __construct()
    {
        add_action('presspermit_menu_handler', [$this, 'actMenuHandler']);
        add_action('presspermit_permissions_menu', [$this, 'act_permissions_menu'], 10, 2);

        add_action( 'wp_ajax_pp_search_posts', [$this, 'searchPosts'] );
        add_action( 'wp_ajax_pp_search_terms', [$this, 'searchTerms'] );

        if ('presspermit-posts-teaser' == presspermitPluginPage()) {
            add_action('admin_enqueue_scripts', function() {
	            $urlpath = plugins_url('', PRESSPERMIT_TEASER_FILE);
	
	            wp_enqueue_style('presspermit-settings', $urlpath . '/common/css/settings.css', [], PRESSPERMIT_VERSION);
	
	            wp_enqueue_script('presspermit-select2', $urlpath . "/common/libs/select2/select2.min.js", ['jquery'], PRESSPERMIT_TEASER_VERSION, false);
	
	            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';
	            wp_enqueue_script('presspermit-teaser-settings', $urlpath . "/common/js/settings{$suffix}.js", ['jquery','presspermit-select2'], PRESSPERMIT_TEASER_VERSION, false);
	
	            // Nonce for ajax requests
	            wp_localize_script(
	    			'presspermit-teaser-settings',
	    			'presspermitTeaser',
	    			[
	                    'url' => admin_url( 'admin-ajax.php' ),
	                    'nonce' => wp_create_nonce( 'pp_search_content' ),
	                    'strings' => [
	                        'select_a_page' => __( 'Select a page', 'presspermit-pro' ),
	                        'select_terms' => __( 'Select terms', 'presspermit-pro' )
	                    ]
	                ]
	    	 	);
	
	            wp_enqueue_style('presspermit-teaser-settings', $urlpath . '/common/css/settings.css', [], PRESSPERMIT_TEASER_VERSION);
	            wp_enqueue_style('presspermit-select2', $urlpath . '/common/libs/select2/select2.min.css', [], PRESSPERMIT_TEASER_VERSION);
            });
        }
    }

    function actMenuHandler($pp_page)
    {
        static $done;

        if (!empty($done)) {
            return;
        }

        $done = true;

        $pp_page = presspermitPluginPage();

        if (in_array($pp_page, ['presspermit-posts-teaser'], true)) {

            $class_name = str_replace('-', '', ucwords( str_replace('presspermit-', '', $pp_page), '-') );
            $load_class = "\\PublishPress\Permissions\\Teaser\\UI\\$class_name";

            if (!class_exists($load_class)) {
                require_once(PRESSPERMIT_TEASER_CLASSPATH . "/UI/{$class_name}.php");
            }

            new $load_class();
        }
    }

    function act_permissions_menu($options_menu, $handler)
    {
        // If we are disabling native custom statuses in favor of PublishPress,
        // but PP Collaborative Editing is not active, hide this menu item.
        add_submenu_page(
            $options_menu,
            __('Teaser', 'presspermit-pro'),
            __('Teaser', 'presspermit-pro'),
            'read',
            'presspermit-posts-teaser',
            $handler
        );
    }

    function searchPosts()
	{
		if ( ! wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'pp_search_content' ) ) {
	         wp_send_json( 'Error', 400 );
	    }

        global $wpdb;
        $results = $wpdb->get_results(
            'SELECT ID, post_title FROM ' . $wpdb->prefix . 'posts
            WHERE post_type = "page" AND post_status = "publish"
            AND post_title like \'%' . sanitize_text_field( $_GET['search'] ) . '%\'
            ORDER BY post_title LIMIT 10'
        );

        wp_send_json( $results );
	}

    function searchTerms()
	{
		if ( ! wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'pp_search_content' ) ) {
	         wp_send_json( 'Error', 400 );
	    }

        $results = get_terms(
            [
                'name__like' => sanitize_text_field( $_GET['search'] ),
                'taxonomy' => (!empty($_GET['taxonomy'])) ? $_GET['taxonomy'] : 'category',
                'hide_empty' => false,
                'orderby' => 'name'
            ]
        );

        wp_send_json( $results );
	}
}
