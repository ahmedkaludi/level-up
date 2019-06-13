<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( LEVELUP_INC_DIR . '/frontend/accommodation/functions.php');
require_once( LEVELUP_INC_DIR . '/frontend/accommodation/templates.php');
require_once( LEVELUP_INC_DIR . '/frontend/accommodation/ajax.php');

// ajax
add_action( 'wp_ajax_acc_get_month_vacancies', 'trav_ajax_acc_get_month_vacancies' );
add_action( 'wp_ajax_nopriv_acc_get_month_vacancies', 'trav_ajax_acc_get_month_vacancies' );

add_action( 'wp_ajax_acc_get_available_rooms', 'trav_ajax_acc_get_available_rooms' );
add_action( 'wp_ajax_nopriv_acc_get_available_rooms', 'trav_ajax_acc_get_available_rooms' );

add_action( 'wp_ajax_get_post_gallery', 'trav_ajax_get_post_gallery' );
add_action( 'wp_ajax_nopriv_get_post_gallery', 'trav_ajax_get_post_gallery' );

add_action( 'wp_ajax_acc_get_more_reviews', 'trav_ajax_acc_get_more_reviews' );
add_action( 'wp_ajax_nopriv_acc_get_more_reviews', 'trav_ajax_acc_get_more_reviews' );

add_action( 'wp_ajax_acc_submit_review', 'trav_ajax_acc_submit_review' );
add_action( 'wp_ajax_nopriv_acc_submit_review', 'trav_ajax_acc_submit_review' );

add_action( 'wp_ajax_acc_add_to_wishlist', 'trav_ajax_acc_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_acc_add_to_wishlist', 'trav_ajax_acc_add_to_wishlist' );

add_action( 'wp_ajax_acc_submit_booking', 'trav_ajax_acc_submit_booking' );
add_action( 'wp_ajax_nopriv_acc_submit_booking', 'trav_ajax_acc_submit_booking' );

add_action( 'wp_ajax_acc_cancel_booking', 'trav_ajax_acc_cancel_booking' );
add_action( 'wp_ajax_nopriv_acc_cancel_booking', 'trav_ajax_acc_cancel_booking' );

add_action( 'wp_ajax_get_more_accs', 'trav_ajax_get_more_accs' );
add_action( 'wp_ajax_nopriv_get_more_accs', 'trav_ajax_get_more_accs' );

add_action( 'wp_ajax_acc_check_room_availability', 'trav_ajax_acc_check_room_availability' );
add_action( 'wp_ajax_nopriv_acc_check_room_availability', 'trav_ajax_acc_check_room_availability' );

add_action( 'wp_ajax_acc_update_booking_date', 'trav_ajax_acc_update_booking_date' );
add_action( 'wp_ajax_nopriv_acc_update_booking_date', 'trav_ajax_acc_update_booking_date' );

add_action( 'wp_ajax_acc_change_room', 'trav_ajax_acc_change_room' );
add_action( 'wp_ajax_nopriv_acc_change_room', 'trav_ajax_acc_change_room' );

// trav actions
add_action( 'trav_acc_booking_wrong_data', 'trav_redirect_home' );
add_action( 'trav_acc_conf_wrong_data', 'trav_redirect_home' );
add_action( 'trav_acc_conf_mail_not_sent', 'trav_acc_conf_send_mail' );
add_action( 'trav_acc_deposit_payment_not_paid', 'trav_acc_deposit_payment_not_paid' );
add_action( 'trav_acc_booking_before', 'trav_acc_booking_before' );

/*add_action( 'wp_ajax_get_more_amenities', 'trav_ajax_get_more_amenities' );
add_action( 'wp_ajax_nopriv_get_more_amenities', 'trav_ajax_get_more_amenities' );*/