<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * accommodation metabox registration
 */
if ( ! function_exists( 'trav_register_acc_meta_boxes' ) ) {
	function trav_register_acc_meta_boxes() {
		$meta_boxes = array();

		//room meta boxes
		$prefix = 'trav_room_';
		$meta_boxes[] = array(
			'id' => 'room_details',
			'title' => __( 'Room Details', 'trav' ),
			'pages' => array( 'room_type' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Accommodation', 'trav' ),
					'id'      => "{$prefix}accommodation",
					'type'  => 'post',
					'std' => isset($_GET['acc_id']) ? sanitize_text_field( $_GET['acc_id'] ) : '',
					'post_type' => 'accommodation',
				),
				array(
					'name'  => __( 'Max Adults', 'trav' ),
					'id'    => "{$prefix}max_adults",
					'desc'  => __( 'How many adults are allowed in the room?', 'trav' ),
					'type' => 'number',
					'std' => 1
				),
				array(
					'name'  => __( 'Max Children', 'trav' ),
					'id'    => "{$prefix}max_kids",
					'desc'  => __( 'How many children are allowed in the room?', 'trav' ),
					'type' => 'number',
					'std' => 0
				),
				array(
					'name'  => __( 'Room Amenities', 'trav' ),
					'id'      => "{$prefix}room_amenities",
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'amenity',
						'type' => 'checkbox_list',
					),
				),
			)
		);

		//things_to_do metaboxes
		$prefix = 'trav_ttd_';
		$meta_boxes[] = array(
			'id' => 'ttd_details',
			'title' => __( 'Things To Do Details', 'trav' ),
			'pages' => array( 'things_to_do' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'City', 'trav' ),
					'id'      => "{$prefix}city",
					//'desc'  => __( 'City of this things to do', 'trav' ),
					'type'  => 'taxonomy',
					'placeholder' => __( 'Select a City', 'trav' ),
					'options' => array(
						'taxonomy' => 'location',
						'args' => array(
							),
						'depth' => 2,
						'type' => 'select_advanced',
					),
				)
			)
		);

		//accommodation metaboxes
		$prefix = 'trav_accommodation_';
		//accommodation_details
		$meta_boxes[] = array(
			'id' => 'accommodation_details',
			'title' => __( 'Details', 'trav' ),
			'pages' => array( 'accommodation' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Type', 'trav' ),
					'id'      => "{$prefix}accommodation_types",
					'desc'  => __( 'Select an accommodation type', 'trav' ),
					'placeholder'  => __( 'Select an accommodation type', 'trav' ),
					'type'  => 'taxonomy',
				    'taxonomy' => 'accommodation_type',
				    'field_type' => 'checkbox_list',
					//'options' => array(
					//	'taxonomy' => 'accommodation_type',
					//	'type' => 'select_advanced',
				    //   'multiple' => true,
					//),
				),
				array(
					'name'  => __( 'Amenities', 'trav' ),
					'id'      => "{$prefix}amenities",
					'desc'  => __( 'Select amenities', 'trav' ),
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'amenity',
						'type' => 'checkbox_list',
					),
				),
				array(
					'name'  => __( 'Other Amenity Info', 'trav' ),
					'id'      => "{$prefix}other_amenity_info",
					'desc'  => __( 'This is the content that will be shown on Amenity tab of Detail Page', 'trav' ),
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Hotel Star Rating', 'trav' ),
					'id'    => "{$prefix}star_rating",
					'desc'  => __( 'If this accommodation doesn\'t have rating then leave it 0' , 'trav' ),
					'type' => 'slider',
					'suffix' => __( ' star', 'trav' ),
					'std'  => 0,
					'js_options' => array(
						'min'   => 0,
						'max'   => 5,
						'step'  => 1,
					),
				),
				array(
					'name'  => __( 'Minimum Stay Info', 'trav' ),
					'id'      => "{$prefix}minimum_stay",
					'desc'  => __( 'Leave it blank if this accommodation does not have minimum stay', 'trav' ),
					'type'  => 'number',
					'suffix'=> 'Nights'
				),
				array(
					'name'           => __( 'Gallery Images', 'trav' ),
					'id'                => "trav_gallery_imgs",
					'type'           => 'image_advanced',
					'max_file_uploads' => 50,
				),
				array(
					'name' => __( 'FAQ to This Accommodation', 'trav' ),
					'id'   => "{$prefix}faq",
					'type' => 'wysiwyg',
					'raw'  => true,
					'std'  => __( 'Please write FAQ here', 'trav' ),
					'options' => array(
						//'textarea_rows' => 4,
						// 'teeny'         => true,
						//'media_buttons' => false,
					),
				),
				array(
					'name'   => __( 'Accommodation Logo', 'trav' ),
					'id'    => "{$prefix}logo",
					'type'   => 'image_advanced',
					'max_file_uploads' => 1,
				),
				array(
					'name'  => __( 'AVG/NIGHT Price', 'trav' ),
					'id'      => "{$prefix}avg_price",
					'desc'  => __( 'This is average price per night field.', 'trav' ),
					'type'  => 'number',
				),
				array(
					'name'  => __( 'Accommodation Brief', 'trav' ),
					'id'      => "{$prefix}brief",
					'desc'  => __( 'This is accommodation brief field and the value is shown on search result page and detail page .', 'trav' ),
					'type'  => 'textarea',
				),
			)
		);

		//accommodation_location
		$meta_boxes[] = array(
			'id' => 'accommodation_location',
			'title' => __( 'Location & Other Info', 'trav' ),
			'pages' => array( 'accommodation' ),
			'context' => 'normal',
			'priority' => 'high',
			'autosave' => true,
			'fields' => array(

				array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location1', 'trav' ),
		        	'id'      => "{$prefix}full_location1",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location2', 'trav' ),
		        	'id'      => "{$prefix}full_location2",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location3', 'trav' ),
		        	'id'      => "{$prefix}full_location3",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location4', 'trav' ),
		        	'id'      => "{$prefix}full_location4",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location5', 'trav' ),
		        	'id'      => "{$prefix}full_location5",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location6', 'trav' ),
		        	'id'      => "{$prefix}full_location6",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location7', 'trav' ),
		        	'id'      => "{$prefix}full_location7",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location8', 'trav' ),
		        	'id'      => "{$prefix}full_location8",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location9', 'trav' ),
		        	'id'      => "{$prefix}full_location9",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location10', 'trav' ),
		        	'id'      => "{$prefix}full_location10",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location11', 'trav' ),
		        	'id'      => "{$prefix}full_location11",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location12', 'trav' ),
		        	'id'      => "{$prefix}full_location12",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location13', 'trav' ),
		        	'id'      => "{$prefix}full_location13",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location14', 'trav' ),
		        	'id'      => "{$prefix}full_location14",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location15', 'trav' ),
		        	'id'      => "{$prefix}full_location15",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location16', 'trav' ),
		        	'id'      => "{$prefix}full_location16",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location17', 'trav' ),
		        	'id'      => "{$prefix}full_location17",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location18', 'trav' ),
		        	'id'      => "{$prefix}full_location18",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location19', 'trav' ),
		        	'id'      => "{$prefix}full_location19",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location20', 'trav' ),
		        	'id'      => "{$prefix}full_location20",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location21', 'trav' ),
		        	'id'      => "{$prefix}full_location21",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location22', 'trav' ),
		        	'id'      => "{$prefix}full_location22",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location23', 'trav' ),
		        	'id'      => "{$prefix}full_location23",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location24', 'trav' ),
		        	'id'      => "{$prefix}full_location24",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location25', 'trav' ),
		        	'id'      => "{$prefix}full_location25",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
				array(
					'name'  => __( 'Neighborhood', 'trav' ),
					'id'      => "{$prefix}neighborhood",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Address', 'trav' ),
					'id'      => "{$prefix}address",
					'type'  => 'text',
				),
				array(
					'name'        => __( 'Location', 'trav' ),
					'id'            => "{$prefix}loc",
					'type'        => 'map',
					'style'      => 'width: 500px; height: 300px',
					'address_field' => "{$prefix}address",                   // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
				),
				array(
					'name'  => __( 'Phone No', 'trav' ),
					'id'      => "{$prefix}phone",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Email', 'trav' ),
					'id'      => "{$prefix}email",
					'type'  => 'text',
				),
				array(
					'name'        => __( 'Things to Do Detail', 'trav' ),
					'id'            => "{$prefix}ttd_detail",
					'type'        => 'textarea',
				),
				array(
					'name'  => __( 'Things To Do', 'trav' ),
					'id'      => "{$prefix}ttd",
					'desc'  => __( 'Please select a city first and then save accommodation. Then things to do list in the city will be shown.', 'trav' ),
					'type'  => 'post',
					'post_type' => 'things_to_do',
					'field_type' => 'select_advanced',
					'multiple' => true,
				),
				array(
					'name'  => __( 'Travel Guide', 'trav' ),
					'id'      => "{$prefix}tg",
					'type'  => 'post',
					'post_type' => 'travel_guide',
					'placeholder' => __( 'Select a Travel Guide', 'trav' ),
					'field_type' => 'select_advanced',
					'multiple' => false,
				),
			)
		);

		//accommodation_policies
		$meta_boxes[] = array(
			'id' => 'accommodation_policies',
			'title' => __( 'Policies', 'trav' ),
			'pages' => array( 'accommodation' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Check-in time', 'trav' ),
					'id'      => "{$prefix}check_in",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Check-out time', 'trav' ),
					'id'      => "{$prefix}check_out",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Cancellation / Prepayment info', 'trav' ),
					'id'      => "{$prefix}cancellation",
					'desc'  => __( 'Write cancellation policy here', 'trav' ),
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Security Deposit Amount(%)', 'trav' ),
					'id'      => "{$prefix}security_deposit",
					'desc'  => __( 'Leave it blank if security deposit is not needed. And can insert value 100 if you want customers to pay whole amount of money while booking.', 'trav' ),
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Tax Rate(%)', 'trav' ),
					'id'      => "{$prefix}tax_rate",
					'desc'  => __( 'This field is used to calculate tax when users book hotel room. Leave it blank if tax calculation is not needed.', 'trav' ),
					'type'  => 'text',
				),
			/*
				array(
					'name'  => __( 'Charge Children & Extra Beds', 'trav' ),
					'id'      => "{$prefix}charge_extra_people",
					'type'  => 'radio',
					'std'  => 'No Charge',
					'options' => array(
						'Charge' => __( 'Charge', 'trav' ),
						'No Charge' => __( 'No Charge', 'trav' ),
					),
				),*/
				array(
					'name'  => __( 'Charge Children & Extra Beds Detail Info', 'trav' ),
					'id'      => "{$prefix}extra_beds_detail",
					'type'  => 'textarea'
				),
				array(
					'name'  => __( 'Pets', 'trav' ),
					'id'      => "{$prefix}pets",
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Cards accepted at this accommodation', 'trav' ),
					'id'      => "{$prefix}cards",
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Other Policies', 'trav' ),
					'id'      => "{$prefix}other_policies",
					'type'  => 'textarea',
				)
			)
		);

		//accommodation_page_layout
		$meta_boxes[] = array(
			'id' => 'accommodation_page_layout',
			'title' => __( 'Page Layout', 'trav' ),
			'pages' => array( 'accommodation' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'name' => __( 'Main Top View', 'trav' ),
					'id'    => "{$prefix}main_top",
					'type' => 'checkbox_list',
					'std' => array('gallery', 'map', 'street', 'calendar'),
					'options' => array(
						'gallery' => __( 'Enable Gallery View', 'trav' ),
						'map' => __( 'Enable Map View', 'trav' ),
						'street' => __( 'Enable Street View', 'trav' ),
						'calendar' => __( 'Enable Calendar View', 'trav' ),
					),
				),
				array(
					'name'  => __( 'Calendar Description', 'trav' ),
					'id'      => "{$prefix}calendar_txt",
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Default Tab', 'trav' ),
					'id'      => "{$prefix}def_tab",
					'type'  => 'select',
					'description' => __( 'Select a default tab.', 'trav' ),
					'options' => array(
							'desc' => __( 'Description', 'trav' ),
							'rooms' => __( 'Availability', 'trav' ),
							'amenity' => __( 'Amenities', 'trav' ),
						),
					'std'  => 'desc',
				),
			)
		);

		//accommodation_settings
		$meta_boxes[] = array(
			'id' => 'accommodation_settings',
			'title' => __( 'Accommodation Settings', 'trav' ),
			'pages' => array( 'accommodation' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'name' => __( 'Feature This Accommodation', 'trav' ),
					'id'    => "{$prefix}featured",
					'desc' => __( 'Add this accommodation to featured list.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount This Accommodation', 'trav' ),
					'id' => "{$prefix}hot",
					'desc' => __( 'Add this accommodation to hot list.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount Rate', 'trav' ),
					'id' => "{$prefix}discount_rate",
					'desc' => __( '%', 'trav' ),
					'type' => 'number',
					'std' => 0,
				),
				array(
					'name' => __( 'Start Date', 'trav' ),
					'id' => "{$prefix}sdate",
					'desc' => __( 'Discount Start Date.', 'trav' ),
					'type' => 'date',
				),
				array(
					'name' => __( 'End Date', 'trav' ),
					'id' => "{$prefix}edate",
					'desc' => __( 'Discount End Date.', 'trav' ),
					'type' => 'date',
				),
				array(
					'name' => __( 'Disable Edit Booking', 'trav' ),
					'id'    => "{$prefix}d_edit_booking",
					'desc' => __( 'Disable edit booking for this accommodation on confirmation page.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Disable Cancel Booking', 'trav' ),
					'id' => "{$prefix}d_cancel_booking",
					'desc' => __( 'Disable cancel booking for this accommodation on confirmation page.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
			)
		);

		//accommodation_testimonials
		$meta_boxes[] = array(
			'id' => 'accommodation_testimonials',
			'title' => __( 'Testimonial Fields', 'trav' ),
			'pages' => array( 'accommodation' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'      => __( 'Testimonial Style', 'trav' ),
					'id'        => "{$prefix}tm_style",
					'type'      => 'radio',
					'std'       => 'style1',
					'options'   => array(
						'style1' => __( 'Style1', 'trav' ),
						'style2' => __( 'Style2', 'trav' ),
					),
				),
				array(
					'name'  => __( 'Title', 'trav' ),
					'id'      => "{$prefix}tm_title",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Author photo size(px)', 'trav' ),
					'id'      => "{$prefix}tm_author_photo_size",
					'desc'  => __( 'If you leave this field blank then the default size(74px) will work.' , 'trav' ),
					'type'  => 'number',
					'suffix' => __( ' px', 'trav' ),
				),
				array(
					'name'  => __( 'Custom Class', 'trav' ),
					'id'      => "{$prefix}tm_class",
					'desc'  => __( 'This field is for user customization. Leave this blank as default.' , 'trav' ),
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Testimonials', 'trav' ),
					'id'      => "{$prefix}tm_testimonial",
					'type'  => 'text_list',
					'clone' => true,
					'title' => 'Testimonial Detail',
					'options'   => array(
						'author_name'   => __( 'Author Name', 'trav' ),
						'author_link'   => __( 'Author Link', 'trav' ),
						'author_img_url'    => __( 'Author Photo URL', 'trav' ),
						'testimonial'   => __( 'Content', 'trav' )
					),
				)
			)
		);

		$meta_boxes = apply_filters( 'trav_register_acc_meta_boxes', $meta_boxes );

		return $meta_boxes;
	}
}

