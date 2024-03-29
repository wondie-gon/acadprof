<?php
/**
 * Sidebar - hero setup
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<?php if ( is_active_sidebar( 'hero' ) ) : ?>

	<!-- ******************* The Hero Widget Area ******************* -->

	<div id="carouselHeroControls" class="carousel slide" data-ride="carousel" data-bs-ride="carousel" data-interval="false" data-bs-interval="false">

		<div class="carousel-inner" role="listbox">

			<?php dynamic_sidebar( 'hero' ); ?>

		</div>

		<a class="carousel-control-prev" href="#carouselHeroControls" role="button" data-slide="prev" data-bs-slide="prev">

			<span class="carousel-control-prev-icon" aria-hidden="true"></span>

			<span class="screen-reader-text"><?php echo esc_html_x( 'Previous', 'carousel control', 'acadprof' ); ?></span>

		</a>

		<a class="carousel-control-next" href="#carouselHeroControls" role="button" data-slide="next" data-bs-slide="next">

			<span class="carousel-control-next-icon" aria-hidden="true"></span>

			<span class="screen-reader-text"><?php echo esc_html_x( 'Next', 'carousel control', 'acadprof' ); ?></span>

		</a>

	</div><!-- .carousel -->

	<script>
	jQuery( ".carousel-item" ).first().addClass( "active" );
	</script>

	<?php
endif;
