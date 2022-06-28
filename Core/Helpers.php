<?php


/**
 * Debug (log) function
 *
 * Outputs any content into log file in theme root directory
 *
 * @param mixed   $mixed Content to output
 * @return void
 * @since  1.0
 */

if ( !function_exists( 'gridlove_log' ) ):
	function gridlove_log( $mixed ) {

		WP_Filesystem();
		global $wp_filesystem;

		if ( is_array( $mixed ) ) {
			$mixed = print_r( $mixed, 1 );
		} else if ( is_object( $mixed ) ) {
				ob_start();
				var_dump( $mixed );
				$mixed = ob_get_clean();
			}

		$old = $wp_filesystem->get_contents( get_template_directory() . '/log' );
		$wp_filesystem->put_contents( get_template_directory() . '/log', $old.$mixed . PHP_EOL, FS_CHMOD_FILE );
	}
endif;



/**
 * Get option value from theme options
 *
 * A wrapper function for WordPress native get_option()
 * which gets an option from specific option key (set in theme options panel)
 *
 * @param string  $option Name of the option
 * @return mixed Specific option value or "false" (if option is not found)
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_option' ) ):
	function gridlove_get_option( $option ) {

		global $gridlove_settings;

		if ( empty( $gridlove_settings ) ) {
			$gridlove_settings = get_option( 'gridlove_settings' );
		}

		if ( isset( $gridlove_settings[$option] ) ) {
			return is_array( $gridlove_settings[$option] ) && isset( $gridlove_settings[$option]['url'] ) ? $gridlove_settings[$option]['url'] : $gridlove_settings[$option];
		} else {
			return false;
		}

	}
endif;


/**
 * Get background
 *
 * @return string background CSS
 * @since  1.4
 */

if ( !function_exists( 'gridlove_get_bg_option' ) ):
	function gridlove_get_bg_option( $option = false ) {

		$style = gridlove_get_option( $option );
		$css = '';

		if ( ! empty( $style ) && is_array( $style ) ) {
			foreach ( $style as $key => $value ) {
				if ( ! empty( $value ) && $key != "media" ) {
					if ( $key == "background-image" ) {
						$css .= $key . ":url('" . $value . "');";
					} else {
						$css .= $key . ":" . $value . ";";
					}
				}
			}
		}

		return $css;
	}
endif;


/**
 * Get post meta data
 *
 * @param unknown $field specific option key
 * @return mixed meta data value or set of values
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_post_meta' ) ):
	function gridlove_get_post_meta( $post_id = false, $field = false ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$defaults = array(
			'layout' => 'inherit',
			'sidebar' => array(
				'position' => 'inherit',
				'standard'  => 'inherit',
				'sticky'  => 'inherit',
			),
			'display' => array(
				'fimg' => 'inherit',
				'headline' => 'inherit',
				'tags' => 'inherit',
				'author' => 'inherit',
				'related' => 'inherit',
				'prev_next' => 'inherit',
				'ad_above' => 1,
				'ad_below'	=> 1
			)
		);

		$meta = get_post_meta( $post_id, '_gridlove_meta', true );
		$meta = gridlove_parse_args( $meta, $defaults );


		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;


/**
 * Get page meta data
 *
 * @param unknown $field specific option key
 * @return mixed meta data value or set of values
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_page_meta' ) ):
	function gridlove_get_page_meta( $post_id = false, $field = false ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$defaults = array(
			'sidebar' => array(
				'position' => 'inherit',
				'standard' => 'inherit',
				'sticky'   => 'inherit',
			),
			'layout'  => 'inherit',
			'modules' => array(),
			'pagination' => 'none',
			'authors' => array(
				'orderby' => 'post_count',
				'order'   => 'DESC',
				'exclude' => '',
				'roles'   => array(),
			),
			'cover' => array(
				'post_type'   => 'post',
				'layout'      => '1',
				'limit'       => 5,
				'cat'         => array(),
				'cat_child'   => 0,
				'tag'         => array(),
				'manual'      => array(),
				'time'        => 0,
				'order'       => 'date',
				'format'      => 0,
				'unique'      => 0,
				'sort'        => 'DESC',
				'content'     => '',
				'bg_image'    => '',
				'cat_inc_exc' => 'in',
				'tag_inc_exc' => 'in',
			),
		);

		$meta = get_post_meta( $post_id, '_gridlove_meta', true );
		$meta = gridlove_parse_args( $meta, $defaults );

		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;


/**
 * Get category meta data
 *
 * @param unknown $field specific option key
 * @return mixed meta data value or set of values
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_category_meta' ) ):
	function gridlove_get_category_meta( $cat_id = false, $field = false ) {
		$defaults = array(
			'color' => array( 
				'type' => 'inherit', 
				'value' => gridlove_get_option( 'color_content_acc' )
			),
			'layout' => array(
				'type' => 'inherit',
				'cover' => 'none',
				'cover_ppp' => 3,
				'posts_layout_type' => 'main',
				'main' => '1',
				'masonry' => '1',
				'main_ppp' => 6,
				'combo' => 'none',
				'pagination' => 'load-more'
			), 
			'image' => ''
		);

		if ( $cat_id ) {
			$meta = get_term_meta( $cat_id, '_gridlove_meta', true );
			$meta = gridlove_parse_args( $meta, $defaults );
		} else {
			$meta = $defaults;
		}

		if ( $field ) {
			if ( isset( $meta[$field] ) ) {
				return $meta[$field];
			} else {
				return false;
			}
		}

		return $meta;
	}
endif;




/**
 * Get post format
 *
 * Checks format of current post and possibly modify it based on specific options
 *
 * @return string Format value
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_post_format' ) ):
	function gridlove_get_post_format() {

		$format = get_post_format();

		if ( empty( $format ) ) {

			$format = 'standard';
		}

		return $format;
	}
endif;



/**
 * Calculate time difference
 *
 * @param string  $timestring String to calculate difference from
 * @return  int Time difference in miliseconds
 * @since  1.0
 */

if ( !function_exists( 'gridlove_calculate_time_diff' ) ) :
	function gridlove_calculate_time_diff( $timestring ) {

		$now = current_time( 'timestamp' );

		switch ( $timestring ) {
		case '-1 day' : $time = $now - DAY_IN_SECONDS; break;
		case '-3 days' : $time = $now - ( 3 * DAY_IN_SECONDS ); break;
		case '-1 week' : $time = $now - WEEK_IN_SECONDS; break;
		case '-1 month' : $time = $now - ( YEAR_IN_SECONDS / 12 ); break;
		case '-3 months' : $time = $now - ( 3 * YEAR_IN_SECONDS / 12 ); break;
		case '-6 months' : $time = $now - ( 6 * YEAR_IN_SECONDS / 12 ); break;
		case '-1 year' : $time = $now - ( YEAR_IN_SECONDS ); break;
		default : $time = $now;
		}

		return $time;
	}
endif;



