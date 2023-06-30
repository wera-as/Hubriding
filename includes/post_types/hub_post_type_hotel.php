<?php

function Hotel_Post_Type()
{

    $supports = [
        'title',
        'thumbnail',
    ];

    $labels = [
        'name'                        =>     _x('Hoteller', 'plural'),
        'singular_name'               =>     _x('Hotell', 'singular'),
        'menu_name'                   =>     _x('Hotell', 'admin menu'),
        'name_admin_bar'              =>     _x('Hotell', 'admin bar'),
        'featured_image'              =>     __('Fremhevet bilde'),
        'set_featured_image'          =>     __('Bestem fremhevet bilde'),
        'remove_featured_image'       =>     __('Fjern fremhevet bilde'),
        'add_new'                     =>     _x('Legg til', 'add new'),
        'add_new_item'                =>     __('Legg til nytt hotell'),
        'new_item'                    =>     __('Nytt hotell'),
        'edit_item'                   =>     __('Rediger hotell'),
        'view_item'                   =>     __('Se hotell'),
        'all_items'                   =>     __('Hoteller'),
        'search_items'                =>     __('Søk i hoteller'),
        'not_found'                   =>     __('Ingen hoteller funnet.'),
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
        'rewrite'                  =>     ['slug' => 'hotell'],
        'capability_type'          =>     'page',
        'has_archive'              =>     false,
        'hierarchical'             =>     false,
        'menu_position'            =>     NULL,
        'menu_icon'                =>     NULL,
    ];

    register_post_type('hotell', $args);
}
add_action('init', 'Hotel_Post_Type');
