!function( $ ) {
    $.fn.customers = function( method ) {

        var settings,
            table;

        var methods = {
            init: function( options ) {

                settings = $.extend( true, {}, this.customers.defaults, options );

                return this.each(function() {
                    var $this = $( this );

                    $( ".filters_keyup input" ).keyup(function(event) {
                        event.stopPropagation();
                        table.api().draw();
                    });

                    $( ".filters select", $this ).change(function() {
                        table.api().draw();
                    });

                    table = $( "table.table", this ).dataTable( $.extend( true, {}, settings.datatables, {
                        initComplete: function( settings, json ) {
                            $( this ).show();
                        },
                        ajax: {
                            data: function( data ) {
                                $( ".filters input, .filters select", $this ).each(function() {
                                    data[$( this ).attr( "name" )] = $( this ).val()
                                });
                            }
                        },
                        stateLoadParams: function( settings, data ) {
                            $( ".filters input, .filters select", $this ).each(function() {
                                $( this ).val( data[ $( this ).attr( "name" ) ] );
                            });
                        },
                        stateSaveParams: function( settings, data ) {
                            $( ".filters input, .filters select", $this ).each(function() {
                                data[ $( this ).attr( "name" ) ] = $( this ).val();
                            });
                        }
                    } ) );
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

    $.fn.customers.defaults = {
        datatables: {
            autoWidth: false,
            columns: [
                { data: "check" },
                { data: "name" },
                { data: "gender" },
                { data: "email" },
                { data: "ytdsales" },
                { data: "actions" }
            ],
            columnDefs: [
                { visible: false, targets: [ 0 ] },
                { orderable: false, targets: [ 0, 5 ] },
                { className: "number", targets: [ 4 ] },
                { className: "actions", targets: [ 5 ] }
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
        }
    };
} ( window.jQuery );

$( document ).ready(function() {
    if ( typeof dzangocart != 'undefined' ) {
        $( ".dzangocart.customers" ).customers( dzangocart.customers );
    }
});
