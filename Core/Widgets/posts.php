<?php

class GRIDLOVE_Posts_Widget extends WP_Widget {

	var $defaults;

	function __construct() {
		$widget_ops = array( 'classname' => 'gridlove_posts_widget', 'description' => esc_html__( 'Display your posts with this widget', 'gridlove' ) );
		$control_ops = array( 'id_base' => 'gridlove_posts_widget' );
		parent::__construct( 'gridlove_posts_widget', esc_html__( 'Gridlove Posts', 'gridlove' ), $widget_ops, $control_ops );

		$this->defaults = array(
			'title' => esc_html__( 'Posts', 'gridlove' ),
			'slider' => 0,
			'per_slide'	=> 3,
			'numposts' => 5,
			'category' => array(),
			'auto_detect' => 0,
			'orderby' => 0,
			'time' => 0,
			'meta' => array( 'date' ),
			'format' => 0,
			'manual' => array(),
			'tag' => array(),
			'autoplay' => 0,
			'autoplay_time' => 5
		);
	}


	function widget( $args, $instance ) {
		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );

		
		if ( !empty( $title ) ) {
			$title =  $before_title . $title . $after_title;
			
			if( !empty( $instance['slider'] ) ) {			
				$title = str_replace('</h4>', '<span class="gridlove-slider-controls" data-autoplay="'.absint($this->get_autoplay( $instance )).'"></span></h4>', $title);
			}

			echo $title;
		}

		$exclude_id = is_single() ? get_the_ID() : 0;

		$q_args = array(
			'post_type'=> 'post',
			'post__not_in' => array($exclude_id),
			'posts_per_page' => $instance['numposts'],
			'ignore_sticky_posts' => 1,
			'orderby' => $instance['orderby']
		);


		if ( !empty( $instance['manual'] ) && !empty( $instance['manual'][0] ) ) {
			$q_args['posts_per_page'] = absint( count( $instance['manual'] ) );
			$q_args['orderby'] =  'post__in';
			$q_args['post__in'] =  $instance['manual'];
			$q_args['post_type'] = array_keys( get_post_types( array( 'public' => true ) ) );

		} else {

			if ( !empty( $instance['auto_detect'] ) && is_single() ) {

				$cats = get_the_category();

				if ( !empty( $cats ) ) {
					foreach ( $cats as $k => $cat ) {
						$q_args['category__in'][] = $cat->term_id;
					}
				}

			} else {

				if ( !empty( $instance['category'] ) ) {
					$q_args['category__in'] = $instance['category'];
				}
			}

			if ( !empty( $instance['tag'] ) ) {
				$q_args['tag_slug__in'] = $instance['tag'];
			}

			if ( !empty( $instance['format'] ) ) {
				
				if( $instance['format'] == 'standard'){
					
					$terms = array();
					$formats = get_theme_support('post-formats');
					if(!empty($formats) && is_array($formats[0])){
						foreach($formats[0] as $format){
							$terms[] = 'post-format-'.$format;
						}
					}
					$operator = 'NOT IN';

				} else {
					$terms = array('post-format-'.$instance['format']);
					$operator = 'IN';
				}
				
				$q_args['tax_query'] = array(
					array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => $terms,
					'operator' => $operator
					)
				);
			}

			if ( $instance['orderby'] == 'views' && function_exists( 'ev_get_meta_key' ) ) {
				
				$q_args['orderby'] = 'meta_value_num';
				$q_args['meta_key'] = ev_get_meta_key();

			}

			if($q_args['orderby'] == 'title'){
				$q_args['order'] = 'ASC';
			}


