<?php

/**
 * Provide a public-facing view for the plugin
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

$default_options = Divi_Accessibility_Admin::get_options_list();

echo '<style>';

if ( $this->can_load( 'dropdown_keyboard_navigation' ) ) {

?>
.nav li.et-hover > ul,
.menu li.et-hover > ul {
	visibility: visible !important;
	opacity: 1 !important; <?php // Necessary for tabbing through the primary top nav - Divi only has .et-hover containing visibility. ?>
}
.da11y-submenu-show {
	visibility: visible !important;
}
<?php

} // End if().

if ( $this->can_load( 'keyboard_navigation_outline' ) ) {

?>
.keyboard-outline {
	<?php

	$outline_color = $default_options['outline_color'];

	if ( isset( $this->settings['outline_color'] ) ) {
		$outline_color = $this->settings['outline_color'];
	}

	?>
	outline: <?php echo $outline_color; ?> solid 2px;
	-webkit-transition: none !important;
	transition: none !important;
}
button:active.keyboard-outline,
button:focus.keyboard-outline,
input:active.keyboard-outline,
input:focus.keyboard-outline,
a[role="tab"].keyboard-outline {
	outline-offset: -5px;
}
.et-search-form input:focus.keyboard-outline {
	padding-left: 15px;
	padding-right: 15px;
}
.et_pb_tab {
	-webkit-animation: none !important;
	animation: none !important;
}
<?php

} // End if().

if ( $this->can_load( 'screen_reader_text' ) ) {

?>
.et_pb_contact_form_label,
.widget_search .screen-reader-text,
.et_pb_search .screen-reader-text {
	display: block !important; <?php // Reverse Divi adding display: none to screen reader text ?>
}
.da11y-screen-reader-text,
.et_pb_contact_form_label,
.widget_search .screen-reader-text,
.et_pb_search .screen-reader-text {
	clip: rect(1px, 1px, 1px, 1px);
	position: absolute !important;
	height: 1px;
	width: 1px;
	overflow: hidden;
	text-shadow: none;
	text-transform: none;
	letter-spacing: normal;
	line-height: normal;
	font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
	font-size: 1em;
	font-weight: 600;
	-webkit-font-smoothing: subpixel-antialiased;
}
.da11y-screen-reader-text:focus {
	background: #f1f1f1;
	color: #00547A;
	-webkit-box-shadow: 0 0 2px 2px rgba(0,0,0,.6);
	box-shadow: 0 0 2px 2px rgba(0,0,0,.6);
	clip: auto !important;
	display: block;
	height: auto;
	left: 5px;
	padding: 15px 23px 14px;
	text-decoration: none;
	top: 7px;
	width: auto;
	z-index: 1000000; <?php // Above WP toolbar. ?>
}
<?php

} // End if().

echo '</style>';
