<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://campuspress.com
 * @since      1.0.0
 *
 * @package    Divi_Accessibility
 * @subpackage Divi_Accessibility/public/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( $this->can_load( 'dropdown_keyboard_navigation' ) ) {

?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

			if($('.menu-item-has-children').find('a') ) {
				$('.menu-item-has-children').find('a').addClass('da11y-submenu');
				$('.menu-item-has-children').find('a').not('.sub-menu a').attr('aria-expanded', 'false');
			}

			$('.da11y-submenu').on('focus', function() {
				$(this).not('.sub-menu a').attr('aria-expanded', 'true');
				$(this).siblings('.sub-menu').addClass('da11y-submenu-show');
				$(this).trigger('mouseenter');
			});
			$('.menu-item-has-children a').on('focusout', function() {
				if($(this).parent().not('.menu-item-has-children').is(':last-child')) {
					$(this).parents('.menu-item-has-children').children('.da11y-submenu')
					.attr('aria-expanded', 'false').trigger('mouseleave')
					.siblings('.sub-menu').removeClass('da11y-submenu-show');
				}
			});

			/**
			 * Generate search form styles.
			 *
			 * @since Divi v3.0.23
			 */
			function et_set_search_form_css() {
				var search_container = $('.et_search_form_container');
				var body = $('body');

				if (search_container.hasClass('et_pb_search_visible')) {
					var header_height = $('#main-header').innerHeight();
					var menu_width = $('#top-menu').width();
					var font_size = $('#top-menu li a').css('font-size');

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
				var search_container = $('.et_search_form_container');

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
	})(jQuery);
	</script>

<?php

} // End if().

if ( $this->can_load( 'skip_navigation_link' ) ) {

?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

			/**
			 * Add skiplink to page.
			 */
			function skipTo(target) {
				var skiplink = '<a href="' + target + '" class="skip-link da11y-screen-reader-text">Skip to content</a>';

				$(target).attr('tabindex', -1);

				$('body').prepend(skiplink);
			}
			skipTo('#main-content');

			/**
			 * Use js to focus for internal links.
			 */
			$('a[href^="#"]').click(function () {
				var content = $('#' + $(this).attr('href').slice(1));

				content.focus();
			});

		});
	})(jQuery);
	</script>

<?php

} // End if().

if ( $this->can_load( 'keyboard_navigation_outline' ) ) {

?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

			var lastKey = new Date();
			var lastClick = new Date();

			/**
			 * Only apply focus styles for keyboard usage.
			 */
			$(this).on('focusin', function (e) {
				$('.keyboard-outline').removeClass('keyboard-outline');

				var wasByKeyboard = lastClick < lastKey;

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
	})(jQuery);
	</script>

<?php

} // End if().

if ( $this->can_load( 'focusable_modules' ) ) { ?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

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
	})(jQuery);
	</script>

<?php

} // End if().

if ( $this->can_load( 'fix_labels' ) ) {

?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

			/**
			 * Add unique ID to search module input with matching label.
			 *
			 * @divi-module  Search
			 */
			$('.et-search-field').each(function (e) {
				$(this).attr('id', 'et_pb_search_module_input_' + e);
				$('#et_pb_search_module_input_' + e).before('<label class="da11y-screen-reader-text" for="et_pb_search_module_input_' + e + '">Search for...</label>');
				$('#et_pb_search_module_input_' + e).after('<button type="submit" class="da11y-screen-reader-text">Search</button>');
			});

			/**
			 * Add unique ID to contact module input with matching label.
			 *
			 * @divi-module  Contact
			 */
			$('.et_pb_contact_form').each(function (e) {
				$(this).find('.et_pb_contact_captcha_question').parent().wrap('<label></label>');
			});
		});
	})(jQuery);
	</script>

<?php
}

