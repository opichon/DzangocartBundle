!function( $ ) {
	$.fn.orders = function( method ) {

		var settings;

		var methods = {
			init: function( options ) {

				settings = $.extend( true, {}, this.orders.defaults, options );

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

//                    var widget = $( "[name='filters[customer]']" );
//
//                    var customers = new Bloodhound({
//                        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
//                        queryTokenizer: Bloodhound.tokenizers.whitespace,
//                        remote: {
//                            url: settings.typeahead.remote.url,
//                            replace: function( url, uriEncodedQuery ) {
//                                return url.replace( "__query__", uriEncodedQuery );
//                            }
//                        }
//                    });
//
//                    customers.initialize();
//
//                    widget.typeahead( null, {
//                        name: "customer",
//                        displayKey: "value",
//                        source: customers.ttAdapter()
//                    })
//                    .on( "typeahead:selected", function( e, datum ) {
//                        $( "[name='filters[customer_id]']" ).val( datum.id );
//                            table.api().draw();
//                    });
//
//                    widget.keyup( function( ) {
//                        if ( $(this).val() === "" ) {
//                            $( "[name='filters[customer_id]']" ).val( "" );
//                            table.api().draw();
//                        }
//                    })
				});
			}
		};

		if ( methods[ method ] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
		}
		else if ( typeof method === "object" || !method)  {
			return methods.init.apply( this, arguments );
		}
		else {
			$.error( "Method " +  method + " does not exist in jQuery.orders." );
		}
	};

	$.fn.orders.defaults = {
		daterangepicker: {
			locale: { cancelLabel: "Clear" },
			maxDate: moment(),
			minDate: moment( "2009-01-01" ),
			startDate: moment()
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != "undefined" ) {
		$( ".dzangocart.orders" ).orders( dzangocart.orders || {} );
	}
});
