<?php

/**
 * @link              http://photographer.zp.ua
 * @since             1.0
 * @package           The_Photo_Core
 *
 * @wordpress-plugin
 * Plugin Name:       The Photo Core
 * Plugin URI:        http://photographer.zp.ua
 * Description:       Core plugin for The Photo - Premium Photography WordPress Theme
 * Version:           1.0
 * Author:            Andrew Melnik
 * Author URI:        http://photographer.zp.ua
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       the-photo-core
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


function activate_the_photo_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-photo-core-activator.php';
	The_Photo_Core_Activator::activate();
}

function deactivate_the_photo_core() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-the-photo-core-deactivator.php';
	The_Photo_Core_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_the_photo_core' );
register_deactivation_hook( __FILE__, 'deactivate_the_photo_core' );

require plugin_dir_path( __FILE__ ) . 'includes/class-the-photo-core.php';

function run_the_photo_core() {

	$plugin = new The_Photo_Core();
	$plugin->run();

}

run_the_photo_core();

$the_photo = new The_Photo_Core();