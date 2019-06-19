<?php
/*
 * Tour Detail
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $tour_id, $st_id;

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
$city = trav_tour_get_city( $tour_id );
$country = trav_tour_get_country( $tour_id );

if ( ! empty( $tour_id ) ) : ?>

    <h3><?php echo __( 'Tour Details', 'trav' ) ?></h3>

    <h4><a href="<?php echo esc_url( get_permalink( $tour_id ) ); ?>"><?php echo esc_html( get_the_title( $tour_id ) ) ?></a></h4>

    <dl class="term-description">
        <?php
        $tour_meta = get_post_meta( $tour_id );
        $tour_detail_fields = array( 
            'security_deposit' => array( 'label' => __('Security Deposit', 'trav'), 'pre' => '', 'sur' => ' ' . '%' ),
            'country' => array( 'label' => __('Country', 'trav'), 'pre' => '', 'sur' => '' ),
            'city' => array( 'label' => __('City', 'trav'), 'pre' => '', 'sur' => '' ),
            'address' => array( 'label' => __('Address', 'trav'), 'pre' => '', 'sur' => '' ),
            'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
            'cancellation' => array( 'label' => __('Cancellation', 'trav'), 'pre' => '', 'sur' => '' ),
        );

        foreach ( $tour_detail_fields as $field => $value ) {
            if ( empty( $$field ) ) {
                $$field = empty( $tour_meta["trav_tour_$field"] )?'':$tour_meta["trav_tour_$field"][0];
            }
            if ( ! empty( $$field ) ) {
                $content = $value['pre'] . $$field . $value['sur'];
                echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
            }
        } ?>
    </dl>
    <hr />

    <?php if ( ! empty( $st_id ) ) : ?>

        <h4><?php echo esc_html( trav_tour_get_schedule_type_title( $tour_id, $st_id ) ) ?></h4>

        <dl class="term-description">
            <?php
            $st_data = trav_tour_get_schedule_type_data( $tour_id, $st_id );

            if ( ! empty( $st_data ) ) {
                $tour_detail_fields = array(
                    'description' => __('Description', 'trav'),
                    'time' => __('Time', 'trav'),
                );

                foreach ( $tour_detail_fields as $field => $label ) {
                    $$field = empty( $st_data["$field"] )?'':$st_data["$field"];
                    if ( ! empty( $$field ) ) {
                        echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
                    }
                }
            }

            ?>
        </dl>

    <?php 
    endif;
endif;