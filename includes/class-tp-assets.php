<?php
/**
 * Register/enqueue plugin assets
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class TP_Assets {
    public static function register() {
        $css = TP_PLUGIN_URL . 'assets/css/team-plugin.css';
        $js  = TP_PLUGIN_URL . 'assets/js/team-plugin.js';
        wp_register_style( 'tp-team', $css, [], TP_PLUGIN_VERSION );
        wp_register_script( 'tp-team', $js, [ 'wp-util' ], TP_PLUGIN_VERSION, true );

        // Font Awesome (CDN by default, overrideable).
        $fa_src = apply_filters( 'tp/fontawesome_src', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css' );
        wp_register_style( 'tp-fontawesome', $fa_src, [], '6.5.2' );
    }

    /**
     * Enqueue Font Awesome only if a common FA handle is not already present.
     */
    public static function ensure_fontawesome() {
        $known = [
            'font-awesome', 'fontawesome', 'fa', 'fontawesome-all', 'fontawesome-free',
            'tp-fontawesome', 'et_font_awesome', 'elementor-icons-fa-solid', 'elementor-icons-fa-brands', 'elementor-icons-fa-regular'
        ];
        foreach ( $known as $handle ) {
            // Only skip if a matching handle is actually enqueued or already printed.
            if ( wp_style_is( $handle, 'enqueued' ) || wp_style_is( $handle, 'done' ) ) {
                return; // Something FA-like is present and active.
            }
        }
        if ( apply_filters( 'tp/enqueue_fontawesome', true ) ) {
            wp_enqueue_style( 'tp-fontawesome' );
        }
    }
}
