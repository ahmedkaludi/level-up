<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * init function
 */
if ( ! function_exists( 'trav_init' ) ) {
    function trav_init() {
        ob_start();
        // register header nav menu location
        register_nav_menu( 'header-menu', 'Header Menu' );
        global $trav_options, $def_currency, $search_max_rooms, $search_max_cabins, $search_max_adults, $search_max_kids, $search_max_passengers, $logo_url, $language_count, $login_url, $signup_url, $redirect_url_on_login, $my_account_page;
        //global variables
        $def_currency = apply_filters( 'trav_def_currency', isset( $trav_options['def_currency'] ) ? $trav_options['def_currency'] : 'usd' );
        $search_max_rooms = 30;
        $search_max_cabins = 30;
        $search_max_adults = 30;
        $search_max_kids = 10;
        $search_max_passengers = 10;
        $logo_url = '';
        if ( ! empty( $trav_options['logo'] ) && ! empty( $trav_options['logo']['url'] ) ) {
            $logo_url = $trav_options['logo']['url'];
        } else {
            $logo_url = TRAV_IMAGE_URL . '/logo.png';
        }

        $language_count = 1;
        // wpml variables
        if ( defined('ICL_LANGUAGE_CODE') ) {
            $languages = icl_get_languages('skip_missing=1');
            $language_count = count( $languages );
        }
        $login_url = '';
        $signup_url = '';
        $redirect_url_on_login = '';
        if ( ! empty( $trav_options['redirect_page'] ) ) {
            $redirect_url_on_login = trav_get_permalink_clang( $trav_options['redirect_page'] );
        } else {
            $redirect_url_on_login = trav_get_current_page_url();
        }

        if ( ! empty( $trav_options['modal_login'] ) ) {
            $login_url = '#travelo-login';
            $signup_url = '#travelo-signup';
        } else {
            if ( ! empty( $trav_options['login_page'] ) ) {
                $login_url = trav_get_permalink_clang( $trav_options['login_page'] );
                $signup_url = add_query_arg( 'action', 'register', trav_get_permalink_clang( $trav_options['login_page'] ) );
            } else {
                $login_url = wp_login_url( $redirect_url_on_login );
                $signup_url = wp_registration_url();
            }
        }

        $my_account_page = '';
        if ( ! empty( $trav_options['dashboard_page'] ) ) {
            if ( is_user_logged_in() ) {
                $my_account_page = trav_get_permalink_clang( $trav_options['dashboard_page'] );
            } else {
                $my_account_page = $login_url;
            }
        }

        if ( ! empty( $trav_options['vld_credit_card'] ) ) {
            add_action( 'trav_booking_form_after', 'trav_credit_cart_form' );
        }
        if ( ! empty( $trav_options['vld_captcha'] ) ) {
            add_action( 'trav_booking_form_after', 'trav_captcha_form' );
        }
        if ( ! empty( $trav_options['terms_page'] ) ) {
            add_action( 'trav_booking_form_after', 'trav_terms_form' );
        }
        add_filter( 'trav_booking_button_text', 'trav_booking_button_text' );
        add_action( 'redux/travelo/panel/before', 'trav_one_click_install_main_pages' );
    }
}

/*
 * functions after theme setup
 */
if ( ! function_exists( 'trav_after_setup_theme' ) ) {
    function trav_after_setup_theme() {
        add_role( 'trav_busowner', 'Business Owner' );
        $role = get_role('trav_busowner');
        $role->add_cap('read');
        $role->add_cap('upload_files');
        $role->add_cap('edit_posts');
    }
}

/*
 * functions when theme activation
 */
if ( ! function_exists( 'trav_after_switch_theme' ) ) {
    function trav_after_switch_theme() {
        if ( ! wp_next_scheduled('trav_hourly_cron') ) {
            wp_schedule_event( time(), 'hourly', 'trav_hourly_cron' );
            wp_schedule_event( time(), 'twicedaily', 'trav_twicedaily_cron' );
            wp_schedule_event( time(), 'daily', 'trav_daily_cron' );
        }
    }
}

/*
 * functions when theme deactivation
 */
if ( ! function_exists( 'trav_switch_theme' ) ) {
    function trav_switch_theme() {
        wp_clear_scheduled_hook( 'trav_hourly_cron' );
        wp_clear_scheduled_hook( 'trav_twicedaily_cron' );
        wp_clear_scheduled_hook( 'trav_daily_cron' );
    }
}

/*
 * function to make bussiness manager to see their own property only
 */
if ( ! function_exists( 'trav_posts_for_current_author' ) ) {
    function trav_posts_for_current_author($query) {
        // if ( $query->is_admin && ! current_user_can( 'delete_others_pages' ) ) {
        if ( $query->is_admin && ! current_user_can( 'manage_options' ) && $query->get('post_type') != "post" ) {
            global $user_ID;
            $query->set('author',  $user_ID);
        }
        return $query;
    }
}

/*
 * function to register required plugins
 */
if ( ! function_exists( 'trav_register_required_plugins' ) ) {
    function trav_register_required_plugins() {
        $plugins = array(
            array(
                'name'               => 'Revolution Slider',
                'slug'               => 'revslider',
                'source'             => 'https://c-themes.com/plugins/revslider.zip',
                'required'           => true,
                'version'            => '5.4.8',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
                'image_url'          => TRAV_TEMPLATE_DIRECTORY_URI . '/images/admin/plugins/revolution_slider.jpg'
            ),
            array(
                'name'               => 'WPBakery Page Builder',
                'slug'               => 'js_composer',
                'source'             => 'https://c-themes.com/plugins/js_composer.zip',
                'required'           => true,
                'version'            => '5.5.2',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
                'image_url'          => TRAV_TEMPLATE_DIRECTORY_URI . '/images/admin/plugins/wpbakery_page_builder.jpg'
            ),
            array(
                'name'               => 'MailChimp for WordPress',
                'slug'               => 'mailchimp-for-wp',
                'required'           => true,
                'image_url'          => TRAV_TEMPLATE_DIRECTORY_URI . '/images/admin/plugins/mailchimp-for-wp.jpg'
            ),
            array(
                'name'               => 'Contact Form 7',
                'slug'               => 'contact-form-7',
                'required'           => true,
                'image_url'          => TRAV_TEMPLATE_DIRECTORY_URI . '/images/admin/plugins/contact_form_7.jpg'
            ),
        );

        $config = array(
            'default_path' => '',                      // Default absolute path to pre-packaged plugins.
            'menu'         => 'install-required-plugins', // Menu slug.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            'strings'      => array(
                'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
                'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
                'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
                'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
                'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
                'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
                'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
                'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
                'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
                'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
                'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
                'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
                'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
                'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
                'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
                'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
            )
        );

        tgmpa( $plugins, $config );

    }
}

/*
 * wp_title filter
 */
if ( ! function_exists( 'trav_wp_title' ) ) {
    function trav_wp_title( $title, $sep ) {
        if ( is_feed() ) {
            return $title;
        }

        if ( is_page_template( 'templates/template-login.php' ) ) {
            if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'register' ) ) {
                $title = __( 'Registration Form', 'trav' );
            } else if ( isset( $_GET['action'] ) && ( $_GET['action'] == 'lostpassword' ) ) {
                $title = __( 'Lost Password', 'trav' );
            } else {
                $title = __( 'Login', 'trav' );
            }
            $title .= ' - ';
        }

        if ( get_query_var('paged') ) {
            $paged = get_query_var('paged');
        } elseif ( get_query_var('page') ) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        // Add the blog name
        $title .= get_bloginfo( 'name', 'display' );

        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) ) {
            $title .= " $sep $site_description";
        }

        // Add a page number if necessary:
        if ( ( $paged >= 2 ) && ! is_404() ) {
            $title .= " $sep " . sprintf( __( 'Page %s', 'trav' ), $paged );
        }

        return $title;
    }
}

/*
 * enqueue script function
 */
