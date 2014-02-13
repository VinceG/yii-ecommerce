/*
---------------------------------
	Main Menu
---------------------------------
*/

 $(document).ready(function(){ 
	$("ul.sf-menu").supersubs({ 
		minWidth:    15, //min width in em
		maxWidth:    20,
		extraWidth:  1 
	}).superfish({
		delay:	0,
		animation: {height:'show'}
	});
}); 

/*
---------------------------------
	Forms
---------------------------------
*/ 

/* Equal fields */
function equalHeight(group) {
	var tallest = 0;
	group.each(function() { 
		var thisHeight = jQuery(this).height();
		if(thisHeight > tallest) {
			tallest = thisHeight;
		}
	});
	group.height(tallest);
}
jQuery(document).ready(function() {
	equalHeight(jQuery(".formee-equal"));
});
jQuery(window).resize(function() {
	equalHeight(jQuery(".formee-equal"));
});


/*
---------------------------------
	Form validation
---------------------------------
*/
jQuery(document).ready(function(){
	jQuery("form").validationEngine('attach', {promptPosition : "bottomRight", autoPositionUpdate : true});
});


/*
---------------------------------
	AutoGrow Textarea
---------------------------------
*/
jQuery(document).ready(function(){
    jQuery(".txt_autogrow").autoGrow();
});

jQuery( document ).ready(function(){
	$('.fallr-open').on('click', function() {
		openFallr('Message');
	});
});

/**
 * Open Fallr Popup
 *
 */
function openFallr(message) {
	var gap     = 20;
	var boxH    = jQuery(window).height() - gap;     // bottom gap
	var boxW    = jQuery(window).width() - gap * 2;  // left + right gap
	jQuery.fallr('show', {
		buttons         : { 
                            // object contains buttons definition
                            button1 : {
                                        text    : 'Close',                 // default button text
                                        danger  : false,                // is the button trigger dangerous action?
                                        onclick : function(){           // default button function 
                                                    $.fallr('hide'); 
                                                  }
                            }
                          },
        icon            : '',          // [string] icon displayed
        content         : message,          // [string] fallr content
        position        : 'top',            // [string] top/center/bottom
        closeKey        : true,            // [bool] close fallr with ESC key
        closeOverlay    : true,            // [bool] close fallr on overlay click
        useOverlay      : true,             // [bool] should overlay be shown
        height          : boxH,           // [string] css value for exact height
        width           : boxW,          // [string] css value for exact width
	});
}

/*
---------------------------------
	MultiSelect
---------------------------------
*/

jQuery(function(){
  jQuery(".multiselect1").multiselect({sortable: true, searchable: true});
});


/*
---------------------------------
	Prettify [sytanx highlighter]
---------------------------------
*/
jQuery( document ).ready(function(){
	//$('pre, code').addClass('prettyprint');
	//prettyPrint();
});
