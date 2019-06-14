<?php
get_header();

if ( have_posts() ) {
	while ( have_posts() ) : the_post();
		$car_id = get_the_ID();
		$car_meta = get_post_meta( $car_id );
		$car_type = wp_get_post_terms( $car_id, 'car_type' );
		$car_agent = wp_get_post_terms( $car_id, 'car_agent' );
		$car_preferences = wp_get_post_terms( $car_id, 'preference' );
		$gallery_imgs = array_key_exists( 'trav_gallery_imgs', $car_meta ) ? $car_meta['trav_gallery_imgs'] : array();
		$calendar_desc = empty( $car_meta['trav_car_calendar_txt'] ) ? '' : $car_meta['trav_car_calendar_txt'][0];
		$show_gallery = 0;
		$show_calendar = 0;
		if ( array_key_exists( 'trav_car_main_top', $car_meta ) ) {
			$main_top_meta = $car_meta['trav_car_main_top'];
			$show_gallery = in_array( 'gallery', $main_top_meta ) ? 1 : 0;
			$show_calendar = in_array( 'calendar', $main_top_meta ) ? 1 : 0;
		}

		// init booking search variables
		$date_from = ( isset( $_GET['date_from'] ) ) ? trav_tophptime( $_GET['date_from'] ) : '';
		$date_to = ( isset( $_GET['date_to'] ) ) ? trav_tophptime( $_GET['date_to'] ) : '';
		$time_from = ( isset( $_GET['time_from'] ) ) ? sanitize_text_field( $_GET['time_from'] ) : '';
		$time_to = ( isset( $_GET['time_to'] ) ) ? sanitize_text_field( $_GET['time_to'] ) : '';
		$location_from = ( isset( $_GET['location_from'] ) ) ? sanitize_text_field( $_GET['location_from'] ) : '';
		$location_to = ( isset( $_GET['location_to'] ) ) ? sanitize_text_field( $_GET['location_to'] ) : '';
		
		// add to user recent activity
		trav_update_user_recent_activity( $car_id ); 
		?>
        <section id="content" class="gray-area">
            <div class="container car-detail-page">
                <div class="row">
                    <div id="main" class="col-md-9">
                        <div class="tab-container style1" id="car-main-content">
                            <ul class="tabs">
                                <?php if ( ! empty( $gallery_imgs ) && $show_gallery ) { ?>
									<li><a data-toggle="tab" href="#photos-tab"><?php echo __( 'photos', 'trav' ) ?></a></li>
								<?php } ?>
								<?php if ( $show_calendar ) { ?>
									<li><a data-toggle="tab" href="#calendar-tab"><?php echo __( 'calendar', 'trav' ) ?></a></li>
								<?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php if ( ! empty( $gallery_imgs ) && $show_gallery ) { ?>
									<div id="photos-tab" class="tab-pane fade">
										<div class="photo-gallery style1" data-animation="slide" data-sync="#photos-tab .image-carousel">
											<ul class="slides">
												<?php foreach ( $gallery_imgs as $gallery_img ) {
													echo '<li>' . wp_get_attachment_image( $gallery_img, 'full' ) . '</li>';
												} ?>
											</ul>
										</div>
										<div class="image-carousel style1" data-animation="slide" data-item-width="70" data-item-margin="10" data-sync="#photos-tab .photo-gallery">
											<ul class="slides">
												<?php foreach ( $gallery_imgs as $gallery_img ) {
													echo '<li>' . wp_get_attachment_image( $gallery_img, 'widget-thumb' ) . '</li>';
												} ?>
											</ul>
										</div>
									</div>
								<?php } ?>

								<?php  if ( $show_calendar ) { ?>
									<div id="calendar-tab" class="tab-pane fade">
										<div class="row">

											<div class="col-sm-6 col-md-4 no-lpadding">
												<label><?php _e( 'SELECT MONTH', 'trav' );?></label>
												<div class="selector">
													<select class="full-width" id="select-month">
														<?php for ( $i = 0; $i<12; $i++ ) {
															$year_month = mktime( 0, 0, 0, date("m") + $i, 1, date("Y") );
															echo '<option value="' . date( 'Y-n', $year_month ) . '"> ' . __( date('F', $year_month ), 'trav' ) . date(' Y', $year_month ) . '</option>';
														} ?>
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<?php if ( ! empty( $calendar_desc ) ) { ?>
												<div class="col-sm-8">
													<div class="calendar"></div>
													<div class="calendar-legend">
														<label class="available"><?php echo __( 'available', 'trav' ) ?></label>
														<label class="unavailable"><?php echo __( 'unavailable', 'trav' ) ?></label>
														<label class="past"><?php echo __( 'past', 'trav' ) ?></label>
													</div>
												</div>
												<div class="col-sm-4">
													<p class="description">
														<?php
															echo esc_html( $calendar_desc )
														?>
													</p>
												</div>
											<?php } else { ?>
												<div class="calendar"></div>
												<div class="calendar-legend">
													<label class="available"><?php echo __( 'available', 'trav' ) ?></label>
													<label class="unavailable"><?php echo __( 'unavailable', 'trav' ) ?></label>
													<label class="past"><?php echo __( 'past', 'trav' ) ?></label>
												</div>
											<?php } ?>

										</div>
									</div>
								<?php } ?>
                            </div>
                        </div>

                        <div id="car-features" class="tab-container">
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="car-details">
                                    <div class="intro table-wrapper full-width hidden-table-sms">
                                        <div class="col-sm-4 table-cell features">
                                            <!--<dl class="term-description">-->
                                            <table>
												<?php
												$tr = '<tr><td><label>%s:</label></td><td>%s</td></tr>';
												if ( ! empty ( $car_agent ) ) {
													echo sprintf( $tr, __( 'Rental Company', 'trav' ), esc_attr( $car_agent[0]->name ) );
												}
												if ( ! empty ( $car_type ) ) {
													echo sprintf( $tr, __( 'Car Type', 'trav' ), esc_attr( $car_type[0]->name ) );
												}
												echo sprintf( $tr, __( 'Car name', 'trav' ), esc_html( get_the_title() ) );
												if ( ! empty ( $car_meta["trav_car_passenger"] ) ) {
													echo sprintf( $tr, __( 'Passenger', 'trav' ), esc_html( $car_meta["trav_car_passenger"][0] ) );
												}
												if ( ! empty ( $car_meta["trav_car_baggage"] ) ) {
													echo sprintf( $tr, __( 'Baggage', 'trav' ), esc_html( $car_meta["trav_car_baggage"][0] ) );
												}
												if ( ! empty ( $car_meta["trav_car_max_cars"] ) &&  $car_meta["trav_car_max_cars"][0] > 0 ) {
													echo sprintf( $tr, __( 'Car features', 'trav' ), __('available') );
												} else {
													echo sprintf( $tr, __( 'Car features', 'trav' ), __('unavailable') );
												}
												if ( ! empty ( $car_meta["trav_car_tax_rate"] ) ) {
													echo sprintf( $tr, __( 'Taxes &amp; Fees', 'trav' ), esc_html( $car_meta["trav_car_tax_rate"][0] . "%" ) );
												}
												if ( ! empty ( $car_meta["trav_car_security_deposit"] ) ) {
													echo sprintf( $tr, __( 'Security Deposit', 'trav' ), esc_html( $car_meta["trav_car_security_deposit"][0] . '%' ) );
												}	
                                                ?>
                                            <!--</dl>-->
                                            </table>
                                        </div>
                                        <div class="col-sm-8 table-cell">
                                            <div class="detailed-features clearfix">
                                                <div class="col-md-6">
                                                    <h4 class="box-title">
                                                        <?php _e( 'Pick-up location details', 'trav' ); ?>
                                                        <?php  if ( ! empty( $car_meta["trav_car_pick_up_phone"] ) ) { ?>
                                                        	<small><?php _e( 'Phone:', 'trav'); ?> <?php echo $car_meta["trav_car_pick_up_phone"][0]; ?></small>
                                                        <?php } ?>
                                                    </h4>
                                                    <div class="icon-box style11">
                                                        <div class="icon-wrapper">
                                                            <i class="soap-icon-clock"></i>
                                                        </div>
                                                        <dl class="details">
                                                            <dt class="skin-color"><?php _e( 'pickup time', 'trav'); ?></dt>
                                                            <dd>
                                                            	<?php 
                                                            		$time = array( 'anytime' => __( 'Anytime', 'trav' ), 'morning' => __( 'Morning', 'trav' ), 'afternoon' => __( 'Afternoon', 'trav' ) );
                                                            	?>
                                                        		<?php echo ( ! empty( $car_meta['trav_car_pick_up_time'] ) ) ? $time[$car_meta['trav_car_pick_up_time'][0]] : ""; ?>
                                                        	</dd>
                                                        </dl>
                                                    </div>
                                                    <div class="icon-box style11">
                                                        <div class="icon-wrapper">
                                                            <i class="soap-icon-departure"></i>
                                                        </div>
                                                        <dl class="details">
                                                            <dt class="skin-color"><?php _e( 'Location', 'trav'); ?></dt>
                                                            <dd>
                                                            	<?php echo ( ! empty( $car_meta['trav_car_location'] ) ) ? $car_meta['trav_car_location'][0] : ""; ?>
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4 class="box-title">
                                                        <?php _e( 'Drop-off location details', 'trav' ); ?>
                                                        <?php  if ( ! empty( $car_meta["trav_car_drop_off_phone"] ) ) { ?>
                                                        	<small><?php _e( 'Phone:', 'trav'); ?> <?php echo $car_meta["trav_car_drop_off_phone"][0]; ?></small>		
                                                        <?php } ?>
                                                    </h4>
                                                    <div class="icon-box style11">
                                                        <div class="icon-wrapper">
                                                            <i class="soap-icon-clock"></i>
                                                        </div>
                                                        <dl class="details">
                                                            <dt class="skin-color"><?php _e( 'Drop off Time', 'trav' ); ?></dt>
                                                            <dd><?php echo ( ! empty( $car_meta['trav_car_drop_off_time'] ) ) ? $time[$car_meta['trav_car_drop_off_time'][0]] : ""; ?></dd>                                                            
                                                        </dl>
                                                    </div>
                                                    <div class="icon-box style11">
                                                        <div class="icon-wrapper">
                                                            <i class="soap-icon-departure"></i>
                                                        </div>
                                                        <dl class="details">
                                                            <dt class="skin-color"><?php _e( 'Location', 'trav' ); ?></dt>
                                                            <dd><?php echo ( ! empty( $car_meta['trav_car_location'] ) ) ? $car_meta['trav_car_location'][0] : ""; ?></dd>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="booking-form" method="post" action="">
                                    	<div class="update-search clearfix">                                    	
                                    		<div class="alert alert-error" style="display:none;"><span class="message"><?php _e( 'Please select check in date.','trav' ); ?></span><span class="close"></span></div>
											<input type="hidden" name="car_id" value="<?php echo esc_attr( $car_id ); ?>">
											<input type="hidden" name="action" value="car_check_availability">
											<?php wp_nonce_field( 'post-' . $car_id, '_wpnonce', false ); ?>
											<?php if ( isset( $_GET['edit_booking_no'] ) && ! empty( $_GET['edit_booking_no'] ) ) : ?>
												<input type="hidden" name="edit_booking_no" value="<?php echo esc_attr( $_GET['edit_booking_no'] ) ?>">
												<input type="hidden" name="pin_code" value="<?php echo esc_attr( $_GET['pin_code'] ) ?>">
											<?php endif; ?>											
											<div class="col-xs-6 col-sm-3">
												<label><?php _e( 'PICK UP DATE','trav' ); ?></label>
												<div class="datepicker-wrap validation-field from-today">
													<input name="date_from" type="text" placeholder="<?php echo trav_get_date_format('html'); ?>" class="input-text full-width" value="<?php echo $date_from; ?>" />
												</div>
											</div>
											<div class="col-xs-6 col-sm-2">
												<label><?php _e( 'PICK UP TIME','trav' ); ?></label>
												<div class="timepicker-wrap validation-field">
													<input name="time_from" type="text" placeholder="" class="input-text full-width" value="<?php echo $time_from;?>" />
												</div>
											</div>
											<div class="col-xs-6 col-sm-4">
												<label><?php _e( 'PICK-UP LOCATION','trav' ); ?></label>
												<div class="validation-field">
													<input name="location_from" type="text" placeholder="" class="input-text full-width" value="<?php echo $location_from;?>" />
												</div>
											</div>
											<div class="col-xs-6 col-sm-3">
												<label class="visible-md visible-lg">&nbsp;</label>
												<div class="row">
													<div class="col-xs-12">
														<button class="full-width icon-check animated bounce" type="submit"><?php _e( "BOOK NOW", "trav" ); ?></button>
													</div>
												</div>
											</div>
											<div class="col-xs-6 col-sm-3">
												<label><?php _e( 'DROP-OFF DATE','trav' ); ?></label>
												<div class="datepicker-wrap validation-field from-today">
													<input name="date_to" type="text" placeholder="<?php echo trav_get_date_format('html'); ?>" class="input-text full-width" value="<?php echo $date_to; ?>" />
												</div>
											</div>
											<div class="col-xs-6 col-sm-2">
												<label><?php _e( 'DROP-OFF TIME','trav' ); ?></label>
												<div class="timepicker-wrap validation-field">
													<input name="time_to" type="text" placeholder="" class="input-text full-width" value="<?php echo $time_to;?>" />
												</div>
											</div>
											<div class="col-xs-6 col-sm-4">
												<label><?php _e( 'DROP-OFF LOCATION','trav' ); ?></label>
												<div class="validation-field">
													<input name="location_to" type="text" placeholder="" class="input-text full-width" value="<?php echo $location_to;?>" />
												</div>
											</div>
											<div class="col-xs-6 col-sm-3">
												
											</div>
										</div>
									</form>
                                    <div class="long-description">
										<div class="box entry-content">
											<?php the_content(); ?>
											<?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
										</div>										
									</div>
									<div class="car-features box">
                                        <div id="car-amenities" class="row add-clearfix">
                                        	<?php
												$preference_icons = get_option( "preference_icon" );
												$preference_html = '';
												foreach ( $car_preferences as $preference ) {
													if ( is_array( $preference_icons ) && isset( $preference_icons[ $preference->term_id ] ) ) {
														$preference_html .= '<div class="col-sms-6 col-sm-6 col-md-4">';
														if ( isset( $preference_icons[ $preference->term_id ]['uci'] ) ) {
															$preference_html .= '<span class="icon-box style2"><div class="custom_amenity"><img title="' . esc_attr( $preference->name ) . '" src="' . esc_url( $preference_icons[ $preference->term_id ]['url'] ) . '" height="29" alt="amenity-image"></div>' . esc_html( $preference->name ) . '</span>';
														} else if ( isset( $preference_icons[ $preference->term_id ]['icon'] ) ) {
															$_class = $preference_icons[ $preference->term_id ]['icon'];
															$preference_html .= '<span class="icon-box style2"><i class="circle ' . esc_attr( $_class ) . '" title="' . esc_attr( $preference->name ) . '"></i>' . esc_html( $preference->name ) . '</span>';
														}
														$preference_html .= '</div>';
													}
													
												}
												echo wp_kses_post( $preference_html );
											?>
                                        </div>
                                    </div>                                    
                                </div>                               
                            </div>
                        </div>
                    </div>
                    <div class="sidebar col-md-3">
                    	<article class="detailed-logo">
                    		<?php if ( isset( $car_meta['trav_car_logo'] ) ) { ?>
								<figure>
									<img width="114" src="<?php echo esc_url( wp_get_attachment_url( $car_meta['trav_car_logo'][0] ) );?>" alt="Car Logo">
								</figure>
							<?php } ?>
							
                            <div class="details">
                                <h2 class="box-title"><?php the_title(); ?>
                                <?php
                                	if ( ! empty ( $car_type ) ) {
										echo '<small>' . esc_attr( $car_type[0]->name ) . '</small>';
									}
								?>	
                                </h2>
                                <?php if ( isset( $car_meta['trav_car_price'] ) && is_numeric( $car_meta['trav_car_price'][0] ) ) { ?>
	                                <span class="price clearfix">
	                                    <small class="pull-left"><?php _e('per day', 'trav'); ?></small>
	                                    <span class="pull-right"><?php echo esc_html( trav_get_price_field( $car_meta['trav_car_price'][0] ) ); ?></span>
	                                </span>
                                <?php } ?>
                                <?php if ( isset( $car_meta['trav_car_mileage'] ) && is_numeric( $car_meta['trav_car_mileage'][0] ) ) { ?>
	                                <div class="mile clearfix">
	                                    <span class="skin-color"><?php _e('Mileage:', 'trav'); ?></span>
	                                    <span class="mileage pull-right"><?php echo esc_html($car_meta['trav_car_mileage'][0] ); ?> <?php _e('Miles', 'trav'); ?></span>
	                                </div>
	                            <?php } ?>
                                <p class="description">
                                	<?php
										if ( isset( $car_meta['trav_car_brief'] ) ) {
											echo esc_html( $car_meta['trav_car_brief'][0] );
										} else {
											$brief_content = apply_filters('the_content', get_post_field('post_content', $acc_id));
											echo wp_kses_post( wp_trim_words( $brief_content, 20, '' ) );
										}
									?>
                                </p>
                                <?php if ( is_user_logged_in() ) {
									$user_id = get_current_user_id();
									$wishlist = get_user_meta( $user_id, 'wishlist', true );
									if ( empty( $wishlist ) ) $wishlist = array();
									if ( ! in_array( trav_car_org_id( $car_id ), $wishlist) ) { ?>
										<a class="button yellow-bg full-width uppercase btn-small btn-add-wishlist" data-label-add="<?php _e( 'add to wishlist', 'trav' ); ?>" data-label-remove="<?php _e( 'remove from wishlist', 'trav' ); ?>"><?php _e( 'add to wishlist', 'trav' ); ?></a>
									<?php } else { ?>
										<a class="button yellow-bg full-width uppercase btn-small btn-remove-wishlist" data-label-add="<?php _e( 'add to wishlist', 'trav' ); ?>" data-label-remove="<?php _e( 'remove from wishlist', 'trav' ); ?>"><?php _e( 'remove from wishlist', 'trav' ); ?></a>
									<?php } ?>
								<?php } else { ?>
										<h5><?php _e( 'To save your wishlist please login.', 'trav' ); ?></h5>
										<a href="#travelo-login" class="button yellow-bg full-width uppercase btn-small soap-popupbox"><?php _e( 'login', 'trav' ); ?></a>
								<?php } ?>
                                
                            </div>
                        </article>
                        <?php generated_dynamic_sidebar(); ?>                        
                    </div>
                </div>
            </div>
        </section>
        
<?php endwhile;
}
get_footer();