/**
 *  Create additional image sizes
 *
 * @return  array List of image size parameters
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_image_sizes' ) ):
	function gridlove_get_image_sizes() {

		//Check if user has disabled to generate particular image sizes from theme options
		$disabled_img_sizes = (array) gridlove_get_option( 'disable_img_sizes' );
		$disabled_img_sizes = array_keys( array_filter( $disabled_img_sizes ) );

		//print_r($disabled_img_sizes);

		$sizes = array();

		if ( !in_array( 'a', $disabled_img_sizes ) ) {
			$sizes['gridlove-a4'] = array( 'title' => 'A4', 'w' => 370 , 'h' => 150 , 'crop' => true );
			$sizes['gridlove-a4-orig'] = array( 'title' => 'A4 Original', 'w' => 370 , 'h' => 9999 , 'crop' => false );
		}

		if ( !in_array( 'b', $disabled_img_sizes ) ) {
			$sizes['gridlove-b6'] = array( 'title' => 'B6', 'w' => 285 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-b7'] = array( 'title' => 'B7', 'w' => 335 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-b8'] = array( 'title' => 'B8', 'w' => 385 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-b9'] = array( 'title' => 'B9', 'w' => 435 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-b12'] = array( 'title' => 'B12', 'w' => 585 , 'h' => 300 , 'crop' => true );
		}

		if ( !in_array( 'd', $disabled_img_sizes ) ) {
			$sizes['gridlove-d3'] = array( 'title' => 'D3', 'w' => 270 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-d3-orig'] = array( 'title' => 'D3 Original', 'w' => 270 , 'h' => 9999 , 'crop' => false );
			$sizes['gridlove-d4'] = array( 'title' => 'D4', 'w' => 370 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-d4-orig'] = array( 'title' => 'D4 Original', 'w' => 370 , 'h' => 9999 , 'crop' => false );
			$sizes['gridlove-d5'] = array( 'title' => 'D5', 'w' => 470 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-d6'] = array( 'title' => 'D6', 'w' => 570 , 'h' => 300 , 'crop' => true );
			$sizes['gridlove-d6-orig'] = array( 'title' => 'D6 Original', 'w' => 570 , 'h' => 9999 , 'crop' => false );
		}

		if ( !in_array( 'cover', $disabled_img_sizes ) ) {

			if ( gridlove_get_option( 'cover_type' ) == 'fixed' ) {
				$width = absint( gridlove_get_option( 'cover_w' ) );
				$crop = true;
			} else {
				$width = 999999;
				$crop = false;
			}

			$sizes['gridlove-cover'] = array( 'title' => 'Cover', 'w' => $width, 'h' => absint( gridlove_get_option( 'cover_h' ) ), 'crop' => $crop );
		}

		if ( !in_array( 'single', $disabled_img_sizes ) ) {
			$sizes['gridlove-single'] = array( 'title' => 'Single', 'w' => 740 , 'h' => 9999 , 'crop' => false );
		}

		$sizes['gridlove-thumbnail'] = array( 'title' => 'Thumbnail', 'w' => 80 , 'h' => 60 , 'crop' => true );

		//Allow child themes or plugins to modify sizes
		$sizes = apply_filters( 'gridlove_modify_image_sizes', $sizes );

		//print_r( $sizes );

		return $sizes;
	}
endif;


/**
 * Check if RTL mode is enabled
 *
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'gridlove_is_rtl' ) ):
	function gridlove_is_rtl() {

		if ( gridlove_get_option( 'rtl_mode' ) ) {
			$rtl = true;
			//Check if current language is excluded from RTL
			$rtl_lang_skip = explode( ",", gridlove_get_option( 'rtl_lang_skip' ) );
			if ( !empty( $rtl_lang_skip )  ) {
				$locale = get_locale();
				if ( in_array( $locale, $rtl_lang_skip ) ) {
					$rtl = false;
				}
			}
		} else {
			$rtl = false;
		}

		return $rtl;
	}
endif;


/**
 * Detect WordPress template
 *
 * It checks which template is currently active
 * so we know what set of options to load later
 *
 * @return string Template name prefix we use in options panel
 * @since  1.0
 */

if ( !function_exists( 'gridlove_detect_template' ) ):
	function gridlove_detect_template() {

		global $gridlove_current_template;

		if ( !empty( $gridlove_current_template ) ) {
			return $gridlove_current_template;
		}

		if ( is_single() ) {

			$type = get_post_type( get_the_ID() );

			if ( in_array( $type, array( 'product', 'forum', 'topic' ) ) ) {
				$template = $type;
			} else {
				$template = 'single';
			}

		} else if ( is_page_template( 'template-modules.php' ) && is_page() ) {
				$template = 'modules';
			} else if ( is_page() ) {
				$template = 'page';
			} else if ( is_category() ) {
				$template = 'category';
			} else if ( is_tag() ) {
				$template = 'tag';
			} else if ( is_search() ) {
				$template = 'search';
			} else if ( is_author() ) {
				$template = 'author';
			} else if ( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_post_type_archive( 'product' ) ) {
				$template = 'product_archive';
			} else if ( is_archive() ) {
				$template = 'archive';
			} else {
			$template = 'archive'; //default
		}

		$gridlove_current_template = $template;

		return $template;
	}
endif;


/**
 * Get image ID from URL
 *
 * It gets image/attachment ID based on URL
 *
 * @param string  $image_url URL of image/attachment
 * @return int|bool Attachment ID or "false" if not found
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_image_id_by_url' ) ):
	function gridlove_get_image_id_by_url( $image_url ) {
		global $wpdb;

		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );

		if ( isset( $attachment[0] ) ) {
			return $attachment[0];
		}

		return false;
	}
endif;


/**
 * Calculate reading time by content length
 *
 * @param string  $text Content to calculate
 * @return int Number of minutes
 * @since  1.0
 */

if ( !function_exists( 'gridlove_read_time' ) ):
	function gridlove_read_time( $text ) {

		$words = count( preg_split( "/[\n\r\t ]+/", wp_strip_all_tags( $text ) ) );
		$number_words_per_minute = gridlove_get_option('words_read_per_minute');
		$number_words_per_minute = !empty($number_words_per_minute) ? absint( $number_words_per_minute ) : 200;

		if ( !empty( $words ) ) {
			$time_in_minutes = ceil( $words / $number_words_per_minute );
			return $time_in_minutes;
		}

		return false;
	}
endif;


/**
 * Trim chars of a string
 *
 * @param string  $string Content to trim
 * @param int     $limit  Number of characters to limit
 * @param string  $more   Chars to append after trimed string
 * @return string Trimmed part of the string
 * @since  1.0
 */

if ( !function_exists( 'gridlove_trim_chars' ) ):
	function gridlove_trim_chars( $string, $limit, $more = '...' ) {

		if ( !empty( $limit ) ) {

			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $string ), ' ' );
			preg_match_all( '/./u', $text, $chars );
			$chars = $chars[0];
			$count = count( $chars );

			if ( $count > $limit ) {

				$chars = array_slice( $chars, 0, $limit );

				for ( $i = ( $limit -1 ); $i >= 0; $i-- ) {
					if ( in_array( $chars[$i], array( '.', ' ', '-', '?', '!' ) ) ) {
						break;
					}
				}

				$chars =  array_slice( $chars, 0, $i );
				$string = implode( '', $chars );
				$string = rtrim( $string, ".,-?!" );
				$string.= $more;
			}

		}

		return $string;
	}
endif;


/**
 * Parse args ( merge arrays )
 *
 * Similar to wp_parse_args() but extended to also merge multidimensional arrays
 *
 * @param array   $a - set of values to merge
 * @param array   $b - set of default values
 * @return array Merged set of elements
 * @since  1.0
 */

