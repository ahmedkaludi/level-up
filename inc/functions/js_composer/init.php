<?php

/**
 * Initialize Visual Composer
 */

if ( class_exists( 'Vc_Manager', false ) ) {

    if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
        vc_set_shortcodes_templates_dir( LEVELUP_INC_DIR . '/functions/js_composer/vc_templates' );
    }

    add_action( 'vc_before_init', 'trav_vcSetAsTheme' );

    function trav_vcSetAsTheme() {
        vc_set_as_theme(true);
    }

    add_action( 'vc_before_init', 'trav_load_js_composer' );

    function trav_load_js_composer() {
        require_once LEVELUP_INC_DIR . '/functions/js_composer/js_composer.php';
    }

    add_action( 'vc_after_init', 'trav_vc_enable_deprecated_shortcodes' );

    function trav_vc_enable_deprecated_shortcodes() { 
        if ( class_exists( 'WPBMap' ) ) { 
            $category = __('by SoapTheme', 'trav');

            WPBMap::modify( 'vc_accordion', 'deprecated', false );
            WPBMap::modify( 'vc_accordion', 'category', $category );
            WPBMap::modify( 'vc_accordion', 'name', __('Trav Accordion', 'trav') );
            WPBMap::modify( 'vc_accordion_tab', 'name', __('Section', 'trav') );

            WPBMap::modify( 'vc_tabs', 'deprecated', false );
            WPBMap::modify( 'vc_tabs', 'category', $category );
            WPBMap::modify( 'vc_tabs', 'name', __('Trav Tabs', 'trav') );
            WPBMap::modify( 'vc_tab', 'name', __('Tab', 'trav') );
        }
    }

    add_action( 'vc_after_init', 'trav_disable_frontend_editor' );

    function trav_disable_frontend_editor() { 
        if ( function_exists( 'vc_disable_frontend' ) ) :
          vc_disable_frontend();
        endif;
    }
}

