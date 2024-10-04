<?php
function cep_events_list_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'limit' => 5,
        'category' => '',
    ), $atts);

    $args = array(
        'post_type' => 'event',
        'posts_per_page' => $atts['limit'],
        'orderby' => 'meta_value',
        'meta_key' => '_event_date',
        'order' => 'ASC',
    );

    if (!empty($atts['category'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'event_category',
                'field' => 'slug',
                'terms' => $atts['category'],
            ),
        );
    }

    $events = new WP_Query($args);

    ob_start();
    if ($events->have_posts()) {
        echo '<ul class="events-list">';
        while ($events->have_posts()) {
            $events->the_post();
            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a> - ' . get_post_meta(get_the_ID(), '_event_date', true) . '</li>';
        }
        echo '</ul>';
    } else {
        echo __('No events found.', 'custom-events-plugin');
    }
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('events_list', 'cep_events_list_shortcode');
