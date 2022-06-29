<?php


/**
 * in_widget_form callback
 *
 * Appends "highlight" option to each widget so we can later apply different styling
 *
 * @return void
 * @since  1.0
 */

add_action( 'in_widget_form', 'gridlove_add_widget_form_options', 10, 3 );

if ( !function_exists( 'gridlove_add_widget_form_options' ) ) :

	function gridlove_add_widget_form_options(  $widget, $return, $instance) {

	if(!isset($instance['gridlove-highlight'])){
		$instance['gridlove-highlight'] = 0;
	}

?>	
	<p class="gridlove-opt-highlight">
		<label for="<?php echo esc_attr( $widget->get_field_id( 'gridlove-highlight' )); ?>">
			<input type="checkbox" id="<?php echo esc_attr($widget->get_field_id( 'gridlove-highlight' )); ?>" name="<?php echo esc_attr($widget->get_field_name( 'gridlove-highlight' )); ?>" value="1" <?php checked($instance['gridlove-highlight'], 1); ?> />
			<?php esc_html_e( 'Highlight this widget', 'gridlove');?>
		</label>
		<small class="howto"><?php  echo wp_kses( sprintf( __( 'Display widget in <a href="%s">highlight styling</a>.', 'gridlove' ), admin_url( 'admin.php?page=gridlove_options&tab=6' ) ), wp_kses_allowed_html( 'post' ));?></small>
	</p>

<?php
	
	}

endif;


/**
 * widget_update_callback
 *
 * Save highlight option in widgets
 *
 * @return void
 * @since  1.0
 */

add_filter( 'widget_update_callback', 'gridlove_save_widget_form_options', 20, 2 );

if ( !function_exists( 'gridlove_save_widget_form_options' ) ) :

	function gridlove_save_widget_form_options( $instance, $new_instance ) {
		
		$instance['gridlove-highlight'] = isset( $new_instance['gridlove-highlight'] ) ? 1 : 0;
		return $instance;

	}

endif;

/**
 * Store registered sidebars so we can use them inside theme options 
 * before wp_registered_sidebars global is initialized
 *
 * @since  1.0
 */

add_action( 'admin_init', 'gridlove_check_sidebars' );

if ( !function_exists( 'gridlove_check_sidebars' ) ):
	function gridlove_check_sidebars() {
		global $wp_registered_sidebars;
		if ( !empty( $wp_registered_sidebars ) ) {
			update_option( 'gridlove_registered_sidebars', $wp_registered_sidebars );
		}
	}
endif;




/**
 * Change customize link to lead to theme options instead of live customizer 
 *
 * @since  1.0
 */

add_filter( 'wp_prepare_themes_for_js', 'gridlove_change_customize_link' );

if ( !function_exists( 'gridlove_change_customize_link' ) ):
	function gridlove_change_customize_link( $themes ) {
		if ( array_key_exists( 'gridlove', $themes ) ) {
			$themes['gridlove']['actions']['customize'] = admin_url( 'admin.php?page=gridlove_options' );
		}
		return $themes;
	}
endif;


/**
 * Change default arguments of flickr widget plugin
 *
 * @since  1.0
 */

add_filter( 'mks_flickr_widget_modify_defaults', 'gridlove_flickr_widget_defaults' );

if ( !function_exists( 'gridlove_flickr_widget_defaults' ) ):
	function gridlove_flickr_widget_defaults( $defaults ) {

		$defaults['count'] = 9;
		$defaults['t_width'] = 79;
		$defaults['t_height'] = 79;
		
		return $defaults;
	}
endif;



/**
 * Change default arguments of author widget plugin
 *
 * @since  1.0
 */

add_filter( 'mks_author_widget_modify_defaults', 'gridlove_author_widget_defaults' );

if ( !function_exists( 'gridlove_author_widget_defaults' ) ):
	function gridlove_author_widget_defaults( $defaults ) {
		$defaults['title'] = '';
		$defaults['avatar_size'] = 80;
		return $defaults;
	}
endif;



/**
 * Change default arguments of social widget plugin
 *
 * @since  1.0
 */

add_filter( 'mks_social_widget_modify_defaults', 'gridlove_social_widget_defaults' );

if ( !function_exists( 'gridlove_social_widget_defaults' ) ):
	function gridlove_social_widget_defaults( $defaults ) {
		$defaults['size'] = 42;
		$defaults['style'] = 'circle';
		return $defaults;
	}
endif;



/**
 * Display theme admin notices
 *
 * @since  1.0
 */

