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
			aoColumnDefs: [
				{ bSortable: false, aTargets: [ 0, 13 ] },
				{ bVisible: false, aTargets: [ 0 ] },
				{ sClass: "number", aTargets: [ 5, 7, 8, 9 ] },
				{ sClass: "actions", aTargets: [ 13 ] }
			],
			asStripeClasses: [],
			bAutoWidth: false,
			bPaginate: true,
			bProcessing: true,
			bServerSide: true,
			bSortable: true,
			oLanguage: {
				sUrl: "/bundles/uamdatatables/lang/" + porot.lang + ".txt"
			}
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	if ( typeof dzangocart != 'undefined' ) {
		$( ".dzangocart.sales" ).sales( dzangocart.sales );
	}
});