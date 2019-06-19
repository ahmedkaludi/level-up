<?php
/*
 * Tour Booking Completed Form
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $booking_data, $tour_id;

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
?>

<div class="row">
    <div id="main" class="col-sm-8 col-md-9">
        <div class="booking-information travelo-box">

            <?php do_action( 'trav_tour_conf_completed_form_before', $booking_data ); ?>

            <h3><?php _e( 'Completed', 'trav' ); ?></h3>
            <hr />

            <dl class="term-description">
                <?php
                $booking_detail = array(
                    'booking_no' => __('Booking Number', 'trav'),
                    'pin_code' => __('Pin Code', 'trav'),
                );

                foreach ( $booking_detail as $field => $label ) {
                    $$field = empty( $booking_data[ $field ] )?'':$booking_data[ $field ];
                    if ( ! empty( $$field ) ) {
                        echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
                    }
                } ?>
            </dl>

            <a href="<?php echo esc_url( get_permalink( $tour_id ) ); ?>" class="button btn-small green"><?php _e( "BOOK AGAIN", "trav" ); ?></a>
            <hr />

            <?php trav_get_template( 'tour-detail.php', '/templates/tour/' ); ?>

            <?php do_action( 'trav_tour_conf_completed_form_after', $booking_data ); ?>
        </div>
    </div>

    <div class="sidebar col-sm-4 col-md-3">
        <?php do_action( 'trav_tour_conf_completed_sidebar_before', $booking_data ); ?>

        <?php generated_dynamic_sidebar(); ?>

        <?php do_action( 'trav_tour_conf_completed_sidebar_after', $booking_data ); ?>
    </div>
</div>