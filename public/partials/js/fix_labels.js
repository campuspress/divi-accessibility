jQuery(document).ready(function($) {

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

	/**
	* Correct labels on social media icons
	*/
	$('.et-social-facebook a.icon span').text('Facebook');
	$('.et-social-twitter a.icon span').text('Twitter');
	$('.et-social-google-plus a.icon span').text('Google Plus');
	$('.et-social-pinterest a.icon span').text('Pinterest');
	$('.et-social-linkedin a.icon span').text('LinkedIn');
	$('.et-social-tumblr a.icon span').text('Tumblr');
	$('.et-social-instagram a.icon span').text('Instagram');
	$('.et-social-skype a.icon span').text('Skype');
	$('.et-social-flikr a.icon span').text('Flickr');
	$('.et-social-myspace a.icon span').text('Myspace');
	$('.et-social-dribbble a.icon span').text('Dribble');
	$('.et-social-youtube a.icon span').text('YouTube');
	$('.et-social-vimeo a.icon span').text('Vimeo');
	$('.et-social-rss a.icon span').text('RSS');

});

