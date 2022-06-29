<?php 

/**
 * Load page metaboxes
 * 
 * Callback function for page metaboxes load
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_load_page_metaboxes' ) ) :
	function gridlove_load_page_metaboxes() {
		
		/* Layout metabox */
		add_meta_box(
			'gridlove_page_layout',
			esc_html__( 'Layout', 'gridlove' ),
			'gridlove_page_layout_metabox',
			'page',
			'side',
			'default'
		);

		/* Sidebar metabox */
		add_meta_box(
			'gridlove_page_sidebar',
			esc_html__( 'Sidebar', 'gridlove' ),
			'gridlove_page_sidebar_metabox',
			'page',
			'side',
			'default'
		);

		/* Authors template metabox */
		add_meta_box(
			'gridlove_author_options',
			esc_html__('Authors', 'gridlove'),
			'gridlove_author_options_metabox',
			'page',
			'side',
			'default'
		) ;

		/* Cover area metabox */
		add_meta_box(
			'gridlove_cover',
			esc_html__( 'Cover Area', 'gridlove' ),
			'gridlove_cover_metabox',
			'page',
			'normal',
			'high'
		);

		/* Modules metabox */
		add_meta_box(
			'gridlove_modules',
			esc_html__( 'Modules', 'gridlove' ),
			'gridlove_modules_metabox',
			'page',
			'normal',
			'high'
		);

		/* Pagination metabox */
		add_meta_box(
			'gridlove_pagination',
			esc_html__( 'Pagination', 'gridlove' ),
			'gridlove_pagination_metabox',
			'page',
			'normal',
			'high'
		);

	}
endif;


