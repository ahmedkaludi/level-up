<?php
/**
 * Number field class.
 */
class RWMB_Number_Field extends RWMB_Input_Field
{
	/**
	 * Get field HTML
	 *
	 * @param mixed $meta
	 * @param array $field
	 * @return string
	 */
	static function html( $meta, $field )
	{
		if ( empty( $field['suffix'] ) ) $field['suffix'] = '';
		$attributes = call_user_func( array( RW_Meta_Box::get_class_name( $field ), 'get_attributes' ), $field, $meta );
		return sprintf( '<input %s>%s', self::render_attributes( $attributes ), $field['suffix'] );
	}

	/**
	 * Normalize parameters for field
	 *
	 * @param array $field
	 *
	 * @return array
	 */
	static function normalize( $field )
	{
		$field = parent::normalize( $field );

		$field = wp_parse_args( $field, array(
			'step' => 1,
			'min'  => 0,
			'max'  => false,
		) );

		return $field;
	}

	/**
	 * Get the attributes for a field
	 *
	 * @param array $field
	 * @param mixed $value
	 *
	 * @return array
	 */
	static function get_attributes( $field, $value = null )
	{
		$attributes = parent::get_attributes( $field, $value );
		$attributes = wp_parse_args( $attributes, array(
			'step' => $field['step'],
			'max'  => $field['max'],
			'min'  => $field['min'],
		) );
		$attributes['type'] = 'number';

		return $attributes;
	}
}
