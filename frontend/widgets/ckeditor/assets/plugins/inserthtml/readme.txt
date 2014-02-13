
/*********************************************************************************************************/
/**
 * inserthtml plugin for CKEditor 3.x (Author: Lajox ; Email: lajox@19www.com)
 * version:	 1.0
 * Released: On 2009-12-11
 * Download: http://code.google.com/p/lajox
 */
/*********************************************************************************************************/

/**************************************************************************************************************
inserthtml plugin for CKEditor 3.x

 --Insert Html Code Plugin

Plugin Description： CKEditor 3.0 Insert Html Code Plugin 1.0

***************************************************************************************************************/


/**************Help Begin***************/

1. Upload inserthtml folder to  ckeditor/plugins/

2. Configured in the ckeditor/config.js :
    Add to config.toolbar a value 'inserthtml'
e.g. 

config.toolbar = 
[
    [ 'Source', '-', 'Bold', 'Italic', 'inserthtml' ]
];


3. Again Configured in the ckeditor/config.js ,
   Expand the extra plugin 'inserthtml' such as:

config.extraPlugins='myplugin1,myplugin2,inserthtml';

4. Modify the default language in inserthtml/plugin.js
	Just the line:
		lang : ['en'],

/**************Help End***************/


