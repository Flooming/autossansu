<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* 
*/
class Redq_Rental_Admin_Page {
  
  function __construct() {
    add_action( 'admin_menu', array( $this , 'redq_rental_admin_menu')  );
  }

  /**
   * redq_rental_admin_menu
   *
   * @version 1.0.0
   * @since 2.0.4
   */
  public function redq_rental_admin_menu() {
    add_menu_page( $page_title = 'RnB Menu Page', $menu_title = 'RnB Calendar', $capability = 'manage_options', $menu_slug = 'rnb_admin', $function =  array( $this , 'redq_rental_admin_main_menu_options'),$icon_url = 'dashicons-calendar-alt', 59 );
  }

  /**
   * redq_rental_admin_main_menu_options
   *
   * @version 1.0.0
   * @since 2.0.4
   */
  public function redq_rental_admin_main_menu_options() {
    if ( !current_user_can( 'manage_options' ) )  {
      wp_die( __( 'You do not have sufficient permissions to access this page.', 'redq-rental' ) );
    }

    include_once 'views/admin-menu-page-full-calender.php';
  }
}

new Redq_Rental_Admin_Page();