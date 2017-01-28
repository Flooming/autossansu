
<div id="price_calculation_product_data" class="panel woocommerce_options_panel">
	
	<?php 
		woocommerce_wp_select( array( 'id' => 'pricing_type','label' => __( 'Set Price Type', 'redq-rental' ), 'description' => sprintf( __( 'Choose a price type - this controls the <a href="%s">schema</a>.', 'redq-rental' ), 'http://schema.org/' ), 'options' => array(
			'general_pricing'            => __( 'General Pricing', 'redq-rental' ),
			'daily_pricing' => __( 'Daily Pricing', 'redq-rental' ),			
			'monthly_pricing'       => __( 'Monthly Pricing', 'redq-rental' ),
			'days_range'       => __( 'Days Range Pricing', 'redq-rental' ),
		) ) );
	?>

	<div class="hourly-pricing-panel show_if_general_pricing">
		<?php 
			woocommerce_wp_text_input( array( 'id' => 'hourly_price', 'label' => __( 'Hourly Price ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'desc_tip' => 'true', 'description' => sprintf( __( 'Hourly price will be applicabe if booking or rental days min 1day', 'redq-rental' ) ) ) );
		?>
	</div>

	<div class="general-pricing-panel show_if_general_pricing">
		<h4 class="redq-headings"><?php _e('Set general pricing plan','redq-rental'); ?></h4>
		<?php 
			woocommerce_wp_text_input( array( 'id' => 'general_price', 'label' => __( 'General Price ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text' ) );
		?>
	</div>

	<div class="daily-pricing-panel">
		<h4 class="redq-headings"><?php _e('Set daily pricing Plan','redq-rental'); ?></h4>
		
		<?php 
			$daily_pricing = get_post_meta( $post_id, 'redq_daily_pricing', true ); 
			
			if(isset($daily_pricing) && empty($daily_pricing)){
				$daily_pricing['friday'] = '';
				$daily_pricing['saturday'] = '';
				$daily_pricing['sunday'] = '';
				$daily_pricing['monday'] = '';
				$daily_pricing['tuesday'] = '';
				$daily_pricing['wednesday'] = '';
				$daily_pricing['thursday'] = '';
			}
		?>

		<?php 
			woocommerce_wp_text_input( array( 'id' => 'friday_price', 'label' => __( 'Friday ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $daily_pricing['friday'] ,  ) );

			woocommerce_wp_text_input( array( 'id' => 'saturday_price', 'label' => __( 'Saturday ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $daily_pricing['saturday'] ,  ) );

			woocommerce_wp_text_input( array( 'id' => 'sunday_price', 'label' => __( 'Sunday ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $daily_pricing['sunday'] ,  ) );

			woocommerce_wp_text_input( array( 'id' => 'monday_price', 'label' => __( 'Monday ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $daily_pricing['monday'] ,  ) );

			woocommerce_wp_text_input( array( 'id' => 'tuesday_price', 'label' => __( 'Tuesday ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $daily_pricing['tuesday'] ,  ) );

			woocommerce_wp_text_input( array( 'id' => 'wednesday_price', 'label' => __( 'Wednesday ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $daily_pricing['wednesday'] ,  ) );

			woocommerce_wp_text_input( array( 'id' => 'thursday_price', 'label' => __( 'Thursday ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $daily_pricing['thursday'] ,  ) );
		?>
	</div>

	<div class="monthly-pricing-panel">
		<h4 class="redq-headings"><?php _e('Set monthly pricing plan','redq-rental') ?></h4>
		<?php 
			$monthly_pricing = get_post_meta( $post_id, 'redq_monthly_pricing', true ); 

			if(isset($monthly_pricing) && empty($monthly_pricing)){
				$monthly_pricing['january'] = '';
				$monthly_pricing['february'] = '';
				$monthly_pricing['march'] = '';
				$monthly_pricing['april'] = '';
				$monthly_pricing['may'] = '';
				$monthly_pricing['june'] = '';
				$monthly_pricing['july'] = '';
				$monthly_pricing['august'] = '';
				$monthly_pricing['september'] = '';
				$monthly_pricing['october'] = '';
				$monthly_pricing['november'] = '';
				$monthly_pricing['december'] = '';
			}

		?>
		<?php 
			woocommerce_wp_text_input( array( 'id' => 'january_price', 'label' => __( 'January ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['january'] ) );

			woocommerce_wp_text_input( array( 'id' => 'february_price', 'label' => __( 'February ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['february'] ) );

			woocommerce_wp_text_input( array( 'id' => 'march_price', 'label' => __( 'March ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text','value' => $monthly_pricing['march']  ) );

			woocommerce_wp_text_input( array( 'id' => 'april_price', 'label' => __( 'April ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text','value' => $monthly_pricing['april']  ) );

			woocommerce_wp_text_input( array( 'id' => 'may_price', 'label' => __( 'May ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['may'] ) );

			woocommerce_wp_text_input( array( 'id' => 'june_price', 'label' => __( 'June ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['june']  ) );

			woocommerce_wp_text_input( array( 'id' => 'july_price', 'label' => __( 'July ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['july']  ) );

			woocommerce_wp_text_input( array( 'id' => 'august_price', 'label' => __( 'August ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['august'] ) );

			woocommerce_wp_text_input( array( 'id' => 'september_price', 'label' => __( 'September ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['september']  ) );

			woocommerce_wp_text_input( array( 'id' => 'october_price', 'label' => __( 'October ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['october'] ) );

			woocommerce_wp_text_input( array( 'id' => 'november_price', 'label' => __( 'November ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['november'] ) );

			woocommerce_wp_text_input( array( 'id' => 'december_price', 'label' => __( 'December ( '.get_woocommerce_currency_symbol().' )', 'redq-rental' ), 'placeholder' => __( 'Enter price here', 'redq-rental' ), 'type' => 'text', 'value' => $monthly_pricing['december'] ) );			
		?>
	</div>


	<div class="redq-days-range-panel">
		<h4 class="redq-headings"><?php _e('Set day ranges pricing plans','redq-rental') ?></h4>
		<div class="table_grid sortable" id="sortable">
			<table class="widefat">				
				<tfoot>
					<tr>
						<th>
							<a href="#" class="button button-primary add_redq_row" data-row="<?php
								ob_start();
								include( 'html-days-range-meta.php' );
								$html = ob_get_clean();
								echo esc_attr( $html );
							?>"><?php _e( 'Add Days Range', 'redq-rental' ); ?></a>
						</th>
					</tr>
				</tfoot>
				<tbody id="resource_availability_rows">
					<?php
						 $days_range = get_post_meta($post_id , 'redq_day_ranges_cost' , true);
						if ( ! empty( $days_range ) && is_array( $days_range ) ) {
							foreach ( $days_range as $day_range ) {
								include( 'html-days-range-meta.php' );
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<!-- Rental Inventory Tab -->
<div id="rental_inventory_product_data" class="panel woocommerce_options_panel">

	<?php 

		/**
		 * Delete unwanted post or posts for inventory
		 *
		 * @since 2.0.0
		 * @var object
		 */
		$resource_identifiers = get_post_meta(get_the_ID(), 'resource_identifier', true);
		$selected_terms = array();

		$args = array(
			'post_parent' => get_the_ID(),
			'post_type'   => 'Inventory', 
			'numberposts' => -1,
			'post_status' => 'any' 
		); 

		$children_array = get_children( $args );

		if(isset($children_array) && !empty($children_array)){
			$unwanted_inventories = array_diff(array_keys($children_array), array_keys($resource_identifiers));
			$unwanted_arrange_inventories = array();
			
			if(isset($unwanted_inventories) && is_array($unwanted_inventories) && !empty($unwanted_inventories)){			
				foreach ($unwanted_inventories as $key => $value) {
					$unwanted_arrange_inventories[] = $value;
				}
				foreach ($unwanted_arrange_inventories as $key => $value) {
					$rental_availability = get_post_meta( get_the_ID(), 'redq_block_dates_and_times', true );
					foreach($rental_availability as $rental_key => $rental_value) {
					  	if($rental_key == $value) {  
					    	unset($rental_availability[$rental_key]);
					  	}
					}
					$rental_availability = update_post_meta( get_the_ID(), 'redq_block_dates_and_times', $rental_availability );
					wp_delete_post($value,true);
				}
			}	
		}		


	 ?>



	<div class="redq-rental-inventory-panel">
		<h4 class="redq-headings"><?php _e('Inventory management','redq-rental') ?></h4>
		<?php //include( 'html-rental-inventory-non-repeatable-data.php' ); ?> 
		<div class="table_grid sortable" id="sortable">
			<table class="widefat">				
				<tfoot>
					<tr>
						<th>
							<a href="#" class="button button-primary add_redq_row" data-row="<?php
								ob_start();
								include( 'html-rental-inventory-repeatable-data.php' );
								$html = ob_get_clean();
								echo esc_attr( $html );
							?>"><?php _e( 'Add Inventory Items', 'redq-rental' ); ?></a>
						</th>
					</tr>
				</tfoot>
				<tbody id="resource_availability_rows">
					<?php
						$unique_models = get_post_meta($post_id , 'redq_inventory_products_quique_models' , true);
						if ( ! empty( $unique_models ) && is_array( $unique_models ) ) {
							foreach ( $unique_models as $key => $unique_model ) {
								include( 'html-rental-inventory-repeatable-data.php' );
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</div>

</div>




<!-- price discount Tab -->
<div id="price_discount_product_data" class="panel woocommerce_options_panel">
	<div class="redq-price-discount-panel">
		<h4 class="redq-headings"><?php _e('Set price discount depending on day length','redq-rental') ?></h4>
		<div class="table_grid sortable" id="sortable">
			<table class="widefat">				
				<tfoot>
					<tr>
						<th>
							<a href="#" class="button button-primary add_redq_row" data-row="<?php
								ob_start();
								include( 'html-price-discount-meta.php' );
								$html = ob_get_clean();
								echo esc_attr( $html );
							?>"><?php _e( 'Add Price Discount', 'redq-rental' ); ?></a>
						</th>
					</tr>
				</tfoot>
				<tbody id="resource_availability_rows">
					<?php
						$price_discounts = get_post_meta($post_id , 'redq_price_discount_cost' , true);
						if ( ! empty( $price_discounts ) && is_array( $price_discounts ) ) {
							foreach ( $price_discounts as $price_discount ) {
								include( 'html-price-discount-meta.php' );
							}
						}
					?>
				</tbody>
			</table>
		</div>
	</div>

</div>






<!-- settgins field  -->
<div id="product_settings_data" class="panel woocommerce_options_panel">
	<h4 class="redq-headings"><?php _e('Settings of this product','redq-rental'); ?></h4>
	<?php include( 'html-product-settings-data.php' ); ?> 
</div>














