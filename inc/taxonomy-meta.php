<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// soap icon field for amenity
if ( ! class_exists( 'Trav_Amenity_Custom_Field') ) :
class Trav_Amenity_Custom_Field {
	function __construct () {
		add_action( 'amenity_add_form_fields', array( $this, 'taxonomy_custom_fields' ), 10, 2 );
		add_action( 'amenity_edit_form_fields', array( $this, 'taxonomy_custom_fields' ), 10, 2 );
		add_action( 'edited_amenity', array( $this, 'save_taxonomy_custom_fields' ), 10, 2 );
		add_action( 'create_amenity', array( $this, 'save_taxonomy_custom_fields' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_styles' ) );
	}

	function taxonomy_custom_fields($tag) {
		$amenity_icon = array();
		if ( ! is_string( $tag ) ) {
			$t_id = $tag->term_id;
			$amenity_icons = get_option( "amenity_icon" );
			$amenity_icon = ( is_array( $amenity_icons ) && isset( $amenity_icons[$t_id] ) )?$amenity_icons[$t_id]:array();
		}
		$icons = array (
			'soap-icon-wifi' => 'WI_FI',
			'soap-icon-swimming' => 'swimming pool',
			'soap-icon-television' => 'television',
			'soap-icon-coffee' => 'coffee',
			'soap-icon-aircon' => 'air conditioning',
			'soap-icon-fitnessfacility' => 'fitness facility',
			'soap-icon-fridge' => 'fridge',
			'soap-icon-winebar' => 'wine bar',
			'soap-icon-smoking' => 'smoking allowed',
			'soap-icon-entertainment' => 'entertainment',
			'soap-icon-securevault' => 'secure vault',
			'soap-icon-pickanddrop' => 'pick and drop',
			'soap-icon-phone' => 'room service',
			'soap-icon-pets' => 'pets allowed',
			'soap-icon-playplace' => 'play place',
			'soap-icon-breakfast' => 'complimentary breakfast',
			'soap-icon-parking' => 'Free parking',
			'soap-icon-conference' => 'conference room',
			'soap-icon-fireplace' => 'fire place',
			'soap-icon-handicapaccessiable' => 'Handicap Accessible',
			'soap-icon-doorman' => 'Doorman',
			'soap-icon-tub' => 'Hot Tub',
			'soap-icon-elevator' => 'Elevator in Building',
			'soap-icon-star' => 'Suitable for Events',
		);
		?>

		<tr class="form-field">
			<th scope="row" valign="top">
				<label><?php echo esc_html__( 'Amenity Icon', 'trav' ); ?></label>
			</th>
			<td>
				<div class="default_icon_area">
					<select id="amenity_icon" name="amenity_icon" >
						<option value="" disabled selected><?php _e('Select Amenity Icon', 'trav');?></option>
						<?php 
							foreach ( $icons as $icon => $content ) {
								$selected = '';
								if ( ! empty( $amenity_icon ) && ! isset( $amenity_icon['uci'] ) && ( $amenity_icon['icon'] == $icon ) ) $selected = 'selected';
								echo '<option class="' . esc_attr( $icon ) . '" value="' . esc_attr( $icon ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $icon ) . '</option>';
							}
						?>
					</select>

					<div id="img_select_area" style="margin-top:20px;"></div>
					<div style="clear:both"><br />
					<!--<span class="description"><?php _e( 'Select an Icon for this room facility', 'trav' ); ?></span><br />
					<a href="#" class="more_icons"><?php _e('More Icons', 'trav'); ?></a>
					<input type="text" name="more_icons">-->
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row" valign="top">
			</th>
			<td>
				<div style="margin-bottom:20px;">
					<?php $selected = isset( $amenity_icon['uci'] )?'checked':''; ?>
					<input type="checkbox" id="use_custom_amenity_icon" name="use_custom_amenity_icon" value="y" <?php echo esc_attr( $selected ); ?>>
					<label for="use_custom_amenity_icon"><?php echo __( 'Use Custom Icon', 'trav' ); ?></label><br />
				</div>
				<div class="upload_amenity_icon_area" style="display:none;">
					<?php
						if ( ! isset( $amenity_icon['url'] ) ) $amenity_icon['url'] = "";
					?>
					<img class="amenity_icon_img" alt="amenity-image" src="<?php echo esc_url( $amenity_icon['url'] ) ?>"/>
					<input type="hidden" name="amenity_icon_image_url" class="amenity_icon_image_url" id="amenity_icon_image" value="<?php echo esc_attr( $amenity_icon['url'] ) ?>" />
					<input type="button" id="amenity_icon_button" class="button amenity_icon_button" value="Browse" />
					<a href="#" class="remove_icon" <?php if ( empty( $amenity_icon['url'] ) ) echo 'style="display:none;"';?>><?php esc_html_e('Remove custom icon', 'trav');?></a>
				</div>
			</td>
		</tr>
		<script>
			tjq = jQuery;
			var meta_image_frame;
			var image_button;
			tjq(document).ready(function(){
				tjq('#amenity_icon option').each(function(index){
					if(index==0) return;
					var _class = tjq(this).attr('class');
					var selected = "";
					if(tjq(this).attr('selected')) selected="selected";
					tjq('#img_select_area').append('<div class="icon_wrapper ' + selected + '"><i class="' + _class + ' circle" attr="' + _class + '"></i> </div> ')
				});
				tjq('body').on('click', '.icon_wrapper', function(){
					tjq('.icon_wrapper').removeClass('selected');
					tjq('#amenity_icon').val(tjq(this).find('i').attr('attr'));
					tjq(this).addClass('selected');
				});
				tjq('#amenity_icon').change(function(){
					tjq('.icon_wrapper').removeClass('selected');
					tjq('.icon_wrapper').find('.' + tjq(this).val()).closest('.icon_wrapper').addClass('selected');
				});
				tjq('#use_custom_amenity_icon').change(function() {
					tjq('.upload_amenity_icon_area').toggle(this.checked);
				});
				if (tjq('#use_custom_amenity_icon').attr('checked')) {
					tjq('.upload_amenity_icon_area').show();
				}
			});
			tjq('#amenity_icon_button').click(function(e){
				image_button = tjq(this);
				// Prevents the default action from occuring.
				e.preventDefault();

				// If the frame already exists, re-open it.
				if ( meta_image_frame ) {
					meta_image_frame.open();
					return;
				}

				// Sets up the media library frame
				meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
					title: 'Choose or Upload an Icon',
					button: { text:  'Use this icon' },
					library: { type: 'image' }
				});
				// Runs when an image is selected.
				meta_image_frame.on('select', function(){
					var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
					image_button.siblings('.amenity_icon_image_url').val(media_attachment.url);
					image_button.siblings('.amenity_icon_img').attr('src',media_attachment.url);
					tjq('.remove_icon').show();
				});
				meta_image_frame.open();
			});
			tjq('.remove_icon').click(function(){
				tjq('.amenity_icon_image_url').val('');
				tjq('.amenity_icon_img').attr('src','');
				tjq(this).hide();
			});
		</script>
		<style>/*[class^="soap-icon"].circle {color: #d9d9d9;}*/[class^="soap-icon"].circle:hover, [class^="soap-icon"].circle.selected {color: #e44049;}.icon_wrapper{float:left;padding:5px;}.icon_wrapper.selected{border: 3px solid #ccc;padding: 2px;}</style>
		<?php
	}

	function save_taxonomy_custom_fields( $term_id ) {
		$t_id = $term_id;
		$amenity_icons = get_option( "amenity_icon" );
		if ( empty( $amenity_icons ) ) $amenity_icons = array();
		$amenity_icons[$t_id] = array();

		if ( isset($_POST['use_custom_amenity_icon'])) {
			$amenity_icons[$t_id]['uci'] = 1;
			if( isset( $_POST['amenity_icon_image_url'])) {
				$amenity_icons[$t_id]['url'] = sanitize_text_field( $_POST['amenity_icon_image_url'] );
			}
		} else {
			if ( isset( $_POST['amenity_icon'] ) ) {
				$amenity_icons[$t_id]['icon'] = sanitize_text_field( $_POST['amenity_icon'] );
			}
		}
		update_option( "amenity_icon", $amenity_icons );
	}

	function load_scripts_styles() {
		global $taxonomy;
		if ( $taxonomy == 'amenity' ) {
			wp_enqueue_style( 'trav_admin_style', TRAV_TEMPLATE_DIRECTORY_URI . '/css/soap-icon.css' );
			wp_enqueue_media();
		}
	}
}
endif;
new Trav_Amenity_Custom_Field;

// soap icon field for preference
if ( ! class_exists( 'Trav_Preference_Custom_Field') ) :
	class Trav_Preference_Custom_Field {
		function __construct () {
			add_action( 'preference_add_form_fields', array( $this, 'taxonomy_custom_fields' ), 10, 2 );
			add_action( 'preference_edit_form_fields', array( $this, 'taxonomy_custom_fields' ), 10, 2 );
			add_action( 'edited_preference', array( $this, 'save_taxonomy_custom_fields' ), 10, 2 );
			add_action( 'create_preference', array( $this, 'save_taxonomy_custom_fields' ), 10, 2 );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts_styles' ) );
		}

