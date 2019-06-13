<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Handle get accommodation month vacancies ajax request.
 */
if ( ! function_exists( 'trav_ajax_acc_get_month_vacancies' ) ) {
    function trav_ajax_acc_get_month_vacancies() {
        $result_json = array( 'success' => 0, 'result' => '' );
        //validation
        if ( ! isset( $_POST['year'] ) || ! isset( $_POST['month'] ) || ! isset( $_POST['accommodation_id'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Invalid input data.', 'trav' );
            wp_send_json( $result_json );
        }

        //initiate variables
        $acc_id = sanitize_text_field( $_POST['accommodation_id'] );
        $year = sanitize_text_field( $_POST['year'] );
        $month = sanitize_text_field( $_POST['month'] );
        $room_type_id = isset( $_POST['room_type'] ) ? sanitize_text_field( $_POST['room_type'] ) : '';
        $vacancies = trav_acc_get_month_vacancies( $acc_id, $year,$month, $room_type_id );

        $result_json['success'] = 1;
        $result_json['result'] = $vacancies;
        wp_send_json( $result_json );
    }
}

/*
 * Handle ajax request to get matched rooms to given data.
 */
if ( ! function_exists( 'trav_ajax_acc_get_available_rooms' ) ) {
    function trav_ajax_acc_get_available_rooms() {
        //validation and initiate variables
        $result_json = array( 
            'success'   => 0, 
            'result'    => '' 
        );

        if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['accommodation_id'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-' . sanitize_text_field( $_POST['accommodation_id'] ) ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );

            wp_send_json( $result_json );
        }

        $rooms = ( isset( $_POST['rooms'] ) && is_numeric( $_POST['rooms'] ) ) ? sanitize_text_field( $_POST['rooms'] ) : 1;
        $adults = ( isset( $_POST['adults'] ) && is_numeric( $_POST['adults'] ) ) ? sanitize_text_field( $_POST['adults'] ) : 1;
        $kids = ( isset( $_POST['kids'] ) && is_numeric( $_POST['kids'] ) ) ? sanitize_text_field( $_POST['kids'] ) : 0;
        $child_ages = isset( $_POST['child_ages'] ) ? $_POST['child_ages'] : '';

        if ( isset( $_POST['accommodation_id'] ) && isset( $_POST['date_from'] ) && trav_strtotime( $_POST['date_from'] ) && isset( $_POST['date_to'] ) && trav_strtotime( $_POST['date_to'] ) && ( ( time()-(60*60*24) ) < trav_strtotime( $_POST['date_from'] ) ) ) {
            $acc_id = (int) $_POST['accommodation_id'];
            $except_booking_no = 0; 
            $pin_code = 0;

            if ( isset( $_POST['edit_booking_no'] ) ) {
                $except_booking_no = sanitize_text_field( $_POST['edit_booking_no'] );
            }

            if ( isset( $_POST['pin_code'] ) ) {
                $pin_code = sanitize_text_field( $_POST['pin_code'] );
            }

            $return_value = trav_acc_get_available_rooms( $acc_id, $_POST['date_from'], $_POST['date_to'], $rooms, $adults, $kids, $child_ages, $except_booking_no, $pin_code );

            if ( ! empty ( $return_value ) && is_array( $return_value ) ) {

                $number_of_days = count( $return_value['check_dates'] );
                
                ob_start();

                $available_room_type_ids = $return_value['bookable_room_type_ids'];
                if ( ! empty( $available_room_type_ids ) ) {
                    foreach ( $available_room_type_ids as $room_type_id ) {
                        $room_price = 0;
                        foreach ( $return_value['check_dates'] as $check_date ) {
                            $room_price += (float) $return_value['prices'][ $room_type_id ][ $check_date ]['total'];
                        }
                        
                        trav_acc_get_room_detail_html( $room_type_id, 'available', $room_price, $number_of_days, $rooms );
                    }
                }

                $not_available_room_type_ids = array_diff( $return_value['matched_room_type_ids'], $return_value['bookable_room_type_ids'] ) ;
                if ( ! empty( $not_available_room_type_ids ) ) {
                    foreach ( $not_available_room_type_ids as $room_type_id ) {
                        trav_acc_get_room_detail_html( $room_type_id, 'not_available' );
                    }
                }

                $not_match_room_type_ids = array_diff( $return_value['all_room_type_ids'], $return_value['matched_room_type_ids'] ) ;
                if ( ! empty( $not_match_room_type_ids ) ) {
                    foreach ( $not_match_room_type_ids as $room_type_id ) {
                        trav_acc_get_room_detail_html( $room_type_id, 'not_match' );
                    }
                }

                $output = ob_get_contents();
                ob_end_clean();

                $result_json['success'] = 1;
                $result_json['result'] = $output;
            } else {
                $result_json['success'] = 1;
                $result_json['result'] = $return_value;
            }
        } else {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Invalid input data', 'trav' );
        }

        wp_send_json( $result_json );
    }
}

if ( ! function_exists( 'trav_ajax_get_post_gallery' ) ) {
    function trav_ajax_get_post_gallery() {
        $post_id = sanitize_text_field( $_REQUEST['post_id'] );
        trav_get_post_gallery_html( $post_id );
        
        exit();
    }
}

/*
 * Handle get more reviews ajax request
 */
if ( ! function_exists( 'trav_ajax_acc_get_more_reviews' ) ) {
    function trav_ajax_acc_get_more_reviews() {
        $acc_id = sanitize_text_field( $_POST['accommodation_id'] );
        $last_no = sanitize_text_field( $_POST['last_no'] );
        $per_page = 10;
        $review_count = trav_get_review_html( $acc_id, $last_no, $per_page );
        exit();
    }
}

/*
 * Handle submit reviews ajax request
 */
if ( ! function_exists( 'trav_ajax_acc_submit_review' ) ) {
    function trav_ajax_acc_submit_review() {
        global $wpdb;

        $result_json = array( 'success' => 0, 'result' => '', 'title' => '' );
        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-' . $_POST['post_id'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
            wp_send_json( $result_json );
        }

        $fields = array( 'post_id', 'booking_no', 'pin_code', 'review_title', 'review_text', 'review_rating', 'trip_type' );

        //validation
        $data = array();
        foreach( $fields as $field ) {
            $data[$field] = ( isset( $_POST[$field] ) ) ? sanitize_text_field( $_POST[$field] ) : '';
        }

        if ( ! $booking_data = trav_acc_get_booking_data( $data['booking_no'], $data['pin_code'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Wrong Booking Number and Pin Code.', 'trav' );
            wp_send_json( $result_json );
        }

        if ( ! is_array( $booking_data ) || $booking_data['status'] == 0 || trav_acc_org_id($data['post_id']) != $booking_data['accommodation_id'] ) {
            $result_json['success'] = 0;
            $result_json['title'] = __( 'Sorry, You cannot leave a rating.', 'trav' );
            $result_json['result'] = __( 'You cancelled your booking, so cannot leave a rating.', 'trav' );
            wp_send_json( $result_json );
        }

        if ( trav_strtotime( $booking_data['date_to'] ) > trav_strtotime(date("Y-m-d")) ) {
            $result_json['success'] = 0;
            $result_json['title'] = __( 'Sorry, You cannot leave a rating before travel.', 'trav' );
            $result_json['result'] = __( 'You can leave a review after travel.', 'trav' );
            wp_send_json( $result_json );
        }

        $data['post_id'] = $booking_data['accommodation_id'];
        $data['reviewer_name'] = $booking_data['first_name'] . ' ' . $booking_data['last_name'];
        $data['reviewer_email'] = $booking_data['email'];
        $data['reviewer_ip'] = $_SERVER['REMOTE_ADDR'];
        $data['review_rating_detail'] = serialize( $_POST['review_rating_detail'] );
        $data['review_rating'] = array_sum( $_POST['review_rating_detail'] ) / count( $_POST['review_rating_detail'] ); 
        $data['date'] = date( 'Y-m-d H:i:s' );
        $data['status'] = 0;
        if ( is_user_logged_in() ) {
            $data['user_id'] = get_current_user_id();
        }

        if ( ! $review_data = $wpdb->get_row( sprintf( 'SELECT * FROM ' . TRAV_REVIEWS_TABLE . ' WHERE booking_no=%d AND pin_code=%d', $data['booking_no'], $data['pin_code'] ), ARRAY_A ) ) {
            if ( $wpdb->insert( TRAV_REVIEWS_TABLE, $data ) ) {
                $result_json['success'] = 1;
                $result_json['title'] = __( 'Thank you! Your review has been submitted successfully.', 'trav' );
                $result_json['result'] = __( 'You can change your review anytime.', 'trav' );
            } else {
                $result_json['success'] = 0;
                $result_json['title'] = __( 'Sorry, An error occurred while add review.', 'trav' );
                $result_json['result'] = __( 'Please try again after a while.', 'trav' );
            }
        } else {
            if ( $wpdb->update( TRAV_REVIEWS_TABLE, $data, array('booking_no'=>$data['booking_no'], 'pin_code'=>$data['pin_code']) ) ) {
                $result_json['success'] = 1;
                $result_json['title'] = __( 'Thank you! Your review has been submitted successfully.', 'trav' );
                $result_json['result'] = __( 'You can change your review anytime.', 'trav' );
            } else {
                $result_json['success'] = 0;
                $result_json['title'] = __( 'Sorry, An error occurred while add review.', 'trav' );
                $result_json['result'] = __( 'Please try again after a while.', 'trav' );
            }
        }
        
        wp_send_json( $result_json );
    }
}

/*
 * Handle Add to Wishlist Action on Detail Page
 */
if ( ! function_exists( 'trav_ajax_acc_add_to_wishlist' ) ) {
    function trav_ajax_acc_add_to_wishlist() {
        $result_json = array( 'success' => 0, 'result' => '' );

        if ( ! is_user_logged_in() ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Please login to update your wishlist.', 'trav' );
            wp_send_json( $result_json );
        }

        $user_id = get_current_user_id();
        $new_item_id = sanitize_text_field( trav_acc_org_id( $_POST['accommodation_id'] ) );
        $wishlist = get_user_meta( $user_id, 'wishlist', true );
        if ( isset( $_POST['remove'] ) ) {
            //remove
            $wishlist = array_diff( $wishlist, array( $new_item_id ) );
            if ( update_user_meta( $user_id, 'wishlist', $wishlist ) ) {
                $result_json['success'] = 1;
                $result_json['result'] = __( 'This accommodation has removed from your wishlist successfully.', 'trav' );
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
                    $result_json['result'] = __( 'This accommodation has added to your wishlist successfully.', 'trav' );
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
 * Handle submit booking ajax request
 */
if ( ! function_exists( 'trav_ajax_acc_submit_booking' ) ) {
    function trav_ajax_acc_submit_booking() {
        global $wpdb, $trav_options;

        // validation
        $result_json = array( 'success' => 0, 'result' => '' );

        if ( ! isset( $_POST['transaction_id'] ) || ! isset( $_SESSION['booking_data'][$_POST['transaction_id']] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, some error occurred on input data validation.', 'trav' );
            wp_send_json( $result_json );
        }

        $raw_booking_data = $_SESSION['booking_data'][$_POST['transaction_id']];
        $booking_fields = array( 'accommodation_id', 'room_type_id', 'rooms', 'adults', 'kids', 'child_ages', 'total_price', 'room_price', 'tax', 'currency_code', 'exchange_rate', 'deposit_price', 'date_from', 'date_to', 'created', 'booking_no', 'pin_code', 'status', 'discount_rate' );
        $booking_data = array();
        foreach( $booking_fields as $booking_field ) {
            if ( ! empty( $raw_booking_data[ $booking_field ] ) ) {
                $booking_data[ $booking_field ] = $raw_booking_data[ $booking_field ];
            }
        }

        $is_payment_enabled = trav_is_payment_enabled() && ! empty( $booking_data['deposit_price'] );

        if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'post-' . $booking_data['room_type_id'] ) ) {
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
        $data['child_ages'] = serialize( $data['child_ages'] );
        $data['date_from'] = date( 'Y-m-d', trav_strtotime( $data['date_from'] ) );
        $data['date_to'] = date( 'Y-m-d', trav_strtotime( $data['date_to'] ) );
        if ( is_user_logged_in() ) {
            $data['user_id'] = get_current_user_id();
        }

        $latest_booking_id = $wpdb->get_var( 'SELECT id FROM ' . TRAV_ACCOMMODATION_BOOKINGS_TABLE . ' ORDER BY id DESC LIMIT 1' );
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
            'accommodation_id'      => '',
            'room_type_id'          => '',
            'rooms'                 => '',
            'adults'                => '',
            'kids'                  => '',
            'child_ages'            => '',
            'total_price'           => '',
            'room_price'            => '',
            'tax'                   => '',
            'currency_code'         => 'usd',
            'exchange_rate'         => 1,
            'deposit_price'         => 0,
            'deposit_paid'          => ( $is_payment_enabled ? 0 : 1 ),
            'date_from'             => '',
            'date_to'               => '',
            'created'               => date( 'Y-m-d H:i:s' ),
            'booking_no'            => $booking_no,
            'pin_code'              => $pin_code,
            'status'                => 1,
            'discount_rate'         => ''
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
        $room_price_data = trav_acc_get_room_price_data( $data['accommodation_id'], $data['room_type_id'], $booking_data['date_from'], $booking_data['date_to'], $data['rooms'], $data['adults'], $data['kids'], $data['child_ages'] );
        if ( ! $room_price_data || ! is_array( $room_price_data ) ) {
            $result_json['success'] = -1;
            $result_json['result'] = __( 'Sorry, The room you are booking now is just taken by another customer. Please have another look.', 'trav' );
            wp_send_json( $result_json );
        }

        do_action( 'trav_acc_add_booking_before', $data );

        // save default language accommodation and room type
        $data['accommodation_id'] = trav_acc_org_id( $data['accommodation_id'] );
        $data['room_type_id'] = trav_room_org_id( $data['room_type_id'] );
        
        // add to db
        if ( $wpdb->insert( TRAV_ACCOMMODATION_BOOKINGS_TABLE, $data ) ) {
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
                    do_action( 'trav_woo_add_acc_booking', $data );

                    $result_json['result']['payment'] = 'woocommerce';
                } elseif ( trav_is_paypal_enabled() ) {
                    // paypal direct
                    $result_json['result']['payment'] = 'paypal';
                }
            } else {
                $result_json['result']['payment'] = 'no';
            }

            do_action( 'trav_acc_add_booking_after', $data );
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
if ( ! function_exists( 'trav_ajax_acc_cancel_booking' ) ) {
    function trav_ajax_acc_cancel_booking() {
        if ( ! isset( $_POST['edit_booking_no'] ) || ! isset( $_POST['pin_code'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, some error occurred on input data validation.', 'trav' );
            wp_send_json( $result_json );
        }
        $edit_booking_no = sanitize_text_field( $_POST['edit_booking_no'] );
        $pin_code = sanitize_text_field( $_POST['pin_code'] );
        $result = trav_acc_update_booking( $edit_booking_no, $pin_code, array( 'status' => 0 ), 'cancel' );
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
 * Handle More Accommodatation Action on Search Result Page
 */
if ( ! function_exists( 'trav_ajax_get_more_accs' ) ) {
    function trav_ajax_get_more_accs() {
        global $trav_options;
        $order_by_array = array(
            'name' => 'acc_title',
            'price' => 'cast(avg_price as unsigned)',
            'rating' => 'review'
        );
        $order_array = array( 'ASC', 'DESC' );

        $s = isset($_REQUEST['s']) ? sanitize_text_field( $_REQUEST['s'] ) : '';
        $rooms = ( isset( $_REQUEST['rooms'] ) && is_numeric( $_REQUEST['rooms'] ) ) ? sanitize_text_field( $_REQUEST['rooms'] ) : 1;
        $adults = ( isset( $_REQUEST['adults'] ) && is_numeric( $_REQUEST['adults'] ) ) ? sanitize_text_field( $_REQUEST['adults'] ) : 1;
        $kids = ( isset( $_REQUEST['kids'] ) && is_numeric( $_REQUEST['kids'] ) ) ? sanitize_text_field( $_REQUEST['kids'] ) : 0;
        $min_price = ( isset( $_REQUEST['min_price'] ) && is_numeric( $_REQUEST['min_price'] ) ) ? sanitize_text_field( $_REQUEST['min_price'] ) : 0;
        $max_price = ( isset( $_REQUEST['max_price'] ) && ( is_numeric( $_REQUEST['max_price'] ) || ( $_REQUEST['max_price'] == 'no_max' ) ) ) ? sanitize_text_field( $_REQUEST['max_price'] ) : 'no_max';
        $rating = ( isset( $_REQUEST['rating'] ) && is_numeric( $_REQUEST['rating'] ) ) ? sanitize_text_field( $_REQUEST['rating'] ) : 0;
        $order_by = ( isset( $_REQUEST['order_by'] ) && array_key_exists( $_REQUEST['order_by'], $order_by_array ) ) ? sanitize_text_field( $_REQUEST['order_by'] ) : 'name';
        $order = ( isset( $_REQUEST['order'] ) && in_array( $_REQUEST['order'], $order_array ) ) ? sanitize_text_field( $_REQUEST['order'] ) : 'ASC';
        $acc_type = ( isset( $_REQUEST['acc_type'] ) && is_array( $_REQUEST['acc_type'] ) ) ? $_REQUEST['acc_type'] : array();
        $amenities = ( isset( $_REQUEST['amenities'] ) && is_array( $_REQUEST['amenities'] ) ) ? $_REQUEST['amenities'] : array();
        $per_page = ( isset( $trav_options['acc_posts'] ) && is_numeric($trav_options['acc_posts']) ) ? $trav_options['acc_posts'] : 12;

        $date_from = isset( $_REQUEST['date_from'] ) ? trav_sanitize_date( $_REQUEST['date_from'] ) : '';
        $date_to = isset( $_REQUEST['date_to'] ) ? trav_sanitize_date( $_REQUEST['date_to'] ) : '';
        if ( trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) {
            $date_from = '';
            $date_to = '';
        }

        $count = isset( $_POST['count'] ) ? (int)$_POST['count'] : 0;

        $query_results = trav_acc_get_search_result( $s, $date_from, $date_to, $rooms, $adults, $kids, $order_by_array[$order_by], $order, $count, $per_page, $min_price, $max_price, $rating, $acc_type, $amenities );

        global $acc_list;
        $results = $query_results;
        $acc_list = array();
        foreach ( $results as $result ) {
            $acc_list[] = $result->acc_id;
        }

        global $current_view, $before_article, $after_article;
        $current_view =isset( $_POST['view'] ) ? sanitize_text_field( $_POST['view'] ) : 'list';

        if ( $current_view == 'block' ) {
            $before_article = '<div class="col-sms-6 col-sm-6 col-md-4">';
            $after_article = '</div>';
        } elseif ( $current_view == 'grid' ) {
            $before_article = '<div class="col-sm-6 col-md-4">';
            $after_article = '</div>';
        } else {
            $before_article = '';
            $after_article = '';
        }

        if ( ! empty( $results ) ) {
            trav_get_template( 'accommodation-list.php', '/templates/accommodation/');
        }
        exit();
    }
}

/*
 * Check room availability for updated date on thank you Page
 */
if ( ! function_exists( 'trav_ajax_acc_check_room_availability' ) ) {
    function trav_ajax_acc_check_room_availability() {
        if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['booking_no'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'booking-' . $_POST['booking_no'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
            wp_send_json( $result_json );
        }

        if ( ! isset( $_POST['date_from'] ) || ! isset( $_POST['date_to'] ) || trav_strtotime( $_POST['date_from'] ) >= trav_strtotime( $_POST['date_to'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Wrong Date Interval. Please check your dates.', 'trav' );
            wp_send_json( $result_json );
        }

        global $wpdb;
        $booking_no = sanitize_text_field( $_POST['booking_no'] );
        $pin_code = sanitize_text_field( $_POST['pin_code'] );

        if ( ! $booking_data = trav_acc_get_booking_data( $booking_no, $pin_code ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Wrong booking number and pin code.', 'trav' );
            wp_send_json( $result_json );
        }

        $booking_data['date_from'] = trav_sanitize_date( $_POST['date_from'] );
        $booking_data['date_to'] = trav_sanitize_date( $_POST['date_to'] );
        $booking_data['rooms'] = sanitize_text_field( $_POST['rooms'] );
        $booking_data['adults'] = sanitize_text_field( $_POST['adults'] );
        $booking_data['kids'] = sanitize_text_field( $_POST['kids'] );
        $booking_data['child_ages'] = $_POST['child_ages'];
        $room_price_data = trav_acc_get_room_price_data( $booking_data['accommodation_id'], $booking_data['room_type_id'], $booking_data['date_from'], $booking_data['date_to'], $booking_data['rooms'], $booking_data['adults'], $booking_data['kids'], $booking_data['child_ages'], $booking_no, $pin_code );

        if ( ! $room_price_data || ! is_array( $room_price_data ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'The room is not available for the selected date, rooms and person. Please have another look at booking fields.', 'trav' );
            wp_send_json( $result_json );
        } else {
            $tax_rate = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_tax_rate', true );
            $tax = 0;
            if ( ! empty( $tax_rate ) ) $tax = $tax_rate * $room_price_data['total_price'] / 100;
            $total_price = $room_price_data['total_price'] + $tax;

            $return_html = '<dl class="other-details">';
            $return_html .= '<dt class="feature">' . esc_html( trav_get_day_interval( $booking_data['date_from'], $booking_data['date_to'] ) ) . ' ' . __( 'night Stay', 'trav') . ':</dt><dd class="value">' . esc_html( trav_get_price_field( $room_price_data['total_price'] ) ) . '</dd>';
            if ( ! empty( $tax_rate ) ) :
                $return_html .= '<dt class="feature">' . __( 'taxes and fees', 'trav') . ':</dt><dd class="value">' . esc_html( trav_get_price_field( $tax ) ) . '</dd>';
            endif;
            $return_html .= '<dt class="feature">' . __( 'Total Price', 'trav') . '</dt><dd>' . esc_html( trav_get_price_field( $total_price ) ) . '</dd>';
            $return_html .= '</dl>';

            $result_json['success'] = 1;
            $result_json['result'] = $return_html;
            wp_send_json( $result_json );
        }
    }
}

/*
 * update booking date
 */
if ( ! function_exists( 'trav_ajax_acc_update_booking_date' ) ) {
    function trav_ajax_acc_update_booking_date() {
        if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['booking_no'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'booking-' . $_POST['booking_no'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
            wp_send_json( $result_json );
        }

        global $wpdb;
        $booking_no = sanitize_text_field( $_POST['booking_no'] );
        $pin_code = sanitize_text_field( $_POST['pin_code'] );

        if ( ! $booking_data = trav_acc_get_booking_data( $booking_no, $pin_code ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Wrong booking number and pin code.', 'trav' );
            wp_send_json( $result_json );
        }

        $booking_data['date_from'] = date( 'Y-m-d', trav_strtotime( $_POST['date_from'] ) );
        $booking_data['date_to'] = date( 'Y-m-d', trav_strtotime( $_POST['date_to'] ) );
        $booking_data['rooms'] = sanitize_text_field( $_POST['rooms'] );
        $booking_data['adults'] = sanitize_text_field( $_POST['adults'] );
        $booking_data['kids'] = sanitize_text_field( $_POST['kids'] );
        $booking_data['child_ages'] = serialize( $_POST['child_ages'] );
        $room_price_data = trav_acc_get_room_price_data( $booking_data['accommodation_id'], $booking_data['room_type_id'], $booking_data['date_from'], $booking_data['date_to'], $booking_data['rooms'], $booking_data['adults'], $booking_data['kids'], $booking_data['child_ages'], $booking_no, $pin_code );

        if ( ! $room_price_data ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'The room is not available for the selected date, rooms and person. Please have another look at booking fields.', 'trav' );
            wp_send_json( $result_json );
        }

        $tax_rate = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_tax_rate', true );
        $tax = 0;
        if ( ! empty( $tax_rate ) ) $tax = $tax_rate * $room_price_data['total_price'] / 100;
        $total_price_incl_tax = $room_price_data['total_price'] + $tax;
        $booking_data['room_price'] = $room_price_data['total_price'];
        $booking_data['tax'] = $tax;
        $booking_data['total_price'] = $total_price_incl_tax;
        /*if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
        $booking_data['currency_code'] = trav_get_user_currency();*/
        $booking_data['updated'] = date( 'Y-m-d H:i:s' );
        $result = trav_acc_update_booking( $booking_no, $pin_code, $booking_data, 'update' );
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
 * update booking room
 */
if ( ! function_exists( 'trav_ajax_acc_change_room' ) ) {
    function trav_ajax_acc_change_room() {
        if ( ! isset( $_POST['_wpnonce'] ) || ! isset( $_POST['booking_no'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'booking-' . $_POST['booking_no'] ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Sorry, your nonce did not verify.', 'trav' );
            wp_send_json( $result_json );
        }

        global $wpdb;
        $booking_no = sanitize_text_field( $_POST['booking_no'] );
        $pin_code = sanitize_text_field( $_POST['pin_code'] );

        if ( ! $booking_data = trav_acc_get_booking_data( $booking_no, $pin_code ) ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'Wrong booking number and pin code.', 'trav' );
            wp_send_json( $result_json );
        }


        $booking_data['room_type_id'] = sanitize_text_field( trav_room_org_id( $_POST['room_type_id'] ) );
        $room_price_data = trav_acc_get_room_price_data( $booking_data['accommodation_id'], $booking_data['room_type_id'], $booking_data['date_from'], $booking_data['date_to'], $booking_data['rooms'], $booking_data['adults'], $booking_data['kids'], $booking_data['child_ages'], $booking_no, $pin_code );

        if ( ! $room_price_data ) {
            $result_json['success'] = 0;
            $result_json['result'] = __( 'The room is not available for the selected date, rooms and person. Please have another look at booking fields.', 'trav' );
            wp_send_json( $result_json );
        }

        $tax_rate = get_post_meta( $booking_data['accommodation_id'], 'trav_accommodation_tax_rate', true );
        $tax = 0;
        if ( ! empty( $tax_rate ) ) $tax = $tax_rate * $room_price_data['total_price'] / 100;
        $total_price_incl_tax = $room_price_data['total_price'] + $tax;
        $booking_data['room_price'] = $room_price_data['total_price'];
        $booking_data['tax'] = $tax;
        $booking_data['total_price'] = $total_price_incl_tax;
        /*if ( ! isset( $_SESSION['exchange_rate'] ) ) trav_init_currency();
        $booking_data['currency_code'] = trav_get_user_currency();*/
        $booking_data['updated'] = date( 'Y-m-d H:i:s' );

        $result = trav_acc_update_booking( $booking_no, $pin_code, $booking_data, 'update' );
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