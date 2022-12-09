<?php 
/**
 * Class of shortcode to display audio posts collection list 
 * 
 * @since 1.0.1
 */
class Acadprof_Audios_Collection extends Acadprof_Shortcode 
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
    public function __construct( $shortcode, $theme_name )
    {
        $this->shortcode = $shortcode;
        parent::__construct( $theme_name );
    }

    // get default attributes
    public function get_default_atts() {
        $this->default_atts = array(
        'title'					=>	esc_html__( 'Our Audios Collection', $this->theme_name ),
		'tag'					=>	'',
		'category'				=>	'',
		'items_per_page'		=>	'-1',
		'num_of_columns'		=>	'4',
        );
        return $this->default_atts;
    }

    /**
     * Method for self-closing audios shortcode output
     * 
     * @param array  $atts    Shortcode attributes. Default empty.
     * @return mixed shortcode output for audios collection
     */
    public function shortcode_callback( $atts, $content = null ) {
        // get final valid atts 
        $atts = $this->get_valid_atts( $atts );

        // get args for wp_Query
        $args = $this->get_post_args( $atts );

        // returning output
        ob_start();
        ?>
        <div class="container-fluid">
            <section class="all-audio-taglist"></section><!-- .all-audio-taglist -->
            <hr class="my-3">

            <section class="audio-collection-section mb-5">
                <?php 
                echo sprintf( 
                    '<h1 class="audio-section-title">%1$s</h1>', 
                    sprintf( __( '%s', $this->theme_name ), 
                        esc_html( $atts['title'] )
                    )
                );
                ?>
                <?php
                // quering posts
                $audio_posts = new WP_Query( $args );
                
                if ( $audio_posts->have_posts() ) { ?>
                <div class="row row-cards">
                <?php
                    while ( $audio_posts->have_posts() ) : $audio_posts->the_post();
                    ?>

                    <div id="audio-<?php the_ID(); ?>" <?php post_class( $this->get_responsive_bs_class( $atts ) ); ?>>

                        <div class="card card-sm card-audio">
                            <a class="media-holder audio-link" href="<?php the_permalink(); ?>">
                            <?php echo acadprof_get_embedded_audio( get_the_ID() ); ?>
                            </a>
                            <div class="card-body">
                                <?php
                                // get title
                                echo sprintf( 
                                '<h5><a href="%1$s">%2$s</a></h5>', 
                                esc_url( get_permalink() ), 
                                sprintf( __( '%s', $this->theme_name ), 
                                    esc_html( get_the_title() )
                                    )
                                );
                            
                                ?>
                                <div class="text-muted">
                                    <?php echo acadprof_posted_on_stringified(); ?>
                                </div>
                            </div>
                        </div>
                        

                    </div><!-- #audio-<?php the_ID(); ?> -->
                    <?php 
                    endwhile; wp_reset_postdata(); 
                    ?>
                </div><!-- .row.row-cards -->
                <?php
                }
                ?>
	    	</section><!-- .audio-collection-section -->
        </div><!-- .container-fluid -->
        <?php
        return ob_get_clean();
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
        $tag_term = term_exists( $atts['tag'], 'post_tag' );
        if ( $tag_term !== 0 && $tag_term !== null ) {
            $valid['tag'] = $atts['tag'];
        } else {
            $valid['tag'] = '';
        }

        // category term
        $cat_term = term_exists( $atts['category'], 'category' );
        if ( $cat_term !== 0 && $cat_term !== null ) {
            $valid['category'] = $atts['category'];
        } else {
            $valid['category'] = '';
        }

        // number of audios
        $valid['items_per_page'] = ! is_int( $atts['items_per_page'] ) ? ( int ) $atts['items_per_page'] : $atts['items_per_page'];

        // number of columns
        $valid['num_of_columns'] = ! is_int( $atts['num_of_columns'] ) ? ( int ) $atts['num_of_columns'] : $atts['num_of_columns'];

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
            'post_type'         =>  'post',
            'posts_per_page'    =>  $atts['items_per_page'],
            'post_status'       =>  'publish',
            'orderby'           =>  'date',
            'order'           	=>  'DESC',
        );

        $formatquery = array(
        		array(
        			'taxonomy' 	=> 'post_format',
		            'field' 	=> 'slug',
		            'terms' => array( 'post-format-audio' )
        		)
           	);

        // $args['tax_query'] = $formatquery;
        if ( ! empty( $atts['tag'] ) ) {
        	$tagquery = array(
        		'relation'	=>	'AND',
        		array(
                    'taxonomy'  =>  'post_tag',
                    'field'     =>  'slug',
                    'terms'     =>  $atts['tag']
                )
        	);

        	$tag_audio_query = array_merge( $tagquery, $formatquery );
        }


        if ( ! empty( $atts['category'] ) ) {
        	$catquery = array(
        		'relation'	=>	'AND',
        		array(
                    'taxonomy'  =>  'category',
                    'field'     =>  'slug',
                    'terms'     =>  $atts['category']
                )
        	);

        	$cat_audio_query = array_merge( $catquery, $formatquery );
        }

        if ( empty( $atts['tag'] ) && empty( $atts['category'] ) ) {
        	$args['tax_query'] = $formatquery;
        } else {

	        if ( ! empty( $atts['tag'] ) && ! empty( $atts['category'] ) ) {
	        	$relation = array( 'relation'	=>	'AND' );

	        	$merged = array_merge( array( $tag_audio_query ), array( $cat_audio_query ) );

	        	$args['tax_query'] = array_merge( $relation, $merged );
	        } elseif ( empty( $atts['tag'] ) && ! empty( $atts['category'] ) ) {
	        	$args['tax_query'] = $cat_audio_query;
	        } elseif ( ! empty( $atts['tag'] ) && empty( $atts['category'] ) ) {
	        	$args['tax_query'] = $tag_audio_query;
	        }

        }

        // get post args
        return $args;
    }

} // End class