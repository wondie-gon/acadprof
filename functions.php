<?php
/**
 * UnderStrap functions and definitions
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// theme version
if ( ! defined( '_AP_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_AP_VERSION', '1.0.0' );
}

/**
* Dynamic version to solve caching during theme development
*
* NOTE ======> Make sure to change value to false at production
*/
if ( ! defined( 'AP_DEV_MODE' ) ) {
	define( 'AP_DEV_MODE', true );
}

// UnderStrap's includes directory.
$acadprof_inc_dir = 'inc';

// Array of files to include.
$acadprof_includes = array(
	'/theme-settings.php',                  						// Initialize theme default settings.
	'/setup.php',                           						// Theme setup and theme supports.
	'/widgets.php',                         						// Register widget area.
	'/enqueue.php',                         						// Enqueue scripts and styles.
	'/custom-post-metaboxes.php',           						// Custom post meta boxes.
	'/customize/active-callbacks.php',    							// customizer active callbacks
	'/customize/sanitize-callbacks.php',    						// customizer sanitize callbacks
	'/customize/class-acadprof-customizer.php',     				// General customizer
	'/template-tags.php',                   						// Custom template tags
	'/pagination.php',                      						// Custom pagination for this theme.
	'/custom-comments.php', 										// custom comments template hooks
	'/hooks.php',                           						// Custom hooks.
	'/extras.php',                          						// Custom independent functions
	'/template-builder/class-acadprof-post-excerpt-layout.php', 	// excerpt template builder
	'/social-media-links.php',   									// templates for social media links
	'/custom-template-hooks.php',           						// Custom template hooks.
	'/class-wp-bootstrap-navwalker.php',    						// Load custom WP nav walker.
	'/class-acadprof-profile-intro-widget.php', 					// profile intro widget
	'/editor.php',                          						// Load Editor functions.
	'/block-editor.php',                    						// Load Block Editor functions.
	'/deprecated.php',                      						// Load deprecated functions.
);

// load shortcodes
$acadprof_includes[] = '/shortcodes/class-acadprof-shortcodes.php';

// Load WooCommerce functions if WooCommerce is activated.
if ( class_exists( 'WooCommerce' ) ) {
	$acadprof_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if ( class_exists( 'Jetpack' ) ) {
	$acadprof_includes[] = '/jetpack.php';
}

// Include files.
foreach ( $acadprof_includes as $file ) {
	require_once get_theme_file_path( $acadprof_inc_dir . $file );
}

// Register and load profile intro widget
function register_profile_intro_widget() {
    register_widget( 'Acadprof_Profile_Intro_Widget' );
}
add_action( 'widgets_init', 'register_profile_intro_widget' );

// Initializing shortcodes
$acadprof_scodes = new Acadprof_Shortcodes( 'acadprof' );
$acadprof_scodes->init();
