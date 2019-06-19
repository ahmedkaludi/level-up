<?php
/**
 * Car Booking Confirmation Template
 */
global $wpdb;
global $booking_data, $car_id, $deposit_rate, $date_from, $date_to;

if ( ! isset( $_REQUEST['booking_no'] ) || ! isset( $_REQUEST['pin_code'] ) ) {
	do_action('trav_car_conf_wrong_data');
	exit;
}

if ( ! $booking_data = trav_car_get_booking_data( $_REQUEST['booking_no'], $_REQUEST['pin_code'] ) ) {
	do_action('trav_car_conf_wrong_data');
	exit;
}

$car_id = trav_car_clang_id( $booking_data['car_id'] );
$deposit_rate = get_post_meta( $car_id, 'trav_car_security_deposit', true );
$deposit_rate = empty( $deposit_rate ) ? 0 : $deposit_rate;
$date_from = trav_tophptime( $booking_data['date_from'] );
$date_to = trav_tophptime( $booking_data['date_to'] );
$time_from = $booking_data['time_from'];
$time_to = $booking_data['time_to'];

$query_args = array(
				'date_from' => $date_from,
				'date_to' => $date_to,
				'edit_booking_no' => $booking_data['booking_no'],
				'pin_code' => $booking_data['pin_code'],
	);

// if deposit is required and it is not paid process payment
if ( ! empty( $deposit_rate ) && empty( $booking_data['deposit_paid'] ) ) {
	// init payment variables
	$ItemName = '';
	if ( $deposit_rate < 100 ) {
		$ItemName = sprintf( __( 'Deposit(%d%%) for ', 'trav' ), $deposit_rate );
	} else {
		$ItemName = __( 'Deposit for ', 'trav' );
	}
	$ItemName .= get_the_title( $car_id );

	$payment_data = array();
	$payment_data['item_name'] = $ItemName;
	$payment_data['item_number'] = $car_id;
	$payment_data['item_desc'] = __( 'From', 'trav' ) . ' ' . $date_from . ' ' . $time_from . ' ' . __( 'To', 'trav' ) . ' ' . $date_to . ' '. $time_to . ' ' . get_the_title( $car_id );
	$payment_data['item_qty'] = 1;
	$payment_data['item_price'] = $booking_data['deposit_price'];
	$payment_data['item_total_price'] = $payment_data['item_qty'] * $payment_data['item_price'];
	$payment_data['grand_total'] = $payment_data['item_total_price'];
	$payment_data['currency'] = strtoupper( $booking_data['currency_code'] );
	$payment_data['return_url'] = trav_get_current_page_url() . '?booking_no=' . $booking_data['booking_no'] . '&pin_code=' . $booking_data['pin_code'] . '&payment=success';
	$payment_data['cancel_url'] = trav_get_current_page_url() . '?booking_no=' . $booking_data['booking_no'] . '&pin_code=' . $booking_data['pin_code'] . '&payment=failed';
	$payment_data['status'] = '';
	$payment_data['deposit_rate'] = $deposit_rate;

	if ( ! empty( $_REQUEST['transaction_id'] ) && ! empty( $_SESSION['booking_data'][$_REQUEST['transaction_id']] ) ) $payment_data['status'] = 'before';
	$payment_result = trav_process_payment( $payment_data );

	// after payment
	if ( $payment_result ) {
		if ( ! empty( $payment_result['success'] ) && ( $payment_result['method'] == 'paypal' ) ) {
			$other_booking_data = array();
			if ( ! empty( $booking_data['other'] ) ) {
				$other_booking_data = unserialize( $booking_data['other'] );
			}
			$other_booking_data['pp_transaction_id'] = $payment_result['transaction_id'];
			$booking_data['deposit_paid'] = 1;
			$update_status = $wpdb->update( TRAV_CAR_BOOKINGS_TABLE, array( 'deposit_paid' => $booking_data['deposit_paid'], 'status' => 1, 'other' => serialize( $other_booking_data ) ), array( 'booking_no' => $booking_data['booking_no'], 'pin_code' => $booking_data['pin_code'] ) );
			if ( $update_status === false ) {
				do_action( 'trav_payment_update_booking_error' );
			} elseif ( empty( $update_status ) ) {
				do_action( 'trav_payment_update_booking_no_row' );
			} else {
				do_action( 'trav_payment_update_booking_success' );
			}
		}
	}
}

if ( ! empty( $deposit_rate ) && empty( $booking_data['deposit_paid'] ) ) {
	do_action('trav_car_deposit_payment_not_paid', $booking_data ); // deposit payment not paid
}

if ( empty( $booking_data['mail_sent'] ) && ! empty( $booking_data['status'] ) && ( empty( $deposit_rate ) || ! empty( $booking_data['deposit_paid'] ) ) ) {
	do_action('trav_car_conf_mail_not_sent', $booking_data); // mail is not sent
}

if ( ! empty( $_REQUEST['transaction_id'] ) && ! empty( $_SESSION['booking_data'] ) ) {
	unset( $_SESSION['booking_data'][$_REQUEST['transaction_id']] ); // unset session data for further action
}

if ( $booking_data['status'] == 1 ) { // if upcoming 
	trav_get_template( 'booking-success.php', '/templates/car/' ); 
} elseif ( $booking_data['status'] == 0 ) { // if cancelled 
	trav_get_template( 'booking-cancelled.php', '/templates/car/' ); 
} elseif ( $booking_data['status'] == 2 ) { // if completed 
	trav_get_template( 'booking-completed.php', '/templates/car/' ); 
}