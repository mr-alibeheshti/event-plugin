<?php
// Enqueue styles for the plugin
function cep_enqueue_styles()
{
    wp_enqueue_style('onix-map-widget-style', CEP_PLUGIN_URL . 'assets/css/archive-event-style.css', array(), CEP_VERSION);
    wp_enqueue_style('onix-blog-widget-style', CEP_PLUGIN_URL . 'assets/css/single-event-style.css', array(), CEP_VERSION);
}
add_action('wp_enqueue_scripts', 'cep_enqueue_styles');

// Enqueue Google Fonts
function cep_enqueue_google_fonts()
{
    if (is_post_type_archive('event') || is_singular('event')) {
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap', array(), null);
    }
}
add_action('wp_enqueue_scripts', 'cep_enqueue_google_fonts');
