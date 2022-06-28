<?php

/**
 * Get module defaults
 *
 * @param string  $type Module type
 * @return array Default arguments of a module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_defaults' ) ):
	function gridlove_get_module_defaults( $type = false ) {

		$defaults = array(
			'posts' => array(
				'type' => 'posts',
				'type_name' => esc_html__( 'Posts', 'gridlove' ),
				'title' => '',
				'hide_title' => 0,
				'layout_type' => 'simple',
				'simple_layout' => '1',
				'combo_layout' => '1',
				'slider_layout' => '1',
				'masonry_layout' => '1',
				'limit' => 6,
				'content_inject' => 0,
				'content' => '',
				'autop' => 0,
				'center' => 0,
				'content_position' => 2,
				'cat' => array(),
				'cat_child' => 0,
				'tag' => array(),
				'manual' => array(),
				'time' => 0,
				'order' => 'date',
				'sort' => 'DESC',
				'format' => 0,
				'unique' => 0,
				'more_link' => 0,
				'more_text' => '',
				'more_url' => 'http://',
				'autoplay' => 0,
				'autoplay_time' => 5,
				'active' => 1,
				'cat_inc_exc' => 'in',
				'tag_inc_exc' => 'in',
				'css_class' => ''
			),

			'cats' => array(
				'type' => 'cats',
				'type_name' => esc_html__( 'Category', 'gridlove' ),
				'title' => '',
				'hide_title' => 0,
				'layout_type' => 'simple',
				'simple_layout' => '6',
				'slider_layout' => '6',
				'display_count' => 1,
				'count_label' => esc_html__( 'articles', 'gridlove' ),
				'autoplay' => 0,
				'autoplay_time' => 5,
				'cat' => array(),
				'active' => 1,
				'css_class' => '',
				'category_color' => 1
			),

			'text' => array(
				'type' => 'text',
				'type_name' => esc_html__( 'Text', 'gridlove' ),
				'title' => '',
				'hide_title' => 0,
				'content' => '',
				'autop' => 0,
				'center' => 0,
				'style' => 'boxed',
				'active' => 1,
				'css_class' => ''
			)
		);

		$custom_post_types = gridlove_get_custom_post_types();

		if ( !empty( $custom_post_types ) ) {
			foreach ( $custom_post_types as $custom_post_type ) {
				$defaults[$custom_post_type] = array(
					'type' => $custom_post_type,
					'cpt' => true,
					'type_name' => esc_html__( 'CPT', 'gridlove' ) . ' '.ucfirst( $custom_post_type ),
					'title' => '',
					'hide_title' => 0,
					'layout_type' => 'simple',
					'simple_layout' => '1',
					'combo_layout' => '1',
					'slider_layout' => '1',
					'masonry_layout' => '1',
					'limit' => 6,
					'content_inject' => 0,
					'content' => '',
					'autop' => 0,
					'center' => 0,
					'content_position' => 2,
					'tax' => array(),
					'manual' => array(),
					'time' => 0,
					'order' => 'date',
					'sort' => 'DESC',
					'format' => 0,
					'unique' => 0,
					'more_link' => 0,
					'more_text' => '',
					'more_url' => 'http://',
					'autoplay' => 0,
					'autoplay_time' => 5,
					'active' => 1,
					'css_class' => ''
				);
				$custom_post_type_taxonomies = gridlove_get_taxonomies( $custom_post_type );
				if ( !empty( $custom_post_type_taxonomies ) ) {
					foreach ( $custom_post_type_taxonomies as $custom_post_type_taxonomy ) {
						$defaults[$custom_post_type][$custom_post_type_taxonomy['id'] . '_inc_exc'] = 'in';
					}
				}
			}
		}


		if ( !empty( $type ) && array_key_exists( $type, $defaults ) ) {
			return $defaults[$type];
		}

		return $defaults;

	}
endif;

/**
 * Get module options
 *
 * @param string  $type Module type
 * @return array Options for sepcific module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_options' ) ):
	function gridlove_get_module_options( $type = false ) {

		$options = array(
			'posts' => array(
				'simple_layouts' => gridlove_get_simple_layouts(),
				'combo_layouts' => gridlove_get_combo_layouts(),
				'slider_layouts' => gridlove_get_slider_layouts(),
				'masonry_layouts' => gridlove_get_masonry_layouts(),
				'layout_types' => array(
					'simple' => esc_html__( 'Simple', 'gridlove' ),
					'combo' => esc_html__( 'Combo', 'gridlove' ),
					'slider' => esc_html__( 'Slider', 'gridlove' ) ,
					'masonry' => esc_html__( 'Masonry', 'gridlove' ) ,
				),
				'cats' => get_categories( array( 'hide_empty' => false, 'number' => 0 ) ),
				'time' => gridlove_get_time_diff_opts(),
				'order' => gridlove_get_post_order_opts(),
				'formats' => gridlove_get_post_format_opts(),
			),

			'cats' => array(
				'simple_layouts' => gridlove_get_cats_simple_layouts(),
				'slider_layouts' => gridlove_get_cats_slider_layouts(),
				'layout_types' => array( 'simple' => esc_html__( 'Simple', 'gridlove' ), 'slider' => esc_html__( 'Slider', 'gridlove' ) ),
				'cats' => get_categories( array( 'hide_empty' => false, 'number' => 0 ) ),
			),

			'text' => array(
			)
		);

		$custom_post_types = gridlove_get_custom_post_types();

		if ( !empty( $custom_post_types ) ) {
			foreach ( $custom_post_types as $custom_post_type ) {
				$options[$custom_post_type] = array(
					'simple_layouts' => gridlove_get_simple_layouts(),
					'combo_layouts' => gridlove_get_combo_layouts(),
					'slider_layouts' => gridlove_get_slider_layouts(),
					'masonry_layouts' => gridlove_get_masonry_layouts(),
					'layout_types' => array(
						'simple' => esc_html__( 'Simple', 'gridlove' ),
						'combo' => esc_html__( 'Combo', 'gridlove' ),
						'slider' => esc_html__( 'Slider', 'gridlove' ) ,
						'masonry' => esc_html__( 'Masonry', 'gridlove' ) ,
					),
					'time' => gridlove_get_time_diff_opts(),
					'order' => gridlove_get_post_order_opts(),
					'formats' => gridlove_get_post_format_opts(),
					'taxonomies' => gridlove_get_taxonomies( $custom_post_type )
				);
			}
		}

		if ( !empty( $type ) && array_key_exists( $type, $options ) ) {
			return $options[$type];
		}

		return $options;

	}
endif;


/**
 * Get modules
 *
 * Functions parses module page template data and sets current module array
 *
 * @return array Modules data
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_modules' ) ):
	function gridlove_get_modules( ) {

		$meta = gridlove_get_page_meta( get_the_ID() );

		if ( empty( $meta['modules'] ) ) {
			return false;
		}

		$modules = $meta['modules'];

		if ( $meta['pagination'] != 'none' ) {

			$modules = gridlove_set_paginated_module( $modules, $meta['pagination'] );

		}
		return $modules;

	}
endif;


/**
 * Get module layout
 *
 * Functions gets current post layout for specific module
 *
 * @param array   $module Module data
 * @return array Params for current layout
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_layout' ) ):
	function gridlove_get_module_layout( $module ) {

		return gridlove_parse_layout_params( $module[ $module['layout_type'].'_layout' ] , $module['layout_type'] );
	}
endif;

/**
 * Is module slider
 *
 * Check if slider is applied to module
 *
 * @param array   $module Module data
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'gridlove_module_is_slider' ) ):
	function gridlove_module_is_slider( $module ) {

		if ( ( $module['type'] == 'posts' || $module['type'] == 'cats' || isset( $module['cpt'] ) )  && $module['layout_type'] == 'slider' ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Get module autoplay
 *
 * Check slider module autoplay options and display autoplay time
 *
 * @param array   $module Module data
 * @return int    0 or number of milliseconds
 * @since  1.2
 */

