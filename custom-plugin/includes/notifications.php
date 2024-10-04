<?php
function cep_send_event_notification($post_id, $post, $update)
{
    if ($post->post_type !== 'event' || $post->post_status !== 'publish') {
        return;
    }

    $subscribers = get_users(array('role' => 'subscriber'));
    $subject = $update ? 'Event Updated: ' : 'New Event: ';
    $subject .= $post->post_title;

    $message = "A new event has been published:\n\n";
    $message .= "Title: " . $post->post_title . "\n";
    $message .= "Date: " . get_post_meta($post_id, '_event_date', true) . "\n";
    $message .= "Location: " . get_post_meta($post_id, '_event_location', true) . "\n";
    $message .= "View event: " . get_permalink($post_id);

    foreach ($subscribers as $subscriber) {
        wp_mail($subscriber->user_email, $subject, $message);
    }
}
add_action('save_post', 'cep_send_event_notification', 10, 3);

function cep_create_rsvp_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'event_rsvps';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        event_id mediumint(9) NOT NULL,
        user_id mediumint(9) NOT NULL,
        rsvp_status varchar(20) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function cep_process_rsvp()
{
    check_ajax_referer('cep_rsvp_nonce', 'nonce');

    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $user_id = get_current_user_id();
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';

    if (!$event_id || !$user_id || !$status) {
        wp_send_json_error('Invalid data');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'event_rsvps';

    $result = $wpdb->replace(
        $table_name,
        array(
            'event_id' => $event_id,
            'user_id' => $user_id,
            'rsvp_status' => $status
        ),
        array('%d', '%d', '%s')
    );

    if ($result) {
        wp_send_json_success('RSVP updated successfully');
    } else {
        wp_send_json_error('Failed to update RSVP');
    }
}
add_action('wp_ajax_cep_process_rsvp', 'cep_process_rsvp');
