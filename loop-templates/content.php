<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Acadprof
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$block_args = array(
	'wrapper_class'     	=>  'article-block mb-5 p-3', 
	'has_inner_wrapper'     =>  true, 
	'inner_wrapper_class'	=>  'row',
	'header_class'      	=>  'col-12', 
	'excerpt_class'     	=>  'col-12 py-2', 
	'footer_class'      	=>  'col-12 py-2', 
	'cat_block_class'   	=>  '', 
	'img_block_class'   	=>  '', 
	'img_size'          	=>  'large', 
	'img_class'         	=>  'img-fluid d-block'
);

$post_layout_mod = get_theme_mod( 'acadprof_excerpt_image_layout' );

Acadprof_Post_Excerpt_Layout::show_post_block( $post_layout_mod, $block_args );
?>

