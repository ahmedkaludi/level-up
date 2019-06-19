<?php
/*
 * Tour Booking Form
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // exit if accessed directly
}

global $trav_options, $def_currency;
global $trav_booking_page_data, $is_payment_enabled;

do_action( 'trav_tour_booking_before' ); // init $trav_booking_page_data

$booking_data = $trav_booking_page_data['booking_data'];
$transaction_id = $trav_booking_page_data['transaction_id'];
$tour_url = $trav_booking_page_data['tour_url'];
$price_data = $trav_booking_page_data['price_data'];
$multi_book = $trav_booking_page_data['multi_book'];
$is_payment_enabled = $trav_booking_page_data['is_payment_enabled'];

$action_url = $trav_booking_page_data['tour_book_conf_url'];
$post_id = $booking_data['tour_id'];
$action = 'tour_submit_booking';
?>

<div class="row">
    <div class="col-sms-6 col-sm-8 col-md-9">
        <div class="booking-section travelo-box">

            <?php do_action( 'trav_tour_booking_form_before', $booking_data ); ?>
            <form class="booking-form" method="POST" action="<?php echo esc_url( $action_url ); ?>">
                <input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
                <input type="hidden" name="transaction_id" value='<?php echo esc_attr( $transaction_id ) ?>'>
                <?php wp_nonce_field( 'post-' . $post_id, '_wpnonce', false ); ?>

                <?php trav_get_template( 'booking-form.php', '/templates/booking/' ); ?>

            </form>
            <?php do_action( 'trav_tour_booking_form_after', $booking_data ); ?>

        </div>
    </div>
    <div class="sidebar col-sms-6 col-sm-4 col-md-3">
        <div class="booking-details travelo-box">

            <?php do_action( 'trav_tour_booking_sidebar_before', $booking_data ); ?>

            <h4><?php _e( 'Booking Details', 'trav'); ?></h4>
            <article class="tour-detail">
                <figure class="clearfix">
                    <a href="<?php echo esc_url( $tour_url ); ?>" class="hover-effect middle-block">
                        <?php echo get_the_post_thumbnail( $booking_data['tour_id'], 'thumbnail', array( 'class'=>'middle-item' ) ); ?>
                    </a>
                    <div class="travel-title">
                        <h5 class="box-title"><a href="<?php echo esc_url( $tour_url ); ?>"><?php echo esc_html( get_the_title( $booking_data['tour_id'] ) );?></a>
                            <small>
                                <?php echo trav_tour_get_schedule_type_title( $booking_data['tour_id'], $booking_data['st_id'] ) ?>
                            </small>
                        </h5>
                        <a href="<?php echo esc_url( $tour_url );?>" class="button"><?php _e( 'EDIT', 'trav'); ?></a>
                    </div>
                </figure>
                <div class="details">
                    <div class="icon-box style12 style13 full-width">
                        <div class="icon-wrapper">
                            <i class="soap-icon-calendar"></i>
                        </div>
                        <dl class="details">
                            <dt class="skin-color"><?php _e( 'Date', 'trav' ) ?></dt>
                            <dd><?php echo date_i18n( "M j, Y", trav_strtotime( $booking_data['tour_date'] ) );?></dd>
                        </dl>
                    </div>
                    <div class="icon-box style12 style13 full-width">
                        <div class="icon-wrapper">
                            <i class="soap-icon-clock"></i>
                        </div>
                        <dl class="details">
                            <dt class="skin-color"><?php _e( 'Duration', 'trav' ) ?></dt>
                            <dd><?php echo $price_data['duration'] ?></dd>
                        </dl>
                    </div>
                    <div class="icon-box style12 style13 full-width">
                        <div class="icon-wrapper">
                            <i class="soap-icon-departure"></i>
                        </div>
                        <dl class="details">
                            <dt class="skin-color"><?php _e( 'Location', 'trav' ) ?></dt>
                            <dd><?php echo esc_html( trav_tour_get_city( $booking_data['tour_id'] ) ); ?>, <?php echo esc_html( trav_tour_get_country( $booking_data['tour_id'] ) ); ?></dd>
                        </dl>
                    </div>
                </div>
            </article>
            
            <h4><?php _e( 'Other Details', 'trav' ); ?></h4>
            <dl class="other-details">
                <?php if ( ! empty( $multi_book ) ) : ?>
                    <dt class="feature"><?php _e( 'Price Per Adult', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $price_data['price'] ) ) ?></dd>
                    <?php if ( ! empty( $price_data['child_price'] ) && ( (float) $price_data['child_price'] ) > 0 ) : ?>
                        <dt class="feature"><?php _e( 'Price Per Child', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $price_data['child_price'] ) ) ?></dd>
                    <?php endif; ?>
                    <dt class="feature"><?php _e( 'Adults', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( $booking_data['adults'] ) ?></dd>
                    <?php if ( ! empty( $booking_data['kids'] ) ) : ?>
                        <dt class="feature"><?php _e( 'Kids', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( $booking_data['kids'] ) ?></dd>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ( ! empty( $booking_data['discount_rate'] ) ) : ?>
                    <dt class="feature"><?php _e( 'Discount', 'trav' ); ?>:</dt><dd class="value"><?php echo '-' . $booking_data['discount_rate'] . '%' ?></dd>
                <?php endif; ?>

                <?php if ( $is_payment_enabled ) : ?>
                    <dt class="feature"><?php _e( 'Security Deposit', 'trav' ); ?>:</dt><dd class="value"><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
                <?php endif; ?>

                <dt class="total-price"><?php _e( 'Total Price', 'trav'); ?></dt><dd class="total-price-value"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] ) ) ?></dd>
            </dl>

            <?php do_action( 'trav_tour_booking_sidebar_after', $booking_data ); ?>

        </div>

        <?php generated_dynamic_sidebar(); ?>
    </div>
</div>

<script type="text/javascript">
    tjq = jQuery;

    tjq(document).ready(function(){
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
            submitHandler: function( form ) {
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
                                setTimeout( function(){ tjq('.opacity-ajax-overlay').show(); }, 500 );
                                window.location.href = confirm_url;
                            }
                        } else if ( response.success == -1 ) {
                            alert( response.result );

                            setTimeout( function(){ tjq('.opacity-ajax-overlay').show(); }, 500 );
                            window.location.href = '<?php echo esc_js( $tour_url ); ?>';
                        } else {
                            // console.log( response );
                            trav_show_modal( 0, response.result, '' );
                        }
                    }
                });
                return false;
            }
        });
    });
</script>