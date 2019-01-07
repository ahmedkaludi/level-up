<?php
// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Level-up Theme Customizer
 *
 * @package Level-up
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

/**
 * Check for WP_Customizer_Control existence before adding custom control because WP_Customize_Control
 * is loaded on customizer page only
 *
 * @see _wp_customize_include()
 */


function levelup_customize_register( $wp_customize ) {

	// Get default customizer values
	$defaults = levelup_get_option_defaults();

	// Load custom controls
	require_once( get_template_directory() . '/inc/customizer/controls.php' );
	require_once( get_template_directory() . '/inc/customizer/sanitize.php' );


	// Customize title and tagline sections and labels
	

  	// Field Type Theme Options
	if(!is_plugin_active( 'levelup/levelup.php' )){
		$wp_customize->add_panel( 'levelup_panel_main' , array(
			'title'=> 'Levelup',
			'capability'    => 'edit_theme_options',
			'priority'      => 2
		));
	}
	$wp_customize->add_section( 'theme_field_settings' , array(
	'title'      => __('Typography','level-up'),
	'priority'   => 10,
	'panel'		=> 'levelup_panel_main'
	) );

    //Body Font Family
    $wp_customize->add_setting( 'levelup_body_font_family', array(
        'default'           => $defaults['levelup_body_font_family'],
        'capability'    => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( new Levelup_Customizer_Select2_Google_Fonts(
		$wp_customize,
		'levelup_body_font_family',
		array(
	 		'label'       	=> esc_html__( 'Levelup Body Font', 'level-up' ),
	  		'section'     	=> 'theme_field_settings',
	  		'settings'	  	=> 'levelup_body_font_family',
	  		'type'			=> 'select2_google_fonts',
			'transport' 	=> 'postMessage',
	  		'choices'		=> levelup_google_fonts(),
	  ) )
	);

    $wp_customize->add_setting( 'levelup_font_variants', array(
        'default'           => '',
        'capability'    => 'edit_theme_options',
        'transport' => 'postMessage',
		'sanitize_callback' => 'levelup_multi_types_sanitize',
    ));
    $wp_customize->add_control(
	    new Levelup_Customizer_Select2_Multiselect(
	        $wp_customize,
	        'levelup_body_font_variants',
	        array(
	            'label'          => __( 'Levelup Body Font Variants', 'level-up' ),
	            'section'        => 'theme_field_settings',
	            'settings'       => 'levelup_font_variants',
	            'description'    => '',
	            'type'           => 'select2_multiselect',
	            'transport' 	=> 'postMessage',
	            'choices'        => array()
	        )
	    )
	);

	$wp_customize->add_setting( 'levelup_font_subsets', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'capability'    => 'edit_theme_options',
    ));
    $wp_customize->add_control(
	    new WP_Customize_Control(
	        $wp_customize,
	        'levelup_body_font_subsets',
	        array(
	            'label'          => __( 'Levelup Body Font Subsets', 'level-up' ),
	            'section'        => 'theme_field_settings',
	            'settings'       => 'levelup_font_subsets',
	            'description'    => '',
	            'type'           => 'select',
	            'choices'        => array()
	        )
	    )
	);
    //Body font family
    
	


  	// Page Settings

	$wp_customize->add_section( 'levelup_page_settings' , array(
	'title'      => __('Page Settings','level-up'),
	'priority'   => 90
	) );
	// Show breadcrumbs

	$wp_customize->add_setting( 'levelup_page_breadcrumb', array(
			'default'     => $defaults['levelup_page_breadcrumb'],
			'capability'  => 'edit_theme_options',
			'sanitize_callback' => 'levelup_sanitize_checkbox',
	) );
	$wp_customize->add_control(
		new Levelup_Customizer_Toggle_Control(
			$wp_customize,
			'levelup_page_breadcrumb',
			array(
				'label'	      => esc_html__( 'Show Breadcrumbs?', 'level-up' ),
				'section'     => 'levelup_page_settings',
				'settings'    => 'levelup_page_breadcrumb',
				'description'    => 'Breadcrumb works on "Large Header" and "Mini Header"',
				'type'        => 'light',// light, ios, flat
				'priority'    => 20,
			)
		)
	);

}
add_action( 'customize_register', 'levelup_customize_register' );


require get_template_directory() . '/inc/customizer/output/header.php';
require get_template_directory() . '/inc/customizer/output/output-css.php';



/**
 * Level-up scripts
 *
 * @package Level-up
 */

/**
 * Enqueue scripts and styles.
 */

function levelup_site_scripts() {

	wp_enqueue_script( 'levelup-navigation', get_template_directory_uri() . '/inc/customizer/assets/site/js/navigation.js', array(), '', true );
	wp_enqueue_script( 'levelup-skip-link-focus-fix', get_template_directory_uri() . '/inc/customizer/assets/site/js/skip-link-focus-fix.js', array(), '', true );

	wp_enqueue_script( 'levelup-body-js', get_template_directory_uri() . '/inc/customizer/assets/site/js/levelup-body.js', array('jquery'), '', true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'levelup_site_scripts' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */

function levelup_customize_preview_js() {
	wp_enqueue_script( 'levelup-customizer', get_template_directory_uri() . '/inc/customizer/assets/admin/js/customizer.js', array( 'customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'levelup_customize_preview_js' );


/**
 * Admin Script
 */
function levelup_admin_js() {
	wp_enqueue_script( 'levelup-admin', get_template_directory_uri() . '/inc/customizer/assets/admin/js/admin.js', array( 'jquery' ), '', true );
	$default = levelup_generate_defaults();
	$levelup_font_variants = get_theme_mod( 'levelup_font_variants', true );
	if($levelup_font_variants==''){
		$levelup_font_variants = $default['levelup_font_variants'];
	}
	$levelup_font_subsets = get_theme_mod( 'levelup_font_subsets', true );
	if($levelup_font_subsets==''){
		$levelup_font_subsets = $default['levelup_font_subsets'];
	}
	$levelup_settings = array(
		'ajax_url' => admin_url('admin-ajax.php'),
		
		'levelup_google_font' => get_theme_mod( 'levelup_body_font_family', true ),
		'levelup_font_variants' => $levelup_font_variants,
		'levelup_font_subsets' => $levelup_font_subsets,
	);

	wp_localize_script( 'levelup-admin', 'levelup_settings', $levelup_settings );
}
add_action( 'admin_enqueue_scripts', 'levelup_admin_js' );

/**
 * Load All Google Fonts
 */

function levelup_get_google_fonts() {
	return include( get_template_directory() . '/inc/customizer/google-fonts.php' );
}
/**
 * Return All fonts
 */
function levelup_google_fonts() {
	return $fonts = levelup_get_google_fonts();
}

/**
 * Return ALl Google Font Variants
 */
function levelup_google_font_variants() {
	$google_fonts = levelup_google_fonts();
	if(isset($_POST['fontFamily'])) {
		$find_font = levelup_google_font_search($google_fonts, 'name', $_POST['fontFamily']);
		$output = array(
			'variants' => $find_font[0]['variants'],
			'subsets' => $find_font[0]['subsets'],
		);
		echo wp_json_encode( $output );
	}else {
		return;
	}

	die();
}
add_action( 'wp_ajax_load_google_font_variants', 'levelup_google_font_variants' );

/**
 * Level-up Google Font Search
 */
function levelup_google_font_search($array, $key, $value) {
    $results = array();
    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, levelup_google_font_search($subarray, $key, $value));
        }
    }

    return $results;
}

function levelup_multi_types_sanitize( $input ) {
	

	return $input;
}