if ( !function_exists( 'gridlove_module_get_autoplay' ) ):
	function gridlove_module_get_autoplay( $module ) {

		if ( isset( $module['autoplay'] ) && !empty( $module['autoplay'] ) &&  isset( $module['autoplay_time'] ) && !empty( $module['autoplay_time'] ) ) {
			return absint( $module['autoplay_time'] ) * 1000;
		}

		return 0;
	}
endif;



/**
 * Is module paginated
 *
 * Check if current module has a pagination
 *
 * @param unknown $m_ind current module index
 * @return pagination string or false
 * @since  1.0
 */

if ( !function_exists( 'gridlove_module_is_paginated' ) ):
	function gridlove_module_is_paginated( $module ) {

		if ( isset( $module['paginated'] ) && !empty( $module['paginated'] ) ) {
			return $module['paginated'];
		}

		return false;
	}
endif;


/**
 * Set paginated module
 *
 * Get last posts module index so we know which module we should apply pagination to
 *
 * @param array   $modules    Modules data
 * @param string  $pagination Pagination type
 * @return array Modules data with paginated argument set
 * @since  1.0
 */

if ( !function_exists( 'gridlove_set_paginated_module' ) ):
	function gridlove_set_paginated_module( $modules, $pagination ) {

		$last_module_index = false;

		if ( !empty( $modules ) ) {

			foreach ( $modules as $n => $module ) {
				if ( in_array($module['type'], array('posts', 'cpt') ) && !gridlove_module_is_slider( $module ) ) {
					$last_module_index = $n;
				}
			}

			if ( $last_module_index !== false ) {

				$modules[$last_module_index]['paginated'] = $pagination;

				if ( gridlove_module_template_is_paged() ) {
					$modules = gridlove_parse_paged_module_template( $modules );
				}
			}

		}

		return $modules;

	}
