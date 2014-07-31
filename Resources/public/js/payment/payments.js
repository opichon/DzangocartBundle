!function( $ ) {
    $.fn.payments = function( method ) {

        var settings,
            table;

        var methods = {
            init: function( options ) {

                settings = $.extend( true, {}, this.payments.defaults, options );

                return this.each(function() {
                    var $this = $( this );

                    $( ".filters select" ).change(function(event) {
                        event.stopPropagation();
                        table.api().draw();
                    });

                    $( ".filters input" ).keyup(function(event) {
                        event.stopPropagation();
                        table.api().draw();
                    });

                    table = $( "table.table", this ).dataTable( $.extend( true, {}, settings.datatables, {
                        drawCallback: function( settings ) {
                            helpers.initGatewayChoice( settings.json.gateways );
                            $( this ).show();
                        },
                        ajax: {
                            data: function( data ) {
                                $( ".filters input, .filters select", $this ).each(function() {
                                    data[$( this ).attr( "name" )] = $( this ).val()
                                });

                                $( ".filters :checkbox", $this ).each(function() {
                                    data[$( this ).attr( "name" )] = $( this ).is( ":checked" ) ? 1 : 0
                                });

                            }
                        },
                        stateLoadParams: function( settings, data ) {
                            $( ".filters input, .filters select", $this ).each(function() {
                                $( this ).val( data[ $( this ).attr( "name" ) ] );
                            });

                            $( ".filters :checkbox", $this ).each(function() {
                                $( this ).attr( "checked", data[ $( this ).attr( "name" ) ] );
                            });
                        },
                        stateSaveParams: function( settings, data ) {
                            $( ".filters input, .filters select", $this ).each(function() {
                                data[ $( this ).attr( "name" ) ] = $( this ).val();
                            });

                            $( ".filters :checkbox", $this ).each(function() {
                                data[ $( this ).attr( "name" ) ] = $( this ).is( ":checked" );
                            });
                        }
                    } ) );


                    moment.lang( dzangocart.locale );

                    $( ".filters .period", $this ).daterangepicker(
                        $.extend( true, {}, settings.daterangepicker,
                            {
                                startDate: moment( $( ".filters .date_from", $this ).val(), "YYYY-MM-DD" ),
                                endDate: moment( $( ".filters .date_to", $this ).val(), "YYYY-MM-DD" )
                            }
                        ),
                        function( start, end ) {
                            $( ".filters .date_from", $this ).val( start.format( "YYYY-MM-DD" ) );
                            $( ".filters .date_to", $this ).val( end.format( "YYYY-MM-DD" ) );
                            table.api().draw();
                        }
                    ).data( "daterangepicker" ).updateInputText();

                    $( ".filters input", $this ).change(function() {
                        table.api().draw();
                    });
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
            columns: [
                { data: "check" },
                { data: "date" },
                { data: "order_id" },
                { data: "gateway" },
                { data: "type" },
                { data: "amount" },
                { data: "status" },
                { data: "test" },
                { data: "actions" }
            ],
            columnDefs: [
                { orderable: false, targets: [ 0, 8 ] },
                { visible: false, targets: [ 0 ] },
                { className: "number", targets: [ 5 ] },
                { className: "actions", targets: [ 8 ] }
            ],
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
            locale: { cancelLabel: 'Clear' },
            maxDate: moment(),
            minDate: moment('2009-01-01'),
            startDate: moment()
        }
    };
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.payments" ).payments( dzangocart.payments );
	}
});
