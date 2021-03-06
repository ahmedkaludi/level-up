<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'levelup_sanitize_integer' ) ) :
/**
 * Sanitize integers
 * @since 1.0.0
 */
function levelup_sanitize_integer( $input ) {
	return absint( $input );
}

endif;

if ( ! function_exists( 'levelup_sanitize_float' ) ) :
/**
 * Sanitize float
 * @since 1.0.0
 */
function levelup_sanitize_float( $input ) {
	return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
}

endif;


if ( ! function_exists( 'levelup_sanitize_choices' ) ) :
/**
 * Sanitize choices
 * @since 1.0.0
 */
function levelup_sanitize_choices( $input, $setting ) {

	// Ensure input is a slug
	$input = sanitize_key( $input );

	// Get list of choices from the control
	// associated with the setting
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it;
	// otherwise, return the default
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
endif;

if ( ! function_exists( 'levelup_sanitize_checkbox' ) ) :
/**
 * Sanitize checkbox values
 * @since 1.0.0
 */
function levelup_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}
endif;


