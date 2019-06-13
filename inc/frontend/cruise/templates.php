<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Accommodation Cabin Detail HTML in Cabin List
 */
if ( ! function_exists( 'trav_cruise_get_cabin_detail_html' ) ) {
	function trav_cruise_get_cabin_detail_html( $cabin_type_id, $type = 'all', $cabin_price = 0, $number_of_days = 0, $cabins = 0) { // available type - all,available,not_available,not_match
		$cabin_type_id = trav_cabin_clang_id( $cabin_type_id );
		?>
			<article class="box">
                <figure class="col-sm-4 col-md-3">
                    <a class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $cabin_type_id );?>" href="#" title="<?php echo __( 'popup gallery', 'trav' ); ?>"><?php echo get_the_post_thumbnail( $cabin_type_id, 'list-thumb' ); ?></a>
                </figure>
                <div class="details col-xs-12 col-sm-8 col-md-9">
                    <div>
                        <div>
                            <div class="box-title">
                                <h4 class="title"><a href="<?php echo esc_url( get_permalink( $cabin_type_id ) ); ?>"><?php echo esc_html( get_the_title( $cabin_type_id ) ); ?></a></h4>
                                <dl class="description">
								<?php
									$max_adults = get_post_meta( $cabin_type_id, 'trav_cabin_max_adults', true );
									if ( ! empty( $max_adults ) ) {
								?>
									<dt><?php _e( 'Max Guests:', 'trav' ) ?>:</dt>
									<dd><?php echo esc_html( $max_adults ); ?></dd>
								<?php } ?>
								<?php
									$max_kids = get_post_meta( $cabin_type_id, 'trav_cabin_max_kids', true );
									if ( ! empty( $max_kids ) ) {
								?>
									<dt><?php _e( 'Max Kids:', 'trav' ) ?>:</dt>
									<dd><?php echo esc_html( $max_kids ); ?></dd>
								<?php } ?>
								<?php
									$deck = get_post_meta( $cabin_type_id, 'trav_cabin_deck', true );
									if ( ! empty( $deck ) ) {
								?>
									<dt><?php _e( 'Deck:', 'trav' ) ?>:</dt>
									<dd><?php echo esc_html( $deck ); ?></dd>
								<?php } ?>
								<?php
									$size = get_post_meta( $cabin_type_id, 'trav_cabin_size', true );
									if ( ! empty( $size ) ) {
								?>
									<dt><?php _e( 'Size:', 'trav' ) ?>:</dt>
									<dd><?php echo esc_html( $size ); ?></dd>
								<?php } ?>                                    
                                </dl>
                            </div>
                            <div class="amenities">
                                <?php
									$facilities = wp_get_post_terms( $cabin_type_id, 'amenity' );
									$amenity_icons = get_option( "amenity_icon" );
									foreach ( $facilities as $facility ) {
										if ( is_array( $amenity_icons ) && isset( $amenity_icons[ $facility->term_id ] ) ) {
											if( isset( $amenity_icons[ $facility->term_id ]['uci'] ) ) {
												echo '<img alt="amenity-image" class="custom_amenity" title="' . esc_attr( $facility->name ) . '" src="' . esc_url( $amenity_icons[ $facility->term_id ]['url'] ) . '" height="28">';
											} else if ( isset( $amenity_icons[ $facility->term_id ]['icon'] ) ) {
												$_class = " circle";
												$_class = $amenity_icons[ $facility->term_id ]['icon'] . $_class;
												echo '<i class="' . esc_attr( $_class ) . '" title="' . esc_attr( $facility->name ) . '"></i>';
											}
											
										}										
									}
								?>
                            </div>
                        </div>
                        <div class="price-section">
							<span class="price">
								<?php if ( $type == 'available' ) { ?>
									<small>
										<?php if ( ( $number_of_days == 0 ) && ( $cabins == 0 ) ) { 
											echo __( 'PER/NIGHT', 'trav' );
										} else {
											echo esc_html( $number_of_days . ' ' . __( 'Nights', 'trav' ) ) . '<br />' . esc_html( $cabins . ' ' . __( 'Cabins', 'trav' ) );
										}?>
									</small>
									<?php echo esc_html( trav_get_price_field( $cabin_price ) );
								} ?>
							</span>
						</div>
                    </div>
                    <div>
						<div class="entry-content">
							<?php 
								$post = get_post( $cabin_type_id ); 
								$content = apply_filters('the_content', $post->post_content);
								echo wp_kses_post( $content );
								// echo do_shortcode( $content );
								// echo strip_shortcodes( $content );
							?>
						</div>
						<div class="action-section">
							<?php if ( $type == 'available' ) { ?>
								<button title="<?php _e( 'book now', 'trav') ?>" class="button btn-small full-width text-center btn-book-now" data-cabin-type-id="<?php echo esc_attr( $cabin_type_id ); ?>"><?php _e( 'BOOK NOW', 'trav') ?></button>
							<?php } elseif ( $type == 'all' ) { ?>
								<a href="#" title="<?php _e( 'show price', 'trav') ?>" class="button btn-small full-width text-center btn-show-price" data-cabin-type-id="<?php echo esc_attr( $cabin_type_id ); ?>"><?php _e( 'SHOW PRICE', 'trav') ?></a>
							<?php } elseif ( $type == 'not_available' ) { ?>
								<h4><?php echo __( 'Sold Out', 'trav' ) ?></h4>
							<?php } elseif ( $type == 'not_match' ) { ?>
								<h4><?php echo __( 'Exceeds Max Guests', 'trav' ) ?></h4>
							<?php } ?>
						</div>
					</div>
                </div>
            </article>
		<?php
	}
}