			if ( !empty( $instance['time'] ) ) {
				$q_args['date_query'] = array(
					'after' => date( 'Y-m-d', gridlove_calculate_time_diff( $instance['time'] ) )
				);
			}
		}
		
		$gridlove_posts = new WP_Query( $q_args );

		if ( $gridlove_posts->have_posts() ): ?>

		<?php $per_slide = !empty( $instance['per_slide'] ) ? absint($instance['per_slide']) : 0; ?>
		<?php $slider_class = !empty( $instance['slider'] ) && $gridlove_posts->post_count > $per_slide ? 'gridlove-widget-slider' : ''; ?>
		<?php if(empty($slider_class)){ $per_slide = 0; } ?>
		
		<div class="gridlove-posts-widget <?php echo esc_attr( $slider_class ); ?>">

			<?php $i = 1; while ( $gridlove_posts->have_posts() ) : $gridlove_posts->the_post(); ?>
				
				<?php if( $per_slide && ( $i == 0 || !( ($i - 1) % $per_slide ) )   ): ?>
					<div class="gridlove-posts-widget-item-wrapper">
				<?php endif; ?>
				
				<article <?php post_class(''); ?>>

		            <?php if ( $fimg = gridlove_get_featured_image( 'thumbnail' ) ) : ?>
		                <div class="entry-image">
		                <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
		                   	<?php echo $fimg; ?>
		                </a>
		                </div>
		            <?php endif; ?>

		            <div class="entry-header">
		                <?php the_title( sprintf( '<h6><a href="%s">', esc_url( get_permalink() ) ), '</a></h6>' );  ?>
		                <?php if(!empty($instance['meta']) && $meta = gridlove_get_meta_data( false, $instance['meta']) ) : ?>
		                	<div class="entry-meta"><?php echo $meta; ?></div>
		            	<?php endif; ?>
		            </div>

				</article>

				
				<?php if( $per_slide && ( !( ($i) % $per_slide ) || $gridlove_posts->post_count == $i  )  ): ?>
					</div>
				<?php endif; ?>

			<?php $i++; endwhile; ?>

		</div>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

		<?php
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['orderby'] = $new_instance['orderby'];
		$instance['category'] = $new_instance['category'];
		$instance['numposts'] = absint( $new_instance['numposts'] );
		$instance['time'] = $new_instance['time'];
		$instance['slider'] = isset( $new_instance['slider'] ) ? 1 : 0;
		$instance['per_slide'] = absint( $new_instance['per_slide'] );
		$instance['auto_detect'] = isset( $new_instance['auto_detect'] ) ? 1 : 0;
		$instance['meta'] = !empty($new_instance['meta']) ? $new_instance['meta'] : array();
		$instance['manual'] = !empty( $new_instance['manual'] ) ? explode( ",", $new_instance['manual'] ) : array();
		$instance['format'] = $new_instance['format'];
		$instance['tag'] = gridlove_get_tax_term_slug_by_name( $new_instance['tag'] );
		$instance['autoplay'] = isset( $new_instance['autoplay'] ) ? 1 : 0;
		$instance['autoplay_time'] = absint( $new_instance['autoplay_time'] );
		
		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title', 'gridlove' ); ?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<p>
	   	 	<label for="<?php echo esc_attr($this->get_field_id( 'numposts' )); ?>"><?php esc_html_e( 'Number of posts to show', 'gridlove' ); ?>:</label>
		 	<input id="<?php echo esc_attr($this->get_field_id( 'numposts' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'numposts' )); ?>" value="<?php echo absint( $instance['numposts'] ); ?>" class="small-text" />
	  	</p>

		<p class="gridlove-opt-slider">
			<input id="<?php echo esc_attr($this->get_field_id( 'slider' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'slider' )); ?>" value="1" <?php checked( 1, $instance['slider'] ); ?>/>
			<label for="<?php echo esc_attr($this->get_field_id( 'slider' )); ?>"><?php esc_html_e( 'Apply slider', 'gridlove' ); ?></label>
		</p>

		<?php $style = isset($instance['slider']) && !empty($instance['slider']) ? 'display:block;': 'display:none;'; ?>
		<p class="gridlove-opt-per-slide" style="<?php echo esc_attr($style); ?>">
	   	 	<label for="<?php echo esc_attr($this->get_field_id( 'per_slide' )); ?>"><?php esc_html_e( 'Posts per slide', 'gridlove' ); ?>:</label>
		 	<input id="<?php echo esc_attr($this->get_field_id( 'per_slide' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'per_slide' )); ?>" value="<?php echo absint( $instance['per_slide'] ); ?>" class="small-text" />
	  	</p>

	  	<p class="gridlove-opt-per-slide" style="<?php echo esc_attr($style); ?>">
	   	 	<label for="<?php echo esc_attr($this->get_field_id( 'autoplay' )); ?>"><input id="<?php echo esc_attr($this->get_field_id( 'autoplay' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'autoplay' )); ?>" value="1" <?php checked( 1, $instance['autoplay'] ); ?>/>
	   	 		<?php esc_html_e( 'Autoplay (rotate) slider every', 'gridlove' ); ?> <input id="<?php echo esc_attr($this->get_field_id( 'autoplay_time' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'autoplay_time' )); ?>" value="<?php echo absint( $instance['autoplay_time'] ); ?>" class="small-text" /> <?php esc_html_e( 'seconds', 'gridlove' ); ?></label>
		 	
	  	</p>

		<p>
	  		<?php $this->widget_meta( $this, $instance['meta'] ); ?>
		</p>

	  	<p>
	  	 <?php $this->widget_orderby( $this, $instance['orderby'] ); ?>
	    </p>

	   <p>
	   	 <label for="<?php echo esc_attr($this->get_field_id( 'manual' )); ?>"><?php esc_html_e( 'Or choose manually', 'gridlove' ); ?>:</label>
		 <input id="<?php echo esc_attr($this->get_field_id( 'manual' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'manual' )); ?>" value="<?php echo esc_attr(implode( ",", $instance['manual'] )); ?>" class="widefat" />
		 <small class="howto"><?php esc_html_e( 'Specify post ids separated by comma if you want to select only those posts. i.e. 213,32,12,45 Note: you can also choose pages as well as custom post types', 'gridlove' ); ?></small>
	   </p>

	  <p>
	  	<?php $this->widget_tax( $this, 'category', $instance['category'] ); ?>
	  </p>

	  
	  <p>
		<input id="<?php echo esc_attr($this->get_field_id( 'auto_detect' )); ?>" type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'auto_detect' )); ?>" value="1" <?php checked( 1, $instance['auto_detect'] ); ?>/>
		<label for="<?php echo esc_attr($this->get_field_id( 'auto_detect' )); ?>"><?php esc_html_e( 'Auto detect category', 'gridlove' ); ?></label>
		<small class="howto"><?php esc_html_e( 'If sidebar is used on single post template, display posts from current post category ', 'gridlove' ); ?></small>
	  </p>

	   <p>
	   	 <label for="<?php echo esc_attr($this->get_field_id( 'tag' )); ?>"><?php esc_html_e( 'Tagged with', 'gridlove' ); ?>:</label>
		 <input id="<?php echo esc_attr($this->get_field_id( 'tag' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'tag' )); ?>" value="<?php echo esc_attr(gridlove_get_tax_term_name_by_slug($instance['tag'])); ?>" class="widefat" />
		 <small class="howto"><?php esc_html_e( 'Specify one or more tags separated by comma. i.e. life, cooking, funny moments', 'gridlove' ); ?></small>
	   </p>

	   <p>
	  	 <?php $this->widget_format( $this, $instance['format'] ); ?>
	   </p>

	<p>
		<label for="<?php echo esc_attr($this->get_field_id( 'time' )); ?>"><?php esc_html_e( 'Only select posts which are not older than', 'gridlove' ); ?>:</label>
		<select id="<?php echo esc_attr($this->get_field_id( 'time' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'time' )); ?>" class="widefat">
			<?php $time = gridlove_get_time_diff_opts(); ?>
			<?php foreach ( $time as $key => $value ): ?>
				<option value="<?php echo esc_attr($key); ?>" <?php selected( $instance['time'], $key, true ); ?>><?php echo esc_html($value);?></option>
			<?php endforeach; ?>
		</select>
	</p>	

	<?php
	}


	function widget_orderby( $widget_instance = false, $orderby = false ) {

		$orders = gridlove_get_post_order_opts();

		if ( !empty( $widget_instance ) ) { ?>
				<label for="<?php echo esc_attr($widget_instance->get_field_id( 'orderby' )); ?>"><?php esc_html_e( 'Order by:', 'gridlove' ); ?></label>
				<select id="<?php echo esc_attr($widget_instance->get_field_id( 'orderby' )); ?>" name="<?php echo esc_attr($widget_instance->get_field_name( 'orderby' )); ?>" class="widefat">
					<?php foreach ( $orders as $key => $order ) { ?>
						<option value="<?php echo esc_attr($key); ?>" <?php selected( $orderby, $key );?>><?php echo esc_html($order); ?></option>
					<?php } ?>
				</select>
		<?php }
	}

	function widget_format( $widget_instance = false, $format = false ) {

		$formats = gridlove_get_post_format_opts();

		if ( !empty( $widget_instance ) ) { ?>
				<label for="<?php echo esc_attr($widget_instance->get_field_id( 'format' )); ?>"><?php esc_html_e( 'Format:', 'gridlove' ); ?></label>
				<select id="<?php echo esc_attr($widget_instance->get_field_id( 'format' )); ?>" name="<?php echo esc_attr($widget_instance->get_field_name( 'format' )); ?>" class="widefat">
					<?php foreach ( $formats as $key => $name ) { ?>
						<option value="<?php echo esc_attr($key); ?>" <?php selected( $format, $key );?>><?php echo esc_html($name); ?></option>
					<?php } ?>
				</select>
		<?php }
	}

	function widget_tax( $widget_instance, $taxonomy, $selected_taxonomy = false ) {
		if ( !empty( $widget_instance ) && !empty( $taxonomy ) ) {
			$categories = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );
?>
				<label for="<?php echo esc_attr($widget_instance->get_field_id( 'category' )); ?>"><?php esc_html_e( 'Choose from:', 'gridlove' ); ?></label><br/>
					<?php foreach ( $categories as $category ) { ?>
						<input type="checkbox" name="<?php echo esc_attr($widget_instance->get_field_name( 'category' )); ?>[]" value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array( $category->term_id, (array)$selected_taxonomy ) ? 'checked': ''?> /> <?php echo esc_html($category->name); ?><br/>
					<?php } ?>
		<?php }
	}

	function widget_style( $widget_instance = false, $current = false ) {

		$styles = array(
			'h' => esc_html__( 'Small (list)', 'gridlove' ),
			'g' => esc_html__( 'Large', 'gridlove' )
		);

		if ( !empty( $widget_instance ) ) { ?>
				<label for="<?php echo esc_attr($widget_instance->get_field_id( 'layout' )); ?>"><?php esc_html_e( 'Posts style:', 'gridlove' ); ?></label>
				<select id="<?php echo esc_attr($widget_instance->get_field_id( 'layout' )); ?>" name="<?php echo esc_attr($widget_instance->get_field_name( 'layout' )); ?>" class="widefat">
					<?php foreach ( $styles as $id => $title ) { ?>
						<option value="<?php echo esc_attr($id); ?>" <?php selected( $current, $id );?>><?php echo $title; ?></option>
					<?php } ?>
				</select>
		<?php }
	}

	function widget_meta( $widget_instance = false, $current = false ) {

		$meta = gridlove_get_meta_opts();

		if ( !empty( $widget_instance ) ) : ?>
				<label for="<?php echo esc_attr($widget_instance->get_field_id( 'meta' )); ?>"><?php esc_html_e( 'Display meta data:', 'gridlove' ); ?></label><br/>
				<?php foreach ( $meta as $id => $title ) : ?>
				<?php $checked = in_array($id, $current ) ? 'checked="checked"' : ''; ?>
				<input type="checkbox" id="<?php echo esc_attr($widget_instance->get_field_id( 'meta' )); ?>" name="<?php echo esc_attr($widget_instance->get_field_name( 'meta' )); ?>[]" value="<?php echo esc_attr($id); ?>" <?php echo $checked; ?>> <?php echo esc_html($title); ?><br/>
				<?php endforeach; ?>
		<?php endif; ?>
	<?php }

	function get_autoplay( $instance ){

		if ( isset( $instance['autoplay'] ) && !empty( $instance['autoplay'] ) &&  isset( $instance['autoplay_time'] ) && !empty( $instance['autoplay_time'] ) ){
			return absint( $instance['autoplay_time'] ) * 1000;  
		}

		return 0;
	}

}

?>
