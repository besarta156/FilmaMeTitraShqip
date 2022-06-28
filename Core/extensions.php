<?php


/**
 * body_class callback
 *
 * Checks for specific browser/device and applies additional class to body element
 *
 * @since  1.0
 */

add_filter( 'body_class', 'gridlove_body_class' );

if ( !function_exists( 'gridlove_body_class' ) ):
	function gridlove_body_class( $classes ) {
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

		//Add some browser classes which can be usefull for some css hacks later
		if ( $is_lynx ) $classes[] = 'lynx';
		elseif ( $is_gecko ) $classes[] = 'gecko';
		elseif ( $is_opera ) $classes[] = 'opera';
		elseif ( $is_NS4 ) $classes[] = 'ns4';
		elseif ( $is_chrome ) $classes[] = 'chrome';
		elseif ( $is_IE ) $classes[] = 'ie';
		else $classes[] = 'unknown';

		if ( $is_iphone ) $classes[] = 'iphone';

		$cover_overlay = gridlove_get_option('cover_overlay');

		if(	$cover_overlay && $cover_overlay != 'dark' ){

			$classes[] = 'gridlove-cover-overlay-'.$cover_overlay;
		}

		$classes[] = 'gridlove-v_' . str_replace('.', '_', GRIDLOVE_THEME_VERSION);

		return $classes;
	}
endif;



/**
 * wp_head callback
 *
 * Outputs additional CSS code from theme otpions
 *
 * @return void
 * @since  1.0
 */

add_action( 'wp_head', 'gridlove_wp_head', 99 );

if ( !function_exists( 'gridlove_wp_head' ) ):
	function gridlove_wp_head() {

		//Additional CSS (if user adds his custom css inside theme options)
		$additional_css = trim( preg_replace( '/\s+/', ' ', gridlove_get_option( 'additional_css' ) ) );
		if ( !empty( $additional_css ) ) {
			echo '<style type="text/css">'.$additional_css.'</style>';
		}

	}
endif;



/**
 * wp_footer callback
 *
 * Outputs additional JavaScript code from theme otpions
 *
 * @return void
 * @since  1.0
 */

add_action( 'wp_footer', 'gridlove_wp_footer', 99 );

if ( !function_exists( 'gridlove_wp_footer' ) ):
	function gridlove_wp_footer() {

		//Additional JS
		$additional_js = trim( preg_replace( '/\s+/', ' ', gridlove_get_option( 'additional_js' ) ) );
		if ( !empty( $additional_js ) ) {
			echo '<script type="text/javascript">
				/* <![CDATA[ */
					'.$additional_js.'
				/* ]]> */
				</script>';
		}

	}
endif;



/**
 * dynamic_sidebar_params callback
 *
 * Check if highlight option is selected and add gridlove-highlight class to a widget
 *
 * @return void
 * @since  1.0
 */

add_filter( 'dynamic_sidebar_params', 'gridlove_modify_widget_display' );

if ( !function_exists( 'gridlove_modify_widget_display' ) ) :

	function gridlove_modify_widget_display( $params ) {

		if ( strpos( $params[0]['id'], 'gridlove_footer_sidebar' ) !== false ) {
			return $params; //do not apply highlight styling for footer widgets
		}

		global $wp_registered_widgets;

		$widget_id              = $params[0]['widget_id'];
		$widget_obj             = $wp_registered_widgets[$widget_id];
		$widget_num             = $widget_obj['params'][0]['number'];
		$widget_opt = get_option( $widget_obj['callback'][0]->option_name );

		if ( isset( $widget_opt[$widget_num]['gridlove-highlight'] ) && $widget_opt[$widget_num]['gridlove-highlight'] == 1 ) {
			$params[0]['before_widget'] = preg_replace( '/class="/', "class=\"gridlove-highlight ", $params[0]['before_widget'], 1 );
		}

		return $params;

	}

endif;


/**
 * Include Hybrid Media Grabber Class
 *
 * Used for pulling out media from post content
 *
 * @return void
 * @since  1.0
 */

add_action( 'init', 'gridlove_add_media_grabber' );

if ( !function_exists( 'gridlove_add_media_grabber' ) ):
	function gridlove_add_media_grabber() {
		if ( !class_exists( 'Hybrid_Media_Grabber' ) ) {
			include_once get_template_directory() .'/inc/media-grabber/class-hybrid-media-grabber.php';
		}
	}
