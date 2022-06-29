<?php 

/**
 * Save category meta 
 * 
 * Callback function to save category meta data
 * 
 * @since  1.0
 */

add_action( 'edited_category', 'gridlove_save_category_meta_fields', 10, 2 );
add_action( 'create_category', 'gridlove_save_category_meta_fields', 10, 2 );

if ( !function_exists( 'gridlove_save_category_meta_fields' ) ) :
	function gridlove_save_category_meta_fields( $term_id ) {

		if ( isset( $_POST['gridlove'] ) ) {

			$meta = array();

			if( isset( $_POST['gridlove']['color'] ) ) {

				if( $_POST['gridlove']['color']['type'] != 'inherit' ){
					$meta['color'] = $_POST['gridlove']['color'];
				}

				gridlove_update_cat_colors( $term_id, $_POST['gridlove']['color']['value'], $_POST['gridlove']['color']['type'] );
			}

			if( isset( $_POST['gridlove']['layout'] ) ) { 
				if( $_POST['gridlove']['layout']['type'] != 'inherit' ){
					$meta['layout'] = $_POST['gridlove']['layout'];
				}
			}

			if ( isset( $_POST['gridlove']['image'] ) ) {
				$meta['image'] = $_POST['gridlove']['image'];
			}
			
			if( !empty( $meta) ){
				update_term_meta( $term_id, '_gridlove_meta', $meta);
			} else {
				delete_term_meta( $term_id, '_gridlove_meta');
			}
			
		}

	}
endif;


/**
 * Add category meta 
 * 
 * Callback function to load category meta fields on "new category" screen
 * 
 * @since  1.0
 */

add_action( 'category_add_form_fields', 'gridlove_category_add_meta_fields', 10, 2 );

