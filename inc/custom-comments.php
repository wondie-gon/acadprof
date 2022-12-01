<?php
/**
 * Comment layout
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Add Bootstrap classes to comment form submit button and comment field.
add_filter( 'comment_form_defaults', 'acadprof_bootstrap_floating_comment_form' );

if ( ! function_exists( 'acadprof_bootstrap_floating_comment_form' ) ) {
	/**
	 * Adds Bootstrap classes to comment form submit button and comment field.
	 *
	 * @param string[] $args Comment form arguments and fields.
	 *
	 * @return string[]
	 */
	function acadprof_bootstrap_floating_comment_form( $args ) {
		// get commenter data
		$commenter     = wp_get_current_commenter();
		
		// check if name and email set to required
		$req   = get_option( 'require_name_email' );
		// check html5 supported
		$is_html5 = current_theme_supports( 'html5', 'comment-form' );
		// change default $args['format']
		$args['format'] = $is_html5 ? 'html5' : 'xhtml';
		// Define attributes in HTML5 or XHTML syntax.
		$required_attribute = ( $is_html5 ? ' required' : ' required="required"' );
		$checked_attribute  = ( $is_html5 ? ' checked' : ' checked="checked"' );

		// Identify required fields visually.
		$required_indicator = ' <span class="required" aria-hidden="true">*</span>';
		// arguments of comment form
		$args = array(
			'comment_field'		=>	sprintf(
				'<div class="comment-form-comment form-floating mb-3">%s %s</div>', 
				sprintf(
					'<textarea class="form-control" placeholder="%s" id="comment" name="comment" aria-required="true" style="height: 150px"></textarea>', 
					__( 'Leave your comment here', 'acadprof' )
				), 
				sprintf(
					'<label for="comment">%s%s</label>', 
					_x( 'Comment', 'noun' ), 
					$required_indicator
				)
			), 
			'fields'			=>	array(
				'author'	=>	sprintf(
					'<div class="comment-form-author form-floating mb-3">%s %s</div>', 
					sprintf(
						'<input type="text" class="form-control" id="author" name="author" value="%s" placeholder="%s"%s />', 
						esc_attr( $commenter['comment_author'] ), 
						__( 'Name', 'acadprof' ), 
						( $req ? $required_attribute : '' )
					), 
					sprintf(
						'<label for="author">%s%s</label>', 
						__( 'Your Name', 'acadprof' ), 
						( $req ? $required_indicator : '' )
					)
				),
				'email'	=>	sprintf(
					'<div class="row">
						<div class="col-md">
							<div class="comment-form-email form-floating mb-3">%s %s</div>
						</div>', 
					sprintf(
						'<input type="email" class="form-control" id="email" name="email" value="%s" placeholder="email@example.com"%s />', 
						esc_attr( $commenter['comment_author_email'] ), 
						( $req ? $required_attribute : '' )
					), 
					sprintf(
						'<label for="email">%s%s</label>', 
						__( 'Email address', 'acadprof' ), 
						( $req ? $required_indicator : '' )
					)
				), 
				'url'	=>	sprintf(
						'<div class="col-md">
							<div class="comment-form-url form-floating mb-3">%s %s</div>
						</div>
					</div>', 
					sprintf(
						'<input type="url" class="form-control" id="url" name="url" value="%s" placeholder="https://www.example.com" />', 
						esc_attr( $commenter['comment_author_url'] )
					), 
					sprintf(
						'<label for="url">%s</label>', 
						__( 'Website', 'acadprof' )
					)
				), 
			), 
			'class_submit'	=>	'btn btn-secondary'

		);

		// modifying cookies consent checkbox field
		if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) {
			$consent = empty( $commenter['comment_author_email'] ) ? '' : $checked_attribute;
	
			$args['fields']['cookies'] = sprintf(
				'<div class="comment-form-cookies-consent mb-3 form-check">%s %s</div>',
				sprintf(
					'<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="form-check-input" value="yes"%s />', 
					$consent
				),
				sprintf(
					'<label for="wp-comment-cookies-consent" class="form-check-label">%s</label>',
					__( 'Save my name, email, and website in this browser for the next time I comment.' )
				)
			);
		}

		return $args;
	}
} // End of if function_exists( 'acadprof_bootstrap_comment_form' ).


// Add note if comments are closed.
add_action( 'comment_form_comments_closed', 'acadprof_comment_form_comments_closed' );

if ( ! function_exists( 'acadprof_comment_form_comments_closed' ) ) {
	/**
	 * Displays a note that comments are closed if comments are closed and there are comments.
	 */
	function acadprof_comment_form_comments_closed() {
		if ( get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'acadprof' ); ?></p>
			<?php
		}
	}
} // End of if function_exists( 'acadprof_comment_form_comments_closed' ).