endif;



/**
 * widgets_init callback
 *
 * Used to unregister widgets
 *
 * @return void
 * @since  1.0
 */

add_action( 'widgets_init', 'gridlove_unregister_widgets', 99 );

if ( !function_exists( 'gridlove_unregister_widgets' ) ):
	function gridlove_unregister_widgets() {

		$widgets = array( 'EV_Widget_Entry_Views' );

		//Allow child themes or plugins to add/remove widgets they want to unregister
		$widgets = apply_filters( 'gridlove_modify_unregister_widgets', $widgets );

		if ( !empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				unregister_widget( $widget );
			}
		}

	}
endif;



/**
 * Remove entry views support for other post types,
 * we need post support only
 *
 * @return void
 * @since  1.0
 */

add_action( 'init', 'gridlove_remove_entry_views_support', 99 );

if ( !function_exists( 'gridlove_remove_entry_views_support' ) ):
	function gridlove_remove_entry_views_support() {

		$types = array( 'page', 'attachment', 'literature', 'portfolio_item', 'recipe', 'restaurant_item' );

		//Allow child themes or plugins to modify entry views support
		$widgets = apply_filters( 'gridlove_modify_entry_views_support', $types );

		if ( !empty( $types ) ) {
			foreach ( $types as $type ) {
				remove_post_type_support( $type, 'entry-views' );
			}
		}

	}
endif;



/**
 * Prevent redirect issue that may brake home page pagination caused by some plugins
 *
 * @return void
 * @since  1.0
 */

add_filter( 'redirect_canonical', 'gridlove_disable_redirect_canonical' );

function gridlove_disable_redirect_canonical( $redirect_url ) {
	if ( is_page_template( 'template-modules.php' ) && is_paged() ) {
		$redirect_url = false;
	}
	return $redirect_url;
}






/**
 * pre_get_posts filter callback
 *
 * If a user select custom number of posts per specific archive
 * template, override default post per page value
 *
 * @since  1.0
 */

add_action( 'pre_get_posts', 'gridlove_pre_get_posts' );

if ( !function_exists( 'gridlove_pre_get_posts' ) ):
	function gridlove_pre_get_posts( $query ) {



		if ( !is_admin() && $query->is_main_query() && ( $query->is_archive() || $query->is_search() || $query->is_posts_page ) && !$query->is_feed() ) {

			$template = gridlove_detect_template();
			$archive_ads = gridlove_get_archive_ad();

			//Get posts per page
			$ppp = gridlove_get_option( $template.'_ppp' ) == 'custom' ? gridlove_get_option( $template.'_ppp_num' ) : get_option( 'posts_per_page' );
			$query->set( 'posts_per_page', absint( $ppp ) );

			//Check for category template options
			if ( $template == 'category' ) {

				$cat_id = get_queried_object_id();
				$meta = gridlove_get_category_meta( $cat_id, 'layout' );

				if ( isset( $meta['type'] ) && $meta['type'] != 'inherit' ) {
					$cover_layout = $meta['cover'];
					$combo_layout = $meta['combo'];
					$ppp = $meta['main_ppp'];
					$query->set( 'posts_per_page', absint( $ppp ) );
				} else {
					$cover_layout = gridlove_get_option( 'category_cover_layout' );
					$combo_layout = gridlove_get_option( 'category_combo' ) ? gridlove_get_option( 'category_combo_layout' ) : 'none';
				}

				if ( gridlove_get_option( 'category_cover_unique' ) &&  $cover_layout != 'none' ) {

					$cover = gridlove_get_category_cover_query();

					if ( !empty( $cover ) && isset( $cover->posts ) ) {

						$exclude_ids = array();

						foreach ( $cover->posts as $p ) {
							$exclude_ids[] = $p->ID;
						}

						$query->set( 'post__not_in', $exclude_ids );

					}

					wp_reset_postdata();
				}

				//Set posts per page
				$query->set( 'posts_per_page', absint( $ppp ) );

				//Check if combo layout exists
				if ( $combo_layout != 'none' ) {

					$offset = count( gridlove_parse_layout_params( $combo_layout, 'combo' ) );

					if ( $query->is_paged ) {
						$offset = $offset + ( ( $query->query_vars['paged'] - 1 ) * $ppp );
						$query->set( 'offset', $offset );
					} else {
						$query->set( 'posts_per_page', absint( $ppp ) + $offset );
					}

				}
				
				if ( $archive_ads  ) {
					$query->set( 'posts_per_page', absint( $ppp ) + $offset - 1 );
				}
				
			} else {

				if ( $archive_ads  ) {
					$query->set( 'posts_per_page', absint( $ppp ) - 1 );
				}
			}

		}

	}
