<?php

/**
 * Get the list of available options for post ordering
 *
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_post_order_opts' ) ) :
	function gridlove_get_post_order_opts() {

		$options = array(
			'date' => esc_html__( 'Date', 'gridlove' ),
			'comment_count' => esc_html__( 'Number of comments', 'gridlove' ),
			'views' => esc_html__( 'Number of views', 'gridlove' ),
			'title'	=> esc_html__( 'Title (alphabetically)', 'gridlove' ),
			'rand' => esc_html__( 'Random', 'gridlove' ),
		);

		$options = apply_filters('gridlove_modify_post_order_opts', $options ); //Allow child themes or plugins to modify
		return $options;
	}
endif;


/**
 * Get the list of time limit options
 *
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_time_diff_opts' ) ) :
	function gridlove_get_time_diff_opts() {

		$options = array(
			'-1 day' => esc_html__( '1 Day', 'gridlove' ),
			'-3 days' => esc_html__( '3 Days', 'gridlove' ),
			'-1 week' => esc_html__( '1 Week', 'gridlove' ),
			'-1 month' => esc_html__( '1 Month', 'gridlove' ),
			'-3 months' => esc_html__( '3 Months', 'gridlove' ),
			'-6 months' => esc_html__( '6 Months', 'gridlove' ),
			'-1 year' => esc_html__( '1 Year', 'gridlove' ),
			'0' => esc_html__( 'All time', 'gridlove' )
		);

		return $options;
	}
endif;

/**
 * Get the list of available options to filter posts by format
 *
 * @return array List of available post formats
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_post_format_opts' ) ) :
	function gridlove_get_post_format_opts() {
		
		$options = array();
		$options['standard'] = esc_html__( 'Standard', 'gridlove' );
		
		$formats = get_theme_support('post-formats');
		if(!empty($formats) && is_array($formats[0])){
			foreach($formats[0] as $format){
				$options[$format] = ucfirst($format);
			}
		}

		$options['0'] = esc_html__( 'All', 'gridlove' );

		$options = apply_filters('gridlove_modify_post_format_opts', $options ); //Allow child themes or plugins to modify
		return $options;
	}
endif;


/**
 * Get the list of available layouts
 *
 * @return array List of available options
 * @since  1.4
 */

