<?php
// Register Custom Taxonomy
function cep_register_event_taxonomy()
{
    $args = array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => _x('Event Categories', 'taxonomy general name', 'custom-events-plugin'),
            'singular_name'     => _x('Event Category', 'taxonomy singular name', 'custom-events-plugin'),
            'search_items'      => __('Search Event Categories', 'custom-events-plugin'),
            'all_items'         => __('All Event Categories', 'custom-events-plugin'),
            'parent_item'       => __('Parent Event Category', 'custom-events-plugin'),
            'parent_item_colon' => __('Parent Event Category:', 'custom-events-plugin'),
            'edit_item'         => __('Edit Event Category', 'custom-events-plugin'),
            'update_item'       => __('Update Event Category', 'custom-events-plugin'),
            'add_new_item'      => __('Add New Event Category', 'custom-events-plugin'),
            'new_item_name'     => __('New Event Category Name', 'custom-events-plugin'),
            'menu_name'         => __('Event Categories', 'custom-events-plugin'),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'event-category'),
    );

    register_taxonomy('event_category', array('event'), $args);
}
add_action('init', 'cep_register_event_taxonomy');

