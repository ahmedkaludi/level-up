<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * captcha form
 */
?>
<div class="form-group row">
	<div class="col-sm-12 col-md-12">
		<img src="<?php echo esc_url( TRAV_TEMPLATE_DIRECTORY_URI . '/captcha.php?width=400&amp;height=100&amp;characters=5' ) ?>" class="col-sm-6 col-md-5" alt="captcha"/>
		<div class="col-sm-6 col-md-5">
			<label><?php _e( 'Security Code', 'trav'); ?></label>
			<input id="security_code" class="input-text" name="security_code" type="text" />
		</div>
	</div>
</div>
<hr />