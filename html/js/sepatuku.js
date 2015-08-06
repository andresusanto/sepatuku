$(document).ready(function() {
	$(".drawer").drawer();
	$("#menu-mobile").click(function() {
		$('.drawer').drawer('toggle');
	});	
});

$( document.body ).on( 'click', '.dropdown-menu li', function( event ) {
	var $target = $( event.currentTarget );

	$target.closest( '.btn-group' )
	 .find( '[data-bind="label"]' ).text( $target.text() )
		.end()
	 .children( '.dropdown-toggle' ).dropdown( 'toggle' );

	return false;

});

$('.s-checkout-as-guest').on('click', function() {
	window.location = 'shopping-cart.html';
});

$('.s-login-button').on('click', function() {
	window.location = 'product.html';
});