if ( !function_exists( 'gridlove_parse_args' ) ):
	function gridlove_parse_args( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$r = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $r[ $k ] ) ) {
				$r[ $k ] = gridlove_parse_args( $v, $r[ $k ] );
			} else {
				$r[ $k ] = $v;
			}
		}
		return $r;
	}
endif;


/**
 * Compare two values
 *
 * Fucntion compares two values and sanitazes 0
 *
 * @param mixed   $a
 * @param mixed   $b
 * @return bool Returns true if equal
 * @since  1.0
 */

if ( !function_exists( 'gridlove_compare' ) ):
	function gridlove_compare( $a, $b ) {
		return (string) $a === (string) $b;
	}
endif;



/**
 * Hex 2 rgba
 *
 * Convert hexadecimal color to rgba
 *
 * @param string  $color   Hexadecimal color value
 * @param float   $opacity Opacity value
 * @return string RGBA color value
 * @since  1.0
 */

if ( !function_exists( 'gridlove_hex2rgba' ) ):
	function gridlove_hex2rgba( $color, $opacity = false ) {
		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if ( empty( $color ) )
			return $default;

		//Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb =  array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) { $opacity = 1.0; }
			$output = 'rgba('.implode( ",", $rgb ).','.$opacity.')';
		} else {
			$output = 'rgb('.implode( ",", $rgb ).')';
		}

		//Return rgb(a) color string
		return $output;
	}
endif;


/**
 * Get list of social options
 *
 * Used for user social profiles
 *
 * @return array
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_social' ) ) :
	function gridlove_get_social() {
		$social = array(
			'behance' => 'Behance',
			'delicious' => 'Delicious',
			'deviantart' => 'DeviantArt',
			'digg' => 'Digg',
			'dribbble' => 'Dribbble',
			'facebook' => 'Facebook',
			'flickr' => 'Flickr',
			'github' => 'Github',
			'google' => 'GooglePlus',
			'instagram' => 'Instagram',
			'linkedin' => 'LinkedIN',
			'pinterest' => 'Pinterest',
			'reddit' => 'ReddIT',
			'rss' => 'Rss',
			'skype' => 'Skype',
			'snapchat' => 'Snapchat',
			'slack' => 'Slack',
			'stumbleupon' => 'StumbleUpon',
			'soundcloud' => 'SoundCloud',
			'spotify' => 'Spotify',
			'tumblr' => 'Tumblr',
			'twitter' => 'Twitter',
			'vimeo-square' => 'Vimeo',
			'vk' => 'vKontakte',
			'vine' => 'Vine',
			'weibo' => 'Weibo',
			'wordpress' => 'WordPress',
			'xing' => 'Xing' ,
			'yahoo' => 'Yahoo',
			'youtube' => 'Youtube'
		);

		return $social;
	}
endif;


/**
 * Generate dynamic css
 *
 * Function parses theme options and generates css code dynamically
 *
 * @return string Generated css code
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_dynamic_css' ) ):
	function gridlove_generate_dynamic_css() {
		ob_start();
		get_template_part( 'assets/css/dynamic-css' );
		$output = ob_get_contents();
		ob_end_clean();
		return gridlove_compress_css_code( $output );
	}
endif;


/**
 * Compress CSS Code
 *
 * @param string  $code Uncompressed css code
 * @return string Compressed css code
 * @since  1.0
 */

if ( !function_exists( 'gridlove_compress_css_code' ) ) :
	function gridlove_compress_css_code( $code ) {

		// Remove Comments
		$code = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code );

		// Remove tabs, spaces, newlines, etc.
		$code = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $code );

		return $code;
	}
endif;


/**
 * Get JS settings
 *
 * Function creates list of settings from thme options to pass
 * them to global JS variable so we can use it in JS files
 *
 * @return array List of JS settings
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_js_settings' ) ):
	function gridlove_get_js_settings() {
		$js_settings = array();

		$js_settings['rtl_mode'] = gridlove_is_rtl() ? true : false;
		$js_settings['header_sticky'] = gridlove_get_option( 'header_sticky' ) ? true : false;
		$js_settings['header_sticky_offset'] = absint( gridlove_get_option( 'header_sticky_offset' ) );
		$js_settings['header_sticky_up'] = gridlove_get_option( 'header_sticky_up' ) ? true : false;
		$js_settings['logo'] = gridlove_get_option( 'logo' );
		$js_settings['logo_retina'] = gridlove_get_option( 'logo_retina' );
		$js_settings['logo_mini'] = gridlove_get_option( 'logo_mini' );
		$js_settings['logo_mini_retina'] = gridlove_get_option( 'logo_mini_retina' );
		$js_settings['gridlove_gallery'] = gridlove_get_option( 'gridlove_bultin_gallery' ) != 0 ? true : false;
		$js_settings['responsive_secondary_nav'] = gridlove_get_option( 'responsive_secondary_nav' ) ? true : false;
		$js_settings['responsive_more_link'] = gridlove_get_option( 'responsive_more_link' ) ? __gridlove('responsive_more_link') : false;
		$js_settings['responsive_social_nav'] = gridlove_get_option( 'responsive_social_nav' ) ? true : false;

		return $js_settings;
	}
endif;


/**
 * Get all translation options
 *
 * @return array Returns list of all translation strings available in theme options panel
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_translate_options' ) ):
	function gridlove_get_translate_options() {
		global $gridlove_translate;
		get_template_part( 'core/translate' );
		$translate = apply_filters( 'gridlove_modify_translate_options', $gridlove_translate );
		return $translate;
	}
endif;


/**
 * Sort option items
 *
 * Use this function to properly order sortable options
 *
 * @param unknown $items    Array of items
 * @param unknown $selected Array of IDs of currently selected items
 * @return array ordered items
 * @since  1.0
 */

if ( !function_exists( 'gridlove_sort_option_items' ) ):
	function gridlove_sort_option_items( $items, $selected, $field = 'term_id' ) {

		if ( empty( $selected ) ) {
			return $items;
		}

		$new_items = array();
		$temp_items = array();
		$temp_items_ids = array();

		foreach ( $selected as $selected_item_id ) {

			foreach ( $items as $item ) {
				if ( $selected_item_id == $item->$field ) {
					$new_items[] = $item;
				} else {
					if ( !in_array( $item->$field, $selected ) && !in_array( $item->$field, $temp_items_ids ) ) {
						$temp_items[] = $item;
						$temp_items_ids[] = $item->$field;
					}
				}
			}

		}

		$new_items = array_merge( $new_items, $temp_items );

		return $new_items;
	}
endif;