/*
 * tour metabox registration
 */
if ( ! function_exists( 'trav_register_tour_meta_boxes' ) ) {
	function trav_register_tour_meta_boxes() {
		$meta_boxes = array();

		$prefix = 'trav_tour_';
		//tour_details
		$meta_boxes[] = array(
			'id' => 'tour_details',
			'title' => __( 'Details', 'trav' ),
			'pages' => array( 'tour' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Type', 'trav' ),
					'id'      => "{$prefix}types",
					'desc'  => __( 'Select a tour type', 'trav' ),
					'placeholder'  => __( 'Select a tour type', 'trav' ),
					'type'  => 'taxonomy',
					'taxonomy' => 'tour_type',
					'field_type' => 'checkbox_list',
				),
				array(
					'name'  => __( 'Minimum Price Per Person', 'trav' ),
					'id'      => "{$prefix}min_price",
					'desc'  => __( 'This is minimum price per person. This value will be shown on tour list and will not be used for price calculation.', 'trav' ),
					'type'  => 'number',
				),
				array(
					'name'  => __( 'Tour Brief', 'trav' ),
					'id'      => "{$prefix}brief",
					'desc'  => __( 'This is tour brief field and the value is shown on search result page and detail page .', 'trav' ),
					'type'  => 'textarea',
				),
				array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location1', 'trav' ),
		        	'id'      => "{$prefix}tour_location1",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location2', 'trav' ),
		        	'id'      => "{$prefix}tour_location2",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location3', 'trav' ),
		        	'id'      => "{$prefix}tour_location3",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location4', 'trav' ),
		        	'id'      => "{$prefix}tour_location4",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location5', 'trav' ),
		        	'id'      => "{$prefix}tour_location5",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location6', 'trav' ),
		        	'id'      => "{$prefix}tour_location6",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location7', 'trav' ),
		        	'id'      => "{$prefix}tour_location7",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location8', 'trav' ),
		        	'id'      => "{$prefix}tour_location8",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location9', 'trav' ),
		        	'id'      => "{$prefix}tour_location9",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location10', 'trav' ),
		        	'id'      => "{$prefix}tour_location10",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        				array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location11', 'trav' ),
		        	'id'      => "{$prefix}tour_location11",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location12', 'trav' ),
		        	'id'      => "{$prefix}tour_location12",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location13', 'trav' ),
		        	'id'      => "{$prefix}tour_location13",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location14', 'trav' ),
		        	'id'      => "{$prefix}tour_location14",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location15', 'trav' ),
		        	'id'      => "{$prefix}tour_location15",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location16', 'trav' ),
		        	'id'      => "{$prefix}tour_location16",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location17', 'trav' ),
		        	'id'      => "{$prefix}tour_location17",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location18', 'trav' ),
		        	'id'      => "{$prefix}tour_location18",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location19', 'trav' ),
		        	'id'      => "{$prefix}tour_location19",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location20', 'trav' ),
		        	'id'      => "{$prefix}tour_location20",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        				array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location21', 'trav' ),
		        	'id'      => "{$prefix}tour_location21",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location22', 'trav' ),
		        	'id'      => "{$prefix}tour_location22",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location23', 'trav' ),
		        	'id'      => "{$prefix}tour_location23",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location24', 'trav' ),
		        	'id'      => "{$prefix}tour_location24",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        array(
					'type'  => 'taxonomy_advanced',
		        	'name'  => __( 'Location25', 'trav' ),
		        	'id'      => "{$prefix}tour_location25",
    				'taxonomy' => 'location',
    				'field_type' => 'select_tree',
        			'placeholder' => __( 'Select a Location', 'trav' )
		        ),
		        
				array(
					'name'  => __( 'Neighborhood', 'trav' ),
					'id'      => "{$prefix}neighborhood",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Address', 'trav' ),
					'id'      => "{$prefix}address",
					'type'  => 'text',
				),
				/*array(
					'name'        => __( 'Location', 'trav' ),
					'id'            => "{$prefix}loc",
					'type'        => 'map',
					'style'      => 'width: 500px; height: 300px',
					'address_field' => "{$prefix}address",                   // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
				),*/
				array(
					'name'  => __( 'Phone No', 'trav' ),
					'id'      => "{$prefix}phone",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Email', 'trav' ),
					'id'      => "{$prefix}email",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Travel Guide', 'trav' ),
					'id'      => "{$prefix}tg",
					'type'  => 'post',
					'post_type' => 'travel_guide',
					'placeholder' => __( 'Select a Travel Guide', 'trav' ),
					'field_type' => 'select_advanced',
					'multiple' => false,
				),
				array(
					'name'  => __( 'Security Deposit Amount(%)', 'trav' ),
					'id'      => "{$prefix}security_deposit",
					'desc'  => __( 'Leave it blank if security deposit is not needed. And can insert value 100 if you want customers to pay whole amount of money while booking.', 'trav' ),
					'type'  => 'number',
				),
				array(
					'name'  => __( 'Cancellation / Prepayment info', 'trav' ),
					'id'      => "{$prefix}cancellation",
					'desc'  => __( 'Write cancellation policy here', 'trav' ),
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Schedule Types', 'trav' ),
					'id'      => "{$prefix}schedule_types",
					'type'  => 'text_list',
					'clone' => true,
					'title' => 'Schedule Type Detail',
					'options'   => array(
						'title'   => __( 'Title', 'trav' ),
						'description'   => __( 'Description', 'trav' ),
						'time'   => __( 'Time', 'trav' ),
					),
				),
			)
		);

		//tour settings
		$meta_boxes[] = array(
			'id' => 'tour_settings',
			'title' => __( 'Tour Settings', 'trav' ),
			'pages' => array( 'tour' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'name' => __( 'Tour Repeatability', 'trav' ),
					'id'    => "{$prefix}repeated",
					'desc' => __( 'If you set Repeated, tour schedules in this tour will have date selection field.', 'trav' ),
					'type'  => 'radio',
					'std'  => '0',
					'options' => array(
						'1' => __( 'Repeated', 'trav' ),
						'0' => __( 'Not Repeated', 'trav' ),
					),
				),
				array(
					'name' => __( 'Person Number Selectability', 'trav' ),
					'id'    => "{$prefix}multi_book",
					'type'  => 'radio',
					'std'  => '0',
					'options' => array(
						'1' => __( 'Selectable', 'trav' ),
						'0' => __( 'Not Selectable', 'trav' ),
					),
				),
				array(
					'name' => __( 'Feature This Tour', 'trav' ),
					'id'    => "{$prefix}featured",
					'desc' => __( 'Add this tour to featured list.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount This Tour', 'trav' ),
					'id' => "{$prefix}hot",
					'desc' => __( 'Discount this tour.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount Rate', 'trav' ),
					'id' => "{$prefix}discount_rate",
					'desc' => __( '%', 'trav' ),
					'type' => 'number',
					'std' => 0,
				),
				array(
					'name' => __( 'Disable Cancel Booking', 'trav' ),
					'id' => "{$prefix}d_cancel_booking",
					'desc' => __( 'Disable cancel booking for this tour on confirmation page.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Put schedule list section first.', 'trav' ),
					'id' => "{$prefix}sl_first",
					'desc' => __( 'Put schedule list section before main content section.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
			)
		);

		$meta_boxes = apply_filters( 'trav_register_tour_meta_boxes', $meta_boxes );

		return $meta_boxes;
	}
}

