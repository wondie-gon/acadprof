<?php
/**
 * Class to add all shortcodes
 * 
 * @package Acadprof
 * @since 1.0.0
 * 
 */
// restrict direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class Acadprof_Shortcodes {
    /**
     * theme's text domain
     */
    public $theme_name;

    /**
     * Constuctor function
     */
    public function __construct( $theme_name ) {
        $this->theme_name = $theme_name;

        // load shortcode classes
        $this->load_shortcode_classes();
    }

    // initialize all shortcodes
    public function init() {
       // hook each shortcode to init action
       add_action( 'init', array( $this, 'addShortcodes' ) );
    }

    /**
     * Loads all shortcodes
     */
    public function load_shortcode_classes() {
        // load abstract 'Acadprof_Shortcode'
        require get_template_directory() . '/inc/shortcodes/abstract-acadprof-shortcode.php';

        // load class for related posts shortcodes
        require get_template_directory() . '/inc/shortcodes/class-acadprof-related-posts.php';

        // load class for more posts block posts shortcodes
        require get_template_directory() . '/inc/shortcodes/class-acadprof-more-posts-block.php';

        // load class 'Acadprof_Audios_Collection' shortcodes
        require get_template_directory() . '/inc/shortcodes/class-acadprof-audios-collection.php';

        // load class 'Acadprof_Videos_Collection' shortcodes
        require get_template_directory() . '/inc/shortcodes/class-acadprof-videos-collection.php';
    }

    /**
     * Adds shortcodes all in one
     */
    public function addShortcodes() {
        // instantiate 'Acadprof_Related_Posts' shortcode class
        $related_posts = new Acadprof_Related_Posts( 'acadprof_related_posts', $this->theme_name );
        // add shortcode
        $related_posts->add_shortcode();

        // instantiate 'Acadprof_More_Posts_Block' shortcode class
        $related_posts = new Acadprof_More_Posts_Block( 'acadprof-more-posts', $this->theme_name );
        // add shortcode
        $related_posts->add_shortcode();

        // instantiate 'Acadprof_Audios_Collection' shortcode class
        $audios_collection = new Acadprof_Audios_Collection( 'acadprof_audios_collection', $this->theme_name );
        // add shortcode
        $audios_collection->add_shortcode();

        // instantiate 'Acadprof_Videos_Collection' shortcode class
        $videos_collection = new Acadprof_Videos_Collection( 'acadprof_videos_collection', $this->theme_name );
        // add shortcode
        $videos_collection->add_shortcode();
    }

} // End class
