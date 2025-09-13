<?php
/**
 * Register Custom Post Types and Taxonomies
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class TP_Post_Types {
    public static function register_all() {
        self::register_cpt();
        self::register_taxonomies();
    }

    private static function labels( $singular, $plural ) {
        return [
            'name'               => $plural,
            'singular_name'      => $singular,
            'menu_name'          => $plural,
            'name_admin_bar'     => $singular,
            'add_new'            => __( 'Add New', 'team-plugin' ),
            'add_new_item'       => sprintf( __( 'Add New %s', 'team-plugin' ), $singular ),
            'new_item'           => sprintf( __( 'New %s', 'team-plugin' ), $singular ),
            'edit_item'          => sprintf( __( 'Edit %s', 'team-plugin' ), $singular ),
            'view_item'          => sprintf( __( 'View %s', 'team-plugin' ), $singular ),
            'all_items'          => sprintf( __( 'All %s', 'team-plugin' ), $plural ),
            'search_items'       => sprintf( __( 'Search %s', 'team-plugin' ), $plural ),
            'not_found'          => __( 'Not found', 'team-plugin' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'team-plugin' ),
        ];
    }

    public static function register_cpt() {
        $args = [
            'label'               => __( 'Team Member', 'team-plugin' ),
            'labels'              => self::labels( __( 'Team Member', 'team-plugin' ), __( 'Team', 'team-plugin' ) ),
            'public'              => true,
            'show_in_rest'        => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-groups',
            'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ],
            'has_archive'         => false,
            'rewrite'             => [ 'slug' => 'people' ],
        ];

        if ( function_exists( 'acf_register_post_type' ) ) {
            acf_register_post_type( array_merge( $args, [ 'post_type' => 'tp_person' ] ) );
        } else {
            register_post_type( 'tp_person', $args );
        }
    }

    public static function register_taxonomies() {
        // Role taxonomy (Investors, Early Stage, Late Stage, Specialists)
        $role_args = [
            'labels'            => self::labels( __( 'Role', 'team-plugin' ), __( 'Roles', 'team-plugin' ) ),
            'public'            => true,
            'show_in_rest'      => true,
            'hierarchical'      => true,
            'rewrite'           => [ 'slug' => 'team-role' ],
        ];

        // Location taxonomy (Global, Bay Area, London, etc.)
        $loc_args = [
            'labels'            => self::labels( __( 'Location', 'team-plugin' ), __( 'Locations', 'team-plugin' ) ),
            'public'            => true,
            'show_in_rest'      => true,
            'hierarchical'      => false,
            'rewrite'           => [ 'slug' => 'team-location' ],
        ];

        // Focus taxonomy (AI, Cloud/SaaS, Security)
        $focus_args = [
            'labels'            => self::labels( __( 'Focus', 'team-plugin' ), __( 'Focus Areas', 'team-plugin' ) ),
            'public'            => true,
            'show_in_rest'      => true,
            'hierarchical'      => false,
            'rewrite'           => [ 'slug' => 'team-focus' ],
        ];

        if ( function_exists( 'acf_register_taxonomy' ) ) {
            acf_register_taxonomy( array_merge( $role_args, [ 'taxonomy' => 'tp_role', 'object_type' => [ 'tp_person' ] ] ) );
            acf_register_taxonomy( array_merge( $loc_args, [ 'taxonomy' => 'tp_location', 'object_type' => [ 'tp_person' ] ] ) );
            acf_register_taxonomy( array_merge( $focus_args, [ 'taxonomy' => 'tp_focus', 'object_type' => [ 'tp_person' ] ] ) );
        } else {
            register_taxonomy( 'tp_role', [ 'tp_person' ], $role_args );
            register_taxonomy( 'tp_location', [ 'tp_person' ], $loc_args );
            register_taxonomy( 'tp_focus', [ 'tp_person' ], $focus_args );
        }
    }
}

