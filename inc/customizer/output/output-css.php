<?php
/**
 * Level-up Theme Customizer outout for layout settings
 *
 * @package Level-up
 */


if ( ! function_exists( 'levelup_customizer_style' ) ) :
/**
 * Styles the header image and text displayed on the blog.
 *
 * @see levelup_custom_header_setup().
 */
function levelup_customizer_style() {

	// Get default customizer values
	$defaults = levelup_generate_defaults();
	$header_text_color = get_header_textcolor();
	
	?>
	<style type="text/css">
	<?php
	?>
	.levelup-primary-menu .customize-partial-edit-shortcut button {
		margin-left: 50px;
	}
	.site-title a,
	.site-description {
		color: #<?php echo ! empty( $header_text_color ) ? esc_attr( $header_text_color ) : ''; ?>;
	}

	body, button, input, select, optgroup, textarea {
		color: <?php echo $defaults['body_font_color']; ?>;
	}

	body {
		font-family: "<?php echo $defaults['levelup_body_font_family']; ?>", sans-serif;
		font-size: <?php echo $defaults['body_font_size']; ?>px;
	}


	</style>
	<?php
}
endif;

add_action('wp_head', 'levelup_nonamp_fonts');
function levelup_nonamp_fonts(){
	$defaults = levelup_generate_defaults();
	 $google_fonts = $defaults['levelup_body_font_family'];
	 $varients = $defaults['levelup_font_variants'];
	 if(is_array($varients)){
	 	$google_fonts .= ":".implode(',', $varients);
	 }else{
	 	$google_fonts .= ":".$varients;
	 }
	 $google_fonts .= '&subset='.$defaults['levelup_font_subsets'];

	$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s',  $google_fonts  );
	
	wp_enqueue_style( 'levelup-google-fonts', $fonts_url, array(), rand() );
}