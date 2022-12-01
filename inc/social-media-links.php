<?php
/**
* Custom social media links template functions
*
* @package Acadprof
* @since 1.0.0 
*/
/**
* Get social media links username array
*
*/
if ( ! function_exists( 'acadprof_get_social_media_usernames' ) ) {
	function acadprof_get_social_media_usernames() {
		$social_usernames = array();
		// facebook
		$social_usernames['facebook'] = ! empty( get_theme_mod( 'acadprof_facebook_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_facebook_link_username' ) ) : '';
		// twitter
		$social_usernames['twitter'] = ! empty( get_theme_mod( 'acadprof_twitter_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_twitter_link_username' ) ) : '';
		// googleplus
		$social_usernames['googleplus'] = ! empty( get_theme_mod( 'acadprof_googleplus_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_googleplus_link_username' ) ) : '';
		// pinterest
		$social_usernames['pinterest'] = ! empty( get_theme_mod( 'acadprof_pinterest_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_pinterest_link_username' ) ) : '';
		// linkedin
		$social_usernames['linkedin'] = ! empty( get_theme_mod( 'acadprof_linkedin_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_linkedin_link_username' ) ) : '';
		// github
		$social_usernames['github'] = ! empty( get_theme_mod( 'acadprof_github_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_github_link_username' ) ) : '';
		// instagram
		$social_usernames['instagram'] = ! empty( get_theme_mod( 'acadprof_instagram_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_instagram_link_username' ) ) : '';
		// youtube
		$social_usernames['youtube'] = ! empty( get_theme_mod( 'acadprof_youtube_link_username' ) ) ? esc_attr( get_theme_mod( 'acadprof_youtube_link_username' ) ) : '';

		return $social_usernames;
	}
}


