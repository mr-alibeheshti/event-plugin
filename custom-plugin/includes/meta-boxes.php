<?php
// Add Meta Boxes
function cep_add_event_meta_boxes() {
    add_meta_box(
        'event_details',
        __('Event Details', 'custom-events-plugin'),
        'cep_event_details_meta_box',
        'event',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'cep_add_event_meta_boxes');

function cep_event_details_meta_box($post) {
    wp_nonce_field('cep_save_event_details', 'cep_event_details_nonce');
    $event_date = get_post_meta($post->ID, '_event_date', true);
    $event_time = get_post_meta($post->ID, '_event_time', true);
    $event_location = get_post_meta($post->ID, '_event_location', true);
    ?>
    <p>
        <label for="event_date"><?php _e('Event Date:', 'custom-events-plugin'); ?></label>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>">
    </p>
    <p>
        <label for="event_time"><?php _e('Event Time:', 'custom-events-plugin'); ?></label>
        <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr($event_time); ?>">
    </p>
    <p>
        <label for="event_location"><?php _e('Event Location:', 'custom-events-plugin'); ?></label>
        <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($event_location); ?>" size="25">
    </p>
    <?php
}

// Save Meta Box Data
function cep_save_event_details($post_id) {
    if (!isset($_POST['cep_event_details_nonce']) || !wp_verify_nonce($_POST['cep_event_details_nonce'], 'cep_save_event_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date']));
    }
    if (isset($_POST['event_time'])) {
        update_post_meta($post_id, '_event_time', sanitize_text_field($_POST['event_time']));
    }
    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location']));
    }
}
add_action('save_post_event', 'cep_save_event_details');