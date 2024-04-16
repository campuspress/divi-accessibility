jQuery(document).ready(function($) {

	/**
	 * Add role="tabList".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls').each(function () {
		$(this).attr('role', 'tablist');
	});

	/**
	 * Add role="presentation".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls li').each(function () {
		$(this).attr('role', 'presentation');
	});

	/**
	 * Add role="tab".
	 *
	 * @divi-module  Tab
	 */
	 $('.et_pb_tabs_controls a').each(function () {
		$(this).attr({
			'role': 'tab',
		});
	});

	/**
	 * Add role="tabpanel".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab').each(function () {
		$(this).attr('role', 'tabpanel');
	});

	/**
	 * Add initial state:
	 *
	 * aria-selected="false"
	 * aria-expanded="false"
	 * tabindex=-1
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls li:not(.et_pb_tab_active) a').each(function () {
		$(this).attr({
			'aria-selected': 'false',
			'aria-expanded': 'false',
			tabindex: -1
		});
	});


	/**
	* Add initial state:
	*
	* aria-selected="true"
	* aria-expanded="true"
	* tabindex=-1
	*
	* @divi-module  Tab
	 */
	$('.et_pb_tabs_controls li.et_pb_tab_active a').each(function () {
		$(this).attr({
			'aria-selected': 'true',
			'aria-expanded': 'true',
			tabindex: 0
		});
	});


	// Add aria-haspopup="true" support to submenus
	$('ul.sub-menu .menu-item a').each(function () {
		$(this).attr({
			'aria-haspopup': 'true',
		});
	});

	// Add role="link" to all links
	$('a:not(.et-social-icon a, .wp-block-button__link, figure a, .et_pb_button, .et_pb_video_play a, .et_pb_tabs_controls a)').each(function () {
		$(this).attr({
			'role': 'link',
		});
	});

	// Add role="button" to clickable elements
	$(#et_mobile_nav_menu, #searchsubmit, .icon, .wp-block-button__link, .et_pb_button, .et_pb_video_play a').each(function () {
		$(this).attr({
			'role': 'button',
		});
	});

	$('.et_pb_menu__search-button').each(function () {
		$(this).attr({
			'role': 'searchbox',
			'aria-label': 'search',
		});
	});

	$('.et_pb_menu__close-search-button').each(function () {
		$(this).attr({
			'aria-label': 'click to close search',
		});
	});

	//Add aria support to reCAPTCHA
	$('#g-recaptcha-response').each(function () {
		$(this).attr({
			'aria-hidden': 'true',
			'aria-label': 'do not use',
			'aria-readonly': 'true',
		});
	});

	/**
	 * Add unique ID to tab controls.
	 * Add aria-controls="x".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls a').each(function (e) {
		$(this).attr({
			id: 'et_pb_tab_control_' + e,
			'aria-controls': 'et_pb_tab_panel_' + e
		});
	});

	/**
	 * Add unique ID to tab panels.
	 * Add aria-labelledby="x".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab').each(function (e) {
		$(this).attr({
			id: 'et_pb_tab_panel_' + e,
			'aria-labelledby': 'et_pb_tab_control_' + e
		});
	});

	/**
	 * Set initial inactive tab panels to aria-hidden="false".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab.et_pb_active_content').each(function () {
		$(this).attr('aria-hidden', 'false');
	});

	/**
	 * Set initial inactive tab panels to aria-hidden="true".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab:not(.et_pb_active_content)').each(function () {
		$(this).attr('aria-hidden', 'true');
	});

	/**
	 * Add unique ID to tab module.
	 * Need to use data attribute because a regular ID somehow interferes with Divi.
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs').each(function (e) {
		$(this).attr('data-da11y-id', 'et_pb_tab_module_' + e);
	});

	/**
	 * Update aria-selected attribute when tab is clicked or when hitting enter while focused.
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls a').on('click', function () {
		const id = $(this).attr('id');
		const namespace = $(this).closest('.et_pb_tabs').attr('data-da11y-id'); // Used as a selector to scope changes to current module.
		// Reset all tab controls to be aria-selected="false" & aria-expanded="false".
		$('[data-da11y-id="' + namespace + '"] .et_pb_tabs_controls a').attr({
			'aria-selected': 'false',
			'aria-expanded': 'false',
			tabindex: -1
		});
		// Make active tab control aria-selected="true" & aria-expanded="true".
		$(this).attr({
			'aria-selected': 'true',
			'aria-expanded': 'true',
			tabindex: 0
		});
		// Reset all tabs to be aria-hidden="true".
		$('#' + namespace + ' .et_pb_tab').attr('aria-hidden', 'true');
		// Label active tab panel as aria-hidden="false".
		$('[aria-labelledby="' + id + '"]').attr('aria-hidden', 'false');
	});

	// Arrow navigation for tab modules
	$('.et_pb_tabs_controls a').keyup(function (e) {
		const namespace = $(this).closest('.et_pb_tabs').attr('data-da11y-id');
		const module = $('[data-da11y-id="' + namespace + '"]');
		if (e.which === 39) { // Right.
			const next = module.find('li.et_pb_tab_active').next();
			if (next.length > 0) {
				next.find('a').trigger('click');
			} else {
				module.find('li:first a').trigger('click');
			}
		} else if (e.which === 37) { // Left.
			const next = module.find('li.et_pb_tab_active').prev();
			if (next.length > 0) {
				next.find('a').trigger('click');
			} else {
				module.find('li:last a').trigger('click');
			}
		}
		$('.et_pb_tabs_controls a').removeClass('keyboard-outline');
		module.find('li.et_pb_tab_active a').addClass('keyboard-outline');
	});

	/**
	 * Add unique ID to search module.
	 * Need to use data attribute because a regular ID somehow interferes with Divi.
	 *
	 * @divi-module  Search
	 */
	$('.et_pb_search').each(function (e) {
		$(this).attr('data-da11y-id', 'et_pb_search_module_' + e);
	});

	/**
	 * Add aria-required="true" to inputs.
	 *
	 * @divi-module  Contact Form
	 */
	$('[data-required_mark="required"]').each(function () {
		$(this).attr('aria-required', 'true');
	});

	/**
	 * Hide hidden error field on contact form.
	 *
	 * @divi-module  Contact Form
	 */
	$('.et_pb_contactform_validate_field').attr('type', 'hidden');

	/**
	 * Add alert role to error or success contact form message
	 *
	 * @divi-module  Contact Form
	 */
	$('.et-pb-contact-message').attr('role', 'alert');

	/**
	* Add main role to main-content
	*/
	$('#main-content').attr('role', 'main');

	/**
	 * Add aria-label="x".
	 *
	 * @divi-module  Fullwidth header, comment-wrap
	 */
	$('.et_pb_fullwidth_header').each(function (e) {
		$(this).attr('aria-label', 'Wide Header' + e);
	});
	$('#comment-wrap').attr('aria-label', 'Comments');

	/**
	 * Hide manually disabled ARIA elements
	 */
	$('.aria-hidden').each(function (index, element) {
		hideAriaElement(element);
	});

	function hideAriaElement(element) {
		const $element = $(element);
		$(element).attr('aria-hidden', 'true');

		for(const child of $element.children()){
			hideAriaElement(child);
		}
	}
});
