<?php
/**
 * custom control for post dropdown
 * 
 * @package Acadprof
 * @since 1.0.0
 */
// restrict direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return NULL;
}

/**
 * Class to create a custom post control
 */
if ( ! class_exists( 'Acadprof_Post_Dropdown_Custom_Control' ) ) {
    class Acadprof_Post_Dropdown_Custom_Control extends WP_Customize_Control {
        /**
         * The type of control being rendered
         */
        // public $type = 'dropdown_posts';

        private $posts = array();

        public function __construct($manager, $id, $args = array(), $options = array())
        {
            parent::__construct( $manager, $id, $args );

            // change default query args
            $this->input_attrs = wp_parse_args( $options, array( 'numberposts' => '-1' ) );

            // get posts
            $this->posts = get_posts( $this->input_attrs );
        }

        /**
        * Render the content on the theme customizer page
        */
        public function render_content()
        {
        ?>
            <div class="dropdown_posts_control">
                <?php if( !empty( $this->label ) ) { ?>
                    <label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
                        <?php echo esc_html( $this->label ); ?>
                    </label>
                <?php } ?>
                <?php if( !empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php } ?>
                <select name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" class="custom-select-control" <?php $this->link(); ?>>
                    <?php
                        if( !empty( $this->posts ) ) {
                            foreach ( $this->posts as $post ) {
                                printf( '<option value="%s" %s>%s</option>',
                                    $post->ID,
                                    selected( $this->value(), $post->ID, false ),
                                    $post->post_title
                                );
                            }
                        }
                    ?>
                </select>
            </div>
        <?php
        }
    }
}