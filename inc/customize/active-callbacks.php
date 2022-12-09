<?php
/**
 * Active callback functions
 * 
 * @package Acadprof
 * @since 1.0.0
 */
/**
 * Check if the social media nav disabled
 * @param WP_Customize_Control $control Object for customize control
 */
function acadprof_if_social_media_link_nav_enabled( $control ) {
    return false != $control->manager->get_setting( 'enable_acadprof_social_media_link_nav' )->value();
}

/**
 * Check if the social media share links disabled
 * @param WP_Customize_Control $control Object for customize control
 */
function acadprof_if_social_media_sharing_enabled( $control ) {
    return false != $control->manager->get_setting( 'enable_acadprof_social_media_sharing' )->value();
}

/**
 * Checks if featured section was enabled
 */
function acadprof_is_featured_posts_enabled( $control ) {
    return ( false != $control->manager->get_setting( 'acadprof_featured_posts_enabled' )->value() ) ? true : false;
}