

// This is for the Alerts message
window.setTimeout(function() {
    $("#Success-Alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);