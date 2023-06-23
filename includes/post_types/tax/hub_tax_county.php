<?php

function County_Post_Tax()
{

    $supports = [
        'title',
    ];

    $labels = [
        'name'                        =>     _x('Fylker', 'plural'),
        'singular_name'               =>     _x('Fylke', 'singular'),
        'menu_name'                   =>     _x('Fylker', 'admin menu'),
        'name_admin_bar'              =>     _x('Fylker', 'admin bar'),
        'featured_image'              =>     __('Hovedbilde'),
        'set_featured_image'          =>     __('Sett hovedbilde'),
        'remove_featured_image'       =>     __('Fjern hovedbilde'),
        'add_new'                     =>     _x('Legg til', 'add new'),
        'add_new_item'                =>     __('Legg til nytt fylke'),
        'new_item'                    =>     __('Nytt fylke'),
        'edit_item'                   =>     __('Rediger fylke'),
        'view_item'                   =>     __('Se fylke'),
        'all_items'                   =>     __('Alle fylker'),
        'search_items'                =>     __('Søk i fylker'),
        'not_found'                   =>     __('Ingen fylker funnet.'),
    ];

    $args = [
        'labels'                   =>     $labels,
        'supports'                 =>     $supports,
        'public'                   =>     true,
        'publicly_queryable'       =>     true,
        'exclude_from_search'      =>     false,
        'can_export'               =>     true,
        'show_ui'                  =>     true,
		'show_in_menu'             =>     true,	
        'show_in_rest'             =>     true,
        'menu_position'            =>     5,
        'query_var'                =>     true,
        'rewrite'                  =>     ['slug' => 'fylke'],
        'capability_type'          =>     'page',
        'has_archive'              =>     false,
        'hierarchical'             =>     false,
        'menu_position'            =>     NULL,
        'menu_icon'                =>     NULL,
    ];

    register_taxonomy('fylke', 'page', $args);
}
add_action('init', 'County_Post_Tax');
