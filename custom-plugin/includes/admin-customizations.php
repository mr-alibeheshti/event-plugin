<?php
// Customize admin columns for events
function cep_custom_event_columns($columns) {
    $columns['event_date'] = __('Event Date', 'custom-events-plugin');
    $columns['event_location'] = __('Location', 'custom-events-plugin');
    return $columns;
}
add_filter('manage_event_posts_columns', 'cep_custom_event_columns');

function cep_custom_event_column_content($column, $post_id) {
    switch ($column) {
        case 'event_date':
            echo esc_html(get_post_meta($post_id, '_event_date', true));
            break;
        case 'event_location':
            echo esc_html(get_post_meta($post_id, '_event_location', true));
            break;
    }
}
add_action('manage_event_posts_custom_column', 'cep_custom_event_column_content', 10, 2);