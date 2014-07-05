!function( $ ) {
    $.fn.sales = function( method ) {

        var settings,
            table;

        var methods = {
            init: function( options ) {

                settings = $.extend( true, {}, this.sales.defaults, options );

                return this.each(function() {
                    var $this = $( this );

                    table = $( "table.table", this ).dataTable( $.extend( true, {}, settings.dataTables, {
                        initComplete: function( settings, json ) {
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
                            $( ".filters :checkbox", $this ).each(function() {
                                $( this ).attr( "checked", data[ $( this ).attr( "name" ) ] );
                            });

                            $( ".filters input", $this ).each(function() {
                                $( this ).val( data[ $( this ).attr( "name" ) ] );
                            });
                        },
                        stateSaveParams: function( settings, data ) {
                            $( ".filters :checkbox", $this ).each(function() {
                                data[ $( this ).attr( "name" ) ] = $( this ).is( ":checked" );
                            });

                            $( ".filters input", $this ).each(function() {
                                data[ $( this ).attr( "name" ) ] = $( this ).val();
                            });
                        }
                    } ) );

                    moment.lang( dzangocart.locale );

                    $( ".filters .period", this ).daterangepicker(
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
                    )
                    .data( "daterangepicker" ).updateInputText();

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
            $.error( "Method " +  method + " does not exist in jQuery.sales." );
        }
    };

    $.fn.sales.defaults = {
        dataTables: {
            autoWidth: false,
            columns: [
                { data: "check" },
                { data: "date" },
                { data: "order_id" },
                { data: "customer" },
                { data: "item" },
                { data: "quantity" },
                { data: "amount_excl" },
                { data: "tax_amount" },
                { data: "amount_incl" },
                { data: function( row, type, val, meta ) {
                        if ( "display" == type ) {
                            return row.paid == 1
                                ? "<i class='fa fa-thumbs-o-up'></i>"
                                : "<i class='fa fa-exclamation-triangle'></i>";
                        }
                    }
                },
                { data: "affiliate" },
                { data: function( row, type, val, meta ) {
                        if ( "display" == type ) {
                            return row.test
                                ? "<i class='fa fa-asterisk'></i>"
                                : "";
                        }
                    }
                },
                { data: "actions" }
            ],
            columnDefs: [
                { visible: false, targets: [ 0 ] },
                { orderable: false, targets: [ 0, 5, 9, 12 ] },
                { className: "number", targets: [ 5, 7, 8, 9 ] },
                { className: "center", targets: [ 9, 11 ] },
                { className: "actions", targets: [ 12 ] }
            ],
            language: {
                url: "/bundles/dzangocart/datatables/" + dzangocart.locale + ".json"
            },
            orderable: true,
            orderCellsTop: true,
            paging: true,
            processing: true,
            searching: false,
            serverSide: true,
            stateSave: false,
            stripeClasses: []
        },
        daterangepicker: {
            ranges: {
                "MTD": [moment().startOf( "month" ), moment()],
                "Last Month": [
                    moment().subtract( "month", 1).startOf( "month" ),
                    moment().subtract( "month", 1).endOf( "month" )
                ],
                "QTD": [
                    moment().month( moment().quarter() * 3 ).subtract( "month", 3).startOf( "month" ),
                    moment()
                ],
                "Last quarter": [
                    moment().month( (moment().quarter() - 1) * 3 ).subtract( "month", 3 ).startOf( "month" ),
                    moment().month( (moment().quarter() - 1) * 3 ).subtract( "month", 1 ).endOf( "month" )
                ],
                "YTD": [moment().startOf( "year" ), moment()],
                "Last Year": [
                    moment().subtract( "year", 1 ).startOf( "year"),
                    moment().subtract( "year", 1 ).endOf( "year" )
                ]
            },
            startDate: moment(),
            locale: { cancelLabel: 'Clear' },
            maxDate: moment(),
            minDate: moment('2009-01-01')
        }
    };
} ( window.jQuery );

$( document ).ready(function() {
    if ( typeof dzangocart != 'undefined' ) {
        $( ".dzangocart.sales" ).sales( dzangocart.sales );
    }
});
