<?php 
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Rental Product Tab Class.
 *
 * The WooCommerce product class handles individual product data.
 *
 * @class 		WC_Product_Redq_Rental
 * @version 	1.0.0
 * @since 		1.0.0
 * @package		WooCommerce-rental-and-booking/includes
 * @category	Class
 * @author 		RedQTeam
 */

class WC_Redq_Rental_Tabs {

	/**
	 * Constructor.
	 *
	 * @param null
	 */
	public function __construct(){	
		add_action( 'init', array( $this, 'redq_rental_show_log' ));
		add_action( 'woocommerce_product_tabs', array( $this, 'redq_rental_product_tabs' ));	
	}


	/**
	 * Show debug log data . Will remove at last
	 *
	 * @version		1.7.0
	 * @access public
	 * @param null
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public function redq_rental_show_log(){
		if(!function_exists('_log')){
		  	function _log( $message ) {
			    if( WP_DEBUG === true ){
			      	if( is_array( $message ) || is_object( $message ) ){
			        	error_log( print_r( $message, true ) );
			      	} else {
			        	error_log( $message );
			      	}
			    }
		  	}
		}
	}


	/**
	 * Initialize attribute and feature tabs
	 *
	 * @version		1.7.0
	 * @access public
	 * @param array $taxonomy
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public function redq_rental_product_tabs($tabs){

		$product_type = get_product(get_the_ID())->product_type;
		
		if(isset($product_type) && $product_type === 'redq_rental'){
			$item_attributes = WC_Product_Redq_Rental::redq_get_rental_non_payable_attributes('attributes');
			if(isset($item_attributes) && !empty($item_attributes)){
				$tabs['attributes'] = array(
			        'title'     => __( 'Attributes', 'redq-rental' ),
			        'priority'  => 5,
			        'callback'  => 'WC_Redq_Rental_Tabs::redq_attributes'
			    );  
			}
			
			$additional_features = WC_Product_Redq_Rental::redq_get_rental_non_payable_attributes('features');
			if(isset($additional_features) && !empty($additional_features)){
				$tabs['features'] = array(
			        'title'     => __( 'Features', 'redq-rental' ),
			        'priority'  => 8,
			        'callback'  => 'WC_Redq_Rental_Tabs::redq_features'
			    ); 	
			}		     
		}	    

	    return $tabs; 
	}


	/**
	 * Attributes tab callback function
	 *
	 * @version		1.7.0
	 * @access public
	 * @param null
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public static function redq_attributes(){ ?>		

		<?php $item_attributes = WC_Product_Redq_Rental::redq_get_rental_non_payable_attributes('attributes');  ?>
		<?php if(isset($item_attributes) && !empty($item_attributes)): ?>
		<div class="item-arrtributes">			
			<ul class="attributes">
				<?php foreach ($item_attributes as $key => $value) { ?>
					<li>
						<span class="attribute-icon"><i class="fa <?php echo esc_attr($value['icon']); ?>"></i></span>
						<span class="attribute-name"><?php echo esc_attr($value['name']); ?></span>
						<span class="attribute-vaue"> : <?php echo esc_attr($value['value']); ?></span>
					</li>	
				<?php } ?>		
			</ul>	
		</div>
		<?php endif; ?>
	<?}


	/**
	 * Features tab callback function
	 *
	 * @version		1.7.0
	 * @access public
	 * @param null
	 * @return WC_Product or WC_Product_Rental_product
	 */
	public static function redq_features(){ ?>

		<?php $additional_features = WC_Product_Redq_Rental::redq_get_rental_non_payable_attributes('features'); ?>	

		<?php if(isset($additional_features) && !empty($additional_features)): ?>
		<div class="item-extras">			
			<ul class="attributes">
				<?php foreach ($additional_features as $key => $value) { ?>
					<?php
						if($value['availability'] === 'yes'){
							$checked = 'checked';
						}else{
							$checked = 'unchecked';
						}
					 ?>
					<li class="<?php echo esc_attr($checked); ?>">													
						<?php echo esc_attr($value['name']); ?>
					</li>	 
				<?php } ?>		
			</ul>	
		</div>
		<?php endif; ?>
		
	<?php }


}	

new WC_Redq_Rental_Tabs();