		function taxonomy_custom_fields($tag) {
			$preference_icon = array();
			$preference_short_name = "";
			if ( ! is_string( $tag ) ) {
				$t_id = $tag->term_id;
				$preference_icons = get_option( "preference_icon" );
				$preference_icon = ( is_array( $preference_icons ) && isset( $preference_icons[$t_id] ) )?$preference_icons[$t_id]:array();
				$preference_short_names = get_option( "preference_short_name" );
				$preference_short_name = ( isset( $preference_short_names[$t_id] ) )?$preference_short_names[$t_id]:"";
			}
			$icons = array (
				'soap-icon-aircon' 					=> 'air conditioning',
				'soap-icon-user'					=> 'passengers',
				'soap-icon-suitcase'				=> 'bags',
				'soap-icon-fmstereo'				=> 'satellite navigation',
				'soap-icon-fueltank'				=> 'disel vehicle',
				'soap-icon-automatic-transmission'	=> 'automatic transmission',
				'soap-icon-wifi' 					=> 'WI_FI',
				'soap-icon-television' 				=> 'television',
				'soap-icon-coffee' 					=> 'coffee',
				'soap-icon-fitnessfacility' 		=> 'fitness facility',
				'soap-icon-fridge'					 => 'fridge',
				'soap-icon-winebar' 				=> 'wine bar',
				'soap-icon-smoking' 				=> 'smoking allowed',
				'soap-icon-entertainment' 			=> 'entertainment',
				'soap-icon-securevault' 			=> 'secure vault',
				'soap-icon-pickanddrop' 			=> 'pick and drop',
				'soap-icon-phone' 					=> 'room service',
				'soap-icon-pets' 					=> 'pets allowed',
				'soap-icon-playplace' 				=> 'play place',
				'soap-icon-breakfast' 				=> 'complimentary breakfast',
				'soap-icon-parking' 				=> 'Free parking',
				'soap-icon-conference'				=> 'conference room',
				'soap-icon-fireplace' 				=> 'fire place',
				'soap-icon-handicapaccessiable' 	=> 'Handicap Accessible',
				'soap-icon-doorman' 				=> 'Doorman',
				'soap-icon-tub' 					=> 'Hot Tub',
				'soap-icon-elevator' 				=> 'Elevator in Building',
				'soap-icon-star' 					=> 'Suitable for Events',
			);
			?>
			<tr class="form-field">
				<th>
					<label><?php echo esc_html__( 'Short Name', 'trav' ); ?></label>
				</th>
				<td>
					<input type="text" name="preference_short_name" value="<?php echo $preference_short_name; ?>">
					<p><?php echo esc_html__( 'The short name is how it appears on archive page. It should be less than 4 letters. If blanks, the first letter of name will be used instead.', 'trav' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label><?php echo esc_html__( 'Preference Icon', 'trav' ); ?></label>
				</th>
				<td>
					<div class="default_icon_area">
						<select id="preference_icon" name="preference_icon" >
							<option value="" disabled selected><?php _e('Select Preference Icon', 'trav');?></option>
							<?php 
								foreach ( $icons as $icon => $content ) {
									$selected = '';
									if ( ! empty( $preference_icon ) && ! isset( $preference_icon['uci'] ) && ( $preference_icon['icon'] == $icon ) ) $selected = 'selected';
									echo '<option class="' . esc_attr( $icon ) . '" value="' . esc_attr( $icon ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $icon ) . '</option>';
								}
							?>
						</select>

						<div id="img_select_area" style="margin-top:20px;"></div>
						<div style="clear:both"></div><br />
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
				</th>
				<td>
					<div style="margin-bottom:20px;">
						<?php $selected = isset( $preference_icon['uci'] )?'checked':''; ?>
						<input type="checkbox" id="use_custom_preference_icon" name="use_custom_preference_icon" value="y" <?php echo esc_attr( $selected ); ?>>
						<label for="use_custom_preference_icon"><?php echo __( 'Use Custom Icon', 'trav' ); ?></label><br />
					</div>
					<div class="upload_preference_icon_area" style="display:none;">
						<?php
							if ( ! isset( $preference_icon['url'] ) ) $preference_icon['url'] = "";
						?>
						<img class="preference_icon_img" alt="amenity-image" src="<?php echo esc_url( $preference_icon['url'] ) ?>"/>
						<input type="hidden" name="preference_icon_image_url" class="preference_icon_image_url" id="preference_icon_image" value="<?php echo esc_attr( $preference_icon['url'] ) ?>" />
						<input type="button" id="preference_icon_button" class="button preference_icon_button" value="Browse" />
						<a href="#" class="remove_icon" <?php if ( empty( $preference_icon['url'] ) ) echo 'style="display:none;"';?>><?php esc_html_e('Remove custom icon', 'trav');?></a>
					</div>
				</td>
			</tr>
			<script>
				tjq = jQuery;
				var meta_image_frame;
				var image_button;
				tjq(document).ready(function(){
					tjq('#preference_icon option').each(function(index){
						if(index==0) return;
						var _class = tjq(this).attr('class');
						var selected = "";
						if(tjq(this).attr('selected')) selected="selected";
						tjq('#img_select_area').append('<div class="icon_wrapper ' + selected + '"><i class="' + _class + ' circle" attr="' + _class + '"></i> </div> ')
					});
					tjq('body').on('click', '.icon_wrapper', function(){
						tjq('.icon_wrapper').removeClass('selected');
						tjq('#preference_icon').val(tjq(this).find('i').attr('attr'));
						tjq(this).addClass('selected');
					});
					tjq('#preference_icon').change(function(){
						tjq('.icon_wrapper').removeClass('selected');
						tjq('.icon_wrapper').find('.' + tjq(this).val()).closest('.icon_wrapper').addClass('selected');
					});
					tjq('#use_custom_preference_icon').change(function() {
						tjq('.upload_preference_icon_area').toggle(this.checked);
					});
					if (tjq('#use_custom_preference_icon').attr('checked')) {
						tjq('.upload_preference_icon_area').show();
					}
				});
				tjq('#preference_icon_button').click(function(e){
					image_button = tjq(this);
					// Prevents the default action from occuring.
					e.preventDefault();

					// If the frame already exists, re-open it.
					if ( meta_image_frame ) {
						meta_image_frame.open();
						return;
					}

					// Sets up the media library frame
					meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
						title: 'Choose or Upload an Icon',
						button: { text:  'Use this icon' },
						library: { type: 'image' }
					});
					// Runs when an image is selected.
					meta_image_frame.on('select', function(){
						var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
						image_button.siblings('.preference_icon_image_url').val(media_attachment.url);
						image_button.siblings('.preference_icon_img').attr('src',media_attachment.url);
						tjq('.remove_icon').show();
					});
					meta_image_frame.open();
				});
				tjq('.remove_icon').click(function(){
					tjq('.preference_icon_image_url').val('');
					tjq('.preference_icon_img').attr('src','');
					tjq(this).hide();
				});
			</script>
			<style>/*[class^="soap-icon"].circle {color: #d9d9d9;}*/[class^="soap-icon"].circle:hover, [class^="soap-icon"].circle.selected {color: #e44049;}.icon_wrapper{float:left;padding:5px;}.icon_wrapper.selected{border: 3px solid #ccc;padding: 2px;}</style>
			<?php
		}

