<?php 
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    The_Photo_Core
 * @subpackage The_Photo_Core/includes
 * @author     Andrew Melnik
 */
class The_Photo_Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      The_Photo_Core_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'the-photo-core';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();	

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - The_Photo_Core_Loader. Orchestrates the hooks of the plugin.
	 * - The_Photo_Core_i18n. Defines internationalization functionality.
	 * - The_Photo_Core_Admin. Defines all hooks for the admin area.
	 * - The_Photo_Core_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
	
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-the-photo-core-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-the-photo-core-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-the-photo-core-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-the-photo-core-public.php';
		
		/**
		 * The class responsible for defining all functions that can be used in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-the-photo-core-public-functions.php';

		$this->loader = new The_Photo_Core_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the The_Photo_Core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new The_Photo_Core_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

 		$plugin_admin = new The_Photo_Core_Admin( $this->get_plugin_name(), $this->get_version() );
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_tab' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'the_photo_settings_init');
		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		/*Only for dashboard*/	
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

 		$plugin_public = new The_Photo_Core_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_public, 'photosession_post_type');
		//$this->loader->add_action( 'init', $plugin_public, 'create_tax');
		
		//$this->loader->add_action( 'init', $plugin_public, 'create_type_from_db');
		
		//$this->loader->add_action( 'wp_enqueue_scripts', 	$plugin_public, 'enqueue_styles' );
		//$this->loader->add_action( 'wp_enqueue_scripts', 	$plugin_public, 'enqueue_scripts' );
		
		$this->loader->add_filter( 'show_admin_bar', $plugin_public, 'set_admin_bar', 11);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    The_Photo_Core_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	public function get_meta_fields(){
		$options = get_option('the_photo_options_metadata');
		$out = array();
		foreach($options as $number => $value){
			$out[] = $value;
		}
		return $out;
	}
	
	public function the_photo_attachment_fields($file, $post_id){
		$out = '';
		@$meta = exif_read_data($file);		
		$fields = $this->get_meta_fields();
		
		if(!array($fields) OR !$meta){
			return false;
		}
		
		foreach($fields as $field){
			
			$fieldname = strtolower(preg_replace('/\s+/', '-', $field));
			$value = get_post_meta( $post_id, $fieldname, true );
			if(!$value){				
				switch($field){
					case 'Camera':
						if (isset($meta['Model'])) {
							$value = $meta['Model'];							
						}		
						break;				
					case 'Exposure Time':	
						if (isset($meta['ExposureTime'])) {				
							$values = $meta['ExposureTime'];
							$divider = explode('/',$values);
							$value = '1/' . $divider[1]/$divider[0] . ' s';
						}	
						break;
					case 'Aperture':
						if (isset($meta['FNumber'])) {	
							$values = $meta['FNumber'];
							$divider = explode('/',$values);
							$value = 'F/' . $divider[0]/$divider[1];
						}	
						break;
					case 'ISO':
						if (isset($meta['ISOSpeedRatings'])) {	
							$value = $meta['ISOSpeedRatings'];
						}
						break;
					case 'Focal Length':
						if (isset($meta['FocalLength'])) {	
							$values = $meta['FocalLength'];
							$divider = explode('/',$values);
							$value = $divider[0]/$divider[1] . ' mm';
						}	
						break;						
				}
			}
			
			$out[$field] = $value;
		}
		
		return $out;
	}

}