/*
 * travel guide metabox registration
 */
if ( ! function_exists( 'trav_register_tg_meta_boxes' ) ) {
	function trav_register_tg_meta_boxes() {
		$meta_boxes = array();
		$prefix = 'trav_tg_';
		$meta_boxes[] = array(
			'id'          => 'trav_tg_detail',
			'title'       => __( 'Travel Guide Detail', 'trav' ),
			'description' => __( 'Enter Travel Guide Detail.', 'trav' ),
			'pages'        => array('travel_guide'),
			'context'     => 'normal',
			'priority'    => 'high',
			'fields'      => array(
				array(
					'name' => __( 'General Information', 'trav' ),
					'id'   => "{$prefix}g_info",
					'type' => 'wysiwyg',
					'raw'  => true,
					'std'  => '',
					'options' => array(
					),
				),
				array(
					'name' => __( 'Sports', 'trav' ),
					'id'   => "{$prefix}sports",
					'type' => 'wysiwyg',
					'raw'  => true,
					'std'  => '',
					'options' => array(
					),
				),
				array(
					'name' => __( 'Culture & History', 'trav' ),
					'id'   => "{$prefix}culture",
					'type' => 'wysiwyg',
					'raw'  => true,
					'std'  => '',
					'options' => array(
					),
				),
				array(
					'name' => __( 'Night Life', 'trav' ),
					'id'   => "{$prefix}nightlife",
					'type' => 'wysiwyg',
					'raw'  => true,
					'std'  => '',
					'options' => array(
					),
				),
			)
		);
		$meta_boxes = apply_filters( 'trav_register_tg_meta_boxes', $meta_boxes );
		return $meta_boxes;
	}
}

