import {WP_Snowberry_Helpers} from './wp-snowberry-helpers.js';

var WP_Snowberry = ( function ( WP_Snowberry, $ ) {

	/**
	 * Common Functionality
	 * @type {Object}
	 */
	WP_Snowberry.Common = {
		init() {
			if ( $( 'body' ).hasClass( 'fl-builder-edit' ) ) {
				return;
			}

			this.initTooltips();
			this.initFSelect();
			$( document ).on( 'facetwp-loaded', $.proxy( this.initFSelect, this ) );
		},

		initTooltips() {
			$( '[data-toggle="tooltip"],.js-tooltip a' ).tooltip();
		},

		initFSelect() {
			if ( 'undefined' === typeof window.fSelect ) {
				return false;
			}

			$( '.facetwp-type-sort select' ).each( ( i, select ) => fSelect( select, { showSearch: false } ) );
		}
	};

	const onDocReady = [
		() => {
			WP_Snowberry.Common.init();
		}
	];

	// Iterate through callbacks and move each callback separately to event queue
	$( function () {
		onDocReady.forEach( callback => {
			setTimeout( callback, 0 );
		} );
	} );

	return WP_Snowberry;
}( WP_Snowberry || { }, jQuery ) );