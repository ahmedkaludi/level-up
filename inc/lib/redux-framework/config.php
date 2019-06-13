<?php

if ( ! class_exists( 'Redux' ) ) {
    return;
}

$options_pages = array();
$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
$options_pages[''] = 'Select a page:';
foreach ($options_pages_obj as $page) {
    $options_pages[$page->ID] = $page->post_title;
}

$opt_name = "travelo";
$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    'opt_name'             => $opt_name,
    'disable_tracking'     => true,
    'display_name'         => $theme->get( 'Name' ),
    'display_version'      => $theme->get( 'Version' ),
    'menu_type'            => 'submenu',
    'allow_sub_menu'       => false,
    'menu_title'           => __( 'Theme Options', 'trav' ),
    'page_title'           => __( 'Travelo Theme Options', 'trav' ),
    'google_api_key'       => '',
    'google_update_weekly' => false,
    'async_typography'     => true,
    //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
    'admin_bar'            => false,
    'admin_bar_icon'       => 'dashicons-portfolio',
    'admin_bar_priority'   => 50,
    'global_variable'      => 'trav_options',
    'dev_mode'             => false,
    'update_notice'        => false,
    'customizer'           => true,
    //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
    //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
    'page_priority'        => null,
    'page_parent'          => 'travelo',
    'page_permissions'     => 'manage_options',
    'menu_icon'            => '',
    'last_tab'             => '',
    'page_icon'            => 'icon-themes',
    'page_slug'            => 'theme_options',
    'save_defaults'        => true,
    'default_show'         => false,
    'default_mark'         => '',
    'show_import_export'   => true,
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    'output_tag'           => true,
    'footer_credit'        => '<span id="footer-thankyou">Theme Options panel created by <strong>SoapTheme</strong></span>',
    'database'             => '',
    'system_info'          => false,
    //'compiler'           => true,
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => 'red',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    )
);

$args['share_icons'][] = array(
    'url'   => 'http://twitter.com/soaptheme',
    'title' => 'Follow us on Twitter',
    'icon'  => 'el el-twitter'
);

$args['intro_text'] = '';
$args['footer_text'] = '&copy; 2015 Travelo';

Redux::setArgs( $opt_name, $args );

$tabs = array(
    array(
        'id'      => 'redux-help-tab-1',
        'title'   => __( 'Theme Information', 'trav' ),
        'content' => __( '<p>If you have any question please check documentation <a href="http://soaptheme.net/document/travelo-wp/">Documentation</a>. And that are beyond the scope of documentation, please feel free to contact us.</p>', 'trav' )
    ),
);
Redux::setHelpTab( $opt_name, $tabs );

// Set the help sidebar
$content = __( '<p></p>', 'trav' );
Redux::setHelpSidebar( $opt_name, $content );