/*
 * car metabox registration
 */
if ( ! function_exists( 'trav_register_car_meta_boxes' ) ) {
	function trav_register_car_meta_boxes() {
		$meta_boxes = array();

		$prefix = 'trav_car_';

		//car_page_layout
		$meta_boxes[] = array(
			'id' => 'car_page_layout',
			'title' => __( 'Page Layout', 'trav' ),
			'pages' => array( 'car' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'name' => __( 'Main Top View', 'trav' ),
					'id'    => "{$prefix}main_top",
					'type' => 'checkbox_list',
					'std' => array('gallery', 'calendar'),
					'options' => array(
						'gallery' => __( 'Enable Gallery View', 'trav' ),
						'calendar' => __( 'Enable Calendar View', 'trav' ),
					),
				),
				array(
					'name'  => __( 'Calendar Description', 'trav' ),
					'id'      => "{$prefix}calendar_txt",
					'type'  => 'textarea',
				),
			),
		);

		//car_settings
		$meta_boxes[] = array(
			'id' => 'car_settings',
			'title' => __( 'Car Settings', 'trav' ),
			'pages' => array( 'car' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'name' => __( 'Feature This Car', 'trav' ),
					'id'    => "{$prefix}featured",
					'desc' => __( 'Add this car to featured list.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount This Car', 'trav' ),
					'id' => "{$prefix}hot",
					'desc' => __( 'Add this car to hot list.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount Rate', 'trav' ),
					'id' => "{$prefix}discount_rate",
					'desc' => __( '%', 'trav' ),
					'type' => 'number',
					'std' => 0,
				),
				array(
					'name' => __( 'Start Date', 'trav' ),
					'id' => "{$prefix}sdate",
					'desc' => __( 'Discount Start Date.', 'trav' ),
					'type' => 'date',
				),
				array(
					'name' => __( 'End Date', 'trav' ),
					'id' => "{$prefix}edate",
					'desc' => __( 'Discount End Date.', 'trav' ),
					'type' => 'date',
				),
				array(
					'name' => __( 'Disable Edit Booking', 'trav' ),
					'id'    => "{$prefix}d_edit_booking",
					'desc' => __( 'Disable edit booking for this car on confirmation page.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Disable Cancel Booking', 'trav' ),
					'id' => "{$prefix}d_cancel_booking",
					'desc' => __( 'Disable cancel booking for this car on confirmation page.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
			)
		);

		//car_details
		$meta_boxes[] = array(
			'id' => 'car_details',
			'title' => __( 'Details', 'trav' ),
			'pages' => array( 'car' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Car Type', 'trav' ),
					'id'      => "{$prefix}types",
					'desc'  => __( 'Select a car type', 'trav' ),
					'placeholder'  => __( 'Select a car type', 'trav' ),
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'car_type',
						'type' => 'select_advanced',
					),
				),
				array(
					'name'  => __( 'Car Rental Agent', 'trav' ),
					'id'      => "{$prefix}agent",
					'desc'  => __( 'Select a car rental agent', 'trav' ),
					'placeholder'  => __( 'Select a car rental agent', 'trav' ),
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'car_agent',
						'type' => 'select_advanced',
					),
				),
				array(
					'name'  => __( 'Car Preferences', 'trav' ),
					'id'      => "{$prefix}preferences",
					'desc'  => __( 'Select car preferences', 'trav' ),
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'preference',
						'type' => 'checkbox_list',
					),
				),
				array(
					'name'	=> __( 'Gallery Images', 'trav' ),
					'id'    => "trav_gallery_imgs",
					'type'  => 'image_advanced',
					'max_file_uploads' => 50,
				),
				array(
					'name'   => __( 'Car Logo', 'trav' ),
					'id'    => "{$prefix}logo",
					'type'   => 'image_advanced',
					'max_file_uploads' => 1,
				),
				array(
					'name'  => __( 'Car Brief', 'trav' ),
					'id'      => "{$prefix}brief",
					'desc'  => __( 'This is car brief field and the value is shown on search result page and detail page .', 'trav' ),
					'type'  => 'textarea',
				),
				array(
					'name'   => __( 'Passenger', 'trav' ),
					'id'    => "{$prefix}passenger",
					'type'   => 'text',
					'type' => 'number',
					'std' => 1
				),
				array(
					'name'   => __( 'Baggage', 'trav' ),
					'id'    => "{$prefix}baggage",
					'type' => 'number',
					'std' => 0
				),
				array(
					'name'   => __( 'Tax Rate(%)', 'trav' ),
					'id'    => "{$prefix}tax_rate",
					'desc'  => __( 'This field is used to calculate tax. Leave it blank if tax calculation is not needed.', 'trav' ),
					'type' => 'text',
				),
				array(
					'name'  => __( 'Location', 'trav' ),
					'id'      => "{$prefix}location",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Pick-up Time', 'trav' ),
					'id'      => "{$prefix}pick_up_time",
					'type' => 'select',
					'options' => array(
							'anytime' => __( 'Anytime', 'trav' ),
							'morning' => __( 'Morning', 'trav' ),
							'afternoon' => __( 'Afternoon', 'trav' )
						),
					'std'  => 'anytime',
				),
				array(
					'name'  => __( 'Pick-up Phone', 'trav' ),
					'id'      => "{$prefix}pick_up_phone",
					'type'  => 'text',
				),
				/*array(
					'name'  => __( 'Pick-up Location', 'trav' ),
					'id'      => "{$prefix}pick_up_location",
					'type'  => 'text',
				),*/
				array(
					'name'  => __( 'Drop-off Phone', 'trav' ),
					'id'      => "{$prefix}drop_off_phone",
					'type'  => 'text',
				),
				/*array(
					'name'  => __( 'Drop-off Location', 'trav' ),
					'id'      => "{$prefix}drop_off_location",
					'type'  => 'text',
				),*/
				array(
					'name'  => __( 'Drop-off Time', 'trav' ),
					'id'      => "{$prefix}drop_off_time",
					'type' => 'select',
					'options' => array(
							'anytime' => __( 'Anytime', 'trav' ),
							'morning' => __( 'Morning', 'trav' ),
							'afternoon' => __( 'Afternoon', 'trav' )
						),
					'std'  => 'anytime',
				),
				array(
					'name'  => __( 'Price Per Day', 'trav' ),
					'id'      => "{$prefix}price",
					'desc'  => __( 'This is car price per day.', 'trav' ),
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Mileage', 'trav' ),
					'id'      => "{$prefix}mileage",
					'desc'  => __( 'Leave it blank if unlimited mileage.', 'trav' ),
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Number of Available Cars', 'trav' ),
					'id'      => "{$prefix}max_cars",
					'desc'  => __( 'Please set number of available cars. You can leave this field empty to make it no limit.', 'trav' ),
					'type'  => 'number',
					'std'	=> 0,
				),
				array(
					'name'  => __( 'Security Deposit Amount(%)', 'trav' ),
					'id'      => "{$prefix}security_deposit",
					'desc'  => __( 'Leave it blank if security deposit is not needed. And can insert value 100 if you want customers to pay whole amount of money while booking.', 'trav' ),
					'type'  => 'text',
				),
			)
		);

		$meta_boxes = apply_filters( 'trav_register_car_meta_boxes', $meta_boxes );

		return $meta_boxes;
	}
}

