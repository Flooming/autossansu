<?php

/**
* 
*/
class WC_Google_Calendar_Api {
  
  function __construct() {
    add_action( 'woocommerce_new_order', array( $this, 'google_calendar_events' ),  1, 1  );
  }

  function google_calendar_events($order_id){
  }
}