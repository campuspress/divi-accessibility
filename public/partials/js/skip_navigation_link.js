jQuery(document).ready(function($) {

	/**
	 * Add skiplink to page.
	 */
	function skipTo(target) {
		const skiplink = '<a href="' + target + '" class="skip-link da11y-screen-reader-text">Skip to content</a>';
		$(target).attr('tabindex', -1);
		$('body').prepend(skiplink);
	}
	skipTo('#main-content');

	/**
	 * Use js to focus for internal links.
	 */
	$('a[href^="#"]').click(function () {
		const content = $('#' + $(this).attr('href').slice(1));
		content.focus();
	});

});