add_action( 'admin_init', 'gridlove_check_installation' );

if ( !function_exists( 'gridlove_check_installation' ) ):
	function gridlove_check_installation() {
		add_action( 'admin_notices', 'gridlove_welcome_msg', 1 );
		add_action( 'admin_notices', 'gridlove_update_msg', 1 );
	}
endif;



/**
 * Display welcome message and quick tips after theme activation
 *
 * @since  1.0
 */

if ( !function_exists( 'gridlove_welcome_msg' ) ):
	function gridlove_welcome_msg() {
		if ( !get_option( 'gridlove_welcome_box_displayed' ) ) { 
			update_option( 'gridlove_theme_version', GRIDLOVE_THEME_VERSION );
			include_once get_template_directory() .'/core/admin/welcome-panel.php';
		}
	}
endif;


/**
 * Display message when new version of the theme is installed/updated
 *
 * @since  1.0
 */

if ( !function_exists( 'gridlove_update_msg' ) ):
	function gridlove_update_msg() {
		if ( get_option( 'gridlove_welcome_box_displayed' ) ) {
			$prev_version = get_option( 'gridlove_theme_version' );
			$cur_version = GRIDLOVE_THEME_VERSION;
			if ( $prev_version === false ) { $prev_version = '0.0.0'; }
			if ( version_compare( $cur_version, $prev_version, '>' ) ) {
				include_once get_template_directory() .'/core/admin/update-panel.php';
			}
		}
	}
endif;



/**
 * Add Meks dashboard widget
 *
 * @since  1.0
 */

add_action( 'wp_dashboard_setup', 'gridlove_add_dashboard_widgets' );

if ( !function_exists( 'gridlove_add_dashboard_widgets' ) ):
	function gridlove_add_dashboard_widgets() {
		add_meta_box( 'gridlove_dashboard_widget', 'Meks - WordPress Themes & Plugins', 'gridlove_dashboard_widget_cb', 'dashboard', 'side', 'high' );
	}
endif;


if ( !function_exists( 'gridlove_dashboard_widget_cb' ) ):
	function gridlove_dashboard_widget_cb() {
		$hide = false;
		if ( $data = get_transient( 'gridlove_mksaw' ) ) {
			if ( $data != 'error' ) {
				echo $data;
			} else {
				$hide = true;
			}
		} else {
			$url = 'https://demo.mekshq.com/mksaw.php';
			$args = array( 'body' => array( 'key' => md5( 'meks' ), 'theme' => 'gridlove' ) );
			$response = wp_remote_post( $url, $args );
			if ( !is_wp_error( $response ) ) {
				$json = wp_remote_retrieve_body( $response );
				if ( !empty( $json ) ) {
					$json = ( json_decode( $json ) );
					if ( isset( $json->data ) ) {
						echo $json->data;
						set_transient( 'gridlove_mksaw', $json->data, 86400 );
					} else {
						set_transient( 'gridlove_mksaw', 'error', 86400 );
						$hide = true;
					}
				} else {
					set_transient( 'gridlove_mksaw', 'error', 86400 );
					$hide = true;
				}

			} else {
				set_transient( 'gridlove_mksaw', 'error', 86400 );
				$hide = true;
			}
		}

		if ( $hide ) {
			echo '<style>#gridlove_dashboard_widget {display:none;}</style>'; //hide widget if data is not returned properly
		}

	}
endif;


/**
 * Remove common scripts from some plugins 
 * which are not loaded properly and may break admin stuff
 *
 * @since  1.3
 */

add_action('admin_enqueue_scripts', 'gridlove_exclude_admin_scripts', 99 );

if ( !function_exists( 'gridlove_exclude_admin_scripts' ) ):
	function gridlove_exclude_admin_scripts() {

		wp_dequeue_style( 'jquery-ui.js' );

	}
endif;

/**
 * Extend user social profiles
 *
 * @since  1.3
 */

add_filter( 'user_contactmethods', 'gridlove_user_social_profiles' );

if ( !function_exists( 'gridlove_user_social_profiles' ) ):
	function gridlove_user_social_profiles( $contactmethods ) {

		unset( $contactmethods['aim'] );
		unset( $contactmethods['yim'] );
		unset( $contactmethods['jabber'] );

		$social = gridlove_get_social();
		foreach ( $social as $soc_id => $soc_name ) {
			if ( $soc_id ) {
				$contactmethods[$soc_id] = $soc_name;
			}
		}

		return $contactmethods;
	}
endif;

?>