/**
 * Generate fonts link
 *
 * Function creates font link from fonts selected in theme options
 *
 * @return string
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_fonts_link' ) ):
	function gridlove_generate_fonts_link() {

		$fonts = array();
		$fonts[] = gridlove_get_option( 'main_font' );
		$fonts[] = gridlove_get_option( 'h_font' );
		$fonts[] = gridlove_get_option( 'nav_font' );
		$unique = array(); //do not add same font links
		$native = gridlove_get_native_fonts();
		$protocol = is_ssl() ? 'https://' : 'http://';
		$link = array();

		foreach ( $fonts as $font ) {
			if ( !in_array( $font['font-family'], $native ) ) {
				$temp = array();
				if ( isset( $font['font-style'] ) ) {
					$temp['font-style'] = $font['font-style'];
				}
				if ( isset( $font['subsets'] ) ) {
					$temp['subsets'] = $font['subsets'];
				}
				if ( isset( $font['font-weight'] ) ) {
					$temp['font-weight'] = $font['font-weight'];
				}
				$unique[$font['font-family']][] = $temp;
			}
		}

		$subsets = array( 'latin' ); //latin as default

		foreach ( $unique as $family => $items ) {

			$link[$family] = $family;

			$weight = array( '400' );

			foreach ( $items as $item ) {

				//Check weight and style
				if ( isset( $item['font-weight'] ) && !empty( $item['font-weight'] ) ) {
					$temp = $item['font-weight'];
					if ( isset( $item['font-style'] ) && empty( $item['font-style'] ) ) {
						$temp .= $item['font-style'];
					}

					if ( !in_array( $temp, $weight ) ) {
						$weight[] = $temp;
					}
				}

				//Check subsets
				if ( isset( $item['subsets'] ) && !empty( $item['subsets'] ) ) {
					if ( !in_array( $item['subsets'], $subsets ) ) {
						$subsets[] = $item['subsets'];
					}
				}
			}

			$link[$family] .= ':'.implode( ",", $weight );
			//$link[$family] .= '&subset='.implode( ",", $subsets );
		}

		if ( !empty( $link ) ) {

			$query_args = array(
				'family' => urlencode( implode( '|', $link ) ),
				'subset' => urlencode( implode( ',', $subsets ) )
			);


			$fonts_url = add_query_arg( $query_args, $protocol.'fonts.googleapis.com/css' );

			return esc_url_raw( $fonts_url );
		}

		return '';

	}
endif;


/**
 * Get native fonts
 *
 *
 * @return array List of native fonts
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_native_fonts' ) ):
	function gridlove_get_native_fonts() {

		$fonts = array(
			"Arial, Helvetica, sans-serif",
			"'Arial Black', Gadget, sans-serif",
			"'Bookman Old Style', serif",
			"'Comic Sans MS', cursive",
			"Courier, monospace",
			"Garamond, serif",
			"Georgia, serif",
			"Impact, Charcoal, sans-serif",
			"'Lucida Console', Monaco, monospace",
			"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
			"'MS Sans Serif', Geneva, sans-serif",
			"'MS Serif', 'New York', sans-serif",
			"'Palatino Linotype', 'Book Antiqua', Palatino, serif",
			"Tahoma,Geneva, sans-serif",
			"'Times New Roman', Times,serif",
			"'Trebuchet MS', Helvetica, sans-serif",
			"Verdana, Geneva, sans-serif"
		);

		return $fonts;
	}
endif;


/**
 * Get font option
 *
 * @return string Font-family
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_font_option' ) ):
	function gridlove_get_font_option( $option = false ) {

		$font = gridlove_get_option( $option );
		$native_fonts = gridlove_get_native_fonts();
		if ( !in_array( $font['font-family'], $native_fonts ) ) {
			$font['font-family'] = "'".$font['font-family']."'";
		}

		return $font;
	}
endif;


/**
 * Get background
 *
 * @return string background CSS
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_bg_option' ) ):
	function gridlove_get_bg_option( $option = false ) {

		$style = gridlove_get_option( $option );
		$css = '';

		if ( ! empty( $style ) && is_array( $style ) ) {
			foreach ( $style as $key => $value ) {
				if ( ! empty( $value ) && $key != "media" ) {
					if ( $key == "background-image" ) {
						$css .= $key . ":url('" . $value . "');";
					} else {
						$css .= $key . ":" . $value . ";";
					}
				}
			}
		}

		return $css;
	}
endif;


/**
 * Check if post/page is paginated
 *
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'gridlove_is_paginated_post' ) ):
	function gridlove_is_paginated_post() {

		global $multipage;
		return 0 !== $multipage;

	}
endif;



/**
 * Check if is first page of paginated post
 *
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'gridlove_is_paginated_post_first_page' ) ):
	function gridlove_is_paginated_post_first_page() {

		if ( !gridlove_is_paginated_post() ) {
			return false;
		}

		global $page;

		return $page === 1;

	}
endif;



/**
 * Get term slugs by term names for specific taxonomy
 *
 * @param string  $names List of tag names separated by comma
 * @param string  $tax   Taxonomy name
 * @return array List of slugs
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_tax_term_slug_by_name' ) ):
	function gridlove_get_tax_term_slug_by_name( $names, $tax = 'post_tag' ) {

		if ( empty( $names ) ) {
			return '';
		}

		$slugs = array();
		$names = explode( ",", $names );

		foreach ( $names as $name ) {
			$tag = get_term_by( 'name', trim( $name ), $tax );

			if ( !empty( $tag ) && isset( $tag->slug ) ) {
				$slugs[] = $tag->slug;
			}
		}

		return $slugs;

	}
endif;


/**
 * Get term names by term slugs for specific taxonomy
 *
 * @param array   $slugs List of tag slugs
 * @param string  $tax   Taxonomy name
 * @return string List of names separrated by comma
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_tax_term_name_by_slug' ) ):
	function gridlove_get_tax_term_name_by_slug( $slugs, $tax = 'post_tag' ) {

		if ( empty( $slugs ) ) {
			return '';
		}

		$names = array();

		foreach ( $slugs as $slug ) {
			$tag = get_term_by( 'slug', trim( $slug ), $tax );
			if ( !empty( $tag ) && isset( $tag->name ) ) {
				$names[] = $tag->name;
			}
		}

		if ( !empty( $names ) ) {
			$names = implode( ",", $names );
		} else {
			$names = '';
		}

		return $names;

	}
endif;



/**
 * Get related posts for particular post
 *
 * @param int     $post_id
 * @return object WP_Query
 * @since  1.6
 */
if ( !function_exists( 'gridlove_get_related_posts' ) ):
	function gridlove_get_related_posts( $post_id = false ) {

        if ( empty( $post_id ) ) {
            $post_id = get_the_ID();
        }
        switch (gridlove_get_option( 'single_related_using' )){
            case 'yarpp':
                if(!gridlove_is_yarpp_active()){
                    return false;
                    break;
                }

                $post_ids = wp_list_pluck(yarpp_get_related(array(), $post_id), 'ID');
                break;
            case 'crp':
                if(!gridlove_is_crp_active()){
                    return false;
                    break;
                }

                $post_ids = wp_list_pluck(get_crp_posts_id(), 'ID');
                break;
            case 'wrpr':
                if(!gridlove_is_wrpr_active()){
                    return false;
                    break;
                }
               	
               	$posts = wp_rp_fetch_posts_and_title();
                $selected_posts = wp_rp_get_selected_posts();
       
                $post_ids = wp_list_pluck($posts['posts'], 'ID');

                if ( !empty($selected_posts) ) {
					$wrpr_options = wp_rp_get_options();
					$limit = absint( $wrpr_options['max_related_posts'] );

                	$selected_posts = wp_list_pluck($selected_posts, 'ID');
                	$selected_post_ids = array(); 
                	
                	foreach ( $selected_posts as $id ) {
                		if (!empty($id)) {
                			$selected_post_ids[] = preg_replace('/in_/', '', $id );
                		}
                	}
                	$merge_ids = array_merge($selected_post_ids, $post_ids);
                	$post_ids = array_slice( $merge_ids, 0, $limit );
                }
                             
                break;
            case 'jetpack':
                if(!gridlove_is_jetpack_active() || !class_exists('Jetpack_RelatedPosts')){
                    return false;
                    break;
                }

                $jetpack = Jetpack_RelatedPosts::init();
                $post_ids = wp_list_pluck($jetpack->get_for_post_id($post_id, array()), 'id');
                break;
            case 'default':
            default:
                return gridlove_generate_default_related_query($post_id);
                break;
        }

        if(empty($post_ids))
            return false;

        return new WP_Query(array('post__in' => $post_ids));
	}
