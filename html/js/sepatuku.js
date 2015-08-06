// MENU DRAWER MOBILE
$(document).ready(function() {
	$(".drawer").drawer();
	$("#menu-mobile").click(function() {
		$('.drawer').drawer('toggle');
	});	
});


// DROPDOWN
$( document.body ).on( 'click', '.dropdown-menu li', function( event ) {
	var $target = $( event.currentTarget );

	$target.closest( '.btn-group' )
	 .find( '[data-bind="label"]' ).text( $target.text() )
		.end()
	 .children( '.dropdown-toggle' ).dropdown( 'toggle' );

	return false;

});

// CHECKOUT AS GUEST ON CLICK
$('.s-checkout-as-guest').on('click', function() {
	window.location = 'shopping-cart.html';
});

// LOGIN ON CLICK
$('.s-login-button').on('click', function() {
	window.location = 'product.html';
});

// ELEVATE ZOOM
$("#product-zoom").elevateZoom({
	zoomType : "inner",
    gallery : "product-zoom-gallery",
    galleryActiveClass: "active"
}); 
