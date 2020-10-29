jQuery(document).ready(function($) {

	let lastKey = new Date();
	let lastClick = new Date();

	/**
	 * Only apply focus styles for keyboard usage.
	 */
	$(this).on('focusin', function (e) {
		$('.keyboard-outline').removeClass('keyboard-outline');
		const wasByKeyboard = lastClick < lastKey;
		if (wasByKeyboard) {
			$(e.target).addClass('keyboard-outline');
		}
	});

	$(this).on('mousedown', function () {
		lastClick = new Date();
	});

	$(this).on('keydown', function () {
		lastKey = new Date();
	});

});

