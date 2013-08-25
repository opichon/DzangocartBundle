!function( $ ) {
	$.fn.payments = function( method ) {

		var settings,
			table;

		var methods = {
			init: function( options ) {

				settings = $.extend( true, {}, this.payments.defaults, options );

				return this.each(function() {
					var $this = $( this );

					table = $( "table.table", this ).dataTable( $.extend( true, {}, settings.dataTables, {
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

							$( ".filters .date input", $this ).each(function() {
								data.push({
									name: $( this ).attr( "name" ),
									value: $( this ).val()
								});
							});
						},
						fnStateLoadParams: function( oSettings, oData ) {
							$( ".filters :checkbox", $this ).each(function() {
								$( this ).attr( "checked", oData[ $( this ).attr( "name" ) ] );
							});

							$( ".filters input", $this ).each(function() {
								$( this ).val( oData[ $( this ).attr( "name" ) ] );
							});
						},
						fnStateSaveParams: function( oSettings, oData ) {
							$( ".filters :checkbox", $this ).each(function() {
								oData[ $( this ).attr( "name" ) ] = $( this ).is( ":checked" );
							});

							$( ".filters input", $this ).each(function() {
								oData[ $( this ).attr( "name" ) ] = $( this ).val();
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
			$.error( "Method " +  method + " does not exist in jQuery.payments." );
		}
	};

	$.fn.payments.defaults = {
		dataTables: {
			aoColumnDefs: [
				{ bSortable: false, aTargets: [ 0, 8 ] },
				{ bVisible: false, aTargets: [ 0 ] },
				{ sClass: "number", aTargets: [ 3 ] },
				{ sClass: "actions", aTargets: [ 8 ] }
			],
			asStripeClasses: [],
			bAutoWidth: false,
			bPaginate: true,
			bProcessing: true,
			bServerSide: true,
			bSortable: true,
			bStateSave: true,
			oLanguage: {
				sUrl: "/bundles/uamdatatables/lang/" + dzangocart.locale + ".txt"
			},
			sCookiePrefix: "dzangocart_"
		},
		daterangepicker: {
			minDate: moment('2009-01-01'),
			maxDate: moment()
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.payments" ).payments( dzangocart.payments );
	}
});