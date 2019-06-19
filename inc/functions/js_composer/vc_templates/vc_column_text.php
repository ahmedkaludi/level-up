<?php
$output = $el_class = $css_animation = '';

extract( shortcode_atts( array(
	'el_class' => '',
	'css_animation' => '',
	'css' => ''
), $atts ) );

$el_class = $this->getExtraClass( $el_class );

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
$css_class .= $this->getCSSAnimation( $css_animation );
if ( ! empty( $css_class ) ) { $output .= "\n\t" . '<div class="' . esc_attr( $css_class ) . '">'; } else { $output .= "\n\t" . '<div>'; }
$output .= "\n\t\t\t" . wpb_js_remove_wpautop( $content, true );
$output .= "\n\t" . '</div> ' . $this->endBlockComment( '.wpb_text_column' );

echo $output;