$(document).ready(function() {
	$(".drawer").drawer();
	$("#menu-mobile").click(function() {
		$('.drawer').drawer('toggle');
	});	
});