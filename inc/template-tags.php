<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acadprof_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function acadprof_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s"> (%4$s) </time>';
		}
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			sprintf(
				'<em class="small">%s</em> %s',
				esc_html__( 'Updated on', 'acadprof' ), 
				esc_html( get_the_modified_date() )
			)
		);
		$posted_on   = apply_filters(
			'acadprof_posted_on',
			sprintf(
				'<span class="posted-on"><em class="small">%1$s</em> <a href="%2$s" rel="bookmark">%3$s</a></span>',
				esc_html_x( 'Posted on', 'post date', 'acadprof' ),
				esc_url( get_permalink() ),
				apply_filters( 'acadprof_posted_on_time', $time_string )
			)
		);
		$byline      = apply_filters(
			'acadprof_posted_by',
			sprintf(
				'<span class="byline"><em class="small"> %1$s</em><span class="author vcard"> <a class="url fn n" href="%2$s">%3$s</a></span></span>',
				$posted_on ? esc_html_x( 'by', 'post author', 'acadprof' ) : esc_html_x( 'Posted by', 'post author', 'acadprof' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_html( get_the_author() )
			)
		);
		echo $posted_on . $byline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'acadprof_category_tag_links' ) ) {
	/**
	 * Prints HTML with meta information for the categories and tags.
	 */
	function acadprof_category_tag_links() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( ' ' );
			if ( $categories_list && acadprof_categorized_blog() ) {
				/* translators: %s: Categories of current post */
				printf( 
					'<p class="small"><em>' . esc_html__( 'in ', 'acadprof' ) . '</em><span class="cat-links wonui-badge-cats">' . esc_html__( '%s', 'acadprof' ) . '</span></p>', 
					$categories_list

				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', ' | ' );
			if ( $tags_list ) {
				/* translators: %s: Tags of current post */
				printf( 
					'<p class="small"><em>' . esc_html__( 'tagged ', 'acadprof' ) . '</em><span class="tags-links wonui-badge-tags">' . esc_html__( '%s', 'acadprof' ) . '</span></p>', 
					$tags_list

				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
}

if ( ! function_exists( 'acadprof_entry_footer' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function acadprof_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( ' ' );
			if ( $categories_list && acadprof_categorized_blog() ) {
				/* translators: %s: Categories of current post */
				printf( 
					'<p class="small"><em>' . esc_html__( 'in ', 'acadprof' ) . '</em><span class="cat-links wonui-badge-cats">' . esc_html__( '%s', 'acadprof' ) . '</span></p>', 
					$categories_list

				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', ' | ' );
			if ( $tags_list ) {
				/* translators: %s: Tags of current post */
				printf( 
					'<p class="small"><em>' . esc_html__( 'tagged ', 'acadprof' ) . '</em><span class="tags-links wonui-badge-tags">' . esc_html__( '%s', 'acadprof' ) . '</span></p>', 
					$tags_list

				); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		if ( is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link wonui-badge-comments rounded-badges">';
			comments_popup_link( esc_html__( 'Leave a comment', 'acadprof' ), esc_html__( '1 Comment', 'acadprof' ), esc_html__( '% Comments', 'acadprof' ) );
			echo '</span>';
		}
		acadprof_edit_post_link();
	}
}

if ( ! function_exists( 'acadprof_categorized_blog' ) ) {
	/**
	 * Returns true if a blog has more than 1 category.
	 *
	 * @return bool
	 */
	function acadprof_categorized_blog() {
		$all_the_cool_cats = get_transient( 'acadprof_categories' );
		if ( false === $all_the_cool_cats ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories(
				array(
					'fields'     => 'ids', 
					'hide_empty' => 1, 
					'number'     => 2, 
				)
			);
			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );
			set_transient( 'acadprof_categories', $all_the_cool_cats );
		}
		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so acadprof_categorized_blog should return true.
			return true;
		}
		// This blog has only 1 category so acadprof_categorized_blog should return false.
		return false;
	}
}

add_action( 'edit_category', 'acadprof_category_transient_flusher' );
add_action( 'save_post', 'acadprof_category_transient_flusher' );

if ( ! function_exists( 'acadprof_category_transient_flusher' ) ) {
	/**
	 * Flush out the transients used in acadprof_categorized_blog.
	 */
	function acadprof_category_transient_flusher() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		delete_transient( 'acadprof_categories' );
	}
}

if ( ! function_exists( 'acadprof_body_attributes' ) ) {
	/**
	 * Displays the attributes for the body element.
	 */
	function acadprof_body_attributes() {
		/**
		 * Filters the body attributes.
		 *
		 * @param array $atts An associative array of attributes.
		 */
		$atts = array_unique( apply_filters( 'acadprof_body_attributes', $atts = array() ) );
		if ( ! is_array( $atts ) || empty( $atts ) ) {
			return;
		}
		$attributes = '';
		foreach ( $atts as $name => $value ) {
			if ( $value ) {
				$attributes .= sanitize_key( $name ) . '="' . esc_attr( $value ) . '" ';
			} else {
				$attributes .= sanitize_key( $name ) . ' ';
			}
		}
		echo trim( $attributes ); // phpcs:ignore WordPress.Security.EscapeOutput
	}
}

if ( ! function_exists( 'acadprof_comment_navigation' ) ) {
	/**
	 * Displays the comment navigation.
	 *
	 * @param string $nav_id The ID of the comment navigation.
	 */
	function acadprof_comment_navigation( $nav_id ) {
		if ( get_comment_pages_count() <= 1 ) {
			// Return early if there are no comments to navigate through.
			return;
		}
		?>
		<nav class="comment-navigation" id="<?php echo esc_attr( $nav_id ); ?>">

			<h1 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'acadprof' ); ?></h1>

			<?php if ( get_previous_comments_link() ) { ?>
				<div class="nav-previous">
					<?php previous_comments_link( __( '&larr; Older Comments', 'acadprof' ) ); ?>
				</div>
			<?php } ?>

			<?php if ( get_next_comments_link() ) { ?>
				<div class="nav-next">
					<?php next_comments_link( __( 'Newer Comments &rarr;', 'acadprof' ) ); ?>
				</div>
			<?php } ?>

		</nav><!-- #<?php echo esc_attr( $nav_id ); ?> -->
		<?php
	}
}

if ( ! function_exists( 'acadprof_edit_post_link' ) ) {
	/**
	 * Displays the edit post link for post.
	 */
	function acadprof_edit_post_link() {
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'acadprof' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}

if ( ! function_exists( 'acadprof_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function acadprof_post_nav() {
		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );
		if ( ! $next && ! $previous ) {
			return;
		}
		?>
		<nav class="container navigation post-navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'acadprof' ); ?></h2>
			<div class="nav-links">
				<?php
				if ( get_previous_post_link() ) {
					previous_post_link( '<span class="nav-previous">%link</span>', _x( '<i class="fa fa-angle-left"></i>&nbsp;%title', 'Previous post link', 'acadprof' ) );
				}
				if ( get_next_post_link() ) {
					next_post_link( '<span class="nav-next">%link</span>', _x( '%title&nbsp;<i class="fa fa-angle-right"></i>', 'Next post link', 'acadprof' ) );
				}
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
}

if ( ! function_exists( 'acadprof_link_pages' ) ) {
	/**
	 * Displays/retrieves page links for paginated posts (i.e. including the
	 * `<!--nextpage-->` Quicktag one or more times). This tag must be
	 * within The Loop. Default: echo.
	 *
	 * @return void|string Formatted output in HTML.
	 */
	function acadprof_link_pages() {
		$args = apply_filters(
			'acadprof_link_pages_args',
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'acadprof' ),
				'after'  => '</div>',
			)
		);
		wp_link_pages( $args );
	}
}

/* 
* Function to display custom post meta values of 
* external link and keywords for posts
*/

if ( ! function_exists( 'acadprof_external_link_and_keywords_custom_post_meta' ) ) {
	/**
	 * Prints HTML with meta information for custom post meta of external link and keywords
	 */
	function acadprof_external_link_and_keywords_custom_post_meta() {
		// get custom post meta values
		$acadprof_meta_external_link = esc_url( get_post_meta( get_the_ID(), '_acadprof_external_link_key', true ) );
		$acadprof_meta_keywords = esc_html( get_post_meta( get_the_ID(), '_acadprof_keywords_key', true ) );

		if ( ! empty( $acadprof_meta_keywords ) ) {
			?>
			<span class="d-block keywords-list">
              	<b><?php _e( 'Keywords: ', 'acadprof' ); ?></b>
              	<?php 
              	/* translators: %s: keywords custom post meta of current post */
              	echo sprintf( __( '%s', 'acadprof' ), $acadprof_meta_keywords ); 
              	?>
            </span>
			<?php
		}

		if ( ! empty( $acadprof_meta_external_link ) ) {
			?>
			<span class="d-block py-2">
				<a href="<?php echo $acadprof_meta_external_link; ?>" target="_blank" class="btn btn-wonui">
              	<?php _e( 'Read Full', 'acadprof' ); ?>
 				</a>
            </span>
			<?php
		}
	}
}

/**
 * Custom template tag function to display post thumbnail as link
 * @param int $post_id id of post you want to display thumbnail
 * @param string $size Requested image size. Can be any registered image size name, or 
 * 						an array of width and height values in pixels (in that order).
 * @param array/string $class Class list for img tag in array or string separated by space
 */
if ( ! function_exists( 'acadprof_the_post_thumbnail_link' ) ) {
	function acadprof_the_post_thumbnail_link( $post_id, $size = 'large', $class = 'img-fluid d-block' ) {
		echo '<a href="' . get_the_permalink() . '" aria-hidden="true" tabindex="-1">' . acadprof_get_the_post_thumbnail( $post_id, $size, $class ) . '</a>';
	}
}

/**
 * Custom template tag function to return post thumbnail
 * 
 * @param int $post_id id of post you want to display thumbnail
 * @param string $size Requested image size. Can be any registered image size name, or 
 * 						an array of width and height values in pixels (in that order).
 * @param array/string $class Class list for img tag in array or string separated by space
 * @return mixed /post thumbnail
 */
if ( ! function_exists( 'acadprof_get_the_post_thumbnail' ) ) {
	function acadprof_get_the_post_thumbnail( $post_id, $size = 'large', $class = 'img-fluid d-block' ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}
		// prepare $classes as array
		$class = ( ! is_array( $class ) && is_string( $class ) ) ? (array) $class : $class;
		// prepare $attr param to include class
		$attr = array(
			'class' => esc_attr( implode( ' ', $class ) ), 
			'alt'   =>  the_title_attribute( 
				array(
					'echo'  =>  false
				) 
			)
		);
		// get thumbnail of post
		$acadprof_img =  get_the_post_thumbnail( $post_id, $size, $attr );

		return apply_filters( 'acadprof_thumbnail_filter', $acadprof_img, $post_id, $size, $class );
	}
}

/**
* Function for getting url of attachement, including featured image with img tag
*
* @param $num Number of attachments needed
*/

function acadprof_get_attachment_url( $num = 1 ) {

	$output = '';

    if ( has_post_thumbnail() && $num == 1 ) :

        $output = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
        
    else:
        $acadprof_attachments = get_posts( array(
            'post_type'       	=>    'attachment',
            'numberposts'		=>    $num,
            'post_parent'     	=>    get_the_ID(),   
        ) );

        // if attachments exist
        if ( $acadprof_attachments && $num == 1 ) {
        	foreach ( $acadprof_attachments as $attachment ) {
        		$output = wp_get_attachment_url( $attachment->ID );
        	}
        } elseif ( $acadprof_attachments && ( $num > 1 || $num == -1 ) ) {
        	
        	$output = $acadprof_attachments;
        }

        wp_reset_postdata();

    endif;

    return $output;
}

/* 
* Function to display embedded video
*/
if ( ! function_exists( 'acadprof_get_embedded_video' ) ) {
	function acadprof_get_embedded_video( $post_id ) {

		if ( ! function_exists( 'get_media_embedded_in_content' ) ) { 
		    require_once ABSPATH . WPINC . '/media.php'; 
		}

		$post = get_post( $post_id );
		$content = do_shortcode( apply_filters( 'the_content', $post->post_content ) );
     	$embedded_vids = get_media_embedded_in_content( $content );

     	// check if there is embedded vid
     	if ( ! empty( $embedded_vids ) ) {
     		foreach ( $embedded_vids as $embedded_vid ) {
     			if ( strpos( $embedded_vid, 'video' ) || strpos( $embedded_vid, 'youtube' ) || strpos( $embedded_vid, 'vimeo' ) ) {
     				return $embedded_vid;
     			}
     		}
     	} else {
     		return;
     	}

	}
}

/* 
* Function to display embedded audio
*/
if ( ! function_exists( 'acadprof_get_embedded_audio' ) ) {
	function acadprof_get_embedded_audio( $post_id ) {

		if ( ! function_exists( 'get_media_embedded_in_content' ) ) { 
		    require_once ABSPATH . WPINC . '/media.php'; 
		}

		$output = '';

		$post = get_post( $post_id );
		$content = do_shortcode( apply_filters( 'the_content', $post->post_content ) );
     	$audios = get_media_embedded_in_content( $content, array( 'audio', 'iframe' ) );

     	// check if there is embedded audio
     	if ( ! empty( $audios ) ) {
     		$output = str_replace( '?visual=true', '?visual=false', $audios[0] );
     	}

     	return $output;

	}
}
