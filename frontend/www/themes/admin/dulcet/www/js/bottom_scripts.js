var $lastToast = null;
/*
---------------------------------
	LightBox
---------------------------------
*/
  jQuery(document).ready(function(jQuery){
	jQuery('.lightbox').lightbox();
  });


// Remove validation errors when switching between tabs  
$(document).ready(function() {
    $('.tabs-header-class').on('click', function() {
		$('.formError').remove();
	});
} );

/*
---------------------------------
	Forms
---------------------------------
*/
jQuery(function(){
    jQuery("input:checkbox, input:radio, input:file").uniform();

	UpdateChosen();
	
	// Check new PM Messages
	if($application.req.controller != 'personalmessages') {
		// Check when page loads
		checkNewPMMessages();
		if($application.settings.pm_ajax_check_messages > 0) {
			// Set interval
			setInterval(function() {
			     checkNewPMMessages();
			}, $application.settings.pm_ajax_check_messages * 1000);
		}
	}
	
	// Close Modal element
	$('#close-modal-element').live('click', function() {
		$('#admin-modal-window').modal('hide');
	});
	
	// Reply message event
	$('#create-message').live('click', function() {
		$.ajax({
		  url: "/admin/personalmessages/GetAjaxTopicForm",
	      dataType: "json",
		  cache: false,
		  success: function(data) {
				if(data.error) {
					alert(data.error);
					return;
				}
				
				// Enter data into the modal divs
				$('#modal-header-div > h3').html(data.title);
				$('#modal-body-div').html(data.html);

				// Footer
				if(data.footer) {
					$('#modal-footer-div').html(data.footer);
				}
				
				if(!$('#admin-modal-window').hasClass('in')) {
					// Show Modal Window
					$('#admin-modal-window').modal('show');
				}
			}
		});
	});
	
	// create message
	$('#create-pm-message').live('click', function() {
		$.ajax({
		  url: "/admin/personalmessages/PostAjaxCreateMessage",
		  data: {
					title: $('.pm-create-message-title').val(), 
					type: $('.pm-create-message-type').val(), 
					to: $('.pm-create-message-to').val(), 
					message: getEditorContent('#pm-reply-message')},
	      dataType: "json",
		  cache: false,
		  type: 'POST',
		  success: function(data) {
				if(data.error) {
					alert(data.error);
					return;
				}
				
				// Enter data into the modal divs
				$('#modal-header-div > h3').html(data.title);
				$('#modal-body-div').html(data.html);

				// Footer
				if(data.footer) {
					$('#modal-footer-div').html(data.footer);
				}
			}
		});
	});
	
	// Reply message event
	$('#reply-message, #reply-message-normal').live('click', function() {
		// Hide toast
		if($lastToast) {
			jQuery().toastmessage('removeToast', $lastToast);
		}
		$.ajax({
		  url: "/admin/personalmessages/GetAjaxMessageView",
		  data: {topicId: $('#topic_id').val()},
	      dataType: "json",
		  cache: false,
		  success: function(data) {
				if(data.error) {
					alert(data.error);
					return;
				}
				
				// Enter data into the modal divs
				$('#modal-header-div > h3').html(data.title);
				$('#modal-body-div').html(data.html);

				// Footer
				if(data.footer) {
					$('#modal-footer-div').html(data.footer);
				}
				
				if(!$('#admin-modal-window').hasClass('in')) {
					// Show Modal Window
					$('#admin-modal-window').modal('show');
				}
				
				// update within 1 sec
				setTimeout("UpdateChosen();", 2000);
			}
		});
	});
	
	// Send Reply message event
	$('#send-reply-message').live('click', function() {
		$.ajax({
		  url: "/admin/personalmessages/PostAjaxPMMessage",
		  data: {topicId: $('#topic_id').val(), message: getEditorContent('#pm-reply-message')},
	      dataType: "json",
		  cache: false,
		  type: 'POST',
		  success: function(data) {
				if(data.error) {
					alert(data.error);
					return;
				}
				
				// Enter data into the modal divs
				$('#modal-header-div > h3').html(data.title);
				$('#modal-body-div').html(data.html);

				// Footer
				if(data.footer) {
					$('#modal-footer-div').html(data.footer);
				}
			}
		});
	});
	
});

