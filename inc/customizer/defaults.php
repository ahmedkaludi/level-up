<?php
/**
 *
 * @package Level-up
 */

// No direct access, please
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! function_exists( 'levelup_get_option_defaults' ) ) :
/**
 * Set default options
 */
function levelup_get_option_defaults() {

	$levelup_defaults = array(
        
        'theme_feild_type_range' => '1300',
		'theme_feild_type_radio' => 'theme_feild_type_radio_one',
		'amp_page_breadcrumb' => true,
		'amp_page_title_bg' => '#ff5544',

		'levelup_body_font_family' => "Poppins",
		'levelup_body_font_variants' => '100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic',
  		'levelup_body_font_subsets' => 'cyrillic',

  		
        
		
		//'levelup_page_breadcrumb' => true,

	);
	
	return apply_filters( 'levelup_option_defaults', $levelup_defaults );
}
endif;


/**
*  Get default customizer option
*/
if ( ! function_exists( 'levelup_get_option' ) ) :

	/**
	 * Get default customizer option
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function levelup_get_option( $key ) {

		$default_options = levelup_get_option_defaults();

		if ( empty( $key ) ) {
			return;
		}

		$theme_options = (array)get_theme_mod( 'theme_options' );
		$theme_options = wp_parse_args( $theme_options, $default_options );

		$value = null;

		if ( isset( $theme_options[ $key ] ) ) {
			$value = $theme_options[ $key ];
		}

		return $value;
	}

endif;


if( ! function_exists( 'levelup_generate_defaults' ) ) : 

	function levelup_generate_defaults(){

		$default_options = levelup_get_option_defaults();
		$saved_options = get_theme_mods();

		$returned = [];

		foreach( $default_options as $key => $option ) {
			if( array_key_exists( $key, $saved_options ) ) {
				$returned[ $key ] = $saved_options[ $key ];
			} else {
				switch ( $key ) {
					// case 'levelup_heading_font_family':
					// 	$returned[ $key ] = $default_options[ 'body_font_family' ];
					// 	break;
					default:
						$returned[ $key ] = $default_options[ $key ];
						break;
				}
			}
		}

		return $returned;

	}

endif;