/*
 * cruise metabox registration
 */
if ( ! function_exists( 'trav_register_cruise_meta_boxes' ) ) {
	function trav_register_cruise_meta_boxes() {
		$meta_boxes = array();

		//cabin meta boxes
		$prefix = 'trav_cabin_';
		$meta_boxes[] = array(
			'id' => 'cabin_details',
			'title' => __( 'Cabin Details', 'trav' ),
			'pages' => array( 'cabin_type' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Cruise', 'trav' ),
					'id'      => "{$prefix}cruise",
					'type'  => 'post',
					'std' => isset($_GET['cruise_id']) ? sanitize_text_field( $_GET['cruise_id'] ) : '',
					'post_type' => 'cruise',
				),
				array(
					'name'  => __( 'Max Adults', 'trav' ),
					'id'    => "{$prefix}max_adults",
					'desc'  => __( 'How many adults are allowed in the cabin?', 'trav' ),
					'type' => 'number',
					'std' => 1
				),
				array(
					'name'  => __( 'Max Children', 'trav' ),
					'id'    => "{$prefix}max_kids",
					'desc'  => __( 'How many children are allowed in the cabin?', 'trav' ),
					'type' => 'number',
					'std' => 0
				),
				array(
					'name'  => __( 'Cabin Amenities', 'trav' ),
					'id'      => "{$prefix}amenities",
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'amenity',
						'type' => 'checkbox_list',
					),
				),
				array(
					'name'	=> __( 'Deck', 'trav' ),
					'id'	=> "{$prefix}deck",
					'type'  => 'text',					
				),
				array(
					'name'	=> __( 'Size', 'trav' ),
					'id'	=> "{$prefix}size",
					'type'  => 'text',					
				),
				/*array(
					'name'	=> __( 'Balcony', 'trav' ),
					'id'	=> "{$prefix}balcony",
					'type'  => 'text',					
				),*/					
			)
		);
		
		//food_dinning metaboxes
		$prefix = 'trav_fd_';
		$meta_boxes[] = array(
			'id' => 'fd_details',
			'title' => __( 'Food & Dinning Details', 'trav' ),
			'pages' => array( 'food_dinning' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
							array(
								'name'  => __( 'Seating Times', 'trav' ),
								'id'      => "{$prefix}time",
								'type'  => 'text',
							)
						)
		);

		//cruise metaboxes
		$prefix = 'trav_cruise_';
		//cruise_details
		$meta_boxes[] = array(
			'id' => 'cruise_details',
			'title' => __( 'Details', 'trav' ),
			'pages' => array( 'cruise' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Ship Name', 'trav' ),
					'id'      => "{$prefix}ship_name",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Cruise Type', 'trav' ),
					'id'      => "{$prefix}cruise_types",
					'desc'  => __( 'Select a cruise type', 'trav' ),
					'placeholder'  => __( 'Select a cruise type', 'trav' ),
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'cruise_type',
						'type' => 'select_advanced',
					),
				),
				array(
					'name'  => __( 'Cruise Line', 'trav' ),
					'id'      => "{$prefix}cruise_lines",
					'desc'  => __( 'Select a cruise line', 'trav' ),
					'placeholder'  => __( 'Select a cruise line', 'trav' ),
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'cruise_line',
						'type' => 'select_advanced',
					),
				),
				array(
					'name'  => __( 'Amenities', 'trav' ),
					'id'      => "{$prefix}amenities",
					'desc'  => __( 'Select amenities', 'trav' ),
					'type'  => 'taxonomy',
					'options' => array(
						'taxonomy' => 'amenity',
						'type' => 'checkbox_list',
					),
				),
				array(
					'name'  => __( 'Other Amenity Info', 'trav' ),
					'id'      => "{$prefix}other_amenity_info",
					'desc'  => __( 'This is the content that will be shown on Amenity tab of Detail Page', 'trav' ),
					'type'  => 'textarea',
				),
				array(
					'name'           => __( 'Gallery Images', 'trav' ),
					'id'                => "trav_gallery_imgs",
					'type'           => 'image_advanced',
					'max_file_uploads' => 50,
				),
				/*array(
					'name' => __( 'FAQ to This Cruise', 'trav' ),
					'id'   => "{$prefix}faq",
					'type' => 'wysiwyg',
					'raw'  => true,
					'std'  => __( 'Please write FAQ here', 'trav' ),
					'options' => array(
						//'textarea_rows' => 4,
						// 'teeny'         => true,
						//'media_buttons' => false,
					),
				),*/
				array(
					'name'   => __( 'Cruise Logo', 'trav' ),
					'id'    => "{$prefix}logo",
					'type'   => 'image_advanced',
					'max_file_uploads' => 1,
				),
				array(
					'name'  => __( 'AVG/NIGHT Price', 'trav' ),
					'id'      => "{$prefix}avg_price",
					'desc'  => __( 'This is average price per night field.', 'trav' ),
					'type'  => 'number',
				),
				array(
					'name'  => __( 'Cruise Brief', 'trav' ),
					'id'      => "{$prefix}brief",
					'desc'  => __( 'This is cruise brief field and the value is shown on search result page and detail page .', 'trav' ),
					'type'  => 'textarea',
				),
				/*array(
					'name'  => __( 'Cruise Duration', 'trav' ),
					'id'      => "{$prefix}duration",
					'type'  => 'number',
				),
				array(
					'name'  => __( 'Cruise Itinerary', 'trav' ),
					'id'      => "{$prefix}itinerary",
					'type'  => 'text_list',
					'clone' => true,
					'title' => 'Schedule Type Detail',
					'options'   => array(
						'day'   		=> __( 'Day', 'trav' ),
						'ports of call'	=> __( 'Ports of Call', 'trav' ),
						'arrival'   	=> __( 'Arrival', 'trav' ),
						'departure'   	=> __( 'Departure', 'trav' ),
					),
				),*/
				array(
					'name'  => __( 'Travel Guide', 'trav' ),
					'id'      => "{$prefix}tg",
					'type'  => 'post',
					'post_type' => 'travel_guide',
					'placeholder' => __( 'Select a Travel Guide', 'trav' ),
					'field_type' => 'select_advanced',
					'multiple' => false,
				),
			)
		);

		//cruise_location
		$meta_boxes[] = array(
			'id' => 'cruise_location',
			'title' => __( 'Location & Other Info', 'trav' ),
			'pages' => array( 'cruise' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				/*array(
					'name'  => __( 'Country', 'trav' ),
					'id'      => "{$prefix}country",
					'desc'  => __( 'Select a Country', 'trav' ),
					'type'  => 'taxonomy_advanced',
					'placeholder' => __( 'Select a Country', 'trav' ),
					'options' => array(
						'taxonomy' => 'location',
						'args' => array(
								'parent' => '0'
							),
						'type' => 'select_advanced'
					),
				),
				array(
					'name'  => __( 'City', 'trav' ),
					'id'      => "{$prefix}city",
					'desc'  => __( 'Select a City', 'trav' ),
					'type'  => 'taxonomy_advanced',
					'placeholder' => __( 'Select a City', 'trav' ),
					'options' => array(
						'taxonomy' => 'location',
						'args' => array(
							),
						'depth' => 2,
						'type' => 'select_advanced',
					),
				),
				array(
					'name'  => __( 'Neighborhood', 'trav' ),
					'id'      => "{$prefix}neighborhood",
					'type'  => 'text',
				),*/
				array(
					'name'  => __( 'Phone No', 'trav' ),
					'id'      => "{$prefix}phone",
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Email', 'trav' ),
					'id'      => "{$prefix}email",
					'type'  => 'text',
				),
				array(
					'name'        => __( 'Food & Dinning details', 'trav' ),
					'id'            => "{$prefix}fd_detail",
					'type'        => 'textarea',
				),
				array(
					'name'  => __( 'Food & Dinning', 'trav' ),
					'id'      => "{$prefix}fd",
					'type'  => 'post',
					'post_type' => 'food_dinning',
					'field_type' => 'select_advanced',
					'multiple' => true,
				),
			)
		);

		//cruise_policies
		$meta_boxes[] = array(
			'id' => 'cruise_policies',
			'title' => __( 'Policies', 'trav' ),
			'pages' => array( 'cruise' ),
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				array(
					'name'  => __( 'Cancellation / Prepayment info', 'trav' ),
					'id'      => "{$prefix}cancellation",
					'desc'  => __( 'Write cancellation policy here', 'trav' ),
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Security Deposit Amount(%)', 'trav' ),
					'id'      => "{$prefix}security_deposit",
					'desc'  => __( 'Leave it blank if security deposit is not needed. And can insert value 100 if you want customers to pay whole amount of money while booking.', 'trav' ),
					'type'  => 'text',
				),
				array(
					'name'  => __( 'Tax Rate(%)', 'trav' ),
					'id'      => "{$prefix}tax_rate",
					'desc'  => __( 'This field is used to calculate tax when users book hotel room. Leave it blank if tax calculation is not needed.', 'trav' ),
					'type'  => 'text',
				),			
				array(
					'name'  => __( 'Charge Children & Extra Beds Detail Info', 'trav' ),
					'id'      => "{$prefix}extra_beds_detail",
					'type'  => 'textarea'
				),
				array(
					'name'  => __( 'Pets', 'trav' ),
					'id'      => "{$prefix}pets",
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Cards accepted at this cruise', 'trav' ),
					'id'      => "{$prefix}cards",
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Other Policies', 'trav' ),
					'id'      => "{$prefix}other_policies",
					'type'  => 'textarea',
				)
			)
		);

		//cruise_page_layout
		$meta_boxes[] = array(
			'id' => 'cruise_page_layout',
			'title' => __( 'Page Layout', 'trav' ),
			'pages' => array( 'cruise' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'name' => __( 'Main Top View', 'trav' ),
					'id'    => "{$prefix}main_top",
					'type' => 'checkbox_list',
					'std' => array('gallery', 'calendar'),
					'options' => array(
						'gallery' => __( 'Enable Gallery View', 'trav' ),
						'calendar' => __( 'Enable Calendar View', 'trav' ),
					),
				),
				array(
					'name'  => __( 'Calendar Description', 'trav' ),
					'id'      => "{$prefix}calendar_txt",
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Default Tab', 'trav' ),
					'id'      => "{$prefix}def_tab",
					'type'  => 'select',
					'description' => __( 'Select a default tab.', 'trav' ),
					'options' => array(
							'desc' => __( 'Description', 'trav' ),
							'cabins' => __( 'Availability', 'trav' ),
							'amenity' => __( 'Amenities', 'trav' ),
						),
					'std'  => 'desc',
				),
			)
		);

		//cruise_settings
		$meta_boxes[] = array(
			'id' => 'cruise_settings',
			'title' => __( 'Cruise Settings', 'trav' ),
			'pages' => array( 'cruise' ),
			'context' => 'side',
			'priority' => 'default',
			'fields' => array(
				array(
					'name' => __( 'Feature This Cruise', 'trav' ),
					'id'    => "{$prefix}featured",
					'desc' => __( 'Add this cruise to featured list.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount This Cruise', 'trav' ),
					'id' => "{$prefix}hot",
					'desc' => __( 'Add this cruise to hot list.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Discount Rate', 'trav' ),
					'id' => "{$prefix}discount_rate",
					'desc' => __( '%', 'trav' ),
					'type' => 'number',
					'std' => 0,
				),
				array(
					'name' => __( 'Start Date', 'trav' ),
					'id' => "{$prefix}sdate",
					'desc' => __( 'Discount Start Date.', 'trav' ),
					'type' => 'date',
				),
				array(
					'name' => __( 'End Date', 'trav' ),
					'id' => "{$prefix}edate",
					'desc' => __( 'Discount End Date.', 'trav' ),
					'type' => 'date',
				),
				array(
					'name' => __( 'Disable Edit Booking', 'trav' ),
					'id'    => "{$prefix}d_edit_booking",
					'desc' => __( 'Disable edit booking for this cruise on confirmation page.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
				array(
					'name' => __( 'Disable Cancel Booking', 'trav' ),
					'id' => "{$prefix}d_cancel_booking",
					'desc' => __( 'Disable cancel booking for this cruise on confirmation page.', 'trav' ),
					'type' => 'checkbox',
					'std' => array(),
				),
			)
		);

		$meta_boxes = apply_filters( 'trav_register_cruise_meta_boxes', $meta_boxes );

		return $meta_boxes;
	}
}