if ( !function_exists( 'gridlove_category_add_meta_fields' ) ) :
	function gridlove_category_add_meta_fields() {
		$meta = gridlove_get_category_meta();
		$cover = gridlove_get_cover_layouts( false, true, array('custom') );
		$main = gridlove_get_simple_layouts();
		$masonry = gridlove_get_masonry_layouts();
		$combo = gridlove_get_combo_layouts( false, true );
		$pagination = gridlove_get_pagination_layouts();
		?>
	 
	<div class="form-field">
		<label><?php esc_html_e( 'Color', 'gridlove' ); ?></label>
		<label><input type="radio" name="gridlove[color][type]" value="inherit" class="color-type" <?php checked( $meta['color']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from accent color', 'gridlove' ); ?></label>
		<label><input type="radio" name="gridlove[color][type]" value="custom" class="color-type" <?php checked( $meta['color']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom color', 'gridlove' ); ?></label>
		<div id="gridlove-color-wrap">
			<p>
			   <input name="gridlove[color][value]" type="text" class="gridlove-colorpicker" value="<?php echo esc_attr($meta['color']['value']); ?>" data-default-color="<?php echo esc_attr($meta['color']['value']); ?>"/>
			</p>

			<?php $recent_colors = get_option( 'gridlove_recent_cat_colors' ); ?>
			<?php if(!empty($recent_colors)) : ?>
				<p class="description"><?php esc_html_e( 'Recently used', 'gridlove' ); ?>:<br/>
					<?php foreach($recent_colors as $color) : ?>
						<a href="javascript:void(0);" style="background: <?php echo esc_attr($color); ?>;" class="gridlove-rec-color" data-color="<?php echo esc_attr($color); ?>"></a>
					<?php endforeach; ?>
				</p>
			<?php endif; ?>
		</div>
		<br/>	
	</div>

	<div class="form-field">
		<label><?php esc_html_e( 'Layout options', 'gridlove' ); ?></label>
		<label><input type="radio" name="gridlove[layout][type]" value="inherit" class="layout-type" <?php checked( $meta['layout']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from global category options', 'gridlove' ); ?></label>
		<label><input type="radio" name="gridlove[layout][type]" value="custom" class="layout-type" <?php checked( $meta['layout']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom layout', 'gridlove' ); ?></label>	
	</div>

	<div class="form-field gridlove-layout-opt">
		<label><?php esc_html_e( 'Cover area layout', 'gridlove' ); ?></label>
		<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $cover as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['cover'] ) ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
	  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
	  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][cover]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['cover'] );?>/>
	  		</li>
	  	<?php endforeach; ?>
	    </ul>
	</div>

	<div class="form-field gridlove-layout-opt">
		<label><?php esc_html_e( 'Number of cover area posts', 'gridlove' ); ?></label>
		<input name="gridlove[layout][cover_ppp]" type="text" class="gridlove-small-text" value="<?php echo esc_attr($meta['layout']['cover_ppp']); ?>" />
	</div>

	<div class="form-field gridlove-layout-opt">
        <label><?php esc_html_e( 'Layout type', 'gridlove' ); ?></label>
        <label><input type="radio" name="gridlove[layout][posts_layout_type]" value="main" class="posts-layout-type" <?php checked( $meta['layout']['posts_layout_type'], 'main' );?>> <?php esc_html_e( 'Simple', 'gridlove' ); ?></label>
        <label><input type="radio" name="gridlove[layout][posts_layout_type]" value="masonry" class="posts-layout-type" <?php checked( $meta['layout']['posts_layout_type'], 'masonry' );?>> <?php esc_html_e( 'Masonry', 'gridlove' ); ?></label>
    </div>

	<div class="form-field gridlove-layout-opt gridlove-layouts-type gridlove-layouts-type-main <?php echo ($meta['layout']['posts_layout_type'] == 'main') ? '' : 'inactive'; ?>">
		<label><?php esc_html_e( 'Main layout', 'gridlove' ); ?></label>
		<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $main as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['main'] ) ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
	  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
	  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][main]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['main'] );?>/>
	  		</li>
	  	<?php endforeach; ?>
	    </ul>
	</div>

	<div class="form-field gridlove-layout-opt gridlove-layouts-type gridlove-layouts-type-masonry <?php echo ($meta['layout']['posts_layout_type'] == 'masonry') ? '' : 'inactive'; ?>">
		<label><?php esc_html_e( 'Main layout', 'gridlove' ); ?></label>
		<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $masonry as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['masonry'] ) ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
	  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
	  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][masonry]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['masonry'] );?>/>
	  		</li>
	  	<?php endforeach; ?>
	    </ul>
	</div>

	<div class="form-field gridlove-layout-opt">
		<label><?php esc_html_e( 'Number of main layout posts per page', 'gridlove' ); ?></label>
		<input name="gridlove[layout][main_ppp]" type="text" class="gridlove-small-text" value="<?php echo esc_attr($meta['layout']['main_ppp']); ?>" />
	</div>

	<div class="form-field gridlove-layout-opt">
		<label><?php esc_html_e( 'Combo layout', 'gridlove' ); ?></label>
		<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $combo as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['combo'] ) ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
	  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
	  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][combo]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['combo'] );?>/>
	  		</li>
	  	<?php endforeach; ?>
	    </ul>
	</div>

	<div class="form-field gridlove-layout-opt">
		<label><?php esc_html_e( 'Pagination', 'gridlove' ); ?></label>
		<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $pagination as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['pagination'] ) ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
	  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
	  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][pagination]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['pagination'] );?>/>
	  		</li>
	  	<?php endforeach; ?>
	    </ul>
	</div>

	<div class="form-field">
		<label><?php esc_html_e( 'Image', 'gridlove' ); ?></label>
		<?php $display = $meta['image'] ? 'initial' : 'none'; ?>
		<p>
			<img id="gridlove-image-preview" src="<?php echo esc_url( $meta['image'] ); ?>" style="width: 300px;  border: 2px solid #ebebeb; display:<?php echo esc_attr( $display ); ?>;">
		</p>

		<p>
			<input type="hidden" name="gridlove[image]" id="gridlove-image-url" value="<?php echo esc_attr( $meta['image'] ); ?>"/>
			<input type="button" id="gridlove-image-upload" class="button-secondary" value="<?php esc_attr_e( 'Upload', 'gridlove' ); ?>"/>
			<input type="button" id="gridlove-image-clear" class="button-secondary" value="<?php esc_attr_e( 'Clear', 'gridlove' ); ?>" style="display:<?php echo esc_attr( $display ); ?>"/>
		</p>

		<p class="description"><?php esc_html_e( 'Upload an image for this category', 'gridlove' ); ?></p>
	</div>

	<?php
	}
endif;




/**
 * Edit category meta 
 * 
 * Callback function to load category meta fields on edit screen
 * 
 * @since  1.0
 */

add_action( 'category_edit_form_fields', 'gridlove_category_edit_meta_fields', 10, 2 );