endif;

/**
 * Module template is paged
 *
 * Check if we are on paginated modules page
 *
 * @return int|false
 * @since  1.0
 */

if ( !function_exists( 'gridlove_module_template_is_paged' ) ):
	function gridlove_module_template_is_paged() {
		$current_page = is_front_page() ? absint( get_query_var( 'page' ) ) : absint( get_query_var( 'paged' ) );
		return $current_page > 1 ? $current_page : false;
	}
endif;


/**
 * Parse paged module template
 *
 * When we are on paginated module page
 * pull only the last posts module and its section
 * but check queries for other modules
 *
 * @param array   $modules existing modules data
 * @return array Paginated module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_parse_paged_module_template' ) ):
	function gridlove_parse_paged_module_template( $modules ) {
		if ( !empty( $modules ) ) {

			foreach ( $modules as $m_ind => $module ) {

				if ( gridlove_module_is_paginated( $module ) ) {

					$cut_modules = array( 0 => $module );

					return $cut_modules;

				} else {

					if ( isset( $module['unique'] ) && !empty( $module['unique'] ) && !empty( $module['active'] ) ) {
						gridlove_get_module_query( $module );
					}
				}
			}
		}

	}
endif;




/**
 * Get module heading
 *
 * Function gets heading/title html for current module
 *
 * @param array   $module Module data
 * @return string HTML output
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_heading' ) ):
	function gridlove_get_module_heading( $module ) {

		$args = array();

		if ( !empty( $module['title'] ) && empty( $module['hide_title'] ) ) {

			$args['title'] = '<h2>'.$module['title'].'</h2>';
		}

		$args['actions'] = '';

		if ( gridlove_module_is_slider( $module ) ) {
			$args['actions'].= '<div class="gridlove-slider-controls" data-items="'.esc_attr( count( gridlove_get_module_layout( $module ) ) ).'" data-autoplay="'.esc_attr( gridlove_module_get_autoplay( $module ) ).'"></div>';
		}

		if ( isset( $module['more_link'] ) && !empty( $module['more_link'] ) && !empty( $module['more_text'] ) && !empty( $module['more_url'] ) ) {
			$args['actions'].= '<a class="gridlove-action-link" href="'.esc_url( $module['more_url'] ).'">'.$module['more_text'].'</a>';
		}

		return !empty( $args ) ? gridlove_get_heading( $args ) : '';

	}
endif;


/**
 * Get module query
 *
 * @param array   $module Module data
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_query' ) ):
	function gridlove_get_module_query( $module, $paged = false ) {

		global $gridlove_unique_module_posts;

		$module = wp_parse_args( $module, gridlove_get_module_defaults( $module['type'] ) );

		$args['ignore_sticky_posts'] = 1;

		if ( !empty( $module['manual'] ) ) {

			$args['posts_per_page'] = absint( count( $module['manual'] ) );
			$args['orderby'] =  'post__in';
			$args['post__in'] =  $module['manual'];
			$args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) ); //support all existing public post types

		} else {

			$args['post_type'] = 'post';
			$args['posts_per_page'] = absint( $module['limit'] );

			if ( !empty( $module['cat'] ) ) {

				if ( $module['cat_child'] ) {
					$child_cat_ids = array();
					foreach ( $module['cat'] as $parent ) {
						$child_cats = get_categories( array( 'child_of' => $parent ) );
						if ( !empty( $child_cats ) ) {
							foreach ( $child_cats as $child ) {
								$child_cat_ids[] = $child->term_id;
							}
						}
					}
					$module['cat'] = array_merge( $module['cat'], $child_cat_ids );
				}

				$args['category__' . $module['cat_inc_exc']] = $module['cat'];
			}

			if ( !empty( $module['tag'] ) ) {
				$args['tag__' . $module['tag_inc_exc']] = gridlove_get_tax_term_id_by_slug( $module['tag'] );
			}

			if ( !empty( $module['format'] ) ) {

				if ( $module['format'] == 'standard' ) {

					$terms = array();
					$formats = get_theme_support( 'post-formats' );
					if ( !empty( $formats ) && is_array( $formats[0] ) ) {
						foreach ( $formats[0] as $format ) {
							$terms[] = 'post-format-'.$format;
						}
					}
					$operator = 'NOT IN';

				} else {
					$terms = array( 'post-format-'.$module['format'] );
					$operator = 'IN';
				}

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'post_format',
						'field'    => 'slug',
						'terms'    => $terms,
						'operator' => $operator
					)
				);
			}


			$args['orderby'] = $module['order'];
			$args['order'] = $module['sort'];

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {

				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();

			}

			if ( $time_diff = $module['time'] ) {
				$args['date_query'] = array( 'after' => date( 'Y-m-d', gridlove_calculate_time_diff( $time_diff ) ) );
			}

			if ( !empty( $gridlove_unique_module_posts ) ) {
				$args['post__not_in'] = $gridlove_unique_module_posts;
			}
		}

		if ( $paged ) {
			$args['paged'] = $paged;
		}

		if ( gridlove_module_is_content_inject( $module ) ) {
			$args['posts_per_page']--;
		}

		$args = apply_filters('gridlove_modify_module_query', $args ); //Allow child themes or plugins to modify

		$query = new WP_Query( $args );

		if ( $module['unique'] && !is_wp_error( $query ) && !empty( $query ) ) {

			foreach ( $query->posts as $p ) {
				$gridlove_unique_module_posts[] = $p->ID;
			}
		}

		return $query;

	}
endif;



/**
 * Get CPT module query
 *
 * @param array   $module Module data
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_module_cpt_query' ) ):
	function gridlove_get_module_cpt_query( $module, $paged = false ) {

		global $gridlove_unique_module_posts;

		$module = wp_parse_args( $module, gridlove_get_module_defaults( $module['type'] ) );

		$args['ignore_sticky_posts'] = 1;

		if ( !empty( $module['manual'] ) ) {

			$args['posts_per_page'] = absint( count( $module['manual'] ) );
			$args['orderby'] =  'post__in';
			$args['post__in'] =  $module['manual'];
			$args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) ); //support all existing public post types

		} else {

			$args['post_type'] = $module['type'];
			$args['posts_per_page'] = absint( $module['limit'] );
			$args['orderby'] = $module['order'];
			$args['order'] = $module['sort'];

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {

				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();

			}

			if ( !empty( $module['tax'] ) ) {
				$taxonomies = array();
				foreach ( $module['tax'] as $k => $v ) {

					$temp = array();
					if ( !empty( $v ) ) {
						$temp['fields'] = 'id';
						$temp['taxonomy'] = $k;
						$temp['terms'] = $v;
						$temp['operator'] = $module["{$k}_inc_exc"] == 'not_in' ? 'NOT IN' : 'IN';
						$taxonomies[] = $temp;
					}
				}
				$args['tax_query'] = $taxonomies;
			}

			if ( $time_diff = $module['time'] ) {
				$args['date_query'] = array( 'after' => date( 'Y-m-d', gridlove_calculate_time_diff( $time_diff ) ) );
			}

			if ( !empty( $gridlove_unique_module_posts ) ) {
				$args['post__not_in'] = $gridlove_unique_module_posts;
			}
		}

		if ( $paged ) {
			$args['paged'] = $paged;
		}

		if ( gridlove_module_is_content_inject( $module ) ) {
			$args['posts_per_page']--;
		}

		$args = apply_filters('gridlove_modify_module_cpt_query', $args ); //Allow child themes or plugins to modify

		$query = new WP_Query( $args );

		if ( $module['unique'] && !is_wp_error( $query ) && !empty( $query ) ) {

			foreach ( $query->posts as $p ) {
				$gridlove_unique_module_posts[] = $p->ID;
			}
		}

		return $query;

	}
endif;



/**
 * Get cover area query for modules template
 *
 * @return object WP_query
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_modules_cover_query' ) ):
	function gridlove_get_modules_cover_query() {

		$cover = gridlove_get_page_meta( get_the_ID(), 'cover' );

		global $gridlove_unique_module_posts;

		$args['ignore_sticky_posts'] = 1;

		if ( !empty( $cover['manual'] ) ) {

			$args['orderby'] =  'post__in';
			$args['post__in'] =  $cover['manual'];
			$args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) ); //support all existing public post types

		} else {
			
			$args['post_type'] = $cover['post_type'];
			$post_type_with_taxonomies = gridlove_get_post_type_with_taxonomies($cover['post_type']);
			$args['posts_per_page'] = absint( $cover['limit'] ) ;
			
			
			if(!empty($post_type_with_taxonomies->taxonomies)){
				foreach ( $post_type_with_taxonomies->taxonomies as $taxonomy ) {
					$taxonomy_id = gridlove_patch_taxonomy_id($taxonomy['id']);
					
					if(empty($cover[$taxonomy_id . '_inc_exc']) || empty($cover[$taxonomy_id])){
						continue;
					}
					
					$operator = $cover[$taxonomy_id . '_inc_exc'] === 'not_in' ? 'NOT IN' : 'IN';
					
					if($taxonomy['hierarchical']){
						$args['tax_query'][] = array(
							'taxonomy' => $taxonomy['id'],
							'field'    => 'id',
							'terms'    => $cover[$taxonomy_id],
							'operator' => $operator,
							'include_children' => boolval($cover[$taxonomy_id . '_child'])
						);
					}else{
						$args['tax_query'][] = array(
							'taxonomy' => $taxonomy['id'],
							'field'    => 'id',
							'terms'    => gridlove_get_tax_term_id_by_slug($cover[$taxonomy_id], $taxonomy['id']),
							'operator' => $operator
						);
					}
				}
			}
			
			
			if ( !empty( $cover['format'] ) ) {

				if ( $cover['format'] == 'standard' ) {

					$terms = array();
					$formats = get_theme_support( 'post-formats' );
					if ( !empty( $formats ) && is_array( $formats[0] ) ) {
						foreach ( $formats[0] as $format ) {
							$terms[] = 'post-format-'.$format;
						}
					}
					$operator = 'NOT IN';

				} else {
					$terms = array( 'post-format-'.$cover['format'] );
					$operator = 'IN';
				}

				$args['tax_query'][] = array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $terms,
					'operator' => $operator
				);
			}

			$args['orderby'] = $cover['order'];
			$args['order'] = $cover['sort'];

			if ( $args['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = ev_get_meta_key();
			}

			if ( $args['orderby'] == 'title' ) {
				$args['order'] = 'ASC';
			}

			if ( $time_diff = $cover['time'] ) {
				$args['date_query'] = array( 'after' => date( 'Y-m-d', gridlove_calculate_time_diff( $time_diff ) ) );
			}

		}

		$args = apply_filters('gridlove_modify_module_cover_query', $args ); //Allow child themes or plugins to modify

		$query = new WP_Query( $args );

		if ( $cover['unique'] && !is_wp_error( $query ) && !empty( $query ) ) {

			foreach ( $query->posts as $p ) {
				$gridlove_unique_module_posts[] = $p->ID;
			}
		}

		return $query;

	}
endif;


/**
 * Is module slider
 *
 * Check if slider is applied to module
 *
 * @param array   $module Module data
 * @return bool
 * @since  1.0
 */

