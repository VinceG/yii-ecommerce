/*==== Simple DatePicker ====*/
jQuery(function() {
	jQuery( "#datepicker" ).datepicker({
		showAnim: 'slide'
	});
});

/*==== Multiple DatePicker ====*/
jQuery(function() {
	jQuery( "#multiple_datepicker" ).datepicker({
		numberOfMonths: 2,
		showButtonPanel: true,
		showAnim: 'slide'
	});
});


/*==== Date Range (DatePicker) ====*/
jQuery(function() {
	var dates = jQuery( "#datepicker_from, #datepicker_to" ).datepicker({
		defaultDate: "+1w",
		showAnim: 'slide',
		numberOfMonths: 2,
		onSelect: function( selectedDate ) {
			var option = this.id == "datepicker_from" ? "minDate" : "maxDate",
				instance = jQuery( this ).data( "datepicker" ),
				date = jQuery.datepicker.parseDate(
					instance.settings.dateFormat ||
					jQuery.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		}
	});
});


/*==== Inline DatePicker ====*/
jQuery(function() {
	jQuery( "#datepicker2" ).datepicker();
});

/*==== Tabs ====*/
jQuery(function() {
	jQuery( ".ui_tabs, .ui_tabs_right" ).tabs();
});

jQuery(function() {
		jQuery( ".ui_tabs_right_ajax" ).tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					jQuery( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible. " +
						"If this wouldn't be a demo." );
				}
			}
		});
	});

/*==== Accordion ====*/
jQuery(function() {
	jQuery( ".ui_accordion" ).accordion({
		active: false,
		collapsible: true,
		autoHeight: false,
		navigation: true
	});
});
	
/*==== Simple Slider ====*/
jQuery(function() {
	jQuery( "#simple_slider" ).slider();
});

jQuery(function() {
	jQuery( "#slider_snap_increments" ).slider({
		value:100,
		min: 0,
		max: 500,
		step: 50,
		slide: function( event, ui ) {
			jQuery( "#amount_increments" ).val( "$" + ui.value );
		}
	});
	jQuery( "#amount_increments" ).val( "$" + jQuery( "#slider_snap_increments" ).slider( "value" ) );
});

/*==== Multiple Vertical Slider ====*/
jQuery(function() {
	// setup Multiple Vertical
	jQuery( "#multiple_vertical_slider > span" ).each(function() {
		// read initial values from markup and remove that
		var value = parseInt( jQuery( this ).text(), 10 );
		jQuery( this ).empty().slider({
			value: value,
			range: "min",
			animate: true,
			orientation: "vertical"
		});
	});
});

/*==== Slider Range ====*/
jQuery(function() {
	jQuery( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 500,
		values: [ 75, 300 ],
		slide: function( event, ui ) {
			jQuery( "#range_amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		}
	});
	jQuery( "#range_amount" ).val( "$" + jQuery( "#slider-range" ).slider( "values", 0 ) +
		" - $" + jQuery( "#slider-range" ).slider( "values", 1 ) );
});

/*==== SliderRange Minimum ====*/
jQuery(function() {
	jQuery( "#slider-range-min" ).slider({
		range: "min",
		value: 285,
		min: 1,
		max: 700,
		slide: function( event, ui ) {
			jQuery( "#range_amount_min" ).val( "$" + ui.value );
		}
	});
	jQuery( "#range_amount_min" ).val( "$" + jQuery( "#slider-range-min" ).slider( "value" ) );
});

/*==== Progres bar ====*/
/*1*/jQuery(function() {
		jQuery( "#progressbar" ).progressbar({
			value: 59
		});
});
	
/*2*/jQuery(function() {
		jQuery( "#progressbar2" ).progressbar({
			value: 35
		});
	});
		
/*3*/jQuery(function() {
		jQuery( "#progressbar3" ).progressbar({
			value: 90
		});
	});
	
/*4*/jQuery(function() {
		jQuery( "#progressbar4" ).progressbar({
			value: 45
		});
	});
	
/*5*/jQuery(function() {
		jQuery( "#progressbar5" ).progressbar({
			value: 23
		});
		 
	});


jQuery(document).ready(function(){
    jQuery.fn.anim_progressbar = function (aOptions) {
        // def values
        var iCms = 1000;
        var iMms = 60 * iCms;
        var iHms = 3600 * iCms;
        var iDms = 24 * 3600 * iCms;

        // def options
        var aDefOpts = {
            start: new Date(), // now
            finish: new Date().setTime(new Date().getTime() + 60 * iCms), // now + 60 sec
            interval: 100
        }
        var aOpts = jQuery.extend(aDefOpts, aOptions);
        var vPb = this;

        // each progress bar
        return this.each(
            function() {
                var iDuration = aOpts.finish - aOpts.start;

                // calling original progressbar
                jQuery(vPb).children('.pbar').progressbar();

                // looping process
                var vInterval = setInterval(
                    function(){
                        var iLeftMs = aOpts.finish - new Date(); // left time in MS
                        var iElapsedMs = new Date() - aOpts.start, // elapsed time in MS
                            iDays = parseInt(iLeftMs / iDms), // elapsed days
                            iHours = parseInt((iLeftMs - (iDays * iDms)) / iHms), // elapsed hours
                            iMin = parseInt((iLeftMs - (iDays * iDms) - (iHours * iHms)) / iMms), // elapsed minutes
                            iSec = parseInt((iLeftMs - (iDays * iDms) - (iMin * iMms) - (iHours * iHms)) / iCms), // elapsed seconds
                            iPerc = (iElapsedMs > 0) ? iElapsedMs / iDuration * 100 : 0; // percentages

                        // display current positions and progress
                        jQuery(vPb).children('.percent').html('<b>'+iPerc.toFixed(1)+'%</b>');
                        jQuery(vPb).children('.elapsed').html(iDays+' days '+iHours+'h:'+iMin+'m:'+iSec+'s</b>');
                        jQuery(vPb).children('.pbar').children('.ui-progressbar-value').css('width', iPerc+'%');

                        // in case of Finish
                        if (iPerc >= 100) {
                            clearInterval(vInterval);
                            jQuery(vPb).children('.percent').html('<b>100%</b>');
                            jQuery(vPb).children('.elapsed').html('Finished');
                        }
                    } ,aOpts.interval
                );
            }
        );
    }

    // default mode
    jQuery('#progress1').anim_progressbar();

    // from second #2 till 15
    var iNow = new Date().setTime(new Date().getTime() + 2 * 1000); // now plus 2 secs
    var iEnd = new Date().setTime(new Date().getTime() + 15 * 1000); // now plus 15 secs
    jQuery('#progress2').anim_progressbar({start: iNow, finish: iEnd, interval: 100});

    // we will just set interval of updating to 1 sec
    jQuery('#progress3').anim_progressbar({interval: 1000});
});