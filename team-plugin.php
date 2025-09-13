<?php
/**
 * Plugin Name: Team People Grid (ACF)
 * Description: Sortable/filterable team grid with hover overlays and footer-rendered modals. Built to integrate with ACF Pro.
 * Version: 0.1.0
 * Author: Your Team
 * Text Domain: team-plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants.
define( 'TP_PLUGIN_FILE', __FILE__ );
define( 'TP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TP_PLUGIN_VERSION', '0.1.0' );

// Simple autoloader for plugin classes.
spl_autoload_register( function ( $class ) {
    if ( 0 !== strpos( $class, 'TP_' ) ) {
        return;
    }
    $file = TP_PLUGIN_DIR . 'includes/class-' . strtolower( str_replace( '_', '-', $class ) ) . '.php';
    if ( file_exists( $file ) ) {
        require_once $file;
    }
} );

// Bootstrap plugin on plugins_loaded to ensure ACF is available if present.
add_action( 'plugins_loaded', function() {
    // Core bootstrap.
    TP_Plugin::instance();
} );

// Activation: flush rewrite rules.
register_activation_hook( __FILE__, function() {
    // Ensure CPT/Tax are registered before flush.
    TP_Post_Types::register_all();
    flush_rewrite_rules();
} );

// Deactivation: flush rewrite rules.
register_deactivation_hook( __FILE__, function() {
    flush_rewrite_rules();
} );