/**
 * Save page meta
 * 
 * Callback function to save page meta data
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_save_page_metaboxes' ) ) :
	function gridlove_save_page_metaboxes( $post_id, $post ) {
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return;
		}
			
		if ( ! isset( $_POST['gridlove_page_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['gridlove_page_metabox_nonce'], 'gridlove_page_metabox_save' ) ) {
   			return;
		}

		if ( $post->post_type == 'page' && isset( $_POST['gridlove'] ) ) {
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

			if( isset( $_POST['gridlove']['cover'] ) &&  !empty($_POST['gridlove']['cover']) ){
				
				$post_data_for_saving = gridlove_get_cover_post_data_for_saving();
				$post_type_with_taxonomies = gridlove_get_post_type_with_taxonomies($_POST['gridlove']['cover']['post_type']);
				
				foreach ($post_data_for_saving as $value ){
					$gridlove_meta['cover'][$value] = $_POST['gridlove']['cover'][$value];
				}
				
				if(!empty($post_type_with_taxonomies->taxonomies)){
					foreach ( $post_type_with_taxonomies->taxonomies as $taxonomy ) {
						
						$taxonomy_id = gridlove_patch_taxonomy_id($taxonomy['id']);
						
						if(!empty($_POST['gridlove']['cover'][$taxonomy_id])){
							
							$gridlove_meta['cover'][$taxonomy_id . '_inc_exc'] = $_POST['gridlove']['cover'][$taxonomy_id . '_inc_exc'];
							
							if($taxonomy['hierarchical']){
								$gridlove_meta['cover'][$taxonomy_id] = $_POST['gridlove']['cover'][$taxonomy_id];
								$gridlove_meta['cover'][$taxonomy_id . '_child'] = $_POST['gridlove']['cover'][$taxonomy_id . '_child'];
							}else{
								$gridlove_meta['cover'][$taxonomy_id] = gridlove_get_tax_term_slug_by_name( $_POST['gridlove']['cover'][$taxonomy_id], $taxonomy['id']);
							}
						}
					}
				}
				
				if ( isset( $_POST['gridlove']['cover']['manual'] ) && !empty( $_POST['gridlove']['cover']['manual'] ) ) {
							$gridlove_meta['cover']['manual'] = array_map( 'absint', explode( ",", $_POST['gridlove']['cover']['manual'] ) );
				}

			}

			if(isset( $_POST['gridlove']['modules']) && !empty($_POST['gridlove']['modules']) ){
				
				foreach($_POST['gridlove']['modules'] as $i => $module ){
					if ( isset( $module['manual'] ) && !empty( $module['manual'] ) ) {
						$_POST['gridlove']['modules'][$i]['manual'] = array_map( 'absint', explode( ",", $module['manual'] ) );
					}

					if ( isset( $module['tag'] ) && !empty( $module['tag'] ) ) {
						$_POST['gridlove']['modules'][$i]['tag'] = gridlove_get_tax_term_slug_by_name( $module['tag'], 'post_tag');
					}

					if( !empty( $module['tax'] ) ) {

						$taxonomies = array();
						foreach( $module['tax'] as $k => $tax ){
							
							if(!empty($tax)){
								
								if( is_array($tax) ){
									$taxonomies[$k] = $tax;
								} else {
								 	$taxonomies[$k] = gridlove_get_tax_term_id_by_name( $tax, $k);
								}
							}

						}
						
						$_POST['gridlove']['modules'][$i]['tax'] =  $taxonomies;
					}


				}

				$gridlove_meta['modules'] = array_values($_POST['gridlove']['modules']);
			}

			if( isset( $_POST['gridlove']['pagination'] ) &&  $_POST['gridlove']['pagination'] != 'none' ){
				$gridlove_meta['pagination'] = $_POST['gridlove']['pagination'];
			}

			if ( isset( $_POST['gridlove']['authors'] ) ) {
				$gridlove_meta['authors']['orderby'] = !empty( $_POST['gridlove']['authors']['orderby'] ) ? $_POST['gridlove']['authors']['orderby'] : 0;
				$gridlove_meta['authors']['order'] = !empty( $_POST['gridlove']['authors']['order'] ) ? $_POST['gridlove']['authors']['order'] : 'DESC';
				$gridlove_meta['authors']['exclude'] = !empty( $_POST['gridlove']['authors']['exclude'] ) ? array_map('absint', explode(',', $_POST['gridlove']['authors']['exclude'])) : '';
				$gridlove_meta['authors']['roles'] = !empty( $_POST['gridlove']['authors']['roles'] ) ? $_POST['gridlove']['authors']['roles'] : array();
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
 * Module generator metabox
 * 
 * Callback function to create modules metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_modules_metabox' ) ) :
	function gridlove_modules_metabox( $object, $box ) {

		wp_nonce_field( 'gridlove_page_metabox_save', 'gridlove_page_metabox_nonce' );

		$meta = gridlove_get_page_meta( $object->ID );
		$module_defaults = gridlove_get_module_defaults();
		$module_options = gridlove_get_module_options();
?>
		
		<?php if( empty( $meta['modules'] ) ) : ?>
			<p class="gridlove-empty-modules howto"><?php esc_html_e( 'You don\'t have any modules on this page yet. Click the button below to create your first module.', 'gridlove' ); ?></p>
		<?php endif; ?>

		<div class="gridlove-modules">
				<?php if(!empty( $meta['modules'] ) ): ?>
					<?php foreach($meta['modules'] as $i => $module ) : $module = gridlove_parse_args( $module, $module_defaults[$module['type']]); ?>
						<?php gridlove_generate_module( $module, $module_options[$module['type']], $i ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
		</div>

		<div class="gridlove-modules-bottom">
			<?php foreach( $module_defaults as $type => $module ): ?>
				<a href="javascript:void(0);" class="gridlove-add-module button-secondary" data-type="<?php echo esc_attr($type); ?>"><?php echo '+ '.$module['type_name']. ' ' .esc_html__( 'Module', 'gridlove'); ?></a>
			<?php endforeach; ?>
		</div>

		<div id="gridlove-module-clone">
			<?php foreach( $module_defaults as $type => $module ): ?>
				<div class="<?php echo esc_attr($type); ?>">
					<?php gridlove_generate_module( $module, $module_options[$type]); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="gridlove-modules-count" data-count="<?php echo esc_attr(count($meta['modules'])); ?>"></div>
				  	
	<?php
	}
endif;



/**
 * Generate module field
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $i id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module' ) ) :
	function gridlove_generate_module( $module, $options, $i = false ) {
		
		$name_prefix = ( $i === false ) ? '' :  'gridlove[modules]['.$i.']';
		$edit = ( $i === false ) ? '' :  'edit';
		$module_class = ( $i === false ) ? '' :  'gridlove-module-'.$i;
		$module_num = ( $i === false ) ? '' : $i;

		$deactivate_class = $module['active'] ? '' : 'gridlove-hidden';
		$activate_class = $module['active'] ? 'gridlove-hidden' : '';

		if( !$module['active'] ) {
			$module_class .= ' gridlove-module-disabled';
		}
?>
		<div class="gridlove-module <?php echo esc_attr($module_class); ?>" data-module="<?php echo esc_attr($module_num); ?>">
			
			<div class="left">
				<span class="gridlove-module-type">
					<?php echo ($module['type_name']); ?>
				</span>
				<span class="gridlove-module-title"><?php echo esc_html($module['title']); ?></span>
			</div>

			<div class="right">
				<a href="javascript:void(0);" class="gridlove-edit-module"><?php esc_html_e( 'Edit', 'gridlove' ); ?></a> | 
				<a href="javascript:void(0);" class="gridlove-deactivate-module"><span class="<?php echo esc_attr($activate_class); ?>"><?php esc_html_e( 'Activate', 'gridlove' ); ?></span><span class="<?php echo esc_attr($deactivate_class); ?>"><?php esc_html_e( 'Deactivate', 'gridlove' ); ?></span></a> | 
				<a href="javascript:void(0);" class="gridlove-remove-module"><?php esc_html_e( 'Remove', 'gridlove' ); ?></a>
			</div>

			<div class="gridlove-module-form <?php echo esc_attr($edit); ?>">
				<input class="gridlove-module-deactivate gridlove-count-me" type="hidden" name="<?php echo esc_attr($name_prefix); ?>[active]" value="<?php echo esc_attr($module['active']); ?>"/>
				<input class="gridlove-count-me" type="hidden" name="<?php echo esc_attr($name_prefix); ?>[type]" value="<?php echo esc_attr($module['type']); ?>"/>
				<?php $mod_type = isset($module['cpt']) ? 'cpt' : $module['type']; ?>
				<?php call_user_func( 'gridlove_generate_module_'.$mod_type, $module, $options, $name_prefix ); ?>

		   	</div>

		</div>
		
	<?php
	}
endif;


/**
 * Generate posts module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module_posts' ) ) :
function gridlove_generate_module_posts( $module, $options, $name_prefix ){
	
	extract( $options ); ?>

	<div class="gridlove-opt-tabs">
		<a href="javascript:void(0);" class="active"><?php esc_html_e( 'Appearance', 'gridlove' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Selection', 'gridlove' ); ?></a>
	</div>

	<div class="gridlove-tab first">

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Title', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="gridlove-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'gridlove' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'gridlove' ); ?></small>

			</div>
		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Layout', 'gridlove' ); ?>:
			</div>

		    <div class="gridlove-opt-content">

		    	<?php foreach ( $layout_types as $layout_type => $title ): ?>
		    		<label><input type="radio" class="gridlove-count-me gridlove-module-layout-switch" name="<?php echo esc_attr($name_prefix); ?>[layout_type]" value="<?php echo esc_attr($layout_type); ?>" <?php checked( $layout_type, $module['layout_type'] );?>/> <?php echo esc_html( $title ); ?></label>
		    	<?php endforeach; ?>

		    	<div class="gridlove-module-layouts">
		    		
		    		<?php $active = $module['layout_type'] == 'simple' ? 'active' : ''; ?>

					<div class="gridlove-module-layout simple <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $simple_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['simple_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="<?php echo esc_attr($layout['step'] * 2); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[simple_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['simple_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					</div>


		    		<?php $active = $module['layout_type'] == 'combo' ? 'active' : ''; ?>

		    		<div class="gridlove-module-layout combo <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $combo_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['combo_layout'] ) ? ' selected': ''; ?>
					  			
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="<?php echo esc_attr($layout['step']); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[combo_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['combo_layout'] );?> />
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					</div>

					<?php $active = $module['layout_type'] == 'slider' ? 'active' : ''; ?>

					<div class="gridlove-module-layout slider <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $slider_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['slider_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>"  data-min="<?php echo esc_attr($layout['step'] + 1); ?>" data-step="1" data-default="<?php echo esc_attr($layout['step'] + 1); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[slider_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['slider_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>

					</div>

					<?php $active = $module['layout_type'] == 'masonry' ? 'active' : ''; ?>

					<div class="gridlove-module-layout masonry <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $masonry_layouts as $id => $layout ): ?>
                            <li>
					  			<?php $selected_class = gridlove_compare( $id, $module['masonry_layout'] ) ? ' selected': ''; ?>
                                <img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="6">
                                <br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[masonry_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['masonry_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>

					</div>

					

				</div>

		    	
		    </div>

	    </div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Number of posts', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<?php 
					switch($module['layout_type']){
						case 'combo': $layouts = gridlove_get_combo_layouts(); break;
						case 'slider':  $layouts = gridlove_get_slider_layouts(); break;
						case 'masonry':  $layouts = gridlove_get_masonry_layouts(); break;
						default: $layouts = gridlove_get_simple_layouts(); break;
					}

					foreach($layouts as $id => $layout ){
						if( $id == $module[$module['layout_type'].'_layout']){
							$selected_step = $layout['step'];
							$selected_min = $layout['step'];
							if( $module['layout_type'] == 'slider'){
								$selected_step = 1;
								$selected_min++; 
							}
							break;
						}
					}


				?>
				<input class="gridlove-count-me gridlove-input-slider" type="range" min="<?php echo esc_attr($selected_min); ?>" step="<?php echo esc_attr($selected_step); ?>" max="30" name="<?php echo esc_attr($name_prefix); ?>[limit]" value="<?php echo esc_attr($module['limit']);?>"/> <span class="gridlove-slider-opt-count"><?php echo esc_attr($module['limit']);?></span><br/>
			</div>
		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Inject custom content:', 'gridlove' ); ?>
			</div>
			<div class="gridlove-opt-content">
				<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[content_inject]" value="1" <?php checked( $module['content_inject'], 1 ); ?> class="gridlove-count-me gridlove-content-inject"/> <small class="howto"><?php esc_html_e( 'Add content/code in certain postition by replacing that post.', 'gridlove' ); ?></small> </label>
			</div>
			
		   	<?php $hidden_class = $module['content_inject'] ? '' : 'gridlove-hidden'; ?>
			
			<div class="gridlove-custom <?php echo esc_attr( $hidden_class ); ?>">
				<div class="gridlove-opt-title">
					<?php esc_html_e( 'Custom content position', 'gridlove' ); ?>:
				</div>
				<div class="gridlove-opt-content">
					<input type="number" class="gridlove-count-me small-text" name="<?php echo esc_attr($name_prefix); ?>[content_position]" value="<?php echo esc_attr( $module['content_position'] ); ?>" />
					<small class="howto"><?php esc_html_e( 'Specify position of a post which will be replaced by custom content ', 'gridlove' ); ?></small>
				</div>
			</div>

			<div class="gridlove-custom <?php echo esc_attr( $hidden_class ); ?>">
				<div class="gridlove-opt-title">
					<?php esc_html_e( 'Custom content', 'gridlove' ); ?>:
				</div>
				<div class="gridlove-opt-content">
					<textarea class="gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[content]"><?php echo esc_textarea( $module['content'] ); ?></textarea>
					<small class="howto"><?php esc_html_e( 'Paste any text, HTML, script or shortcodes here', 'gridlove' ); ?></small>
					<br/>
					<label>
						<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autop]" value="1" <?php checked( $module['autop'], 1 ); ?> class="gridlove-count-me" />
						<?php esc_html_e( 'Automatically add paragraphs', 'gridlove' ); ?>
					</label> <br/>

					<label>
						<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[center]" value="1" <?php checked( $module['center'], 1 ); ?> class="gridlove-count-me" />
						<?php esc_html_e( 'Center align content', 'gridlove' ); ?>
					</label>
				</div>
			</div>

		</div>

		
		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Actions:', 'gridlove' ); ?>
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[more_link]" value="1" <?php checked( $module['more_link'], 1 ); ?> class="gridlove-count-me gridlove-more-button-switch"/> <?php esc_html_e( 'Display "view all" button', 'gridlove' ); ?> </label>
		   		<?php $hidden_class = $module['more_link'] ? '' : 'gridlove-hidden'; ?>
		   		<div class="gridlove-more-button-opt <?php echo esc_attr( $hidden_class ); ?>">
			   		<label><?php esc_html_e( 'Text', 'gridlove' ); ?>:</label><input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_text]" value="<?php echo esc_attr($module['more_text']);?>" class="gridlove-count-me" />
			   		<br/><label><?php esc_html_e( 'URL', 'gridlove' ); ?>:</label><input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_url]" value="<?php echo esc_attr($module['more_url']);?>" class="gridlove-count-me" /><br/>
			   		<small class="howto"><?php esc_html_e( 'Specify text and URL for "view all" button', 'gridlove' ); ?></small>
		   		</div>
		   		<?php $hidden_class = $module['layout_type'] == 'slider' ? '' : 'gridlove-hidden'; ?>
		   		<div class="gridlove-autoplay-opt <?php echo esc_attr( $hidden_class ); ?>">
		   			<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autoplay]" value="1" <?php checked( $module['autoplay'], 1 ); ?> class="gridlove-count-me"/> <?php esc_html_e( 'Autoplay (rotate) slider every', 'gridlove' ); ?> 
			   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[autoplay_time]" value="<?php echo absint($module['autoplay_time']);?>" class="gridlove-count-me small-text" /> <?php esc_html_e( 'seconds', 'gridlove' ); ?> </label>
		   		</div>
		   		
		   	</div>
	    </div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr($module['css_class']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'gridlove' ); ?></small>
			</div>
		</div>

	</div>

	<div class="gridlove-tab">
		
		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Order by', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $order as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[order]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['order'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>					

				<div class="gridlove-live-search-opt">

					<br/><?php esc_html_e( 'Or choose manually', 'gridlove' ); ?>:<br/>
		   			<input type="text" class="gridlove-live-search" placeholder="<?php esc_html_e( 'Type to search...', 'gridlove' ); ?>" /><br/>
		   			<?php $manualy_selected_posts = gridlove_get_manually_selected_posts($module['manual'], $module['type']); ?>
		   			<?php $manual = !empty( $manualy_selected_posts ) ? implode( ",", $module['manual'] ) : ''; ?>
		   			<input type="hidden" class="gridlove-count-me gridlove-live-search-hidden" data-type="<?php echo esc_attr($module['type']); ?>" name="<?php echo esc_attr($name_prefix); ?>[manual]" value="<?php echo esc_attr($manual); ?>" />
		   			<div class="gridlove-live-search-items tagchecklist">
		   				<?php gridlove_display_manually_selected_posts($manualy_selected_posts); ?>
		   			</div>

		   		</div>

		   	</div>
	    </div>

	     <div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Sort', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="DESC" <?php checked( $module['sort'], 'DESC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Descending', 'gridlove') ?></label><br/>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="ASC" <?php checked( $module['sort'], 'ASC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Ascending', 'gridlove') ?></label><br/>
		   	</div>
	    </div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'In category', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<div class="gridlove-fit-height">
		   		<?php foreach ( $cats as $cat ) : ?>
		   			<?php $checked = in_array( $cat->term_id, $module['cat'] ) ? 'checked' : ''; ?>
		   			<label><input class="gridlove-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[cat][]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo esc_attr( $checked ); ?> /><?php echo esc_html( $cat->name );?></label><br/>
		   		<?php endforeach; ?>
		   		</div>
		   		<small class="howto"><?php esc_html_e( 'Check whether you want to display posts from specific categories only', 'gridlove' ); ?></small>
		   		<br/>
		   		<label><input type="checkbox" name="<?php echo esc_attr( $name_prefix ); ?>[cat_child]" value="1" class="gridlove-count-me" <?php checked( $module['cat_child'], 1 );?>/><?php esc_html_e( 'Apply child categories', 'gridlove' ); ?></label><br/>
		    	<small class="howto"><?php esc_html_e( 'If parent category is selected, posts from child categories will be included automatically', 'gridlove' ); ?></small>
                <br/>
                <label><input type="radio" name="<?php echo esc_attr( $name_prefix ); ?>[cat_inc_exc]" value="in" <?php checked( $module['cat_inc_exc'], 'in' ); ?> class="gridlove-count-me" /><?php esc_html_e('Include', 'gridlove') ?></label><br/>
                <label><input type="radio" name="<?php echo esc_attr( $name_prefix ); ?>[cat_inc_exc]" value="not_in" <?php checked( $module['cat_inc_exc'], 'not_in' ); ?> class="gridlove-count-me" /><?php esc_html_e('Exclude', 'gridlove') ?></label><br/>
                <small class="howto"><?php esc_html_e( 'Whether to include or exclude posts from selected categories', 'gridlove' ); ?></small>
            </div>
	   	</div>

	   	<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Tagged with', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[tag]" value="<?php echo esc_attr(gridlove_get_tax_term_name_by_slug($module['tag'])); ?>" class="gridlove-count-me"/><br/>
		   		<small class="howto"><?php esc_html_e( 'Specify one or more tags separated by comma. i.e. life, cooking, funny moments', 'gridlove' ); ?></small>
                <br>
                <label><input type="radio" name="<?php echo esc_attr( $name_prefix ); ?>[tag_inc_exc]" value="in" <?php checked( $module['tag_inc_exc'], 'in' ); ?> class="gridlove-count-me" /><?php esc_html_e('Include', 'gridlove') ?></label><br/>
                <label><input type="radio" name="<?php echo esc_attr( $name_prefix ); ?>[tag_inc_exc]" value="not_in" <?php checked( $module['tag_inc_exc'], 'not_in' ); ?> class="gridlove-count-me" /><?php esc_html_e('Exclude', 'gridlove') ?></label><br/>
                <small class="howto"><?php esc_html_e( 'Whether to include or exclude posts from selected tags', 'gridlove' ); ?></small>
            </div>
	   	</div>

	   	<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Format', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $formats as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[format]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['format'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that have a specific format', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Not older than', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $time as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[time]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['time'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that are not older than specific time range', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

	   	<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Unique posts (do not duplicate)', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[unique]" value="1" <?php checked( $module['unique'], 1 ); ?> class="gridlove-count-me" /></label>
		   		<small class="howto"><?php esc_html_e( 'If you check this option, posts in this module will be excluded from other modules below.', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	</div>

<?php }
endif;



/**
 * Generate cats module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module_cats' ) ) :
function gridlove_generate_module_cats( $module, $options, $name_prefix ){
	
	extract( $options ); ?>

	<div class="gridlove-opt-tabs">
		<a href="javascript:void(0);" class="active"><?php esc_html_e( 'Appearance', 'gridlove' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Selection', 'gridlove' ); ?></a>
	</div>

	<div class="gridlove-tab first">

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Title', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="gridlove-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'gridlove' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'gridlove' ); ?></small>

			</div>
		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Layout', 'gridlove' ); ?>:
			</div>

		    <div class="gridlove-opt-content">

		    	<?php foreach ( $layout_types as $layout_type => $title ): ?>
		    		<label><input type="radio" class="gridlove-count-me gridlove-module-cats-layout-switch" name="<?php echo esc_attr($name_prefix); ?>[layout_type]" value="<?php echo esc_attr($layout_type); ?>" <?php checked( $layout_type, $module['layout_type'] );?>/> <?php echo esc_html( $title ); ?></label>
		    	<?php endforeach; ?>

		    	<div class="gridlove-module-layouts">
		    		
		    		<?php $active = $module['layout_type'] == 'simple' ? 'active' : ''; ?>

					<div class="gridlove-module-layout simple <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $simple_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['simple_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="<?php echo esc_attr($layout['step'] * 2); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[simple_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['simple_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					</div>

					<?php $active = $module['layout_type'] == 'slider' ? 'active' : ''; ?>

					<div class="gridlove-module-layout slider <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $slider_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['slider_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>"  data-min="<?php echo esc_attr($layout['step'] + 1); ?>" data-step="1" data-default="<?php echo esc_attr($layout['step'] + 1); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[slider_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['slider_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>

					</div>

				</div>

		    </div>

	    </div>

	    <?php $hidden_class = $module['layout_type'] == 'slider' ? '' : 'gridlove-hidden'; ?>
		<div class="gridlove-opt <?php echo esc_attr( $hidden_class );?>">
			<div class="gridlove-opt-title">
				<span class="gridlove-autoplay-opt "><?php esc_html_e( 'Slider Action:', 'gridlove' ); ?></span>
			</div>
			<div class="gridlove-opt-content">
		   		<div class="gridlove-autoplay-opt">
		   			<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autoplay]" value="1" <?php checked( $module['autoplay'], 1 ); ?> class="gridlove-count-me"/> <?php esc_html_e( 'Autoplay (rotate) slider every', 'gridlove' ); ?> 
			   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[autoplay_time]" value="<?php echo absint($module['autoplay_time']);?>" class="gridlove-count-me small-text" /> <?php esc_html_e( 'seconds', 'gridlove' ); ?> </label>
		   		</div>
		   	</div>
	    </div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<span class="gridlove-category-color"><?php esc_html_e( 'Apply category colors:', 'gridlove' ); ?></span>
			</div>
			<div class="gridlove-opt-content">
	   			<label>
	   				<input type="hidden" name="<?php echo esc_attr($name_prefix); ?>[category_color]" value="0" class="gridlove-count-me" />
	   				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[category_color]" value="1" <?php checked( $module['category_color'], 1 ); ?> class="gridlove-count-me"/> 
		   		</label>
		   	</div>
	    </div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Display posts count', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input type="hidden" name="<?php echo esc_attr($name_prefix); ?>[display_count]" value="0" class="gridlove-count-me" />
		   		<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[display_count]" value="1" <?php checked( $module['display_count'], 1 ); ?> class="gridlove-count-me gridlove-next-hide" />
		   	</div>
	    </div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Count label', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[count_label]" value="<?php echo esc_attr($module['count_label']);?>" class="gridlove-count-me" />
		   	</div>
	    </div>
		
	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr($module['css_class']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'gridlove' ); ?></small>
			</div>
		</div>

	</div>

	<div class="gridlove-tab">
		
		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Categories', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<ul class="sortable">
					<?php $cats = gridlove_sort_option_items( $cats,  $module['cat']); ?>
			   		<?php foreach ( $cats as $cat ) : ?>
			   			<?php $checked = in_array( $cat->term_id, $module['cat'] ) ? 'checked="checked"' : ''; ?>
			   			<li>
			   				<label>
			   					<input class="gridlove-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[cat][]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo esc_attr($checked); ?> /><?php echo $cat->name;?>
			   				</label>
			   			</li>
			   		<?php endforeach; ?>
			   	</ul>
		   		<small class="howto"><?php esc_html_e( 'Select and re-order categories you would like to display, or leave empty for "all categories"', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	</div>

<?php }
endif;


/**
 * Generate text module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module_text' ) ) :
	function gridlove_generate_module_text( $module, $options, $name_prefix ){
		
		extract( $options ); ?>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Title', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="gridlove-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'gridlove' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'gridlove' ); ?></small>				
			</div>
		</div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Content', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">

				<div class="gridlove-content-row">
					<textarea class="gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[content]"><?php echo esc_textarea( $module['content'] ); ?></textarea>
				</div>

				<small class="howto"><?php esc_html_e( 'Paste any text, HTML, script or shortcodes here', 'gridlove' ); ?></small>
				<br/>
				<label>
					<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autop]" value="1" <?php checked( $module['autop'], 1 ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Automatically add paragraphs', 'gridlove' ); ?>
				</label> <br/>

				<label>
					<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[center]" value="1" <?php checked( $module['center'], 1 ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Center align content', 'gridlove' ); ?>
				</label>
			</div>
		</div>

		 <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Style', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				
				<label>
					<input type="radio" name="<?php echo esc_attr($name_prefix); ?>[style]" value="boxed" <?php checked( $module['style'], 'boxed' ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Boxed (the same as posts module)', 'gridlove' ); ?>
				</label> <br/>

				<label>
					<input type="radio" name="<?php echo esc_attr($name_prefix); ?>[style]" value="transparent" <?php checked( $module['style'], 'transparent' ); ?> class="gridlove-count-me" />
					<?php esc_html_e( 'Transparent (without box and background)', 'gridlove' ); ?>
				</label>
				<small class="howto"><?php esc_html_e( 'Choose how to display text module', 'gridlove' ); ?></small>
			</div>
		</div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr($module['css_class']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'gridlove' ); ?></small>
			</div>
		</div>

	<?php }
endif;

/**
 * Cover area metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_cover_metabox' ) ) :
function gridlove_cover_metabox( $object, $box ){
	
	$meta = gridlove_get_page_meta( $object->ID, 'cover' );
	$layouts = gridlove_get_cover_layouts( false, true );
	$order = gridlove_get_post_order_opts();
	$time = gridlove_get_time_diff_opts();
	$formats = gridlove_get_post_format_opts();
	$post_types = gridlove_get_posts_types_with_taxonomies( array('page') );

	$name_prefix = 'gridlove[cover]';
	$meta_layout = $meta['layout'];
	$show_hide_class = $meta_layout == 'none' || $meta_layout == 'custom' ? 'gridlove-hidden-custom' : ''; 
	$show_class = $meta_layout == 'custom' ? 'gridlove-show-custom' : 'gridlove-hidden-custom';
	
	$show_hide_class_post_type = $show_hide_class;
	if(count($post_types) < 2){
		$show_hide_class_post_type = 'gridlove-hidden-custom';
	}
	?>

	<div class="gridlove-opt-box">

		<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Layout', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
			    <ul class="gridlove-img-select-wrap">
			  	<?php foreach ( $layouts as $id => $layout ): ?>
			  		<li>
			  			<?php $selected_class = gridlove_compare( $id, $meta['layout'] ) ? ' selected': ''; ?>
			  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
			  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
			  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['layout'] );?>/>
			  		</li>
			  	<?php endforeach; ?>
			    </ul>
		    	<small class="howto"><?php esc_html_e( 'Choose your cover area layout', 'gridlove' ); ?></small>
		    </div>
	    </div>

	    <div class="gridlove-opt-inline gridlove-show-hide <?php echo esc_attr($show_hide_class_post_type); ?>">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Post type', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
                <select class="gridlove-fa-post-type" name="<?php echo esc_attr( $name_prefix ); ?>[post_type]">
                    <?php foreach ($post_types as $post_type) :?>v
                        <?php
                        if( empty($post_type) ){
                            continue;
                        }
                        ?>
                        <option value="<?php echo esc_attr($post_type->name)?>" <?php selected($meta['post_type'], $post_type->name); ?>><?php echo esc_attr($post_type->labels->singular_name); ?></option>
                    <?php endforeach; ?>
                </select>
			</div>
		</div>

	    <div class="gridlove-opt-inline gridlove-show-hide <?php echo esc_attr($show_hide_class); ?>">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Number of posts', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[limit]" value="<?php echo esc_attr($meta['limit']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Max number of posts to display', 'gridlove' ); ?></small>
			</div>
		</div>

		<div class="gridlove-opt-inline gridlove-show-hide <?php echo esc_attr($show_hide_class); ?>">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Order by', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $order as $id => $title ) : ?>
		   			<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[order]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['order'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>

		   		<div class="gridlove-live-search-opt">
					
					<br/><?php esc_html_e( 'Or choose manually', 'gridlove' ); ?>:<br/>
		   			<input type="text" class="gridlove-live-search gridlove-live-search-with-cpts" placeholder="<?php esc_html_e( 'Type to search...', 'gridlove' ); ?>" /><br/>
		   			<?php $manualy_selected_posts = gridlove_get_manually_selected_posts($meta['manual'], 'cover'); ?>
		   			<?php $manual = !empty( $manualy_selected_posts ) ? implode( ",", $meta['manual'] ) : ''; ?>
		   			<input type="hidden" class="gridlove-count-me gridlove-live-search-hidden" data-type="cover" name="<?php echo esc_attr($name_prefix); ?>[manual]" value="<?php echo esc_attr($manual); ?>" />
		   			<div class="gridlove-live-search-items tagchecklist">
		   				<?php gridlove_display_manually_selected_posts($manualy_selected_posts); ?>
		   			</div>

		   		</div>
		   	</div>
	    </div>

	    <div class="gridlove-opt-inline gridlove-show-hide <?php echo esc_attr($show_hide_class); ?>">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Sort', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="DESC" <?php checked( $meta['sort'], 'DESC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Descending', 'gridlove') ?></label><br/>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="ASC" <?php checked( $meta['sort'], 'ASC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Ascending', 'gridlove') ?></label><br/>
		   	</div>
	    </div>

	    <div class="gridlove-opt-inline gridlove-show-hide <?php echo esc_attr($show_hide_class); ?>">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Unique posts (do not duplicate)', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[unique]" value="1" <?php checked( $meta['unique'], 1 ); ?> class="gridlove-count-me" /></label>
		   		<small class="howto"><?php esc_html_e( 'If you check this option, selected posts will be excluded from modules.', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	</div>

	<div class="gridlove-opt-box gridlove-show-hide <?php echo esc_attr($show_hide_class); ?>">
		
		<?php foreach ( $post_types as $post_type ) :
			
			if ( empty( $post_type->taxonomies ) ) {
				continue;
			}
			
			foreach ( $post_type->taxonomies as $taxonomy ) :
				
				if ( ! isset( $taxonomy['hierarchical'] ) ) {
					continue;
				}
				
				if( $taxonomy['hierarchical'] && empty( $taxonomy['terms'] ) ){
					continue;
				}
				
				?>

                <div class="gridlove-opt-inline gridlove-watch-for-changes" data-watch="gridlove-fa-post-type" data-show-on-value="<?php echo esc_attr($post_type->name);?>">
                    <div class="gridlove-opt-title">
						<?php echo esc_attr( $taxonomy['name'] ); ?>:
                    </div>
                    <div class="gridlove-opt-content">
						<?php
						
						$taxonomy_id = gridlove_patch_taxonomy_id($taxonomy['id']);
						
						if ( $taxonomy['hierarchical'] ):
							if ( empty( $taxonomy['terms'] ) ) {
								continue;
							}
							?>
                            <div class="gridlove-fit-height">
								<?php foreach ( $taxonomy['terms'] as $term ) : ?>
									<?php $checked = !empty($meta[$taxonomy_id]) && in_array( $term->term_id, $meta[$taxonomy_id] ) ? 'checked="checked"' : ''; ?>
                                    <label><input class="gridlove-count-me" type="checkbox" name="<?php echo esc_attr( $name_prefix . '[' . $taxonomy_id . ']' ); ?>[]" value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo $checked; ?> /><?php echo $term->name; ?>
                                    </label>
                                    <br/>
								<?php endforeach; ?>
                            </div><br>
                            <label>
								<?php $apply_child = !empty($meta[$taxonomy_id . '_child']) ? $meta[$taxonomy_id . '_child'] : 0; ?>
                                <input type="checkbox" name="<?php echo esc_attr( $name_prefix . '[' . $taxonomy_id . '_child' ); ?>]" value="1" class="vce-count-me" <?php checked($apply_child, 1); ?>/>
                                <strong><?php printf(esc_html__('Apply child %s', 'gridlove'), strtolower($taxonomy['name'])); ?></strong>
                            </label>
                            <small class="howto"><?php printf(esc_html__( 'Check whether you want to display posts from specific %s only', 'gridlove' ), strtolower($taxonomy['name'])); ?></small>
						<?php else: ?>
							<?php $value = empty($meta[$taxonomy_id]) ? '' : gridlove_get_tax_term_name_by_slug( $meta[$taxonomy_id], $taxonomy['id'] ); ?>
                            <input type="text" name="<?php echo esc_attr( $name_prefix . '[' . $taxonomy_id . ']' ); ?>" value="<?php echo esc_attr( $value ); ?>" class="gridlove-count-me"/><br/>
                            <small class="howto"><?php printf(esc_html__( 'Specify one or more %s separated by comma. i.e. life, cooking, funny moments', 'gridlove' ), strtolower($taxonomy['name'])); ?></small>
						<?php endif;
						
						$taxonomy_inc_exc = empty($meta[ $taxonomy_id . '_inc_exc' ]) ? 'in' : $meta[ $taxonomy_id . '_inc_exc' ];
						?>
                        <br/>
                        <label><input type="radio" name="<?php echo esc_attr( $name_prefix . '[' . $taxonomy_id . '_inc_exc]' ); ?>" value="in" <?php checked( $taxonomy_inc_exc, 'in' ); ?> class="gridlove-count-me"/><?php esc_html_e( 'Include', 'gridlove' ) ?>
                        </label><br/>
                        <label><input type="radio" name="<?php echo esc_attr( $name_prefix ) . '[' . $taxonomy_id . '_inc_exc]'; ?>" value="not_in" <?php checked( $taxonomy_inc_exc, 'not_in' ); ?> class="gridlove-count-me"/><?php esc_html_e( 'Exclude', 'gridlove' ) ?>
                        </label><br/>
                        <small class="howto"><?php printf(esc_html__( 'Whether to include or exclude posts from selected %s', 'gridlove' ), strtolower($taxonomy['name'])); ?></small>
                    </div>
                    <br>
                </div>
			<?php endforeach; ?><?php endforeach; ?>

	   	<div class="gridlove-opt-inline gridlove-watch-for-changes" data-watch="gridlove-fa-post-type" data-show-on-value="post">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Format', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $formats as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[format]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['format'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that have a specific format', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

		<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Not older than', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $time as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[time]" value="<?php echo esc_attr($id); ?>" <?php checked( $meta['time'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that are not older than specific time range', 'gridlove' ); ?></small>
	   		</div>
	   	</div>


	</div>


	<div class="gridlove-opt-box gridlove-show-hide gridlove-show-hide-custom <?php echo esc_attr($show_hide_class); ?> <?php echo esc_attr($show_class); ?> ">
	   	
	   	<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Custom Content', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">

				<div class="gridlove-content-row">
					<?php 
						$text_name = esc_attr($name_prefix). '[content]';
						$settings = array(
							'textarea_name' => $text_name,
							'editor_class' => 'gridlove-count-me'
						); 
					?>
					<?php wp_editor( $meta['content'], 'cover-area-custom-content', $settings ); ?>
				</div>
			</div>
		</div>

		<div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Background Image', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">

				<div class="gridlove-content-row">
					<input type="text" name="<?php echo esc_attr($name_prefix); ?>[bg_image]" value="<?php echo esc_url($meta['bg_image']); ?>" class="gridlove-custom-content-bg"/>
					<a href="#" class="gridlove-select-bg-image button"><?php _e('Upload', 'gridlove'); ?></a>
				</div>
			</div>
		</div>
	</div>


<?php }
endif;


/**
 * Pagination metabox
 * 
 * Callback function to create pagination metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_pagination_metabox' ) ) :
	function gridlove_pagination_metabox( $object, $box ) {
		
		$meta = gridlove_get_page_meta( $object->ID );
		$layouts = gridlove_get_pagination_layouts( false, true );
?>
	  	<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $layouts as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $meta['pagination'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo esc_html( $layout['title'] ); ?></span>
	  			<input type="radio" class="gridlove-hidden" name="gridlove[pagination]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $meta['pagination'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Note: Pagination will be applied to the last post module on the page', 'gridlove' ); ?></p>

	  <?php
	}
endif;


/**
 * Layout metabox
 * 
 * Callback function to create layout metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_page_layout_metabox' ) ) :
	function gridlove_page_layout_metabox( $object, $box ) {
		
		wp_nonce_field( 'gridlove_page_metabox_save', 'gridlove_page_metabox_nonce' );

		$gridlove_meta = gridlove_get_page_meta( $object->ID );
		$layouts = gridlove_get_page_layouts( true );
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

if ( !function_exists( 'gridlove_page_sidebar_metabox' ) ) :
	function gridlove_page_sidebar_metabox( $object, $box ) {
		

		$sidebar = gridlove_get_page_meta( $object->ID, 'sidebar' );
		$sidebars_lay = gridlove_get_sidebar_layouts( true );
		$sidebars = gridlove_get_sidebars_list( true );
?>
	  	<ul class="gridlove-img-select-wrap">
	  	<?php foreach ( $sidebars_lay as $id => $layout ): ?>
	  		<li>
	  			<?php $selected_class = $id == $sidebar['position'] ? ' selected': ''; ?>
	  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>">
	  			<span><?php echo esc_html( $layout['title'] ); ?></span>
	  			<input type="radio" class="gridlove-hidden" name="gridlove[sidebar][position]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $sidebar['position'] );?>/> </label>
	  		</li>
	  	<?php endforeach; ?>
	   </ul>

	   <p class="description"><?php esc_html_e( 'Display sidebar', 'gridlove' ); ?></p>

	  <?php if ( !empty( $sidebars ) ): ?>

	  	<p><select name="gridlove[sidebar][standard]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $sidebar['standard'] );?>><?php echo esc_html( $name ); ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose standard sidebar to display', 'gridlove' ); ?></p>

	  	<p><select name="gridlove[sidebar][sticky]" class="widefat">
	  	<?php foreach ( $sidebars as $id => $name ): ?>
	  		<option value="<?php echo esc_attr($id); ?>" <?php selected( $id, $sidebar['sticky'] );?>><?php echo esc_html( $name ); ?></option>
	  	<?php endforeach; ?>
	  </select></p>
	  <p class="description"><?php esc_html_e( 'Choose sticky sidebar to display', 'gridlove' ); ?></p>

	  <?php endif; ?>
	  <?php
	}
endif;


/**
 * Author Options metabox
 * 
 * Callback function to create author options metabox
 * 
 * @since  1.0
 */

