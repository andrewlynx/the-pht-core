<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    The_Photo_Core
 * @subpackage The_Photo_Core/public
 * @author     Andrew Melnik
 */
class The_Photo_Core_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/the-photo-core-public.css', array(), $this->version, 'all' );
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/the-photo-core-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function photosession_post_type()	{
		register_post_type('photosessions', // Register Custom Post Type
			array(
			'labels' => array(
				'name' => __('Photosessions', 'the_photo'), // Rename these to suit
				'singular_name' => __('Photosession', 'the_photo'),
				'add_new' => __('Add New', 'the_photo'),
				'add_new_item' => __('Add New Photosession', 'the_photo'),
				'edit' => __('Edit', 'the_photo'),
				'edit_item' => __('Edit Photosession', 'the_photo'),
				'new_item' => __('New Photosession', 'the_photo'),
				'view' => __('View', 'the_photo'),
				'view_item' => __('View Photosession', 'the_photo'),
				'search_items' => __('Search Photosessions', 'the_photo'),
				'not_found' => __('No Photosessions found', 'the_photo'),
				'not_found_in_trash' => __('No Photosessions found in Trash', 'the_photo')
			),
			'public' => true,
			'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
			'has_archive' => true,
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			), // Go to Dashboard Custom The Photo post for supports
			'can_export' => true, // Allows export in Tools > Export
			'taxonomies' => array(
				'post_tag',
				'category'
			) // Add Category and Post Tags support
		));
		
		register_post_type('albums', // Register Custom Post Type
			array(
			'labels' => array(
				'name' => __('Albums', 'the_photo'), // Rename these to suit
				'singular_name' => __('Album', 'the_photo'),
				'add_new' => __('Add New', 'the_photo'),
				'add_new_item' => __('Add New Album', 'the_photo'),
				'edit' => __('Edit', 'the_photo'),
				'edit_item' => __('Edit Album', 'the_photo'),
				'new_item' => __('New Album', 'the_photo'),
				'view' => __('View', 'the_photo'),
				'view_item' => __('View Album', 'the_photo'),
				'search_items' => __('Search Albums', 'the_photo'),
				'not_found' => __('No Albums found', 'the_photo'),
				'not_found_in_trash' => __('No Albums found in Trash', 'the_photo')
			),
			'public' => true,
			'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
			'has_archive' => true,
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			), // Go to Dashboard Custom The Photo post for supports
			'can_export' => true, // Allows export in Tools > Export
			'taxonomies' => array(
				'post_tag',
				'category',
				
			) // Add Category and Post Tags support
		));
	}
	
	//add_action('init', 'the_photo_photosession_post_type'); // Add our The Photo Custom Post Type
	
	public function set_admin_bar() {
		if (!is_super_admin()) {
			return false;
		} 
		return true;
	}
}
