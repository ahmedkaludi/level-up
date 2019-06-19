<?php
/*
 * Accommodation Booking Success Form
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // exit if accessed directly
}

global $logo_url, $search_max_rooms, $search_max_adults, $search_max_kids;
global $booking_data, $acc_id, $room_type_id, $deposit_rate, $date_from, $date_to;

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
$acc_meta = get_post_meta( $acc_id );
$tax_rate = get_post_meta( $acc_id, 'trav_accommodation_tax_rate', true );
?>

<div class="row">
    <div class="col-sm-8 col-md-9">
        <div class="booking-information travelo-box">

            <?php do_action( 'trav_acc_conf_form_before', $booking_data ); ?>

            <?php if ( ( isset( $_REQUEST['payment'] ) && ( $_REQUEST['payment'] == 'success' ) ) || ( isset( $_REQUEST['message'] ) && ( $_REQUEST['message'] == 1 ) ) ): ?>
                <h2><?php _e( 'Booking Confirmation', 'trav' ); ?></h2>

                <hr />

                <div class="booking-confirmation clearfix">
                    <i class="soap-icon-recommend icon circle"></i>
                    <div class="message">
                        <h4 class="main-message"><?php _e( 'Thank You. Your Booking Order is Confirmed Now.', 'trav' ); ?></h4>
                        <p><?php _e( 'A confirmation email has been sent to your provided email address.', 'trav' ); ?></p>
                    </div>
                    <!-- <a href="#" class="button btn-small print-button uppercase">print Details</a> -->
                </div>
                <hr />
            <?php endif; ?>

            <h3><?php echo __( 'Check Your Details' , 'trav' ) ?></h3>
            <dl class="term-description">
                <?php
                $booking_detail = array(
                    'booking_no'    => array( 'label' => __('Booking Number', 'trav'), 'pre' => '', 'sur' => '' ),
                    'pin_code'      => array( 'label' => __('Pin Code', 'trav'), 'pre' => '', 'sur' => '' ),
                    'email'         => array( 'label' => __('E-mail address', 'trav'), 'pre' => '', 'sur' => '' ),
                    'date_from'     => array( 'label' => __('Check-In', 'trav'), 'pre' => '', 'sur' => '' ),
                    'date_to'       => array( 'label' => __('Check-out', 'trav'), 'pre' => '', 'sur' => '' ),
                    'rooms'         => array( 'label' => __('Rooms', 'trav'), 'pre' => '', 'sur' => '' ),
                    'adults'        => array( 'label' => __('Adults', 'trav'), 'pre' => '', 'sur' => '' ),
                );

                foreach ( $booking_detail as $field => $value ) {
                    if ( empty( $$field ) ) $$field = empty( $booking_data[ $field ] )?'':$booking_data[ $field ];
                    if ( ! empty( $$field ) ) {
                        $content = $value['pre'] . $$field . $value['sur'];
                        echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
                    }
                }
                if ( ! empty( $booking_data[ 'kids' ] ) ) {
                    echo sprintf( $dt_dd, __('Kids', 'trav'), esc_html( $booking_data[ 'kids' ] ) );
                    for( $i = 1; $i <= $booking_data[ 'kids' ]; $i++ ) {
                        echo sprintf( $dt_dd, sprintf( __('Child%d Age', 'trav'), $i ), esc_html( $booking_data[ 'child_ages' ][$i-1] ) );
                    }
                }
                ?>
            </dl>

            <hr />

            <dl class="term-description">
                <dt><?php echo __( 'Room', 'trav' ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['room_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></dd>

                <?php if ( ! empty( $tax_rate ) ) : ?>
                    <dt><?php printf( __('VAT (%d%%) Included', 'trav' ), $tax_rate ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['tax'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></dd>
                <?php endif; ?>

                <?php if ( ! empty( $booking_data['discount_rate'] ) ) : ?>
                    <dt><?php echo __('Discount', 'trav' ) ?>:</dt><dd><?php echo '-' . $booking_data['discount_rate'] . '%' ?></dd>
                <?php endif; ?>

                <?php if ( ! ( $booking_data['deposit_price'] == 0 ) ) : ?>
                    <dt><?php printf( __('Security Deposit (%d%%)', 'trav' ), $deposit_rate ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
                <?php endif; ?>
            </dl>
            
            <dl class="term-description" style="font-size: 16px;" >
                <dt style="text-transform: none;"><?php echo __( 'Total Price', 'trav' ) ?></dt><dd><b style="color: #2d3e52;"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></b></dd>
            </dl>
            <hr />

            <?php trav_get_template( 'acc-room-detail.php', '/templates/accommodation/' ); ?>
            <?php do_action( 'trav_acc_conf_form_after', $booking_data ); ?>
        </div>
    </div>
    <div class="sidebar col-sm-4 col-md-3">
        <?php if ( empty( $acc_meta["trav_accommodation_d_edit_booking"] ) || empty( $acc_meta["trav_accommodation_d_edit_booking"][0] ) || empty( $acc_meta["trav_accommodation_d_cancel_booking"] ) || empty( $acc_meta["trav_accommodation_d_cancel_booking"][0] ) ) { ?>
            <div class="travelo-box edit-booking">

                <?php do_action( 'trav_acc_conf_sidebar_before', $booking_data ); ?>

                <h4><?php echo __('Is Everything Correct?','trav')?></h4>
                <p><?php echo __( 'You can always view or change your booking online - no registration required', 'trav' ) ?></p>
                <ul class="triangle hover box">
                    <?php if ( empty( $acc_meta["trav_accommodation_d_edit_booking"] ) || empty( $acc_meta["trav_accommodation_d_edit_booking"][0] ) ) { ?>
                        <li><a href="#change-date" class="soap-popupbox"><?php echo __('Change Dates & Guest Details','trav')?></a></li>
                        <li><a href="#change-room" class="soap-popupbox"><?php echo __('Change your room','trav')?></a></li>
                    <?php } ?>
                    <?php if ( empty( $acc_meta["trav_accommodation_d_cancel_booking"] ) || empty( $acc_meta["trav_accommodation_d_cancel_booking"][0] ) ) { ?>
                        <li><a href="<?php $query_args['pbsource'] = 'cancel_booking'; echo esc_url( add_query_arg( $query_args ,get_permalink( $acc_id ) ) );?>" class="btn-cancel-booking"><?php echo __('Cancel your booking','trav')?></a></li>
                    <?php } ?>
                </ul>

                <?php do_action( 'trav_acc_conf_sidebar_after', $booking_data ); ?>

            </div>
        <?php } ?>
        <?php generated_dynamic_sidebar(); ?>
    </div>
</div>
<div id="change-date" class="travelo-box travelo-modal-box">
    <div>
        <a href="#" class="logo-modal"><?php bloginfo( 'name' );?><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo('name'); ?>"></a>
    </div>
    <form id="change-date-form" method="post">
        <input type="hidden" name="action" value="acc_check_room_availability">
        <input type="hidden" name="booking_no" value="<?php echo esc_attr( $booking_data['booking_no'] ); ?>">
        <input type="hidden" name="pin_code" value="<?php echo esc_attr( $booking_data['pin_code'] ); ?>">
        <?php wp_nonce_field( 'booking-' . $booking_data['booking_no'], '_wpnonce', false ); ?>
        <div class="update-search clearfix">
            <div class="row">
                <div class="col-xs-12">
                    <label><?php _e( 'CHECK IN','trav' ); ?></label>
                    <div class="datepicker-wrap validation-field from-today">
                        <input name="date_from" type="text" placeholder="<?php echo trav_get_date_format('html') ?>" class="input-text full-width" value="<?php echo esc_attr( $date_from ); ?>" />
                    </div>
                </div>
                <div class="col-xs-12">
                    <label><?php _e( 'CHECK OUT','trav' ); ?></label>
                    <div class="datepicker-wrap validation-field from-today">
                        <input name="date_to" type="text" placeholder="<?php echo trav_get_date_format('html') ?>" class="input-text full-width" value="<?php echo esc_attr( $date_to ); ?>" />
                    </div>
                </div>
                <div class="col-xs-4">
                    <label><?php _e( 'ROOMS','trav' ); ?></label>
                    <div class="selector validation-field">
                        <select name="rooms" class="full-width">
                            <?php
                                $rooms = ( isset( $booking_data['rooms'] ) && is_numeric( (int) $booking_data['rooms'] ) )?(int) $booking_data['rooms']:1;
                                for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
                                    $selected = '';
                                    if ( $i == $rooms ) $selected = 'selected';
                                    echo '<option value="' . esc_attr( $i ). '" ' . esc_attr( $selected ) . '>' . esc_html( $i ). '</option>';
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
                                $adults = ( isset( $booking_data['adults'] ) && is_numeric( (int) $booking_data['adults'] ) )?(int) $booking_data['adults']:1;
                                for ( $i = 1; $i <= $search_max_adults; $i++ ) {
                                    $selected = '';
                                    if ( $i == $adults ) $selected = 'selected';
                                    echo '<option value="' . esc_attr( $i ). '" ' . esc_attr( $selected ) . '>' . esc_html( $i ). '</option>';
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
                                $kids = ( isset( $booking_data['kids'] ) && is_numeric( (int) $booking_data['kids'] ) )?(int) $booking_data['kids']:0;
                                for ( $i = 0; $i <= $search_max_kids; $i++ ) {
                                    $selected = '';
                                    if ( $i == $kids ) $selected = 'selected';
                                    echo '<option value="' . esc_attr( $i ). '" ' . esc_attr( $selected ) . '>' . esc_html( $i ). '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="clearer"></div>
                <div class="col-xs-12 age-of-children <?php if ( $kids == 0) echo 'no-display'?>">
                    <div class="row">
                    <?php
                        $kid_nums = ( $kids > 0 )?$kids:1;
                        for ( $kid_num = 1; $kid_num <= $kid_nums; $kid_num++ ) { ?>
                    
                        <div class="col-xs-4 child-age-field">
                            <label><?php echo esc_html( __( 'Child ', 'trav' ) . $kid_num ) ?></label>
                            <div class="selector validation-field">
                                <select name="child_ages[]" class="full-width">
                                    <?php
                                        $max_kid_age = 17;
                                        $child_ages = ( isset( $booking_data['child_ages'][ $kid_num -1 ] ) && is_numeric( (int) $booking_data['child_ages'][ $kid_num -1 ] ) )?(int) $booking_data['child_ages'][ $kid_num -1 ]:0;
                                        for ( $i = 0; $i <= $max_kid_age; $i++ ) {
                                            $selected = '';
                                            if ( $i == $child_ages ) $selected = 'selected';
                                            echo '<option value="' . esc_attr( $i ). '" ' . esc_attr( $selected ) . '>' . esc_html( $i ). '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="col-xs-12 update_booking_date_row">
                    <div class="booking-details"></div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button id="update_booking_date" data-animation-duration="1" data-animation-type="bounce" class="full-width icon-check animated bounce" type="submit"><?php _e( "CHANGE NOW", "trav" ); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="change-room" class="travelo-box travelo-modal-box">
    <div>
        <a href="#" class="logo-modal"><?php bloginfo( 'name' );?><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo('name'); ?>"></a>
    </div>
    <form id="change-room-form" method="post">
        <input type="hidden" name="action" value="acc_change_room">
        <input type="hidden" name="booking_no" value="<?php echo esc_attr( $booking_data['booking_no'] ); ?>">
        <input type="hidden" name="pin_code" value="<?php echo esc_attr( $booking_data['pin_code'] ); ?>">
        <?php wp_nonce_field( 'booking-' . $booking_data['booking_no'], '_wpnonce', false ); ?>
        <div class="update-search clearfix">
            <div class="row">
                <div class="col-xs-12">
                    <label><?php _e( 'AVAILABLE ROOMS','trav' ); ?></label>
                    <div class="selector validation-field">
                        <select name="room_type_id" class="full-width" data-original-val="<?php echo esc_attr( $room_type_id ) ?>">
                            <?php
                                $return_value = trav_acc_get_available_rooms( $acc_id, $booking_data['date_from'], $booking_data['date_to'], $booking_data['rooms'], $booking_data['adults'], $booking_data['kids'], $booking_data['child_ages'], $booking_data['booking_no'], $booking_data['pin_code'] );
                                if ( ! empty ( $return_value ) && is_array( $return_value ) ) {
                                    foreach ( $return_value['bookable_room_type_ids'] as $room_id ) {
                                        $room_price = 0;
                                        foreach ( $return_value['check_dates'] as $check_date ) {
                                            $room_price += (float) $return_value['prices'][ $room_id ][ $check_date ]['total'];
                                        }
                                        $room_price *= ( 1 + $tax_rate / 100 );
                                        $selected = '';
                                        $room_id_lang = trav_room_clang_id( $room_id );
                                        if ( $room_id_lang == $room_type_id ) $selected = 'selected';
                                        echo '<option value="' . esc_attr( $room_id ) . '" ' . esc_attr( $selected ) . '>' . esc_html( get_the_title( $room_id_lang ) . ' (' . trav_get_price_field( $room_price, $booking_data['currency_code'], 0 ) . ')' ) . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12">
                    <button id="update_booking_room" data-animation-duration="1" data-animation-type="bounce" class="full-width icon-check animated bounce" type="submit"><?php _e( "CHANGE ROOM", "trav" ); ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<style>#ui-datepicker-div {z-index: 10004 !important;} .update-search > div.row > div {margin-bottom: 10px;} .booking-details .other-details dt, .booking-details .other-details dd {padding: 0.2em 0;} .update-search > div.row > div:last-child {margin-bottom:0 !important;} </style>
<script>
    tjq = jQuery;
    tjq(document).ready(function(){
        tjq('.btn-cancel-booking').click(function(e){
            e.preventDefault();
            var r = confirm("<?php echo __('Do you really want to cancel this booking?', 'trav') ?>");
            if (r == true) {
                tjq.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action : 'acc_cancel_booking',
                        edit_booking_no : '<?php echo esc_js( $booking_data['booking_no'] ) ?>',
                        pin_code : '<?php echo esc_js( $booking_data['pin_code'] ) ?>'
                    },
                    success: function ( response ) {
                        if ( response.success == 1 ) {
                            trav_show_modal(1,response.result);
                            setTimeout(function(){ window.location.href = tjq('.btn-cancel-booking').attr('href'); }, 3000);
                        } else {
                            alert( response.result );
                        }
                    }
                });
            }
            return false;
        });
        tjq('body').on('change', '#change-date-form input, #change-date-form select', function(){
            var booking_data = tjq('#change-date-form').serialize();
            tjq('.update_booking_date_row').hide();
            var date_from = tjq('#change-date-form').find('input[name="date_from"]').datepicker("getDate").getTime();
            var date_to = tjq('#change-date-form').find('input[name="date_to"]').datepicker("getDate").getTime();
            var one_day=1000*60*60*24;
            var minimum_stay = 0;
            if (date_from >= date_to) { alert('<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.', 'trav' ); ?>'); return false; }

            <?php if ( ! empty( $acc_meta["trav_accommodation_minimum_stay"] ) ) { ?>

                minimum_stay = <?php echo esc_js( $acc_meta["trav_accommodation_minimum_stay"][0] )?>;
                if (date_from + one_day * minimum_stay - date_to > 0) { alert('<?php echo sprintf( __( 'Minimum stay for this accommodation is %d nights. Have another look at your dates and try again.', 'trav' ), $acc_meta["trav_accommodation_minimum_stay"][0] ) ?>'); return false; }

            <?php } ?>

            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: booking_data,
                success: function(response){
                    if ( response.success == 1 ) {
                        tjq('.booking-details').html(response.result);
                        tjq('.update_booking_date_row').show();
                    } else {
                        tjq('.update_booking_date_row').hide();
                        alert( response.result );
                    }
                }
            });
        });
        tjq('body').on('click', '#update_booking_date', function(e){
            e.preventDefault();
            tjq('#change-date-form input[name="action"]').val('acc_update_booking_date');
            var booking_data = tjq('#change-date-form').serialize();
            tjq('.travelo-modal-box').fadeOut();
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: booking_data,
                success: function(response){
                    if ( response.success == 1 ) {
                        tjq('.opacity-overlay').fadeOut(400,function(){trav_show_modal( 1, response.result, '' )});
                        setTimeout(function(){ location.reload(); }, 3000);
                    } else {
                        trav_show_modal( 0, response.result, '' );
                    }
                }
            });
            return false;
        });
        tjq('#update_booking_room').click(function(){
            if ( tjq('select[name="room_type_id"]').val() == tjq('select[name="room_type_id"]').data('original-val') ) {
                tjq(".opacity-overlay").fadeOut();
                return false;
            }
            var booking_data = tjq('#change-room-form').serialize();
            tjq('.travelo-modal-box').fadeOut();
            jQuery.ajax({
                url: ajaxurl,
                type: "POST",
                data: booking_data,
                success: function(response){
                    if ( response.success == 1 ) {
                        tjq('.opacity-overlay').fadeOut(400,function(){trav_show_modal( 1, response.result, '' )});
                        setTimeout(function(){ location.reload(); }, 3000);
                    } else {
                        alert( response.result );
                    }
                }
            });
            return false;
        });
    });
</script>