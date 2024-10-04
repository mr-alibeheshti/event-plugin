<?php
class Custom_Events_Admin
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
    }

    public function enqueue_admin_scripts()
    {
        wp_enqueue_style('custom-events-admin-css', plugin_dir_url(__FILE__) . 'css/admin-style.css', array(), CEP_VERSION);
        wp_enqueue_script('custom-events-admin-js', plugin_dir_url(__FILE__) . 'js/admin-script.js', array('jquery'), CEP_VERSION, true);
    }

    public function add_plugin_admin_menu()
    {
        add_submenu_page(
            'edit.php?post_type=event',
            __('Event Settings', 'custom-events-plugin'),
            __('Settings', 'custom-events-plugin'),
            'manage_options',
            'custom-events-settings',
            array($this, 'display_plugin_admin_page')
        );
    }

    public function display_plugin_admin_page()
    {
        // Admin page content
?>
        <div class="wrap">
            <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
            <form method="post" action="options.php">
                <?php
                // Add your settings fields here
                settings_fields('custom_events_options');
                do_settings_sections('custom-events-settings');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }
}
