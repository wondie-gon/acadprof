<?php
/**
 * Class for shortcode
 * 
 * @since 1.0.1
 */
abstract class Acadprof_Shortcode
{
    /**
     * Name of theme
     */
    public $theme_name;
    /**
     * Name of shortcode
     * 
     */
    protected $shortcode;
    /**
     * attributes of shortcode
     * 
     */
    protected $default_atts = array();

    // constructor function for initializing instance
    /**
     * @param string $theme_name    Name (slug) of theme
     */
    public function __construct( $theme_name )
    {
        $this->theme_name = $theme_name;
        
    }

    // get default attributes
    public function get_default_atts() {
        return $this->default_atts;
    }
    // register shortcode
    public function add_shortcode() {
        // Register callback to shortcode
        add_shortcode( $this->shortcode, array( $this, 'shortcode_callback' ), 10, 2 );
    }

    /**
     * Abstract method for shortcode output
     * 
     * @param array  $atts    Shortcode attributes. Default empty.
     * @return mixed shortcode output
     */
    abstract public function shortcode_callback( $atts, $content );
    
    /**
     * ==Helper method==
     * Get responsive bootstrap class name to 
     * be used for each post box
     * 
     * @param array $atts User defined attributes
     * @return string Bootstrap class name for post blocks
     */
    public function get_responsive_bs_class( $atts ) {
        // get postfix num of column class
	    $postfix = 12 / $atts['num_of_columns'];

        // concating with bs grid class
        $classes = esc_attr( 'my-5 col-sm-6 col-md-' . $postfix );
        // give space for each class name
        $col_class = explode( ' ', $classes );

        return $col_class;
    }

    // get sanitized bootstrap grid classes
    public function sanitized_bs_grid_class( $classes, $fallback_cls = 'my-5 col-sm-6 col-md-4' ) {

        // bootstrap grid classes array
        $bs_classes = array(
            'default_col'   =>    'col-12',
            'col_sm'        =>    'col-sm-6',
            'col_md'        =>    'col-md-4',
            'col_lg'        =>    'col-lg-3',
            'padding'       =>    'py-2',
            'margin'        =>    '',
            'addional_cls'  =>    '',
        );

        $bs_classes = array_merge( $bs_classes, (array) $classes );

        // joing classes array to string
        $class_names = implode( ' ', $bs_classes );

        
        // Strip out any %-encoded octets.
        $sanitized_cls = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $class_names );
     
        // Limit to A-Z, a-z, 0-9, '_', '-'.
        $sanitized_cls = preg_replace( '/[^A-Za-z0-9_-]/', '', $sanitized_cls );

        if ( '' === $sanitized_cls && $fallback_cls ) {
            return sanitize_html_class( $fallback_cls );
        }

        /**
         * Filters a sanitized HTML class string.
         *
         * @since 2.8.0
         *
         * @param string $sanitized_cls The sanitized HTML class.
         * @param array $classes     HTML classes before sanitization.
         * @param string $fallback_cls  The fallback string.
         */
        // return apply_filters( 'bs_grid_class', $sanitized_cls, $classes, $fallback_cls );
        return $sanitized_cls;
    }

} // class end
