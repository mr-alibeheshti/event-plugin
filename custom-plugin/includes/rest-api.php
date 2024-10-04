<?php
function cep_register_event_rest_fields() {
    register_rest_field('event', 'event_details', array(
        'get_callback' => 'cep_get_event_details',
        'update_callback' => 'cep_update_event_details',
        'schema' => array(
            'description' => 'Event details',
            'type' => 'object',
        ),
    ));
}
add_action('rest_api_init', 'cep_register_event_rest_fields');

function cep_get_event_details($object) {
    $post_id = $object['id'];
    return array(
        'date' => get_post_meta($post_id, '_event_date', true),
        'time' => get_post_meta($post_id, '_event_time', true),
        'location' => get_post_meta($post_id, '_event_location', true),
    );
}

function cep_update_event_details($value, $object, $field_name) {
    if (!current_user_can('edit_post', $object->ID)) {
        return new WP_Error('rest_cannot_edit', __('Sorry, you are not allowed to edit this post.'), array('status' => rest_authorization_required_code()));
    }
    
    if (isset($value['date'])) {
        update_post_meta($object->ID, '_event_date', sanitize_text_field($value['date']));
    }
    if (isset($value['time'])) {
        update_post_meta($object->ID, '_event_time', sanitize_text_field($value['time']));
    }
    if (isset($value['location'])) {
        update_post_meta($object->ID, '_event_location', sanitize_text_field($value['location']));
    }
    
    return true;
}