<?php

/* Load frontend scripts and styles */
add_action( 'wp_enqueue_scripts', 'gridlove_load_scripts' );
/**
 * Load scripts and styles on frontend
 *
 * It just wrapps two other separate functions for loading css and js files
 *
 * @return void
 * @since  1.0
 */

function gridlove_load_scripts() {
	gridlove_load_css();
	gridlove_load_js();
}

/**
 * Load frontend css files
 *
 * @return void
 * @since  1.0
 */

function gridlove_load_css() {

	//Load google fonts
	if ( $fonts_link = gridlove_generate_fonts_link() ) {
		wp_enqueue_style( 'gridlove-fonts', $fonts_link, false, GRIDLOVE_THEME_VERSION );
	}

	//Check if is minified option active and load appropriate files
	if ( gridlove_get_option( 'minify_css' ) ) {

		wp_enqueue_style( 'gridlove-main', get_template_directory_uri() . '/assets/css/min.css', false, GRIDLOVE_THEME_VERSION );

	} else {

		$styles = array(
			'font-awesome' => 'font-awesome.css',
			'bootstrap' => 'bootstrap.css',
			'magnific-popup' => 'magnific-popup.css',
			'owl-carousel' => 'owl-carousel.css',
			'main' => 'main.css'
		);

		foreach ( $styles as $id => $style ) {
			wp_enqueue_style( 'gridlove-' . $id, get_template_directory_uri() . '/assets/css/' . $style, false, GRIDLOVE_THEME_VERSION );
		}
	}

	//Append dynamic css
	wp_add_inline_style( 'gridlove-main', gridlove_generate_dynamic_css() );


	//Load WooCommerce CSS
	if ( gridlove_is_woocommerce_active() ) {
		wp_enqueue_style( 'gridlove-woocommerce', get_template_directory_uri() . '/assets/css/gridlove-woocommerce.css', array( 'gridlove-main'), GRIDLOVE_THEME_VERSION );
	}

	//Load bbPress CSS
	if ( gridlove_is_bbpress_active() ) {
		wp_enqueue_style( 'gridlove-bbpress', get_template_directory_uri() . '/assets/css/gridlove-bbpress.css', array( 'gridlove-main'), GRIDLOVE_THEME_VERSION );
	}

	//Load RTL css
	if ( gridlove_is_rtl() ) {
		wp_enqueue_style( 'gridlove-rtl', get_template_directory_uri() . '/assets/css/rtl.css', array( 'gridlove-main' ), GRIDLOVE_THEME_VERSION );
	}

	//Do not load font awesome from our shortcodes plugin
	wp_dequeue_style( 'mks_shortcodes_fntawsm_css' );

}


/**
 * Load frontend js files
 *
 * @return void
 * @since  1.0
 */

function gridlove_load_js() {

	//Load comment reply js
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	//Check if is minified option active and load appropriate files
	if ( gridlove_get_option( 'minify_js' ) ) {

		wp_enqueue_script( 'gridlove-main', get_template_directory_uri() . '/assets/js/min.js', array( 'jquery', 'jquery-masonry' ), GRIDLOVE_THEME_VERSION, true );

	} else {

		$scripts = array(
			'imagesloaded' => 'imagesloaded.js',
			'magnific-popup' => 'magnific-popup.js',
			'fitvids' => 'fitvids.js',
			'autoellipsis' => 'autoellipsis.js',
			'sticky-kit' => 'sticky-kit.js',
			'owl-carousel' => 'owl-carousel.js',
			'objectfit-images' => 'ofi.js',
			'main' => 'main.js'
		);

		foreach ( $scripts as $id => $script ) {
			wp_enqueue_script( 'gridlove-'.$id, get_template_directory_uri().'/assets/js/'. $script, array( 'jquery', 'jquery-masonry' ), GRIDLOVE_THEME_VERSION, true );
		}
	}

	wp_localize_script( 'gridlove-main', 'gridlove_js_settings', gridlove_get_js_settings() );
}
?>
