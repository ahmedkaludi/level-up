<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * Get Single Schedule HTML  in Tour detail page
 * input : $schedule = array( '2015-01-01' => array(schedule data) );
 * output : echo html
 */
if ( ! function_exists( 'trav_tour_get_single_schedule_html' ) ) {
	function trav_tour_get_single_schedule_html( $tour_id, $st_id, $schedule ) {
		global $trav_options;

		// init variables
		$st_data = trav_tour_get_schedule_type_data( $tour_id, $st_id );
		$st_title = ( ! empty( $st_data ) && ! empty( $st_data['title'] ) ) ? $st_data['title'] : '';
		$st_desc = ( ! empty( $st_data ) && ! empty( $st_data['description'] ) ) ? $st_data['description'] : '';
		$st_time = ( ! empty( $st_data ) && ! empty( $st_data['time'] ) ) ? $st_data['time'] : '';

		$repeated = get_post_meta( $tour_id, 'trav_tour_repeated', true );
		$multi_book = get_post_meta( $tour_id, 'trav_tour_multi_book', true );
		$tour_booking_page = '';
		if ( ! empty( $trav_options['tour_booking_page'] ) ) {
			$tour_booking_page = trav_get_permalink_clang( $trav_options['tour_booking_page'] );
		}

		$location_arr = array();
		$location_arr[] = trav_tour_get_city( $tour_id );
		$location_arr[] = trav_tour_get_country( $tour_id );
		$location = implode(', ', $location_arr );
		$discount = get_post_meta( $tour_id, 'trav_tour_hot', true );
		$discount_rate = get_post_meta( $tour_id, 'trav_tour_discount_rate', true );

		// init variables
		$def_date = key($schedule);
		$default_data = $schedule[$def_date];
		foreach ( $schedule as $key => $value ) {
			if ( ! empty( $value['available_seat'] ) ) {
				$def_date = $key;
				$default_data = $value;
				break;
			}
		}
		$adults = 1; $kids = 0;
		?>
		<div class="intro small-box table-wrapper full-width hidden-table-sms">
			<div class="col-sm-4 table-cell features">
				<table>
					<tr><td><label><?php _e( 'Location', 'trav' ) ?>:</label></td><td><?php echo esc_html( $location ) ?></td></tr>
					<?php if ( empty( $repeated ) ) : ?>
						<!-- <tr><td><label><?php _e( 'Tour Date', 'trav' ) ?>:</label></td><td><?php echo date( 'l, F, j, Y', trav_strtotime( $default_data['tour_date'] ) ); ?></td></tr> -->
						<tr><td><label><?php _e( 'Tour Date', 'trav' ) ?>:</label></td><td><?php echo date_i18n( 'l, F, j, Y', trav_strtotime( $default_data['tour_date'] ) ); ?></td></tr>
					<?php endif; ?>
					<tr><td><label><?php _e( 'Duration', 'trav' ) ?>:</label></td><td><?php echo esc_html( $default_data['duration'] ) ?></td></tr>
					<tr><td><label><?php _e( 'Available Seats', 'trav' ) ?>:</label></td><td class="available-seats"><?php echo esc_html( $default_data['available_seat'] ) ?></td></tr>

					<?php if ( ! empty( $multi_book ) ) { ?>
						<tr><td><label><?php _e( 'Price Per Adult', 'trav' ) ?>:</label></td><td class="adult-price"><?php echo esc_html( trav_get_price_field( $default_data['price'] ) ) ?></td></tr>
						<?php if ( ! empty( $default_data['child_price'] ) && ( (float) $default_data['child_price'] ) != 0 ) { ?>
							<tr><td><label><?php _e( 'Price Per Child', 'trav' ) ?>:</label></td><td class="child-price"><?php echo esc_html( trav_get_price_field( $default_data['child_price'] ) ) ?></td></tr>
						<?php } ?>
					<?php } else { ?>
						<tr><td><label><?php _e( 'Price', 'trav' ) ?>:</label></td><td class="adult-price"><?php echo esc_html( trav_get_price_field( $default_data['price'] ) ) ?></td></tr>
					<?php } ?>

					<?php if ( ! empty( $discount ) && ! empty( $discount_rate ) ) { ?>
						<tr><td><label><?php _e( 'Discount', 'trav' ) ?>:</label></td><td><?php echo sprintf( __( '%d%% Off', 'trav' ), $discount_rate )  ?></td></tr>
					<?php } ?>
				</table>
			</div>
			<div class="col-sm-8 table-cell">
				<form method="get" action="<?php echo $tour_booking_page ?>" class="tour-booking-form">
					<input type="hidden" name="tour_id" value="<?php echo esc_attr( $tour_id ) ?>">
					<input type="hidden" name="st_id" value="<?php echo esc_attr( $st_id ) ?>">
					<?php wp_nonce_field( 'post-' . $tour_id, '_wpnonce', false ); ?>
					<?php if ( defined('ICL_LANGUAGE_CODE') ) : ?>
						<input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>">
					<?php endif; ?>
					<?php if ( empty( $multi_book ) ) : ?>
						<input type="hidden" name="adults" value="1">
					<?php endif; ?>
					<?php if ( empty( $repeated ) ) : ?>
						<input type="hidden" name="tour_date" value="<?php echo $default_data['tour_date'] ?>">
					<?php endif; ?>

					<div class="detail-section-top row">
						<div class="st-details col-md-9 col-sm-8">
							<?php if ( ! empty( $st_title ) ) : ?>
								<h4 class="box-title"><?php echo wp_kses_post( $st_title ) ?></h4>
							<?php endif; ?>
							<?php if ( ! empty( $st_desc ) ) : ?>
								<div class="st-description"><?php echo wp_kses_post( $st_desc ) ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $st_time ) ) : ?>
							<div class="time"><i class="soap-icon-clock yellow-color"></i><span><?php echo wp_kses_post( $st_time ) ?></span></div>
							<?php endif; ?>
						</div>
						<div class="price-details col-md-3 col-sm-4">
							<h3 class="price">
								<div class="adult-price"><?php echo trav_get_price_field( $default_data['price'] ) ?></div>
								<?php if ( ! empty( $multi_book ) ) : ?>
									<small><?php _e( 'per adult', 'trav') ?></small>
								<?php endif; ?>
							</h3>
							<?php if ( empty( $multi_book ) && empty( $repeated ) ) : ?>
								<button title="book now" class="button btn-small full-width text-center btn-book-now <?php echo empty( $default_data['available_seat'] ) ? 'no-display' : '' ?>"><?php _e( "BOOK NOW", "trav" ); ?></button>
								<h4 class="sold-out <?php echo empty( $default_data['available_seat'] ) ? '' : 'no-display' ?>"><?php echo __( 'Sold Out', 'trav' ) ?></h4>
								<h4 class="exceed-persons no-display"><?php echo __( 'Exceed Persons', 'trav' ) ?></h4>
							<?php endif; ?>
						</div>
					</div>

					<?php if ( ! empty( $multi_book ) || ! empty( $repeated ) ) : ?>
						<div class="detail-section-bottom">
							<div class="row">
								<?php if ( ! empty( $repeated ) ) : ?>
									<div class="col-md-4 col-sm-6">
										<label><?php _e( 'AVAILABLE ON','trav' ); ?></label>
										<div class="selector validation-field">
											<select name="tour_date" class="full-width tour-date-select">
												<?php 
													foreach( $schedule as $key => $value ) {
														$selected = ( $key == $def_date ) ? 'selected' : '';
														echo '<option value="' . esc_attr( $key ) . '" ' . $selected . ' data-max-seat="' . esc_attr( $value['available_seat'] ) . '" data-price="' . esc_attr( $value['price'] ) . '" data-child-price="' . esc_attr( $value['child_price'] ) . '" >' . esc_html( trav_tophptime( $key ) ) . '</option>';
													}
												 ?>
											</select>
										</div>
									</div>
								<?php else: ?>
									<div class="price-data no-display" data-max-seat="<?php echo esc_attr( $default_data['available_seat'] ) ?>" data-price="<?php echo esc_attr( $default_data['price'] ) ?>" data-child-price="<?php echo esc_attr( $default_data['child_price'] ) ?>"></div>
								<?php endif; ?>

								<?php if ( ! empty( $multi_book ) ) : ?>
									<div class="col-md-2 col-sm-3 col-xs-6">
										<label><?php _e( 'ADULTS','trav' ); ?></label>
										<div class="selector validation-field">
											<select name="adults" class="full-width">
												<?php
													for ( $i = 1; $i <= 10; $i++ ) {
														$selected = ( $i == $adults ) ? 'selected' : '';
														echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-2 col-sm-3 col-xs-6">
										<label><?php _e( 'KIDS','trav' ); ?></label>
										<div class="selector validation-field">
											<select name="kids" class="full-width">
												<?php
													for ( $i = 0; $i <= 10; $i++ ) {
														$selected = ( $i == $kids ) ? 'selected' : '';
														echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
								<?php endif; ?>

								<div class="col-md-4 pull-right">
									<label>
										<?php _e( 'Total', 'trav') ?>:
										<span class="total-price">
											<?php echo trav_get_price_field( $default_data['price'] ) ?>
										</span>
									</label>
									<div class="row">
										<div class="col-sm-12">
											<button data-animation-duration="1" data-animation-type="bounce" class="btn-book-now full-width icon-check animated bounce <?php echo empty( $default_data['available_seat'] ) ? 'no-display' : '' ?>" type="submit"><?php _e( "BOOK NOW", "trav" ); ?></button>
											<h4 class="sold-out <?php echo empty( $default_data['available_seat'] ) ? '' : 'no-display' ?>"><?php echo __( 'Sold Out', 'trav' ) ?></h4>
											<h4 class="exceed-persons no-display"><?php echo __( 'Exceed Persons', 'trav' ) ?></h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</div>

		<?php
	}
}

