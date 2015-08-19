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
	window.location = 'account-info.html';
});

// ADD TO CART ON CLICK
$('.s-add-to-cart').on('click', function() {
	window.location = 'shopping-cart.html';
});

// PRODUCT DETAIL ON CLICK
$('.s-button-detail').on('click', function() {
	window.location = 'product-detail.html';
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
$('.s-checkout-submit').on('click', function() {
	alert('Thank you for trusting us!')
});

// CHANGE PASSWORD
$('.s-change-password-submit').on('click', function() {
	alert('Password Changed!')
});

// LOOKBOOK			
$(function() {
	var GammaSettings = {
			// order is important!
			viewport : [ {
				width : 1200,
				columns : 5
			}, {
				width : 900,
				columns : 4
			}, {
				width : 500,
				columns : 3
			}, { 
				width : 320,
				columns : 2
			}, { 
				width : 0,
				columns : 2
			} ]
	};

	Gamma.init( GammaSettings );

});

$('.gamma-gallery li').on("click", function() {
	alert('click image. ganti dengan script yang diinginkan atau hapus alert ini bila ingin menampilkan gambar.');
});

// SLIDER TESTIMONY
$(document).ready(function(){
	$('.quote-slider').bxSlider({
		slideWidth: 1000,
		minSlides: 1,
		maxSlides: 1,
		slideMargin: 10
	});
});

// SLIDER HOME
$(document).ready(function(){
	$('.home-slider').bxSlider({
		minSlides: 1,
		maxSlides: 1,
		controls: false,
		auto: true
	});
});

$('#see-all-products').on('click', function() {
	window.location = 'product.html';
});

$('#see-labels').on('click', function() {
	window.location = 'product.html';
});

// POPUP PRODUCT
$('.s-products a').on('click', function(e) {
	e.preventDefault();
	$('.s-show-product').show();
});

$('.s-close').on('click', function() {
	$('.s-show-product').hide();
});

$('.s-overlay').on('click', function() {
	$('.s-show-product').hide();
});