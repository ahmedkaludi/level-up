<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Handle Add to Wishlist Action on Detail Page
 */
if ( ! function_exists( 'trav_ajax_car_add_to_wishlist' ) ) {
	function trav_ajax_car_add_to_wishlist() {
		$result_json = array( 'success' => 0, 'result' => '' );
		if ( ! is_user_logged_in() ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Please login to update your wishlist.', 'trav' );
			wp_send_json( $result_json );
		}
		$user_id = get_current_user_id();
		$new_item_id = sanitize_text_field( trav_car_org_id( $_POST['car_id'] ) );
		$wishlist = get_user_meta( $user_id, 'wishlist', true );
		
		if ( isset( $_POST['remove'] ) ) {
			//remove
			$wishlist = array_diff( $wishlist, array( $new_item_id ) );
			if ( update_user_meta( $user_id, 'wishlist', $wishlist ) ) {
				$result_json['success'] = 1;
				$result_json['result'] = __( 'This car has removed from your wishlist successfully.', 'trav' );
			} else {
				$result_json['success'] = 0;
				$result_json['result'] = __( 'Sorry, An error occurred while update wishlist.', 'trav' );
			}
		} else {
			//add
			if ( empty( $wishlist ) ) $wishlist = array();
			if ( ! in_array( $new_item_id, $wishlist) ) {
				array_push( $wishlist, $new_item_id );
				if ( update_user_meta( $user_id, 'wishlist', $wishlist ) ) {
					$result_json['success'] = 1;
					$result_json['result'] = __( 'This car has added to your wishlist successfully.', 'trav' );
				} else {
					$result_json['success'] = 0;
					$result_json['result'] = __( 'Sorry, An error occurred while update wishlist.', 'trav' );
				}
			} else {
				$result_json['success'] = 1;
				$result_json['result'] = __( 'Already exists in your wishlist.', 'trav' );
			}
		}
		wp_send_json( $result_json );
	}
}

/*
 * Handle Check Car Availability
 */
if ( ! function_exists( 'trav_ajax_car_check_availability' ) ) {
	function trav_ajax_car_check_availability() {
		//validation and initiate variables
		$result_json = array( 'success' => 0, 'result' => '' );
		if ( isset( $_POST['booking_no'] ) ) {
			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'booking-' . $_POST['booking_no'] ) ) {
				$result_json['success'] = 0;
				$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
				wp_send_json( $result_json );
			}
			if ( ! $booking_data = trav_car_get_booking_data( $_POST['booking_no'], $_POST['pin_code'] ) ) {
				$result_json['success'] = 0;
				$result_json['result'] = __( 'Wrong booking number and pin code.', 'trav' );
				wp_send_json( $result_json );
			}
		} else if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['car_id'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-' . sanitize_text_field( $_POST['car_id'] ) ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
			wp_send_json( $result_json );
		}

		if ( isset( $_POST['car_id'] ) && isset( $_POST['date_from'] ) && trav_strtotime( $_POST['date_from'] ) && isset( $_POST['date_to'] ) && trav_strtotime( $_POST['date_to'] ) && ( ( time()-(60*60*24) ) < trav_strtotime( $_POST['date_from'] ) ) ) {
			$car_id = $_POST['car_id'];

			$except_booking_no = 0;
			$pin_code = 0;
			if ( isset( $_POST['edit_booking_no'] ) ) $except_booking_no = sanitize_text_field( $_POST['edit_booking_no'] );
			if ( isset( $_POST['pin_code'] ) ) $pin_code = sanitize_text_field( $_POST['pin_code'] );
			
			$result = trav_car_check_availability( $car_id, $_POST['date_from'], $_POST['date_to'], $except_booking_no, $pin_code );
			if ( true === $result ) {
				$result_json['success'] = 1;				
			} else {
				$result_json['success'] = 0;
				$result_json['result'] = $result;
			}
		} else {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Invalid input data', 'trav' );
		}

		wp_send_json( $result_json );
	}
}

/*
 * Handle submit booking ajax request
 */
