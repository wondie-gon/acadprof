<?php
/**
 * Profile Intro setup
 * Uses custom widget from class Acadprof_Profile_Intro_Widget
 * to display profile introduction or resume summary of a person 
 * on home and about pages
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( is_active_sidebar( 'acadprof_profile_intro' ) ) :
	?>

	<div class="intro-section w-100 py-5" id="wrapper-profile-intro">

		<?php
		get_template_part( 'sidebar-templates/sidebar', 'profile-intro' );
		?>

	</div>

	<?php
endif;
