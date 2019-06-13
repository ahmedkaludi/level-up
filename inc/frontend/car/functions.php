<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * generate js variable data to transfer car.js
 */
if ( ! function_exists( 'trav_get_car_js_data' ) ) {
	function trav_get_car_js_data() {
		global $post, $trav_options;
		
		$car_data = array();

		$car_data['car_id'] = $post->ID;
		
		if ( ! empty( $trav_options['car_booking_page'] ) ) {
			$car_data['booking_url'] = trav_get_permalink_clang( $trav_options['car_booking_page'] );
		}
		if ( defined('ICL_LANGUAGE_CODE') ) { $car_data['lang'] = ICL_LANGUAGE_CODE; }

		//messages
		$car_data['msg_no_booking_page'] = __( 'Please set car booking page on admin/Theme Options/Page Settings', 'trav' );
		$car_data['msg_wrong_date_1'] = __( 'Enter your pick-up and drop-off dates in the search box and click Search Now button', 'trav' );
		$car_data['msg_wrong_date_2'] = __( 'Please select Pick-up date.', 'trav' );
		$car_data['msg_wrong_date_3'] = __( 'Please select Drop-off date.', 'trav' );
		$car_data['msg_wrong_date_4'] = __( 'Please select Pick-up time.', 'trav' );
		$car_data['msg_wrong_date_5'] = __( 'Please select Drop-off time.', 'trav' );
		$car_data['msg_wrong_date_6'] = __( 'Please select Pick-up location.', 'trav' );
		$car_data['msg_wrong_date_7'] = __( 'Please select Drop-off location.', 'trav' );
		$car_data['msg_wrong_date_8'] = __( 'Your pick-up date is before your drop-off date. Have another look at your date and try again.', 'trav' );
		$car_data['msg_wrong_date_9'] = __( 'Wrong Pick-up date. Please check again.', 'trav' );
		$car_data['msg_wrong_date_10'] = __( 'Wrong search fields. Please check again.', 'trav' );

		return $car_data;
	}
}

/*
 * get booking page url
 */
if ( ! function_exists( 'trav_car_get_booking_page_url' ) ) {
	function trav_car_get_booking_page_url() {
		global $trav_options;
		$car_booking_page_url = '';
		if ( isset( $trav_options['car_booking_page'] ) && ! empty( $trav_options['car_booking_page'] ) ) {
			$car_booking_page_url = trav_get_permalink_clang( $trav_options['car_booking_page'] );
		}
		return $car_booking_page_url;
	}
}

/*
 * check car availability
 */
if ( ! function_exists( 'trav_car_check_availability' ) ) {
	function trav_car_check_availability( $car_id, $from_date, $to_date, $except_booking_no=0, $pin_code=0 ) {
		if ( empty( $car_id ) || 'car' != get_post_type( $car_id ) ) return esc_html__( 'Invalide Car ID.', 'trav' ); //invalid data

		$car_id = esc_sql( trav_car_org_id( $car_id ) );
		$except_booking_no = esc_sql( $except_booking_no );
		$pin_code = esc_sql( $pin_code );
		
		if ( ! trav_strtotime( $from_date ) || ! trav_strtotime( $to_date ) || ( trav_strtotime( $from_date ) >= trav_strtotime( $to_date ) ) ) {
			return esc_html__( 'Invalid date. Please check your booking date again.', 'trav' ); //invalid data
		}

		// initiate variables
		global $wpdb;
		//$check_dates = array();
		$availability_data = array();

		// prepare date for loop
		$from_date_obj = new DateTime( '@' . trav_strtotime( $from_date ) );
		$to_date_obj = new DateTime( '@' . trav_strtotime( $to_date ) );
		$date_interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($from_date_obj, $date_interval, $to_date_obj);

		foreach ( $period as $dt ) {
			$cars = trav_car_get_day_car_numbers( $car_id, $dt->format( "Y-m-d" ) );
			if ( $cars == 0 ) {
				return esc_html__( 'No Cars Available. Please have another look at booking date.', 'trav' );
			}
		}
		return true;
	}
}

/*
 * car booking page before action
 */