add_action( 'acadprof_social_media_nav', 'acadprof_social_media_links_nav', 10 );
/**
* Social media links nav template
*
*/
if ( ! function_exists( 'acadprof_social_media_links_nav' ) ) {
	function acadprof_social_media_links_nav() {
		// get social usernames
		$mediaUsernames = acadprof_get_social_media_usernames();
		?>
		<div class="row">
			<div class="col-md-12">
				<h5><?php _e( 'Follow', 'acadprof' ); ?></h5>
			</div>
		</div>

		<ul class="navbar-nav flex-row flex-wrap ms-md-auto py-3">
			<?php 
			if ( isset( $mediaUsernames['facebook'] ) && ! empty( $mediaUsernames['facebook'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
	            	<a href="https://www.facebook.com/<?php echo esc_attr( $mediaUsernames['facebook'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-facebook-f"></i></a>
	          	</li>
				<?php
			}
			?>
          	
          	<?php 
			if ( isset( $mediaUsernames['twitter'] ) && ! empty( $mediaUsernames['twitter'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
	            	<a href="https://www.twitter.com/<?php echo esc_attr( $mediaUsernames['twitter'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-twitter"></i></a>
	          	</li>
				<?php
			}
			?>
          	
          	<?php 
			if ( isset( $mediaUsernames['googleplus'] ) && ! empty( $mediaUsernames['googleplus'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
	            	<a href="https://plus.google.com/<?php echo esc_attr( $mediaUsernames['googleplus'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-google-plus-g"></i></a>
	          	</li>
				<?php
			}
			?>

			<?php 
			if ( isset( $mediaUsernames['github'] ) && ! empty( $mediaUsernames['github'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
		            <a href="https://www.github.com/<?php echo esc_attr( $mediaUsernames['github'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-github"></i></a>
	          	</li>
				<?php
			}
			?>
          	
          	<?php 
			if ( isset( $mediaUsernames['pinterest'] ) && ! empty( $mediaUsernames['pinterest'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
	            	<a href="https://www.pinterest.com/<?php echo esc_attr( $mediaUsernames['pinterest'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-pinterest-p"></i></a>
	          	</li>
				<?php
			}
			?>
          	
          	<?php 
			if ( isset( $mediaUsernames['instagram'] ) && ! empty( $mediaUsernames['instagram'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
	            	<a href="https://www.instagram.com/<?php echo esc_attr( $mediaUsernames['instagram'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-instagram"></i></a>
	          	</li>
				<?php
			}
			?>
          	
          	<?php 
			if ( isset( $mediaUsernames['linkedin'] ) && ! empty( $mediaUsernames['linkedin'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
	            	<a href="https://www.linkedin.com/in/<?php echo esc_attr( $mediaUsernames['linkedin'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-linkedin-in"></i></a>
	          	</li>
				<?php
			}
			?>
          	
          	<?php 
			if ( isset( $mediaUsernames['youtube'] ) && ! empty( $mediaUsernames['youtube'] ) ) {
				?>
				<li class="nav-item col-6 col-lg-auto">
	            	<a href="https://www.youtube.com/<?php echo esc_attr( $mediaUsernames['youtube'] ); ?>" class="nav-link py-2 px-0 px-lg-2"><i class="fab fa-youtube"></i></a>
	          	</li>
				<?php
			}
			?>
        </ul>
		<?php
	}
}

add_action( 'acadprof_social_media_share', 'acadprof_post_social_media_share_links', 10 );
/** 
*   social media sharing for posts
*/
if ( ! function_exists( 'acadprof_post_social_media_share_links' ) ) :

    function acadprof_post_social_media_share_links() {

        global $post;

        $share_to_facebook = get_theme_mod( 'acadprof_select_facebook_to_share_post' );
        $share_to_twitter = get_theme_mod( 'acadprof_select_twitter_to_share_post' );
        $share_to_googleplus = get_theme_mod( 'acadprof_select_googleplus_to_share_post' );
        $share_to_pinterest = get_theme_mod( 'acadprof_select_pinterest_to_share_post' );
        $share_to_linkedin = get_theme_mod( 'acadprof_select_linkedin_to_share_post' );

        ?>          
        <ul class="nav acadprof-nav-pills">
            <li class="nav-item acadprof_social_item">
                <i class="fa fa-share-alt"></i>
            </li>
        <?php

            if ( $share_to_facebook )  :
        ?>
            <li class="nav-item acadprof_social_item"> 
                <a title="Share on Facebook" href="https://www.facebook.com/sharer.php?u=<?php echo get_permalink(); ?>&t=<?php echo get_the_title() . ' - '; bloginfo( 'name' ); ?>" class="nav-link social-share-link social_facebook" data-toggle="pill"><i class="d-block fab fa-facebook-f"></i></a> 
            </li>
        <?php

            endif;  
        
            if ( $share_to_twitter ) :
        ?>
            <li class="nav-item acadprof_social_item"> 
                <a title="Share on Twitter" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title() . ' - '; bloginfo( 'name' ); echo ' Check this out!'; ?>&url=<?php echo get_permalink(); ?>" class="nav-link social-share-link social_twitter" data-toggle="pill"><i class="d-block fab fa-twitter"></i></a> 
            </li>
        <?php

            endif;  
        
            if ( $share_to_googleplus ) :
        ?>
            <li class="nav-item acadprof_social_item"> 
                <a title="Share on Google+" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>" class="nav-link social-share-link social_gplus" data-toggle="pill"><i class="d-block fab fa-google-plus-g"></i></a> 
            </li>
        <?php

            endif;  
        
            if ( $share_to_pinterest ) :
        ?>
            <li class="nav-item acadprof_social_item"> 
                <a title="Share on Pinterest" href="https://www.pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>&description=<?php echo get_the_title() . ' - '; bloginfo( 'name' ); ?>" class="nav-link social-share-link social_pinterest" data-toggle="pill"><i class="d-block fab fa-pinterest-p"></i></a> 
            </li>
        <?php

            endif;  
        
            if ( $share_to_linkedin )  :
        ?>
            <li class="nav-item acadprof_social_item"> 
                <a title="Share on LinkedIn" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo get_permalink(); ?>&title=<?php echo get_the_title() . ' - '; bloginfo( 'name' ); ?>&summary=<?php echo get_the_title() . ' - '; bloginfo( 'name' ); ?>" class="nav-link social-share-link social_linkden" data-toggle="pill"><i class="d-block fab fa-linkedin-in"></i></a> 
            </li>
        <?php

            endif;  
        
        ?>
        </ul>

    <?php

     }

endif;