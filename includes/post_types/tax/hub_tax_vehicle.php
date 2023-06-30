<?php

function Vehicle_Post_Tax()
{

    $supports = [
        'title',
    ];

    $labels = [
        'name'                        =>     _x('Kjøretøy', 'plural'),
        'singular_name'               =>     _x('Kjøretøy', 'singular'),
        'menu_name'                   =>     _x('Kjøretøy', 'admin menu'),
        'name_admin_bar'              =>     _x('Kjøretøy', 'admin bar'),
        'featured_image'              =>     __('Hovedbilde'),
        'set_featured_image'          =>     __('Sett hovedbilde'),
        'remove_featured_image'       =>     __('Fjern hovedbilde'),
        'add_new'                     =>     _x('Legg til', 'add new'),
        'add_new_item'                =>     __('Legg til nytt kjøretøy'),
        'new_item'                    =>     __('Nytt kjøretøy'),
        'edit_item'                   =>     __('Rediger kjøretøy'),
        'view_item'                   =>     __('Se kjøretøy'),
        'all_items'                   =>     __('Alle kjøretøy'),
        'search_items'                =>     __('Søk i kjøretøy'),
        'not_found'                   =>     __('Ingen kjøretøy funnet.'),
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
        'rewrite'                  =>     ['slug' => 'kjøretøy'],
        'capability_type'          =>     'page',
        'has_archive'              =>     false,
        'hierarchical'             =>     false,
        'menu_position'            =>     NULL,
        'menu_icon'                =>     NULL,
    ];

    register_taxonomy('kjøretøy', 'page', $args);
}
add_action('init', 'Vehicle_Post_Tax');