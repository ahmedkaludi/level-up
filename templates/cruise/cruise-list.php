<?php
/*
 * Cruise List
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $cruise_list, $current_view, $before_article, $after_article;

foreach( $cruise_list as $cruise_id ) {
    $cruise_id = trav_cruise_clang_id( $cruise_id );
    $avg_price = get_post_meta( $cruise_id, 'trav_cruise_avg_price', true );
    $review = get_post_meta( trav_cruise_org_id( $cruise_id ), 'review', true );
    $review = ( ! empty( $review ) )?round( $review, 1 ):0;
    $discount_rate = get_post_meta( $cruise_id, 'trav_cruise_discount_rate', true );
    $cruise_logo = get_post_meta( $cruise_id, 'trav_cruise_logo', true );
    $brief = get_post_meta( $cruise_id, 'trav_cruise_brief', true );
    if ( empty( $brief ) ) {
        $brief = apply_filters('the_content', get_post_field('post_content', $cruise_id));
        $brief = wp_trim_words( $brief, 20, '' );
    }

    $date_from = isset( $_REQUEST['date_from'] ) ? trav_sanitize_date( $_REQUEST['date_from'] ) : '';
    $date_to = isset( $_REQUEST['date_to'] ) ? trav_sanitize_date( $_REQUEST['date_to'] ) : '';

    if ( ! empty( $date_from ) && ! empty( $date_to ) && trav_strtotime( $date_from ) >= trav_strtotime( $date_to ) ) {
        $date_from = '';
        $date_to = '';
    } else {
        if ( ! empty( $date_from ) ) {
            $date_from = trav_tosqltime( $date_from );
        }
        if ( ! empty( $date_to ) ) {
            $date_to = trav_tosqltime( $date_to);
        }
    }

    $query_args = array(
        'date_from' => $date_from,
        'date_to' => $date_to
    );
    $url = esc_url( add_query_arg( $query_args, get_permalink( $cruise_id ) ) );
    $schedules = trav_cruise_get_schedules( $cruise_id, $date_from );
    if ( $schedules ) {
        $date = $schedules[0]['date_from'];
        $arrival = $schedules[0]['arrival'];
        $departure = $schedules[0]['departure'];
        $duration = $schedules[0]['duration'];
    }

    echo ( $before_article );

    if ( $current_view == 'block' ) { ?>

        <article class="box">
            <figure>
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $cruise_id );?>" href="#"><?php echo get_the_post_thumbnail( $cruise_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>
            <div class="details">
                <a title="<?php _e( 'View Detail', 'trav' ); ?>" href="<?php echo esc_url( $url ); ?>" class="pull-right button uppercase"><?php _e( 'SELECT', 'trav' ); ?></a>
                <h4 class="box-title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $cruise_id ) );?></a></h4>
                <label class="price-wrapper"><span class="price-per-unit"><?php echo esc_html( trav_get_price_field( $avg_price ) ); ?></span><?php echo ( isset( $duration ) )?$duration . " " . __( 'nights', 'trav' ):""; ?></label>
            </div>
        </article>

    <?php } elseif ( $current_view == 'grid' ) { ?>

        <article class="box">
            <figure>
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $cruise_id );?>" href="#"><?php echo get_the_post_thumbnail( $cruise_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>
            <div class="details">
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
            </div>
        </article>


    <?php } else { ?>

        <article class="box">
            <figure class="col-sm-5 col-md-4">
                <a title="<?php _e( 'View Photo Gallery', 'trav' ); ?>" class="hover-effect popup-gallery" data-post_id="<?php echo esc_attr( $cruise_id );?>" href="#"><?php echo get_the_post_thumbnail( $cruise_id, 'gallery-thumb' ); ?></a>
                <?php if ( ! empty( $discount_rate ) ) { ?>
                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate . '%' . ' ' . __( 'Discount', 'trav' ) ); ?></span></span>
                <?php } ?>
            </figure>
            <div class="details col-sm-7 col-md-8">
                <div class="clearfix">
                    <h4 class="box-title pull-left"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $cruise_id ) );?></a><?php echo trav_cruise_get_star_rating( $cruise_id ); ?><small><?php echo ( isset( $duration ) )?$duration . " " . __( 'nights', 'trav' ):""; ?></small></h4>
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
    
    echo ( $after_article );
}