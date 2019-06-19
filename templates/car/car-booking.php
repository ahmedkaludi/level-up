<?php
global $trav_options, $def_currency;
global $trav_booking_page_data, $is_payment_enabled;
do_action( 'trav_car_booking_before' ); // init $trav_booking_page_data
$booking_data = $trav_booking_page_data['booking_data'];
$transaction_id = $trav_booking_page_data['transaction_id'];
$car_url = $trav_booking_page_data['car_url'];
$edit_url = $trav_booking_page_data['edit_url'];
$car_price_data = $trav_booking_page_data['car_price_data'];
$is_payment_enabled = $trav_booking_page_data['is_payment_enabled'];
$tax = $trav_booking_page_data['tax'];

$action_url = $trav_booking_page_data['car_book_conf_url'];
$post_id = $booking_data['car_id'];
$action = 'car_submit_booking';
?>

<div class="row">
	<div class="col-sms-6 col-sm-8 col-md-9">
		<div class="booking-section travelo-box">

			<?php do_action( 'trav_car_booking_form_before', $booking_data ); ?>
			<form class="booking-form" method="POST" action="<?php echo esc_url( $action_url ); ?>">
				<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
				<input type="hidden" name="transaction_id" value='<?php echo esc_attr( $transaction_id ) ?>'>
				<?php wp_nonce_field( 'post-' . $post_id, '_wpnonce', false ); ?>

				<?php trav_get_template( 'booking-form.php', '/templates/booking/' ); ?>
			</form>
			<?php do_action( 'trav_car_booking_form_after', $booking_data ); ?>

		</div>
	</div>
	<div class="sidebar col-sms-6 col-sm-4 col-md-3">
        
        <div class="booking-details travelo-box">
        	<?php do_action( 'trav_car_booking_sidebar_before', $booking_data ); ?>
            <h4><?php _e( 'Booking Details', 'trav'); ?></h4>
            <article class="car-detail">
                <figure class="clearfix">
                    <a href="<?php echo esc_url( $car_url ); ?>" class="hover-effect middle-block">
						<?php echo get_the_post_thumbnail( $booking_data['car_id'], 'thumbnail', array( 'class'=>'middle-item' ) ); ?>
					</a>					
                    <div class="travel-title">
                        <h5 class="box-title">
                        	<?php echo esc_html( get_the_title( $booking_data['car_id'] ) );?>
                            <?php
                            	$car_type = wp_get_post_terms( $booking_data['car_id'], 'car_type' );
                            	if ( ! empty ( $car_type ) ) {
									echo '<small>' . esc_attr( $car_type[0]->name ) . '</small>';
								}
							?>	
                        </h5>
                        <a href="<?php echo esc_url( $edit_url );?>" class="button"><?php _e( 'EDIT', 'trav'); ?></a>
                    </div>
                </figure>
                <div class="details">
                    <div class="icon-box style11 full-width">
                        <div class="icon-wrapper">
                            <i class="soap-icon-departure"></i>
                        </div>
                        <dl class="details">
                            <dt class="skin-color"><?php _e( 'Date', 'trav'); ?></dt>
                            <dd><?php echo date( "M j, Y", trav_strtotime( $booking_data['date_from'] ) );?> <?php _e( 'to', 'trav'); ?> <?php echo date( "M j, Y", trav_strtotime( $booking_data['date_to'] ) );?></dd>
                        </dl>
                    </div>
                    <div class="icon-box style11 full-width">
                        <div class="icon-wrapper">
                            <i class="soap-icon-departure"></i>
                        </div>
                        <dl class="details">
                            <dt class="skin-color"><?php _e( 'Time', 'trav'); ?></dt>
                            <dd><?php echo $booking_data['time_from']; ?> <?php _e( 'to', 'trav'); ?> <?php echo $booking_data['time_to']; ?></dd>
                        </dl>
                    </div>
                    <div class="icon-box style11 full-width">
                        <div class="icon-wrapper">
                            <i class="soap-icon-departure"></i>
                        </div>
                        <dl class="details">
                            <dt class="skin-color"><?php _e( 'Location', 'trav'); ?></dt>
                            <dd><?php echo $booking_data['location_from']; ?> <?php _e( 'to', 'trav'); ?> <?php echo $booking_data['location_to']; ?></dd>
                        </dl>
                    </div>
                </div>
            </article>
            
            <h4><?php _e( 'Other Details', 'trav'); ?></h4>
            <dl class="other-details">
            	<?php
            	$mileage = get_post_meta( $post_id, 'trav_car_mileage', true);
            	if ( isset( $mileage ) ) { ?>
                    <dt class="feature"><?php _e( 'Mileage included:', 'trav'); ?></dt><dd class="value"><?php echo esc_html( $mileage ); ?> <?php _e( 'miles', 'trav'); ?></dd>
                <?php } ?>
                <dt class="feature"><?php _e( 'Per day price:', 'trav'); ?></dt><dd class="value"><?php echo esc_html( trav_get_price_field( $car_price_data['price_per_day'] ) ); ?></dd>
                <dt class="feature"><?php _e( 'taxes and fees:', 'trav'); ?></dt><dd class="value"><?php echo esc_html( trav_get_price_field( $trav_booking_page_data['tax'] ) ); ?></dd>
                <dt class="total-price"><?php _e( 'Total Price', 'trav'); ?></dt><dd class="total-price-value"><?php echo esc_html( trav_get_price_field( $booking_data['total_price'] ) ); ?></dd>
            </dl>
            <?php do_action( 'trav_car_booking_sidebar_after', $booking_data ); ?>
        </div>

        <?php generated_dynamic_sidebar(); ?>        

    </div>
</div>


<script>
	tjq = jQuery;

	tjq(document).ready(function(){
		var validation_rules = {
				first_name: { required: true},
				last_name: { required: true},
				email: { required: true, email: true},
				email2: { required: true, equalTo: 'input[name="email"]'},
				phone: { required: true},
				address: { required: true},
				city: { required: true},
				zip: { required: true},
			};
		if ( tjq('input[name="security_code"]').length ) {
			validation_rules['security_code'] = { required: true};
		}
		if ( tjq('input[name="cc_type"]').length ) {
			validation_rules['cc_type'] = { required: true};
			validation_rules['cc_holder_name'] = { required: true};
			validation_rules['cc_number'] = { required: true};
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
								var confirm_url = tjq('.booking-form').attr('action')
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
							window.location.href = '<?php echo esc_js( $edit_url ); ?>';
						} else {
							console.log( response );
							trav_show_modal( 0, response.result, '' );
						}
					}
				});
				return false;
			}
		});
	});
</script>