if ( ! function_exists( 'trav_ajax_car_submit_booking' ) ) {
	function trav_ajax_car_submit_booking() {
		global $wpdb, $trav_options;

		// validation
		$result_json = array( 'success' => 0, 'result' => '' );
		if ( ! isset( $_POST['transaction_id'] ) || ! isset( $_SESSION['booking_data'][$_POST['transaction_id']] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, some error occurred on input data validation.', 'trav' );
			wp_send_json( $result_json );
		}

		$raw_booking_data = $_SESSION['booking_data'][$_POST['transaction_id']];
		
		$booking_fields = array( 'car_id', 'date_from', 'date_to', 'time_from', 'time_to', 'location_from', 'location_to', 'total_price', 'price', 'tax', 'currency_code', 'exchange_rate', 'deposit_price', 'created', 'booking_no', 'pin_code', 'status' );
		$booking_data = array();
		foreach( $booking_fields as $booking_field ) {
			if ( ! empty( $raw_booking_data[ $booking_field ] ) ) {
				$booking_data[ $booking_field ] = $raw_booking_data[ $booking_field ];
			}
		}

		$is_payment_enabled = trav_is_payment_enabled() && ! empty( $booking_data['deposit_price'] );

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-' . $booking_data['car_id'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
			wp_send_json( $result_json );
		}

		if ( isset( $trav_options['vld_captcha'] ) && ! empty( $trav_options['vld_captcha'] ) ) {
			if ( ! isset( $_POST['security_code'] ) || $_POST['security_code'] != $_SESSION['security_code'] ) {
				$result_json['success'] = 0;
				$result_json['result'] = __( 'Captcha error. Please check your security code again.', 'trav' );
				wp_send_json( $result_json );
			}
		}

		if ( isset( $trav_options['vld_credit_card'] ) && ! empty( $trav_options['vld_credit_card'] ) ) {
			if ( ! isset( $_POST['cc_type'] ) || ! isset( $_POST['cc_holder_name'] ) || ! isset( $_POST['cc_number'] ) || ! isset( $_POST['cc_exp_month'] ) || ! isset( $_POST['cc_exp_year'] ) || ! trav_cc_validation( $_POST['cc_type'], $_POST['cc_holder_name'], $_POST['cc_number'], $_POST['cc_exp_month'], $_POST['cc_exp_year'] ) ) {
				$result_json['success'] = 0;
				$result_json['result'] = __( 'Vcc validation An error.', 'trav' );
				wp_send_json( $result_json );
			}
		}

		// init variables
		$post_fields = array( 'first_name', 'last_name', 'email', 'country_code', 'phone', 'address', 'city', 'zip', 'country', 'special_requirements');
		$customer_info = array();
		foreach ( $post_fields as $post_field ) {
			if ( ! empty( $_POST[ $post_field ] ) ) {
				$customer_info[ $post_field ] = sanitize_text_field( $_POST[ $post_field ] );
			}
		}

		$data = array_merge( $customer_info, $booking_data );
		$data['date_from'] = date( 'Y-m-d', trav_strtotime( $data['date_from'] ) );
		$data['date_to'] = date( 'Y-m-d', trav_strtotime( $data['date_to'] ) );
		if ( is_user_logged_in() ) {
			$data['user_id'] = get_current_user_id();
		}
		$latest_booking_id = $wpdb->get_var( 'SELECT id FROM ' . TRAV_CAR_BOOKINGS_TABLE . ' ORDER BY id DESC LIMIT 1' );
		$booking_no = mt_rand( 1000, 9999 );
		$booking_no .= $latest_booking_id;
		$pin_code = mt_rand( 1000, 9999 );
		if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
		$default_booking_data = array(  'first_name'        => '',
										'last_name'         => '',
										'email'             => '',
										'country_code'      => '',
										'phone'             => '',
										'address'           => '',
										'city'              => '',
										'zip'               => '',
										'country'           => '',
										'special_requirements' => '',
										'car_id'		=> '',
										'total_price'       => '',
										'price'        		=> '',
										'tax'               => '',
										'currency_code'     => 'usd',
										'exchange_rate'     => 1,
										'deposit_price'		=> 0,
										'deposit_paid'		=> ( $is_payment_enabled ? 0 : 1 ),
										'date_from'         => '',
										'date_to'           => '',
										'time_from'			=> '',
										'time_to'			=> '',
										'location_from'		=> '',
										'location_to'		=> '',
										'created'           => date( 'Y-m-d H:i:s' ),
										'booking_no'        => $booking_no,
										'pin_code'          => $pin_code,
										'status'            => 1
									);

		$data = array_replace( $default_booking_data, $data );

		// credit card offline charge
		if ( ( ! empty( $trav_options['vld_credit_card'] ) ) && ( ! empty( $trav_options['cc_off_charge'] ) ) ) {
			$cc_fields = array( 'cc_type', 'cc_holder_name', 'cc_number', 'cc_cid', 'cc_exp_year', 'cc_exp_month' );
			$cc_infos = array();
			foreach( $cc_fields as $cc_field ) {
				$cc_infos[$cc_field] = empty( $_POST[$cc_field] ) ? '' : $_POST[$cc_field];
			}
			$data['other'] = serialize( $cc_infos );
		}

		// recheck availability
		$car_price_data = trav_car_get_price_data( $data['car_id'], $data['date_from'], $booking_data['date_to'] );
		if ( ! $car_price_data || ! is_array( $car_price_data ) ) {
			$result_json['success'] = -1;
			$result_json['result'] = __( 'Sorry, The car you are booking now is just taken by another customer. Please have another look.', 'trav' );
			wp_send_json( $result_json );
		}

		do_action( 'trav_car_add_booking_before', $data );

		// save default language car
		$data['car_id'] = trav_car_org_id( $data['car_id'] );

		// add to db
		if ( $wpdb->insert( TRAV_CAR_BOOKINGS_TABLE, $data ) ) {
			$booking_id = $wpdb->insert_id;
			$data['booking_id'] = $booking_id;
			$_SESSION['booking_data'][$_POST['transaction_id']] = $data;
			$result_json['success'] = 1;
			$result_json['result']['booking_no'] = $booking_no;
			$result_json['result']['pin_code'] = $pin_code;
			$result_json['result']['transaction_id'] = $_POST['transaction_id'];
			if ( $is_payment_enabled ) {
				if ( trav_is_woo_enabled() ) {
					// woocommerce
					do_action( 'trav_woo_add_car_booking', $data );
					$result_json['result']['payment'] = 'woocommerce';
				} elseif ( trav_is_paypal_enabled() ) {
					// paypal direct
					$result_json['result']['payment'] = 'paypal';
				}
			} else {
				$result_json['result']['payment'] = 'no';
			}
			do_action( 'trav_car_add_booking_after', $data );
		} else {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, An error occurred while add booking.', 'trav' );
		}
		wp_send_json( $result_json );
	}
}