Redux::setSection( $opt_name, array(
    'title' => __( 'Basic Settings', 'trav' ),
    'id'    => 'basic-settings',
    'icon'  => 'el el-home',
    'fields'     => array(
        array(
            'id'       => 'welcome_txt',
            'type'     => 'text',
            'title'    => __( 'Welcome Text', 'trav' ),
            'subtitle' => __( 'Set welcome text on login and signup page', 'trav' ),
            'default'  => 'Welcome to Travelo!',
        ),
        array(
            'id'       => 'signup_desc',
            'type'     => 'text',
            'title'    => __( 'Agreement Content', 'trav' ),
            'subtitle' => __( "Set agreement content for signup. ( in signup modal )", 'trav' ),
            'default'  => "By signing up, I agree to Travelo's Terms of Service, Privacy Policy, Guest Refund Policy, and Host Guarantee Terms",
        ),
        array(
            'id'       => 'copyright',
            'type'     => 'text',
            'title'    => __( 'Copyright Text', 'trav' ),
            'subtitle' => __( 'Set copyright text in footer', 'trav' ),
            'default'  => '2015 Travelo',
        ),
        array(
            'id'       => 'email',
            'type'     => 'text',
            'title'    => __('E-Mail Address', 'trav'),
            'subtitle' => __( 'Set email address text in header( in header7 )', 'trav' ),
            'desc' => __('Leave blank to hide e-mail field', 'trav'),
            'default'  => '',
        ),
        array(
            'id'       => 'phone_no',
            'type'     => 'text',
            'title'    => __('Phone Number', 'trav'),
            'subtitle' => __( 'Set phone number text in header( in header2 & header7 )', 'trav' ),
            'desc' => __('Leave blank to hide phone number field', 'trav'),
            'default'  => '',
        ),
        array(
            'id'       => 'map_api_key',
            'type'     => 'text',
            'title'    => __('Google Map API Key', 'trav'),
            'subtitle' => __( 'Input API key to show Google Maps in Front-end', 'trav' ),
            'desc' => __('If you don\'t have Map API key, you can get from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key">Here</a>', 'trav'),
            'default'  => '',
        ),
        array(
            'id'       => 'map_marker_img',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Map Marker Image', 'trav' ),
            'compiler' => 'true',
            'desc'     => '',
            'subtitle' => __( 'Set an image file for your map marker', 'trav' ),
            'default'  => array( 'url' => TRAV_TEMPLATE_DIRECTORY_URI . "/images/pins/Accommodation.png" ),
        ),        
        array(
            'id'       => 'pace_loading',
            'type'     => 'switch',
            'title'    => __( 'Page Load Progress Bar', 'trav' ),
            'subtitle' => __( 'Enable page load progress bar while page loading', 'trav' ),
            'default'  => true,
        ),
        array(
            'id'       => 'modal_login',
            'type'     => 'switch',
            'title'    => __('Modal Login/Sign Up', 'trav'),
            'subtitle' => __('Enable modal login and modal signup.', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'ajax_pagination',
            'type'     => 'switch',
            'title'    => __('Ajax Pagination', 'trav'),
            'subtitle' => __('Enable ajax pagination.', 'trav'),
            'default'  => false,
        ),
        array(
            'id'       => 'sticky_menu',
            'type'     => 'switch',
            'title'    => __( 'Sticky Menu', 'trav' ),
            'subtitle' => __( 'Enable Sticky Menu', 'trav' ),
            'default'  => true,
        ),
        array(
            'id'       => 'date_format',
            'type'     => 'select',
            'title'    => __('Date Format', 'trav'),
            'subtitle' => __('Please select a date format for datepicker.', 'trav'),
            'options'  => array(
                'mm/dd/yy' => 'mm/dd/yy',
                'dd/mm/yy' => 'dd/mm/yy',
                'yy-mm-dd' => 'yy-mm-dd',
            ),
            'default'  => 'mm/dd/yy'
        ),
		array(
				'id' => 'vld_captcha',
				'type' => 'switch',
				'title' => __('Captcha validation on booking', 'trav'),
				'subtitle' => __('Use captcha validation while booking.', 'trav'),
				'default' => true,
			),
    )
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Styling Options', 'trav' ),
    'id'    => 'styling-settings',
    'icon'  => 'el el-brush'
) );


Redux::setSection( $opt_name, array(
    'title'      => __( 'Logo & Favicon', 'trav' ),
    'id'         => 'logo-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'favicon',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Favicon', 'trav' ),
            'compiler' => 'true',
            'desc'     => '',
            'subtitle' => __( 'Set a 16x16 ico image for your favicon', 'trav' ),
            'default'  => array( 'url' => TRAV_TEMPLATE_DIRECTORY_URI . "/images/favicon.ico" ),
        ),
        array(
            'id'       => 'logo',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Logo Image', 'trav' ),
            'compiler' => 'true',
            'desc'     => '',
            'subtitle' => __( 'Set an image file for your logo', 'trav' ),
            'default'  => array( 'url' => TRAV_TEMPLATE_DIRECTORY_URI . "/images/logo.png" ),
        ),
        array(
            'id'             => 'logo_height_header',
            'type'           => 'dimensions',
            'units'          => 'px',    // You can specify a unit value. Possible: px, em, %
            'units_extended' => 'false',  // Allow users to select any type of unit
            'title'          => __( 'Header Logo Height', 'trav' ),
            'subtitle'  => __( 'Set height of logo in header', 'trav' ),
            'desc'           => __( 'Leave blank to use default value that supported by each header style', 'trav' ),
            'width'         => false,
            'default'        => array()
        ),
        array(
            'id'             => 'logo_height_footer',
            'type'           => 'dimensions',
            'units'          => 'px',    // You can specify a unit value. Possible: px, em, %
            'units_extended' => 'false',  // Allow users to select any type of unit
            'title'          => __( 'Footer Logo Height', 'trav' ),
            'subtitle'  => __( 'Set height of logo in footer', 'trav' ),
            'desc'           => __( 'Leave blank to use default value that supported by each footer style', 'trav' ),
            'width'         => false,
            'default'        => array()
        ),
        array(
            'id'             => 'logo_height_loading',
            'type'           => 'dimensions',
            'units'          => 'px',    // You can specify a unit value. Possible: px, em, %
            'units_extended' => 'false',  // Allow users to select any type of unit
            'title'          => __( 'Loading Page Logo Height', 'trav' ),
            'subtitle'  => __( 'Set height of logo in loading page', 'trav' ),
            'desc'           => __( 'Leave blank to use default value that supported by theme', 'trav' ),
            'width'         => false,
            'default'        => array()
        ),
        array(
            'id'             => 'logo_height_404',
            'type'           => 'dimensions',
            'units'          => 'px',    // You can specify a unit value. Possible: px, em, %
            'units_extended' => 'false',  // Allow users to select any type of unit
            'title'          => __( '404 Page Logo Height', 'trav' ),
            'subtitle'  => __( 'Set height of logo in 404', 'trav' ),
            'desc'           => __( 'Leave blank to use default value that supported by theme', 'trav' ),
            'width'         => false,
            'default'        => array()
        ),
        array(
            'id'             => 'logo_height_chaser',
            'type'           => 'dimensions',
            'units'          => 'px',    // You can specify a unit value. Possible: px, em, %
            'units_extended' => 'false',  // Allow users to select any type of unit
            'title'          => __( 'Chaser Menu Logo Height', 'trav' ),
            'subtitle'  => __( 'Set height of logo in chaser menu ( fixed menu bar at the top while scrolling. ). ', 'trav' ),
            'desc'           => __( 'Leave blank to use default value that supported by theme', 'trav' ),
            'width'         => false,
            'default'        => array()
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Header & Footer', 'trav' ),
    'id'         => 'hf-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'header_style',
            'type'     => 'image_select',
            'title'    => __('Header Style', 'trav'), 
            'subtitle' => __('Select header style', 'trav'),
            'options'  => array(
                'header'      => array(
                    'alt'   => 'header0', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h-def.jpg'
                ),
                'header1'      => array(
                    'alt'   => 'header1', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h1.jpg'
                ),
                'header2'      => array(
                    'alt'   => 'header2', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h2.jpg'
                ),
                'header3'      => array(
                    'alt'   => 'header3', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h3.jpg'
                ),
                'header4'      => array(
                    'alt'   => 'header4', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h4.jpg'
                ),
                'header5'      => array(
                    'alt'   => 'header5', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h5.jpg'
                ),
                'header6'      => array(
                    'alt'   => 'header6', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h6.jpg'
                ),
                'header7'      => array(
                    'alt'   => 'header7', 
                    'img'   => TRAV_IMAGE_URL . '/admin/header/h7.jpg'
                ),
            ),
            'default' => 'header'
        ),
        array(
            'id'       => 'footer_skin',
            'type'     => 'image_select',
            'title'    => __('Footer Style', 'trav'), 
            'subtitle' => __('Select footer style', 'trav'),
            'options'  => array(
                'style-def'      => array(
                    'alt'   => 'style-def', 
                    'img'   => TRAV_IMAGE_URL . '/admin/footer/style-def.jpg'
                ),
                'style1'      => array(
                    'alt'   => 'style1', 
                    'img'   => TRAV_IMAGE_URL . '/admin/footer/style1.jpg'
                ),
                'style2'      => array(
                    'alt'   => 'style2', 
                    'img'   => TRAV_IMAGE_URL . '/admin/footer/style2.jpg'
                ),
                'style3'      => array(
                    'alt'   => 'style3', 
                    'img'   => TRAV_IMAGE_URL . '/admin/footer/style3.jpg'
                ),
                'style4'      => array(
                    'alt'   => 'style4', 
                    'img'   => TRAV_IMAGE_URL . '/admin/footer/style4.jpg'
                ),
                'style5'      => array(
                    'alt'   => 'style5', 
                    'img'   => TRAV_IMAGE_URL . '/admin/footer/style5.jpg'
                ),
                'style6'      => array(
                    'alt'   => 'style6', 
                    'img'   => TRAV_IMAGE_URL . '/admin/footer/style6.jpg'
                ),
            ),
            'default' => 'style-def'
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Site Skin & Layout', 'trav' ),
    'id'         => 'skin-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'skin',
            'type'     => 'image_select',
            'title'    => __('Site Skin', 'trav'), 
            'subtitle' => __('Select a Site Skin', 'trav'),
            'options'  => array(
                'style-light-blue' => array(
                    'alt'   => 'light blue',
                    'title' => 'Light-Blue',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/light-blue.jpg'
                ),
                'style-dark-blue' => array(
                    'alt'   => 'dark blue',
                    'title' => 'Dark-Blue',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/dark-blue.jpg'
                ),
                'style-sea-blue' => array(
                    'alt'   => 'sea blue',
                    'title' => 'Sea-Blue',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/sea-blue.jpg'
                ),
                'style-sky-blue' => array(
                    'alt'   => 'sky blue',
                    'title' => 'Sky-Blue',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/sky-blue.jpg'
                ),
                'style-dark-orange' => array(
                    'alt'   => 'dark orange',
                    'title' => 'Dark-Orange',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/dark-orange.jpg'
                ),
                'style-light-orange' => array(
                    'alt'   => 'light orange',
                    'title' => 'Light-Orange',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/light-orange.jpg'
                ),
                'style-light-yellow' => array(
                    'alt'   => 'light yellow',
                    'title' => 'Light-Yellow',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/light-yellow.jpg'
                ),
                'style-orange' => array(
                    'alt'   => 'orange',
                    'title' => 'Orange',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/orange.jpg'
                ),
                'style-purple'  => array(
                    'alt'   => 'purple',
                    'title' => 'Purple',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/purple.jpg'
                ),
                'style-red' => array(
                    'alt'   => 'red',
                    'title' => 'Red',
                    'img'   => TRAV_IMAGE_URL . '/admin/skin/red.jpg'
                ),
            ),
            'default' => 'style-light-blue'
        ),
        array(
            'id'       => 'boxed_version',
            'type'     => 'switch',
            'title'    => __( 'Boxed Layout', 'trav' ),
            'subtitle' => __( 'Enable Boxed Layout', 'trav' ),
            'default'  => false,
        ),
        array(
            'id'       => 'body_background',
            'type'     => 'background',
            'required' => array( 'boxed_version', '=', true ),
            'output'   => array( 'body' ),
            'title'    => __( 'Body Background', 'trav' ),
            'subtitle' => __( 'Body background with image, color, etc.', 'trav' ),
            'default'   => '#FFFFFF',
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Social Sharing Links', 'trav' ),
    'id'         => 'social-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'title' => __('Facebook', 'trav'),
            'desc' => __( 'Insert your custom link to show the Facebook icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'facebook',
            'type' => 'text'),
        array(
            'title' => __('Twitter', 'trav'),
            'desc' => __( 'Insert your custom link to show the Twitter icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'twitter',
            'type' => 'text'),
        array(
            'title' => __('Google+', 'trav'),
            'desc' => __( 'Insert your custom link to show the Google+ icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'googleplus',
            'type' => 'text'),
        array(
            'title' => __('LinkedIn', 'trav'),
            'desc' => __( 'Insert your custom link to show the LinkedIn icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'linkedin',
            'type' => 'text'),
        array(
            'title' => __('YouTube', 'trav'),
            'desc' => __( 'Insert your custom link to show the YouTube icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'youtube',
            'type' => 'text'),
        array(
            'title' => __('Vimeo', 'trav'),
            'desc' => __( 'Insert your custom link to show the Vimeo icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'vimeo',
            'type' => 'text'),
        array(
            'title' => __('Pinterest', 'trav'),
            'desc' => __( 'Insert your custom link to show the Pinterest icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'pinterest',
            'type' => 'text'),
        array(
            'title' => __('Skype', 'trav'),
            'desc' => __( 'Insert your custom link to show the Skype icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'skype',
            'type' => 'text'),
        array(
            'title' => __('Instagram', 'trav'),
            'desc' => __( 'Insert your custom link to show the Instagram icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'instagram',
            'type' => 'text'),
        array(
            'title' => __('Dribbble', 'trav'),
            'desc' => __( 'Insert your custom link to show the Dribbble icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'dribble',
            'type' => 'text'),
        array(
            'title' => __('Flickr', 'trav'),
            'desc' => __( 'Insert your custom link to show the Flickr icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'flickr',
            'type' => 'text'),
        array(
            'title' => __('Tumblr', 'trav'),
            'desc' => __( 'Insert your custom link to show the Tumblr icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'tumblr',
            'type' => 'text'),
        array(
            'title' => __('Behance', 'trav'),
            'desc' => __( 'Insert your custom link to show the Behance icon. Leave blank to hide icon.', 'trav' ),
            'id' => 'behance',
            'type' => 'text')
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Custom JS & CSS', 'trav' ),
    'id'         => 'custom-code',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'custom_css',
            'type'     => 'ace_editor',
            'title'    => __( 'Custom CSS Code', 'trav' ),
            'subtitle' => __( 'Paste your CSS code here.', 'trav' ),
            'mode'     => 'css',
            'theme'    => 'chrome',
            'default'  => ""
        ),
        array(
            'id'       => 'custom_js',
            'type'     => 'ace_editor',
            'title'    => __( 'Custom Javascript Code', 'trav' ),
            'subtitle' => __( 'Paste your Javascript code here.', 'trav' ),
            'mode'     => 'javascript',
            'theme'    => 'chrome',
            'default'  => ""
        ),
    )
) );

$desc = __('All price fields in admin panel will be considered in this currency', 'trav');
require_once LEVELUP_INC_DIR . '/functions/currency.php';
Redux::setSection( $opt_name, array(
    'title'      => __( 'Currency Settings', 'trav' ),
    'id'         => 'currency-settings',
    'icon'  => 'el el-usd',
    'fields'     => array(
        array(
            'id'       => 'def_currency',
            'type'     => 'select',
            'title'    => __( 'Default Currency', 'trav' ),
            'subtitle' => __( 'Select default currency', 'trav' ),
            'desc'     => apply_filters( 'trav_options_def_currency_desc', $desc ),
            //Must provide key => value pairs for select options
            'options'  => trav_get_all_available_currencies(),
            'default'  => 'usd'
        ),
        array(
            'id'       => 'site_currencies',
            'type'     => 'checkbox',
            'title'    => __('Available Currencies', 'trav'),
            'subtitle' => __('You can select currencies that this site support. You can manage currency list <a href="admin.php?page=currencies">here</a>', 'trav'),
            'desc'     => '',
            'options'  => trav_get_all_available_currencies(),
            'default'  => trav_get_default_available_currencies()
        ),
        array(
            'id'       => 'cs_pos',
            'type'     => 'button_set',
            'title'    => __( 'Currency Symbol Position', 'trav' ),
            'subtitle' => __( "Select a Curency Symbol Position for Frontend", 'trav' ),
            'desc'     => '',
            'options'  => array(
                'before' => __( 'Before Price', 'trav' ),
                'after' => __( 'After Price', 'trav' )
            ),
            'default'  => 'before'
        ),
        array(
            'id'       => 'decimal_prec',
            'type'     => 'select',
            'title'    => __( 'Decimal Precision', 'trav' ),
            'subtitle' => __( 'Please choose decimal precision', 'trav' ),
            'desc'     => '',
            'options'  => array(
                '0' => '0',
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ),
            'default'  => '2'
        ),
        array(
            'id'       => 'currency_format',
            'type'     => 'select',
            'title'    => __( 'Currency Display Format', 'trav' ),
            'subtitle' => __( 'Please choose currency display format', 'trav' ),
            'desc'     => '',
            'options'  => array(
                'nodelimit-point' => '####.##',
                'nodelimit-comma' => '####,##',
                'cdelimit-point' => '#,###.##',
                'pdelimit-comma' => '#.###,##',
                'cbdelimit-point' => "#, ###.##",
                'bdelimit-point' => '# ###.##',
                'bdelimit-comma' => '# ###,##',
                'qdelimit-point' => "#'###.##",
            ),
            'default'  => 'nodelimit-point'
        ),
    )
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Main Page Settings', 'trav' ),
    'id'         => 'main-page-settings',
    'fields'     => array(
        array(
            'id'       => 'dashboard_page',
            'type'     => 'select',
            'title'    => __('Dashboard Page', 'trav'),
            'subtitle' => __('User Dashboard Page.', 'trav'),
            'desc'     => '',
            'options'  => $options_pages,
            'default'  => ''
        ),
        array(
            'id'       => 'login_page',
            'type'     => 'select',
            'title'    => __('Login Page', 'trav'),
            'subtitle' => __('You can leave this field blank if you don\'t need Custom Login Page', 'trav'),
            'desc'     => __('If you set wrong page you should be unable to login. In that case you can login with /wp-login.php?no_redirect=1', 'trav'),
            'options'  => $options_pages,
            'default'  => ''
        ),
        array(
            'id'       => 'redirect_page',
            'type'     => 'select',
            'title'    => __('Page to Redirect to on login', 'trav'),
            'subtitle' => __('Select a Page to Redirect to on login.', 'trav'),
            'options'  => $options_pages,
            'default'  => ''
        ),
			array(
				'id'       => 'terms_page',
				'type'     => 'select',
				'title'    => __('Terms & Conditions Page', 'trav'),
				'subtitle' => __('Booking Terms and Conditions Page.', 'trav'),
				'options'  => $options_pages,
			),
    )
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Accommodation', 'trav' ),
    'id'    => 'accommodation-settings',
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Accommodation Main Settings', 'trav' ),
    'id'         => 'accommodation-main-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'disable_acc',
            'type'     => 'button_set',
            'title'    => __('Enable/Disable accommodation feature.', 'trav'),
            'default'  => 0,
            'options'  => array(
                '0' => __( 'Enable', 'trav' ),
                '1' => __( 'Disable', 'trav' )
            ),
        ),
        array(
            'id'       => 'acc_booking_page',
            'type'     => 'select',
            'required' => array( 'disable_acc', '=', '0' ),
            'title'    => __('Accommodation Booking Page', 'trav'),
            'subtitle' => __('This sets the base page of your accommodation booking.', 'trav'),
            'options'  => $options_pages,
        ),
        array(
            'id'       => 'acc_booking_confirmation_page',
            'type'     => 'select',
            'required' => array( 'disable_acc', '=', '0' ),
            'title'    => __('Accommodation Booking Confirmation Page', 'trav'),
            'subtitle' => __('This sets the accommodation booking confirmation page.', 'trav'),
            'options'  => $options_pages,
        ),
        /*array(
            'id'       => 'terms_page',
            'type'     => 'select',
            'required' => array( 'disable_acc', '=', '0' ),
            'title'    => __('Terms & Conditions Page', 'trav'),
            'subtitle' => __('Booking Terms and Conditions Page.', 'trav'),
            'options'  => $options_pages,
        ),
        array(
            'id'        => 'vld_captcha',
            'type'      => 'switch',
            'required'  => array( 'disable_acc', '=', '0' ),
            'title'     => __('Captcha validation on booking', 'trav'),
            'subtitle'  => __('Use captcha validation while booking.', 'trav'),
            'default'   => true,
        ),*/
    ),
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Accommodation List Page Settings', 'trav' ),
    'id'         => 'accommodation-list-page-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'acc_posts',
            'type'     => 'text',
            'title'    => __( 'Accommodations per page', 'trav' ),
            'subtitle' => __( 'Select a number of accommodations to show on Search Accommodation Result Page', 'trav' ),
            'default'  => '12',
        ),
        array(
            'id'       => 'acc_enable_price_filter',
            'type'     => 'switch',
            'title'    => __('Enable Price Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'        => "acc_price_filter_max",
            'type'      => 'text',
            'required'  => array( 'acc_enable_price_filter', '=', true ),
            'title'     => "Price Filter Max Value",
            'subtitle'  => __( "Set a max price value for price search filter", 'trav' ),
            'default'   => "200",
        ),
        array(
            'id'        => "acc_price_filter_step",
            'type'      => 'text',
            'required'  => array( 'acc_enable_price_filter', '=', true ),
            'title'     => "Price Filter Step",
            'subtitle'  => __( "Set a price step value for price search filter", 'trav' ),
            'default'   => "50",
        ),
        array(
            'id'       => 'acc_enable_review_filter',
            'type'     => 'switch',
            'title'    => __('Enable Review Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'acc_enable_acc_type_filter',
            'type'     => 'switch',
            'title'    => __('Enable Accommodation Type Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'acc_enable_amenity_filter',
            'type'     => 'switch',
            'title'    => __('Enable Amenity Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'        => 'acc_list_zoom',
            'type'      => 'text',
            'title'     => __( 'Map zoom value', 'trav' ),
            'subtitle'  => __( 'Select a zoom value for Map in List page.', 'trav' ),
            'default'   => '14',
        ),
    ),
) );

// add-on compatibility
$acc_add_on_settings = apply_filters( 'trav_options_acc_addon_settings', array() );
if ( ! empty( $acc_add_on_settings ) ) {
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Accommodation Add-On Settings', 'trav' ),
        'id'         => 'accommodation-add-settings',
        'subsection' => true,
        'fields'     => array( $acc_add_on_settings )
    ) );
}

Redux::setSection( $opt_name, array(
    'title'      => __( 'Accommodation Email Settings', 'trav' ),
    'id'         => 'accommodation-email-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'        => 'acc_confirm_email_start',
            'type'      => 'section',
            'title'     => __( 'Customer Email Setting', 'trav' ),
            'indent'    => true,
        ),
        // array(
        //     'title'     => __('Enable Icalendar', 'trav'),
        //     'subtitle'  => __('Send icalendar with booking confirmation email.', 'trav'),
        //     'id'        => 'acc_confirm_email_ical',
        //     'default'   => true,
        //     'type'      => 'switch',
        // ),
        array(
            'title'     => __('Booking Confirmation Email Subject', 'trav'),
            'subtitle'  => __( 'Accommodation booking confirmation email subject.', 'trav' ),
            'id'        => 'acc_confirm_email_subject',
            'default'   => 'Your booking at [accommodation_name]',
            'type'      => 'text',
        ),
        array(
            'title'     => __('Booking Confirmation Email Description', 'trav'),
            'subtitle'  => __( 'Accommodation booking confirmation email description.', 'trav' ),
            'id'        => 'acc_confirm_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_confirm_email_description.htm' ),
            'type'      => 'editor'
        ),
        array(
            'title'     => __('Update Booking Email Subject', 'trav'),
            'subtitle'  => __( 'Accommodation update booking email subject.', 'trav' ),
            'id'        => 'acc_update_email_subject',
            'default'   => 'Your booking at [accommodation_name] is now updated.',
            'type'      => 'text'
        ),
        array(
            'title'     => __('Update Booking Email Description', 'trav'),
            'subtitle'  => __( 'Accommodation update booking email description.', 'trav' ),
            'id'        => 'acc_update_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_update_email_description.htm' ),
            'type'      => 'editor'
        ),
        array(
            'title'     => __('Cancel Booking Email Subject', 'trav'),
            'subtitle'  => __( 'Accommodation cancel booking email subject.', 'trav' ),
            'id'        => 'acc_cancel_email_subject',
            'default'   => 'Your booking at [accommodation_name] is now canceled.',
            'type'      => 'text'
        ),
        array(
            'title'     => __('Cancel Booking Email Description', 'trav'),
            'subtitle'  => __( 'Accommodation cancel booking email description.', 'trav' ),
            'id'        => 'acc_cancel_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_cancel_email_description.htm' ),
            'type'      => 'editor'
        ),
        array(
            'id'        => 'acc_confirm_email_end',
            'type'      => 'section',
            'indent'    => false,
        ),
        array(
            'id'        => 'acc_admin_email_start',
            'type'      => 'section',
            'title'     => __( 'Admin Notification Setting', 'trav' ),
            'indent'    => true,
        ),
        array(
            'title'     => __('Administrator Notification', 'trav'),
            'subtitle'  => __('enable individual booked email notification to site administrator.', 'trav'),
            'id'        => 'acc_booked_notify_admin',
            'default'   => true,
            'type'      => 'switch'
        ),
        array(
            'title'     => __('Administrator Booking Notification Email Subject', 'trav'),
            'subtitle'  => __( 'Administrator Notification Email Subject for Accommodation Booking.', 'trav' ),
            'id'        => 'acc_admin_email_subject',
            'default'   => 'Received a booking at [accommodation_name]',
            'required'  => array( 'acc_booked_notify_admin', '=', '1' ),
            'type'      => 'text'
        ),
        array(
            'title'     => __('Administrator Booking Notification Email Description', 'trav'),
            'subtitle'  => __( 'Administrator Notification Email Description for Accommodation Booking.', 'trav' ),
            'id'        => 'acc_admin_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_admin_email_description.htm' ),
            'required'  => array( 'acc_booked_notify_admin', '=', '1' ),
            'type'      => 'editor'
        ),
        array(
            'title'     => __('Administrator Booking Update Notification Email Subject', 'trav'),
            'subtitle'  => __( 'Administrator notification email subject for accommodation booking update.', 'trav' ),
            'id'        => 'acc_update_admin_email_subject',
            'default'   => 'A booking at [accommodation_name] is updated.',
            'required'  => array( 'acc_booked_notify_admin', '=', '1' ),
            'type'      => 'text'
        ),
        array(
            'title'     => __('Administrator Booking Update Notification Email Description', 'trav'),
            'subtitle'  => __( 'Administrator notification email description for accommodation booking update.', 'trav' ),
            'id'        => 'acc_update_admin_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_update_admin_email_description.htm' ),
            'required'  => array( 'acc_booked_notify_admin', '=', '1' ),
            'type'      => 'editor'
        ),
        array(
            'title'     => __('Administrator Booking Cancel Notification Email Subject', 'trav'),
            'subtitle'  => __( 'Administrator notification email subject for accommodation booking cancel.', 'trav' ),
            'id'        => 'acc_cancel_admin_email_subject',
            'default'   => 'A booking at [accommodation_name] is canceled.',
            'required'  => array( 'acc_booked_notify_admin', '=', '1' ),
            'type'      => 'text'
        ),
        array(
            'title'     => __('Administrator Booking Cancel Notification Email Description', 'trav'),
            'subtitle'  => __( 'Administrator notification email description for accommodation booking cancel.', 'trav' ),
            'id'        => 'acc_cancel_admin_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_cancel_admin_email_description.htm' ),
            'required'  => array( 'acc_booked_notify_admin', '=', '1' ),
            'type'      => 'editor'
        ),
        array(
            'id'        => 'acc_admin_email_end',
            'type'      => 'section',
            'indent'    => false,
        ),
        array(
            'id'        => 'acc_bowner_email_start',
            'type'      => 'section',
            'title'     => __( 'Accommodation Onwer Notification Setting', 'trav' ),
            'indent'    => true,
        ),
        array(
            'title'     => __('Accommodation Owner Notification', 'trav'),
            'subtitle'  => __('Enable individual booked email notification to accommodation owner.', 'trav'),
            'id'        => 'acc_booked_notify_bowner',
            'default'   => true,
            'type'      => 'switch'
        ),
        array(
            'title'     => __('Accommodation Owner Notification Email Subject', 'trav'),
            'subtitle'  => __( 'Accommodation Owner Notification Email Subject for Accommodation Booking.', 'trav' ),
            'id'        => 'acc_bowner_email_subject',
            'default'   => 'Received a booking at [accommodation_name]',
            'required'  => array( 'acc_booked_notify_bowner', '=', '1' ),
            'type'      => 'text'
        ),
        array(
            'title'     => __('Accommodation Owner Notification Email Description', 'trav'),
            'subtitle'  => __( 'Accommodation Owner Notification Email Description for Accommodation Booking.', 'trav' ),
            'id'        => 'acc_bowner_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_bowner_email_description.htm' ),
            'required'  => array( 'acc_booked_notify_bowner', '=', '1' ),
            'type'      => 'editor'
        ),
        array(
            'title'     => __('Accommodation Owner Booking Update Notification Email Subject', 'trav'),
            'subtitle'  => __( 'Accommodation Owner notification email subject for accommodation booking update.', 'trav' ),
            'id'        => 'acc_update_bowner_email_subject',
            'default'   => 'A booking at [accommodation_name] is updated.',
            'required'  => array( 'acc_booked_notify_bowner', '=', '1' ),
            'type'      => 'text'
        ),
        array(
            'title'     => __('Accommodation Owner Booking Update Notification Email Description', 'trav'),
            'subtitle'  => __( 'Accommodation Owner notification email description for accommodation booking update.', 'trav' ),
            'id'        => 'acc_update_bowner_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_update_bowner_email_description.htm' ),
            'required'  => array( 'acc_booked_notify_bowner', '=', '1' ),
            'type'      => 'editor'
        ),
        array(
            'title'     => __('Accommodation Owner Booking Cancel Notification Email Subject', 'trav'),
            'subtitle'  => __( 'Accommodation Owner notification email subject for accommodation booking cancel.', 'trav' ),
            'id'        => 'acc_cancel_bowner_email_subject',
            'default'   => 'A booking at [accommodation_name] is canceled.',
            'required'  => array( 'acc_booked_notify_bowner', '=', '1' ),
            'type'      => 'text'
        ),
        array(
            'title'     => __('Accommodation Owner Booking Cancel Notification Email Description', 'trav'),
            'subtitle'  => __( 'Accommodation Owner notification email description for accommodation booking cancel.', 'trav' ),
            'id'        => 'acc_cancel_bowner_email_description',
            'default'   => file_get_contents( dirname( __FILE__ ) . '/templates/acc_cancel_bowner_email_description.htm' ),
            'required'  => array( 'acc_booked_notify_bowner', '=', '1' ),
            'type'      => 'editor'
        ),
        array(
            'id'        => 'acc_bowner_email_end',
            'type'      => 'section',
            'indent'    => false,
        ),
    ),
) );