if ( ! function_exists( 'trav_car_booking_before' ) ) {
	function trav_car_booking_before() {
		global $trav_options, $def_currency;
		// prevent direct access
		if ( ! isset( $_REQUEST['booking_data'] ) ) {
			do_action('trav_car_booking_wrong_data');
			exit;
		}

		// init booking data : array( 'car_id', 'date_from', 'date_to', 'time_from', 'time_to', 'location_from', 'location_to' );
		$raw_booking_data = '';
		parse_str( $_REQUEST['booking_data'], $raw_booking_data );

		//verify nonce
		if ( ! isset( $raw_booking_data['_wpnonce'] ) || ! wp_verify_nonce( $raw_booking_data['_wpnonce'], 'post-' . $raw_booking_data['car_id'] ) ) {
			do_action('trav_car_booking_wrong_data');
			exit;
		}

		// init booking_data fields
		$booking_fields = array( 'car_id', 'date_from', 'date_to', 'time_from', 'time_to', 'location_from', 'location_to' );
		$booking_data = array();
		foreach ( $booking_fields as $field ) {
			if ( ! isset( $raw_booking_data[ $field ] ) ) {
				do_action('trav_car_booking_wrong_data');
				exit;
			} else {
				$booking_data[ $field ] = $raw_booking_data[ $field ];
			}
		}

		// date validation
		if ( trav_strtotime( $booking_data['date_from'] ) >= trav_strtotime( $booking_data['date_to'] ) ) {
			do_action('trav_car_booking_wrong_data');
			exit;
		}

		// make an array for redirect url generation
		$query_args = array(
						'date_from' => $booking_data['date_from'],
						'date_to' => $booking_data['date_to'],
						'time_from' => $booking_data['time_from'],
						'time_to' => $booking_data['time_to'], 
						'location_from' => $booking_data['location_from'],
						'location_to' => $booking_data['location_to']
			);

		// get price data
		$car_price_data = trav_car_get_price_data( $booking_data['car_id'], $booking_data['date_from'], $booking_data['date_to'] );
		$car_url = get_permalink( $booking_data['car_id'] );
		$edit_url = add_query_arg( $query_args, $car_url );

		// redirect if $car_price_data is not valid
		if ( ! $car_price_data || ! is_array( $car_price_data ) ) {
			$query_args['error']=1;
			wp_redirect( $edit_url );
		}

		// calculate tax, discount and total price
        $is_discount = get_post_meta( $booking_data['car_id'], 'trav_car_hot', true );
        $discount_rate = get_post_meta( $booking_data['car_id'], 'trav_car_discount_rate', true );
        $tax_rate = get_post_meta( $booking_data['car_id'], 'trav_car_tax_rate', true );
        $tax = 0;
        if ( ! empty( $tax_rate ) ) {
            $tax = $tax_rate * $car_price_data['total_price'] / 100;
        }
        if ( ! empty( $is_discount ) && ! empty( $discount_rate ) && ( $discount_rate > 0 ) && ( $discount_rate <= 100 ) ) { 
            $booking_data['discount_rate'] = $discount_rate;
        } else { 
            $booking_data['discount_rate'] = 0;
        }

		$booking_data['price'] = $car_price_data['total_price'];
        $booking_data['tax'] = $tax;
        $booking_data['total_price'] = ( $booking_data['price'] + $booking_data['tax'] ) * ( 100 - $booking_data['discount_rate'] ) / 100;

		// calculate deposit payment
		$deposit_rate = get_post_meta( $booking_data['car_id'], 'trav_car_security_deposit', true );
		// if woocommerce enabled change currency_code and exchange rate as default
		if ( ! empty( $deposit_rate ) && trav_is_woo_enabled() ) {
			$booking_data['currency_code'] = $def_currency;
			$booking_data['exchange_rate'] = 1;
		} else {
			if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
			$booking_data['currency_code'] = trav_get_user_currency();
			$booking_data['exchange_rate'] = $_SESSION['exchange_rate'];
		}

		// if payment enabled set deposit price field
		$is_payment_enabled = ! empty( $deposit_rate ) && trav_is_payment_enabled();
		if ( $is_payment_enabled ) {
			$booking_data['deposit_price'] = $deposit_rate / 100 * $booking_data['total_price'] * $booking_data['exchange_rate'];
		}

		// initialize session values
		$transaction_id = mt_rand( 100000, 999999 );
		$_SESSION['booking_data'][$transaction_id] = $booking_data; //car_id, date_from, date_to, time_from, time_to, location_from, location_to, price, tax, total_price, currency_code, exchange_rate, deposit_price

		// thank you page url
        $car_book_conf_url = '';
        if ( ! empty( $trav_options['car_booking_confirmation_page'] ) ) {
            $car_book_conf_url = trav_get_permalink_clang( $trav_options['car_booking_confirmation_page'] );
        } else {
            // thank you page is not set
        }
				
		global $trav_booking_page_data;
		$trav_booking_page_data['transaction_id'] = $transaction_id;
		$trav_booking_page_data['car_url'] = $car_url;
		$trav_booking_page_data['edit_url'] = $edit_url;
		$trav_booking_page_data['booking_data'] = $booking_data;
		$trav_booking_page_data['car_price_data'] = $car_price_data;
		$trav_booking_page_data['is_payment_enabled'] = $is_payment_enabled;
		$trav_booking_page_data['car_book_conf_url'] = $car_book_conf_url;
		$trav_booking_page_data['tax'] = $tax;
		$trav_booking_page_data['tax_rate'] = $tax_rate;
		$trav_booking_page_data['discount_rate'] = $discount_rate;
	}
}

/*
 * Calculate the price of selected car and return price array data
 */
if ( ! function_exists( 'trav_car_get_price_data' ) ) {
	function trav_car_get_price_data( $car_id, $from_date, $to_date, $except_booking_no=0, $pin_code=0 ) {
		global $wpdb;

		$car_id = trav_car_org_id( $car_id );
		
		//validation
		if ( ( time()-( 60*60*24 ) ) > trav_strtotime( $from_date ) ) return false;
		if ( ! trav_strtotime( $from_date ) || ! trav_strtotime( $to_date ) || ( trav_strtotime( $from_date ) >= trav_strtotime( $to_date ) ) ) return false;

		if ( true !== trav_car_check_availability( $car_id, $from_date, $to_date, $except_booking_no=0, $pin_code=0 ) ) {
			return false;
		}

		$from_date_obj = new DateTime( '@' . trav_strtotime( $from_date ) );
		$to_date_obj = new DateTime( '@' . trav_strtotime( $to_date ) );
		$date_diff = $to_date_obj->diff($from_date_obj);
		$dates = $date_diff->days + 1;
		$price_per_day = get_post_meta( $car_id, 'trav_car_price', true );
		$total_price = $dates * $price_per_day;
		$return_value = array(
			'price_per_day' => $price_per_day,
			'check_dates' => $dates,
			'total_price' => $total_price
		);

		return $return_value;
	}
}

