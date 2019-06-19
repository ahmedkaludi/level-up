<?php
/*
 * Cruise Detail
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $cruise_id, $cabin_type_id;

$dt_dd = '<dt>%s:</dt><dd>%s</dd>';

if ( ! empty( $cruise_id ) ) : ?>

    <h3><?php echo __( 'Cruise Details', 'trav' ) ?></h3>

    <h4><a href="<?php echo esc_url( get_permalink( $cruise_id ) ); ?>"><?php echo esc_html( get_the_title( $cruise_id ) ) ?></a></h4>
    <dl class="term-description">
        <?php
        $cruise_meta = get_post_meta( $cruise_id );
        $cruise_detail_fields = array( 
            'charge_extra_people' => array( 'label' => __('Extra people', 'trav'), 'pre' => '', 'sur' => '' ),
            'security_deposit' => array( 'label' => __('Security Deposit', 'trav'), 'pre' => '', 'sur' => ' ' . '%' ),
            'cancellation' => array( 'label' => __('Cancellation', 'trav'), 'pre' => '', 'sur' => '' ),
        );

        foreach ( $cruise_detail_fields as $field => $value ) {
            if ( empty( $$field ) ) {
                $$field = empty( $cruise_meta["trav_cruise_$field"] ) ? '' : $cruise_meta["trav_cruise_$field"][0];
            }
            if ( ! empty( $$field ) ) {
                $content = $value['pre'] . $$field . $value['sur'];
                echo sprintf( $dt_dd, esc_html( $value['label'] ), esc_html( $content ) );
            }
        } 
        ?>
    </dl>
    <hr />

    <?php if ( ! empty( $cabin_type_id ) ) : ?>

        <h4><a href="<?php echo esc_url( get_permalink( $cabin_type_id ) ); ?>"><?php echo esc_html( get_the_title( $cabin_type_id ) ) ?></a></h4>
        <dl class="term-description">
            <?php
            $cabin_meta = get_post_meta( $cabin_type_id );
            $cabin_detail_fields = array( 
                'max_adults' => __('Max Adults', 'trav'),
                'max_kids' => __('Max Children', 'trav'),
            );

            foreach ( $cabin_detail_fields as $field => $label ) {
                $$field = empty( $cabin_meta["trav_cabin_$field"] ) ? '' : $cabin_meta["trav_cabin_$field"][0];
                if ( ! empty( $$field ) ) {
                    echo sprintf( $dt_dd, esc_html( $label ), esc_html( $$field ) );
                }
            }
            ?>
        </dl>

    <?php 
    endif;
endif;