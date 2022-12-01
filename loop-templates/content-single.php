<?php
/**
 * Single post partial template
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class( 'single-article-block' ); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php 
		if ( get_theme_mod( 'enable_acadprof_social_media_sharing' ) ) {
			/**
			* get social media share links
			*
			* Hook - acadprof_social_media_share
			* @hooked acadprof_post_social_media_share_links - 10
			*/
			do_action( 'acadprof_social_media_share' );
		}
		?>

		<div class="entry-meta py-3">

			<?php acadprof_posted_on(); ?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php echo acadprof_get_the_post_thumbnail( $post->ID, 'full', array( 'w-100 d-block' ) ); ?>

	<div class="entry-content pt-3">

		<?php
		the_content();
		acadprof_link_pages();

		// display custom post metas
		acadprof_external_link_and_keywords_custom_post_meta();
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer py-3">

		<?php acadprof_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
