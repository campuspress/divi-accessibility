jQuery(document).ready(function($) {
	function setSubmenuTabState(menuItem, isOpen) {
		const submenu = menuItem.children('.sub-menu');
		const submenuLinks = submenu.find('a');

		if ( ! submenu.length ) {
			return;
		}

		menuItem.children('.da11y-submenu')
			.attr('aria-expanded', isOpen ? 'true' : 'false');

		submenu.toggleClass('da11y-submenu-show', isOpen);

		if ( isOpen ) {
			submenuLinks.removeAttr('tabindex');
			return;
		}

		submenuLinks.attr('tabindex', '-1');
		submenu.find('.da11y-submenu').attr('aria-expanded', 'false');
		submenu.find('.sub-menu').removeClass('da11y-submenu-show');
	}

	$('.et-menu > li').on('focusout', function() {
		$(this).removeClass('et-hover');
	});
	if($('.menu-item-has-children > a').length ) {
		$('.menu-item-has-children > a').addClass('da11y-submenu');
		$('.menu-item-has-children > a').attr('aria-expanded', 'false');
		$('.menu-item-has-children').each(function() {
			setSubmenuTabState($(this), false);
		});
	}

	$('.menu-item a').on('focus', function() {
		setSubmenuTabState($(this).parent(), true);
		$(this).trigger('mouseenter');
	});

	$('.menu-item-has-children a').on('focusout', function() {
		if( $(this).parent().not('.menu-item-has-children').is(':last-child') ) {
			$(this).parents('.menu-item-has-children').each(function() {
				setSubmenuTabState($(this), false);
			}).trigger('mouseleave');
		}
	});

	$('.menu-item-has-children a').keyup(function(event) {
		if (event.keyCode === 27) {
			event.preventDefault();
			var menuParent = $(this).parents('.menu-item-has-children').last();
			if(menuParent.length) {
				setSubmenuTabState(menuParent, false);
				menuParent.trigger('mouseleave');
				menuParent.children('a').focus();
			}
		}
	});

	/**
	 * Generate search form styles.
	 *
	 * @since Divi v3.0.23
	 */
	function et_set_search_form_css() {
		const search_container = $('.et_search_form_container');
		const body = $('body');
		if (search_container.hasClass('et_pb_search_visible')) {
			const header_height = $('#main-header').innerHeight();
			const menu_width = $('#top-menu').width();
			const font_size = $('#top-menu li a').css('font-size');
			search_container.css({ height: header_height + 'px' });
			search_container.find('input').css('font-size', font_size);
			if (!body.hasClass('et_header_style_left')) {
				search_container.css('max-width', menu_width + 60);
			} else {
				search_container.find('form').css('max-width', menu_width + 60);
			}
		}
	}

	/**
	 * Show the search.
	 *
	 * @since Divi v3.0.23
	 */
	function show_search() {
		const search_container = $('.et_search_form_container');
		if (search_container.hasClass('et_pb_is_animating')) {
			return;
		}
		$('.et_menu_container').removeClass('et_pb_menu_visible et_pb_no_animation').addClass('et_pb_menu_hidden');
		search_container.removeClass('et_pb_search_form_hidden et_pb_no_animation').addClass('et_pb_search_visible et_pb_is_animating');
		setTimeout(function () {
			$('.et_menu_container').addClass('et_pb_no_animation');
			search_container.addClass('et_pb_no_animation').removeClass('et_pb_is_animating');
		}, 1000);
		search_container.find('input').focus();
		et_set_search_form_css();
	}

	/**
	 * Hide the search.
	 *
	 * @since Divi v3.0.23
	 */
	function hide_search() {
		if ($('.et_search_form_container').hasClass('et_pb_is_animating')) {
			return;
		}
		$('.et_menu_container').removeClass('et_pb_menu_hidden et_pb_no_animation').addClass('et_pb_menu_visible');
		$('.et_search_form_container').removeClass('et_pb_search_visible et_pb_no_animation').addClass('et_pb_search_form_hidden et_pb_is_animating');
		setTimeout(function () {
			$('.et_menu_container').addClass('et_pb_no_animation');
			$('.et_search_form_container').addClass('et_pb_no_animation').removeClass('et_pb_is_animating');
		}, 1000);
	}

	$(this).keyup(function () {
		$('.et-search-field').focus(function () {
			show_search();
		}).blur(function () {
			hide_search();
		});
	});

});
