<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    The_Photo_Core
 * @subpackage The_Photo_Core/includes
 * @author     Andrew Melnik
 */
class The_Photo_Core_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		//Add image fields
		add_filter( 'attachment_fields_to_edit', array($this, 'the_photo_attachment_field_credit'), 10, 2 ); 
		add_filter( 'attachment_fields_to_save', array($this, 'the_photo_attachment_field_credit_save'), 10, 2 );
		
		// Create option if not exist
		$options = get_option('the_photo_options_metadata');
		if(!$options){
			$options = array(
				1 => 'Camera',
				2 => 'Exposure Time',
				3 => 'Aperture',
				4 => 'ISO',
				5 => 'Focal Length'
			);
			update_option('the_photo_options_metadata', $options); 
		}
		
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		//wp_enqueue_style( $this->plugin_name . 'font-awesome', plugin_dir_url( __FILE__ ) . 'css/fonts/font-awesome.min.css', array(), $this->version, 'all' );
		//wp_enqueue_style( $this->plugin_name,  plugin_dir_url( __FILE__ ) . 'css/the-photo-core-admin.css',  array(), $this->version, 'all' );
		
		$currTheme = wp_get_theme();
		if (class_exists('WPBakeryVisualComposerAbstract') && ($currTheme->template == 'the-photo')) { 
			//wp_enqueue_style($this->plugin_name.'visual-composer', plugin_dir_url( __FILE__ ) .'shortcodes/execute-shortcodes.css', false, null, 'all');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script ('jquery');
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/scripts.js', array( 'jquery' ), $this->version, true );			
		wp_enqueue_script ( $this->plugin_name);
			
		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
	}

	
	/**
	* Add plugin and theme settings tab in admin area.
	*
	* @since    1.0.0
	*/	
	public function add_settings_tab() {
	
		add_menu_page( 
			'The Photo Options',
			'The Photo Options',
			'manage_options',
			'the-photo-options',
			array($this, 'option_page')
		);
				
	}
	
	public function the_photo_settings_init(){
		
		register_setting('the_photo_options_metadata', 'the_photo_options_metadata');
	 
		// register a new section in the "the_photo" page
		add_settings_section(
			'the_photo_section_metadata_settings',
			__('Photo exif settings.', 'the_photo'),
			array($this, 'the_photo_section_metadata_settings_cb'),
			'the_photo_options_metadata'
		);
	 
		// register a new field in the "the_photo_section_metadata_settings" section, inside the "the_photo" page
		add_settings_field(
			'the_photo_options_metadata', 
			__('Metadata', 'the_photo'),
			array($this, 'the_photo_field_metadata_cb'),
			'the_photo_options_metadata',
			'the_photo_section_metadata_settings',
			[
				'label_for'         => 'the_photo_options_metadata',
				'class'             => 'the_photo_row',
				'the_photo_custom_data' => 'custom',
			]
		);
	}
	
	public function the_photo_field_metadata_cb($args){

		// get the value of the setting we've registered with register_setting()
		$max = '';
		$options = get_option('the_photo_options_metadata');

		// output the field 
		foreach($options as $number => $value){
		?>
			<p>
				<input type="text" placeholder="Input metadata field" name="the_photo_options_metadata[<?php echo $number ?>]" value="<?php echo $value ?>">
				<a href="#" class="option-remove" data-option="the_photo_options_metadata" data-remove="<?php echo $number ?>"> X </a>
			</p>
		<?php
			$max = $number;
		}
		?>
			<p><a href="#" class="option-add" data-option="the_photo_options_metadata" data-add="<?php echo ++$max ?>"> Add Item </a></p>
		<?php

	}
	
	public function the_photo_section_metadata_settings_cb($args){
		?>
		<p id="<?= esc_attr($args['id']); ?>"><?= esc_html__('Edit metadata to display for each photo. Fields "Camera", "Exposure Time", "Aperture", "ISO" and "Focal Length" are predefined, they are taken from image EXIF if they exist. You can add or edit this or other fields manually on sigle photo in Media tab', 'the_photo'); ?></p>

		<?php
	}
	
	public function option_page() {
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}
		
		// check if the user have submitted the settings
		// wordpress will add the "settings-updated" $_GET parameter to the url
		if (isset($_GET['settings-updated'])) {
			// add settings saved message with the class of "updated"
			add_settings_error('the_photo_messages', 'the_photo_message', __('Settings Saved', 'the_photo'), 'updated');
		}
	 
		// show error/update messages
		settings_errors('the_photo_messages');
		?>
		<div class="wrap">
			<h1><?= esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "the_photo"
				settings_fields('the_photo_options_metadata');
				// output setting sections and their fields
				// (sections are registered for "the_photo", each field is registered to a specific section)
				do_settings_sections('the_photo_options_metadata');
				// output save settings button
				submit_button('Save Settings');
				?>
			</form>
		</div>
		<?php
	}	
	
	public function the_photo_attachment_field_credit( $form_fields, $post ) {
		
		global $the_photo;	
		$file = wp_get_attachment_url($post->ID);

		$fields = $the_photo->the_photo_attachment_fields($file, $post->ID);
		if($fields){
			foreach ($fields as $field => $value){
				
				$fieldname = strtolower(preg_replace('/\s+/', '-', $field));
				
				$form_fields[$fieldname] = array(
					'label' => $field,
					'input' => 'text',
					'value' => $value,
				);
				
			}
		}

		return $form_fields;
	}



	public function the_photo_attachment_field_credit_save( $post, $attachment ) {
		
		$options = get_option('the_photo_options_metadata');
		foreach($options as $number => $field){
			$fieldname = strtolower(preg_replace('/\s+/', '-', $field));
				if( isset( $attachment[$fieldname] ) )
					update_post_meta( $post['ID'], $fieldname, $attachment[$fieldname] );
		}
		
		return $post;
	}
	
}
