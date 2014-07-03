!function( $ ) {
	$.fn.sips = function( method ) {

		var settings,
			table;

		var methods = {
			init: function( options ) {

				settings = $.extend( true, {}, this.sips.defaults, options );

				return this.each(function() {
					var $this = $( this );

					table = $( "table.table", this ).dataTable( $.extend( true, {}, settings.dataTables, {
                        initComplete: function( settings, json ) {
                            $( this ).show();
                        },
                        ajax: {
                            data: function( d ) {
                                $( ".filters input, .filters select" ).each(function() {
                                    var name = $( this ).attr( "name" ),
                                        value = $( this ).attr( "type" ) == "checkbox"
                                            ? ($( this ).is( ":checked" ) ? $( this ).val() : 0)
                                            : $( this ).val();

                                    d[name] = value;
                                } );
                            }
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
							table.fnDraw();
						}
					).data( "daterangepicker" ).updateInputText();

					$( ".filters input", $this ).change(function() {
						table.fnDraw();
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
			$.error( "Method " +  method + " does not exist in jQuery.sips." );
		}
	};

	$.fn.sips.defaults = {
		dataTables: {
			autoWidth: false,
			columnDefs: [
				{ orderable: false, targets: [ 0, 12 ] },
				{ visible: false, targets: [ 0 ] },
				{ classname: "number", targets: [ 4 ] },
				{ classname: "actions", targets: [ 12 ] }
			],
			language: {
				url: "/bundles/dzangocart/datatables/" + dzangocart.locale + ".json"
			},
			orderable: true,
			paging: true,
			processing: true,
			serverSide: true,
			stateSave: true,
			stripeClasses: [],
		},
		daterangepicker: {
			minDate: moment('2009-01-01'),
			maxDate: moment()
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.sips" ).sips( dzangocart.sips );
	}
});
