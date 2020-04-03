<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://campuspress.com
 * @since      1.0.0
 *
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/public
 */
class Divi_Accessibility_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $da11y;

	/**
	 * The prefix for the plugins's options.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $da11y_options;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $version;

	/**
	 * The plugin settings.
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       array
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $da11y          The name of this plugin.
	 * @param    string    $da11y_options  The prefix for the plugin's options.
	 * @param    string    $version        The version of this plugin.
	 * @param    array     $settings       The plugin settings.
	 */
	public function __construct( $da11y, $da11y_options, $version, $settings ) {

		$this->da11y         = $da11y;
		$this->da11y_options = $da11y_options;
		$this->version       = $version;
		$this->settings      = $settings;

	}

	/**
	 * Render the gemerated CSS for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function embedded_styles() {
		//include_once 'partials/divi-accessibility-embedded-css.php';
	}

	/**
	 * Render the gemerated JS for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function embedded_scripts() {
		//include_once 'partials/divi-accessibility-embedded-js.php';
	}

	/**
	 * Remove Divi viewport meta since we want to load our own.
	 *
	 * @since    1.0.2
	 */
	public function remove_divi_viewport_meta() {
		remove_action( 'wp_head', 'et_add_viewport_meta' );
	}

	/**
	 * Allow users to pinch and zoom divi theme.
	 *
	 * @since    1.0.2
	 */
	public function accessible_viewport_meta() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'divi-accessibility-da11y',
			plugin_dir_url( __FILE__ ) . 'js/da11y.js',
			array( 'jquery' ),
			$this->version, true
		);
		if ( $this->can_load( 'keyboard_navigation_outline' ) ) {
			$default_options = Divi_Accessibility_Admin::get_options_list();
			$outline_color = $default_options['outline_color'];
			if ( isset( $this->settings['outline_color'] ) ) {
				$outline_color = $this->settings['outline_color'];
			}
			wp_localize_script( 'divi-accessibility-da11y', '_da11y', array(
				'outline_color' => esc_js( $outline_color ),
			) );
		}

		$scripts = array(
			'dropdown_keyboard_navigation',
			'skip_navigation_link',
			'keyboard_navigation_outline',
			'focusable_modules',
			'fix_labels',
			'aria_support',
			'aria_hidden_icons',
			'aria_mobile_menu',
		);
		foreach ( $scripts as $name ) {
			wp_add_inline_script(
				'divi-accessibility-da11y',
				$this->get_resource_data( $name, 'js' )
			); //@TODO optionally enqueue
		}

		$styles = array(
			'dropdown_keyboard_navigation',
			'keyboard_navigation_outline',
			'screen_reader_text',
		);
		foreach ( $styles as $name ) {
			wp_add_inline_style(
				reset( wp_styles()->queue ),
				$this->get_resource_data( $name, 'css' )
			); //@TODO optionally enqueue
		}

		if ( true == $this->can_load_tota11y() ) {
			wp_enqueue_script(
				'divi-accessibility-tota11y',
				plugin_dir_url( __FILE__ ) . 'js/tota11y.min.js',
				array( 'jquery' ),
				$this->version, false
			);
		}
	}

	public function get_resource_data( $name, $type ) {
		if ( ! $this->can_load( $name ) ) {
			return false;
		}
		$file = $this->get_resource_name( $name, $type );
		return is_readable( $file )
			? file_get_contents( $file )
			: false;
	}

	public function get_resource_name( $name, $type ) {
		$root = trailingslashit( DA11Y_PATH ) . 'public/partials/' . $type;
		$minified = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG
			? ''
			: '.min';
		return trailingslashit( $root ) .
			sanitize_file_name( $name ) .
			$minified .
			'.' . $type;
	}

	/**
	 * Check if we have permission to load tota11y.
	 *
	 * @since     1.0.0
	 * @return    boolean
	 */
	public function can_load_tota11y() {

		$settings = $this->settings;

		if ( isset( $settings['tota11y'] ) ) {
			$tota11y = $settings['tota11y'];
		}

		if ( current_user_can( 'manage_options' ) && ( true == $tota11y ) ) {
			return true;
		}

	}

	/**
	 * Check if we have permission to load a checkbox option.
	 *
	 * @since     1.0.0
	 * @param     string     $option
	 * @return    boolean
	 */
	public function can_load( $option ) {

		$can_load = false;

		$settings = $this->settings;
		
		if ( isset( $settings[ $option ] ) && 1 == $settings[ $option ] ) {
			$can_load = true;
		}

		return apply_filters( 'divi_accessibility_can_load', $can_load, $option );

	}

	/**
	 * Prevent WordPress from adding a unique ID from menu list items.
	 * Because Divi uses js to build the mobile navigation menu from the main navigation links,
	 * unique ID's are cloned, causing issues with accessibility & validation.
	 *
	 * @since     1.2.0
	 */
	public function remove_duplicate_menu_ids() {

		if ( $this->can_load( 'fix_duplicate_menu_ids' ) ) {
			add_filter( 'nav_menu_item_id', '__return_null', 1000 );
		}

	}

	/**
	 * Log plugin info to console for admin users.
	 *
	 * @since     1.0.0
	 */
	public function developer_mode() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( $this->can_load( 'developer_mode' ) ) {
			include_once 'partials/divi-accessibility-developer-mode.php';
		}

	}

}
