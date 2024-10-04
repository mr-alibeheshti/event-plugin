<?php
// Register Custom Post Type
function cep_register_event_post_type() {
    $args = array(
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'event'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt'),
        'labels'             => array(
            'name'               => _x('Events', 'post type general name', 'custom-events-plugin'),
            'singular_name'      => _x('Event', 'post type singular name', 'custom-events-plugin'),
            'menu_name'          => _x('Events', 'admin menu', 'custom-events-plugin'),
            'add_new'            => _x('Add New', 'event', 'custom-events-plugin'),
            'add_new_item'       => __('Add New Event', 'custom-events-plugin'),
            'edit_item'          => __('Edit Event', 'custom-events-plugin'),
            'new_item'           => __('New Event', 'custom-events-plugin'),
            'view_item'          => __('View Event', 'custom-events-plugin'),
            'search_items'       => __('Search Events', 'custom-events-plugin'),
            'not_found'          => __('No events found', 'custom-events-plugin'),
            'not_found_in_trash' => __('No events found in Trash', 'custom-events-plugin'),
        ),
    );

    register_post_type('event', $args);
}
add_action('init', 'cep_register_event_post_type');