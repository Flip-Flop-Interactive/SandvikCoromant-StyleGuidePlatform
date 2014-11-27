/**
 * @file
 * A JavaScript file for the theme.
 */
(function($, window, document, undefined) {
  var header = {}, headerHeadroom = {}, footer = {}, footerHeadroom = {}, bgImg = '';
  
  header = document.querySelector( '#header' );
  headerHeadroom = new Headroom( header );
  headerHeadroom.init();

  footer = document.querySelector( '#footer' );
  footerHeadroom = new Headroom( footer );
  footerHeadroom.init();

// END
})(jQuery, this, this.document);