endif;

/**
 * Generate related posts query
 *
 * Depending on post ID generate related posts using theme options
 *
 * @param int     $post_id
 * @return object WP_Query
 * @since  1.0
 */
if ( !function_exists( 'gridlove_generate_default_related_query' ) ):
    function gridlove_generate_default_related_query($post_id){

        if ( empty( $post_id ) ) {
            $post_id = get_the_ID();
        }

        $args['post_type'] = 'post';

        //Exclude current post from query
        $args['post__not_in'] = array( $post_id );

        //If previuos next posts active exclude them too
        if ( gridlove_get_option( 'single_prevnext' ) ) {

            $prev_next = gridlove_get_prev_next_posts();

            if ( !empty( $prev_next['prev'] ) ) {
                $args['post__not_in'][] = $prev_next['prev']->ID;
            }

            if ( !empty( $prev_next['next'] ) ) {
                $args['post__not_in'][] = $prev_next['next']->ID;
            }
        }

        $num_posts = absint( gridlove_get_option( 'related_limit' ) );

        if ( $num_posts > 100 ) {
            $num_posts = 100;
        }

        $args['posts_per_page'] = $num_posts;


        $args['orderby'] = gridlove_get_option( 'related_order' );

        if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = ev_get_meta_key();
        }

        if ( $args['orderby'] == 'title' ) {
            $args['order'] = 'ASC';
        }

        if ( $time_diff = gridlove_get_option( 'related_time' ) ) {
            $args['date_query'] = array( 'after' => date( 'Y-m-d', gridlove_calculate_time_diff( $time_diff ) ) );
        }

        if ( $type = gridlove_get_option( 'related_type' ) ) {

            switch ( $type ) {

                case 'cat':
                    $cats = get_the_category( $post_id );
                    $cat_args = array();
                    if ( !empty( $cats ) ) {
                        foreach ( $cats as $k => $cat ) {
                            $cat_args[] = $cat->term_id;
                        }
                    }
                    $args['category__in'] = $cat_args;
                    break;

                case 'tag':
                    $tags = get_the_tags( $post_id );
                    $tag_args = array();
                    if ( !empty( $tags ) ) {
                        foreach ( $tags as $tag ) {
                            $tag_args[] = $tag->term_id;
                        }
                    }
                    $args['tag__in'] = $tag_args;
                    break;

                case 'cat_and_tag':
                    $cats = get_the_category( $post_id );
                    $cat_args = array();
                    if ( !empty( $cats ) ) {
                        foreach ( $cats as $k => $cat ) {
                            $cat_args[] = $cat->term_id;
                        }
                    }
                    $tags = get_the_tags( $post_id );
                    $tag_args = array();
                    if ( !empty( $tags ) ) {
                        foreach ( $tags as $tag ) {
                            $tag_args[] = $tag->term_id;
                        }
                    }
                    $args['tax_query'] = array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'category',
                            'field'    => 'id',
                            'terms'    => $cat_args,
                        ),
                        array(
                            'taxonomy' => 'post_tag',
                            'field'    => 'id',
                            'terms'    => $tag_args,
                        )
                    );
                    break;

                case 'cat_or_tag':
                    $cats = get_the_category( $post_id );
                    $cat_args = array();
                    if ( !empty( $cats ) ) {
                        foreach ( $cats as $k => $cat ) {
                            $cat_args[] = $cat->term_id;
                        }
                    }
                    $tags = get_the_tags( $post_id );
                    $tag_args = array();
                    if ( !empty( $tags ) ) {
                        foreach ( $tags as $tag ) {
                            $tag_args[] = $tag->term_id;
                        }
                    }
                    $args['tax_query'] = array(
                        'relation' => 'OR',
                        array(
                            'taxonomy' => 'category',
                            'field'    => 'id',
                            'terms'    => $cat_args,
                        ),
                        array(
                            'taxonomy' => 'post_tag',
                            'field'    => 'id',
                            'terms'    => $tag_args,
                        )
                    );
                    break;

                case 'author':
                    global $post;
                    $author_id = isset( $post->post_author ) ? $post->post_author : 0;
                    $args['author'] = $author_id;
                    break;

                case 'default':
                    break;
            }
        }


        $related_query = new WP_Query( $args );

        return $related_query;
    }
endif;


/**
 * Check if current post should be highlighted based on theme options
 *
 * @return string highlight css class or an empty string
 * @since  1.0
 */

if ( !function_exists( 'gridlove_highlight_post_class' ) ):
	function gridlove_highlight_post_class() {

		if ( gridlove_get_option( 'use_highlight' ) ) {

			$highlight_class = 'gridlove-highlight';

			//Category

			$highlight_cats = gridlove_get_option( 'highlight_cat' );

			if ( !empty( $highlight_cats ) ) {
				$cats = get_the_category();
				if ( !empty( $cats ) ) {
					foreach ( $cats as $k => $cat ) {
						if ( in_array( $cat->term_id, $highlight_cats ) ) {

							if ( is_category() ) {
								$obj = get_queried_object();

								if ( $cat->term_id == $obj->term_id ) {
									continue; //skip
								}
							}

							return $highlight_class;
						}
					}
				}
			}

			//Tag

			$highlight_tags = gridlove_get_option( 'highlight_tag' );

			if ( !empty( $highlight_tags ) ) {
				$tags = get_the_tags();
				if ( !empty( $tags ) ) {
					foreach ( $tags as $k => $tag ) {
						if ( in_array( $tag->term_id, $highlight_tags ) ) {

							if ( is_tag() ) {
								$obj = get_queried_object();

								if ( $tag->term_id == $obj->term_id ) {
									continue; //skip
								}
							}

							return $highlight_class;
						}
					}
				}
			}

			//Comments

			$highlight_comments = gridlove_get_option( 'highlight_comments' );

			if ( !empty( $highlight_comments ) ) {
				if ( get_comments_number() >= $highlight_comments ) {
					return $highlight_class;
				}
			}

			//Views

			$highlight_views = gridlove_get_option( 'highlight_views' );

			if ( !empty( $highlight_views ) ) {

				if ( function_exists( 'ev_get_post_view_count' ) ) {
					global $wp_locale;
					$thousands_sep = isset( $wp_locale->number_format['thousands_sep'] ) ? $wp_locale->number_format['thousands_sep'] : ',';
					$views = absint( str_replace( $thousands_sep, '', ev_get_post_view_count( get_the_ID() ) ) );

					if ( $views >= $highlight_views ) {
						return $highlight_class;
					}
				}

			}

			//Manual

			if ( $manual_posts = gridlove_get_option( 'highlight_manual_ids' ) ) {
				$manual_posts = explode( ",", $manual_posts );
				if ( in_array( get_the_ID(), $manual_posts ) ) {
					return $highlight_class;
				}

			} elseif ( $manual_posts = gridlove_get_option( 'highlight_manual' ) ) {
				if ( in_array( get_the_ID(), $manual_posts ) ) {
					return $highlight_class;
				}
			}


		}

		return '';
	}
