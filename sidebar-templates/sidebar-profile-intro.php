<?php
/**
 * Profile intro sidebar setup
 * Uses custom widget from class Acadprof_Profile_Intro_Widget
 * to display profile introduction or resume summary of a person 
 * on home and about pages
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'acadprof_container_type' );
?>

<?php if ( is_active_sidebar( 'acadprof_profile_intro' ) ) : ?>

	<!-- ******************* The Profile Intro Widget Area ******************* -->

	<div class="<?php echo esc_attr( $container ); ?> intro-container" id="wrapper-profile-content" tabindex="-1">

		<?php dynamic_sidebar( 'acadprof_profile_intro' ); ?>

	</div>

	<?php
endif;
