<?php
class Acadprof_Related_Posts extends Acadprof_Shortcode 
{
    /**
     * Name of shortcode
     * 
     */
    public $shortcode;
    
    /**
     * Constructor function
     * 
     * @param string $theme_name Name of theme
     */
    public function __construct( $shortcode, $theme_name ) {
        $this->shortcode = $shortcode;
        parent::__construct( $theme_name );
    }

    // get default attributes
    public function get_default_atts() {
        $this->default_atts = array(
            'title'					=>	esc_html__( 'Related Posts', $this->theme_name ),
            'tag'					=>	'',
            'num_of_posts'			=>	'6',
            'num_of_columns'		=>	'3',
            'show_thumbnail'		=>	true,
            'show_excerpt'			=>	true,
            'show_button'			=>	true,
        );
        return $this->default_atts;
    }

    /**
     * Method for self-closing shortcode output
     * 
     * @param array  $atts    Shortcode attributes. Default empty.
     * @return mixed shortcode output with related posts
     */
    public function shortcode_callback( $atts, $content = null ) {
        // get final valid atts 
        $atts = $this->get_valid_atts( $atts );

        // returning output
        ob_start();
        ?>
        <div class="row related-posts-section">
        <?php
            // for invalid or empty tag attribute value
            $term = term_exists( $atts['tag'], 'post_tag' );
            if ( 0 === $term || null === $term ) { ?>
                <div class="col-12 py-3">
                    <h3 class="h3">
                        <?php echo '[' . $this->shortcode . ' tag="term-slug"] '; ?>
                        <?php esc_attr_e( 'shortcode requires a valid post tag term as attribute value to display related posts', $this->theme_name ) ?>
                    </h3>
                </div>
            <?php 
            } else {

            // get args for wp_Query
            $args = $this->get_post_args( $atts );
            // init posts collection object
            $tagged_posts = new WP_Query( $args );

            if ( $tagged_posts->have_posts() ) : ?>
                
                <div class="col-12 py-3">
                    <?php 
                    echo sprintf( 
                        '<h1 class="related-title">%1$s</h1>', 
                        sprintf( __( '%s', $this->theme_name ), 
                            esc_html( $atts['title'] )
                        )
                    );
                    ?>
                </div>
            
            <?php
                while ( $tagged_posts->have_posts() ) : $tagged_posts->the_post();
                ?>

                <div id="tagged-<?php the_ID(); ?>" <?php post_class( $this->get_responsive_bs_class( $atts ) ); ?>>
                <?php
                // display image
                if ( has_post_thumbnail() && false != $atts['show_thumbnail'] ) { ?>
                    <div class="img-holder w-100">
                    <?php

                        $img_class = array( 'img-fitBox' );

                        the_post_thumbnail( 
                            'acadprof-img-medium', 
                            array(
                            'class' =>  esc_attr( implode( ' ', $img_class ) ),
                            'alt'   =>  the_title_attribute( 
                                array(
                                'echo'  =>  false
                                ) 
                            ),
                            ) 
                        ); 
                    ?> 
                    </div><!-- .img-holder -->
                <?php } ?>
                <?php
                    // get title
                    echo sprintf( 
                    '<h4><b><a href="%1$s">%2$s</a></b></h4>', 
                    esc_url( get_permalink() ), 
                    sprintf( __( '%s', $this->theme_name ), 
                        esc_html( get_the_title() )
                        )
                    );
                    
                    // show excerpt 
                    if ( false !== $atts['show_excerpt'] ) :
                        // get excerpt
                        $tagged_excerpt = get_the_excerpt(); ?>
                        <p><?php echo sprintf( __( '%s', $this->theme_name ), $tagged_excerpt ); ?></p>
                    <?php endif; ?>
                    
                    <?php if ( false !== $atts['show_button'] ) : ?>
                        <a class="btn-main-dark my-2" href="<?php the_permalink(); ?>"><?php _e( 'Learn More', $this->theme_name ); ?>
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div><!-- #tagged-<?php the_ID(); ?> -->
            <?php endwhile; wp_reset_postdata();

            endif;  //End tagged_posts
        }
        ?> 
        </div><!-- .row.related-posts-section -->
        <?php
        return ob_get_clean();
    }
    /**
     * ==For internal use==
     * Method to validate and get valid attributes
     * 
     * @param array  $atts    Shortcode attributes. Default empty.
     * @return array valid attributes
     */
    protected function get_valid_atts( $atts ) {
        // normalizing attribute keys to lower case
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        // overriding default attributes with user defined
        $atts = shortcode_atts( $this->get_default_atts(), $atts, $this->shortcode );

        // valid atts
        $valid = array();

        /**
         * validating attributes values
         */
        $valid['title'] = $atts['title'];

        // post tags
        $term = term_exists( $atts['tag'], 'post_tag' );
        if ( $term !== 0 && $term !== null ) {
            $valid['tag'] = $atts['tag'];
        } else {
            $valid['tag'] = '';
        }

        // number of posts
        $valid['num_of_posts'] = ! is_int( $atts['num_of_posts'] ) ? ( int ) $atts['num_of_posts'] : $atts['num_of_posts'];

        // number of columns
        $valid['num_of_columns'] = ! is_int( $atts['num_of_columns'] ) ? ( int ) $atts['num_of_columns'] : $atts['num_of_columns'];

        // validating booleans
        $valid['show_thumbnail'] = ! is_bool( $atts['show_thumbnail'] ) ? ( bool ) $atts['show_thumbnail'] : $atts['show_thumbnail'];
        $valid['show_excerpt'] = ! is_bool( $atts['show_excerpt'] ) ? ( bool ) $atts['show_excerpt'] : $valid['show_excerpt'];
        $valid['show_button'] = ! is_bool( $atts['show_button'] ) ? ( bool ) $atts['show_button'] : $atts['show_button'];

        // get valid atts
        return $valid;
    }

    /**
     * ==For internal use==
     * Method to retrieve the prepared post args
     * 
     * @param array $atts User defined attributes
     * @return array Outputs prepared args for post query
     */
    protected function get_post_args( $atts ) {
        // get current post
        $post = get_post( get_the_ID() );

        // get attributes array
        if ( ! is_array( $atts ) ) {
            $atts = ( array ) $atts;
        }
        
        // establish post args
        $args = array(
            'post_type' 		=> 'post',
            'posts_per_page'	=>	$atts['num_of_posts'],
            'tag'				=> 	$atts['tag'],
            'post__not_in'      =>  array( $post->ID ),
            'orderby'			=>	'date',
            'order'				=>	'ASC',
            'tax_query' => array( 
                array(
                    'taxonomy' 	=> 'post_format',
                    'field' 	=> 'slug',
                    'terms' => array('post-format-aside', 'post-format-gallery', 'post-format-link', 'post-format-image', 'post-format-quote', 'post-format-status', 'post-format-audio', 'post-format-chat', 'post-format-video'),
                    'operator' => 'NOT IN'
                ) 
            ),
        );

        // get post args
        return $args;
    }

    /**
     * ==Helper method==
     * Get responsive bootstrap class name to 
     * be used for each post box
     * 
     * @param array $atts User defined attributes
     * @return string Bootstrap class name for post blocks
     */
    public function get_responsive_bs_class( $atts ) {
        // get postfix num of column class
	    $postfix = 12 / $atts['num_of_columns'];

        // concating with bs grid class
        $classes = esc_attr( 'my-5 col-sm-6 col-md-' . $postfix );
        // give space for each class name
        $col_class = explode( ' ', $classes );

        return $col_class;
    }

} // End class
