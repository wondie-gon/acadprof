<?php
/**
 * Customizer for layout of post displays
 * 
 * @link http://codex.wordpress.org/Theme_Customization_API
 * 
 * @package Acadprof
 * @since 1.0.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_Customize_Posts_Layout' ) ) {
    class Acadprof_Customize_Posts_Layout {

        /**
		 * Instance
		 *
		 * @access private
		 * @var object
		 */
		private static $instance;

        /**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
        /**
         * Constructor
         */
        public function __construct() {
            
            if ( is_customize_preview() ) {
                // initialize posts ajax
                $this->init_posts_ajax();

                // register customizer
                add_action( 'customize_register', array( $this, 'add_customize_posts' ) );

                // setting customize live preview
                add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
            }
        }
        /**
         * This hooks into 'customize_register' and allows 
         * you to add new sections and controls to the 
         * Theme Customize screen.
         */
        public function add_customize_posts( $wp_customize ) {
            /**
             * Posts customizer panel
             */
            $wp_customize->add_panel( 
                'acadprof-posts', 
                array(
                    'title'         =>  esc_html__( 'Posts', 'acadprof' ), 
                    'capability'    =>  'edit_theme_options', 
                    'priority'      =>  210, 
                ) 
            );
            /**
             * Excerpt layout section
             */
            $wp_customize->add_section(
                'acadprof_excerpt_layout', 
                array(
                    'title'         =>  esc_html__( 'Post excerpt layout', 'acadprof' ), 
                    'description'   =>  esc_html__( 'Allows to customize layout of post excerpts', 'acadprof' ), 
                    'priority'      =>  10, 
                    'capability'    =>  'edit_theme_options', 
                    'panel'         =>  'acadprof-posts',
                )
            );
            /**
             * Excerpt / image layout customizer field
             */
            // setting
            $wp_customize->add_setting(
                'acadprof_excerpt_image_layout', 
                array(
                    'default'           =>  'image-left', 
                    'type'              =>  'theme_mod', 
                    'capability'        =>  'edit_theme_options', 
                    'sanitize_callback' =>  'acadprof_sanitize_choices', 
                )
            );
            // control
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_excerpt_image_layout', 
                    array(
                        'label'             => esc_html__( 'Excerpt / Image layout', 'acadprof' ), 
                        'description'       => esc_html__( 'Select layout of excerpt and image', 'acadprof' ), 
                        'settings'          => 'acadprof_excerpt_image_layout', 
                        'section'           => 'acadprof_excerpt_layout', 
                        'type'              => 'radio', 
                        'choices'   =>  array(
                            'image-left'        =>  esc_html__( 'Image left to excerpt', 'acadprof' ), 
                            'image-right'       =>  esc_html__( 'Image right to excerpt', 'acadprof' ), 
                            'image-top'         =>  esc_html__( 'Image top of excerpt', 'acadprof' ), 
                            'image-top-left'    =>  esc_html__( 'Image top left of excerpt', 'acadprof' ), 
                        ), 
                        'priority'          => 10, // Within the section.
                    )
                )
            );

            /**
             * Excerpt length
             */
            $wp_customize->add_setting(
                'acadprof_excerpt_length', 
                array(
                    'default'			=>	'20', 
                    'type'              =>  'theme_mod', 
                    'capability'        =>  'edit_theme_options', 
                    'transport'         =>  'postMessage', 
                    'sanitize_callback' =>  'acadprof_sanitize_number', 
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_excerpt_length', 
                    array(
                        'label'				=>	esc_html__( 'Excerpt length', 'acadprof' ), 
                        'description'		=>	esc_html__( 'Allows to customize excerpt length', 'acadprof' ), 
                        'section'			=>	'acadprof_excerpt_layout', 
                        'settings'			=>	'acadprof_excerpt_length', 
                        'type' 				=>	'number', 
                        'input_attrs'       =>  array(
                            'min'   =>  0, 
                            'max'   =>  100, 
                            'step'  =>  1, 
                        ), 
                        'priority'          => 20, 
                    )
                )
            );
        }

        public function init_posts_ajax() {
            require get_template_directory() . '/inc/class-acadprof-posts-ajax.php';
            return new Acadprof_Posts_Ajax();
        }

        /**
         * Enqueue customize preview js
         */
        public function customize_preview_js() {
            // version
	        $vers = AP_DEV_MODE ? time() : _AP_VERSION;

            wp_enqueue_script( 
                'acadprof-post-customize', 
                get_template_directory_uri() . '/assets/js/post-customize-preview.js', 
                array( 'jquery', 'customize-preview', 'acadprof-ajax-js' ), 
                $vers, 
                true 
            );
        }
    }
}
Acadprof_Customize_Posts_Layout::get_instance();
