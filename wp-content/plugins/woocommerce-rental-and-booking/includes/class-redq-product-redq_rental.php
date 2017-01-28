<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Rental Product Class.
 *
 * The WooCommerce product class handles individual product data.
 *
 * @class 		WC_Product_Redq_Rental
 * @version		1.0.0
 * @package		WooCommerce-rental-and-booking/includes
 * @category	Class
 * @author 		RedQTeam
 */

class WC_Product_Redq_Rental extends WC_Product {
	

	/**
	 * Constructor.
	 *
	 * @param mixed $product
	 */
	public function __construct( $product ) {
		$this->product_type = 'redq_rental';
		parent::__construct( $product );
	}


	/**
	 * Get product pricing type
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $id
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public function redq_get_pricing_type($product_id){
		
		if(isset($product_id) && !empty($product_id)){
			$id = $product_id;
		}else{
			$id = get_the_ID();
		}

		$pricing_type = get_post_meta($id,'pricing_type',true);

		return apply_filters('redq_pricing_type' , $pricing_type);
	}


	/**
	 * Get product daily pricing
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $id
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public function redq_get_daily_pricing($product_id){
		
		if(isset($product_id) && !empty($product_id)){
			$id = $product_id;
		}else{
			$id = get_the_ID();
		}

		$daily_pricing = get_post_meta($id,'redq_daily_pricing',true);

		return apply_filters('redq_daily_pricing' , $daily_pricing);
	}


	/**
	 * Get product monthly pricing
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $id
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public function redq_get_monthly_pricing($product_id){
		
		if(isset($product_id) && !empty($product_id)){
			$id = $product_id;
		}else{
			$id = get_the_ID();
		}

		$monthly_pricing = get_post_meta($id,'redq_monthly_pricing',true);

		return apply_filters('redq_monthly_pricing' , $monthly_pricing);
	}


	/**
	 * Get product day ranges pricing
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $id
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public function redq_get_day_ranges_pricing($product_id){
		
		if(isset($product_id) && !empty($product_id)){
			$id = $product_id;
		}else{
			$id = get_the_ID();
		}

		$day_ranges_pricing_plans = get_post_meta($id,'redq_day_ranges_cost',true);

		return apply_filters('redq_day_ranges_pricing' , $day_ranges_pricing_plans);
	}


	/**
	 * Get product payable resources, person , security deposite, pickup and dorpoff locations
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $taxonomy
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public static function redq_get_rental_payable_attributes($taxonomy, $product_id = null){

		if(empty($product_id)){
			$product_id = get_the_ID();
		}else{
			$product_id = $product_id;
		}

		$payable_attributes_identifiers = get_post_meta($product_id, 'resource_identifier', true);
		$selected_terms = array();		

		if(is_array($payable_attributes_identifiers) && !empty($payable_attributes_identifiers)){
			foreach ($payable_attributes_identifiers as $resource_key => $resource_value) {				
				$args = array(
							'orderby'           => 'name', 
							'order'             => 'ASC',
							'fields'      => 'all',       
						); 
				if(taxonomy_exists($taxonomy)){
					$terms = wp_get_post_terms( $resource_value['inventory_id'], $taxonomy, $args );
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

		

		switch ($taxonomy) {

			case 'pickup_location':
				$pick_up_locations = array();
				if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
					foreach ($unique_selected_terms as $key => $value) {
						$term_id = $value->term_id;
						$pickup_cost = get_term_meta($term_id, 'inventory_pickup_cost_termmeta', true);
						$pickup_lat = get_term_meta($term_id, 'inventory_pickup_lat_termmeta', true);	
						$pickup_long = get_term_meta($term_id, 'inventory_pickup_long_termmeta', true);					
						$pick_up_locations[$key]['title'] = $value->name;
						$pick_up_locations[$key]['slug'] = $value->slug;
						$pick_up_locations[$key]['address'] = $value->description;				
						$pick_up_locations[$key]['cost'] = $pickup_cost;
						$pick_up_locations[$key]['lat'] = $pickup_lat;				
						$pick_up_locations[$key]['lon'] = $pickup_long;
					}
				}

				return apply_filters('redq_pickup_locations', $pick_up_locations);	

				break;

			case 'dropoff_location':
				$drop_off_locations = array();

				if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
					foreach ($unique_selected_terms as $key => $value) {
						$term_id = $value->term_id;
						$dropoff_cost = get_term_meta($term_id, 'inventory_dropoff_cost_termmeta', true);
						$dropoff_lat = get_term_meta($term_id, 'inventory_dropoff_lat_termmeta', true);
						$dropoff_long = get_term_meta($term_id, 'inventory_dropoff_long_termmeta', true);				
						$drop_off_locations[$key]['title'] = $value->name;
						$drop_off_locations[$key]['slug'] = $value->slug;
						$drop_off_locations[$key]['address'] = $value->description;				
						$drop_off_locations[$key]['cost'] = $dropoff_cost;
						$drop_off_locations[$key]['lat'] = $dropoff_lat;
						$drop_off_locations[$key]['lon'] = $dropoff_long;	
					}
				}

				return apply_filters('redq_dropoff_locations', $drop_off_locations);	

				break;

			case 'resource':
				$resources = array();

				if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
					foreach ($unique_selected_terms as $key => $value) {
						$term_id = $value->term_id;
						$resource_cost = get_term_meta($term_id, 'inventory_resource_cost_termmeta', true);
						$resource_applicable = get_term_meta($term_id, 'inventory_price_applicable_term_meta', true);
						$resource_hourly_cost = get_term_meta($term_id, 'inventory_hourly_cost_termmeta', true);
						$resources[$key]['resource_name'] = $value->name;
						$resources[$key]['resource_slug'] = $value->slug;
						$resources[$key]['resource_cost'] = $resource_cost;
						$resources[$key]['resource_applicable'] = $resource_applicable;
						$resources[$key]['resource_hourly_cost'] = $resource_hourly_cost;
					}
				}



				return apply_filters('redq_payable_resources', $resources);	

				break;

			case 'person':
				$person_cost = array();
				if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
					foreach ($unique_selected_terms as $key => $value) {
						$term_id = $value->term_id;
						$payable = get_term_meta($term_id, 'inventory_person_payable_or_not', true);
						$cost = get_term_meta($term_id, 'inventory_person_cost_termmeta', true);
						$applicable = get_term_meta($term_id, 'inventory_person_price_applicable_term_meta', true);
						$hourly_cost = get_term_meta($term_id, 'inventory_peroson_hourly_cost_termmeta', true);				
						$person_cost[$key]['person_count'] = $value->name;
						$person_cost[$key]['person_slug'] = $value->slug;
						$person_cost[$key]['person_payable'] = $payable;
						$person_cost[$key]['person_cost'] = $cost;
						$person_cost[$key]['person_cost_applicable'] = $applicable;
						$person_cost[$key]['person_hourly_cost'] = $hourly_cost;
					}
				}

				return apply_filters('redq_payable_person', $person_cost);	

				break;

			case 'deposite':
				$security_deposites = array();

				if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
					foreach ($unique_selected_terms as $key => $value) {
						$term_id = $value->term_id;
						$sd_cost = get_term_meta($term_id, 'inventory_sd_cost_termmeta', true);
						$sd_applicable = get_term_meta($term_id, 'inventory_sd_price_applicable_term_meta', true);
						$sd_clickable = get_term_meta($term_id, 'inventory_sd_price_clickable_term_meta', true);
						$sd_hourly_cost = get_term_meta($term_id, 'inventory_sd_hourly_cost_termmeta', true);
						$security_deposites[$key]['security_deposite_name'] = $value->name;
						$security_deposites[$key]['security_deposite_slug'] = $value->slug;
						$security_deposites[$key]['security_deposite_cost'] = $sd_cost;
						$security_deposites[$key]['security_deposite_applicable'] = $sd_applicable;
						$security_deposites[$key]['security_deposite_clickable'] = $sd_clickable;
						$security_deposites[$key]['security_deposite_hourly_cost'] = $sd_hourly_cost;
					}
				}

				return apply_filters('redq_payable_security_deposite', $security_deposites);	

				break;		
			
			default:
				return 'something goes wrong';
				break;
		}
	}


	/**
	 * Get product non-payable attributes and features
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $taxonomy
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public static function redq_get_rental_non_payable_attributes($taxonomy, $product_id = null){

		if(empty($product_id)){
			$product_id = get_the_ID();
		}else{
			$product_id = $product_id;
		}

		$payable_attributes_identifiers = get_post_meta($product_id, 'resource_identifier', true);
		$selected_terms = array();	

		if(is_array($payable_attributes_identifiers) && !empty($payable_attributes_identifiers)){
			foreach ($payable_attributes_identifiers as $resource_key => $resource_value) {				
				$args = array(
							'orderby'           => 'name', 
							'order'             => 'ASC',
							'fields'      => 'all',       
						); 
				if(taxonomy_exists($taxonomy)){
					$terms = wp_get_post_terms( $resource_value['inventory_id'], $taxonomy, $args );
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

		switch ($taxonomy) {

			case 'attributes':
				$attributes = array();

				if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
					foreach ($unique_selected_terms as $key => $value) {
						$term_id = $value->term_id;
						$name = get_term_meta($term_id, 'inventory_attribute_name', true);
						$avalue = get_term_meta($term_id, 'inventory_attribute_value', true);
						$icon = get_term_meta($term_id, 'inventory_attribute_icon', true);
						$selected_icon = get_term_meta($term_id, 'choose_attribute_icon', true);
						$image = get_term_meta($term_id, 'attributes_image_icon', true);
						
						if(isset($name) && !empty($name)){
							$attributes[$key]['name'] = $name;
						}else{
							$attributes[$key]['name'] = $value->name;
						}
						$attributes[$key]['value'] = $avalue;	
						$attributes[$key]['selected_icon'] = $selected_icon;	
						$attributes[$key]['icon'] = $icon;				
						$attributes[$key]['image'] = $image;	
					}
				}

				return apply_filters('redq_non_payable_attributes', $attributes);	

				break;

			case 'features':
				$features = array();

				if(isset($unique_selected_terms) && is_array($unique_selected_terms)){
					foreach ($unique_selected_terms as $key => $value) {
						$term_id = $value->term_id;
						$name = get_term_meta($term_id, 'inventory_feature_name', true);
						if(isset($name) && !empty($name)){
							$features[$key]['name'] = $name;
						}else{
							$features[$key]['name'] = $value->name;
						}
						$availability = get_term_meta($term_id, 'inventory_feature_availability', true); 
						if(isset($availability) && !empty($availability)){
							$features[$key]['availability'] = $availability;
						}else{
							$features[$key]['availability'] = 'yes';
						}												
					}
				}

				return apply_filters('redq_non_payable_features', $features);	

				break;				
			
			default:
				return 'something goes wrong';
				break;
		}
	}



	/**
	 * Get all weekend days
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $taxonomy
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public static function getWednesdays($year, $month, $day){
	    return new DatePeriod(
	        new DateTime("first $day of $year-$month"),
	        DateInterval::createFromDateString('next '.$day.''),
	        new DateTime("last day of $year-$month-01")
	    );
	}


	/**
	 * Get car company name
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $taxonomy
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public static function redq_get_rental_car_company($taxonomy){	    

	    $post_id = get_the_ID();
	    $car_companies = array();

	    $args = array(
					'orderby'           => 'name', 
					'order'             => 'ASC',
					'fields'      => 'all',       
				); 	    

		if(taxonomy_exists($taxonomy)){
			$terms = wp_get_post_terms( $post_id, $taxonomy, $args );
		}

		if(isset($terms) && is_array($terms)){
			foreach ($terms as $key => $value) {
				$term_id = $value->term_id;
				$car_companies[$key]['name'] = $value->name;							
			}
		}
		return $car_companies;
	}


	/**
	 * Get the add to cart button text.
	 *
	 * @access public
	 * @return string
	 */
	public function add_to_cart_text() {
		return apply_filters( 'woocommerce_product_add_to_cart_text', $this->get_button_text(), $this );
	}


