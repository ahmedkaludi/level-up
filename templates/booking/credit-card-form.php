<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * credit card form
 */
?>
<div class="card-information">
	<h2><?php echo __( 'Your Card Information', 'trav' ) ?></h2>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label><?php echo __( 'Credit Card Type', 'trav' ) ?></label>
			<div class="selector">
				<select id="cc_type" name="cc_type" class="full-width">
					<option value="">Select a Card</option>
					<option value="American Express">American Express</option>
					<option value="Visa">Visa</option>
					<option value="MasterCard">MasterCard</option>
					<option value="Diners Club">Diners Club</option>
					<option value="JCB">JCB</option>
				</select>
			</div>
		</div>
		<div class="col-sm-6 col-md-5">
			<label><?php echo __( 'Card holder name', 'trav' ) ?></label>
			<input name="cc_holder_name" type="text" class="input-text full-width" value="" placeholder="" />
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label><?php echo __( 'Card number', 'trav' ) ?></label>
			<input name="cc_number" type="text" class="input-text full-width" value="" placeholder="" />
		</div>
		<div class="col-sm-6 col-md-5">
			<label><?php echo __( 'Card identification number', 'trav' ) ?></label>
			<input name="cc_cid" type="text" class="input-text full-width" value="" placeholder="">
		</div>
	</div>
	<div class="form-group row">
		<div class="col-sm-6 col-md-5">
			<label>Expiration Date</label>
			<div class="constant-column-2">
				<div class="selector">
					<select name="cc_exp_month" class="full-width">
						<?php for ( $i = 1; $i <= 12; $i++ ) { ?>
						<option value="<?php echo esc_attr( $i ) ?>"><?php echo esc_html( sprintf( "%02d", $i ) ); ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="selector">
					<select name="cc_exp_year" class="full-width">
						<?php for ( $i = 0; $i<10; $i++ ) { ?>
						<option value="<?php echo esc_attr( date("Y") + $i ) ?>"><?php echo esc_html( date("Y") + $i ) ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
	</div>
</div>
<hr />