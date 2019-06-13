<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * generate js variable data to transfer cruise.js
 */
if ( ! function_exists( 'trav_get_cruise_js_data' ) ) {
	function trav_get_cruise_js_data() {
		global $post, $trav_options;
		
		$cruise_data = array();

		$cruise_data['cruise_id'] = $post->ID;

        $review_labels = array(
                            '4.75' => __('exceptional', 'trav'),
                            '4.5' => __('wonderful', 'trav'),
                            '4' => __('very good', 'trav'),
                            '3.5' => __('good', 'trav'),
                            '3' => __('pleasant', 'trav'),
                            '0' => __('disappointed', 'trav'),
        );
        $cruise_data['review_labels'] = $review_labels;
		
		if ( ! empty( $trav_options['cruise_booking_page'] ) ) {
			$cruise_data['booking_url'] = trav_get_permalink_clang( $trav_options['cruise_booking_page'] );
		}
		if ( defined('ICL_LANGUAGE_CODE') ) { $cruise_data['lang'] = ICL_LANGUAGE_CODE; }

		//messages
		$cruise_data['msg_no_booking_page'] = __( 'Please set cruise booking page on admin/Theme Options/Page Settings', 'trav' );
		$cruise_data['msg_wrong_date_1'] = __( 'Enter your date from in the search box and click Search Now button', 'trav' );
		$cruise_data['msg_wrong_date_2'] = __( 'Please select date from.', 'trav' );
		$cruise_data['msg_wrong_date_3'] = __( 'Wrong date from. Please check again.', 'trav' );
		$cruise_data['msg_wrong_date_4'] = __( 'Wrong search fields. Please check again.', 'trav' );

		return $cruise_data;
	}
}

/*
 * Check if a given cruise is available for a given month and return schedules array
 */
if ( ! function_exists( 'trav_cruise_get_month_schedules' ) ) {
	function trav_cruise_get_month_schedules( $cruise_id, $year, $month ) {
		$num = cal_days_in_month( CAL_GREGORIAN, $month, $year );
		$schedules = array();
		$date = new DateTime();
		for ( $i = 1; $i <= $num; $i++ ) {
			$date->setDate( $year, $month, $i );
			$schedules[$i] = trav_cruise_get_day_schedule( $cruise_id, $date->format('Y-m-d') );
		}
		return $schedules;
	}
}

/*
 * Check if a given cruise is available for a given day and return vacany number
 */
if ( ! function_exists( 'trav_cruise_get_day_schedule' ) ) {
	function trav_cruise_get_day_schedule( $cruise_id, $date ) {
		global $wpdb;
		$cruise_id = esc_sql( trav_cruise_org_id( $cruise_id ) );
		$date = esc_sql( $date );
		$where = '1=1';
		if ( ! empty( $cruise_id ) ) $where .= " AND cruise_id='{$cruise_id}'";
		if ( ! empty( $date ) ) $where .= " AND date_from = '{$date}'";

		$sql = "SELECT cruise_id FROM " . TRAV_CRUISE_SCHEDULES_TABLE . " AS schedules WHERE {$where} ";
		$schedule = $wpdb->get_var( $sql );
		return ( $schedule > 0 )?$schedule:0;
	}
}

/*
 * get cruise schedules
 */
if ( ! function_exists( 'trav_cruise_get_schedules' ) ) {
	function trav_cruise_get_schedules( $cruise_id, $date_from = '', $date_to = '' ) {
		global $wpdb;
		$cruise_id = esc_sql( trav_cruise_org_id( $cruise_id ) );

		if ( empty( $date_from ) ) {
			$date_from = new DateTime();
			$date_from = esc_sql( $date_from->format('Y-m-d') );
		}
		
		$where = '1=1';
		if ( ! empty( $cruise_id ) ) $where .= " AND cruise_id='{$cruise_id}'";
		if ( ! empty( $date_from ) ) $where .= " AND date_from >= '{$date_from}' ";
        if ( ! empty( $date_to ) ) $where .= " AND date_from <= '{$date_to}' ";

		$sql = "SELECT * FROM " . TRAV_CRUISE_SCHEDULES_TABLE . " WHERE {$where} ORDER BY date_from asc; ";
		$schedules = $wpdb->get_results( $sql, ARRAY_A );

		if ( empty( $schedules ) ) {
			return false;
		} else {
			return $schedules;
		}
	}
}

/*
 * Return matched cabins to given data. It is used for check availability function
 */