/*
 * get booking data with booking_no and pin_code
 */
if ( ! function_exists( 'trav_car_get_booking_data' ) ) {
	function trav_car_get_booking_data( $booking_no, $pin_code ) {
		global $wpdb;
		return $wpdb->get_row( 'SELECT * FROM ' . TRAV_CAR_BOOKINGS_TABLE . ' WHERE booking_no="' . esc_sql( $booking_no ) . '" AND pin_code="' . esc_sql( $pin_code ) . '"', ARRAY_A );
	}
}

/*
 * get booking confirmation url
 */
if ( ! function_exists( 'trav_car_get_book_conf_url' ) ) {
	function trav_car_get_book_conf_url() {
		global $trav_options;
		$car_book_conf_url = '';
		if ( isset( $trav_options['car_booking_confirmation_page'] ) && ! empty( $trav_options['car_booking_confirmation_page'] ) ) {
			$car_book_conf_url = trav_get_permalink_clang( $trav_options['car_booking_confirmation_page'] );
		}
		return $car_book_conf_url;
	}
}

/*
 * echo deposit payment not paid notice on confirmation page
 */
if ( ! function_exists( 'trav_car_deposit_payment_not_paid' ) ) {
	function trav_car_deposit_payment_not_paid( $booking_data ) {
		echo '<div class="alert alert-notice">' . __( 'Deposit payment is not paid.', 'trav' ) . '<span class="close"></span></div>';
	}
}

/*
 * send confirmation email
 */
if ( ! function_exists( 'trav_car_conf_send_mail' ) ) {
	function trav_car_conf_send_mail( $booking_data ) {
		global $wpdb;
		$mail_sent = 0;
		if ( trav_car_send_confirmation_email( $booking_data['booking_no'], $booking_data['pin_code'], 'new' ) ) {
			$mail_sent = 1;
			$wpdb->update( TRAV_CAR_BOOKINGS_TABLE, array( 'mail_sent' => $mail_sent ), array( 'booking_no' => $booking_data['booking_no'], 'pin_code' => $booking_data['pin_code'] ), array( '%d' ), array( '%d','%d' ) );
		}
	}
}

/*
 * send booking confirmation email function
 */
