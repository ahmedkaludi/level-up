<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( LEVELUP_INC_DIR . '/frontend/cruise/functions.php');
require_once( LEVELUP_INC_DIR . '/frontend/cruise/templates.php');
require_once( LEVELUP_INC_DIR . '/frontend/cruise/ajax.php');

// ajax
add_action( 'wp_ajax_cruise_get_month_schedules', 'trav_ajax_cruise_get_month_schedules' );
add_action( 'wp_ajax_nopriv_cruise_get_month_schedules', 'trav_ajax_cruise_get_month_schedules' );

add_action( 'wp_ajax_cruise_get_available_cabins', 'trav_ajax_cruise_get_available_cabins' );
add_action( 'wp_ajax_nopriv_cruise_get_available_cabins', 'trav_ajax_cruise_get_available_cabins' );

add_action( 'wp_ajax_cruise_get_more_reviews', 'trav_ajax_cruise_get_more_reviews' );
add_action( 'wp_ajax_nopriv_cruise_get_more_reviews', 'trav_ajax_cruise_get_more_reviews' );

add_action( 'wp_ajax_cruise_submit_review', 'trav_ajax_cruise_submit_review' );
add_action( 'wp_ajax_nopriv_cruise_submit_review', 'trav_ajax_cruise_submit_review' );

add_action( 'wp_ajax_cruise_add_to_wishlist', 'trav_ajax_cruise_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_cruise_add_to_wishlist', 'trav_ajax_cruise_add_to_wishlist' );

add_action( 'wp_ajax_cruise_submit_booking', 'trav_ajax_cruise_submit_booking' );
add_action( 'wp_ajax_nopriv_cruise_submit_booking', 'trav_ajax_cruise_submit_booking' );

add_action( 'wp_ajax_cruise_cancel_booking', 'trav_ajax_cruise_cancel_booking' );
add_action( 'wp_ajax_nopriv_cruise_cancel_booking', 'trav_ajax_cruise_cancel_booking' );

add_action( 'wp_ajax_get_more_cruises', 'trav_ajax_get_more_cruises' );
add_action( 'wp_ajax_nopriv_get_more_cruises', 'trav_ajax_get_more_cruises' );

add_action( 'wp_ajax_cruise_check_cabin_availability', 'trav_ajax_cruise_check_cabin_availability' );
add_action( 'wp_ajax_nopriv_cruise_check_cabin_availability', 'trav_ajax_cruise_check_cabin_availability' );

add_action( 'wp_ajax_cruise_update_booking_date', 'trav_ajax_cruise_update_booking_date' );
add_action( 'wp_ajax_nopriv_cruise_update_booking_date', 'trav_ajax_cruise_update_booking_date' );

add_action( 'wp_ajax_cruise_change_cabin', 'trav_ajax_cruise_change_cabin' );
add_action( 'wp_ajax_nopriv_cruise_change_cabin', 'trav_ajax_cruise_change_cabin' );

// trav actions
add_action( 'trav_cruise_booking_wrong_data', 'trav_redirect_home' );
add_action( 'trav_cruise_conf_wrong_data', 'trav_redirect_home' );
add_action( 'trav_cruise_conf_mail_not_sent', 'trav_cruise_conf_send_mail' );
add_action( 'trav_cruise_deposit_payment_not_paid', 'trav_cruise_deposit_payment_not_paid' );
add_action( 'trav_cruise_booking_before', 'trav_cruise_booking_before' );
