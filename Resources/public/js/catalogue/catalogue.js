!function( $ ) {
	$.fn.catalogue = function( method ) {

		var settings;

		// Public methods
		var methods = {
			init: function( options ) {
				settings = $.extend( true, {}, $.fn.catalogue.defaults, options );

				return this.each(function() {
					var $this = $( this );

					$( "table.treetable", this )
						.treetable( $.extend( true, {}, settings.treetable, {
							onInitialized: function(){
								$(this.table).show();
							}
						}));
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
			$.error( "Method " +  method + " does not exist in $.catalogue." );
		}
	};

	$.fn.catalogue.defaults = {
		treetable: {
			expandable: true
		}
	};
} ( window.jQuery );

$( document ).ready(function() {
	$( '.catalogue' ).catalogue( );
});
