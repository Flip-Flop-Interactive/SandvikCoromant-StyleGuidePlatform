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

// END
})(jQuery, this, this.document);