if ( ! function_exists( 'trav_cruise_get_available_cabins' ) ) {
    function trav_cruise_get_available_cabins( $cruise_id, $date_from, $duration=1, $cabins=1, $adults=1, $kids, $child_ages, $except_booking_no=0, $pin_code=0 ) {

        // validation
        $cruise_id = trav_cruise_org_id( $cruise_id );
        
        $date_to_obj = new DateTime( '@' . trav_strtotime( $date_from .' + ' . $duration . ' days' ) );
        $date_to = esc_sql( $date_to_obj->format( "Y-m-d" ) );

        if ( ! trav_strtotime( $date_from ) || ! trav_strtotime( $date_to ) || ( ( time()-(60*60*24) ) > trav_strtotime( $date_from ) ) ) {
            return __( 'Invalid date. Please check your booking date again.', 'trav' ); //invalid data
        }

        // initiate variables
        global $wpdb;
        if ( ! is_array($child_ages) ) $child_ages = unserialize($child_ages);

        $sql = "SELECT DISTINCT pm0.post_id FROM " . $wpdb->postmeta . " as pm0 INNER JOIN " . $wpdb->posts . " AS cabin ON (pm0.post_id = cabin.ID) AND (cabin.post_status = 'publish') AND (cabin.post_type = 'cabin_type') WHERE meta_key = 'trav_cabin_cruise' AND meta_value = " . esc_sql( $cruise_id );
        $all_cabin_ids = $wpdb->get_col( $sql );
        if ( empty( $all_cabin_ids ) ){
            return __( 'No cabins', 'trav' ); //invalid data
        }

        $avg_adults = ceil( $adults / $cabins );
        $avg_kids = ceil( $kids / $cabins );

        // get available cruise cabin_type_id based on max_adults and max_kids
        $sql = "SELECT DISTINCT pm0.post_id AS cabin_type_id FROM (SELECT * FROM " . $wpdb->postmeta . " WHERE meta_key = 'trav_cabin_cruise' AND meta_value = " . esc_sql( $cruise_id ) . " ) AS pm0 
                INNER JOIN " . $wpdb->posts . " AS cabin ON (pm0.post_id = cabin.ID) AND (cabin.post_status = 'publish') AND (cabin.post_type = 'cabin_type')
                INNER JOIN " . $wpdb->postmeta . " AS pm1 ON (pm0.post_id = pm1.post_id) AND (pm1.meta_key = 'trav_cabin_max_adults')
                LEFT JOIN " . $wpdb->postmeta . " AS pm2 ON (pm0.post_id = pm2.post_id) AND (pm2.meta_key = 'trav_cabin_max_kids')
                WHERE ( pm1.meta_value >= " . esc_sql( $avg_adults ) . " ) AND ( pm1.meta_value + IFNULL(pm2.meta_value,0) >= " . esc_sql( $avg_adults + $avg_kids ) . " )";

        $matched_cabin_ids = $wpdb->get_col( $sql ); //object (cabin_type_id)

        if ( empty( $matched_cabin_ids ) ){
            $return_value = array(
                'all_cabin_type_ids' => $all_cabin_ids,
                'matched_cabin_type_ids' => array(),
                'bookable_cabin_type_ids' => array(),
                'check_dates' => array(),
                'prices' => array()
            );
            return $return_value;
        }

        // get available cruise cabin_type_id and price based on date
        // initiate variables
        $check_dates = array();
        $price_data = array();
        $total_price_data = array();

        // prepare date for loop
        $date_from_obj = new DateTime( '@' . trav_strtotime( $date_from ) );
        $date_to_obj = new DateTime( '@' . trav_strtotime( $date_to ) );
        // $date_to_obj = $date_to_obj->modify( '+1 day' ); 
        $date_interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($date_from_obj, $date_interval, $date_to_obj);

        $cruise_id = esc_sql( $cruise_id );
        $cabins = esc_sql( $cabins );
        $adults = esc_sql( $adults );
        $kids = esc_sql( $kids );
        $child_ages = esc_sql( $child_ages );
        $except_booking_no = esc_sql( $except_booking_no );
        $pin_code = esc_sql( $pin_code );

        $bookable_cabin_ids = $matched_cabin_ids;

        foreach ( $period as $dt ) {
            $check_date = esc_sql( $dt->format( "Y-m-d" ) );
            $check_dates[] = $check_date;

            $sql = "SELECT vacancies.cabin_type_id, vacancies.price_per_cabin , vacancies.price_per_person, vacancies.child_price
                    FROM (SELECT cabin_type_id, cabins, price_per_cabin, price_per_person, child_price
                            FROM " . TRAV_CRUISE_VACANCIES_TABLE . " 
                            WHERE 1=1 AND cruise_id='" . $cruise_id . "' AND cabin_type_id IN (" . implode( ',', $bookable_cabin_ids ) . ") AND date_from <= '" . $check_date . "'  AND date_to > '" . $check_date . "' ) AS vacancies
                    LEFT JOIN (SELECT cabin_type_id, SUM(cabins) AS cabins 
                            FROM " . TRAV_CRUISE_BOOKINGS_TABLE . " 
                            WHERE 1=1 AND status!='0' AND cruise_id='" . $cruise_id . "' AND date_to > '" . $check_date . "'  AND date_from <= '" . $check_date . "'" . ( ( empty( $except_booking_no ) || empty( $pin_code ) )?"":( " AND NOT ( booking_no = '" . $except_booking_no . "' AND pin_code = '" . $pin_code . "' )" ) ) . " GROUP BY cabin_type_id
                    ) AS bookings ON vacancies.cabin_type_id = bookings.cabin_type_id
                    WHERE vacancies.cabins - IFNULL(bookings.cabins,0) >= " . $cabins . ";";

            $results = $wpdb->get_results( $sql ); // object (cabin_type_id, price_per_cabin, price_per_person, child_price)

            if ( empty( $results ) ) { //if no available cabins on selected date
                $return_value = array(
                    'all_cabin_type_ids' => $all_cabin_ids,
                    'matched_cabin_type_ids' => $matched_cabin_ids,
                    'bookable_cabin_type_ids' => array(),
                    'check_dates' => array(),
                    'prices' => array(),
                );
                return $return_value;
            }

            $day_available_cabin_type_ids = array();

            foreach ( $results as $result ) {
                $day_available_cabin_type_ids[] = $result->cabin_type_id;
                $price_per_cabin = (float) $result->price_per_cabin;
                $price_per_person = (float) $result->price_per_person;
                $child_price_data = unserialize( $result->child_price );

                //calculate child price
                $child_price = array();
                $total_child_price = 0;

                if ( ( $kids > 0 ) && ( ! empty( $child_price_data ) ) && ( ! empty( $child_ages ) ) ) {

                    usort($child_price_data, function($a, $b) { return $a[0] - $b[0]; });

                    foreach ( $child_ages as $child_age ) {
                        $is_child = false;
                        foreach ( $child_price_data as $age_price_pair ) {
                            if ( is_array( $age_price_pair ) && ( count( $age_price_pair ) >= 2 ) && ( (int) $child_age <= (int) $age_price_pair[0] ) ) {
                                $is_child = true;
                                $child_price[] = (float) $age_price_pair[1];
                                $total_child_price += (float) $age_price_pair[1];
                                break;
                            }
                        }

                        //if child price for this age is not set, calculate as a adult
                        if ( ! $is_child ) {
                            $child_price[] = $price_per_person;
                            $total_child_price += $price_per_person;
                        }
                    }
                }

                $total_price = $price_per_cabin * $cabins + $price_per_person * $adults + $total_child_price;
                $price_data[ $result->cabin_type_id ][ $check_date ] = array(
                    'ppr' => $price_per_cabin,
                    'ppp' => $price_per_person,
                    'cp' => $child_price,
                    'total' => $total_price
                );
            }

            $bookable_cabin_ids = $day_available_cabin_type_ids;
        }

        //$number_of_days = count( $check_dates );
        $return_value = array(
            'all_cabin_type_ids' => $all_cabin_ids,
            'matched_cabin_type_ids' => $matched_cabin_ids,
            'bookable_cabin_type_ids' => $bookable_cabin_ids,
            'check_dates' => $check_dates,
            'prices' => $price_data
        );

        return $return_value;
    }
}

/*
 * cruise booking page before action
 */
