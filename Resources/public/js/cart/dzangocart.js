;(function ( $, window, document, undefined ) {
	
	var defaults = {
		};

	// The actual plugin constructor
	function Dzangocart( element, options ) {
		this.element = element;

		this.options = $.extend( {}, defaults, options) ;
		
		this._defaults = defaults;
		this._name = "dzangocart";
		
		this.init();
	}

	Dzangocart.prototype.init = function () {
		var $this = this,
			customer_data = this.parseCookie( "dzangocart" ),
        	referer = window.location;

		$( "a.dzangocart, area.dzangocart, input.dzangocart" ).click(function( e ) {
			e.preventDefault();

            var url = $( this ).is ( "[href]" )
            	? $( this ).attr( "href" )
            	: $( this ).data( "url" );

            var queryString = url.replace(/^[^\?]+\??/, "");
            
            var options = $this.parseQueryString( queryString );

            if ( options.checkout ) {
                location.href = url 
                    + ( customer_data ? "&customer_data=" + encodeURIComponent( customer_data ) : "" )
                    + "&referer=" + encodeURIComponent( referer );
            } else {
                url = url
                    + ( customer_data ? "&customer_data=" + encodeURIComponent( customer_data ) : "" )
                    + "&referer=" + encodeURIComponent( referer );

                $this.show( url );
                
                this.blur();
            }
		});
	};

	Dzangocart.prototype.show = function ( url ) {
		$.modal(
			'<iframe src="' + url + '" height="425" width="640" style="border: 0;">',
			{
				overlayClose : true
			}
		);
	};

	Dzangocart.prototype.parseCookie = function( cookie_name ) {
        var nameEQ = cookie_name + "=";
        var ca = document.cookie.split( ";" );

        for ( var i = 0; i < ca.length; i++ ) {
            var c = ca[i];

            while ( c.charAt(0) === " " ) {
            	c = c.substring( 1, c.length );
            }

            if ( c.indexOf( nameEQ ) === 0 ) {
            	return c.substring( nameEQ.length, c.length );
            }
        }

        return null;
    };

	Dzangocart.prototype.parseQueryString = function( query ) {
	    var options = {};

	    if ( !query ) {
    	    return options;
    	}

	    var params = query.split( /[;&]/ );

	    for ( var i = 0; i < params.length; i++ ) {
	        var pair = params[i].split( '=' );
    	    
    	    if ( !pair || pair.length !== 2 ) {
    	    	continue;
    	    }

        	var key = unescape( pair[0] );
	        var val = unescape( pair[1] );

	        val = val.replace( /\+/g, ' ' );
    
    	    options[key] = val;
    	}

	    return options;
	};

	$.fn[ "dzangocart" ] = function ( options ) {
		return this.each(function () {
			if ( !$.data( this, "dzangocart" ) ) {
				$.data(
					this,
					"dzangocart", 
					new Dzangocart( this, options )
				);
			}
		});
	}

})( jQuery, window, document );

$( document ).ready(function() {
	$( document ).dzangocart();
});
