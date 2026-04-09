jQuery(document).ready(function($) {
	var toggleButtonClass = 'da11y-toggle-trigger';
	var toggleButtonStyleId = 'da11y-focusable-modules-style';
	var toggleSyncDelay = 500;

	/**
	 * Add button reset styles for generated toggle controls.
	 *
	 * @divi-module Accordion, Toggle
	 */
	function ensureToggleButtonStyles() {
		if ($('#' + toggleButtonStyleId).length) {
			return;
		}

		$('<style />', {
			id: toggleButtonStyleId,
			text: '.et_pb_toggle_title .' + toggleButtonClass + '{-webkit-appearance:none;appearance:none;background:none;border:0;border-radius:0;box-shadow:none;color:inherit;cursor:pointer;display:block;font:inherit;line-height:inherit;margin:0;padding:0;text-align:inherit;width:100%;}'
		}).appendTo('head');
	}

	/**
	 * Get a generated button from the current toggle title.
	 *
	 * @divi-module Accordion, Toggle
	 *
	 * @param {jQuery} $toggle
	 * @returns {jQuery}
	 */
	function getToggleButton($toggle) {
		return $toggle.find('.et_pb_toggle_title > .' + toggleButtonClass).first();
	}

	/**
	 * Sync aria state to the generated toggle button.
	 *
	 * @divi-module Accordion, Toggle
	 *
	 * @param {jQuery} $toggle
	 */
	function syncToggleButtonState($toggle) {
		var $panel = $toggle.find('.et_pb_toggle_content').first();
		var $button = getToggleButton($toggle);
		var isAccordion = $toggle.hasClass('et_pb_accordion_item');
		var isOpen = $toggle.hasClass('et_pb_toggle_open');

		if (!$panel.length || !$button.length) {
			return;
		}

		$button.attr({
			'aria-controls': $panel.attr('id'),
			'aria-expanded': isOpen ? 'true' : 'false',
		});

		if (isAccordion && isOpen) {
			$button.attr('aria-disabled', 'true');
		} else {
			$button.removeAttr('aria-disabled');
		}
	}

	/**
	 * Add appropriate aria attributes to Accordion & Toggle Divi modules
	 *
	 * @divi-module  Accordion, Toggle
	 */
	ensureToggleButtonStyles();

	$('.et_pb_toggle').each(function (index) {
		var $toggle = $(this);
		var $title = $toggle.find('.et_pb_toggle_title');
		var $panel = $toggle.find('.et_pb_toggle_content');
		var $button = getToggleButton($toggle);

		if (!$panel.attr('id')) {
			$panel.attr('id', 'et_pb_toggle_content_' + index);
		}

		if (!$button.length) {
			$title.wrapInner('<button type="button" class="' + toggleButtonClass + '"></button>');
			$button = getToggleButton($toggle);
		}

		$button.attr('type', 'button');
		syncToggleButtonState($toggle);
	});

	/**
	 * Set aria attributes of Accordion & Toggle modules when one is clicked.
	 *
	 * @divi-module  Accordion, Toggle
	 */
	$('.et_pb_toggle_title').on('click', function() {
		var $clickedToggle = $(this).closest('.et_pb_toggle');
		var $togglesToSync = $clickedToggle;

		if ($clickedToggle.hasClass('et_pb_accordion_item')) {
			$togglesToSync = $clickedToggle.closest('.et_pb_accordion').find('.et_pb_toggle');
		}

		setTimeout(function() {
			$togglesToSync.each(function() {
				syncToggleButtonState($(this));
			});
		}, toggleSyncDelay);
	});

});
