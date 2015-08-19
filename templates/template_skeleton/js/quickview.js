$.fn.ccProductQuickView = function (callback) {
    var apiUrl = $(this).attr('href');
    var delay = 0;
    var param = apiUrl.substr(apiUrl.indexOf("?"),apiUrl.length);
    if (window.isFakeView) {
        apiUrl = '/hidden/view_test/Store_ProductDetails/testOddId/exec'+param;
        delay = 2000;
    }
    if (window.isTDKView) {
        apiUrl = '/pages/product_details/tests/1-normal/execute'+param;
        delay = 2000;
    }    
    setTimeout(function () {
        $.get(apiUrl, function (data) {
            callback(data);
        });
    }, delay);
};

$(document).ready(function(){
	
	//dialog box
	window.alert = $.ccDialogBox;
	
	$('a.quickview').click(function (ev) {
	ev.preventDefault();
	$('body').append('<div id="screen-overlay"></div>\n\
	                            <div id="screen_quickview">\n\
	                                    <div class="loading-ajax"><span>Please Wait...</span></div>\n\
	                            </div>');
	 var positionTop = 50;
	 var positionLeft = $(window).width() / 2 - $('#screen_quickview').width() / 2;
	 $('#screen_quickview').css({top: positionTop + $(window).scrollTop(), left: positionLeft});
	 $('#screen-overlay').show();
	 $('#screen_quickview').show();
	 //var x = $(this).attr('href');
	 //alert(x);
	 $(this).ccProductQuickView(function (data) {
	    $('.loading-ajax').remove();
	     $('#screen_quickview').html('<div class="quick_view">'+data+'\n\
	                                                <a href="/" id="close_screen"></a>\n\
	                                            </div>\n\
	     ');
	    $('#screen_quickview').append('<script type="text/javascript" src="../js/quickview.js"></script>');
	    $('#screen_quickview').append('<script type="text/javascript" src="../js/cloud-zoom.1.0.3-min.js"></script>');
	
	    $("body").on("click", "a#button_close",function(e){
	                e.preventDefault();
	                closeBox();
	     });
	     $("body").on("click", "a#close_screen",function(ev){
	                ev.preventDefault();
	                closeScreen();
	     });
	});
	});
    

	function closeBox() {
	    $('#dialog-overlay, #dialog-box').remove();
	};
	function closeScreen() {
	    $('#screen-overlay, #screen_quickview').remove();
	};    
});

jQuery.fn.extend({
  facebooklogin: function (returnURL) {
    $(this).click(function(event) {
      FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          console.log('Logged in.');
          var access_token =   FB.getAuthResponse()['accessToken'];
          console.log(access_token);
          FB.api('/me', function(response) {
            console.log('Good to see you, ' + response.name + '. Your email is: '+response.email);
            response.facebook_token = access_token;
            console.log(response);
            $.ajax({
              type: "POST",
              url: '/account/loginFacebook',
              data:response,
              success: function(a){
                window.location = returnURL;
              }});
          });
        }
        else {
          FB.login(function(response) {
            // automatically try to login to SIRCLO if facebook login successful
            if (response.authResponse) {
              $(this).click();
            }
          }, {
            scope: 'email',
            return_scopes: true
          });
        }
      });
    });
  }
});

