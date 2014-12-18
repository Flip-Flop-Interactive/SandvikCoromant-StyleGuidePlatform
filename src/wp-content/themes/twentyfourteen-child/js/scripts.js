/**
 * @file
 * A JavaScript file for the theme.
 */
(function($, window, document, undefined) {

	var header = {}, headerHeadroom = {}, footer = {}, footerHeadroom = {};
	
	header = document.querySelector( '#header' );
	if( header ){
		headerHeadroom = new Headroom( header );
		headerHeadroom.init();
	}

	footer = document.querySelector( '#footer' );
	if( footer ){
		footerHeadroom = new Headroom( footer );
		footerHeadroom.init();
	}

	// #menu links should close modal
	$( '#menu a' ).click( function(){
		$( '#menu' ).modal( 'toggle' );
	});

	// #scroll to top functionality
	$( '#scroll_to_top' ).click( function( event ){
		event.preventDefault();
		$( window ).scrollTop( 0 );
	});

	// Prevent bounce event in iOS devices
	$( document ).on( 'touchmove', function( event ){
		event.preventDefault();
	})

// END
})(jQuery, this, this.document);