Redux::setSection( $opt_name, array(
    'title' => __( 'Tour', 'trav' ),
    'id'    => 'tour-settings',
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Tour Main Settings', 'trav' ),
    'id'         => 'tour-main-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'disable_tour',
            'type'     => 'button_set',
            'title'    => __('Enable/Disable tour feature.', 'trav'),
            'default'  => 0,
            'options'  => array(
                '0' => __( 'Enable', 'trav' ),
                '1' => __( 'Disable', 'trav' )
            ),
        ),
        array(
            'id'       => 'tour_booking_page',
            'type'     => 'select',
            'required' => array( 'disable_tour', '=', '0' ),
            'title'    => __('Tour Booking Page', 'trav'),
            'subtitle' => __('This sets the base page of your tour booking.', 'trav'),
            'options'  => $options_pages,
        ),
        array(
            'id'       => 'tour_booking_confirmation_page',
            'type'     => 'select',
            'required' => array( 'disable_tour', '=', '0' ),
            'title'    => __('Tour Booking Confirmation Page', 'trav'),
            'subtitle' => __('This sets the tour booking confirmation page.', 'trav'),
            'options'  => $options_pages,
        ),
        /*array(
            'id'       => 'tour_terms_page',
            'type'     => 'select',
            'required' => array( 'disable_tour', '=', '0' ),
            'title'    => __('Terms & Conditions Page', 'trav'),
            'subtitle' => __('Booking Terms and Conditions Page.', 'trav'),
            'options'  => $options_pages,
        ),
        array(
            'id' => 'tour_vld_captcha',
            'type' => 'switch',
            'required' => array( 'disable_tour', '=', '0' ),
            'title' => __('Captcha validation on booking', 'trav'),
            'subtitle' => __('Use captcha validation while booking.', 'trav'),
            'default' => true,
        ),*/
    ),
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Tour List Page Settings', 'trav' ),
    'id'         => 'tour-list-page-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'        => 'tour_posts',
            'type'      => 'text',
            'title'     => __( 'Tours per page', 'trav' ),
            'subtitle'  => __( 'Select a number of tours to show on Search Tour Result Page', 'trav' ),
            'default'   => '12',
        ),
        array(
            'id'        => 'tour_enable_price_filter',
            'type'      => 'switch',
            'title'     => __('Enable Price Filter', 'trav'),
            'default'   => true,
        ),
        array(
            'id'        => "tour_price_filter_max",
            'type'      => 'text',
            'required'  => array( 'tour_enable_price_filter', '=', true ),
            'title'     => "Price Filter Max Value",
            'subtitle'  => __( "Set a max price value for price search filter", 'trav' ),
            'default'   => "200",
        ),
        array(
            'id'        => "tour_price_filter_step",
            'type'      => 'text',
            'required'  => array( 'tour_enable_price_filter', '=', true ),
            'title'     => "Price Filter Step",
            'subtitle'  => __( "Set a price step value for price search filter", 'trav' ),
            'default'   => "50",
        ),
        array(
            'id'        => 'tour_enable_tour_type_filter',
            'type'      => 'switch',
            'title'     => __('Enable Tour Type Filter', 'trav'),
            'default'   => true,
        ),
    ),
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Tour Email Settings', 'trav' ),
    'id'         => 'tour-email-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'tour_confirm_email_start',
            'type'     => 'section',
            'title'    => __( 'Customer Email Setting', 'trav' ),
            'indent'   => true,
        ),
        array(
            'title' => __('Booking Confirmation Email Subject', 'trav'),
            'subtitle' => __( 'Tour booking confirmation email subject.', 'trav' ),
            'id' => 'tour_confirm_email_subject',
            'default' => 'Your booking at [tour_name]',
            'type' => 'text'),
        array(
            'title' => __('Booking Confirmation Email Description', 'trav'),
            'subtitle' => __( 'Tour booking confirmation email description.', 'trav' ),
            'id' => 'tour_confirm_email_description',
            'default' => file_get_contents( dirname( __FILE__ ) . '/templates/tour_confirm_email_description.htm' ),
            'type' => 'editor'),
        array(
            'title' => __('Cancel Booking Email Subject', 'trav'),
            'subtitle' => __( 'Tour cancel booking email subject.', 'trav' ),
            'id' => 'tour_cancel_email_subject',
            'default' => 'Your booking at [tour_name] is now canceled.',
            'type' => 'text'),
        array(
            'title' => __('Cancel Booking Email Description', 'trav'),
            'subtitle' => __( 'Tour cancel booking email description.', 'trav' ),
            'id' => 'tour_cancel_email_description',
            'default' => file_get_contents( dirname( __FILE__ ) . '/templates/tour_cancel_email_description.htm' ),
            'type' => 'editor'),
        array(
            'id'     => 'tour_confirm_email_end',
            'type'   => 'section',
            'indent' => false,
        ),
        array(
            'id'       => 'tour_admin_email_start',
            'type'     => 'section',
            'title'    => __( 'Admin Notification Setting', 'trav' ),
            'indent'   => true,
        ),
        array(
            'title' => __('Administrator Notification', 'trav'),
            'subtitle' => __('enable individual booked email notification to site administrator.', 'trav'),
            'id' => 'tour_booked_notify_admin',
            'default' => 'true',
            'type' => 'switch'),
        array(
            'title' => __('Administrator Booking Notification Email Subject', 'trav'),
            'subtitle' => __( 'Administrator Notification Email Subject for Tour Booking.', 'trav' ),
            'id' => 'tour_admin_email_subject',
            'default' => 'Received a booking at [tour_name]',
            'required' => array( 'tour_booked_notify_admin', '=', '1' ),
            'type' => 'text'),
        array(
            'title' => __('Administrator Booking Notification Email Description', 'trav'),
            'subtitle' => __( 'Administrator Notification Email Description for Tour Booking.', 'trav' ),
            'id' => 'tour_admin_email_description',
            'default' => file_get_contents( dirname( __FILE__ ) . '/templates/tour_admin_email_description.htm' ),
            'required' => array( 'tour_booked_notify_admin', '=', '1' ),
            'type' => 'editor'),
        array(
            'title' => __('Administrator Booking Cancel Notification Email Subject', 'trav'),
            'subtitle' => __( 'Administrator notification email subject for tour booking cancel.', 'trav' ),
            'id' => 'tour_cancel_admin_email_subject',
            'default' => 'A booking at [tour_name] is canceled.',
            'required' => array( 'tour_booked_notify_admin', '=', '1' ),
            'type' => 'text'),
        array(
            'title' => __('Administrator Booking Cancel Notification Email Description', 'trav'),
            'subtitle' => __( 'Administrator notification email description for tour booking cancel.', 'trav' ),
            'id' => 'tour_cancel_admin_email_description',
            'default' => file_get_contents( dirname( __FILE__ ) . '/templates/tour_cancel_admin_email_description.htm' ),
            'required' => array( 'tour_booked_notify_admin', '=', '1' ),
            'type' => 'editor'),
        array(
            'id'     => 'tour_admin_email_end',
            'type'   => 'section',
            'indent' => false,
        ),
        array(
            'id'       => 'tour_bowner_email_start',
            'type'     => 'section',
            'title'    => __( 'Tour Onwer Notification Setting', 'trav' ),
            'indent'   => true,
        ),
        array(
            'title' => __('Tour Owner Notification', 'trav'),
            'subtitle' => __('enable individual booked email notification to tour owner.', 'trav'),
            'id' => 'tour_booked_notify_bowner',
            'default' => 'true',
            'type' => 'switch'),
        array(
            'title' => __('Tour Owner Notification Email Subject', 'trav'),
            'subtitle' => __( 'Tour Owner Notification Email Subject for Tour Booking.', 'trav' ),
            'id' => 'tour_bowner_email_subject',
            'default' => 'Received a booking at [tour_name]',
            'required' => array( 'tour_booked_notify_bowner', '=', '1' ),
            'type' => 'text'),
        array(
            'title' => __('Tour Owner Notification Email Description', 'trav'),
            'subtitle' => __( 'Tour Owner Notification Email Description for Tour Booking.', 'trav' ),
            'id' => 'tour_bowner_email_description',
            'default' => file_get_contents( dirname( __FILE__ ) . '/templates/tour_bowner_email_description.htm' ),
            'required' => array( 'tour_booked_notify_bowner', '=', '1' ),
            'type' => 'editor'),
        array(
            'title' => __('Tour Owner Booking Cancel Notification Email Subject', 'trav'),
            'subtitle' => __( 'Tour Owner notification email subject for tour booking cancel.', 'trav' ),
            'id' => 'tour_cancel_bowner_email_subject',
            'default' => 'A booking at [tour_name] is canceled.',
            'required' => array( 'tour_booked_notify_bowner', '=', '1' ),
            'type' => 'text'),
        array(
            'title' => __('Tour Owner Booking Cancel Notification Email Description', 'trav'),
            'subtitle' => __( 'Tour Owner notification email description for tour booking cancel.', 'trav' ),
            'id' => 'tour_cancel_bowner_email_description',
            'default' => file_get_contents( dirname( __FILE__ ) . '/templates/tour_cancel_bowner_email_description.htm' ),
            'required' => array( 'tour_booked_notify_bowner', '=', '1' ),
            'type' => 'editor'),
        array(
            'id'     => 'tour_bowner_email_end',
            'type'   => 'section',
            'indent' => false,
        ),
    ),
) );

