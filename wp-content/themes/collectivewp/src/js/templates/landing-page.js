/**
 * File landing-page.js
 *
 * Landing page header scripts.
 *
 * @param {jQuery} $ - The jQuery object.
 * @author Thong Dang
 * @since  Sep 1, 2023
 */
( function ( $ ) {
	$( document ).ready( function () {
		$( window ).scroll( function () {
			if ( $( this ).scrollTop() >= 100 ) {
				$( '.site-header' ).addClass( 'scrolled' );
			} else {
				$( '.site-header' ).removeClass( 'scrolled' );
			}
		} );
	} );
} )( jQuery );
