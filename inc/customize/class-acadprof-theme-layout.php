<?php
/**
* General customizer theme layout
*
* @package Acadprof
* @since 1.0.0 
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_Theme_Layout' ) ) {
    class Acadprof_Theme_Layout {
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
         * constructor
         */
        public function __construct() {
            if ( is_customize_preview() ) {
                add_action( 'customize_register', array( $this, 'theme_layout_customizer' ) );
            }
        }
        /**
         * Customize register action hook for theme layout
         * 
         * @param WP_Customize_Manager $wp_customize Customizer reference.
         */
        public function theme_layout_customizer( $wp_customize ) {
            /**
             * Theme layout customizer section
             */
            $wp_customize->add_section(
                'acadprof_theme_layout_options',
                array(
                    'title'       => __( 'Theme Layout Settings', 'acadprof' ),
                    'capability'  => 'edit_theme_options',
                    'description' => __( 'Container width and sidebar defaults', 'acadprof' ),
                    'priority'    => apply_filters( 'acadprof_theme_layout_options_priority', 160 ),
                )
            );
            /**
             * Setting bootstrap version
             */
            $wp_customize->add_setting(
                'acadprof_bootstrap_version',
                array(
                    'default'           => 'bootstrap4',
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',
                    'capability'        => 'edit_theme_options',
                )
            );
            /**
             * Setting bootstrap version field
             */
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'acadprof_bootstrap_version',
                    array(
                        'label'       => __( 'Bootstrap Version', 'acadprof' ),
                        'description' => __( 'Choose between Bootstrap 4 or Bootstrap 5', 'acadprof' ),
                        'section'     => 'acadprof_theme_layout_options',
                        'settings'    => 'acadprof_bootstrap_version',
                        'type'        => 'select',
                        'choices'     => array(
                            'bootstrap4' => __( 'Bootstrap 4', 'acadprof' ),
                            'bootstrap5' => __( 'Bootstrap 5', 'acadprof' ),
                        ),
                        'priority'    => apply_filters( 'acadprof_bootstrap_version_priority', 10 ),
                    )
                )
            );
            /**
             * container type setting
             */
            $wp_customize->add_setting(
                'acadprof_container_type',
                array(
                    'default'           => 'container',
                    'type'              => 'theme_mod',
                    'sanitize_callback' => 'acadprof_sanitize_choices',
                    'capability'        => 'edit_theme_options',
                )
            );
            /**
             * container type control
             */
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'acadprof_container_type',
                    array(
                        'label'       => __( 'Container Width', 'acadprof' ),
                        'description' => __( 'Choose between Bootstrap\'s container and container-fluid', 'acadprof' ),
                        'section'     => 'acadprof_theme_layout_options',
                        'settings'    => 'acadprof_container_type',
                        'type'        => 'select',
                        'choices'     => array(
                            'container'       => __( 'Fixed width container', 'acadprof' ),
                            'container-fluid' => __( 'Full width container', 'acadprof' ),
                        ),
                        'priority'    => apply_filters( 'acadprof_container_type_priority', 10 ),
                    )
                )
            );
            /**
             * Navbar type setting
             */
            $wp_customize->add_setting(
                'acadprof_navbar_type',
                array(
                    'default'           => 'collapse',
                    'type'              => 'theme_mod',
                    'sanitize_callback' => 'acadprof_sanitize_choices',
                    'capability'        => 'edit_theme_options',
                )
            );
            /**
             * Navbar type setting
             */
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'acadprof_navbar_type',
                    array(
                        'label'             => __( 'Responsive Navigation Type', 'acadprof' ),
                        'description'       => __(
                            'Choose between an expanding and collapsing navbar or an offcanvas drawer.',
                            'acadprof'
                        ),
                        'section'           => 'acadprof_theme_layout_options',
                        'settings'          => 'acadprof_navbar_type',
                        'type'              => 'select',
                        'sanitize_callback' => 'acadprof_sanitize_choices',
                        'choices'           => array(
                            'collapse'  => __( 'Collapse', 'acadprof' ),
                            'offcanvas' => __( 'Offcanvas', 'acadprof' ),
                        ),
                        'priority'          => apply_filters( 'acadprof_navbar_type_priority', 20 ),
                    )
                )
            );
            /**
             * Sidebar position setting
             */
            $wp_customize->add_setting(
                'acadprof_sidebar_position',
                array(
                    'default'           => 'right',
                    'type'              => 'theme_mod',
                    'sanitize_callback' => 'acadprof_sanitize_choices',
                    'capability'        => 'edit_theme_options',
                )
            );
            /**
             * Sidebar position setting
             */
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'acadprof_sidebar_position',
                    array(
                        'label'             => __( 'Sidebar Positioning', 'acadprof' ),
                        'description'       => __(
                            'Set sidebar\'s default position. Can either be: right, left, both or none. Note: this can be overridden on individual pages.',
                            'acadprof'
                        ),
                        'section'           => 'acadprof_theme_layout_options',
                        'settings'          => 'acadprof_sidebar_position',
                        'type'              => 'select',
                        'sanitize_callback' => 'acadprof_sanitize_choices',
                        'choices'           => array(
                            'right' => __( 'Right sidebar', 'acadprof' ),
                            'left'  => __( 'Left sidebar', 'acadprof' ),
                            'both'  => __( 'Left & Right sidebars', 'acadprof' ),
                            'none'  => __( 'No sidebar', 'acadprof' ),
                        ),
                        'priority'          => apply_filters( 'acadprof_sidebar_position_priority', 20 ),
                    )
                )
            );
            /**
             * Site info override setting
             */
            $wp_customize->add_setting(
                'acadprof_site_info_override',
                array(
                    'default'           => '',
                    'type'              => 'theme_mod',
                    'sanitize_callback' => 'wp_kses_post',
                    'capability'        => 'edit_theme_options',
                )
            );
            /**
             * Site info override setting
             */
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'acadprof_site_info_override',
                    array(
                        'label'       => __( 'Footer Site Info', 'acadprof' ),
                        'description' => __( 'Override Acadprof\'s site info located at the footer of the page.', 'acadprof' ),
                        'section'     => 'acadprof_theme_layout_options',
                        'settings'    => 'acadprof_site_info_override',
                        'type'        => 'textarea',
                        'priority'    => 20,
                    )
                )
            );
        }
    }
    
}
Acadprof_Theme_Layout::get_instance();