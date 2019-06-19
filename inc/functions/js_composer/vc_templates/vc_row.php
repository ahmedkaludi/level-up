<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

$el_class = $full_height = $parallax_speed_bg = $parallax_speed_video = $full_width = $equal_height = $flex_row = $columns_placement = $content_placement = $parallax = $parallax_image = $css = $el_id = $video_bg = $video_bg_url = $video_bg_parallax = '';
$is_container = $animation_type = $animation_delay = $animation_duration = $add_clearfix = $children_same_height = '';
$output = $after_output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

wp_enqueue_script( 'wpb_composer_front_js' );

$el_class = $this->getExtraClass( $el_class );

$css_classes = array(
    'vc_row',
    'wpb_row', //deprecated
    'vc_row-fluid',
    $el_class,
    vc_shortcode_custom_css_class( $css ),
);

if (vc_shortcode_custom_css_has_property( $css, array('border', 'background') ) || $video_bg || $parallax) {
    $css_classes[]='vc_row-has-fill';
}

if (!empty($atts['gap'])) {
    $css_classes[] = 'vc_column-gap-'.$atts['gap'];
}

$wrapper_attributes = array();
// build attributes for wrapper
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $full_width ) ) {
    $wrapper_attributes[] = 'data-vc-full-width="true"';
    $wrapper_attributes[] = 'data-vc-full-width-init="false"';
    if ( 'stretch_row_content' === $full_width ) {
        $wrapper_attributes[] = 'data-vc-stretch-content="true"';
    } elseif ( 'stretch_row_content_no_spaces' === $full_width ) {
        $wrapper_attributes[] = 'data-vc-stretch-content="true"';
        $css_classes[] = 'vc_row-no-padding';
    }
    $after_output .= '<div class="vc_row-full-width"></div>';
}

if ( ! empty( $full_height ) ) {
    $css_classes[] = ' vc_row-o-full-height';
    if ( ! empty( $columns_placement ) ) {
        $flex_row = true;
        $css_classes[] = ' vc_row-o-columns-' . $columns_placement;
    }
}

if ( ! empty( $equal_height ) ) {
    $flex_row = true;
    $css_classes[] = ' vc_row-o-equal-height';
}

    if ( ! empty( $content_placement ) ) {
    $flex_row = true;
        $css_classes[] = ' vc_row-o-content-' . $content_placement;
    }

if ( ! empty( $flex_row ) ) {
    $css_classes[] = ' vc_row-flex';
}

$has_video_bg = ( ! empty( $video_bg ) && ! empty( $video_bg_url ) && vc_extract_youtube_id( $video_bg_url ) );

$parallax_speed = $parallax_speed_bg;
if ( $has_video_bg ) {
    $parallax = $video_bg_parallax;
    $parallax_speed = $parallax_speed_video;
    $parallax_image = $video_bg_url;
    $css_classes[] = ' vc_video-bg-container';
    wp_enqueue_script( 'vc_youtube_iframe_api_js' );
}
if ( empty( $parallax_speed ) ) {
    $parallax_speed = "1.5";
}

if ( !$has_video_bg && !empty( $parallax ) && !empty( $parallax_image ) ) {
    $css_classes[] = 'skrollable skrollable-between';
    $end_percent = ((float)$parallax_speed - 1) * 100;
    $wrapper_attributes[] = 'data-bottom-top="background-position:50% -'. $end_percent .'%;"';
    $wrapper_attributes[] = 'data-top-bottom="background-position:50% '. $end_percent .'%;"';
    $parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
    $parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
    if ( ! empty( $parallax_image_src[0] ) ) {
        $parallax_image_src = $parallax_image_src[0];
    }
    $wrapper_attributes[] = 'style="background: url(' . esc_attr( $parallax_image_src ) . ') repeat-y; background-attachment: fixed; -webkit-background-attachment: fixed; background-size: cover; background-position: 50% 0;"';
} else {
    if ( ! empty( $parallax ) ) {
        wp_enqueue_script( 'vc_jquery_skrollr_js' );
        $wrapper_attributes[] = 'data-vc-parallax="' . esc_attr( $parallax_speed ) . '"'; // parallax speed
        $css_classes[] = 'vc_general vc_parallax vc_parallax-' . $parallax;
        if ( false !== strpos( $parallax, 'fade' ) ) {
            $css_classes[] = 'js-vc_parallax-o-fade';
            $wrapper_attributes[] = 'data-vc-parallax-o-fade="on"';
        } elseif ( false !== strpos( $parallax, 'fixed' ) ) {
            $css_classes[] = 'js-vc_parallax-o-fixed';
        }
    }

    if ( ! empty( $parallax_image ) ) {
        if ( $has_video_bg ) {
            $parallax_image_src = $parallax_image;
        } else {
            $parallax_image_id = preg_replace( '/[^\d]/', '', $parallax_image );
            $parallax_image_src = wp_get_attachment_image_src( $parallax_image_id, 'full' );
            if ( ! empty( $parallax_image_src[0] ) ) {
                $parallax_image_src = $parallax_image_src[0];
            }
        }
        $wrapper_attributes[] = 'data-vc-parallax-image="' . esc_attr( $parallax_image_src ) . '"';
    }
    if ( ! $parallax && $has_video_bg ) {
        $wrapper_attributes[] = 'data-vc-video-bg="' . esc_attr( $video_bg_url ) . '"';
    }
}

if ( !empty( $animation_type ) ) {
    $css_classes[] = 'animated';
    $wrapper_attributes[] = 'data-animation-type="' . esc_attr( $animation_type ) . '"';
    if ( !empty( $animation_duration ) )  {
        $wrapper_attributes[] = 'data-animation-duration="' . esc_attr( $animation_duration ) . '"';
    }
    if ( !empty( $animation_delay ) )  {
        $wrapper_attributes[] = 'data-animation-delay="' . esc_attr( $animation_delay ) . '"';
    }
}

if ( $is_container ) {
    $css_classes[] = 'inner-container';
} else {
    if ( !empty( $add_clearfix ) ) {
        $css_classes[] = 'add-clearfix';
    }
    if ( !empty( $children_same_height ) ) {
        $css_classes[] = 'same-height';
    }
}
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . '>';
if ( $is_container ) {
    $row_classes = array( 'row' );
    if ( !empty( $add_clearfix ) ) {
        $row_classes[] = 'add-clearfix';
    }
    if ( !empty( $children_same_height ) ) {
        $row_classes[] = 'same-height';
    }
    if ( strpos( $el_class, "no-padding" ) !== false ) {
        $row_classes[] = 'no-padding';
    }
    $output .= '<div class="container"><div class="'. esc_attr( implode( ' ', $row_classes ) ) .'">';
}
$output .= wpb_js_remove_wpautop( $content );
if ( $is_container ) {
    $output .= '</div></div>';
}
$output .= '</div>';
$output .= $after_output;

echo $output;