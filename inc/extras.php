<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_filter( 'body_class', 'acadprof_body_classes' );

if ( ! function_exists( 'acadprof_body_classes' ) ) {
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function acadprof_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		// Adds a body class based on the presence of a sidebar.
		$sidebar_pos = get_theme_mod( 'acadprof_sidebar_position' );
		if ( is_page_template( 'page-templates/fullwidthpage.php' ) ) {
			$classes[] = 'acadprof-no-sidebar';
		} elseif (
			is_page_template(
				array(
					'page-templates/both-sidebarspage.php',
					'page-templates/left-sidebarpage.php',
					'page-templates/right-sidebarpage.php',
				)
			)
		) {
			$classes[] = 'acadprof-has-sidebar';
		} elseif ( 'none' !== $sidebar_pos ) {
			$classes[] = 'acadprof-has-sidebar';
		} else {
			$classes[] = 'acadprof-no-sidebar';
		}

		// adds class to all pages for custom styles
		$classes[] = 'wonui-page';

		return $classes;
	}
}

if ( function_exists( 'acadprof_adjust_body_class' ) ) {
	/*
	 * acadprof_adjust_body_class() deprecated in v0.9.4. We keep adding the
	 * filter for child themes which use their own acadprof_adjust_body_class.
	 */
	add_filter( 'body_class', 'acadprof_adjust_body_class' );
}

// Filter custom logo with correct classes.
add_filter( 'get_custom_logo', 'acadprof_change_logo_class' );

if ( ! function_exists( 'acadprof_change_logo_class' ) ) {
	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return string
	 */
	function acadprof_change_logo_class( $html ) {

		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html );
		$html = str_replace( 'alt=""', 'title="Home" alt="logo"', $html );

		return $html;
	}
}

