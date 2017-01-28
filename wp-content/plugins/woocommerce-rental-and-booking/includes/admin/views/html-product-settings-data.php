<div id="rnb_setting_tabs">
	<ul>
		<li><a href="#showorhide">Show/Hide</a></li>
		<li><a href="#physical_appearence">Physical Appearence</a></li>
		<li><a href="#logical_appearence">Logicial Appearence</a></li>
	</ul>
	<div id="showorhide">
		<?php 

			$show_pickup_date = get_post_meta(get_the_ID(),'redq_rental_local_show_pickup_date',true);
			if(empty($show_pickup_date)){
				$pickupdate = get_option('redq_rental_global_show_pickup_date');
				if(empty($pickupdate)){
					$show_pickup_date = 'open';	
				}else{
					if(get_option('redq_rental_global_show_pickup_date') === 'yes'){
						$show_pickup_date = 'open';	
					}else{
						$show_pickup_date = 'closed';
					}
				}							
			}			

			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_show_pickup_date',
		            'label'   => __( 'Show Pickup Date', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($show_pickup_date),
				) 
			);

			$show_pickup_time = get_post_meta(get_the_ID(),'redq_rental_local_show_pickup_time',true);
			if(empty($show_pickup_time)){
				$pickuptime = get_option('redq_rental_global_show_pickup_time');
				if(empty($pickuptime)){
					$show_pickup_time = 'open';	
				}else{
					if(get_option('redq_rental_global_show_pickup_time') === 'yes'){
						$show_pickup_time = 'open';	
					}else{
						$show_pickup_time = 'closed';
					}
				}				
			}

			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_show_pickup_time',
		            'label'   => __( 'Show Pickup Time', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($show_pickup_time),
				) 
			);

			$show_dropoff_date = get_post_meta(get_the_ID(),'redq_rental_local_show_dropoff_date',true);
			if(empty($show_dropoff_date)){
				$dropoffdate = get_option('redq_rental_global_show_dropoff_date');
				if(empty($dropoffdate)){
					$show_dropoff_date = 'open';
				}else{
					if(get_option('redq_rental_global_show_dropoff_date') === 'yes'){
						$show_dropoff_date = 'open';	
					}else{
						$show_dropoff_date = 'closed';
					}
				}				
			}

			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_show_dropoff_date',
		            'label'   => __( 'Show Dropoff Date', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($show_dropoff_date),
				) 
			);

			$show_dropoff_time = get_post_meta(get_the_ID(),'redq_rental_local_show_dropoff_time',true);
			if(empty($show_dropoff_time)){
				$dropofftime = get_option('redq_rental_global_show_dropoff_time');
				if(empty($dropofftime)){
					$show_dropoff_time = 'open';
				}else{
					if(get_option('redq_rental_global_show_dropoff_time') === 'yes'){
						$show_dropoff_time = 'open';	
					}else{
						$show_dropoff_time = 'closed';
					}
				}				
			}

			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_show_dropoff_time',
		            'label'   => __( 'Show Dropoff Time', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($show_dropoff_time),
				) 
			);

			$show_pricing_flip_box = get_post_meta(get_the_ID(),'redq_rental_local_show_pricing_flip_box',true);
			if(empty($show_pricing_flip_box)){
				$flipbox = get_option('redq_rental_global_show_pricing_flip_box');
				if(empty($flipbox)){
					$show_pricing_flip_box = 'open';
				}else{
					if(get_option('redq_rental_global_show_pricing_flip_box') === 'yes'){
						$show_pricing_flip_box = 'open';	
					}else{
						$show_pricing_flip_box = 'closed';
					}
				}				
			}

			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_show_pricing_flip_box',
		            'label'   => __( 'Show pricing flip box', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($show_pricing_flip_box),
				) 
			);

			$show_price_discount_on_days = get_post_meta(get_the_ID(),'redq_rental_local_show_price_discount_on_days',true);
			if(empty($show_price_discount_on_days)){
				$discount = get_option('redq_rental_global_show_price_discount');
				if(empty($discount)){
					$show_price_discount_on_days = 'open';
				}else{
					if(get_option('redq_rental_global_show_price_discount') === 'yes'){
						$show_price_discount_on_days = 'open';	
					}else{
						$show_price_discount_on_days = 'closed';
					}
				}				
			}

			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_show_price_discount_on_days',
		            'label'   => __( 'Show price discount', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($show_price_discount_on_days),
				) 
			);


			$show_price_instance_payment = get_post_meta(get_the_ID(),'redq_rental_local_show_price_instance_payment',true);
			if(empty($show_price_instance_payment)){
				$instance_payment = get_option('redq_rental_global_show_instant_payment');
				if(empty($instance_payment)){
					$show_price_instance_payment = 'open';
				}else{
					if(get_option('redq_rental_global_show_instant_payment') === 'yes'){
						$show_price_instance_payment = 'open';	
					}else{
						$show_price_instance_payment = 'closed';
					}
				}				
			}

			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_show_price_instance_payment',
		            'label'   => __( 'Show instance payment', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($show_price_instance_payment),
				) 
			);


		?>
	</div>
	<div id="physical_appearence">
		<?php 

			$pickup_location_heading_title = get_post_meta(get_the_ID(),'redq_pickup_location_heading_title',true);
			$all_options = wp_load_alloptions();

			woocommerce_wp_text_input( 
				array( 
					'id' => 'pickup_location_heading_title', 
					'name' => 'redq_pickup_location_heading_title',
					'label' => __( 'Pickup Location Heading Title', 'redq-rental' ), 
					'placeholder' => __( 'pickup location title', 'redq-rental' ), 
					'type' => 'text',
					'value' => $pickup_location_heading_title, 				
				) 
			);

			$dropoff_location_heading_title = get_post_meta(get_the_ID(),'redq_dropoff_location_heading_title',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'dropoff_location_heading_title', 
					'name' => 'redq_dropoff_location_heading_title',
					'label' => __( 'Dropoff Location Heading Title', 'redq-rental' ), 
					'placeholder' => __( 'Dropoff location title', 'redq-rental' ), 
					'type' => 'text',
					'value' => $dropoff_location_heading_title, 				
				) 
			);

			$pickup_date_heading_title = get_post_meta(get_the_ID(),'redq_pickup_date_heading_title',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'pickup_date_heading_title', 
					'name' => 'redq_pickup_date_heading_title',
					'label' => __( 'Pickup Date Heading Title', 'redq-rental' ), 
					'placeholder' => __( 'Pickup date title', 'redq-rental' ), 
					'type' => 'text',
					'value' => $pickup_date_heading_title, 				
				) 
			);


			$pickup_date_placeholder = get_post_meta(get_the_ID(),'redq_pickup_date_placeholder',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'pickup_date_placeholder', 
					'name' => 'redq_pickup_date_placeholder',
					'label' => __( 'Pickup Date Placeholder', 'redq-rental' ), 
					'placeholder' => __( 'Pickup date placeholder', 'redq-rental' ), 
					'type' => 'text',
					'value' => $pickup_date_placeholder, 				
				) 
			);


			$pickup_time_placeholder = get_post_meta(get_the_ID(),'redq_pickup_time_placeholder',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'pickup_time_placeholder', 
					'name' => 'redq_pickup_time_placeholder',
					'label' => __( 'Pickup Time Placeholder', 'redq-rental' ), 
					'placeholder' => __( 'Pickup date placeholder', 'redq-rental' ), 
					'type' => 'text',
					'value' => $pickup_time_placeholder, 				
				) 
			);


			$dropoff_date_heading_title = get_post_meta(get_the_ID(),'redq_dropoff_date_heading_title',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'dropoff_date_heading_title', 
					'name' => 'redq_dropoff_date_heading_title',
					'label' => __( 'Dropoff Date Heading Title', 'redq-rental' ), 
					'placeholder' => __( 'Dropoff date title', 'redq-rental' ), 
					'type' => 'text',
					'value' => $dropoff_date_heading_title, 				
				) 
			);


			$dropoff_date_placeholder = get_post_meta(get_the_ID(),'redq_dropoff_date_placeholder',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'dropoff_date_placeholder', 
					'name' => 'redq_dropoff_date_placeholder',
					'label' => __( 'Drop-off Date Placeholder', 'redq-rental' ), 
					'placeholder' => __( 'Drop-off date placeholder', 'redq-rental' ), 
					'type' => 'text',
					'value' => $dropoff_date_placeholder, 				
				) 
			);


			$dropoff_time_placeholder = get_post_meta(get_the_ID(),'redq_dropoff_time_placeholder',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'dropoff_time_placeholder', 
					'name' => 'redq_dropoff_time_placeholder',
					'label' => __( 'Drop-off Time Placeholder', 'redq-rental' ), 
					'placeholder' => __( 'Drop-off time placeholder', 'redq-rental' ), 
					'type' => 'text',
					'value' => $dropoff_time_placeholder, 				
				) 
			);

			$resources_heading_title = get_post_meta(get_the_ID(),'redq_resources_heading_title',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'resources_heading_title', 
					'name' => 'redq_resources_heading_title',
					'label' => __( 'Resources Heading Title', 'redq-rental' ), 
					'placeholder' => __( 'Resources title', 'redq-rental' ), 
					'type' => 'text',
					'value' => $resources_heading_title, 				
				) 
			);

			$person_heading_title = get_post_meta(get_the_ID(),'redq_person_heading_title',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'person_heading_title', 
					'name' => 'redq_person_heading_title',
					'label' => __( 'Person Heading Title', 'redq-rental' ), 
					'placeholder' => __( 'Person title', 'redq-rental' ), 
					'type' => 'text',
					'value' => $person_heading_title, 				
				) 
			);

			$person_placeholder = get_post_meta(get_the_ID(),'redq_person_placeholder',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'person_placeholder', 
					'name' => 'redq_person_placeholder',
					'label' => __( 'Person Placeholder', 'redq-rental' ), 
					'placeholder' => __( 'Person placeholder', 'redq-rental' ), 
					'type' => 'text',
					'value' => $person_placeholder, 				
				) 
			);


			$security_deposite_heading_title = get_post_meta(get_the_ID(),'redq_security_deposite_heading_title',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'security_deposite_heading_title', 
					'name' => 'redq_security_deposite_heading_title',
					'label' => __( 'Security Deposite Heading Title', 'redq-rental' ), 
					'placeholder' => __( 'Security deposite title', 'redq-rental' ), 
					'type' => 'text',
					'value' => $security_deposite_heading_title, 				
				) 
			);


			$book_now_button_text = get_post_meta(get_the_ID(),'redq_book_now_button_text',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'book_now_button_text', 
					'name' => 'redq_book_now_button_text',
					'label' => __( 'Book Now Button Text', 'redq-rental' ), 
					'placeholder' => __( 'Book now button text', 'redq-rental' ), 
					'type' => 'text',
					'value' => $book_now_button_text, 				
				) 
			);


		 ?>
	</div>
	<div id="logical_appearence">
		
		<?php do_action('rnb_before_logical_apearence'); ?>

		<?php 
			$value = get_post_meta(get_the_ID(),'redq_block_general_dates',true);

			woocommerce_wp_select( 
				array( 
					'id' => 'block_rental_dates',
					'label' => __( 'Block Rental Dates', 'redq-rental' ), 
					'description' => sprintf( __( 'This will be applicable for calendar date blocks', 'redq-rental' ), 'http://schema.org/' ), 
					'options' => array(						
						'yes'=> __( 'Yes', 'redq-rental' ),
						'no' => __( 'No', 'redq-rental' ),							
					),
					'value' => $value
				) 
			);

			$date_format = get_post_meta(get_the_ID(),'redq_calendar_date_format',true);

			woocommerce_wp_select( 
				array( 
					'id' => 'choose_date_format',
					'label' => __( 'Date Format Settings', 'redq-rental' ), 
					'description' => sprintf( __( 'This will be applicable for all date calendar', 'redq-rental' ), 'http://schema.org/' ), 
					'options' => array(						
						'm/d/Y' => __( 'm/d/Y', 'redq-rental' ),
						'd/m/Y' => __( 'd/m/Y', 'redq-rental' ),
						'Y/m/d' => __( 'Y/m/d', 'redq-rental' ),
					),
					'value' => $date_format
				) 
			);

			$max_time_late = get_post_meta(get_the_ID(),'redq_max_time_late',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'max_time_late', 
					'name' => 'redq_max_time_late',
					'label' => __( 'Maximum time late', 'redq-rental' ),
					'description' => sprintf( __( 'Another day will be count if anyone being late during departure', 'redq-rental' ), 'http://schema.org/' ),  
					'placeholder' => __( 'time', 'redq-rental' ), 
					'type' => 'number', 
					'custom_attributes' => array(
						'step' 	=> '1',
						'min'	=> '0'
					),
					'value' => $max_time_late,  
				) 
			);

			$enable_single_day_time_based_booking = get_post_meta(get_the_ID(),'redq_rental_local_enable_single_day_time_based_booking',true);		
			if(isset($enable_single_day_time_based_booking) && empty($enable_single_day_time_based_booking)){
				$enable_single_day_time_based_booking = 'open';
			}
			woocommerce_wp_checkbox( 
				array(             
		            'id'      => 'redq_rental_local_enable_single_day_time_based_booking',
		            'label'   => __( 'Single Day Booking', 'redq-rental' ),
		            'cbvalue' => 'open', 
					'value' => esc_attr($enable_single_day_time_based_booking),
				) 
			);

			$max_rental_days = get_post_meta(get_the_ID(),'redq_max_rental_days',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'max_rental_days', 
					'name' => 'redq_max_rental_days',
					'label' => __( 'Maximum Booking Days', 'redq-rental' ),
					'placeholder' => __( 'Max days', 'redq-rental' ), 
					'type' => 'number', 
					'custom_attributes' => array(
						'step' 	=> '1',
						'min'	=> '0'
					),
					'value' => $max_rental_days,  
				) 
			);


			$min_rental_days = get_post_meta(get_the_ID(),'redq_min_rental_days',true);
			woocommerce_wp_text_input( 
				array( 
					'id' => 'min_rental_days', 
					'name' => 'redq_min_rental_days',
					'label' => __( 'Minimum Booking Days', 'redq-rental' ),
					'placeholder' => __( 'Min days', 'redq-rental' ), 
					'type' => 'number', 
					'custom_attributes' => array(
						'step' 	=> '1',
						'min'	=> '0'
					),
					'value' => $min_rental_days,  
				) 
			);

			?>

			<?php 

				$days = array(
						7 => 'Sunday',
						1 => 'Monday',
						2 => 'Tuesday',
						3 => 'Wednesday',
						4 => 'Thursday',
						5 => 'Friday',
						6 => 'Saturday',
					);

				$rental_off_days = get_post_meta(get_the_ID(),'redq_rental_off_days',true);

				if(isset($rental_off_days) && empty($rental_off_days)){
					$rental_off_days = array();
				}

			 ?>

			<p class="form-field">
				<label for="weekend">Select Weekends</label>
				<select multiple="multiple" class="inventory-resources"  style="width:350px" name="redq_rental_off_days[]" data-placeholder="<?php esc_attr_e( 'Choose off days', 'woocommerce' ); ?>" title="<?php esc_attr_e( 'Weekends', 'woocommerce' ) ?>" class="wc-enhanced-select">
					<?php if(is_array($days) && !empty($days)): ?>	
						<?php foreach($days as $key => $value){ ?>
							<option value="<?php echo esc_attr($key); ?>" <?php if(in_array($key, $rental_off_days)){ ?> selected  <?php } ?> ><?php echo esc_attr($value); ?></option>
						<?php } ?>
					<?php endif; ?>
				</select>
			</p>

		<?php do_action('rnb_after_logical_apearence'); ?>

	</div>
</div>
 


