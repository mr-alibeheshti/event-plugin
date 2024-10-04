<?php
function cep_load_plugin_template($template) {
    if (is_post_type_archive('event')) {
        $template = plugin_dir_path(dirname(__FILE__)) . 'templates/archive-event.php';
    }
    if (is_singular('event')) {
        $template = plugin_dir_path(dirname(__FILE__)) . 'templates/single-event.php';
    }
    return $template;
}
add_filter('template_include', 'cep_load_plugin_template');