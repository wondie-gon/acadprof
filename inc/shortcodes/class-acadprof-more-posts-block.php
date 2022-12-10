<?php
/**
 * Class for shortcode that displays more posts (assynchrounously or not)
 * 
 * @package Acadprof
 * @since 1.0.0
 * 
 */
// restrict direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_More_Posts_Block' ) ) {
    class Acadprof_More_Posts_Block extends Acadprof_Shortcode {
        /**
         * Name of shortcode
         * 
         */
        public $shortcode;
        /**
         * Constructor function
         * 
         * @param string $theme_name Name of theme
         */
        public function __construct( $shortcode, $theme_name ) {
            $this->shortcode = $shortcode;
            parent::__construct( $theme_name );

            // registering scipts
            add_action( 'wp_enqueue_scripts', array( $this, 'register_shortcode_scripts' ) );
            // hook to custom action to enqueue scripts for shortcode
            add_action( 'acadprof_enqueue_more_post_scripts', array( $this, 'add_shortcode_scripts' ) );
        }

        /**
         * Gets default attributes for shortcode
         */
        public function get_default_atts() {
            $this->default_atts = array(
                'title'             =>	esc_html__( 'More Posts', $this->theme_name ), 
                'loads_async'       =>  true, 
                'loads_by_btn'      =>  true, 
                'btn_text'          =>  esc_html__( 'Load More Posts', $this->theme_name ), 
                'post_type'         => 'post', 
                'posts_per_page'    => '8', 
                'exclude_ids'       =>	'', 
                'order'			    =>	'DESC', 
                'tag'				=>	'', 
                'columns_lg'		=>	'4', 
                'columns_md'		=>	'3', 
                'columns_sm'		=>	'2', 
                'show_thumbnail'	=>	true, 
                'img_size_name'	    =>	'medium', 
                'show_excerpt'		=>	true,
            );
            return $this->default_atts;
        }

        /**
         * Callback function for shortcode to be hooked 
         * with action hook 'init'
         * 
         * @param array/string $atts List of shortcode attributes
         * @param mixed $content Content to be displayed with enclosing 
         * shortcode tag calls. Defaults to null
         * 
         * @return mixed/html resulting html of posts
         */
        public function shortcode_callback( $atts, $content = null ) {
            // get post object
            $post = get_post();

            if ( empty( $atts['exclude_ids'] ) ) {
                $atts['exclude_ids'] = $post->ID;
            }
            /**
             * Filters the default more posts block shortcode output
             * 
             * If the filtered output is not empty, it will be used to override the 
             * shortcode's default output
             * 
             * @param string $override  More posts block output. Default is empty
             * @param array $atts       Array of shortcode's attributes
             * @param string $content   Shortcode content 
             */
            $override = apply_filters( 'acadprof_more_post_sc_override', '', $atts, $content );

            if ( '' !== $override ) {
                return $override;
            }

            // overriding default attributes with user defined
            $atts = shortcode_atts( $this->get_default_atts(), $atts, $this->shortcode );

            // get valid atts to work with
            $atts = $this->get_valid_atts( $atts );

            // column class
            $col_class = $this->get_bs_col_class( $atts, 'my-3' );

            // outputting html
            $output = '
                    <div class="container-fluid container-shortcode">
                        <div class="row">
            ';
            // block title
            if ( ! empty( $atts['title'] ) ) {
                $output .= sprintf( 
                    '<div class="col-12 my-2">
                        <h2>' . esc_html__( '%s', $this->theme_name ) . '</h2>
                    </div>', 
                    $atts['title'] 
                );
            }
            
            if ( false === $atts['loads_async'] ) {
                // Query
                $the_query = new WP_Query( 
                    array ( 
                        'post_type' => $atts['post_type'], 
                        'posts_per_page' => absint( $atts['posts_per_page'] ), 
                        'post__not_in'  =>  $atts['exclude_ids'],
                        'order' => $atts['order'], 
                    ) 
                );
                
                // Posts
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $output .= '<div class="' . esc_attr( implode( ' ', get_post_class( $col_class ) ) ) . '">';
                    // var_dump( get_post_class( $col_class ) );
                    // show thumbnail
                    if ( true === $atts['show_thumbnail'] && has_post_thumbnail() ) {
                        $output .= '<a href="' . get_the_permalink() . '" aria-hidden="true" tabindex="-1">' . acadprof_get_the_post_thumbnail( get_the_ID(), $atts['img_size_name'], 'img-fluid d-block mb-2' ) . '</a>';
                    }
                    // title of post
                    $output .= sprintf( 
                        '<h4><a href="%1$s" rel="bookmark">%2$s</a></h4>', 
                        esc_url( get_permalink() ), 
                        sprintf( __( '%s', $this->theme_name ), 
                            esc_html( get_the_title() )
                        )
                    );
                    // show excerpt
                    if ( true === $atts['show_excerpt'] ) {
                        $output .= get_the_excerpt();
                    }
                    $output .= '</div>';
                } // end while
                
                // Reset post data
                wp_reset_postdata();
            } else {
                /**
                 * Equeues more posts block shortcode's scripts, and localizes 
                 * variables that will be used to load posts asynchronously
                 * 
                 * @since 1.0.0
                 * 
                 * @param array $atts Attributes of the shortcode
                 */
                do_action( 'acadprof_enqueue_more_post_scripts', $atts );

                // display event listener button for async request
                if ( true === $atts['loads_by_btn'] ) {
                    $output .= sprintf(
                        '<div class="d-grid col-md-4">
                            <button id="request-posts-btn" class="btn btn-primary btn-lg">' . esc_html__( '%s', $this->theme_name ) . '</button>
                        </div>', 
                        $atts['btn_text']

                    );
                }
                // response display block
                $output .= '<div id="response-display-block" class="row py-3"></div>';
            }
            
            // closing 
            $output .= '
                    </div><!-- .row -->
                </div><!-- .container-fluid.container-shortcode -->
            ';
            // Return code
            return $output;
        }

        /**
         * ==For internal use==
         * Method to validate and get valid attributes
         * 
         * @param array  $atts    Shortcode attributes. Default empty.
         * @return array valid attributes
         */
        protected function get_valid_atts( $atts ) {
            // normalizing attribute keys to lower case
            $atts = array_change_key_case( (array) $atts, CASE_LOWER );
            /**
             * validating attributes values
             */
            if ( ! empty( $atts['title'] ) ) {
                $atts['title'] = sanitize_text_field( $atts['title'] );
            }
            // validating ids to exclude
            if ( ! is_array( $atts['exclude_ids'] ) && ! empty( $atts['exclude_ids'] ) ) {
                $atts['exclude_ids'] = ( array ) $atts['exclude_ids'];

                foreach ( $atts['exclude_ids'] as &$value ) {
                    $value = ( int ) $value;
                }
                // break the reference with the last element
                unset( $value );
            }
            // post tags
            if ( ! empty( $atts['tag'] ) ) {
                $term = term_exists( $atts['tag'], 'post_tag' );
                if ( $term === 0 || $term === null ) {
                    $atts['tag'] = '';
                }
            }

            // number of posts per load or page
            if ( ! empty( $atts['posts_per_page'] ) ) {
                $atts['posts_per_page'] = ! is_int( $atts['posts_per_page'] ) ? ( int ) $atts['posts_per_page'] : $atts['posts_per_page'];
            }

            // number of columns at large
            if ( ! empty( $atts['columns_lg'] ) ) {
                $atts['columns_lg'] = ! is_int( $atts['columns_lg'] ) ? ( int ) $atts['columns_lg'] : $atts['columns_lg'];
            }

            // number of columns at medium
            if ( ! empty( $atts['columns_md'] ) ) {
                $atts['columns_md'] = ! is_int( $atts['columns_md'] ) ? ( int ) $atts['columns_md'] : $atts['columns_md'];
            }

            // number of columns at small
            if ( ! empty( $atts['columns_sm'] ) ) {
                $atts['columns_sm'] = ! is_int( $atts['columns_sm'] ) ? ( int ) $atts['columns_sm'] : $atts['columns_sm'];
            }

            // sanitize btn text
            if ( ! empty( $atts['btn_text'] ) ) {
                $atts['btn_text'] = sanitize_text_field( $atts['btn_text'] );
            }

            // validating booleans
            $atts['loads_async'] = wp_validate_boolean( $atts['loads_async'] );
            $atts['loads_by_btn'] = wp_validate_boolean( $atts['loads_by_btn'] );
            $atts['show_thumbnail'] = wp_validate_boolean( $atts['show_thumbnail'] );
            $atts['show_excerpt'] = wp_validate_boolean( $atts['show_excerpt'] );

            // get valid atts
            return $atts;
        }

        /**
         * ==Helper method==
         * Get responsive bootstrap class name to 
         * be used for each post box
         * 
         * @param array $cols                   User defined column array
         * @param string $additional_class      Additional classto be added
         * @return string Bootstrap class name for post blocks
         */
        public function get_bs_col_class( $cols = array(), $additional_class = '' ) {

            $cols = wp_parse_args( 
                $cols, 
                array(
                    'columns_lg'    =>  4, 
                    'columns_md'    =>  3, 
                    'columns_sm'    =>  2, 
                )
            );

            $col_class = 'col-12';
            if ( $cols['columns_sm'] && (int) $cols['columns_sm'] <= 12 ) {
                $sm_postfix = 12 / (int) $cols['columns_sm'];
                $col_class .= ' col-sm-' . $sm_postfix;
            }

            if ( $cols['columns_md'] && (int) $cols['columns_md'] <= 12 ) {
                $md_postfix = 12 / (int) $cols['columns_md'];
                $col_class .= ' col-md-' . $md_postfix;
            }

            if ( $cols['columns_lg'] && (int) $cols['columns_lg'] <= 12 ) {
                $lg_postfix = 12 / (int) $cols['columns_lg'];
                $col_class .= ' col-lg-' . $lg_postfix;
            }

            if ( '' !== $additional_class ) {
                $col_class .= ' ' . $additional_class;
            }
            // preparing for output
            $col_class = esc_attr( implode( ' ', explode( ' ', $col_class ) ) );

            return $col_class;
        }

        /**
         * Setup JS integration for asynchronous request of posts
         */
        public function register_shortcode_scripts() {
            // dynamic version
	        $vers = AP_DEV_MODE ? time() : _AP_VERSION;
            wp_register_script(
                'acadprof-more-posts', 
                get_template_directory_uri() . '/assets/js/load-more-request.js',
                null,
                $vers,
                true
            );
        }
        public function add_shortcode_scripts( $atts ) {
            wp_enqueue_script( 'acadprof-more-posts' );

            // vars to localize
            $arr_to_localize = array(
                '_rest_nonce'       =>  wp_create_nonce( 'wp_rest' ), 
                'domain_root'       =>  get_site_url(), 
            );

            // check if asynchronous loading enabled
            if ( true == $atts['loads_async'] ) {
                $atts_to_js = array(
                    '_title'			    =>	$atts['title'], 
                    '_loads_async'          =>  $atts['loads_async'], 
                    '_loads_by_btn'         =>  $atts['loads_by_btn'], 
                    '_btn_text'             =>  $atts['btn_text'], 
                    '_post_type'            =>  $atts['post_type'], 
                    '_posts_per_page'       =>  $atts['posts_per_page'], 
                    '_exclude_ids'          =>  $atts['exclude_ids'], 
                    '_order'			    =>  $atts['order'], 
                    '_tag'				    =>	$atts['tag'], 
                    '_columns_lg'           =>	$atts['columns_lg'], 
                    '_columns_md'           =>	$atts['columns_md'], 
                    '_columns_sm'           =>	$atts['columns_sm'], 
                    '_show_thumbnail'       =>	$atts['show_thumbnail'], 
                    '_img_size_name'       =>	$atts['img_size_name'], 
                    '_show_excerpt'	        =>	$atts['show_excerpt'],
                );
                // merge to $arr_to_localize
                $arr_to_localize = wp_parse_args( $atts_to_js, $arr_to_localize );
            }

            /**
             * Localize vars for use in js
             */
            wp_localize_script( 'acadprof-more-posts', 'acadprof_rest_obj', $arr_to_localize );
        }
        
    }
}