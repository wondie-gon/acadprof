<?php 
/**
 * Class for customizing featured posts 
 * 
 * @package Acadprof
 * @since 1.0.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_Customize_Featured_Posts' ) ) {
    class Acadprof_Customize_Featured_Posts {

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
                // register customizer
                add_action( 'customize_register', array( $this, 'activate_customize_controls' ) );
                add_action( 'customize_register', array( $this, 'featured_posts_intro' ) );
                add_action( 'customize_register', array( $this, 'featured_posts_selection' ) );
                add_action( 'customize_register', array( $this, 'featured_posts_style' ) );

                // setting customize live preview
                add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
            }
        }

        public function activate_customize_controls( $wp_customize ) {
            /**
             * Section for featured posts
             */
            $wp_customize->add_section(
                'acadprof_featured_posts',
                array(
                    'title'         =>  esc_html__( 'Featured Posts', 'acadprof' ), 
                    'description'   =>  esc_html__( 'Allows to customize featured posts block', 'acadprof' ), 
                    'priority'      =>  20, 
                    'capability'    =>  'edit_theme_options', 
                    'panel'         =>  'acadprof-posts',
                )
            );
            /*
            * Enabling and Setting contents for featured posts block
            */
            $wp_customize->add_setting(
                'acadprof_featured_posts_enabled',
                    array(
                        'default'			=>	0,
                        'sanitize_callback'	=>	'acadprof_sanitize_checkbox',
                    )
                );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize,
                    'acadprof_featured_posts_enabled',
                    array(
                        'section'		=>	'acadprof_featured_posts',
                        'label'			=>	esc_html__( 'Enable Section', 'acadprof' ),
                        'description' 	=>	esc_html__( 'Check to display featured posts block.', 'acadprof' ),
                        'settings'		=>	'acadprof_featured_posts_enabled',
                        'type'			=>	'checkbox',
                    )
                )
            );
        }

        public function featured_posts_intro( $wp_customize ) {
            // Get defaults for theme customizer
            $defaults = self::get_default_mods();

            // section title
            $wp_customize->add_setting(
                'acadprof_featured_posts_block_heading',
                array(
                    'default'			=>	$defaults['acadprof_featured_posts_block_heading'],
                    'sanitize_callback'	=>	'sanitize_text_field',
                    'transport'			=>	'postMessage',
                )
            );

            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_featured_posts_block_heading', 
                    array(
                        'label'				=>	esc_html__( 'Section heading: ', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'settings'			=>	'acadprof_featured_posts_block_heading',
                        'active_callback'	=>	'acadprof_is_featured_posts_enabled',
                    )
                )
            );

            // section intro
            $wp_customize->add_setting(
                'acadprof_featured_posts_block_intro_parag', 
                array(
                    'default'			=>	'',
                    'transport'			=>	'postMessage',
                    'sanitize_callback'	=>	'sanitize_textarea_field',
                    )
                );

            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_featured_posts_block_intro_parag', 
                    array(
                        'label'				=>	esc_html__( 'Section introduction', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'type'				=>	'textarea',
                        'settings'			=>	'acadprof_featured_posts_block_intro_parag',
                        'active_callback'	=>	'acadprof_is_featured_posts_enabled',
                        'input_attrs' => array(
                            'class' => 'custm-txtarea',
                            'style' => 'border: 1px solid #999',
                            'placeholder' => esc_html__( 'Enter section description...', 'acadprof' ),
                        ),
                    )
                )
            );

            /*
            * Number of cards
            */
            $wp_customize->add_setting(
                'acadprof_number_of_featured_posts_cards',
                    array(
                        'default'			=>	$defaults['acadprof_number_of_featured_posts_cards'],
                        'sanitize_callback'	=>	'acadprof_sanitize_number',
                        'transport'			=>	'refresh',
                    )
                );
                
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize,
                    'acadprof_number_of_featured_posts_cards',
                    array(
                        'label'				=>	esc_html__( 'Number of post cards', 'acadprof' ),
                        'section'			=>	'acadprof_featured_posts',
                        'settings'			=>	'acadprof_number_of_featured_posts_cards',
                        'active_callback'	=>	'acadprof_is_featured_posts_enabled',
                        'type' 				=>	'number',
                    )
                )
            );
        }

        public function featured_posts_selection( $wp_customize ) {
            // Get defaults for theme customizer
            $defaults = self::get_default_mods();
            /**
             * select posts and customize featured posts cards
             */
            $num_of_featured_posts = absint( get_theme_mod( 'acadprof_number_of_featured_posts_cards', $defaults['acadprof_number_of_featured_posts_cards'] ) );

            $num = 0;

            while ( $num < $num_of_featured_posts ) {

                // select post
                $wp_customize->add_setting(
                    'acadprof_featured_post_' . $num, 
                    array(
                        'default'			=>	'',
                        'sanitize_callback'	=>	'absint',
                    )
                );
                $wp_customize->add_control(
                    new Acadprof_Post_Dropdown_Custom_Control(
                        $wp_customize, 
                        'acadprof_featured_post_' . $num, 
                        array(
                            'label'				=>	esc_html__( 'Post ', 'acadprof' ) . $num,
                            'section'			=>	'acadprof_featured_posts',
                            'settings'			=>	'acadprof_featured_post_' . $num, 
                            'active_callback'	=>	'acadprof_is_featured_posts_enabled',
                        )
                    )
                    
                );

                // Custom separator for summary cards
                $wp_customize->add_setting(
                'acadprof_featured_posts_custom_separator_' . $num, 
                    array(
                        'sanitize_callback'	=>	'acadprof_sanitize_html'
                    )
                );
                $wp_customize->add_control( 
                    new Acadprof_Separator_Custom_Control(
                        $wp_customize, 
                        'acadprof_featured_posts_custom_separator_' . $num,
                            array(
                                'type'	            =>	'acadprof-separator',
                                'section'	        =>	'acadprof_featured_posts',
                                'settings'	        =>	'acadprof_featured_posts_custom_separator_' . $num,
                                'active_callback'	=>	'acadprof_is_featured_posts_enabled',
                            )
                    )
                );
                $num++;
            }
        }

        public function featured_posts_style( $wp_customize ) {
            // section background color
            $wp_customize->add_setting( 
                'acadprof_featured_posts_section_bg_color', 
                array(
                    'default'			=>	'',
                    'transport'			=> 	'postMessage',
                    'sanitize_callback'	=> 	'acadprof_sanitize_hex_color',
                ) 
            );

            $wp_customize->add_control( 
                new WP_Customize_Color_control( 
                    $wp_customize, 
                    'acadprof_featured_posts_section_bg_color', 
                    array(
                        'label'				=> 	esc_html__( 'Section Background Color', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'settings'			=> 	'acadprof_featured_posts_section_bg_color',
                        'active_callback'	=>	'acadprof_is_featured_posts_enabled',
                    ) 
                ) 
            );

            // section header color
            $wp_customize->add_setting( 
                'acadprof_featured_posts_section_hdng_color', 
                array(
                    'default'			=>	'',
                    'transport'			=> 	'postMessage',
                    'sanitize_callback'	=> 	'acadprof_sanitize_hex_color',
                ) 
            );

            $wp_customize->add_control( 
                new WP_Customize_Color_control( 
                    $wp_customize, 
                    'acadprof_featured_posts_section_hdng_color', 
                    array(
                        'label'				=> 	esc_html__( 'Section Heading Color', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'settings'			=> 	'acadprof_featured_posts_section_hdng_color',
                        'active_callback'	=>	'acadprof_is_featured_posts_enabled',
                    ) 
                ) 
            );

            // cards background color
            $wp_customize->add_setting( 
                'acadprof_featured_posts_cards_bg_color', 
                array(
                    'default'			=>	'',
                    'transport'			=> 	'postMessage',
                    'sanitize_callback'	=> 	'acadprof_sanitize_hex_color',
                ) 
            );

            $wp_customize->add_control( 
                new WP_Customize_Color_control( 
                    $wp_customize, 
                    'acadprof_featured_posts_cards_bg_color', 
                    array(
                        'label'				=> 	esc_html__( 'Cards Background Color', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'settings'			=> 	'acadprof_featured_posts_cards_bg_color',
                        'active_callback'	=> 	'acadprof_is_featured_posts_enabled',
                    ) 
                ) 
            );

            // cards title color
            $wp_customize->add_setting( 
                'acadprof_featured_posts_cards_title_color', 
                array(
                    'default'			=>	'',
                    'transport'			=> 	'postMessage',
                    'sanitize_callback'	=> 	'acadprof_sanitize_hex_color',
                ) 
            );

            $wp_customize->add_control( 
                new WP_Customize_Color_control( 
                    $wp_customize, 
                    'acadprof_featured_posts_cards_title_color', 
                    array(
                        'label'				=> 	esc_html__( 'Cards title Color', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'settings'			=> 	'acadprof_featured_posts_cards_title_color',
                        'active_callback'	=> 	'acadprof_is_featured_posts_enabled',
                    ) 
                ) 
            );

            // button background color
            $wp_customize->add_setting( 
                'acadprof_featured_posts_btn_bg_color', 
                array(
                    'default'			=>	'',
                    'transport'			=> 	'postMessage',
                    'sanitize_callback'	=> 	'acadprof_sanitize_hex_color',
                ) 
            );

            $wp_customize->add_control( 
                new WP_Customize_Color_control( 
                    $wp_customize, 
                    'acadprof_featured_posts_btn_bg_color', 
                    array(
                        'label'				=> 	esc_html__( 'Button Background Color', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'settings'			=> 	'acadprof_featured_posts_btn_bg_color',
                        'active_callback'	=> 	'acadprof_is_featured_posts_enabled',
                    ) 
                ) 
            );

            // button link color
            $wp_customize->add_setting( 
                'acadprof_featured_posts_btn_text_color', 
                array(
                    'default'			=>	'',
                    'transport'			=> 	'postMessage',
                    'sanitize_callback'	=> 	'acadprof_sanitize_hex_color',
                ) 
            );

            $wp_customize->add_control( 
                new WP_Customize_Color_control( 
                    $wp_customize, 
                    'acadprof_featured_posts_btn_text_color', 
                    array(
                        'label'				=> 	esc_html__( 'Button link Color', 'acadprof' ),
                        'section'			=> 	'acadprof_featured_posts',
                        'settings'			=> 	'acadprof_featured_posts_btn_text_color',
                        'active_callback'	=> 	'acadprof_is_featured_posts_enabled',
                    ) 
                ) 
            );
        }

        public function customize_preview_js() {}

        public static function get_default_mods() {
		
            $defaults = array();
            $defaults['acadprof_featured_posts_block_heading'] =	esc_html__( 'Featured Posts', 'acadprof' );
            $defaults['acadprof_number_of_featured_posts_cards'] = 4;
    
            $defaults = apply_filters( 'acadprof_featured_posts_default_mods', $defaults );
    
            return $defaults;
        }
        
    }
}
Acadprof_Customize_Featured_Posts::get_instance();