<?php
/**
 * Custom header setup
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Hook custom headers callback to 'after_setup_theme' action hook
 */
add_action( 'after_setup_theme', 'acadprof_custom_header_setup' );

if ( ! function_exists( 'acadprof_custom_header_setup' ) ) {
	/**
	 * Set up custom header feature.
	 */
	function acadprof_custom_header_setup() {

		$acadprof_custom_hdr_args = array(
			'width'         		=>	980,
			'flex-width'			=>	true,
			'height'        		=>	200,
			'flex-height'   		=>	true,
			'default-text-color'	=>	'#BECCD4',
			'header-text'			=> 	true,
			'uploads'               => 	true,
			'default-image' 		=>	get_template_directory_uri() . '/assets/images/ap-header-img.jpg',
		);

		/**
		 * Filter acadprof custom-header support arguments.
		 *
		 * @since 1.0
		 *
		 * @param array $acadprof_custom_hdr_args {
		 *     An array of custom-header support arguments.
		 * 
		 *     	@type int		'width'					Width in pixels of the custom 
		 * 												header image. Default 980.
		 * 		@type string	'flex-width'            Flex support for width of header.
		 *     	@type int		'height'				Height in pixels of the custom 
		 * 												header image. Default 200.
		 * 		@type string	'flex-height'			Flex support for height of header.
		 * 		@type string	'default-text-color'	Default color of the header text.
		 * 		@type bool		'header-text'			Display the header text along 
		 * 												with the image.
		 * 		@type bool		'uploads'				Enable upload of image file 
		 * 												in admin.
		 * 		@type string	'default-image'     	Default image of the header. 	
		 * }
		 */
		$acadprof_custom_hdr_args = apply_filters( 'acadprof_custom_header_args', $acadprof_custom_hdr_args );

		// add to theme support
		add_theme_support( 'custom-header', $acadprof_custom_hdr_args );
/*
		add_theme_support(
			'custom-header',
			apply_filters(
				'acadprof_custom_header_args',
				array(
					'width'         =>	980,
					'flex-width'	=>	true,
					'height'        =>	200,
					'flex-height'   =>	true,
					'default-image' => get_parent_theme_file_uri( '/assets/images/ap-header-img.jpg' ),
				)
			)
		);
*/
		/**
		 * Registering defualt images for custom header
		 */
		$acadprof_default_header_imgs = array(
			'ap-header-img'	=>	array(
				'url'		=>	'%s/assets/images/ap-header-img.jpg',
				'thumbnail_url'	=>	'%s/assets/images/ap-header-img_thumbnail.jpg',
				'description'	=>	__( 'Default Header Image Light', 'acadprof' ),
			),
			'ap-header-img-alt'	=>	array(
				'url'		=>	'%s/assets/images/ap-header-img-alt.jpg',
				'thumbnail_url'	=>	'%s/assets/images/ap-header-img-alt_thumbnail.jpg',
				'description'	=>	__( 'Default Header Image Dark', 'acadprof' ),
			),
		);

		// register default header images
		register_default_headers( $acadprof_default_header_imgs );
	}
}
