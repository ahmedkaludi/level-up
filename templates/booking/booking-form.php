<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$user_info = trav_get_current_user_info();
$_countries = trav_get_all_countries();
global $trav_options;

do_action( 'trav_booking_form_before' ); 
?>

<div class="person-information">
	<h2><?php _e( 'Your Personal Information', 'trav'); ?></h2>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'first name', 'trav'); ?></label>
			<input type="text" name="first_name" class="input-text full-width" value="<?php echo esc_attr( $user_info['first_name'] ) ?>" placeholder="" />
		</div>
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'last name', 'trav'); ?></label>
			<input type="text" name="last_name" class="input-text full-width" value="<?php echo esc_attr( $user_info['last_name'] ) ?>" placeholder="" />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'email address', 'trav'); ?></label>
			<input type="text" name="email" class="input-text full-width" value="<?php echo esc_attr( $user_info['email'] ) ?>" placeholder="" />
		</div>
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'Verify E-mail Address', 'trav'); ?></label>
			<input type="text" name="email2" class="input-text full-width" value="<?php echo esc_attr( $user_info['email'] ) ?>" placeholder="" />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'Country code', 'trav'); ?></label>
			<div class="selector">
				<select class="full-width" name="country_code">
					<?php foreach ( $_countries as $_country ) { ?>
						<option value="<?php echo esc_attr( $_country['d_code'] ) ?>" <?php selected( $user_info['country_code'], $_country['name'] . ' (' . $_country['d_code'] . ')' ); ?>><?php echo esc_html( $_country['name'] . ' (' . $_country['d_code'] . ')' ) ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'Phone number', 'trav'); ?></label>
			<input type="text" name="phone" class="input-text full-width" value="<?php echo esc_attr( $user_info['phone'] ) ?>" placeholder="" />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'address', 'trav'); ?></label>
			<input type="text" name="address" class="input-text full-width" value="<?php echo esc_attr( $user_info['address'] ) ?>" placeholder="" />
		</div>
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'city', 'trav'); ?></label>
			<input type="text" name="city" class="input-text full-width" value="<?php echo esc_attr( $user_info['city'] ) ?>" placeholder="" />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'zip code', 'trav'); ?></label>
			<input type="text" name="zip" class="input-text full-width" value="<?php echo esc_attr( $user_info['zip'] ) ?>" placeholder="" />
		</div>
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'Country', 'trav'); ?></label>
			<div class="selector">
				<select class="full-width" name="country">
					<?php foreach ( $_countries as $_country ) { ?>
						<option value="<?php echo esc_attr( $_country['name'] ) ?>" <?php selected( $user_info['country'], $_country['name'] ); ?>><?php echo esc_html( $_country['name'] ) ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-12 col-md-10">
			<label><?php _e( 'Special requirements', 'trav'); ?></label>
			<textarea name="special_requirements" class="full-width" rows="4"></textarea>
		</div>
	</div>
</div>
<hr />

<?php do_action( 'trav_booking_form_after' ); ?>

<div class="form-group row confirm-booking-btn">
	<div class="col-sm-6 col-md-5">
		<button type="submit" class="full-width btn-large">
			<?php $button_text = __( 'CONFIRM BOOKING', 'trav'); ?>
			<?php echo apply_filters( 'trav_booking_button_text', $button_text ); ?>
		</button>
	</div>
</div>