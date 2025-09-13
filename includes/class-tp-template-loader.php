<?php
/**
 * Simple template loader with theme overrides
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class TP_Template_Loader {
    public static function locate( $template, $args = [] ) {
        $slug = trim( $template, '/' );
        $paths = [
            trailingslashit( get_stylesheet_directory() ) . 'team-plugin/' . $slug,
            trailingslashit( get_template_directory() ) . 'team-plugin/' . $slug,
            TP_PLUGIN_DIR . 'templates/' . $slug,
        ];

        $found = '';
        foreach ( $paths as $path ) {
            if ( file_exists( $path ) ) {
                $found = $path;
                break;
            }
        }

        if ( $found ) {
            if ( ! empty( $args ) ) {
                extract( $args, EXTR_SKIP );
            }
            include $found;
        }
    }
}

