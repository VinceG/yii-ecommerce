/*!
---------------------------------
	Docs Datatable
---------------------------------
*/
jQuery(document).ready(function() {
    jQuery('#datatable_1docs, #datatable_2docs').dataTable( {
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": false,
        "bInfo": false,
        "bAutoWidth": false
    } );
} );
jQuery(document).ready(function() {
    jQuery('#datatable_req_docs').dataTable( {
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bSort": true,
        "bInfo": false,
        "bAutoWidth": false
    } );
} );

/*!
---------------------------------
	Docs Dialog
---------------------------------
*/
$(function() {
	$( "#dialog" ).dialog({
		autoOpen: false,
		show: "blind",
		hide: "explode",
		buttons: {
			OK: function() {
				$( this ).dialog( "close" );
			}
		}
	});

	$( "#opener" ).click(function() {
		$( "#dialog" ).dialog( "open" );
		return false;
	});
});



