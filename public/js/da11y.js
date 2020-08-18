;( function( $ ) {
	const outline = ( ( window || {} )._da11y || {} ).active_outline_color || false;
	if ( outline ) {
		$( document ).on( 'focusin', 'input,button,a[role="tab"]', function() {
			const $me = $( this );
			setTimeout( function() {
				if ( $me.is( '.keyboard-outline' ) ) {
					$me.css( 'outline-color', outline );
				}
			} );
		} );
	}
} )( jQuery );
