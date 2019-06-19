<?php
/*
Template Name: Home Page Template
*/
global $trav_options, $search_max_rooms, $search_max_adults, $search_max_kids, $search_max_passengers, $def_currency;
$all_features = array( 'acc', 'tour', 'car', 'cruise' );
$enabled_features = array();
foreach( $all_features as $feature ) {
	if ( empty( $trav_options['disable_' . $feature ] ) ) $enabled_features[] = $feature;
}
get_header();
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$slider_active = get_post_meta( get_the_ID(), 'trav_page_slider', true );
		$slider        = ( $slider_active == '' ) ? 'Deactivated' : $slider_active;
		if ( class_exists( 'RevSlider' ) && $slider != 'Deactivated' ) {
			echo '<div id="slideshow">';
			putRevSlider( $slider );
			echo '</div>';
		} ?>
		<section id="content"<?php if ( count( $enabled_features ) > 1 ) echo ' class="no-padding"' ?>>
			<div class="search-box-wrapper">
				<div class="search-box container">
					<?php if ( count( $enabled_features ) > 1 ) : ?>
						<ul class="search-tabs clearfix">
							<?php if ( in_array('acc', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'acc' ) echo 'class="active"' ?> ><a href="#hotels-tab" data-toggle="tab"><?php _e( 'HOTELS', 'trav' ) ?></a></li>
							<?php endif; ?>
							<?php if ( in_array('tour', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'tour' ) echo 'class="active"' ?> ><a href="#tours-tab" data-toggle="tab"><?php _e( 'TOURS', 'trav' ) ?></a></li>
							<?php endif; ?>
							<?php if ( in_array('car', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'car' ) echo 'class="active"' ?> ><a href="#cars-tab" data-toggle="tab"><?php _e( 'CARS', 'trav' ) ?></a></li>
							<?php endif; ?>
							<?php if ( in_array('cruise', $enabled_features) ) : ?>
								<li <?php if ( $enabled_features[0] == 'cruise' ) echo 'class="active"' ?> ><a href="#cruises-tab" data-toggle="tab"><?php _e( 'CRUISES', 'trav' ) ?></a></li>
							<?php endif; ?>
						</ul>
						<div class="visible-mobile">
							<ul id="mobile-search-tabs" class="search-tabs clearfix">
								<?php if ( in_array('acc', $enabled_features) ) : ?>
									<li <?php if ( $enabled_features[0] == 'acc' ) echo 'class="active"' ?> ><a href="#hotels-tab" data-toggle="tab"><?php _e( 'HOTELS', 'trav' ) ?></a></li>
								<?php endif; ?>
								<?php if ( in_array('tour', $enabled_features) ) : ?>
									<li <?php if ( $enabled_features[0] == 'tour' ) echo 'class="active"' ?> ><a href="#tours-tab" data-toggle="tab"><?php _e( 'TOURS', 'trav' ) ?></a></li>
								<?php endif; ?>
								<?php if ( in_array('car', $enabled_features) ) : ?>
									<li <?php if ( $enabled_features[0] == 'car' ) echo 'class="active"' ?> ><a href="#cars-tab" data-toggle="tab"><?php _e( 'CARS', 'trav' ) ?></a></li>
								<?php endif; ?>
								<?php if ( in_array('cruise', $enabled_features) ) : ?>
									<li <?php if ( $enabled_features[0] == 'cruise' ) echo 'class="active"' ?> ><a href="#cruises-tab" data-toggle="tab"><?php _e( 'CRUISES', 'trav' ) ?></a></li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>
					<div class="search-tab-content">
						<?php if ( in_array('acc', $enabled_features) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'acc' ) echo ' active in' ?>" id="hotels-tab">
							<?php endif; ?>
							<form role="search" method="get" class="acc-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="accommodation">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Your Destination','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or hotel name', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Your check-out date is before your check-in date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for check-in and check-out.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'CHECK IN','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'CHECK OUT','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-4">
												<label><?php _e( 'Rooms','trav' ); ?></label>
												<div class="selector">
													<select name="rooms" class="full-width">
														<?php
															$rooms = ( isset( $_GET['rooms'] ) && is_numeric( (int) $_GET['rooms'] ) )?(int) $_GET['rooms']:1;
															for ( $i = 1; $i <= $search_max_rooms; $i++ ) {
																$selected = '';
																if ( $i == $rooms ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Adults','trav' ); ?></label>
												<div class="selector">
													<select name="adults" class="full-width">
														<?php
															$adults = ( isset( $_GET['adults'] ) && is_numeric( (int) $_GET['adults'] ) )?(int) $_GET['adults']:1;
															for ( $i = 1; $i <= $search_max_adults; $i++ ) {
																$selected = '';
																if ( $i == $adults ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-4">
												<label><?php _e( 'Kids','trav' ); ?></label>
												<div class="selector">
													<select name="kids" class="full-width">
														<?php
															$kids = ( isset( $_GET['kids'] ) && is_numeric( (int) $_GET['kids'] ) )?(int) $_GET['kids']:0;
															for ( $i = 0; $i <= $search_max_kids; $i++ ) {
																$selected = '';
																if ( $i == $kids ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="age-of-children no-display">
											<h5><?php _e( 'Age of Children','trav' ); ?></h5>
											<div class="row">
												<div class="col-xs-4 child-age-field">
													<label><?php echo __( 'Child ', 'trav' ) . '1' ?></label>
													<div class="selector validation-field">
														<select name="child_ages[]" class="full-width">
															<?php
																$max_kid_age = 17;
																for ( $i = 0; $i <= $max_kid_age; $i++ ) {
																	echo '<option value="' . esc_attr( $i ) . '">' . esc_html( $i ) . '</option>';
																}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array('tour', $enabled_features) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'tour' ) echo ' active in' ?>" id="tours-tab">
							<?php endif; ?>
							<form role="search" method="get" class="tour-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="tour">
								<div class="row">
									<div class="form-group col-sm-4 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Destination ', 'trav' ) ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or tour name', 'trav') ?>" />
									</div>
									<div class="form-group col-sm-8 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-6">
												<label><?php _e( 'From', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'To', 'trav' ) ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-3 fixheight">
										<?php $trip_types = get_terms( 'tour_type' ); ?>
										<div class="row">
											<?php if ( ! empty( $trip_types ) ) : ?>
												<div class="col-xs-6">
													<label><?php _e( 'Trip Type', 'trav' ) ?></label>
													<div class="selector">
														<select name="tour_types" class="full-width">
															<option value=""><?php _e( 'Trip Type', 'trav' ) ?></option>
															<?php foreach ( $trip_types as $trip_type ) : ?>
																<option value="<?php echo $trip_type->term_id ?>"><?php _e( $trip_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-xs-6">
												<label><?php _e( 'Budget', 'trav' ) ?></label>
												<input type="text" name="max_price" class="input-text full-width" placeholder="<?php echo sprintf( __( 'Max Budget (%s)', 'trav'), $def_currency ) ?>" />
											</div>
										</div>
									</div>
									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array('car', $enabled_features) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'car' ) echo ' active in' ?>" id="cars-tab">
							<?php endif; ?>
							<form role="search" method="get" class="car-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="car">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'pick-up from', 'trav') ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'city, distirct or specific airpot', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Your drop-off date is before your pick-up date. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for pick-up and drop-off.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'From','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'To','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-5">
												<label><?php _e( 'Passengers','trav' ); ?></label>
												<div class="selector">
													<select name="passengers" class="full-width">
														<?php
															$passengers = ( isset( $_GET['passengers'] ) && is_numeric( (int) $_GET['passengers'] ) )?(int) $_GET['passengers']:1;
															for ( $i = 1; $i <= $search_max_passengers; $i++ ) {
																$selected = '';
																if ( $i == $passengers ) $selected = 'selected';
																echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
															}
														?>
													</select>
												</div>
											</div>	
											<div class="col-xs-7">
	                                            <label><?php _e('Car Type', 'trav'); ?></label>
	                                            <div class="selector">
	                                                <select class="full-width" name="car_types">
	                                                	<option value=""><?php _e( 'select a car type','trav' ); ?></option>
	                                                	<?php
	                                                	$all_car_types = get_terms( 'car_type', array('hide_empty' => 0) );
														foreach ( $all_car_types as $each_car_type ) {
															echo '<option value="' . esc_attr( $each_car_type->term_id ) . '">' . esc_html( $each_car_type->name ) . '</option>';
														}
														?>
	                                                </select>
	                                            </div>
	                                        </div>
	                                    </div>
									</div>

									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width icon-check animated" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( in_array( 'cruise', $enabled_features  ) ) : ?>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								<div class="tab-pane fade<?php if ( $enabled_features[0] == 'cruise' ) echo ' active in' ?>" id="cruises-tab">
							<?php endif; ?>
							<form role="search" method="get" class="cruise-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
								<input type="hidden" name="post_type" value="cruise">
								<div class="row">
									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'Where','trav' ); ?></h4>
										<label><?php _e( 'Your Destination','trav' ); ?></label>
										<input type="text" name="s" class="input-text full-width" placeholder="<?php _e( 'Enter a destination or cruise name', 'trav') ?>" />
									</div>

									<div class="form-group col-sm-6 col-md-4">
										<h4 class="title"><?php _e( 'When','trav' ); ?></h4>
										<div class="row search-when" data-error-message1="<?php echo __( 'Date to is before date from. Have another look at your date and try again.' , 'trav') ?>" data-error-message2="<?php echo __( 'Please select current or future dates for date from and date to.' , 'trav') ?>">
											<div class="col-xs-6">
												<label><?php _e( 'DATE FROM','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_from" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
											<div class="col-xs-6">
												<label><?php _e( 'DATE TO','trav' ); ?></label>
												<div class="datepicker-wrap from-today">
													<input name="date_to" type="text" class="input-text full-width" placeholder="<?php echo trav_get_date_format('html'); ?>" />
												</div>
											</div>
										</div>
									</div>

									<div class="form-group col-sm-6 col-md-3">
										<h4 class="title"><?php _e( 'What','trav' ); ?></h4>
										<div class="row">
											<div class="col-xs-6">
												<?php $cruise_types = get_terms( 'cruise_type' ); ?>
												<?php if ( ! empty( $cruise_types ) ) : ?>
													<label><?php _e( 'Cruise Types','trav' ); ?></label>
													<div class="selector">
														<select name="cruise_type" class="full-width">
															<option value=""><?php _e( 'Cruise Type', 'trav' ) ?></option>
															<?php foreach ( $cruise_types as $cruise_type ) : ?>
																<option value="<?php echo $cruise_type->term_id ?>"><?php _e( $cruise_type->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												<?php endif; ?>
											</div>
											
											<div class="col-xs-6">
												<?php $cruise_lines = get_terms( 'cruise_line' ); ?>
												<?php if ( ! empty( $cruise_lines ) ) : ?>
													<label><?php _e( 'Cruise Lines','trav' ); ?></label>
													<div class="selector">
														<select name="cruise_line" class="full-width">
															<option value=""><?php _e( 'Cruise Line', 'trav' ) ?></option>
															<?php foreach ( $cruise_lines as $cruise_line ) : ?>
																<option value="<?php echo $cruise_line->term_id ?>"><?php _e( $cruise_line->name, 'trav' ) ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												<?php endif; ?>
											</div>

										</div>
									</div>

									<div class="form-group col-sm-6 col-md-2 fixheight">
										<label class="hidden-xs">&nbsp;</label>
										<button type="submit" class="full-width" data-animation-type="bounce" data-animation-duration="1"><?php _e( 'SEARCH NOW', 'trav') ?></button>
									</div>
								</div>
							</form>
							<?php if ( count( $enabled_features ) > 1 ) : ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</section>
	<?php endwhile;
endif;
get_footer();