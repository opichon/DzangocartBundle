!function( $ ) {
	$.fn.orders = function( method ) {

		var settings,
			table;

		var methods = {
			init: function( options ) {

				settings = $.extend( true, {}, this.orders.defaults, options );

				return this.each(function() {
					var $this = $( this );

					table = $( 'table.table', this ).dataTable( $.extend( true, {}, settings.dataTables, {
						fnDrawCallback: function() {
							$( this ).show();
						},
 						fnServerParams: function( data ) {
							$( ".filters :checkbox", $this ).each(function() {
								data.push({
									name: $( this ).attr( "name" ),
									value: $( this ).is( ":checked" ) ? 1 : 0
								});
							});

							$( ".filters input", $this ).each(function() {
								data.push({
									name: $( this ).attr( "name" ),
									value: $( this ).val()
								});
							});
						},
						fnStateLoadParams: function( oSettings, oData ) {
							$( ".filters :checkbox" ).each(function() {
								$( this ).attr( "checked", oData[ $( this ).attr( "name" ) ] );
							});							
						},
						fnStateSaveParams: function( oSettings, oData ) {
							$( ".filters :checkbox" ).each(function() {
								oData[ $( this ).attr( "name" ) ] = $( this ).is( ":checked" );
							});
						}
					} ) );

					$( ".filters input", $this ).change(function() {
						table.fnDraw();
					});

					$( ".filters .daterange", $this ).daterangepicker(
						{
							format: settings.date_format
						},
						function( start, end ) {
							$( "#filters_date_from" ).val( start.format( settings.date_format ) );
							$( "#filters_date_to" ).val( end.format( settings.date_format ) );
							table.fnDraw();
						}
					);
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
		dataTables: {
			aaSorting: [ [ 1, 'asc' ] ],
			aoColumnDefs: [
				{ bSortable: false, aTargets: [ 0, 11 ] },
				{ bVisible: false, aTargets: [ 0 ] },
				{ sClass: "number", aTargets: [ 5, 6, 7, 8 ] },
				{ sClass: "actions", aTargets: [ 11 ] }
			],
			asStripeClasses: [],
			bAutoWidth: false,
			bPaginate: true,
			bProcessing: true,
			bServerSide: true,
			bSortable: true,
			oLanguage: {
				sUrl: "/bundles/uamdatatables/lang/" + dzangocart.lang + ".txt"
			},
			sDom: "<'row-fluid'<'span6'l>r>t<'row-fluid'<'span6'i><'span6'p>>",
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.orders" ).orders( dzangocart.orders || {} );
	}
});