<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * Car Booking Success Form
 */
global $logo_url;
global $booking_data, $car_id, $deposit_rate, $date_from, $date_to;
$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
$car_meta = get_post_meta( $car_id );
$tax = get_post_meta( $car_id, 'trav_car_tax', true );
?>

<div class="row">
	<div class="col-sm-8 col-md-9">
		<div class="booking-information travelo-box">

			<?php do_action( 'trav_car_conf_form_before', $booking_data ); ?>

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
				$booking_detail = array('booking_no' => array( 'label' => __('Booking Number', 'trav'), 'pre' => '', 'sur' => '' ),
										'pin_code' => array( 'label' => __('Pin Code', 'trav'), 'pre' => '', 'sur' => '' ),
										'email' => array( 'label' => __('E-mail address', 'trav'), 'pre' => '', 'sur' => '' ),
										'date_from' => array( 'label' => __('Pick-Up Date', 'trav'), 'pre' => '', 'sur' => '' ),
										'date_to' => array( 'label' => __('Drop-Off Date', 'trav'), 'pre' => '', 'sur' => '' ),
										'time_from' => array( 'label' => __('Pick-Up Time', 'trav'), 'pre' => '', 'sur' => '' ),
										'time_to' => array( 'label' => __('Drop-Off Time', 'trav'), 'pre' => '', 'sur' => '' ),
										'location_from' => array( 'label' => __('Pick-Up Location', 'trav'), 'pre' => '', 'sur' => '' ),
										'location_to' => array( 'label' => __('Drop-Off Location', 'trav'), 'pre' => '', 'sur' => '' ),
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
			<dl class="term-description">
				<dt><?php echo __( 'Price', 'trav' ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></dd>
				<?php if ( ! empty( $tax ) ) : ?>
					<dt><?php _e('VAT Included', 'trav' ); ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['tax'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></dd>
				<?php endif; ?>
				<?php if ( ! ( $booking_data['deposit_price'] == 0 ) ) : ?>
					<dt><?php printf( __('Security Deposit(%d%%)', 'trav' ), $deposit_rate ) ?>:</dt><dd><?php echo esc_html( trav_get_price_field( $booking_data['deposit_price'], $booking_data['currency_code'], 0 ) ) ?></dd>
				<?php endif; ?>
			</dl>
			<dl class="term-description" style="font-size: 16px;" >
				<dt style="text-transform: none;"><?php echo __( 'Total Price', 'trav' ) ?></dt><dd><b style="color: #2d3e52;"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] * $booking_data['exchange_rate'], $booking_data['currency_code'], 0 ) ) ?></b></dd>
			</dl>
			<hr />

			<?php trav_get_template( 'car-detail.php', '/templates/car/' ); ?>
			<?php do_action( 'trav_car_conf_form_after', $booking_data ); ?>
		</div>
	</div>
	<div class="sidebar col-sm-4 col-md-3">
		<?php if ( empty( $car_meta["trav_car_d_edit_booking"] ) || empty( $car_meta["trav_car_d_edit_booking"][0] ) || empty( $car_meta["trav_car_d_cancel_booking"] ) || empty( $car_meta["trav_car_d_cancel_booking"][0] ) ) { ?>
			<div class="travelo-box edit-booking">

				<?php do_action( 'trav_car_conf_sidebar_before', $booking_data ); ?>

				<h4><?php echo __('Is Everything Correct?','trav')?></h4>
				<p><?php echo __( 'You can always view or change your booking online - no registration required', 'trav' ) ?></p>
				<ul class="triangle hover box">
					<?php if ( empty( $car_meta["trav_car_d_edit_booking"] ) || empty( $car_meta["trav_car_d_edit_booking"][0] ) ) { ?>
						<li><a href="#change-date" class="soap-popupbox"><?php echo __('Change Dates & Location Details','trav')?></a></li>
					<?php } ?>
					<?php if ( empty( $car_meta["trav_car_d_cancel_booking"] ) || empty( $car_meta["trav_car_d_cancel_booking"][0] ) ) { ?>
						<li><a href="<?php $query_args['pbsource'] = 'cancel_booking'; echo esc_url( add_query_arg( $query_args, get_permalink( $car_id ) ) );?>" class="btn-cancel-booking"><?php echo __( 'Cancel your booking', 'trav' ); ?></a></li>
					<?php } ?>
				</ul>

				<?php do_action( 'trav_car_conf_sidebar_after', $booking_data ); ?>

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
		<input type="hidden" name="action" value="car_check_availability">
		<input type="hidden" name="booking_no" value="<?php echo esc_attr( $booking_data['booking_no'] ); ?>">
		<input type="hidden" name="pin_code" value="<?php echo esc_attr( $booking_data['pin_code'] ); ?>">
		<input type="hidden" name="car_id" value="<?php echo esc_attr( $car_id ); ?>">
		<?php wp_nonce_field( 'booking-' . $booking_data['booking_no'], '_wpnonce', false ); ?>
		<div class="update-search clearfix">
			<div class="row">
				<div class="col-xs-6">
					<label><?php _e( 'PICK-UP DATE','trav' ); ?></label>
					<div class="datepicker-wrap validation-field from-today">
						<input name="date_from" type="text" placeholder="<?php echo trav_get_date_format('html') ?>" class="input-text full-width" value="<?php echo esc_attr( $date_from ); ?>" />
					</div>
				</div>
				<div class="col-xs-6">
					<label><?php _e( 'DROP-OFF DATE','trav' ); ?></label>
					<div class="datepicker-wrap validation-field from-today">
						<input name="date_to" type="text" placeholder="<?php echo trav_get_date_format('html') ?>" class="input-text full-width" value="<?php echo esc_attr( $date_to ); ?>" />
					</div>
				</div>
				<div class="col-xs-6">
					<label><?php _e( 'PICK-UP TIME','trav' ); ?></label>
					<div class="validation-field">
						<input name="time_from" type="text" placeholder="" class="input-text full-width" value="<?php echo esc_attr( $time_from ); ?>" />
					</div>
				</div>
				<div class="col-xs-6">
					<label><?php _e( 'DROP-OFF TIME','trav' ); ?></label>
					<div class=" validation-field">
						<input name="time_to" type="text" placeholder="" class="input-text full-width" value="<?php echo esc_attr( $time_to ); ?>" />
					</div>
				</div>
				<div class="col-xs-12">
					<label><?php _e( 'PICK-UP LOCATION','trav' ); ?></label>
					<div class="validation-field">
						<input name="location_from" type="text" placeholder="" class="input-text full-width" value="<?php echo esc_attr( $location_from ); ?>" />
					</div>
				</div>
				<div class="col-xs-12">
					<label><?php _e( 'DROP-OFF LOCATION','trav' ); ?></label>
					<div class="validation-field">
						<input name="location_to" type="text" placeholder="" class="input-text full-width" value="<?php echo esc_attr( $location_to ); ?>" />
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
						action : 'car_cancel_booking',
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
		tjq('body').on('change', '#change-date-form input', function(){
			var booking_data = tjq('#change-date-form').serialize();
			tjq('.update_booking_date_row').hide();
			var date_from = tjq('#change-date-form').find('input[name="date_from"]').datepicker("getDate").getTime();
			var date_to = tjq('#change-date-form').find('input[name="date_to"]').datepicker("getDate").getTime();
			var one_day=1000*60*60*24;

			if (date_from >= date_to) { alert('<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.', 'trav' ); ?>'); return false; }

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
			tjq('#change-date-form input[name="action"]').val('car_update_booking_date');
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
	});
</script>