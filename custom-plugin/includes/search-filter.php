<?php
function cep_event_search_form()
{
    ob_start();
?>
    <form role="search" method="get" class="event-search-form" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search Events…', 'placeholder', 'custom-events-plugin'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <input type="hidden" name="post_type" value="event" />
        <input type="submit" class="search-submit" value="<?php echo esc_attr_x('Search', 'submit button', 'custom-events-plugin'); ?>" />
    </form>
<?php
    return ob_get_clean();
}
add_shortcode('event_search', 'cep_event_search_form');

function cep_filter_events_query($query)
{
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('event')) {
        if (isset($_GET['event_category'])) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'event_category',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['event_category']),
                ),
            ));
        }
        if (isset($_GET['event_date'])) {
            $query->set('meta_query', array(
                array(
                    'key' => '_event_date',
                    'value' => sanitize_text_field($_GET['event_date']),
                    'compare' => '>=',
                    'type' => 'DATE'
                ),
            ));
        }
    }
}
add_action('pre_get_posts', 'cep_filter_events_query');
