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
  $("#menu a").click(function(){
    $('#menu').modal('toggle');
  });

// END
})(jQuery, this, this.document);
