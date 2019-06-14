<?php
/*
 * Single Accommodation Page Template
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

global $search_max_rooms, $search_max_adults, $search_max_kids;

get_header();

if ( have_posts() ) {
    while ( have_posts() ) : the_post();

        //init variables
        $acc_id = get_the_ID();
        $acc_meta = get_post_meta( $acc_id );
        $acc_meta['review'] = get_post_meta( trav_acc_org_id( $acc_id ), 'review', true );
        $acc_meta['review_detail'] = get_post_meta( trav_acc_org_id( $acc_id ), 'review_detail', true );
        $tm_data = get_post_meta( $acc_id, 'trav_accommodation_tm_testimonial', true );
        $accommodation_type = wp_get_post_terms( $acc_id, 'accommodation_type' );
        $args = array(
            'post_type' => 'room_type',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'trav_room_accommodation',
                    // 'value' => array( $acc_id ),
                    'value' => array( trav_acc_org_id( $acc_id ) )
                )
            ),
            'suppress_filters' => 1,
            'post_status' => 'publish',
        );
        $room_types = get_posts( $args );
        $city = trav_acc_get_city( $acc_id );
        $country = trav_acc_get_country( $acc_id );
        $facilities = wp_get_post_terms( $acc_id, 'amenity' );
        $things_to_do = empty( $acc_meta['trav_accommodation_ttd'] ) ? '' : $acc_meta['trav_accommodation_ttd'];

        // init map & gallery & calendar variables
        $gallery_imgs = array_key_exists( 'trav_gallery_imgs', $acc_meta ) ? $acc_meta['trav_gallery_imgs'] : array();
        $map = empty( $acc_meta['trav_accommodation_loc'] ) ? '' : $acc_meta['trav_accommodation_loc'][0];
        $calendar_desc = empty( $acc_meta['trav_accommodation_calendar_txt'] ) ? '' : $acc_meta['trav_accommodation_calendar_txt'][0];
        $show_gallery = 0;
        $show_map = 0;
        $show_street_view = 0;
        $show_calendar = 0;
        if ( array_key_exists( 'trav_accommodation_main_top', $acc_meta ) ) {
            $main_top_meta = $acc_meta['trav_accommodation_main_top'];
            $show_gallery = in_array( 'gallery', $main_top_meta ) ? 1 : 0;
            $show_map = in_array( 'map', $main_top_meta ) ? 1 : 0;
            $show_street_view = in_array( 'street', $main_top_meta ) ? 1 : 0;
            $show_calendar = in_array( 'calendar', $main_top_meta ) ? 1 : 0;
        }

        // init booking search variables
        $rooms = ( isset( $_GET['rooms'] ) && is_numeric( $_GET['rooms'] ) ) ? sanitize_text_field( $_GET['rooms'] ) : 1;
        $adults = ( isset( $_GET['adults'] ) && is_numeric( $_GET['adults'] ) ) ? sanitize_text_field( $_GET['adults'] ) : 1;
        $kids = ( isset( $_GET['kids'] ) && is_numeric( $_GET['kids'] ) ) ? sanitize_text_field( $_GET['kids'] ) : 0;
        $child_ages = isset( $_GET['child_ages'] ) ? $_GET['child_ages'] : '';
        $date_from = ( isset( $_GET['date_from'] ) ) ? trav_tophptime( $_GET['date_from'] ) : '';
        $date_to = ( isset( $_GET['date_to'] ) ) ? trav_tophptime( $_GET['date_to'] ) : '';
        $except_booking_no = ( isset( $_GET['edit_booking_no'] ) ) ? sanitize_text_field( $_GET['edit_booking_no'] ) : 0;
        $pin_code = ( isset( $_GET['pin_code'] ) ) ? sanitize_text_field( $_GET['pin_code'] ) : 0;

        // add to user recent activity
        trav_update_user_recent_activity( $acc_id ); ?>

        <section id="content">
            <div class="container">
                <div class="row">
                    <div id="main" class="col-sm-8 col-md-9">
                        <div class="tab-container style1" id="hotel-main-content">
                            <ul class="tabs">

                                <?php if ( ! empty( $gallery_imgs ) && $show_gallery ) { ?>
                                    <li><a data-toggle="tab" href="#photos-tab"><?php echo __( 'photos', 'trav' ) ?></a></li>
                                <?php } ?>

                                <?php if ( ! empty( $map ) ) { ?>
                                    <?php if ( $show_map ) { ?>
                                        <li><a data-toggle="tab" href="#map-tab"><?php echo __( 'map', 'trav' ) ?></a></li>
                                    <?php } ?>
                                    <?php if ( $show_street_view ) { ?>
                                        <li><a data-toggle="tab" href="#steet-view-tab"><?php echo __( 'street view', 'trav' ) ?></a></li>
                                    <?php } ?>
                                <?php } ?>

                                <?php if ( $show_calendar ) { ?>
                                    <li><a data-toggle="tab" href="#calendar-tab"><?php echo __( 'calendar', 'trav' ) ?></a></li>
                                <?php } ?>

                                <?php if ( ! empty( $acc_meta['trav_accommodation_tg'] ) ) { ?>
                                    <li class="pull-right"><a class="button btn-small yellow-bg white-color" href="<?php echo esc_url( get_permalink( $acc_meta['trav_accommodation_tg'][0] ) ); ?>"><?php _e( 'TRAVEL GUIDE', 'trav' ) ?></a></li>
                                <?php } ?>

                            </ul>
                            <div class="tab-content">

                                <?php if ( ! empty( $gallery_imgs ) && $show_gallery ) { ?>
                                    <div id="photos-tab" class="tab-pane fade">
                                        <div class="photo-gallery flexslider style1" data-animation="slide" data-sync="#photos-tab .image-carousel">
                                            <ul class="slides">
                                                <?php foreach ( $gallery_imgs as $gallery_img ) {
                                                    echo '<li>' . wp_get_attachment_image( $gallery_img, 'full' ) . '</li>';
                                                } ?>
                                            </ul>
                                        </div>
                                        <div class="image-carousel style1" data-animation="slide" data-item-width="70" data-item-margin="10" data-sync="#photos-tab .photo-gallery">
                                            <ul class="slides">
                                                <?php foreach ( $gallery_imgs as $gallery_img ) {
                                                    echo '<li>' . wp_get_attachment_image( $gallery_img, 'widget-thumb' ) . '</li>';
                                                } ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ( ! empty( $map ) ) { ?>
                                    <?php  if ( $show_map ) { ?>
                                        <div id="map-tab" class="tab-pane fade"></div>
                                    <?php } ?>
                                    <?php //if ( $show_street_view ) { ?>
                                        <div id="steet-view-tab" class="tab-pane fade" style="height: 500px;"></div>
                                    <?php //} ?>
                                <?php } ?>

                                <?php  if ( $show_calendar ) { ?>
                                    <div id="calendar-tab" class="tab-pane fade">
                                        <div class="row">

                                            <div class="col-sm-6 col-md-4 no-lpadding">
                                                <label><?php _e( 'SELECT MONTH', 'trav' );?></label>
                                                <div class="selector">
                                                    <select class="full-width" id="select-month">
                                                        <?php for ( $i = 0; $i<12; $i++ ) {
                                                            $year_month = mktime( 0, 0, 0, date_i18n("m") + $i, 1, date_i18n("Y") );
                                                            echo '<option value="' . date_i18n( 'Y-n', $year_month ) . '"> ' . __( date_i18n('F', $year_month ), 'trav' ) . date_i18n(' Y', $year_month ) . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <?php if ( ! empty( $room_types ) ) { ?>
                                                    <div class="col-sm-6 col-md-4 no-lpadding">
                                                        <label><?php _e( 'SELECT ROOM TYPE', 'trav' );?></label>
                                                        <div class="selector">
                                                            <select class="full-width" id="select-room-type">
                                                                <option value=""><?php _e( 'All Room Types', 'trav' ); ?></option>
                                                                <?php
                                                                    foreach ( $room_types as $room_type ) {
                                                                        echo '<option value="' . esc_attr( $room_type->ID ) . '">' . get_the_title( trav_room_clang_id( $room_type->ID ) ) . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            <?php } ?>

                                        </div>
                                        <div class="row">
                                            <?php if ( ! empty( $calendar_desc ) ) { ?>
                                                <div class="col-sm-8">
                                                    <div class="calendar"></div>
                                                    <div class="calendar-legend">
                                                        <label class="available"><?php echo __( 'available', 'trav' ) ?></label>
                                                        <label class="unavailable"><?php echo __( 'unavailable', 'trav' ) ?></label>
                                                        <label class="past"><?php echo __( 'past', 'trav' ) ?></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <p class="description">
                                                        <?php
                                                            echo esc_html( $calendar_desc )
                                                        ?>
                                                    </p>
                                                </div>
                                            <?php } else { ?>
                                                <div class="calendar"></div>
                                                <div class="calendar-legend">
                                                    <label class="available"><?php echo __( 'available', 'trav' ) ?></label>
                                                    <label class="unavailable"><?php echo __( 'unavailable', 'trav' ) ?></label>
                                                    <label class="past"><?php echo __( 'past', 'trav' ) ?></label>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div id="hotel-features" class="tab-container">
                            <ul class="tabs">
                                <?php $def_tab = ( ! empty(  $acc_meta['trav_accommodation_def_tab'] ) ) ? $acc_meta['trav_accommodation_def_tab'][0] : 'desc';?>
                                <li<?php echo ( $def_tab == 'desc' ) ? ' class="active"' : '' ?>><a href="#hotel-description" data-toggle="tab"><?php _e( 'Description','trav' ); ?></a></li>
                                <!-- <li<?php echo ( $def_tab == 'rooms' ) ? ' class="active"' : '' ?>><a href="#hotel-availability" data-toggle="tab"><?php _e( 'Availability','trav' ); ?></a></li> -->
                                <li<?php echo ( $def_tab == 'amenity' ) ? ' class="active"' : '' ?>><a href="#hotel-amenities" data-toggle="tab"><?php _e( 'Amenities','trav' ); ?></a></li>
                                <!-- <li><a href="#hotel-reviews" data-toggle="tab"><?php _e( 'Reviews','trav' ); ?></a></li> -->
                                <?php if ( ! empty( $acc_meta['trav_accommodation_faq'] ) ) : ?>
                                    <li><a href="#hotel-faqs" data-toggle="tab"><?php _e( 'Rooms And Suites','trav' ); ?></a></li>
                                <?php endif ?>
                                <?php if ( ! empty( $things_to_do ) ) : ?>
                                    <li><a href="#hotel-things-todo" data-toggle="tab"><?php _e( 'Things to Do','trav' ); ?></a></li>
                                <?php endif; ?>
                              <!--  <li><a href="#hotel-write-review" data-toggle="tab"><?php _e( 'Write a Review','trav' ); ?></a></li> -->
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade<?php echo ( $def_tab == 'rooms' ) ? ' in active' : '' ?>" id="hotel-availability">
                                    <form id="check_availability_form" method="post">
                                        <input type="hidden" name="accommodation_id" value="<?php echo esc_attr( $acc_id ); ?>">
                                        <input type="hidden" name="action" value="acc_get_available_rooms">
                                        <?php wp_nonce_field( 'post-' . $acc_id, '_wpnonce', false ); ?>
                                        <?php if ( isset( $_GET['edit_booking_no'] ) && ! empty( $_GET['edit_booking_no'] ) ) : ?>
                                            <input type="hidden" name="edit_booking_no" value="<?php echo esc_attr( $_GET['edit_booking_no'] ) ?>">
                                            <input type="hidden" name="pin_code" value="<?php echo esc_attr( $_GET['pin_code'] ) ?>">
                                        <?php endif; ?>
                                        <div class="update-search clearfix">
                                            <div class="alert alert-error" style="display:none;"><span class="message"><?php _e( 'Please select check in date.','trav' ); ?></span><span class="close"></span></div>
                                            <div class="col-md-5">
                                                <h4 class="title"><?php _e( 'When','trav' ); ?></h4>
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <label><?php _e( 'CHECK IN','trav' ); ?></label>
                                                        <div class="datepicker-wrap validation-field from-today">
                                                            <input name="date_from" type="text" placeholder="<?php echo trav_get_date_format('html'); ?>" class="input-text full-width" value="<?php echo $date_from; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <label><?php _e( 'CHECK OUT','trav' ); ?></label>
                                                        <div class="datepicker-wrap validation-field from-today">
                                                            <input name="date_to" type="text" placeholder="<?php echo trav_get_date_format('html'); ?>" class="input-text full-width" value="<?php echo $date_to;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <label><?php _e( 'ROOMS','trav' ); ?></label>
                                                        <div class="selector validation-field">
                                                            <select name="rooms" class="full-width">
                                                                <?php
                                                                    for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
                                                                        $selected = ( $i == $rooms ) ? 'selected' : '';
                                                                        echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <label><?php _e( 'ADULTS','trav' ); ?></label>
                                                        <div class="selector validation-field">
                                                            <select name="adults" class="full-width">
                                                                <?php
                                                                    for ( $i = 1; $i <= $search_max_adults; $i++ ) {
                                                                        $selected = ( $i == $adults ) ? 'selected' : '';
                                                                        echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <label><?php _e( 'KIDS','trav' ); ?></label>
                                                        <div class="selector validation-field">
                                                            <select name="kids" class="full-width">
                                                                <?php
                                                                    for ( $i = 0; $i <= $search_max_kids; $i++ ) {
                                                                        $selected = ( $i == $kids ) ? 'selected' : '';
                                                                        echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="clearer"></div>
                                                    <div class="col-xs-12 age-of-children <?php if ( $kids == 0) echo 'no-display'?>">
                                                        <h5><?php _e( 'Age of Children','trav' ); ?></h5>
                                                        <div class="row">
                                                        <?php
                                                            $kid_nums = ( $kids > 0 )?$kids:1;
                                                            for ( $kid_num = 1; $kid_num <= $kid_nums; $kid_num++ ) {
                                                        ?>
                                                        
                                                            <div class="col-xs-4 child-age-field">
                                                                <label><?php echo esc_html( __( 'Child ', 'trav' ) . $kid_num ) ?></label>
                                                                <div class="selector validation-field">
                                                                    <select name="child_ages[]" class="full-width">
                                                                        <?php
                                                                            $max_kid_age = 17;
                                                                            $child_age = ( isset( $_GET['child_ages'][ $kid_num -1 ] ) && is_numeric( (int) $_GET['child_ages'][ $kid_num -1 ] ) )?(int) $_GET['child_ages'][ $kid_num -1 ]:0;
                                                                            for ( $i = 0; $i <= $max_kid_age; $i++ ) {
                                                                                $selected = ( $i == $child_age ) ? 'selected' : '';
                                                                                echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <h4 class="visible-md visible-lg">&nbsp;</h4>
                                                <label class="visible-md visible-lg">&nbsp;</label>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <button id="check_availability" data-animation-duration="1" data-animation-type="bounce" class="full-width icon-check animated bounce" type="submit"><?php _e( "SEARCH NOW", "trav" ); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <h2><?php echo __( 'Available Rooms', 'trav' ) ?></h2>
                                    <div class="room-list listing-style3 hotel">

                                        <?php 
                                            //get accommodation rooms
                                            if ( ! empty( $room_types ) ) {
                                                if ( ! empty( $date_from ) && ! empty( $date_to ) ) {
                                                    echo '<input type="hidden" name="pre_searched" value="1">';
                                                    $return_value = trav_acc_get_available_rooms( $acc_id, $_GET['date_from'], $_GET['date_to'], $rooms, $adults, $kids, $child_ages, $except_booking_no, $pin_code );
                                                    if ( is_array( $return_value ) ) {
                                                        $number_of_days = count( $return_value['check_dates'] );
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
                                                    } else {
                                                        echo wp_kses_post( $return_value );
                                                    }
                                                } else {
                                                    echo '<input type="hidden" name="pre_searched" value="0">';
                                                    foreach ( $room_types as $room_type ) {
                                                        trav_acc_get_room_detail_html( $room_type->ID, 'all');
                                                    }
                                                }
                                            } else {
                                                echo __( 'No Rooms Found', 'trav' );
                                            }
                                        ?>

                                    </div>
                                </div>
                                <div class="tab-pane fade<?php echo ( $def_tab == 'desc' ) ? ' in active' : '' ?>" id="hotel-description">
                                    <div class="intro table-wrapper full-width hidden-table-sms">
                                        <div class="col-sm-4 features table-cell">
                                            <table>
                                            <?php
                                                $tr = '<tr><td><label>%s:</label></td><td>%s</td></tr>';
                                                //accommodation type
                                                if ( ! empty ( $accommodation_type ) ) {
                                                    echo sprintf( $tr, __( 'Type', 'trav' ), esc_attr( $accommodation_type[0]->name ) );
                                                }

                                                $detail_fields = array( 
                                                    'star_rating' => array( 'label' => __('Rating Stars', 'trav'), 'pre' => '', 'sur' => ' ' . __( 'star', 'trav') ),
                                                    'country' => array( 'label' => __('Country', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'city' => array( 'label' => __('City', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'address' => array( 'label' => __('Address', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'neighborhood' => array( 'label' => __('Neighborhood', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'check_in' => array( 'label' => __('Check In', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'check_out' => array( 'label' => __('Check Out', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'charge_extra_people' => array( 'label' => __('Extra people', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'minimum_stay' => array( 'label' => __('Minimum Stay', 'trav'), 'pre' => '', 'sur' => ' ' . __( 'nights', 'trav') ),
                                                    'discount_rate' => array( 'label' => __('Discount', 'trav'), 'pre' => '', 'sur' => ' ' . __('% Off', 'trav') ),
                                                );

                                                foreach ( $detail_fields as $field => $value ) {
                                                    if ( empty( $$field ) ) $$field = empty( $acc_meta["trav_accommodation_$field"] )?'':$acc_meta["trav_accommodation_$field"][0];
                                                    if ( ! empty( $$field ) ) {
                                                        $content = $value['pre'] . $$field . $value['sur'];
                                                        echo sprintf( $tr, esc_html( $value['label'] ), esc_html( $content ) );
                                                    }
                                                }
                                            ?>
                                            </table>
                                        </div>
                                        <?php
                                            if ( ! empty( $tm_data ) ) {
                                                $tm_style = empty( $acc_meta['trav_accommodation_tm_style'] )?'':$acc_meta['trav_accommodation_tm_style'][0];
                                                $tm_title = empty( $acc_meta['trav_accommodation_tm_title'] )?'':$acc_meta['trav_accommodation_tm_title'][0];
                                                $tm_author_photo_size = empty( $acc_meta['trav_accommodation_tm_author_photo_size'] )?'':$acc_meta['trav_accommodation_tm_author_photo_size'][0];
                                                $tm_class = empty( $acc_meta['trav_accommodation_tm_class'] )?'':$acc_meta['trav_accommodation_tm_class'][0];
                                                
                                                $tm_string ='';
                                                $tm_string .= '[testimonials style="' . $tm_style . '" title="' . $tm_title . '" author_img_size="' . $tm_author_photo_size . '" class="' . $tm_class . '"]';
                                                $tm_template = '[testimonial author_name="%s" author_link="%s" author_img_url="%s" ]%s[/testimonial]';
                                                $tm_content = '';
                                                foreach ( $tm_data as $tm_id => $values ) {
                                                    if ( empty( $values[0] ) && empty( $values[1] ) && empty( $values[2] ) && empty( $values[3] ) ) continue;
                                                    $tm_content .= sprintf( $tm_template, $values[0], $values[1], $values[2], $values[3] );
                                                }
                                                $tm_string .= $tm_content;
                                                $tm_string .= '[/testimonials]';
                                                if ( ! empty( $tm_content ) ) {
                                        ?>
                                                    <div class="col-sm-8 table-cell testimonials no-rpadding no-lpadding">
                                                        <?php
                                                            echo wp_kses_post( do_shortcode( $tm_string ) );
                                                        ?>
                                                    </div>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="long-description">
                                        <div class="box entry-content">
                                            <?php the_content(); ?>
                                            <?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
                                        </div>
                                       <!-- <div class="box policies-box">
                                            <h2><?php printf( __( 'Policies of %s', 'trav' ), wp_kses_post( get_the_title( $acc_id ) ) ) ?></h2>
                                            <?php
                                                $tr = '<div class="row"><div class="col-xs-2"><label>%s:</label></div><div class="col-xs-10">%s</div></div>';

                                                $detail_fields = array( 
                                                    'check_in' => array( 'label' => __('Check-in', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'check_out' => array( 'label' => __('Check-out', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'cancellation' => array( 'label' => __('Cancellation / prepayment', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'security_deposit' => array( 'label' => __('Security Deposit Amount (%)', 'trav'), 'pre' => '', 'sur' => '%' ),
                                                    'extra_beds_detail' => array( 'label' => __('Children and Extra Beds', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'cards' => array( 'label' => __('Cards accepted at this property', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'pets' => array( 'label' => __('Pets', 'trav'), 'pre' => '', 'sur' => '' ),
                                                    'other_policies' => array( 'label' => __('Other Policies', 'trav'), 'pre' => '', 'sur' => '' ),
                                                );

                                                foreach ( $detail_fields as $field => $value ) {
                                                    $$field = empty( $acc_meta["trav_accommodation_$field"] )?'':$acc_meta["trav_accommodation_$field"][0];
                                                    if ( ! empty( $$field ) ) {
                                                        $content = $value['pre'] . $$field . $value['sur'];
                                                        echo sprintf( $tr, esc_html( $value['label'] ), esc_html( $content ) );
                                                    }
                                                }
                                            ?>
                                        </div> -->
                                    </div>
                                </div>
                                <div class="tab-pane fade<?php echo ( $def_tab == 'amenity' ) ? ' in active' : '' ?>" id="hotel-amenities">
                                    <h2><?php echo __('Amenities of ', 'trav'); the_title();?></h2>
                                    <p>
                                        <?php
                                            echo esc_attr( empty( $acc_meta["trav_accommodation_other_amenity_info"] ) ? '' : $acc_meta["trav_accommodation_other_amenity_info"][0] );
                                        ?>
                                    </p>
                                    <ul class="amenities clearfix style1">
                                        <?php
                                            $amenity_icons = get_option( "amenity_icon" );
                                            $amenity_html = '';
                                            foreach ( $facilities as $facility ) {
                                                if ( is_array( $amenity_icons ) && isset( $amenity_icons[ $facility->term_id ] ) ) {
                                                    $amenity_html .= '<li class="col-md-4 col-sm-6">';
                                                    if ( isset( $amenity_icons[ $facility->term_id ]['uci'] ) ) {
                                                        $amenity_html .= '<div class="icon-box style1"><div class="custom_amenity"><img title="' . esc_attr( $facility->name ) . '" src="' . esc_url( $amenity_icons[ $facility->term_id ]['url'] ) . '" height="42" alt="amenity-image"></div>' . esc_html( $facility->name ) . '</div>';
                                                    } else if ( isset( $amenity_icons[ $facility->term_id ]['icon'] ) ) {
                                                        $_class = $amenity_icons[ $facility->term_id ]['icon'];
                                                        $amenity_html .= '<div class="icon-box style1"><i class="' . esc_attr( $_class ) . '" title="' . esc_attr( $facility->name ) . '"></i>' . esc_html( $facility->name ) . '</div>';
                                                    }
                                                    $amenity_html .= '</li>';
                                                }
                                                
                                            }
                                            echo wp_kses_post( $amenity_html );
                                        ?>
                                    </ul>
                                </div>
                                <div class="tab-pane fade" id="hotel-reviews">
                                    <div class="intro table-wrapper full-width hidden-table-sms">
                                        <div class="rating table-cell col-sm-4">
                                            <?php
                                                $acc_review = ( ! empty( $acc_meta['review'] ) )?(float) $acc_meta['review']:0;
                                                $acc_review = round( $acc_review, 1 );
                                            ?>
                                            <span class="score"><?php echo esc_html( $acc_review );?>/5.0</span>
                                            <div class="five-stars-container"><div class="five-stars" style="width: <?php echo esc_attr( $acc_review / 5 * 100 ) ?>%;"></div></div>
                                            <a href="#" class="goto-writereview-pane button green btn-small full-width"><?php echo esc_html__( 'WRITE A REVIEW', 'trav' ) ?></a>
                                        </div>
                                        <div class="table-cell col-sm-8 no-rpadding no-lpadding">
                                            <div class="detailed-rating validation-field">
                                                <ul class="clearfix">
                                                    <?php
                                                        $review_factors = array(
                                                                'cln' => __( 'Cleanliness', 'trav' ),
                                                                'cft' => __( 'Comfort', 'trav' ),
                                                                'loc' => __( 'Location', 'trav' ),
                                                                'fac' => __( 'Facilities', 'trav' ),
                                                                'stf' => __( 'Staff', 'trav' ),
                                                                'vfm' => __( 'Value for money', 'trav' ),
                                                            );
                                                        $i = 0;
                                                        $review_detail = array( 0, 0, 0, 0, 0, 0 );
                                                        if ( ! empty( $acc_meta['review_detail'] ) ) $review_detail = is_array( $acc_meta['review_detail'] ) ? $acc_meta['review_detail'] : unserialize( $acc_meta['review_detail'] );
                                                        foreach ( $review_factors as $factor => $label ) {
                                                            echo '<li class="col-md-6"><div class="each-rating"><label>' . esc_html( $label ) . '</label><div class="five-stars-container" data-toggle="tooltip" data-placement="bottom" data-original-title="' . esc_attr( $review_detail[$i] ) . ' stars" ><div class="five-stars" style="width: ' . esc_attr( $review_detail[$i] / 5 * 100 ) . '%;"></div></div></div></li>';
                                                            $i++;
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="guest-reviews">
                                        <h2><?php echo __('Guest Reviews', 'trav') . ' <small>('; echo esc_html( trav_get_review_count(  trav_acc_org_id( $acc_id ) ) . ' ' .  __('reviews', 'trav') . ')' ); ?></small></h2>
                                        <?php
                                            $per_page = 10;
                                            $review_count = trav_get_review_html( trav_acc_org_id( $acc_id ), 0, $per_page);
                                        ?>
                                    </div>
                                    <?php if ( $review_count >= $per_page ) { ?>
                                        <a href="#" class="more-review"><button class="silver full-width btn-large"><?php echo __( 'LOAD MORE REVIEWS', 'trav' ) ?></button></a>
                                    <?php } ?>
                                </div>
                                <?php if ( ! empty( $acc_meta['trav_accommodation_faq'] ) ) : ?>
                                    <div class="tab-pane fade" id="hotel-faqs">
                                        <?php echo do_shortcode( $acc_meta['trav_accommodation_faq'][0] ); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ( ! empty( $things_to_do ) ) : ?>
                                    <div class="tab-pane fade" id="hotel-things-todo">
                                        <h2><?php _e('Things to Do', 'trav');?></h2>
                                        <p><?php echo esc_html( empty( $acc_meta['trav_accommodation_ttd_detail'] )?'':$acc_meta['trav_accommodation_ttd_detail'][0] ); ?></p>
                                        <div class="activities image-box style2 innerstyle">
                                            <?php foreach( $things_to_do as $ttd_id ) { ?>
                                                <article class="box">
                                                    <figure>
                                                        <a title="<?php echo esc_attr( get_the_title( $ttd_id ) ); ?>" href="<?php echo esc_url( get_permalink( $ttd_id ) ); ?>"><?php echo ( get_the_post_thumbnail( $ttd_id, 'list-thumb' ) ); ?></a>
                                                    </figure>
                                                    <div class="details">
                                                        <div class="details-header">
                                                            <h4 class="box-title"><?php echo esc_html( get_the_title( $ttd_id ) ); ?></h4>
                                                        </div>
                                                        <p><?php
                                                            $ttd_excerpt = get_post_field('post_excerpt', $ttd_id);
                                                            if ( ! empty( $ttd_excerpt ) ) {
                                                                echo wp_kses_post( apply_filters( 'the_excerpt', $ttd_excerpt ) );
                                                            } else {
                                                                $ttd_content = apply_filters('the_content', get_post_field('post_content', $ttd_id));
                                                                echo wp_kses_post( wp_trim_words( $ttd_content, 55, '' ) );
                                                            }
                                                        ?></p>
                                                        <a class="button" title="<?php echo __( 'MORE', 'trav' ) ?>" href="<?php echo esc_url( get_permalink( $ttd_id ) ); ?>"><?php echo __( 'MORE', 'trav' ) ?></a>
                                                    </div>
                                                </article>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="tab-pane fade" id="hotel-write-review">
                                    <?php
                                        $booking_data = '';
                                        $review_data ='';
                                        $rating_detail = '';
                                        $averagy_rating = 0;
                                        global $wpdb;
                                        if ( is_user_logged_in() ) {
                                            $booking_data = $wpdb->get_row( sprintf( 'SELECT * FROM ' . TRAV_ACCOMMODATION_BOOKINGS_TABLE . ' WHERE accommodation_id=%d AND user_id=%d AND date_to<%s ORDER BY date_to DESC', trav_acc_org_id( $acc_id ), get_current_user_id(), date("Y-m-d") ), ARRAY_A );
                                            if ( ! empty( $booking_data ) ) {
                                                $review_data = $wpdb->get_row( sprintf( 'SELECT * FROM ' . TRAV_REVIEWS_TABLE . ' WHERE booking_no=%d AND pin_code=%d', $booking_data['booking_no'], $booking_data['pin_code'] ), ARRAY_A );
                                                if ( is_array( $review_data ) && isset( $review_data['review_rating_detail'] ) ) {
                                                    $rating_detail = unserialize($review_data['review_rating_detail']);
                                                    $averagy_rating = array_sum($rating_detail)/count($rating_detail);
                                                }
                                            }
                                        }
                                    ?>
                                    <div class="alert alert-error" style="display: none;"><span class="message"></span><span class="close"></span></div>
                                    <div class="main-rating table-wrapper full-width hidden-table-sms intro">
                                        <article class="image-box box hotel listing-style1 table-cell col-sm-4 photo">
                                            <figure>
                                                <?php the_post_thumbnail( 'gallery-thumb' )?>
                                            </figure>
                                            <div class="details">
                                                <h4 class="box-title"><?php the_title(); ?><small><i class="soap-icon-departure"></i> <?php echo esc_html( empty( $city )?'':( $city . ', ' ) ); echo esc_html( empty( $country )?'':( $country ) ); ?></small></h4>
                                                <div class="feedback">
                                                    <div title="<?php echo esc_attr( $acc_review . ' ' . __( 'stars', 'trav') );?>" class="five-stars-container" data-toggle="tooltip" data-placement="bottom"><span class="five-stars" style="width: <?php echo esc_html( $acc_review / 5 * 100 );?>%;"></span></div>
                                                    <span class="review"><?php echo esc_html( trav_get_review_count( $acc_id ) ); echo ' ' . __( 'reviews', 'trav' ) ?></span>
                                                </div>
                                            </div>
                                        </article>
                                        <div class="table-cell col-sm-8 no-rpadding">
                                            <div class="overall-rating">
                                                <h4><?php _e( 'Your overall Rating of this property', 'trav');?></h4>
                                                <div class="star-rating clearfix">
                                                    <div class="five-stars-container"><div class="five-stars" style="width: <?php echo esc_attr( $averagy_rating / 5 * 100 ) ?>%;"></div></div>
                                                    <span class="status"></span>
                                                </div>
                                                <div class="detailed-rating validation-field">
                                                    <ul class="clearfix">
                                                        <?php
                                                            $i = 0;
                                                            foreach ( $review_factors as $factor => $label ) {
                                                                echo '<li class="col-md-6"><div class="each-rating"><label>' . esc_html( $label ) . '</label><div class="five-stars-container editable-rating" data-original-stars="' . esc_attr( ( is_array($rating_detail) && isset( $rating_detail[ $i ] ) )?$rating_detail[ $i ]:0 ) . '"></div></div></li>';
                                                                $i++;
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form class="review-form" id="review-form" method="post">
                                        <?php wp_nonce_field( 'post-' . $acc_id, '_wpnonce', false ); ?>
                                        <input type="hidden" name="review_rating" value="<?php echo esc_attr( $averagy_rating ) ?>">
                                        <?php
                                        $i = 0;
                                        foreach ( $review_factors as $factor => $label ) {
                                            echo '<input type="hidden" class="rating_detail_hidden" name="review_rating_detail[]" value="' . esc_attr( ( is_array($rating_detail) && isset( $rating_detail[ $i ] ) )?$rating_detail[ $i ]:'' ) . '">';
                                            $i++;
                                        } ?>
                                        <input type="hidden" name="post_id" value="<?php echo esc_attr( $acc_id ); ?>">
                                        <input type="hidden" name="action" value="acc_submit_review">
                                        <div class="row clearer">
                                            <div class="form-group col-md-5 no-padding">
                                                <h4><?php _e( 'Booking Number', 'trav'); ?></h4>
                                                <input type="text" name="booking_no" class="input-text full-width validation-field" value="<?php if ( is_array( $booking_data ) && isset( $booking_data['booking_no'] ) ) echo esc_attr( $booking_data['booking_no'] ); ?>" data-error-message="<?php _e( 'Enter your booking number', 'trav' ); ?>" placeholder="<?php _e( 'Enter your booking number', 'trav' ); ?>" />
                                            </div>
                                            <div class="form-group col-md-5 col-md-offset-1 no-padding">
                                                <h4><?php _e( 'Pin Code', 'trav'); ?></h4>
                                                <input type="text" name="pin_code" class="input-text full-width validation-field" value="<?php if ( is_array( $booking_data ) && isset( $booking_data['pin_code'] ) ) echo esc_attr( $booking_data['pin_code'] ); ?>" data-error-message="<?php _e( 'Enter your pin code', 'trav' ); ?>" placeholder="<?php _e( 'Enter your pin code', 'trav' ); ?>" />
                                            </div>
                                        </div>

                                         <div class="form-group col-md-5 no-float no-padding">
                                            <h4><?php _e( 'Title of your review', 'trav'); ?></h4>
                                            <input type="text" name="review_title" class="input-text full-width validation-field" value="<?php if ( is_array( $review_data ) && isset( $review_data['review_title'] ) ) echo esc_attr( $review_data['review_title'] ); ?>" data-error-message="<?php _e( 'Enter a review title', 'trav' ); ?>" placeholder="<?php _e( 'Enter a review title', 'trav' ); ?>" />
                                        </div>
                                        <div class="form-group">
                                            <h4><?php _e( 'Your review', 'trav'); ?></h4>
                                            <textarea name="review_text" class="input-text full-width validation-field" data-error-message="<?php _e( 'Enter your review', 'trav' ); ?>" placeholder="<?php _e( 'Enter your review', 'trav' ); ?>" rows="5"><?php if ( is_array( $review_data ) && isset( $review_data['review_text'] ) ) echo esc_textarea( $review_data['review_text'] ); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <h4><?php _e( 'What sort of Trip was this?', 'trav'); ?></h4>
                                            <ul class="sort-trip clearfix">
                                                <?php
                                                    $trip_types = array(
                                                            'businessbag' => __( 'Business', 'trav' ),
                                                            'couples' => __( 'Couples', 'trav' ),
                                                            'family' => __( 'Family', 'trav' ),
                                                            'friends' => __( 'Friends', 'trav' ),
                                                            'user' => __( 'Solo', 'trav' ),
                                                        );
                                                    $active_trip_type = 0;
                                                    $i = 0;
                                                    if ( is_array( $review_data ) && isset( $review_data['trip_type'] ) ) $active_trip_type = $review_data['trip_type'];
                                                    foreach ($trip_types as $key => $value) {
                                                        $active = '';
                                                        if ( $i == $active_trip_type ) $active = ' class="active"';
                                                        echo '<li' . $active . '><a href="#"><i class="soap-icon-' . esc_attr( $key ) . ' circle"></i></a><span>' . esc_html( $value ) . '</span></li>';
                                                        $i++;
                                                    }
                                                ?>
                                            </ul>
                                            <input type="hidden" name="trip_type" value="<?php echo esc_attr( $active_trip_type ); ?>">
                                        </div>
                                        <div class="form-group col-md-5 no-float no-padding no-margin">
                                            <button type="submit" class="btn-large full-width submit-review"><?php echo __( 'SUBMIT REVIEW', 'trav' ) ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar col-sm-4 col-md-3">
                        <article class="detailed-logo">
                            <?php if ( isset( $acc_meta['trav_accommodation_logo'] ) ) { ?>
                                <figure>
                                    <img width="114" src="<?php echo esc_url( wp_get_attachment_url( $acc_meta['trav_accommodation_logo'][0] ) );?>" alt="Accommodation Logo">
                                </figure>
                            <?php } ?>
                            <div class="details">
                                <h2 class="box-title">
                                    <?php the_title(); ?>
                                    <?php echo trav_acc_get_star_rating( $acc_id ); ?>
                                    <small><i class="soap-icon-departure yellow-color"></i><span class="fourty-space"><?php echo esc_html( empty( $city )?'':( $city . ', ' ) ); echo esc_html( empty( $country )?'':( $country ) ); ?></span></small>
                                </h2>
                                <?php if ( isset( $acc_meta['trav_accommodation_avg_price'] ) && is_numeric( $acc_meta['trav_accommodation_avg_price'][0] ) ) { ?>
                                    <span class="price clearfix">
                                        <small class="pull-left"><?php _e( 'avg/night', 'trav' ); ?></small>
                                        <span class="pull-right"><?php echo esc_html( trav_get_price_field( $acc_meta['trav_accommodation_avg_price'][0] ) ); ?></span>
                                    </span>
                                <?php } ?>
                                <div class="feedback clearfix">
                                    <div title="<?php echo esc_attr( $acc_review . ' ' . __( 'stars', 'trav' ) );?>" class="five-stars-container" data-toggle="tooltip" data-placement="bottom"><span class="five-stars" style="width: <?php echo esc_attr( $acc_review / 5 * 100 );?>%;"></span></div>
                                    <span class="review pull-right"><?php echo esc_html( trav_get_review_count( $acc_id ) . ' ' . __( 'reviews', 'trav' ) ) ?></span>
                                </div>
                                <p class="description">
                                    <?php
                                        if ( isset( $acc_meta['trav_accommodation_brief'] ) ) {
                                            echo esc_html( $acc_meta['trav_accommodation_brief'][0] );
                                        } else {
                                            $brief_content = apply_filters('the_content', get_post_field('post_content', $acc_id));
                                            echo wp_kses_post( wp_trim_words( $brief_content, 20, '' ) );
                                        }
                                    ?>
                                </p>
                                <?php if ( is_user_logged_in() ) {
                                    $user_id = get_current_user_id();
                                    $wishlist = get_user_meta( $user_id, 'wishlist', true );
                                    if ( empty( $wishlist ) ) $wishlist = array();
                                    if ( ! in_array( trav_acc_org_id( $acc_id ), $wishlist) ) { ?>
                                        <a class="button yellow-bg full-width uppercase btn-small btn-add-wishlist" data-label-add="<?php _e( 'add to wishlist', 'trav' ); ?>" data-label-remove="<?php _e( 'remove from wishlist', 'trav' ); ?>"><?php _e( 'add to wishlist', 'trav' ); ?></a>
                                    <?php } else { ?>
                                        <a class="button yellow-bg full-width uppercase btn-small btn-remove-wishlist" data-label-add="<?php _e( 'add to wishlist', 'trav' ); ?>" data-label-remove="<?php _e( 'remove from wishlist', 'trav' ); ?>"><?php _e( 'remove from wishlist', 'trav' ); ?></a>
                                    <?php } ?>
                                <?php } else { ?>
                                        <h5><?php _e( 'To save your wishlist please login.', 'trav' ); ?></h5>
                                        <a href="<?php echo $login_url ?>" class="button yellow-bg full-width uppercase btn-small <?php echo ( $login_url == '#travelo-login' )?' soap-popupbox':'' ?>"><?php _e( 'login', 'trav' ); ?></a>
                                <?php } ?>
                            </div>
                        </article>
                        <?php generated_dynamic_sidebar(); ?>
                    </div>
                </div>
            </div>
        </section><!-- #content -->
<?php endwhile;
}
get_footer();