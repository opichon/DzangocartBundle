!function( $ ) {
    $.fn.payments = function( method ) {

        var settings;

        var methods = {
            init: function( options ) {

                settings = $.extend( true, {}, this.payments.defaults, options );

                return this.each(function() {
                    var $this = $( this );

                    moment.lang( dzangocart.locale );

                    $( ".filters .period", this ).daterangepicker(
                        $.extend( true, {}, settings.daterangepicker,
                            {
                                startDate: moment( $( ".filters .date_from", $this ).val(), "YYYY-MM-DD" ),
                                endDate: moment( $( ".filters .date_to", $this ).val(), "YYYY-MM-DD" )
                            }
                        ),
                        function( start, end ) {
                            $( ".filters .date_from", $this ).attr( "value", start.format( "YYYY-MM-DD" ) ).change();
                            $( ".filters .date_to", $this ).attr( "value", end.format( "YYYY-MM-DD" ) ).change();
                        }
                    );

                    $this.uamdatatables( settings.uamdatatables );
                });
            }
        };

        var helpers = {
            initGatewayChoice: function( data ) {
                var gatewayChoiceWidget = $( '#filters_gateway_id' );

                var choices = "";
                if ( gatewayChoiceWidget.find( 'option' ).length <= 1 ) {

                    for ( var i = 0; i < data.length; i++ ) {
                        choices = choices + '<option value="' + data[i]['id'] + '">' +  data[i]['value'] +'</option>';
                    }
                    gatewayChoiceWidget.append( choices );
                }
            }
        };

        if ( methods[ method ] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
        }
        else if ( typeof method === "object" || !method ) {
            return methods.init.apply( this, arguments );
        }
        else {
            $.error( "Method " +  method + " does not exist in jQuery.payments." );
        }
    };

    $.fn.payments.defaults = {
        datatables: {
            stripeClasses: [],
            autoWidth: false,
            paging: true,
            processing: true,
            serverSide: true,
            orderable: true,
            stateSave: false,
            searching: false,
            orderCellsTop: true,
            language: {
                url: "/bundles/dzangocart/datatables/" + dzangocart.locale + ".json"
            }
        },
        daterangepicker: {
            locale: { cancelLabel: "Clear" },
            maxDate: moment(),
            minDate: moment( "2009-01-01" ),
            startDate: moment()
        }
    };
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.payments" ).payments( dzangocart.payments );
	}
});
