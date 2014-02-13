
/* Basic Data Table
------------------------------------------------------------------*/
jQuery(document).ready(function() {
    jQuery('#datatable_1').dataTable();
} );

/*Custom Pagination
------------------------------------------------------------------*/
jQuery(document).ready(function() {
    jQuery('#datatable_2').dataTable( {
        "sPaginationType": "full_numbers"
    } );
} );

/*Ajax source [+custom pagination]
------------------------------------------------------------------*/
jQuery(document).ready(function() {
    jQuery('#datatable_3').dataTable( {
        "bProcessing": true,
        "sAjaxSource": 'ajax_examples/arrays.txt',		
        "sPaginationType": "full_numbers"
    } );
} );


/*DataTables hidden row details
------------------------------------------------------------------*/
/* Formating function for row details */
function fnFormatDetails ( oTable, nTr )
{
	var aData = oTable.fnGetData( nTr );
	var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';
	sOut += '<tr><td>Rendering engine:</td><td>'+aData[1]+' '+aData[4]+'</td></tr>';
	sOut += '<tr><td>Link to source:</td><td>Could provide a link here</td></tr>';
	sOut += '<tr><td>Extra info:</td><td>And any further details here (images etc)</td></tr>';
	sOut += '</table>';
	
	return sOut;
}

jQuery(document).ready(function() {
	/*
	 * Insert a 'details' column to the table
	 */
	var nCloneTh = document.createElement( 'th' );
	var nCloneTd = document.createElement( 'td' );
	nCloneTd.innerHTML = '<img src="/themes/admin/images/details_open.png">';
	nCloneTd.className = "center";
	
	jQuery('#datatable_4 thead tr').each( function () {
		this.insertBefore( nCloneTh, this.childNodes[0] );
	} );
	
	jQuery('#datatable_4 tbody tr').each( function () {
		this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
	} );
	
	/*
	 * Initialse DataTables, with no sorting on the 'details' column
	 */
	var oTable = jQuery('#datatable_4').dataTable( {
		"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0 ] }
		],
		"aaSorting": [[1, 'asc']],		
        "sPaginationType": "full_numbers"
	});
	
	/* Add event listener for opening and closing details
	 * Note that the indicator for showing which row is open is not controlled by DataTables,
	 * rather it is done here
	 */
	jQuery('#datatable_4 tbody td img').live('click', function () {
		var nTr = this.parentNode.parentNode;
		if ( this.src.match('details_close') )
		{
			/* This row is already open - close it */
			this.src = "/themes/admin/images/details_open.png";
			oTable.fnClose( nTr );
		}
		else
		{
			/* Open this row */
			this.src = "/themes/admin/images/details_close.png";
			oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
		}
	} );
} );


/*DataTables row select
------------------------------------------------------------------*/
jQuery(document).ready(function() {
    /* Add a click handler to the rows - this could be used as a callback */
    jQuery('#datatable_5 tr').click( function() {
        if ( jQuery(this).hasClass('row_selected') )
            jQuery(this).removeClass('row_selected');
        else
            jQuery(this).addClass('row_selected');
    } );
     
    /* Init the table */
    var oTable = jQuery('#datatable_5').dataTable({		
        "sPaginationType": "full_numbers"
	} );
} );
 
/*
 * I don't actually use this here, but it is provided as it might be useful and demonstrates
 * getting the TR nodes from DataTables
 */
function fnGetSelected( oTableLocal )
{
    var aReturn = new Array();
    var aTrs = oTableLocal.fnGetNodes();
     
    for ( var i=0 ; i<aTrs.length ; i++ )
    {
        if ( jQuery(aTrs[i]).hasClass('row_selected') )
        {
            aReturn.push( aTrs[i] );
        }
    }
    return aReturn;
};


/*Show and hide columns dynamically [no Filter, no LengthChange]
------------------------------------------------------------------*/
jQuery(document).ready(function() {
		jQuery('#datatable_6').dataTable( {
			"sPaginationType": "full_numbers",
			"bFilter": false,
			"bLengthChange": false,
		} );
	} );
	
function fnShowHide( iCol )
{
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable = jQuery('#datatable_6').dataTable();
		
		var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
		oTable.fnSetColumnVis( iCol, bVis ? false : true );
};
