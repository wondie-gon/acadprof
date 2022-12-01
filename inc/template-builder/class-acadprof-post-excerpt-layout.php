<?php
/**
 * Template class for post excerpt layouts
 * @package Acadprof
 * @since 1.0.0
 * 
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Acadprof_Post_Excerpt_Layout' ) ) {
    class Acadprof_Post_Excerpt_Layout {

        /**
         * Constructor
         */
        public function __construct() {}
        /**
         * Template function to display post excerpt inside post template files
         * 
         * @param string $layout_mod Customizer selected value of excerpt/image layout
         * @param array $block_args Array of argument to set mainly different blocks 
         * in the template
         */
        public static function show_post_block( $layout_mod, $block_args = array() ) {
            // get args
            $block_args = self::config_args( $block_args );
            // printing post block template
            self::post_block_start( $block_args );
            self::main_content_inner( $layout_mod, $block_args );
            self::post_block_end( $block_args );
        }
        /**
         * Allows to display main content part of post block including 
         * post excerpt text and thumbnail inside a wrapper block
         * based on selected layout from theme 
         * customize
         * 
         * @param string $layout_mod Customizer selected value of excerpt/image layout
         * @param array $block_args Array of argument to set mainly different blocks 
         * in the template
         */
        public static function main_content_inner( $layout_mod, $block_args = array() ) {
            // get args
            $block_args = self::config_args( $block_args );
            // determine selected layout
            switch ( $layout_mod ) {
                case 'image-top-left':
                    self::main_content_image_top_left( 'image-top-left', $block_args );
                    break;
                case 'image-top':
                    self::main_content_image_top( 'image-top', $block_args );
                    break;
                case 'image-right':
                    self::main_content_image_right( 'image-right', $block_args );
                    break;
                
                default:
                    self::main_content_image_left( 'image-left', $block_args );
                    break;
            }
        }
        /**
         * Internal method to configure arguments by merging passed args
         * with defaults
         * @param array $args Array of args to be used for setting block 
         * classes
         * @return array merged array for use in various methods of this class
         */
        private static function config_args( $args = array() ) {
            $defaults = array(
                'wrapper_class'     =>  '', 
                'has_inner_wrapper'     =>  true, 
                'inner_wrapper_class'     =>  'row', 
                'header_class'      =>  '', 
                'excerpt_class'     =>  '', 
                'footer_class'      =>  '', 
                'cat_block_class'   =>  '', 
                'img_block_class'   =>  '', 
                'img_size'          =>  'large', 
                'img_class'         =>  'img-fluid d-block'
            );
            $args = wp_parse_args( $args, $defaults );
            return $args;
        }
        /**
         * For internal use
         * Template for layout with image/thumbnail on top left, category and tag block 
         * on top right, and excerpt text below image
         * 
         * @param string $layout_mod Customizer selected value of excerpt/image layout
         * @param array $block_args Array of argument to set classes for image wrapping block,  
         * text area blocks, and image size and image class etc
         */
        private static function main_content_image_top_left( $layout_mod, $block_args = array() ) {
            // check for thumbnails
            if ( has_post_thumbnail() ) {
                self::thumbnail_block( $layout_mod, $block_args['img_block_class'], $block_args['img_size'], $block_args['img_class'] );
                self::category_tags_block( $layout_mod, 'py-2' );
            } else {
                self::category_tags_block( $layout_mod, 'py-2' );
            }

            self::main_content_texts( $layout_mod, $block_args );
        }
        /**
         * For internal use
         * Template for layout with image/thumbnail on top and everything else below image
         * 
         * @param string $layout_mod Customizer selected value of excerpt/image layout
         * @param array $block_args Array of argument to set classes for image wrapping block,  
         * text area blocks, and image size and image class etc
         */
        private static function main_content_image_top( $layout_mod, $block_args = array() ) {
            // check for thumbnails
            if ( has_post_thumbnail() ) {
                // self::thumbnail_block( $layout_mod, '', 'full', array( 'img-fluid d-block' ) );
                self::thumbnail_block( $layout_mod, $block_args['img_block_class'], $block_args['img_size'], $block_args['img_class'] );
            }
            self::main_content_texts( $layout_mod, $block_args );
        }
        /**
         * For internal use
         * Template for layout with image/thumbnail on left side and everything else 
         * on the right
         * 
         * @param string $layout_mod Customizer selected value of excerpt/image layout
         * @param array $block_args Array of argument to set classes for image wrapping block,  
         * text area blocks, and image size and image class etc
         */
        private static function main_content_image_left( $layout_mod, $block_args = array() ) {
            // check for thumbnails
            if ( has_post_thumbnail() ) {
                self::thumbnail_block( $layout_mod, $block_args['img_block_class'], $block_args['img_size'], $block_args['img_class'] );
            ?>
                <div class="col-md-8">
                    <div class="row">
                    <?php
                        self::main_content_texts( $layout_mod, $block_args );
                    ?>
                    </div>
                </div>
            <?php
            } else {
                self::main_content_texts( $layout_mod, $block_args );
            }
        }
        /**
         * For internal use
         * Template for layout with image/thumbnail on right side and everything else 
         * on the left
         * 
         * @param string $layout_mod Customizer selected value of excerpt/image layout
         * @param array $block_args Array of argument to set classes for image wrapping block,  
         * text area blocks, and image size and image class etc
         */
        private static function main_content_image_right( $layout_mod, $block_args = array() ) {
            // check for thumbnails
            if ( has_post_thumbnail() ) {
            ?>
                <div class="col-md-8">
                    <div class="row">
                    <?php
                        self::main_content_texts( $layout_mod, $block_args );
                    ?>
                    </div>
                </div>
            <?php
                self::thumbnail_block( $layout_mod, $block_args['img_block_class'], $block_args['img_size'], $block_args['img_class'] );
            } else {
                self::main_content_texts( $layout_mod, $block_args );
            }
        }
        /**
         * Template to display post title with meta data, excerpt, category and tag buttons
         * and excerpt footer parts
         * 
         * @param string $layout_mod Customizer selected value of excerpt/image layout
         * @param array $block_args Array of argument to set classes for image wrapping block,  
         * text area blocks, and image size and image class etc
         */
        private static function main_content_texts( $layout_mod, $block_args = array() ) {
            // displaying block parts
            self::entry_header( $block_args['header_class'] );
            self::entry_content( $block_args['excerpt_class'] );
            if ( 'image-top-left' !== $layout_mod ) {
                self::category_tags_block( $layout_mod, $block_args['cat_block_class'] );
            }
            self::entry_footer( $block_args['footer_class'] );
        }
        /**
         * Opening the post block
         * @param string $additional_classes list of additional classes
         * for post block
         */
        public static function post_block_start( $block_args = array() ) {
            // get args
            $block_args = self::config_args( $block_args );
            ?>
            <article <?php post_class( $block_args['wrapper_class'] ); ?> id="post-<?php the_ID(); ?>">
            <?php
            if ( $block_args['has_inner_wrapper'] ) {
                // inner wrapper attribute
                $inner_attr = empty( $block_args['inner_wrapper_class'] ) ? '' : ' class="' . esc_attr( self::list_classes( $block_args['inner_wrapper_class'] ) ) . '"';
                ?>
                <div<?php echo $inner_attr; ?>>
                <?php
            }
        }
        /**
         * End of post block
         */
        public static function post_block_end( $block_args = array() ) {
            // get args
            $block_args = self::config_args( $block_args );
            // inner wrapper
            if ( $block_args['has_inner_wrapper'] ) {
                ?>
                </div>
                <?php
            }
            // closing wrapper
            ?>
            </article><!-- #post-<?php the_ID(); ?> -->
            <?php
        }
        /**
         * Prints post entry header
         * @param string/array $entry_class additional class for block
         */
        public static function entry_header( $entry_class = '' ) {
            // header class list
            $header_class_list = self::list_classes( $entry_class, array( 'entry-header' ) );
            ?>
            <header class="<?php echo esc_attr( $header_class_list ); ?>">
				<?php
				the_title(
					sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ),
					'</a></h3>'
				);
				?>
				<?php if ( 'post' === get_post_type() ) { ?>
					<div class="entry-meta">
						<?php acadprof_posted_on(); ?>
					</div><!-- .entry-meta -->
				<?php } ?>
			</header><!-- .entry-header -->
            <?php
        }
        /**
         * Prints post entry content
         * @param string/array $entry_class additional class for block
         */
        public static function entry_content( $entry_class = '' ) {
            // content entry class list
            $content_class_list = self::list_classes( $entry_class, array( 'entry-content' ) );
            ?>
            <div class="<?php echo esc_attr( $content_class_list ); ?>">
				<?php
				the_excerpt();
				?>
			</div><!-- .entry-content -->
            <?php
        }
        /**
         * Prints post entry footer
         * @param string/array $entry_class additional class for block
         */
        public static function entry_footer( $entry_class = '' ) {
            // footer entry class list
            $footer_class_list = self::list_classes( $entry_class, array( 'entry-footer' ) );
            ?>
            <footer class="<?php echo esc_attr( $footer_class_list ); ?>">
                <?php acadprof_link_pages(); ?>
            </footer><!-- .entry-footer -->
            <?php
        }
        /**
         * Method to display block containing categories, tags buttons/links
         * Prints block of categories and tags links
         * @param array/string $classes Additional list of classes for block wrapper
         * 
         */
        public static function category_tags_block( $layout_mod = 'image-left', $classes = '' ) {
            // bootstrap classes
            $bs_classes = array();
            if ( 'image-top-left' === $layout_mod && has_post_thumbnail() ) {
                $bs_classes = array( 'col-md-6' );
            } else {
                $bs_classes = array( 'col-12' );
            }
            // get class list
            $classes_list = self::list_classes( $classes, $bs_classes );
            ?>
            <div class="<?php echo esc_attr( $classes_list ) ?>">
                <?php acadprof_category_tag_links(); ?>
            </div>
            <?php
        }
        /**
         * prints thumbnail block
         * @param array/string list of image wrapper block's classes
         * @param string $size thumbnail size
         * @param array $class Class list for img tag in array or string separated by space
         * @return mixed/html block containing post thumbnail
         */
        public static function thumbnail_block( $layout_mod = 'image-left', $block_classes = '', $size = 'large', $class = 'img-fluid d-block' ) {
            $wrapper_class = self::img_wrapper_class( $layout_mod, $block_classes );
            ?>
            <div class="<?php echo esc_attr( $wrapper_class ); ?>">
                <?php acadprof_the_post_thumbnail_link( get_the_ID(), $size, $class ); ?>
            </div>
            <?php
        }
        /**
         * Method to return class of image wrapper block
         * @param array/string $classes Additional list of classes for block wrapper
         * @return string Class list of image wrapper
         */
        private static function img_wrapper_class( $layout_mod = 'image-left', $classes = '' ) {
            // bootstrap classes
            $bs_classes = array();
            // wrapper class
            if ( 'image-top' === $layout_mod ) {
                $bs_classes = array( 'col-12' );
            } elseif ( 'image-top-left' === $layout_mod ) {
                $bs_classes = array( 'col-md-6' );
            } else {
                $bs_classes = array( 'col-md-4' );
            }
            // get class list
            $classes_list = self::list_classes( $classes, $bs_classes );
            return $classes_list;
        }
        /**
         * For internal use
         * Helper to get parsed classes
         * @param array/string $classes list of class argument separated by space
         * @param array $defaults default class list array
         * @return string list of parsed classes
         */
        private static function list_classes( $classes, $defaults = array() ) {
            $class_list = '';
            // prepare $classes as array
            $classes = ( ! is_array( $classes ) && is_string( $classes ) ) ? (array) $classes : $classes;
            // parsing
            if ( ! empty( $defaults ) ) {
                $classes = wp_parse_args( $classes, $defaults );
                $class_list .= implode( ' ', (array) implode( ' ', $classes ) );
            } else {
                $class_list .= implode( ' ', $classes );
            }
            return $class_list;
        }
    }
}