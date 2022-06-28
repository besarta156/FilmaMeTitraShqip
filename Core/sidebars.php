<?php

/**
 * Register sidebars
 *
 * Callback function for theme sidebars registration and init
 * 
 * @return void
 * @since  1.0
 */

add_action( 'widgets_init', 'gridlove_register_sidebars' );

if ( !function_exists( 'gridlove_register_sidebars' ) ) :
	function gridlove_register_sidebars() {
		
		/* Default Sidebar */
		register_sidebar(
			array(
				'id' => 'gridlove_default_sidebar',
				'name' => esc_html__( 'Default Sidebar', 'gridlove' ),
				'description' => esc_html__( 'This is default sidebar.', 'gridlove' ),
				'before_widget' => '<div id="%1$s" class="widget gridlove-box %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);

		/* Default Sticky Sidebar */
		register_sidebar(
			array(
				'id' => 'gridlove_default_sticky_sidebar',
				'name' => esc_html__( 'Default Sticky Sidebar', 'gridlove' ),
				'description' => esc_html__( 'This is default sticky sidebar. Sticky means that it will be always pinned to top while you are scrolling through your website content.', 'gridlove' ),
				'before_widget' => '<div id="%1$s" class="widget gridlove-box %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);

		/* Header Sidebar */
		register_sidebar(
			array(
				'id' => 'gridlove_header_sidebar',
				'name' => esc_html__( 'Header Sidebar', 'gridlove' ),
				'description' => esc_html__( 'This is open/close sidebar area which you can access in header.', 'gridlove' ),
				'before_widget' => '<div id="%1$s" class="widget gridlove-box %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);


		/* Add sidebars from theme options */
		$custom_sidebars = gridlove_get_option( 'sidebars' );

		if (!empty( $custom_sidebars ) ){
			foreach ( $custom_sidebars as $key => $title) {
				
				if ( is_numeric($key) ) {
					register_sidebar(
						array(
							'id' => 'gridlove_sidebar_'.$key,
							'name' => $title,
							'description' => '',
							'before_widget' => '<div id="%1$s" class="widget gridlove-box %2$s">',
							'after_widget' => '</div>',
							'before_title' => '<h4 class="widget-title">',
							'after_title' => '</h4>'
						)
					);
				}
			}
		}


		/* Footer Sidebar Area 1*/
		register_sidebar(
			array(
				'id' => 'gridlove_footer_sidebar_1',
				'name' => esc_html__( 'Footer Column 1', 'gridlove' ),
				'description' => esc_html__( 'This is footer area column 1.', 'gridlove' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);

		/* Footer Sidebar Area 2*/
		register_sidebar(
			array(
				'id' => 'gridlove_footer_sidebar_2',
				'name' => esc_html__( 'Footer Column 2', 'gridlove' ),
				'description' => esc_html__( 'This footer area column 2.', 'gridlove' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);


		/* Footer Sidebar Area 3*/
		register_sidebar(
			array(
				'id' => 'gridlove_footer_sidebar_3',
				'name' => esc_html__( 'Footer Column 3', 'gridlove' ),
				'description' => esc_html__( 'This footer area column 3.', 'gridlove' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);

		/* Footer Sidebar Area 4 */
		register_sidebar(
			array(
				'id' => 'gridlove_footer_sidebar_4',
				'name' => esc_html__( 'Footer Column 4', 'gridlove' ),
				'description' => esc_html__( 'This is footer area column 4.', 'gridlove' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widget-title">',
				'after_title' => '</h4>'
			)
		);

	}

endif;




?>
