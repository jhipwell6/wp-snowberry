export var WP_Snowberry_Helpers = ( function ( WP_Snowberry_Helpers, $ ) {

	WP_Snowberry_Helpers.template = ( id, data ) => {
		let template = $.trim( $( '#' + id ).html() );
		if ( data ) {
			Object.entries( data ).forEach( ( [ key, value ] ) => {
				let regex = new RegExp( '{{' + key + '}}', 'ig' );
				template = template.replace( regex, value );
			} );
		}
		return template;
	};
	
	WP_Snowberry_Helpers.getParam = ( p ) => {
		var match = RegExp( "[?&]" + p + "=([^&]*)" ).exec( window.location.search );
		return match && decodeURIComponent( match[1].replace( /\+/g, " " ) );
	};

	return WP_Snowberry_Helpers;
}( WP_Snowberry_Helpers || { }, jQuery ) );