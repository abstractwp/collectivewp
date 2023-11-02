'use strict';
( function ( $ ) {
	$( document ).ready( function () {
		const slider = $( '.testimonial-slider' );
		slider.slick( {
			infinite: false,
			autoplay: false,
			autoplaySpeed: 5000,
			dots: false,
			prevArrow: $( '.slick-prev' ),
			nextArrow: $( '.slick-next' ),
		} );
	} );
} )( jQuery );
