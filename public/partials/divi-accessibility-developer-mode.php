<?php

/**
 * Public-facing view for developer mode.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://campuspress.com
 * @since      1.0.0
 *
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/public/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once DA11Y_PATH . 'admin/class-divi-accessibility-admin.php';
$options = Divi_Accessibility_Admin::get_options_list();


echo '<script type="text/javascript">';
echo 'console.log("\n%cDivi Accessibility Version ' . $this->version . '", "color: #FFF; background: #974DF3; padding: 3px; font-size: 15px;");';

foreach ( $options as $key => $option ) {

	if ( 'outline_color' == $key ) {
		$output = $this->settings['outline_color'];
	} elseif ( $this->can_load( $key ) ) {
		$output = 1;
	} else {
		$output = 0;
	}

	if ( 'outline_color' == $key ) {
		echo 'console.log("%c' . $output . ' ← ' . $key . '", "color:' . $output . ';");';
	} else {
		echo 'console.log("' . $output . ' ←", "' . $key . '");';
	}
} // End foreach().

echo 'console.log("😎\n\n");';
echo '</script>';
