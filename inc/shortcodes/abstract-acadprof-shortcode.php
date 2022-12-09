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

} // class end
