<?php
/*
 * Cruise Booking Success Form
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // exit if accessed directly
}

global $logo_url, $search_max_cabins, $search_max_adults, $search_max_kids;
global $booking_data, $cruise_id, $cabin_type_id, $deposit_rate, $date_from, $date_to;

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
$cruise_meta = get_post_meta( $cruise_id );
$tax_rate = get_post_meta( $cruise_id, 'trav_cruise_tax_rate', true );
?>

<div class="row">
    <div class="col-sm-8 col-md-9">
        <div class="booking-information travelo-box">

            <?php do_action( 'trav_cruise_conf_form_before', $booking_data ); ?>

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
                    'date_from'     => array( 'label' => __('Date from', 'trav'), 'pre' => '', 'sur' => '' ),
                    'date_to'       => array( 'label' => __('Date to', 'trav'), 'pre' => '', 'sur' => '' ),
                    'cabins'         => array( 'label' => __('Cabins', 'trav'), 'pre' => '', 'sur' => '' ),
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
                <dt><?php echo __( 'Cabin', 'trav' ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['cabin_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></dd>

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

            <?php trav_get_template( 'cruise-cabin-detail.php', '/templates/cruise/' ); ?>
            <?php do_action( 'trav_cruise_conf_form_after', $booking_data ); ?>
        </div>
    </div>
    <div class="sidebar col-sm-4 col-md-3">
        <?php if ( empty( $cruise_meta["trav_cruise_d_edit_booking"] ) || empty( $cruise_meta["trav_cruise_d_edit_booking"][0] ) || empty( $cruise_meta["trav_cruise_d_cancel_booking"] ) || empty( $cruise_meta["trav_cruise_d_cancel_booking"][0] ) ) { ?>
            <div class="travelo-box edit-booking">

                <?php do_action( 'trav_cruise_conf_sidebar_before', $booking_data ); ?>

                <h4><?php echo __('Is Everything Correct?','trav')?></h4>
                <p><?php echo __( 'You can always view or change your booking online - no registration required', 'trav' ) ?></p>
                <ul class="triangle hover box">
                    <?php if ( empty( $cruise_meta["trav_cruise_d_edit_booking"] ) || empty( $cruise_meta["trav_cruise_d_edit_booking"][0] ) ) { ?>
                        <li><a href="#change-date" class="soap-popupbox"><?php echo __('Change Dates & Guest Details','trav')?></a></li>
                        <li><a href="#change-cabin" class="soap-popupbox"><?php echo __('Change your cabin','trav')?></a></li>
                    <?php } ?>
                    <?php if ( empty( $cruise_meta["trav_cruise_d_cancel_booking"] ) || empty( $cruise_meta["trav_cruise_d_cancel_booking"][0] ) ) { ?>
                        <li><a href="<?php $query_args['pbsource'] = 'cancel_booking'; echo esc_url( add_query_arg( $query_args ,get_permalink( $cruise_id ) ) );?>" class="btn-cancel-booking"><?php echo __('Cancel your booking','trav')?></a></li>
                    <?php } ?>
                </ul>

                <?php do_action( 'trav_cruise_conf_sidebar_after', $booking_data ); ?>

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
        <input type="hidden" name="action" value="cruise_check_cabin_availability">
        <input type="hidden" name="booking_no" value="<?php echo esc_attr( $booking_data['booking_no'] ); ?>">
        <input type="hidden" name="pin_code" value="<?php echo esc_attr( $booking_data['pin_code'] ); ?>">
        <input type="hidden" name="duration" value="<?php echo esc_attr( $booking_data['duration'] ); ?>">
        <?php
            $cruise_schedules = trav_cruise_get_schedules( $booking_data['cruise_id'] );
        ?>
        <?php wp_nonce_field( 'booking-' . $booking_data['booking_no'], '_wpnonce', false ); ?>
        <div class="update-search clearfix">
            <div class="row">
                <div class="col-xs-12">
                    <label><?php _e( 'DATE FROM','trav' ); ?></label>
                    <div class="selector validation-field">
                        <select id="date_from" name="date_from" class="full-width">
                            <option value=""><?php _e( 'Select Date', 'trav' ); ?></option>
                            <?php 
                                if ( isset( $cruise_schedules ) && is_array( $cruise_schedules ) ) {
                                    foreach ( $cruise_schedules as $schedule ) {
                                        $selected = ( $schedule['date_from'] == $booking_data['date_from'] ) ? 'selected' : '';
                                        echo '<option value="' . esc_attr( $schedule['date_from'] ) . '" ' . $selected . ' data-cruise-duration="' . $schedule['duration'] . '">' . esc_html( trav_tophptime( $schedule['date_from'] ) ) . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-xs-4">
                    <label><?php _e( 'CABINS','trav' ); ?></label>
                    <div class="selector validation-field">
                        <select name="cabins" class="full-width">
                            <?php
                                $cabins = ( isset( $booking_data['cabins'] ) && is_numeric( (int) $booking_data['cabins'] ) )?(int) $booking_data['cabins']:1;
                                for ( $i = 1; $i <= $search_max_cabins; $i++ ) {
                                    $selected = '';
                                    if ( $i == $cabins ) $selected = 'selected';
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
<div id="change-cabin" class="travelo-box travelo-modal-box">
    <div>
        <a href="#" class="logo-modal"><?php bloginfo( 'name' );?><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo('name'); ?>"></a>
    </div>
    <form id="change-cabin-form" method="post">
        <input type="hidden" name="action" value="cruise_change_cabin">
        <input type="hidden" name="booking_no" value="<?php echo esc_attr( $booking_data['booking_no'] ); ?>">
        <input type="hidden" name="pin_code" value="<?php echo esc_attr( $booking_data['pin_code'] ); ?>">
        <?php wp_nonce_field( 'booking-' . $booking_data['booking_no'], '_wpnonce', false ); ?>
        <div class="update-search clearfix">
            <div class="row">
                <div class="col-xs-12">
                    <label><?php _e( 'AVAILABLE CANBINS','trav' ); ?></label>
                    <div class="selector validation-field">
                        <select name="cabin_type_id" class="full-width" data-original-val="<?php echo esc_attr( $cabin_type_id ) ?>">
                            <?php
                                $return_value = trav_cruise_get_available_cabins( $cruise_id, $booking_data['date_from'], $booking_data['duration'], $booking_data['cabins'], $booking_data['adults'], $booking_data['kids'], $booking_data['child_ages'], $booking_data['booking_no'], $booking_data['pin_code'] );
                                if ( ! empty ( $return_value ) && is_array( $return_value ) ) {
                                    foreach ( $return_value['bookable_cabin_type_ids'] as $cabin_id ) {
                                        $cabin_price = 0;
                                        foreach ( $return_value['check_dates'] as $check_date ) {
                                            $cabin_price += (float) $return_value['prices'][ $cabin_id ][ $check_date ]['total'];
                                        }
                                        $cabin_price *= ( 1 + $tax_rate / 100 );
                                        $selected = '';
                                        $cabin_id_lang = trav_cabin_clang_id( $cabin_id );
                                        if ( $cabin_id_lang == $cabin_type_id ) $selected = 'selected';
                                        echo '<option value="' . esc_attr( $cabin_id ) . '" ' . esc_attr( $selected ) . '>' . esc_html( get_the_title( $cabin_id_lang ) . ' (' . trav_get_price_field( $cabin_price, $booking_data['currency_code'], 0 ) . ')' ) . '</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12">
                    <button id="update_booking_cabin" data-animation-duration="1" data-animation-type="bounce" class="full-width icon-check animated bounce" type="submit"><?php _e( "CHANGE cabin", "trav" ); ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<style>#ui-datepicker-div {z-index: 10004 !important;} .update-search > div.row > div {margin-bottom: 10px;} .booking-details .other-details dt, .booking-details .other-details dd {padding: 0.2em 0;} .update-search > div.row > div:last-child {margin-bottom:0 !important;} </style>
<script>
    tjq = jQuery;
    tjq(document).ready(function(){
        tjq("#date_from").change(function() {
            
        });
        tjq('.btn-cancel-booking').click(function(e){
            e.preventDefault();
            var r = confirm("<?php echo __('Do you really want to cancel this booking?', 'trav') ?>");
            if (r == true) {
                tjq.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action : 'cruise_cancel_booking',
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
            tjq('input[name="duration"]').val( tjq('select[name="date_from"]').find(":selected").data('cruise-duration') );

            var booking_data = tjq('#change-date-form').serialize();
            tjq('.update_booking_date_row').hide();

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
            tjq('#change-date-form input[name="action"]').val('cruise_update_booking_date');
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
        tjq('#update_booking_cabin').click(function(){
            if ( tjq('select[name="cabin_type_id"]').val() == tjq('select[name="cabin_type_id"]').data('original-val') ) {
                tjq(".opacity-overlay").fadeOut();
                return false;
            }
            var booking_data = tjq('#change-cabin-form').serialize();
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