<?php
class CustomEventsPluginTest extends WP_UnitTestCase
{

    public function setUp()
    {
        parent::setUp();
        // کد لازم برای راه‌اندازی آزمون
    }

    public function tearDown()
    {
        parent::tearDown();
        // کد لازم برای پاکسازی بعد از آزمون
    }

    public function test_event_post_type_exists()
    {
        $this->assertTrue(post_type_exists('event'));
    }

    public function test_event_category_taxonomy_exists()
    {
        $this->assertTrue(taxonomy_exists('event_category'));
    }

    public function test_create_event()
    {
        $event_id = wp_insert_post(array(
            'post_title' => 'Test Event',
            'post_type' => 'event',
            'post_status' => 'publish'
        ));

        $this->assertNotEquals(0, $event_id);
        $this->assertEquals('Test Event', get_the_title($event_id));
    }

    public function test_event_meta_fields()
    {
        $event_id = wp_insert_post(array(
            'post_title' => 'Test Event',
            'post_type' => 'event',
            'post_status' => 'publish'
        ));

        update_post_meta($event_id, '_event_date', '2023-12-31');
        update_post_meta($event_id, '_event_time', '20:00');
        update_post_meta($event_id, '_event_location', 'Test Location');

        $this->assertEquals('2023-12-31', get_post_meta($event_id, '_event_date', true));
        $this->assertEquals('20:00', get_post_meta($event_id, '_event_time', true));
        $this->assertEquals('Test Location', get_post_meta($event_id, '_event_location', true));
    }

    public function test_event_shortcode()
    {
        $event_id = wp_insert_post(array(
            'post_title' => 'Test Event',
            'post_type' => 'event',
            'post_status' => 'publish'
        ));

        update_post_meta($event_id, '_event_date', '2023-12-31');

        $shortcode_output = do_shortcode('[events_list]');
        $this->assertStringContainsString('Test Event', $shortcode_output);
        $this->assertStringContainsString('2023-12-31', $shortcode_output);
    }

    public function test_rsvp_functionality()
    {
        $event_id = wp_insert_post(array(
            'post_title' => 'Test Event',
            'post_type' => 'event',
            'post_status' => 'publish'
        ));

        $user_id = $this->factory->user->create();
        wp_set_current_user($user_id);

        $_POST['action'] = 'cep_process_rsvp';
        $_POST['event_id'] = $event_id;
        $_POST['status'] = 'attending';
        $_POST['nonce'] = wp_create_nonce('cep_rsvp_nonce');

        try {
            $this->_handleAjax('cep_process_rsvp');
        } catch (WPAjaxDieContinueException $e) {
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'event_rsvps';
        $rsvp = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE event_id = %d AND user_id = %d",
            $event_id,
            $user_id
        ));

        $this->assertNotNull($rsvp);
        $this->assertEquals('attending', $rsvp->rsvp_status);
    }

    public function test_rest_api()
    {
        $event_id = wp_insert_post(array(
            'post_title' => 'Test Event',
            'post_type' => 'event',
            'post_status' => 'publish'
        ));

        update_post_meta($event_id, '_event_date', '2023-12-31');
        update_post_meta($event_id, '_event_location', 'Test Location');

        $request = new WP_REST_Request('GET', '/wp/v2/event/' . $event_id);
        $response = rest_do_request($request);
        $data = $response->get_data();

        $this->assertEquals(200, $response->get_status());
        $this->assertEquals('Test Event', $data['title']['rendered']);
        $this->assertEquals('2023-12-31', $data['event_details']['date']);
        $this->assertEquals('Test Location', $data['event_details']['location']);
    }
}