endif;


/**
 * found_posts filter callback
 *
 * If a user selects combo layout,
 * add offset for that specific number of posts to ensure correct pagination
 *
 * @since  1.0
 */

add_filter( 'found_posts', 'gridlove_found_posts', 10, 2 );

if ( !function_exists( 'gridlove_found_posts' ) ):
	function gridlove_found_posts( $found_posts, $query ) {

		if ( !is_admin() && $query->is_main_query() && $query->is_category() ) {

			$cat_id = get_queried_object_id();
			$meta = gridlove_get_category_meta( $cat_id, 'layout' );

			if ( isset( $meta['type'] ) && $meta['type'] != 'inherit' ) {
				$combo_layout = $meta['combo'];
			} else {
				$combo_layout = gridlove_get_option( 'category_combo' ) ? gridlove_get_option( 'category_combo_layout' ) : 'none';
			}

			if ( $combo_layout != 'none' ) {

				$offset = count( gridlove_parse_layout_params( $combo_layout, 'combo' ) );

				if ( $offset ) {

					if ( $query->is_paged ) {

						return $found_posts - $offset;

					} else {

						$real_ppp = $query->query_vars['posts_per_page'] - $offset;
						
						if( $real_ppp ){
							$pages = ( ( $found_posts - $query->query_vars['posts_per_page'] ) / $real_ppp  ) + 1;
							$found_posts = $pages * $query->query_vars['posts_per_page'];
						}

						return $found_posts;
					}
				}
			}

		}

		return $found_posts;
	}
endif;



/**
 * Add class to gallery images to enable pop-up and change image sizes
 *
 * @since  1.0
 */

add_filter( 'shortcode_atts_gallery', 'gridlove_gallery_atts', 10, 3 );

if ( !function_exists( 'gridlove_gallery_atts' ) ):
	function gridlove_gallery_atts( $output, $pairs, $atts ) {

		$atts['link'] = 'file';
		$output['link'] = 'file';
		add_filter( 'wp_get_attachment_link', 'gridlove_add_class_attachment_link', 10, 1 );

		if ( !isset( $output['columns'] ) ) {
			$output['columns'] = 1;
		}

		switch ( $output['columns'] ) {
		case '1' : $output['size'] = 'gridlove-single'; break;
		case '2' : $output['size'] = 'gridlove-single'; break;
		case '3' : $output['size'] = 'gridlove-single'; break;
		case '4' : $output['size'] = 'gridlove-single'; break;
		case '5' :
		case '6' : $output['size'] = 'gridlove-single'; break;
		case '7' :
		case '8' :
		case '9' : $output['size'] = 'gridlove-single'; break;
		default: $output['size'] = 'gridlove-single'; break;
		}

		return $output;
	}
endif;

if ( !function_exists( 'gridlove_add_class_attachment_link' ) ):
	function gridlove_add_class_attachment_link( $link ) {
		$link = str_replace( '<a', '<a class="gridlove-popup"', $link );
		return $link;
	}
endif;


/**
 * Filter Function to add class to linked media image for popup
 *
 * @return   $content
 */

add_filter( 'the_content', 'gridlove_popup_media_in_content', 100, 1 );

if ( !function_exists( 'gridlove_popup_media_in_content' ) ):
	function gridlove_popup_media_in_content( $content ) {

		if ( gridlove_get_option( 'on_single_img_popup' ) ) {

			$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")>/i";
			$replacement = '<a$1class="gridlove-popup-img" href=$2$3.$4$5>';
			$content = preg_replace( $pattern, $replacement, $content );
			return $content;
		}

		return  $content;
	}
endif;


