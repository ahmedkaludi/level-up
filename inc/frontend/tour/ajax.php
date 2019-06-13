<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * Handle get tour month vacancies ajax request.
 */
if ( ! function_exists( 'trav_ajax_tour_get_available_schedules' ) ) {
    function trav_ajax_tour_get_available_schedules() {
        //validation and initiate variables
        $result_json = array( 'success' => 0, 'result' => '' );
        if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['tour_id'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-' . sanitize_text_field( $_POST['tour_id'] ) ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
            wp_send_json( $result_json );
        }
        if ( isset( $_POST['tour_id'] ) && isset( $_POST['date_from'] ) && trav_strtotime( $_POST['date_from'] ) && isset( $_POST['date_to'] ) && trav_strtotime( $_POST['date_to'] ) && ( ( time()-(60*60*24) ) < trav_strtotime( $_POST['date_from'] ) ) ) {
            ob_start();
            trav_tour_get_schedule_list_html( array('tour_id'=>$_POST['tour_id'], 'date_from'=>$_POST['date_from'], 'date_to'=>$_POST['date_to']) );
            $output = ob_get_contents();
            ob_end_clean();
            $result_json['success'] = 1;
            $result_json['result'] = $output;
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
if ( ! function_exists( 'trav_ajax_tour_submit_booking' ) ) {
    function trav_ajax_tour_submit_booking() {
        global $wpdb, $trav_options;

        // validation
        $result_json = array( 'success' => 0, 'result' => '' );

        if ( ! isset( $_POST['transaction_id'] ) || ! isset( $_SESSION['booking_data'][$_POST['transaction_id']] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, some error occurred on input data validation.', 'trav' );
            wp_send_json( $result_json );
        }

        $raw_booking_data = $_SESSION['booking_data'][$_POST['transaction_id']];
        $booking_fields = array( 'tour_id', 'st_id', 'tour_date', 'adults', 'kids', 'total_price', 'currency_code', 'exchange_rate', 'deposit_price', 'discount_rate' );
        $booking_data = array();
        foreach( $booking_fields as $booking_field ) {
            if ( ! empty( $raw_booking_data[ $booking_field ] ) ) {
                $booking_data[ $booking_field ] = $raw_booking_data[ $booking_field ];
            }
        }

        $is_payment_enabled = trav_is_payment_enabled() && ! empty( $booking_data['deposit_price'] );

        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-' . $booking_data['tour_id'] ) ) {
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
        $data['tour_date'] = date( 'Y-m-d', trav_strtotime( $data['tour_date'] ) );
        if ( is_user_logged_in() ) {
            $data['user_id'] = get_current_user_id();
        }

        $latest_booking_id = $wpdb->get_var( 'SELECT id FROM ' . TRAV_TOUR_BOOKINGS_TABLE . ' ORDER BY id DESC LIMIT 1' );
        $booking_no = mt_rand( 1000, 9999 );
        $booking_no .= $latest_booking_id;
        $pin_code = mt_rand( 1000, 9999 );

        if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
        $default_booking_data = array(  
            'first_name'            => '',
            'last_name'             => '',
            'email'                 => '',
            'country_code'          => '',
            'phone'                 => '',
            'address'               => '',
            'city'                  => '',
            'zip'                   => '',
            'country'               => '',
            'special_requirements'  => '',
            'tour_id'               => '',
            'st_id'                 => 0,
            'tour_date'             => '',
            'adults'                => '',
            'kids'                  => '',
            'total_price'           => '',
            'currency_code'         => 'usd',
            'exchange_rate'         => 1,
            'deposit_price'         => 0,
            'deposit_paid'          => ( $is_payment_enabled ? 0 : 1 ),
            'created'               => date( 'Y-m-d H:i:s' ),
            'booking_no'            => $booking_no,
            'pin_code'              => $pin_code,
            'status'                => 1,
            'discount_rate'         => '',
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
        $room_price_data = trav_tour_get_price_data( array( 'tour_id'=>$data['tour_id'], 'st_id'=>$data['st_id'], 'tour_date'=>$booking_data['tour_date'], 'adults'=>$data['adults'], 'kids'=>$data['kids'] ) );
        if ( empty( $room_price_data ) || ! is_array( $room_price_data ) ) {
            $result_json['success'] = -1;
            $result_json['result'] = __( 'Sorry, The tour you are booking now is just taken by another customer. Please have another look.', 'trav' );
            wp_send_json( $result_json );
        }

        do_action( 'trav_tour_add_booking_before', $data );

        // save default language tour and room type
        $data['tour_id'] = trav_tour_org_id( $data['tour_id'] );
        // add to db
        if ( $wpdb->insert( TRAV_TOUR_BOOKINGS_TABLE, $data ) ) {
            $data['booking_id'] = $wpdb->insert_id;
            $_SESSION['booking_data'][$_POST['transaction_id']] = $data;

            $result_json['success'] = 1;
            $result_json['result'] = array();
            $result_json['result']['booking_no'] = $booking_no;
            $result_json['result']['pin_code'] = $pin_code;
            $result_json['result']['transaction_id'] = $_POST['transaction_id'];

            if ( $is_payment_enabled ) {
                if ( trav_is_woo_enabled() ) {
                    // woocommerce
                    do_action( 'trav_woo_add_tour_booking', $data );

                    $result_json['result']['payment'] = 'woocommerce';
                } elseif ( trav_is_paypal_enabled() ) {
                    // paypal direct
                    $result_json['result']['payment'] = 'paypal';
                }
            } else {
                $result_json['result']['payment'] = 'no';
            }

            do_action( 'trav_tour_add_booking_after', $data );
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
if ( ! function_exists( 'trav_ajax_tour_cancel_booking' ) ) {
    function trav_ajax_tour_cancel_booking() {
        if ( ! isset( $_POST['edit_booking_no'] ) || ! isset( $_POST['pin_code'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, some error occurred on input data validation.', 'trav' );
            wp_send_json( $result_json );
        }
        $edit_booking_no = sanitize_text_field( $_POST['edit_booking_no'] );
        $pin_code = sanitize_text_field( $_POST['pin_code'] );
        $result = trav_tour_update_booking( $edit_booking_no, $pin_code, array( 'status' => 0 ), 'cancel' );
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