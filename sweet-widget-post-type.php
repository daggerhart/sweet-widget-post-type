<?php
/*
Plugin Name:       Sweet Widget Post Type
Plugin URI:        https://github.com/daggerhart/sweet-widget-post-type
GitHub Plugin URI: https://github.com/daggerhart/sweet-widget-post-type
GitHub Branch:     master
Description:       Provide a new post type named Sweet Widget (sw_widget) and a new reusable Widget
Contributors:      daggerhart
Author:            Jonathan Daggerhart
Author URI:        http://www.daggerhart.com
Version:           0.0.1
*/

if ( !defined('ABSPATH') ) die();

if ( ! class_exists('Sweet_Widgets_Post_Type') ) :

// initialize plugin on the init action
add_action( 'init', array( 'Sweet_Widgets_Post_Type', 'register'), 0 );

/**
 * Class Sweet_Widgets_Post_Type
 * @link https://github.com/daggerhart/sweet-widget-post-type
 */
class Sweet_Widgets_Post_Type {

	public $version = '0.0.1';

	/**
	 * Instantiate and hook plugin into WordPress
	 */
	static function register(){
		require_once __DIR__ . '/includes/class-sweet-widget-post-type-widget.php';

		$plugin = new self();

		$plugin->register_sw_widget_post_type();

		add_action( 'widgets_init', array( $plugin, 'widgets_init' ) );
	}

	/**
	 * Register the WP_Widget
	 */
	function widgets_init(){
		register_widget( 'Sweet_Widget_Post_Type_Widget' );
	}

	/**
	 * sw_widget Post Type
	 */
	function register_sw_widget_post_type(){
		$post_type = array(
			'labels'      => array(
				'name'               => _x( 'Sweet Widgets', 'post type general name' ),
				'singular_name'      => _x( 'Sweet Widget', 'post type singular name' ),
				'menu_name'          => _x( 'Sweet Widgets', 'admin menu' ),
				'name_admin_bar'     => _x( 'Sweet Widget', 'add new on admin bar' ),
				'add_new'            => _x( 'Add New', 'sweet widget' ),
				'add_new_item'       => __( 'Add New Sweet Widget' ),
				'new_item'           => __( 'New Sweet Widget' ),
				'edit_item'          => __( 'Edit Sweet Widget' ),
				'view_item'          => __( 'View Sweet Widget' ),
				'all_items'          => __( 'All Sweet Widgets' ),
				'search_items'       => __( 'Search Sweet Widgets' ),
				'parent_item_colon'  => __( 'Parent Sweet Widgets:' ),
				'not_found'          => __( 'No sweet widgets found.' ),
				'not_found_in_trash' => __( 'No sweet widgets found in Trash.' )
			),
			'description'        => __( 'An html snippet able to be displayed within a Widget.' ),
			'show_ui'            => true,
			'show_in_nav_menus'  => false,
			'show_in_menu'       => true,
			'show_in_admin_bar'  => true,
			'public'             => false,
			'publicly_queryable' => false,
			'query_var'          => false,
			'rewrite'            => false,
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array(
				'title',
				'editor',
				'thumbnail',
				'revisions'
			),
			'capabilities'       => array(
				'publish_posts'       => 'edit_theme_options',
				'edit_posts'          => 'edit_theme_options',
				'edit_others_posts'   => 'edit_theme_options',
				'delete_posts'        => 'edit_theme_options',
				'delete_others_posts' => 'edit_theme_options',
				'read_private_posts'  => 'edit_theme_options',
				'edit_post'           => 'edit_theme_options',
				'delete_post'         => 'edit_theme_options',
				'read_post'           => 'read_post',
			),
		);

		register_post_type( 'sw_widget', $post_type );
	}
}

endif; // class exists