/*
 * post/page metabox registration
 */
if ( ! function_exists( 'trav_register_post_meta_boxes' ) ) {
	function trav_register_post_meta_boxes() {
		$meta_boxes = array();

		// default page metabox
		$prefix = 'trav_page_';
		$fields = array();
		$fields[] = array(
					'name'           => __( 'Background Slider Images', 'trav' ),
					'id'                => "trav_gallery_imgs",
					'type'           => 'image_advanced',
					'max_file_uploads' => 50,
				);
		$fields[] = array(
					'name'  => __( 'Content On Background Slider', 'trav' ),
					'id'      => "{$prefix}bg_content",
					'desc'  => __( 'Enter HTML code here.', 'trav' ),
					'type'  => 'wysiwyg',
				);
		if ( class_exists( 'RevSlider' ) ) {
			$fields[] = array(
				'name' => __( 'Revolution Slider', 'trav' ),
				'desc' => __( 'To activate your slider, select an option from the dropdown. To deactivate your slider, set the dropdown back to "Deactivated."', 'trav' ),
				'id'   => "{$prefix}slider",
				'type' => 'rev_slider',
				'std'  => 'Deactivated',
				'placeholder' => 'Deactivated'
				);
		}
		$fields[] = array(
					'name'  => __( 'Custom CSS', 'trav' ),
					'id'      => "{$prefix}custom_css",
					'desc'  => __( 'Enter custom css code here.', 'trav' ),
					'type'  => 'textarea',
				);
		$fields[] = array(
					'name'  => __( 'Enable/Disable Inner Header', 'trav' ),
					'id'      => "{$prefix}inner",
					'type'  => 'radio',
					'std'  => 'enable',
					'desc'  => __( 'Enable/Disable Inner Header with title and breadcrumb.', 'trav' ),
					'options' => array(
						'enable' => __( 'Enable', 'trav' ),
						'disable' => __( 'Disable', 'trav' ),
					),
				);
		$meta_boxes[] = array(
			'id'          => 'trav_page',
			'title'       => __( 'Page Setting', 'trav' ),
			'description' => __( 'Select your options to display a slider above the masthead.', 'trav' ),
			'pages'        => array('page'),
			'context'     => 'normal',
			'priority'    => 'high',
			'fields'      => $fields
		);

		// default post metabox
		$prefix = 'trav_post_';
		$meta_boxes[] = array(
			'id'          => 'trav_post_media_setting',
			'title'       => __( 'Image/Gallery/Video Setting', 'trav' ),
			'description' => __( 'Select your Image/Gallery/Video options.', 'trav' ),
			'pages'        => array( 'post', 'things_to_do', 'room_type', 'tour', 'cabin_type', 'food_dinning' ),
			'context'     => 'normal',
			'priority'    => 'high',
			'fields'      => array(
				array(
					'name' => __( 'Media Type', 'trav' ),
					'desc' => __( 'Please select one media type from the dropdown to show Featured Image/Gallery/Video.', 'trav' ),
					'id'   => "{$prefix}media_type",
					'type' => 'select',
					'options' => array(
							'img' => __( 'Featured Image', 'trav' ),
							'sld' => __( 'Gallery', 'trav' ),
							'video' => __( 'Video', 'trav' ),
							'no' => __( 'No Media', 'trav' ),
						),
					'std'  => 'img',
				),
				array(
					'name'           => __( 'Gallery Images', 'trav' ),
					'id'                => "trav_gallery_imgs",
					'type'           => 'image_advanced',
					'max_file_uploads' => 50,
				),
				array(
					'name' => __( 'Gallery Type', 'trav' ),
					'desc' => __( 'Please select Media Type to Gallery to make this worked.', 'trav' ),
					'id'   => "{$prefix}gallery_type",
					'type' => 'select',
					'options' => array(
							'sld_1' => __( 'Gallery Style 1', 'trav' ),
							'sld_2' => __( 'Gallery Style 2', 'trav' ),
							'sld_with_cl' => __( 'Gallery with Carousel', 'trav' ),
						),
					'std'  => 'sld_1',
				),
				array(
					'name'  => __( 'Direction Navigation', 'trav' ),
					'desc'  => __( 'Enable Direction Navigation', 'trav' ),
					'id'      => "{$prefix}direction_nav",
					'type'  => 'checkbox',
					'std'  => 1
				),
				array(
					'name'  => __( 'Video Embed Code', 'trav' ),
					'id'      => "{$prefix}video",
					'type'  => 'textarea',
				),
				array(
					'name'  => __( 'Video Width', 'trav' ),
					'desc'  => __( 'Enable Video Full Width', 'trav' ),
					'id'      => "{$prefix}video_width",
					'type'  => 'checkbox',
				),
			)
		);
		$meta_boxes = apply_filters( 'trav_register_post_meta_boxes', $meta_boxes );
		return $meta_boxes;
	}
}

