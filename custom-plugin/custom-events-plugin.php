<?php
/*
Plugin Name: Custom Events Plugin
Description: A comprehensive plugin to manage events with custom post types, taxonomies, meta boxes, REST API integration, and more.
Version: 1.2
Author: Your Name
Text Domain: custom-events-plugin
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('CEP_VERSION', '1.2');
define('CEP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CEP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once CEP_PLUGIN_DIR . 'includes/post-types.php';
require_once CEP_PLUGIN_DIR . 'includes/taxonomies.php';
require_once CEP_PLUGIN_DIR . 'includes/meta-boxes.php';
require_once CEP_PLUGIN_DIR . 'includes/template-functions.php';
require_once CEP_PLUGIN_DIR . 'includes/enqueue-scripts.php';
require_once CEP_PLUGIN_DIR . 'includes/admin-customizations.php';
require_once CEP_PLUGIN_DIR . 'includes/shortcodes.php';
require_once CEP_PLUGIN_DIR . 'includes/search-filter.php';
require_once CEP_PLUGIN_DIR . 'includes/notifications.php';
require_once CEP_PLUGIN_DIR . 'includes/rest-api.php';
require_once CEP_PLUGIN_DIR . 'includes/performance.php';

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'cep_activate');
register_deactivation_hook(__FILE__, 'cep_deactivate');

function cep_activate() {
    cep_register_event_post_type();
    cep_register_event_taxonomy();
    cep_create_rsvp_table();
    flush_rewrite_rules();
}

function cep_deactivate() {
    flush_rewrite_rules();
}

// Load plugin textdomain
function cep_load_textdomain() {
    load_plugin_textdomain('custom-events-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'cep_load_textdomain');