/**
 * Modify WooCommerce wrappers
 *
 * Provide support for WooCommerce pages to match theme HTML markup
 *
 * @return HTML output
 * @since  1.2
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'gridlove_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'gridlove_woocommerce_wrapper_end', 10 );

if ( !function_exists( 'gridlove_woocommerce_wrapper_start' ) ):
	function gridlove_woocommerce_wrapper_start() {
		$sidebar = gridlove_get_current_sidebar();
		echo '<div id="content" class="gridlove-site-content container gridlove-sidebar-'.esc_attr( $sidebar['position']).'"><div class="row"><div class="gridlove-content"><div class="gridlove-box box-vm box-inner-p">';
	}
endif;

if ( !function_exists( 'gridlove_woocommerce_wrapper_end' ) ):
	function gridlove_woocommerce_wrapper_end() {
		echo '</div></div>';
	}
endif;

add_action( 'gridlove_before_end_content', 'gridlove_woocommerce_close_wrap' );

if ( !function_exists( 'gridlove_woocommerce_close_wrap' ) ):
	function gridlove_woocommerce_close_wrap() {
		if ( gridlove_is_woocommerce_active() && gridlove_is_woocommerce_page() ) {
			echo '</div></div>';
		}
	}
endif;

/**
 * Remove Yet Another Related Posts Plugin (YARPP) or Contextual Related Posts or WordPress Related Posts filter at the end of content
 *
 * @since 1.6
 */
add_action('init' , 'gridlove_remove_related_plugins_actions', 999);
if ( !function_exists( 'gridlove_remove_related_plugins_actions' ) ):
    function gridlove_remove_related_plugins_actions(){
        if(gridlove_get_option( 'single_related_using' ) == 'yarpp'){
            global $yarpp;
            remove_filter('the_content', array($yarpp, 'the_content'), 1200);
        }

        if(gridlove_get_option( 'single_related_using' ) == 'crp'){
            remove_action( 'template_redirect', 'crp_content_prepare_filter' );
        }

        if(gridlove_get_option( 'single_related_using' ) == 'wrpr'){
        	if (!is_user_logged_in()) {        		
            	remove_filter('the_content', 'wp_rp_add_related_posts_hook', 10);
        	}
        }

        if(gridlove_get_option( 'single_related_using' ) == 'jetpack'){
            add_filter('jetpack_relatedposts_filter_enabled_for_request', '__return_false');
        }
    }
endif;

add_action('admin_bar_menu', 'gridlove_add_frontend_adminbar_theme_options_links', 100);
/**
 * Add Theme options links to adminbar on frontend
 *
 * @param WP_Admin_Bar $admin_bar
 * @return WP_Admin_Bar
 * @since  1.8
 */
if(!function_exists('gridlove_add_frontend_adminbar_theme_options_links')):
    function gridlove_add_frontend_adminbar_theme_options_links($admin_bar){
        if(is_admin() || !current_user_can('manage_options')){
            return $admin_bar;
        }

        $admin_bar->add_menu( array(
            'id'    => 'wp-admin-bar-gridlove_options',
            'title' => '<span class="ab-icon dashicons-admin-generic"></span>' . __('Theme Options', 'gridlove'),
            'href'  => admin_url('admin.php?page=gridlove_options&tab=1'),
            'meta'  => array(
                'title' => __('Theme Options', 'gridlove'),
                'target' => '_blank',
            ),
        ));

        return $admin_bar;
    }
endif;




/**
 * Add comment form default fields args filter 
 * to replace comment fields labels
 */

add_filter('comment_form_default_fields', 'gridlove_comment_fields_labels');

if(!function_exists('gridlove_comment_fields_labels')):
function gridlove_comment_fields_labels($fields){

	$replace = array(
		'author' => array(
			'old' => __( 'Name' ),
			'new' =>__gridlove( 'comment_name' )
		),
		'email' => array(
			'old' => __( 'Email' ),
			'new' =>__gridlove( 'comment_email' )
		),
		'url' => array(
			'old' => __( 'Website' ),
			'new' =>__gridlove( 'comment_website' )
		),

		'cookies' => array(
			'old' => __( 'Save my name, email, and website in this browser for the next time I comment.' ),
			'new' =>__gridlove( 'comment_cookie_gdpr' )
		)
	);

	foreach($fields as $key => $field){

		if(array_key_exists($key, $replace)){
			$fields[$key] = str_replace($replace[$key]['old'], $replace[$key]['new'], $fields[$key]);
		}

	}
	
	return $fields;

}

endif;

?>
