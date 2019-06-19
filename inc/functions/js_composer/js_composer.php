<?php

// ! File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$extra_class = array(
	'type' 			=> 'textfield',
	'heading' 		=> __( 'Extra class name', 'trav' ),
	'param_name' 	=> 'class',
	'description' 	=> __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'trav' )
);

$ul_class = array(
	'type' 			=> 'textfield',
	'heading' 		=> __( 'Class for ul element', 'trav' ),
	'param_name' 	=> 'ul_class',
	'description' 	=> __( 'If you wish to style ul element differently, then use this field to add a class name and then refer to it in your css file.', 'trav' )
);

$content_area = array(
	"type" 			=> "textarea_html",
	"heading" 		=> __( "Content", 'trav' ),
	"param_name" 	=> "content",
	"description" 	=> __( "Enter your content.", 'trav' )
);

$add_css3_animation = array(
	// animation type
	array(
		"type" 			=> "textfield",
		"class" 		=> "",
		"heading" 		=> __("Animation Type", 'trav'),
		"admin_label" 	=> false,
		"param_name" 	=> "animation_type",
		"value" 		=> "",
		"description" 	=> "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see css/animate.css for the animation types. <br />Note: Works only in modern browsers."
	),

	// animation duration
	array(
		"type" 			=> "textfield",
		"class" 		=> "",
		"heading" 		=> __("Animation Duration", 'trav'),
		"param_name" 	=> "animation_duration",
		"value" 		=> "1",
		"description" 	=> "Input the duration of animation in seconds.",
		"dependency" 	=> array(
			"element" 	=> "animation_type",
			"not_empty" => true
		)
	),

	// animation delay
	array(
		"type" 			=> "textfield",
		"class" 		=> "",
		"heading" 		=> __("Animation Delay", 'trav'),
		"param_name" 	=> "animation_delay",
		"value" 		=> "0",
		"description" 	=> "Input the delay of animation in seconds.",
		"dependency" 	=> array(
			"element" 	=> "animation_type",
			"not_empty" => true
		)
	)
);

$margin_bottom = array(
	"type" 			=> "dropdown",
	"class" 		=> "",
	"heading" 		=> __("Margin Bottom", 'trav'),
	"param_name" 	=> "margin_bottom_class",
	"value" 		=> array(
		__( "None", 'trav' ) 				=> "",
		__( "Small - 20px", 'trav' ) 		=> "small",
		__( "Medium - 30px", 'trav' ) 		=> "medium",
		__( "Large - 40px", 'trav' ) 		=> "large",
		__( "Extra large - 70px", 'trav' ) 	=> "x-large"
	),
	"description" 	=> "",
	"def" 			=> "none"
);

$acc_type_terms = get_terms( 'accommodation_type', array( 
	'hide_empty' => false 
) );
$acc_types = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $acc_type_terms ) ) :
	foreach ( $acc_type_terms as $term ) {
		$acc_types[$term->name] = $term->term_id;
	}
endif;

$tour_type_terms = get_terms( 'tour_type', array( 
	'hide_empty' => false 
) );
$tour_types = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $tour_type_terms ) ) :
	foreach ( $tour_type_terms as $term ) {
		$tour_types[$term->name] = $term->term_id;
	}
endif;

$car_type_terms = get_terms( 'car_type', array( 
	'hide_empty' => false 
) );
$car_types = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $car_type_terms ) ) :
	foreach ( $car_type_terms as $term ) {
		$car_types[$term->name] = $term->term_id;
	}
endif;

$car_agent_terms = get_terms( 'car_agent', array( 
	'hide_empty' => false 
) );
$car_agents = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $car_agent_terms ) ) :
	foreach ( $car_agent_terms as $term ) {
		$car_agents[$term->name] = $term->term_id;
	}
endif;

$cruise_type_terms = get_terms( 'cruise_type', array( 
	'hide_empty' => false 
) );
$cruise_types = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $cruise_type_terms ) ) :
	foreach ( $cruise_type_terms as $term ) {
		$cruise_types[$term->name] = $term->term_id;
	}
endif;

$cruise_line_terms = get_terms( 'cruise_line', array( 
	'hide_empty' => false 
) );
$cruise_lines = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $cruise_line_terms ) ) :
	foreach ( $cruise_line_terms as $term ) {
		$cruise_lines[$term->name] = $term->term_id;
	}
endif;

$location_terms = get_terms( 'location', array( 
	'parent' => 0, 
	'hide_empty' => false 
) );
$countries = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $location_terms ) ) :
	foreach ( $location_terms as $term ) {
		$countries[$term->term_id] = $term->name;
	}
endif;

$location_terms = get_terms( 'location', array( 
	'hide_empty' => false 
) );

$location_terms = array_filter( $location_terms, 'trav_check_term_depth_1' );
$states = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $location_terms ) ) :
	foreach ( $location_terms as $term ) {
		$states[$term->term_id] = $term->name;
	}
endif;

$location_terms = get_terms( 'location', array( 
	'hide_empty' => false 
) );
$location_terms = array_filter( $location_terms, 'trav_check_term_depth_2' );
$cities = array( 
	__( "All", "trav" ) => "" 
);
if ( ! is_wp_error( $location_terms ) ) :
	foreach ( $location_terms as $term ) {
		$cities[$term->term_id] = $term->name;
	}
endif;

// ! Removing unwanted shortcodes
vc_remove_element("vc_widget_sidebar");
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_rss");
vc_remove_element("vc_gallery");
vc_remove_element("vc_teaser_grid");
// vc_remove_element("vc_btn");
vc_remove_element("vc_cta_button");
vc_remove_element("vc_posts_grid");
vc_remove_element("vc_images_carousel");
vc_remove_element("vc_posts_slider");
vc_remove_element("vc_carousel");
vc_remove_element("vc_message");
vc_remove_element("vc_progress_bar");
vc_remove_element("vc_tour");

vc_add_param("vc_row", array(
	"type" 			=> "checkbox",
	"class" 		=> "",
	"heading" 		=> __("Is Container", 'trav'),
	"param_name" 	=> "is_container",
	"value" 		=> array( 
		__( 'yes', 'trav' ) => 'yes' 
	),
	"description" 	=> "This option will add container class to this row. Please check bootstrap container class for more detail.",
	"def" 			=> ""
));
vc_add_param('vc_row', array(
    'type' => 'checkbox',
    'class' => '',
    'heading' => __( 'Add clearfix', 'deliver' ),
    'param_name' => 'add_clearfix',
    'value' => array(
        '' => 'true'
    )
));
vc_add_param("vc_row_inner", array(
	"type" => "checkbox",
	"class" => "",
	"heading" => __("Is Container", 'trav'),
	"param_name" => "is_container",
	"value" => array( __( 'yes', 'trav' ) => 'yes' ),
	"description" => "This option will add container class to this row. Please check bootstrap container class for more detail.",
	"def" => ""
));

vc_add_param('vc_row_inner', array(
    'type' => 'checkbox',
    'class' => '',
    'heading' => __( 'Add clearfix', 'deliver' ),
    'param_name' => 'add_clearfix',
    'value' => array(
        '' => 'true'
    )
));
vc_add_param("vc_row", $margin_bottom);
vc_add_param("vc_row_inner", $margin_bottom);
vc_add_param("vc_column", $margin_bottom);
vc_add_param("vc_column_inner", $margin_bottom);
vc_add_param("vc_column_text", $margin_bottom);

/* container */
vc_map( array(
	"name" => __("Container", 'trav'),
	"base" => "container",
	"icon" => "container",
	"is_container" => true,
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		$extra_class
	),
	'js_view' => 'VcColumnView',
) );

/* section */
vc_map( array(
	"name" => __("Section", 'trav'),
	"base" => "section",
	"icon" => "section",
	"is_container" => true,
	"category" => __('by SoapTheme', 'trav'),
	'content_element' => false,
	"params" => array(
		$extra_class
	),
	'js_view' => 'VcColumnView',
) );

