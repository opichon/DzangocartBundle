!function( $ ) {
	$.fn.sales = function( method ) {

		var settings,
			table;

		var methods = {
			init: function( options ) {

				settings = $.extend( true, {}, this.sales.defaults, options );

				return this.each(function() {
					var $this = $( this );

					table = $( "table.table", this ).DataTable( $.extend( true, {}, settings.dataTables, {
                        drawCallback: function() {
							$( this ).show();
						},
                        serverParams: function( data ) {
                            $( ".filters :checkbox", $this ).each(function() {
                                data[$( this ).attr( "name" )] = $( this ).is( ":checked" ) ? 1 : 0;
                            });

                            $( ".filters input", $this ).each(function() {
                                data[$( this ).attr( "name" )] = $( this ).val();
                            });
                        },
						stateLoadParams: function( oSettings, oData ) {
							$( ".filters :checkbox", $this ).each(function() {
								$( this ).attr( "checked", oData[ $( this ).attr( "name" ) ] );
							});

							$( ".filters input", $this ).each(function() {
								$( this ).val( oData[ $( this ).attr( "name" ) ] );
							});
						},
						stateSaveParams: function( oSettings, oData ) {
							$( ".filters :checkbox", $this ).each(function() {
								oData[ $( this ).attr( "name" ) ] = $( this ).is( ":checked" );
							});

							$( ".filters input", $this ).each(function() {
								oData[ $( this ).attr( "name" ) ] = $( this ).val();
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
							table.draw();
						}
					).data( "daterangepicker" ).updateInputText();

					$( ".filters input", $this ).change(function() {
						table.draw();
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
			columnDefs: [
				{ visible: false, targets: [ 0 ] },
				{ orderable: false, targets: [ 0, 5, 10, 13 ] },
				{ className: "number", targets: [ 5, 7, 8, 9 ] },
				{ className: "center", targets: [ 10, 12 ] },
				{ className: "actions", targets: [ 13 ] }
			],
			stripeClasses: [],
			autoWidth: false,
			paging: true,
			processing: true,
			serverSide: true,
			orderable: true,
			stateSave: true,
			language: {
				url: "/bundles/uamdatatables/lang/" + dzangocart.locale + ".txt"
			},
            //[removed in datatable 1.10]
			//sCookiePrefix: "dzangocart_"
		},
		daterangepicker: {
			minDate: moment('2009-01-01'),
			maxDate: moment()
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.sales" ).sales( dzangocart.sales );
	}
});