// add-on compatibility
$tour_add_on_settings = apply_filters( 'trav_options_tour_addon_settings', array() );
if ( ! empty( $tour_add_on_settings ) ) {
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Tour Add-On Settings', 'trav' ),
        'id'         => 'tour-add-settings',
        'subsection' => true,
        'fields'     => array( $tour_add_on_settings )
    ) );
}

Redux::setSection( $opt_name, array(
	'title' => __( 'Car Rental', 'trav' ),
	'id'    => 'car-settings',
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Car Rental Main Settings', 'trav' ),
	'id'         => 'car-main-settings',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'disable_car',
			'type'     => 'button_set',
			'title'    => __('Enable/Disable car rental feature.', 'trav'),
			'default'  => 0,
			'options'  => array(
				'0' => __( 'Enable', 'trav' ),
				'1' => __( 'Disable', 'trav' )
			),
		),
		array(
			'id'       => 'car_booking_page',
			'type'     => 'select',
			'required' => array( 'disable_car', '=', '0' ),
			'title'    => __('Car Booking Page', 'trav'),
			'subtitle' => __('This sets the base page of your car booking.', 'trav'),
			'options'  => $options_pages,
		),
		array(
			'id'       => 'car_booking_confirmation_page',
			'type'     => 'select',
			'required' => array( 'disable_car', '=', '0' ),
			'title'    => __('Car Booking Confirmation Page', 'trav'),
			'subtitle' => __('This sets the car booking confirmation page.', 'trav'),
			'options'  => $options_pages,
		),			
	),
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Car Rental List Page Settings', 'trav' ),
    'id'         => 'car-rental-list-page-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'car_posts',
            'type'     => 'text',
            'required' => array( 'disable_car', '=', '0' ),
            'title'    => __( 'Car per page', 'trav' ),
            'subtitle' => __( 'Select a number of cars to show on Search Car Result Page', 'trav' ),
            'default'  => '12',
        ),
        array(
            'id'       => 'car_enable_price_filter',
            'type'     => 'switch',
            'title'    => __('Enable Price Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id' => "car_price_filter_max",
            'type' => 'text',
            'required'  => array( 'car_enable_price_filter', '=', true ),
            'title' => "Price Filter Max Value",
            'subtitle' => __( "Set a max price value for price search filter", 'trav' ),
            'default' => "200",
        ),
        array(
            'id' => "car_price_filter_step",
            'type' => 'text',
            'required'  => array( 'car_enable_price_filter', '=', true ),
            'title' => "Price Filter Step",
            'subtitle' => __( "Set a price step value for price search filter", 'trav' ),
            'default' => "50",
        ),
        array(
            'id'       => 'car_enable_car_type_filter',
            'type'     => 'switch',
            'title'    => __('Enable Car Type Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'car_enable_car_agent_filter',
            'type'     => 'switch',
            'title'    => __('Enable Car Rental Agent Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'car_enable_preference_filter',
            'type'     => 'switch',
            'title'    => __('Enable Car Preference Filter', 'trav'),
            'default'  => true,
        ),
    ),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Car Rental Email Settings', 'trav' ),
	'id'         => 'car-email-settings',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'car_confirm_email_start',
			'type'     => 'section',
			'title'    => __( 'Customer Email Setting', 'trav' ),
			// 'subtitle' => __( '', 'trav' ),
			'indent'   => true,
		),
		array(
			'title' => __('Booking Confirmation Email Subject', 'trav'),
			'subtitle' => __( 'Car booking confirmation email subject.', 'trav' ),
			'id' => 'car_confirm_email_subject',
			'default' => 'Your booking at [car_name]',
			'type' => 'text'),
		array(
			'title' => __('Booking Confirmation Email Description', 'trav'),
			'subtitle' => __( 'Car booking confirmation email description.', 'trav' ),
			'id' => 'car_confirm_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_confirm_email_description.htm' ),
			'type' => 'editor'),
		array(
			'title' => __('Update Booking Email Subject', 'trav'),
			'subtitle' => __( 'Car update booking email subject.', 'trav' ),
			'id' => 'car_update_email_subject',
			'default' => 'Your booking at [car_name] is now updated.',
			'type' => 'text'),
		array(
			'title' => __('Update Booking Email Description', 'trav'),
			'subtitle' => __( 'Car update booking email description.', 'trav' ),
			'id' => 'car_update_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_update_email_description.htm' ),
			'type' => 'editor'),
		array(
			'title' => __('Cancel Booking Email Subject', 'trav'),
			'subtitle' => __( 'Car cancel booking email subject.', 'trav' ),
			'id' => 'car_cancel_email_subject',
			'default' => 'Your booking at [car_name] is now canceled.',
			'type' => 'text'),
		array(
			'title' => __('Cancel Booking Email Description', 'trav'),
			'subtitle' => __( 'Car cancel booking email description.', 'trav' ),
			'id' => 'car_cancel_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_cancel_email_description.htm' ),
			'type' => 'editor'),
		array(
			'id'     => 'car_confirm_email_end',
			'type'   => 'section',
			'indent' => false,
		),
		array(
			'id'       => 'car_admin_email_start',
			'type'     => 'section',
			'title'    => __( 'Admin Notification Setting', 'trav' ),
			// 'subtitle' => __( '', 'trav' ),
			'indent'   => true,
		),
		array(
			'title' => __('Administrator Notification', 'trav'),
			'subtitle' => __('enable individual booked email notification to site administrator.', 'trav'),
			'id' => 'car_booked_notify_admin',
			'default' => 'true',
			'type' => 'switch'),
		array(
			'title' => __('Administrator Booking Notification Email Subject', 'trav'),
			'subtitle' => __( 'Administrator Notification Email Subject for Car Booking.', 'trav' ),
			'id' => 'car_admin_email_subject',
			'default' => 'Received a booking at [car_name]',
			'required' => array( 'car_booked_notify_admin', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Administrator Booking Notification Email Description', 'trav'),
			'subtitle' => __( 'Administrator Notification Email Description for Car Booking.', 'trav' ),
			'id' => 'car_admin_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_admin_email_description.htm' ),
			'required' => array( 'car_booked_notify_admin', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Administrator Booking Update Notification Email Subject', 'trav'),
			'subtitle' => __( 'Administrator notification email subject for Car booking update.', 'trav' ),
			'id' => 'car_update_admin_email_subject',
			'default' => 'A booking at [car_name] is updated.',
			'required' => array( 'car_booked_notify_admin', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Administrator Booking Update Notification Email Description', 'trav'),
			'subtitle' => __( 'Administrator notification email description for Car booking update.', 'trav' ),
			'id' => 'car_update_admin_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_update_admin_email_description.htm' ),
			'required' => array( 'car_booked_notify_admin', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Administrator Booking Cancel Notification Email Subject', 'trav'),
			'subtitle' => __( 'Administrator notification email subject for Car booking cancel.', 'trav' ),
			'id' => 'car_cancel_admin_email_subject',
			'default' => 'A booking at [car_name] is canceled.',
			'required' => array( 'car_booked_notify_admin', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Administrator Booking Cancel Notification Email Description', 'trav'),
			'subtitle' => __( 'Administrator notification email description for Car booking cancel.', 'trav' ),
			'id' => 'car_cancel_admin_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_cancel_admin_email_description.htm' ),
			'required' => array( 'car_booked_notify_admin', '=', '1' ),
			'type' => 'editor'),
		array(
			'id'     => 'car_admin_email_end',
			'type'   => 'section',
			'indent' => false,
		),
		array(
			'id'       => 'car_bowner_email_start',
			'type'     => 'section',
			'title'    => __( 'Car Onwer Notification Setting', 'trav' ),
			// 'subtitle' => __( '', 'trav' ),
			'indent'   => true,
		),
		array(
			'title' => __('Car Owner Notification', 'trav'),
			'subtitle' => __('enable individual booked email notification to Car owner.', 'trav'),
			'id' => 'car_booked_notify_bowner',
			'default' => 'true',
			'type' => 'switch'),
		array(
			'title' => __('Car Owner Notification Email Subject', 'trav'),
			'subtitle' => __( 'Car Owner Notification Email Subject for Car Booking.', 'trav' ),
			'id' => 'car_bowner_email_subject',
			'default' => 'Received a booking at [car_name]',
			'required' => array( 'car_booked_notify_bowner', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Car Owner Notification Email Description', 'trav'),
			'subtitle' => __( 'Car Owner Notification Email Description for Car Booking.', 'trav' ),
			'id' => 'car_bowner_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_bowner_email_description.htm' ),
			'required' => array( 'car_booked_notify_bowner', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Car Owner Booking Update Notification Email Subject', 'trav'),
			'subtitle' => __( 'Car Owner notification email subject for Car booking update.', 'trav' ),
			'id' => 'car_update_bowner_email_subject',
			'default' => 'A booking at [car_name] is updated.',
			'required' => array( 'car_booked_notify_bowner', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Car Owner Booking Update Notification Email Description', 'trav'),
			'subtitle' => __( 'Car Owner notification email description for Car booking update.', 'trav' ),
			'id' => 'car_update_bowner_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_update_bowner_email_description.htm' ),
			'required' => array( 'car_booked_notify_bowner', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Car Owner Booking Cancel Notification Email Subject', 'trav'),
			'subtitle' => __( 'Car Owner notification email subject for Car booking cancel.', 'trav' ),
			'id' => 'car_cancel_bowner_email_subject',
			'default' => 'A booking at [car_name] is canceled.',
			'required' => array( 'car_booked_notify_bowner', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Car Owner Booking Cancel Notification Email Description', 'trav'),
			'subtitle' => __( 'Car Owner notification email description for Car booking cancel.', 'trav' ),
			'id' => 'car_cancel_bowner_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/car_cancel_bowner_email_description.htm' ),
			'required' => array( 'car_booked_notify_bowner', '=', '1' ),
			'type' => 'editor'),
		array(
			'id'     => 'car_bowner_email_end',
			'type'   => 'section',
			'indent' => false,
		),
	),
) );

// add-on compatibility
$car_add_on_settings = apply_filters( 'trav_options_car_addon_settings', array() );
if ( ! empty( $car_add_on_settings ) ) {
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Car Add-On Settings', 'trav' ),
		'id'         => 'car-add-settings',
		'subsection' => true,
		'fields'     => array( $car_add_on_settings )
	) );
}