/*
 * rwmb metabox registration
 */
if ( ! function_exists( 'trav_register_meta_boxes' ) ) {
	function trav_register_meta_boxes( $meta_boxes ) {
		global $trav_options;

		//room_type custom post type
		if ( empty( $trav_options['disable_acc'] ) ) :
			$acc_meta_boxes = trav_register_acc_meta_boxes();
			$meta_boxes = array_merge( $meta_boxes, $acc_meta_boxes );
		endif;

		//tour custom post type
		if ( empty( $trav_options['disable_tour'] ) ) :
			$tour_meta_boxes = trav_register_tour_meta_boxes();
			$meta_boxes = array_merge( $meta_boxes, $tour_meta_boxes );
		endif;

		//car custom post type
		if ( empty( $trav_options['disable_car'] ) ) :
			$car_meta_boxes = trav_register_car_meta_boxes();
			$meta_boxes = array_merge( $meta_boxes, $car_meta_boxes );
		endif;

		//cruise custom post type
		if ( empty( $trav_options['disable_cruise'] ) ) :
			$cruise_meta_boxes = trav_register_cruise_meta_boxes();
			$meta_boxes = array_merge( $meta_boxes, $cruise_meta_boxes );
		endif;

		$post_meta_boxes = trav_register_post_meta_boxes();
		$meta_boxes = array_merge( $meta_boxes, $post_meta_boxes );

		// Travel Guide metabox
		$tg_meta_boxes = trav_register_tg_meta_boxes();
		$meta_boxes = array_merge( $meta_boxes, $tg_meta_boxes );

		$meta_boxes = apply_filters( 'trav_register_meta_boxes', $meta_boxes );

		return $meta_boxes;
	}
}

