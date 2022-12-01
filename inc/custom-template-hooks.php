<?php

/**
* Function hook for dislaying related posts of post
*/
if ( ! function_exists( 'acadprof_related_posts_action' ) ) :

	function acadprof_related_posts_action( $post_id ) {

		$args = array(
				'post__not_in'			=>	array( $post_id ),
				'posts_per_page'		=>	4,
				'ignore_sticky_posts'	=>	1,
				);
		// get tags of current single post
		$acadprof_tags = wp_get_post_tags( $post_id );

		// get categories of current single post
		$acadprof_categorries = get_the_category( $post_id );

		if ( $acadprof_tags ) {

			$acadprof_tag_ids = array();

			foreach ( $acadprof_tags as $acadprof_tag ) {
				$acadprof_tag_ids[] = $acadprof_tag->term_id;
			}

			// include tag in args
			$args['tag__in'] = $acadprof_tag_ids;

		} else {
			$acadprof_category_ids = array();

			foreach ( $acadprof_categorries as $acadprof_category ) {
				$acadprof_category_ids[] = $acadprof_category->term_id;
			}

			// include category in args
			$args['category__in'] = $acadprof_category_ids;
		}

		// get posts based on the argument above
		$acadprof_related_posts = new WP_Query( $args );

		if ( $acadprof_related_posts->have_posts() ) {
			?>
			<div class="row py-3">
				<div class="col-12">
					<h1><?php _e( 'Related Posts', 'acadprof' ); ?></h1>
				</div>
			</div>
			<div class="row row-cols-1 row-cols-md-2 g-4">
			<?php

			while($acadprof_related_posts->have_posts()) {
				$acadprof_related_posts->the_post();
				?>
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'col mb-4 related-post-col' ); ?>>
				    <div class="card fancy-shadow">
			    	<?php  
				    	if ( has_post_thumbnail() ) {
			    	?>
				      	<a href="<?php the_permalink(); ?>">
	                  	<?php
	                      	$related_img_class = array( 'card-img-top img-fitBox' );
	                      	the_post_thumbnail( 
		                        'acadprof-img-small', 
		                        array(
		                          'class' =>  esc_attr( implode( ' ', $related_img_class ) ),
		                          'alt'   =>  the_title_attribute( 
		                            array(
		                              'echo'  =>  false
		                            ) 
		                          ),
		                        ) 
	                      	);  
		                       
	                  	?>
		                </a>
		            <?php  
		            	}
		            ?>
				      	<div class="card-body">
				        	<h3 class="card-title">
				        		<?php  
			                      echo sprintf( 
			                        '<a href="%1$s">%2$s</a>', 
			                         esc_url( get_permalink() ), 
			                         sprintf( __( '%s', 'acadprof' ), 
			                          esc_html( get_the_title() )
			                          )
			                        );
			                    ?>
				        	</h3>
				        	<?php  
				        		the_excerpt();
				        	?>
				      	</div>
				    </div>
				</div>
				<?php
			}
			?>
			</div>
			<?php

		}

		wp_reset_query();

	}
endif;
add_action( 'acadprof_related_post_cards', 'acadprof_related_posts_action', 10 );