if ( !function_exists( 'gridlove_module_is_content_inject' ) ):
	function gridlove_module_is_content_inject( $module ) {

		if ( $module['content_inject'] && !empty( $module['content_position'] ) && !empty( $module['content'] ) ) {
			return absint( $module['content_position'] );
		}

		return false;
	}
endif;


/**
 * Get posts from manually selected field in modules  
 *
 * @since  1.7 
 *
 * @param srting $post_ids - Selected posts ids from choose manually meta field
 * @return array - List of selected posts or empty list
 */
if ( !function_exists( 'gridlove_get_manually_selected_posts' ) ):
	function gridlove_get_manually_selected_posts( $post_ids, $module_type = 'posts' ) {
		
		if ( empty($post_ids) ) {
			return array();
		}

		$post_type = in_array($module_type, array('posts', 'cover')) ? array_keys( get_post_types( array( 'public' => true ) ) ) : $module_type;

		$get_selected_posts = get_posts( 
			array(
				'post__in' => $post_ids, 
				'orderby' => 'post__in', 
				'post_type' => $post_type, 
				'posts_per_page' => '-1'
			) 
		);

		return wp_list_pluck( $get_selected_posts, 'post_title', 'ID' );
	}
endif;


/**
 * Display manualy selected posts  
 *
 * @since  1.7 
 *
 * @param array $posts - Array of manualy selected posts
 * @return HTML - Title of manualy selected post
 */
