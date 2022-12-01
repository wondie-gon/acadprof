( function( $ ) {

    function get_content_by_id( id ) {
        var data = {
            'p_id': id, 
            'action': 'get_content_by_id', 
            'ajaxNonce': acadprof_ajax_obj._ajax_nonce
        };
    
        $.post( 
            acadprof_ajax_obj.ajax_url, 
            data, 
            function( response ) {
                return response;
            } 
        );
    }
    

}) ( jQuery );