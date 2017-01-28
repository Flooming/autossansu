<?php
class WC_Rnb_Settings {

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::redq_add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_rnb_settings', __CLASS__ . '::redq_settings_tab' );
        add_action( 'woocommerce_update_options_rnb_settings', __CLASS__ . '::redq_update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function redq_add_settings_tab( $settings_tabs ) {
        $settings_tabs['rnb_settings'] = __( 'RnB Settings', 'redq-rental' );
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function redq_settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function redq_update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Configure Your Global Settings For Rental Product', 'redq-rental' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'redq_rental_global_title'
            ),
            'pickup-location-title' => array(
                'name' => __( 'Pickup Location Title', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'This text will show as Pickup location title in fornt-end', 'redq-rental' ),
                'id'   => 'redq_rental_global_pickup_location_title'
            ),
            'return-location-title' => array(
                'name' => __( 'Return Location Title', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'This text will show as return location title in fornt-end', 'redq-rental' ),
                'id'   => 'redq_rental_global_return_location_title'
            ),
            'pickup-date' => array(
                'name' => __( 'Pickup Date Title', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'This text will show as pickup date title in fornt-end', 'redq-rental' ),
                'id'   => 'redq_rental_global_pickup_date_title'
            ),
            'return-date' => array(
                'name' => __( 'Return Date Title', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'This text will show as return date title in fornt-end', 'redq-rental' ),
                'id'   => 'redq_rental_global_return_date_title'
            ),
            
            'resources-title' => array(
                'name' => __( 'Resources Title', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'This text will show as resources title in fornt-end', 'redq-rental' ),
                'id'   => 'redq_rental_global_resources_title'
            ),

            'person-title' => array(
                'name' => __( 'Person Title', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'This text will show as person title in fornt-end', 'redq-rental' ),
                'id'   => 'redq_rental_global_person_title'
            ),

            'deposite-title' => array(
                'name' => __( 'Deposite Title', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'This text will show as deposite title in fornt-end', 'redq-rental' ),
                'id'   => 'redq_rental_global_deposite_title'
            ),

            'pre-payment' => array(
                'name' => __( 'Pay During Booking', 'redq-rental' ),
                'type' => 'number',
                'desc' => __( 'You must have to pay this percentage (%) amonut during booking. default payamont value is 100%', 'redq-rental' ),
                'id'   => 'redq_rental_global_pre_payment',
                'custom_attributes' => array(
                        'step'  => '1',
                        'min'   => '0',
                        'max'   => '100'
                    ),
            ),

            array(
                'title'   => __( 'Show Pickup Date', 'redq-rental' ),
                'desc'    => __( 'Show Pickup Date', 'redq-rental' ),
                'id'      => 'redq_rental_global_show_pickup_date',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),

            array(
                'title'   => __( 'Show Pickup Time', 'redq-rental' ),
                'desc'    => __( 'Show Pickup Time', 'redq-rental' ),
                'id'      => 'redq_rental_global_show_pickup_time',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),

             array(
                'title'   => __( 'Show Dropoff Date', 'redq-rental' ),
                'desc'    => __( 'Show Dropoff Date', 'redq-rental' ),
                'id'      => 'redq_rental_global_show_dropoff_date',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),

            array(
                'title'   => __( 'Show Dropoff Time', 'redq-rental' ),
                'desc'    => __( 'Show Dropoff Time', 'redq-rental' ),
                'id'      => 'redq_rental_global_show_dropoff_time',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),

            array(
                'title'   => __( 'Show Pricing Flip Box', 'redq-rental' ),
                'desc'    => __( 'Show Pricing Flip Box', 'redq-rental' ),
                'id'      => 'redq_rental_global_show_pricing_flip_box',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),

            array(
                'title'   => __( 'Show price discount', 'redq-rental' ),
                'desc'    => __( 'Show price discount', 'redq-rental' ),
                'id'      => 'redq_rental_global_show_price_discount',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),


            array(
                'title'   => __( 'Show instance payment', 'redq-rental' ),
                'desc'    => __( 'Show instance payment', 'redq-rental' ),
                'id'      => 'redq_rental_global_show_instant_payment',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),


            'language-domain' => array(
                'name' => __( 'Enter Your Language Domain', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'Enter your language domain. E.x - de', 'redq-rental' ),
                'id'   => 'redq_rental_lang_domain_title'
            ),

            'lang-months' => array(
                'name' => __( 'Enter Month Name', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'Write month name in comma separated e.x - Januar,Februar,März,April, Mai,Juni,Juli,August,September,Oktober,November,Dezember', 'redq-rental' ),
                'id'   => 'redq_rental_lang_month_name_title'
            ),

            'lang-weekday' => array(
                'name' => __( 'Enter Week Day Name', 'redq-rental' ),
                'type' => 'text',
                'desc' => __( 'Write weekday name in comma separated e.x - So, Mo, Di, Mi, Do, Fr, Sa', 'redq-rental' ),
                'id'   => 'redq_rental_lang_weekday_name_title'
            ),


            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_rnb_settings_section_end'
            )
        );

        return apply_filters( 'wc_rnb_settings_settings', $settings );
    }

}

WC_Rnb_Settings::init();
