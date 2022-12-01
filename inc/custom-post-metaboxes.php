<?php
/**
 * Custom post metaboxes
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

//Add default metaboxes to posts

// hook meta box action
add_action( 'add_meta_boxes', 'acadprof_posts_custom_meta_box' );

// adding meta box for posts
if ( ! function_exists(	'acadprof_posts_custom_meta_box' ) ) {
	
	function acadprof_posts_custom_meta_box() {

		// add meta box
		add_meta_box( 'acadprof_custom_post_meta', __( 'Post meta', 'acadprof' ), 'acadprof_custom_post_meta_box_callback', 'post', 'normal' ); 

	}

}

// post details meta box callback
if ( ! function_exists(	'acadprof_custom_post_meta_box_callback' ) ) :
	
	function acadprof_custom_post_meta_box_callback( $post ) {

		wp_nonce_field( 'ap_posts_save_custom_meta', 'acadprof_custom_post_meta_nonce' );

		$acadprof_ext_link = esc_url( get_post_meta( $post->ID, '_acadprof_external_link_key', true ) );

		// keywords
		$acadprof_keywords = esc_html( get_post_meta( $post->ID, '_acadprof_keywords_key', true ) );
		?>

		<table class="cpt-meta-table">
			<tbody>

				<tr>
					<td class="label-td">
						<label for="acadprof_ext_link"><?php _e( 'External Link: ', 'acadprof' ); ?></label>
					</td>
					<td class="input-td" colspan="3">
						<input type="url" id="acadprof_ext_link" class="all-options form-control" name="acadprof_ext_link" value="<?php echo esc_url( $acadprof_ext_link ); ?>" placeholder="<?php _e( 'Enter link', 'acadprof' ); ?>" size="25" />
					</td>
				</tr>
				<tr>
					<td>
						<span class="description"><?php _e( 'Enter external link, for example, for abstract post of a publication.', 'acadprof' ); ?></span>
					</td>
				</tr>

				<tr>
					<td class="label-td">
						<label for="acadprof_keywords"><?php _e( 'Keywords: ', 'acadprof' ); ?></label>
					</td>
					<td class="input-td" colspan="3">
						<input type="url" id="acadprof_keywords" class="all-options form-control" name="acadprof_keywords" value="<?php echo esc_attr( $acadprof_keywords ); ?>" placeholder="<?php _e( 'Enter keywords', 'acadprof' ); ?>" size="25" />
					</td>
				</tr>
				<tr>
					<td>
						<span class="description"><?php _e( 'Enter keywords separated by comma, for example, for abstract post of a publication.', 'acadprof' ); ?></span>
					</td>
				</tr>

			</tbody>
		</table>

		<?php


	}

endif;

// hook function to saving action
add_action( 'save_post', 'ap_posts_save_custom_meta' );

// save action of post general details meta box value
if ( ! function_exists( 'ap_posts_save_custom_meta' ) ) :
	
	function ap_posts_save_custom_meta( $post_id ) {

		global $post;

		// get the post type
		// $post_type = get_post_type_object( $post->post_type );

		// verify if this is an autosave routine
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// check if nonce not set or verify if this came from the screen and with proper authorization
		if ( ! isset( $_POST['acadprof_custom_post_meta_nonce'] ) || ! wp_verify_nonce( $_POST['acadprof_custom_post_meta_nonce'], 'ap_posts_save_custom_meta' ) ) {
			return;
		}

		// if user has no permission to edit post
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$metadata['_acadprof_keywords_key'] = ( isset( $_POST['acadprof_keywords'] ) ? sanitize_text_field( $_POST['acadprof_keywords'] ) : '' );

		$metadata['_acadprof_external_link_key'] = ( isset( $_POST['acadprof_ext_link'] ) ? esc_url_raw( $_POST['acadprof_ext_link'] ) : '' );


		// update records from the input fields
		foreach ( $metadata as $key => $value ) {
			
			// get current value from each input
			$current_value = get_post_meta( $post_id, $key, true );

			if ( $value && '' == $current_value ) {

				add_post_meta( $post_id, $key, $value, true );

			} elseif ( $value && $value != $current_value ) {

				update_post_meta( $post_id, $key, $value );

			} elseif ( '' == $value && $current_value ) {

				delete_post_meta( $post_id, $key, $current_value );

			}
		}


	}

endif;

// interaction messages for post custom post type
if ( ! function_exists( 'acadprof_posts_updated_messages' ) ) :
	
	function acadprof_posts_updated_messages( $messages ) {

		global $post, $post_ID;

		$messages['post'] = array(
			0	=>	'',
			1	=>	sprintf( __( 'Post updated. <a href="%s">View post</a>', 'acadprof' ), esc_url( get_permalink( $post_ID ) ) ),
			2	=>	__( 'Custom field updated', 'acadprof' ),
			3	=>	__( 'Custom field deleted', 'acadprof' ),
			4	=>	__( 'Post updated', 'acadprof' ),
			5	=>	isset( $_GET['revision'] ) ? sprintf( __( 'Post restored to revision from %s', 'acadprof' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
			6	=>	sprintf( __( 'Post published. <a href="%s">View Post</a>', 'acadprof' ), esc_url( get_permalink( $post_ID ) ) ),
			7	=>	__( 'Post saved', 'acadprof' ),
			8	=>	sprintf( __( 'Post submitted. <a target="_blank" href="%s">Preview post</a>', 'acadprof' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9	=>	sprintf( __( 'Post scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview post</a>', 'acadprof' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
			10	=>	sprintf( __( 'Post draft updated. <a target="_blank" href="%s">Preview post</a>', 'acadprof' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
			);

		return $messages;

	}

endif;