/**
 * File resources-search.js
 *
 * Resources search scripts.
 *
 * @param {jQuery} $ - The jQuery object.
 * @author Thong Dang
 * @since  Aug 29, 2023
 */
( function ( $ ) {
	$( document ).ready( function () {
		const searchInput = $( '.resource-search .wp-block-search__input' );

		$( '.resource-search .wp-element-button' ).click( function ( event ) {
			event.preventDefault();

			const baseSiteUrl =
				window.location.protocol + '//' + window.location.host;

			const key = $( '.resource-search .wp-block-search__input' ).val();

			if ( key.length ) {
				const searchUrl =
					baseSiteUrl + '/resources/?_resource_search=' + key;
				window.location.href = searchUrl;
			} else {
				searchInput.focus();
				searchInput.addClass( 'error' );
			}
		} );

		// Remove empty-key class when input text changes
		searchInput.on( 'input', function () {
			if ( $( this ).val().length ) {
				$( this ).removeClass( 'error' ); // Remove class if input has text
			}
		} );
	} );
} )( jQuery );