/* block */
vc_map( array(
	"name" => __("Block", 'trav'),
	"base" => "block",
	"icon" => "block",
	"category" => __('by SoapTheme', 'trav'),
	"is_container" => true,
	// 'content_element' => false,
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => __("Type", 'trav'),
			"param_name" => "type",
			"value" => array(
				__( "Default", 'trav' ) => "",
				__( "Small", 'trav' ) => "small",
				__( "Medium", 'trav' ) => "medium",
				__( "Large", 'trav' ) => "large",
				__( "Section", 'trav' ) => "section",
				__( "White Box", 'trav' ) => "whitebox",
				__( "Border Box", 'trav' ) => "borderbox",
			),
			"description" => ""
		),
		$extra_class
	),
	'js_view' => 'VcColumnView',
) );

/* block */
vc_map( array(
	"name" => __("Folded Corner Block", 'trav'),
	"base" => "folded_corner_block",
	"icon" => "folded_corner_block",
	"category" => __('by SoapTheme', 'trav'),
	"is_container" => true,
	// 'content_element' => false,
	"params" => array(
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Color', 'trav' ),
			'param_name' => 'background'
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Folded Part Color', 'trav' ),
			'param_name' => 'fold_color'
		),
		array(
			"type" => "textfield",
			"heading" => __("Fold Size", 'trav'),
			"admin_label" => true,
			"param_name" => "fold_size",
			"value" => "60",
		),
		$extra_class
	),
	'js_view' => 'VcColumnView',
) );

/* Button */
vc_map( array(
	"name" => __("Button", 'trav'),
	"base" => "button",
	"icon" => "button",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => __("Link", 'trav'),
			"admin_label" => true,
			"param_name" => "link",
			"value" => "#"
		),
		array(
			"type" => "dropdown",
			"heading" => __("Button Tag", 'trav'),
			"param_name" => "tag",
			"value" => array(
				__( "A(anchor)", "trav" )=> "a",
				__( "Button", "trav" )=> "button",
			),
			"std" => 'a',
		),
		array(
			"type" => "dropdown",
			"heading" => __("Button Type", 'trav'),
			"admin_label" => true,
			"param_name" => "type",
			"value" => array(
				__( "Default", "trav" )=> "",
				__( "Large", "trav" )=> "large",
				__( "Medium", "trav" )=> "medium",
				__( "Small", "trav" )=> "small",
				__( "Mini", "trav" )=> "mini",
				__( "Extra", "trav" )=> "extra",
			),
			"std" => '',
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => __("Color", 'trav'),
			"admin_label" => true,
			"param_name" => "color",
			"value" => array(
				__( "Default", "trav" ) => "",
				__( "White", "trav" ) => "white",
				__( "Silver", "trav" ) => "silver",
				__( "Yellow", "trav" ) => "yellow",
				__( "Green", "trav" ) => "green",
				__( "Red", "trav" ) => "red",
				__( "Orange", "trav" ) => "orange",
				__( "Purple", "trav" ) => "purple",
				__( "Light Brown", "trav" ) => "light-brown",
				__( "Light Orange", "trav" ) => "light-orange",
				__( "Light Purple", "trav" ) => "light-purple",
				__( "Light Yellow", "trav" ) => "light-yellow",
				__( "Dull Blue", "trav" ) => "dull-blue",
				__( "Sea Blue", "trav" ) => "sea-blue",
				__( "Sky Blue1", "trav" ) => "sky-blue1",
				__( "Sky Blue2", "trav" ) => "sky-blue2",
				__( "Dark Blue1", "trav" ) => "dark-blue1",
				__( "Dark Blue2", "trav" ) => "dark-blue2",
				__( "Dark Orange", "trav" ) => "dark-orange",
			),
			"std" => 'md',
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => __("Target", 'trav'),
			"param_name" => "target",
			"value" => array(
				"_self" => "_self",
				"_blank" => "_blank",
				"_top" => "_top",
				"_parent" => "_parent"
			),
			"std" => '',
			"description" => ""
		),
		array(
			"type" => "textfield",
			"heading" => __("Icon", 'trav'),
			"admin_label" => true,
			"param_name" => "icon",
			'description' => 'f.e: fa fa-cog'
		),
		$content_area,
		$extra_class
	)
) );

/* Alert */
vc_map( array(
	"name" => __("Alert Box", 'trav'),
	"base" => "alert",
	"icon" => "alert",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Type', 'trav' ),
			'param_name' => 'type',
			'value' => array(
				__( 'General', 'trav' ) => 'general',
				__( 'Notice', 'trav' ) => 'notice',
				__( 'Success', 'trav' ) => 'success',
				__( 'Error', 'trav' ) => 'error',
				__( 'Help', 'trav' ) => 'help',
				__( 'Information', 'trav' ) => 'info'
			),
			'admin_label' => true,
			'std' => 'general'
		),
		$content_area,
		$extra_class
	)
) );

/* Blockquote Shortcode */
vc_map( array(
	"name" => __("Blockquote", 'trav'),
	"base" => "blockquote",
	"icon" => "blockquote",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", 'trav' ) => "style1",
				__( "Style 2", 'trav' ) => "style2",
			),
			"description" => ""
		),
		$content_area,
		$extra_class
	)
) );



/* Images Shortcode */
vc_map( array(
	"name" => __("Images", 'trav'),
	"base" => "images",
	"icon" => "images",
	"is_container" => true,
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", 'trav' ) => "style1",
				__( "Style 2", 'trav' ) => "style2",
			),
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Number of Column", 'trav'),
			"param_name" => "column",
			"value" => array(
				__( "2", 'trav' ) => "2",
				__( "3", 'trav' ) => "3",
				__( "4", 'trav' ) => "4",
				__( "5", 'trav' ) => "5",
			),
			"std" => "3",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array("style1")
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Position", 'trav'),
			"param_name" => "position",
			"value" => array(
				__( "Left", 'trav' ) => "left",
				__( "Right", 'trav' ) => "right",
			),
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array("style1")
			)
		),
		$extra_class,
	),
	'js_view' => 'VcColumnView',
) );

/* Dropcap Shortcode */
vc_map( array(
	"name" => __("Dropcap", 'trav'),
	"base" => "dropcap",
	"icon" => "dropcap",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", 'trav' ) => "style1",
				__( "Style 2", 'trav' ) => "style2",
			),
			"description" => ""
		),
		$content_area,
		$extra_class
	)
) );

/* Testimonials */
vc_map( array(
	"name" => __("Testimonials", 'trav'),
	"base" => "testimonials",
	"icon" => "testimonials",
	"class" => "",
	"as_parent" => array( 'only' => 'testimonial' ),
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'trav' ),
			'param_name' => 'title',
			'admin_label' => true
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", 'trav' ) => "style1",
				__( "Style 2", 'trav' ) => "style2",
				__( "Style 3", 'trav' ) => "style3",
			),
			"std" => 'style1',
			"description" => ""
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Image Size', 'trav' ),
			'param_name' => 'author_img_size',
			'value' => '74'
		),
		$extra_class
	),
	'js_view' => 'VcColumnView',
	'default_content' => '[testimonial][/testimonial]'
) );

/* Testimonial */
vc_map( array(
	"name" => __("Testimonial", 'trav'),
	"base" => "testimonial",
	"icon" => "testimonial",
	"allowed_container_element" => 'testimonials',
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Name', 'trav' ),
			'param_name' => 'author_name',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Author Link', 'trav' ),
			'param_name' => 'author_link'
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Photo of Author', 'trav' ),
			'param_name' => 'author_img_id'
		),
		$content_area,
		$extra_class
	)
) );

