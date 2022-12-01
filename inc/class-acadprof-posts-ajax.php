<?php
/**
 * Class for ajax requests of posts
 * 
 * @package Acadprof
 * @since 1.0.0
 */
class Acadprof_Posts_Ajax {

    // constructor
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_ajax_scripts' ) );

        add_action( 'wp_ajax_get_content_by_id', array( $this, 'get_content_by_id_ajax' ) );
        add_action( 'wp_ajax_get_posts_by_ids', array( $this, 'get_posts_by_ids_ajax' ) );
    }

    /**
     * Handles ajax request to get post content by id
     */
    public function get_content_by_id_ajax() {
        // verify the nonce sent by jQuery
        check_ajax_referer( 'acadprof-ajax-nonce' );

        // data from js
        $p_id = absint( $_POST['p_id'] );

        $postQry = get_post( $p_id );

        wp_send_json( array( 'status' => 'Success', 'postcontent' => $postQry->post_content ) );
    }

    /**
     * Handles ajax request action
     */
    public function get_posts_by_ids_ajax() {
        // checking nonce
        /*
        $nonce = $_POST['ajaxNonce'];
        if ( ! wp_verify_nonce( $nonce, 'acadprof-ajax-nonce' ) ) {
            die( 'You Got Busted!' );
        }
        */

        // verify the nonce sent by jQuery
        check_ajax_referer( 'acadprof-ajax-nonce' );

        // data from js
        $p_ids = $_POST['p_ids'];

        // data from js
        if ( empty( $p_ids ) ) {
            return;
        }

        // ids in array
        $p_ids = is_array( $p_ids ) ? $p_ids : (array) $p_ids;

        // make sure each id int
        foreach ( $p_ids as &$value ) {
            $value = absint( $value );
            // unset($value); // break the reference with the last element
        }

        // query args
        $args = array( 
            'post_type'     =>  'post', 
            'include'       =>  $p_ids, 
            'post_status'   =>  'publish'      
        );

        // get response posts
        $response_posts = get_posts( $args );

        wp_send_json( array( 'status' => 'Success', 'posts' => $response_posts ) );
    }
    
    /**
     * Enqueues ajax functionality script
     * and 
     * Localizing global variables to be used by JavaScript. 
     * Later in JavaScript, object properties are accessed as 
     * acadprof_ajax_obj.ajax_url, acadprof_ajax_obj._ajax_nonce
     */
    public function enqueue_ajax_scripts() {
        // dynamic version
	    $vers = AP_DEV_MODE ? time() : _AP_VERSION;
        /**
         * Registers JavaScript for ajax functionalities 
         * to be enqueued when required
         */
        wp_register_script( 
            'acadprof-ajax-js', 
            get_template_directory_uri() . '/assets/js/ajax-script.js', 
            array( 'jquery' ), 
            $vers, 
            false 
        );
        // acadprof-ajax-js-js
        wp_enqueue_script( 'acadprof-ajax-js' );
        // enqueue localazed vars
        wp_localize_script( 
            'acadprof-ajax-js', 
            'acadprof_ajax_obj', 
            array(
                'ajax_url'   => admin_url( 'admin-ajax.php' ),
                '_ajax_nonce' => wp_create_nonce( 'acadprof-ajax-nonce' )
            )
        );
    }
}