if ( !function_exists( 'gridlove_author_options_metabox' ) ) :
	function gridlove_author_options_metabox( $object, $box ) {
		
		$authors_meta = gridlove_get_page_meta( $object->ID, 'authors' );
			
		$orderby_options = array(
			'post_count' => __('Post Count', 'gridlove'),
			'user_name' => __('Username', 'gridlove'),
			'display_name' => __('Display Name', 'gridlove'),
			'user_registered' => __('Register Date', 'gridlove'),
		);
		
		?>
		<p><strong><?php _e('Order by', 'gridlove');?></strong></p>
		<?php 
		foreach ($orderby_options as $value => $name): ?>
			<?php $checked = ($authors_meta['orderby'] === $value) ? 'checked' : '' ; ?>
			<input type="radio" name="gridlove[authors][orderby]" value="<?php echo esc_attr($value); ?>" <?php echo $checked; ?>>
			<label for="gridlove[authors][orderby]"><?php echo esc_html_e($name, 'gridlove'); ?></label><br>
		<?php endforeach; ?>

		<p><strong><?php esc_html_e('Order', 'gridlove');?></strong></p>
		<input type="radio" name="gridlove[authors][order]" value="DESC" <?php checked($authors_meta['order'],'DESC');?>>
		<label for="gridlove[authors][order]">Descending</label><br>
		<input type="radio" name="gridlove[authors][order]" value="ASC" <?php checked($authors_meta['order'],'ASC');?>>
		<label for="gridlove[authors][order]">Ascending</label><br>
		
		<p><strong><?php esc_html_e('Exclude roles','gridlove');?></strong></p>	
		<?php 
		 	global $wp_roles;
     		$roles = $wp_roles->get_names(); 	

		foreach($roles as $role) : ?>
		  	<input type="checkbox" name="gridlove[authors][roles][]" value="<?php echo esc_attr($role); ?>" <?php echo (in_array($role, $authors_meta['roles'])) ? 'checked="checked"' : ''; ?>>
			<label for="gridlove[authors][roles]"><?php echo esc_html_e($role,'gridlove'); ?></label><br>
		<?php endforeach; ?>

		<p><strong><?php esc_html_e('Exclude by ID','gridlove');?></strong></p>
		<?php $implode_args = !empty($authors_meta['exclude']) ? implode(',', $authors_meta['exclude']) : '' ?>
		<input type="text" name="gridlove[authors][exclude]" value="<?php echo esc_attr( $implode_args);?>"><br>
		<small><?php esc_html_e('Enter author IDs separated by comma', 'gridlove');?></small>
		
	
		<?php 
	}
