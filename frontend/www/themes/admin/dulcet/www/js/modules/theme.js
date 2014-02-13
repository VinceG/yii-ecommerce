var editor;
jQuery(function() {
	/**
	 * Load a file into the editor
	 */
	$('.edit-theme-file').on('click', function() {
		var $fileId = $(this).attr('id').replace('theme_file_', '');
		if(!$fileId) {
			alert('Sorry, That file was not found!');
			return false;
		}
		
		// Set file
		$('#current_loaded_theme_file').val($fileId);
		
		// Load content
		$.ajax({
		  url: "/admin/themes/GetAjaxThemeFile",
		  data: {"fileId" : $fileId},
	      dataType: "json",
		  success: function(data){
			if(data.error && data.error != '') {
				alert(data.error);
				return;
			}
			$('#theme_editor_div').html(data.html);
			editor = buildEditor('ThemeFile_content');
			editor.focus();
		  }
		});
	});
	
	/**
	 * Cancel template editing
	 */
	$('#cancel_template_edit').live('click', function() {
		$('#theme_editor_div').html('Select a file to see the editor.');
	});
	
	/**
	 * save template editing
	 */
	$('#save_template_edit').live('click', function() {
		saveEditorContents();
	});
});

/**
 * Save editor contents
 */
function saveEditorContents() {
	var $val = editor.getValue();
	var $fileId = $('#current_loaded_theme_file').val();
	if(!$fileId) {
		alert('Sorry, That file was not found!');
		return false;
	}
	// Load content
	$.ajax({
	  url: "/admin/themes/AjaxSetThemeFileContent",
	  data: {"fileId" : $fileId, 'content': $val},
      dataType: "json",
	  type: 'POST',
	  success: function(data){
		if(data.error && data.error != '') {
			alert(data.error);
			return;
		}
		alert(data.html);
	  }
	});
}

/**
 * Build editor
 */
function buildEditor(textAreaId) {
	editor = CodeMirror.fromTextArea(document.getElementById(textAreaId), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
		onCursorActivity: function() {
		   editor.matchHighlight("CodeMirror-matchhighlight");
		},
		extraKeys: {
		"Ctrl-S": function(cm) {
          saveEditorContents();
        },
		"Cmd-S": function(cm) {
          saveEditorContents();
        }
      }
    });

	return editor;
}