/* Icon Box Shortcode */
vc_map( array(
	"name" => __("Icon Box", 'trav'),
	"base" => "icon_box",
	"icon" => "icon_box",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Class for icon", 'trav'),
			"admin_label" => true,
			"param_name" => "icon",
			'description' => 'f.e: fa fa-coffee'
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( "Default", "trav" ) => "",
				__( "Style1", "trav" ) => "style1",
				__( "Style2", "trav" ) => "style2",
				__( "Style3", "trav" ) => "style3",
				__( "Style4", "trav" ) => "style4",
				__( "Style5", "trav" ) => "style5",
				__( "Style6", "trav" ) => "style6",
				__( "Style7", "trav" ) => "style7",
				__( "Style8", "trav" ) => "style8",
				__( "Style9", "trav" ) => "style9",
				__( "Style10", "trav" ) => "style10",
				__( "Style11", "trav" ) => "style11",
			),
			"std" => "",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Number", 'trav'),
			"param_name" => "number",
			"value" => "",
			"description" => "",
			"dependency" => array(
				"element" => "style",
				"value" => array("style3")
			)
		),
		$content_area,
		$extra_class,
	)
) );

/* Parallax Block */
vc_map( array(
	"name" => __("Parallax Block", 'trav'),
	"base" => "parallax_block",
	"icon" => "parallax_block",
	"class" => "",
	"is_container" => true,
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Background Image Url', 'trav' ),
			'param_name' => 'bg_image'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Parallax Ratio (0 ~ 1)', 'trav' ),
			'param_name' => 'ratio',
			'value' => 0.5
		),
		$extra_class
	),
	'js_view'		 => 'VcColumnView'
) );

/* Accommodation Shortcode */
vc_map( array(
	"name" => __("Accommodations", 'trav'),
	"base" => "accommodations",
	"icon" => "accommodations",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Title', 'trav' ),
			"admin_label" => true,
			"param_name" => "title",
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'trav'),
			"admin_label" => true,
			"param_name" => "type",
			"value" => array(
				__( 'Latest', 'trav' ) => 'latest',
				__( 'Featured', 'trav' ) => 'featured',
				__( 'Popular', 'trav' ) => 'popular',
				__( 'Hot', 'trav' ) => 'hot',
				__( 'Selected', 'trav' ) => 'selected',
			),
			"std" => "latest",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"admin_label" => true,
			"param_name" => "style",
			"value" => array(
				__( 'Style1', 'trav' ) => 'style1',
				__( 'Style2', 'trav' ) => 'style2',
				__( 'Style3', 'trav' ) => 'style3',
				__( 'Style4', 'trav' ) => 'style4',
			),
			"std" => "style1",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count of Accommodations", 'trav'),
			"param_name" => "count",
			"value" => "10",
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Accommodation Type", 'trav'),
			"param_name" => "acc_type",
			"value" => $acc_types,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Country", 'trav'),
			"param_name" => "country",
			"value" => $countries,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("State", 'trav'),
			"param_name" => "state",
			"value" => $states,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("City", 'trav'),
			"param_name" => "city",
			"value" => $cities,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			'type'			=> 'autocomplete',
			'heading' => __( 'Accommodation IDs', 'trav' ),
			'param_name'	=> 'post_ids',
			'settings'		=> array(
				'multiple' => true,
				'sortable' => true,
			),
			'save_always'	=> true,
			'description' 	=> __( 'Please select accommodations you want to show.', 'trav' ),
			"dependency" => array(
				"element" => "type",
				"value" => array("selected")
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Discount Badge', 'trav' ),
			'param_name' => 'show_badge',
			'description' => __( 'Show discount badge', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => 'yes',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider Effect', 'trav' ),
			'param_name' => 'slide',
			'description' => __( 'Render as a Slider', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => 'yes',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Width', 'trav' ),
			"param_name" => "item_width",
			"value" => "270",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Margin', 'trav' ),
			"param_name" => "item_margin",
			"value" => "30",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count Per Row", 'trav'),
			"param_name" => "count_per_row",
			"value" => "4",
			"description" => "",
			"dependency" => array(
				"element" => "slide",
				"value" => array("no")
			)
		),
	)
) );
vc_add_params("accommodations", $add_css3_animation);

/* Tour Shortcode */
vc_map( array(
	"name" => __("Tours", 'trav'),
	"base" => "tours",
	"icon" => "tours",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Title', 'trav' ),
			"admin_label" => true,
			"param_name" => "title",
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'trav'),
			"admin_label" => true,
			"param_name" => "type",
			"value" => array(
				__( 'Latest', 'trav' ) => 'latest',
				__( 'Featured', 'trav' ) => 'featured',
				__( 'Popular', 'trav' ) => 'popular',
				__( 'Hot', 'trav' ) => 'hot',
				__( 'Selected', 'trav' ) => 'selected',
			),
			"std" => "latest",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( 'Style1', 'trav' ) => 'style1',
				__( 'Style2', 'trav' ) => 'style2',
				__( 'Style3', 'trav' ) => 'style3',
			),
			"std" => "style1",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count of Tours", 'trav'),
			"param_name" => "count",
			"value" => "10",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count Per Row", 'trav'),
			"param_name" => "count_per_row",
			"value" => "3",
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Tour Type", 'trav'),
			"param_name" => "tour_type",
			"value" => $tour_types,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Country", 'trav'),
			"param_name" => "country",
			"value" => $countries,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("State", 'trav'),
			"param_name" => "state",
			"value" => $states,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("City", 'trav'),
			"param_name" => "city",
			"value" => $cities,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			'type'			=> 'autocomplete',
			'heading' => __( 'Tour IDs', 'trav' ),
			'param_name'	=> 'post_ids',
			'settings'		=> array(
				'multiple' => true,
				'sortable' => true,
			),
			'save_always'	=> true,
			'description' 	=> __( 'Please select tours you want to show.', 'trav' ),
			"dependency" => array(
				"element" => "type",
				"value" => array("selected")
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Discount Badge', 'trav' ),
			'param_name' => 'show_badge',
			'description' => __( 'Show discount badge', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => '',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider Effect', 'trav' ),
			'param_name' => 'slide',
			'description' => __( 'Render as a Slider', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => 'yes',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Width', 'trav' ),
			"param_name" => "item_width",
			"value" => "270",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Margin', 'trav' ),
			"param_name" => "item_margin",
			"value" => "30",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
	)
) );
vc_add_params("tours", $add_css3_animation);

/* Car Shortcode */
vc_map( array(
	"name" => __("Cars", 'trav'),
	"base" => "cars",
	"icon" => "cars",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Title', 'trav' ),
			"admin_label" => true,
			"param_name" => "title",
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'trav'),
			"admin_label" => true,
			"param_name" => "type",
			"value" => array(
				__( 'Latest', 'trav' ) => 'latest',
				__( 'Featured', 'trav' ) => 'featured',
				__( 'Popular', 'trav' ) => 'popular',
				__( 'Hot', 'trav' ) => 'hot',
				__( 'Selected', 'trav' ) => 'selected',
			),
			"std" => "latest",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( 'Style1', 'trav' ) => 'style1',
				__( 'Style2', 'trav' ) => 'style2',
				__( 'Style3', 'trav' ) => 'style3',
			),
			"std" => "style1",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count of Cars", 'trav'),
			"param_name" => "count",
			"value" => "10",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count Per Row", 'trav'),
			"param_name" => "count_per_row",
			"value" => "4",
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Car Type", 'trav'),
			"param_name" => "car_type",
			"value" => $car_types,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Car Agent", 'trav'),
			"param_name" => "car_agent",
			"value" => $car_agents,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Car IDs', 'trav' ),
			"param_name" => "post_ids",
			"description" => __( "Fill this field with Car IDs separated by commas. ", "trav" ),
			"dependency" => array(
				"element" => "type",
				"value" => array("selected")
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Discount Badge', 'trav' ),
			'param_name' => 'show_badge',
			'description' => __( 'Show discount badge', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => '',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider Effect', 'trav' ),
			'param_name' => 'slide',
			'description' => __( 'Render as a Slider', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => 'yes',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Width', 'trav' ),
			"param_name" => "item_width",
			"value" => "270",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Margin', 'trav' ),
			"param_name" => "item_margin",
			"value" => "30",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
	)
) );
vc_add_params("cars", $add_css3_animation);

