jQuery(document).ready(function($) {
	var mobileMenuSyncDelay = 50;

	/**
	 * Sync the aria state for mobile submenu parent links.
	 *
	 * @param {jQuery} $mobileNav
	 */
	function syncMobileSubmenuState($mobileNav) {
		var isOpen = $mobileNav.hasClass('opened');

		$mobileNav.find('.menu-item-has-children > a').each(function() {
			var $link = $(this);
			var hasVisibleSubmenu = isOpen && $link.siblings('.sub-menu:visible').length > 0;

			$link.attr('aria-expanded', hasVisibleSubmenu ? 'true' : 'false');
		});
	}

	/**
	 * Sync the aria state for an individual mobile menu trigger and its submenu links.
	 *
	 * @param {jQuery} $mobileMenuBar
	 */
	function syncMobileMenuState($mobileMenuBar) {
		var $mobileNav = $mobileMenuBar.closest('.mobile_nav');
		var isOpen = $mobileNav.hasClass('opened');

		$mobileMenuBar
			.toggleClass('a11y-mobile-menu-open', isOpen)
			.attr('aria-expanded', isOpen ? 'true' : 'false');

		if ($mobileNav.length) {
			syncMobileSubmenuState($mobileNav);
		}
	}

	/**
	 * Mobile menu Aria support.
	 */
	$('.mobile_menu_bar').attr({'role': 'button', 'aria-expanded': 'false', 'aria-label': 'Menu', 'tabindex': 0});
	$('.mobile_menu_bar').on('click', function() {
		var $mobileMenuBar = $(this);

		setTimeout(function() {
			syncMobileMenuState($mobileMenuBar);
		}, mobileMenuSyncDelay);
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
			$('.mobile_nav.opened').each(function() {
				$(this).find('.mobile_menu_bar').first().trigger('click');
			});
		}
	});

	/**
	* Closes mobile menu when it loses focus.
	*/
	$(this).on('focusin', function () {
		$('.mobile_nav.opened').each(function() {
			var $mobileNav = $(this);

			if(!$mobileNav.find('.et_mobile_menu :focus').length) {
				$mobileNav.find('.mobile_menu_bar').first().trigger('click');
			}
		});
	});

	$('.mobile_nav').each(function() {
		var $mobileNav = $(this);
		var $mobileMenuBar = $mobileNav.find('.mobile_menu_bar').first();

		if ($mobileMenuBar.length) {
			syncMobileMenuState($mobileMenuBar);
		}
	});

});
