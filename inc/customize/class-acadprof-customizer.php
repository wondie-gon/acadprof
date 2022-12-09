<?php
/**
 * Theme customizer
 *
 * 
 * @package Acadprof
 *
 * @since 1.0.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_Customizer' ) ) {
    class Acadprof_Customizer {
        /**
         * constructor
         */
        public function __construct() {
            add_action( 'customize_register', array( $this, 'custom_controls' ) );
            add_action( 'customize_register', array( $this, 'register_customizers' ) );
            add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
            add_action( 'wp_head', array( $this, 'customize_css_out' ) );
            add_action( 'customize_controls_enqueue_scripts', array( $this, 'custom_controls_js' ) );

            // Load all other customizers
            $this->load_all_customizers();
        }
        /**
         * Adds custom controls
         * 
         * @param object $wp_customize Customizer reference
         */
        public function custom_controls( $wp_customize ) {
            // loading custom control classes
            require_once( get_template_directory() . '/inc/customize/controls/acadprof-image-radio-control.php' );
            require_once( get_template_directory() . '/inc/customize/controls/acadprof-post-dropdown-custom-control.php' );
            require_once( get_template_directory() . '/inc/customize/controls/acadprof-custom-separator.php' );

            // registering custom controls
            $wp_customize->register_control_type( 'Acadprof_Image_Radio_Control' );
            $wp_customize->register_control_type( 'Acadprof_Post_Dropdown_Custom_Control' );
            
            $wp_customize->register_control_type( 'Acadprof_Separator_Custom_Control' );
        }

        /**
         * Register basic customizer support.
         * 
         * @param object $wp_customize Customizer reference.
         */
        public function register_customizers( $wp_customize ) {
            $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
            $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
            $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
            $wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
        }
        /**
         * Action to be hooked to 'wp_head'
         * This will output the custom WordPress settings to the live theme's WP head.
         * 
         */
        public function customize_css_out() {
            ?>
            <!-- Customizer styles -->
            <?php $this->generate_css( '#site-title a, #site-title', 'color', 'header_textcolor', '#' ); ?>
            <?php $this->generate_css( 'body', 'background-color', 'background_color', '#' ); ?>
            <?php
        }

        /**
         * Setup JS integration for custom controls
         */
        public function custom_controls_js() {
            wp_enqueue_script(
                'acadprof-custom-controls-js',
                get_template_directory_uri() . '/assets/js/customizer-controls.js',
                array( 'jquery', 'customize-controls' ),
                '',
                true
            );
        }

        /**
         * Setup JS integration for live previewing.
         */
        public function customize_preview_js() {
            wp_enqueue_script(
                'acadprof-customizer-js', 
                get_template_directory_uri() . '/assets/js/customizer.js',
                array( 'jquery', 'customize-preview' ),
                '',
                true
            );
        }
        /**
         * This will generate a line of CSS for use in header output. If the setting 
         * ($setting_id) has no defined value, the CSS will not be output.
         * 
         * @uses get_theme_mod() 
         * @param string $selector CSS selector 
         * @param string $css_property The name of the CSS *property* to modify 
         * @param string $setting_id The name of the 'theme_mod' option to fetch 
         * @param string $val_prefix Optional. Anything that needs to be output before the CSS property 
         * @param string $val_postfix Optional. Anything that needs to be output after the CSS property 
         * @param bool $echo Optional. Whether to print directly to the page (default: true). 
         * @return string Returns a single line of CSS with selectors and a property.
         */
        public function generate_css( $selector, $css_property, $setting_id, $val_prefix = '', $val_postfix = '', $echo = true ) {
            $css_output = '';
            $mod_value = get_theme_mod( $setting_id );

            if ( ! empty( $mod_value ) ) {
                $css_output = sprintf( '%s { %s: %s }', 
                    $selector, 
                    $css_property, 
                    $val_prefix . $mod_value . $val_postfix
                );
                if ( $echo ) {
                    echo $css_output;
                }
            }
            return $css_output;
        }

        /**
         * Loads all customizers
         */
        public function load_all_customizers() {
            // loading theme layout customizer
            require_once( get_template_directory() . '/inc/customize/class-acadprof-theme-layout.php' );
            // loading excerpt layout customizer
            require_once( get_template_directory() . '/inc/customize/class-acadprof-customize-posts-layout.php' );
            // loading social media customizer
            require_once( get_template_directory() . '/inc/customize/class-acadprof-customize-social-media.php' );
            // loading featured posts customizer
            require_once( get_template_directory() . '/inc/customize/class-acadprof-customize-featured-posts.php' );
        }
    }
    
}
return new Acadprof_Customizer();