Redux::setSection( $opt_name, array(
	'title' => __( 'Cruise', 'trav' ),
	'id'    => 'cruise-settings',
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Cruise Main Settings', 'trav' ),
	'id'         => 'cruise-main-settings',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'disable_cruise',
			'type'     => 'button_set',
			'title'    => __('Enable/Disable cruise feature.', 'trav'),
			'default'  => 0,
			'options'  => array(
				'0' => __( 'Enable', 'trav' ),
				'1' => __( 'Disable', 'trav' )
			),
		),
		array(
			'id'       => 'cruise_booking_page',
			'type'     => 'select',
			'required' => array( 'disable_cruise', '=', '0' ),
			'title'    => __('Cruise Booking Page', 'trav'),
			'subtitle' => __('This sets the base page of your cruise booking.', 'trav'),
			'options'  => $options_pages,
		),
		array(
			'id'       => 'cruise_booking_confirmation_page',
			'type'     => 'select',
			'required' => array( 'disable_cruise', '=', '0' ),
			'title'    => __('Cruise Booking Confirmation Page', 'trav'),
			'subtitle' => __('This sets the cruise booking confirmation page.', 'trav'),
			'options'  => $options_pages,
		),	
    ),
));

Redux::setSection( $opt_name, array(
    'title'      => __( 'Cruise List Page Settings', 'trav' ),
    'id'         => 'cruise-list-page-settings',
    'subsection' => true,
    'fields'     => array(		
		array(
			'id'       => 'cruise_posts',
			'type'     => 'text',
			'required' => array( 'disable_cruise', '=', '0' ),
			'title'    => __( 'Cruises per page', 'trav' ),
			'subtitle' => __( 'Select a number of cruises to show on Search Cruise Result Page', 'trav' ),
			'default'  => '12',
		),
		array(
            'id'       => 'cruise_enable_price_filter',
            'type'     => 'switch',
            'title'    => __('Enable Price Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id' => "cruise_price_filter_max",
            'type' => 'text',
            'required'  => array( 'cruise_enable_price_filter', '=', true ),
            'title' => "Price Filter Max Value",
            'subtitle' => __( "Set a max price value for price search filter", 'trav' ),
            'default' => "200",
        ),
        array(
            'id' => "cruise_price_filter_step",
            'type' => 'text',
            'required'  => array( 'cruise_enable_price_filter', '=', true ),
            'title' => "Price Filter Step",
            'subtitle' => __( "Set a price step value for price search filter", 'trav' ),
            'default' => "50",
        ),
        array(
            'id'       => 'cruise_enable_review_filter',
            'type'     => 'switch',
            'title'    => __('Enable Review Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'cruise_enable_cruise_type_filter',
            'type'     => 'switch',
            'title'    => __('Enable Cruise Type Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'cruise_enable_amenity_filter',
            'type'     => 'switch',
            'title'    => __('Enable Amenity Filter', 'trav'),
            'default'  => true,
        ),
        array(
            'id'       => 'cruise_enable_cruise_line_filter',
            'type'     => 'switch',
            'title'    => __('Enable Cruise Line Filter', 'trav'),
            'default'  => true,
        ),
	),
) );

// add-on compatibility
$cruise_add_on_settings = apply_filters( 'trav_options_cruise_addon_settings', array() );
if ( ! empty( $cruise_add_on_settings ) ) {
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Cruise Add-On Settings', 'trav' ),
		'id'         => 'cruise-add-settings',
		'subsection' => true,
		'fields'     => array( $cruise_add_on_settings )
	) );
}

Redux::setSection( $opt_name, array(
	'title'      => __( 'Cruise Email Settings', 'trav' ),
	'id'         => 'Cruise-email-settings',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'cruise_confirm_email_start',
			'type'     => 'section',
			'title'    => __( 'Customer Email Setting', 'trav' ),
			// 'subtitle' => __( '', 'trav' ),
			'indent'   => true,
		),
		array(
			'title' => __('Enable Icalendar', 'trav'),
			'subtitle' => __('Send icalendar with booking confirmation email.', 'trav'),
			'id' => 'cruise_confirm_email_ical',
			'default' => true,
			'type' => 'switch'),
		array(
			'title' => __('Booking Confirmation Email Subject', 'trav'),
			'subtitle' => __( 'Cruise booking confirmation email subject.', 'trav' ),
			'id' => 'cruise_confirm_email_subject',
			'default' => 'Your booking at [cruise_name]',
			'type' => 'text'),
		array(
			'title' => __('Booking Confirmation Email Description', 'trav'),
			'subtitle' => __( 'Cruise booking confirmation email description.', 'trav' ),
			'id' => 'cruise_confirm_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_confirm_email_description.htm' ),
			'type' => 'editor'),
		array(
			'title' => __('Update Booking Email Subject', 'trav'),
			'subtitle' => __( 'Cruise update booking email subject.', 'trav' ),
			'id' => 'cruise_update_email_subject',
			'default' => 'Your booking at [cruise_name] is now updated.',
			'type' => 'text'),
		array(
			'title' => __('Update Booking Email Description', 'trav'),
			'subtitle' => __( 'Cruise update booking email description.', 'trav' ),
			'id' => 'cruise_update_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_update_email_description.htm' ),
			'type' => 'editor'),
		array(
			'title' => __('Cancel Booking Email Subject', 'trav'),
			'subtitle' => __( 'Cruise cancel booking email subject.', 'trav' ),
			'id' => 'cruise_cancel_email_subject',
			'default' => 'Your booking at [cruise_name] is now canceled.',
			'type' => 'text'),
		array(
			'title' => __('Cancel Booking Email Description', 'trav'),
			'subtitle' => __( 'Cruise cancel booking email description.', 'trav' ),
			'id' => 'cruise_cancel_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_cancel_email_description.htm' ),
			'type' => 'editor'),
		array(
			'id'     => 'cruise_confirm_email_end',
			'type'   => 'section',
			'indent' => false,
		),
		array(
			'id'       => 'cruise_admin_email_start',
			'type'     => 'section',
			'title'    => __( 'Admin Notification Setting', 'trav' ),
			// 'subtitle' => __( '', 'trav' ),
			'indent'   => true,
		),
		array(
			'title' => __('Administrator Notification', 'trav'),
			'subtitle' => __('enable individual booked email notification to site administrator.', 'trav'),
			'id' => 'cruise_booked_notify_admin',
			'default' => 'true',
			'type' => 'switch'),
		array(
			'title' => __('Administrator Booking Notification Email Subject', 'trav'),
			'subtitle' => __( 'Administrator Notification Email Subject for Cruise Booking.', 'trav' ),
			'id' => 'cruise_admin_email_subject',
			'default' => 'Received a booking at [cruise_name]',
			'required' => array( 'cruise_booked_notify_admin', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Administrator Booking Notification Email Description', 'trav'),
			'subtitle' => __( 'Administrator Notification Email Description for Cruise Booking.', 'trav' ),
			'id' => 'cruise_admin_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_admin_email_description.htm' ),
			'required' => array( 'cruise_booked_notify_admin', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Administrator Booking Update Notification Email Subject', 'trav'),
			'subtitle' => __( 'Administrator notification email subject for cruise booking update.', 'trav' ),
			'id' => 'cruise_update_admin_email_subject',
			'default' => 'A booking at [cruise_name] is updated.',
			'required' => array( 'cruise_booked_notify_admin', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Administrator Booking Update Notification Email Description', 'trav'),
			'subtitle' => __( 'Administrator notification email description for cruise booking update.', 'trav' ),
			'id' => 'cruise_update_admin_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_update_admin_email_description.htm' ),
			'required' => array( 'cruise_booked_notify_admin', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Administrator Booking Cancel Notification Email Subject', 'trav'),
			'subtitle' => __( 'Administrator notification email subject for cruise booking cancel.', 'trav' ),
			'id' => 'cruise_cancel_admin_email_subject',
			'default' => 'A booking at [cruise_name] is canceled.',
			'required' => array( 'cruise_booked_notify_admin', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Administrator Booking Cancel Notification Email Description', 'trav'),
			'subtitle' => __( 'Administrator notification email description for cruise booking cancel.', 'trav' ),
			'id' => 'cruise_cancel_admin_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_cancel_admin_email_description.htm' ),
			'required' => array( 'cruise_booked_notify_admin', '=', '1' ),
			'type' => 'editor'),
		array(
			'id'     => 'cruise_admin_email_end',
			'type'   => 'section',
			'indent' => false,
		),
		array(
			'id'       => 'cruise_bowner_email_start',
			'type'     => 'section',
			'title'    => __( 'Cruise Onwer Notification Setting', 'trav' ),
			// 'subtitle' => __( '', 'trav' ),
			'indent'   => true,
		),
		array(
			'title' => __('Cruise Owner Notification', 'trav'),
			'subtitle' => __('enable individual booked email notification to cruise owner.', 'trav'),
			'id' => 'cruise_booked_notify_bowner',
			'default' => 'true',
			'type' => 'switch'),
		array(
			'title' => __('Cruise Owner Notification Email Subject', 'trav'),
			'subtitle' => __( 'Cruise Owner Notification Email Subject for cruise Booking.', 'trav' ),
			'id' => 'cruise_bowner_email_subject',
			'default' => 'Received a booking at [cruise_name]',
			'required' => array( 'cruise_booked_notify_bowner', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Cruise Owner Notification Email Description', 'trav'),
			'subtitle' => __( 'Cruise Owner Notification Email Description for Cruise Booking.', 'trav' ),
			'id' => 'cruise_bowner_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_bowner_email_description.htm' ),
			'required' => array( 'cruise_booked_notify_bowner', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Cruise Owner Booking Update Notification Email Subject', 'trav'),
			'subtitle' => __( 'Cruise Owner notification email subject for cruise booking update.', 'trav' ),
			'id' => 'cruise_update_bowner_email_subject',
			'default' => 'A booking at [cruise_name] is updated.',
			'required' => array( 'cruise_booked_notify_bowner', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Cruise Owner Booking Update Notification Email Description', 'trav'),
			'subtitle' => __( 'Cruise Owner notification email description for cruise booking update.', 'trav' ),
			'id' => 'cruise_update_bowner_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_update_bowner_email_description.htm' ),
			'required' => array( 'cruise_booked_notify_bowner', '=', '1' ),
			'type' => 'editor'),
		array(
			'title' => __('Cruise Owner Booking Cancel Notification Email Subject', 'trav'),
			'subtitle' => __( 'Cruise Owner notification email subject for cruise booking cancel.', 'trav' ),
			'id' => 'cruise_cancel_bowner_email_subject',
			'default' => 'A booking at [cruise_name] is canceled.',
			'required' => array( 'cruise_booked_notify_bowner', '=', '1' ),
			'type' => 'text'),
		array(
			'title' => __('Cruise Owner Booking Cancel Notification Email Description', 'trav'),
			'subtitle' => __( 'Cruise Owner notification email description for cruise booking cancel.', 'trav' ),
			'id' => 'cruise_cancel_bowner_email_description',
			'default' => file_get_contents( dirname( __FILE__ ) . '/templates/cruise_cancel_bowner_email_description.htm' ),
			'required' => array( 'cruise_booked_notify_bowner', '=', '1' ),
			'type' => 'editor'),
		array(
			'id'     => 'cruise_bowner_email_end',
			'type'   => 'section',
			'indent' => false,
		),
	),
) );

