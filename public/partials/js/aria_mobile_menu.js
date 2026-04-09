jQuery(document).ready(function($) {

	function getOpenedMobileNav() {
		return $('.mobile_nav.opened').first();
	}

	function getOpenedMobileMenuToggle() {
		const openedNav = getOpenedMobileNav();

		if ( ! openedNav.length ) {
			return $();
		}

		return openedNav.find('.mobile_menu_bar').first();
	}

	/**
	 * Mobile menu Aria support.
	 */
	$('.mobile_menu_bar').attr({'role': 'button', 'aria-expanded': 'false', 'aria-label': 'Menu', 'tabindex': 0});
	$('.mobile_menu_bar').on('click', function() {
		if($(this).hasClass('a11y-mobile-menu-open') ) {
			$(this).removeClass('a11y-mobile-menu-open').attr('aria-expanded', 'false');
		} else {
			$(this).addClass('a11y-mobile-menu-open').attr('aria-expanded', 'true');
		}
	});

	/**
	* Allows mobile menu to be opened with keyboard.
	*/
	$('.mobile_menu_bar').keyup(function(event) {
		if (event.keyCode === 13 || event.keyCode === 32) {
			$(this).click();
		}
	});

	/**
	* Allows mobile menu to be closed with keyboard.
	*/
	$(document).keyup(function(event) {
		if (event.keyCode === 27) {
			const menuToggle = getOpenedMobileMenuToggle();
			if ( menuToggle.length ) {
				menuToggle.click();
				menuToggle.trigger('focus');
			}
		}
	});

	/**
	* Closes mobile menu when it loses focus.
	*/
	$(this).on('focusin', function () {
		const openedNav = getOpenedMobileNav();

		if ( ! openedNav.length ) {
			return;
		}

		if ( openedNav.find('.et_mobile_menu :focus, .mobile_menu_bar:focus').length ) {
			return;
		}

		getOpenedMobileMenuToggle().click();
	});

});