/*
 * Handle cancel booking ajax request
 */
if ( ! function_exists( 'trav_ajax_car_cancel_booking' ) ) {
	function trav_ajax_car_cancel_booking() {
		if ( ! isset( $_POST['edit_booking_no'] ) || ! isset( $_POST['pin_code'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, some error occurred on input data validation.', 'trav' );
			wp_send_json( $result_json );
		}
		$edit_booking_no = sanitize_text_field( $_POST['edit_booking_no'] );
		$pin_code = sanitize_text_field( $_POST['pin_code'] );
		$result = trav_car_update_booking( $edit_booking_no, $pin_code, array( 'status' => 0 ), 'cancel' );
		if ( false === $result ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, some error occurred on update.', 'trav' );
		} else {
			$result_json['success'] = 1;
			$result_json['result'] = __( 'Your booking cancelled successfully.', 'trav' );

		}
		wp_send_json( $result_json );
	}
}

/*
 * update booking date
 */
if ( ! function_exists( 'trav_ajax_car_update_booking_date' ) ) {
	function trav_ajax_car_update_booking_date() {
		if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['booking_no'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'booking-' . $_POST['booking_no'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
			wp_send_json( $result_json );
		}

		global $wpdb;
		$booking_no = sanitize_text_field( $_POST['booking_no'] );
		$pin_code = sanitize_text_field( $_POST['pin_code'] );

		if ( ! $booking_data = trav_car_get_booking_data( $booking_no, $pin_code ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Wrong booking number and pin code.', 'trav' );
			wp_send_json( $result_json );
		}

		$booking_data['date_from'] = date( 'Y-m-d', trav_strtotime( $_POST['date_from'] ) );
		$booking_data['date_to'] = date( 'Y-m-d', trav_strtotime( $_POST['date_to'] ) );
		$booking_data['time_from'] = sanitize_text_field( $_POST['time_from'] );
		$booking_data['time_to'] = sanitize_text_field( $_POST['time_to'] );
		$booking_data['location_from'] = sanitize_text_field( $_POST['location_from'] );
		$booking_data['location_to'] = sanitize_text_field( $_POST['location_to'] );

		$car_price_data = trav_car_get_price_data( $booking_data['car_id'], $booking_data['date_from'], $booking_data['date_to'], $booking_no, $pin_code );
		
		if ( ! $car_price_data || ! is_array( $car_price_data ) ) {
			$result_json['success'] = -1;
			$result_json['result'] = __( 'Sorry, The car is not available for the selected date. Please have another look.', 'trav' );
			wp_send_json( $result_json );
		}

		$tax = get_post_meta( $booking_data['car_id'], 'trav_car_tax', true );
		if ( empty( $tax ) ) $tax = 0;
		
		$booking_data['price'] = $car_price_data['total_price'];
		$booking_data['tax'] = $tax;
		$booking_data['total_price'] = $booking_data['price'] + $booking_data['tax'];

		/*if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
		$booking_data['currency_code'] = trav_get_user_currency();*/
		$booking_data['updated'] = date( 'Y-m-d H:i:s' );
		$result = trav_car_update_booking( $booking_no, $pin_code, $booking_data, 'update' );
		if ( false === $result ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Sorry, some error occurred on update.', 'trav' );
		} else {
			$result_json['success'] = 1;
			$result_json['result'] = __( 'Your booking is updated successfully.', 'trav' );
		}
		wp_send_json( $result_json );
	}
}

/*
 * Handle get car month car numbers ajax request.
 */
if ( ! function_exists( 'trav_ajax_car_get_month_car_numbers' ) ) {
	function trav_ajax_car_get_month_car_numbers() {
		$result_json = array( 'success' => 0, 'result' => '' );
		//validation
		if ( ! isset( $_POST['year'] ) || ! isset( $_POST['month'] ) || ! isset( $_POST['car_id'] ) ) {
			$result_json['success'] = 0;
			$result_json['result'] = __( 'Invalid input data.', 'trav' );
			wp_send_json( $result_json );
		}

		//initiate variables
		$car_id = sanitize_text_field( $_POST['car_id'] );
		$year = sanitize_text_field( $_POST['year'] );
		$month = sanitize_text_field( $_POST['month'] );
		$month_cars = trav_car_get_month_car_numbers( $car_id, $year, $month );

		$result_json['success'] = 1;
		$result_json['result'] = $month_cars;
		wp_send_json( $result_json );
	}
}