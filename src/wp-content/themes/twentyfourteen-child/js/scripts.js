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

	// if user has targeted a link by #id, smooth scroll up a bit so it won't be obscured
	if (location.hash.length > 1) {
		var $target = $(location.hash), offsetY = $('#header').height();
		if ($target.length) {
			$('html,body').animate({
				scrollTop: $target.offset().top - offsetY
			}, 700);
		}
	}
	
	// #menu links should close modal
	$( "#menu a" ).click( function(){
		$( '#menu' ).modal( 'toggle' );
	});


	// Get dynamic data within Image modal and display
	$( '.entry-image' ).click( function(){

		var target 	= $( this ).data( 'target-url' );
		var image 	= $( '<img/>' );
			image.attr( 'src', target );
		var close   = $( '<a href="#" class="close" title="Close" data-dismiss="modal"><i class="icon icon_zoom-icon"></i></a>')

		$( '#image .modal-dialog' ).empty();
		$( '#image .modal-dialog' ).append( image );
		$( '#image .modal-dialog' ).append( close );
	});




// END
})(jQuery, this, this.document);
