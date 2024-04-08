<?php

function Arrival_Post_Type()
{

    $supports = [
        'title',
    ];

    $labels = [
        'name'                        =>     _x('Tilkomstruter', 'plural'),
        'singular_name'               =>     _x('Tilkomstrute', 'singular'),
        'menu_name'                   =>     _x('Tilkomstrute', 'admin menu'),
        'name_admin_bar'              =>     _x('Tilkomstrute', 'admin bar'),
        'featured_image'              =>     __('Fremhevet bilde'),
        'set_featured_image'          =>     __('Bestem fremhevet bilde'),
        'remove_featured_image'       =>     __('Fjern fremhevet bilde'),
        'add_new'                     =>     _x('Legg til', 'add new'),
        'add_new_item'                =>     __('Legg til ny tilkomstrute'),
        'new_item'                    =>     __('Ny tilkomstrute'),
        'edit_item'                   =>     __('Rediger tilkomstrute'),
        'view_item'                   =>     __('Se tilkomstrute'),
        'all_items'                   =>     __('Tilkomstruter'),
        'search_items'                =>     __('Søk i tilkomstruter'),
        'not_found'                   =>     __('Ingen tilkomstruter funnet.'),
    ];

    $args = [
        'labels'                   =>     $labels,
        'supports'                 =>     $supports,
        'public'                   =>     true,
        'publicly_queryable'       =>     true,
        'exclude_from_search'      =>     false,
        'can_export'               =>     true,
        'show_ui'                  =>     true,
        'show_in_menu'             =>     'hubriding',
        'show_in_rest'             =>     true,
        'menu_position'            =>     5,
        'query_var'                =>     true,
        'rewrite'                  =>     ['slug' => 'tilkomstrute'],
        'capability_type'          =>     'page',
        'has_archive'              =>     false,
        'hierarchical'             =>     false,
        'menu_position'            =>     NULL,
        'menu_icon'                =>     NULL,
    ];

    register_post_type('tilkomstrute', $args);
}
add_action('init', 'Arrival_Post_Type');
