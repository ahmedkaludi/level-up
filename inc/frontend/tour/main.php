<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( LEVELUP_INC_DIR . '/frontend/tour/functions.php');
require_once( LEVELUP_INC_DIR . '/frontend/tour/templates.php');
require_once( LEVELUP_INC_DIR . '/frontend/tour/ajax.php');

add_action( 'wp_ajax_tour_get_available_schedules', 'trav_ajax_tour_get_available_schedules' );
add_action( 'wp_ajax_nopriv_tour_get_available_schedules', 'trav_ajax_tour_get_available_schedules' );

add_action( 'wp_ajax_tour_submit_booking', 'trav_ajax_tour_submit_booking' );
add_action( 'wp_ajax_nopriv_tour_submit_booking', 'trav_ajax_tour_submit_booking' );

add_action( 'wp_ajax_tour_cancel_booking', 'trav_ajax_tour_cancel_booking' );
add_action( 'wp_ajax_nopriv_tour_cancel_booking', 'trav_ajax_tour_cancel_booking' );

add_action( 'trav_tour_booking_wrong_data', 'trav_redirect_home' );
add_action( 'trav_tour_conf_wrong_data', 'trav_redirect_home' );
add_action( 'trav_tour_conf_mail_not_sent', 'trav_tour_conf_send_mail' );
add_action( 'trav_tour_deposit_payment_not_paid', 'trav_tour_deposit_payment_not_paid' );
add_action( 'trav_tour_booking_before', 'trav_tour_booking_before' );