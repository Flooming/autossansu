jQuery(document).ready(function($) {

	  
    /**
     * Add new row
     *
     * @since 2.0.0
     * @return null
     */   
	$( '.add_redq_row' ).click(function(){		
		$(this).closest('table').find('tbody').append( $( this ).data( 'row' ) );
		 $('body').trigger('row_added');  


        // var inventoryCount = $('#rental_inventory_count').val(),
        //     incrementalCount = $('.rental_inventory').length;
       
        // if(parseInt(inventoryCount) <= parseInt(incrementalCount)){
        //    $(this).hide();
        // }else{
        //     $(this).show();
        // }
        
		return false;
	});




    /**
     * Cotnrol date format
     *
     * @since 2.0.0
     * @return null
     */ 
    var date_format;
    if(redq_rental_writepanel_js_params.all_data.choose_date_format != undefined){
        if(redq_rental_writepanel_js_params.all_data.choose_date_format.toLowerCase() === 'd/m/y'){
        date_format = 'd/m/yy'; 
        }

        if(redq_rental_writepanel_js_params.all_data.choose_date_format.toLowerCase() === 'm/d/y'){
            date_format = 'm/d/yy'; 
        }

        if(redq_rental_writepanel_js_params.all_data.choose_date_format.toLowerCase() === 'y/m/d'){
            date_format = 'yy/m/d'; 
        }        
    }



    $('body').on('row_added', function(){
        $( '.date-picker' ).datepicker({
            dateFormat: date_format,
            numberOfMonths: 1,
            showButtonPanel: true,
            showOn: 'button',
            buttonImage: redq_rental_writepanel_js_params.calendar_image,
            buttonImageOnly: true
        });
    });

    $( '.date-picker' ).datepicker({
        dateFormat: date_format,
        numberOfMonths: 1,
        showButtonPanel: true,
        showOn: 'button',  
        buttonImage: redq_rental_writepanel_js_params.calendar_image,          
        buttonImageOnly: true
    });

    $('body').on('.add_redq_row', function(){       
        
        $( '.date-picker' ).datepicker({
            dateFormat: date_format,
            numberOfMonths: 1,
            showButtonPanel: true,
            showOn: 'button',            
            buttonImageOnly: true
        });
    });



    /**
     * Remove row
     *
     * @since 1.0.0
     * @version 2.0.0
     * @return null
     */  
	$('body').on('click', 'button.remove_row', function(){
		$(this).closest('.redq-remove-rows').remove();

        // var inventoryCount = $('#rental_inventory_count').val(),
        //     incrementalCount = $('.rental_inventory').length;

        // if(parseInt(inventoryCount) <= parseInt(incrementalCount)){
        //    $('.add_redq_row').hide();
        // }else{
        //     $('.add_redq_row').show();
        // }

		return false;
	});


    // $('#rental_inventory_count').on('change' , function(){
    //     var inventoryCount = $(this).val(),
    //         incrementalCount = $('.rental_inventory').length;

    //     if(parseInt(inventoryCount) <= parseInt(incrementalCount)){
    //        $('.add_redq_row').hide();
    //     }else{
    //         $('.add_redq_row').show();
    //     }
    // });




    $('body').on('click', 'td.remove', function(){
        $(this).closest('tr').remove();
        return false;
    });

    



    /**
     * Show or hide row
     *
     * @since 2.0.0
     * @return null
     */ 
	$('.redq-hide-row').hide();

	$('body').on('click', '.show-or-hide', function(e) {
        $(this).closest('div.redq-show-bar').next('div.redq-hide-row').slideToggle();
        return false;
    });	

	$( ".sortable" ).sortable({
		cursor: 'move'
	});



    /**
     * Control pricing types
     *
     * @since 2.0.0
     * @return null
     */  
    $('.daily-pricing-panel').hide();
    $('.monthly-pricing-panel').hide();

    var pricingType = $('#pricing_type').val();

    if(pricingType == 'daily_pricing'){
		$('.daily-pricing-panel').show();
		$('.general-pricing-panel').hide();
		$('.monthly-pricing-panel').hide();
        $('.redq-days-range-panel').hide();
	}else if(pricingType == 'monthly_pricing'){
		$('.daily-pricing-panel').hide();
		$('.general-pricing-panel').hide();
		$('.monthly-pricing-panel').show();
         $('.redq-days-range-panel').hide();
	}else if(pricingType == 'days_range'){
        $('.daily-pricing-panel').hide();
        $('.general-pricing-panel').hide();
        $('.monthly-pricing-panel').hide();
        $('.redq-days-range-panel').show();
    }else{
		$('.daily-pricing-panel').hide();
		$('.general-pricing-panel').show();
		$('.monthly-pricing-panel').hide();
        $('.redq-days-range-panel').hide();
	}


    $('#pricing_type').change(function(){
    	var pricingType = this.value;

    	if(pricingType == 'daily_pricing'){
            $('.daily-pricing-panel').show();
            $('.general-pricing-panel').hide();
            $('.monthly-pricing-panel').hide();
            $('.redq-days-range-panel').hide();
        }else if(pricingType == 'monthly_pricing'){
            $('.daily-pricing-panel').hide();
            $('.general-pricing-panel').hide();
            $('.monthly-pricing-panel').show();
             $('.redq-days-range-panel').hide();
        }else if(pricingType == 'days_range'){
            $('.daily-pricing-panel').hide();
            $('.general-pricing-panel').hide();
            $('.monthly-pricing-panel').hide();
            $('.redq-days-range-panel').show();
        }else{
            $('.daily-pricing-panel').hide();
            $('.general-pricing-panel').show();
            $('.monthly-pricing-panel').hide();
            $('.redq-days-range-panel').hide();
        }

    })

   




    /**
     * Control resource tabs
     *
     * @since 2.0.0
     * @return null
     */ 
    $('body').on('change', 'select#inventory_price_applicable_term_meta', function(){  
        console.log($(this).val());      
        if($(this).val() != 'one_time'){
            $('input#inventory_hourly_cost_termmeta').parent('.form-field').show();     
        }else{
            $('input#inventory_hourly_cost_termmeta').parent('.form-field').hide();
        }
    });



    /**
     * Control person tabs
     *
     * @since 2.0.0
     * @return null
     */    

    $('body').on('change', 'select#inventory_person_payable_or_not', function(){  
        console.log($(this).val());      
        if($(this).val() != 'yes'){
            $('input#inventory_person_cost_termmeta').parent('.form-field').hide(); 
            $('select#inventory_person_price_applicable_term_meta').parent('.form-field').hide();  
            $('input#inventory_peroson_hourly_cost_termmeta').parent('.form-field').hide();    
        }else{
            $('input#inventory_person_cost_termmeta').parent('.form-field').show(); 
            $('select#inventory_person_price_applicable_term_meta').parent('.form-field').show();  
            $('input#inventory_peroson_hourly_cost_termmeta').parent('.form-field').show(); 
        }
    });

    $('body').on('change', 'select#inventory_person_price_applicable_term_meta', function(){  
        if($(this).val() != 'one_time'){
            $('input#inventory_peroson_hourly_cost_termmeta').parent('.form-field').show();     
        }else{
            $('input#inventory_peroson_hourly_cost_termmeta').parent('.form-field').hide();
        }
    });    
    



    /**
     * Control security deposit tabs
     *
     * @since 2.0.0
     * @return null
     */ 
    $('body').on('change', 'select#inventory_sd_price_applicable_term_meta', function(){  
        if($(this).val() != 'one_time'){
            $('input#inventory_sd_hourly_cost_termmeta').parent('.form-field').show();     
        }else{
            $('input#inventory_sd_hourly_cost_termmeta').parent('.form-field').hide();
        }
    });    



    /**
     * Control settings tabs
     *
     * @since 2.0.0
     * @return null
     */ 
    $( "#rnb_setting_tabs" ).tabs();


    $(".inventory-resources").select2({
        placeholder: "Select resources"
    });



});