<?php 
/**
 * Social media links customizer
 * 
 * @package Acadprof
 * @since 1.0.0
 */
// disallow direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_Customize_Social_Media' ) ) {
    class Acadprof_Customize_Social_Media {
        /**
		 * Instance
		 *
		 * @access private
		 * @var object
		 */
        private static $instance;

        /**
         * class instance initiator
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
                add_action( 'customize_register', array( $this, 'register_section_and_fields' ) );
            }
        }
        
        /**
         * Action function to register section and settings
         * @param WP_Customize_Manager $wp_customize Customizer reference.
         */
        public function register_section_and_fields( $wp_customize ) {
            /**
             * Social media link customizer section
             */
            $wp_customize->add_section(
                'acadprof_social_media_link_section', 
                array(
                    'title'		=>	__( 'Social Media Links', 'acadprof' ),
                    'capability'  => 'edit_theme_options',
                    'description' => __( 'Customize social media links menu and sharing links', 'acadprof' ),
                    'priority'	=>	160,
                )
            );
            /* 
            * Whether to display social media link nav bar
            */
            $wp_customize->add_setting(
                'enable_acadprof_social_media_link_nav',
                array(
                    'default'	=> false,
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod',  
                    'sanitize_callback'	=>	'acadprof_sanitize_checkbox'
                )
            );
            $wp_customize->add_control(
                new WP_Customize_Control(
                    $wp_customize,
                    'enable_acadprof_social_media_link_nav',
                    array(
                        'label' 		=> __( 'Enable Social Media links', 'acadprof' ),
                        'section' 		=> 'acadprof_social_media_link_section',
                        'settings'		=> 'enable_acadprof_social_media_link_nav',
                        'type' 			=> 'checkbox',
                    )
                )
            );

            /* 
            * setting up social media usernames
            */
            // Facebook
            $wp_customize->add_setting(
                'acadprof_facebook_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_facebook_link_username', 
                    array(
                        'settings'			=> 'acadprof_facebook_link_username', 
                        'label'				=>	__( 'Facebook', 'acadprof' ), 
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );

            // Twitter
            $wp_customize->add_setting(
                'acadprof_twitter_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_twitter_link_username', 
                    array(
                        'settings'			=>  'acadprof_twitter_link_username', 
                        'label'				=>	__( 'Twitter', 'acadprof' ), 
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );

            // googleplus
            $wp_customize->add_setting(
                'acadprof_googleplus_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_googleplus_link_username', 
                    array(
                        'settings'			=>  'acadprof_googleplus_link_username', 
                        'label'				=>	__( 'Google+', 'acadprof' ), 
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );
            // pinterest
            $wp_customize->add_setting(
                'acadprof_pinterest_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_pinterest_link_username', 
                    array(
                        'settings'			=>	'acadprof_pinterest_link_username',
                        'section'			=>	'acadprof_social_media_link_section',
                        'label'				=>	__( 'Pinterest', 'acadprof' ), 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );
            // linkedin
            $wp_customize->add_setting(
                'acadprof_linkedin_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_linkedin_link_username', 
                    array(
                        'settings'			=>	'acadprof_linkedin_link_username', 
                        'label'				=>	__( 'Linkedin', 'acadprof' ), 
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );

            // github
            $wp_customize->add_setting(
                'acadprof_github_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_github_link_username', 
                    array(
                        'settings'			=>	'acadprof_github_link_username', 
                        'label'				=>	__( 'Github', 'acadprof' ), 
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );
            // instagram
            $wp_customize->add_setting(
                'acadprof_instagram_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_instagram_link_username', 
                    array(
                        'settings'			=>	'acadprof_instagram_link_username', 
                        'label'				=>	__( 'Instagram', 'acadprof' ),
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );

            // youtube
            $wp_customize->add_setting(
                'acadprof_youtube_link_username', 
                array(
                    'default'	=>	'', 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod', 
                    'sanitize_callback' => 'sanitize_text_field',	
                )
            );

            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_youtube_link_username', 
                    array(
                        'settings'			=>	'acadprof_youtube_link_username',
                        'section'			=>	'acadprof_social_media_link_section',
                        'label'				=>	__( 'Youtube', 'acadprof' ), 
                        'active_callback'	=>	'acadprof_if_social_media_link_nav_enabled',
                    )
                )
            );

            /* 
            * Whether to display social media sharing
            */
            $wp_customize->add_setting(
                'enable_acadprof_social_media_sharing',
                array(
                    'default'	=> false,
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod',  
                    'sanitize_callback'	=>	'acadprof_sanitize_checkbox'
                )
            );
            $wp_customize->add_control(
                'enable_acadprof_social_media_sharing',
                array(
                    'settings' => 'enable_acadprof_social_media_sharing', 
                    'label' => __( 'Enable Social Media Sharing', 'acadprof' ), 
                    'description'	=>	__( 'Check which social media to share posts to.', 'acadprof' ),
                    'section' => 'acadprof_social_media_link_section',
                    'type' => 'checkbox',
                )
            );

            /* 
            * Check social media to which posts are to be shared
            */
            // Facebook
            $wp_customize->add_setting(
                'acadprof_select_facebook_to_share_post', 
                array(
                    'default'	=>	false, 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod',  
                    'sanitize_callback' => 'acadprof_sanitize_checkbox',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_select_facebook_to_share_post', 
                    array(
                        'settings'          => 'acadprof_select_facebook_to_share_post', 
                        'label'				=>	__( 'Facebook', 'acadprof' ),
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_sharing_enabled',
                        'type'				=>	'checkbox',
                    )
                )
            );
            // Twitter
            $wp_customize->add_setting(
                'acadprof_select_twitter_to_share_post', 
                array(
                    'default'	        =>	false, 
                    'capability'        => 'edit_theme_options', 
                    'type'              => 'theme_mod',  
                    'sanitize_callback' => 'acadprof_sanitize_checkbox',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_select_twitter_to_share_post', 
                    array(
                        'settings' 			=> 'acadprof_select_twitter_to_share_post', 
                        'label'				=>	__( 'Twitter', 'acadprof' ),
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_sharing_enabled',
                        'type'				=>	'checkbox',
                    )
                )
            );
            // googleplus
            $wp_customize->add_setting(
                'acadprof_select_googleplus_to_share_post', 
                array(
                    'default'	        =>	false, 
                    'capability'        => 'edit_theme_options', 
                    'type'              => 'theme_mod',  
                    'sanitize_callback' => 'acadprof_sanitize_checkbox',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_select_googleplus_to_share_post', 
                    array(
                        'settings' 			=> 'acadprof_select_googleplus_to_share_post', 
                        'label'				=>	__( 'Google+', 'acadprof' ),
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_sharing_enabled',
                        'type'				=>	'checkbox',
                    )
                )
            );
            // pinterest
            $wp_customize->add_setting(
                'acadprof_select_pinterest_to_share_post', 
                array(
                    'default'	        =>	false, 
                    'capability'        => 'edit_theme_options', 
                    'type'              => 'theme_mod',  
                    'sanitize_callback' => 'acadprof_sanitize_checkbox',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_select_pinterest_to_share_post', 
                    array(
                        'settings' 			=> 'acadprof_select_pinterest_to_share_post', 
                        'label'				=>	__( 'Pinterest', 'acadprof' ), 
                        'section'			=>	'acadprof_social_media_link_section', 
                        'active_callback'	=>	'acadprof_if_social_media_sharing_enabled',
                        'type'				=>	'checkbox',
                    )
                )
            );
            // linkedin
            $wp_customize->add_setting(
                'acadprof_select_linkedin_to_share_post', 
                array(
                    'default'	=>	false, 
                    'capability' => 'edit_theme_options', 
                    'type'              => 'theme_mod',  
                    'sanitize_callback' => 'acadprof_sanitize_checkbox',	
                )
            );
            $wp_customize->add_control( 
                new WP_Customize_Control(
                    $wp_customize, 
                    'acadprof_select_linkedin_to_share_post', 
                    array(
                        'settings' 			=> 'acadprof_select_linkedin_to_share_post', 
                        'label'				=>	__( 'Linkedin', 'acadprof' ), 
                        'section'			=>	'acadprof_social_media_link_section',
                        'active_callback'	=>	'acadprof_if_social_media_sharing_enabled',
                        'type'				=>	'checkbox',
                    )
                )
            );
        }
    }
}
/**
 * Initiating class
 */
Acadprof_Customize_Social_Media::get_instance();