if ( ! function_exists( 'acadprof_pingback' ) ) {
	/**
	 * Add a pingback url auto-discovery header for single posts of any post type.
	 */
	function acadprof_pingback() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="' . esc_url( get_bloginfo( 'pingback_url' ) ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'acadprof_pingback' );

if ( ! function_exists( 'acadprof_mobile_web_app_meta' ) ) {
	/**
	 * Add mobile-web-app meta.
	 */
	function acadprof_mobile_web_app_meta() {
		echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
		echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr( get_bloginfo( 'name' ) ) . ' - ' . esc_attr( get_bloginfo( 'description' ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'acadprof_mobile_web_app_meta' );

/**
 * Custom action hook to add Open Graph Meta Tags
 */
add_action( 'wp_head', 'acadprof_meta_og', 5 );

if ( ! function_exists( 'acadprof_meta_og' ) ) {
	function acadprof_meta_og() {
		if ( ! is_single() ) {
	    	return;
	    }

	    global $post;

    	// url of 1 attachment such as image
        $atchmnt_url = acadprof_get_attachment_url( 1 ); 

        $excerpt = strip_tags( $post->post_content );
        $excerpt_more = '';
        if ( strlen( $excerpt ) > 155) {
            $excerpt = substr( $excerpt, 0, 155 );
            $excerpt_more = ' ...';
        }
        $excerpt = str_replace( '"', '', $excerpt );
        $excerpt = str_replace( "'", '', $excerpt );
        $excerptwords = preg_split( '/[\n\r\t ]+/', $excerpt, -1, PREG_SPLIT_NO_EMPTY );
        array_pop( $excerptwords );
        $excerpt = implode( ' ', $excerptwords ) . $excerpt_more;

        // translate ready excerpt
        $excerpt_ready = sprintf( esc_html__( '%s', 'acadprof' ), $excerpt );

        // translate ready title
        $title_ready = sprintf( esc_html__( '%s', 'acadprof' ), get_the_title() );

        // translate ready site name
        $site_name_ready = sprintf( esc_html__( '%s', 'acadprof' ), get_bloginfo( 'name' ) );
    ?>
	<meta name="author" content="<?php esc_html( get_the_author() ); ?>">
	<meta name="description" content="<?php echo $excerpt_ready; ?>">
	<meta property="og:title" content="<?php echo $title_ready; ?>">
	<meta property="og:description" content="<?php echo $excerpt_ready; ?>">
	<meta property="og:type" content="article">
	<meta property="og:url" content="<?php echo get_permalink(); ?>">
	<meta property="og:site_name" content="<?php echo $site_name_ready; ?>">
	<?php if ( ! empty( $atchmnt_url ) ) : ?>
	<meta property="og:image" content="<?php echo esc_url( $atchmnt_url ); ?>">
	<?php endif; ?>
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:description" content="<?php echo $excerpt_ready; ?>" />
	<meta name="twitter:title" content="<?php echo $title_ready; ?>" />
	<meta name="twitter:site" content="<?php echo $site_name_ready; ?>" />
	<?php if ( ! empty( $atchmnt_url ) ) : ?>
	<meta name="twitter:image" content="<?php echo esc_url( $atchmnt_url ); ?>" />
	<?php endif; ?>
	<?php
	}
}

if ( ! function_exists( 'acadprof_default_body_attributes' ) ) {
	/**
	 * Adds schema markup to the body element.
	 *
	 * @param array $atts An associative array of attributes.
	 * @return array
	 */
	function acadprof_default_body_attributes( $atts ) {
		$atts['itemscope'] = '';
		$atts['itemtype']  = 'http://schema.org/WebSite';
		return $atts;
	}
}
add_filter( 'acadprof_body_attributes', 'acadprof_default_body_attributes' );

// Escapes all occurances of 'the_archive_description'.
add_filter( 'get_the_archive_description', 'acadprof_escape_the_archive_description' );

if ( ! function_exists( 'acadprof_escape_the_archive_description' ) ) {
	/**
	 * Escapes the description for an author or post type archive.
	 *
	 * @param string $description Archive description.
	 * @return string Maybe escaped $description.
	 */
	function acadprof_escape_the_archive_description( $description ) {
		if ( is_author() || is_post_type_archive() ) {
			return wp_kses_post( $description );
		}

		/*
		 * All other descriptions are retrieved via term_description() which returns
		 * a sanitized description.
		 */
		return $description;
	}
} // End of if function_exists( 'acadprof_escape_the_archive_description' ).

// Escapes all occurances of 'the_title()' and 'get_the_title()'.
add_filter( 'the_title', 'acadprof_kses_title' );

// Escapes all occurances of 'the_archive_title' and 'get_the_archive_title()'.
add_filter( 'get_the_archive_title', 'acadprof_kses_title' );

if ( ! function_exists( 'acadprof_kses_title' ) ) {
	/**
	 * Sanitizes data for allowed HTML tags for post title.
	 *
	 * @param string $data Post title to filter.
	 * @return string Filtered post title with allowed HTML tags and attributes intact.
	 */
	function acadprof_kses_title( $data ) {
		// Tags not supported in HTML5 are not allowed.
		$allowed_tags = array(
			'abbr'             => array(),
			'aria-describedby' => true,
			'aria-details'     => true,
			'aria-label'       => true,
			'aria-labelledby'  => true,
			'aria-hidden'      => true,
			'b'                => array(),
			'bdo'              => array(
				'dir' => true,
			),
			'blockquote'       => array(
				'cite'     => true,
				'lang'     => true,
				'xml:lang' => true,
			),
			'cite'             => array(
				'dir'  => true,
				'lang' => true,
			),
			'dfn'              => array(),
			'em'               => array(),
			'i'                => array(
				'aria-describedby' => true,
				'aria-details'     => true,
				'aria-label'       => true,
				'aria-labelledby'  => true,
				'aria-hidden'      => true,
				'class'            => true,
			),
			'code'             => array(),
			'del'              => array(
				'datetime' => true,
			),
			'img'              => array(
				'src'    => true,
				'alt'    => true,
				'width'  => true,
				'height' => true,
				'class'  => true,
				'style'  => true,
			),
			'ins'              => array(
				'datetime' => true,
				'cite'     => true,
			),
			'kbd'              => array(),
			'mark'             => array(),
			'pre'              => array(
				'width' => true,
			),
			'q'                => array(
				'cite' => true,
			),
			's'                => array(),
			'samp'             => array(),
			'span'             => array(
				'dir'      => true,
				'align'    => true,
				'lang'     => true,
				'xml:lang' => true,
			),
			'small'            => array(),
			'strong'           => array(),
			'sub'              => array(),
			'sup'              => array(),
			'u'                => array(),
			'var'              => array(),
		);
		$allowed_tags = apply_filters( 'acadprof_kses_title', $allowed_tags );

		return wp_kses( $data, $allowed_tags );
	}
} // End of if function_exists( 'acadprof_kses_title' ).

/**
* This function will change time to a custom string
* @param (int) $time in seconds
* --Examples--
* echo acadprof_elapsed_time_to_nice_time(94672800);
*
* @since 1.0.0
*/
function acadprof_elapsed_time_to_nice_time( $time ) {

    // nice time units in seconds
    $one_minute = 60;
    $one_hour = 60 * 60; // 3600
    $one_day = 24 * 60 * 60; // 86400
    $one_week = 7 * 24 * 60 * 60; // 604800
    // $one_month = 30 * 24 * 60 * 60; // but different months have d/t num of days
    $one_year = 365 * 24 * 60 * 60; // 31557600

    $suffix_str = esc_html__( 'ago', 'acadprof' );
    $rounded_time = 0;
    $time_unit = '';

    // construct output, compare time
    if ( $time <= 5 * $one_minute ) {

        $nice_time = esc_html__( 'Now', 'acadprof' );

    } else {
        
        if ( ( $time > 5 * $one_minute ) && ( $time < $one_hour ) ) {

            $rounded_time = intdiv( $time, $one_minute );

            $time_unit = ( $rounded_time == 1 ) ? esc_html__( 'minute', 'acadprof' ) : esc_html__( 'minutes', 'acadprof' );

        } elseif ( ( $time >= $one_hour ) && ( $time < $one_day ) ) {

            $rounded_time = intdiv( $time, $one_hour );

            $time_unit = ( $rounded_time == 1 ) ? esc_html__( 'hour', 'acadprof' ) : esc_html__( 'hours', 'acadprof' );

        } elseif ( ( $time >= $one_day ) && ( $time < $one_week ) ) {

            $rounded_time = intdiv( $time, $one_day );

            $time_unit = ( $rounded_time == 1 ) ? esc_html__( 'day', 'acadprof' ) : esc_html__( 'days', 'acadprof' );

        } elseif ( ( $time >= $one_week ) && ( $time < $one_year ) ) {

            $rounded_time = intdiv( $time, $one_week );

            $time_unit = ( $rounded_time == 1 ) ? esc_html__( 'week', 'acadprof' ) : esc_html__( 'weeks', 'acadprof' );

        } elseif ( $time >= $one_year ) {

            $rounded_time = intdiv( $time, $one_year );

            $time_unit = ( $rounded_time == 1 ) ? esc_html__( 'year', 'acadprof' ) : esc_html__( 'years', 'acadprof' );

        }

        $nice_time = $rounded_time . '&nbsp;' . $time_unit . '&nbsp;' . $suffix_str;

    }

    return $nice_time;

}

/**
* function to change published time to a text of rounded elapsed time 
* after post published
* 
* @since 1.0.0
*/
if ( ! function_exists( 'acadprof_posted_on_stringified' ) ) {
	function acadprof_posted_on_stringified() {
	    /*
	    * current Unix timestamp
	    */
	    $time_now = time();

	    /*
	    * published Unix timestamp
	    */
	    $publishedtimestamp = get_post_timestamp();

	    // elapsed time
	    $time_since_published = $time_now - $publishedtimestamp;

	    // changing time to a custom string 
	    $time_stringified = acadprof_elapsed_time_to_nice_time( $time_since_published );

	    $nice_time_output = '<span class="posted-on mr-3">' . $time_stringified . '</span>';

	    return $nice_time_output;
	}
}

/**
* To get rid of the “Category:”, “Tag:”, “Author:”, “Archives:” 
* and “Other taxonomy name:” in the archive title
*/
if ( ! function_exists( 'acadprof_modified_archive_title' ) ) {
	function acadprof_modified_archive_title( $title ) {
	    if ( is_category() ) {
	        $title = single_cat_title( '', false );
	    } elseif ( is_tag() ) {
	        $title = single_tag_title( '', false );
	    } elseif ( is_author() ) {
	        $title = '<span class="vcard">' . get_the_author() . '</span>';
	    } elseif ( is_post_type_archive() ) {
	        $title = post_type_archive_title( '', false );
	    } elseif ( is_tax() ) {
	        $title = single_term_title( '', false );
	    }
	  
	    return $title;
	}
}
add_filter( 'get_the_archive_title', 'acadprof_modified_archive_title' );

if ( ! function_exists( 'acadprof_default_navbar_type' ) ) {
	/**
	 * Overrides the responsive navbar type for Bootstrap 4
	 *
	 * @param string $current_mod
	 * @return string
	 */
	function acadprof_default_navbar_type( $current_mod ) {

		if ( 'bootstrap5' !== get_theme_mod( 'acadprof_bootstrap_version' ) ) {
			$current_mod = 'collapse';
		}

		return $current_mod;
	}
}
add_filter( 'theme_mod_acadprof_navbar_type', 'acadprof_default_navbar_type', 20 );
