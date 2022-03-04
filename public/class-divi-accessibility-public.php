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

	const TYPE_JS  = 'js';
	const TYPE_CSS = 'css';

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
	 * @param    string $da11y          The name of this plugin.
	 * @param    string $da11y_options  The prefix for the plugin's options.
	 * @param    string $version        The version of this plugin.
	 * @param    array  $settings       The plugin settings.
	 */
	public function __construct( $da11y, $da11y_options, $version, $settings ) {

		$this->da11y         = $da11y;
		$this->da11y_options = $da11y_options;
		$this->version       = $version;
		$this->settings      = $settings;

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
	 * Enqueue and bootstrap the public facing resources.
	 */
	public function setup_scripts_and_styles() {
		wp_enqueue_script(
			'divi-accessibility-da11y',
			plugin_dir_url( __FILE__ ) . 'js/da11y.js',
			array( 'jquery' ),
			$this->version,
			true
		);
		wp_localize_script(
			'divi-accessibility-da11y',
			'_da11y',
			$this->get_public_data()
		);

		foreach ( $this->get_script_resources() as $name ) {
			$this->add_resource( 'divi-accessibility-da11y', $name, self::TYPE_JS );
		}

		$root_style = reset( wp_styles()->queue );
		foreach ( $this->get_style_resources() as $name ) {
			$this->add_resource( $root_style, $name, self::TYPE_CSS );
		}

		if ( true == $this->can_load_tota11y() ) {
			wp_enqueue_script(
				'divi-accessibility-tota11y',
				plugin_dir_url( __FILE__ ) . 'js/tota11y.min.js',
				array( 'jquery' ),
				$this->version,
				false
			);
		}
	}

	/**
	 * Gets public data exposed to enqueued JS resources
	 *
	 * @return array
	 */
	public function get_public_data() {
		$data     = array(
			'version' => $this->version,
		);
		$defaults = Divi_Accessibility_Admin::get_options_list();
		if ( $this->is_in_developer_mode() ) {
			$data['options'] = array_merge(
				$defaults,
				(array) $this->settings
			);
		}
		if ( $this->can_load( 'keyboard_navigation_outline' ) ) {
			$data['active_outline_color'] = esc_js(
				isset( $this->settings['outline_color'] )
					? $this->settings['outline_color']
					: $defaults['outline_color']
			);
		}
		if ( $this->can_load( 'skip_navigation_link' ) ) {
			$data['skip_navigation_link_text'] = __( 'Skip to content', 'divi-accessibility' );
		}
		return $data;
	}

	/**
	 * Returns a list of all known script resources
	 *
	 * @return array
	 */
	public function get_script_resources() {
		return array(
			'dropdown_keyboard_navigation',
			'skip_navigation_link',
			'keyboard_navigation_outline',
			'focusable_modules',
			'fix_labels',
			'aria_support',
			'aria_hidden_icons',
			'aria_mobile_menu',
			'developer_mode',
		);
	}

	/**
	 * Returns a list of all known style resources
	 *
	 * @return array
	 */
	public function get_style_resources() {
		return array(
			'dropdown_keyboard_navigation',
			'keyboard_navigation_outline',
			'screen_reader_text',
			'underline_urls',
			'underline_urls_not_title',
		);
	}

	/**
	 * Adds resource
	 *
	 * @param string $hook Resource parent.
	 * @param string $name Resource name.
	 * @param string $type Resource type (css|js).
	 */
	public function add_resource( $hook, $name, $type ) {
		if ( ! $this->can_load( $name ) ) {
			return false;
		}
		if ( self::TYPE_JS === $type ) {
			return $this->will_enqueue( $type )
				? wp_enqueue_script(
					"{$hook}-{$name}",
					$this->get_resource_url( $name, $type ),
					array( $hook ),
					$this->version,
					true
				)
				: wp_add_inline_script( $hook, $this->get_resource_data( $name, $type ) );
		}
		if ( self::TYPE_CSS === $type ) {
			return $this->will_enqueue( $type )
				? wp_enqueue_style(
					"{$hook}-{$name}",
					$this->get_resource_url( $name, $type ),
					array( $hook ),
					$this->version
				)
				: wp_add_inline_style( $hook, $this->get_resource_data( $name, $type ) );
		}
		return false;
	}

	/**
	 * Whether resources of certain type are to be be enqueued or loaded inline
	 *
	 * @param string $type Resource type (css|js).
	 *
	 * @return bool
	 */
	public function will_enqueue( $type ) {
		$enqueue = apply_filters( 'divi_accessibility_enqueue', false );
		return apply_filters(
			'divi_accessibility_enqueue_type',
			$enqueue,
			$type
		);
	}

	/**
	 * Load resource contents
	 *
	 * @param string $name Resource name.
	 * @param string $type Resource type (css|js).
	 *
	 * @return string
	 */
	public function get_resource_data( $name, $type ) {
		if ( ! $this->is_known_type( $type ) ) {
			return '';
		}
		$file = $this->get_resource_path( $name, $type );
		return is_readable( $file )
			? file_get_contents( $file )
			: '';
	}

	/**
	 * Gets resource FS path
	 *
	 * @param string $name Resource name.
	 * @param string $type Resource type (css|js).
	 *
	 * @return string
	 */
	public function get_resource_path( $name, $type ) {
		if ( ! $this->is_known_type( $type ) ) {
			return '';
		}
		$root     = trailingslashit( DA11Y_PATH ) . 'public/partials/' . $type;
		$debug    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$minified = $this->is_in_developer_mode() || $debug
			? ''
			: '.min';
		return trailingslashit( $root ) .
			sanitize_file_name( $name ) .
			$minified .
			'.' . $type;
	}

	/**
	 * Gets resource URL
	 *
	 * @param string $name Resource name.
	 * @param string $type Resource type (css|js).
	 *
	 * @return string
	 */
	public function get_resource_url( $name, $type ) {
		if ( ! $this->is_known_type( $type ) ) {
			return '';
		}
		$root     = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'partials/' . $type;
		$debug    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$minified = $this->is_in_developer_mode() || $debug
			? ''
			: '.min';
		return trailingslashit( $root ) .
			sanitize_file_name( $name ) .
			$minified .
			'.' . $type;
	}

	/**
	 * Whether or not we're dealing with a known resource type.
	 *
	 * @param string $type Resource type.
	 *
	 * @return bool
	 */
	public function is_known_type( $type ) {
		return in_array(
			$type,
			array( self::TYPE_JS, self::TYPE_CSS ),
			true
		);
	}

	/**
	 * Whether or not we're in developer mode
	 *
	 * @return bool
	 */
	public function is_in_developer_mode() {
		return apply_filters(
			'divi_accessibility_developer_mode',
			current_user_can( 'manage_options' ) && $this->can_load( 'developer_mode' )
		);
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
	 * @param     string $option
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
	 * @param string $output
	 * @param string $render_method
	 * @param ET_Builder_Element $element
	 *
	 * @return string
	 */
	function hide_aria_element( $output, $render_method, $element ) {
		if( is_string( $output ) ) {
			$is_aria_disabled = $element->props['hide_aria_element'] === 'on';

			if ($is_aria_disabled) {
				$output = preg_replace('/class=\"(.*?)\"/', 'class="$1 aria-hidden"', $output, 1);
			}
		}
		return $output;
	}
}