endif;


/**
 * Get previous/next posts
 *
 * @return array Previous and next post ids
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_prev_next_posts' ) ):
	function gridlove_get_prev_next_posts() {

		$prev = get_adjacent_post( true, '', false, 'category' );
		$next = get_adjacent_post( true, '', true, 'category' );

		return array( 'prev' => $prev, 'next' => $next );

	}
endif;


/**
 * Parse layout params
 *
 * Gets layout ID and parse array of params to prepare layout display
 *
 * @param unknown $layout ID of a layout
 * @param unknown $type   Type of layout grid|row|list
 * @return array
 * @since  1.0
 */

if ( !function_exists( 'gridlove_parse_layout_params' ) ):
	function gridlove_parse_layout_params( $layout = 1, $type = 'simple' ) {

		$params = array(

			'simple' => array(

				1 => array(
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'a' ),
				),

				2 => array(
					array( 'col' => 12, 'layout' => 'b' ),
				),

				3 => array(
					array( 'col' => 6, 'layout' => 'b' ),
					array( 'col' => 6, 'layout' => 'b' ),
				),

				4 => array(
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'c' ),
				),

				5 => array(
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
				),

				6 => array(
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'd' ),
				),

				7 => array(
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
				),

				8 => array(
					array( 'col' => 6, 'layout' => 'd' ),
					array( 'col' => 6, 'layout' => 'd' )
				),

			),

			'combo' => array(

				1 => array(

					array( 'col' => 8, 'layout' => 'b' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'c' ),
				),


				2 => array(

					array( 'col' => 7, 'layout' => 'b' ),
					array( 'col' => 5, 'layout' => 'c' ),
					array( 'col' => 5, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'c' ),
				),

				3 => array(

					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'c' ),
				),

				4 => array(

					array( 'col' => 7, 'layout' => 'b' ),
					array( 'col' => 5, 'layout' => 'd' ),
					array( 'col' => 5, 'layout' => 'c' ),
					array( 'col' => 7, 'layout' => 'b' ),
				),

				5 => array(

					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 8, 'layout' => 'b' ),
				),

				6 => array(

					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'a' ),
				),

				7 => array(

					array( 'col' => 9, 'layout' => 'b' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 5, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'a' ),
				),

				8 => array(

					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 5, 'layout' => 'd' ),
					array( 'col' => 5, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'a' ),
				),

				9 => array(

					array( 'col' => 5, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 5, 'layout' => 'd' ),
				),

				10 => array(

					array( 'col' => 5, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 5, 'layout' => 'd' ),
				),

				11 => array(

					array( 'col' => 5, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 5, 'layout' => 'c' ),
				),

				12 => array(

					array( 'col' => 12, 'layout' => 'b' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
				),

			),

			'slider' => array(

				1 => array(
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'a' ),
				),


				2 => array(
					array( 'col' => 12, 'layout' => 'b' ),
				),

				3 => array(
					array( 'col' => 6, 'layout' => 'b' ),
					array( 'col' => 6, 'layout' => 'b' ),
				),

				4 => array(
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'c' ),
				),

				5 => array(
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
					array( 'col' => 3, 'layout' => 'c' ),
				),

				6 => array(
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'd' ),
					array( 'col' => 4, 'layout' => 'd' ),
				),

				7 => array(
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
					array( 'col' => 3, 'layout' => 'd' ),
				),

				8 => array(

					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'c' ),
					
				),

				9 => array(

					array( 'col' => 4, 'layout' => 'a' ),
					array( 'col' => 4, 'layout' => 'd' ),
				),


				10 => array(

					array( 'col' => 4, 'layout' => 'c' ),
					array( 'col' => 4, 'layout' => 'd' ),
				),

				11 => array(

					array( 'col' => 6, 'layout' => 'd' ),
					array( 'col' => 6, 'layout' => 'd' ),
				),

			),

            'masonry' => array(

                1 => array(
                    array( 'col' => 4, 'layout' => 'a' ),
                    array( 'col' => 4, 'layout' => 'a' ),
                    array( 'col' => 4, 'layout' => 'a' ),
                ),

                2 => array(
                    array( 'col' => 4, 'layout' => 'c' ),
                    array( 'col' => 4, 'layout' => 'c' ),
                    array( 'col' => 4, 'layout' => 'c' ),
                ),

                3 => array(
                    array( 'col' => 3, 'layout' => 'c' ),
                    array( 'col' => 3, 'layout' => 'c' ),
                    array( 'col' => 3, 'layout' => 'c' ),
                    array( 'col' => 3, 'layout' => 'c' ),
                ),

                4 => array(
                    array( 'col' => 4, 'layout' => 'd' ),
                    array( 'col' => 4, 'layout' => 'd' ),
                    array( 'col' => 4, 'layout' => 'd' ),
                ),

                5 => array(
                    array( 'col' => 3, 'layout' => 'd' ),
                    array( 'col' => 3, 'layout' => 'd' ),
                    array( 'col' => 3, 'layout' => 'd' ),
                    array( 'col' => 3, 'layout' => 'd' ),
				),
				
                6 => array(
                    array( 'col' => 6, 'layout' => 'd' ),
                    array( 'col' => 6, 'layout' => 'd' ),
                ),

            ),


		);

		$params = apply_filters( 'gridlove_modify_layout_params', $params );

		if ( array_key_exists( $type, $params ) && array_key_exists( $layout, $params[$type] ) ) {
			return $params[$type][$layout];
		}

		return $params['simple']['1']; //fallback

	}
endif;

/**
 * Get cover layout
 *
 * @return array WP_Query and Layout ID
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_cover_layout' ) ):
	function gridlove_get_cover_layout() {

		$template = gridlove_detect_template();

		if ( !in_array( $template, array( 'modules', 'category' ) ) ) {
			return false;
		}

		if ( $template == 'modules' ) {
			$meta = gridlove_get_page_meta( get_the_ID(), 'cover' );
			$cover = $meta['layout'];
		}

		if ( $template == 'category' ) {
			$cat_id = get_queried_object_id();
			$meta = gridlove_get_category_meta( $cat_id, 'layout' );
			$cover = isset($meta['type']) && $meta['type'] != 'inherit' ? $meta['cover'] : gridlove_get_option( 'category_cover_layout' );
		}

		if ( $cover == 'none' ) {
			return false;
		}

		return $cover;

	}
endif;


/**
 * Get cover area query
 *
 * Get query for cover area based on current template
 *
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_cover_query' ) ):
	function gridlove_get_cover_query() {

		$template = gridlove_detect_template();

		if ( $template == 'modules' ) {
			return gridlove_get_modules_cover_query();
		}

		if( $template == 'category' ){
			return gridlove_get_category_cover_query();
		}

		return false;

	}
endif;

/**
 * Get cover area query for category template
 *
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_category_cover_query' ) ):
	function gridlove_get_category_cover_query() {

		$obj = get_queried_object();
		$meta = gridlove_get_category_meta( $obj->term_id, 'layout' );

		$args['post_type'] = 'post';
		$args['posts_per_page'] = isset($meta['type']) && $meta['type'] != 'inherit' ? absint($meta['cover_ppp']) : gridlove_get_option( 'category_cover_limit' );
		$args['orderby'] = gridlove_get_option( 'category_cover_order' );
		
		if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = ev_get_meta_key();
		}

		$args['cat'] = $obj->term_id;

		$query = new WP_Query( $args );

		return $query;
	}
endif;

/**
 * Get cover area autoplay
 *
 * Checks cover layout options to return autoplay time if autoplay option is set
 * 
 * @param   int $layout_id ID of cover layout
 * @return int Autoplay time (0 or number of milliseconds)
 * @since  1.2
 */

