<?php

/**
 * Metaboxes setup
 * 
 * @since  1.0
 */

add_action( 'load-post.php', 'gridlove_meta_boxes_setup' );
add_action( 'load-post-new.php', 'gridlove_meta_boxes_setup' );

if ( !function_exists( 'gridlove_meta_boxes_setup' ) ) :
	function gridlove_meta_boxes_setup() {
		global $typenow;
		if ( $typenow == 'page' ) {
			add_action( 'add_meta_boxes', 'gridlove_load_page_metaboxes' );
			add_action( 'save_post', 'gridlove_save_page_metaboxes', 10, 2 );
		}

		if ( $typenow == 'post' ) {
			add_action( 'add_meta_boxes', 'gridlove_load_post_metaboxes' );
			add_action( 'save_post', 'gridlove_save_post_metaboxes', 10, 2 );
		}
	}
endif;


include_once( get_template_directory().'/core/admin/metaboxes/page.php');
include_once( get_template_directory().'/core/admin/metaboxes/post.php');
include_once( get_template_directory().'/core/admin/metaboxes/category.php');

?>
