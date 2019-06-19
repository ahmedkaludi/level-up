<?php
/*
 * Accommodation Detail
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $acc_id, $room_type_id;

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';
$city = trav_acc_get_city( $acc_id );
$country = trav_acc_get_country( $acc_id );

if ( ! empty( $acc_id ) ) : ?>

    <h3><?php echo __( 'Accommodation Details', 'trav' ) ?></h3>

    <h4><a href="<?php echo esc_url( get_permalink( $acc_id ) ); ?>"><?php echo esc_html( get_the_title( $acc_id ) ) ?></a></h4>
    <dl class="term-description">
        <?php
        $acc_meta = get_post_meta( $acc_id );
        $acc_detail_fields = array( 
            'star_rating' => array( 'label' => __('Rating Stars', 'trav'), 'pre' => '', 'sur' => ' ' . __( 'star', 'trav') ),
            'charge_extra_people' => array( 'label' => __('Extra people', 'trav'), 'pre' => '', 'sur' => '' ),
            'minimum_stay' => array( 'label' => __('Minimum Stay', 'trav'), 'pre' => '', 'sur' => '' ),
            'security_deposit' => array( 'label' => __('Security Deposit', 'trav'), 'pre' => '', 'sur' => ' ' . '%' ),
            'country' => array( 'label' => __('Country', 'trav'), 'pre' => '', 'sur' => '' ),
            'city' => array( 'label' => __('City', 'trav'), 'pre' => '', 'sur' => '' ),
            'address' => array( 'label' => __('Address', 'trav'), 'pre' => '', 'sur' => '' ),
            'phone' => array( 'label' => __('Phone No', 'trav'), 'pre' => '', 'sur' => '' ),
            'neighborhood' => array( 'label' => __('Neighborhood', 'trav'), 'pre' => '', 'sur' => '' ),
            'cancellation' => array( 'label' => __('Cancellation', 'trav'), 'pre' => '', 'sur' => '' ),
        );

        foreach ( $acc_detail_fields as $field => $value ) {
            if ( empty( $$field ) ) {
                $$field = empty( $acc_meta["trav_accommodation_$field"] ) ? '' : $acc_meta["trav_accommodation_$field"][0];
            }
            if ( ! empty( $$field ) ) {
                $content = $value['pre'] . $$field . $value['sur'];
                echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
            }
        } 
        ?>
    </dl>
    <hr />

    <?php if ( ! empty( $room_type_id ) ) : ?>

        <h4><a href="<?php echo esc_url( get_permalink( $room_type_id ) ); ?>"><?php echo esc_html( get_the_title( $room_type_id ) ) ?></a></h4>
        <dl class="term-description">
            <?php
            $room_meta = get_post_meta( $room_type_id );
            $room_detail_fields = array( 
                'max_adults' => __('Max Adults', 'trav'),
                'max_kids' => __('Max Children', 'trav'),
            );

            foreach ( $room_detail_fields as $field => $label ) {
                $$field = empty( $room_meta["trav_room_$field"] ) ? '' : $room_meta["trav_room_$field"][0];
                if ( ! empty( $$field ) ) {
                    echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
                }
            }
            ?>
        </dl>

    <?php 
    endif;
endif;