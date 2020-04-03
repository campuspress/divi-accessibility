jQuery(document).ready(function($) {

	/**
	 * Allow Accordion & Toggle Divi modules to be focusable.
	 *
	 * @divi-module  Accordion, Toggle
	 */
	$('.et_pb_toggle').each(function () {
		$(this).attr('tabindex', 0);
	});

	/**
	 * Prevent spacebar from scolling page when toggle & accordion have focus.
	 *
	 * @divi-module  Accordion, Toggle
	 */
	$('.et_pb_toggle').on('keydown', function(e) {
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
			$('.et_pb_toggle:focus .et_pb_toggle_title').trigger('click');
		}
	});

});

