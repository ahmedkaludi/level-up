<h2><?php echo __( 'Account Settings', 'trav' ); ?></h2>
<h5 class="skin-color"><?php echo __( 'Change Your Password', 'trav' ); ?></h5>
<!-- <div class="alert alert-success">Success Message. Your Message Comes Here<span class="close"></span></div> -->
<form id="update_password_form" method="post">
	<div class="alert alert-error" style="display:none;" data-success="<?php echo __( 'Password successfully changed.', 'trav' ) ?>" data-mismatch="<?php echo __( 'Password mismatch.', 'trav' ) ?>" data-empty="<?php echo __( 'Password cannot be empty.', 'trav' ) ?>"><span class="message"></span><span class="close"></span></div>
	<input type="hidden" name="action" value="update_password">
	<?php wp_nonce_field( 'update_password' ); ?>
	<div class="row form-group">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<label><?php echo __( 'Old Password', 'trav' ); ?></label>
			<input name="old_pass" type="password" class="input-text full-width">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<label><?php echo __( 'Enter New Password', 'trav' ); ?></label>
			<input name="pass1" type="password" class="input-text full-width">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<label><?php echo __( 'Confirm New password', 'trav' ); ?></label>
			<input name="pass2" type="password" class="input-text full-width">
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn-medium"><?php echo __( 'UPDATE PASSWORD', 'trav' ); ?></button>
	</div>
</form>
<hr>
<h5 class="skin-color"><?php echo __( 'Change Your Email', 'trav' ); ?></h5>
<form id="update_email_form" method="post">
	<div class="alert alert-error" style="display:none;" data-success="<?php echo __( 'Email successfully changed.', 'trav' ) ?>" data-mismatch="<?php echo __( 'Email mismatch.', 'trav' ) ?>" data-empty="<?php echo __( 'Email cannot be empty.', 'trav' ) ?>"><span class="message"></span><span class="close"></span></div>
	<input type="hidden" name="action" value="update_email">
	<?php wp_nonce_field( 'update_email' ); ?>
	<div class="row form-group">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<label><?php echo __( 'Enter New Email', 'trav' ); ?></label>
			<input name="email1" type="email" class="input-text full-width">
		</div>
	</div>
	<div class="row form-group">
		<div class="col-xs-12 col-sm-6 col-md-4">
			<label><?php echo __( 'Confirm New Email', 'trav' ); ?></label>
			<input name="email2" type="email" class="input-text full-width">
		</div>
	</div>
	<div class="form-group">
		<button type="submit" class="btn-medium"><?php echo __( 'UPDATE EMAIL ADDRESS', 'trav' ); ?></button>
	</div>
</form>
<script>
tjq = jQuery;
tjq(document).ready(function(){
	tjq('#update_password_form input').change(function(){
		tjq('#update_password_form .alert').hide();
	});
	tjq('#update_password_form').submit(function(){

		var pass1 = tjq('input[name="pass1"]').val();
		var pass2 = tjq('input[name="pass2"]').val();

		// validation
		if ( pass1 == '' || pass2 == '' ) {
			tjq('#update_password_form .alert').removeClass('alert-success');
			tjq('#update_password_form .alert').addClass('alert-error');
			tjq('#update_password_form .alert .message').text( tjq('#update_password_form .alert').data('empty') );
			tjq('#update_password_form .alert').show();
			return false;
		}

		if ( pass1 != pass2 ) {
			tjq('#update_password_form .alert').removeClass('alert-success');
			tjq('#update_password_form .alert').addClass('alert-error');
			tjq('#update_password_form .alert .message').text( tjq('#update_password_form .alert').data('mismatch') );
			tjq('#update_password_form .alert').show();
			return false;
		}

		update_data = tjq("#update_password_form").serialize();
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: update_data,
			success: function(response){
				if ( response.success == 1 ) {
					tjq('#update_password_form .alert').removeClass('alert-error');
					tjq('#update_password_form .alert').addClass('alert-success');
					tjq('#update_password_form .alert .message').text( tjq('#update_password_form .alert').data('success') );
					tjq('#update_password_form .alert').show();
				} else {
					tjq('#update_password_form .alert').removeClass('alert-success');
					tjq('#update_password_form .alert').addClass('alert-error');
					tjq('#update_password_form .alert .message').text( response.result );
					tjq('#update_password_form .alert').show();
				}
			}
		});
		return false;
	});

	tjq('#update_email_form input').change(function(){
		tjq('#update_email_form .alert').hide();
	});
	tjq('#update_email_form').submit(function(){

		var email1 = tjq('input[name="email1"]').val();
		var email2 = tjq('input[name="email2"]').val();

		// validation
		if ( email1 == '' || email2 == '' ) {
			tjq('#update_email_form .alert').removeClass('alert-success');
			tjq('#update_email_form .alert').addClass('alert-error');
			tjq('#update_email_form .alert .message').text( tjq('#update_email_form .alert').data('empty') );
			tjq('#update_email_form .alert').show();
			return false;
		}

		if ( email1 != email2 ) {
			tjq('#update_email_form .alert').removeClass('alert-success');
			tjq('#update_email_form .alert').addClass('alert-error');
			tjq('#update_email_form .alert .message').text( tjq('#update_email_form .alert').data('mismatch') );
			tjq('#update_email_form .alert').show();
			return false;
		}

		update_data = tjq("#update_email_form").serialize();
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: update_data,
			success: function(response){
				if ( response.success == 1 ) {
					tjq('#update_email_form .alert').removeClass('alert-error');
					tjq('#update_email_form .alert').addClass('alert-success');
					tjq('#update_email_form .alert .message').text( tjq('#update_email_form .alert').data('success') );
					tjq('#update_email_form .alert').show();
				} else {
					tjq('#update_email_form .alert').removeClass('alert-success');
					tjq('#update_email_form .alert').addClass('alert-error');
					tjq('#update_email_form .alert .message').text( response.result );
					tjq('#update_email_form .alert').show();
				}
			}
		});
		return false;
	});
});
</script>