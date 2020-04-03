jQuery(document).ready(function($) {

	/**
	 * Mobile menu Aria support.
	 */
	$('.mobile_menu_bar_toggle').attr({'role': 'button', 'tabindex': 0});
	$('.mobile_menu_bar_toggle').on('click', function() {
		if($(this).hasClass('a11y-mobile-menu-open') ) {
			$(this).removeClass('a11y-mobile-menu-open').attr('aria-expanded', 'false');
		} else {
			$(this).addClass('a11y-mobile-menu-open').attr('aria-expanded', 'true');
		}
	});

});

