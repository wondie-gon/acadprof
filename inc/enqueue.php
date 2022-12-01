<?php
/**
 * Acadprof enqueue scripts
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
* Font Awesome CDN Setup Webfont
*
* This is a function provided by fontawesome for enqueueing it in WordPress
*
* For further info, see
* @link https://fontawesome.com/v5.15/how-to-use/customizing-wordpress/snippets/setup-cdn-webfont
*
* Lay the Foundation
* First we’ll lay a foundation with this function that you’ll call one or more times to set up 
* the bits and pieces of Font Awesome that you choose.
*
* This will load Font Awesome from the Font Awesome Free or Pro CDN.
*/
if ( ! function_exists( 'acadprof_fa_custom_setup_cdn_webfont' )  ) {
  function acadprof_fa_custom_setup_cdn_webfont( $cdn_url = '', $integrity = null ) {
    $matches = [];
    $match_result = preg_match( '|/([^/]+?)\.css$|', $cdn_url, $matches );
    $resource_handle_uniqueness = ( $match_result === 1 ) ? $matches[1] : md5( $cdn_url );
    $resource_handle = "font-awesome-cdn-webfont-$resource_handle_uniqueness";

    foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action ) {
      add_action(
        $action,
        function () use ( $cdn_url, $resource_handle ) {
          wp_enqueue_style( $resource_handle, $cdn_url, [], null );
        }
      );
    }

    if( $integrity ) {
      add_filter(
        'style_loader_tag',
        function( $html, $handle ) use ( $resource_handle, $integrity ) {
          if ( in_array( $handle, [ $resource_handle ], true ) ) {
            return preg_replace(
              '/\/>$/',
              'integrity="' . $integrity .
              '" crossorigin="anonymous" />',
              $html,
              1
            );
          } else {
            return $html;
          }
        },
        10,
        2
      );
    }
  }
}

/**
* Enqueue the latest available Fontawesome Style using setup function from fontawesome
*
* For the CDN, Go to 
* @link https://cdnjs.com/
* 
*/
acadprof_fa_custom_setup_cdn_webfont(
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css',
  'sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=='
);

/**
* Custom function to add to core action hook 'wp_enqueue_scripts'
* so as to enqueue styles and scripts for the theme
*/
if ( ! function_exists( 'acadprof_scripts' ) ) {
	/**
	 * Load theme's JavaScript and CSS sources.
	 */
	function acadprof_scripts() {
		// Get the theme data.
		$the_theme         = wp_get_theme();
		$theme_version     = $the_theme->get( 'Version' );
		$bootstrap_version = get_theme_mod( 'acadprof_bootstrap_version', 'bootstrap4' );
		$suffix            = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Grab asset urls.
		// $theme_styles  = "/assets/css/theme{$suffix}.css";
		$theme_styles  = "/assets/css/theme.css";
		$custom_styles  = "/assets/css/acadprof-custom.css";
		// $theme_scripts = "/assets/js/theme{$suffix}.js";
		$theme_scripts = "/assets/js/theme.js";
		if ( 'bootstrap4' === $bootstrap_version ) {
			$theme_styles  = "/assets/css/theme-bootstrap4{$suffix}.css";
			$theme_scripts = "/assets/js/theme-bootstrap4{$suffix}.js";
		}

		$css_version = $theme_version . '.' . filemtime( get_template_directory() . $theme_styles );
		wp_enqueue_style( 'acadprof-styles', get_template_directory_uri() . $theme_styles, array(), $css_version );
		// custom style
		wp_enqueue_style( 'acadprof-custom-styles', get_template_directory_uri() . $custom_styles, array(), $css_version );

		wp_enqueue_script( 'jquery' );

		$js_version = $theme_version . '.' . filemtime( get_template_directory() . $theme_scripts );
		wp_enqueue_script( 'acadprof-scripts', get_template_directory_uri() . $theme_scripts, array(), $js_version, true );
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
} // End of if function_exists( 'acadprof_scripts' ).

add_action( 'wp_enqueue_scripts', 'acadprof_scripts' );