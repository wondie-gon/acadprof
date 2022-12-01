/**
 * File post-customize-preview.js.
 *
 * Posts customizer live preview 
 * 
 * @see: 
 * @link https://codex.wordpress.org/Theme_Customization_API
 */

 ( function( $ ) {

    /**
     * Custom post excerpt length
     */
    wp.customize( 'acadprof_excerpt_length', function( value ) {
		value.bind( function( newLeng ) {
            // new test 
            // --- working progress--
			var postArticles = $( '.article-block' );
            postArticles.each( function() {
                // get each post id
                var id_str = $( this ).attr( 'id' );
                var p_id = parseInt( id_str.substring( id_str.indexOf( '-' ) + 1 ), 10 );
                // -------
                var excElem = $( this ).find( '.entry-content p' ).first();

                var excTxt = excElem.text();
                var excTxtArr = excTxt.split( ' ' );

                // console.log(excTxtArr);
                var sliceExcerptArr = function( arr, l ) {
                    return arr.slice( 0, l );
                };

                // console.log(sliceExcerptArr( excTxtArr, newLeng ));
                excElem.text( sliceExcerptArr( excTxtArr, newLeng ).join( ' ' ) );
            } );
		} );
	} );
	
} )( jQuery );
