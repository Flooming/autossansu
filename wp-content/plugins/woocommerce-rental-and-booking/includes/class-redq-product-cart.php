<?php 
if ( ! defined( 'ABSPATH' ) )
	exit;
/**
 * Hande cart page
 *  
 * @version 1.0.0
 * @since 1.0.0
 */

class WC_Redq_Rental_Cart {

	public function __construct(){
		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'redq_rental_add_cart_item_data' ), 10, 2 );
		add_filter( 'woocommerce_add_cart_item', array( $this, 'redq_rental_add_cart_item' ), 10, 1 );
		add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'redq_rental_get_cart_item_from_session' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_quantity', array($this, 'redq_cart_item_quantity'), 10, 2 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'redq_rental_get_item_data' ), 10, 2 );
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'redq_rental_order_item_meta' ), 10, 2 );
	}


	/**
	 * Insert posted data into cart item meta
	 *
	 * @param  string $product_id , array $cart_item_meta
	 * @return array
	 */
	public function redq_rental_add_cart_item_data($cart_item_meta, $product_id){		
		
		$product_type = get_product($product_id)->product_type;


		if(isset($product_type) && $product_type === 'redq_rental'){
			$posted_data = $this->get_posted_data($product_id,$_POST);
			$cart_item_meta['rental_data'] = $posted_data;
		}

		return $cart_item_meta;	
	}


	/**
	 * Add cart item meta
	 *
	 * @param  array $cart_item
	 * @return array
	 */
	public function redq_rental_add_cart_item($cart_item){	
		$product_id = $cart_item['data']->id;
		$product_type = get_product($product_id)->product_type;
		
		if(isset($cart_item['rental_data']['rental_days_and_costs']['cost']) && $product_type === 'redq_rental'){
			$cart_item['data']->set_price( $cart_item['rental_data']['rental_days_and_costs']['cost'] );
		}

		return $cart_item;
	}


	/**
	 * Get item data from session
	 *
	 * @param  array $cart_item
	 * @return array
	 */
	public function redq_rental_get_cart_item_from_session( $cart_item, $values ) {		
		if ( ! empty( $values['rental_data'] ) ) {
			$cart_item  = $this->redq_rental_add_cart_item( $cart_item );
		}
		return $cart_item;
	}


	/**
	 * Set quanlity always 1
	 *
	 * @param  array $cart_item_key , int $product_quantity
	 * @return int
	 */
	public function redq_cart_item_quantity($product_quantity, $cart_item_key){
		global $woocommerce;		
		$cart_details = $woocommerce->cart->cart_contents;
		if(isset($cart_details)){
			foreach ($cart_details as $key => $value) {
				if($key === $cart_item_key){	
					$product_id = $value['product_id'];
					$product_type = get_product($product_id)->product_type;
					if($product_type === 'redq_rental'){
						return 1;						
					}else{
						return $product_quantity;
					}
				}			
			}
		}
	}


	/**
	 * Show cart item data in cart and checkout page
	 *
	 * @param  blank array $custom_data , array $cart_item
	 * @return array
	 */
	public function redq_rental_get_item_data($custom_data, $cart_item){		
		
		$product_id = $cart_item['data']->id;
		$product_type = get_product($product_id)->product_type;

		if(isset($product_type) && $product_type === 'redq_rental'){

			$rental_data = $cart_item['rental_data'];
			
			$all_data = get_post_meta($product_id,'redq_all_data',true);
			$options_data = $all_data['local_settings_data'];
			

			if(isset($rental_data) && !empty($rental_data)){				

				if(isset($rental_data['pickup_location'])){
					$custom_data[] = array(
						'name'    => $options_data['pickup_location_title'] ? $options_data['pickup_location_title'] : __('Pickup Location','redq-rental'),
						'value'   => $rental_data['pickup_location']['address'],
						'display' => ''
					);
				}

				if(isset($rental_data['pickup_location']) && !empty($rental_data['pickup_location']['cost'])){
					$custom_data[] = array(
						'name'    => $options_data['pickup_location_title'].__(' Cost','redq-rental'),
						'value'   => wc_price($rental_data['pickup_location']['cost']),
						'display' => ''
					);
				}

				if(isset($rental_data['dropoff_location'])){
					$custom_data[] = array(
						'name'    => $options_data['dropoff_location_title'] ? $options_data['dropoff_location_title'] : __('Drop Off Location','redq-rental'),
						'value'   => $rental_data['dropoff_location']['address'],
						'display' => ''
					);
				}

				if(isset($rental_data['dropoff_location']) && !empty($rental_data['dropoff_location']['cost'])){
					$custom_data[] = array(
						'name'    => $options_data['dropoff_location_title'].__(' Cost','redq-rental'),
						'value'   => wc_price($rental_data['dropoff_location']['cost']),
						'display' => ''
					);
				}

				if(isset($rental_data['payable_resource'])){
					$resource_name = '';
					foreach ($rental_data['payable_resource'] as $key => $value) {
						if($value['cost_multiply'] === 'per_day'){
							$resource_name .= $value['resource_name'].' ( '.wc_price($value['resource_cost']).' - '.__('Per Day','redq-rental').' )'.' , <br> ';
						}else{
							$resource_name .= $value['resource_name'].' ( '.wc_price($value['resource_cost']).' - '.__('One Time','redq-rental').' )'.' , <br> ';	
						}
					}
					$custom_data[] = array(
						'name'    => $options_data['resources_heading_title'] ? $options_data['resources_heading_title'] : __('Resources','redq-rental'),
						'value'   => $resource_name,
						'display' => ''
					);
				}

				if(isset($rental_data['payable_security_deposites'])){
					$security_deposite_name = '';
					foreach ($rental_data['payable_security_deposites'] as $key => $value) {
						if($value['cost_multiply'] === 'per_day'){
							$security_deposite_name .= $value['security_deposite_name'].' ( '.wc_price($value['security_deposite_cost']).' - '.__('Per Day','redq-rental').' )'.' , <br> ';
						}else{
							$security_deposite_name .= $value['security_deposite_name'].' ( '.wc_price($value['security_deposite_cost']).' - '.__('One Time','redq-rental').' )'.' , <br> ';	
						}						
					}
					$custom_data[] = array(
						'name'    => $options_data['deposite_heading_title'] ? $options_data['deposite_heading_title'] : __('Security Deposit','redq-rental'),
						'value'   => $security_deposite_name,
						'display' => ''
					);
				}

				if(isset($rental_data['payable_person'])){
					$custom_data[] = array(
						'name'    => $options_data['person_heading_title'] ? $options_data['person_heading_title'] : __('Person ','redq-rental'),
						'value'   => $rental_data['payable_person']['person_count'],
						'display' => ''
					);
				}

				if(isset($rental_data['pickup_date']) && $options_data['show_pickup_date'] === 'open'){

					$pickup_date_time = $rental_data['pickup_date'];
					
					if(isset($rental_data['pickup_time'])){
						$pickup_date_time = $rental_data['pickup_date'].' at '. $rental_data['pickup_time'];
					}
					$custom_data[] = array(
						'name'    => $options_data['pickup_date_title'] ? $options_data['pickup_date_title'] : __('Pickup Date & Time','redq-rental'),
						'value'   => $pickup_date_time,
						'display' => ''
					);
				}				

				if(isset($rental_data['dropoff_date']) && $options_data['show_dropoff_date'] === 'open'){

					$return_date_time = $rental_data['dropoff_date'];
					
					if(isset($rental_data['dropoff_time'])){
						$return_date_time = $rental_data['dropoff_date'].' at '. $rental_data['dropoff_time'];
					}

					$custom_data[] = array(
						'name'    => $options_data['dropoff_date_title'] ? $options_data['dropoff_date_title'] : __('Dropoff Date & Time','redq-rental'),
						'value'   => $return_date_time,
						'display' => ''
					);
				}

				if(isset($rental_data['rental_days_and_costs'])){
					if($rental_data['rental_days_and_costs']['days'] > 0){
						$custom_data[] = array(
							'name'    => __('Total Days','redq-rental'),
							'value'   => $rental_data['rental_days_and_costs']['days'],
							'display' => ''
						);
					}else{
						$custom_data[] = array(
							'name'    => __('Total Hours','redq-rental'),
							'value'   => $rental_data['rental_days_and_costs']['hours'],
							'display' => ''
						);
					}

					if(!empty($rental_data['rental_days_and_costs']['due_payment'])){
						$custom_data[] = array(
							'name'    => __('Payment Due','redq-rental'),
							'value'   => wc_price($rental_data['rental_days_and_costs']['due_payment']),
							'display' => ''
						);
					}					
				}
			}
		}



		return $custom_data;
	}



	/**
	 * order_item_meta function
	 *
	 * @param  string $item_id , array $values
	 * @return array
	 */
	public function redq_rental_order_item_meta($order_id, $values){
		
		
		$product_id = $values['data']->id;
		$product_type = get_product($product_id)->product_type;		

		if(isset($product_type) && $product_type === 'redq_rental'){

			$rental_data = $values['rental_data'];

			$all_data = get_post_meta($product_id,'redq_all_data',true);
			$options_data = $all_data['local_settings_data'];

			if(isset($rental_data['pickup_location'])){			
				woocommerce_add_order_item_meta( $order_id, $options_data['pickup_location_title'] ? $options_data['pickup_location_title'] : __('Pickup Location','redq-rental'), $rental_data['pickup_location']['address'] );	
			}

			if(isset($rental_data['pickup_location']) && !empty($rental_data['pickup_location']['cost'])){			
				woocommerce_add_order_item_meta( $order_id, $options_data['pickup_location_title'].__(' Cost','redq-rental'), wc_price($rental_data['pickup_location']['cost']) );	
			}

			if(isset($rental_data['dropoff_location'])){			
				woocommerce_add_order_item_meta( $order_id, $options_data['dropoff_location_title'] ? $options_data['dropoff_location_title'] : __('Drop-off Location','redq-rental'), $rental_data['dropoff_location']['address'] );	
			}

			if(isset($rental_data['dropoff_location']) && !empty($rental_data['dropoff_location']['cost'])){			
				woocommerce_add_order_item_meta( $order_id, $options_data['dropoff_location_title'].__(' Cost','redq-rental'), wc_price($rental_data['dropoff_location']['cost']) );	
			}

			if(isset($rental_data['payable_resource'])){
				$resource_name = '';
				foreach ($rental_data['payable_resource'] as $key => $value) {
					if($value['cost_multiply'] === 'per_day'){
						$resource_name .= $value['resource_name'].' ( '.wc_price($value['resource_cost']).' - '.__('Per Day','redq-rental').' )'.' , <br> ';
					}else{
						$resource_name .= $value['resource_name'].' ( '.wc_price($value['resource_cost']).' - '.__('One Time','redq-rental').' )'.' , <br> ';	
					}
				}			
				woocommerce_add_order_item_meta( $order_id, $options_data['resources_heading_title'] ? $options_data['resources_heading_title'] : __('Resources','redq-rental'), $resource_name );
			}

			if(isset($rental_data['payable_security_deposites'])){
				$security_deposite_name = '';
				foreach ($rental_data['payable_security_deposites'] as $key => $value) {
					if($value['cost_multiply'] === 'per_day'){
						$security_deposite_name .= $value['security_deposite_name'].' ( '.wc_price($value['security_deposite_cost']).' - '.__('Per Day','redq-rental').' )'.' , <br> ';
					}else{
						$security_deposite_name .= $value['security_deposite_name'].' ( '.wc_price($value['security_deposite_cost']).' - '.__('One Time','redq-rental').' )'.' , <br> ';	
					}
				}			
				woocommerce_add_order_item_meta( $order_id, $options_data['deposite_heading_title'] ? $options_data['deposite_heading_title'] : __(' Deposit ','redq-rental'), $security_deposite_name );
			}

			if(isset($rental_data['payable_person'])){			
				woocommerce_add_order_item_meta( $order_id, $options_data['person_heading_title'] ? $options_data['person_heading_title'] : __('Person','redq-rental'), $rental_data['payable_person']['person_count'] );	
			}

			if(isset($rental_data['pickup_date']) && $options_data['show_pickup_date'] === 'open'){

				$pickup_date_time = $rental_data['pickup_date'];
					
				if(isset($rental_data['pickup_time'])){
					$pickup_date_time = $rental_data['pickup_date'].' at '. $rental_data['pickup_time'];
				}

				woocommerce_add_order_item_meta( $order_id, $options_data['pickup_date_title'] ? $options_data['pickup_date_title'] : __('Pickup Date','redq-rental'), $pickup_date_time );	
			}			

			if(isset($rental_data['dropoff_date']) && $options_data['show_dropoff_date'] === 'open'){
				
				$return_date_time = $rental_data['dropoff_date'];
					
				if(isset($rental_data['dropoff_time'])){
					$return_date_time = $rental_data['dropoff_date'].' at '. $rental_data['dropoff_time'];
				}				

				woocommerce_add_order_item_meta( $order_id, $options_data['dropoff_date_title'] ? $options_data['dropoff_date_title'] : __('Drop-off Date','redq-rental'), $return_date_time );	
			}	

			if(isset($rental_data['rental_days_and_costs'])){
				if($rental_data['rental_days_and_costs']['days'] > 0){
					woocommerce_add_order_item_meta( $order_id, __('Total Days','redq-rental'), $rental_data['rental_days_and_costs']['days'] );
				}else{
					woocommerce_add_order_item_meta( $order_id, __('Total Hours','redq-rental'), $rental_data['rental_days_and_costs']['hours'] );	
				}
				
				if(!empty($rental_data['rental_days_and_costs']['due_payment'])){
					woocommerce_add_order_item_meta( $order_id, __('Payment Due','redq-rental'), wc_price($rental_data['rental_days_and_costs']['due_payment']));	
				}							
			}


            

			// start inventory post meta update from here

			$output_date_format  = get_post_meta($values['product_id'],'redq_calendar_date_format',true);
            $new = new RedQ_Rental_And_Bookings();
            
			$block_dates_times = get_post_meta($values['product_id'],'redq_block_dates_and_times',true);		



			// update rental availability
			$rental_availability = get_post_meta( $values['product_id'], 'redq_rental_availability', true );
			$date_format = get_post_meta( $values['product_id'], 'redq_calendar_date_format', true );
			$choose_euro_format = get_post_meta( $values['product_id'], 'redq_choose_european_date_format', true );
			$enable_single_day_time_booking = get_post_meta($values['product_id'],'redq_rental_local_enable_single_day_time_based_booking',true);


			if($choose_euro_format === 'no'){
				
				$pdate = $rental_data['pickup_date'];
				$ddate = $rental_data['dropoff_date'];
				$ptime = $rental_data['pickup_time'];
				$dtime = $rental_data['dropoff_time'];
				

				$formated_pickup_time  = date("H:i", strtotime($ptime));
			    $formated_dropoff_time = date("H:i", strtotime($dtime));
			    $pickup_date_time  = strtotime("$pdate $formated_pickup_time");
			    $dropoff_date_time = strtotime("$ddate $formated_dropoff_time");

			    $hours = abs($pickup_date_time - $dropoff_date_time)/(60*60);	    
			    $total_hours = 0;

			  

			    if($hours < 24){
			    	$days = 0;
			    	$total_hours = ceil($hours);

			    	if($enable_single_day_time_booking == 'open'){
				    	$booked_dates_ara = $new->manage_all_dates($rental_data['pickup_date'], date($date_format, strtotime($rental_data['dropoff_date'])), 'no', $output_date_format);
				    	foreach ($booked_dates_ara as $booked_dates_key => $booked_dates_value) {
							foreach ($block_dates_times as $block_dates_time_key => $block_dates_time_value) {
								
								if(!in_array($booked_dates_value, $block_dates_time_value['only_block_dates'])){						
									$ara = array(
										'type' => 'custom_date',
										'from' => date($output_date_format, strtotime($booked_dates_value)),
										'to'   => date($output_date_format, strtotime($booked_dates_value)),
										'rentable' => 'no',
										'post_id' => $block_dates_time_key
									);

									array_push($block_dates_times[$block_dates_time_key]['block_dates'], $ara);
									$block_dates_times[$block_dates_time_key]['only_block_dates'][] = $booked_dates_value;
									break;
								}
							}
						}
					}					

			    }else{

			    	$days = intval($hours/24);
			    	$extra_hours = $hours%24;			    	

			    	if($enable_single_day_time_booking == 'open'){
			    		$booked_dates_ara = $new->manage_all_dates($rental_data['pickup_date'], date($date_format, strtotime($rental_data['dropoff_date'])), 'no', $output_date_format);
			    	}else{
			    		if($extra_hours > floatval($rental_data['max_hours_late'])){
				    		$booked_dates_ara = $new->manage_all_dates($rental_data['pickup_date'], date($date_format, strtotime($rental_data['dropoff_date'])), 'no', $output_date_format);
				    	}else{
				    		$booked_dates_ara = $new->manage_all_dates($rental_data['pickup_date'], date($date_format, strtotime($rental_data['dropoff_date'].'-1 day')), 'no', $output_date_format);					        		
				    	}
			    	}

			    	foreach ($booked_dates_ara as $booked_dates_key => $booked_dates_value) {
						foreach ($block_dates_times as $block_dates_time_key => $block_dates_time_value) {
							
							if(!in_array($booked_dates_value, $block_dates_time_value['only_block_dates'])){						
								$ara = array(
									'type' => 'custom_date',
									'from' => date($output_date_format, strtotime($booked_dates_value)),
									'to'   => date($output_date_format, strtotime($booked_dates_value)),
									'rentable' => 'no',
									'post_id' => $block_dates_time_key
								);

								array_push($block_dates_times[$block_dates_time_key]['block_dates'], $ara);
								$block_dates_times[$block_dates_time_key]['only_block_dates'][] = $booked_dates_value;
								break;
							}
						}
					}


			    } 

			}else{


				$pickup_date  = str_replace('/' , '.' , $rental_data['pickup_date']); 
				$dropoff_date = str_replace('/' , '.' , $rental_data['dropoff_date']); 

				$pdate = $pickup_date;
				$ddate = $dropoff_date;
				$ptime = $rental_data['pickup_time'];
				$dtime = $rental_data['dropoff_time'];

				$formated_pickup_time  = date("H:i", strtotime($ptime));
			    $formated_dropoff_time = date("H:i", strtotime($dtime));
			    $pickup_date_time  = strtotime("$pdate $formated_pickup_time");
			    $dropoff_date_time = strtotime("$ddate $formated_dropoff_time");

			    $hours = abs($pickup_date_time - $dropoff_date_time)/(60*60);	    
			    $total_hours = 0;		    

			    if($hours < 24){
			    	$days = 0;
			    	$total_hours = ceil($hours);

			    	if($enable_single_day_time_booking == 'open'){
				    	$booked_dates_ara = $new->manage_all_dates($pickup_date, date($date_format, strtotime($dropoff_date)), $choose_euro_format, $date_format);
				    	foreach ($booked_dates_ara as $booked_dates_key => $booked_dates_value) {
							foreach ($block_dates_times as $block_dates_time_key => $block_dates_time_value) {
								
								if(!in_array($booked_dates_value, $block_dates_time_value['only_block_dates'])){						
									$ara = array(
										'type' => 'custom_date',
										'from' => date($output_date_format, strtotime(str_replace('/' , '.' , $booked_dates_value))),
										'to'   => date($output_date_format, strtotime(str_replace('/' , '.' , $booked_dates_value))),
										'rentable' => 'no',
										'post_id' => $block_dates_time_key
									);

									array_push($block_dates_times[$block_dates_time_key]['block_dates'], $ara);
									$block_dates_times[$block_dates_time_key]['only_block_dates'][] = $booked_dates_value;
									break;
								}
							}
						}
					}

			    	// $time_blockings  = array();
			    	// foreach ($block_dates_times as $block_dates_time_key => $block_dates_time_value) {
			    	// 	array_push($block_dates_times[$block_dates_time_key]['block_times'], $rental_data['pickup_date']);	
			    	// 	break;
			    	// }

			    }else{

			    	$days = intval($hours/24);
			    	$extra_hours = $hours%24;			    	

			    	if($enable_single_day_time_booking == 'open'){
			    		$booked_dates_ara = $new->manage_all_dates($pickup_date, date($date_format, strtotime($dropoff_date)), $choose_euro_format, $date_format);
			    	}else{
				    	if($extra_hours > floatval($rental_data['max_hours_late'])){
				    		$booked_dates_ara = $new->manage_all_dates($pickup_date, date($date_format, strtotime($dropoff_date)), $choose_euro_format, $date_format);
				    	}else{
				    		$booked_dates_ara = $new->manage_all_dates($pickup_date, date($date_format, strtotime($dropoff_date.'-1 day')), $choose_euro_format, $date_format);					        		
				    	}
			    	}
			    	

			    	foreach ($booked_dates_ara as $booked_dates_key => $booked_dates_value) {
						foreach ($block_dates_times as $block_dates_time_key => $block_dates_time_value) {
							
							if(!in_array($booked_dates_value, $block_dates_time_value['only_block_dates'])){						
								$ara = array(
									'type' => 'custom_date',
									'from' => date($output_date_format, strtotime(str_replace('/' , '.' , $booked_dates_value))),
									'to'   => date($output_date_format, strtotime(str_replace('/' , '.' , $booked_dates_value))),
									'rentable' => 'no',
									'post_id' => $block_dates_time_key
								);


								array_push($block_dates_times[$block_dates_time_key]['block_dates'], $ara);
								$block_dates_times[$block_dates_time_key]['only_block_dates'][] = $booked_dates_value;
								break;
							}
						}
					}
			    }
			}


			foreach ($block_dates_times as $key => $value) {				
				update_post_meta($key, 'redq_rental_availability', $value['block_dates']);
			}
			
			if(isset($block_dates_times)){
				update_post_meta( $values['product_id'], 'redq_block_dates_and_times', $block_dates_times );
			}

		}
	}


	/**
	 * Return all post data for rental
	 *
	 * @param  string $product_id , array $posted_data
	 * @return array
	 */
	public function get_posted_data($product_id , $posted_data){
		$payable_resource = array();
		$payable_security_deposites = array();
		$payable_person   = array();
		$pickup_location  = array();
		$dropoff_location = array();
		$data = array();

		$all_rental_data = get_post_meta($product_id,'redq_all_data',true);	
		
		if(isset($posted_data['extras']) && !empty($posted_data['extras'])){
			foreach ($posted_data['extras'] as $key => $value) {				
				$extras = explode('|', $value);
				$payable_resource[$key]['resource_name'] = $extras[0]; 
				$payable_resource[$key]['resource_cost'] = $extras[1];
				$payable_resource[$key]['cost_multiply'] = $extras[2];
				$payable_resource[$key]['resource_hourly_cost'] = $extras[3];
			}
			$data['payable_resource'] = $payable_resource;	
		}

		if(isset($posted_data['security_deposites']) && !empty($posted_data['security_deposites'])){
			foreach ($posted_data['security_deposites'] as $key => $value) {				
				$extras = explode('|', $value);
				$payable_security_deposites[$key]['security_deposite_name'] = $extras[0]; 
				$payable_security_deposites[$key]['security_deposite_cost'] = $extras[1];
				$payable_security_deposites[$key]['cost_multiply'] = $extras[2];
				$payable_security_deposites[$key]['security_deposite_hourly_cost'] = $extras[3];				
			}
			$data['payable_security_deposites'] = $payable_security_deposites;	
		}

		if(isset($posted_data['additional_person_info']) && !empty($posted_data['additional_person_info'])){
							
			$person = explode('|', $posted_data['additional_person_info']);
			$payable_person['person_count']  = $person[0]; 
			$payable_person['person_cost']   = $person[1];
			$payable_person['cost_multiply'] = $person[2];
			$payable_person['person_hourly_cost']   = $person[3];
			
			$data['payable_person'] = $payable_person;	
		}

		if(isset($posted_data['pickup_location']) && !empty($posted_data['pickup_location'])){

			$pickup_location_split = explode('|', $posted_data['pickup_location']);
			$pickup_location['address'] = $pickup_location_split[0]; 
			$pickup_location['title']   = $pickup_location_split[1];
			$pickup_location['cost']   = $pickup_location_split[2];

			$data['pickup_location']    = $pickup_location; 
		}

		if(isset($posted_data['dropoff_location']) && !empty($posted_data['dropoff_location'])){

			$dropoff_location_split = explode('|', $posted_data['dropoff_location']);
			$dropoff_location['address'] = $dropoff_location_split[0]; 
			$dropoff_location['title']   = $dropoff_location_split[1];
			$dropoff_location['cost']   = $dropoff_location_split[2];

			$data['dropoff_location']    = $dropoff_location; 
		}
		
		if(isset($posted_data['pickup_date']) && !empty($posted_data['pickup_date'])){
			if($all_rental_data['choose_euro_format'] == 'yes'){
				$data['pickup_date'] = str_replace('/' , '.' , $posted_data['pickup_date']); 
			}else{
				$data['pickup_date'] = $posted_data['pickup_date']; 	
			}			
		}

		if(isset($posted_data['pickup_time']) && !empty($posted_data['pickup_time'])){
			$data['pickup_time'] = $posted_data['pickup_time']; 
		}

		if(isset($posted_data['dropoff_date']) && !empty($posted_data['dropoff_date'])){			
			if($all_rental_data['choose_euro_format'] == 'yes'){
				$data['dropoff_date'] = str_replace('/' , '.' , $posted_data['dropoff_date']); 
			}else{
				$data['dropoff_date'] = $posted_data['dropoff_date']; 	
			} 
		}

		if(isset($posted_data['dropoff_time']) && !empty($posted_data['dropoff_time'])){
			$data['dropoff_time'] = $posted_data['dropoff_time']; 
		}

		if(isset($data['pickup_date']) && !empty($data['pickup_date']) && !isset($data['dropoff_date']) && empty($data['dropoff_date']) ){
			if(!isset($data['pickup_time']) || !isset($data['dropoff_time'])){
				$data['dropoff_date'] = $data['pickup_date'];
			}else{
				$data['dropoff_date'] = $data['pickup_date'];	
			}		
		}

		if(isset($data['pickup_time']) && !empty($data['pickup_time']) && !isset($data['dropoff_time']) && empty($data['dropoff_time']) ){
			$data['dropoff_time'] = $data['pickup_time'];
		}

		if(isset($data['dropoff_date']) && !empty($data['dropoff_date']) && !isset($data['pickup_date']) && empty($data['pickup_date']) ){
			if(!isset($data['pickup_time']) || !isset($data['dropoff_time'])){
				$data['pickup_date'] = $data['dropoff_date'];
			}else{
				$data['pickup_date'] = $data['dropoff_date'];
			}
		}

		if(isset($data['dropoff_time']) && !empty($data['dropoff_time']) && !isset($data['pickup_time']) && empty($data['pickup_time']) ){
			$data['pickup_time'] = $data['dropoff_time'];
		}

		$cost_calculation = $this->calculate_cost($product_id, $data);
		$data['rental_days_and_costs'] = $cost_calculation;
		$data['max_hours_late'] = get_post_meta($product_id, 'redq_max_time_late',true);

		
		if($all_rental_data['choose_euro_format'] == 'yes'){
			$data['pickup_date'] = str_replace('.' , '/' , $data['pickup_date']); 
		}else{
			$data['pickup_date'] = $data['pickup_date']; 	
		}

		if($all_rental_data['choose_euro_format'] == 'yes'){
			$data['dropoff_date'] = str_replace('.' , '/' , $data['dropoff_date']); 
		}else{
			$data['dropoff_date'] = $data['dropoff_date']; 	
		}

		return $data;
	}


	/**
	 * Return rental cost and days
	 *
	 * @param  string $key , array $data
	 * @return array
	 */
	public function calculate_cost($product_id, $data){
		
		$product_type = get_product($product_id)->product_type;

		if(isset($product_type) && $product_type === 'redq_rental'){

			$all_rental_data = get_post_meta($product_id,'redq_all_data',true);	
			$calculate_cost_and_day = array();

			if(isset($data['pickup_location'])){
				$pickup_cost = $data['pickup_location']['cost'];
			}else{
				$pickup_cost = 0;
			}

			if(isset($data['dropoff_location'])){
				$dropoff_cost = $data['dropoff_location']['cost'];
			}else{
				$dropoff_cost = 0;
			}

			if(isset($data['payable_resource'])){
				$payable_resource = $data['payable_resource'];
			}else{
				$payable_resource = array();
			}

			if(isset($data['payable_security_deposites'])){
				$payable_security_deposites = $data['payable_security_deposites'];
			}else{
				$payable_security_deposites = array();
			}

			if(isset($data['payable_person'])){
				$payable_person   = $data['payable_person'];
			}else{
				$payable_person = array();
			}

			if(isset($data['pickup_date'])){
				$pickup_date  = $data['pickup_date'];
			}else{
				$pickup_date = '';
			}
			
			if(isset($data['pickup_time'])){
				$pickup_time  = $data['pickup_time'];
			}else{
				$pickup_time = '';
			}
			
			if(isset($data['dropoff_date'])){
				$dropoff_date = $data['dropoff_date'];
			}else{
				$dropoff_date = '';
			}

			if(isset($data['dropoff_time'])){
				$dropoff_time = $data['dropoff_time']; 
			}else{
				$dropoff_time = '';
			}

			if(isset($all_rental_data['price_discount']) && $all_rental_data['price_discount']){
				$price_discount = $all_rental_data['price_discount'];
			}else{
				$price_discount = array();
			}		

			$days = $this->calculate_rental_days($data , $all_rental_data);
			$calculate_cost_and_day['days'] = $days['days'];
			$calculate_cost_and_day['hours'] = $days['hours'];	

			$pricing_type = $all_rental_data['pricing_type'];	

			if($pricing_type === 'general_pricing'){
				$general_pricing = $all_rental_data['general_pricing'];
				$hourly_pricing  = $all_rental_data['hourly_pricing'];
				$cost = $this->calculate_general_pricing_plan_cost($general_pricing, $days, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount);
			}

			if($pricing_type === 'daily_pricing'){			
				$daily_pricing_plan = $all_rental_data['daily_pricing'];
				$hourly_pricing  = $all_rental_data['hourly_pricing'];
				$cost = $this->calculate_daily_pricing_plan_cost($daily_pricing_plan, $pickup_date , $days, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount);			
			}

			if($pricing_type === 'monthly_pricing'){			
				$monthly_pricing_plan = $all_rental_data['monthly_pricing'];
				$hourly_pricing  = $all_rental_data['hourly_pricing'];
				$cost = $this->calculate_monthly_pricing_plan_cost($monthly_pricing_plan, $pickup_date , $days, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount);			
			}

			if($pricing_type === 'days_range'){	
				$day_ranges_pricing_plan = $all_rental_data['days_range_cost'];
				$hourly_pricing  = $all_rental_data['hourly_pricing'];
				$cost = $this->calculate_day_ranges_pricing_plan_cost($day_ranges_pricing_plan, $pickup_date , $days, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount);			
			}

			$pre_payment_percentage = get_option('redq_rental_global_pre_payment');

			if(empty($pre_payment_percentage)){
				$pre_payment_percentage = 100;
			}

			$instance_payment = ($cost*$pre_payment_percentage)/100;
			$due_payment = $cost - $instance_payment;

			$calculate_cost_and_day['cost'] = $instance_payment;		
			$calculate_cost_and_day['due_payment'] = $due_payment;	

			return $calculate_cost_and_day;

		}

	}



	/**
	 * Calculate total rental days
	 *
	 * @param  array $data
	 * @return string
	 */
	public function calculate_rental_days($data , $all_rental_data){

		$durations = array();
		$choose_euro_format = $all_rental_data['choose_euro_format'];
		$max_hours_late = $all_rental_data['max_time_late'];



		if($choose_euro_format === 'no'){
			if(isset($data['pickup_date'])){
				$pickup_date  = $data['pickup_date'];
			}else{
				$pickup_date = '';
			}	
			if(isset($data['dropoff_date'])){
				$dropoff_date = $data['dropoff_date'];
			}else{
				$dropoff_date = '';
			}			
		}else{
			if(isset( $data['pickup_date'])){
				$pickup_date  = date('Y/m/d', strtotime(str_replace('/', '-', $data['pickup_date'])));
			}else{
				$pickup_date = '';
			}

			if(isset($data['dropoff_date'])){
				$dropoff_date = date('Y/m/d', strtotime(str_replace('/', '-', $data['dropoff_date'])));
			}else{
				$dropoff_date = '';
			}			
		}		

		if(isset($data['pickup_time'])){
			$pickup_time  = $data['pickup_time'];
		}else{
			$pickup_time = '';
		}
		if(isset($data['dropoff_time'])){
			$dropoff_time = $data['dropoff_time']; 
		}else{
			$dropoff_time = '';
		}

		

		$formated_pickup_time  = date("H:i", strtotime($pickup_time));
	    $formated_dropoff_time = date("H:i", strtotime($dropoff_time));
	    $pickup_date_time  = strtotime("$pickup_date $formated_pickup_time");
	    $dropoff_date_time = strtotime("$dropoff_date $formated_dropoff_time");

	    $hours = abs($pickup_date_time - $dropoff_date_time)/(60*60);	    
	    $total_hours = 0;

	    $enable_single_day_time_booking = $all_rental_data['local_settings_data']['enable_single_day_time_booking'];	    

	    if($hours < 24){
	    	if($enable_single_day_time_booking == 'open'){
	    		$days = 1;
	    	}else{
	    		$days = 0;
	    	}	    	
	    	$total_hours = ceil($hours);
	    }else{
	    	$days = intval($hours/24);
	    	$extra_hours = $hours%24;

	    	if($enable_single_day_time_booking == 'open'){
	    		if($extra_hours >= floatval($max_hours_late)){
		    		$days = $days + 1;
		    	}
	    	}else{
	    		if($extra_hours > floatval($max_hours_late)){
		    		$days = $days + 1;
		    	}
	    	}	    	
	    }  

	    $durations['days'] = $days;
	    $durations['hours'] = $total_hours;	   

	    return $durations;
	}



	/**
	 * Calculate general pricing plan's cost
	 *
	 * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
	 * @return string
	 */
	public function calculate_general_pricing_plan_cost($general_pricing, $durations, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount){
		
		$days = $durations['days'];
		$hours = $durations['hours'];

		if($days > 0){
			$cost = intval($days)*floatval($general_pricing);
			$cost = $this->calculate_price_discount($cost, $price_discount, $days);
			$cost = $this->calculate_extras_cost($cost , $days, $payable_resource, $payable_person,$payable_security_deposites,$pickup_cost,$dropoff_cost);
		}else{
			$cost = intval($hours)*floatval($hourly_pricing);
			$cost = $this->calculate_hourly_extras_cost($cost , $hours, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost);
		}	
		return $cost;
	}



	/**
	 * Calculate daily pricing plan's cost
	 *
	 * @param  array $daily_pricing_plan, string $pickup_date, string $days, array $payable_resource, array $payable_person
	 * @return string
	 */
	public function calculate_daily_pricing_plan_cost($daily_pricing_plan, $pickup_date , $durations, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount){
		
		$cost = 0;
		$days = $durations['days'];
		$hours = $durations['hours'];
		
		if($days > 0){
			for($i = 0; $i < intval($days) ; $i++){
				if($i == 0){
					$day = date("N", strtotime($pickup_date));
					switch ($day) {						
						case 1:
							if($daily_pricing_plan['monday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['monday']);
							}else{
								$cost = $cost + 0;
							}		
							break;	
						case 2:
							if($daily_pricing_plan['tuesday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['tuesday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 3:
							if($daily_pricing_plan['wednesday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['wednesday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 4:
							if($daily_pricing_plan['thursday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['thursday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 5:
							if($daily_pricing_plan['friday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['friday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 6:
							if($daily_pricing_plan['saturday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['saturday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 7:
							if($daily_pricing_plan['sunday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['sunday']);
							}else{
								$cost = $cost + 0;
							}		
							break;					
						
						default:
							break;
					}
				}else{
					$day = date("N", strtotime($pickup_date." +$i day"));
					switch ($day) {
						
						case 1:
							if($daily_pricing_plan['monday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['monday']);
							}else{
								$cost = $cost + 0;
							}		
							break;	
						case 2:
							if($daily_pricing_plan['tuesday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['tuesday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 3:
							if($daily_pricing_plan['wednesday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['wednesday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 4:
							if($daily_pricing_plan['thursday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['thursday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 5:
							if($daily_pricing_plan['friday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['friday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 6:
							if($daily_pricing_plan['saturday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['saturday']);
							}else{
								$cost = $cost + 0;
							}		
							break;
						case 7:
							if($daily_pricing_plan['sunday'] != ''){
								$cost = $cost + floatval($daily_pricing_plan['sunday']);
							}else{
								$cost = $cost + 0;
							}		
							break;					
						
						default:
							break;
					}
				}			

			} //end for loop

			$cost = $this->calculate_price_discount($cost, $price_discount, $days);
			$cost = $this->calculate_extras_cost($cost , $days, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost);
		}else{
			$cost = intval($hours)*floatval($hourly_pricing);
			$cost = $this->calculate_hourly_extras_cost($cost , $hours, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost);
		}		

		return $cost;
	}



	/**
	 * Calculate monthly pricing plan's cost
	 *
	 * @param  array $monthly_pricing_plan, string $pickup_date, string $days, array $payable_resource, array $payable_person
	 * @return string
	 */
	public function calculate_monthly_pricing_plan_cost($monthly_pricing_plan, $pickup_date , $durations, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount){
		
		$cost = 0;
		$days = $durations['days'];
		$hours = $durations['hours'];

		if($days > 0){

			for($i=0 ; $i<intval($days) ; $i++){
				if($i == 0){
					$month = date("n", strtotime($pickup_date));					
					switch ($month) {
						case 1:
							if($monthly_pricing_plan['january'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['january']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 2:
							if($monthly_pricing_plan['february'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['february']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 3:
							if($monthly_pricing_plan['march'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['march']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 4:
							if($monthly_pricing_plan['april'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['april']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 5:
							if($monthly_pricing_plan['may'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['may']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 6:
							if($monthly_pricing_plan['june'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['june']);
							}else{
								$cost = $cost + 0;
							}
							break;	
						case 7:
							if($monthly_pricing_plan['july'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['july']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 8:
							if($monthly_pricing_plan['august'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['august']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 9:
							if($monthly_pricing_plan['september'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['september']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 10:
							if($monthly_pricing_plan['october'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['october']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 11:
							if($monthly_pricing_plan['november'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['november']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 12:
							if($monthly_pricing_plan['december'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['december']);
							}else{
								$cost = $cost + 0;
							}
							break;										
						
						default:						
							break;
					}


				}else{

					$month = date("n", strtotime($pickup_date." +$i day"));

					switch ($month) {
						case 1:
							if($monthly_pricing_plan['january'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['january']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 2:
							if($monthly_pricing_plan['february'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['february']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 3:
							if($monthly_pricing_plan['march'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['march']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 4:
							if($monthly_pricing_plan['april'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['april']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 5:
							if($monthly_pricing_plan['may'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['may']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 6:
							if($monthly_pricing_plan['june'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['june']);
							}else{
								$cost = $cost + 0;
							}
							break;	
						case 7:
							if($monthly_pricing_plan['july'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['july']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 8:
							if($monthly_pricing_plan['august'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['august']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 9:
							if($monthly_pricing_plan['september'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['september']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 10:
							if($monthly_pricing_plan['october'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['october']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 11:
							if($monthly_pricing_plan['november'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['november']);
							}else{
								$cost = $cost + 0;
							}
							break;
						case 12:
							if($monthly_pricing_plan['december'] != ''){
								$cost = $cost + floatval($monthly_pricing_plan['december']);
							}else{
								$cost = $cost + 0;
							}
							break;										
						
						default:						
							break;
					}

				}
			}

			$cost = $this->calculate_price_discount($cost, $price_discount, $days);
			$cost = $this->calculate_extras_cost($cost , $days, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost);	
		}else{
			$cost = intval($hours)*floatval($hourly_pricing);
			$cost = $this->calculate_hourly_extras_cost($cost , $hours, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost);
		}

		return $cost;
	}



	/**
	 * Calculate day ranges plan's cost
	 *
	 * @param  array $day_ranges_pricing_plan, string $pickup_date, string $days, array $payable_resource, array $payable_person
	 * @return string
	 */
	public function calculate_day_ranges_pricing_plan_cost($day_ranges_pricing_plan, $pickup_date , $durations, $payable_resource, $payable_person, $hourly_pricing, $payable_security_deposites,$pickup_cost,$dropoff_cost, $price_discount){

		$cost = 0;
		$flag = 0;
		$days = $durations['days'];
		$hours = $durations['hours'];				

		if($days >0 ){
			foreach ($day_ranges_pricing_plan as $key => $value) {
				if($flag == 0){
					if($value['cost_applicable'] === 'per_day'){
						if(intval($value['min_days']) <= intval($days) && intval($value['max_days']) >= intval($days)){
							$cost = floatval($value['range_cost']) * intval($days);						
							$flag = 1;
						}	
					}else{
						if(intval($value['min_days']) <= intval($days) && intval($value['max_days']) >= intval($days)){
							$cost = floatval($value['range_cost']);						
							$flag = 1;
						}	
					}					
				}
			}			

			$cost = $this->calculate_price_discount($cost, $price_discount, $days);
			$cost = $this->calculate_extras_cost($cost , $days, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost);
			
		}else{
			$cost = intval($hours)*floatval($hourly_pricing);
			$cost = $this->calculate_hourly_extras_cost($cost , $hours, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost);
		}		
		
		return $cost;
	}


	/**
	 * Calculate price discount
	 *
	 * @param  string $cost, array $price_discount, string $days
	 * @return string
	 */
	public function calculate_price_discount($cost, $price_discount, $days){
		
		$flag = 0;
		$discount_amount;
		$discount_type;

		foreach ($price_discount as $key => $value) {
			if($flag == 0){
				if(intval($value['min_days']) <= intval($days) && intval($value['max_days']) >= intval($days)){
					$discount_type = $value['discount_type'];
					$discount_amount = $value['discount_amount'];
					$flag = 1;
				}
			}
		}			
	
		if(isset($discount_type) && !empty($discount_type) && isset($discount_amount) && !empty($discount_amount)){						
			if($discount_type === 'percentage'){
				$cost = $cost - ($cost*$discount_amount)/100;
			}else{
				$cost = $cost - $discount_amount;
			}
		}		

		return $cost;
	}


	/**
	 * Calculate resource and person cost
	 *
	 * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
	 * @return string
	 */
	public function calculate_extras_cost($cost , $days, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost){
		
		if( isset($pickup_cost) && !empty($pickup_cost)){					
			$cost = floatval($cost) + floatval($pickup_cost);					
		}

		if(isset($dropoff_cost) && !empty($dropoff_cost)){					
			$cost = floatval($cost) + floatval($dropoff_cost);					
		}

		if(isset($payable_resource) && sizeof($payable_resource) != 0){
			foreach ($payable_resource as $key => $value) {
				if($value['cost_multiply'] == 'per_day'){
					$cost = floatval($cost) + intval($days)*floatval($value['resource_cost']);
				}else{
					$cost = floatval($cost) + floatval($value['resource_cost']);
				}
			}
		}

		if(isset($payable_security_deposites) && sizeof($payable_security_deposites) != 0){
			foreach ($payable_security_deposites as $key => $value) {
				if($value['cost_multiply'] == 'per_day'){
					$cost = floatval($cost) + intval($days)*floatval($value['security_deposite_cost']);
				}else{
					$cost = floatval($cost) + floatval($value['security_deposite_cost']);
				}
			}
		}

		if(isset($payable_person) && sizeof($payable_person) != 0){				
			if($payable_person['cost_multiply'] == 'per_day'){
				$cost = floatval($cost) + intval($days)*floatval($payable_person['person_cost']);
			}else{
				$cost = floatval($cost) + floatval($payable_person['person_cost']);
			}				
		}

		return $cost;
	}



	/**
	 * Calculate hourly resource and person cost
	 *
	 * @param  string $general_pricing, string $days, array $payable_resource, array $payable_person
	 * @return string
	 */
	public function calculate_hourly_extras_cost($cost , $hours, $payable_resource, $payable_person, $payable_security_deposites,$pickup_cost,$dropoff_cost){		

		if( isset($pickup_cost) && !empty($pickup_cost)){					
			$cost = floatval($cost) + floatval($pickup_cost);					
		}

		if(isset($dropoff_cost) && !empty($dropoff_cost)){					
			$cost = floatval($cost) + floatval($dropoff_cost);					
		}

		if(isset($payable_resource) && sizeof($payable_resource) != 0){
			foreach ($payable_resource as $key => $value) {
				if($value['cost_multiply'] == 'per_day'){
					$cost = floatval($cost) + intval($hours)*floatval($value['resource_hourly_cost']);
				}else{
					$cost = floatval($cost) + floatval($value['resource_cost']);
				}
			}
		}

		if(isset($payable_person) && sizeof($payable_person) != 0){				
			if($payable_person['cost_multiply'] == 'per_day'){
				$cost = floatval($cost) + intval($hours)*floatval($payable_person['person_hourly_cost']);
			}else{
				$cost = floatval($cost) + floatval($payable_person['person_cost']);
			}				
		}

		if(isset($payable_security_deposites) && sizeof($payable_security_deposites) != 0){
			foreach ($payable_security_deposites as $key => $value) {
				if($value['cost_multiply'] == 'per_day'){
					$cost = floatval($cost) + intval($hours)*floatval($value['security_deposite_hourly_cost']);
				}else{
					$cost = floatval($cost) + floatval($value['security_deposite_cost']);
				}
			}
		}	

		return $cost;
	}


}

new WC_Redq_Rental_Cart();