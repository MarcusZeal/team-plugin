<?php
/**
 * Core plugin bootstrap
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class TP_Plugin {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Register CPT/Tax and ACF fields.
        add_action( 'init', [ 'TP_Post_Types', 'register_all' ] );
        add_action( 'acf/init', [ 'TP_ACF_Fields', 'register_field_groups' ] );

        // Assets and shortcode.
        add_action( 'wp_enqueue_scripts', [ 'TP_Assets', 'register' ] );
        add_shortcode( 'team_grid', [ 'TP_Shortcode', 'render' ] );

        // Footer modal output after all content.
        add_action( 'wp_footer', [ 'TP_Modal_Registry', 'print_all' ], 1000 );
    }
}

