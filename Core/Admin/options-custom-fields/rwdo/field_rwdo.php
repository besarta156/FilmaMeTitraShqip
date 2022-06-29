<?php

/**
 * Radio button list with disabled custom field created for Redux Framework
 *
 * @since 1.6
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'ReduxFramework_rwdo' ) ) {

    /**
     * Main ReduxFramework_rwdo class
     *
     * @since       1.6
     */
    class ReduxFramework_rwdo {

        /**
         * Field Constructor.
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @return      void
         */
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field  = $field;
            $this->value  = $value;
        }

        /**
         * Field Render Function.
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0
         * @return      void
         */
        function render() {

            if ( ! empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
                if ( empty( $this->field['args'] ) ) {
                    $this->field['args'] = array();
                }
                $this->field['options'] = $this->parent->get_wordpress_data( $this->field['data'], $this->field['args'] );
            }

            $this->field['data_class'] = ( isset( $this->field['multi_layout'] ) ) ? 'data-' . $this->field['multi_layout'] : 'data-full';

            if ( ! empty( $this->field['options'] ) ) {
                echo '<ul class="' . $this->field['data_class'] . '">';

                foreach ( $this->field['options'] as $k => $v ) {
                    $disabled = $this->is_disabled($k);
                    $k = $this->trim_disabled($k);
                    echo '<li>';
                    echo '<label for="' . $this->field['id'] . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '">';
                    echo '<input type="radio" class="radio ' . $this->field['class'] . '" id="' . $this->field['id'] . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="' . $this->trim_disabled($k) . '" ' . checked( $this->value, $k, false ) . ' ' . $disabled . '/>';
                    echo ' <span>' . $v . '</span>';
                    echo '</label>';
                    echo '</li>';
                }
                //foreach

                echo '</ul>';
            }
        } //function

        function is_disabled($string_with_potential_disabled){
            return (strpos($string_with_potential_disabled, '-disabled') != false) ? 'disabled="disabled"' : '';
        }

        function trim_disabled($string_with_potential_disabled){
            return str_replace('-disabled', '', $string_with_potential_disabled);
        }
    }
}