/*
 * Register room types meta box on accommodation page
 */
if ( ! function_exists( 'trav_accommodation_rooms_meta_box' ) ) {
	function trav_accommodation_rooms_meta_box( $post )
	{
		add_meta_box( 
			'trav_accommodation_rooms_meta_box', // this is HTML id
			'Room Types in This Accommodation', 
			'trav_accommodation_rooms_meta_box_html', // the callback function
			'accommodation', // register on post type = page
			'side', // 
			'default'
		);
	}
}

/*
 * room types meta box HTML on accommodation page
 */
if ( ! function_exists( 'trav_accommodation_rooms_meta_box_html' ) ) {
	function trav_accommodation_rooms_meta_box_html( $post )
	{
		if ( isset( $_GET['post'] ) ) {
			$acc_id = $_GET['post'];
			$args = array(
				'post_type' => 'room_type',
				'meta_query' => array(
					array(
						'key' => 'trav_room_accommodation',
						'value' => array( sanitize_text_field( $_GET['post'] ) ),
					)
				),
				'suppress_filters' => 0,
			);
			$room_types = get_posts( $args );
			if ( ! empty( $room_types ) ) {
				echo '<ul>';
				foreach ($room_types as $room_type) {
					echo '<li>' . esc_html( get_the_title($room_type->ID) ) . '  <a href="' . esc_url( get_edit_post_link($room_type->ID) ) . '">edit</a></li>';
				}
				echo '</ul>';
			} else {
				echo 'No Room Types in This Accommodation. <br />';
			}
			echo '<a href="' . esc_url( admin_url('post-new.php?post_type=room_type&acc_id=' . $acc_id) ) . '">Add New Room Type</a>';
			//wp_reset_postdata();
		} else { //in case of new
			echo 'No Room Types in This Accommodation. <br />';
			echo '<a href="' . esc_url( admin_url('post-new.php?post_type=room_type') ) . '">Add New Room Type</a>';
		}
	}
}

/*
 * Register cabin types meta box on cruise page
 */
if ( ! function_exists( 'trav_cruise_cabins_meta_box' ) ) {
	function trav_cruise_cabins_meta_box( $post )
	{
		add_meta_box( 
			'trav_cruise_cabins_meta_box', // this is HTML id
			'Cabin Types in This Cruise', 
			'trav_cruise_cabins_meta_box_html', // the callback function
			'cruise', // register on post type = page
			'side', // 
			'default'
		);
	}
}

/*
 * cabin types meta box HTML on cruise page
 */
if ( ! function_exists( 'trav_cruise_cabins_meta_box_html' ) ) {
	function trav_cruise_cabins_meta_box_html( $post )
	{
		if ( isset( $_GET['post'] ) ) {
			$cruise_id = $_GET['post'];
			$args = array(
				'post_type' => 'cabin_type',
				'meta_query' => array(
					array(
						'key' => 'trav_cabin_cruise',
						'value' => array( sanitize_text_field( $_GET['post'] ) ),
					)
				),
				'suppress_filters' => 0,
			);
			$cabin_types = get_posts( $args );
			if ( ! empty( $cabin_types ) ) {
				echo '<ul>';
				foreach ($cabin_types as $cabin_type) {
					echo '<li>' . esc_html( get_the_title($cabin_type->ID) ) . '  <a href="' . esc_url( get_edit_post_link($cabin_type->ID) ) . '">edit</a></li>';
				}
				echo '</ul>';
			} else {
				echo 'No Cabin Types in This Cruise. <br />';
			}
			echo '<a href="' . esc_url( admin_url('post-new.php?post_type=cabin_type&cruise_id=' . $cruise_id) ) . '">Add New cabin Type</a>';
			//wp_reset_postdata();
		} else { //in case of new
			echo 'No Cabin Types in This Cruise. <br />';
			echo '<a href="' . esc_url( admin_url('post-new.php?post_type=cabin_type') ) . '">Add New cabin Type</a>';
		}
	}
}

function trav_bgslider_script_enqueue() {
	global $current_screen;
	if ( ! empty( $current_screen ) && 'page' != $current_screen->id ) {
		return; 
	}
	?>

	<script type="text/javascript">
		jQuery(document).ready( function($) {

			if($('#page_template').val() == 'templates/template-bg-slider.php') {
				// show the meta box
				$('.rwmb-image_advanced-wrapper').show();
				$('#trav_page_bg_content').closest('.rwmb-field').show();
				$('#trav_page .rwmb-rev_slider-wrapper').hide();
			} else {
				// hide your meta box
				$('.rwmb-image_advanced-wrapper').hide();
				$('#trav_page_bg_content').closest('.rwmb-field').hide();
				$('#trav_page .rwmb-rev_slider-wrapper').show();
			}

			// Debug only
			// - outputs the template filename
			// - checking for console existance to avoid js errors in non-compliant browsers
			if (typeof console == "object") 
				console.log ('default value = ' + $('#page_template').val());

			/**
			 * Live adjustment of the meta box visibility
			*/
			$('#page_template').live('change', function(){
					if($(this).val() == 'templates/template-bg-slider.php') {
					// show the meta box
					$('.rwmb-image_advanced-wrapper').show();
					$('#trav_page_bg_content').closest('.rwmb-field').show();
					$('#trav_page .rwmb-rev_slider-wrapper').hide();
				} else {
					// hide your meta box
					$('.rwmb-image_advanced-wrapper').hide();
					$('#trav_page_bg_content').closest('.rwmb-field').hide();
					$('#trav_page .rwmb-rev_slider-wrapper').show();
				}
			});
		});
	</script>
<?php
}

add_filter( 'rwmb_meta_boxes', 'trav_register_meta_boxes' );
add_action( "add_meta_boxes", "trav_accommodation_rooms_meta_box" );
add_action( "add_meta_boxes", "trav_cruise_cabins_meta_box" );
add_action('admin_head', 'trav_bgslider_script_enqueue');

class levelup_metaboxes{
	private static $meta_boxes = null;
	public function __construct(){
		add_action( 'admin_init', array($this, 'register_meta_boxes')  );
	}
	public static function get_meta_boxes()
	{
		if ( null === self::$meta_boxes )
		{
			self::$meta_boxes = apply_filters( 'rwmb_meta_boxes', array() );
			self::$meta_boxes = empty( self::$meta_boxes ) || ! is_array( self::$meta_boxes ) ? array() : self::$meta_boxes;
		}
		return self::$meta_boxes;
	}
	public function register_meta_boxes()
		{
			$meta_boxes = self::get_meta_boxes();
			foreach ( $meta_boxes as $meta_box )
			{
				new RW_Meta_Box( $meta_box );
			}
		}
}
$levelup_metaboxes = new levelup_metaboxes();
?>