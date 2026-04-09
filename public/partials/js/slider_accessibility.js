jQuery(document).ready(function($) {

	function isActivationKey(event) {
		return event.which === 13 || event.which === 32;
	}

	function setArrowSupport($arrows) {
		$arrows.each(function() {
			var $arrow = $(this);
			var isPrev = $arrow.hasClass('et-pb-arrow-prev');

			$arrow.attr({
				'role': 'button',
				'tabindex': 0,
				'aria-label': isPrev ? 'Previous slide' : 'Next slide'
			});
		});
	}

	function updateDotStates($dots) {
		$dots.each(function(index) {
			var $dot = $(this);
			var isActive = $dot.hasClass('et-pb-active-control') || $dot.parent().hasClass('et-pb-active-control');

			$dot.attr({
				'aria-label': 'Go to slide ' + (index + 1),
				'aria-current': isActive ? 'true' : 'false'
			});
		});
	}

	function setDotSupport($dots) {
		$dots.attr({
			'role': 'button',
			'tabindex': 0
		});

		updateDotStates($dots);
	}

	function applySliderSupport() {
		var $sliders = $('.et_pb_slider, .et_pb_post_slider, .et_pb_video_slider');

		$sliders.each(function() {
			var $slider = $(this);
			setArrowSupport($slider.find('.et-pb-arrow-prev, .et-pb-arrow-next'));
			setDotSupport($slider.find('.et-pb-controllers a'));
		});
	}

	$(document).on('keydown', '.et-pb-arrow-prev, .et-pb-arrow-next, .et-pb-controllers a', function(event) {
		if (event.which === 32) {
			event.preventDefault();
		}
	});

	$(document).on('keyup', '.et-pb-arrow-prev, .et-pb-arrow-next, .et-pb-controllers a', function(event) {
		if (isActivationKey(event)) {
			$(this).trigger('click');
		}
	});

	$(document).on('click', '.et-pb-controllers a', function() {
		var $dots = $(this).closest('.et-pb-controllers').find('a');
		setTimeout(function() {
			updateDotStates($dots);
		}, 100);
	});

	applySliderSupport();
	$(window).on('load', applySliderSupport);
});
