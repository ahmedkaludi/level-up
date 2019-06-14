<?php
global $search_max_cabins, $search_max_adults, $search_max_kids;
get_header();

if ( have_posts() ) {
	while ( have_posts() ) : the_post();

		//init variables
		$cruise_id = get_the_ID();
		$cruise_meta = get_post_meta( $cruise_id );
		$cruise_meta['review'] = get_post_meta( trav_cruise_org_id( $cruise_id ), 'review', true );
		$cruise_meta['review_detail'] = get_post_meta( trav_cruise_org_id( $cruise_id ), 'review_detail', true );
		$cruise_type = wp_get_post_terms( $cruise_id, 'cruise_type' );
		$cruise_line = wp_get_post_terms( $cruise_id, 'cruise_line' );
		
		$args = array(
			'post_type' => 'cabin_type',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'trav_cabin_cruise',
					'value' => array( $cruise_id )
				)
			),
			'suppress_filters' => 0,
		);
		$cabin_types = get_posts( $args );
		$facilities = wp_get_post_terms( $cruise_id, 'amenity' );
		$cruise_fd = empty( $cruise_meta['trav_cruise_fd'] ) ? '' : $cruise_meta['trav_cruise_fd'];

		// init gallery & calendar variables
		$gallery_imgs = array_key_exists( 'trav_gallery_imgs', $cruise_meta ) ? $cruise_meta['trav_gallery_imgs'] : array();
		$calendar_desc = empty( $cruise_meta['trav_cruise_calendar_txt'] ) ? '' : $cruise_meta['trav_cruise_calendar_txt'][0];
		$show_gallery = 0;
		$show_calendar = 0;
		if ( array_key_exists( 'trav_cruise_main_top', $cruise_meta ) ) {
			$main_top_meta = $cruise_meta['trav_cruise_main_top'];
			$show_gallery = in_array( 'gallery', $main_top_meta ) ? 1 : 0;
			$show_calendar = in_array( 'calendar', $main_top_meta ) ? 1 : 0;
		}

		// init booking search variables
		$cabins = ( isset( $_GET['cabins'] ) && is_numeric( $_GET['cabins'] ) ) ? sanitize_text_field( $_GET['cabins'] ) : 1;
		$adults = ( isset( $_GET['adults'] ) && is_numeric( $_GET['adults'] ) ) ? sanitize_text_field( $_GET['adults'] ) : 1;
		$kids = ( isset( $_GET['kids'] ) && is_numeric( $_GET['kids'] ) ) ? sanitize_text_field( $_GET['kids'] ) : 0;
		$child_ages = isset( $_GET['child_ages'] ) ? $_GET['child_ages'] : '';
		$date_from = ( ! empty( $_GET['date_from'] ) ) ? date( 'Y-m-d', strtotime( $_GET['date_from'] ) ) : '';
		$date_to = ( ! empty( $_GET['date_to'] ) ) ? date( 'Y-m-d', strtotime( $_GET['date_to'] ) ) : '';

		$except_booking_no = ( isset( $_GET['edit_booking_no'] ) ) ? sanitize_text_field( $_GET['edit_booking_no'] ) : 0;
		$pin_code = ( isset( $_GET['pin_code'] ) ) ? sanitize_text_field( $_GET['pin_code'] ) : 0;

		$cruise_schedules = trav_cruise_get_schedules( $cruise_id, $date_from, $date_to );

		if ( ! empty( $date_from ) && $cruise_schedules ) {
			$date_from = $cruise_schedules[0]['date_from'];
			$duration = $cruise_schedules[0]['duration'];
		} else {
			$date_from = "";
			$duration = "";
			$cruise_schedules = trav_cruise_get_schedules( $cruise_id );
		}
		if ( $cruise_schedules ) {				
			$cruise_meta["trav_cruise_duration"][0] = $cruise_schedules[0]['duration'];
			$cruise_meta["trav_cruise_departure"][0] = $cruise_schedules[0]['departure'];
			$cruise_meta["trav_cruise_arrival"][0] = $cruise_schedules[0]['arrival'];
		}

		// add to user recent activity
		trav_update_user_recent_activity( $cruise_id ); ?>

		<section id="content">
			<div class="container">
				<div class="row">
					<div id="main" class="col-sm-8 col-md-9">
						<div class="tab-container style1" id="cruise-main-content">
							<ul class="tabs">
								<?php if ( ! empty( $gallery_imgs ) && $show_gallery ) { ?>
									<li><a data-toggle="tab" href="#photos-tab"><?php echo __( 'photos', 'trav' ) ?></a></li>
								<?php } ?>

								<?php if ( $show_calendar ) { ?>
									<li><a data-toggle="tab" href="#calendar-tab"><?php echo __( 'calendar', 'trav' ) ?></a></li>
								<?php } ?>

								<?php if ( ! empty( $cruise_meta['trav_cruise_tg'] ) ) { ?>
									<li class="pull-right"><a class="button btn-small yellow-bg white-color" href="<?php echo esc_url( get_permalink( $cruise_meta['trav_cruise_tg'][0] ) ); ?>"><?php _e( 'TRAVEL GUIDE', 'trav' ) ?></a></li>
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

						<div id="cruise-features" class="tab-container">
							<ul class="tabs">
								<?php $def_tab = ( ! empty(  $cruise_meta['trav_cruise_def_tab'] ) ) ? $cruise_meta['trav_cruise_def_tab'][0] : 'desc';?>
								<li<?php echo ( $def_tab == 'desc' ) ? ' class="active"' : '' ?>><a href="#cruise-description" data-toggle="tab"><?php _e( 'Description','trav' ); ?></a></li>
								<li<?php echo ( $def_tab == 'cabins' ) ? ' class="active"' : '' ?>><a href="#cruise-availability" data-toggle="tab"><?php _e( 'Availability','trav' ); ?></a></li>
								<li<?php echo ( $def_tab == 'amenity' ) ? ' class="active"' : '' ?>><a href="#cruise-amenities" data-toggle="tab"><?php _e( 'Amenities','trav' ); ?></a></li>
								<li><a href="#cruise-food-dinning" data-toggle="tab"><?php _e( 'Food & Dinning','trav' ); ?></a></li>
								<li><a href="#cruise-reviews" data-toggle="tab"><?php _e( 'Reviews','trav' ); ?></a></li>
								<li><a href="#cruise-write-review" data-toggle="tab"><?php _e( 'Write a Review','trav' ); ?></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade<?php echo ( $def_tab == 'cabins' ) ? ' in active' : '' ?>" id="cruise-availability">
									<form id="check_availability_form" method="post">
										<input type="hidden" name="cruise_id" value="<?php echo esc_attr( $cruise_id ); ?>">
										<input type="hidden" name="action" value="cruise_get_available_cabins">
										<input type="hidden" name="duration" value="<?php echo esc_attr( $duration ); ?>">
										<?php wp_nonce_field( 'post-' . $cruise_id, '_wpnonce', false ); ?>
										<?php if ( isset( $_GET['edit_booking_no'] ) && ! empty( $_GET['edit_booking_no'] ) ) : ?>
											<input type="hidden" name="edit_booking_no" value="<?php echo esc_attr( $_GET['edit_booking_no'] ) ?>">
											<input type="hidden" name="pin_code" value="<?php echo esc_attr( $_GET['pin_code'] ) ?>">
										<?php endif; ?>
										<div class="update-search clearfix">
											<div class="alert alert-error" style="display:none;"><span class="message"><?php _e( 'Please select date from.','trav' ); ?></span><span class="close"></span></div>
											<div class="col-md-4">
												<h4 class="title"><?php _e( 'When','trav' ); ?></h4>												
												<label><?php _e( 'Date From','trav' ); ?></label>
												<div class="selector validation-field">
													<select id="date_from" name="date_from" class="full-width">
														<option value=""><?php _e( 'Select Date', 'trav' ); ?></option>
														<?php 
															if ( isset( $cruise_schedules ) && is_array( $cruise_schedules ) ) {
																foreach ( $cruise_schedules as $schedule ) {
																	$selected = ( $schedule['date_from'] == $date_from ) ? 'selected' : '';
																	echo '<option value="' . esc_attr( $schedule['date_from'] ) . '" ' . $selected . ' data-cruise-duration="' . $schedule['duration'] . '">' . esc_html( trav_tophptime( $schedule['date_from'] ) ) . '</option>';
																}
															}
														?>
													</select>
												</div>												
											</div>

											<div class="col-md-5">
												<h4 class="title"><?php _e( 'Who','trav' ); ?></h4>
												<div class="row">
													<div class="col-xs-4">
														<label><?php _e( 'CABINS','trav' ); ?></label>
														<div class="selector validation-field">
															<select name="cabins" class="full-width">
																<?php
																	for ( $i = 1; $i <= $search_max_cabins; $i++ ) {
																		$selected = ( $i == $cabins ) ? 'selected' : '';
																		echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
																	}
																?>
															</select>
														</div>
													</div>
													<div class="col-xs-4">
														<label><?php _e( 'ADULTS','trav' ); ?></label>
														<div class="selector validation-field">
															<select name="adults" class="full-width">
																<?php
																	for ( $i = 1; $i <= $search_max_adults; $i++ ) {
																		$selected = ( $i == $adults ) ? 'selected' : '';
																		echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
																	}
																?>
															</select>
														</div>
													</div>
													<div class="col-xs-4">
														<label><?php _e( 'KIDS','trav' ); ?></label>
														<div class="selector validation-field">
															<select name="kids" class="full-width">
																<?php
																	for ( $i = 0; $i <= $search_max_kids; $i++ ) {
																		$selected = ( $i == $kids ) ? 'selected' : '';
																		echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
																	}
																?>
															</select>
														</div>
													</div>
													<div class="clearer"></div>
													<div class="col-xs-12 age-of-children <?php if ( $kids == 0) echo 'no-display'?>">
														<h5><?php _e( 'Age of Children','trav' ); ?></h5>
														<div class="row">
														<?php
															$kid_nums = ( $kids > 0 )?$kids:1;
															for ( $kid_num = 1; $kid_num <= $kid_nums; $kid_num++ ) {
														?>
														
															<div class="col-xs-4 child-age-field">
																<label><?php echo esc_html( __( 'Child ', 'trav' ) . $kid_num ) ?></label>
																<div class="selector validation-field">
																	<select name="child_ages[]" class="full-width">
																		<?php
																			$max_kid_age = 17;
																			$child_age = ( isset( $_GET['child_ages'][ $kid_num -1 ] ) && is_numeric( (int) $_GET['child_ages'][ $kid_num -1 ] ) )?(int) $_GET['child_ages'][ $kid_num -1 ]:0;
																			for ( $i = 0; $i <= $max_kid_age; $i++ ) {
																				$selected = ( $i == $child_age ) ? 'selected' : '';
																				echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
																			}
																		?>
																	</select>
																</div>
															</div>
														<?php } ?>
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-3">
												<h4 class="visible-md visible-lg">&nbsp;</h4>
												<label class="visible-md visible-lg">&nbsp;</label>
												<div class="row">
													<div class="col-xs-12">
														<button id="check_availability" data-animation-duration="1" data-animation-type="bounce" class="full-width icon-check animated bounce" type="submit"><?php _e( "SEARCH NOW", "trav" ); ?></button>
													</div>
												</div>
											</div>
										</div>
									</form>

									<h2><?php echo __( 'Available Cabins', 'trav' ) ?></h2>
									<div class="room-list listing-style3 hotel">
										<?php 
											//get cabins
											if ( ! empty( $cabin_types ) ) {											
												if ( ! empty( $date_from ) ) {
													echo '<input type="hidden" name="pre_searched" value="1">';
													
													$return_value = trav_cruise_get_available_cabins( $cruise_id, $date_from, $duration, $cabins, $adults, $kids, $child_ages, $except_booking_no, $pin_code );
													if ( is_array( $return_value ) ) {
														$number_of_days = count( $return_value['check_dates'] );
														$available_cabin_type_ids = $return_value['bookable_cabin_type_ids'];
														if ( ! empty( $available_cabin_type_ids ) ) {
															foreach ( $available_cabin_type_ids as $cabin_type_id ) {
																$cabin_price = 0;
																foreach ( $return_value['check_dates'] as $check_date ) {
																	$cabin_price += (float) $return_value['prices'][ $cabin_type_id ][ $check_date ]['total'];
																}
																trav_cruise_get_cabin_detail_html( $cabin_type_id, 'available', $cabin_price, $number_of_days, $cabins );
															}
														}
														$not_available_cabin_type_ids = array_diff( $return_value['matched_cabin_type_ids'], $return_value['bookable_cabin_type_ids'] ) ;
														if ( ! empty( $not_available_cabin_type_ids ) ) {
															foreach ( $not_available_cabin_type_ids as $cabin_type_id ) {
																trav_cruise_get_cabin_detail_html( $cabin_type_id, 'not_available' );
															}
														}
														$not_match_cabin_type_ids = array_diff( $return_value['all_cabin_type_ids'], $return_value['matched_cabin_type_ids'] ) ;
														if ( ! empty( $not_match_cabin_type_ids ) ) {
															foreach ( $not_match_cabin_type_ids as $cabin_type_id ) {
																trav_cruise_get_cabin_detail_html( $cabin_type_id, 'not_match' );
															}
														}
													} else {
														echo wp_kses_post( $return_value );
													}
												} else {
													echo '<input type="hidden" name="pre_searched" value="0">';
													foreach ( $cabin_types as $cabin_type ) {
														trav_cruise_get_cabin_detail_html( $cabin_type->ID, 'all');
													}
												}
											} else {
												echo __( 'No Cabins Found', 'trav' );
											}
										?>
									</div>
								</div>
								<div class="tab-pane fade<?php echo ( $def_tab == 'desc' ) ? ' in active' : '' ?>" id="cruise-description">
									<div class="intro table-wrapper full-width hidden-table-sms">
										<div class="col-sm-5 features table-cell">
											<table>
												<?php
													$tr = '<tr><td><label>%s:</label></td><td>%s</td></tr>';
													//cruise type
													if ( ! empty ( $cruise_type ) ) {
														echo sprintf( $tr, __( 'Type', 'trav' ), esc_attr( $cruise_type[0]->name ) );
													}
													//cruise line
													if ( ! empty ( $cruise_line ) ) {
														echo sprintf( $tr, __( 'Cruise Line', 'trav' ), esc_attr( $cruise_line[0]->name ) );
													}

													$detail_fields = array( 'star_rating' => array( 'label' => __('Rating Stars', 'trav'), 'pre' => '', 'sur' => ' ' . __( 'star', 'trav') ),
																			'ship_name' => array( 'label' => __( 'Ship Name', 'trav' ), 'pre' => '', 'sur' => '' ),
																			'duration' => array( 'label' => __( 'Cruise Length', 'trav' ), 'pre' => '', 'sur' => ' ' . __( 'NIGHTS', 'trav' ) ),
																			'departure' => array( 'label' => __( 'Departure', 'trav' ), 'pre' => '', 'sur' => ' ' ),
																			'arrival' => array( 'label' => __( 'Arrival', 'trav' ), 'pre' => '', 'sur' => ' ' ),																			
																			'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
																			'security_deposit' => array( 'label' => __('Security Deposit', 'trav'), 'pre' => '', 'sur' => ' ' . '%' ),
																		);

													foreach ( $detail_fields as $field => $value ) {
														if ( empty( $$field ) ) $$field = empty( $cruise_meta["trav_cruise_$field"] )?'':$cruise_meta["trav_cruise_$field"][0];
														if ( ! empty( $$field ) ) {
															$content = $value['pre'] . $$field . $value['sur'];
															echo sprintf( $tr, esc_html( $value['label'] ), esc_html( $content ) );
														}
													}
												?>
											</table>		                                    
		                                </div>
		                                <div class="col-sm-7 table-cell cruise-itinerary">
		                                    <div class="travelo-box">
		                                        <h4 class="box-title"><?php _e( 'Cruise Itinerary', 'trav' ); ?></h4>
		                                        <table>
		                                            <thead>
		                                                <tr>
		                                                    <th><?php _e( 'Day', 'trav' ); ?></th>
		                                                    <th><?php _e( 'Ports of Call', 'trav' ); ?></th>
		                                                    <th><?php _e( 'Arrival', 'trav' ); ?></th>
		                                                    <th><?php _e( 'Departure', 'trav' ); ?></th>
		                                                </tr>
		                                            </thead>
		                                            <tbody>
		                                            	<?php if( is_array( $cruise_schedules ) && ! empty( $cruise_schedules[0]['itinerary'] ) ):
			                                            		$itinerary_data = unserialize( $cruise_schedules[0]['itinerary'] );
			                                            		foreach( $itinerary_data as $values): ?>
				                                                <tr>
				                                                    <td><?php echo $values[0]; ?></td>
				                                                    <td><?php echo $values[1]; ?></td>
				                                                    <td><?php echo $values[2]; ?></td>
				                                                    <td><?php echo $values[3]; ?></td>
				                                                </tr>
		                                                <?php  endforeach; ?>
		                                                <?php endif; ?>
		                                            </tbody>
		                                        </table>
		                                    </div>
		                                </div>
		                            </div>
									<div class="long-description">
										<div class="box entry-content">
											<?php the_content(); ?>
											<?php wp_link_pages('before=<div class="page-links">&after=</div>'); ?>
										</div>
										<div class="box policies-box">
											<h2><?php printf( __( 'Policies of %s', 'trav' ), wp_kses_post( get_the_title( $cruise_id ) ) ) ?></h2>
											<?php
												$tr = '<div class="row"><div class="col-xs-2"><label>%s:</label></div><div class="col-xs-10">%s</div></div>';

												$detail_fields = array( 'cancellation' => array( 'label' => __('Cancellation / prepayment', 'trav'), 'pre' => '', 'sur' => '' ),
																		'security_deposit' => array( 'label' => __('Security Deposit Info', 'trav'), 'pre' => '', 'sur' => '%' ),
																		'extra_beds_detail' => array( 'label' => __('Children and Extra Beds', 'trav'), 'pre' => '', 'sur' => '' ),
																		'cards' => array( 'label' => __('Cards accepted at this property', 'trav'), 'pre' => '', 'sur' => '' ),
																		'pets' => array( 'label' => __('Pets', 'trav'), 'pre' => '', 'sur' => '' ),
																		'other_policies' => array( 'label' => __('Other Policies', 'trav'), 'pre' => '', 'sur' => '' ),
																	);

												foreach ( $detail_fields as $field => $value ) {
													$$field = empty( $cruise_meta["trav_cruise_$field"] )?'':$cruise_meta["trav_cruise_$field"][0];
													if ( ! empty( $$field ) ) {
														$content = $value['pre'] . $$field . $value['sur'];
														echo sprintf( $tr, esc_html( $value['label'] ), esc_html( $content ) );
													}
												}
											?>
										</div>
									</div>
								</div>
								<div class="tab-pane fade<?php echo ( $def_tab == 'amenity' ) ? ' in active' : '' ?>" id="cruise-amenities">
									<h2><?php echo __('Amenities of ', 'trav'); the_title();?></h2>
									<p>
										<?php
											echo esc_attr( empty( $cruise_meta["trav_cruise_other_amenity_info"] ) ? '' : $cruise_meta["trav_cruise_other_amenity_info"][0] );
										?>
									</p>
									<ul class="amenities clearfix style1">
										<?php
											$amenity_icons = get_option( "amenity_icon" );
											$amenity_html = '';
											foreach ( $facilities as $facility ) {
												if ( is_array( $amenity_icons ) && isset( $amenity_icons[ $facility->term_id ] ) ) {
													$amenity_html .= '<li class="col-md-4 col-sm-6">';
													if ( isset( $amenity_icons[ $facility->term_id ]['uci'] ) ) {
														$amenity_html .= '<div class="icon-box style1"><div class="custom_amenity"><img title="' . esc_attr( $facility->name ) . '" src="' . esc_url( $amenity_icons[ $facility->term_id ]['url'] ) . '" height="42" alt="amenity-image"></div>' . esc_html( $facility->name ) . '</div>';
													} else if ( isset( $amenity_icons[ $facility->term_id ]['icon'] ) ) {
														$_class = $amenity_icons[ $facility->term_id ]['icon'];
														$amenity_html .= '<div class="icon-box style1"><i class="' . esc_attr( $_class ) . '" title="' . esc_attr( $facility->name ) . '"></i>' . esc_html( $facility->name ) . '</div>';
													} else {
														$amenity_html .= '<div class="icon-box style1"><i class="" title="' . esc_attr( $facility->name ) . '"></i>' . esc_html( $facility->name ) . '</div>';
													}
													$amenity_html .= '</li>';
												}
												
											}
											echo wp_kses_post( $amenity_html );
										?>
									</ul>
								</div>
								<div class="tab-pane fade" id="cruise-reviews">
									<div class="intro table-wrapper full-width hidden-table-sms">
										<div class="rating table-cell col-sm-4">
											<?php
												$cruise_review = ( ! empty( $cruise_meta['review'] ) )?(float) $cruise_meta['review']:0;
												$cruise_review = round( $cruise_review, 1 );
											?>
											<span class="score"><?php echo esc_html( $cruise_review );?>/5.0</span>
											<div class="five-stars-container"><div class="five-stars" style="width: <?php echo esc_attr( $cruise_review / 5 * 100 ) ?>%;"></div></div>
											<a href="#" class="goto-writereview-pane button green btn-small full-width"><?php echo esc_html__( 'WRITE A REVIEW', 'trav' ) ?></a>
										</div>
										<div class="table-cell col-sm-8 no-rpadding no-lpadding">
											<div class="detailed-rating validation-field">
												<ul class="clearfix">
													<?php
														$review_factors = array(
																'sql' => __( 'Ship Quality', 'trav' ),
																'dfd' => __( 'Dining/Food', 'trav' ),
																'rql' => __( 'Rooms Quality', 'trav' ),
																'par' => __( 'Play areas', 'trav' ),
																'cln' => __( 'Cleanliness', 'trav' ),
																'ffl' => __( 'Fitness facility', 'trav' ),
															);
														$i = 0;
														$review_detail = array( 0, 0, 0, 0, 0, 0 );
														if ( ! empty( $cruise_meta['review_detail'] ) ) $review_detail = is_array( $cruise_meta['review_detail'] ) ? $cruise_meta['review_detail'] : unserialize( $cruise_meta['review_detail'] );
														foreach ( $review_factors as $factor => $label ) {
															echo '<li class="col-md-6"><div class="each-rating"><label>' . esc_html( $label ) . '</label><div class="five-stars-container" data-toggle="tooltip" data-placement="bottom" data-original-title="' . esc_attr( $review_detail[$i] ) . ' stars" ><div class="five-stars" style="width: ' . esc_attr( $review_detail[$i] / 5 * 100 ) . '%;"></div></div></div></li>';
															$i++;
														}
													?>
												</ul>
											</div>
										</div>
									</div>
									<div class="guest-reviews">
										<h2><?php echo __('Guest Reviews', 'trav') . ' <small>('; echo esc_html( trav_get_review_count( $cruise_id ) . ' ' .  __('reviews', 'trav') . ')' ); ?></small></h2>
										<?php
											$per_page = 10;
											$review_count = trav_get_review_html($cruise_id, 0, $per_page);
										?>
									</div>
									<?php if ( $review_count >= $per_page ) { ?>
										<a href="#" class="more-review"><button class="silver full-width btn-large"><?php echo __( 'LOAD MORE REVIEWS', 'trav' ) ?></button></a>
									<?php } ?>
								</div>								
								<?php if ( ! empty( $cruise_fd ) ) : ?>
									<div class="tab-pane fade" id="cruise-food-dinning">
										<div class="box">
											<h2><?php _e('Food and Dinning on Ship', 'trav');?></h2>
											<p><?php echo esc_html( empty( $cruise_meta['trav_cruise_fd_detail'] )?'':$cruise_meta['trav_cruise_fd_detail'][0] ); ?></p>
										</div>
										<div class="food-dinning-list image-box style2">
											<?php foreach( $cruise_fd as $fd_id ) { ?>
												<div class="box">
				                                    <figure>
				                                        <a title="<?php echo esc_attr( get_the_title( $fd_id ) ); ?>" href="<?php echo esc_url( get_permalink( $fd_id ) ); ?>"><?php echo ( get_the_post_thumbnail( $fd_id, 'list-thumb' ) ); ?></a>
				                                    </figure>
				                                    <div class="details">
				                                        <div class="box-title">
				                                            <h4 class="title"><?php echo esc_html( get_the_title( $fd_id ) ); ?></h4>
				                                            <dl>
				                                                <dt><?php _e( 'Seating Times:', 'trav' ); ?></dt>
				                                                <dd>
			                                                	<?php
				                                                	$seat_time = get_post_meta( $fd_id, 'trav_fd_time');
				                                                	echo esc_html( empty( $seat_time )?'':$seat_time[0] );
				                                                ?>
				                                                </dd>
				                                            </dl>
				                                        </div>
				                                        <hr class="hidden-xs">
				                                        <p><?php
															$fd_excerpt = get_post_field('post_excerpt', $fd_id);
															if ( ! empty( $fd_excerpt ) ) {
																echo wp_kses_post( apply_filters( 'the_excerpt', $fd_excerpt ) );
															} else {
																$fd_content = apply_filters('the_content', get_post_field('post_content', $fd_id));
																echo wp_kses_post( wp_trim_words( $fd_content, 55, '' ) );
															}
														?></p>
				                                    </div>
				                                </div>												
											<?php } ?>
										</div>
									</div>
								<?php endif; ?>
								<div class="tab-pane fade" id="cruise-write-review">
									<?php
										$booking_data = '';
										$review_data ='';
										$rating_detail = '';
										$averagy_rating = 0;
										if ( is_user_logged_in() ) {
											$booking_data = $wpdb->get_row( sprintf( 'SELECT * FROM ' . TRAV_CRUISE_BOOKINGS_TABLE . ' WHERE cruise_id=%d AND user_id=%d AND date_to<%s ORDER BY date_to DESC', trav_cruise_org_id( $cruise_id ), get_current_user_id(), date("Y-m-d") ), ARRAY_A );
											if ( ! empty( $booking_data ) ) {
												$review_data = $wpdb->get_row( sprintf( 'SELECT * FROM ' . TRAV_REVIEWS_TABLE . ' WHERE booking_no=%d AND pin_code=%d', $booking_data['booking_no'], $booking_data['pin_code'] ), ARRAY_A );
												if ( is_array( $review_data ) && isset( $review_data['review_rating_detail'] ) ) {
													$rating_detail = unserialize($review_data['review_rating_detail']);
													$averagy_rating = array_sum($rating_detail)/count($rating_detail);
												}
											}
										}
									?>
									<div class="alert alert-error" style="display: none;"><span class="message"></span><span class="close"></span></div>
									<div class="main-rating table-wrapper full-width hidden-table-sms intro">
										<article class="image-box box cruise listing-style1 table-cell col-sm-4 photo">
											<figure>
												<?php the_post_thumbnail( 'gallery-thumb' )?>
											</figure>
											<div class="details">
												<h4 class="box-title"><?php the_title(); ?><small><?php echo $cruise_meta["trav_cruise_ship_name"][0]; ?></small></h4>
												<div class="feedback">
													<div title="<?php echo esc_attr( $cruise_review . ' ' . __( 'stars', 'trav') );?>" class="five-stars-container" data-toggle="tooltip" data-placement="bottom"><span class="five-stars" style="width: <?php echo esc_html( $cruise_review / 5 * 100 );?>%;"></span></div>
													<span class="review"><?php echo esc_html( trav_get_review_count( $cruise_id ) ); echo ' ' . __( 'reviews', 'trav' ) ?></span>
												</div>
											</div>
										</article>
										<div class="table-cell col-sm-8 no-rpadding">
											<div class="overall-rating">
												<h4><?php _e( 'Your overall Rating of this cruise', 'trav');?></h4>
												<div class="star-rating clearfix">
													<div class="five-stars-container"><div class="five-stars" style="width: <?php echo esc_attr( $averagy_rating / 5 * 100 ) ?>%;"></div></div>
													<span class="status"></span>
												</div>
												<div class="detailed-rating validation-field">
													<ul class="clearfix">
														<?php
															$i = 0;
															foreach ( $review_factors as $factor => $label ) {
																echo '<li class="col-md-6"><div class="each-rating"><label>' . esc_html( $label ) . '</label><div class="five-stars-container editable-rating" data-original-stars="' . esc_attr( ( is_array($rating_detail) && isset( $rating_detail[ $i ] ) )?$rating_detail[ $i ]:0 ) . '"></div></div></li>';
																$i++;
															}
														?>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<form class="review-form" id="review-form" method="post">
										<?php wp_nonce_field( 'post-' . $cruise_id, '_wpnonce', false ); ?>
										<input type="hidden" name="review_rating" value="<?php echo esc_attr( $averagy_rating ) ?>">
										<?php
										$i = 0;
										foreach ( $review_factors as $factor => $label ) {
											echo '<input type="hidden" class="rating_detail_hidden" name="review_rating_detail[]" value="' . esc_attr( ( is_array($rating_detail) && isset( $rating_detail[ $i ] ) )?$rating_detail[ $i ]:'' ) . '">';
											$i++;
										} ?>
										<input type="hidden" name="post_id" value="<?php echo esc_attr( $cruise_id ); ?>">
										<input type="hidden" name="action" value="cruise_submit_review">
										<div class="row clearer">
											<div class="form-group col-md-5 no-padding">
												<h4><?php _e( 'Booking Number', 'trav'); ?></h4>
												<input type="text" name="booking_no" class="input-text full-width validation-field" value="<?php if ( is_array( $booking_data ) && isset( $booking_data['booking_no'] ) ) echo esc_attr( $booking_data['booking_no'] ); ?>" data-error-message="<?php _e( 'Enter your booking number', 'trav' ); ?>" placeholder="<?php _e( 'Enter your booking number', 'trav' ); ?>" />
											</div>
											<div class="form-group col-md-5 col-md-offset-1 no-padding">
												<h4><?php _e( 'Pin Code', 'trav'); ?></h4>
												<input type="text" name="pin_code" class="input-text full-width validation-field" value="<?php if ( is_array( $booking_data ) && isset( $booking_data['pin_code'] ) ) echo esc_attr( $booking_data['pin_code'] ); ?>" data-error-message="<?php _e( 'Enter your pin code', 'trav' ); ?>" placeholder="<?php _e( 'Enter your pin code', 'trav' ); ?>" />
											</div>
										</div>

										 <div class="form-group col-md-5 no-float no-padding">
											<h4><?php _e( 'Title of your review', 'trav'); ?></h4>
											<input type="text" name="review_title" class="input-text full-width validation-field" value="<?php if ( is_array( $review_data ) && isset( $review_data['review_title'] ) ) echo esc_attr( $review_data['review_title'] ); ?>" data-error-message="<?php _e( 'Enter a review title', 'trav' ); ?>" placeholder="<?php _e( 'Enter a review title', 'trav' ); ?>" />
										</div>
										<div class="form-group">
											<h4><?php _e( 'Your review', 'trav'); ?></h4>
											<textarea name="review_text" class="input-text full-width validation-field" data-error-message="<?php _e( 'Enter your review', 'trav' ); ?>" placeholder="<?php _e( 'Enter your review', 'trav' ); ?>" rows="5"><?php if ( is_array( $review_data ) && isset( $review_data['review_text'] ) ) echo esc_textarea( $review_data['review_text'] ); ?></textarea>
										</div>
										<div class="form-group">
											<h4><?php _e( 'What sort of Trip was this?', 'trav'); ?></h4>
											<ul class="sort-trip clearfix">
												<?php
													$trip_types = array(
															'businessbag' => __( 'Business', 'trav' ),
															'couples' => __( 'Couples', 'trav' ),
															'family' => __( 'Family', 'trav' ),
															'friends' => __( 'Friends', 'trav' ),
															'user' => __( 'Solo', 'trav' ),
														);
													$active_trip_type = 0;
													$i = 0;
													if ( is_array( $review_data ) && isset( $review_data['trip_type'] ) ) $active_trip_type = $review_data['trip_type'];
													foreach ($trip_types as $key => $value) {
														$active = '';
														if ( $i == $active_trip_type ) $active = ' class="active"';
														echo '<li' . $active . '><a href="#"><i class="soap-icon-' . esc_attr( $key ) . ' circle"></i></a><span>' . esc_html( $value ) . '</span></li>';
														$i++;
													}
												?>
											</ul>
											<input type="hidden" name="trip_type" value="<?php echo esc_attr( $active_trip_type ); ?>">
										</div>
										<div class="form-group col-md-5 no-float no-padding no-margin">
											<button type="submit" class="btn-large full-width submit-review"><?php echo __( 'SUBMIT REVIEW', 'trav' ) ?></button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="sidebar col-sm-4 col-md-3">
						<article class="detailed-logo">
							<?php if ( isset( $cruise_meta['trav_cruise_logo'] ) ) { ?>
								<figure>
									<img width="114" src="<?php echo esc_url( wp_get_attachment_url( $cruise_meta['trav_cruise_logo'][0] ) );?>" alt="Accommodation Logo">
								</figure>
							<?php } ?>
							<div class="details">
								<h2 class="box-title">
									<?php the_title(); ?>
								</h2>
								<?php if ( isset( $cruise_meta['trav_cruise_avg_price'] ) && is_numeric( $cruise_meta['trav_cruise_avg_price'][0] ) ) { ?>
									<span class="price clearfix">
										<small class="pull-left"><?php _e( 'avg/night', 'trav' ); ?></small>
										<span class="pull-right"><?php echo esc_html( trav_get_price_field( $cruise_meta['trav_cruise_avg_price'][0] ) ); ?></span>
									</span>
								<?php } ?>
								<div class="feedback clearfix">
									<div title="<?php echo esc_attr( $cruise_review . ' ' . __( 'stars', 'trav' ) );?>" class="five-stars-container" data-toggle="tooltip" data-placement="bottom"><span class="five-stars" style="width: <?php echo esc_attr( $cruise_review / 5 * 100 );?>%;"></span></div>
									<span class="review pull-right"><?php echo esc_html( trav_get_review_count( $cruise_id ) . ' ' . __( 'reviews', 'trav' ) ) ?></span>
								</div>
								<p class="description">
									<?php
										if ( isset( $cruise_meta['trav_cruise_brief'] ) ) {
											echo esc_html( $cruise_meta['trav_cruise_brief'][0] );
										} else {
											$brief_content = apply_filters('the_content', get_post_field('post_content', $cruise_id));
											echo wp_kses_post( wp_trim_words( $brief_content, 20, '' ) );
										}
									?>
								</p>
								<?php if ( is_user_logged_in() ) {
									$user_id = get_current_user_id();
									$wishlist = get_user_meta( $user_id, 'wishlist', true );
									if ( empty( $wishlist ) ) $wishlist = array();
									if ( ! in_array( trav_cruise_org_id( $cruise_id ), $wishlist) ) { ?>
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
		</section><!-- #content -->
<?php endwhile;
}
get_footer();