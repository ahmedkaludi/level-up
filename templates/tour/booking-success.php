<?php
/*
 * Tour Booking Success Form
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $booking_data, $tour_id, $deposit_rate;

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
$tour_meta = get_post_meta( $tour_id );
?>

<div class="row">
    <div class="col-sm-8 col-md-9">
        <div class="booking-information travelo-box">

            <?php do_action( 'trav_tour_conf_form_before', $booking_data ); ?>

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
                    'tour_date'     => array( 'label' => __('Tour Date', 'trav'), 'pre' => '', 'sur' => '' ),
                    'adults'        => array( 'label' => __('Adults', 'trav'), 'pre' => '', 'sur' => '' ),
                    'kids'          => array( 'label' => __('Kids', 'trav'), 'pre' => '', 'sur' => '' ),
                );

                foreach ( $booking_detail as $field => $value ) {
                    if ( empty( $$field ) ) $$field = empty( $booking_data[ $field ] )?'':$booking_data[ $field ];
                    if ( ! empty( $$field ) ) {
                        $content = $value['pre'] . $$field . $value['sur'];
                        echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
                    }
                }
                ?>
            </dl>
            
            <hr />
            
            <?php if ( ! empty( $booking_data['discount_rate'] ) ) : ?>
                <dl class="term-description">
                    <dt><?php echo __('Discount', 'trav' ) ?>:</dt><dd><?php echo '-' . $booking_data['discount_rate'] . '%' ?></dd>
                </dl>
            <?php endif; ?>

            <hr />
            
            <?php if ( ! ( $booking_data['deposit_price'] == 0 ) ) : ?>
                <dl class="term-description">
                    <dt><?php printf( __('Security Deposit(%d%%)', 'trav' ), $deposit_rate ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
                </dl>
            <?php endif; ?>
            
            <dl class="term-description" style="font-size: 16px;" >
                <dt style="text-transform: none;"><?php echo __( 'Total Price', 'trav' ) ?></dt><dd><b style="color: #2d3e52;"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></b></dd>
            </dl>

            <hr />

            <?php trav_get_template( 'tour-detail.php', '/templates/tour/' ); ?>
            <?php do_action( 'trav_tour_conf_form_after', $booking_data ); ?>
        </div>
    </div>

    <div class="sidebar col-sm-4 col-md-3">
        <?php if ( empty( $tour_meta["trav_tour_d_edit_booking"] ) || empty( $tour_meta["trav_tour_d_edit_booking"][0] ) || empty( $tour_meta["trav_tour_d_cancel_booking"] ) || empty( $tour_meta["trav_tour_d_cancel_booking"][0] ) ) { ?>
            <div class="travelo-box edit-booking">

                <?php do_action( 'trav_tour_conf_sidebar_before', $booking_data ); ?>

                <h4><?php echo __('Is Everything Correct?','trav')?></h4>
                <p><?php echo __( 'You can always view or cancel your booking online - no registration required', 'trav' ) ?></p>
                <ul class="triangle hover box">
                    <?php if ( empty( $tour_meta["trav_tour_d_cancel_booking"] ) || empty( $tour_meta["trav_tour_d_cancel_booking"][0] ) ) { ?>
                        <li><a href="<?php $query_args['pbsource'] = 'cancel_booking'; echo esc_url( add_query_arg( $query_args ,get_permalink( $tour_id ) ) );?>" class="btn-cancel-booking"><?php echo __('Cancel your booking','trav')?></a></li>
                    <?php } ?>
                </ul>

                <?php do_action( 'trav_tour_conf_sidebar_after', $booking_data ); ?>

            </div>
        <?php } ?>

        <?php generated_dynamic_sidebar(); ?>
    </div>
</div>

<script type="text/javascript">
    tjq = jQuery;

    tjq(document).ready(function(){
        tjq('.btn-cancel-booking').click(function(e){
            e.preventDefault();

            var r = confirm("<?php echo __('Do you really want to cancel this booking?', 'trav') ?>");

            if ( r == true ) {
                tjq.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action : 'tour_cancel_booking',
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
    });
</script>