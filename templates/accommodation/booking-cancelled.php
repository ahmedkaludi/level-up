<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * Accommodation Booking Cancelled Form
 */
global $booking_data, $acc_id;
$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
?>

<div class="row">
	<div id="main" class="col-sm-8 col-md-9">
		<div class="booking-information travelo-box">

			<?php do_action( 'trav_acc_conf_cancel_form_before', $booking_data ); ?>

			<h3><?php _e( 'Cancelled', 'trav' ); ?></h3>
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
			<a href="<?php echo esc_url( get_permalink( $acc_id ) ); ?>" class="button btn-small green"><?php _e( "BOOK AGAIN", "trav" ); ?></a>
			<hr />

			<?php trav_get_template( 'acc-room-detail.php', '/templates/accommodation/' ); ?>
			<?php do_action( 'trav_acc_conf_cancel_form_after', $booking_data ); ?>
		</div>
	</div>
	<div class="sidebar col-sm-4 col-md-3">
		<?php do_action( 'trav_acc_conf_cancel_sidebar_before', $booking_data ); ?>
		<?php generated_dynamic_sidebar(); ?>
		<?php do_action( 'trav_acc_conf_cancel_sidebar_after', $booking_data ); ?>
	</div>
</div>