if ( $this->can_load( 'aria_support' ) ) {

?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

			/**
			 * Add role="tabList".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tabs_controls').each(function () {
				$(this).attr('role', 'tabList');
			});

			/**
			 * Add role="presentation".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tabs_controls li').each(function () {
				$(this).attr('role', 'presentation');
			});

			/**
			 * Add role="tab".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tabs_controls a').each(function () {
				$(this).attr('role', 'tab');
			});

			/**
			 * Add role="tabpanel".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tab').each(function () {
				$(this).attr('role', 'tabpanel');
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
				$(this).attr('aria-selected', 'false');
				$(this).attr('aria-expanded', 'false');
				$(this).attr('tabindex', -1);
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
				$(this).attr('aria-selected', 'true');
				$(this).attr('aria-expanded', 'true');
				$(this).attr('tabindex', 0);
			});

			/**
			 * Add unique ID to tab controls.
			 * Add aria-controls="x".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tabs_controls a').each(function (e) {
				$(this).attr('id', 'et_pb_tab_control_' + e);
				$(this).attr('aria-controls', 'et_pb_tab_panel_' + e);
			});

			/**
			 * Add unique ID to tab panels.
			 * Add aria-labelledby="x".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tab').each(function (e) {
				$(this).attr('id', 'et_pb_tab_panel_' + e);
				$(this).attr('aria-labelledby', 'et_pb_tab_control_' + e);
			});

			/**
			 * Set initial inactive tab panels to aria-hidden="false".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tab.et_pb_active_content').each(function () {
				$(this).attr('aria-hidden', 'false');
			});

			/**
			 * Set initial inactive tab panels to aria-hidden="true".
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tab:not(.et_pb_active_content)').each(function () {
				$(this).attr('aria-hidden', 'true');
			});

			/**
			 * Add unique ID to tab module.
			 * Need to use data attribute because a regular ID somehow interferes with Divi.
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tabs').each(function (e) {
				$(this).attr('data-da11y-id', 'et_pb_tab_module_' + e);
			});

			/**
			 * Update aria-selected attribute when tab is clicked or when hitting enter while focused.
			 *
			 * @divi-module  Tab
			 */
			$('.et_pb_tabs_controls a').on('click', function () {
				var id = $(this).attr('id');
				var namespace = $(this).closest('.et_pb_tabs').attr('data-da11y-id'); // Used as a selector to scope changes to current module.

				// Reset all tab controls to be aria-selected="false" & aria-expanded="false".
				$('[data-da11y-id="' + namespace + '"] .et_pb_tabs_controls a')
					.attr('aria-selected', 'false')
					.attr('aria-expanded', 'false')
					.attr('tabindex', -1);

				// Make active tab control aria-selected="true" & aria-expanded="true".
				$(this)
					.attr('aria-selected', 'true')
					.attr('aria-expanded', 'true')
					.attr('tabindex', 0);

				// Reset all tabs to be aria-hidden="true".
				$('#' + namespace + ' .et_pb_tab')
					.attr('aria-hidden', 'true');

				// Label active tab panel as aria-hidden="false".
				$('[aria-labelledby="' + id + '"]')
					.attr('aria-hidden', 'false');
			});

			// Arrow navigation for tab modules
			$('.et_pb_tabs_controls a').keyup(function (e) {
				var namespace = $(this).closest('.et_pb_tabs').attr('data-da11y-id');
				var module = $('[data-da11y-id="' + namespace + '"]');

				if (e.which === 39) { // Right.
					var next = module.find('li.et_pb_tab_active').next();

					if (next.length > 0) {
						next.find('a').trigger('click');
					} else {
						module.find('li:first a').trigger('click');
					}
				} else if (e.which === 37) { // Left.
					var next = module.find('li.et_pb_tab_active').prev();

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
				$(this).attr('data-da11y-id', 'et_pb_search_module_' + e);
			});

			/**
			 * Add aria-required="true" to inputs.
			 *
			 * @divi-module  Contact Form
			 */
			$('[data-required_mark="required"]').each(function () {
				$(this).attr('aria-required', 'true');
			});

			/**
			 * Hide hidden error field on contact form.
			 *
			 * @divi-module  Contact Form
			 */
			$('.et_pb_contactform_validate_field').attr('type', 'hidden');

			/**
			 * Add alert role to error or success contact form message
			 *
			 * @divi-module  Contact Form
			 */
			$('.et-pb-contact-message').attr('role', 'alert');

			/**
			* Add main role to main-content
			*/
			$('#main-content').attr('role', 'main');

			/**
			 * Add aria-label="x".
			 *
			 * @divi-module  Fullwidth header, comment-wrap
			 */
			$('.et_pb_fullwidth_header').each(function (e) {
				$(this).attr('aria-label', 'Wide Header' + e);
			});
			$('#comment-wrap').attr('aria-label', 'Comments');
		});
	})(jQuery);
	</script>

<?php

} // End if().

if ( $this->can_load( 'aria_hidden_icons' ) ) {

?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

			/**
			 * Add aria-hidden="true" to all icons
			 */
			$('#et_top_search, .et_close_search_field, .et_pb_main_blurb_image').attr('aria-hidden', 'true');

            /**
             * Correct labels on social media icons
			 */
			$('.et-social-facebook a.icon span').html("facebook");
			$('.et-social-twitter a.icon span').html("twitter");
			$('.et-social-google-plus a.icon span').html("google plus");
			$('.et-social-pinterest a.icon span').html("pinterest");
			$('.et-social-linkedin a.icona span').html("linked in");
			$('.et-social-tumblr a.icon span').html("tumblr");
            $('.et-social-instagram a.icon span').html("instagram");
			$('.et-social-skype a.icon span').html("skype");
			$('.et-social-flikr a.icon span').html("flickr");
			$('.et-social-myspace a.icon span').html("my space");
			$('.et-social-dribbble a.icon span').html("dribble");
			$('.et-social-youtube a.icon span').html("you tube");
			$('.et-social-vimeo a.icon span').html("vimeo");
			$('.et-social-rss a.icon span').html("rss");
		});
	})(jQuery);
	</script>

<?php
}

if ( $this->can_load( 'aria_mobile_menu' ) ) {

?>

	<script type="text/javascript">
	(function ($) {
		$(document).ready(function () {

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
	})(jQuery);
	</script>

<?php
}