/* Cruise Shortcode */
vc_map( array(
	"name" => __("Cruises", 'trav'),
	"base" => "cruises",
	"icon" => "cruises",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Title', 'trav' ),
			"admin_label" => true,
			"param_name" => "title",
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'trav'),
			"admin_label" => true,
			"param_name" => "type",
			"value" => array(
				__( 'Latest', 'trav' ) => 'latest',
				__( 'Featured', 'trav' ) => 'featured',
				__( 'Popular', 'trav' ) => 'popular',
				__( 'Hot', 'trav' ) => 'hot',
				__( 'Selected', 'trav' ) => 'selected',
			),
			"std" => "latest",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( 'Style1', 'trav' ) => 'style1',
				__( 'Style2', 'trav' ) => 'style2',
				__( 'Style3', 'trav' ) => 'style3',
			),
			"std" => "style1",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count of Cruises", 'trav'),
			"param_name" => "count",
			"value" => "10",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count Per Row", 'trav'),
			"param_name" => "count_per_row",
			"value" => "4",
			"description" => ""
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Cruise Type", 'trav'),
			"param_name" => "cruise_type",
			"value" => $cruise_types,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "checkbox",
			"class" => "",
			"heading" => __("Cruise Line", 'trav'),
			"param_name" => "cruise_line",
			"value" => $cruise_lines,
			"std" => "",
			"description" => "",
			"dependency" => array(
				"element" => "type",
				"value" => array('latest', 'featured', 'popular', 'hot')
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Cruise IDs', 'trav' ),
			"param_name" => "post_ids",
			"description" => __( "Fill this field with Cruise IDs separated by commas. ", "trav" ),
			"dependency" => array(
				"element" => "type",
				"value" => array("selected")
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Discount Badge', 'trav' ),
			'param_name' => 'show_badge',
			'description' => __( 'Show discount badge', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => '',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider Effect', 'trav' ),
			'param_name' => 'slide',
			'description' => __( 'Render as a Slider', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => 'yes',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => '',
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Width', 'trav' ),
			"param_name" => "item_width",
			"value" => "270",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Margin', 'trav' ),
			"param_name" => "item_margin",
			"value" => "30",
			"dependency" => array(
				"element" => "slide",
				"value" => array("yes")
			)
		),
	)
) );
vc_add_params("cruises", $add_css3_animation);

/* Posts Shortcode */
vc_map( array(
	"name" => __("Posts", 'trav'),
	"base" => "posts",
	"icon" => "posts",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Title', 'trav' ),
			"admin_label" => true,
			"param_name" => "title",
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Type", 'trav'),
			"admin_label" => true,
			"param_name" => "type",
			"value" => array(
				__( 'Latest', 'trav' ) => 'latest',
				__( 'Popular', 'trav' ) => 'popular',
				__( 'Selected', 'trav' ) => 'selected',
			),
			"std" => "latest",
			"description" => ""
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( 'Style1', 'trav' ) => 'style1',
				__( 'Style2', 'trav' ) => 'style2',
				__( 'Style3', 'trav' ) => 'style3',
				__( 'Style4', 'trav' ) => 'style4',
			),
			"std" => "style1",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count of Posts", 'trav'),
			"param_name" => "count",
			"value" => "10",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Count Per Row", 'trav'),
			"param_name" => "count_per_row",
			"value" => "4",
			"description" => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Post IDs', 'trav' ),
			"param_name" => "post_ids",
			"description" => __( "Fill this field with Post IDs separated by commas. ", "trav" ),
			"dependency" => array(
				"element" => "type",
				"value" => array("selected")
			)
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider Effect', 'trav' ),
			'param_name' => 'slide',
			'description' => __( 'Render as a Slider', 'trav' ),
			'value' => array(
				__( 'Yes', 'trav' ) => '',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => 'no',
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Width', 'trav' ),
			"param_name" => "item_width",
			"value" => "270",
			"dependency" => array(
				"element" => "slide",
				"value" => array("")
			)
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Slide Item Margin', 'trav' ),
			"param_name" => "item_margin",
			"value" => "30",
			"dependency" => array(
				"element" => "slide",
				"value" => array("")
			)
		),
	)
) );
vc_add_params("posts", $add_css3_animation);


/* Slider Shortcode */
vc_map( array(
	"name" => __("Slider", 'trav'),
	"base" => "slider",
	"icon" => "slider",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	'as_parent' => array( 'only' => 'slide' ),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Slider Type', 'trav' ),
			'param_name' => 'type',
			'value' => array(
				__( 'Gallery1', 'trav' ) => 'gallery1',
				__( 'Gallery2', 'trav' ) => 'gallery2',
				__( 'Gallery3', 'trav' ) => 'gallery3',
				__( 'Gallery4', 'trav' ) => 'gallery4',
				__( 'Carousel1', 'trav' ) => 'carousel1',
				__( 'Carousel2', 'trav' ) => 'carousel2',
				__( 'Carousel3', 'trav' ) => 'carousel',
			),
			'std' => '',
			'description' => ""
		),
		$ul_class,
		$extra_class
	),
	'default_content' => '[slide][/slide]',
	'js_view' => 'VcColumnView'
) );

/* Slide Shortcode */
vc_map( array(
	"name" => __("Slide", 'trav'),
	"base" => "slide",
	"icon" => "slide",
	"is_container" => true,
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"allowed_container_element" => 'slider',
	'as_child' => array( 'only' => 'slider, images' ),
	"params" => array(
		// $content_area,
		$extra_class
	),
	'js_view' => 'VcColumnView'
) );

/* Person Shortcode */
vc_map( array(
	"name" => __("Person", 'trav'),
	"base" => "person",
	"icon" => "person",
	"is_container" => true,
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'trav' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Style1', 'trav' ) => 'style1',
				__( 'Style2', 'trav' ) => 'style2'
			),
			'std' => 'style1',
			'description' => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Link', 'trav' ),
			"param_name" => "link",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Image Url', 'trav' ),
			"param_name" => "img_src",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Image Alt', 'trav' ),
			"param_name" => "img_alt",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Image Width', 'trav' ),
			"param_name" => "img_width",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Image Height', 'trav' ),
			"param_name" => "img_height",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Twitter Link', 'trav' ),
			"param_name" => "twitter",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Google Plus Link', 'trav' ),
			"param_name" => "googleplus",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Facebook Link', 'trav' ),
			"param_name" => "facebook",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'LinkedIn Link', 'trav' ),
			"param_name" => "linkedin",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Vimeo Link', 'trav' ),
			"param_name" => "vimeo",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Flickr Link', 'trav' ),
			"param_name" => "flickr",
		),
		$content_area,
		$extra_class
	)
) );

/* Social Links Shortcode */
vc_map( array(
	"name" => __("Social Links", 'trav'),
	"base" => "social_links",
	"icon" => "social_links",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				"Style1" => "style1",
				"Style2" => "style2"
			),
			"std" => 'style1',
			"description" => "Please select social link buttons's style in here."
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Link Target", 'trav'),
			"param_name" => "linktarget",
			"value" => array(
				"_self" => "",
				"_blank" => "_blank",
				"_top" => "_top",
				"_parent" => "_parent"
			),
			"std" => '_blank',
			"description" => "Do you want to open links in new window or ... ? Please select target type here."
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Twitter Link', 'trav' ),
			"param_name" => "twitter",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Google Plus Link', 'trav' ),
			"param_name" => "googleplus",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Facebook Link', 'trav' ),
			"param_name" => "facebook",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'LinkedIn Link', 'trav' ),
			"param_name" => "linkedin",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Vimeo Link', 'trav' ),
			"param_name" => "vimeo",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Flickr Link', 'trav' ),
			"param_name" => "flickr",
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Skype Link', 'trav' ),
			"param_name" => "skype",
		),
		$extra_class
	)
) );

