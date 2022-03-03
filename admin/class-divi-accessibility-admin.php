<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://campuspress.com
 * @since      1.0.0
 *
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/admin
 */
class Divi_Accessibility_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook Page hook.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'divi-accessibility-admin-style', plugin_dir_url( __FILE__ ) . 'css/divi-accessibility-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the scripts for the admin area.
	 *
	 * @param string $hook Page hook.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		wp_enqueue_script( 'divi-accessibility-admin-script', plugin_dir_url( __FILE__ ) . 'js/divi-accessibility-admin.js', array( 'wp-color-picker' ), $this->version, true );
	}

	/**
	 * Add an options page under the Divi menu.
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		add_submenu_page(
			'et_divi_options',
			__( 'Divi Accessibility', 'divi-accessibility' ),
			__( 'Accessibility', 'divi-accessibility' ),
			'manage_options',
			$this->da11y,
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Adds a link to the plugin settings page.
	 *
	 * @since     1.0.0
	 * @param     array $links    The current array of links.
	 * @return    array              The modified array of links.
	 */
	public function link_settings( $links ) {

		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=' . $this->da11y ) ),
			esc_html__( 'Settings', 'divi-accessibility' )
		);

		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( 'https://github.com/campuspress/divi-accessibility' ),
			esc_html__( 'GitHub', 'divi-accessibility' )
		);

		return $links;

	}

	/**
	 * Render the options page for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_options_page() {
		include_once 'partials/divi-accessibility-admin-display.php';
	}

	/**
	 * Returns an array of options names and their default values.
	 *
	 * @since    1.0.0
	 * @return   array    An array of options
	 */
	public static function get_options_list() {

		$options = array(
            'aria_support'                 => 1,
            'dropdown_keyboard_navigation' => 1,
            'fix_labels'                   => 1,
            'focusable_modules'            => 1,
            'keyboard_navigation_outline'  => 1,
            'outline_color'                => '#2ea3f2',
            'screen_reader_text'           => 1,
            'skip_navigation_link'         => 1,
            'aria_hidden_icons'            => 1,
            'aria_mobile_menu'             => 1,
            'fix_duplicate_menu_ids'       => 1,
            'underline_urls'               => 0,
            'underline_urls_not_title'     => 0,
            'tota11y'                      => 0,
            'developer_mode'               => 0,
		);

		return $options;

	}

	/**
	 * Register all related settings of this plugin.
	 *
	 * @since    1.1.0
	 */
	public function register_settings() {

		$general_section = $this->da11y_options . '_general_section';
		$tools_section   = $this->da11y_options . '_tools_section';

		register_setting(
			$this->da11y,
			$this->da11y_options,
			array( $this, 'divi_accessibility_validate_options' )
		);

		// Add general section.
		add_settings_section(
			$general_section,
			'General',
			null, // Don't use section callback.
			$this->da11y
		);

		// ARIA support.
		add_settings_field(
            $this->da11y . '_aria_support',
			__( 'ARIA support', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'aria_support',
                'label_for'     => $this->da11y . '_aria_support',
                'label_text'    => __( 'Add appropriate ARIA attributes across Divi elements &amp; modules.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Dropdown keyboard navigation.
		add_settings_field(
            $this->da11y . '_dropdown_keyboard_navigation',
			__( 'Dropdown keyboard navigation', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'dropdown_keyboard_navigation',
                'label_for'     => $this->da11y . '_dropdown_keyboard_navigation',
                'label_text'    => __( 'Allow easier navigation of Divi dropdown menus with the keyboard.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Fix labels.
		add_settings_field(
            $this->da11y . '_fix_labels',
			__( 'Fix labels', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'fix_labels',
                'label_for'     => $this->da11y . '_fix_labels',
                'label_text'    => __( 'Fix missing labels &amp; incorrect or missing assignments to their corresponding inputs.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Focusable modules.
		add_settings_field(
            $this->da11y . '_focusable_modules',
			__( 'Focusable modules', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'focusable_modules',
                'label_for'     => $this->da11y . '_focusable_modules',
                'label_text'    => __( 'Allow Divi modules such as <em>Toggle</em> &amp; <em>Accordion</em> to be focusable with keyboard navigation. Hitting enter will open/close when focused.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Keyboard navigation outline.
		add_settings_field(
            $this->da11y . '_keyboard_navigation_outline',
			__( 'Keyboard navigation outline', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'keyboard_navigation_outline',
                'label_for'     => $this->da11y . '_keyboard_navigation_outline',
                'label_text'    => __( 'Add an outline to focused elements when navigation with the keyboard.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Outline color.
		add_settings_field(
            $this->da11y . '_outline_color',
			__( 'Outline color', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_color_picker_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'outline_color',
                'label_for'     => $this->da11y . '_outline_color',
                'label_text'    => '',
                'label_subtext' => __( 'Keyboard navigation outline', 'divi-accessibility' ),
			)
		);

		// Screen reader text.
		add_settings_field(
            $this->da11y . '_screen_reader_text',
			__( 'Screen reader text', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'screen_reader_text',
                'label_for'     => $this->da11y . '_screen_reader_text',
                'label_text'    => __( 'Add plugin screen reader class used on certain labels &amp; reverses Divi incorrectly applying <code>display: none;</code> on its own screen reader classes.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Skip navigation link.
		add_settings_field(
            $this->da11y . '_skip_navigation_link',
			__( 'Skip navigation link', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'skip_navigation_link',
                'label_for'     => $this->da11y . '_skip_navigation_link',
                'label_text'    => __( 'Allow user to skip over Divi navigation when using keyboard and go straight to content.', 'divi-accessibility' ),
                'label_subtext' => __( 'Requires screen reader text option to be on', 'divi-accessibility' ),
			)
		);

		// Aria-hidden on icons.
		add_settings_field(
            $this->da11y . '_aria_hidden_icons',
			__( 'Hide icons', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'aria_hidden_icons',
                'label_for'     => $this->da11y . '_aria_hidden_icons',
                'label_text'    => __( 'Hide all icons to screen readers so text is read normally.', 'divi-accessibility' ),
                'label_subtext' => __( 'This may not work for all icons', 'divi-accessibility' ),
			)
		);

		// Aria support for mobile menu.
		add_settings_field(
            $this->da11y . '_aria_mobile_menu',
			__( 'Aria support for mobile menu', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'       => 'aria_mobile_menu',
                'label_for'  => $this->da11y . '_aria_mobile_menu',
                'label_text' => __( 'Apply Aria attributes to the mobile (burger) menu to make it accessible.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Fix duplicate menu ids.
		add_settings_field(
            $this->da11y . '_fix_duplicate_menu_ids',
			__( 'Fix duplicate menu ids', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'fix_duplicate_menu_ids',
                'label_for'     => $this->da11y . '_fix_duplicate_menu_ids',
                'label_text'    => __( 'Because Divi uses the same menu twice (Once for the primary menu and again for the mobile menu), the unique ID\'s are duplicated causing validation issues. This option prevents WordPress from adding a unique ID to the menu list items.', 'divi-accessibility' ),
                'label_subtext' => '',
			)
		);

		// Add underline to all links in #et-main-area
		add_settings_field(
            $this->da11y . '_underline_urls',
			__( 'Underline URLs', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'underline_urls',
                'label_for'     => $this->da11y . '_underline_urls',
                'label_text'    => __( 'Easily find out URLs when they are underlined' ),
                'label_subtext' => '',
			)
		);

		// Remove underline from .entry-title
		add_settings_field(
            $this->da11y . '_underline_urls_not_title',
			__( 'Exclude underlines from titles and buttons', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$general_section,
			array(
                'name'          => 'underline_urls_not_title',
                'label_for'     => $this->da11y . '_underline_urls_not_title',
                'label_text'    => __( 'If you don\'t like title, headings or buttons to be underlined you can disable it from here' ),
                'label_subtext' => '',
			)
		);

		// Add tools section.
		add_settings_section(
			$tools_section,
			__( 'Tools', 'divi-accessibility' ),
			null, // Don't use section callback.
			$this->da11y
		);

		// Tota11y.
		add_settings_field(
            $this->da11y . '_tota11y',
			__( 'Tota11y', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$tools_section,
			array(
                'name'          => 'tota11y',
                'label_for'     => $this->da11y . '_tota11y',
                'label_text'    => __( 'Add a small button to the bottom corner of site to visualize how your site performs with assistive technology.', 'divi-accessibility' ),
                'label_subtext' => __( 'Admin users only', 'divi-accessibility' ),
			)
		);

		// Developer mode.
		add_settings_field(
            $this->da11y . '_developer_mode',
			__( 'Developer mode', 'divi-accessibility' ),
			array( $this, 'divi_accessibility_checkbox_cb' ),
			$this->da11y,
			$tools_section,
			array(
                'name'          => 'developer_mode',
                'label_for'     => $this->da11y . '_developer_mode',
                'label_text'    => __( 'Log plugin info to console.', 'divi-accessibility' ),
                'label_subtext' => __( 'Admin users only', 'divi-accessibility' ),
			)
		);

	}

	/**
	 * Validate options before saving to DB.
	 *
	 * @since    1.0.0
	 * @param    array $input Input.
	 */
	public function divi_accessibility_validate_options( $input ) {

		$valid_options = array();
		$option_list   = $this->get_options_list();

		// Loop through all available options.
		foreach ( $option_list as $key => $option ) {

			// If color-picker.
			if ( 'outline_color' == $key ) {

				$default_color = $option;

				if ( $this->is_valid_color( $input[ $key ] ) ) {

					$valid_options[ $key ] = sanitize_text_field( $input[ $key ] );

				} else {

					$valid_options[ $key ] = $default_color;

				}
			} elseif ( isset( $input[ $key ] ) && 1 == $input[ $key ] ) {

				$valid_options[ $key ] = 1;

			} else {

				$valid_options[ $key ] = 0;

			}
		} // End foreach().

		return $valid_options;

	}

	/**
	 * Check if value is a valid HEX color.
	 *
	 * @since    1.0.0
	 * @param string $value Value to check.
	 * @return   boolean
	 */
	public function is_valid_color( $value ) {

		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #.

			return true;

		}

		return false;

	}

	/**
	 * Callback for checkbox settings.
	 *
	 * @since    1.0.0
	 * @param    array $arg Input args.
	 */
	public function divi_accessibility_checkbox_cb( $arg ) {

		$name          = $arg['name'];
		$label_for     = $arg['label_for'];
		$label_text    = $arg['label_text'];
		$label_subtext = ! empty( $arg['label_subtext'] ) ? $arg['label_subtext'] : '';

		if ( isset( $this->settings[ $name ] ) ) {
			$checked = $this->settings[ $name ];
		} else {
			$checked = 0;
		}

		?>

		<fieldset>

			<label class="widefat">
				<input type="checkbox"
				<?php checked( $checked, 1 ); ?>
                name="<?php echo esc_attr( $this->da11y_options ) . '[' . esc_attr( $name ) . ']'; ?>"
                id="<?php echo esc_attr( $label_for ); ?>"
                aria-describedby="<?php echo esc_attr( $label_for ); ?>-desc"
                value="1" />
				<?php echo wp_kses_post( $label_text ); ?>
			</label>

			<?php if ( '' != $label_subtext ) { ?>
				<p id="<?php echo esc_attr( $label_for ); ?>-desc" class="description">(<em><?php echo esc_html( $label_subtext ); ?></em>)</p>
			<?php } ?>

		</fieldset>

		<?php
	}

	/**
	 * Callback for color picker settings.
	 *
	 * @since    1.0.0
	 * @param    array $arg Input args.
	 */
	public function divi_accessibility_color_picker_cb( $arg ) {

		$name          = $arg['name'];
		$label_for     = $arg['label_for'];
		$label_text    = $arg['label_text'];
		$label_subtext = $arg['label_subtext'];

		$option_list   = $this->get_options_list();
		$default_color = $option_list['outline_color'];

		if ( isset( $this->settings['outline_color'] ) ) {
			$color = $this->settings['outline_color'];
		}

		?>

		<fieldset>
			<label class="widefat">
				<input type="text"
                       name="<?php echo esc_attr( $this->da11y_options ) . '[' . esc_attr( $name ) . ']'; ?>"
                       id="<?php echo esc_attr( $label_for ); ?>"
                       value="<?php echo esc_attr( $color ); ?>"
                       class="da11y-color-picker"
                       data-default-color="<?php echo esc_attr( $default_color ); ?>"
				/>
			</label>

			<?php if ( '' != $label_subtext ) { ?>
				<p class="description">(<em><?php echo esc_html( $label_subtext ); ?></em>)</p>
			<?php } ?>

		</fieldset>

		<?php
	}

	/**
	 * Register DIVI builder accessibility settings
	 *
	 * @param array $fields
	 * @return array
	 */
	function divi_builder_register_accessibilty_settings( $fields ) {
		$hide_aria_setting = array(
                'type'        => 'yes_no_button',
                'description' => __( 'Hide From Screen Readers', 'divi-accessibility' ),
                'label'             => __( 'Hide From Screen Readers', 'divi-accessibility' ),
                'option_category'   => 'configuration',
                'options' => array(
                        'off' => et_builder_i18n( 'No' ),
                        'on' => et_builder_i18n( 'Yes' )
				),
                'default' => 'off',
                'toggle_slug' => 'accessibility'
		);

		$fields['hide_aria_element'] = $hide_aria_setting;

		$show_for_screen_readers_only = array(
			'type'        => 'yes_no_button',
			'description' => __( 'Make element visible for Screen Readers only', 'divi-accessibility' ),
			'label'             => __( 'Show For Screen Readers Only', 'divi-accessibility' ),
			'option_category'   => 'configuration',
			'options' => array(
				'off' => et_builder_i18n( 'No' ),
				'on' => et_builder_i18n( 'Yes' )
			),
			'default' => 'off',
			'toggle_slug' => 'accessibility'
		);
		$fields['show_for_screen_readers_only'] = $show_for_screen_readers_only;

		return $fields;
	}

	/**
	 * Add section in DIVI element options modal
	 * @param string $content
	 * @param string $post_type
	 *
	 * @return string
	 */
	function divi_builder_add_accessibility_group( $content, $post_type ) {
		if( $content ) {
			$json = mb_substr( $content, 43, strlen( $content ) - 68 );

			$params = json_decode( $json, true );
			foreach( $params['optionsToggles'] as $key => $element ) {
				$element['general']['toggles']['accessibility'] = array( 'title' => __( 'Accessibility Settings', 'divi-accessibility' ), 'priority' => 50 );
				$params['optionsToggles'][$key] = $element;
			}

			return sprintf(
					'window.ETBuilderBackend=jQuery.extend(true,%s,window.ETBuilderBackend)',
					et_fb_remove_site_url_protocol( wp_json_encode( $params, ET_BUILDER_JSON_ENCODE_OPTIONS ) )
			);
		}
		return '';
	}
}