Redux::setSection( $opt_name, array(
    'title' => __( 'Payment', 'trav' ),
    'id'    => 'payment-settings',
) );
Redux::setSection( $opt_name, array(
    'title' => __( 'Paypal', 'trav' ),
    'id'    => 'paypal-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'title' => __('PayPal Integration', 'trav'),
            'subtitle' => __('Enable payment through PayPal in booking step.', 'trav'),
            'id' => 'acc_pay_paypal',
            'default' => true,
            'type' => 'switch'),

        array(
            'title' => __('Sandbox Mode', 'trav'),
            'subtitle' => __('Enable PayPal sandbox for testing.', 'trav'),
            'id' => 'acc_pay_paypal_sandbox',
            'default' => false,
            'required' => array( 'acc_pay_paypal', '=', '1' ),
            'type' => 'switch'),

        array(
            'title' => __('PayPal API Username', 'trav'),
            'subtitle' => __('Your PayPal Account API Username.', 'trav'),
            'id' => 'acc_pay_paypal_api_username',
            'default' => '',
            'required' => array( 'acc_pay_paypal', '=', '1' ),
            'type' => 'text'),

        array(
            'title' => __('PayPal API Password', 'trav'),
            'subtitle' => __('Your PayPal Account API Password.', 'trav'),
            'id' => 'acc_pay_paypal_api_password',
            'default' => '',
            'required' => array( 'acc_pay_paypal', '=', '1' ),
            'type' => 'text'),

        array(
            'title' => __('PayPal API Signature', 'trav'),
            'subtitle' => __('Your PayPal Account API Signature.', 'trav'),
            'id' => 'acc_pay_paypal_api_signature',
            'default' => '',
            'required' => array( 'acc_pay_paypal', '=', '1' ),
            'type' => 'text'),
    )
) );