/*
 * Get Schedules list HTML in Tour detail page
 * input : array( 'tour_id', 'date_from', 'date_to' );
 * output : echo html
 */
if ( ! function_exists( 'trav_tour_get_schedule_list_html' ) ) {
	function trav_tour_get_schedule_list_html( $tour_data = array() ) {
		$schedules = trav_tour_get_available_schedules( $tour_data );
		$tour_id = $tour_data['tour_id'];
		if ( ! empty( $schedules ) ) {
			foreach( $schedules as $st_id => $schedule ) {
				trav_tour_get_single_schedule_html( $tour_id, $st_id, $schedule );
			}
		}
	}
}

/*
 * Single tour Block HTML
 */
if ( ! function_exists( 'trav_tour_get_tour_list_sigle' ) ) {
	function trav_tour_get_tour_list_sigle( $tour_id, $list_style, $before_article='', $after_article='', $show_badge=false, $animation='' ) {
		echo wp_kses_post( $before_article );
		$tour_id = trav_tour_clang_id( $tour_id );
		$min_price = get_post_meta( $tour_id, 'trav_tour_min_price', true );
		$brief = get_post_meta( $tour_id, 'trav_tour_brief', true );
		if ( empty( $brief ) ) {
			$brief = apply_filters('the_content', get_post_field('post_content', $tour_id));
			$brief = wp_trim_words( $brief, 20, '' );
		}
		$discount_rate = get_post_meta( $tour_id, 'trav_tour_discount_rate', true );
		$url = get_permalink( $tour_id );
		$duration = trav_tour_get_tour_duration( $tour_id );

		if ( $list_style == "style1" ) { ?>

			<article class="box">
				<figure <?php echo wp_kses_post( $animation ) ?>>
					<a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $tour_id );?>" href="#"><?php echo get_the_post_thumbnail( $tour_id, 'biggallery-thumb' ); ?></a>
					<?php if ( ! empty( $discount_rate ) ) { ?>
						<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
					<?php } ?>
				</figure>
				<div class="details">
					<?php if ( ! empty( $min_price ) && is_numeric( $min_price ) ) { ?>
						<span class="price"><?php echo esc_html( trav_get_price_field( $min_price ) ); ?></span>
					<?php } ?>
					<h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $tour_id ) );?></a></h4>
					<hr>
					<div class="description"><?php echo wp_kses_post( $brief ); ?></div>
					<hr>
					<div class="text-center">
						<div class="time">
							<i class="soap-icon-clock yellow-color"></i>
							<span><?php echo esc_html( $duration ) ?></span>
						</div>
					<div class="action">
						<a title="<?php _e( 'View Detail', 'trav' ); ?>" class="button btn-small full-width" href="<?php echo esc_url( $url ); ?>"><?php _e( 'BOOK NOW', 'trav' ); ?></a>
					</div>
				</div>
			</article>
		<?php } elseif ( $list_style == "style2" ) { ?>
			<article class="box">
				<?php if ( ! empty( $discount_rate ) ) { ?>
					<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
				<?php } ?>
				<figure <?php echo wp_kses_post( $animation ) ?>>
					<a href="<?php echo esc_url( $url ); ?>"><?php echo get_the_post_thumbnail( $tour_id, 'biggallery-thumb' ); ?></a>
					<figcaption>
						<?php if ( ! empty( $min_price ) && is_numeric( $min_price ) ) { ?>
							<span class="price"><?php echo esc_html( trav_get_price_field( $min_price ) ); ?></span>
						<?php } ?>
						<h2 class="caption-title"><?php echo esc_html( get_the_title( $tour_id ) );?></h2>
					</figcaption>
				</figure>
			</article>
		<?php } elseif ( $list_style == "style3" ) { ?>

			<article class="box">
				<figure class="col-sm-5 col-md-4">
					<a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $tour_id );?>" href="#"><?php echo get_the_post_thumbnail( $tour_id, 'biggallery-thumb' ); ?></a>
					<?php if ( $show_badge && ! empty( $discount_rate ) ) { ?>
						<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
					<?php } ?>
				</figure>
				<div class="details col-sm-7 col-md-8">
					<div>
						<div>
							<h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $tour_id ) );?></a><small><i class="soap-icon-clock yellow-color"></i> <?php echo esc_html( $duration ) ?></small></h4>
						</div>
						<div>
							<span class="price"><small><?php _e( 'per person', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $min_price ) ); ?></span>
						</div>
					</div>
					<div>
						<?php echo wp_kses_post( $brief ); ?>
						<div>
							<a title="<?php _e( 'View Detail', 'trav' ); ?>" class="button btn-small full-width text-center" href="<?php echo esc_url( $url ); ?>"><?php _e( 'BOOK NOW', 'trav' ); ?></a>
						</div>
					</div>
				</div>
			</article>
		<?php }

		echo wp_kses_post( $after_article );
	}
}