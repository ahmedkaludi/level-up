<?php
$output = $el_class = $active_tab = $toggle_type = $style = '';
//
extract(shortcode_atts(array(
	'toggle_type' => 'toggle',
	'title' => '',
	'style' => 'style1',
	'el_class' => '',
	'active_tab' => '1',
	'with_image' => 'no',
	'image_animation_type' => 'fadeIn', //available values 62 ( bounce|flash|pulse|rubberBand|shake|swing|tada|wobble|bounceIn|bounceInDown|bounceInLeft|bounceInRight|bounceInUp|bounceOut|bounceOutDown|bounceOutLeft|bounceOutRight|bounceOutUp|fadeIn|fadeInDown|fadeInDownBig|fadeInLeft|fadeInLeftBig|fadeInRight|fadeInRightBig|fadeInUp|fadeInUpBig|fadeOut|fadeOutDown|fadeOutDownBig|fadeOutLeft|fadeOutLeftBig|fadeOutRight|fadeOutRightBig|fadeOutUp|fadeOutUpBig|flip|flipInX|flipInY|flipOutX|flipOutY|lightSpeedIn|lightSpeedOut|rotateIn|rotateInDownLeft|rotateInDownRight|rotateInUpLeft|rotateInUpRight|rotateOut|rotateOutDownLeft|rotateOutDownRight|rotateOutUpLeft|rotateOutUpRight|slideInDown|slideInLeft|slideInRight|slideOutLeft|slideOutRight|slideOutUp|hinge|rollIn|rollOut )
	'image_animation_duration' => 1
), $atts));

preg_match_all( '/\[vc_accordion_tab(.*?)]/i', $content, $matches, PREG_OFFSET_CAPTURE );
$tabs_arr = array();
if ( isset( $matches[0] ) ) {
	$tabs_arr = $matches[0];
}

foreach ( $tabs_arr as $i => $tab ) {
	if ( $i === (int)$active_tab - 1 ) {
		$before_content = substr($content, 0, $tab[1]);
		$current_content = substr($content, $tab[1]);
		$current_content = preg_replace('/\[vc_accordion_tab/', '[vc_accordion_tab active="yes"' , $current_content, 1);
		$content = $before_content . $current_content;
	}
}

$uid = uniqid('trav-tgg-');
if ( $toggle_type == 'accordion' ) {
	foreach ( $tabs_arr as $i => $tab ) {
		$before_content = substr($content, 0, $tab[1]);
		$current_content = substr($content, $tab[1]);

		$replace_str = '[vc_accordion_tab parent_id="' . $uid . '"';
		if ( $with_image == 'yes' || $with_image == 'true' ) {
			$replace_str .= ' with_image="yes"';
		}

		$current_content = preg_replace('/\[vc_accordion_tab/', $replace_str , $current_content, 1);
		$content = $before_content . $current_content;
	}
}

$el_class = $this->getExtraClass($el_class);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim('toggle-container ' . $style . ' ' . $el_class), $this->settings['base'], $atts );
$img_atts = '';

if ( $with_image == 'yes' || $with_image == 'true' ) {
	$css_class .= ' with-image';
	$img_atts .= 'data-image-animation-type="'. esc_attr( $image_animation_type ) .'" data-image-animation-duration="' . esc_attr( $image_animation_duration ) . '"';
}

if ( ! empty( $title ) ) { $output .= '<h2>' . esc_html( $title ) . '</h2>'; }

$output .= "\n\t".'<div class="'. esc_attr( $css_class ) .'" id="' . $uid . '" ' . $img_atts . ' >';
$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
$output .= "\n\t".'</div> ';

echo $output;