/* Background Slider Shortcode */
vc_map( array(
	"name" => __("Background Slider", 'trav'),
	"base" => "bgslider",
	"icon" => "bgslider",
	"class" => "",
	"is_container" => true, 
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Full Screen', 'trav' ),
			'param_name' => 'full_screen',
			'value' => array(
				__( 'Yes', 'trav' ) => 'yes',
				__( 'No', 'trav' ) => 'no'
			),
			'std' => 'yes',
			'description' => ""
		),
		array(
			"type" => "textfield",
			"class" => "",
			'heading' => __( 'Image Urls', 'trav' ),
			"description" => __( "Fill this field with Image Urls separated by commas. ", "trav" ),
			"param_name" => "img_urls",
		),
		// $content_area,
		$ul_class,
		$extra_class
	),
	'js_view' => 'VcColumnView'
) );

/* imageframe Shortcode */
vc_map( array(
	"name" => __("Image Frame", 'trav'),
	"base" => "imageframe",
	"icon" => "imageframe",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'trav' ),
			'param_name' => 'title',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image Url', 'trav' ),
			'param_name' => 'src',
			'admin_label' => true
		),
		array(
			"type" => "textfield",
			"heading" => __("Link", 'trav'),
			"param_name" => "link",
			"value" => "#",
			"description" => "You can fill 'no' if you do not need link."
		),
		array(
			"type" => "dropdown",
			"heading" => __("Hover Effect", 'trav'),
			"param_name" => "hover",
			"value" => array(
				__( "No Effect", "trav" )=> "",
				__( "Default Effect", "trav" )=> "hover-effect",
				__( "Yellow Effect", "trav" )=> "hover-effect yellow",
			),
			"std" => '',
		),
		array(
			"type" => "textfield",
			"heading" => __("Width", 'trav'),
			"param_name" => "width",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"heading" => __("Height", 'trav'),
			"param_name" => "height",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"heading" => __("Label", 'trav'),
			"param_name" => "label",
			"value" => ""
		),
		array(
			"type" => "textfield",
			"heading" => __("Label Content", 'trav'),
			"param_name" => "label_content",
			"value" => ""
		),
		array(
			"type" => "checkbox",
			"heading" => __("Image Centerize", 'trav'),
			"param_name" => "position",
			"value" => array(
				__( "Yes", "trav" )=> "middle",
			),
			"std" => '',
		),
		$content_area,
		$extra_class
	),
) );
vc_add_params("imageframe", $add_css3_animation);

/* content_boxes Shortcode */
vc_map( array(
	"name" => __("Content Box Wrapper", 'trav'),
	"base" => "content_boxes",
	"icon" => "content_boxes",
	"is_container" => true,
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"admin_label" => true,
			"value" => array(
				__( "Style 1", 'trav' ) => "style1",
				__( "Style 2", 'trav' ) => "style2",
				__( "Style 3", 'trav' ) => "style3",
				__( "Style 4", 'trav' ) => "style4",
				__( "Style 5", 'trav' ) => "style5",
				__( "Style 6", 'trav' ) => "style6",
				__( "Style 7", 'trav' ) => "style7",
				__( "Style 8", 'trav' ) => "style8",
				__( "Style 9", 'trav' ) => "style9",
				__( "Style 10", 'trav' ) => "style10",
				__( "Style 11", 'trav' ) => "style11",
				__( "Style 12", 'trav' ) => "style12",
			),
			"std" => '',
		),
		$extra_class
	),
	'js_view' => 'VcColumnView'
) );

/* content_box Shortcode */
vc_map( array(
	"name" => __("Content Box", 'trav'),
	"base" => "content_box",
	"icon" => "content_box",
	// "is_container" => true,
	"class" => "",
	"as_parent" => array( 'only' => 'imageframe, content_box_detail, content_box_action' ),
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		$extra_class
	),
	'js_view' => 'VcColumnView'
) );

/* content_box_detail Shortcode */
vc_map( array(
	"name" => __("Content Box Detail", 'trav'),
	"base" => "content_box_detail",
	"icon" => "content_box_detail",
	"class" => "",
	"as_child" => array( 'only' => 'content_box, animation' ),
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		$content_area,
		$extra_class
	)
) );

/* content_box_action Shortcode */
vc_map( array(
	"name" => __("Content Box Action", 'trav'),
	"base" => "content_box_action",
	"icon" => "content_box_action",
	"class" => "",
	"as_child" => array( 'only' => 'content_box' ),
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		$content_area,
		$extra_class
	)
) );

/* promo_box Shortcode */
vc_map( array(
	"name" => __("Promotion Box", 'trav'),
	"base" => "promo_box",
	"icon" => "promo_box",
	"class" => "",
	"as_parent" => array( 'only' => 'promo_box_left, promo_box_right' ),
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"heading" => __("Type", 'trav'),
			"param_name" => "type",
			"admin_label" => true,
			"value" => array(
				__( "Type 1", 'trav' ) => "type1",
				__( "Type 2", 'trav' ) => "type2",
			),
			"std" => '',
		),
		array(
			"type" => "attach_image",
			"heading" => __("Main Image", 'trav'),
			"param_name" => "img_id",
			"value" => ""
		),
		array(
			"type" => "dropdown",
			"heading" => __("Image Section Width", 'trav'),
			"param_name" => "img_section_width",
			"value" => array(
				__( "2 columns - 1/6", 'trav' ) => "2",
				__( "3 columns - 1/4", 'trav' ) => "3",
				__( "4 columns - 1/3", 'trav' ) => "4",
				__( "5 columns - 5/12", 'trav' ) => "5",
				__( "6 columns - 1/2", 'trav' ) => "6",
				__( "7 columns - 7/12", 'trav' ) => "7",
				__( "8 columns - 2/3", 'trav' ) => "8",
				__( "9 columns - 3/4", 'trav' ) => "9",
				__( "10 columns - 5/6", 'trav' ) => "10",
			),
			'description' => __( 'You can change image section width.','trav' ),
			"std" => '4',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image Alt', 'trav' ),
			'param_name' => 'img_alt'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image Width', 'trav' ),
			'param_name' => 'img_width'
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Image Height', 'trav' ),
			'param_name' => 'img_height'
		),
		array(
			"type" => "dropdown",
			"heading" => __("Content Section Width", 'trav'),
			"param_name" => "content_section_width",
			"value" => array(
				__( "2 columns - 1/6", 'trav' ) => "2",
				__( "3 columns - 1/4", 'trav' ) => "3",
				__( "4 columns - 1/3", 'trav' ) => "4",
				__( "5 columns - 5/12", 'trav' ) => "5",
				__( "6 columns - 1/2", 'trav' ) => "6",
				__( "7 columns - 7/12", 'trav' ) => "7",
				__( "8 columns - 2/3", 'trav' ) => "8",
				__( "9 columns - 3/4", 'trav' ) => "9",
				__( "10 columns - 5/6", 'trav' ) => "10",
			),
			'description' => __( 'You can change content section width.','trav' ),
			"std" => '8',
		),
		$extra_class,
	),
	'js_view' => 'VcColumnView'
) );
vc_add_params("promo_box", $add_css3_animation);

vc_map( array(
	"name" => __("Promo Box Left", 'trav'),
	"base" => "promo_box_left",
	"icon" => "promo_box_left",
	"is_container" => true,
	'as_child' => array( 'only' => 'promo_box' ),
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		$extra_class,
	),
	'js_view' => 'VcColumnView'
) );

vc_map( array(
	"name" => __("Promo Box Right", 'trav'),
	"base" => "promo_box_right",
	"icon" => "promo_box_right",
	"is_container" => true,
	'as_child' => array( 'only' => 'promo_box' ),
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		$extra_class,
	),
	'js_view' => 'VcColumnView'
) );

vc_map( array(
	"name" => __("Search Form", 'trav'),
	"base" => "search_form",
	"icon" => "search_form",
	"is_container" => true,
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Post Type", 'trav'),
			"param_name" => "post_type",
			'value' => array(
				__( 'Accommodation', 'trav' ) => 'accommodation',
				__( 'Tour', 'trav' ) => 'tour',
				__( 'Car', 'trav' ) => 'car',
				__( 'Post', 'trav' ) => 'post',
			),
			"std" => "post",
			"description" => "Post type you are going to use search form for.",
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			'value' => array(
				__( 'Style 1', 'trav' ) => 'style1',
				__( 'Style 2', 'trav' ) => 'style2',
				__( 'Style 3', 'trav' ) => 'style3',
			),
			"std" => "post",
			"description" => "Post type you are going to use search form for.",
		),
		$extra_class,
	),
	'js_view' => 'VcColumnView'
) );

