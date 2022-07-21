<?php

/**
 * Load post metaboxes
 * 
 * Callback function for post metaboxes load
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_load_post_metaboxes' ) ) :
	function gridlove_load_post_metaboxes() {

		/* Layout metabox */
		add_meta_box(
			'gridlove_post_layout',
			esc_html__( 'Layout', 'gridlove' ),
			'gridlove_post_layout_metabox',
			'post',
			'side',
			'default'
		);

		/* Sidebar metabox */
		add_meta_box(
			'gridlove_post_sidebar',
			esc_html__( 'Sidebar', 'gridlove' ),
			'gridlove_post_sidebar_metabox',
			'post',
			'side',
			'default'
		);

		/* Display options metabox */
		add_meta_box(
			'gridlove_display_options',
			esc_html__( 'Display options', 'gridlove' ),
			'gridlove_post_display_options_metabox',
			'post',
			'side',
			'default'
		);

	}
endif;


/**
 * Save post meta
 * 
 * Callback function to save post meta data
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_save_post_metaboxes' ) ) :
	function gridlove_save_post_metaboxes( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['gridlove_post_metabox_nonce'] ) || !wp_verify_nonce( $_POST['gridlove_post_metabox_nonce'], 'gridlove_post_metabox_save' ) ) {
   			return;
		}


		if ( $post->post_type == 'post' && isset( $_POST['gridlove'] ) ) {
			$post_type = get_post_type_object( $post->post_type );
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;

			$gridlove_meta = array();

			if( isset( $_POST['gridlove']['sidebar'] ) &&  !empty($_POST['gridlove']['sidebar'])){
				foreach( $_POST['gridlove']['sidebar'] as $key => $value ){
					if(  $value != 'inherit' ){
						$gridlove_meta['sidebar'][$key] = $value; 
					}			
				}
			}

			if( isset( $_POST['gridlove']['layout'] ) &&  $_POST['gridlove']['layout'] != 'inherit' ){
				$gridlove_meta['layout'] = $_POST['gridlove']['layout'];
			}

			if( isset( $_POST['gridlove']['display'] ) &&  !empty($_POST['gridlove']['display'])){
				foreach( $_POST['gridlove']['display'] as $key => $value ){
					if(  $value != 'inherit' ){
						$gridlove_meta['display'][$key] = $value; 
					}			
				}
			}
			
			if(!empty($gridlove_meta)){
				update_post_meta( $post_id, '_gridlove_meta', $gridlove_meta );
			} else {
				delete_post_meta( $post_id, '_gridlove_meta');
			}

		}
	}
endif;


/**
 * Layout metabox
 * 
 * Callback function to create layout metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_post_layout_metabox' ) ) :
	function gridlove_post_layout_metabox( $object, $box ) {
		
		wp_nonce_field( 'gridlove_post_metabox_save', 'gridlove_post_metabox_nonce' );

		$gridlove_meta = gridlove_get_post_meta( $object->ID );
		$layouts = gridlove_get_single_layouts( true );
?>
	  	<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $layouts as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $gridlove_meta['layout'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo esc_html( $layout['title'] ); ?></span>
	  			<input type="radio" class="gridlove-hidden" name="gridlove[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $gridlove_meta['layout'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Choose a layout', 'gridlove' ); ?></p>

	  <?php
	}
endif;



/**
 * Sidebar metabox
 * 
 * Callback function to create sidebar metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_post_sidebar_metabox' ) ) :
	function gridlove_post_sidebar_metabox( $object, $box ) {
		

		$sidebar = gridlove_get_post_meta( $object->ID, 'sidebar' );
		
		$sidebars_lay = gridlove_get_sidebar_layouts( true );
		$sidebars = gridlove_get_sidebars_list( true );
?>
	  	<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $sidebars_lay as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $sidebar['position'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo esc_html($layout['title']); ?></span>
	  			<input type="radio" class="gridlove-hidden" name="gridlove[sidebar][position]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $sidebar['position'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Display sidebar', 'gridlove' ); ?></p>

	  <?php if ( !empty( $sidebars ) ): ?>

	  	<p><select name="gridlove[sidebar][standard]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $sidebar['standard'] );?>><?php echo esc_html($name); ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose standard sidebar to display', 'gridlove' ); ?></p>

	  	<p><select name="gridlove[sidebar][sticky]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $sidebar['sticky'] );?>><?php echo esc_html($name); ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose sticky sidebar to display', 'gridlove' ); ?></p>

	  <?php endif; ?>
	  <?php
	}
endif;


/**
 * Display options metabox
 * 
 * Callback function to create display options metabox
 * 
 * @since  1.2
 */

if ( !function_exists( 'gridlove_post_display_options_metabox' ) ) :
	function gridlove_post_display_options_metabox( $object, $box ) {
		
		$gridlove_meta = gridlove_get_post_meta( $object->ID );
		
		$meta = $gridlove_meta['display'];

		$options = array(
					'fimg' => esc_html( 'Featured image', 'gridlove'),
					'headline' => esc_html( 'Headline (excerpt)', 'gridlove'),
					'tags'	=> esc_html( 'Tags', 'gridlove'),
					'author' => esc_html( 'Author Area', 'gridlove'),
					'related' => esc_html( 'Related posts', 'gridlove'),
					'prev_next' => esc_html( 'Previous/Next posts', 'gridlove'),
					'ad_above' => esc_html( 'Ad above content', 'gridlove'),
					'ad_below' => esc_html( 'Ad below content', 'gridlove'),
			);

		$values = array(
			'inherit' => esc_html( 'Inherit', 'gridlove'),
			1 => esc_html( 'On', 'gridlove'),
			0 => esc_html( 'Off', 'gridlove'),
			);

		foreach($options as $id => $option): ?>
		
			<p><label><?php echo $option; ?>:</label> <select name="gridlove[display][<?php echo esc_attr($id); ?>]" class="alignright">
				<?php foreach($values as $value => $title): ?>
					<?php if( in_array( $id, array( 'ad_below', 'ad_above' ) ) && $value === 'inherit') { 
						continue;
					} ?>
					<option value="<?php echo esc_attr($value); ?>" <?php selected($meta[$id], $value); ?>><?php echo $title; ?></option>
				<?php endforeach; ?>
			</select></p>

		<?php endforeach; ?>	  

	   <p class="description"><?php esc_html_e( 'Optionally override global single post display options', 'gridlove' ); ?></p>

	  <?php
	}
endif;
?>