/*
 * Single Cruise Block HTML
 */
if ( ! function_exists( 'trav_cruise_get_cruise_list_sigle' ) ) {
	function trav_cruise_get_cruise_list_sigle( $cruise_id, $list_style, $before_article='', $after_article='', $show_badge=false, $animation='' ) {
		echo wp_kses_post( $before_article );
		// $cruise_id = trav_cruise_clang_id( $cruise_id );
		$avg_price = get_post_meta( $cruise_id, 'trav_cruise_avg_price', true );
		$review = get_post_meta( $cruise_id, 'review', true );
		$review = ( ! empty( $review ) )?round( $review, 1 ):0;
		$discount_rate = get_post_meta( $cruise_id, 'trav_cruise_discount_rate', true );
		$schedules = trav_cruise_get_schedules( $cruise_id );
		if ( $schedules ) {
	        $date = $schedules[0]['date_from'];
	        $arrival = $schedules[0]['arrival'];
	        $departure = $schedules[0]['departure'];
	        $duration = $schedules[0]['duration'];
	    }
	    $url = get_permalink( $cruise_id );

		if ( $list_style == "style1" || $list_style == "style2" ) { ?>
			<article class="box">
				<figure <?php echo wp_kses_post( $animation ) ?>>
					<a href="#" data-post_id="<?php echo esc_attr( $cruise_id ) ?>" class="hover-effect popup-gallery"><?php echo get_the_post_thumbnail( $cruise_id, 'biggallery-thumb' );  ?></a>
					<?php if ( $show_badge && ! empty( $discount_rate ) ) { ?>
						<span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
					<?php } ?>
				</figure>
				<div class="details">
					<?php if ( $list_style == "style1" ) { ?>
						<span class="price">
		                    <small><?php _e( 'avg/night', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $avg_price ) ); ?>
		                </span>
		                <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $cruise_id ) );?></a><?php echo trav_cruise_get_star_rating( $cruise_id ); ?><small><?php echo ( isset( $duration ) )?$duration . " " . __( 'nights', 'trav' ):""; ?></small></h4>
		                <div class="feedback">
		                    <div data-placement="bottom" data-toggle="tooltip" class="five-stars-container" title="<?php echo esc_attr( $review . ' ' . __( 'stars', 'trav' ) ) ?>"><span style="width: <?php echo esc_html( $review / 5 * 100 ) ?>%;" class="five-stars"></span></div>
		                    <span class="review"><?php echo esc_html( trav_get_review_count( $cruise_id ) . ' ' .  __('reviews', 'trav') ); ?></span>
		                </div>
		                <div class="row time">
		                    <div class="date col-xs-6">
		                        <i class="soap-icon-clock yellow-color"></i>
		                        <div>
		                            <span class="skin-color"><?php _e( 'Date', 'trav' ); ?></span><br /><?php echo ( isset( $date ) )?date( "M j, Y", trav_strtotime( $date ) ):""; ?>
		                        </div>
		                    </div>
		                    <div class="departure col-xs-6">
		                        <i class="soap-icon-departure yellow-color"></i>
		                        <div>
		                            <span class="skin-color"><?php _e( 'Departure', 'trav' ); ?></span><br /><?php echo ( isset( $departure ) )?$departure:""; ?>
		                        </div>
		                    </div>
		                </div>
		                <div class="action">
		                    <a title="<?php _e( 'View Detail', 'trav' ); ?>" class="button btn-small full-width" href="<?php echo esc_url( $url ); ?>"><?php _e( 'SELECT', 'trav' ); ?></a>
		                </div>
					<?php } elseif ( $list_style == "style2" ) { ?>
			                <a title="<?php _e( 'View Detail', 'trav' ); ?>" href="<?php echo esc_url( $url ); ?>" class="pull-right button uppercase"><?php _e( 'SELECT', 'trav' ); ?></a>
			                <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $cruise_id ) );?></a></h4>
			                <label class="price-wrapper"><span class="price-per-unit"><?php echo esc_html( trav_get_price_field( $avg_price ) ); ?></span><?php echo ( isset( $duration ) )?$duration . " " . __( 'nights', 'trav' ):""; ?></label>
					<?php } ?>
				</div>
			</article>

		<?php } else { ?>
			<article class="box">
	            <figure class="col-sm-5 col-md-4">
	                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $cruise_id );?>" href="#"><?php echo get_the_post_thumbnail( $cruise_id, 'gallery-thumb' ); ?></a>
	                <?php if ( $show_badge && ! empty( $discount_rate ) ) { ?>
	                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
	                <?php } ?>
	            </figure>
	            <div class="details col-sm-7 col-md-8">
	                <div class="clearfix">
	                    <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $cruise_id ) );?></a><?php echo trav_cruise_get_star_rating( $cruise_id ); ?><small><?php echo ( isset( $duration ) )?$duration . " " . __( 'nights', 'trav' ):""; ?></small></h4>
	                    <span class="price pull-right"><small><?php _e( 'avg/night', 'trav' ) ?></small><?php echo esc_html( trav_get_price_field( $avg_price ) ); ?></span>
	                </div>
	                <div class="character clearfix">
	                    <div class="col-xs-3 cruise-logo">
	                        <?php if ( isset( $cruise_logo ) ) { ?>
	                            <img width="110" height="25" src="<?php echo esc_url( wp_get_attachment_url( $cruise_logo ) );?>" alt="Cruise Logo" />
	                        <?php } ?>
	                    </div>
	                    <div class="col-xs-4 date">
	                        <i class="soap-icon-clock yellow-color"></i>
	                        <div>
	                            <span class="skin-color"><?php _e( 'Date', 'trav' ); ?></span><br /><?php echo ( isset( $date ) )?date( "M j, Y", trav_strtotime( $date ) ):""; ?>
	                        </div>
	                    </div>
	                    <div class="col-xs-5 departure">
	                        <i class="soap-icon-departure yellow-color"></i>
	                        <div>
	                            <span class="skin-color"><?php _e( 'Departure', 'trav' ); ?></span><br /><?php echo ( isset( $departure ) )?$departure:""; ?>
	                        </div>
	                    </div>
	                </div>
	                <div class="clearfix">
	                    <div class="review pull-left">
	                        <div data-placement="bottom" data-toggle="tooltip" class="five-stars-container" title="<?php echo esc_attr( $review . ' ' . __( 'stars', 'trav' ) ) ?>"><span style="width: <?php echo esc_html( $review / 5 * 100 ) ?>%;" class="five-stars"></span></div>
	                        <span class=""><?php echo esc_html( trav_get_review_count( $cruise_id ) . ' ' .  __('reviews', 'trav') ); ?></span>
	                    </div>
	                    <a href="<?php echo esc_url( $url ); ?>" class="button btn-small pull-right"><?php _e( 'select cruise', 'trav' ); ?></a>
	                </div>
	            </div>
	        </article>

		<?php } 
		echo wp_kses_post( $after_article );
	}
}