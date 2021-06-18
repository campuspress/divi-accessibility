jQuery(document).ready(function($) {

	/**
	 * Add appropriate aria attributes to Accordion & Toggle Divi modules
	 *
	 * @divi-module  Accordion, Toggle
	 */
	$('.et_pb_toggle').each(function (index) {
		var $toggle = $(this);
		var $title = $toggle.find('.et_pb_toggle_title');
		var $panel = $toggle.find('.et_pb_toggle_content');
		var isAccordion = $toggle.hasClass('et_pb_accordion_item');

		$title.attr('role', 'button');
		$title.attr('tabindex', 0);
		$title.attr('aria-controls', 'et_pb_toggle_content_' + index);
		$panel.attr('id', 'et_pb_toggle_content_' + index);

		if ($toggle.hasClass('et_pb_toggle_open')) {
			$title.attr('aria-expanded', true);

			if (isAccordion) $title.attr('aria-disabled', true);
		} else {
			$title.attr('aria-expanded', false);

			if (isAccordion) $title.removeAttr('aria-disabled');
		}
	});

	/**
	 * Prevent spacebar from scolling page when toggle & accordion have focus.
	 *
	 * @divi-module  Accordion, Toggle
	 */
	$('.et_pb_toggle_title').on('keydown', function(e) {
		// Spacebar.
		if (e.which === 32){
			e.preventDefault();
		}
	});

	/**
	 * Expand Accordion & Toggle modules when enter or spacebar are pressed while focused.
	 *
	 * @divi-module  Accordion, Toggle
	 */
	$(document).on('keyup', function(e) {
		// Spacebar & Enter.
		if (e.which === 13 || e.which === 32) {
			$('.et_pb_toggle_title:focus').trigger('click');
		}
	});

	/**
	 * Set aria attributes of Accordion & Toggle modules when one is clicked.
	 *
	 * @divi-module  Accordion, Toggle
	 */
	$('.et_pb_toggle_title').on('click', function() {
		var $clickedToggleTitle = $(this);
		var $clickedToggle = $clickedToggleTitle.parent();
		var isAccordion = $clickedToggle.hasClass('et_pb_accordion_item');

		if (isAccordion) {
			// Only change the aria attributes if an accordion toggle isn't already open
			if (!$clickedToggle.hasClass('et_pb_toggle_open')) {
				var $allSiblingToggles = $clickedToggleTitle.closest('.et_pb_accordion').find('.et_pb_toggle');

				// Reset the aria attributes on the open toggle
				$allSiblingToggles.each(function() {
					$toggle = $(this);
					if ($toggle.hasClass('et_pb_toggle_open')) {
						var $openToggleTitle = $toggle.find('.et_pb_toggle_title')

						$openToggleTitle.attr('aria-expanded', false);
						$openToggleTitle.removeAttr('aria-disabled');
					}
				});

				// Set the aria attributes on the clicked toggle
				setTimeout(function() {
					$clickedToggleTitle.attr('aria-expanded', true);
					$clickedToggleTitle.attr('aria-disabled', true);
				}, 500);
			}
		} else {
			// The toggle isn't part of an accordion so its aria attributes should be udpated
			if ($clickedToggle.hasClass('et_pb_toggle_open')) {
				$clickedToggleTitle.attr('aria-expanded', false);
			} else {
				$clickedToggleTitle.attr('aria-expanded', true);
			}
		}
	});

});

