<?php
$user_id = get_current_user_id();
$user_info = trav_get_current_user_info();
$photo = trav_get_avatar( array( 'id' => $user_id, 'email' => $user_info['email'], 'size' => 270 ) );
$_countries = trav_get_all_countries();
?>
<div class="view-profile">
	<article class="image-box style2 box innerstyle personal-details">
		<figure>
			<a title="" href="#"><?php echo wp_kses_post( $photo ) ?></a>
		</figure>
		<div class="details">
			<a href="#" class="button btn-mini pull-right edit-profile-btn"><?php echo __( 'EDIT PROFILE', 'trav' ) ?></a>
			<h2 class="box-title fullname"><?php echo esc_html( $user_info['display_name'] ) ?></h2>
			<dl class="term-description">
				<?php 
				if ( ! empty( $user_info['login'] ) ) { 
					?>
					<dt><?php echo __( 'user name', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['login'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['first_name'] ) ) { 
					?>
					<dt><?php echo __( 'first name', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['first_name'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['last_name'] ) ) { 
					?>
					<dt><?php echo __( 'last name', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['last_name'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['phone'] ) ) { 
					?>
					<dt><?php echo __( 'phone number', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['phone'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['birthday'] ) ) { 
					?>
					<dt><?php echo __( 'Date of birth', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['birthday'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['address'] ) ) { 
					?>
					<dt><?php echo __( 'Street Address and number', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['address'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['city'] ) ) { 
					?>
					<dt><?php echo __( 'Town / City', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['city'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['zip'] ) ) { 
					?>
					<dt><?php echo __( 'ZIP code', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['zip'] ) ?></dd>
					<?php 
				} 
				?>

				<?php 
				if ( ! empty( $user_info['country'] ) ) { 
					?>
					<dt><?php echo __( 'Country', 'trav' ) ?>:</dt><dd><?php echo esc_html( $user_info['country'] ) ?></dd>
					<?php 
				} 
				?>
			</dl>
		</div>
	</article>
	<hr>
	<h2><?php echo __( 'About You', 'trav' ) ?></h2>
		<div class="intro">
		<p><?php echo esc_html( $user_info['description'] ); ?></p>
	</div>
</div>
<div class="edit-profile">
	<a href="#" class="button btn-mini pull-right view-profile-btn"><?php echo __( 'VIEW PROFILE', 'trav' ) ?></a>
	<form class="edit-profile-form" method="post" enctype='multipart/form-data'>
		<h2><?php echo __( 'Personal Details', 'trav' ) ?></h2>
		<input type="hidden" name="action" value="update_profile">
		<?php wp_nonce_field( 'update_profile' ); ?>
		<div class="col-sm-9 no-padding no-float">
			<div class="row form-group">
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'First Name', 'trav' ) ?></label>
					<input name="first_name" type="text" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $user_info['first_name'] ) ?>">
				</div>
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'Last Name', 'trav' ) ?></label>
					<input name="last_name" type="text" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $user_info['last_name'] ) ?>">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'Email Address', 'trav' ) ?></label>
					<input name="email" type="email" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $user_info['email'] ) ?>">
				</div>
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'Date of Birth', 'trav' ) ?></label>
					<div class="datepicker-wrap to-today">
						<input name="birthday" type="text" placeholder="<?php echo trav_get_date_format('html') ?>" class="input-text full-width" value="<?php echo esc_attr( $user_info['birthday'] ) ?>">
					</div>
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'Country Code', 'trav' ) ?></label>
					<div class="selector">
						<select name="country_code" class="full-width">
							<?php foreach ( $_countries as $_country ) {
								$selected = '';
								if ( $_country['name'] . ' (' . $_country['d_code'] . ')' == $user_info['country_code'] ) $selected = " selected";
								?>
								<option<?php echo esc_attr( $selected ) ?> value="<?php echo esc_attr( $_country['name'] . ' (' . $_country['d_code'] . ')' ) ?>"><?php echo esc_html( $_country['name'] . ' (' . $_country['d_code'] . ')' ) ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'Phone Number', 'trav' ) ?></label>
					<input name="phone" type="text" class="input-text full-width" placeholder="" value="<?php echo esc_attr( $user_info['phone'] ) ?>">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'Address', 'trav' ) ?></label>
					<input name="address" type="text" class="input-text full-width" value="<?php echo esc_attr( $user_info['address'] ) ?>">
				</div>
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'City', 'trav' ) ?></label>
					<input name="city" type="text" class="input-text full-width" value="<?php echo esc_attr( $user_info['city'] ) ?>">
				</div>
			</div>
			<div class="row form-group">
				<div class="col-sms-6 col-sm-6">
					<label><?php echo __( 'Country', 'trav' ) ?></label>
					<div class="selector">
						<select name="country" class="full-width">
							<?php foreach ( $_countries as $_country ) {
								$selected = '';
								if ( $_country['name'] == $user_info['country'] ) $selected = "selected"; ?>
									<option <?php echo wp_kses_post( $selected ) ?> value="<?php echo esc_attr( $_country['name'] ) ?>"><?php echo esc_html( $_country['name'] ) ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<hr>
			<h2><?php echo __( 'Upload Profile Photo', 'trav' ) ?></h2>
			<div class="row form-group">
				<div class="col-sms-12 col-sm-6">
					<div class="fileinput full-width small-box">
						<input name="photo" type="file" class="input-text" data-placeholder="select image/s" accept="image/*">
					</div>
				</div>
				<div class="col-sms-12 col-sm-6">
					<div id="photo_preview" class="image-close-box"<?php if ( empty( $user_info['photo_url'] ) ) { echo ' style="display:none"'; } ?>>
						<input type="hidden" name="remove_photo">
						<div class="close-banner"></div>
						<span class="close"></span>
						<img src="<?php echo esc_url( $user_info['photo_url'] ) ?>" alt="your photo">
					</div>
				</div>
			</div>
			<hr>
			<h2><?php echo __( 'Describe Yourself', 'trav' ) ?></h2>
			<div class="form-group">
				<textarea name="description" rows="5" class="input-text full-width" placeholder="please tell us about you"><?php echo esc_textarea( $user_info['description'] ); ?></textarea>
			</div>
			<div class="from-group">
				<button type="submit" class="btn-medium col-sms-6 col-sm-4"><?php echo __( 'UPDATE SETTINGS', 'trav' ) ?></button>
			</div>
		</div>
	</form>
</div>
<script>
tjq = jQuery;
tjq(document).ready(function(){

	tjq("#profile .edit-profile-btn").click(function(e) {
		e.preventDefault();
		tjq(".view-profile").fadeOut();
		tjq(".edit-profile").fadeIn();
	});
	tjq("#profile .view-profile-btn").click(function(e) {
		e.preventDefault();
		tjq(".edit-profile").fadeOut();
		tjq(".view-profile").fadeIn();
	});

	tjq('a[href="#profile"]').on('shown.bs.tab', function (e) {
		tjq(".view-profile").show();
		tjq(".edit-profile").hide();
	});

	function readURL(input) {

		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				tjq('#photo_preview img').attr('src', e.target.result);
				tjq('#photo_preview').show();
			}
			reader.readAsDataURL(input.files[0]);
		} else {
			tjq('#photo_preview').hide();
		}
	}

	tjq('.edit-profile input[name="photo"]').change(function(){
		readURL(this);
	});

	var photo_upload = tjq('input[name="photo"]');
	tjq('#photo_preview .close').click(function(){
		photo_upload.replaceWith( photo_upload = photo_upload.clone( true ) );
		tjq('#photo_preview').hide();
		tjq('input[name="remove_photo"').val('1');
	});
});
</script>