if ( !function_exists( 'gridlove_get_main_layouts' ) ):
    function gridlove_get_main_layouts( ) {

        $layouts = array();

        $layouts['a'] = array( 'title' => esc_html__( 'Layout A', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_a.png');
        $layouts['b'] = array( 'title' => esc_html__( 'Layout B', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_b.png');
        $layouts['c'] = array( 'title' => esc_html__( 'Layout C', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_c.png');
        $layouts['d'] = array( 'title' => esc_html__( 'Layout D', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_d.png');

        $layouts = apply_filters('gridlove_modify_main_layouts', $layouts ); //Allow child themes or plugins to modify
        return $layouts;
    }
endif;

/**
 * Get the list of available simple layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_simple_layouts' ) ):
	function gridlove_get_simple_layouts( $inherit = false ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		$layouts['1'] = array( 'title' => esc_html__( 'Layout A (3 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_a3.png', 'step' => 3);
		$layouts['2'] = array( 'title' => esc_html__( 'Layout B (1 column)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_b1.png', 'step' => 1);
		$layouts['3'] = array( 'title' => esc_html__( 'Layout B (2 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_b2.png', 'step' => 2);
		$layouts['4'] = array( 'title' => esc_html__( 'Layout C (3 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_c3.png', 'step' => 3);
		$layouts['5'] = array( 'title' => esc_html__( 'Layout C (4 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_c4.png', 'step' => 4);
		$layouts['6'] = array( 'title' => esc_html__( 'Layout D (3 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_d3.png', 'step' => 3);
		$layouts['7'] = array( 'title' => esc_html__( 'Layout D (4 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_d4.png', 'step' => 4);
		$layouts['8'] = array( 'title' => esc_html__( 'Layout D (2 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_d2.png', 'step' => 2);
		
		$layouts = apply_filters('gridlove_modify_simple_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;


/**
 * Get the list of available simple category layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_cats_simple_layouts' ) ):
	function gridlove_get_cats_simple_layouts( $inherit = false ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		$layouts['1'] = array( 'title' => esc_html__( 'Layout 1', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_layout_a3.png', 'step' => 3);
		$layouts['4'] = array( 'title' => esc_html__( 'Layout 2', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_layout_c3.png', 'step' => 3);
		$layouts['5'] = array( 'title' => esc_html__( 'Layout 3', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_layout_c4.png', 'step' => 4);
		$layouts['6'] = array( 'title' => esc_html__( 'Layout 4', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_layout_d3.png', 'step' => 3);
		$layouts['7'] = array( 'title' => esc_html__( 'Layout 5', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_layout_d4.png', 'step' => 4);
		
		$layouts = apply_filters('gridlove_modify_cats_simple_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;

/**
 * Get the list of available combo layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_combo_layouts' ) ):
	function gridlove_get_combo_layouts( $inherit = false, $none = false ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		if ( $none ) {
			$layouts['none'] = array( 'title' => esc_html__( 'None', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/none.png');
		}

		$layouts['1'] = array( 'title' => esc_html__( 'Layout 1', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_1.png', 'step' => 5 );
		$layouts['2'] = array( 'title' => esc_html__( 'Layout 2', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_2.png', 'step' => 5 );
		$layouts['3'] = array( 'title' => esc_html__( 'Layout 3', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_3.png', 'step' => 6 );
		$layouts['4'] = array( 'title' => esc_html__( 'Layout 4', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_4.png', 'step' => 4 );
		$layouts['5'] = array( 'title' => esc_html__( 'Layout 5', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_5.png', 'step' => 5 );
		$layouts['6'] = array( 'title' => esc_html__( 'Layout 6', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_6.png', 'step' => 6 );
		$layouts['7'] = array( 'title' => esc_html__( 'Layout 7', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_7.png', 'step' => 5 );
		$layouts['8'] = array( 'title' => esc_html__( 'Layout 8', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_8.png', 'step' => 6 );
		$layouts['9'] = array( 'title' => esc_html__( 'Layout 9', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_9.png', 'step' => 6 );
		$layouts['10'] = array( 'title' => esc_html__( 'Layout 10', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_10.png', 'step' => 6 );
		$layouts['11'] = array( 'title' => esc_html__( 'Layout 11', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_11.png', 'step' => 6 );
		$layouts['12'] = array( 'title' => esc_html__( 'Layout 12', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_combo_12.png', 'step' => 5 );

		$layouts = apply_filters('gridlove_modify_combo_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;


/**
 * Get the list of available slider layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_slider_layouts' ) ):
	function gridlove_get_slider_layouts( $inherit = false ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		$layouts['1'] = array( 'title' => esc_html__( 'Layout 1', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_1.png', 'step' => 3);
		$layouts['2'] = array( 'title' => esc_html__( 'Layout 2', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_2.png', 'step' => 1);
		$layouts['3'] = array( 'title' => esc_html__( 'Layout 3', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_3.png', 'step' => 2);
		$layouts['4'] = array( 'title' => esc_html__( 'Layout 4', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_4.png', 'step' => 3);
		$layouts['5'] = array( 'title' => esc_html__( 'Layout 5', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_5.png', 'step' => 4);
		$layouts['6'] = array( 'title' => esc_html__( 'Layout 6', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_6.png', 'step' => 3);
		$layouts['7'] = array( 'title' => esc_html__( 'Layout 7', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_7.png', 'step' => 4);
		$layouts['8'] = array( 'title' => esc_html__( 'Layout 8', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_8.png', 'step' => 3 );
		$layouts['9'] = array( 'title' => esc_html__( 'Layout 9', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_9.png', 'step' => 3 );
		$layouts['10'] = array( 'title' => esc_html__( 'Layout 10', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_10.png', 'step' => 3 );
		$layouts['11'] = array( 'title' => esc_html__( 'Layout 11', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_slider_11.png', 'step' => 2 );
		
		$layouts = apply_filters('gridlove_modify_slider_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;

/**
 * Get the list of available category slider layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_cats_slider_layouts' ) ):
	function gridlove_get_cats_slider_layouts( $inherit = false ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		$layouts['1'] = array( 'title' => esc_html__( 'Layout 1', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_slider_layout_01.png', 'step' => 3);
		$layouts['4'] = array( 'title' => esc_html__( 'Layout 2', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_slider_layout_02.png', 'step' => 1);
		$layouts['5'] = array( 'title' => esc_html__( 'Layout 3', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_slider_layout_03.png', 'step' => 2);
		$layouts['6'] = array( 'title' => esc_html__( 'Layout 4', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_slider_layout_04.png', 'step' => 3);
		$layouts['7'] = array( 'title' => esc_html__( 'Layout 5', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/category_slider_layout_05.png', 'step' => 4);
		
		$layouts = apply_filters('gridlove_modify_cats_slider_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;

/**
 * Get the list of masonry layouts
 *
 * @return array List of masonry options
 * @since  1.4
 */

if ( !function_exists( 'gridlove_get_masonry_layouts' ) ):
    function gridlove_get_masonry_layouts( ) {

        $layouts = array();

        $layouts['1'] = array( 'title' => esc_html__( 'Layout A (3 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_a3_masonry.jpg', 'step' => 1);
        $layouts['2'] = array( 'title' => esc_html__( 'Layout C (3 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_c3_masonry.jpg', 'step' => 1);
        $layouts['3'] = array( 'title' => esc_html__( 'Layout C (4 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_c4_masonry.jpg', 'step' => 1);
        $layouts['4'] = array( 'title' => esc_html__( 'Layout D (3 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_d3_masonry.jpg', 'step' => 1);
        $layouts['5'] = array( 'title' => esc_html__( 'Layout D (4 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_d4_masonry.jpg', 'step' => 1);
        $layouts['6'] = array( 'title' => esc_html__( 'Layout D (2 columns)', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_d2_masonry.png', 'step' => 1);

        $layouts = apply_filters('gridlove_modify_masonry_layouts', $layouts ); //Allow child themes or plugins to modify
        return $layouts;

    }

endif;

/**
 * Get the list of available cover layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @param bool    $none    Whether you want to add "none" option ( to set layout to "off")
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_cover_layouts' ) ):
	function gridlove_get_cover_layouts( $inherit = false, $none = false, $exclude = array() ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png');
		}

		if ( $none ) {
			$layouts['none'] = array( 'title' => esc_html__( 'None', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/none.png');
		}

		$layouts['1'] = array( 'title' => esc_html__( 'Layout 1', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_cover_1.png');
		$layouts['2'] = array( 'title' => esc_html__( 'Layout 2', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_cover_2.png');
		$layouts['3'] = array( 'title' => esc_html__( 'Layout 3', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_cover_3.png');
		$layouts['4'] = array( 'title' => esc_html__( 'Layout 4', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_cover_4.png');
		$layouts['custom'] = array( 'title' => esc_html__( 'Custom', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_cover_custom.png');


		if( !empty( $exclude ) ){

			foreach($exclude as $layout){
				if(array_key_exists($layout, $layouts)){
					unset($layouts[$layout]);
				}
			}
		}

		$layouts = apply_filters('gridlove_modify_cover_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;

/**
 * Get the list of available single post layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_single_layouts' ) ):
	function gridlove_get_single_layouts( $inherit = false ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		$layouts['1_0'] = array( 'title' => esc_html__( 'Layout 1', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_1.png');
		$layouts['2_0'] = array( 'title' => esc_html__( 'Layout 2', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_2.png');
		$layouts['3_0'] = array( 'title' => esc_html__( 'Layout 3', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_3.png');
		$layouts['4_0'] = array( 'title' => esc_html__( 'Layout 4', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_4.png');
		$layouts['5_0'] = array( 'title' => esc_html__( 'Layout 5', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_5.png');
		$layouts['6_0'] = array( 'title' => esc_html__( 'Layout 6', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_6.png');
		$layouts['7_1'] = array( 'title' => esc_html__( 'Layout 7', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_7.png');
		$layouts['8_1'] = array( 'title' => esc_html__( 'Layout 8', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_8.png');
		$layouts['9_2'] = array( 'title' => esc_html__( 'Layout 9', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_single_9.png');
		
		$layouts = apply_filters('gridlove_modify_single_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;


/**
 * Get the list of available page layouts
 *
 * @param bool    $ihnerit Whether you want to add "inherit" option
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_page_layouts' ) ):
	function gridlove_get_page_layouts( $inherit = false ) {

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		$layouts['1_0'] = array( 'title' => esc_html__( 'Layout 1', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_page_1.png');
		$layouts['2_0'] = array( 'title' => esc_html__( 'Layout 2', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_page_2.png');
		$layouts['3_0'] = array( 'title' => esc_html__( 'Layout 3', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_page_3.png');
		$layouts['4_1'] = array( 'title' => esc_html__( 'Layout 4', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_page_4.png');
		$layouts['5_1'] = array( 'title' => esc_html__( 'Layout 5', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_page_5.png');
		$layouts['6_2'] = array( 'title' => esc_html__( 'Layout 6', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/layout_page_6.png');
		
		$layouts = apply_filters('gridlove_modify_page_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
		
	}
endif;



/**
 * Get the list of available sidebar layouts
 *
 * You may have left sidebar, right sidebar or no sidebar
 *
 * @param bool    $ihnerit Whether you want to include "inherit" option in the list
 * @return array List of available sidebar layouts
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_sidebar_layouts' ) ):
	function gridlove_get_sidebar_layouts( $inherit = false ) {

		$layouts = array();

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		$layouts['none'] = array( 'title' => esc_html__( 'None', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/none.png' );
		$layouts['left'] = array( 'title' => esc_html__( 'Left sidebar', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/content_sid_left.png' );
		$layouts['right'] = array( 'title' => esc_html__( 'Right sidebar', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/content_sid_right.png' );

		$layouts = apply_filters('gridlove_modify_sidebar_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
	}
endif;

/**
 * Get the list of available pagination types
 *
 * @param bool    $ihnerit Whether you want to include "inherit" option in the list
 * @param bool    $none    Whether you want to add "none" option ( to set layout to "off")
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_pagination_layouts' ) ):
	function gridlove_get_pagination_layouts( $inherit = false, $none = false ) {

		$layouts = array();

		if ( $inherit ) {
			$layouts['inherit'] = array( 'title' => esc_html__( 'Inherit', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/inherit.png' );
		}

		if ( $none ) {
			$layouts['none'] = array( 'title' => esc_html__( 'None', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/none.png' );
		}

		$layouts['numeric'] = array( 'title' => esc_html__( 'Numeric pagination links', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/pag_numeric.png' );
		$layouts['prev-next'] = array( 'title' => esc_html__( 'Prev/Next page links', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/pag_prev_next.png' );
		$layouts['load-more'] = array( 'title' => esc_html__( 'Load more button', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/pag_load_more.png' );
		$layouts['infinite-scroll'] = array( 'title' => esc_html__( 'Infinite scroll', 'gridlove' ), 'img' => get_template_directory_uri() . '/assets/img/admin/pag_infinite.png' );
		
		$layouts = apply_filters('gridlove_modify_pagination_layouts', $layouts ); //Allow child themes or plugins to modify
		return $layouts;
	}
endif;


/**
 * Get the list of registered sidebars
 *
 * @param bool    $ihnerit Whether you want to include "inherit" option in the list
 * @return array Returns list of available sidebars
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_sidebars_list' ) ):
	function gridlove_get_sidebars_list( $inherit = false ) {

		$sidebars = array();

		if ( $inherit ) {
			$sidebars['inherit'] = esc_html__( 'Inherit', 'gridlove' );
		}

		$sidebars['none'] = esc_html__( 'None', 'gridlove' );

		global $wp_registered_sidebars;

		if ( !empty( $wp_registered_sidebars ) ) {

			foreach ( $wp_registered_sidebars as $sidebar ) {
				$sidebars[$sidebar['id']] = $sidebar['name'];
			}

		}
		//Get sidebars from wp_options if global var is not loaded yet
		$fallback_sidebars = get_option( 'gridlove_registered_sidebars' );
		if ( !empty( $fallback_sidebars ) ) {
			foreach ( $fallback_sidebars as $sidebar ) {
				if ( !array_key_exists( $sidebar['id'], $sidebars ) ) {
					$sidebars[$sidebar['id']] = $sidebar['name'];
				}
			}
		}

		//Check for theme additional sidebars
		$custom_sidebars = gridlove_get_option( 'sidebars' );

		if ( $custom_sidebars ) {
			foreach ( $custom_sidebars as $k => $title) {
				if ( is_numeric($k) && !array_key_exists( 'gridlove_sidebar_'.$k, $sidebars ) ) {
					$sidebars['gridlove_sidebar_'.$k] = $title;
				}
			}
		}

		//Do not display footer sidebars for selection
		unset( $sidebars['gridlove_header_sidebar'] );
		unset( $sidebars['gridlove_footer_sidebar_1'] );
		unset( $sidebars['gridlove_footer_sidebar_2'] );
		unset( $sidebars['gridlove_footer_sidebar_3'] );
		unset( $sidebars['gridlove_footer_sidebar_4'] );

		$sidebars = apply_filters('gridlove_modify_sidebars_list', $sidebars ); //Allow child themes or plugins to modify
		return $sidebars;
	}
endif;


/**
 * Get meta options
 *
 * @param   array $default Enable defaults i.e. array('date', 'comments')
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_meta_opts' ) ):
	function gridlove_get_meta_opts(	$default = array() ) {

		$options = array();

		$options['author'] = esc_html__( 'Author', 'gridlove' );
		$options['date'] = esc_html__( 'Date', 'gridlove' );
		$options['comments'] = esc_html__( 'Comments', 'gridlove' );
		$options['views'] = esc_html__( 'Views', 'gridlove' );
		$options['rtime'] = esc_html__( 'Reading time', 'gridlove' );

		if(!empty($default)){
			foreach($options as $key => $option){
				if(in_array( $key, $default)){
					$options[$key] = 1;
				} else {
					$options[$key] = 0;
				}
			}
		}

		$options = apply_filters('gridlove_modify_meta_opts', $options ); //Allow child themes or plugins to modify
		return $options;
	}
endif;



/**
 * Get header top elements
 *
 * Get the list of available elements to display in slots of header top bar
 * 
 * @return array List of available options
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_header_top_elements' ) ):
	function gridlove_get_header_top_elements() {

		$options = array();

		$options['secondary-menu-1'] = esc_html__( 'Secondary Menu 1', 'gridlove' );
		$options['secondary-menu-2'] = esc_html__( 'Secondary Menu 2', 'gridlove' );
		$options['social-menu'] = esc_html__( 'Social Menu', 'gridlove' );
		$options['date'] = esc_html__( 'Date', 'gridlove' );
		$options['0'] = esc_html__( 'None', 'gridlove' );

		$options = apply_filters('gridlove_modify_header_top_elements', $options ); //Allow child themes or plugins to modify
		return $options;
	}
endif;


/**
 * Get sticky header menu
 *
 * Get the list of available menus
 * 
 * @return array List of available menus
 * @since  1.7.3
 */

if ( !function_exists( 'gridlove_get_sticky_header_menus' ) ):
	function gridlove_get_sticky_header_menus() {

		$options = array();

		$options['gridlove_main_menu'] = esc_html__( 'Main Menu', 'gridlove' );
		$options['gridlove_secondary_menu_1'] = esc_html__( 'Secondary Menu 1', 'gridlove' );
		$options['gridlove_secondary_menu_2'] = esc_html__( 'Secondary Menu 2', 'gridlove' );
		$options['0'] = esc_html__( 'None', 'gridlove' );

		$options = apply_filters('gridlove_modify_sticky_header_menus', $options ); //Allow child themes or plugins to modify
		return $options;
	}
endif;

/**
 * Check if there is available theme update
 *
 * @return string HTML output with update notification and the link to change log
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_update_notification' ) ):
	function gridlove_get_update_notification() {
		$current = get_site_transient( 'update_themes' );
		$message_html = '';
		if ( isset( $current->response['gridlove'] ) ) {
			$message_html = '<span class="update-message">New update available!</span>
                <span class="update-actions">Version '.$current->response['gridlove']['new_version'].': <a href="http://mekshq.com/docs/gridlove-change-log" target="blank">See what\'s new</a><a href="'.admin_url( 'update-core.php' ).'">Update</a></span>';
		} else {
			$message_html = '<a class="theme-version-label" href="https://mekshq.com/docs/gridlove-change-log" target="blank">Version '.GRIDLOVE_THEME_VERSION.'</a>';
		}

		return $message_html;
	}
endif;

/**
 * Get Admin JS localized variables 
 *
 * Function creates list of variables from theme to pass
 * them to global JS variable so we can use it in JS files
 *
 * @since  1.7 
 * 
 * @return array List of JS settings
 */
if ( !function_exists( 'gridlove_get_admin_js_settings' ) ):
	function gridlove_get_admin_js_settings() {
		
		$js_settings = array();
		$js_settings['ajax_url'] = admin_url( 'admin-ajax.php');
		$js_settings['is_gutenberg'] = gridlove_is_gutenberg_page();
		return $js_settings;
	}
endif;


/**
 * Check if Envato Market plugin is active
 *
 * @return bool
 * @since  1.7.2
 */
if ( !function_exists( 'gridlove_is_envato_market_active' ) ):
	function gridlove_is_envato_market_active() {

		if ( in_array( 'envato-market/envato-market.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}
		return false;
	}
endif;

?>