if ( ! function_exists( 'trav_car_send_confirmation_email' ) ) {
	function trav_car_send_confirmation_email( $booking_no, $booking_pincode, $type='new', $subject='', $description='' ) {
		global $wpdb, $logo_url, $trav_options;
		$booking_data = trav_car_get_booking_data( $booking_no, $booking_pincode );
		if ( ! empty( $booking_data ) ) {
			// server variables
			$admin_email = get_option('admin_email');
			$home_url = esc_url( home_url() );
			$site_name = $_SERVER['SERVER_NAME'];
			$logo_url = esc_url( $logo_url );
			$car_book_conf_url = trav_car_get_book_conf_url();
			$booking_data['car_id'] = trav_car_clang_id( $booking_data['car_id'] );
			
			// car info
			$car_name = get_the_title( $booking_data['car_id'] );
			$car_url = esc_url( trav_get_permalink_clang( $booking_data['car_id'] ) );
			$car_thumbnail = get_the_post_thumbnail( $booking_data['car_id'], 'list-thumb' );
			$car_phone = get_post_meta( $booking_data['car_id'], 'trav_car_phone', true );
			$car_email = get_post_meta( $booking_data['car_id'], 'trav_car_email', true );
			
			// booking info
			$booking_no = $booking_data['booking_no'];
			$booking_pincode = $booking_data['pin_code'];
			$date_from = new DateTime( $booking_data['date_from'] );
			$date_to = new DateTime( $booking_data['date_to'] );
			$number1 = $date_from->format('U');
			$number2 = $date_to->format('U');
			$booking_days = ($number2 - $number1)/(3600*24);
			$booking_checkin_time = date( 'l, F, j, Y', trav_strtotime($booking_data['date_from']) ) . ' ' . $booking_data['time_from'];
			$booking_checkout_time = date( 'l, F, j, Y', trav_strtotime($booking_data['date_to']) ) . ' ' . $booking_data['time_to'];
			$booking_car_price = esc_html( trav_get_price_field( $booking_data['price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
			$booking_tax = esc_html( trav_get_price_field( $booking_data['tax'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
			$booking_total_price = esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
			$booking_deposit_price = esc_html( $booking_data['deposit_price'] . $booking_data['currency_code'] );
			$booking_deposit_paid = esc_html( empty( $booking_data['deposit_paid'] ) ? 'No' : 'Yes' );
			$booking_update_url = esc_url( add_query_arg( array( 'booking_no'=>$booking_data['booking_no'], 'pin_code'=>$booking_data['pin_code'] ), $car_book_conf_url ) );
			$booking_pick_up_location = $booking_data['location_from'];
			$booking_drop_off_location = $booking_data['location_to'];			

			// customer info
			$customer_first_name = $booking_data['first_name'];
			$customer_last_name = $booking_data['last_name'];
			$customer_email = $booking_data['email'];
			$customer_country_code = $booking_data['country_code'];
			$customer_phone = $booking_data['phone'];
			$customer_address = $booking_data['address'];
			$customer_city = $booking_data['city'];
			$customer_zip = $booking_data['zip'];
			$customer_country = $booking_data['country'];
			$customer_special_requirements = $booking_data['special_requirements'];

			$variables = array( 'home_url',
								'site_name',
								'logo_url',
								'car_name',
								'car_url',
								'car_thumbnail',
								'car_country',
								'car_city',
								'car_phone',
								'car_email',
								'booking_no',
								'booking_pincode',
								'booking_days',
								'booking_checkin_time',
								'booking_checkout_time',
								'booking_car_price',
								'booking_tax',
								'booking_total_price',
								'booking_deposit_price',
								'booking_deposit_paid',
								'booking_update_url',
								'customer_first_name',
								'customer_last_name',
								'customer_email',
								'customer_country_code',
								'customer_phone',
								'customer_address',
								'customer_city',
								'customer_zip',
								'customer_country',
								'customer_special_requirements',
								'booking_pick_up_location',
								'booking_drop_off_location'
							);

			if ( empty( $subject ) ) {
				if ( $type == 'new' ) {
					$subject = empty( $trav_options['car_confirm_email_subject'] ) ? 'Booking Confirmation Email Subject' : $trav_options['car_confirm_email_subject'];
				} elseif ( $type == 'update' ) {
					$subject = empty( $trav_options['car_update_email_subject'] ) ? 'Booking Updated Email Subject' : $trav_options['car_update_email_subject'];
				} elseif ( $type == 'cancel' ) {
					$subject = empty( $trav_options['car_cancel_email_subject'] ) ? 'Booking Canceled Email Subject' : $trav_options['car_cancel_email_subject'];
				}
			}

			if ( empty( $description ) ) {
				if ( $type == 'new' ) {
					$description = empty( $trav_options['car_confirm_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['car_confirm_email_description'];
				} elseif ( $type == 'update' ) {
					$description = empty( $trav_options['car_update_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['car_update_email_description'];
				} elseif ( $type == 'cancel' ) {
					$description = empty( $trav_options['car_cancel_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['car_cancel_email_description'];
				}
			}

			foreach ( $variables as $variable ) {
				$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
				$description = str_replace( "[" . $variable . "]", $$variable, $description );
			}

			$mail_sent = trav_send_mail( $site_name, $admin_email, $customer_email, $subject, $description );

			/* mailing function to business owner */
			$bowner_address = '';
			if ( ! empty( $trav_options['car_booked_notify_bowner'] ) ) {

				if ( $type == 'new' ) {
					$subject = empty( $trav_options['car_bowner_email_subject'] ) ? 'You received a booking' : $trav_options['car_bowner_email_subject'];
					$description = empty( $trav_options['car_bowner_email_description'] ) ? 'Booking Details' : $trav_options['car_bowner_email_description'];
				} elseif ( $type == 'update' ) {
					$subject = empty( $trav_options['car_update_bowner_email_subject'] ) ? 'A booking is updated' : $trav_options['car_update_bowner_email_subject'];
					$description = empty( $trav_options['car_update_bowner_email_description'] ) ? 'Booking Details' : $trav_options['car_update_bowner_email_description'];
				} elseif ( $type == 'cancel' ) {
					$subject = empty( $trav_options['car_cancel_bowner_email_subject'] ) ? 'A booking is canceled' : $trav_options['car_cancel_bowner_email_subject'];
					$description = empty( $trav_options['car_cancel_bowner_email_description'] ) ? 'Booking Details' : $trav_options['car_cancel_bowner_email_description'];
				}

				foreach ( $variables as $variable ) {
					$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
					$description = str_replace( "[" . $variable . "]", $$variable, $description );
				}

				if ( ! empty( $car_email ) ) {
					$bowner_address = $car_email;
				} else {
					$post_author_id = get_post_field( 'post_author', $booking_data['car_id'] );
					$bowner = get_user_by( 'id', $post_author_id );
					if ( ! empty( $bowner ) ) {
						$bowner_address = $bowner->user_email;
					}
				}

				if ( ! empty( $bowner_address ) ) {
					trav_send_mail( $site_name, $admin_email, $bowner_address, $subject, $description );
				}
			}

			/* mailing function to admin */
			if ( ! empty( $trav_options['car_booked_notify_admin'] ) ) {
				if ( $bowner_address != $admin_email ) {
					if ( $type == 'new' ) {
						$subject = empty( $trav_options['car_admin_email_subject'] ) ? 'You received a booking' : $trav_options['car_admin_email_subject'];
						$description = empty( $trav_options['car_admin_email_description'] ) ? 'Booking Details' : $trav_options['car_admin_email_description'];
					} elseif ( $type == 'update' ) {
						$subject = empty( $trav_options['car_update_admin_email_subject'] ) ? 'A booking is updated' : $trav_options['car_update_admin_email_subject'];
						$description = empty( $trav_options['car_update_admin_email_description'] ) ? 'Booking Details' : $trav_options['car_update_admin_email_description'];
					} elseif ( $type == 'cancel' ) {
						$subject = empty( $trav_options['car_cancel_admin_email_subject'] ) ? 'A booking is canceled' : $trav_options['car_cancel_admin_email_subject'];
						$description = empty( $trav_options['car_cancel_admin_email_description'] ) ? 'Booking Details' : $trav_options['car_cancel_admin_email_description'];
					}

					foreach ( $variables as $variable ) {
						$subject = str_replace( "[" . $variable . "]", $$variable, $subject );
						$description = str_replace( "[" . $variable . "]", $$variable, $description );
					}

					trav_send_mail( $site_name, $admin_email, $admin_email, $subject, $description );
				}
			}
			return true;
		}
		return false;
	}
}

/*
 * function to update booking
 */
if ( ! function_exists( 'trav_car_update_booking' ) ) {
	function trav_car_update_booking( $booking_no, $pin_code, $new_data, $action='update' ) {
		global $wpdb, $trav_options;
		$result = $wpdb->update( TRAV_CAR_BOOKINGS_TABLE, $new_data, array( 'booking_no' => $booking_no, 'pin_code' => $pin_code ) );
		if ( $result ) {
			trav_car_send_confirmation_email( $booking_no, $pin_code, $action );
			return $result;
		}
		return false;
	}
}

/*
 * get booking default values
 */
if ( ! function_exists( 'trav_car_default_booking_data' ) ) {
	function trav_car_default_booking_data( $type='new' ) {
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
										'car_id'  	=> '',
										'price'        => '',
										'tax'               => '',
										'total_price'       => '',
										'currency_code'     => '',
										'exchange_rate'     => 1,
										'deposit_price'     => 0,
										'deposit_paid'      => 1,
										'date_from'         => '',
										'date_to'           => '',
										'time_from'         => '',
										'time_to'           => '',
										'location_from'     => '',
										'location_to'       => '',
										'booking_no'        => '',
										'pin_code'          => '',
										'status'            => 1,
										'updated'           => date( 'Y-m-d H:i:s' ),
									);
		if ( $type == 'new' ) {
			$a = array( 'user_id' => '',
						'created' => date( 'Y-m-d H:i:s' ),
						'mail_sent' => '',
						'other' => '',
						'id' => '' );
			$default_booking_data = array_merge( $default_booking_data, $a );
		}

		return $default_booking_data;
	}
}

/*
 * Check if a given car is available for a given day and return available car numbers
 */
if ( ! function_exists( 'trav_car_get_day_car_numbers' ) ) {
	function trav_car_get_day_car_numbers( $car_id, $date ) {
		global $wpdb;
		$car_id = esc_sql( trav_car_org_id( $car_id ) );

		$from_date_obj = new DateTime( '@' . trav_strtotime( $date .' - ' . TRAV_CAR_MAINTENANCE_DATES . ' days' ) );
		$to_date_obj = new DateTime( '@' . trav_strtotime( $date .' + ' . TRAV_CAR_MAINTENANCE_DATES . ' days' ) );
		$from_date = esc_sql( $from_date_obj->format( "Y-m-d" ) );
		$to_date = esc_sql( $to_date_obj->format( "Y-m-d" ) );

		$sql = "SELECT cars.available_cars - IFNULL(bookings.cars, 0) AS cars
				FROM (SELECT posts.*, pm0.meta_value as available_cars
						FROM " . $wpdb->posts . " AS posts
						INNER JOIN " . $wpdb->postmeta . " AS pm0 ON (pm0.post_id = posts.ID) AND (pm0.meta_key = 'trav_car_max_cars')
						WHERE (posts.post_status = 'publish') AND (posts.post_type = 'car') AND pm0.meta_value > 0 AND posts.ID ='" . $car_id . "') as cars
				LEFT JOIN (SELECT car_bookings.car_id, count(*) as cars
							FROM " . TRAV_CAR_BOOKINGS_TABLE . " as car_bookings
							WHERE 1=1 AND car_bookings.status != '0' 
							AND car_bookings.car_id ='" . $car_id . "' 
							AND ( ( car_bookings.date_to >= '" . $from_date . "' AND car_bookings.date_to <= '" . $to_date . "' ) OR ( car_bookings.date_from >= '" . $from_date . "' and car_bookings.date_from <= '" . $to_date . "' ) OR ( car_bookings.date_from <= '" . $from_date . "' and car_bookings.date_to >= '" . $to_date . "' ) )" . ( ( empty( $except_booking_no ) || empty( $pin_code ) ) ? "" : ( " AND NOT ( booking_no = '" . $except_booking_no . "' AND pin_code = '" . $pin_code . "' )" ) ) . "
				) as bookings ON cars.ID = bookings.car_id
				WHERE 1 = 1;";
		$results = $wpdb->get_results( $sql );
		$cars = $wpdb->get_var( $sql );
		return ( $cars > 0 ) ? $cars : 0;
	}
}

/*
 * Check if a given car is available for a given month and return vailable car numbers array
 */
if ( ! function_exists( 'trav_car_get_month_car_numbers' ) ) {
	function trav_car_get_month_car_numbers( $car_id, $year, $month ) {
		$num = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$car_numbers = array();
		$date = new DateTime();
		for ( $i = 1; $i <= $num; $i++ ) {
			$date->setDate( $year, $month, $i );
			$car_numbers[$i] = trav_car_get_day_car_numbers( $car_id, $date->format('Y-m-d') );
		}
		return $car_numbers;
	}
}

/*
 * Get cars from ids
 */
if ( ! function_exists( 'trav_car_get_cars_from_id' ) ) {
	function trav_car_get_cars_from_id( $ids ) {
		if ( ! is_array( $ids ) ) return false;
		$results = array();
		foreach( $ids as $id ) {
			$result = get_post( $id );
			if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
				if ( $result->post_type == 'car' ) $results[] = $result;
			}
		}
		return $results;
	}
}

/*
 * Get discounted(hot) cars and return data
 */
if ( ! function_exists( 'trav_car_get_hot_cars' ) ) {
	function trav_car_get_hot_cars( $count = 10, $car_type=array(), $car_agent=array() ) {
		$args = array(
			'post_type'  => 'car',
			'orderby'    => 'rand',
			'posts_per_page' => $count,
			'suppress_filters' => 0,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'trav_car_hot',
					'value'   => '1',
				),
				array(
					'key'     => 'trav_car_discount_rate',
					'value'   => array( 0, 100 ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN',
				),
				array(
					'key'     => 'trav_car_edate',
					'value'   => date('Y-m-d'),
					'compare' => '>=',
				),
				array(
					'key'     => 'trav_car_sdate',
					'value'   => date('Y-m-d'),
					'compare' => '<=',
				),
			),
		);

		if ( ! empty( $car_type ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'car_type',
						'field' => 'term_id',
						'terms' => $car_type
						)
				);
		}

		if ( ! empty( $car_agent ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'car_agent',
						'field' => 'term_id',
						'terms' => $car_agent
						)
				);
		}
		return get_posts( $args );
	}
}

/*
 * Get special( latest or featured ) cars and return data
 */
if ( ! function_exists( 'trav_car_get_special_cars' ) ) {
	function trav_car_get_special_cars( $type='latest', $count=10, $exclude_ids=array(),  $car_type=array(), $car_agent=array() ) {
		$args = array(
				'post_type'  => 'car',
				'suppress_filters' => 0,
				'posts_per_page' => $count,
			);
		
		if ( ! empty( $exclude_ids ) ) {
			$args['post__not_in'] = $exclude_ids;
		}

		if ( ! empty( $car_type ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'car_type',
						'field' => 'term_id',
						'terms' => $car_type
						)
				);
		}

		if ( ! empty( $car_agent ) ) {
			$args['tax_query'] = array(
					array(
						'taxonomy' => 'car_agent',
						'field' => 'term_id',
						'terms' => $car_agent
						)
				);
		}

		if ( $type == 'featured'  ) {
			$args = array_merge( $args, array(
				'orderby'    => 'rand',
				'meta_key'     => 'trav_car_featured',
				'meta_value'   => '1',
			) );
			return get_posts( $args );
		} elseif ( $type == 'latest' ) {
			$args = array_merge( $args, array(
				'orderby' => 'post_date',
				'order' => 'DESC',
			) );
			return get_posts( $args );
		} elseif ( $type == 'popular' ) {
			global $wpdb;
			$tbl_post = esc_sql( $wpdb->prefix . 'posts' );
			$tbl_postmeta = esc_sql( $wpdb->prefix . 'postmeta' );
			$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
			$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
			$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );

			$date = date( 'Y-m-d', strtotime( '-30 days' ) );
			$sql = 'SELECT car_id, COUNT(*) AS booking_count FROM ' . TRAV_CAR_BOOKINGS_TABLE . ' AS booking';
			$where = ' WHERE (booking.status <> 0) AND (booking.created > %s)';

			$sql .= " INNER JOIN {$tbl_post} AS p1 ON (p1.ID = booking.car_id) AND (p1.post_status = 'publish')";
			
			if ( ! empty( $car_type ) ) {
				$sql .= " INNER JOIN {$tbl_term_relationships} AS tr1 ON tr1.object_id = booking.car_id 
						INNER JOIN {$tbl_term_taxonomy} AS tt1 ON tt1.term_taxonomy_id = tr1.term_taxonomy_id";
				$where .= " AND tt1.taxonomy = 'car_type' AND tt1.term_id IN (" . esc_sql( implode( ',', $car_type ) ) . ")";
			}
			if ( ! empty( $car_agent ) ) {
				$sql .= " INNER JOIN {$tbl_term_relationships} AS tr2 ON tr2.object_id = booking.car_id 
						INNER JOIN {$tbl_term_taxonomy} AS tt1 ON tt2.term_taxonomy_id = tr2.term_taxonomy_id";
				$where .= " AND tt2.taxonomy = 'car_agent' AND tt2.term_id IN (" . esc_sql( implode( ',', $car_agent ) ) . ")";
			}

			$sql .= $where . ' GROUP BY booking.car_id ORDER BY booking_count desc LIMIT %d';
			$popular_cars = $wpdb->get_results( sprintf( $sql, $date, $count ) );
			$result = array();
			if ( ! empty( $popular_cars ) ) {
				foreach ( $popular_cars as $car ) {
					$result[] = get_post( trav_car_clang_id( $car->car_id ) );
				}
			}
			// if booked car number in last month is smaller than count then add latest cars
			if ( count( $popular_cars ) < $count ) {
				foreach ( $popular_cars as $car ) {
					$exclude_ids[] = trav_car_clang_id( $car->car_id );
				}
				$result = array_merge( $result, trav_car_get_special_cars( 'latest', $count - count( $popular_cars ), $exclude_ids, $car_type, $car_agent ) );
			}
			return $result;
		}
	}
}

/*
 * Get Car Search Result
 */
if ( ! function_exists( 'trav_car_get_search_result' ) ) {
	function trav_car_get_search_result( $s='', $date_from='', $date_to='', $order_by='car_title', $order='ASC', $last_no=0, $per_page=12, $min_price=0, $max_price='no_max', $passengers=1, $car_type, $car_agent, $preferences ) {

		// if wrong date return false
		if ( ! empty( $date_from ) && ! empty( $date_to ) && ( trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) ) return false;
		global $wpdb, $language_count;
		$tbl_posts = esc_sql( $wpdb->posts );
		$tbl_postmeta = esc_sql( $wpdb->postmeta );
		$tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
		$tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
		$tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );
		$tbl_icl_translations = esc_sql( $wpdb->prefix . 'icl_translations' );

		$temp_tbl_name = trav_get_temp_table_name();
		$sql = '';
		$order_by = esc_sql( $order_by );
		$order = esc_sql( $order );
		$last_no = esc_sql( $last_no );
		$per_page = esc_sql( $per_page );
		$min_price = esc_sql( $min_price );
		$max_price = esc_sql( $max_price );
		$passengers = esc_sql( $passengers );

		if ( empty( $car_type ) || ! is_array( $car_type ) ) $car_type = array();
		if ( empty( $car_agent ) || ! is_array( $car_agent ) ) $car_agent = array();
		if ( empty( $preferences ) || ! is_array( $preferences ) ) $preferences = array();
		foreach ( $car_type as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $car_type[$key] );
		}
		foreach ( $car_agent as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $car_agent[$key] );
		}
		foreach ( $preferences as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $preferences[$key] );
		}

		//mysql escape sting and like escape
		if ( floatval( get_bloginfo( 'version' ) ) >= 4.0 ) {
			$s = esc_sql( $wpdb->esc_like( $s ) );
		} else {
			$s = esc_sql( like_escape( $s ) );
		}

		$from_date_obj = date_create_from_format( trav_get_date_format('php'), $date_from );
		$to_date_obj = date_create_from_format( trav_get_date_format('php'), $date_to );

		$s_query = ''; // sql for search keyword
		$c_query = ''; // sql for conditions ( price )
		$v_query = ''; // sql for vacancy check

		if ( ! empty( $s ) ) {
			$s_query = "SELECT DISTINCT post_s1.ID AS car_id FROM {$tbl_posts} AS post_s1 
						LEFT JOIN {$tbl_postmeta} AS meta_s1 ON post_s1.ID = meta_s1.post_id
						WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'car') AND (meta_s1.meta_key = 'trav_car_location')
						  AND ((post_s1.post_title LIKE '%{$s}%') 
							OR (post_s1.post_content LIKE '%{$s}%')
							OR (meta_s1.meta_value LIKE '%{$s}%'))";
		} else {
			$s_query = "SELECT post_s1.ID AS car_id FROM {$tbl_posts} AS post_s1 
						WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'car')";
		}

		// if wpml is enabled do search by default language post
		if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) ) {
			$s_query = "SELECT DISTINCT it2.element_id AS car_id FROM ({$s_query}) AS t0
						INNER JOIN {$tbl_icl_translations} it1 ON (it1.element_type = 'post_car') AND it1.element_id = t0.car_id
						INNER JOIN {$tbl_icl_translations} it2 ON (it2.element_type = 'post_car') AND it2.language_code='" . trav_get_default_language() . "' AND it2.trid = it1.trid ";
		}

		$c_query = "SELECT t1.*, meta_c1.meta_value AS available_cars
					FROM ( {$s_query} ) AS t1
					INNER JOIN {$tbl_postmeta} AS meta_c1 ON (meta_c1.post_id = t1.car_id) AND (meta_c1.meta_key = 'trav_car_max_cars')
					INNER JOIN {$tbl_postmeta} AS meta_c2 ON (meta_c2.post_id = t1.car_id) AND (meta_c2.meta_key = 'trav_car_passenger')
					WHERE meta_c2.meta_value >= {$passengers}";

		// if this searh has specified date then check vacancy and booking data, but if it doesn't have specified date then only check other search factors
		if ( $from_date_obj && $to_date_obj ) {
			// has specified date
			$date_interval = DateInterval::createFromDateString('1 day');
			$from_date_obj = new DateTime( '@' . trav_strtotime( $from_date_obj->format( "Y-m-d" ) .' - ' . TRAV_CAR_MAINTENANCE_DATES . ' days' ) );
			$to_date_obj = new DateTime( '@' . trav_strtotime( $to_date_obj->format( "Y-m-d" ) .' + ' . TRAV_CAR_MAINTENANCE_DATES . ' days' ) );
			
			$period = new DatePeriod( $from_date_obj, $date_interval, $to_date_obj );

			$sql_check_date_parts = array();
			$days = 0;
			foreach ( $period as $dt ) {
				$check_date = $dt->format( "Y-m-d" );
				$sql_check_date_parts[] = "SELECT '{$check_date}' AS check_date";
				$days++;
			}
			$sql_check_date = implode( ' UNION ', $sql_check_date_parts );

			$v_query = "SELECT car_tbl.car_id FROM ( 
							SELECT t3.car_id, MIN(cars) AS min_cars FROM (
								SELECT t2.*, (IFNULL(t2.available_cars,0) - IFNULL(COUNT(bookings.id),0)) AS cars, check_dates.check_date 
								FROM ({$c_query}) AS t2
								JOIN ( {$sql_check_date} ) AS check_dates
								LEFT JOIN " . TRAV_CAR_BOOKINGS_TABLE . " AS bookings ON bookings.status!='0' AND (bookings.car_id = t2.car_id) AND (bookings.date_from <= check_dates.check_date AND bookings.date_to >= check_dates.check_date)
								GROUP BY t2.car_id, check_dates.check_date
							  ) AS t3 
							  GROUP BY t3.car_id ) AS car_tbl
						WHERE car_tbl.min_cars > 0";
		} else {
			// without specified date
			$v_query = $c_query;
		}

		$sql = $v_query;
		// if wpml is enabled return current language posts
		if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) && ( trav_get_default_language() != ICL_LANGUAGE_CODE ) ) {
			$sql = "SELECT it4.element_id AS car_id FROM ({$sql}) AS t5
					INNER JOIN {$tbl_icl_translations} it3 ON (it3.element_type = 'post_car') AND it3.element_id = t5.car_id
					INNER JOIN {$tbl_icl_translations} it4 ON (it4.element_type = 'post_car') AND it4.language_code='" . ICL_LANGUAGE_CODE . "' AND it4.trid = it3.trid";
		}

		$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS {$temp_tbl_name} AS " . $sql;
		$wpdb->query( $sql );

		$sql = "SELECT t1.*, post_l1.post_title as car_title, meta_price.meta_value as price FROM {$temp_tbl_name} as t1
				INNER JOIN {$tbl_posts} post_l1 ON (t1.car_id = post_l1.ID) AND (post_l1.post_status = 'publish') AND (post_l1.post_type = 'car')
				LEFT JOIN {$tbl_postmeta} AS meta_price ON (t1.car_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_car_price')";
		$where = ' 1=1';

		if ( $min_price != 0 ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
		}
		if ( $max_price != 'no_max' ) {
			$where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
		}

		if ( ! empty( $car_type ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.car_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'car_type' AND tt.term_id IN (" . esc_sql( implode( ',', $car_type ) ) . ")";
		}

		if ( ! empty( $car_agent ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr1 ON tr1.object_id = t1.car_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt1 ON tt1.term_taxonomy_id = tr1.term_taxonomy_id";
			$where .= " AND tt1.taxonomy = 'car_agent' AND tt1.term_id IN (" . esc_sql( implode( ',', $car_agent ) ) . ")";
		}

		if ( ! empty( $preferences ) ) {
			$where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr2 
					INNER JOIN {$tbl_term_taxonomy} AS tt2 ON ( tr2.term_taxonomy_id= tt2.term_taxonomy_id )
					WHERE tt2.taxonomy = 'preference' AND tt2.term_id IN (" . esc_sql( implode( ',', $preferences ) ) . ") AND tr2.object_id = t1.car_id ) = " . count( $preferences ) . ")";
		}

		$sql .= " WHERE {$where} GROUP BY car_id ORDER BY {$order_by} {$order} LIMIT {$last_no}, {$per_page};";
		$results = $wpdb->get_results( $sql );
		return $results;
	}
}

