<?php 
/**
 * Sanitize callback functions
 *
 * 
 * @package Acadprof
 *
 * @since 1.0.0
 *
 */
/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
if ( ! function_exists( 'acadprof_sanitize_checkbox' ) ) :

	function acadprof_sanitize_checkbox( $checked ) {

		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}

endif;

/**
 * Sanitize Select choices
 *
 * @param  string $input    setting input.
 * @param  object $setting  setting object.
 * @return mixed            setting input value.
 */
function acadprof_sanitize_choices( $input, $setting ) {

	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control
	// associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it;
	// otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitization Number
 *
 * @param number $input		Customizer setting input number
 * @param object $setting  	Setting object.
 * @return number			Return number.
 *
 */
if ( ! function_exists( 'acadprof_sanitize_number' ) ) {

    function acadprof_sanitize_number( $input, $setting ) {
        
        $input_attrs = array();

		if ( isset( $setting->manager->get_control( $setting->id )->input_attrs ) ) {
			$input_attrs = $setting->manager->get_control( $setting->id )->input_attrs;
		}

		if ( isset( $input_attrs ) ) {

			$input_attrs['min']  = isset( $input_attrs['min'] ) ? $input_attrs['min'] : 0;
			$input_attrs['step'] = isset( $input_attrs['step'] ) ? $input_attrs['step'] : 1;

			if ( isset( $input_attrs['max'] ) && $input > $input_attrs['max'] ) {
				$input = $input_attrs['max'];
			} elseif ( $input < $input_attrs['min'] ) {
				$input = $input_attrs['min'];
			}
		}
		return is_numeric( $input ) ? $input : '';
    }
}

/**
 * Sanitization: html
 * Control: textarea
 *
 * Sanitization callback for 'html' type text inputs. This
 * callback sanitizes $input for HTML allowable in posts.
 *
 * https://codex.wordpress.org/Function_Reference/wp_kses
 * https://gist.github.com/adamsilverstein/10783774
 * https://github.com/devinsays/options-framework-plugin/blob/master/options-check/functions.php#L69
 * http://ottopress.com/2010/wp-quickie-kses/
 * 
 * @uses	wp_filter_post_kses()	https://developer.wordpress.org/reference/functions/wp_filter_post_kses/
 * @uses	wp_kses()	https://developer.wordpress.org/reference/functions/wp_kses/
 */
if ( ! function_exists( 'acadprof_sanitize_html' ) ) {
	function acadprof_sanitize_html( $input ) {
		global $allowedposttags;

		return wp_kses( $input, $allowedposttags );
	}
}

/**
 * HEX Color sanitization callback
 *
 * - Sanitization: hex_color
 * - Control: text, WP_Customize_Color_Control
 *
 */
if ( ! function_exists( 'acadprof_sanitize_hex_color' ) ) {
	function acadprof_sanitize_hex_color( $hex_color, $setting ) {
		// Sanitize $input as a hex value without the hash prefix.
		$hex_color = sanitize_hex_color( $hex_color );
		
		// If $input is a valid hex value, return it; otherwise, return the default.
		return ( ! is_null( $hex_color ) ? $hex_color : $setting->default );
	}
}

/**
 * Alpha Color (Hex, RGB & RGBa) sanitization
 *
 * @param  string	Input to be sanitized
 * @return string	Sanitized input
 */
if ( ! function_exists( 'acadprof_hex_rgba_sanitization' ) ) {
	function acadprof_hex_rgba_sanitization( $input, $setting ) {
		if ( empty( $input ) || is_array( $input ) ) {
			return $setting->default;
		}

		if ( false === strpos( $input, 'rgb' ) ) {
			// If string doesn't start with 'rgb' then santize as hex color
			$input = sanitize_hex_color( $input );
		} else {
			if ( false === strpos( $input, 'rgba' ) ) {
				// Sanitize as RGB color
				$input = str_replace( ' ', '', $input );
				sscanf( $input, 'rgb(%d,%d,%d)', $red, $green, $blue );
				$input = 'rgb(' . acadprof_in_range( $red, 0, 255 ) . ',' . acadprof_in_range( $green, 0, 255 ) . ',' . acadprof_in_range( $blue, 0, 255 ) . ')';
			}
			else {
				// Sanitize as RGBa color
				$input = str_replace( ' ', '', $input );
				sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
				$input = 'rgba(' . acadprof_in_range( $red, 0, 255 ) . ',' . acadprof_in_range( $green, 0, 255 ) . ',' . acadprof_in_range( $blue, 0, 255 ) . ',' . acadprof_in_range( $alpha, 0, 1 ) . ')';
			}
		}
		return $input;
	}
}

/**
 * Only allow values between a certain minimum & maxmium range
 *
 * @param  number	Input to be sanitized
 * @return number	Sanitized input
 */
if ( ! function_exists( 'acadprof_in_range' ) ) {
	function acadprof_in_range( $input, $min, $max ) {
		if ( $input < $min ) {
			$input = $min;
		}
		if ( $input > $max ) {
			$input = $max;
		}
		return $input;
	}
}