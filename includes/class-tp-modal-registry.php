<?php
/**
 * Collect and print modals in footer so they are outside builder wrappers
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class TP_Modal_Registry {
    protected static $modals = [];

    public static function add( $post_id, $args = [] ) {
        self::$modals[ $post_id ] = $args;
    }

    public static function reset() {
        self::$modals = [];
    }

    public static function print_all() {
        if ( empty( self::$modals ) ) {
            return;
        }
        wp_enqueue_style( 'tp-team' );
        wp_enqueue_script( 'tp-team' );
        TP_Assets::ensure_fontawesome();

        echo '<div class="tp-modals-root tp-theme" aria-live="polite">';
        foreach ( self::$modals as $post_id => $data ) {
            TP_Template_Loader::locate( 'modal.php', [ 'post_id' => $post_id, 'data' => $data ] );
        }
        echo '</div>';

        // Emit a small inline script with ordering so JS can navigate next/prev.
        $order = array_keys( self::$modals );
        wp_add_inline_script( 'tp-team', 'window.TP_TEAM_ORDER = ' . wp_json_encode( array_values( $order ) ) . ';', 'before' );
    }
}
