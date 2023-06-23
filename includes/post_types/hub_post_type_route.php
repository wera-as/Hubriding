<?php

function Route_Post_Type()
{

    $supports = [
        'title',
		'thumbnail',
    ];

    $labels = [
        'name'                        =>     _x('Ruter', 'plural'),
        'singular_name'               =>     _x('Rute', 'singular'),
        'menu_name'                   =>     _x('Rute', 'admin menu'),
        'name_admin_bar'              =>     _x('Rute', 'admin bar'),
        'featured_image'              =>     __('Hovedbilde'),
        'set_featured_image'          =>     __('Sett hovedbilde'),
        'remove_featured_image'       =>     __('Fjern hovedbilde'),
        'add_new'                     =>     _x('Legg til', 'add new'),
        'add_new_item'                =>     __('Legg til ny rute'),
        'new_item'                    =>     __('Ny rute'),
        'edit_item'                   =>     __('Rediger rute'),
        'view_item'                   =>     __('Se rute'),
        'all_items'                   =>     __('Ruter'),
        'search_items'                =>     __('Søk i ruter'),
        'not_found'                   =>     __('Ingen ruter funnet.'),
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
        'rewrite'                  =>     ['slug' => 'rute'],
        'capability_type'          =>     'page',
        'has_archive'              =>     false,
        'hierarchical'             =>     false,
        'menu_position'            =>     NULL,
        'menu_icon'                =>     NULL,
    ];

    register_post_type('rute', $args);
}
add_action('init', 'Route_Post_Type');
