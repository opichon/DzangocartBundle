!function( $ ) {
    $.fn.direct_payments = function( method ) {

        var settings,
            table;

        var methods = {
            init: function( options ) {

                settings = $.extend( true, {}, this.direct_payments.defaults, options );

                return this.each(function() {
                    var $this = $( this );

                    table = $( "table.table", this ).dataTable( $.extend( true, {}, settings.dataTables, {
                        drawCallback: function() {
                            $( this ).show();
                        },
                        ajax: {
                            data: function( data ) {
                                $( ".filters input", $this ).each(function() {
                                    data[$( this ).attr( "name" )] = $( this ).val()
                                });

                                $( ".filters :checkbox", $this ).each(function() {
                                    data[$( this ).attr( "name" )] = $( this ).is( ":checked" ) ? 1 : 0
                                });

                            }
                        },
                        stateLoadParams: function( settings, data ) {
                            $( ".filters input", $this ).each(function() {
                                $( this ).val( data[ $( this ).attr( "name" ) ] );
                            });

                            $( ".filters :checkbox", $this ).each(function() {
                                $( this ).attr( "checked", data[ $( this ).attr( "name" ) ] );
                            });
                        },
                        stateSaveParams: function( settings, data ) {
                            $( ".filters input", $this ).each(function() {
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

    $.fn.direct_payments.defaults = {
        dataTables: {
            columns: [
                { data: "check" },
                { data: "date" },
                { data: "order_id" },
                { data: "amount" },
                { data: "bank" },
                { data: "type" },
                { data: "cheque_number" },
                { data: "test" },
                { data: "actions" }
            ],
            columnDefs: [
                { orderable: false, targets: [ 0, 8 ] },
                { visible: false, targets: [ 0 ] },
                { className: "number", targets: [ 2 ] },
                { className: "actions", targets: [ 8 ] }
            ],
            stripeClasses: [],
            autoWidth: false,
            paging: true,
            processing: true,
            serverSide: true,
            orderable: true,
            stateSave: true,
            searching: false,
            language: {
                url: "/bundles/dzangocart/datatables/" + dzangocart.locale + ".json"
            }
        },
        daterangepicker: {
            minDate: moment('2009-01-01'),
            maxDate: moment()
        }
    };
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.direct_payments" ).direct_payments( dzangocart.direct_payments );
	}
});