	/**
	 * Get button text.
	 *
	 * @access public
	 * @return string
	 */
	public function get_button_text() {
		return $this->button_text ? $this->button_text : __( 'Book Now', 'redq_rental' );
	}


	/**
	 * Translate keys
	 *
	 * @version		1.7.0
	 * @access public
	 * @param string $taxonomy
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public static function redq_translate_keys($type , $pricing){	   

	    switch ($type) {

			case 'daily_pricing':
				$translated_days = array();
				foreach ($pricing as $key => $value) {
					switch ($key) {
						case 'friday':
							$key = __('Friday','redq-rental');
							break;
						case 'saturday':
							$key = __('Saturday','redq-rental');
							break;
						case 'sunday':
							$key = __('Sunday','redq-rental');
							break;
						case 'monday':
							$key = __('Monday','redq-rental');
							break;
						case 'tuesday':
							$key = __('Tuesday','redq-rental');
							break;
						case 'wednesday':
							$key = __('Wednesday','redq-rental');
							break;
						case 'thursday':
							$key = __('Thursday','redq-rental');
							break;						
						
						default:
							break;
					}
					$translated_days[$key] = $value;
				}

				return apply_filters('redq_translated_days', $translated_days);	

				break;

			case 'monthly_pricing':
				$translated_month = array();
				foreach ($pricing as $key => $value) {
					switch ($key) {
						case 'january':
							$key = __('january','redq-rental');
							break;
						case 'february':
							$key = __('february','redq-rental');
							break;
						case 'march':
							$key = __('march','redq-rental');
							break;
						case 'april':
							$key = __('april','redq-rental');
							break;
						case 'may':
							$key = __('may','redq-rental');
							break;
						case 'june':
							$key = __('june','redq-rental');
							break;
						case 'july':
							$key = __('july','redq-rental');
							break;
						case 'august':
							$key = __('august','redq-rental');
							break;	
						case 'september':
							$key = __('september','redq-rental');
							break;
						case 'october':
							$key = __('october','redq-rental');
							break;
						case 'november':
							$key = __('november','redq-rental');
							break;	
						case 'december':
							$key = __('december','redq-rental');
							break;						
						default:
							break;
					}

					$translated_month[$key] = $value;
				}

				return apply_filters('redq_translated_months', $translated_month);	

				break;

			default:
				return 'something goes wrong';
				break;
		}

	}


	

}




