<?php

/**
 * Hide update notification and update theme version
 *
 * @since  1.0
 */

add_action('wp_ajax_gridlove_update_version', 'gridlove_update_version');

if(!function_exists('gridlove_update_version')):
function gridlove_update_version(){
	update_option('gridlove_theme_version', GRIDLOVE_THEME_VERSION);
	die();
}
endif;


/**
 * Hide welcome notification
 *
 * @since  1.0
 */

add_action('wp_ajax_gridlove_hide_welcome', 'gridlove_hide_welcome');

if(!function_exists('gridlove_hide_welcome')):
function gridlove_hide_welcome(){
	update_option('gridlove_welcome_box_displayed', true);
	die();
}
endif;


/**
 * Get searched posts or pages on ajax call for auto-complete functionality
 * 
 */
add_action( 'wp_ajax_gridlove_ajax_search', 'gridlove_ajax_search' );

if ( !function_exists( 'gridlove_ajax_search' ) ):
	function gridlove_ajax_search() {
		
		$post_type = in_array($_GET['type'], array('posts', 'cover')) ? array_keys( get_post_types( array( 'public' => true ) ) ) : $_GET['type'];
		
		$posts = get_posts( array(
				's' => $_GET['term'],
				'post_type' => $post_type,
				'posts_per_page' => -1
			) );

		$suggestions = array();

		global $post;
		
		foreach ( $posts as $post ) {
			setup_postdata( $post );
			$suggestion = array();
			$suggestion['label'] = esc_html( $post->post_title );
			$suggestion['id'] = $post->ID;
			$suggestions[]= $suggestion;
		}

		$response = $_GET["callback"] . "(" . json_encode( $suggestions ) . ")";

		echo $response;

		die();
	}
endif;


?>
