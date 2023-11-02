'use strict';
( function ( $ ) {
	$( document ).ready( function () {
		const slider = $( '.contents-slider' );
		slider.slick( {
			infinite: false,
			autoplay: false,
			autoplaySpeed: 5000,
			dots: false,
			prevArrow: $( '.content-slick-prev' ),
			nextArrow: $( '.content-slick-next' ),
		} );
	} );
} )( jQuery );
