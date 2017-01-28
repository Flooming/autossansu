<?php
/**
 * Plugin Name: WooCommerce Rental & Bookings System
 * Plugin URI: https://codecanyon.net/item/rnb-woocommerce-rental-booking-system/14835145?ref=redqteam
 * Description: RnB – WooCommerce Rental & Booking is a user friendly woocommerce booking plugin built as woocommerce extension. This powerful woocommerce plugin allows you to sell your time or date based bookings. It creates a new product type to your WooCommerce site. Perfect for those wanting to offer rental , booking , or real estate agencies or services. 
 * Version: 2.1.9
 * Author: RedQ Team
 * Author URI: http://redqteam.com
 * License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: redq-rental
 * Domain Path: /languages
 *
 */

if ( ! defined( 'ABSPATH' ) )
    exit;

/**
* RedQ_Rental_And_Bookings
*/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	class RedQ_Rental_And_Bookings{

		/**
		 * Plugin data from get_plugins()
		 *
		 * @since 1.0
		 * @var object
		 */
		public $plugin_data;

		/**
		 * Includes to load
		 *
		 * @since 1.0
		 * @var array
		 */
		public $includes;

		/**
		 * Plugin Action and Filter Hooks
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function __construct(){
			add_action('plugins_loaded' , array($this, 'redq_rental_set_plugins_data'), 1);
			add_action('plugins_loaded' , array($this, 'redq_rental_define_constants'), 1);
			add_action('plugins_loaded' , array($this, 'redq_rental_set_includes'), 1);
			add_action('plugins_loaded' , array($this, 'redq_rental_load_includes'), 1);
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_styles_and_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles_and_scripts' ) );
			add_action('woocommerce_redq_rental_add_to_cart', array($this, 'redq_add_to_cart'),30);
			add_action( 'plugins_loaded', array( $this, 'redq_support_multilanguages' ) );
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'redq_more_plugins_links' ), 1 );
		}


		public function redq_more_plugins_links( $links ) {	
			$links[] = '<a href="https://redq.gitbooks.io/woocommerce-rental-and-booking/content/" target="_blank">Docs</a>';	
			$links[] = '<a href="https://redqsupport.ticksy.com/" target="_blank">Support</a>';				
		   	$links[] = '<a href="https://codecanyon.net/user/redqteam/portfolio?ref=redqteam" target="_blank">Portfolio</a>';
		   	return $links;
		}


		public function redq_rental_set_plugins_data(){
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugin_dir = plugin_basename( dirname( __FILE__ ) );
			$plugin_data = current( get_plugins( '/' . $plugin_dir ) );
			$this->plugin_data = apply_filters( 'redq_plugin_data', $plugin_data );
		}


		/**
		 * Plugin constant define
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function redq_rental_define_constants(){
			define( 'REDQ_RENTAL_VERSION', 		$this->plugin_data['Version'] );					// plugin version
			define( 'REDQ_RENTAL_FILE', 		__FILE__ );											// plugin's main file path
			define( 'REDQ_RENTAL_DIR', 			dirname( plugin_basename( REDQ_RENTAL_FILE ) ) );			// plugin's directory
			define( 'REDQ_RENTAL_PATH',			untrailingslashit( plugin_dir_path( REDQ_RENTAL_FILE ) ) );	// plugin's directory path
			define( 'REDQ_RENTAL_URL', 			untrailingslashit( plugin_dir_url( REDQ_RENTAL_FILE ) ) );	// plugin's directory URL

			define( 'REDQ_RENTAL_INC_DIR',		'includes' );	// includes directory
			define( 'REDQ_RENTAL_ASSETS_DIR', 		'assets' );		// assets directory
			define( 'REDQ_RENTAL_LANG_DIR', 	'languages' );	// languages directory
			define( 'REDQ_ROOT_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
			define( 'REDQ_PACKAGE_TEMPLATE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/' );
		}


		/**
		 * Plugin includes files
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function redq_rental_set_includes(){

			$this->includes = apply_filters('redq_rental' , array(
				'admin' => array(
					REDQ_RENTAL_INC_DIR . '/admin/class-redq-rental-meta-boxes.php',
          			REDQ_RENTAL_INC_DIR . '/admin/class-redq-rental-settings-tab.php',
					REDQ_RENTAL_INC_DIR . '/admin/class-redq-rental-admin-page.php',
					//REDQ_RENTAL_INC_DIR . '/admin/class-redq-rental-post-types.php',
					//REDQ_RENTAL_INC_DIR . '/admin/class-rnb-term-meta-text.php',	
					//REDQ_RENTAL_INC_DIR . '/admin/class-rnb-term-meta-select.php',					
				),
				'frontends' => array(
					REDQ_RENTAL_INC_DIR . '/class-redq-product-redq_rental.php',
					REDQ_RENTAL_INC_DIR . '/class-redq-product-cart.php',
					REDQ_RENTAL_INC_DIR . '/class-redq-product-tabs.php',
				)
			));

			
			require_once trailingslashit( REDQ_RENTAL_PATH ) . REDQ_RENTAL_INC_DIR . '/admin/class-redq-rental-post-types.php';
			require_once trailingslashit( REDQ_RENTAL_PATH ) . REDQ_RENTAL_INC_DIR . '/admin/class-rnb-term-meta-text.php';
			require_once trailingslashit( REDQ_RENTAL_PATH ) . REDQ_RENTAL_INC_DIR . '/admin/class-rnb-term-meta-icon.php';
			require_once trailingslashit( REDQ_RENTAL_PATH ) . REDQ_RENTAL_INC_DIR . '/admin/class-rnb-term-meta-select.php';

      		REDQ_RENTAL_INC_DIR . '/class-google-calendar-api.php';

		}


		/**
		 * Plugin includes files
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function redq_rental_load_includes() {

			$includes = $this->includes;

			foreach ( $includes as $condition => $files ) {
				$do_includes = false;
				switch( $condition ) {
					case 'admin':
						if ( is_admin() ) {
							$do_includes = true;
						}
						break;
					case 'frontend':
						if ( ! is_admin() ) {
							$do_includes = true;
						}
						break;
					default:
						$do_includes = true;
						break;
				}

				if ( $do_includes ) {
					foreach ( $files as $file ) {
						require_once trailingslashit( REDQ_RENTAL_PATH ) . $file;
					}
				}
			}
		}


		/**
		 * Plugin enqueues admin stylesheet and scripts
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function admin_styles_and_scripts($hook){

			global $post, $woocommerce, $wp_scripts;

			wp_register_script( 'jquery-ui-js',REDQ_ROOT_URL . '/assets/js/jquery-ui.js', array('jquery'), $ver = true, true);
			wp_enqueue_script( 'jquery-ui-js' );

			wp_register_style('jquery-ui-css', REDQ_ROOT_URL. '/assets/css/jquery-ui.css', array(), $ver = false, $media = 'all');
		  	wp_enqueue_style('jquery-ui-css');

			wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css');

			wp_register_script( 'select2.min',REDQ_ROOT_URL . '/assets/js/select2.min.js', array('jquery'), $ver = true, true);
			wp_enqueue_script( 'select2.min' );

  			wp_register_style('redq-admin', REDQ_ROOT_URL. '/assets/css/redq-admin.css', array(), $ver = false, $media = 'all');
  			wp_enqueue_style('redq-admin');

	  		wp_register_script( 'icon-picker',REDQ_ROOT_URL . '/assets/js/icon-picker.js', array('jquery'), $ver = true, true);
			wp_enqueue_script( 'icon-picker' );

			wp_register_script( 'redq_rental_writepanel_js', REDQ_ROOT_URL . '/assets/js/writepanel.js', array( 'jquery', 'jquery-ui-datepicker' ), true );
			wp_enqueue_script('redq_rental_writepanel_js');

			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-tabs');

			$params = array(
				'post'                   => isset( $post->ID ) ? $post->ID : '',
				'plugin_url'             => $woocommerce->plugin_url(),
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'calendar_image'         => $woocommerce->plugin_url() . '/assets/images/calendar.png',
				'all_data' => $this->reqd_all_booking_data(),
			);


			wp_localize_script( 'redq_rental_writepanel_js', 'redq_rental_writepanel_js_params', $params );


			$this->redq_show_rental_data_on_full_calendar($hook);
		}


		/**
		 * Frontend enqueues front-end stylesheet and scripts
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function frontend_styles_and_scripts(){

			$get_product = get_product(get_the_ID());

			if(isset($get_product) && !empty($get_product)){
				$product_type = get_product(get_the_ID())->product_type;
			}			

			if(isset($product_type) && $product_type === 'redq_rental'){

				wp_register_script( 'jquery.datetimepicker.full', REDQ_ROOT_URL . '/assets/js/jquery.datetimepicker.full.js', array( 'jquery'), true );
				wp_enqueue_script('jquery.datetimepicker.full');

				wp_register_script( 'sweetalert.min', REDQ_ROOT_URL . '/assets/js/sweetalert.min.js', array( 'jquery'), true );
				wp_enqueue_script('sweetalert.min');

				wp_register_script( 'chosen.jquery', REDQ_ROOT_URL . '/assets/js/chosen.jquery.js', array( 'jquery'), true );
				wp_enqueue_script('chosen.jquery');

				wp_enqueue_style('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css');

				wp_register_style('sweetalert', REDQ_ROOT_URL. '/assets/css/sweetalert.css', array(), $ver = false, $media = 'all');
		  		wp_enqueue_style('sweetalert');

		  		wp_register_style('chosen', REDQ_ROOT_URL. '/assets/css/chosen.css', array(), $ver = false, $media = 'all');
		  		wp_enqueue_style('chosen');

		  		wp_register_style('rental-style', REDQ_ROOT_URL. '/assets/css/rental-style.css', array(), $ver = false, $media = 'all');
		  		wp_enqueue_style('rental-style');

				wp_register_script( 'date', REDQ_ROOT_URL . '/assets/js/date.js', array( 'jquery'), true );
				wp_enqueue_script('date');

				wp_register_script( 'accounting', REDQ_ROOT_URL . '/assets/js/accounting.js', array( 'jquery'), true );
				wp_enqueue_script('accounting');

				wp_register_script( 'jquery.flip', REDQ_ROOT_URL . '/assets/js/jquery.flip.js', array( 'jquery'), true );
				wp_enqueue_script('jquery.flip');

				wp_register_script( 'front-end-scripts', REDQ_ROOT_URL . '/assets/js/main-script.js', array( 'jquery'), true );
				wp_enqueue_script('front-end-scripts');

				wp_register_script( 'preselected-cost-handle', REDQ_ROOT_URL . '/assets/js/preselected-cost-handle.js', array( 'jquery'), true );
				wp_enqueue_script('preselected-cost-handle');

				wp_register_script( 'cost-handle', REDQ_ROOT_URL . '/assets/js/cost-handle.js', array( 'jquery'), true );
				wp_enqueue_script('cost-handle');

				$block_dates = $this->calculate_block_dates();
				$this->redq_update_prices();

				$language_domain = get_option('redq_rental_lang_domain_title');
				$month_name = get_option('redq_rental_lang_month_name_title');
				$weekday_name = get_option('redq_rental_lang_weekday_name_title');

				

				$woocommerce_info = array(
				        'symbol' => get_woocommerce_currency_symbol(),
				        'currency' => get_woocommerce_currency(),
				        'thousand' => wc_get_price_thousand_separator(),
				        'decimal' => wc_get_price_decimal_separator(),
				        'number' => wc_get_price_decimals(),
				        'position' => get_option('woocommerce_currency_pos'),
				    );

				$translated_strings = array(
				        'max_booking_days_exceed' => __('Max booking days is exceed', 'redq-rental'),
				        'opps' => __('Ooops', 'redq-rental'),
				        'unavailable_date_range' => __('This date range is unavailable', 'redq-rental'),
				        'min_booking_days' => __('Min rental days is ', 'redq-rental'),
				        'max_booking_days' => __('Max rental days ', 'redq-rental'),			        
				    );

				$localize_info = array(
						'domain' => $language_domain,
						'months'  => $month_name,
						'weekdays' => $weekday_name
 					);

				wp_localize_script('cost-handle' , 'BOOKING_DATA', array(
						'all_data' => $this->reqd_all_booking_data(),
						'block_dates' => $block_dates,
						'localize_info' => $localize_info,
						'woocommerce_info' => $woocommerce_info,
						'translated_strings' => $translated_strings,
					));

				wp_localize_script('front-end-scripts' , 'BOOKING_DATA', array(
						'all_data' => $this->reqd_all_booking_data(),
						'block_dates' => $block_dates,
						'localize_info' => $localize_info
					));

				wp_register_style( 'jquery.datetimepicker', REDQ_ROOT_URL . '/assets/css/jquery.datetimepicker.css', array(), $ver = false, $media = 'all' );
				wp_enqueue_style('jquery.datetimepicker');
			}
		}


		/**
		 * Localize all booking data
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public function reqd_all_booking_data(){

			$rental_availability = array();
			$block_dates = array();	
			$block_times = array();			

			if(get_post_type(get_the_ID()) != 'product'){
				$pid = wp_get_post_parent_id( get_the_ID() );
			}else{
				$pid = get_the_ID();
			}	


			$all_data = get_post_meta($pid,'redq_all_data',true);


			$output_date_format  = get_post_meta($pid,'redq_calendar_date_format',true);

			$args = array(
				'posts_per_page'   => -1,			
				'post_type'        => 'inventory',
				'post_parent'      => $pid,
				'post_status'      => 'publish',
				'suppress_filters' => true 
			);
			$child_posts = get_posts( $args );

			foreach ($child_posts as $key => $value) {
				$rental_date_avalability = get_post_meta( $value->ID, 'redq_rental_availability', true );
				$rental_time_availablity = get_post_meta( $value->ID, 'redq_rental_time_availability', true );


				$combined_block_dates = array();
				$combined_block_times = array();
				$block_dates_array_format = array();

                if(isset($rental_date_avalability) && !empty($rental_date_avalability)){
                	foreach ($rental_date_avalability as $date_key => $date_value) {
                		$block_dates = $this->manage_all_dates($date_value['from'], $date_value['to'], $all_data['choose_euro_format'], $output_date_format);
                		array_push($combined_block_dates, $block_dates);
                	}
                }


                foreach ($combined_block_dates as $block_date) {
					foreach ($block_date as $all_block_key => $all_block_value) {
						$block_dates_array_format[] = $all_block_value;
					}
				}

				if(isset($rental_date_avalability) && !empty($rental_date_avalability)){
					$rental_availability[$value->ID]['block_dates'] = $rental_date_avalability;
				}else{
					$rental_availability[$value->ID]['block_dates'] = array();
				}


				$time_flag = 0;
                $first_time = array();
                $block_times_merge = array();

                if(isset($rental_time_availablity) && !empty($rental_time_availablity)){
                	foreach ($rental_time_availablity as $time_key => $time_value) {
                		$block_times = $this->manage_all_times($time_value['date'] , $time_value['from'], $time_value['to']);
                		array_push($combined_block_times, $block_times);                		
                	}
                }                

                if(isset($combined_block_times) && !empty($combined_block_times)){
					foreach ($combined_block_times as $time_key => $time_value) {
						if($time_flag === 0){
							$first_time = $time_value;
							$time_flag = 1;
						}
						$block_times_merge = array_merge_recursive($first_time, $time_value);	
					}
				}
                
				
				$rental_availability[$value->ID]['block_times'] = $rental_time_availablity;
				$rental_availability[$value->ID]['only_block_dates'] = $block_dates_array_format;
				$rental_availability[$value->ID]['only_block_times'] = $block_times_merge;
			}

			$redq_booking_data = get_post_meta($pid,'redq_all_data',true);


			if(isset($rental_availability)){
				$redq_booking_data['rental_availability_control'] = $rental_availability;
			}

			$pickup_locations = WC_Product_Redq_Rental::redq_get_rental_payable_attributes('pickup_location');
			$dropoff_locations = WC_Product_Redq_Rental::redq_get_rental_payable_attributes('dropoff_location');
			$payable_resources = WC_Product_Redq_Rental::redq_get_rental_payable_attributes('resource');
			$payable_person = WC_Product_Redq_Rental::redq_get_rental_payable_attributes('person');
			$security_deposite = WC_Product_Redq_Rental::redq_get_rental_payable_attributes('SecurityDeposite');


			$redq_booking_data['pickup_locations'] = $pickup_locations;
			$redq_booking_data['dropoff_locations'] = $dropoff_locations;
			$redq_booking_data['resources_cost'] = $payable_resources;
			$redq_booking_data['person'] = $payable_person;
			$redq_booking_data['security_deposite'] = $security_deposite;

			//update_post_meta(get_the_ID(), 'redq_block_dates_and_times', $rental_availability);

			return $redq_booking_data;
		}


		/**
		 * Calculate Block Dates
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public function calculate_block_dates(){

			$block_dates = array();
			$block_times = array();
			$block_dates_final = array();
			$all_data = get_post_meta(get_the_ID(),'redq_all_data',true);
			$output_date_format  = get_post_meta(get_the_ID(),'redq_calendar_date_format',true);
			$rental_availability = get_post_meta( get_the_ID(), 'redq_block_dates_and_times', true );

			if(isset($all_data['block_rental_dates']) && !empty($all_data['block_rental_dates'])){
				$rental_block = $all_data['block_rental_dates'];
			}

			$flag = 0;
			$time_flag = 0;
			$first = array();
			$first_time = array();
			if(isset($rental_block) && $rental_block === 'yes'){
				if(isset($rental_availability) && !empty($rental_availability)){
					foreach ($rental_availability as $key => $value) {
						if($flag === 0){
							$first = $value['only_block_dates'];
							$flag = 1;
						}
						$block_dates = array_intersect($first, $value['only_block_dates']);						
					}



					// foreach ($rental_availability as $key => $value) {
					// 	if($time_flag === 0){
					// 		$first_time = $value['only_block_times'];
					// 		$time_flag = 1;
					// 	}

					// 	$block_times = array_intersect_key($first_time, $value['only_block_times']);

						
					// }	



				}



				// calculate block times
				// step 1 : array gular vitor jader key milbe tader value gula neya
				// step 2 : tarpor combined value gula theke jei value gula common 
				//	tader ke grab kora






			}
			
			$result = array();

			if(isset($block_dates) && !empty($block_dates)){
				foreach ($block_dates as $key => $value) {
					$result[] = $value; 
				}
			}


			// Merge with weekend days 
			
			$weekends = get_post_meta(get_the_ID(),'redq_rental_off_days',true);
			$off_days = array();

			if(isset($weekends) && !empty($weekends)){	
				for($j = 2016 ; $j<= 2017; $j++){
					for($i = 1; $i<= 12 ; $i++){
						foreach ($weekends as $off_key => $off_value) {
							switch ($off_value) {
								case '7':
									$day = 'sunday';
									break;
								case '1':
									$day = 'monday';								
									break;
								case '2':
									$day = 'tuesday';
									break;
								case '3':
									$day = 'wednesday';
									break;
								case '4':
									$day = 'thursday';
									break;
								case '5':
									$day = 'friday';
									break;
								case '6':
									$day = 'saturday';
									break;
								default:

									break;
							}
							foreach (WC_Product_Redq_Rental::getWednesdays($j, $i, $day) as $wednesday) {							
							    $off_days[] = $wednesday->format($output_date_format);
							}
						}						
					}	
				}
			}
			
			$result = array_merge($result, $off_days);

			return $result;
		}


		/**
		 * Manage all Block Dates
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public function manage_all_dates($start_dates, $end_dates , $choose_euro_format, $output_format , $step = '+1 day'){

			$dates = array();

			if($choose_euro_format === 'no'){
				$current = strtotime($start_dates);
		    	$last = strtotime($end_dates);
			}else{
				$current = strtotime(str_replace('/' , '.' , $start_dates));
		    	$last = strtotime(str_replace('/' , '.' , $end_dates));
			}

		    while( $current <= $last ) {

		        $dates[] = date($output_format, $current);
		        $current = strtotime($step, $current);
		    }


			return $dates;
		}


		/**
		 * Manage all Block Times
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public function manage_all_times($date, $start_time , $end_time , $step = 5){

			$times = array();
			$block_times = array();
			
			$start_exp = explode(':', $start_time);
			$end_exp = explode(':', $end_time);

			$start_hour = $start_exp[0];
			$start_min = $start_exp[1];
			$end_hour = $end_exp[0];
			$end_min = $end_exp[1];

			


			for ($hour= $start_hour; $hour <= $end_hour ; $hour++) { 
				for ($minute= $start_min; $minute <= 60 ; $minute = $minute + 5) { 
					
					$com = $hour .':'. $minute;
					array_push($times, $com);
				}
			}

			$block_times[$date] = $times;

			return $block_times;
		}


		/**
		 * Add to cart page show in fornt-end
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function redq_add_to_cart(){
			wc_get_template( 'single-product/add-to-cart/redq_rental.php',$args = array(), $template_path = '', REDQ_PACKAGE_TEMPLATE_PATH);
		}


		/**
		 * Update price according to pircing type
		 *
		 * @since 1.0.0
		 * @return null
		 */
		public function redq_update_prices(){
			$post_id = get_the_ID();
			$pricing_type = get_post_meta(get_the_ID(),'pricing_type',true);

			$product_type = get_product($post_id)->product_type;
			
			if(isset($product_type) && $product_type === 'redq_rental'){			

				if($pricing_type == 'general_pricing'){
					$general_pricing = get_post_meta($post_id,'general_price',true);
					update_post_meta($post_id,'_price',$general_pricing);
				}

				if($pricing_type === 'daily_pricing'){
					$daily_pricing = get_post_meta($post_id,'redq_daily_pricing',true);
					$today = date('N');
					switch ($today) {
						case '7':
							update_post_meta($post_id, '_price' , $daily_pricing['sunday']);
							break;
						case '1':
							update_post_meta($post_id, '_price' , $daily_pricing['monday']);
							break;
						case '2':
							update_post_meta($post_id, '_price' , $daily_pricing['tuesday']);
							break;
						case '3':
							update_post_meta($post_id, '_price' , $daily_pricing['wednesday']);
							break;
						case '4':
							update_post_meta($post_id, '_price' , $daily_pricing['thursday']);
							break;
						case '5':
							update_post_meta($post_id, '_price' , $daily_pricing['friday']);
							break;
						case '6':
							update_post_meta($post_id, '_price' , $daily_pricing['saturday']);
							break;
						default:
							update_post_meta($post_id, '_price' , 'Daily price not set');
							break;
					}
				}

				if($pricing_type === 'monthly_pricing'){
					$monthly_pricing = get_post_meta($post_id,'redq_monthly_pricing',true);
					$current_month = date('m');
					switch ($current_month) {
						case '1':
							update_post_meta($post_id, '_price' , $monthly_pricing['january']);
							break;
						case '2':
							update_post_meta($post_id, '_price' , $monthly_pricing['february']);
							break;
						case '3':
							update_post_meta($post_id, '_price' , $monthly_pricing['march']);
							break;
						case '4':
							update_post_meta($post_id, '_price' , $monthly_pricing['april']);
							break;
						case '5':
							update_post_meta($post_id, '_price' , $monthly_pricing['may']);
							break;
						case '6':
							update_post_meta($post_id, '_price' , $monthly_pricing['june']);
							break;
						case '7':
							update_post_meta($post_id, '_price' , $monthly_pricing['july']);
							break;
						case '8':
							update_post_meta($post_id, '_price' , $monthly_pricing['august']);
							break;
						case '9':
							update_post_meta($post_id, '_price' , $monthly_pricing['september']);
							break;
						case '10':
							update_post_meta($post_id, '_price' , $monthly_pricing['october']);
							break;
						case '11':
							update_post_meta($post_id, '_price' , $monthly_pricing['november']);
							break;
						case '12':
							update_post_meta($post_id, '_price' , $monthly_pricing['december']);
							break;
						default:
							update_post_meta($post_id, '_price' , 'Daily price not set');
							break;
					}
				}

				if($pricing_type === 'days_range'){
					$day_ranges_cost = get_post_meta($post_id,'redq_day_ranges_cost',true);
					update_post_meta($post_id, '_price' , $day_ranges_cost[0]['range_cost']);
				}

			}
		}

		/**
		 * Support lagnuages for inventory 
		 *
		 * @since 1.0.0
		 * @return null
		 */
        public function redq_support_multilanguages() {
            load_plugin_textdomain( 'redq-rental', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }


        /**
		 * Arrage inventory resouces
		 *
		 * @since 2.0.0
		 * @return null
		 */
        public function redq_arrange_inventory_resources() {

        	$resource_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
			$selected_terms = array();	

			$resource_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
			$selected_terms = array();

			if(is_array($resource_identifiers) && !empty($resource_identifiers)){
				foreach ($resource_identifiers as $resource_key => $resource_value) {
					$args = array(
								'orderby'           => 'name', 
								'order'             => 'ASC',
								'fields'      => 'all',       
							); 
					if(taxonomy_exists('resource')){
						$terms = wp_get_post_terms( $resource_value['inventory_id'], 'resource', $args );
					}

					if(isset($terms) && is_array($terms)){
						foreach ($terms as $term_key => $term_value) {
							$selected_terms[] = $term_value;
						}
					}			
				}
			}


			$unique = array_map("unserialize", array_unique(array_map("serialize", $selected_terms)));
			$unique_selected_terms = array();

			foreach ($unique as $key => $value) {
				$unique_selected_terms[] = $value;
			}

			$resources = array();

			if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
				foreach ($unique_selected_terms as $key => $value) {
					$term_id = $value->term_id;
					$resource_cost = get_term_meta($term_id, 'inventory_resource_cost_termmeta', true);
					$resource_applicable = get_term_meta($term_id, 'inventory_price_applicable_term_meta', true);
					$resource_hourly_cost = get_term_meta($term_id, 'inventory_hourly_cost_termmeta', true);
					$resources[$key]['resource_name'] = $value->name;
					$resources[$key]['resource_cost'] = $resource_cost;
					$resources[$key]['resource_applicable'] = $resource_applicable;
					$resources[$key]['resource_hourly_cost'] = $resource_hourly_cost;
				}
			}


			return $resources;
        }


        /**
		 * Arrage inventory person
		 *
		 * @since 2.0.0
		 * @return null
		 */
        public function redq_arrange_inventory_person() {

        	$resource_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
			$selected_terms = array();

			if(is_array($resource_identifiers) && !empty($resource_identifiers)){
				foreach ($resource_identifiers as $resource_key => $resource_value) {				
					$args = array(
								'orderby'           => 'name', 
								'order'             => 'ASC',
								'fields'      => 'all',       
							); 
					if(taxonomy_exists('person')){
						$terms = wp_get_post_terms( $resource_value['inventory_id'], 'person', $args );
					}

					if(isset($terms) && is_array($terms)){
						foreach ($terms as $term_key => $term_value) {
							$selected_terms[] = $term_value;
						}
					}			
				}
			}


			$unique = array_map("unserialize", array_unique(array_map("serialize", $selected_terms)));
			$unique_selected_terms = array();

			foreach ($unique as $key => $value) {
				$unique_selected_terms[] = $value;
			}

			$person_cost = array();

			if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
				foreach ($unique_selected_terms as $key => $value) {
					$term_id = $value->term_id;
					$payable = get_term_meta($term_id, 'inventory_person_payable_or_not', true);
					$cost = get_term_meta($term_id, 'inventory_person_cost_termmeta', true);
					$applicable = get_term_meta($term_id, 'inventory_person_price_applicable_term_meta', true);
					$hourly_cost = get_term_meta($term_id, 'inventory_peroson_hourly_cost_termmeta', true);				
					$person_cost[$key]['person_count'] = $value->name;
					$person_cost[$key]['person_payable'] = $payable;
					$person_cost[$key]['person_cost'] = $cost;
					$person_cost[$key]['person_cost_applicable'] = $applicable;
					$person_cost[$key]['person_hourly_cost'] = $hourly_cost;
				}
			}

			return $person_cost;
        	
        }



        /**
		 * Show all booking data on full calendar
		 *
		 * @since 2.4.0
		 * @return null
		 */
        public function redq_show_rental_data_on_full_calendar($hook){

        	if( 'toplevel_page_rnb_admin' === $hook ) {
		        wp_register_script( 'moment', REDQ_ROOT_URL. '/assets/js/moment.js', array('jquery'), $ver = true, true);
		        wp_enqueue_script( 'moment' );

		        wp_register_style('qtip2', '//cdnjs.cloudflare.com/ajax/libs/qtip2/3.0.3/jquery.qtip.min.css', array(), $ver = false, $media = 'all');
		        wp_enqueue_style('qtip2');

		        wp_register_script( 'qtip2', '//cdn.jsdelivr.net/qtip2/3.0.3/jquery.qtip.min.js', array('jquery', 'moment'), $ver = true, true);
		        wp_enqueue_script( 'qtip2' );

		        wp_register_style('fullcalendar', REDQ_ROOT_URL. '/assets/css/fullcalendar.css', array(), $ver = false, $media = 'all');
		        wp_enqueue_style('fullcalendar');

		        wp_register_script( 'fullcalendar', '//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.7.2/fullcalendar.min.js', array('jquery', 'moment', 'qtip2'), $ver = true, true);
		        wp_enqueue_script( 'fullcalendar' );

		        wp_register_style('magnific-popup', REDQ_ROOT_URL. '/assets/css/magnific-popup.css', array(), $ver = false, $media = 'all');
		        wp_enqueue_style('magnific-popup');

		        wp_register_script( 'magnific-popup', REDQ_ROOT_URL. '/assets/js/jquery.magnific-popup.min.js', array('jquery'), $ver = false, true);
		        wp_enqueue_script( 'magnific-popup' );

		        wp_register_script( 'redq-admin-page', REDQ_ROOT_URL. '/assets/js/admin-page.js', array('jquery'), $ver = false, true);
		        wp_enqueue_script( 'redq-admin-page' );

		        $args = array(
					'post_type' => 'shop_order',
					'post_status' => 'any',
					'posts_per_page' => -1,
		        );
		         
		        $orders = get_posts($args);
		        
		        $fullcalendar = array();

		        if(isset($orders) && !empty($orders)){
			        foreach($orders as $o) {
			          
						$order_id = $o->ID;
						$order = new WC_Order($order_id);

						foreach( $order->get_items() as $item ) {

							$item_meta_array = $item['item_meta_array'];
							foreach ($item_meta_array as $item_meta) {

							  	if( $item_meta->key === '_product_id' ) {

								    $pro = get_product($item_meta->value);	
							  		$product_type = '';

							  		if(isset($pro) && !empty($pro)){
							  			$product_type = $pro->product_type;
							  		}

								    if( $product_type === 'redq_rental' ) {
										$fullcalendar[$order_id]['post_status'] = $o->post_status;
										$fullcalendar[$order_id]['title'] = $item['name'];
										$fullcalendar[$order_id]['link'] = get_the_permalink($item_meta->value);
										$fullcalendar[$order_id]['id'] = $item_meta->value;
										$fullcalendar[$order_id]['description'] = '<table cellspacing="0" class="redq-rental-display-meta"><tbody><tr><th>Order ID:</th><td>#'.$order_id.'</td></tr>';

										foreach ($item_meta_array as $v) {
											if( $v->key !== '_qty'
												&& $v->key !== '_tax_class'
												&& $v->key !== '_product_id'
												&& $v->key !== '_variation_id'
												&& $v->key !== '_line_subtotal'
												&& $v->key !== '_line_total'
												&& $v->key !== '_line_subtotal_tax'
												&& $v->key !== '_line_tax'
												&& $v->key !== '_line_tax_data'
											){
												$fullcalendar[$order_id]['description'] .= '<tr><th>'.$v->key.'</th><td>'.$v->value.'</td></tr>';  
											}

											$product_id = $item_meta->value;
											$euro_date_format = get_post_meta($product_id, 'redq_choose_european_date_format', true);

											if( $v->key === 'Pickup Date') {									

												if($euro_date_format == 'no'){
													$fullcalendar[$order_id]['start'] = DateTime::createFromFormat('m/d/Y', $v->value)->format('Y-m-d');
												}else{
													$start = date('m/d/Y', strtotime(str_replace('/' , '.' , $v->value)));
													$fullcalendar[$order_id]['start'] = DateTime::createFromFormat('m/d/Y', $start)->format('Y-m-d');
												}			  	
											}
											if( $v->key === 'Pickup Time') {
											  	$fullcalendar[$order_id]['start'] .= 'T'.$v->value;
											}
											if( $v->key === 'Drop-off Date') {
												
												if($euro_date_format == 'no'){
													$fullcalendar[$order_id]['end'] = DateTime::createFromFormat('m/d/Y', $v->value)->format('Y-m-d');
												}else{
													$start = date('m/d/Y', strtotime(str_replace('/' , '.' , $v->value)));
													$fullcalendar[$order_id]['end'] = DateTime::createFromFormat('m/d/Y', $start)->format('Y-m-d');
												}											  	
											}
											if( $v->key === 'Drop-off Time') {
											  	$fullcalendar[$order_id]['end'] .= 'T'.$v->value;
											}
											$fullcalendar[$order_id]['url'] = admin_url( 'post.php?post=' . absint( $order->id ) . '&action=edit' );
										}

										$order_total = $order->get_formatted_order_total();
										$fullcalendar[$order_id]['description'] .= '<tr><th>Order Total</th><td>'.$order_total.'</td>';
										$fullcalendar[$order_id]['description'] .= '</tbody></table>';
									}
							  	}
							}
						}
					}
				}

				wp_localize_script( 'redq-admin-page', 'REDQRENTALFULLCALENDER', $fullcalendar );
			}

        }

      

	}

	new RedQ_Rental_And_Bookings();

}else{
    function redq_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'Please Install WooCommerce First before activating this Plugin. You can download WooCommerce from <a href="http://wordpress.org/plugins/woocommerce/">here</a>.', 'redq-rental' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'redq_admin_notice' );
}
