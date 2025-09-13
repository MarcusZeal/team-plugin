<?php
/**
 * Shortcode implementation
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class TP_Shortcode {
    public static function render( $atts, $content = null ) {
        $atts = shortcode_atts( [
            'id'               => uniqid( 'tp_', false ),
            'columns'          => 3,
            'orderby'          => 'menu_order',
            'order'            => 'ASC',
            'posts_per_page'   => -1,
            'role_filter'      => 'true',
            'role_taxonomy'    => 'tp_role',
            'tab_taxonomy'     => '', // optional, e.g. tp_location
            'focus_taxonomy'   => 'tp_focus', // used for chips in UI
            'show_search'      => 'false',
            'include'          => '', // comma-separated IDs
            'exclude'          => '',
        ], $atts, 'team_grid' );

        // Query posts.
        $args = [
            'post_type'      => 'tp_person',
            'orderby'        => sanitize_text_field( $atts['orderby'] ),
            'order'          => sanitize_text_field( $atts['order'] ),
            'posts_per_page' => intval( $atts['posts_per_page'] ),
        ];

        if ( ! empty( $atts['include'] ) ) {
            $args['post__in'] = array_map( 'intval', array_filter( array_map( 'trim', explode( ',', $atts['include'] ) ) ) );
        }
        if ( ! empty( $atts['exclude'] ) ) {
            $args['post__not_in'] = array_map( 'intval', array_filter( array_map( 'trim', explode( ',', $atts['exclude'] ) ) ) );
        }

        $q = new WP_Query( $args );

        if ( ! $q->have_posts() ) {
            ob_start();
            TP_Template_Loader::locate( 'no-results.php' );
            return ob_get_clean();
        }

        // Enqueue assets for front-end use.
        wp_enqueue_style( 'tp-team' );
        wp_enqueue_script( 'tp-team' );
        if ( class_exists( 'TP_Assets' ) ) { TP_Assets::ensure_fontawesome(); }

        // Build a grid and collect modals to render in footer.
        $grid_id = sanitize_html_class( $atts['id'] );
        $columns = max( 1, intval( $atts['columns'] ) );
        $role_tax = sanitize_key( $atts['role_taxonomy'] );
        $tab_tax  = $atts['tab_taxonomy'] ? sanitize_key( $atts['tab_taxonomy'] ) : '';
        $focus_tax= sanitize_key( $atts['focus_taxonomy'] );

        ob_start();
        echo '<div class="tp-grid-wrapper tp-theme" id="' . esc_attr( $grid_id ) . '" data-columns="' . esc_attr( $columns ) . '" data-role-tax="' . esc_attr( $role_tax ) . '"' . ( $tab_tax ? ' data-tab-tax="' . esc_attr( $tab_tax ) . '"' : '' ) . ' data-focus-tax="' . esc_attr( $focus_tax ) . '">';

        // Filters (role) and tabs (optional location)
        TP_Template_Loader::locate( 'filter-bar.php', [
            'grid_id'    => $grid_id,
            'role_tax'   => $role_tax,
            'tab_tax'    => $tab_tax,
        ] );

        echo '<div class="tp-grid" role="list" aria-live="polite">';

        $order_ids = [];
        while ( $q->have_posts() ) { $q->the_post();
            $post_id = get_the_ID();
            $order_ids[] = $post_id;

            // Collect term slugs for filters.
            $role_slugs   = wp_get_post_terms( $post_id, $role_tax, [ 'fields' => 'slugs' ] );
            $tab_slugs    = $tab_tax ? wp_get_post_terms( $post_id, $tab_tax, [ 'fields' => 'slugs' ] ) : [];
            $focus_slugs  = $focus_tax ? wp_get_post_terms( $post_id, $focus_tax, [ 'fields' => 'slugs' ] ) : [];

            // Register modal to render in footer.
            TP_Modal_Registry::add( $post_id, [
                'index'      => count( $order_ids ) - 1,
                'focus'      => $focus_slugs,
                'role'       => $role_slugs,
                'tabs'       => $tab_slugs,
                'grid_id'    => $grid_id,
            ] );

            TP_Template_Loader::locate( 'card.php', [
                'post_id'     => $post_id,
                'grid_id'     => $grid_id,
                'role_slugs'  => $role_slugs,
                'tab_slugs'   => $tab_slugs,
                'focus_slugs' => $focus_slugs,
            ] );
        }
        wp_reset_postdata();

        echo '</div>'; // .tp-grid
        echo '</div>'; // .tp-grid-wrapper

        // Provide order to JS specific to this grid instance, in case multiple on page.
        wp_add_inline_script( 'tp-team', 'window.TP_TEAM_ORDER_MAP = window.TP_TEAM_ORDER_MAP || {}; window.TP_TEAM_ORDER_MAP[' . wp_json_encode( $grid_id ) . '] = ' . wp_json_encode( array_values( $order_ids ) ) . ';', 'before' );

        return ob_get_clean();
    }
}