/* Animation Shortcode */
vc_map( array(
	"name" => __("CSS3 Animation", 'trav'),
	"base" => "animation",
	"icon" => "animation",
	"class" => "",
	"controls" => "full",
	// "is_container" => true,
	"as_parent" => array( 'only' => 'vc_column_text, vc_raw_html, vc_accordion, vc_tabs, content_box, icon_box, imageframe, content_box_detail, testimonials' ),
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		// animation type
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Animation Type", 'trav'),
			"admin_label" => true,
			"param_name" => "type",
			"value" => "",
			"description" => "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see css/animate.css for the animation types. <br />Note: Works only in modern browsers."
		),
		// animation duration
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Animation Duration", 'trav'),
			"admin_label" => true,
			"param_name" => "duration",
			"value" => "1",
			"description" => "Input the duration of animation in seconds.",
			"dependency" => array(
				"element" => "type",
				"not_empty" => true
			)
		),
		// animation delay
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Animation Delay", 'trav'),
			"admin_label" => true,
			"param_name" => "delay",
			"value" => "0",
			"description" => "Input the delay of animation in seconds.",
			"dependency" => array(
				"element" => "type",
				"not_empty" => true
			)
		),
		$extra_class,
	),
	'js_view' => 'VcColumnView'
) );

vc_map( array(
	"name" => __("Team Member", 'trav'),
	"base" => "team_member",
	"icon" => "team_member",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'trav'),
			"param_name" => "style",
			"value" => array(
				__( "Style 1", 'trav' ) => "style1",
				__( "Style 2", 'trav' ) => "style2",
			),
			"std" => 'style1',
			"description" => ""
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Name', 'trav' ),
			'param_name' => 'name',
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Job', 'trav' ),
			'param_name' => 'job'
		),
		array(
			'type' => 'textarea',
			'heading' => __( 'Description', 'trav' ),
			'param_name' => 'desc'
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Photo', 'trav' ),
			'param_name' => 'photo_id'
		),
		array(
			"type" => "textarea_html",
			"heading" => __( "Social Links", 'trav' ),
			"param_name" => "content",
			"description" => __( "Insert social links.", 'trav' ),
			'value' => '[social_links style="style1" linktarget="_blank" facebook="" twitter=""]'
		)
	)
) );

/* Pricing Table */
vc_map( array(
	"name" => __("Pricing Table", 'trav'),
	"base" => "pricing_table_vc",
	"icon" => "pricing_table_vc",
	"class" => "",
	/*"is_container" => true,*/
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", 'trav'),
			"admin_label" => true,
			"param_name" => "title"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Sub Title", 'trav'),
			"admin_label" => true,
			"param_name" => "sub_title"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Icon Class", 'trav'),
			"param_name" => "icon_class"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Price", 'trav'),
			"admin_label" => true,
			"param_name" => "price"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Interval", 'trav'),
			"admin_label" => true,
			"param_name" => "unit_text",
			"value" => __( "Per Month", 'trav' )
		),
		array(
			"type" => "textarea",
			"heading" => __("Description", 'trav'),
			"param_name" => "description",
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Color", 'trav'),
			"param_name" => "color",
			"value" => array(
				__( "white", 'trav' ) => "white",
				__( "yellow", 'trav' ) => "yellow",
				__( "green", 'trav' ) => "green",
				__( "blue", 'trav' ) => "blue",
				__( "red", 'trav' ) => "red",
			),
			"std" => 'white',
			"description" => ""
		),
		$content_area = array(
			"type" => "textarea_html",
			"heading" => __( "Content", 'trav' ),
			"param_name" => "content",
			"description" => __( "Enter your content.", 'trav' ),
			"value" => '<ul class="check-square features"><li>ONLINE BOOKING</li><li>ADVANCED OPTIONS</li></ul>'
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button title", 'trav'),
			"param_name" => "btn_title"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button Url", 'trav'),
			"param_name" => "btn_url"
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Button Color", 'trav'),
			"param_name" => "btn_color"
		),
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Target", 'trav'),
			"param_name" => "btn_target",
			"value" => array(
				"_self" => "",
				"_blank" => "_blank",
				"_top" => "_top",
				"_parent" => "_parent"
			),
			"std" => '',
			"description" => ""
		),
		$extra_class
	)
) );

/* Map */
vc_map( array(
	"name" => __("Map", 'trav'),
	"base" => "map",
	"icon" => "map",
	"class" => "",
	"category" => __('by SoapTheme', 'trav'),
	"params" => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Zoom', 'trav' ),
			'param_name' => 'zoom'			
		),
		array(
			'type' 			=> 'textfield',
			'heading' 		=> __( 'Width(px)', 'trav' ),
			'param_name' 	=> 'width',
		),
		array(
			'type' 			=> 'textfield',
			'heading' 		=> __( 'Height(px)', 'trav' ),
			'param_name' 	=> 'height',
		),
		array(
			'type' 			=> 'textfield',
			'heading' 		=> __( 'Extra class name', 'trav' ),
			'param_name' 	=> 'class',
			'description' 	=> __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'trav' )
		),
		array(
			"type" => "dropdown",
			"heading" => __( 'Show Accommodations', 'trav'),
			"param_name" => 'show_accommodation',
			"value" => array(
				__( "No", "trav" ) => 'no',
				__( "Yes", "trav" ) => 'yes',
			),
			"std" => 'no',
		),
		array(
			'type'			=> 'autocomplete',
			'heading'		=> __( 'Accommodations', 'trav' ),
			'param_name'	=> 'acc_ids',
			'settings'		=> array(
				'multiple' => true,
				'sortable' => true,
			),
			'save_always'	=> true,
			'description' 	=> __( 'Please select accommodations you want to show in map.', 'trav' ),
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'yes'
			),
		),
		array(
			'type' 			=> 'textfield',
			'heading' 		=> __( 'Center', 'trav' ),
			'param_name' 	=> 'center',
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		),
		array(
			"type" => "dropdown",
			"heading" => __("Map Type", 'trav'),
			"admin_label" => true,
			"param_name" => "toggle_type",
			"value" => array(
				'ROADMAP' => 'ROADMAP',
				'SATELLITE' => 'SATELLITE',
				'HYBRID' =>'HYBRID',
				'TERRAIN' => 'TERRAIN'
			),
			"std" => "ROADMAP",
			"description" => "",
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		),
		array(
			"type" => "checkbox",
			"heading" => __( 'Map Type Control', 'trav'),
			"param_name" => 'type_control',
			"value" => array(
				__( "Yes", "trav" ) => 'yes',
			),
			"std" => 'yes',
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		),
		array(
			"type" => "checkbox",
			"heading" => __( 'Map Nav Control', 'trav'),
			"param_name" => 'nav_control',
			"value" => array(
				__( "Yes", "trav" ) => 'yes',
			),
			"std" => 'yes',
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		),
		array(
			"type" => "checkbox",
			"heading" => __( 'Map Scroll Wheel', 'trav'),
			"param_name" => 'scrollwheel',
			"value" => array(
				__( "Yes", "trav" ) => 'yes',
			),
			"std" => 'yes',
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		),
		array(
			"type" => "checkbox",
			"heading" => __( 'Street View Control', 'trav'),
			"param_name" => 'street_view_control',
			"value" => array(
				__( "Yes", "trav" ) => 'yes',
			),
			"std" => 'yes',
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		),
		array(
			"type" => "checkbox",
			"heading" => __( 'Draggable', 'trav'),
			"param_name" => 'draggable',
			"value" => array(
				__( "Yes", "trav" ) => 'yes',
			),
			"std" => 'yes',
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		),
		array(
			'type' 			=> 'textfield',
			'heading' 		=> __( 'Markers', 'trav' ),
			'param_name' 	=> 'markers',
			"dependency" => array(
				"element" => "show_accommodation",
				"value" => 'no'
			),
		)
	)
) );

