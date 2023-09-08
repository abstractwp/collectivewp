/**
 * File resources.js
 *
 * Resources custom scripts.
 *
 * @author Thong Dang
 * @since January 29, 2022
 */

( function ( $ ) {
	$( document ).ready( function () {
		resizeResourceImage();
	} );

	$( window ).resize( function () {
		resizeResourceImage();
	} );

	$( document ).on( 'facetwp-loaded', function () {
		resizeResourceImage();

		// Hide empty filter option.
		$( '.facetwp-dropdown' ).each( function () {
			$( this ).hide();
			if ( $( this ).find( 'option' ).length > 1 ) {
				$( this ).show();
			}
		} );
	} );

	function resizeResourceImage() {
		$( '.resource-img' ).each( function () {
			$( this ).height( $( this ).width() );
		} );
	}
} )( jQuery );
