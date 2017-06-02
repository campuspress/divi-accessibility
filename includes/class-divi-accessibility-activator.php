<?php

/**
 * Fired during plugin activation
 *
 * @link       https://campuspress.com
 * @since      1.0.0
 *
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/includes
 * @author     Joseph Fusco <hello@josephfus.co>
 */
class Divi_Accessibility_Activator {

	/**
	 * Declare default plugin settings.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-divi-accessibility-admin.php';

		$options = Divi_Accessibility_Admin::get_options_list();

		update_option( 'divi_accessibility_options', $options );

	}

}
