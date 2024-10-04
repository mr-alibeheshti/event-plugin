<?php
function cep_cache_events() {
    $events = get_posts(array(
        'post_type' => 'event',
        'posts_per_page' => -1,
    ));
    
    set_transient('cep_all_events', $events, HOUR_IN_SECONDS);
}
add_action('save_post_event', 'cep_cache_events');
add_action('deleted_post', 'cep_cache_events');

function cep_get_cached_events() {
    $cached_events = get_transient('cep_all_events');
    
    if (false === $cached_events) {
        $cached_events = get_posts(array(
            'post_type' => 'event',
            'posts_per_page' => -1,
        ));
        set_transient('cep_all_events', $cached_events, HOUR_IN_SECONDS);
    }
    
    return $cached_events;
}

function cep_optimize_event_query($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('event')) {
        $query->set('posts_per_page', 10);
        $query->set('no_found_rows', true); 
        $query->set('update_post_meta_cache', false);
        $query->set('update_post_term_cache', false);
    }
}
add_action('pre_get_posts', 'cep_optimize_event_query');