if ( !function_exists( 'gridlove_display_manually_selected_posts' ) ):
	function gridlove_display_manually_selected_posts($posts) {
		
		if ( empty($posts) ) {
			return;
		}

		$output = '';
	 	foreach ( $posts as $id => $title ){
			$output .= '<span><button type="button" class="ntdelbutton" data-id="'. esc_attr($id) .'"><span class="remove-tag-icon"></span></button><span class="gridlove-searched-title">'. esc_html( $title ). '</span></span>';
		} 

		echo $output;
	}
endif;


/**
 * Now when taxonomies are dynamical in cover area depending on post type we have to overwrite old settings.
 * For Category to cat and for post_tag to tag
 *
 * @string $taxonomy_id
 * @since 1.7
 * @return $taxonomy_id
 */
if(!function_exists('gridlove_patch_category_and_tags')):
	function gridlove_patch_taxonomy_id($taxonomy_id){
		
		if ( in_array( $taxonomy_id, array( 'category', 'post_tag' ) ) ) {
			if ( $taxonomy_id === 'category' ) {
				$taxonomy_id = 'cat';
			}
			if ( $taxonomy_id === 'post_tag' ) {
				$taxonomy_id = 'tag';
			}
		}
		
		return $taxonomy_id;
	}
endif;


/**
 * Get default cover data
 *
 * @return array
 * @since 1.7
 */
if(!function_exists('gridlove_get_cover_post_data_for_saving')):
	function gridlove_get_cover_post_data_for_saving($append = array()){
		$default =  array(
			'layout',
			'limit',
			'manual',
			'time',
			'order',
			'format',
			'sort',
			'content',
			'bg_image',
			'post_type',
			'unique'
		);
		
		$data = gridlove_parse_args($append, $default);
		
		return apply_filters('gridlove_modify_cover_post_data',$data);
	}
endif;
?>
