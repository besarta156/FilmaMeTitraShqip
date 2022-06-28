<?php

/**
 * Register menus
 *
 * Callback function theme menus registration and init
 * 
 * @return void
 * @since  1.0
 */

add_action( 'init', 'gridlove_register_menus' );

if( !function_exists( 'gridlove_register_menus' ) ) :
    function gridlove_register_menus() {
	    register_nav_menu('gridlove_main_menu', esc_html__( 'Main Menu' , 'gridlove'));
	   	register_nav_menu('gridlove_social_menu', esc_html__( 'Social Menu' ,'gridlove'));
	   	register_nav_menu('gridlove_secondary_menu_1', esc_html__( 'Secondary Menu 1' , 'gridlove'));
	   	register_nav_menu('gridlove_secondary_menu_2', esc_html__( 'Secondary Menu 2' , 'gridlove'));
    }
endif;


/**
 * wp_setup_nav_menu_item callback
 * 
 * Get our meta data from nav menu
 *
 * @since  1.0
 */

add_filter( 'wp_setup_nav_menu_item', 'gridlove_get_menu_meta' );

if ( !function_exists( 'gridlove_get_menu_meta' ) ):
	function gridlove_get_menu_meta( $menu_item ) {
		
		$defaults = array( 
			'category_posts' => 0
		);

		$meta = get_post_meta( $menu_item->ID, '_gridlove_meta', true );
		$meta = wp_parse_args( $meta, $defaults );
		$menu_item->gridlove_meta = $meta;
		
		return $menu_item;
	}
endif;


/**
 * wp_update_nav_menu_item callback
 * 
 * Store values from custom fields in nav menu
 *
 * @since  1.0
 */

add_action( 'wp_update_nav_menu_item', 'gridlove_update_menu_meta', 10, 3 );


if ( !function_exists( 'gridlove_update_menu_meta' ) ):
	function gridlove_update_menu_meta( $menu_id, $menu_item_db_id, $args ) {

		$meta = array();

		if( isset( $_REQUEST['menu-item-gridlove-category-posts'][$menu_item_db_id] ) ) {
			
			$meta['category_posts'] = 1;
		
		}

		if(!empty($meta)){
			update_post_meta( $menu_item_db_id, '_gridlove_meta', $meta );
		} else {
			delete_post_meta( $menu_item_db_id, '_gridlove_meta' );
		}
	
		
	}
endif;




/**
 * wp_edit_nav_menu_walker callback
 * 
 * Add custom fields to nav menu form
 *
 * @since  1.0
 */

add_filter( 'wp_edit_nav_menu_walker', 'gridlove_edit_menu_walker', 10, 2 );

if ( !function_exists( 'gridlove_edit_menu_walker' ) ):
	function gridlove_edit_menu_walker( $walker, $menu_id ) {

		class gridlove_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

			public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				
				parent::start_el( $default_output, $item, $depth, $args, $id );

				$inject_html = '';

				if ( $item->object == 'category' ) {
					$inject_html .= '<p class="description">
		                <label for="menu-item-gridlove-category-posts['.$item->db_id.']">
		        		<input type="checkbox" id="menu-item-gridlove-category-posts['.$item->db_id.']" class="widefat" name="menu-item-gridlove-category-posts['.$item->db_id.']" value="1" '.checked( $item->gridlove_meta['category_posts'], 1, false ). ' />
		                '.esc_html__( 'Automatically display category posts in submenu', 'gridlove' ).'</label>
		            </p>';
				}

				ob_start();
				do_action( 'wp_nav_menu_item_custom_fields', $item->ID, $item, $depth, $args );
				$inject_html .= ob_get_clean();
				
				$new_output = preg_replace( '/(?=<div.*submitbox)/', $inject_html, $default_output );

				$output .= $new_output;


			}

		}

		return 'gridlove_Walker_Nav_Menu_Edit';
	}
endif;



/**
 * nav_menu_css_class callback
 *
 * Used to add/modify CSS classes in nav menu
 *  
 * @since  1.0
 */

add_filter( 'nav_menu_css_class', 'gridlove_modify_nav_menu_classes', 10, 2 );

if ( !function_exists( 'gridlove_modify_nav_menu_classes' ) ):
	function gridlove_modify_nav_menu_classes( $classes, $item ) {

		if ( $item->object == 'category' && isset( $item->gridlove_meta['category_posts'] ) && $item->gridlove_meta['category_posts'] ) {
			$classes[] = 'menu-item-has-children gridlove-category-menu';
		}

		return $classes;

	}
endif;


/**
 * Display category posts in nav menu
 *
 * @since  1.0
 */

if ( !function_exists( 'gridlove_get_nav_menu_category_posts' ) ) :

	function gridlove_get_nav_menu_category_posts( $cat_id ) {
		
	
		$args = array(
			'post_type'    => 'post',
			'cat'      => $cat_id,
			'posts_per_page' => 4
		);

		
		$output = '<li class="gridlove-menu-posts">';
		
		ob_start();

		$args['ignore_sticky_posts'] = 1;

		$menu_posts = new WP_Query( $args );

		if ( $menu_posts->have_posts() ) :

			while ( $menu_posts->have_posts() ) : $menu_posts->the_post(); ?>

				<article <?php post_class(''); ?>>

		            <?php if ( $fimg = gridlove_get_featured_image( 'thumbnail' ) ) : ?>
		                <div class="entry-image">
		                <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
		                   	<?php echo $fimg; ?>
		                </a>
		                </div>
		            <?php endif; ?>

		            <div class="entry-header">
		                <?php the_title( sprintf( '<h6><a href="%s">', esc_url( get_permalink() ) ), '</a></h6>' ); ?>
		            </div>

				</article>

			<?php endwhile;

		endif;

		wp_reset_postdata();

		$output .= ob_get_clean();

		$output .= '</li>';

		return $output;
	
	}

endif;


/**
 * walker_nav_menu_start_el callback
 *
 * Used to display specific data in nav menu on website front-end
 *  
 * @since  1.0
 */

add_filter( 'walker_nav_menu_start_el', 'gridlove_walker_nav_menu_start_el', 10, 4 );

function gridlove_walker_nav_menu_start_el( $item_output, $item, $depth, $args ){

	if ( isset( $item->gridlove_meta['category_posts'] ) && $item->gridlove_meta['category_posts'] ) {
		
			$item_output .= '<ul class="sub-menu">';
			$item_output .= gridlove_get_nav_menu_category_posts( $item->object_id );
			$item_output .= '</ul>';

	}

	return $item_output;
}

?>