/* Accordion Shortcode */

vc_add_param("vc_accordion", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Toggle Type", 'trav'),
	"admin_label" => true,
	"param_name" => "toggle_type",
	"value" => array(
		"Accordion" => "accordion",
		"Toggle" => "toggle"
	),
	"std" => "",
	"description" => ""
));

vc_add_param("vc_accordion", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style", 'trav'),
	"admin_label" => true,
	"param_name" => "style",
	"value" => array(
		__( "Style 1", 'trav' ) => "style1",
		__( "Style 2", 'trav' ) => "style2",
	),
	"std" => "",
	"description" => ""
));

vc_add_param("vc_accordion", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Show Image", 'trav'),
	"param_name" => "with_image",
	"value" => array(
		__( "Yes", 'trav' ) => "yes",
		__( "No", 'trav' ) => "no",
	),
	"dependency" => array(
		"element" => "toggle_type",
		"value" => array("accordion")
	),
	"std" => "no",
	"description" => ""
));

vc_add_param("vc_accordion", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Image Animation Type", 'trav'),
	"param_name" => "image_animation_type",
	"value" => 'fadeIn',
	"description" => "Input the type of animation if you want this element to be animated when it enters into the browsers viewport. Please see css/animate.css for the animation types. <br />Note: Works only in modern browsers.",
	"dependency" => array(
		"element" => "with_image",
		"value" => array("yes")
	)
));

vc_add_param("vc_accordion", array(
	"type" => "textfield",
	"class" => "",
	"heading" => __("Image Animation Duration", 'trav'),
	"param_name" => "image_animation_duration",
	"value" => '1',
	"dependency" => array(
		"element" => "with_image",
		"value" => array("yes")
	)
));

vc_remove_param('vc_accordion', 'interval');
vc_remove_param('vc_accordion', 'collapsible');
vc_remove_param('vc_accordion', 'disable_keyboard');

vc_map_update("vc_accordion", array(
	'is_container' => false,
	'as_parent' => array( 'only' => 'vc_accordion_tab' ),
));

vc_add_param("vc_accordion_tab", array(
	"type" => "attach_image",
	"class" => "",
	"heading" => __("Attach Image", 'trav'),
	"param_name" => "img_id",
	"value" => '',
));

/* Tabs */
vc_remove_param("vc_tabs", "interval");
vc_add_param("vc_tabs", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style", 'trav'),
	"param_name" => "style",
	"value" => array(
		__( "Style 1", 'trav' ) => "",
		__( "Style 2", 'trav' ) => "style1",
		__( "Vertical", 'trav' ) => "full-width-style",
		__( "Transparent", 'trav' ) => "trans-style"
	),
	"std" => '',
	"description" => ""
));

vc_add_param("vc_tabs", array(
	'type' => 'textfield',
	'heading' => __( 'Active Tab Index', 'trav' ),
	'param_name' => 'active_tab_index',
	'value' => '1'
));

vc_add_param("vc_tabs", array(
	'type' => 'textfield',
	'heading' => __( 'Tab Title', 'trav' ),
	'param_name' => 'title',
	'value' => ''
));

vc_add_param("vc_tabs", array(
	'type' => 'attach_image',
	'heading' => __('Attach Image', 'trav'),
	'param_name' => 'img_id',
	'dependency' => array(
		'element' => 'style',
		'value' => 'trans-style'
	)
));

vc_map_update("vc_tabs", array(
	'is_container' => false,
	'as_parent' => array( 'only' => 'vc_tab' ),
));

vc_add_param("vc_tab", array(
	'type' => 'textfield',
	'heading' => __( 'Icon Class', 'trav' ),
	'param_name' => 'icon_class',
	'description' => 'f.e: fa fa-coffee'
));

/*vc_remove_param("vc_tta_tabs", "shape");
vc_remove_param("vc_tta_tabs", "color");
vc_remove_param("vc_tta_tabs", "pagination_color");
vc_remove_param("vc_tta_tabs", "no_fill_content_area");
vc_remove_param("vc_tta_tabs", "spacing");
vc_remove_param("vc_tta_tabs", "gap");
vc_remove_param("vc_tta_tabs", "tab_position");
vc_remove_param("vc_tta_tabs", "alignment");
vc_remove_param("vc_tta_tabs", "autoplay");
vc_remove_param("vc_tta_tabs", "pagination_style");
vc_remove_param("vc_tta_tabs", "style");
vc_add_param("vc_tta_tabs", array(
	"type" => "dropdown",
	"class" => "",
	"heading" => __("Style", 'trav'),
	"param_name" => "style",
	"value" => array(
		__( "Style 1", 'trav' ) => "",
		__( "Style 2", 'trav' ) => "style1",
		__( "Vertical", 'trav' ) => "full-width-style",
		__( "Transparent", 'trav' ) => "trans-style"
	),
	"std" => '',
	"description" => ""
));

vc_remove_param("vc_tta_tabs", "active_section");
vc_add_param("vc_tta_tabs", array(
	'type' => 'textfield',
	'heading' => __( 'Active Tab Index', 'trav' ),
	'param_name' => 'active_tab_index',
	'value' => '1'
));

vc_add_param("vc_tta_tabs", array(
	'type' => 'attach_image',
	'heading' => 'Attach Image',
	'param_name' => 'img_id',
	'dependency' => array(
		'element' => 'style',
		'value' => 'trans-style'
	)
));

vc_remove_param("vc_tta_section", "i_position");
vc_remove_param("vc_tta_section", "add_icon");
vc_remove_param("vc_tta_section", "i_type");
vc_remove_param("vc_tta_section", "i_icon_openiconic");
vc_remove_param("vc_tta_section", "i_icon_typicons");
vc_remove_param("vc_tta_section", "i_icon_fontawesome");
vc_remove_param("vc_tta_section", "i_icon_entypo");
vc_remove_param("vc_tta_section", "i_icon_linecons");

vc_add_param("vc_tta_section", array(
	'type' => 'textfield',
	'heading' => __( 'Icon Class', 'trav' ),
	'param_name' => 'icon_class',
	'description' => 'f.e: fa fa-coffee'
));*/


if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Container extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Section extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Block extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Folded_Corner_Block extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Testimonials extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Parallax_Block extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Slider extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Slide extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Content_Boxes extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Content_Box extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Promo_Box extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Promo_Box_Left extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Promo_Box_Right extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Animation extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Search_Form extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Images extends WPBakeryShortCodesContainer {}
	class WPBakeryShortCode_Bgslider extends WPBakeryShortCodesContainer {}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Testimonial extends WPBakeryShortCode {}
	class WPBakeryShortCode_Content_Box_Detail extends WPBakeryShortCode {}
	class WPBakeryShortCode_Content_Box_Action extends WPBakeryShortCode {}
	class WPBakeryShortCode_Map extends WPBakeryShortCode {}
}

// Replace rows and columns classes
function trav_vc_shortcode_css_class( $class_string, $tag, $atts ) {
	if ( $tag =='vc_row' || $tag =='vc_row_inner' ) {
        if ( strpos($class_string, 'inner-container') === false ) {
            $class_string = str_replace('vc_row-fluid', 'row', $class_string);
        }
    }
    if ( $tag == 'vc_row_inner' ) {
        if ( !empty( $atts['add_clearfix'] ) ) {
            $class_string .= ' add-clearfix';
        }
    }
	if ($tag =='vc_column' || $tag =='vc_column_inner') {
		if ( !(function_exists('vc_is_inline') && vc_is_inline()) ) {
			$class_string = preg_replace('/vc_col-(\w{2})-(\d{1,2})/', 'col-$1-$2', $class_string);
			$class_string = preg_replace('/vc_hidden-(\w{2})/', 'hidden-$1', $class_string);
		}
	}
	if ( !empty( $atts['margin_bottom_class'] ) ) {
		switch ( $atts['margin_bottom_class'] ) {
			case "none":
				break;
			case "small": // margin-bottom : 20
				$class_string .= ' small-box';
				break;
			case "medium": // margin-bottom : 30
				$class_string .= ' box';
				break;
			case "large": // margin-bottom : 40
				$class_string .= ' block';
				break;
			case "x-large": // margin-bottom : 70
				$class_string .= ' large-block';
				break;
		}
	}

	return $class_string;
}
add_filter('vc_shortcodes_css_class', 'trav_vc_shortcode_css_class', 10, 3);// Filters For autocomplete param:
// For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
add_filter( 'vc_autocomplete_map_acc_ids_callback', 'trav_map_acc_ids_autocomplete_suggestor', 10, 3 );
add_filter( 'vc_autocomplete_map_acc_ids_render', 'trav_map_acc_ids_autocomplete_render', 10, 3 );

