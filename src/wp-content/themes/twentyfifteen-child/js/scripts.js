
// Intitalize Headroom.js to various elements
var elements = [ '#header', '#footer' ];
var i = elements.length;
while( --i ){

	var element = document.querySelector( elements[ i ]);
	var headroom = new Headroom( element );
	headroom.init();
}
