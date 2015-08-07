var isTouchDevice = 'ontouchstart' in document.documentElement;

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

// ADD TO CART ON CLICK
$('.s-add-to-cart').on('click', function() {
	window.location = 'shopping-cart.html';
});

// ELEVATE ZOOM
$("#product-zoom").elevateZoom({
	zoomType : "inner",
    gallery : "product-zoom-gallery",
    galleryActiveClass: "active"
}); 

// STATIC PAGE SIDEBAR
if (!isTouchDevice) {
	$(".s-static-sidebar > ul > li").hover(function(){
		if (!$(this).hasClass('active')) {
			$(this).children('ul').slideDown();
		}
	}, function(){
		if (!$(this).hasClass('active')) {
			$(this).children('ul').slideUp();
		}
	});
}

// SHOPPING CART CLICK CHECKOUT
$('.s-to-checkout-page').on('click', function() {
	window.location = 'checkout.html';
});

// CHECKOUT SUBMIT
$('.s-checkout-submit').on('click', function(e) {
	e.preventDefault();
	alert('Thank you for trusting us!')
});