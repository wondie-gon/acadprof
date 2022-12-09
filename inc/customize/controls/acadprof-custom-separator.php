<?php
/**
 * Customizer controls separator
 * @package Acadprof
 * @since 1.0.0
 */
// restrict direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_Separator_Custom_Control' ) ) {

	class Acadprof_Separator_Custom_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 */
		public $type = 'ras-dashen-separator';
		/**
		 * Control method
		 *
		 */
		public function render_content() {
			?>
			<p><hr style="border-color: #0091c8; opacity: 0.2;"></p>
			<?php
		}
	}
	
}