endif;




/**
 * Generate cpt module
 * 
 * @param   $module Data array for current module
 * @param   $options An array of module options
 * @param   $name_prefix id of a current module
 * @since  1.0
 */

if ( !function_exists( 'gridlove_generate_module_cpt' ) ) :
function gridlove_generate_module_cpt( $module, $options, $name_prefix ){

	extract( $options ); ?>

	<div class="gridlove-opt-tabs">
		<a href="javascript:void(0);" class="active"><?php esc_html_e( 'Appearance', 'gridlove' ); ?></a>
		<a href="javascript:void(0);"><?php esc_html_e( 'Selection', 'gridlove' ); ?></a>
	</div>

	<div class="gridlove-tab first">

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Title', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me mod-title" type="text" name="<?php echo esc_attr($name_prefix); ?>[title]" value="<?php echo esc_attr($module['title']);?>"/>
				<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[hide_title]" value="1" <?php checked( $module['hide_title'], 1 ); ?> class="gridlove-count-me" />
				<?php esc_html_e( 'Do not display publicly', 'gridlove' ); ?>
				<small class="howto"><?php esc_html_e( 'Enter your module title', 'gridlove' ); ?></small>

			</div>
		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Layout', 'gridlove' ); ?>:
			</div>

		    <div class="gridlove-opt-content">

		    	<?php foreach ( $layout_types as $layout_type => $title ): ?>
		    		<label><input type="radio" class="gridlove-count-me gridlove-module-layout-switch" name="<?php echo esc_attr($name_prefix); ?>[layout_type]" value="<?php echo esc_attr($layout_type); ?>" <?php checked( $layout_type, $module['layout_type'] );?>/> <?php echo esc_html( $title ); ?></label>
		    	<?php endforeach; ?>

		    	<div class="gridlove-module-layouts">
		    		
		    		<?php $active = $module['layout_type'] == 'simple' ? 'active' : ''; ?>

					<div class="gridlove-module-layout simple <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $simple_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['simple_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="<?php echo esc_attr($layout['step'] * 2); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[simple_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['simple_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					</div>


		    		<?php $active = $module['layout_type'] == 'combo' ? 'active' : ''; ?>

		    		<div class="gridlove-module-layout combo <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $combo_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['combo_layout'] ) ? ' selected': ''; ?>
					  			
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>" data-min="<?php echo esc_attr($layout['step']); ?>" data-step="<?php echo esc_attr($layout['step']); ?>" data-default="<?php echo esc_attr($layout['step']); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[combo_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['combo_layout'] );?> />
					  		</li>
					  	<?php endforeach; ?>
					    </ul>
					</div>

					<?php $active = $module['layout_type'] == 'slider' ? 'active' : ''; ?>

					<div class="gridlove-module-layout slider <?php echo esc_attr($active); ?>">
					    <ul class="gridlove-img-select-wrap">
					  	<?php foreach ( $slider_layouts as $id => $layout ): ?>
					  		<li>
					  			<?php $selected_class = gridlove_compare( $id, $module['slider_layout'] ) ? ' selected': ''; ?>
					  			<img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>"  data-min="<?php echo esc_attr($layout['step'] + 1); ?>" data-step="1" data-default="<?php echo esc_attr($layout['step'] + 1); ?>">
					  			<br/><span><?php echo esc_attr($layout['title']); ?></span>
					  			<input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[slider_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['slider_layout'] );?>/>
					  		</li>
					  	<?php endforeach; ?>
					    </ul>

					</div>

                    <?php $active = $module['layout_type'] == 'masonry' ? 'active' : ''; ?>

                    <div class="gridlove-module-layout masonry <?php echo esc_attr($active); ?>">
                        <ul class="gridlove-img-select-wrap">
                            <?php foreach ( $masonry_layouts as $id => $layout ): ?>
                                <li>
                                    <?php $selected_class = gridlove_compare( $id, $module['masonry_layout'] ) ? ' selected': ''; ?>
                                    <img src="<?php echo esc_url($layout['img']); ?>" title="<?php echo esc_attr($layout['title']); ?>" class="gridlove-img-select<?php echo esc_attr($selected_class); ?>"  data-min="<?php echo esc_attr($layout['step'] + 1); ?>" data-step="1" data-default="6">
                                    <br/><span><?php echo esc_attr($layout['title']); ?></span>
                                    <input type="radio" class="gridlove-hidden gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[masonry_layout]" value="<?php echo esc_attr($id); ?>" <?php checked( $id, $module['masonry_layout'] );?>/>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    </div>

					

				</div>

		    	
		    </div>

	    </div>

	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Number of posts', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<?php 
					switch($module['layout_type']){
						case 'combo': $layouts = gridlove_get_combo_layouts(); break;
						case 'slider':  $layouts = gridlove_get_slider_layouts(); break;
                        case 'masonry':  $layouts = gridlove_get_masonry_layouts(); break;
						default: $layouts = gridlove_get_simple_layouts(); break;
					}

					foreach($layouts as $id => $layout ){
						if( $id == $module[$module['layout_type'].'_layout']){
							$selected_step = $layout['step'];
							$selected_min = $layout['step'];
							if( $module['layout_type'] == 'slider'){
								$selected_step = 1;
								$selected_min++; 
							}
							break;
						}
					}
				?>
				<input class="gridlove-count-me gridlove-input-slider" type="range" min="<?php echo esc_attr($selected_min); ?>" step="<?php echo esc_attr($selected_step); ?>" max="30" name="<?php echo esc_attr($name_prefix); ?>[limit]" value="<?php echo esc_attr($module['limit']);?>"/> <span class="gridlove-slider-opt-count"><?php echo esc_attr($module['limit']);?></span><br/>
			</div>
		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Inject custom content:', 'gridlove' ); ?>
			</div>
			<div class="gridlove-opt-content">
				<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[content_inject]" value="1" <?php checked( $module['content_inject'], 1 ); ?> class="gridlove-count-me gridlove-content-inject"/> <small class="howto"><?php esc_html_e( 'Add content/code in certain postition by replacing that post.', 'gridlove' ); ?></small> </label>
			</div>
			
		   	<?php $hidden_class = $module['content_inject'] ? '' : 'gridlove-hidden'; ?>
			
			<div class="gridlove-custom <?php echo esc_attr( $hidden_class ); ?>">
				<div class="gridlove-opt-title">
					<?php esc_html_e( 'Custom content position', 'gridlove' ); ?>:
				</div>
				<div class="gridlove-opt-content">
					<input type="number" class="gridlove-count-me small-text" name="<?php echo esc_attr($name_prefix); ?>[content_position]" value="<?php echo esc_attr( $module['content_position'] ); ?>" />
					<small class="howto"><?php esc_html_e( 'Specify position of a post which will be replaced by custom content ', 'gridlove' ); ?></small>
				</div>
			</div>

			<div class="gridlove-custom <?php echo esc_attr( $hidden_class ); ?>">
				<div class="gridlove-opt-title">
					<?php esc_html_e( 'Custom content', 'gridlove' ); ?>:
				</div>
				<div class="gridlove-opt-content">
					<textarea class="gridlove-count-me" name="<?php echo esc_attr($name_prefix); ?>[content]"><?php echo esc_textarea( $module['content'] ); ?></textarea>
					<small class="howto"><?php esc_html_e( 'Paste any text, HTML, script or shortcodes here', 'gridlove' ); ?></small>
					<br/>
					<label>
						<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autop]" value="1" <?php checked( $module['autop'], 1 ); ?> class="gridlove-count-me" />
						<?php esc_html_e( 'Automatically add paragraphs', 'gridlove' ); ?>
					</label> <br/>

					<label>
						<input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[center]" value="1" <?php checked( $module['center'], 1 ); ?> class="gridlove-count-me" />
						<?php esc_html_e( 'Center align content', 'gridlove' ); ?>
					</label>
				</div>
			</div>

		</div>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Actions:', 'gridlove' ); ?>
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[more_link]" value="1" <?php checked( $module['more_link'], 1 ); ?> class="gridlove-count-me gridlove-more-button-switch"/> <?php esc_html_e( 'Display "view all" button', 'gridlove' ); ?> </label>
		   		<?php $hidden_class = $module['more_link'] ? '' : 'gridlove-hidden'; ?>
		   		<div class="gridlove-more-button-opt <?php echo esc_attr( $hidden_class ); ?>">
			   		<label><?php esc_html_e( 'Text', 'gridlove' ); ?>:</label><input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_text]" value="<?php echo esc_attr($module['more_text']);?>" class="gridlove-count-me" />
			   		<br/><label><?php esc_html_e( 'URL', 'gridlove' ); ?>:</label><input type="text" name="<?php echo esc_attr($name_prefix); ?>[more_url]" value="<?php echo esc_attr($module['more_url']);?>" class="gridlove-count-me" /><br/>
			   		<small class="howto"><?php esc_html_e( 'Specify text and URL for "view all" button', 'gridlove' ); ?></small>
		   		</div>
		   		<?php $hidden_class = $module['layout_type'] == 'slider' ? '' : 'gridlove-hidden'; ?>
		   		<div class="gridlove-autoplay-opt <?php echo esc_attr( $hidden_class ); ?>">
		   			<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[autoplay]" value="1" <?php checked( $module['autoplay'], 1 ); ?> class="gridlove-count-me"/> <?php esc_html_e( 'Autoplay (rotate) slider every', 'gridlove' ); ?> 
			   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[autoplay_time]" value="<?php echo absint($module['autoplay_time']);?>" class="gridlove-count-me small-text" /> <?php esc_html_e( 'seconds', 'gridlove' ); ?> </label>
		   		</div>
		   		
		   	</div>
	    </div>
	    
	    <div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Custom CSS class', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
				<input class="gridlove-count-me" type="text" name="<?php echo esc_attr($name_prefix); ?>[css_class]" value="<?php echo esc_attr($module['css_class']);?>"/><br/>
				<small class="howto"><?php esc_html_e( 'Specify class name for a possibility to apply custom styling to this module using CSS (i.e. my-custom-module)', 'gridlove' ); ?></small>
			</div>
		</div>	    

	</div>

	<div class="gridlove-tab">
		
		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Order by', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $order as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[order]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['order'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
					

				<div class="gridlove-live-search-opt">

					<br/><?php esc_html_e( 'Or choose manually', 'gridlove' ); ?>:<br/>
		   			<input type="text" class="gridlove-live-search" placeholder="<?php esc_html_e( 'Type to search...', 'gridlove' ); ?>" /><br/>
		   			<?php $manualy_selected_posts = gridlove_get_manually_selected_posts($module['manual'], $module['type']); ?>
		   			<?php $manual = !empty( $manualy_selected_posts ) ? implode( ",", $module['manual'] ) : ''; ?>
		   			<input type="hidden" class="gridlove-count-me gridlove-live-search-hidden" data-type="<?php echo esc_attr($module['type']); ?>" name="<?php echo esc_attr($name_prefix); ?>[manual]" value="<?php echo esc_attr($manual); ?>" />
		   			<div class="gridlove-live-search-items tagchecklist">
		   				<?php gridlove_display_manually_selected_posts($manualy_selected_posts); ?>
		   			</div>

		   		</div>



		   	</div>
	    </div>

	     <div class="gridlove-opt-inline">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Sort', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="DESC" <?php checked( $module['sort'], 'DESC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Descending', 'gridlove') ?></label><br/>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[sort]" value="ASC" <?php checked( $module['sort'], 'ASC' ); ?> class="gridlove-count-me" /><?php esc_html_e('Ascending', 'gridlove') ?></label><br/>
		   	</div>
	    </div>

	   	<?php foreach ( $taxonomies as $taxonomy ) : ?>
		    <div class="gridlove-opt">
				<div class="gridlove-opt-title">
					<?php esc_html_e( 'In ', 'gridlove' ); ?><?php echo $taxonomy['name']; ?>:
				</div>
				<div class="gridlove-opt-content">

					<?php if($taxonomy['hierarchical']) : ?>

						<div class="gridlove-fit-height">
				   			<?php foreach ($taxonomy['terms'] as $term) : ?>
				   			<?php $tax = !empty( $module['tax'][$taxonomy['id']] ) ? $module['tax'][$taxonomy['id']] : array(); ?>
				   			<?php $checked = in_array( $term->term_id, $tax ) ? 'checked="checked"' : ''; ?>
				   			<label><input class="gridlove-count-me" type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[tax][<?php echo esc_attr($taxonomy['id']); ?>][]" value="<?php echo esc_attr($term->term_id); ?>" <?php echo $checked; ?> /><?php echo $term->name;?></label><br/>
					   		<?php endforeach; ?>
				   		</div>
			   			<small class="howto"><?php esc_html_e( 'Check whether you want to display cpt from specific', 'gridlove' ); ?> <?php echo $taxonomy['name']; ?></small>

				   	<?php else: ?>
				   	
							<?php $tax = !empty( $module['tax'][$taxonomy['id']] ) ? gridlove_get_tax_term_name_by_id($module['tax'][$taxonomy['id']], $taxonomy['id'] ) : '' ?>
					   		<input type="text" name="<?php echo esc_attr($name_prefix); ?>[tax][<?php echo esc_attr($taxonomy['id']); ?>]" value="<?php echo esc_attr( $tax ); ?>" class="gridlove-count-me"/><br/>
					   		<small class="howto"><?php esc_html_e( 'Specify one or more terms separated by comma. i.e. life, cooking, funny moments', 'gridlove' ); ?></small>

					<?php endif; ?>
                    <br>
                    <label><input type="radio" name="<?php echo esc_attr( $name_prefix ) . '[' . $taxonomy['id'] . '_inc_exc]'; ?>" value="in" <?php checked( $module[$taxonomy['id'] . '_inc_exc'], 'in' ); ?> class="gridlove-count-me" /><?php esc_html_e('Include', 'gridlove') ?></label><br/>
                    <label><input type="radio" name="<?php echo esc_attr( $name_prefix ) . '[' . $taxonomy['id'] . '_inc_exc]'; ?>" value="not_in" <?php checked( $module[$taxonomy['id'] . '_inc_exc'], 'not_in' ); ?> class="gridlove-count-me" /><?php esc_html_e('Exclude', 'gridlove') ?></label><br/>
                    <small class="howto"><?php esc_html_e( 'Whether to include or exclude cpt from selected taxonomies', 'gridlove' ); ?></small>
                </div>
		   	</div>
		<?php endforeach; ?>

		<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Not older than', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<?php foreach ( $time as $id => $title ) : ?>
		   		<label><input type="radio" name="<?php echo esc_attr($name_prefix); ?>[time]" value="<?php echo esc_attr($id); ?>" <?php checked( $module['time'], $id ); ?> class="gridlove-count-me" /><?php echo esc_html( $title );?></label><br/>
		   		<?php endforeach; ?>
		   		<small class="howto"><?php esc_html_e( 'Display posts that are not older than specific time range', 'gridlove' ); ?></small>
	   		</div>
	   	</div>

	   	<div class="gridlove-opt">
			<div class="gridlove-opt-title">
				<?php esc_html_e( 'Unique posts (do not duplicate)', 'gridlove' ); ?>:
			</div>
			<div class="gridlove-opt-content">
		   		<label><input type="checkbox" name="<?php echo esc_attr($name_prefix); ?>[unique]" value="1" <?php checked( $module['unique'], 1 ); ?> class="gridlove-count-me" /></label>
		   		<small class="howto"><?php esc_html_e( 'If you check this option, posts in this module will be excluded from other modules below.', 'gridlove' ); ?></small>
		   	</div>
	    </div>

	</div>

<?php }
endif;

?>
