<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://campuspress.com
 * @since             1.2.0
 * @package           Divi_Accessibility
 *
 * @wordpress-plugin
 * Plugin Name:       Divi Accessibility
 * Plugin URI:        https://wordpress.org/plugins/accessible-divi/
 * Description:       Improve Divi accessibility in accordance with WCAG 2.0 guidelines.
 * Version:           2.0.0
 * Author:            CampusPress
 * Author URI:        https://campuspress.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       divi-accessibility
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'DA11Y_VERSION' ) ) {
	define( 'DA11Y_VERSION', '2.0.0' );
}

// Used for referring to the plugin file or basename.
if ( ! defined( 'DA11Y_FILE' ) ) {
	define( 'DA11Y_FILE', plugin_basename( __FILE__ ) );
}

// Used for referring to the plugin base path.
if ( ! defined( 'DA11Y_PATH' ) ) {
	define( 'DA11Y_PATH', plugin_dir_path( __FILE__ ) );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-divi-accessibility-activator.php
 */
function activate_divi_accessibility() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-divi-accessibility-activator.php';
	Divi_Accessibility_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_divi_accessibility' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-divi-accessibility.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_divi_accessibility() {

	$plugin = new Divi_Accessibility( DA11Y_VERSION );
	$plugin->run();

}
run_divi_accessibility();
