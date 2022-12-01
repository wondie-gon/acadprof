<?php
/**
 * Custom hooks
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acadprof_site_info' ) ) {
	/**
	 * Add site info hook to WP hook library.
	 */
	function acadprof_site_info() {
		do_action( 'acadprof_site_info' );
	}
}

add_action( 'acadprof_site_info', 'acadprof_add_site_info' );
if ( ! function_exists( 'acadprof_add_site_info' ) ) {
	/**
	 * Add site info content.
	 */
	function acadprof_add_site_info() {
		$the_theme = wp_get_theme();

		$site_info = sprintf(
			'%1$s<a href="%2$s">%3$s</a><span class="sep"> | </span>%4$s (%5$s) %6$s',
			esc_html__( 'Powered by ', 'acadprof' ),
			esc_url( __( 'https://wordpress.org/', 'acadprof' ) ),
			esc_html( 'WordPress' ),
			sprintf( 
				'<a href="%1$s">%2$s</a>',
				esc_url( $the_theme->get( 'ThemeURI' ) ),
				$the_theme->get( 'Name' )
			),
			sprintf( // WPCS: XSS ok.
				/* translators: Theme version */
				esc_html__( 'Version: %1$s', 'acadprof' ),
				$the_theme->get( 'Version' )
			),
			sprintf( 
				'%1$s<a href="%2$s">%3$s</a>',
				/* translators: By */
				esc_html__( 'By ', 'acadprof' ),
				esc_url( $the_theme->get( 'AuthorURI' ) ),
				$the_theme->get( 'Author' )
			)
		);

		// Check if customizer site info has value.
		if ( get_theme_mod( 'acadprof_site_info_override' ) ) {
			$site_info = get_theme_mod( 'acadprof_site_info_override' );
		}

		echo apply_filters( 'acadprof_site_info_content', $site_info ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
}

/**
 * Hides the posted by markup in `acadprof_posted_on()`.
 *
 * @param string $byline Posted by HTML markup.
 * @return string Maybe filtered posted by HTML markup.
 */
add_filter( 'acadprof_posted_by', 'acadprof_hide_posted_by' );

if ( ! function_exists( 'acadprof_hide_posted_by' ) ) {
	function acadprof_hide_posted_by( $byline ) {
		if ( is_author() ) {
			return '';
		}
		return $byline;
	}
}

/**
 * Removes the ... from the excerpt read more link
 *
 * @param string $more The excerpt.
 *
 * @return string
 */
add_filter( 'excerpt_more', 'acadprof_custom_excerpt_more' );

if ( ! function_exists( 'acadprof_custom_excerpt_more' ) ) {
	function acadprof_custom_excerpt_more( $more ) {
		if ( ! is_admin() ) {
			$more = '';
		}
		return $more;
	}
}

/**
 * Filters excerpt length
 *
 * @param number $length length of excerpt
 *
 * @return number modified length of excerpt
 */
add_filter( 'excerpt_length', 'acadprof_custom_excerpt_length', 999 );

if ( ! function_exists( 'acadprof_custom_excerpt_length' ) ) {
	function acadprof_custom_excerpt_length( $length ) {
		$custom_length = get_theme_mod( 'acadprof_excerpt_length' );

		if ( '' !== $custom_length ) {
			return absint( $custom_length );
		} else {
			return $length;
		}
	}
}

/**
 * Adds a custom read more link to all excerpts, manually or automatically generated
 *
 * @param string $post_excerpt Posts's excerpt.
 *
 * @return string
 */
add_filter( 'wp_trim_excerpt', 'acadprof_all_excerpts_get_more_link' );

if ( ! function_exists( 'acadprof_all_excerpts_get_more_link' ) ) {
	function acadprof_all_excerpts_get_more_link( $post_excerpt ) {
		if ( ! is_admin() ) {
			$post_excerpt = $post_excerpt . ' [...]<p><a class="btn btn-wonui rounded-btns acadprof-read-more-link" href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . __(
				'Read More',
				'acadprof'
			) . '<i class="fa fa-angle-right ms-2"></i><span class="screen-reader-text"> from ' . get_the_title( get_the_ID() ) . '</span></a></p>';
		}
		return $post_excerpt;
	}
}

/**
* Hook action to wp_footer when 'acadprof_profile_intro' is active
*/
if ( is_active_sidebar( 'acadprof_profile_intro' ) ) {
	add_action( 'wp_footer', 'acadprof_profile_intro_equal_height_js' );
}
/**
* Hook to add JavaScript anonymous function to 
* make profile intro widget's intro header and text boxes equal height
*/
if ( ! function_exists( 'acadprof_profile_intro_equal_height_js' ) ) {
	function acadprof_profile_intro_equal_height_js() {
		if ( ( is_front_page() && is_home() ) || ( is_front_page() && is_page_template( 'page-templates/fullwidthpage.php' ) ) || ( is_page( 'about' ) && is_page_template( 'page-templates/fullwidthpage.php' ) ) ) {
			
			?>
			<script type="text/javascript">
		        ( function() {
					/**
					* making profile intro widget header and text flex boxes equal height
					*/	
					const profilePic = document.querySelector( '.intro-image' );
					const profilePicHolder = document.querySelector( '.img-holder' );
					const profilePicHolderHeight = profilePicHolder.clientHeight;
					const introHdr = document.querySelector( '.intro-hdr' );
					const introTxt = document.querySelector( '.intro-txt' );
					let viewPort = window.visualViewport;
					if ( viewPort.width >= 768 ) {
						introHdr.style.height = profilePicHolderHeight / 2 + 'px';
						introTxt.style.height = profilePicHolderHeight / 2 + 'px';
					} else {
						profilePic.style.width = 96 + 'vw';
					}

		        } )();
	      	</script>
			<?php
		}
	}
}

// Hook social media sharing feature jQuery script
if ( get_theme_mod( 'enable_acadprof_social_media_sharing' ) ) {
	add_action( 'wp_footer', 'acadprof_social_media_share_feature_js' );
}
/**
* Hook to add jQuery anonymous function 
* for the functionality of social media sharing feature
*/
if ( ! function_exists( 'acadprof_social_media_share_feature_js' ) ) {
	function acadprof_social_media_share_feature_js() {
		if ( is_single() ) {
			?>
			<script type="text/javascript">
		        ( function( $ ) {
					var left_win = ( screen.width - 540 ) / 2;
					var top_win = ( screen.height - 540 ) / 2;
					var params = "menubar=no, toolbar=no, status=no, width=540, height=450, top=" + top_win + ", left=" + left_win;

					$( 'a.social-share-link' ).on( 'click', function( event ) {
						event.preventDefault();
						var href = $( this ).attr( 'href' );
						window.open( href, "NewWindow", params );
					} );

		        } )( jQuery );
	      	</script>
			<?php
		}
	}
}
