<?php

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Level-up Theme Customizer outout for header
 *
 * @package Level-up
 */

/**
 * Level-up Custom Header
 */

function levelup_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'levelup_custom_header_args', array(
		'default-image' 		 => get_template_directory_uri() . '/inc/customizer/assets/img/bg-image.jpg',
		'default-text-color'     => 'F56A6A',
		'width'                  => 1000,
		'height'                 => 250,
		'flex-height'            => true,
		'wp-head-callback'       => 'levelup_customizer_style',
	) ) );
}
add_action( 'after_setup_theme', 'levelup_custom_header_setup' );


/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function levelup_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function levelup_customize_partial_blogdescription() {
	bloginfo( 'description' );
}
?>