if ( ! function_exists( 'trav_cruise_booking_before' ) ) {
    function trav_cruise_booking_before() {
        global $trav_options, $def_currency;

        // prevent direct access
        if ( ! isset( $_REQUEST['booking_data'] ) ) {
            do_action('trav_cruise_booking_wrong_data');
            exit;
        }

        // init booking data : array( 'cruise_id', 'cabin_type_id', 'date_from', 'date_to', 'duration', 'cabins', 'adults', 'kids', 'child_ages' );
        $raw_booking_data = '';
        parse_str( $_REQUEST['booking_data'], $raw_booking_data );

        //verify nonce
        if ( ! isset( $raw_booking_data['_wpnonce'] ) || ! wp_verify_nonce( $raw_booking_data['_wpnonce'], 'post-' . $raw_booking_data['cruise_id'] ) ) {
            do_action('trav_cruise_booking_wrong_data');
            exit;
        }

        if ( isset( $raw_booking_data['date_from'] ) && isset( $raw_booking_data['duration'] ) ) {
            $date_to_obj = new DateTime( '@' . trav_strtotime( $raw_booking_data['date_from'] .' + ' . $raw_booking_data['duration'] . ' days' ) );
            $raw_booking_data['date_to'] = $date_to_obj->format('Y-m-d');
        }

        // init booking_data fields
        $booking_fields = array( 'cruise_id', 'cabin_type_id', 'date_from', 'date_to', 'duration', 'cabins', 'adults', 'kids', 'child_ages' );
        $booking_data = array();
        foreach ( $booking_fields as $field ) {
            if ( ! isset( $raw_booking_data[ $field ] ) ) {
                do_action('trav_cruise_booking_wrong_data');
                exit;
            } else {
                $booking_data[ $field ] = $raw_booking_data[ $field ];
            }
        }

        // make an array for redirect url generation
        $query_args = array(
            'date_from'     => $booking_data['date_from'],
            'duration'       => $booking_data['duration'],
            'cabins'         => $booking_data['cabins'],
            'adults'        => $booking_data['adults'],
            'kids'          => $booking_data['kids'],
            'child_ages'    => $booking_data['child_ages'],
        );

        // get price data
        $cabin_price_data = trav_cruise_get_cabin_price_data( $booking_data['cruise_id'], $booking_data['cabin_type_id'], $booking_data['date_from'], $booking_data['duration'], $booking_data['cabins'], $booking_data['adults'], $booking_data['kids'], $booking_data['child_ages'] );
        $cruise_url = get_permalink( $booking_data['cruise_id'] );
        $edit_url = add_query_arg( $query_args, $cruise_url );

        // redirect if $cabin_price_data is not valid
        if ( ! $cabin_price_data || ! is_array( $cabin_price_data ) ) {
            $query_args['error'] = 1;
            wp_redirect( $edit_url );
        }

        // calculate tax, discount and total price
        $is_discount = get_post_meta( $booking_data['cruise_id'], 'trav_cruise_hot', true );
        $discount_rate = get_post_meta( $booking_data['cruise_id'], 'trav_cruise_discount_rate', true );
        $tax_rate = get_post_meta( $booking_data['cruise_id'], 'trav_cruise_tax_rate', true );
        $tax = 0;
        if ( ! empty( $tax_rate ) ) {
            $tax = $tax_rate * $cabin_price_data['total_price'] / 100;
        }
        if ( ! empty( $is_discount ) && ! empty( $discount_rate ) && ( $discount_rate > 0 ) && ( $discount_rate <= 100 ) ) { 
            $booking_data['discount_rate'] = $discount_rate;
        } else { 
            $booking_data['discount_rate'] = 0;
        }

        $booking_data['cabin_price'] = $cabin_price_data['total_price'];
        $booking_data['tax'] = $tax;
        $booking_data['total_price'] = ( $booking_data['cabin_price'] + $booking_data['tax'] ) * ( 100 - $booking_data['discount_rate'] ) / 100;

        // calculate deposit payment
        $deposit_rate = get_post_meta( $booking_data['cruise_id'], 'trav_cruise_security_deposit', true );

        // if woocommerce-integration enabled, change currency_code and exchange rate as default
        if ( ! empty( $deposit_rate ) && trav_is_woo_enabled() ) {
            $booking_data['currency_code'] = $def_currency;
            $booking_data['exchange_rate'] = 1;
        } else {
            if ( ! isset( $_SESSION['exchange_rate'] ) ) {
                trav_init_currency();
            }
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
        $_SESSION['booking_data'][$transaction_id] = $booking_data; //'cruise_id', 'cabin_type_id', 'date_from', 'date_to', 'duration', 'cabins', 'adults', 'kids', 'child_ages', cabin_price, tax, total_price, currency_code, exchange_rate, deposit_price

        $review = get_post_meta( trav_cruise_org_id( $booking_data['cruise_id'] ), 'review', true );
        $review = ( ! empty( $review ) )?round( $review, 1 ):0;

        // thank you page url
        $cruise_book_conf_url = '';
        if ( ! empty( $trav_options['cruise_booking_confirmation_page'] ) ) {
            $cruise_book_conf_url = trav_get_permalink_clang( $trav_options['cruise_booking_confirmation_page'] );
        } else {
            // thank you page is not set
        }

        global $trav_booking_page_data;
        $trav_booking_page_data['transaction_id'] = $transaction_id;
        $trav_booking_page_data['review'] = $review;
        $trav_booking_page_data['cruise_url'] = $cruise_url;
        $trav_booking_page_data['edit_url'] = $edit_url;
        $trav_booking_page_data['booking_data'] = $booking_data;
        $trav_booking_page_data['cabin_price_data'] = $cabin_price_data;
        $trav_booking_page_data['is_payment_enabled'] = $is_payment_enabled;
        $trav_booking_page_data['cruise_book_conf_url'] = $cruise_book_conf_url;
        $trav_booking_page_data['tax'] = $tax;
        $trav_booking_page_data['tax_rate'] = $tax_rate;
        $trav_booking_page_data['discount_rate'] = $discount_rate;
    }
}

/*
 * Calculate the price of selected cruise cabin and return price array data
 */
if ( ! function_exists( 'trav_cruise_get_cabin_price_data' ) ) {
    function trav_cruise_get_cabin_price_data( $cruise_id, $cabin_type_id, $date_from, $duration, $cabins=1, $adults=1, $kids=0, $child_ages, $except_booking_no=0, $pin_code=0 ) {
        global $wpdb;

        $cruise_id = trav_cruise_org_id( $cruise_id );
        $cabin_type_id = trav_cabin_org_id( $cabin_type_id );

        //validation
        if ( ! is_array( $child_ages ) ){$child_ages = unserialize($child_ages);}

        $cabin_cruise_id = get_post_meta( $cabin_type_id, 'trav_cabin_cruise', true );
        if ( $cabin_cruise_id != $cruise_id ) return false;

        $max_adults = get_post_meta( $cabin_type_id, 'trav_cabin_max_adults', true ); if ( empty($max_adults) ) $max_adults = 0;
        $max_kids = get_post_meta( $cabin_type_id, 'trav_cabin_max_kids', true ); if ( empty($max_adults) ) $max_kids = 0;
        $avg_adults = ceil( $adults / $cabins );
        $avg_kids = ceil( $kids / $cabins );
        if ( ( $avg_adults > $max_adults ) || ( ( $avg_adults + $avg_kids ) > ( $max_adults + $max_kids ) ) ) return false;

        if ( ( time()-( 60*60*24 ) ) > trav_strtotime( $date_from ) ) return false;

        $date_from_obj = new DateTime( '@' . trav_strtotime( $date_from ) );
        $date_to_obj = new DateTime( '@' . trav_strtotime( $date_from .' + ' . $duration . ' days' ) );

        $date_interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($date_from_obj, $date_interval, $date_to_obj);

        $price_data = array();
        $total_price = 0.0;

        $cruise_id = esc_sql( $cruise_id );
        $cabin_type_id = esc_sql( $cabin_type_id );
        $cabins = esc_sql( $cabins );
        $adults = esc_sql( $adults );
        $kids = esc_sql( $kids );
        $child_ages = esc_sql( $child_ages );
        $except_booking_no = esc_sql( $except_booking_no );
        $pin_code = esc_sql( $pin_code );

        foreach ( $period as $dt ) {

            $check_date = esc_sql( $dt->format( "Y-m-d" ) );
            $check_dates[] = $check_date;

            $sql = "SELECT vacancies.cabin_type_id, vacancies.price_per_cabin , vacancies.price_per_person, vacancies.child_price
                    FROM (SELECT cabin_type_id, cabins, price_per_cabin, price_per_person, child_price
                            FROM " . TRAV_CRUISE_VACANCIES_TABLE . " 
                            WHERE 1=1 AND cruise_id='" . $cruise_id . "' AND cabin_type_id = '" . $cabin_type_id . "' AND date_from <= '" . $check_date . "'  AND date_to > '" . $check_date . "' ) AS vacancies
                    LEFT JOIN (SELECT cabin_type_id, SUM(cabins) AS cabins 
                            FROM " . TRAV_CRUISE_BOOKINGS_TABLE . " 
                            WHERE 1=1 AND status!='0' AND cruise_id='" . $cruise_id . "' AND cabin_type_id = '" . $cabin_type_id . "' AND date_to > '" . $check_date . "' AND date_from <= '" . $check_date . "'" . ( ( empty( $except_booking_no ) || empty( $pin_code ) )?"":( " AND NOT ( booking_no = '" . $except_booking_no . "' AND pin_code = '" . $pin_code . "' )" ) ) . "
                    ) AS bookings ON vacancies.cabin_type_id = bookings.cabin_type_id
                    WHERE vacancies.cabins - IFNULL(bookings.cabins,0) >= " . $cabins . ";";
            $result = $wpdb->get_row( $sql ); // object (cabin_type_id, price_per_cabin, price_per_person, child_price)

            if ( empty( $result ) ) { //if no available cabins on selected date
                return false;
            } else {
                $price_per_cabin = (float) $result->price_per_cabin;
                $price_per_person = (float) $result->price_per_person;
                $child_price_data = unserialize( $result->child_price );

                $child_price = array();
                $total_child_price = 0;

                if ( ( $kids > 0 ) && ( ! empty( $child_price_data ) ) && ( ! empty( $child_ages ) ) ) {

                    usort($child_price_data, function($a, $b) { return $a[0] - $b[0]; });

                    foreach ( $child_ages as $child_age ) {
                        $is_child = false;
                        foreach ( $child_price_data as $age_price_pair ) {
                            if ( is_array( $age_price_pair ) && ( count( $age_price_pair ) >= 2 ) && ( (int) $child_age <= (int) $age_price_pair[0] ) ) {
                                $is_child = true;
                                $child_price[] = (float) $age_price_pair[1];
                                $total_child_price += (float) $age_price_pair[1];
                                break;
                            }
                        }

                        //if child price for this age is not set, calculate as a adult
                        if ( ! $is_child ) {
                            $child_price[] = $price_per_person;
                            $total_child_price += $price_per_person;
                        }
                    }
                }

                $day_price = $price_per_cabin * $cabins + $price_per_person * $adults + $total_child_price;
                $price_data[ $check_date ] = array(
                    'ppr' => $price_per_cabin,
                    'ppp' => $price_per_person,
                    'cp' => $child_price,
                    'total' => $day_price
                );
                $total_price += $day_price;
            }
        }

        $return_value = array(
            'check_dates' => $check_dates,
            'prices'      => $price_data,
            'total_price' => $total_price
        );

        return $return_value;
    }
}

/*
 * get booking data with booking_no and pin_code
 */
if ( ! function_exists( 'trav_cruise_get_booking_data' ) ) {
    function trav_cruise_get_booking_data( $booking_no, $pin_code ) {
        global $wpdb;
        return $wpdb->get_row( 'SELECT * FROM ' . TRAV_CRUISE_BOOKINGS_TABLE . ' WHERE booking_no="' . esc_sql( $booking_no ) . '" AND pin_code="' . esc_sql( $pin_code ) . '"', ARRAY_A );
    }
}

/*
 * echo deposit payment not paid notice on confirmation page
 */
if ( ! function_exists( 'trav_cruise_deposit_payment_not_paid' ) ) {
    function trav_cruise_deposit_payment_not_paid( $booking_data ) {
        echo '<div class="alert alert-notice">' . __( 'Deposit amount is not paid.', 'trav' ) . '<span class="close"></span></div>';
    }
}

/*
 * send confirmation email
 */
if ( ! function_exists( 'trav_cruise_conf_send_mail' ) ) {
    function trav_cruise_conf_send_mail( $booking_data ) {
        global $wpdb;
        $mail_sent = 0;
        if ( trav_cruise_send_confirmation_email( $booking_data['booking_no'], $booking_data['pin_code'], 'new' ) ) {
            $mail_sent = 1;
            $wpdb->update( TRAV_CRUISE_BOOKINGS_TABLE, array( 'mail_sent' => $mail_sent ), array( 'booking_no' => $booking_data['booking_no'], 'pin_code' => $booking_data['pin_code'] ), array( '%d' ), array( '%d','%d' ) );
        }
    }
}

/*
 * send booking confirmation email function
 */
if ( ! function_exists( 'trav_cruise_send_confirmation_email' ) ) {
    function trav_cruise_send_confirmation_email( $booking_no, $booking_pincode, $type='new', $subject='', $description='' ) {
        global $wpdb, $logo_url, $trav_options;

        $booking_data = trav_cruise_get_booking_data( $booking_no, $booking_pincode );

        if ( ! empty( $booking_data ) ) {
            // server variables
            $admin_email = get_option('admin_email');
            $home_url = esc_url( home_url() );
            $site_name = $_SERVER['SERVER_NAME'];
            $logo_url = esc_url( $logo_url );
            $cruise_book_conf_url = trav_cruise_get_book_conf_url();
            $booking_data['cruise_id'] = trav_cruise_clang_id( $booking_data['cruise_id'] );
            $booking_data['cabin_type_id'] = trav_cabin_clang_id( $booking_data['cabin_type_id'] );

            // cruise info
            $cruise_name = get_the_title( $booking_data['cruise_id'] );
            $cruise_url = esc_url( trav_get_permalink_clang( $booking_data['cruise_id'] ) );
            $cruise_thumbnail = get_the_post_thumbnail( $booking_data['cruise_id'], 'list-thumb' );
            $cruise_phone = get_post_meta( $booking_data['cruise_id'], 'trav_cruise_phone', true );
            $cruise_cabin_name = esc_html( get_the_title( $booking_data['cabin_type_id'] ) );
            $check_in_time = $booking_data['date_from'];
            $check_out_time = $booking_data['date_to'];

            // booking info
            $booking_no = $booking_data['booking_no'];
            $booking_pincode = $booking_data['pin_code'];
            $date_from = new DateTime( $booking_data['date_from'] );
            $date_to = new DateTime( $booking_data['date_to'] );
            $number1 = $date_from->format('U');
            $number2 = $date_to->format('U');
            $booking_nights = ($number2 - $number1)/(3600*24);
            $booking_checkin_time = date( 'l, F, j, Y', trav_strtotime($booking_data['date_from']) );
            $booking_checkout_time = date( 'l, F, j, Y', trav_strtotime($booking_data['date_to']) );
            $booking_cabins = $booking_data['cabins'];
            $booking_adults = $booking_data['adults'];
            $booking_kids = $booking_data['kids'];
            $booking_cabin_price = esc_html( trav_get_price_field( $booking_data['cabin_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
            $booking_tax = esc_html( trav_get_price_field( $booking_data['tax'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
            $booking_discount = $booking_data['discount_rate'] . '%';
            $booking_total_price = esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) );
            $booking_deposit_price = esc_html( $booking_data['deposit_price'] . $booking_data['currency_code'] );
            $booking_deposit_paid = esc_html( empty( $booking_data['deposit_paid'] ) ? 'No' : 'Yes' );
            $booking_update_url = esc_url( add_query_arg( array( 'booking_no'=>$booking_data['booking_no'], 'pin_code'=>$booking_data['pin_code'] ), $cruise_book_conf_url ) );

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

            $variables = array( 
                'home_url',
                'site_name',
                'logo_url',
                'cruise_name',
                'cruise_url',
                'cruise_thumbnail',
                'cruise_phone',
                'cruise_cabin_name',
                'booking_no',
                'booking_pincode',
                'booking_nights',
                'booking_checkin_time',
                'booking_checkout_time',
                'booking_cabins',
                'booking_adults',
                'booking_kids',
                'booking_cabin_price',
                'booking_tax',
                'booking_discount',
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
                'customer_special_requirements'
            );

            if ( empty( $subject ) ) {
                if ( $type == 'new' ) {
                    $subject = empty( $trav_options['cruise_confirm_email_subject'] ) ? 'Booking Confirmation Email Subject' : $trav_options['cruise_confirm_email_subject'];
                } elseif ( $type == 'update' ) {
                    $subject = empty( $trav_options['cruise_update_email_subject'] ) ? 'Booking Updated Email Subject' : $trav_options['cruise_update_email_subject'];
                } elseif ( $type == 'cancel' ) {
                    $subject = empty( $trav_options['cruise_cancel_email_subject'] ) ? 'Booking Canceled Email Subject' : $trav_options['cruise_cancel_email_subject'];
                }
            }

            if ( empty( $description ) ) {
                if ( $type == 'new' ) {
                    $description = empty( $trav_options['cruise_confirm_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['cruise_confirm_email_description'];
                } elseif ( $type == 'update' ) {
                    $description = empty( $trav_options['cruise_update_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['cruise_update_email_description'];
                } elseif ( $type == 'cancel' ) {
                    $description = empty( $trav_options['cruise_cancel_email_description'] ) ? 'Booking Confirmation Email Description' : $trav_options['cruise_cancel_email_description'];
                }
            }

            foreach ( $variables as $variable ) {
                $subject = str_replace( "[" . $variable . "]", $$variable, $subject );
                $description = str_replace( "[" . $variable . "]", $$variable, $description );
            }

            // if ( ! empty( $trav_options['cruise_confirm_email_ical'] ) && ( $type == 'new' ) ) {
            //     $mail_sent = trav_send_ical_event( $site_name, $admin_email, $customer_first_name . ' ' . $customer_last_name, $customer_email, $check_in_time, $check_out_time, $subject, $description, $cruise_address);
            // } else {
                $mail_sent = trav_send_mail( $site_name, $admin_email, $customer_email, $subject, $description );
            // }

            /* mailing function to business owner */
            $bowner_address = '';
            if ( ! empty( $trav_options['cruise_booked_notify_bowner'] ) ) {

                if ( $type == 'new' ) {
                    $subject = empty( $trav_options['cruise_bowner_email_subject'] ) ? 'You received a booking' : $trav_options['cruise_bowner_email_subject'];
                    $description = empty( $trav_options['cruise_bowner_email_description'] ) ? 'Booking Details' : $trav_options['cruise_bowner_email_description'];
                } elseif ( $type == 'update' ) {
                    $subject = empty( $trav_options['cruise_update_bowner_email_subject'] ) ? 'A booking is updated' : $trav_options['cruise_update_bowner_email_subject'];
                    $description = empty( $trav_options['cruise_update_bowner_email_description'] ) ? 'Booking Details' : $trav_options['cruise_update_bowner_email_description'];
                } elseif ( $type == 'cancel' ) {
                    $subject = empty( $trav_options['cruise_cancel_bowner_email_subject'] ) ? 'A booking is canceled' : $trav_options['cruise_cancel_bowner_email_subject'];
                    $description = empty( $trav_options['cruise_cancel_bowner_email_description'] ) ? 'Booking Details' : $trav_options['cruise_cancel_bowner_email_description'];
                }

                foreach ( $variables as $variable ) {
                    $subject = str_replace( "[" . $variable . "]", $$variable, $subject );
                    $description = str_replace( "[" . $variable . "]", $$variable, $description );
                }

                if ( ! empty( $cruise_email ) ) {
                    $bowner_address = $cruise_email;
                } else {
                    $post_author_id = get_post_field( 'post_author', $booking_data['cruise_id'] );
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
            if ( ! empty( $trav_options['cruise_booked_notify_admin'] ) ) {
                if ( $bowner_address != $admin_email ) {
                    if ( $type == 'new' ) {
                        $subject = empty( $trav_options['cruise_admin_email_subject'] ) ? 'You received a booking' : $trav_options['cruise_admin_email_subject'];
                        $description = empty( $trav_options['cruise_admin_email_description'] ) ? 'Booking Details' : $trav_options['cruise_admin_email_description'];
                    } elseif ( $type == 'update' ) {
                        $subject = empty( $trav_options['cruise_update_admin_email_subject'] ) ? 'A booking is updated' : $trav_options['cruise_update_admin_email_subject'];
                        $description = empty( $trav_options['cruise_update_admin_email_description'] ) ? 'Booking Details' : $trav_options['cruise_update_admin_email_description'];
                    } elseif ( $type == 'cancel' ) {
                        $subject = empty( $trav_options['cruise_cancel_admin_email_subject'] ) ? 'A booking is canceled' : $trav_options['cruise_cancel_admin_email_subject'];
                        $description = empty( $trav_options['cruise_cancel_admin_email_description'] ) ? 'Booking Details' : $trav_options['cruise_cancel_admin_email_description'];
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
 * get booking confirmation url
 */
if ( ! function_exists( 'trav_cruise_get_book_conf_url' ) ) {
    function trav_cruise_get_book_conf_url() {
        global $trav_options;
        $cruise_book_conf_url = '';
        if ( isset( $trav_options['cruise_booking_confirmation_page'] ) && ! empty( $trav_options['cruise_booking_confirmation_page'] ) ) {
            $cruise_book_conf_url = trav_get_permalink_clang( $trav_options['cruise_booking_confirmation_page'] );
        }
        return $cruise_book_conf_url;
    }
}

/*
 * function to update booking
 */
if ( ! function_exists( 'trav_cruise_update_booking' ) ) {
    function trav_cruise_update_booking( $booking_no, $pin_code, $new_data, $action='update' ) {
        global $wpdb, $trav_options;
        $result = $wpdb->update( TRAV_CRUISE_BOOKINGS_TABLE, $new_data, array( 'booking_no' => $booking_no, 'pin_code' => $pin_code ) );
        if ( $result ) {
            trav_cruise_send_confirmation_email( $booking_no, $pin_code, $action );
            return $result;
        }
        return false;
    }
}

/*
 * get booking default values
 */
if ( ! function_exists( 'trav_cruise_default_booking_data' ) ) {
    function trav_cruise_default_booking_data( $type='new' ) {
        $default_booking_data = array(  
            'first_name'        => '',
            'last_name'         => '',
            'email'             => '',
            'country_code'      => '',
            'phone'             => '',
            'address'           => '',
            'city'              => '',
            'zip'               => '',
            'country'           => '',
            'special_requirements' => '',
            'cruise_id'  => '',
            'cabin_type_id'      => '',
            'cabins'             => '',
            'adults'            => '',
            'kids'              => '',
            'child_ages'        => '',
            'cabin_price'        => '',
            'tax'               => '',
            'discount_rate'     => '',
            'total_price'       => '',
            'currency_code'     => '',
            'exchange_rate'     => 1,
            'deposit_price'     => 0,
            'deposit_paid'      => 1,
            'date_from'         => '',
            'date_to'           => '',
            'duration'          => '',
            'booking_no'        => '',
            'pin_code'          => '',
            'status'            => 1,
            'updated'           => date( 'Y-m-d H:i:s' ),
        );
        if ( $type == 'new' ) {
            $additional_info = array( 
                'user_id' => '',
                'created' => date( 'Y-m-d H:i:s' ),
                'mail_sent' => '',
                'other' => '',
                'id' => '' 
            );

            $default_booking_data = array_merge( $default_booking_data, $additional_info );
        }

        return $default_booking_data;
    }
}

/*
 * Get Cruise Search Result
 */
if ( ! function_exists( 'trav_cruise_get_search_result' ) ) {
    function trav_cruise_get_search_result( $s='', $date_from='', $date_to='', $cabins=1, $adults=1, $kids=0, $order_by='cruise_title', $order='ASC', $last_no=0, $per_page=12, $min_price=0, $max_price='no_max', $rating=0, $cruise_type, $amenities, $cruise_line, $duration='no_max' ) {
        // if wrong date return false
        if ( ! empty( $date_from ) && ! empty( $date_to ) && ( trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) ) {
            return false;
        }

        global $wpdb, $language_count;

        $tbl_posts = esc_sql( $wpdb->posts );
        $tbl_postmeta = esc_sql( $wpdb->postmeta );
        $tbl_terms = esc_sql( $wpdb->prefix . 'terms' );
        $tbl_term_taxonomy = esc_sql( $wpdb->prefix . 'term_taxonomy' );
        $tbl_term_relationships = esc_sql( $wpdb->prefix . 'term_relationships' );
        $tbl_icl_translations = esc_sql( $wpdb->prefix . 'icl_translations' );

        $temp_tbl_name = trav_get_temp_table_name();
        $sql = '';
        $cabins = esc_sql( $cabins );
        $adults = esc_sql( $adults );
        $kids = esc_sql( $kids );
        $order_by = esc_sql( $order_by );
        $order = esc_sql( $order );
        $last_no = esc_sql( $last_no );
        $per_page = esc_sql( $per_page );
        $min_price = esc_sql( $min_price );
        $max_price = esc_sql( $max_price );
        $rating = esc_sql( $rating );
        $duration = esc_sql( $duration );
        $date_from = trav_tosqltime( $date_from );
        if ( empty( $date_from ) ) {
            $date_from = date("Y-m-d");
        }
        $date_to = trav_tosqltime( $date_to );

        if ( empty( $cruise_type ) || ! is_array( $cruise_type ) ) $cruise_type = array();
        if ( empty( $amenities ) || ! is_array( $amenities ) ) $amenities = array();
        if ( empty( $cruise_line ) || ! is_array( $cruise_line ) ) $cruise_line = array();
        foreach ( $cruise_type as $key=>$value ) {
            if ( ! is_numeric( $value ) ) unset( $cruise_type[$key] );
        }
        foreach ( $amenities as $key=>$value ) {
            if ( ! is_numeric( $value ) ) unset( $amenities[$key] );
        }
        foreach ( $cruise_line as $key=>$value ) {
            if ( ! is_numeric( $value ) ) unset( $cruise_line[$key] );
        }

        //mysql escape sting and like escape
        if ( floatval( get_bloginfo( 'version' ) ) >= 4.0 ) {
            $s = esc_sql( $wpdb->esc_like( $s ) );
        } else {
            $s = esc_sql( like_escape( $s ) );
        }

        //$from_date_obj = date_create_from_format( trav_get_date_format('php'), $date_from );
        //$to_date_obj = date_create_from_format( trav_get_date_format('php'), $date_to );

        $sql = ''; // sql for search keyword

        if ( ! empty( $s ) ) {
            $sql = "SELECT DISTINCT post_s1.ID AS cruise_id FROM {$tbl_posts} AS post_s1 
                        LEFT JOIN {$tbl_postmeta} AS meta_s1 ON post_s1.ID = meta_s1.post_id
                        LEFT JOIN " . TRAV_CRUISE_SCHEDULES_TABLE . " AS schedules ON schedules.cruise_id = post_s1.ID 
                        WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'cruise')
                          AND ((post_s1.post_title LIKE '%{$s}%') 
                            OR (post_s1.post_content LIKE '%{$s}%')
                            OR (meta_s1.meta_value LIKE '%{$s}%')
                            OR (schedules.departure LIKE '%{$s}%')
                            OR (schedules.arrival LIKE '%{$s}%'))";
        } else {
            $sql = "SELECT DISTINCT post_s1.ID AS cruise_id FROM {$tbl_posts} AS post_s1 
                    LEFT JOIN " . TRAV_CRUISE_SCHEDULES_TABLE . " AS schedules ON schedules.cruise_id = post_s1.ID 
                        WHERE (post_s1.post_status = 'publish') AND (post_s1.post_type = 'cruise')";
        }

        if ( $date_from ) {
            $sql .= " AND schedules.date_from >= '{$date_from}' ";
        }
        if ( $date_to ) {
            $sql .= " AND schedules.date_to <= '{$date_to}' ";   
        }

        // if wpml is enabled do search by default language post
        if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) ) {
            $sql = "SELECT DISTINCT it2.element_id AS cruise_id FROM ({$sql}) AS t0
                        INNER JOIN {$tbl_icl_translations} it1 ON (it1.element_type = 'post_cruise') AND it1.element_id = t0.cruise_id
                        INNER JOIN {$tbl_icl_translations} it2 ON (it2.element_type = 'post_cruise') AND it2.language_code='" . trav_get_default_language() . "' AND it2.trid = it1.trid ";
        }

        // if wpml is enabled return current language posts
        if ( defined('ICL_LANGUAGE_CODE') && ( $language_count > 1 ) && ( trav_get_default_language() != ICL_LANGUAGE_CODE ) ) {
            $sql = "SELECT DISTINCT it4.element_id AS cruise_id FROM ({$sql}) AS t5
                    INNER JOIN {$tbl_icl_translations} it3 ON (it3.element_type = 'post_cruise') AND it3.element_id = t5.cruise_id
                    INNER JOIN {$tbl_icl_translations} it4 ON (it4.element_type = 'post_cruise') AND it4.language_code='" . ICL_LANGUAGE_CODE . "' AND it4.trid = it3.trid";
        }


        $sql = "CREATE TEMPORARY TABLE IF NOT EXISTS {$temp_tbl_name} AS " . $sql;
        $wpdb->query( $sql );

        $sql = "SELECT t1.*, post_l1.post_title as cruise_title, meta_rating.meta_value as review, meta_price.meta_value as avg_price FROM {$temp_tbl_name} as t1
                INNER JOIN {$tbl_posts} post_l1 ON (t1.cruise_id = post_l1.ID) AND (post_l1.post_status = 'publish') AND (post_l1.post_type = 'cruise')
                LEFT JOIN {$tbl_postmeta} AS meta_rating ON (t1.cruise_id = meta_rating.post_id) AND (meta_rating.meta_key = 'review')
                LEFT JOIN {$tbl_postmeta} AS meta_price ON (t1.cruise_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_cruise_avg_price')";
        $where = ' 1=1';

        if ( $min_price != 0 ) {
            $where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
        }
        if ( $max_price != 'no_max' ) {
            $where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
        }

        // rating filter
        if ( $rating != 0 ) {
            $where .= " AND cast(meta_rating.meta_value as DECIMAL(2,1)) >= {$rating} ";
        }

        if ( ! empty( $cruise_type ) ) {
            $sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.cruise_id 
                    INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
            $where .= " AND tt.taxonomy = 'cruise_type' AND tt.term_id IN (" . esc_sql( implode( ',', $cruise_type ) ) . ")";
        }

        if ( ! empty( $amenities ) ) {
            $where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr1 
                    INNER JOIN {$tbl_term_taxonomy} AS tt1 ON ( tr1.term_taxonomy_id= tt1.term_taxonomy_id )
                    WHERE tt1.taxonomy = 'amenity' AND tt1.term_id IN (" . esc_sql( implode( ',', $amenities ) ) . ") AND tr1.object_id = t1.cruise_id ) = " . count( $amenities ) . ")";
        }

        if ( ! empty( $cruise_line ) ) {
            $sql .= " INNER JOIN {$tbl_term_relationships} AS tr2 ON tr2.object_id = t1.cruise_id 
                    INNER JOIN {$tbl_term_taxonomy} AS tt2 ON tt2.term_taxonomy_id = tr2.term_taxonomy_id";
            $where .= " AND tt2.taxonomy = 'cruise_line' AND tt2.term_id IN (" . esc_sql( implode( ',', $cruise_line ) ) . ")";
        }

        $sql .= " WHERE {$where} GROUP BY cruise_id ORDER BY {$order_by} {$order} LIMIT {$last_no}, {$per_page};";

        $results = $wpdb->get_results( $sql );
        
        return $results;
    }
}

/*
 * Get Cruise Search Result Count
 */
if ( ! function_exists( 'trav_cruise_get_search_result_count' ) ) {
    function trav_cruise_get_search_result_count( $min_price, $max_price, $rating, $cruise_type, $amenities, $cruise_line ) {
        global $wpdb;
        $tbl_posts = esc_sql( $wpdb->posts );
        $tbl_term_taxonomy = $wpdb->prefix . 'term_taxonomy';
        $tbl_term_relationships = $wpdb->prefix . 'term_relationships';
        $temp_tbl_name = trav_get_temp_table_name();
        $tbl_postmeta = esc_sql( $wpdb->prefix . 'postmeta' );
        if ( empty( $cruise_type ) || ! is_array( $cruise_type ) ) $cruise_type = array();
        if ( empty( $amenities ) || ! is_array( $amenities ) ) $amenities = array();
        if ( empty( $cruise_line ) || ! is_array( $cruise_line ) ) $cruise_line = array();
        foreach ( $cruise_type as $key=>$value ) {
            if ( ! is_numeric( $value ) ) unset( $cruise_type[$key] );
        }
        foreach ( $amenities as $key=>$value ) {
            if ( ! is_numeric( $value ) ) unset( $amenities[$key] );
        }
        foreach ( $cruise_line as $key=>$value ) {
            if ( ! is_numeric( $value ) ) unset( $cruise_line[$key] );
        }

        //$sql = "SELECT COUNT(*) FROM {$temp_tbl_name} as t1";
        $sql = "SELECT COUNT(DISTINCT t1.cruise_id) FROM {$temp_tbl_name} as t1";
        $where = " 1=1";

        // price filter
        if ( $min_price != 0 || $max_price != 'no_max' ) {
            $sql .= " INNER JOIN {$tbl_postmeta} AS meta_price ON (t1.cruise_id = meta_price.post_id) AND (meta_price.meta_key = 'trav_cruise_avg_price')";
        }
        if ( $min_price != 0 ) {
            $where .= " AND cast(meta_price.meta_value as unsigned) >= {$min_price}";
        }
        if ( $max_price != 'no_max' ) {
            $where .= " AND cast(meta_price.meta_value as unsigned) <= {$max_price} ";
        }

        // rating filter
        if ( $rating != 0 ) {
            $sql .= " LEFT JOIN {$tbl_postmeta} AS meta_rating ON (t1.cruise_id = meta_rating.post_id) AND (meta_rating.meta_key = 'review')";
            $where .= " AND cast(meta_rating.meta_value as DECIMAL(2,1)) >= {$rating} ";
        }

        if ( ! empty( $cruise_type ) ) {
            $sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = t1.cruise_id 
                    INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
            $where .= " AND tt.taxonomy = 'cruise_type' AND tt.term_id IN (" . esc_sql( implode( ',', $cruise_type ) ) . ")";
        }

        if ( ! empty( $amenities ) ) {
            $where .= " AND (( SELECT COUNT(1) FROM {$tbl_term_relationships} AS tr1 
                    INNER JOIN {$tbl_term_taxonomy} AS tt1 ON ( tr1.term_taxonomy_id= tt1.term_taxonomy_id )
                    WHERE tt1.taxonomy = 'amenity' AND tt1.term_id IN (" . esc_sql( implode( ',', $amenities ) ) . ") AND tr1.object_id = t1.cruise_id ) = " . count( $amenities ) . ")";
        }

        if ( ! empty( $cruise_line ) ) {
            $sql .= " INNER JOIN {$tbl_term_relationships} AS tr2 ON tr2.object_id = t1.cruise_id 
                    INNER JOIN {$tbl_term_taxonomy} AS tt2 ON tt2.term_taxonomy_id = tr2.term_taxonomy_id";
            $where .= " AND tt2.taxonomy = 'cruise_line' AND tt2.term_id IN (" . esc_sql( implode( ',', $cruise_line ) ) . ")";
        }

        $sql .= " WHERE {$where}";
        $count = $wpdb->get_var( $sql );
        return $count;
    }
}

/*
 * get star rating label
 */
if ( ! function_exists( 'trav_cruise_get_star_rating' ) ) {
    function trav_cruise_get_star_rating( $cruise_id ) {
        $cruise_type = wp_get_post_terms( $cruise_id, 'cruise_type' );
        $hotel_star_rating = get_post_meta( $cruise_id, 'trav_cruise_star_rating', true );
        $cruise_type_name = '';
        $result = '';
        if ( ! empty ( $cruise_type ) ) {
            $cruise_type_name =  $cruise_type[0]->name;
        }
        if ( ! empty( $hotel_star_rating ) ) {
            $result = ' <div title="' . $hotel_star_rating . '-' . __( 'star', 'trav') . ' ' . $cruise_type_name . '" class="five-stars-container no-back-star" data-toggle="tooltip" data-placement="bottom"><span class="five-stars" style="width: ' . ( $hotel_star_rating / 5 * 100 ) . '%;"></span></div>';
        }
        return $result;
    }
}

/*
 * Get cruises from ids
 */
if ( ! function_exists( 'trav_cruise_get_cruises_from_id' ) ) {
    function trav_cruise_get_cruises_from_id( $ids ) {
        if ( ! is_array( $ids ) ) return false;
        $results = array();
        foreach( $ids as $id ) {
            $result = get_post( $id );
            if ( ! empty( $result ) && ! is_wp_error( $result ) ) {
                if ( $result->post_type == 'cruise' ) $results[] = $result;
            }
        }
        return $results;
    }
}

/*
 * Get discounted(hot) cruises and return data
 */
if ( ! function_exists( 'trav_cruise_get_hot_cruises' ) ) {
    function trav_cruise_get_hot_cruises( $count = 10, $cruise_type=array(), $cruise_line=array() ) {
        $args = array(
            'post_type'  => 'cruise',
            'orderby'    => 'rand',
            'posts_per_page' => $count,
            'suppress_filters' => 0,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'trav_cruise_hot',
                    'value'   => '1',
                ),
                array(
                    'key'     => 'trav_cruise_discount_rate',
                    'value'   => array( 0, 100 ),
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN',
                ),
                array(
                    'key'     => 'trav_cruise_edate',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                ),
            ),
        );

        if ( ! empty( $cruise_type ) ) {
            if ( is_numeric( $cruise_type[0] ) ) {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_type',
                            'field' => 'term_id',
                            'terms' => $cruise_type
                            )
                    );
            } else {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_type',
                            'field' => 'name',
                            'terms' => $cruise_type
                            )
                    );
            }
        }

        if ( ! empty( $cruise_line ) ) {
            if ( is_numeric( $cruise_line[0] ) ) {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_line',
                            'field' => 'term_id',
                            'terms' => $cruise_line
                            )
                    );
            } else {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_line',
                            'field' => 'name',
                            'terms' => $cruise_line
                            )
                    );
            }
        }
        return get_posts( $args );
    }
}

/*
 * Get special( latest or featured ) cruises and return data
 */
if ( ! function_exists( 'trav_cruise_get_special_cruises' ) ) {
    function trav_cruise_get_special_cruises( $type='latest', $count=10, $exclude_ids=array(), $cruise_type=array(), $cruise_line=array() ) {
        $args = array(
                'post_type'  => 'cruise',
                'suppress_filters' => 0,
                'posts_per_page' => $count,
                'post_status' => 'publish',
            );
        
        if ( ! empty( $exclude_ids ) ) {
            $args['post__not_in'] = $exclude_ids;
        }

        if ( ! empty( $cruise_type ) ) {
            if ( is_numeric( $cruise_type[0] ) ) {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_type',
                            'field' => 'term_id',
                            'terms' => $cruise_type
                            )
                    );
            } else {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_type',
                            'field' => 'name',
                            'terms' => $cruise_type
                            )
                    );
            }
        }

        if ( ! empty( $cruise_line ) ) {
            if ( is_numeric( $cruise_line[0] ) ) {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_line',
                            'field' => 'term_id',
                            'terms' => $cruise_line
                            )
                    );
            } else {
                $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'cruise_line',
                            'field' => 'name',
                            'terms' => $cruise_line
                            )
                    );
            }
        }

        if ( $type == 'featured'  ) {
            $args = array_merge( $args, array(
                'orderby'    => 'rand',
                'meta_key'     => 'trav_cruise_featured',
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
            $sql = 'SELECT cruise_id, COUNT(*) AS booking_count FROM ' . TRAV_CRUISE_BOOKINGS_TABLE . ' AS booking';
            $where = ' WHERE (booking.status <> 0) AND (booking.created > %s)';
            
            $sql .= " INNER JOIN {$tbl_post} AS p1 ON (p1.ID = booking.cruise_id) AND (p1.post_status = 'publish')";

            if ( ! empty( $cruise_type ) ) {
                $sql .= " INNER JOIN {$tbl_term_relationships} AS tr ON tr.object_id = booking.cruise_id 
                        INNER JOIN {$tbl_term_taxonomy} AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
                $where .= " AND tt.taxonomy = 'cruise_type' AND tt.term_id IN (" . esc_sql( implode( ',', $cruise_type ) ) . ")";
            }
            if ( ! empty( $cruise_line ) ) {
                $sql .= " INNER JOIN {$tbl_term_relationships} AS tr1 ON tr.object_id = booking.cruise_id 
                        INNER JOIN {$tbl_term_taxonomy} AS tt ON tt1.term_taxonomy_id = tr1.term_taxonomy_id";
                $where .= " AND tt1.taxonomy = 'cruise_line' AND tt1.term_id IN (" . esc_sql( implode( ',', $cruise_line ) ) . ")";
            }
            $sql .= $where . ' GROUP BY booking.cruise_id ORDER BY booking_count desc LIMIT %d';
            $popular_cruises = $wpdb->get_results( sprintf( $sql, $date, $count ) );
            $result = array();
            if ( ! empty( $popular_cruises ) ) {
                foreach ( $popular_cruises as $cruise ) {
                    $result[] = get_post( trav_cruise_clang_id( $cruise->cruise_id ) );
                }
            }
            // if booked canbin number in last month is smaller than count then add latest cruises
            if ( count( $popular_cruises ) < $count ) {
                foreach ( $popular_cruises as $cruise ) {
                    $exclude_ids[] = trav_cruise_clang_id( $cruise->cruise_id );
                }
                $result = array_merge( $result, trav_cruise_get_special_cruises( 'latest', $count - count( $popular_cruises ), $exclude_ids, $cruise_type, $cruise_line ) );
            }
            return $result;
        }
    }
}
