$( document ).ready(function() {
    $( document ).ajaxError(function( event, jqXHR ) {
        if ( 403 === jqXHR.status ) {
            window.location.reload();
        }
    });
});
