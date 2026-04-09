jQuery(document).ready(function($) {
	const opts = ( window || {} )._da11y || {};
	const debugQueue = ( window || {} )._da11yDebugQueue = ( window || {} )._da11yDebugQueue || [];

	function canDebug() {
		return !! ( opts.options && opts.options.developer_mode );
	}

	function debugChange($element, change, reason) {
		if ( ! canDebug() || ! $element.length ) {
			return;
		}

		debugQueue.push({
			feature: 'ARIA Support',
			change: change,
			element: $element.get(0),
			reason: reason,
		});
	}

	function setAttr($element, attr, value, reason) {
		const stringValue = String(value);

		if ( $element.attr(attr) === stringValue ) {
			return;
		}

		$element.attr(attr, value);
		debugChange($element, `${ attr } = ${ stringValue }`, reason);
	}

	function setAttrs($element, attrs, reason) {
		Object.keys(attrs).forEach(function(attr) {
			setAttr($element, attr, attrs[attr], reason);
		});
	}

	/**
	 * Add role="tabList".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls').each(function () {
		setAttr($(this), 'role', 'tablist', 'tab controls container should expose tablist semantics');
	});

	/**
	 * Add role="presentation".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls li').each(function () {
		setAttr($(this), 'role', 'presentation', 'tab list items should be presentational wrappers');
	});

	/**
	 * Add role="tab".
	 *
	 * @divi-module  Tab
	 */
	 $('.et_pb_tabs_controls a').each(function () {
		setAttrs($(this), {
			'role': 'tab',
		}, 'tab control links should expose tab semantics');
	});

	/**
	 * Add role="tabpanel".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab').each(function () {
		setAttr($(this), 'role', 'tabpanel', 'tab content panels should expose tabpanel semantics');
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
		setAttrs($(this), {
			'aria-selected': 'false',
			'aria-expanded': 'false',
			tabindex: -1
		}, 'inactive tabs should not be selected or tabbable');
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
		setAttrs($(this), {
			'aria-selected': 'true',
			'aria-expanded': 'true',
			tabindex: 0
		}, 'active tabs should be selected and remain tabbable');
	});


	// Add aria-haspopup="true" support to submenus
	$('ul.sub-menu .menu-item a').each(function () {
		setAttrs($(this), {
			'aria-haspopup': 'true',
		}, 'submenu links should announce that they can open a submenu');
	});

	// Add role="link" to all links
	$('a:not(.et-social-icon a, .wp-block-button__link, figure a, .et_pb_button, .et_pb_video_play a, .et_pb_tabs_controls a)').each(function () {
		setAttrs($(this), {
			'role': 'link',
		}, 'links without an excluded selector are normalized to link semantics');
	});

	// Add role="button" to clickable elements
	$('#et_search_icon, .et_close_search_field, #et_mobile_nav_menu, #searchsubmit, .icon, .wp-block-button__link, .et_pb_button, .et_pb_video_play a').each(function () {
		setAttrs($(this), {
			'role': 'button',
		}, 'clickable controls without native button markup are normalized to button semantics');
	});

	//Add aria support to reCAPTCHA
	$('#g-recaptcha-response').each(function () {
		setAttrs($(this), {
			'aria-hidden': 'true',
			'aria-label': 'do not use',
			'aria-readonly': 'true',
		}, 'reCAPTCHA response field should stay hidden from assistive technology');
	});

	/**
	 * Add unique ID to tab controls.
	 * Add aria-controls="x".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs_controls a').each(function (e) {
		setAttrs($(this), {
			id: 'et_pb_tab_control_' + e,
			'aria-controls': 'et_pb_tab_panel_' + e
		}, 'tab controls need stable ids and aria-controls relationships');
	});

	/**
	 * Add unique ID to tab panels.
	 * Add aria-labelledby="x".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab').each(function (e) {
		setAttrs($(this), {
			id: 'et_pb_tab_panel_' + e,
			'aria-labelledby': 'et_pb_tab_control_' + e
		}, 'tab panels need stable ids and aria-labelledby relationships');
	});

	/**
	 * Set initial inactive tab panels to aria-hidden="false".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab.et_pb_active_content').each(function () {
		setAttr($(this), 'aria-hidden', 'false', 'active tab panels should be exposed to assistive technology');
	});

	/**
	 * Set initial inactive tab panels to aria-hidden="true".
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tab:not(.et_pb_active_content)').each(function () {
		setAttr($(this), 'aria-hidden', 'true', 'inactive tab panels should be hidden from assistive technology');
	});

	/**
	 * Add unique ID to tab module.
	 * Need to use data attribute because a regular ID somehow interferes with Divi.
	 *
	 * @divi-module  Tab
	 */
	$('.et_pb_tabs').each(function (e) {
		setAttr($(this), 'data-da11y-id', 'et_pb_tab_module_' + e, 'tab modules need a scope id for later state updates');
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
		$('[data-da11y-id="' + namespace + '"] .et_pb_tabs_controls a').each(function() {
			setAttrs($(this), {
				'aria-selected': 'false',
				'aria-expanded': 'false',
				tabindex: -1
			}, 'inactive tabs should no longer be selected after tab activation changes');
		});
		// Make active tab control aria-selected="true" & aria-expanded="true".
		setAttrs($(this), {
			'aria-selected': 'true',
			'aria-expanded': 'true',
			tabindex: 0
		}, 'the active tab should be selected and tabbable after click');
		// Reset all tabs to be aria-hidden="true".
		$('#' + namespace + ' .et_pb_tab').each(function() {
			setAttr($(this), 'aria-hidden', 'true', 'non-active tab panels should be hidden after tab activation changes');
		});
		// Label active tab panel as aria-hidden="false".
		$('[aria-labelledby="' + id + '"]').each(function() {
			setAttr($(this), 'aria-hidden', 'false', 'the active tab panel should be exposed after tab activation changes');
		});
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
		setAttr($(this), 'data-da11y-id', 'et_pb_search_module_' + e, 'search modules need a scope id for related accessibility updates');
	});

	/**
	 * Add aria-required="true" to inputs.
	 *
	 * @divi-module  Contact Form
	 */
	$('[data-required_mark="required"]').each(function () {
		setAttr($(this), 'aria-required', 'true', 'required contact form inputs should expose aria-required');
	});

	/**
	 * Hide hidden error field on contact form.
	 *
	 * @divi-module  Contact Form
	 */
	$('.et_pb_contactform_validate_field').each(function() {
		setAttr($(this), 'type', 'hidden', 'hidden validation fields should not be exposed as visible inputs');
	});

	/**
	 * Add alert role to error or success contact form message
	 *
	 * @divi-module  Contact Form
	 */
	$('.et-pb-contact-message').each(function() {
		setAttr($(this), 'role', 'alert', 'contact form feedback should be announced as an alert');
	});

	/**
	* Add main role to main-content
	*/
	$('#main-content').each(function() {
		setAttr($(this), 'role', 'main', 'main page content should expose a main landmark');
	});

	/**
	 * Add aria-label="x".
	 *
	 * @divi-module  Fullwidth header, comment-wrap
	 */
	$('.et_pb_fullwidth_header').each(function (e) {
		setAttr($(this), 'aria-label', 'Wide Header' + e, 'fullwidth headers should receive a generated label');
	});
	$('#comment-wrap').each(function() {
		setAttr($(this), 'aria-label', 'Comments', 'the comment wrapper should expose an accessible label');
	});

	/**
	 * Hide manually disabled ARIA elements
	 */
	$('.aria-hidden').each(function (index, element) {
		hideAriaElement(element);
	});

	function hideAriaElement(element) {
		const $element = $(element);
		setAttr($element, 'aria-hidden', 'true', 'manually disabled accessibility elements should be hidden from assistive technology');

		for(const child of $element.children()){
			hideAriaElement(child);
		}
	}
});
