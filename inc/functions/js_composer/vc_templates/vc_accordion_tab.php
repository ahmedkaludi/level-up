<?php
$output = $title = $parent_id = $active = '';

extract(shortcode_atts(array(
	'title' => __("Section", 'trav'),
	'parent_id' => '',
	'active' => '',
	'with_image' => '',
	'img_id' => '',
	'img_src' => '',
	'img_alt' => 'toggle-image',
	'img_width' => '',
	'img_height' => '',
), $atts));

$class_in = ( $active === 'yes') ? ' in':'';
$class_collapsed = ( $active === 'yes') ? '' : ' class="collapsed"';

$accordion_attrs = "";
if ( !empty( $parent_id ) ) {
	$accordion_attrs = ' data-parent="#' . $parent_id . '"';
}

$uid = uniqid("trav-tg");
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'panel', $this->settings['base'], $atts );
$output .= "\n\t\t\t" . '<div class="'.$css_class.'">';

if ( ( $with_image == 'yes' ) && ( ! empty( $img_id ) ) ) {
	$output .=  wp_get_attachment_image( $img_id, 'full' );
} else if ( ( $with_image == 'yes' ) && ( ! empty( $img_src ) ) ) { 
	$img_alt = ( $img_alt != '')?" alt='" . esc_attr( $img_alt ) . "'":'';
	$img_width = ( $img_width != '')?" width='" . esc_attr( $img_width ) . "'":'';
	$img_height = ( $img_height != '')?" height='" . esc_attr( $img_height ) . "'":'';
	
	$output .= '<img src="' . esc_url( $img_src ) . '"' . $img_alt . $img_width . $img_height . '/>';
}

$output .= "\n\t\t\t\t" . '<h4 class="panel-title"><a href="#'.$uid.'" data-toggle="collapse"' . $class_collapsed . $accordion_attrs . '>'.$title.'<span class="open-sub"</span></a></h4>';
$output .= "\n\t\t\t\t" . '<div id="' . $uid . '" class="panel-collapse collapse' . $class_in . '">';
	$output .= "\n\t\t\t\t\t" . '<div class="panel-content">';
	$output .= ($content=='' || $content==' ') ? __("Empty section. Edit page to add content here.", 'trav') : "\n\t\t\t\t" . wpb_js_remove_wpautop($content);
	$output .= "\n\t\t\t\t\t" . '</div>';
	$output .= "\n\t\t\t\t" . '</div>';
$output .= "\n\t\t\t" . '</div> ' . "\n";

echo $output;