if ( !function_exists( 'gridlove_category_edit_meta_fields' ) ) :
	function gridlove_category_edit_meta_fields( $term ) {
		$meta = gridlove_get_category_meta( $term->term_id );
		$cover = gridlove_get_cover_layouts( false, true, array('custom') );
		$main = gridlove_get_simple_layouts();
        $masonry = gridlove_get_masonry_layouts();
		$combo = gridlove_get_combo_layouts( false, true );
		$pagination = gridlove_get_pagination_layouts();
?>
	  
	 <tr class="form-field">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Color', 'gridlove' ); ?></label></th>
			<td>
				<label><input type="radio" name="gridlove[color][type]" value="inherit" class="color-type" <?php checked( $meta['color']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from accent color', 'gridlove' ); ?></label><br/>
				<label><input type="radio" name="gridlove[color][type]" value="custom" class="color-type" <?php checked( $meta['color']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom color', 'gridlove' ); ?></label>
				<div id="gridlove-color-wrap">
					<p>
					   <input name="gridlove[color][value]" type="text" class="gridlove-colorpicker" value="<?php echo esc_attr($meta['color']['value']); ?>" data-default-color="<?php echo esc_attr($meta['color']['value']); ?>"/>
					</p>

					<?php $recent_colors = get_option( 'gridlove_recent_cat_colors' ); ?>
					<?php if(!empty($recent_colors)) : ?>
						<p class="description"><?php esc_html_e( 'Recently used', 'gridlove' ); ?>:<br/>
							<?php foreach($recent_colors as $color) : ?>
								<a href="javascript:void(0);" style="background: <?php echo esc_attr($color); ?>;" class="gridlove-rec-color" data-color="<?php echo esc_attr($color); ?>"></a>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
				</div>
			</td>
		</tr>

		<tr class="form-field">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Layout options', 'gridlove' ); ?></label></th>
			<td>
				<label><input type="radio" name="gridlove[layout][type]" value="inherit" class="layout-type" <?php checked( $meta['layout']['type'], 'inherit' );?>> <?php esc_html_e( 'Inherit from global category options', 'gridlove' ); ?></label><br/>
				<label><input type="radio" name="gridlove[layout][type]" value="custom" class="layout-type" <?php checked( $meta['layout']['type'], 'custom' );?>> <?php esc_html_e( 'Set custom layout', 'gridlove' ); ?></label>	
			</td>
		</tr>


		<tr class="form-field gridlove-layout-opt">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Cover area layout', 'gridlove' ); ?></label></th>
			<td>
				<ul class="gridlove-img-select-wrap">
			  	<?php foreach ( $cover as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['cover'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][cover]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['cover'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
			</td>
		</tr>

		<tr class="form-field gridlove-layout-opt">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Number of cover area posts', 'gridlove' ); ?></label></th>
			<td>
				<input name="gridlove[layout][cover_ppp]" type="text" class="gridlove-small-text" value="<?php echo esc_attr($meta['layout']['cover_ppp']); ?>" />
			</td>
		</tr>

        <tr class="form-field gridlove-layout-opt">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Layout type', 'gridlove' ); ?></label></th>
            <td>
                <label><input type="radio" name="gridlove[layout][posts_layout_type]" value="main" class="posts-layout-type" <?php checked( $meta['layout']['posts_layout_type'], 'main' );?>> <?php esc_html_e( 'Simple', 'gridlove' ); ?></label><br/>
                <label><input type="radio" name="gridlove[layout][posts_layout_type]" value="masonry" class="posts-layout-type" <?php checked( $meta['layout']['posts_layout_type'], 'masonry' );?>> <?php esc_html_e( 'Masonry', 'gridlove' ); ?></label>
            </td>
        </tr>

		<tr class="form-field gridlove-layout-opt gridlove-layouts-type gridlove-layouts-type-main <?php echo ($meta['layout']['posts_layout_type'] == 'main') ? '' : 'inactive'; ?>">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Main layout', 'gridlove' ); ?></label></th>
			<td>
				<ul class="gridlove-img-select-wrap">
			  	<?php foreach ( $main as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['main'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][main]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['main'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
			</td>
		</tr>

		<tr class="form-field gridlove-layout-opt gridlove-layouts-type gridlove-layouts-type-masonry <?php echo ($meta['layout']['posts_layout_type'] == 'masonry') ? '' : 'inactive'; ?>">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Main layout', 'gridlove' ); ?></label></th>
			<td>
                <ul class="gridlove-img-select-wrap">
                    <?php foreach ( $masonry as $id => $layout ): ?>
                        <li>
                            <?php $selected_class = gridlove_compare( $id, $meta['layout']['masonry'] ) ? ' selected': ''; ?>
                            <img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
                            <br/><span><?php echo esc_attr($layout['title']); ?></span>
                            <input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][masonry]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['masonry'] );?>/>
                        </li>
                    <?php endforeach; ?>
                </ul>
			</td>
		</tr>

		<tr class="form-field gridlove-layout-opt">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Number of main layout posts per page', 'gridlove' ); ?></label></th>
			<td>
				<input name="gridlove[layout][main_ppp]" type="text" class="gridlove-small-text" value="<?php echo esc_attr($meta['layout']['main_ppp']); ?>" />
			</td>
		</tr>

		<tr class="form-field gridlove-layout-opt">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Combo layout', 'gridlove' ); ?></label></th>
			<td>
				<ul class="gridlove-img-select-wrap">
			  	<?php foreach ( $combo as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['combo'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][combo]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['combo'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
			</td>
		</tr>

		<tr class="form-field gridlove-layout-opt">
		<th scope="row" valign="top"><label><?php esc_html_e( 'Pagination', 'gridlove' ); ?></label></th>
			<td>
				<ul class="gridlove-img-select-wrap">
			  	<?php foreach ( $pagination as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = gridlove_compare( $id, $meta['layout']['pagination'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr( $selected_class ); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="gridlove[layout][pagination]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout']['pagination'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Image', 'gridlove' ); ?></label></th>
			<?php $display = $meta['image'] ? 'initial' : 'none'; ?>
			<td>
			<p>
				<img id="gridlove-image-preview" src="<?php echo esc_url( $meta['image'] ); ?>" style="width: 300px;  border: 2px solid #ebebeb; display:<?php echo esc_attr( $display ); ?>;">
			</p>

			<p>
				<input type="hidden" name="gridlove[image]" id="gridlove-image-url" value="<?php echo esc_attr( $meta['image'] ); ?>"/>
				<input type="button" id="gridlove-image-upload" class="button-secondary" value="<?php esc_attr_e( 'Upload', 'gridlove' ); ?>"/>
				<input type="button" id="gridlove-image-clear" class="button-secondary" value="<?php esc_attr_e( 'Clear', 'gridlove' ); ?>" style="display:<?php echo esc_attr( $display ); ?>"/>
			</p>

			<p class="description"><?php esc_html_e( 'Upload an image for this category', 'gridlove' ); ?></p>
			</td>
		</tr>

	<?php
	}
endif;


/**
 * Delete category meta 
 * 
 * Delete our custom category meta from database on category deletion
 * 
 * @return  void 
 * @since  1.0
 */

add_action( 'delete_category', 'gridlove_delete_category_meta' );

if ( !function_exists( 'gridlove_delete_category_meta' ) ):
	function gridlove_delete_category_meta( $term_id ) {
		
		//Check for category colors deletion
		$colors = get_option( 'gridlove_cat_colors' );

		if ( !empty($colors) && array_key_exists( $term_id, $colors ) ) {
				unset( $colors[$term_id] );
				update_option( 'gridlove_cat_colors', $colors );
		}
	}	
endif;


/**
 * Update category colors 
 * 
 * Function checks for category color and updates two fields
 * in options table. One for list of category colors and second
 * for recently picked colors.
 * 
 * @param   int $cat_id
 * @param   string $color Hexadecimal color value
 * @param   string $type inherit|custom
 * @return  void 
 * @since  1.0
 */
if ( !function_exists( 'gridlove_update_cat_colors' ) ):
	function gridlove_update_cat_colors( $cat_id, $color, $type ) {

		/* Update category color */
		
		$colors = get_option( 'gridlove_cat_colors' );

		if(empty($colors)) {
			$colors = array();
		}

		if ( array_key_exists( $cat_id, $colors ) ) {

			if ( $type == 'inherit' ) {
				unset( $colors[$cat_id] );
			} elseif ( $colors[$cat_id] != $color ) {
				$colors[$cat_id] = $color;
			}

		} else {

			if ( $type != 'inherit' ) {
				$colors[$cat_id] = $color;
			}
		}

		update_option( 'gridlove_cat_colors', $colors );


		/* Store recent category colors */
		if ( $type != 'inherit' ) {

			$num_col = 10;
			$current = get_option( 'gridlove_recent_cat_colors' );
			if(empty($current)) {
				$current = array();
			}
			$update = false;

			if ( !in_array( $color, $current ) ) {
				$current[] = $color;
				if ( count( $current ) > $num_col ) {
					$current = array_slice( $current, ( count( $current ) - $num_col ), ( count( $current ) - 1 ) );
				}
				$update = true;
			}

			if ( $update ) {
				update_option( 'gridlove_recent_cat_colors', $current );
			}
		
		}

	}
endif;

?>
