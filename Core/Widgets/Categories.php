<?php

/**
 * Category widget class
 *
 * @since  1.0
 */

class GRIDLOVE_Category_Widget extends WP_Widget {

	var $defaults;

	function __construct() {
		$widget_ops = array( 'classname' => 'gridlove_category_widget', 'description' => esc_html__( 'Display your category list with this widget', 'gridlove' ) );
		$control_ops = array( 'id_base' => 'gridlove_category_widget' );
		parent::__construct( 'gridlove_category_widget', esc_html__( 'Gridlove Categories', 'gridlove' ), $widget_ops, $control_ops );

		$this->defaults = array(
			'title' => '',
			'categories' => array(),
			'count' => 1,
			'type' => 'count-color'
		);
	}
	


	function widget( $args, $instance ) {
		extract( $args );
		
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		if($instance['type'] == 'full-color'){
			$before_widget = preg_replace('/class="(.*)"/', 'class="$1 full-color"', $before_widget);
		}

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );

		if ( !empty($title) ) {
			echo $before_title . $title . $after_title;
		}

		?>

		<ul class="<?php echo esc_attr('gridlove-'.$instance['type']); ?>">
		    <?php $cats = get_categories( array( 'include'	=> $instance['categories'])); ?>
		    <?php $cats = gridlove_sort_option_items( $cats,  $instance['categories']); ?>
		    <?php foreach($cats as $cat): ?>
		    	<?php if($instance['type'] == 'full-color') : ?>
			    	<?php $count = !empty($instance['count']) ? '<span class="gridlove-count">'.$cat->count.'</span>' : ''; ?>
			    	<li><a class="gridlove-cat <?php echo esc_attr('gridlove-cat-'. $cat->term_id ); ?>" href="<?php echo esc_url(get_category_link($cat)); ?>"><span class="category-text"><?php echo esc_html( $cat->name ); ?></span><?php echo wp_kses_post( $count ); ?></a></li>
		    	<?php else: ?>
		    		<?php $count = !empty($instance['count']) ? '<span class="gridlove-count gridlove-cat '.esc_attr('gridlove-cat-'. $cat->term_id ).'">'.$cat->count.'</span>' : ''; ?>
			    	<li><a href="<?php echo esc_url(get_category_link($cat)); ?>" class="<?php echo esc_attr('gridlove-cat-col-'. $cat->term_id ); ?>"><span class="category-text"><?php echo esc_html( $cat->name ); ?></span><?php echo wp_kses_post( $count ); ?></a></li>
		    	<?php endif; ?>
		    <?php endforeach; ?> 
		</ul>

		<?php
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['categories'] = !empty($new_instance['categories']) ? $new_instance['categories'] : array();
		$instance['count'] = isset($new_instance['count']) ? 1 : 0;
		$instance['type'] = $new_instance['type'];
		
		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->defaults ); ?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title', 'gridlove' ); ?>:</label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<?php $cats = get_categories( array( 'hide_empty' => false, 'number' => 0 ) ); ?>
		<?php $cats = gridlove_sort_option_items( $cats,  $instance['categories']); ?>

		<p>
		<label><?php esc_html_e( 'Choose (re-order) categories:', 'gridlove' ); ?></label><br/>
		<div class="gridlove-widget-content-sortable">
		<?php foreach ( $cats as $cat ) : ?>
		   	<?php $checked = in_array( $cat->term_id, $instance['categories'] ) ? 'checked' : ''; ?>
		   	<label><input type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'categories' )); ?>[]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo esc_attr($checked); ?> /><?php echo esc_html( $cat->name );?></label>
		<?php endforeach; ?>
		</div>
		</p>

		<p>	<label><?php esc_html_e( 'Type:', 'gridlove' ); ?></label><br/>
			<label><input type="radio" name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>" value="count-color" <?php echo checked($instance['type'], 'count-color', true); ?> /><?php esc_html_e( 'Count color', 'gridlove' ); ?></label><br/>
			<label><input type="radio" name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>" value="full-color" <?php echo checked($instance['type'], 'full-color', true); ?> /><?php esc_html_e( 'Full color', 'gridlove' ); ?></label>			
		</p>

		<p>
			<label><input type="checkbox" name="<?php echo esc_attr($this->get_field_name( 'count' )); ?>" value="1" <?php echo checked($instance['count'], 1, true); ?> /><?php esc_html_e( 'Show post count?', 'gridlove' ); ?></label>
		</p>

		<?php
	}

}

?>
