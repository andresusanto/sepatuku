jQuery.ccDialogBox = function(message) {
    var closeDialog = function() {
        $('#cc-dialog-overlay, #cc-dialog-box').hide();
    };
    var closeText = 'Close';
    if ((window.messages != undefined) && ('close' in window.messages)) {
        closeText = window.messages['close'];
    }
    $('embed').css('visibility', 'hidden');
    if (!$('#cc-dialog-box').length) {
        $('body').append('<div id="cc-dialog-overlay"></div><div id="cc-dialog-box"><div id="cc-dialog-message"></div><div id="cc-dialog-close"><span>' + closeText + '</span></div></div>');
    }
    $('#cc-dialog-message').html(message.replace(/\n/g, '<br />'));
    var boxTop = $(window).height() / 3 - $('#cc-dialog-box').height() / 2;
    var boxLeft = $(window).width() / 2 - $('#cc-dialog-box').width() / 2;
    $('#cc-dialog-box').css({top: boxTop + $(window).scrollTop(), left: boxLeft});
    $('#cc-dialog-overlay').css({height: $(document).height()});
    $('#cc-dialog-overlay, #cc-dialog-box').show();
    $('#cc-dialog-close span, #cc-dialog-overlay').bind('click', function() {
        closeDialog();
        $('embed').css('visibility', 'visible');
    });
};