/*
 * Get Car Search Result Count
 */
if ( ! function_exists( 'trav_car_get_search_result_count' ) ) {
	function trav_car_get_search_result_count( $min_price, $max_price, $car_type, $car_agent, $preferences ) {
		global $wpdb;
		$tbl_term_taxonomy = $wpdb->prefix . 'term_taxonomy';
		$tbl_term_relationships = $wpdb->prefix . 'term_relationships';
		$temp_tbl_name = trav_get_temp_table_name();
		$tbl_postmeta = esc_sql( $wpdb->prefix . 'postmeta' );
		if ( empty( $car_type ) || ! is_array( $car_type ) ) $car_type = array();
		if ( empty( $preferences ) || ! is_array( $preferences ) ) $preferences = array();
		if ( empty( $car_agent ) || ! is_array( $car_agent ) ) $car_agent = array();
		foreach ( $car_type as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $car_type[$key] );
		}
		foreach ( $preferences as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $preferences[$key] );
		}
		foreach ( $car_agent as $key=>$value ) {
			if ( ! is_numeric( $value ) ) unset( $car_agent[$key] );
		}

		//$sql = "SELECT COUNT(*) FROM {$temp_tbl_name} as t1";
		$sql = "SELECT COUNT(DISTINCT t1.car_id) FROM {$temp_tbl_name} as t1";
		$where = " 1=1";
		
		// price filter
        if ( $min_price != 0 || $max_price != 'no_max' ) {
            $sql .= " INNER JOIN {$tbl_postmeta} AS meta_price ON (t1.car_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_car_price')";
        }
        if ( $min_price != 0 ) {
            $where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
        }
        if ( $max_price != 'no_max' ) {
            $where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
        }

		if ( ! empty( $car_type ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.car_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
			$where .= " AND tt.taxonomy = 'car_type' AND tt.term_id IN (" . esc_sql( implode( ',', $car_type ) ) . ")";
		}
		if ( ! empty( $car_agent ) ) {
			$sql .= " INNER JOIN {$tbl_term_relationships} AS tr1 ON tr1.object_id = t1.car_id 
					INNER JOIN {$tbl_term_taxonomy} AS tt1 ON tt1.term_taxonomy_id = tr1.term_taxonomy_id";
			$where .= " AND tt1.taxonomy = 'car_agent' AND tt1.term_id IN (" . esc_sql( implode( ',', $car_agent ) ) . ")";
		}

		if ( ! empty( $preferences ) ) {
			$where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr2 
					INNER JOIN {$tbl_term_taxonomy} AS tt2 ON ( tr2.term_taxonomy_id= tt2.term_taxonomy_id )
					WHERE tt2.taxonomy = 'preference' AND tt2.term_id IN (" . esc_sql( implode( ',', $preferences ) ) . ") AND tr2.object_id = t1.car_id ) = " . count( $preferences ) . ")";
		}

		$sql .= " WHERE {$where}";
		$count = $wpdb->get_var( $sql );
		return $count;
	}
}