<?php
/**
 * Define ACF field groups for Team Person
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class TP_ACF_Fields {
    public static function register_field_groups() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return; // ACF not active.
        }

        acf_add_local_field_group( [
            'key' => 'group_tp_person_details',
            'title' => __( 'Person Details', 'team-plugin' ),
            'fields' => [
                [
                    'key' => 'field_tp_title',
                    'label' => __( 'Title / Role Label', 'team-plugin' ),
                    'name' => 'tp_title',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_tp_specialty',
                    'label' => __( 'Specialty', 'team-plugin' ),
                    'name' => 'tp_specialty',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_tp_headshot',
                    'label' => __( 'Headshot', 'team-plugin' ),
                    'name' => 'tp_headshot',
                    'type' => 'image',
                    'return_format' => 'id',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ],
                [
                    'key' => 'field_tp_short_bio',
                    'label' => __( 'Short Intro', 'team-plugin' ),
                    'name' => 'tp_intro',
                    'type' => 'textarea',
                    'rows' => 2,
                ],
                [
                    'key' => 'field_tp_long_bio',
                    'label' => __( 'Biography', 'team-plugin' ),
                    'name' => 'tp_bio',
                    'type' => 'wysiwyg',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 0,
                ],
                [
                    'key' => 'field_tp_social_repeater',
                    'label' => __( 'Social Links', 'team-plugin' ),
                    'name' => 'tp_social',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => __( 'Add Link', 'team-plugin' ),
                    'sub_fields' => [
                        [
                            'key' => 'field_tp_social_platform',
                            'label' => __( 'Platform', 'team-plugin' ),
                            'name' => 'platform',
                            'type' => 'select',
                            'choices' => [
                                'linkedin'   => 'LinkedIn',
                                'x'          => 'X (Twitter)',
                                'twitter'    => 'Twitter',
                                'github'     => 'GitHub',
                                'dribbble'   => 'Dribbble',
                                'medium'     => 'Medium',
                                'website'    => 'Website',
                                'email'      => 'Email',
                                'facebook'   => 'Facebook',
                                'instagram'  => 'Instagram',
                                'youtube'    => 'YouTube',
                                'vimeo'      => 'Vimeo',
                                'tiktok'     => 'TikTok',
                                'threads'    => 'Threads',
                                'angellist'  => 'AngelList',
                            ],
                            'ui' => 1,
                            'return_format' => 'value',
                        ],
                        [
                            'key' => 'field_tp_social_label',
                            'label' => __( 'Label (optional)', 'team-plugin' ),
                            'name' => 'label',
                            'type' => 'text',
                        ],
                        [
                            'key' => 'field_tp_social_url',
                            'label' => __( 'URL', 'team-plugin' ),
                            'name' => 'url',
                            'type' => 'url',
                            'conditional_logic' => [
                                [
                                    [ 'field' => 'field_tp_social_platform', 'operator' => '!=', 'value' => 'email' ],
                                ],
                            ],
                        ],
                        [
                            'key' => 'field_tp_social_email',
                            'label' => __( 'Email', 'team-plugin' ),
                            'name' => 'email',
                            'type' => 'email',
                            'conditional_logic' => [
                                [
                                    [ 'field' => 'field_tp_social_platform', 'operator' => '==', 'value' => 'email' ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'tp_person',
                    ],
                ],
            ],
            'position' => 'acf_after_title',
            'style' => 'default',
            'active' => true,
        ] );
    }
}
