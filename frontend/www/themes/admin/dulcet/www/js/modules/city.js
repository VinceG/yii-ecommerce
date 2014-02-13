jQuery(function() {
	/**
	 * check if city zip code exists
	 */
	$('#get-city-info-by-zip').on('click', function() {
		loadCityInfoByZip(false);
	});
	/**
	 * check and load city info by zip code
	 */
	$('#load-city-info-by-zip').on('click', function() {
		loadCityInfoByZip(true);
	});
	/**
	 * copy billing info into shipping
	 */
	$('#copy-info-from-billing-to-shipping').on('click', function() {
		copyInfoFromTo('billing', 'shipping');
	});
	/**
	 * copy shipping info into billing
	 */
	$('#copy-info-from-shipping-to-billing').on('click', function() {
		copyInfoFromTo('shipping', 'billing');
	});
	
	/**
	 * Copy info from billing/shipping into billing/shipping
	 *
	 */
	function copyInfoFromTo(from, to) {
		$('.' + from + '_info').each(function(index, value) {
			var $elemClass = $(this).attr('class').replace(from + '_info ', '');
			var classID = null;
			var classes = $elemClass.split( ' ' );
		    $.each(classes, function(index, value) {
				if(value, value.indexOf(from+'_') !== -1) {
					classId = value, value.indexOf(from+'_');
					return;
				}
			});
			
			if(classId) {
				classId = classId.replace(from, '').replace('_', '');
				$('.' + to + '_' + classId).val( $('.' + from + '_' + classId).val() );
			}
		});
		UpdateChosen();
	}
	
	/**
	 * Load city info by zip
	 */
	function loadCityInfoByZip(loadInto) {
		var $zipCode = $('#zipcode').val();
		if(!$zipCode) {
			alert('You must enter a zip code!');
			return false;
		}
		
		// Run ajax query to see if that zipcode exists
		//  Load image
		$.ajax({
		  url: "/admin/city/GetCityInfoByZip",
		  data: {"zipCode" : $zipCode},
	      dataType: "json",
		  success: function(data){
			if(data.error && data.error != '') {
				alert(data.error);
				return;
			}
			
			// Did we want to check or load
			if(loadInto) {
				$('.billing_city').val(data.info.city_name);
				$('.billing_state').val(data.info.city_state);
				$('.billing_zip').val(data.info.city_zip);
				$('.billing_country').val(data.info.country);
				UpdateChosen();
			} else {
				alert(data.text);
				return false;
			}
			
		  }
		});
	}
});