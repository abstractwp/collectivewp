<?php
/**
 * Hook the theme's scaffolding template parts into the scaffolding template.
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

/**
 * Register resource cpt.
 */
function cptui_register_my_cpts_resource() {

	/**
	 * Post Type: Resources.
	 */

	$labels = [
		'name'                     => esc_html__( 'Resources', 'wd_s' ),
		'singular_name'            => esc_html__( 'Resource', 'wd_s' ),
		'menu_name'                => esc_html__( 'Resources', 'wd_s' ),
		'all_items'                => esc_html__( 'All resources', 'wd_s' ),
		'add_new'                  => esc_html__( 'Add new resource', 'wd_s' ),
		'add_new_item'             => esc_html__( 'Add new resource', 'wd_s' ),
		'edit_item'                => esc_html__( 'Edit resource', 'wd_s' ),
		'new_item'                 => esc_html__( 'New resource', 'wd_s' ),
		'view_item'                => esc_html__( 'View resource', 'wd_s' ),
		'view_items'               => esc_html__( 'View resources', 'wd_s' ),
		'search_items'             => esc_html__( 'Search resources', 'wd_s' ),
		'not_found'                => esc_html__( 'No resources found', 'wd_s' ),
		'not_found_in_trash'       => esc_html__( 'No resources found in Trash', 'wd_s' ),
		'parent'                   => esc_html__( 'Parent resource', 'wd_s' ),
		'featured_image'           => esc_html__( 'Feature image for resource', 'wd_s' ),
		'set_featured_image'       => esc_html__( 'Set feature image for this resource', 'wd_s' ),
		'remove_featured_image'    => esc_html__( 'Remove feature image for this resource', 'wd_s' ),
		'use_featured_image'       => esc_html__( 'Use as feature image for this resource', 'wd_s' ),
		'archives'                 => esc_html__( 'Resources archive', 'wd_s' ),
		'insert_into_item'         => esc_html__( 'Insert into resource', 'wd_s' ),
		'uploaded_to_this_item'    => esc_html__( 'Uploaded to this resource', 'wd_s' ),
		'filter_items_list'        => esc_html__( 'Filter Resources List', 'wd_s' ),
		'items_list_navigation'    => esc_html__( 'Resources list navigation', 'wd_s' ),
		'items_list'               => esc_html__( 'Resources list', 'wd_s' ),
		'attributes'               => esc_html__( 'Resource attributes', 'wd_s' ),
		'name_admin_bar'           => esc_html__( 'New resource', 'wd_s' ),
		'item_published'           => esc_html__( 'Resource published', 'wd_s' ),
		'item_published_privately' => esc_html__( 'Resource published privately', 'wd_s' ),
		'item_reverted_to_draft'   => esc_html__( 'Resource reverted to draft', 'wd_s' ),
		'item_scheduled'           => esc_html__( 'Resource scheduled', 'wd_s' ),
		'item_updated'             => esc_html__( 'Resource updated', 'wd_s' ),
		'parent_item_colon'        => esc_html__( 'Parent resource', 'wd_s' ),
	];

	$args = [
		'label'                 => esc_html__( 'Resources', 'wd_s' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'rest_namespace'        => 'wp/v2',
		'has_archive'           => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'can_export'            => true,
		'rewrite'               => [
			'slug'       => 'resources',
			'with_front' => true,
		],
		'query_var'             => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-excerpt-view',
		'supports'              => [ 'title', 'editor', 'thumbnail' ],
		'taxonomies'            => [ 'category', 'post_tag', 'resources_topic', 'resources_industry', 'resources_type' ],
		'show_in_graphql'       => false,
	];

	register_post_type( 'resources', $args );
}

add_action( 'init', __NAMESPACE__ . '\cptui_register_my_cpts_resource' );

/**
 * Resource type taxonomy.
 */
function cptui_register_my_taxes_resources_type() {

	/**
	 * Taxonomy: Types.
	 */

	$labels = [
		'name'                       => _x( 'Types', 'Type', 'wd_s' ),
		'singular_name'              => _x( 'Type', 'Type', 'wd_s' ),
		'menu_name'                  => __( 'Types', 'wd_s' ),
		'all_items'                  => __( 'All Types', 'wd_s' ),
		'parent_item'                => __( 'Parent Type', 'wd_s' ),
		'parent_item_colon'          => __( 'Parent Type:', 'wd_s' ),
		'new_item_name'              => __( 'New Type Name', 'wd_s' ),
		'add_new_item'               => __( 'Add New Type', 'wd_s' ),
		'edit_item'                  => __( 'Edit Type', 'wd_s' ),
		'update_item'                => __( 'Update Type', 'wd_s' ),
		'view_item'                  => __( 'View Type', 'wd_s' ),
		'separate_items_with_commas' => __( 'Separate types with commas', 'wd_s' ),
		'add_or_remove_items'        => __( 'Add or remove types', 'wd_s' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'wd_s' ),
		'popular_items'              => __( 'Popular Types', 'wd_s' ),
		'search_items'               => __( 'Search Types', 'wd_s' ),
		'not_found'                  => __( 'Not Found', 'wd_s' ),
		'no_terms'                   => __( 'No items', 'wd_s' ),
		'items_list'                 => __( 'Types list', 'wd_s' ),
		'items_list_navigation'      => __( 'Types list navigation', 'wd_s' ),
	];

	$args = [
		'label'                 => esc_html__( 'Types', 'wd_s' ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => [
			'slug'       => 'type',
			'with_front' => false,
		],
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => false,
		'rest_base'             => 'resources-type',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
		'show_in_graphql'       => false,
	];
	register_taxonomy( 'resources-type', [ 'resources' ], $args );
}
add_action( 'init', __NAMESPACE__ . '\cptui_register_my_taxes_resources_type' );

/**
 * Resource topic taxonomy.
 */
function cptui_register_my_taxes_resources_topic() {

	/**
	 * Taxonomy: Topic.
	 */

	$labels = [
		'name'                       => _x( 'Topics', 'Topics', 'wd_s' ),
		'singular_name'              => _x( 'Topic', 'Topic', 'wd_s' ),
		'menu_name'                  => __( 'Topics', 'wd_s' ),
		'all_items'                  => __( 'All Topics', 'wd_s' ),
		'parent_item'                => __( 'Parent Topic', 'wd_s' ),
		'parent_item_colon'          => __( 'Parent Topic:', 'wd_s' ),
		'new_item_name'              => __( 'New Topic Name', 'wd_s' ),
		'add_new_item'               => __( 'Add New Topic', 'wd_s' ),
		'edit_item'                  => __( 'Edit Topic', 'wd_s' ),
		'update_item'                => __( 'Update Topic', 'wd_s' ),
		'view_item'                  => __( 'View Topic', 'wd_s' ),
		'separate_items_with_commas' => __( 'Separate Topics with commas', 'wd_s' ),
		'add_or_remove_items'        => __( 'Add or remove Topics', 'wd_s' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'wd_s' ),
		'popular_items'              => __( 'Popular Topics', 'wd_s' ),
		'search_items'               => __( 'Search Topics', 'wd_s' ),
		'not_found'                  => __( 'Not Found', 'wd_s' ),
		'no_terms'                   => __( 'No items', 'wd_s' ),
		'items_list'                 => __( 'Topics list', 'wd_s' ),
		'items_list_navigation'      => __( 'Topics list navigation', 'wd_s' ),
	];

	$args = [
		'label'                 => esc_html__( 'Topic', 'wd_s' ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => [
			'slug'       => 'topic',
			'with_front' => false,
		],
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => false,
		'rest_base'             => 'resources-topic',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
		'show_in_graphql'       => false,
	];
	register_taxonomy( 'resources-topic', [ 'resources' ], $args );
}
add_action( 'init', __NAMESPACE__ . '\cptui_register_my_taxes_resources_topic' );

/**
 * Resource industry taxonomy.
 */
function cptui_register_my_taxes_resources_industry() {

	/**
	 * Taxonomy: Industry.
	 */

	$labels = [
		'name'                       => _x( 'Industries', 'Industries', 'wd_s' ),
		'singular_name'              => _x( 'Industry', 'Industry', 'wd_s' ),
		'menu_name'                  => __( 'Industries', 'wd_s' ),
		'all_items'                  => __( 'All Industries', 'wd_s' ),
		'parent_item'                => __( 'Parent Industry', 'wd_s' ),
		'parent_item_colon'          => __( 'Parent Industry:', 'wd_s' ),
		'new_item_name'              => __( 'New Industry Name', 'wd_s' ),
		'add_new_item'               => __( 'Add New Industry', 'wd_s' ),
		'edit_item'                  => __( 'Edit Industry', 'wd_s' ),
		'update_item'                => __( 'Update Industry', 'wd_s' ),
		'view_item'                  => __( 'View Industry', 'wd_s' ),
		'separate_items_with_commas' => __( 'Separate Industries with commas', 'wd_s' ),
		'add_or_remove_items'        => __( 'Add or remove Industries', 'wd_s' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'wd_s' ),
		'popular_items'              => __( 'Popular Industries', 'wd_s' ),
		'search_items'               => __( 'Search Industries', 'wd_s' ),
		'not_found'                  => __( 'Not Found', 'wd_s' ),
		'no_terms'                   => __( 'No items', 'wd_s' ),
		'items_list'                 => __( 'Industries list', 'wd_s' ),
		'items_list_navigation'      => __( 'Industries list navigation', 'wd_s' ),
	];

	$args = [
		'label'                 => esc_html__( 'industry', 'wd_s' ),
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'query_var'             => true,
		'rewrite'               => [
			'slug'       => 'industry',
			'with_front' => false,
		],
		'show_admin_column'     => false,
		'show_in_rest'          => true,
		'show_tagcloud'         => false,
		'rest_base'             => 'resources-industry',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
		'rest_namespace'        => 'wp/v2',
		'show_in_quick_edit'    => false,
		'sort'                  => false,
		'show_in_graphql'       => false,
	];
	register_taxonomy( 'resources-industry', [ 'resources' ], $args );
}
add_action( 'init', __NAMESPACE__ . '\cptui_register_my_taxes_resources_industry' );