add_filter( 'vc_autocomplete_accommodations_post_ids_callback', 'trav_accommodations_post_ids_autocomplete_suggestor', 10, 3 );
add_filter( 'vc_autocomplete_accommodations_post_ids_render', 'trav_accommodations_post_ids_autocomplete_render', 10, 3 );

add_filter( 'vc_autocomplete_tours_post_ids_callback', 'trav_tours_post_ids_autocomplete_suggestor', 10, 3 );
add_filter( 'vc_autocomplete_tours_post_ids_render', 'trav_tours_post_ids_autocomplete_render', 10, 3 );

if ( ! function_exists( 'trav_map_acc_ids_autocomplete_suggestor' ) ) { 
	function trav_map_acc_ids_autocomplete_suggestor( $query, $tag, $param_name ) {
		global $wpdb;

		$post_id = (int) $query;
		$query = esc_sql( trim( $query ) );

		$tbl_posts = esc_sql( $wpdb->posts );

        $sql = "SELECT DISTINCT post_s1.ID AS acc_id, post_s1.post_title as title FROM {$tbl_posts} AS post_s1 
                    WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'accommodation')
                      AND ((post_s1.post_title LIKE '%$query%')
                      OR  (post_s1.ID = '{$post_id}'))";

        $post_infos = $wpdb->get_results( $sql, ARRAY_A );
		$result = array();
		if ( is_array( $post_infos ) ) {
			foreach ( $post_infos as $value ) {
				$data = array();
				$data['value'] = $value['acc_id'];
				$data['label'] = __( 'Id', 'trav' ) . ': ' . $value['acc_id'] . ' - ' . __( 'Title', 'trav' ) . ': ' . $value['title'];
				// $data['label'] = $value['title'];
				$result[] = $data;
			}
		}

		return $result;
	}
}

/*
 * Renderer for "acc_ids" field in "Map" shortcode
 */
if ( ! function_exists( 'trav_map_acc_ids_autocomplete_render' ) ) { 
	function trav_map_acc_ids_autocomplete_render( $query ) {
		$query = trim( $query['value'] ); // get value from requested

		if ( ! empty( $query ) ) {
			// get tour
			$acc_object = get_post( (int) $query );

			if ( is_object( $acc_object ) ) {
				$acc_title = $acc_object->post_title;
				$acc_id = $acc_object->ID;

				$acc_title_display = '';
				if ( ! empty( $acc_title ) ) {
					$acc_title_display = ' - ' . __( 'Title', 'trav' ) . ': ' . $acc_title;
				}

				$acc_id_display = __( 'Id', 'trav' ) . ': ' . $acc_id;

				$data = array();
				$data['value'] = $acc_id;
				$data['label'] = $acc_id_display . $acc_title_display;

				return $data;
			}

			return false;
		}

		return false;
	}
}

if ( ! function_exists( 'trav_accommodations_post_ids_autocomplete_suggestor' ) ) { 
	function trav_accommodations_post_ids_autocomplete_suggestor( $query, $tag, $param_name ) {
		global $wpdb;

		$post_id = (int) $query;
		$query = esc_sql( trim( $query ) );

		$tbl_posts = esc_sql( $wpdb->posts );

        $sql = "SELECT DISTINCT post_s1.ID AS acc_id, post_s1.post_title as title FROM {$tbl_posts} AS post_s1 
                    WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'accommodation')
                      AND ((post_s1.post_title LIKE '%$query%')
                      OR  (post_s1.ID = '{$post_id}'))";

        $post_infos = $wpdb->get_results( $sql, ARRAY_A );
		$result = array();
		if ( is_array( $post_infos ) ) {
			foreach ( $post_infos as $value ) {
				$data = array();
				$data['value'] = $value['acc_id'];
				$data['label'] = __( 'Id', 'trav' ) . ': ' . $value['acc_id'] . ' - ' . __( 'Title', 'trav' ) . ': ' . $value['title'];
				// $data['label'] = $value['title'];
				$result[] = $data;
			}
		}

		return $result;
	}
}

/*
 * Renderer for "post_ids" field in "accommodations" shortcode
 */
if ( ! function_exists( 'trav_accommodations_post_ids_autocomplete_render' ) ) { 
	function trav_accommodations_post_ids_autocomplete_render( $query ) {
		$query = trim( $query['value'] ); // get value from requested

		if ( ! empty( $query ) ) {
			// get tour
			$acc_object = get_post( (int) $query );

			if ( is_object( $acc_object ) ) {
				$acc_title = $acc_object->post_title;
				$acc_id = $acc_object->ID;

				$acc_title_display = '';
				if ( ! empty( $acc_title ) ) {
					$acc_title_display = ' - ' . __( 'Title', 'js_composer' ) . ': ' . $acc_title;
				}

				$acc_id_display = __( 'Id', 'js_composer' ) . ': ' . $acc_id;

				$data = array();
				$data['value'] = $acc_id;
				$data['label'] = $acc_id_display . $acc_title_display;

				return $data;
			}

			return false;
		}

		return false;
	}
}

if ( ! function_exists( 'trav_tours_post_ids_autocomplete_suggestor' ) ) { 
	function trav_tours_post_ids_autocomplete_suggestor( $query, $tag, $param_name ) {
		global $wpdb;

		$post_id = (int) $query;
		$query = esc_sql( trim( $query ) );

		$tbl_posts = esc_sql( $wpdb->posts );

        $sql = "SELECT DISTINCT post_s1.ID AS tour_id, post_s1.post_title as title FROM {$tbl_posts} AS post_s1 
                    WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'tour')
                      AND ((post_s1.post_title LIKE '%$query%')
                      OR  (post_s1.ID = '{$post_id}'))";

        $post_infos = $wpdb->get_results( $sql, ARRAY_A );
		$result = array();
		if ( is_array( $post_infos ) ) {
			foreach ( $post_infos as $value ) {
				$data = array();
				$data['value'] = $value['tour_id'];
				$data['label'] = __( 'Id', 'trav' ) . ': ' . $value['tour_id'] . ' - ' . __( 'Title', 'trav' ) . ': ' . $value['title'];
				// $data['label'] = $value['title'];
				$result[] = $data;
			}
		}

		return $result;
	}
}

/*
 * Renderer for "post_ids" field in "tours" shortcode
 */
if ( ! function_exists( 'trav_tours_post_ids_autocomplete_render' ) ) { 
	function trav_tours_post_ids_autocomplete_render( $query ) {
		$query = trim( $query['value'] ); // get value from requested

		if ( ! empty( $query ) ) {
			// get tour
			$tour_object = get_post( (int) $query );

			if ( is_object( $tour_object ) ) {
				$tour_title = $tour_object->post_title;
				$tour_id = $tour_object->ID;

				$tour_title_display = '';
				if ( ! empty( $tour_title ) ) {
					$tour_title_display = ' - ' . __( 'Title', 'trav' ) . ': ' . $tour_title;
				}

				$tour_id_display = __( 'Id', 'trav' ) . ': ' . $tour_id;

				$data = array();
				$data['value'] = $tour_id;
				$data['label'] = $tour_id_display . $tour_title_display;

				return $data;
			}

			return false;
		}

		return false;
	}
}