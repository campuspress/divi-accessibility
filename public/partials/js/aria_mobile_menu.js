jQuery(document).ready(function($) {

	/**
	 * Mobile menu Aria support.
	 */
	$('.mobile_menu_bar_toggle').attr({'role': 'button', 'aria-expanded': 'false', 'aria-label': 'Menu', 'tabindex': 0});
	$('.mobile_menu_bar_toggle').on('click', function() {
		if($(this).hasClass('a11y-mobile-menu-open') ) {
			$(this).removeClass('a11y-mobile-menu-open').attr('aria-expanded', 'false');
		} else {
			$(this).addClass('a11y-mobile-menu-open').attr('aria-expanded', 'true');
		}
	});

	/**
	* Allows mobile menu to be opened with keyboard.
	*/
	$('.mobile_menu_bar_toggle').keyup(function(event) {
		if (event.keyCode === 13) {
			$('.mobile_menu_bar_toggle').click();
		}
	});

	/**
	* Allows mobile menu to be closed with keyboard.
	*/
	$(document).keyup(function(event) {
		if (event.keyCode === 27) {
			if($('#et_mobile_nav_menu .mobile_nav').hasClass('opened')) {
				$('.mobile_menu_bar_toggle').click();
			}
		}
	});

	/**
	* Closes mobile menu when it loses focus.
	*/
	$(this).on('focusin', function () {
		if($('#et_mobile_nav_menu .mobile_nav').hasClass('opened')) {
			if(!$('#et_mobile_nav_menu .et_mobile_menu :focus').length) {
				$('#et_mobile_nav_menu .mobile_menu_bar_toggle').click();
			}
		}
	});

});