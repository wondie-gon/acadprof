<?php 
class Acadprof_Shortcodes
{
    public $theme_name;

    public function __construct( $theme_name )
    {
        $this->theme_name = $theme_name;

        // load shortcode classes
        $this->load_shortcode_classes();
    }

    // initialize all shortcodes
    public function init() {
       // hook each shortcode to init action
       add_action( 'init', array( $this, 'addShortcodes' ) );
    }

    public function load_shortcode_classes() {
        // load abstract 'Acadprof_Shortcode'
        require get_template_directory() . '/inc/shortcodes/abstract-acadprof-shortcode.php';

        // load class for related posts shortcodes
        require get_template_directory() . '/inc/shortcodes/class-acadprof-related-posts.php';

        // load class 'RD_Audios_Collection' shortcodes
        require get_template_directory() . '/inc/shortcodes/class-acadprof-audios-collection.php';

        // load class 'RD_Videos_Collection' shortcodes
        require get_template_directory() . '/inc/shortcodes/class-acadprof-videos-collection.php';
    }

    // method to add shortcodes
    public function addShortcodes() {
        // instantiate 'RD_Related_Posts' shortcode class
        $related_posts = new Acadprof_Related_Posts( 'acadprof_related_posts', $this->theme_name );
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
