/**
 * @file
 * A JavaScript file for the theme.
 */
(function($, window, document, undefined) {
  var header = {}, headerHeadroom = {}, footer = {}, footerHeadroom = {}, bgImg = '';
  
  // Intitalize Headroom.js to various elements
  // var elements = [ '#header', '#footer' ];
  // var i = elements.length;
  // while( --i ){

  // 	var element = document.querySelector( elements[ i ]);
  // 	headroom = new Headroom( element );
  // 	headroom.init();
  // }

  header = document.querySelector( '#header' );
  headerHeadroom = new Headroom( header );
  headerHeadroom.init();

  footer = document.querySelector( '#footer' );
  footerHeadroom = new Headroom( footer );
  footerHeadroom.init();

  /**
  * background image
  */
  bgImg = '/wp-content/themes/twentyfifteen-child/images/bg-CoroTurnRC.jpg';
  $("body").backstretch(bgImg);

// END
})(jQuery, this, this.document);
