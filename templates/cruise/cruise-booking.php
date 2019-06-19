<?php
/*
 * Cruise Booking Form
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // exit if accessed directly
}

global $trav_options, $def_currency;
global $trav_booking_page_data, $is_payment_enabled;

do_action( 'trav_cruise_booking_before' ); // init $trav_booking_page_data

$booking_data = $trav_booking_page_data['booking_data'];
$transaction_id = $trav_booking_page_data['transaction_id'];
$review = $trav_booking_page_data['review'];
$cruise_url = $trav_booking_page_data['cruise_url'];
$edit_url = $trav_booking_page_data['edit_url'];
$cabin_price_data = $trav_booking_page_data['cabin_price_data'];
$is_payment_enabled = $trav_booking_page_data['is_payment_enabled'];
$tax_rate = $trav_booking_page_data['tax_rate'];
$tax = $trav_booking_page_data['tax'];
$discount_rate = $booking_data['discount_rate'];

$action_url = $trav_booking_page_data['cruise_book_conf_url'];
$post_id = $booking_data['cabin_type_id'];
$action = 'cruise_submit_booking';
?>

<div class="row">
    <div class="col-sms-6 col-sm-8 col-md-9">
        <div class="booking-section travelo-box">

            <?php do_action( 'trav_cruise_booking_form_before', $booking_data ); ?>
            <form class="booking-form" method="POST" action="<?php echo esc_url( $action_url ); ?>">
                <input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
                <input type="hidden" name="transaction_id" value='<?php echo esc_attr( $transaction_id ) ?>'>
                <?php wp_nonce_field( 'post-' . $post_id, '_wpnonce', false ); ?>

                <?php trav_get_template( 'booking-form.php', '/templates/booking/' ); ?>
            </form>
            <?php do_action( 'trav_cruise_booking_form_after', $booking_data ); ?>

        </div>
    </div>
    <div class="sidebar col-sms-6 col-sm-4 col-md-3">
        <div class="booking-details travelo-box">

            <?php do_action( 'trav_cruise_booking_sidebar_before', $booking_data ); ?>

            <h4><?php _e( 'Booking Details', 'trav'); ?></h4>
            <article class="image-box cruise listing-style1">
                <figure class="clearfix">
                    <a href="<?php echo esc_url( $cruise_url ); ?>" class="hover-effect middle-block">
                        <?php echo get_the_post_thumbnail( $booking_data['cruise_id'], 'thumbnail', array( 'class'=>'middle-item' ) ); ?>
                    </a>
                    <div class="travel-title">
                        <h5 class="box-title">
                            <a href="<?php echo esc_url( $cruise_url ); ?>"><?php echo esc_html( get_the_title( $booking_data['cruise_id'] ) );?></a>
                            <small>
                                <?php echo esc_html( get_post_meta( $booking_data['cruise_id'], 'trav_cruise_ship_name', true ) ); ?>                                
                            </small>
                        </h5>
                        <a href="<?php echo esc_url( $edit_url );?>" class="button"><?php _e( 'EDIT', 'trav'); ?></a>
                    </div>
                </figure>
                <div class="details">
                    <div class="feedback">
                        <div data-placement="bottom" data-toggle="tooltip" class="five-stars-container" title="<?php echo esc_attr( $review . ' ' . __( 'stars', 'trav' ) ) ?>"><span style="width: <?php echo esc_attr( $review / 5 * 100 ) ?>%;" class="five-stars"></span></div>
                        <span class="review"><?php echo esc_html( trav_get_review_count( $booking_data['cruise_id'] ) . ' ' .  __('reviews', 'trav') ); ?></span>
                    </div>
                    <div class="constant-column-3 timing clearfix">
                        <div class="check-in">
                            <label><?php _e( 'Departs', 'trav'); ?></label>
                            <span><?php echo date( "M j, Y", trav_strtotime( $booking_data['date_from'] ) );?></span>
                        </div>
                        <div class="duration text-center">
                            <i class="soap-icon-clock"></i>
                            <span>
                                <?php echo esc_html( trav_get_day_interval( $booking_data['date_from'], $booking_data['date_to'] ) . ' ' . __( 'Nights', 'trav' ) ); ?>
                            </span>
                        </div>
                        <div class="check-out">
                            <label><?php _e( 'Arrival', 'trav'); ?></label>
                            <span><?php echo date( "M j, Y", trav_strtotime( $booking_data['date_to'] ) );?></span>
                        </div>
                    </div>
                    <div class="guest">
                        <small class="uppercase"><?php echo esc_html( $booking_data['cabins'] . ' ' . get_the_title( $booking_data['cabin_type_id'] ) ); ?> for <span class="skin-color"><?php echo esc_html( $booking_data['adults'] . ' ' . __( 'Adults', 'trav' ) ); if ( ! empty( $booking_data['kids'] ) ) echo ' &amp; ' . esc_html( $booking_data['kids'] . ' ' . __( 'Kids', 'trav' ) );?></span></small>
                    </div>
                </div>
            </article>

            <h4><?php _e( 'Other Details', 'trav' ); ?></h4>
            <dl class="other-details">
                <dt class="feature"><?php _e( 'cabin Type', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( get_the_title( $booking_data['cabin_type_id'] ) );?></dd>
                <dt class="feature"><?php echo esc_html( trav_get_day_interval( $booking_data['date_from'], $booking_data['date_to'] ) . ' ' .__( 'night Stay', 'trav') ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $cabin_price_data['total_price'] ) ) ?></dd>

                <?php if ( ! empty( $tax_rate ) ) : ?>
                    <dt class="feature"><?php echo __( 'taxes and fees', 'trav') ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $tax ) ) ?></dd>
                <?php endif; ?>
                <?php if ( ! empty( $discount_rate ) ) : ?>
                    <dt class="feature"><?php echo __( 'Discount', 'trav') ?>:</dt><dd class="value"><?php echo '-' . $discount_rate . '%' ?></dd>
                <?php endif; ?>
                <?php if ( $is_payment_enabled ) : ?>
                    <dt class="feature"><?php _e( 'Security Deposit', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
                <?php endif; ?>

                <dt class="total-price"><?php _e( 'Total Price', 'trav'); ?></dt><dd class="total-price-value"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] ) ) ?></dd>
            </dl>

            <a href="#" class="show-price-detail" data-show-desc="<?php _e( 'Show Price Detail', 'trav' ) ?>" data-hide-desc="<?php _e( 'Hide Price Detail', 'trav' ) ?>"><?php _e( 'Show Price Detail', 'trav' ) ?></a><br />
            <dl class="price-details clearer">
                <?php
                    if ( is_array( $cabin_price_data['check_dates'] ) ) :
                    foreach ( $cabin_price_data['check_dates'] as $check_date ) {
                        echo '<dt class="feature">' . esc_html( $check_date ) . ':</dt><dd class="value clearfix"><table>';

                        if ( ! empty( $cabin_price_data['prices'][ $check_date ]['ppr'] ) ) {
                            echo '<tr><td>';
                            echo __('price per cabin', 'trav') . '</td><td>' . esc_html( trav_get_price_field( $cabin_price_data['prices'][ $check_date ]['ppr'] ) );
                            echo '</td></tr>';
                        }
                        if ( ! empty( $cabin_price_data['prices'][ $check_date ]['ppp'] ) ) {
                            echo '<tr><td>';
                            echo __('price per person', 'trav') . '</td><td>' . esc_html( trav_get_price_field( $cabin_price_data['prices'][ $check_date ]['ppp'] ) ) ;
                            echo '</td></tr>';
                        }
                        if ( ! empty( $cabin_price_data['prices'][ $check_date ]['cp'] ) ) {
                            $i = 0;
                            foreach ( $cabin_price_data['prices'][ $check_date ]['cp'] as $child_price) {
                                $i++;
                                echo '<tr><td>';
                                echo __( 'child', 'trav' ) . esc_html( $i ) . ' ' . __( 'price', 'trav') . '</td><td>' . esc_html( trav_get_price_field( $child_price ) );
                                echo '</td></tr>';
                            }
                        }

                        echo '<tr><td>';
                        echo __( 'total', 'trav' ) . '</td><td>' . esc_html( trav_get_price_field( $cabin_price_data['prices'][ $check_date ]['total'] ) ) ;
                        echo '</td></tr></table></dd>';
                    }
                    endif;
                ?>
            </dl>

            <?php do_action( 'trav_cruise_booking_sidebar_after', $booking_data ); ?>

        </div>

        <?php generated_dynamic_sidebar(); ?>

    </div>
</div>

<script>
    jQuery(document).ready( function(tjq) {
        var validation_rules = {
                first_name: { required: true },
                last_name: { required: true },
                email: { required: true, email: true },
                email2: { required: true, equalTo: 'input[name="email"]' },
                phone: { required: true },
                address: { required: true },
                city: { required: true },
                zip: { required: true },
            };

        if ( tjq('input[name="security_code"]').length ) {
            validation_rules['security_code'] = { required: true };
        }

        if ( tjq('input[name="cc_type"]').length ) {
            validation_rules['cc_type'] = { required: true };
            validation_rules['cc_holder_name'] = { required: true };
            validation_rules['cc_number'] = { required: true };
        }

        //validation form
        tjq('.booking-form').validate({
            rules: validation_rules,
            submitHandler: function (form) {
                if ( tjq('input[name="agree"]').length ) {
                    if ( tjq('input[name="agree"]:checked').length == 0 ) {
                        alert("<?php echo esc_js( __( 'Agree to terms&conditions is required' ,'trav' ) ); ?>");
                        return false;
                    }
                }

                var booking_data = tjq('.booking-form').serialize();
                tjq.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: booking_data,
                    success: function ( response ) {
                        console.log(response);
                        if ( response.success == 1 ) {
                            if ( response.result.payment == 'woocommerce' ) {
                                <?php if ( function_exists( 'trav_woo_get_cart_page_url' ) && trav_woo_get_cart_page_url() ) { ?>
                                    window.location.href = '<?php echo esc_js( trav_woo_get_cart_page_url() ); ?>';
                                <?php } else { ?>
                                    trav_show_modal( 0, "<?php echo esc_js( __( 'Please set woocommerce cart page', 'trav' ) ); ?>", '' );
                                <?php } ?>
                            } else {
                                if ( response.result.payment == 'paypal' ) {
                                    tjq('.confirm-booking-btn').before('<div class="alert alert-success"><?php echo esc_js( __( 'You will be redirected to paypal.', 'trav' ) ) ?><span class="close"></span></div>');
                                }

                                var confirm_url = tjq('.booking-form').attr('action');

                                if ( confirm_url.indexOf('?') > -1 ) {
                                    confirm_url = confirm_url + '&';
                                } else {
                                    confirm_url = confirm_url + '?';
                                }

                                confirm_url = confirm_url + 'booking_no=' + response.result.booking_no + '&pin_code=' + response.result.pin_code + '&transaction_id=' + response.result.transaction_id + '&message=1';

                                tjq('.confirm-booking-btn').hide();

                                setTimeout( function(){ 
                                    tjq('.opacity-ajax-overlay').show(); 
                                }, 500 );

                                window.location.href = confirm_url;
                            }
                        } else if ( response.success == -1 ) {
                            alert( response.result );

                            setTimeout( function(){ tjq('.opacity-ajax-overlay').show(); }, 500 );
                            window.location.href = '<?php echo esc_js( $edit_url ); ?>';
                        } else {
                            // console.log( response );
                            trav_show_modal( 0, response.result, '' );
                        }
                    }
                });

                return false;
            }
        });

        tjq('.show-price-detail').click( function(e){
            e.preventDefault();

            tjq('.price-details').toggle();
            if (tjq('.price-details').is(':visible')) {
                tjq(this).html( tjq(this).data('hide-desc') );
            } else {
                tjq(this).html( tjq(this).data('show-desc') );
            }

            return false;
        });
    });
</script>