if ( ! function_exists( 'trav_enqueue_scripts' ) ) {
    function trav_enqueue_scripts() {

        global $logo_url, $trav_options;
        $skin = trav_get_current_skin();
        $custom_css = trav_get_custom_css();

        wp_register_style( 'trav_style_font_googleapis', '//fonts.googleapis.com/css?family=Lato:300,400,700,900' );
        wp_register_style( 'trav_style_animate', TRAV_TEMPLATE_DIRECTORY_URI . '/css/animate' . (WP_DEBUG?'':'.min') . '.css' );
        wp_register_style( 'trav_style_font_awesome', TRAV_TEMPLATE_DIRECTORY_URI . '/css/font-awesome' . (WP_DEBUG?'':'.min') . '.css' );
        wp_register_style( 'trav_style_bootstrap', TRAV_TEMPLATE_DIRECTORY_URI . '/css/bootstrap' . (WP_DEBUG?'':'.min') . '.css' );
        wp_register_style( 'trav_style_main_style', TRAV_TEMPLATE_DIRECTORY_URI . '/css/' . $skin . (WP_DEBUG?'':'.min') . '.css' );
        wp_register_style( 'trav_style_responsive', TRAV_TEMPLATE_DIRECTORY_URI . '/css/responsive' . (WP_DEBUG?'':'.min') . '.css' );
        wp_register_style( 'trav_style_custom', TRAV_TEMPLATE_DIRECTORY_URI . '/css/custom.css' );
        wp_register_style( 'trav_style_ie', TRAV_TEMPLATE_DIRECTORY_URI . '/css/ie' . (WP_DEBUG?'':'.min') . '.css' );
        wp_register_style( 'trav_style_flexslider', TRAV_TEMPLATE_DIRECTORY_URI . '/js/components/flexslider/flexslider.css' );
        wp_register_style( 'trav_style_bxslider', TRAV_TEMPLATE_DIRECTORY_URI . '/js/components/jquery.bxslider/jquery.bxslider.css' );
        wp_register_style( 'trav_style_timepicker', TRAV_TEMPLATE_DIRECTORY_URI . '/js/components/jquery.timepicker/jquery.timepicker.css' );
        wp_register_style( 'trav_style_woocommerce', TRAV_TEMPLATE_DIRECTORY_URI . '/css/woocommerce.css' );
        wp_register_style( 'trav_admin_bar_style', TRAV_TEMPLATE_DIRECTORY_URI . '/css/admin_bar.css' );
        wp_register_style( 'trav_child_theme_css', get_stylesheet_directory_uri() . '/style.css' ); //register default style.css file. only include in childthemes. has no purpose in main theme

        wp_enqueue_style( 'trav_style_font_googleapis');
        wp_enqueue_style( 'trav_style_animate');
        wp_enqueue_style( 'trav_style_font_awesome');
        wp_enqueue_style( 'trav_style_bootstrap');
        wp_enqueue_style( 'trav_style_flexslider' );
        wp_enqueue_style( 'trav_style_bxslider' );
        wp_enqueue_style( 'trav_style_timepicker' );
        wp_enqueue_style( 'trav_style_main_style');
        wp_enqueue_style( 'trav_style_custom');
        wp_enqueue_style( 'trav_style_responsive');

        // rtl css
        if ( is_rtl() ) {
            wp_enqueue_style( 'trav_rtl_bootstrap',  TRAV_TEMPLATE_DIRECTORY_URI . "/css/rtl/bootstrap-rtl.min.css" );
            wp_enqueue_style( 'trav_rtl_jqueryui',  TRAV_TEMPLATE_DIRECTORY_URI . '/css/rtl/jquery-no-theme-rtl' . (WP_DEBUG?'':'.min') . '.css' );
            wp_enqueue_style( 'trav_rtl',  TRAV_TEMPLATE_DIRECTORY_URI . '/css/rtl/rtl' . (WP_DEBUG?'':'.min') . '.css' );
        }

        // woocommerce css
        if ( class_exists( 'WooCommerce' ) ) { 
            wp_enqueue_style( 'trav_style_woocommerce');
        }

        if ( current_user_can( 'edit_theme_options' ) ) { 
            wp_enqueue_style( 'trav_admin_bar_style');
        }

        // child theme css
        if ( get_stylesheet_directory_uri() != TRAV_TEMPLATE_DIRECTORY_URI ) {
            wp_enqueue_style( 'trav_child_theme_css');
        }

        // custom css
        wp_add_inline_style( 'trav_style_custom', $custom_css );


        // wp_register_script( 'trav_jquery', TRAV_TEMPLATE_DIRECTORY_URI . '/js/jquery-2.0.2.min.js', array(), '2.0.2', false );
        wp_register_script( 'trav_script_gmap3', TRAV_TEMPLATE_DIRECTORY_URI . '/js/gmap3.min.js', array( 'jquery' ), '3.0', true);
        wp_register_script( 'trav_script_jquery_ui', TRAV_TEMPLATE_DIRECTORY_URI . '/js/jquery-ui.min.js', array( 'jquery' ), '', true );

        $current_locale = get_locale();
        $current_locale = str_replace( '_', '-', $current_locale);
        if ( substr( $current_locale, 0, 2 ) != 'en' ) { 
            $file_name = '/inc/lib/meta-box/js/jqueryui/datepicker-i18n/jquery.ui.datepicker-' . $current_locale . '.js';
            if ( $file_exists = file_exists( get_template_directory() . $file_name ) ) { 
                wp_register_script( 'trav_script_localization', TRAV_TEMPLATE_DIRECTORY_URI . $file_name, array('jquery'), '', true );
            }
            if ( !$file_exists ) { 
                $file_name = '/inc/lib/meta-box/js/jqueryui/datepicker-i18n/jquery.ui.datepicker-' . substr( $current_locale, 0, 2 ) . '.js';
                if ( $file_exists = file_exists( get_template_directory() . $file_name ) ) { 
                    wp_register_script( 'trav_script_localization', TRAV_TEMPLATE_DIRECTORY_URI . $file_name, array('jquery'), '', true );
                }
            }
        }

        wp_register_script( 'trav_script_bootstrap', TRAV_TEMPLATE_DIRECTORY_URI . '/js/bootstrap.min.js', array( 'jquery' ), '3.0', true );
        wp_register_script( 'trav_script_bxslider', TRAV_TEMPLATE_DIRECTORY_URI . '/js/components/jquery.bxslider/jquery.bxslider.min.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_flex_slider', TRAV_TEMPLATE_DIRECTORY_URI . '/js/components/flexslider/jquery.flexslider-min.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_timepicker', TRAV_TEMPLATE_DIRECTORY_URI . '/js/components/jquery.timepicker/jquery.timepicker.js', array( 'jquery' ), '', true );


        if ( ! empty( $trav_options['map_api_key'] ) ) { 
            wp_register_script( 'trav_script_google_map', '//maps.googleapis.com/maps/api/js?key=' . $trav_options['map_api_key'], array(), '3.0', true );
        } else { 
            wp_register_script( 'trav_script_google_map', '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3.0', true );
        }

        wp_register_script( 'trav_script_jquery_validate', TRAV_TEMPLATE_DIRECTORY_URI . '/js/jquery.validate.min.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_plugin', TRAV_TEMPLATE_DIRECTORY_URI . '/js/plugin.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_page_loading', TRAV_TEMPLATE_DIRECTORY_URI . '/js/page-loading.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_main_script', TRAV_TEMPLATE_DIRECTORY_URI . '/js/theme-scripts.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_accommodation', TRAV_TEMPLATE_DIRECTORY_URI . '/js/accommodation.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_tour', TRAV_TEMPLATE_DIRECTORY_URI . '/js/tour.js', array( 'jquery' ), '', true );
		wp_register_script( 'trav_script_car', TRAV_TEMPLATE_DIRECTORY_URI . '/js/car.js', array( 'jquery' ), '', true );
		wp_register_script( 'trav_script_cruise', TRAV_TEMPLATE_DIRECTORY_URI . '/js/cruise.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_map', TRAV_TEMPLATE_DIRECTORY_URI . '/js/map.js', array( 'jquery' ), '', true );
        wp_register_script( 'trav_script_map_infobox', TRAV_TEMPLATE_DIRECTORY_URI . '/js/gmap3.infobox.js', array( 'jquery' ), '', true );
        wp_localize_script( 'trav_script_map', 'theme_url', TRAV_TEMPLATE_DIRECTORY_URI );
        wp_localize_script( 'trav_script_map', 'button_text', __( 'Details', 'trav' ) );

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'trav_script_plugin' );

        if ( ! empty( $trav_options['pace_loading'] ) ) {
            wp_localize_script( 'trav_script_page_loading', 'js_vars', array( 
                'logo_url' => esc_js( $logo_url ),
                'site_title' => esc_js( get_bloginfo( 'name' ) ),
                'loading_text' => esc_js( __( 'Loading...', 'trav' ) ),
            ) );

            wp_enqueue_script( 'trav_script_page_loading' );
        }

        wp_enqueue_script( 'trav_script_jquery_ui' );
        if ( substr( $current_locale, 0, 2 ) != 'en' && $file_exists) { 
            wp_enqueue_script( 'trav_script_localization' );
        }
        
        wp_enqueue_script( 'trav_script_bootstrap' );
        wp_enqueue_script( 'trav_script_bxslider' );
        wp_enqueue_script( 'trav_script_flex_slider' );
        wp_enqueue_script( 'trav_script_timepicker' );
        wp_enqueue_script( 'trav_script_jquery_validate' );
        wp_localize_script( 'trav_script_main_script', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
        wp_localize_script( 'trav_script_main_script', 'themeurl', TRAV_TEMPLATE_DIRECTORY_URI );
        wp_localize_script( 'trav_script_main_script', 'date_format', trav_get_date_format('js') );
        $sticky_menu = ( ! empty( $trav_options['sticky_menu'] ) ) ? '1' : '0';
        wp_localize_script( 'trav_script_main_script', 'settings', array( 'sticky_menu' => $sticky_menu ) );
        wp_enqueue_script( 'trav_script_main_script' );
        wp_enqueue_script( 'trav_script_google_map' );
        wp_enqueue_script( 'trav_script_gmap3' );
        wp_enqueue_script( 'trav_script_map_infobox' );
        wp_enqueue_script( 'trav_script_map' );

        if ( is_singular() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        if ( 'dev' == TRAV_MODE ) {
            wp_register_script( 'trav_style_changer', TRAV_TEMPLATE_DIRECTORY_URI . '/js/style-changer.js', array( 'jquery' ), '', true );
            wp_enqueue_script( 'trav_style_changer' );
        }

        if ( is_singular( 'accommodation' ) ) {
            //acc_data = array('location', 'ajax_url', 'acc_id', 'minimum_stay', 'review_labels');
            $acc_data = trav_get_acc_js_data();
            wp_localize_script( 'trav_script_accommodation', 'acc_data', $acc_data );
            wp_enqueue_script( 'trav_script_accommodation' );
            wp_enqueue_script( 'trav_script_calendar' );
        }

        if ( is_singular('tour' ) ) {
            $tour_data = trav_get_tour_js_data();
            wp_localize_script( 'trav_script_tour', 'tour_data', $tour_data );
            wp_enqueue_script( 'trav_script_tour' );
        }

		if ( is_singular('car' ) ) {
			$car_data = trav_get_car_js_data();
			wp_localize_script( 'trav_script_car', 'car_data', $car_data );
			wp_enqueue_script( 'trav_script_car' );
		}

		if ( is_singular('cruise' ) ) {
			$cruise_data = trav_get_cruise_js_data();
			wp_localize_script( 'trav_script_cruise', 'cruise_data', $cruise_data );
			wp_enqueue_script( 'trav_script_cruise' );
		}
    }
}

/*
 * template selector function : change default template hierarchy
 */
if ( ! function_exists( 'trav_template_chooser' ) ) {
    function trav_template_chooser($template) {
        $post_type = get_query_var('post_type');
        if ( is_search() && $post_type == 'accommodation' ) {
            // return locate_template( 'templates/accommodation/search-accommodation.php' );
            return locate_template( 'archive-accommodation.php' );
        } elseif ( is_search() && $post_type == 'tour' ) {
            // return locate_template( 'templates/tour/search-tour.php' );
            return locate_template( 'archive-tour.php' );
		} elseif ( is_search() && $post_type == 'car' ) {
			return locate_template( 'archive-car.php' );
		} elseif ( is_search() && $post_type == 'cruise' ) {
			return locate_template( 'archive-cruise.php' );
        }
        return $template;
    }
}

/*
 * template locate and include function
 */
if ( ! function_exists( 'trav_get_template' ) ) {
    function trav_get_template( $template_name, $template_path = '' ) {
        $template = locate_template(
            array(
                trailingslashit( $template_path ) . $template_name,
                $template_name
            )
        );
        include( $template );
    }
}

/*
 * Use this function to remove unnecessary queries from search
 */
if ( ! function_exists( 'trav_posts_request_filter' ) ) {
    function trav_posts_request_filter( $input ) {
        global $wp_query;
        if ( $wp_query->is_main_query() && is_search() && $wp_query->get( 'post_type' ) == 'accommodation' ) {
            //return false;
        }
        return $input;
    }
}

/*
 * get post reviews from post_id
 */
if ( ! function_exists( 'trav_get_reviews' ) ) {
    function trav_get_reviews( $post_id, $start_num=0, $per_page=10 ) {
        global $wpdb;

        $post_id = trav_post_org_id( $post_id );
        //$start_num = ( $page_num -1 ) * $per_page;
        $sql = "SELECT * FROM " . TRAV_REVIEWS_TABLE . " WHERE post_id='" . esc_sql( $post_id ) . "' AND status='1' ORDER BY id DESC LIMIT " . esc_sql( $start_num ) . ", " . esc_sql( $per_page );
        $results = $wpdb->get_results( $sql, ARRAY_A );

        return $results;
    }
}

/*
 * get post review html from post_id
 */
if ( ! function_exists( 'trav_get_review_html' ) ) {
    function trav_get_review_html( $post_id, $start_num=0, $per_page=10 ) {
        $reviews = trav_get_reviews( $post_id, $start_num, $per_page );

        if ( ! empty( $reviews ) ) {

            foreach ( $reviews as $review ) {
                $default = "";
                $photo = trav_get_avatar( array( 'id' => $review['user_id'], 'email' => $review['reviewer_email'], 'size' => 74 ) );
                ?>

                <div class="guest-review table-wrapper">
                    <div class="col-xs-3 col-md-2 author-section table-cell">
                        <a href="#"><?php echo wp_kses_post( $photo ) ?></a>
                        <p class="name"><?php echo esc_html( $review['reviewer_name'] );?></p>
                        <p class="date"><?php echo date( "M, d, Y",trav_strtotime( $review['date'] ) );?></p>
                    </div>
                    <div class="col-xs-9 col-md-10 table-cell comment-container">
                        <div class="comment-header clearfix">
                            <h4 class="comment-title"><?php echo esc_html( stripslashes( $review['review_title'] ) ) ?></h4>
                            <div class="review-score">
                                <div class="five-stars-container"><div class="five-stars" style="width: <?php echo esc_attr( $review['review_rating'] / 5 * 100 ); ?>%;"></div></div>
                                <span class="score"><?php echo esc_html( number_format((float)$review['review_rating'], 1, '.', '') ); ?>/5.0</span>
                            </div>
                        </div>
                        <div class="comment-content">
                            <p><?php echo esc_html( stripslashes( $review['review_text'] ) ) ?></p>
                        </div>
                    </div>
                </div>

                <?php
            }
        }
        
        return count( $reviews );
    }
}

/*
 * get post reviews number from post_id
 */
if ( ! function_exists( 'trav_get_review_count' ) ) {
    function trav_get_review_count( $post_id ) {
        global $wpdb;

        $post_id = trav_post_org_id( $post_id );
        $sql = "SELECT count(*) FROM " . TRAV_REVIEWS_TABLE . " WHERE post_id='" . esc_sql( $post_id ) . "' AND status='1'";
        $result = $wpdb->get_var( $sql );
        
        return $result;
    }
}

/*
 * get current page url
 */
if ( ! function_exists( 'trav_get_current_page_url' ) ) {
    function trav_get_current_page_url() {
        global $wp;
        return home_url(add_query_arg(array(),$wp->request));
    }
}

/*
 * business owner user registration
 */
if ( ! function_exists( 'trav_user_register' ) ) {
    function trav_user_register( $user_id, $password="", $meta=array() ) {
        if ( ! empty( $_POST['user_role'] ) && ( $_POST['user_role'] == 'business_owner') ) {
            $userdata = array();
            $userdata['ID'] = $user_id;
            $userdata['role'] = 'trav_busowner';
            wp_update_user($userdata);
        }
    }
}
/*
 * login failed function
 */
if ( ! function_exists( 'trav_login_failed' ) ) {
    function trav_login_failed( $user ) {
        global $trav_options;

        if ( ! empty( $trav_options['login_page'] ) ) {
            wp_redirect( add_query_arg( array( 'login' => 'failed', 'user' => $user ), trav_get_permalink_clang( $trav_options['login_page'] ) ) );
            exit();
        }
    }
}

/*
 * Authentication function
 */
if ( ! function_exists( 'trav_authenticate' ) ) {
    function trav_authenticate( $user, $username, $password ){
        global $trav_options;

        if ( ! empty( $trav_options['login_page'] ) && ( empty( $username ) || empty( $password ) ) && empty( $_GET['no_redirect'] ) ) {
            wp_redirect( add_query_arg( $_GET, trav_get_permalink_clang( $trav_options['login_page'] ) ) );
            exit;
        }
    }
}

/*
 * Redirect users to "SignUp" page when they try to original Register form.
 */
if ( ! function_exists( 'trav_register_form' ) ) {
    function trav_register_form(){
        global $trav_options;

        $http_post = ( 'POST' == $_SERVER['REQUEST_METHOD'] );

        if ( !$http_post ) { 
            $signup_url = add_query_arg( 'action', 'register', trav_get_permalink_clang( $trav_options['login_page'] ) );
            wp_redirect( $signup_url );
            exit;
        } else { 
            $error_code = '';
            $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : '';
            $user_email = isset( $_POST['user_email'] ) ? $_POST['user_email'] : '';

            $sanitized_user_login = sanitize_user( $user_login );

            $user_email = apply_filters( 'user_registration_email', $user_email );

            // Check the username
            if ( $sanitized_user_login == '' ) {
                $error_code = 'empty_username';
            } elseif ( ! ( $sanitized_user_login == $user_login && ! empty( $sanitized_user_login ) ) ) {
                $error_code = 'invalid_username';
            } elseif ( username_exists( $sanitized_user_login ) ) {
                $error_code = 'username_exists';
            } else {
                /** This filter is documented in wp-includes/user.php */
                $illegal_user_logins = array_map( 'strtolower', (array) apply_filters( 'illegal_user_logins', array() ) );
                if ( in_array( strtolower( $sanitized_user_login ), $illegal_user_logins ) ) {
                    $error_code = 'invalid_username';
                }
            }

            // Check the email address
            if ( $user_email == '' ) {
                $error_code = 'empty_email';
            } elseif ( ! is_email( $user_email ) ) {
                $error_code = 'invalid_email';
            } elseif ( email_exists( $user_email ) ) {
                $error_code = 'email_exists';
            }

            if ( ! empty( $error_code ) ) {
                $signup_url = add_query_arg( 'action', 'register', trav_get_permalink_clang( $trav_options['login_page'] ) );
                $signup_url = add_query_arg( 'error_code', $error_code, $signup_url );

                wp_redirect( $signup_url );
                exit;
            }
        }
    }
}

/*
 * Hide Admin Bar for All Users Except for Administrators function
 */
if ( ! function_exists( 'trav_remove_admin_bar' ) ) {
    function trav_remove_admin_bar() {
        if ( ! current_user_can( 'edit_accommodations' ) && ! is_admin() ) {
            show_admin_bar(false);
        }
    }
}

/*
 * Add new fields to user contact methods area
 */
if ( ! function_exists( 'trav_modify_contact_methods' ) ) {
    function trav_modify_contact_methods($profile_fields) {

        // Add new fields
        $profile_fields['country_code'] = 'Country Code';
        $profile_fields['phone'] = 'Phone Number';
        $profile_fields['birthday'] = 'Date of Birth';
        $profile_fields['address'] = 'Address';
        $profile_fields['city'] = 'City';
        $profile_fields['country'] = 'Country';
        $profile_fields['zip'] = 'Zip Code';
        $profile_fields['author_facebook'] = 'Facebook ';
        $profile_fields['author_twitter'] = 'Twitter';
        $profile_fields['author_linkedin'] = 'LinkedIn';
        $profile_fields['author_dribbble'] = 'Dribbble';
        $profile_fields['author_gplus'] = 'Google+';
        $profile_fields['author_custom'] = 'Custom Message';
        $profile_fields['photo_url'] = 'Custom User Photo Url';

        return $profile_fields;
    }
}

/*
 * Breadcrumbs
 */
if ( ! function_exists( 'trav_breadcrumbs' ) ) {
    function trav_breadcrumbs() {
        global $post;
        if ( is_home() ) {}
        else {
            echo '<ul class="breadcrumbs pull-right">';

            if ( ! is_front_page() ) {
                echo '<li><a href="' . esc_url( home_url() ) . '" title="' . esc_attr__('Home', 'trav') . '">' . esc_html__('Home', 'trav') . '</a></li>';
            }

            if( is_single() ) {
                if ( ( $post->post_type == 'post' ) ) {
                    // default blog post breadcrumb
                    $categories_1 = get_the_category($post->ID);

                    if($categories_1):
                        foreach($categories_1 as $cat_1):
                            $cat_1_ids[] = $cat_1->term_id;
                        endforeach;
                        $cat_1_line = implode(',', $cat_1_ids);
                    endif;

                    $categories = get_categories(array(
                        'include' => $cat_1_line,
                        'orderby' => 'id'
                    ));

                    if ( $categories ) :
                        foreach ( $categories as $cat ) :
                            $cats[] = '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" title="' . esc_html( $cat->name ) . '">' . $cat->name . '</a></li>';
                        endforeach;
                        echo wp_kses_post( join( '', $cats ) );
                    endif;

                    echo '<li class="active">' . esc_html( get_the_title() ) . '</li>';
                } else if ( ( $post->post_type == 'room_type' ) ) {
                    $acc_id = get_post_meta( $post->ID, 'trav_room_accommodation', true );

                    if ( ! empty( $acc_id ) ) { 
                        echo '<li><a href="' . esc_url( get_permalink( $acc_id ) ) . '" title="' . esc_html( get_the_title( $acc_id ) ) .'">' . esc_html( get_the_title( $acc_id ) ) . '</a></li>';
                    }

                    echo '<li class="active">' . esc_html( get_the_title() ) . '</li>';
                } else if ( ( $post->post_type == 'product' ) ) {
                    if ( wc_get_page_id( 'shop' ) ) { 
                        $shop_page_url = wc_get_page_permalink( 'shop' );
                        $shop_page_title = get_the_title( wc_get_page_id( 'shop' ) );

                        echo '<li><a href="' . esc_url( $shop_page_url ) . '" title="' . esc_html( $shop_page_title ) .'">' . esc_html( $shop_page_title ) . '</a></li>';
                    }

                    echo '<li class="active">' . esc_html( get_the_title() ) . '</li>';
                } else {
                    // other single post breadcrumb - accommodation etc
                    echo '<li class="active">' . esc_html( get_the_title() ) . '</li>';
                }
            }

            if ( is_page() && ! is_front_page() ) {
                $parents = array();
                $parent_id = $post->post_parent;

                while ( $parent_id ) :
                    $page = get_page( $parent_id );
                    $parents[] = '<li><a href="' . esc_url( trav_get_permalink_clang( $page->ID ) ) . '" title="' . esc_attr( get_the_title( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a></li>';
                    $parent_id = $page->post_parent;
                endwhile;

                $parents = array_reverse( $parents );

                echo wp_kses_post( join( '', $parents ) );
                echo '<li class="active">' . esc_html( get_the_title() ) . '</li>';
            }

            if ( is_category() ) {
                $category = get_category( get_query_var( 'cat' ) );
                $parents = array();
                $parent_cat = $category;

                while( ! empty( $parent_cat->parent ) ) {
                    $parent_cat = get_category( $parent_cat->parent );
                    $parents[] = '<li><a href="' . esc_url( get_category_link( $parent_cat->cat_ID ) ) . '">' . $parent_cat->cat_name . '</a></li>';
                }

                $parents = array_reverse( $parents );

                echo wp_kses_post( join( '', $parents ) );
                echo '<li class="active">' . esc_html( $category->cat_name ) . '</li>';
            }

            if ( is_tax() ) {
                $taxonomy = get_query_var( 'taxonomy' );
                $term = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
                $parents = array();
                $parent_term = $term;

                while ( ! empty( $parent_term->parent ) ) :
                    $parent_term = get_term( $parent_term->parent, $taxonomy );
                    $parents[] = '<li><a href="' . esc_url( get_term_link( $parent_term->term_id, $taxonomy ) ) . '" title="' . esc_attr( $parent_term->name ) . '">' . esc_html( $parent_term->name ) . '</a></li>';
                endwhile;

                $parents = array_reverse( $parents );
                echo join( '', $parents );

                if ( ! empty( $term->parent ) ) {
                }

                echo '<li class="active">' . esc_html( $term->name ) . '</li>';
            }

            if( is_tag() ){ echo '<li class="active">' . esc_html( single_tag_title( '', FALSE ) ) . '</li>'; }

            if( is_404() ){ echo '<li class="active">' . esc_html__("404 - Page not Found", 'trav') . '</li>'; }

            if ( is_search() ) {
                echo '<li class="active">';
                echo esc_html( get_post_type( $post ) ) . ' ';
                echo esc_html__('SEARCH RESULTS', 'trav');
                echo "</li>";
            }

            if( is_year() ){ echo '<li>' . esc_attr( get_the_time('Y') ) . '</li>'; }

            echo '</ul>';
        }
    }
}

/*
 * get related post
 */
if ( ! function_exists( 'trav_get_related_posts' ) ) {
    function trav_get_related_posts($post_id) {
        $query = new WP_Query();
        $args = '';
        $args = wp_parse_args($args, array(
            'posts_per_page' => -1,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => 0,
            'category__in' => wp_get_post_categories($post_id)
        ));
        $query = new WP_Query($args);
        return $query;
    }
}

/*
 * comment template
 */
if ( ! function_exists( 'trav_comment' ) ) {
    function trav_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment; 
        ?>

        <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
            <div class="the-comment clearfix">
                <div class="avatar">
                    <?php echo trav_get_avatar( array( 'id' => $comment->user_id, 'email' => $comment->comment_author_email, 'size' => 72 ) ); ?>
                </div>

                <div class="comment-box">
                    <div class="comment-author">
                        <?php $comment_reply_link = get_comment_reply_link(array_merge( $args, array('reply_text' => __('REPLY', 'trav'), 'depth' => $depth )));
                        $comment_reply_link = str_replace("class='comment-reply-link", "class='comment-reply-link button btn-mini pull-right", $comment_reply_link);
                        echo ( $comment_reply_link ); ?>
                        <h4 class="box-title"><?php echo get_comment_author_link() ?><small><?php comment_date()?></small></h4>
                    </div>
                    <div class="comment-text">
                        <p><?php comment_text(); ?></p>
                    </div>
                </div>
            </div>
        </li>

        <?php 
    }
}

/*
 * Get Sub String With Specified Word Number
 */
if ( ! function_exists( 'trav_string_limit_words' ) ) {
    function trav_string_limit_words($string, $word_limit) {
        $words = explode(' ', $string, ($word_limit + 1));

        if(count($words) > $word_limit) {
            array_pop($words);
        }

        return implode(' ', $words);
    }
}

/*
 * Save post views to meta field trav_count_post_views;
 */
if ( ! function_exists( 'trav_count_post_views' ) ) {
    function trav_count_post_views() {
        global $post;
        
        if ( is_single() ) {
            $postID = $post->ID;
            $postID = trav_post_org_id( $postID );
            if( ! empty( $postID ) ) {
                $meta_key = 'trav_count_post_views';
                $count = get_post_meta( $postID, $meta_key, true );

                if ( empty( $count ) ) {
                    $count = 0;
                    delete_post_meta( $postID, $meta_key );
                    add_post_meta( $postID, $meta_key, '0' );
                } else {
                    $count++;
                    update_post_meta( $postID, $meta_key, $count );
                }
            }
        }
    }
}

/*
 * add classes to load more button;
 */
if ( ! function_exists( 'trav_next_posts_link_attributes' ) ) {
    function trav_next_posts_link_attributes() {
        return 'class="button btn-large full-width btn-load-more-posts"';
    }
}

/*
 * get all countries
 */
if ( ! function_exists('trav_get_all_countries') ) {
    function trav_get_all_countries() {
        $countries = array(
            array(
                "code"   => "US",
                "name"   => __("United States", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "GB",
                "name"   => __("United Kingdom", "trav"),
                "d_code" => "+44"
            ),
            array(
                "code"   => "CA",
                "name"   => __("Canada", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "AF",
                "name"   => __("Afghanistan", "trav"),
                "d_code" => "+93"
            ),
            array(
                "code"   => "AL",
                "name"   => __("Albania", "trav"),
                "d_code" => "+355"
            ),
            array(
                "code"   => "DZ",
                "name"   => __("Algeria", "trav"),
                "d_code" => "+213"
            ),
            array(
                "code"   => "AS",
                "name"   => __("American Samoa", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "AD",
                "name"   => __("Andorra", "trav"),
                "d_code" => "+376"
            ),
            array(
                "code"   => "AO",
                "name"   => __("Angola", "trav"),
                "d_code" => "+244"
            ),
            array(
                "code"   => "AI",
                "name"   => __("Anguilla", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "AG",
                "name"   => __("Antigua", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "AR",
                "name"   => __("Argentina", "trav"),
                "d_code" => "+54"
            ),
            array(
                "code"   => "AM",
                "name"   => __("Armenia", "trav"),
                "d_code" => "+374"
            ),
            array(
                "code"   => "AW",
                "name"   => __("Aruba", "trav"),
                "d_code" => "+297"
            ),
            array(
                "code"   => "AU",
                "name"   => __("Australia", "trav"),
                "d_code" => "+61"
            ),
            array(
                "code"   => "AT",
                "name"   => __("Austria", "trav"),
                "d_code" => "+43"
            ),
            array(
                "code"   => "AZ",
                "name"   => __("Azerbaijan", "trav"),
                "d_code" => "+994"
            ),
            array(
                "code"   => "BH",
                "name"   => __("Bahrain", "trav"),
                "d_code" => "+973"
            ),
            array(
                "code"   => "BD",
                "name"   => __("Bangladesh", "trav"),
                "d_code" => "+880"
            ),
            array(
                "code"   => "BB",
                "name"   => __("Barbados", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "BY",
                "name"   => __("Belarus", "trav"),
                "d_code" => "+375"
            ),
            array(
                "code"   => "BE",
                "name"   => __("Belgium", "trav"),
                "d_code" => "+32"
            ),
            array(
                "code"   => "BZ",
                "name"   => __("Belize", "trav"),
                "d_code" => "+501"
            ),
            array(
                "code"   => "BJ",
                "name"   => __("Benin", "trav"),
                "d_code" => "+229"
            ),
            array(
                "code"   => "BM",
                "name"   => __("Bermuda", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "BT",
                "name"   => __("Bhutan", "trav"),
                "d_code" => "+975"
            ),
            array(
                "code"   => "BO",
                "name"   => __("Bolivia", "trav"),
                "d_code" => "+591"
            ),
            array(
                "code"   => "BA",
                "name"   => __("Bosnia and Herzegovina", "trav"),
                "d_code" => "+387"
            ),
            array(
                "code"   => "BW",
                "name"   => __("Botswana", "trav"),
                "d_code" => "+267"
            ),
            array(
                "code"   => "BR",
                "name"   => __("Brazil", "trav"),
                "d_code" => "+55"
            ),
            array(
                "code"   => "IO",
                "name"   => __("British Indian Ocean Territory", "trav"),
                "d_code" => "+246"
            ),
            array(
                "code"   => "VG",
                "name"   => __("British Virgin Islands", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "BN",
                "name"   => __("Brunei", "trav"),
                "d_code" => "+673"
            ),
            array(
                "code"   => "BG",
                "name"   => __("Bulgaria", "trav"),
                "d_code" => "+359"
            ),
            array(
                "code"   => "BF",
                "name"   => __("Burkina Faso", "trav"),
                "d_code" => "+226"
            ),
            array(
                "code"   => "MM",
                "name"   => __("Burma Myanmar" , "trav"),
                "d_code" => "+95"
            ),
            array(
                "code"   => "BI",
                "name"   => __("Burundi", "trav"),
                "d_code" => "+257"
            ),
            array(
                "code"   => "KH",
                "name"   => __("Cambodia", "trav"),
                "d_code" => "+855"
            ),
            array(
                "code"   => "CM",
                "name"   => __("Cameroon", "trav"),
                "d_code" => "+237"
            ),
            array(
                "code"   => "CV",
                "name"   => __("Cape Verde", "trav"),
                "d_code" => "+238"
            ),
            array(
                "code"   => "KY",
                "name"   => __("Cayman Islands", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "CF",
                "name"   => __("Central African Republic", "trav"),
                "d_code" => "+236"
            ),
            array(
                "code"   => "TD",
                "name"   => __("Chad", "trav"),
                "d_code" => "+235"
            ),
            array(
                "code"   => "CL",
                "name"   => __("Chile", "trav"),
                "d_code" => "+56"
            ),
            array(
                "code"   => "CN",
                "name"   => __("China", "trav"),
                "d_code" => "+86"
            ),
            array(
                "code"   => "CO",
                "name"   => __("Colombia", "trav"),
                "d_code" => "+57"
            ),
            array(
                "code"   => "KM",
                "name"   => __("Comoros", "trav"),
                "d_code" => "+269"
            ),
            array(
                "code"   => "CK",
                "name"   => __("Cook Islands", "trav"),
                "d_code" => "+682"
            ),
            array(
                "code"   => "CR",
                "name"   => __("Costa Rica", "trav"),
                "d_code" => "+506"
            ),
            array(
                "code"   => "CI",
                "name"   => __("Cote d'Ivoire" , "trav"),
                "d_code" => "+225"
            ),
            array(
                "code"   => "HR",
                "name"   => __("Croatia", "trav"),
                "d_code" => "+385"
            ),
            array(
                "code"   => "CU",
                "name"   => __("Cuba", "trav"),
                "d_code" => "+53"
            ),
            array(
                "code"   => "CY",
                "name"   => __("Cyprus", "trav"),
                "d_code" => "+357"
            ),
            array(
                "code"   => "CZ",
                "name"   => __("Czech Republic", "trav"),
                "d_code" => "+420"
            ),
            array(
                "code"   => "CD",
                "name"   => __("Democratic Republic of Congo", "trav"),
                "d_code" => "+243"
            ),
            array(
                "code"   => "DK",
                "name"   => __("Denmark", "trav"),
                "d_code" => "+45"
            ),
            array(
                "code"   => "DJ",
                "name"   => __("Djibouti", "trav"),
                "d_code" => "+253"
            ),
            array(
                "code"   => "DM",
                "name"   => __("Dominica", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "DO",
                "name"   => __("Dominican Republic", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "EC",
                "name"   => __("Ecuador", "trav"),
                "d_code" => "+593"
            ),
            array(
                "code"   => "EG",
                "name"   => __("Egypt", "trav"),
                "d_code" => "+20"
            ),
            array(
                "code"   => "SV",
                "name"   => __("El Salvador", "trav"),
                "d_code" => "+503"
            ),
            array(
                "code"   => "GQ",
                "name"   => __("Equatorial Guinea", "trav"),
                "d_code" => "+240"
            ),
            array(
                "code"   => "ER",
                "name"   => __("Eritrea", "trav"),
                "d_code" => "+291"
            ),
            array(
                "code"   => "EE",
                "name"   => __("Estonia", "trav"),
                "d_code" => "+372"
            ),
            array(
                "code"   => "ET",
                "name"   => __("Ethiopia", "trav"),
                "d_code" => "+251"
            ),
            array(
                "code"   => "FK",
                "name"   => __("Falkland Islands", "trav"),
                "d_code" => "+500"
            ),
            array(
                "code"   => "FO",
                "name"   => __("Faroe Islands", "trav"),
                "d_code" => "+298"
            ),
            array(
                "code"   => "FM",
                "name"   => __("Federated States of Micronesia", "trav"),
                "d_code" => "+691"
            ),
            array(
                "code"   => "FJ",
                "name"   => __("Fiji", "trav"),
                "d_code" => "+679"
            ),
            array(
                "code"   => "FI",
                "name"   => __("Finland", "trav"),
                "d_code" => "+358"
            ),
            array(
                "code"   => "FR",
                "name"   => __("France", "trav"),
                "d_code" => "+33"
            ),
            array(
                "code"   => "GF",
                "name"   => __("French Guiana", "trav"),
                "d_code" => "+594"
            ),
            array(
                "code"   => "PF",
                "name"   => __("French Polynesia", "trav"),
                "d_code" => "+689"
            ),
            array(
                "code"   => "GA",
                "name"   => __("Gabon", "trav"),
                "d_code" => "+241"
            ),
            array(
                "code"   => "GE",
                "name"   => __("Georgia", "trav"),
                "d_code" => "+995"
            ),
            array(
                "code"   => "DE",
                "name"   => __("Germany", "trav"),
                "d_code" => "+49"
            ),
            array(
                "code"   => "GH",
                "name"   => __("Ghana", "trav"),
                "d_code" => "+233"
            ),
            array(
                "code"   => "GI",
                "name"   => __("Gibraltar", "trav"),
                "d_code" => "+350"
            ),
            array(
                "code"   => "GR",
                "name"   => __("Greece", "trav"),
                "d_code" => "+30"
            ),
            array(
                "code"   => "GL",
                "name"   => __("Greenland", "trav"),
                "d_code" => "+299"
            ),
            array(
                "code"   => "GD",
                "name"   => __("Grenada", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "GP",
                "name"   => __("Guadeloupe", "trav"),
                "d_code" => "+590"
            ),
            array(
                "code"   => "GU",
                "name"   => __("Guam", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "GT",
                "name"   => __("Guatemala", "trav"),
                "d_code" => "+502"
            ),
            array(
                "code"   => "GN",
                "name"   => __("Guinea", "trav"),
                "d_code" => "+224"
            ),
            array(
                "code"   => "GW",
                "name"   => __("Guinea-Bissau", "trav"),
                "d_code" => "+245"
            ),
            array(
                "code"   => "GY",
                "name"   => __("Guyana", "trav"),
                "d_code" => "+592"
            ),
            array(
                "code"   => "HT",
                "name"   => __("Haiti", "trav"),
                "d_code" => "+509"
            ),
            array(
                "code"   => "HN",
                "name"   => __("Honduras", "trav"),
                "d_code" => "+504"
            ),
            array(
                "code"   => "HK",
                "name"   => __("Hong Kong", "trav"),
                "d_code" => "+852"
            ),
            array(
                "code"   => "HU",
                "name"   => __("Hungary", "trav"),
                "d_code" => "+36"
            ),
            array(
                "code"   => "IS",
                "name"   => __("Iceland", "trav"),
                "d_code" => "+354"
            ),
            array(
                "code"   => "IN",
                "name"   => __("India", "trav"),
                "d_code" => "+91"
            ),
            array(
                "code"   => "ID",
                "name"   => __("Indonesia", "trav"),
                "d_code" => "+62"
            ),
            array(
                "code"   => "IR",
                "name"   => __("Iran", "trav"),
                "d_code" => "+98"
            ),
            array(
                "code"   => "IQ",
                "name"   => __("Iraq", "trav"),
                "d_code" => "+964"
            ),
            array(
                "code"   => "IE",
                "name"   => __("Ireland", "trav"),
                "d_code" => "+353"
            ),
            array(
                "code"   => "IL",
                "name"   => __("Israel", "trav"),
                "d_code" => "+972"
            ),
            array(
                "code"   => "IT",
                "name"   => __("Italy", "trav"),
                "d_code" => "+39"
            ),
            array(
                "code"   => "JM",
                "name"   => __("Jamaica", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "JP",
                "name"   => __("Japan", "trav"),
                "d_code" => "+81"
            ),
            array(
                "code"   => "JO",
                "name"   => __("Jordan", "trav"),
                "d_code" => "+962"
            ),
            array(
                "code"   => "KZ",
                "name"   => __("Kazakhstan", "trav"),
                "d_code" => "+7"
            ),
            array(
                "code"   => "KE",
                "name"   => __("Kenya", "trav"),
                "d_code" => "+254"
            ),
            array(
                "code"   => "KI",
                "name"   => __("Kiribati", "trav"),
                "d_code" => "+686"
            ),
            array(
                "code"   => "XK",
                "name"   => __("Kosovo", "trav"),
                "d_code" => "+381"
            ),
            array(
                "code"   => "KW",
                "name"   => __("Kuwait", "trav"),
                "d_code" => "+965"
            ),
            array(
                "code"   => "KG",
                "name"   => __("Kyrgyzstan", "trav"),
                "d_code" => "+996"
            ),
            array(
                "code"   => "LA",
                "name"   => __("Laos", "trav"),
                "d_code" => "+856"
            ),
            array(
                "code"   => "LV",
                "name"   => __("Latvia", "trav"),
                "d_code" => "+371"
            ),
            array(
                "code"   => "LB",
                "name"   => __("Lebanon", "trav"),
                "d_code" => "+961"
            ),
            array(
                "code"   => "LS",
                "name"   => __("Lesotho", "trav"),
                "d_code" => "+266"
            ),
            array(
                "code"   => "LR",
                "name"   => __("Liberia", "trav"),
                "d_code" => "+231"
            ),
            array(
                "code"   => "LY",
                "name"   => __("Libya", "trav"),
                "d_code" => "+218"
            ),
            array(
                "code"   => "LI",
                "name"   => __("Liechtenstein", "trav"),
                "d_code" => "+423"
            ),
            array(
                "code"   => "LT",
                "name"   => __("Lithuania", "trav"),
                "d_code" => "+370"
            ),
            array(
                "code"   => "LU",
                "name"   => __("Luxembourg", "trav"),
                "d_code" => "+352"
            ),
            array(
                "code"   => "MO",
                "name"   => __("Macau", "trav"),
                "d_code" => "+853"
            ),
            array(
                "code"   => "MK",
                "name"   => __("Macedonia", "trav"),
                "d_code" => "+389"
            ),
            array(
                "code"   => "MG",
                "name"   => __("Madagascar", "trav"),
                "d_code" => "+261"
            ),
            array(
                "code"   => "MW",
                "name"   => __("Malawi", "trav"),
                "d_code" => "+265"
            ),
            array(
                "code"   => "MY",
                "name"   => __("Malaysia", "trav"),
                "d_code" => "+60"
            ),
            array(
                "code"   => "MV",
                "name"   => __("Maldives", "trav"),
                "d_code" => "+960"
            ),
            array(
                "code"   => "ML",
                "name"   => __("Mali", "trav"),
                "d_code" => "+223"
            ),
            array(
                "code"   => "MT",
                "name"   => __("Malta", "trav"),
                "d_code" => "+356"
            ),
            array(
                "code"   => "MH",
                "name"   => __("Marshall Islands", "trav"),
                "d_code" => "+692"
            ),
            array(
                "code"   => "MQ",
                "name"   => __("Martinique", "trav"),
                "d_code" => "+596"
            ),
            array(
                "code"   => "MR",
                "name"   => __("Mauritania", "trav"),
                "d_code" => "+222"
            ),
            array(
                "code"   => "MU",
                "name"   => __("Mauritius", "trav"),
                "d_code" => "+230"
            ),
            array(
                "code"   => "YT",
                "name"   => __("Mayotte", "trav"),
                "d_code" => "+262"
            ),
            array(
                "code"   => "MX",
                "name"   => __("Mexico", "trav"),
                "d_code" => "+52"
            ),
            array(
                "code"   => "MD",
                "name"   => __("Moldova", "trav"),
                "d_code" => "+373"
            ),
            array(
                "code"   => "MC",
                "name"   => __("Monaco", "trav"),
                "d_code" => "+377"
            ),
            array(
                "code"   => "MN",
                "name"   => __("Mongolia", "trav"),
                "d_code" => "+976"
            ),
            array(
                "code"   => "ME",
                "name"   => __("Montenegro", "trav"),
                "d_code" => "+382"
            ),
            array(
                "code"   => "MS",
                "name"   => __("Montserrat", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "MA",
                "name"   => __("Morocco", "trav"),
                "d_code" => "+212"
            ),
            array(
                "code"   => "MZ",
                "name"   => __("Mozambique", "trav"),
                "d_code" => "+258"
            ),
            array(
                "code"   => "NA",
                "name"   => __("Namibia", "trav"),
                "d_code" => "+264"
            ),
            array(
                "code"   => "NR",
                "name"   => __("Nauru", "trav"),
                "d_code" => "+674"
            ),
            array(
                "code"   => "NP",
                "name"   => __("Nepal", "trav"),
                "d_code" => "+977"
            ),
            array(
                "code"   => "NL",
                "name"   => __("Netherlands", "trav"),
                "d_code" => "+31"
            ),
            array(
                "code"   => "AN",
                "name"   => __("Netherlands Antilles", "trav"),
                "d_code" => "+599"
            ),
            array(
                "code"   => "NC",
                "name"   => __("New Caledonia", "trav"),
                "d_code" => "+687"
            ),
            array(
                "code"   => "NZ",
                "name"   => __("New Zealand", "trav"),
                "d_code" => "+64"
            ),
            array(
                "code"   => "NI",
                "name"   => __("Nicaragua", "trav"),
                "d_code" => "+505"
            ),
            array(
                "code"   => "NE",
                "name"   => __("Niger", "trav"),
                "d_code" => "+227"
            ),
            array(
                "code"   => "NG",
                "name"   => __("Nigeria", "trav"),
                "d_code" => "+234"
            ),
            array(
                "code"   => "NU",
                "name"   => __("Niue", "trav"),
                "d_code" => "+683"
            ),
            array(
                "code"   => "NF",
                "name"   => __("Norfolk Island", "trav"),
                "d_code" => "+672"
            ),
            array(
                "code"   => "KP",
                "name"   => __("North Korea", "trav"),
                "d_code" => "+850"
            ),
            array(
                "code"   => "MP",
                "name"   => __("Northern Mariana Islands", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "NO",
                "name"   => __("Norway", "trav"),
                "d_code" => "+47"
            ),
            array(
                "code"   => "OM",
                "name"   => __("Oman", "trav"),
                "d_code" => "+968"
            ),
            array(
                "code"   => "PK",
                "name"   => __("Pakistan", "trav"),
                "d_code" => "+92"
            ),
            array(
                "code"   => "PW",
                "name"   => __("Palau", "trav"),
                "d_code" => "+680"
            ),
            array(
                "code"   => "PS",
                "name"   => __("Palestine", "trav"),
                "d_code" => "+970"
            ),
            array(
                "code"   => "PA",
                "name"   => __("Panama", "trav"),
                "d_code" => "+507"
            ),
            array(
                "code"   => "PG",
                "name"   => __("Papua New Guinea", "trav"),
                "d_code" => "+675"
            ),
            array(
                "code"   => "PY",
                "name"   => __("Paraguay", "trav"),
                "d_code" => "+595"
            ),
            array(
                "code"   => "PE",
                "name"   => __("Peru", "trav"),
                "d_code" => "+51"
            ),
            array(
                "code"   => "PH",
                "name"   => __("Philippines", "trav"),
                "d_code" => "+63"
            ),
            array(
                "code"   => "PL",
                "name"   => __("Poland", "trav"),
                "d_code" => "+48"
            ),
            array(
                "code"   => "PT",
                "name"   => __("Portugal", "trav"),
                "d_code" => "+351"
            ),
            array(
                "code"   => "PR",
                "name"   => __("Puerto Rico", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "QA",
                "name"   => __("Qatar", "trav"),
                "d_code" => "+974"
            ),
            array(
                "code"   => "CG",
                "name"   => __("Republic of the Congo", "trav"),
                "d_code" => "+242"
            ),
            array(
                "code"   => "RE",
                "name"   => __("Reunion" , "trav"),
                "d_code" => "+262"
            ),
            array(
                "code"   => "RO",
                "name"   => __("Romania", "trav"),
                "d_code" => "+40"
            ),
            array(
                "code"   => "RU",
                "name"   => __("Russia", "trav"),
                "d_code" => "+7"
            ),
            array(
                "code"   => "RW",
                "name"   => __("Rwanda", "trav"),
                "d_code" => "+250"
            ),
            array(
                "code"   => "BL",
                "name"   => __("Saint Barthelemy" , "trav"),
                "d_code" => "+590"
            ),
            array(
                "code"   => "SH",
                "name"   => __("Saint Helena", "trav"),
                "d_code" => "+290"
            ),
            array(
                "code"   => "KN",
                "name"   => __("Saint Kitts and Nevis", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "MF",
                "name"   => __("Saint Martin", "trav"),
                "d_code" => "+590"
            ),
            array(
                "code"   => "PM",
                "name"   => __("Saint Pierre and Miquelon", "trav"),
                "d_code" => "+508"
            ),
            array(
                "code"   => "VC",
                "name"   => __("Saint Vincent and the Grenadines", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "WS",
                "name"   => __("Samoa", "trav"),
                "d_code" => "+685"
            ),
            array(
                "code"   => "SM",
                "name"   => __("San Marino", "trav"),
                "d_code" => "+378"
            ),
            array(
                "code"   => "ST",
                "name"   => __("Sao Tome and Principe" , "trav"),
                "d_code" => "+239"
            ),
            array(
                "code"   => "SA",
                "name"   => __("Saudi Arabia", "trav"),
                "d_code" => "+966"
            ),
            array(
                "code"   => "SN",
                "name"   => __("Senegal", "trav"),
                "d_code" => "+221"
            ),
            array(
                "code"   => "RS",
                "name"   => __("Serbia", "trav"),
                "d_code" => "+381"
            ),
            array(
                "code"   => "SC",
                "name"   => __("Seychelles", "trav"),
                "d_code" => "+248"
            ),
            array(
                "code"   => "SL",
                "name"   => __("Sierra Leone", "trav"),
                "d_code" => "+232"
            ),
            array(
                "code"   => "SG",
                "name"   => __("Singapore", "trav"),
                "d_code" => "+65"
            ),
            array(
                "code"   => "SK",
                "name"   => __("Slovakia", "trav"),
                "d_code" => "+421"
            ),
            array(
                "code"   => "SI",
                "name"   => __("Slovenia", "trav"),
                "d_code" => "+386"
            ),
            array(
                "code"   => "SB",
                "name"   => __("Solomon Islands", "trav"),
                "d_code" => "+677"
            ),
            array(
                "code"   => "SO",
                "name"   => __("Somalia", "trav"),
                "d_code" => "+252"
            ),
            array(
                "code"   => "ZA",
                "name"   => __("South Africa", "trav"),
                "d_code" => "+27"
            ),
            array(
                "code"   => "KR",
                "name"   => __("South Korea", "trav"),
                "d_code" => "+82"
            ),
            array(
                "code"   => "ES",
                "name"   => __("Spain", "trav"),
                "d_code" => "+34"
            ),
            array(
                "code"   => "LK",
                "name"   => __("Sri Lanka", "trav"),
                "d_code" => "+94"
            ),
            array(
                "code"   => "LC",
                "name"   => __("St. Lucia", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "SD",
                "name"   => __("Sudan", "trav"),
                "d_code" => "+249"
            ),
            array(
                "code"   => "SR",
                "name"   => __("Suriname", "trav"),
                "d_code" => "+597"
            ),
            array(
                "code"   => "SZ",
                "name"   => __("Swaziland", "trav"),
                "d_code" => "+268"
            ),
            array(
                "code"   => "SE",
                "name"   => __("Sweden", "trav"),
                "d_code" => "+46"
            ),
            array(
                "code"   => "CH",
                "name"   => __("Switzerland", "trav"),
                "d_code" => "+41"
            ),
            array(
                "code"   => "SY",
                "name"   => __("Syria", "trav"),
                "d_code" => "+963"
            ),
            array(
                "code"   => "TW",
                "name"   => __("Taiwan", "trav"),
                "d_code" => "+886"
            ),
            array(
                "code"   => "TJ",
                "name"   => __("Tajikistan", "trav"),
                "d_code" => "+992"
            ),
            array(
                "code"   => "TZ",
                "name"   => __("Tanzania", "trav"),
                "d_code" => "+255"
            ),
            array(
                "code"   => "TH",
                "name"   => __("Thailand", "trav"),
                "d_code" => "+66"
            ),
            array(
                "code"   => "BS",
                "name"   => __("The Bahamas", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "GM",
                "name"   => __("The Gambia", "trav"),
                "d_code" => "+220"
            ),
            array(
                "code"   => "TL",
                "name"   => __("Timor-Leste", "trav"),
                "d_code" => "+670"
            ),
            array(
                "code"   => "TG",
                "name"   => __("Togo", "trav"),
                "d_code" => "+228"
            ),
            array(
                "code"   => "TK",
                "name"   => __("Tokelau", "trav"),
                "d_code" => "+690"
            ),
            array(
                "code"   => "TO",
                "name"   => __("Tonga", "trav"),
                "d_code" => "+676"
            ),
            array(
                "code"   => "TT",
                "name"   => __("Trinidad and Tobago", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "TN",
                "name"   => __("Tunisia", "trav"),
                "d_code" => "+216"
            ),
            array(
                "code"   => "TR",
                "name"   => __("Turkey", "trav"),
                "d_code" => "+90"
            ),
            array(
                "code"   => "TM",
                "name"   => __("Turkmenistan", "trav"),
                "d_code" => "+993"
            ),
            array(
                "code"   => "TC",
                "name"   => __("Turks and Caicos Islands", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "TV",
                "name"   => __("Tuvalu", "trav"),
                "d_code" => "+688"
            ),
            array(
                "code"   => "UG",
                "name"   => __("Uganda", "trav"),
                "d_code" => "+256"
            ),
            array(
                "code"   => "UA",
                "name"   => __("Ukraine", "trav"),
                "d_code" => "+380"
            ),
            array(
                "code"   => "AE",
                "name"   => __("United Arab Emirates", "trav"),
                "d_code" => "+971"
            ),
            array(
                "code"   => "UY",
                "name"   => __("Uruguay", "trav"),
                "d_code" => "+598"
            ),
            array(
                "code"   => "VI",
                "name"   => __("US Virgin Islands", "trav"),
                "d_code" => "+1"
            ),
            array(
                "code"   => "UZ",
                "name"   => __("Uzbekistan", "trav"),
                "d_code" => "+998"
            ),
            array(
                "code"   => "VU",
                "name"   => __("Vanuatu", "trav"),
                "d_code" => "+678"
            ),
            array(
                "code"   => "VA",
                "name"   => __("Vatican City", "trav"),
                "d_code" => "+39"
            ),
            array(
                "code"   => "VE",
                "name"   => __("Venezuela", "trav"),
                "d_code" => "+58"
            ),
            array(
                "code"   => "VN",
                "name"   => __("Vietnam", "trav"),
                "d_code" => "+84"
            ),
            array(
                "code"   => "WF",
                "name"   => __("Wallis and Futuna", "trav"),
                "d_code" => "+681"
            ),
            array(
                "code"   => "YE",
                "name"   => __("Yemen", "trav"),
                "d_code" => "+967"
            ),
            array(
                "code"   => "ZM",
                "name"   => __("Zambia", "trav"),
                "d_code" => "+260"
            ),
            array(
                "code"   => "ZW",
                "name"   => __("Zimbabwe", "trav"),
                "d_code" => "+263"
            ),
        );

        return $countries;
    }
}

/*
 * credit card validation. if you use cc merchant account service you can write your validation code here.
 */
if ( ! function_exists('trav_cc_validation') ) {
    function trav_cc_validation( $cc_type, $cc_holder_name, $cc_number, $cc_exp_month, $cc_exp_year ) {
        return true;
    }
}

/*
 * send mail with icalendar functions
 */
if ( ! function_exists('trav_send_ical_event') ) {
    function trav_send_ical_event( $from_name, $from_address, $to_name, $to_address, $startTime, $endTime, $subject, $description, $location) {
        $domain = $from_name;
        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());

        $headers = "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
        
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<body>\n";
        $message .= $description;
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";

        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
    /*        'BEGIN:VTIMEZONE' . "\r\n" .
        'TZID:Eastern Time' . "\r\n" .
        'BEGIN:STANDARD' . "\r\n" .
        'DTSTART:20091101T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
        'TZOFFSETFROM:-0400' . "\r\n" .
        'TZOFFSETTO:-0500' . "\r\n" .
        'TZNAME:EST' . "\r\n" .
        'END:STANDARD' . "\r\n" .
        'BEGIN:DAYLIGHT' . "\r\n" .
        'DTSTART:20090301T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
        'TZOFFSETFROM:-0500' . "\r\n" .
        'TZOFFSETTO:-0400' . "\r\n" .
        'TZNAME:EDST' . "\r\n" .
        'END:DAYLIGHT' . "\r\n" .
        'END:VTIMEZONE' . "\r\n" .*/
        'BEGIN:VEVENT' . "\r\n" .
        'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
        'ATTENDEE;CN="'.$to_name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
        'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
        'UID:'.date("Ymd\TGis",trav_strtotime($startTime)).rand()."@".$domain."\r\n" .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="Eastern Time":'.date("Ymd\THis",trav_strtotime($startTime)). "\r\n" .
        'DTEND;TZID="Eastern Time":'.date("Ymd\THis",trav_strtotime($endTime)). "\r\n" .
        'TRANSP:OPAQUE'. "\r\n" .
        'SEQUENCE:1'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . $location . "\r\n" .
        'CLASS:PUBLIC'. "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST\n';
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;

        $mailsent = wp_mail( $to_address, $subject, $message, $headers );
        return ($mailsent)?(true):(false);
    }
}

/*
 * send mail functions
 */
if ( ! function_exists('trav_send_mail') ) {
    function trav_send_mail( $from_name, $from_address, $to_address, $subject, $description ) {
        //Create Email Headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        // $headers = "From: $from_address\n";
        $headers .= "Content-Type: text/html\n";
        $headers .= "X-TRAV-Content-Type: text/html\n";
        $headers .= "charset=UTF-8\n";
        $headers .= "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";

        $message = "<html>\n";
        $message .= "<body>\n";
        $message .= $description;
        $message .= "</body>\n";
        $message .= "</html>\n";

        $mailsent = wp_mail( $to_address, $subject, $message, $headers );

        return $mailsent;
    }
}

/*
 * time elapsed function
 */
if ( ! function_exists('trav_time_elapsed_string') ) {
    function trav_time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => __( 'year', 'trav'),
            'm' => __( 'month', 'trav'),
            'w' => __( 'week', 'trav'),
            'd' => __( 'day', 'trav'),
            'h' => __( 'hour', 'trav'),
            'i' => __( 'minute', 'trav'),
            's' => __( 'second', 'trav'),
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ' . __( 'ago', 'trav') : __( 'just now', 'trav');
    }
}

/*
 * handle search with empty string
 */
if ( ! function_exists( 'trav_pre_get_posts' ) ) {
    function trav_pre_get_posts( $query ) {
        // If 's' request variable is set but empty
        if ( isset($_GET['s']) && empty($_GET['s']) && $query->is_main_query() ){
            $query->is_search = true;
            $query->is_home = false;
        }

        if ( $query->is_search ) {
            $post_type = $query->get('post_type');
            if ( empty( $post_type ) ) {
                $query->set( 'post_type', array('post') );
            }
        }

        if ( $query->is_tax ) {
            $queries = $query->tax_query->queries;
            if ( ! empty( $queries ) && ( $queries[0]['taxonomy'] == 'location' ) ) {
                $query->set( 'post_type', array( 'things_to_do' ) );
            }
        }
        return $query;
    }
}

/*
 * disable comments for several post types
 */
if ( ! function_exists('trav_disable_comments') ) {
    function trav_disable_comments( $open, $post_id ) {
        $post = get_post( $post_id );
        if( $post->post_type == 'attachment' ) {
            return false;
        }
        return $open;
    }
}

/*
 * get total post count in taxonomy including child
 */
if ( ! function_exists('trav_count_posts_in_taxonomy') ) {
    function trav_count_posts_in_taxonomy( $term_id, $taxonomy ) {
        $term_obj = get_term( $term_id, $taxonomy );
        $count = $term_obj->count;

        $child_terms = get_terms( $taxonomy, array( 'parent' => $term_id ) );
        if ( ! empty( $child_terms ) && ! is_wp_error( $child_terms ) ){
            foreach ( $child_terms as $child_term ) {
                $count += trav_count_posts_in_taxonomy( $child_term->term_id, $taxonomy );
            }
        }

        // get Accommodations count
        $args = array(
            'post_type'     => 'accommodation',
            'meta_key'      => 'trav_accommodation_city',
            'meta_value'    => $term_id,
        );
        $acc_query = new WP_Query( $args );

        if ( $acc_query->have_posts() ) { 
            $count += $acc_query->post_count;
        }
        wp_reset_postdata();

        // get Tours count
        $args = array(
            'post_type'     => 'tour',
            'meta_key'      => 'trav_tour_city',
            'meta_value'    => $term_id,
        );
        $tour_query = new WP_Query( $args );

        if ( $tour_query->have_posts() ) { 
            $count += $tour_query->post_count;
        }
        wp_reset_postdata();

        return $count;
    }
}

/*
 * get avatar function
 */
if ( ! function_exists('trav_get_avatar') ) {
    function trav_get_avatar( $user_data ) {
        $size = empty($user_data['size'])?96:$user_data['size'];
        $photo = '';
        if ( ! empty( $user_data['id'] ) ) {
            $photo_url = get_user_meta( $user_data['id'], 'photo_url', true );
            if ( ! empty( $photo_url ) ) {
                $photo = '<img width="' . $size . '" height="' . $size . '" alt="avatar" src="' . $photo_url . '">';
            }
        }
        if ( empty( $photo ) ) {
            $photo = trav_get_default_avatar( $user_data['email'], $size );
        }
        return wp_kses_post( $photo );
    }
}

/*
 * check if gravatar exists function
 */
if ( ! function_exists('trav_get_default_avatar') ) {
    function trav_get_default_avatar( $email, $size ) {
        $hash = md5(strtolower(trim($email)));
        $uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
        $headers = @get_headers($uri);
        if ( ! preg_match( "|200|", $headers[0] ) ) {
            $photo = '<img width="' . $size . '" height="' . $size . '" alt="avatar" src="' . TRAV_IMAGE_URL . '/avatar.jpg' . '">';
            return $photo;
        }

        return get_avatar( $email, $size );
    }
}

/*
 * get post gallery function
 */
if ( ! function_exists('trav_post_gallery') ) {
    function trav_post_gallery( $post_id ) {
        global $sld_with_cl_id;

        $isv_setting = get_post_meta( $post_id, 'trav_post_media_type', true );
        if ( empty( $isv_setting ) || $isv_setting == 'img' ) {
            if ( '' == get_the_post_thumbnail() ) {
                $isv_setting = '';
                return false;
            } else {
                echo '<figure class="image-container">';
                echo get_the_post_thumbnail( $post_id );
                echo '</figure>';
            }
        } elseif ( $isv_setting == 'sld' ) {
            $gallery_imgs = get_post_meta( $post_id, 'trav_gallery_imgs' );
            $nav_class = '';
            $direction_nav = get_post_meta( $post_id, 'trav_post_direction_nav', true );

            if ( empty( $direction_nav ) ) $nav_class = ' no-navigation';

            if ( empty( $gallery_imgs ) ) {
                $isv_setting = '';

                return false;
            } else {
                $img_empty = true;

                foreach ( $gallery_imgs as $gallery_img ) {
                    $src = wp_get_attachment_image_src( $gallery_img, 'full' );
                    if ( !empty( $src ) ) {
                        $img_empty = false;
                        break;
                    }
                }

                if ( $img_empty ) {
                    $isv_setting = '';

                    return false;
                }

                $gallery_type = get_post_meta( $post_id, 'trav_post_gallery_type', true );

                if ( $gallery_type == 'sld_2' ) { ?>

                    <div class="flexslider photo-gallery style4<?php echo esc_attr( $nav_class ) ?>">
                        <ul class="slides">
                            <?php foreach ( $gallery_imgs as $gallery_img ) {
                                echo '<li>' . wp_get_attachment_image( $gallery_img, 'full' ) . '</li>';
                            } ?>
                        </ul>
                    </div>

                <?php } elseif ( $gallery_type == 'sld_with_cl' ) { ?>
                    <?php if ( empty( $sld_with_cl_id ) ) { $sld_with_cl_id = 0; } $sld_with_cl_id++; ?>
                    <div class="flexslider photo-gallery style1 0000<?php echo esc_attr( $nav_class ) ?>" id="post-slideshow<?php echo esc_attr( $sld_with_cl_id ) ?>" data-sync="#post-carousel<?php echo esc_attr( $sld_with_cl_id ) ?>">
                        <amp-carousel id="carouselWithPreview" width="400" height="300" layout="responsive" type="slides" on="slideChange:carouselWithPreviewSelector.toggle(index=event.index, value=true)">
                            <?php foreach ( $gallery_imgs as $gallery_img ) {   
                            $image_attributes = wp_get_attachment_image_src( $gallery_img, 'full' );
                                if($image_attributes){
                                $amp_image = '<amp-img width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" layout="responsive" alt="a sample image" src="'.$image_attributes[0].'"  ></amp-img>';
                                echo  $amp_image;
                                }
                            } ?>
                        </amp-carousel>
                    </div>
                    <div class="flexslider image-carousel style1" id="post-carousel<?php echo esc_attr( $sld_with_cl_id ) ?>"  data-animation="slide" data-item-width="70" data-item-margin="10" data-sync="#post-slideshow<?php echo esc_attr( $sld_with_cl_id ) ?>">
                        <amp-selector id="carouselWithPreviewSelector" class="carousel-preview"
                            on="select:carouselWithPreview.goToSlide(index=event.targetOption)"
                            layout="container">
                            <?php 
                            $i = 0;
                            foreach ( $gallery_imgs as $gallery_img ) {
                                $sm_image_attributes = wp_get_attachment_image_src( $gallery_img, 'thumbnail' );
                                if($sm_image_attributes){
                                    $thumb_images = '<amp-img option="'.$i.'"
                                    '.(($i==0)?'selected': '').'
                                    src="'.$sm_image_attributes[0].'"
                                    width="70"
                                    height="70"
                                    alt="a sample image"></amp-img>';
                                    echo $thumb_images;
                                }
                                $i++;
                            } ?>
                        </amp-selector>
                    </div>
                <?php } else { ?>
                    <div class="flexslider photo-gallery style3<?php echo esc_attr( $nav_class ) ?>">
                        <amp-carousel width="400" height="300" layout="responsive" type="slides">
                            <?php foreach ( $gallery_imgs as $gallery_img ) {   
                            $image_attributes = wp_get_attachment_image_src( $gallery_img, 'full' );
                                if($image_attributes){
                                $amp_image = '<amp-img width="'.$image_attributes[1].'" height="'.$image_attributes[2].'" layout="responsive" alt="a sample image" src="'.$image_attributes[0].'"  ></amp-img>';
                                echo  $amp_image;
                                }
                            } ?>
                        </amp-carousel>
                    </div>
                <?php }
            }
        } elseif ( $isv_setting == 'video' ) {
            $video_code = get_post_meta( $post_id, 'trav_post_video', true );
            $video_width = get_post_meta( $post_id, 'trav_post_video_width', true );
            
            if ( empty( $video_code ) ) {
                $isv_setting = '';
            } else { ?>
                <div class="video-container">
                    <div <?php if ( ! empty( $video_width ) ) echo 'class="full-video"' ?>>
                        <?php echo do_shortcode( $video_code ); ?>
                    </div>
                </div>
            <?php }
        }
    }
}

/*
 * Single Post Block HTML
 */
if ( ! function_exists( 'trav_get_post_list_sigle' ) ) {
    function trav_get_post_list_sigle( $post_id, $list_style, $before_article='', $after_article='', $animation='' ) {
        $post_id = trav_get_current_language_post_id( $post_id );
        echo wp_kses_post( $before_article );
        $brief = apply_filters('the_content', get_post_field('post_content', $post_id));
        $brief = wp_trim_words( $brief, 20, '' );
        $post = get_post( $post_id );
        setup_postdata( $post );

        if ( $list_style == "style1" || $list_style == "style2" ) { ?>
            <article class="box post">
                <?php if ( '' != get_the_post_thumbnail( $post_id ) ) { ?>
                    <figure<?php echo wp_kses_post( $animation ) ?>>
                        <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" data-post_id="<?php echo esc_attr( $post_id ) ?>" class="hover-effect"><?php echo get_the_post_thumbnail( $post_id, 'gallery-thumb' );  ?></a>
                        <figcaption class="entry-date"><label class="date"><?php echo get_the_date( 'd' , $post_id ); ?></label><label class="month"><?php echo get_the_date( 'M' , $post_id ); ?></label></figcaption>
                    </figure>
                <?php } ?>
                <div class="details">
                    <?php if ( $list_style == "style1" ) { ?>

                        <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="button"><?php echo esc_html__( 'MORE', 'trav' ) ?></a>
                        <h4 class="post-title entry-title"><?php echo wp_kses_post( get_the_title( $post_id ) ); ?></h4>
                        <div><?php echo wp_kses_post( $brief ); ?></div>
                        <div class="post-meta single-line-meta vcard">
                            <?php echo __( 'By','trav' ) ?> <span class="fn"><?php the_author_posts_link(); ?></span><span class="sep">|</span>
                            <a href="<?php echo esc_url( get_comments_link( $post_id ) ); ?>" class="comment"><?php comments_number();?></a>
                        </div>

                    <?php } elseif ( $list_style == "style2" ) { ?>

                        <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="button"><?php echo __( 'MORE', 'trav' ) ?></a>
                        <h4 class="post-title entry-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h4>
                        <div class="post-meta single-line-meta vcard">
                            <?php echo __( 'By','trav' ) ?> <span class="fn"><?php the_author_posts_link(); ?></span><span class="sep">|</span>
                            <a href="<?php echo esc_url( get_comments_link( $post_id ) ); ?>" class="comment"><?php comments_number();?></a>
                        </div>

                    <?php } ?>
                </div>
            </article>

        <?php } elseif ( $list_style == "style3" ) {
            trav_get_template( 'loop-blog.php', '/templates' );
        }
        wp_reset_postdata();
        echo wp_kses_post( $after_article );
    }
}

/*
 * Read more button
 */
if ( ! function_exists( 'trav_modify_read_more_link' ) ) {
    function trav_modify_read_more_link() {
        return '<a class="more-link" href="' . esc_url( get_permalink() ) . '">' . __( 'Continue reading', 'trav' ) . '</a>';
    }
}

/*
 * Comment fields
 */
if ( ! function_exists( 'trav_comment_form_default_fields' ) ) {
    function trav_comment_form_default_fields( $fields ) {
        return $fields;
    }
}
if ( ! function_exists( 'trav_comment_form_before_fields' ) ) {
    function trav_comment_form_before_fields( $fields ) {
        echo '<div class="form-group row">';
    }
}
if ( ! function_exists( 'trav_comment_form_after_fields' ) ) {
    function trav_comment_form_after_fields( $fields ) {
        echo '</div>';
    }
}

/*
 * get site date format
 */
if ( ! function_exists( 'trav_get_date_format' ) ) {
    function trav_get_date_format( $language='' ) {
        global $trav_options;
        if ( isset( $trav_options['date_format'] ) ) {
            if ( $language == 'php' ) {
                switch ( $trav_options['date_format'] ) {
                    case 'dd/mm/yy':
                        return 'd/m/Y';
                        break;
                    case 'yy-mm-dd':
                        return 'Y-m-d';
                        break;
                    case 'mm/dd/yy':
                    default:
                        return 'm/d/Y';
                        break;
                }
            } else {
                return $trav_options['date_format'];
            }
        } else {
            if ( $language == 'php' ) {
                return 'm/d/Y';
            } else {
                return 'mm/dd/yy';
            }
        }
    }
}

/*
 * get site date format
 */
if ( ! function_exists( 'trav_sanitize_date' ) ) {
    function trav_sanitize_date( $input_date ) {
        $date_obj = date_create_from_format( trav_get_date_format('php'), $input_date );
        if ( ! $date_obj ) {
            return '';
        }
        return sanitize_text_field( $input_date );
    }
}

/*
 * function to get time
 */
if ( ! function_exists( 'trav_strtotime' ) ) {
    function trav_strtotime( $input_date ) {
        if ( trav_get_date_format('php') == 'd/m/Y' ) {
            $input_date = str_replace( '/', '-', $input_date );
        }
        return strtotime( $input_date);
    }
}

/*
 * function to make it enable d/m/Y strtotime
 */
if ( ! function_exists( 'trav_tophptime' ) ) {
    function trav_tophptime( $input_date ) {
        if ( ! trav_strtotime( $input_date ) ) {
            return '';
        }
        $return_value =  date( trav_get_date_format('php'), trav_strtotime( $input_date ) );
        return $return_value;
    }
}

/*
 * function to make it enable Y-m-d
 */
if ( ! function_exists( 'trav_tosqltime' ) ) {
    function trav_tosqltime( $input_date ) {
        if ( ! trav_strtotime( $input_date ) ) {
            return '';
        }
        $return_value =  date( 'Y-m-d', trav_strtotime( $input_date ) );
        return $return_value;
    }
}

/*
 * redirect home function
 */
if ( ! function_exists( 'trav_redirect_home' ) ) {
    function trav_redirect_home() {
        wp_redirect( home_url() );
        exit;
    }
}

/*
 * update user recent activity
 */
if ( ! function_exists( 'trav_update_user_recent_activity' ) ) {
    function trav_update_user_recent_activity( $post_id ) {
        if ( is_user_logged_in() ) {
            $post_id = trav_post_org_id( $post_id );
            $user_id = get_current_user_id();
            $recent_activity_array = array();
            $recent_activity = get_user_meta( $user_id , 'recent_activity', true );

            if ( ! empty( $recent_activity ) ) {
                $recent_activity_array = unserialize($recent_activity);

                // add current acc id to recent activity
                if ( ( $key = array_search( $post_id, $recent_activity_array ) ) !== false ) {
                    // if already exitst unset it first
                    unset( $recent_activity_array[$key] );
                }
                array_unshift( $recent_activity_array, $post_id );

                // make recent activity size smaller than 10
                $user_activity_maximum_len = 10;
                if ( count( $recent_activity_array ) > $user_activity_maximum_len ) {
                    $temp = array_chunk( $recent_activity_array, $user_activity_maximum_len );
                    $recent_activity_array = $temp[0];
                }
            } else {
                $recent_activity_array = array( $post_id );
            }

            update_user_meta( $user_id, 'recent_activity', serialize( $recent_activity_array ) );
        }
    }
}

/*
 * get day interval
 */
if ( ! function_exists( 'trav_get_day_interval' ) ) {
    function trav_get_day_interval( $date_from, $date_to ) {
        $date_from = new DateTime( '@' . trav_strtotime( $date_from ) );
        $date_to = new DateTime( '@' . trav_strtotime( $date_to ) );
        $interval = $date_from->diff($date_to);
        return $interval->days;
    }
}

/*
 * send notify email to admin if a business owner submit a property for review
 */
if ( ! function_exists( 'trav_notify_admin_for_pending' ) ) {
    function trav_notify_admin_for_pending( $post ) {
        global $trav_options;
        $user_info = get_userdata ($post->post_author);
        $admin_email = get_option('admin_email');
        $strTo = array ( $admin_email );
        $strSubject = $user_info->user_nicename . ' submitted a ' . $post->post_type;
        $strMessage = '"' . $post->post_title . '" by ' . $user_info->user_nicename . ' was submitted a ' . $post->post_type . ' for review at ' . home_url( '?p=' . $post->ID ) . '&preview=true. Please proof.';
        wp_mail( $strTo, $strSubject, $strMessage );
    }
}

/*
 * Get current user info
 */
if ( ! function_exists( 'trav_get_current_user_info' ) ) {
    function trav_get_current_user_info( ) {
        $user_info = array(
            'display_name' => '',
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'country_code' => '',
            'phone' => '',
            'birthday' => '',
            'address' => '',
            'city' => '',
            'zip' => '',
            'country' => '',
            'photo_url' => '',
        );
        if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $user_info['display_name'] = $current_user->user_firstname;
            $user_info['login'] = $current_user->user_login;
            $user_info['first_name'] = $current_user->user_firstname;
            $user_info['last_name'] = $current_user->user_lastname;
            $user_info['email'] = $current_user->user_email;
            $user_info['description'] = $current_user->description;
            $user_info['country_code'] = get_user_meta( $user_id, 'country_code', true );
            $user_info['phone'] = get_user_meta( $user_id, 'phone', true );
            $user_info['birthday'] = get_user_meta( $user_id, 'birthday', true );
            $user_info['address'] = get_user_meta( $user_id, 'address', true );
            $user_info['city'] = get_user_meta( $user_id, 'city', true );
            $user_info['zip'] = get_user_meta( $user_id, 'zip', true );
            $user_info['country'] = get_user_meta( $user_id, 'country', true );
            $user_info['photo_url'] = ( isset( $current_user->photo_url ) && ! empty( $current_user->photo_url ) ) ? $current_user->photo_url : '';
        }
        return $user_info;
    }
}

if ( ! function_exists( 'trav_post_get_location' ) ) {
    function trav_post_get_location( $post_id, $post_type='accommodation', $location_type='city' ) {
        $meta_field = 'trav_' . $post_type . '_' . $location_type;
        $location_id = get_post_meta( $post_id, $meta_field, true );
        if ( ! empty( $location_id ) ) {
            $location = get_term_by( 'id', $location_id, 'location' );
            if ( $location ) { 
                return __( $location->name, 'trav');
            } else {
                return '';
            }
        }
        return '';
    }
}

/* to make property owner can be set as accommodation author */
if ( ! function_exists( 'trav_author_override' ) ) {
    function trav_author_override( $output ) {
        global $post, $user_ID;

        // return if this isn't the theme author override dropdown
        if (!preg_match('/post_author_override/', $output)) return $output;

        // return if we've already replaced the list (end recursion)
        if (preg_match ('/post_author_override_replaced/', $output)) return $output;

        // replacement call to wp_dropdown_users
        $output = wp_dropdown_users(array(
            'echo' => 0,
            'name' => 'post_author_override_replaced',
            'selected' => empty($post->ID) ? $user_ID : $post->post_author,
            'include_selected' => true
        ));

        // put the original name back
        $output = preg_replace('/post_author_override_replaced/', 'post_author_override', $output);

        return $output;
    }
}

/* function to get all modules that enabled */
if ( ! function_exists( 'trav_get_available_modules' ) ) {
    function trav_get_available_modules() {
        global $trav_options;
        $modules = array();
        if ( empty( $trav_options['disable_acc'] ) ) $modules[] = 'accommodation';
        if ( empty( $trav_options['disable_tour'] ) ) $modules[] = 'tour';
		if ( empty( $trav_options['disable_car'] ) ) $modules[] = 'car';
		if ( empty( $trav_options['disable_cruise'] ) ) $modules[] = 'cruise';
        return $modules;
    }
}

/*
 * get user booking list function
 */
if ( ! function_exists( 'trav_get_user_booking_list' ) ) {
    function trav_get_user_booking_list( $user_id, $status = 1, $sortby = 'created', $order='desc' ) {

        global $wpdb, $trav_options;
        $sql = '';
        $order = ( $order == 'desc' ) ? 'desc' : 'asc';
        $order_by = ' ORDER BY ' . esc_sql( $sortby ) . ' ' . $order;
        $where = ' WHERE 1=1';
        $where .= ' AND user_id=' . esc_sql( $user_id );
        if ( $status != -1 ) {
            $where .= ' AND status=' . esc_sql( $status );
        }
        // $sql = sprintf( 'SELECT * FROM ' . TRAV_ACCOMMODATION_BOOKINGS_TABLE . $where . $order_by, $user_id );
        $available_modules = trav_get_available_modules();
        $sqls = array();
        if ( in_array( 'accommodation', $available_modules ) ) {
            $sqls[] = "SELECT 'accommodation' AS post_type, booking_no, pin_code, total_price, created, status, accommodation_id AS post_id, date_from AS event_date, adults, rooms AS tickets FROM " . TRAV_ACCOMMODATION_BOOKINGS_TABLE . $where;
        }
        if ( in_array( 'tour', $available_modules ) ) {
            $sqls[] = "SELECT 'tour' AS post_type, booking_no, pin_code, total_price, created, status, tour_id AS post_id, tour_date AS event_date, adults, NULL AS tickets FROM " . TRAV_TOUR_BOOKINGS_TABLE . $where;
        }
		if ( in_array( 'car', $available_modules ) ) {
			$sqls[] = "SELECT 'car' AS post_type, booking_no, pin_code, total_price, created, status, car_id AS post_id, date_from AS event_date, NULL AS adults, NULL AS tickets FROM " . TRAV_CAR_BOOKINGS_TABLE . $where;
		}
        if ( in_array( 'cruise', $available_modules ) ) {
            $sqls[] = "SELECT 'cruise' AS post_type, booking_no, pin_code, total_price, created, status, cruise_id AS post_id, date_from AS event_date, adults, cabins AS tickets FROM " . TRAV_CRUISE_BOOKINGS_TABLE . $where;
        }
        $sql = implode( ' UNION ALL ', $sqls );
        $sql .= $order_by;
        //return $sql;

        $booking_list = $wpdb->get_results( $sql );

        if ( empty( $booking_list ) ) return __( 'You don\'t have any booked trips yet.', 'trav' ); // if empty return false

        $acc_book_conf_url = trav_acc_get_book_conf_url();
        $tour_book_conf_url = trav_tour_get_book_conf_url();
		$car_book_conf_url = trav_car_get_book_conf_url();
        $cruise_book_conf_url = trav_cruise_get_book_conf_url();

        $html = '';
        foreach ( $booking_list as $booking_data ) {
            $class = '';
            $label = 'UPCOMING';
            if ( $booking_data->status == 0 ) { $class = ' cancelled'; $label = 'CANCELLED'; }
            if ( $booking_data->status == 2 ) { $class = ' completed'; $label = 'COMPLETED'; }
            // if ( ( $booking_data->status == 1 ) && ( trav_strtotime( $booking_data->event_date ) < trav_strtotime(date('Y-m-d')) ) ) { $class = ' completed'; $label = 'COMPLETED'; }
            $html .= '<div class="booking-info clearfix' . $class . '">';
            $html .= '<div class="date">
                            <label class="month">' . date( 'M', trav_strtotime( $booking_data->event_date ) ) . '</label>
                            <label class="date">' . date( 'd', trav_strtotime( $booking_data->event_date ) ) . '</label>
                            <label class="day">' . date( 'D', trav_strtotime( $booking_data->event_date ) ) . '</label>
                        </div>';
            $conf_url = '';
            $icon_class = '';
            if ( 'accommodation' == $booking_data->post_type ) {
                $conf_url = $acc_book_conf_url;
                $icon_class = 'soap-icon-hotel blue-color';
            } elseif ( 'tour' == $booking_data->post_type ) {
                $conf_url = $tour_book_conf_url;
                $icon_class = 'soap-icon-beach yellow-color';
			} elseif ( 'car' == $booking_data->post_type ) {
				$conf_url = $car_book_conf_url;
				$icon_class = 'soap-icon-beach dark-blue-color';
            } elseif ( 'cruise' == $booking_data->post_type ) {
                $conf_url = $cruise_book_conf_url;
                $icon_class = 'soap-icon-beach orange-color';
            }
            $url = empty($conf_url)?'':( add_query_arg( array( 'booking_no' => $booking_data->booking_no, 'pin_code' => $booking_data->pin_code ), $conf_url ) );
            $html .= '<h4 class="box-title">';
            $html .= '<i class="icon circle ' . $icon_class . '"></i>';
            $html .= '<a href="' . esc_url( $url ) . '">' . get_the_title( trav_acc_clang_id( $booking_data->post_id ) ) . '</a>';
			if ( 'car' != $booking_data->post_type ) {
                $html .= '<small>';
            }
            if ( 'accommodation' == $booking_data->post_type ) {
                $html .= $booking_data->tickets . __( 'rooms', 'trav' ) . ' ';
            }
            if ( 'cruise' == $booking_data->post_type ) {
                $html .= $booking_data->tickets . __( 'cabins', 'trav' ) . ' ';
            }
            $html .= $booking_data->adults . __( 'adults', 'trav' );
            if ( 'car' != $booking_data->post_type ) {
                $html .= '</small>';
            }
            $html .= '</h4>';
            $html .= '<button class="btn-mini status">' . __( $label, 'trav' ) . '</button>';
            $html .= '<dl class="info">';
            $html .= '<dt>' . __( 'booked on', 'trav') . '</dt>';
            $html .= '<dd>' . date( 'l, M, j, Y', trav_strtotime( $booking_data->created ) ) . '</dd>';
            $html .= '</dl>';
            $html .= '<dl class="info">';
            $html .= '<dt>' . __( 'BOOKING NO', 'trav' ) . '</dt><dd>' . $booking_data->booking_no . '</dd>';
            $html .= '<dt>' . __( 'PIN CODE', 'trav' ) . '</dt><dd>' . $booking_data->pin_code . '</dd>';
            $html .= '</dl>';
            $html .= '</div>';
        }
        return $html;
    }
}

/*
 * body_class filter
 */
if ( ! function_exists( 'trav_body_class' ) ) {
    function trav_body_class( $classes ) {
        return $classes;
    }
}

/*
 * body_class filter
 */
if ( ! function_exists( 'trav_inline_script' ) ) {
    function trav_inline_script() {
        global $trav_options;
        if ( ! empty( $trav_options['custom_js'] ) ) {
            echo '<script>' . $trav_options['custom_js'] . '</script>';
        }
    }
}

/*
 * get current site skin
 */
if ( ! function_exists( 'trav_get_current_skin' ) ) {
    function trav_get_current_skin() {
        global $trav_options;

        $skin = 'style';
        if ( TRAV_MODE == 'dev' ) {
            if ( isset( $_COOKIE['colorSkin'] ) ) {
                $skin = 'style-' . $_COOKIE['colorSkin'];
            }
        } else {
            if ( ! empty ( $trav_options['skin'] ) ) {
                $skin = $trav_options['skin'];
            }
        }
        
        return $skin;
    }
}

/*
 * get custom css to add inline css
 */
if ( ! function_exists( 'trav_get_custom_css' ) ) {
    function trav_get_custom_css() {
        global $trav_options, $logo_url;
        $custom_css = "";

        // accommodation calendar words
		if ( is_singular( 'accommodation') || is_singular( 'car' ) || is_singular( 'cruise' ) ) {
            $custom_css .= "
                .available a:before, .available span:before {
                    content: '" . __( "AVAILABLE", 'trav' ) . "';
                }
                .date-passed a:before, .date-passed span:before {
                    content: '" . __( "DATE PASSED", 'trav' ) . "';
                }
                .unavailable a:before, .unavailable span:before {
                    content: '" . __( "NOT AVAILABLE", 'trav' ) . "';
                }";
        }

        // logo url
        $custom_css .= "
            #header .logo a, #footer .bottom .logo a, .chaser .logo a, .logo-modal {
                background-image: url(" . esc_url( $logo_url ) . ");
                background-repeat: no-repeat;
                display: block;
            }
            .chaser .logo a {
                background-size: auto 20px;
            }";

        // custom logo height
        $logo_height_header = ! empty( $trav_options['logo_height_header'] ) ? intval( $trav_options['logo_height_header']['height'] ) : 0;
        $logo_height_footer = ! empty( $trav_options['logo_height_footer'] ) ? intval( $trav_options['logo_height_footer']['height'] ) : 0;
        $logo_height_loading = ! empty( $trav_options['logo_height_loading'] ) ? intval( $trav_options['logo_height_loading']['height'] ) : 0;
        $logo_height_404 = ! empty( $trav_options['logo_height_404'] ) ? intval( $trav_options['logo_height_404']['height'] ) : 0;
        $logo_height_chaser = ! empty( $trav_options['logo_height_chaser'] ) ? intval( $trav_options['logo_height_chaser']['height'] ) : 0;

        if ( ! empty( $logo_height_header ) ) {
            $custom_css .= "#page-wrapper #header .logo img { height: " . $trav_options['logo_height_header']['height'] . "; }";
            $custom_css .= "#page-wrapper #header .logo a { background-size: auto " . $trav_options['logo_height_header']['height'] . "; }";
        }
        if ( ! empty( $logo_height_footer ) ) {
            $custom_css .= "#page-wrapper #footer .logo img { height: " . $trav_options['logo_height_footer']['height'] . "; }";
            $custom_css .= "#page-wrapper #footer .logo a { background-size: auto " . $trav_options['logo_height_footer']['height'] . "; }";
        }
        if ( ! empty( $logo_height_loading ) ) {
            $custom_css .= ".loading-page .logo img { height: " . $trav_options['logo_height_loading']['height'] . "; }";
        }
        if ( ! empty( $logo_height_404 ) ) {
            $custom_css .= ".error404 #main .logo img { height: " . $trav_options['logo_height_404']['height'] . "; }";
        }
        if ( ! empty( $logo_height_chaser ) ) {
            $custom_css .= ".chaser .logo img { height: " . $trav_options['logo_height_chaser']['height'] . "; }";
            $custom_css .= ".chaser .logo a { background-size: auto " . $trav_options['logo_height_chaser']['height'] . "; }";
        }

        if ( ! empty( $trav_options['boxed_version'] ) ) {
            $custom_css .= "#page-wrapper { max-width: 1200px; margin: 0 auto; }";
        }

        // site custom css
        if ( ! empty( $trav_options['custom_css'] ) ) {
            $custom_css .= $trav_options['custom_css'];
        }
        if ( TRAV_MODE == 'dev' ) {
            $custom_css .= '
                #header.style1 .logo a {
                    width: 155px; position: relative;
                }
                #header.style1 .logo a:after {
                    position: absolute; display: block; width: 130px; height: 30px; background: url("http://www.soaptheme.net/wordpress/travelo/wp-content/themes/Travelo/images/logo_txt.png") no-repeat; content: ""; top: 0; right: 0;
                }';
        }

        // page custom css
        $custom_css .= get_post_meta( get_queried_object_id(), 'trav_page_custom_css', true );

        return $custom_css;
    }
}

/*
 * booking form button
 */
if ( ! function_exists( 'trav_booking_button_text' ) ) {
    function trav_booking_button_text( $button_text = '' ) {
        global $is_payment_enabled;
        if ( $is_payment_enabled ) {
            if ( trav_is_woo_enabled() ) {
                $button_text = __( 'SUBMIT BOOKING', 'trav');
            } elseif ( trav_is_paypal_enabled() ) {
                $button_text = __( 'CONFIRM AND DEPOSIT VIA PAYPAL', 'trav');
            }
        } else {
            $button_text = __( 'CONFIRM BOOKING', 'trav');
        }
        return $button_text;
    }
}

/*
 * credit card form
 */
if ( ! function_exists( 'trav_credit_cart_form' ) ) {
    function trav_credit_cart_form() {
        trav_get_template( 'credit-card-form.php', '/templates/booking/' );
    }
}

/*
 * captcha form
 */
if ( ! function_exists( 'trav_captcha_form' ) ) {
    function trav_captcha_form() {
        trav_get_template( 'captcha-form.php', '/templates/booking/' );
    }
}

/*
 * captcha form
 */
if ( ! function_exists( 'trav_terms_form' ) ) {
    function trav_terms_form() {
        trav_get_template( 'terms-form.php', '/templates/booking/' );
    }
}

/*
 * one click install main pages
 */
if ( ! function_exists( 'trav_one_click_install_main_pages' ) ) {
    function trav_one_click_install_main_pages() {
        if ( ! empty( $_GET['install_trav_pages'] ) ) {
            global $trav_options;

            $installed = get_option( 'install_trav_pages' );
            if ( empty( $installed ) ) {
                update_option( 'install_trav_pages', 1 );

                if ( empty( $trav_options['dashboard_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Dashboard',
                        'post_type'     => 'page',
                        'post_content'  => '[dashboard]',
                        'post_status'   => 'publish'
                    );

                    $trav_options['dashboard_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['acc_booking_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Accommodation Booking',
                        'post_type'     => 'page',
                        'post_content'  => '[accommodation_booking]',
                        'post_status'   => 'publish'
                    );

                    $trav_options['acc_booking_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['acc_booking_confirmation_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Accommodation Booking Confirmation',
                        'post_type'     => 'page',
                        'post_content'  => '[accommodation_booking_confirmation]',
                        'post_status'   => 'publish'
                    );

                    $trav_options['acc_booking_confirmation_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['tour_booking_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Tour Booking',
                        'post_type'     => 'page',
                        'post_content'  => '[tour_booking]',
                        'post_status'   => 'publish'
                    );

                    $trav_options['tour_booking_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['tour_booking_confirmation_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Tour Booking Confirmation',
                        'post_type'     => 'page',
                        'post_content'  => '[tour_booking_confirmation]',
                        'post_status'   => 'publish'
                    );
                    
                    $trav_options['tour_booking_confirmation_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['car_booking_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Car Booking',
                        'post_type'     => 'page',
                        'post_content'  => '[car_booking]',
                        'post_status'   => 'publish'
                    );

                    $trav_options['car_booking_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['car_booking_confirmation_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Car Booking Confirmation',
                        'post_type'     => 'page',
                        'post_content'  => '[car_booking_confirmation]',
                        'post_status'   => 'publish'
                    );
                    
                    $trav_options['car_booking_confirmation_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['cruise_booking_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Cruise Booking',
                        'post_type'     => 'page',
                        'post_content'  => '[cruise_booking]',
                        'post_status'   => 'publish'
                    );

                    $trav_options['cruise_booking_page'] = '' . wp_insert_post( $postarr );
                }

                if ( empty( $trav_options['cruise_booking_confirmation_page'] ) ) {
                    $postarr = array(
                        'post_title'    => 'Cruise Booking Confirmation',
                        'post_type'     => 'page',
                        'post_content'  => '[cruise_booking_confirmation]',
                        'post_status'   => 'publish'
                    );
                    
                    $trav_options['cruise_booking_confirmation_page'] = '' . wp_insert_post( $postarr );
                }

                update_option( 'travelo', $trav_options );

                wp_redirect( admin_url( 'admin.php?page=theme_options' ) );
                exit;
            }
        }

        // dismiss the notice if skip setup button is clicked
        if ( ! empty( $_GET['skip_trav_pages'] ) ) {
            update_option( 'install_trav_pages', 1 );
        }
    }
}

/*
 * remove redux tools page
 */
if ( ! function_exists( 'trav_remove_redux_menu' ) ) {
    function trav_remove_redux_menu() {
        remove_submenu_page('tools.php','redux-about');
    }
}

/*
 * check if a taxonomy term is in depth 1
 */
if ( ! function_exists( 'trav_check_term_depth_1' ) ) {
    function trav_check_term_depth_1( $t ) {
        return ( $t->parent != 0 ) && ( get_term( $t->parent, $t->taxonomy )->parent == 0 );
    }
}
/*
 * check if a taxonomy term is in depth 2
 */
if ( ! function_exists( 'trav_check_term_depth_2' ) ) {
    function trav_check_term_depth_2( $t ) {
        return ( $t->parent != 0 ) && ( get_term( $t->parent, $t->taxonomy )->parent != 0 );
    }
}
/*
 * get temporary table name
 */
if ( ! function_exists( 'trav_get_temp_table_name' ) ) {
    function trav_get_temp_table_name() {
        $temp_tbl_name = str_replace( ' ', '', 'Search_' . session_id() ); // Replaces all spaces with hyphens.
        return esc_sql( preg_replace('/[^A-Za-z0-9\-]/', '', $temp_tbl_name) ); // Removes special chars.
    }
}

/*
*/
if ( ! function_exists( 'trav_google_map_url' ) ) { 
    function trav_google_map_url( $map_url ) { 
        global $trav_options;

        if ( ! empty( $trav_options['map_api_key'] ) ) { 
            $map_url = '//maps.googleapis.com/maps/api/js?key=' . $trav_options['map_api_key'];
        }
        return $map_url;
    }
}

if ( ! function_exists( 'modify_widget_data_export' ) ) { 
    function modify_widget_data_export( $widget_val, $widget_type ) { 
        $new_widget_val = $widget_val;

        if ( 'nav_menu' == $widget_type ) { 
            foreach ( $widget_val as $widget_key => $widget_value ) {
                if ( is_int( $widget_key ) && array_key_exists( 'nav_menu', $widget_value ) ) { 
                    $menu_object = wp_get_nav_menu_object( $widget_value['nav_menu'] );

                    if ( $menu_object ) { 
                        $new_widget_val[$widget_key]['slug'] = $menu_object->slug;
                    }
                }
            }
        }

        return $new_widget_val;
    }
}