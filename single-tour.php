<?php
/*
 * Single Tour Page Template
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

get_header();

if ( have_posts() ) {
    while ( have_posts() ) : the_post();

        //init variables
        $tour_id = get_the_ID();
        $city = trav_tour_get_city( $tour_id );
        $country = trav_tour_get_country( $tour_id );

        $date_from = ( isset( $_GET['date_from'] ) ) ? trav_tophptime( $_GET['date_from'] ) : date( trav_get_date_format('php') );
        $date_to = ( isset( $_GET['date_to'] ) ) ? trav_tophptime( $_GET['date_to'] ) : date( trav_get_date_format('php'), trav_strtotime( $date_from ) + 86400 * 30 );
        $repeated = get_post_meta( $tour_id, 'trav_tour_repeated', true );
        $multi_book = get_post_meta( $tour_id, 'trav_tour_multi_book', true );
        $isv_setting = get_post_meta( $tour_id, 'trav_post_media_type', true );
        $discount = get_post_meta( $tour_id, 'trav_tour_hot', true );
        $discount_rate = get_post_meta( $tour_id, 'trav_tour_discount_rate', true );
        $sc_list_pos = get_post_meta( $tour_id, 'trav_tour_sl_first', true );

        $schedule_types = trav_tour_get_schedule_types( $tour_id );

        // add to user recent activity
        trav_update_user_recent_activity( $tour_id ); ?>

        <section id="content">
            <div class="container tour-detail-page">
                <div class="row">
                    <div id="main" class="col-sm-8 col-md-9">
                        <div <?php post_class(); ?>>
                            <div class="image-box">
                                <?php if ( ! empty( $discount ) && ! empty( $discount_rate ) ) : ?>
                                    <span class="discount"><span class="discount-text"><?php echo esc_html( $discount_rate ) . '% ' . __('Discount', 'trav') ?></span></span>
                                <?php endif; ?>
                                <?php trav_post_gallery( $tour_id ) ?>
                            </div>

                            <div id="tour-details" class="travelo-box">
                                <?php if ( ! empty( $repeated ) ): ?>
                                    <form id="check_availability_form" method="post">
                                        <input type="hidden" name="tour_id" value="<?php echo esc_attr( $tour_id ); ?>">
                                        <input type="hidden" name="action" value="tour_get_available_schedules">
                                        <?php wp_nonce_field( 'post-' . $tour_id, '_wpnonce', false ); ?>
                                        <div class="update-search clearfix">
                                            <div class="alert alert-error" style="display:none;"><span class="message"><?php _e( 'Please select check in date.','trav' ); ?></span><span class="close"></span></div>
                                            <h4><?php _e( 'Check Availability', 'trav' ) ?></h4>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <label><?php _e( 'From','trav' ); ?></label>
                                                        <div class="datepicker-wrap validation-field from-today">
                                                            <input name="date_from" type="text" placeholder="<?php echo trav_get_date_format('html'); ?>" class="input-text full-width" value="<?php echo $date_from; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <label><?php _e( 'To','trav' ); ?></label>
                                                        <div class="datepicker-wrap validation-field from-today">
                                                            <input name="date_to" type="text" placeholder="<?php echo trav_get_date_format('html'); ?>" class="input-text full-width" value="<?php echo $date_to;?>" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="visible-md visible-lg">&nbsp;</label>
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <button id="check_availability" data-animation-duration="1" data-animation-type="bounce" class="full-width icon-check animated bounce" type="submit"><?php _e( "UPDATE", "trav" ); ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>

                                <?php if ( empty( $sc_list_pos ) ) : ?>

                                    <div class="entry-content"><?php the_content(); ?></div>
                                    <div id="schedule-list">
                                        <?php trav_tour_get_schedule_list_html( array( 'tour_id'=>$tour_id, 'date_from'=>$date_from, 'date_to'=>$date_to ) ); ?>
                                    </div>

                                <?php else : ?>

                                    <div id="schedule-list">
                                        <?php trav_tour_get_schedule_list_html( array( 'tour_id'=>$tour_id, 'date_from'=>$date_from, 'date_to'=>$date_to ) ); ?>
                                    </div>
                                    <div class="entry-content"><?php the_content(); ?></div>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <div class="sidebar col-sm-4 col-md-3">
                        <?php generated_dynamic_sidebar(); ?>
                    </div>
                </div>
            </div>
        </section>

    <?php 
    endwhile;
}

get_footer();