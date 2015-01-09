/**
 * @file
 * Custom JavaScript file for Sandvik Coromant child theme.
 */
( function( $, window, document, undefined ){

	// #header & #footer slide in & out of viewport on scroll
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

	// clicking in the #menu should close modal
	$( '#menu .modal-dialog').click( function(){
		$( '#menu' ).modal( 'toggle' );
	})

	// enable fluidbox functionality
	$( 'a[rel="lightbox"]' ).fluidbox({
		overlayColor: 'rgba( 247, 247, 247, 0.95 )',
		closeTrigger: [{ selector: 'window', event: 'scroll' }]
	});

	// manually add a shadow to the fullscreen image when it is enabled with a stroke
	// if( $( 'a[rel="lightbox"] img' ).hasClass( 'stroke' )){
		// $( 'a[rel="lightbox"] .fluidbox-ghost' ).addClass( 'shadow' );
	// }


	// #scroll to top functionality
	$( '#scroll_to_top' ).click( function( event ){
		event.preventDefault();
		$( window ).scrollTop( 0 );
	});

})( jQuery, this, this.document );
