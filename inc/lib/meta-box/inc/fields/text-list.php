<?php
/**
 * Text list field class.
 */
class RWMB_Text_List_Field extends RWMB_Multiple_Values_Field
{
	/**
	 * Get field HTML
	 *
	 * @param mixed $meta
	 * @param array $field
	 *
	 * @return string
	 */
	static function html( $meta, $field )
	{
		$html = '';
		if ( ! empty( $field['title'] ) ) $html .= '<h4>' . $field['title'] . '</h4>';
		$input = '<label><input type="text" class="rwmb-text-list" name="%s" id="%s" value="%s" placeholder="%s" />  %s</label><br />';
		$textarea = '<label><textarea class="rwmb-text-list" name="%s" id="%s"/>%s</textarea>  %s</label><br />';

		$i = 0;
		foreach ( $field['options'] as $value => $label )
		{				
			$meta_value = isset( $meta[ $i ] )?$meta[ $i ]:'';
			if ( $value != 'testimonial' ) {
				$html .= sprintf(
					$input,
					$field['field_name'],
					$field['id'],
					$meta_value,
					$value,
					$label
				);
			} else {
				$html .= sprintf(
					$textarea,
					$field['field_name'],
					$field['id'],
					$meta_value,
					$label
				);
			}
			$i++;		
		}
		return $html;
	}

	/**
	 * Get meta value
	 *
	 * @param int   $post_id
	 * @param bool  $saved
	 * @param array $field
	 *
	 * @return mixed
	 */
	static function meta( $post_id, $saved, $field )
	{
		/**
		 * For special fields like 'divider', 'heading' which don't have ID, just return empty string
		 * to prevent notice error when displaying fields
		 */
		if ( empty( $field['id'] ) )
			return '';

		$single = $field['clone'] || ! $field['multiple'];
		$meta   = get_post_meta( $post_id, $field['id'], $single );

		// Use $field['std'] only when the meta box hasn't been saved (i.e. the first time we run)
		$meta = ( ! $saved && '' === $meta || array() === $meta ) ? $field['std'] : $meta;

		$meta_temp = array(array());
		if ( is_array( $meta ) ) {
			foreach ( $meta as $meta_key=>$meta_value ) {
				$meta_temp[$meta_key] = array_map( 'esc_attr', (array) $meta_value );
			}
		}

		return $meta_temp;
	}

	/**
	 * Output the field value
	 * Display option name instead of option value
	 *
	 * @param  array    $field   Field parameters
	 * @param  array    $args    Additional arguments. Not used for these fields.
	 * @param  int|null $post_id Post ID. null for current post. Optional.
	 *
	 * @return mixed Field value
	 */
	static function the_value( $field, $args = array(), $post_id = null )
	{
		$value = self::get_value( $field, $args, $post_id );
		if ( ! $value )
			return '';

		$output = '<ul>';
		if ( $field['clone'] )
		{
			foreach ( $value as $subvalue )
			{
				$output .= '<li>';
				$output .= '<ul>';

				$i = 0;
				foreach ( $field['options'] as $placeholder => $label )
				{
					$output .= sprintf(
						'<li><label>%s</label>: %s</li>',
						$label,
						isset( $subvalue[$i] ) ? $subvalue[$i] : ''
					);
					$i ++;
				}
				$output .= '</ul>';
				$output .= '</li>';
			}
		}
		else
		{
			$i = 0;
			foreach ( $field['options'] as $placeholder => $label )
			{
				$output .= sprintf(
					'<li><label>%s</label>: %s</li>',
					$label,
					isset( $value[$i] ) ? $value[$i] : ''
				);
				$i ++;
			}
		}
		$output .= '</ul>';

		return $output;
	}
}