// add-on compatibility
$payment_add_on_settings = apply_filters( 'trav_options_payment_addon_settings', array() );
if ( ! empty( $payment_add_on_settings ) ) {
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Payment Add-On Settings', 'trav' ),
        'id'         => 'payment-add-settings',
        'subsection' => true,
        'fields'     => $payment_add_on_settings
    ) );
}

if ( class_exists( 'WooCommerce' ) ) { 
    Redux::setSection( $opt_name, array( 
        'title' => __( 'WooCommerce', 'woocommerce' ),
        'id'    => 'woocommerce_options',
        'icon'  => 'el el-shopping-cart',
        'fields'        => array(
            array(
                'title'     => __('Show Mini-cart on Header', 'trav'),
                'id'        => 'woo_show_mini_cart',
                'on'        => __('Enable', 'trav'),
                'off'       => __('Disable', 'trav'),
                'type'      => 'switch',
                'default'   => 1,
            ),
            array(
                'title'     => __('Sale Flash', 'trav'),
                'id'        => 'woo_show_sale_flash',
                'on'        => __('Enable', 'trav'),
                'off'       => __('Disable', 'trav'),
                'type'      => 'switch',
                'default'   => 1,
            ),
            array( 
                'title'     => __('Flash Type', 'trav'),
                'id'        => 'woo_sale_flash_type',
                'type'      => 'select',
                'options'   => array( 
                    'only_text'         => __('Show only Sale Text', 'trav'),
                    'with_percentage'   => __('Show Label with Percentage', 'trav'),
                ),
                'default'   => 'only_text',
                'required'  => array( 'woo_show_sale_flash', '=', 1 ),
            ),
            array(
                'title'     => __('Sale Label', 'trav'),
                'id'        => 'shop_sale_label',
                'type'      => 'text',
                'default'   => '',
                'required'  => array( 'woo_sale_flash_type', '=', 'only_text' ),
            ),
            // array(
            //     'title'     => __('Out of Stock Label', 'trav'),
            //     'id'        => 'shop_out_of_stock_label',
            //     'type'      => 'text',
            //     'default'   => '',
            // ),
        )
    ) );

    Redux::setSection( $opt_name, array( 
        'title'         => __( 'Shop', 'woocommerce' ),
        'id'            => 'woo_shop_options',
        'subsection'    => true, 
        'fields'        => array( 
            array(
                'title'     => __('Page Layout', 'trav'),
                'id'        => 'shop_page_layout',
                'type'      => 'button_set',
                'options'   => array(
                    'no_sidebar'    => __('No Sidebar', 'trav'),
                    'left_sidebar'  => __('Left Sidebar', 'trav'),
                    'right_sidebar' => __('Right Sidebar', 'trav'),
                ),
                'default'   => 'left_sidebar',
            ),
            array(
                'title' => __('Catalog Mode', 'trav'),
                'id'    => 'shop_catalog_mode',
                'desc'  => __('If enable, this option Turns Off the shopping functionality of WooCommerce.', 'trav'),
                'on'    => __('Enable', 'trav'),
                'off'   => __('Disable', 'trav'),
                'type'  => 'switch',
            ),
            // array(
            //     'id'        => 'shop_pagination_shop',
            //     'type'      => 'button_set',
            //     'title'     => __( 'Shop Pagination', 'trav' ),
            //     'options'   => array(
            //         'classic'   => __('Classic', 'trav'),
            //         'load_more' => __('Load More', 'trav'),
            //     ),
            //     'default'   => 'classic',
            // ),
            // array(
            //     'title' => __('Quick View', 'trav'),
            //     'id'    => 'shop_quick_view',
            //     'on'    => __('Enable', 'trav'),
            //     'off'   => __('Disable', 'trav'),
            //     'type'  => 'switch',
            // ),
            array(
                'title'     => __('Number of Columns', 'trav'),
                'id'        => 'shop_product_columns',
                'min'       => '2',
                'step'      => '1',
                'max'       => '6',
                'type'      => 'slider',
                'default'   => '3',
            ),
            array(
                'title'     => __('Number of Products per Page', 'trav'),
                'id'        => 'shop_products_per_page',
                'min'       => '1',
                'step'      => '1',
                'max'       => '48',
                'type'      => 'slider',
                'edit'      => '1',
                'default'   => '12',
            ),
            array(
                'title'     => __('Show Ratings', 'trav'),
                'id'        => 'shop_ratings_archive_page',
                'on'        => __('Enable', 'trav'),
                'off'       => __('Disable', 'trav'),
                'type'      => 'switch',
                'default'   => 1,
            ),
        )
    ));

    Redux::setSection( $opt_name, array( 
        'title'         => __( 'Single Product', 'woocommerce' ),
        'id'            => 'woo_product_options',
        'subsection'    => true, 
        'fields'        => array(
            array(
                'title'    => __('Page Layout', 'trav'),
                'id'       => 'product_page_layout',
                'type'     => 'button_set',
                'options'  => array(
                    'no_sidebar'    => __('No Sidebar', 'trav'),
                    'left_sidebar'  => __('Left Sidebar', 'trav'),
                    'right_sidebar' => __('Right Sidebar', 'trav'),
                ),
                'default'  => 'right_sidebar'
            ),
            // array(
            //     'title'     => __('Show Summary on Sidebar', 'trav'),
            //     'id'        => 'product_summary_pos',
            //     'type'      => 'switch',
            //     'on'        => __('Yes', 'trav'),
            //     'off'       => __('No', 'trav'),
            //     'default'   => 1,
            //     'required'  => array( 'product_page_layout', '!=', 'no_sidebar' ),
            // ),
            // array(
            //     'title'     => __('Sharing Options', 'trav'),
            //     'id'        => 'product_sharing',
            //     'type'      => 'switch',
            //     'on'        => __('Enable', 'trav'),
            //     'off'       => __('Disable', 'trav'),
            //     'default'   => 1,
            // ),
            array(
                'title'     => __('Related Products', 'trav'),
                'id'        => 'product_related',
                'type'      => 'switch',
                'on'        => __('Enable', 'trav'),
                'off'       => __('Disable', 'trav'),
                'default'   => 1,
            ),
            array(
                'title'     => __('Related Product Columns', 'trav'),
                'id'        => 'related_product_columns',
                'type'      => 'slider',
                'min'       => '2',
                'step'      => '1',
                'max'       => '6',
                'default'   => '4',
                'required'  => array( 'product_related', '=', 1 ),
            ),
            array(
                'title'     => __('Up-sell Products', 'trav'),
                'id'        => 'product_up_sell',
                'type'      => 'switch',
                'on'        => __('Enable', 'trav'),
                'off'       => __('Disable', 'trav'),
                'default'   => 1,
            ),
            array(
                'title'     => __('Up-sell Product Columns', 'trav'),
                'id'        => 'up_sell_product_columns',
                'type'      => 'slider',
                'min'       => '2',
                'step'      => '1',
                'max'       => '6',
                'default'   => '4',
                'required'  => array( 'product_up_sell', '=', 1 ),
            ),
        )
    ));
}