		function save_taxonomy_custom_fields( $term_id ) {
			$t_id = $term_id;
			
			$preference_icons = get_option( "preference_icon" );
			if ( empty( $preference_icons ) ) $preference_icons = array();
			$preference_icons[$t_id] = array();

			if ( isset( $_POST['use_custom_preference_icon'] ) ) {
				$preference_icons[$t_id]['uci'] = 1;
				if( isset( $_POST['preference_icon_image_url'] ) ) {
					$preference_icons[$t_id]['url'] = sanitize_text_field( $_POST['preference_icon_image_url'] );
				}
			} else {
				if ( isset( $_POST['preference_icon'] ) ) {
					$preference_icons[$t_id]['icon'] = sanitize_text_field( $_POST['preference_icon'] );
				}
			}
			update_option( "preference_icon", $preference_icons );

			$preference_short_name = get_option( "preference_short_name" );
			if ( empty( $preference_short_name ) ) $preference_short_name = array();
			$preference_short_name[$t_id] = "";
			if ( isset( $_POST['preference_short_name'] ) ) {
				$preference_short_name[$t_id] = sanitize_text_field( $_POST['preference_short_name'] );
			}
			update_option( "preference_short_name", $preference_short_name );
		}

		function load_scripts_styles() {
			global $taxonomy;
			if ( $taxonomy == 'preference' ) {
				wp_enqueue_style( 'trav_admin_style', TRAV_TEMPLATE_DIRECTORY_URI . '/css/soap-icon.css' );
				wp_enqueue_media();
			}
		}
	}
endif;
new Trav_Preference_Custom_Field;

// location taxonomy fields
require_once(get_template_directory() . '/inc/lib/tax-meta-class/Tax-meta-class.php');

if (is_admin()){
	$prefix = 'lc_';
	$config = array(
	'id' => 'lc_info',          // meta box id, unique per meta box
	'title' => 'Location Info',          // meta box title
	'pages' => array('location'),        // taxonomy name, accept categories, post_tag and custom taxonomies
	'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
	'fields' => array(),            // list of meta fields (can be added by field arrays)
	'local_images' => false,          // Use local or hosted images (meta box images for add/remove)
	'use_with_theme' => true          //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
	);

	$my_meta =  new Tax_Meta_Class($config);
	$my_meta->addImage($prefix.'image',array('name'=> __('Thumbnail','trav')));
	//$my_meta->addWysiwyg($prefix.'wysiwyg_field_id',array('name'=> __('My wysiwyg Editor ','trav')));

	$my_meta->Finish();
}