if ( !function_exists( 'gridlove_cover_get_autoplay' ) ):
	function gridlove_cover_get_autoplay( $layout_id ) {

		$autoplay = gridlove_get_option('cover_'.$layout_id.'_autoplay');

		if( $autoplay ){
			$autoplay_time = absint( gridlove_get_option('cover_'.$layout_id.'_autoplay_time') ) * 1000;

			return $autoplay_time;
		}

		return 0;
	}
endif;


/**
 * Check if WooCommerce is active
 *
 * @return bool
 * @since  1.2
 */

if ( !function_exists( 'gridlove_is_woocommerce_active' ) ):
	function gridlove_is_woocommerce_active() {

		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check if we are on WooCommerce page
 *
 * @return bool
 * @since  1.2
 */

if ( !function_exists( 'gridlove_is_woocommerce_page' ) ):
	function gridlove_is_woocommerce_page() {

		return is_singular( 'product' ) || is_tax( 'product_cat' ) || is_post_type_archive( 'product' ) || is_tax( 'product_tag' );
	}
endif;

/**
 * Check if bbPress is active
 *
 * @return bool
 * @since  1.2
 */

if ( !function_exists( 'gridlove_is_bbpress_active' ) ):
	function gridlove_is_bbpress_active() {

		if ( in_array( 'bbpress/bbpress.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}
endif;


/**
 * Used for getting post types with all taxonomies
 *
 * @return array
 * @since    1.9.1
 */
if (!function_exists('gridlove_get_posts_types_with_taxonomies')):
	function gridlove_get_posts_types_with_taxonomies( $exclude = array() ) {
		
		$post_types_with_taxonomies = array();
		
		$post_types = gridlove_get_custom_post_types( true );
		$post_types[] = get_post_type_object('post');
		
		if (empty($post_types))
			return null;
		
		foreach ($post_types as $post_type) {
			if(in_array($post_type->name, $exclude)){
				continue;
			}
			
			$post_taxonomies = gridlove_get_taxonomies($post_type->name);
			
			$post_type->taxonomies = $post_taxonomies;
			$post_types_with_taxonomies[] = $post_type;
		}
		
		return apply_filters('gridlove_modify_posts_types_with_taxonomies', $post_types_with_taxonomies);
	}
endif;

/**
 * Get all public custom post types 
 *
 * @return array List of slugs
 * @since  1.3  
 */

if ( !function_exists( 'gridlove_get_custom_post_types' ) ):
	function gridlove_get_custom_post_types($raw = false ) {
		
		$custom_post_types =  get_post_types( array( 'public' => true, '_builtin' => false ), 'object' );
		
		if(!empty( $custom_post_types )){
			
			$exclude = array( 'topic', 'forum', 'guest-author', 'reply' );
			
			foreach( $custom_post_types as $i => $obj ){
				if( in_array($obj->name, $exclude) ){
					unset( $custom_post_types[$i] );
				}
			}
			
			if(!$raw) {
				$custom_post_types = array_keys( $custom_post_types );
			}
		}

		$custom_post_types =  apply_filters('gridlove_modify_custom_post_types_list', $custom_post_types );
		
		return $custom_post_types;
	}
endif;



/**
 * Get all taxonomies for custom post type
 *
 * @param  $cpt Custom post type ID
 * @return array List of custom post types and taxonomies
 * @since  1.3  
 */
if ( !function_exists( 'gridlove_get_taxonomies' ) ) :
function gridlove_get_taxonomies( $cpt ) {

	$taxonomies = get_taxonomies( array(
	    'object_type' => array($cpt),
        'public' => true,
        'show_ui' => true
    ), 'object');

	$output = array();

	foreach ( $taxonomies as $taxonomy ) {

			$tax = array();
			$tax['id'] = $taxonomy->name;
			$tax['name'] = $taxonomy->label;
			$tax['hierarchical'] = $taxonomy->hierarchical;
			if( $tax['hierarchical'] ){
                $tax['terms'] = get_terms( $taxonomy->name, array('hide_empty' => false) ); //false for testing - change to true
            }
			
			$output[] = $tax;
	}
	
	return $output;
}
endif;

/**
 * Get term IDs by term names for specific taxonomy
 *
 * @param array   $names List of term names
 * @param string  $tax   Taxonomy name
 * @return array List of term IDs
 * @since  1.3
 */

if ( !function_exists( 'gridlove_get_tax_term_id_by_name' ) ):
	function gridlove_get_tax_term_id_by_name( $names, $tax = 'post_tag' ) {

		if ( empty( $names ) ) {
			return '';
		}

		if(!is_array($names)){
			$names = explode(",", $names );
		}

		$ids = array();

		foreach ( $names as $name ) {
			$tag = get_term_by( 'name', trim( $name ), $tax);
			if ( !empty( $tag ) && isset( $tag->term_id ) ) {
				$ids[] = $tag->term_id;
			}
		}

		return $ids;

	}
endif;

/**
 * Get term names by term id for specific taxonomy
 *
 * @param array   $names List of term ids
 * @param string  $tax   Taxonomy name
 * @return array List of term names
 * @since  1.3
 */

if ( !function_exists( 'gridlove_get_tax_term_name_by_id' ) ):
	function gridlove_get_tax_term_name_by_id( $ids, $tax = 'post_tag' ) {

		if ( empty( $ids ) ) {
			return '';
		}

		$names = array();

		foreach ( $ids as $id ) {
			$tag = get_term_by( 'id', trim( $id ), $tax);
			if ( !empty( $tag ) && isset( $tag->name ) ) {
				$names[] = $tag->name;
			}
		}

		$names = implode(',', $names);

		return $names;

	}
endif;

/**
 * Support for Co Authors Plus plugin
 *
 * Check is plugin activated
 * 
 * @return bool
 * @since  1.4
 */

if ( !function_exists( 'gridlove_is_co_authors_active' ) ):
	function gridlove_is_co_authors_active() {
		if ( in_array( 'co-authors-plus/co-authors-plus.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}
		return false;
	}
endif;



/**
 * Get term IDs by term slugs for specific taxonomy
 *
 * @param array   $slugs List of tag slugs
 * @param string  $tax   Taxonomy name
 * @return array List IDs
 * @since  1.4
 */

if ( !function_exists( 'gridlove_get_tax_term_id_by_slug' ) ):
    function gridlove_get_tax_term_id_by_slug( $slugs, $tax = 'post_tag' ) {

        if ( empty( $slugs ) ) {
            return '';
        }

        $ids = array();

        foreach ( $slugs as $slug ) {
            $tag = get_term_by( 'slug', trim( $slug ), $tax );
            if ( !empty( $tag ) && isset( $tag->term_id ) ) {
                $ids[] = $tag->term_id;
            }
        }

        return $ids;

    }
endif;



/**
 * Get related plugins
 *
 * Check if Yet Another Related Posts Plugin (YARPP) or Contextual Related Posts or WordPress Related Posts or Jetpack by WordPress.com is active
 *
 * @return bool
 * @since  1.6
 */
if ( !function_exists( 'gridlove_get_related_posts_plugins' ) ):
    function gridlove_get_related_posts_plugins() {
        $related_plugins['default'] = esc_html__('Built-in (Gridlove) related posts', 'gridlove');
        $related_plugins[(gridlove_is_yarpp_active()) ? 'yarpp' : 'yarpp-disabled'] = esc_html__('Yet Another Related Posts Plugin (YARPP)', 'gridlove');
        $related_plugins[(gridlove_is_crp_active()) ? 'crp' : 'crp-disabled'] = esc_html__('Contextual Related Posts', 'gridlove');
        $related_plugins[(gridlove_is_wrpr_active()) ? 'wrpr' : 'wrpr-disabled'] = esc_html__('WordPress Related Posts', 'gridlove');
        $related_plugins[(gridlove_is_jetpack_active()) ? 'jetpack' : 'jetpack-disabled'] = esc_html__('Jetpack by WordPress.com', 'gridlove');

        return $related_plugins;
    }
endif;

/**
 * Check if Yet Another Related Posts Plugin (YARPP) is active
 *
 * @return bool
 * @since  1.6
 */
if ( !function_exists( 'gridlove_is_yarpp_active' ) ):
    function gridlove_is_yarpp_active(){
        if ( in_array( 'yet-another-related-posts-plugin/yarpp.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            return true;
        }
        return false;
    }
endif;

/**
 * Check if Contextual Related Posts is active
 *
 * @return bool
 * @since  1.6
 */
if ( !function_exists( 'gridlove_is_crp_active' ) ):
    function gridlove_is_crp_active(){
        if ( in_array( 'contextual-related-posts/contextual-related-posts.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            return true;
        }
        return false;
    }
endif;

/**
 * Check if WordPress Related Posts is active
 *
 * @return bool
 * @since  1.6
 */
if ( !function_exists( 'gridlove_is_wrpr_active' ) ):
    function gridlove_is_wrpr_active(){
        if ( in_array( 'wordpress-23-related-posts-plugin/wp_related_posts.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            return true;
        }
        return false;
    }
endif;

/**
 * Check if Jetpack is active
 *
 * @return bool
 * @since  1.6
 */
if ( !function_exists( 'gridlove_is_jetpack_active' ) ):
    function gridlove_is_jetpack_active(){
        if ( in_array( 'jetpack/jetpack.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            return true;
        }
        return false;
    }
endif;

/**
 * Check if SEO by Yoast is active
 *
 * @return bool
 * @since  1.6
 */
if ( !function_exists( 'gridlove_is_yoast_active' ) ):
    function gridlove_is_yoast_active(){
        if ( in_array( 'wordpress-seo/wp-seo.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
            return true;
        }
        return false;
    }
endif;

/**
 * Trim text characters with UTF-8
 * for adding to html attributes it's not breaking the code and
 * you are able to have all the kind of characters (Japanese, Cyrillic, German, French, etc.)
 *
 * @param $text
 * @since  1.6
 */
if(!function_exists('gridlove_esc_text')):
    function gridlove_esc_text($text){
        return rawurlencode( html_entity_decode( wp_kses($text, null), ENT_COMPAT, 'UTF-8') );
    }
endif;

/**
 * Trims URL with special characters like used in (Japanese, Cyrillic, German, French, etc.)
 *
 * @param $url
 * @since  1.6
 */
if(!function_exists('gridlove_esc_url')):
    function gridlove_esc_url($url){
        return rawurlencode( esc_url( esc_attr($url) ) );
    }
endif;

/**
 * Get first post in category
 *
 * @since  1.7
 * @param $category_id 
 * @return object WP Query Object 
 */

if ( !function_exists('gridlove_get_first_post_in_category') ) :
	function gridlove_get_first_post_in_category($category_id){
	
		$args = array(
			'post_type' => 'post', 
			'posts_per_page' => 1,
			'category__in' => array($category_id), 
		);
		
		$query = new WP_Query($args);

		if (!$query->have_posts()) {
			return false;
		}

		while ($query->have_posts()) {
			$query->the_post();
			$post_obj = $query->post;
		}

		wp_reset_postdata();

		return $post_obj;
	}
endif;

/** Used for getting post type with all its taxonomies
 *                                    *
 * @return array
 * @since    1.9.1
*/
if (!function_exists('gridlove_get_post_type_with_taxonomies')):
	function gridlove_get_post_type_with_taxonomies( $post_type ) {
		
		$post_type = get_post_type_object( $post_type );
		
		if (empty($post_type))
			return null;
		
		
		$post_taxonomies = array();
		$taxonomies = get_taxonomies(array(
			'object_type' => array($post_type->name),
			'public'      => true,
			'show_ui'     => true,
		), 'object');
		
		if (!empty($taxonomies)) {
			foreach ($taxonomies as $taxonomy) {
				
				$tax = array();
				$tax['id'] = $taxonomy->name;
				$tax['name'] = $taxonomy->label;
				$tax['hierarchical'] = $taxonomy->hierarchical;
				if ($tax['hierarchical']) {
					$tax['terms'] = get_terms($taxonomy->name, array('hide_empty' => false));
				}
				
				$post_taxonomies[] = $tax;
			}
		}
		
		if (!empty($post_taxonomies)) {
			$post_type->taxonomies = $post_taxonomies;
		}
		
		
		return apply_filters('gridlove_modify_post_type_with_taxonomies', $post_type);
	}
endif;

/**
 * Check if is Gutenberg page
 *
 * @return bool
 * @since  1.7.1
 */
if ( !function_exists( 'gridlove_is_gutenberg_page' ) ):
	function gridlove_is_gutenberg_page() {

		if ( function_exists( 'is_gutenberg_page' ) ) {
			return is_gutenberg_page();
		}
		
		global $wp_version;

		if( version_compare( $wp_version, '5', '<' ) ){
			return false;
		}

		global $current_screen;

		if ( ( $current_screen instanceof WP_Screen ) && !$current_screen->is_block_editor() ) {
			return false;
		}
		
		return true;
	}
endif;

/**
 * Check if ad have to be injected in archive pages and return ad position
 *
 * @return integer Ad position
 * @since  1.7.3
 */
if ( !function_exists( 'gridlove_get_archive_ad' ) ):
	function gridlove_get_archive_ad() {
		$ad = gridlove_get_option('ad_archive');
		if ( empty( $ad ) ) {
			return false;
		}
		
		return gridlove_get_option('ad_archive_position');
	}
endif;
?>