function checkNewPMMessages() {
	// make sure the modal is not open
	if($('#admin-modal-window').hasClass('in')) {
		return;
	}
	$.ajax({
	  url: "/admin/personalmessages/GetLastNewMessage",
      dataType: "json",
	  cache: false,
	  success: function(data){
		if(data.text) {
			if(data.type>0) {
				// Urgent
				/*jQuery.fallr('show', {
					content : '<p>'+ data.text +'</p>',
					icon    : 'info'
				});*/

				// Enter data into the modal divs
				$('#modal-header-div > h3').html(data.title);
				$('#modal-body-div').html(data.html);
				
				// Footer
				if(data.footer) {
					$('#modal-footer-div').html(data.footer);
				}
				
				// Show Modal Window
				$('#admin-modal-window').modal('show');
			} else {
				// Normal
				$lastToast = jQuery().toastmessage('showToast', {
				       text     : data.html,
					   stayTime   : 10000,
				       position : 'bottom-right',
				       type     : 'notice'
				 });
			}
		}
	  }
	});
}

function getEditorContent(selector) {
	switch($application.settings.editor) {
		case 'redactor':
			return $(selector).getCode();
		break;
		
		case 'tinymce':
			return tinyMCE.get(selector.replace('#', '')).getContent();
		break;
		
		case 'ckeditor':
		default:
			selector = selector.replace('#', '');
			var instance = CKEDITOR.instances[selector];
			return instance.getData();
		break;
	}
}

function UpdateChosen() {
	jQuery(".chzn-select").chosen(); 
	jQuery(".chzn-select-nosearch").chosen({disable_search_field:true});
	jQuery(".chzn-select-deselect").chosen({allow_single_deselect:true});
	$(".chzn-select").trigger("liszt:updated");
}

jQuery(function() {
	jQuery(" .ui_tabs ").tabs();
	
	jQuery( ".datePickerBirthDate" ).datepicker({
		showAnim: 'slide',
		changeMonth: true,
		changeYear: true,
		yearRange: 'c-50:c'
	});
	
	// Highlight autocomplete search term
	if($.ui.autocomplete) {
		$.ui.autocomplete.prototype._renderItem = function (ul, item) {
		    item.label = item.value.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
            return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append("<a class=\""+ item.class +"\">" + item.label + "</a>")
                    .appendTo(ul);
        };
	}
	
	// Input masks
	//$(".date-input-mask").inputmask("d/m/y");  //direct mask
	//$(".phone-input-mask").inputmask("(999) 999-9999"); //specifying fn & options
	//$(".phone-ext-input-mask").inputmask("(999) 999-9999? x99999"); //specifying fn & options
});

/** 
 * Show error fallr message
 */
function showErrorFallr(msg) {
	jQuery.fallr('show', {
		content : '<p>'+ msg +'</p>',
		icon    : 'error'
	});
}

/** 
 * Show info fallr message
 */
function showInfoFallr(msg) {
	jQuery.fallr('show', {
		content : '<p>'+ msg +'</p>',
		icon    : 'info'
	});
}

/**
 * Show sucess message
 */
function ajaxOK(msg) {
	jQuery().toastmessage('showToast', {
	       text     : msg,
	       position : 'bottom-right',
	       type     : 'success'
	 });
}

/**
 * Show info message
 */
function ajaxInfo(msg) {
	jQuery().toastmessage('showToast', {
	       text     : msg,
	       position : 'bottom-right',
	       type     : 'info'
	 });
}

/*
---------------------------------
	Alerts
---------------------------------
*/
//jQuery('<span class="alert_close"></span>').prependTo('.alert');
jQuery('.alert_close').click(function () {
      jQuery(this).parent(".alert").slideUp(500);
});
/*
---------------------------------
	qTip Tooltips
---------------------------------
*/
jQuery('.tip-top').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'bottom center',	at: 'top center'	}
});
jQuery('.tip-bottom').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'top center',	at: 'bottom center'	}
});
jQuery('.tip-left').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'right center',	at: 'left center'	}
});
jQuery('.tip-right').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'left center',	at: 'right center'	}
});

jQuery('.tip-leftbottom').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'right top',	at: 'left bottom'	}
});
jQuery('.tip-lefttop').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'right bottom',	at: 'left top'	}
});

jQuery('.tip-rightbottom').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'left top',	at: 'right bottom'	}
});
jQuery('.tip-righttop').qtip({
   content: {	attr: 'title'	},
   position:{	my: 'left bottom',	at: 'right top'	}
});
