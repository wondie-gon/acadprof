<?php
/**
* Profile Intro widget to display personal profile intro
*
* refer in wp-includes/class-wp-widget.php
*/
class Acadprof_Profile_Intro_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'acadprof_profile_widget',
			'description' => 'Widget to display personal profile introduction',
		);
		
		parent::__construct( 
			// Base ID of your widget
			'acadprof_profile_intro', 
			// Widget name will appear in UI
			__( 'Profile Intro Widget', 'acadprof' ), 

			$widget_ops 
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// filter widget title
		$title = apply_filters( 'widget_title', $instance['title'] );

		// start displaying widget
		echo $args['before_widget'];
		?>
		<div class="col-md-4 img-holder">
		<?php
			if ( ! empty( $instance['img_src'] ) ) { 
		?>
			<img src="<?php echo esc_url( $instance['img_src'] ); ?>" class="intro-image img-fluid">
		<?php } ?>
        </div>
		<div class="col-md-8">
          	<div class="intro-hdr d-flex flex-column justify-content-end">
          		<?php
				if ( ! empty( $title ) ) { 
					echo $args['before_title'] . $title . $args['after_title'];
				} 
				// job position
				if ( ! empty( $instance['job_position'] ) ) { 
				?>
					<h3><?php echo sprintf( esc_html__( '%s', 'acadprof' ), $instance['job_position'] ); ?></h3>
				<?php } ?>
            	
            	<ul class="nav profile-social-nav">
				<?php

				// email address
				if ( ! empty( $instance['email_addr'] ) ) { ?>
					<li class="nav-item">
						<a class="nav-link" href="mailto:<?php echo $instance['email_addr']; ?>">
							<span class="fa-stack" style="vertical-align: top;">
							    <i class="far fa-circle fa-stack-2x"></i>
							    <i class="fas fa-envelope fa-stack-1x"></i>
							</span>
						</a>
					</li>
				<?php } ?>

				<?php if ( ! empty( $instance['phone_num'] ) ) { ?>
					<li class="nav-item">
						<a class="nav-link" href="tel:<?php echo $instance['phone_num']; ?>">
							<span class="fa-stack" style="vertical-align: top;">
							    <i class="far fa-circle fa-stack-2x"></i>
							    <i class="fas fa-phone fa-stack-1x"></i>
							</span>
						</a>
					</li>
				<?php } ?>

				<?php if ( ! empty( $instance['linkedin'] ) ) { ?>
					<li class="nav-item">
						<a class="nav-link" href="https://www.linkedin.com/in/<?php echo $instance['linkedin']; ?>">
							<span class="fa-stack" style="vertical-align: top;">
							    <i class="far fa-circle fa-stack-2x"></i>
							    <i class="fab fa-linkedin fa-stack-1x"></i>
							</span>
						</a>
					</li>
				<?php } ?>

				<?php if ( ! empty( $instance['facebook'] ) ) { ?>
					<li class="nav-item">
						<a class="nav-link" href="https://www.facebook.com/<?php echo $instance['facebook']; ?>">
							<span class="fa-stack" style="vertical-align: top;">
							    <i class="far fa-circle fa-stack-2x"></i>
							    <i class="fab fa-facebook-f fa-stack-2x"></i>
							</span>
						</a>
					</li>
				<?php } ?>

				<?php if ( ! empty( $instance['twitter'] ) ) { ?>
					<li class="nav-item">
						<a class="nav-link" href="https://www.twitter.com/<?php echo $instance['twitter']; ?>">
							<span class="fa-stack" style="vertical-align: top;">
							    <i class="far fa-circle fa-stack-2x"></i>
							    <i class="fab fa-twitter fa-stack-1x"></i>
							</span>
						</a>
					</li>
				<?php } ?>

				<?php if ( ! empty( $instance['github_username'] ) ) { ?>
					<li class="nav-item">
						<a class="nav-link" href="https://www.github.com/<?php echo $instance['github_username']; ?>">
							<span class="fa-stack" style="vertical-align: top;">
							    <i class="far fa-circle fa-stack-2x"></i>
							    <i class="fab fa-github fa-stack-1x"></i>
							</span>
						</a>
					</li>
				<?php } ?>
				</ul>
          	</div>
          	<div class="intro-txt d-flex flex-column justify-content-end">
          		<?php if ( ! empty( $instance['resume_intro'] ) ) { ?>
					<p><?php printf( esc_html__( '%s', 'acadprof' ), $instance['resume_intro'] ); ?></p>
				<?php } ?>

				<?php if ( ! empty( $instance['resume_link'] ) ) { ?>
					<a href="<?php echo esc_url( $instance['resume_link'] ); ?>" class="btn btn-wonui-intro rounded-btns"><?php esc_html_e( 'See Full CV', 'acadprof' ); ?></a>
				<?php } ?>
          	</div>
        </div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( $instance, 
			array(
				'title'					=>	'',
				'job_position'			=>	'',
				'img_src'				=>	'',
				'email_addr'			=>	'',
				'phone_num'				=>	'',
				'linkedin'				=>	'',
				'facebook'				=>	'',
				'twitter'				=>	'',
				'github_username'		=>	'',
				'resume_intro'			=>	'',
				'resume_link'			=>	'',
			) 
		);

		?>
		<div class="widget-content">
			<?php  
				$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Profile Name', 'acadprof' ); ?></label>
		        <input
		            type="text"
		            value="<?php echo esc_attr( $title ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
		            id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
		            class="widefat" 
		            placeholder="<?php esc_html_e( 'Profile Name', 'acadprof' ); ?>"
		        />
		    </p>
			<?php  
				$job_position = ! empty( $instance['job_position'] ) ? $instance['job_position'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'job_position' ) ); ?>"><?php esc_attr_e( 'Job Position/Rank:', 'acadprof' ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'job_position' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'job_position' ) ); ?>"
		            value="<?php echo esc_attr( $job_position ); ?>" 
		            placeholder="<?php esc_html_e( 'Job Position', 'acadprof' ); ?>"
		            class="widefat"
		        />
		    </p>

		    <?php  
				$img_src = ! empty( $instance['img_src'] ) ? $instance['img_src'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'img_src' ) ); ?>"><?php esc_attr_e( 'Profile Picture URL:', 'acadprof' ); ?></label>
		        <input
		            type="url"
		            id="<?php echo esc_attr( $this->get_field_id( 'img_src' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'img_src' ) ); ?>"
		            value="<?php echo esc_attr( $img_src ); ?>" 
		            class="widefat"
		        />
		    </p>
		    <!-- http://localhost:8080/tesfahunasmamaw.com/wp-content/uploads/2022/06/profile-pic.jpg -->

		    <?php  
				$email_addr = ! empty( $instance['email_addr'] ) ? $instance['email_addr'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'email_addr' ) ); ?>"><?php esc_attr_e( 'Email:', 'acadprof' ); ?></label>
		        <input
		            type="email"
		            id="<?php echo esc_attr( $this->get_field_id( 'email_addr' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'email_addr' ) ); ?>"
		            value="<?php echo esc_attr( $email_addr ); ?>"
		            placeholder="email@example.com" 
		            class="widefat"
		        />
		    </p>

		    <?php  
				$phone_num = ! empty( $instance['phone_num'] ) ? $instance['phone_num'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'phone_num' ) ); ?>"><?php esc_attr_e( 'Phone Number:', 'acadprof' ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'phone_num' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'phone_num' ) ); ?>"
		            value="<?php echo esc_attr( $phone_num ); ?>"
		            placeholder="+251911000000" 
		            class="widefat"
		        />
		    </p>

		    <?php  
				$linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_attr_e( 'Linkedin username:', 'acadprof' ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>"
		            value="<?php echo esc_attr( $linkedin ); ?>"
		            placeholder="linkedin_username" 
		            class="widefat"
		        />
		    </p>

		    <?php  
				$facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_attr_e( 'Facebook username:', 'acadprof' ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>"
		            value="<?php echo esc_attr( $facebook ); ?>"
		            placeholder="fb_username" 
		            class="widefat"
		        />
		    </p>

		    <?php  
				$twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_attr_e( 'Twitter handle:', 'acadprof' ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>"
		            value="<?php echo esc_attr( $twitter ); ?>"
		            placeholder="@twitter-handle" 
		            class="widefat"
		        />
		    </p>

		    <?php  
				$github_username = ! empty( $instance['github_username'] ) ? $instance['github_username'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'github_username' ) ); ?>"><?php esc_attr_e( 'Github username:', 'acadprof' ); ?></label>
		        <input
		            type="text"
		            id="<?php echo esc_attr( $this->get_field_id( 'github_username' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'github_username' ) ); ?>"
		            value="<?php echo esc_attr( $github_username ); ?>"
		            placeholder="github-username" 
		            class="widefat"
		        />
		    </p>

		    <?php  
				$resume_intro = ! empty( $instance['resume_intro'] ) ? $instance['resume_intro'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'resume_intro' ) ); ?>"><?php esc_attr_e( 'Resume Summary:', 'acadprof' ); ?></label>
		        <textarea
		            id="<?php echo esc_attr( $this->get_field_id( 'resume_intro' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'resume_intro' ) ); ?>"
		            placeholder="<?php esc_html_e( 'Write resume summary', 'acadprof' ); ?>">
		            <?php echo esc_attr( $resume_intro ); ?>
		            </textarea>
		    </p>

		    <?php  
				$resume_link = ! empty( $instance['resume_link'] ) ? $instance['resume_link'] : '';
			?>
		    <p>
		    	<label for="<?php echo esc_attr( $this->get_field_id( 'resume_link' ) ); ?>"><?php esc_attr_e( 'Link to CV:', 'acadprof' ); ?></label>
		        <input
		            type="url"
		            id="<?php echo esc_attr( $this->get_field_id( 'resume_link' ) ); ?>"
		            name="<?php echo esc_attr( $this->get_field_name( 'resume_link' ) ); ?>"
		            value="<?php echo esc_attr( $resume_link ); ?>" 
		            class="widefat"
		        />
		    </p>
		</div><!-- .widget-content -->
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		$instance['job_position'] = ( ! empty( $new_instance['job_position'] ) ) ? sanitize_text_field( $new_instance['job_position'] ) : '';

		$instance['img_src'] = ( ! empty( $new_instance['img_src'] ) ) ? esc_url_raw( $new_instance['img_src'] ) : '';

		$instance['email_addr'] = ( ! empty( $new_instance['email_addr'] ) ) ? sanitize_email( $new_instance['email_addr'] ) : '';
		
		$instance['phone_num'] = ( ! empty( $new_instance['phone_num'] ) ) ? sanitize_text_field( $new_instance['phone_num'] ) : '';

		$instance['linkedin'] = ( ! empty( $new_instance['linkedin'] ) ) ? sanitize_text_field( $new_instance['linkedin'] ) : '';

		$instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? sanitize_text_field( $new_instance['facebook'] ) : '';

		$instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? sanitize_text_field( $new_instance['twitter'] ) : '';

		$instance['github_username'] = ( ! empty( $new_instance['github_username'] ) ) ? sanitize_text_field( $new_instance['github_username'] ) : '';

		$instance['resume_intro'] = ( ! empty( $new_instance['resume_intro'] ) ) ? sanitize_textarea_field( $new_instance['resume_intro'] ) : '';

		$instance['resume_link'] = ( ! empty( $new_instance['resume_link'] ) ) ? esc_url_raw( $new_instance['resume_link'] ) : '';

		return $instance;
	}
}