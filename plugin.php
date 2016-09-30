<?php
/**
 * Plugin Name: Event Tickets Extension: Add Phone Number to Attendee Details
 * Description: Add phone numbers to attendee ticket details.
 * Version: 1.0.0
 * Author: Modern Tribe, Inc.
 * Author URI: http://m.tri.be/1971
 * License: GPLv2 or later
 */

defined( 'WPINC' ) or die;

class Tribe__Extension__Add_Phone_Number_to_Attendee_Details {

    /**
     * The semantic version number of this extension; should always match the plugin header.
     */
    const VERSION = '1.0.0';

    /**
     * Each plugin required by this extension
     *
     * @var array Plugins are listed in 'main class' => 'minimum version #' format
     */
    public $plugins_required = array(
        'Tribe__Tickets__Main' => '4.2'
    );

    /**
     * The constructor; delays initializing the extension until all other plugins are loaded.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 100 );
    }

    /**
     * Extension hooks and initialization; exits if the extension is not authorized by Tribe Common to run.
     */
    public function init() {

        // Exit early if our framework is saying this extension should not run.
        if ( ! function_exists( 'tribe_register_plugin' ) || ! tribe_register_plugin( __FILE__, __CLASS__, self::VERSION, $this->plugins_required ) ) {
            return;
        }

        add_action( 'event_tickets_attendees_table_ticket_column', array( $this, 'add_phone_to_attendee_ticket_details' ) );
    }

    /**
     * Add the phone number to attendee ticket details.
     *
     * @param array $item
     */
    public function add_phone_to_attendee_ticket_details( $item ) {
        
        if ( ! isset( $item['order_id'] ) )
            return;

        $phone_number = get_post_meta( $item['order_id'], '_billing_phone', true );
        
        if ( ! empty( $phone_number ) ) {
            printf( '<div class="event-tickets-ticket-purchaser">Phone: %s</div>', sanitize_text_field( $phone_number ) );
        }
    }
}

new Tribe__Extension__Add_Phone_Number_to_Attendee_Details();
