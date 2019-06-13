<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( LEVELUP_INC_DIR . '/frontend/car/functions.php');
require_once( LEVELUP_INC_DIR . '/frontend/car/templates.php');
require_once( LEVELUP_INC_DIR . '/frontend/car/ajax.php');

add_action( 'wp_ajax_car_add_to_wishlist', 'trav_ajax_car_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_car_add_to_wishlist', 'trav_ajax_car_add_to_wishlist' );

add_action( 'wp_ajax_car_check_availability', 'trav_ajax_car_check_availability' );
add_action( 'wp_ajax_nopriv_car_check_availability', 'trav_ajax_car_check_availability' );

add_action( 'wp_ajax_car_submit_booking', 'trav_ajax_car_submit_booking' );
add_action( 'wp_ajax_nopriv_car_submit_booking', 'trav_ajax_car_submit_booking' );

add_action( 'wp_ajax_car_cancel_booking', 'trav_ajax_car_cancel_booking' );
add_action( 'wp_ajax_nopriv_car_cancel_booking', 'trav_ajax_car_cancel_booking' );

add_action( 'wp_ajax_car_update_booking_date', 'trav_ajax_car_update_booking_date' );
add_action( 'wp_ajax_nopriv_car_update_booking_date', 'trav_ajax_car_update_booking_date' );

add_action( 'wp_ajax_car_get_month_car_numbers', 'trav_ajax_car_get_month_car_numbers' );
add_action( 'wp_ajax_nopriv_car_get_month_car_numbers', 'trav_ajax_car_get_month_car_numbers' );

add_action( 'trav_car_booking_before', 'trav_car_booking_before' );
add_action( 'trav_car_booking_wrong_data', 'trav_redirect_home' );
add_action( 'trav_car_conf_wrong_data', 'trav_redirect_home' );
add_action( 'trav_car_deposit_payment_not_paid', 'trav_car_deposit_payment_not_paid' );
add_action( 'trav_car_booking_before', 'trav_car_booking_before' );
add_action( 'trav_car_conf_mail_not_sent', 'trav_car_conf_send_mail' );


define( 'TRAV_CAR_MAINTENANCE_DATES', 0 );