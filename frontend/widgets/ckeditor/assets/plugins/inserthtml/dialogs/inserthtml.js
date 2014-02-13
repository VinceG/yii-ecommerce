/*********************************************************************************************************/
/**
 * inserthtml plugin for CKEditor 3.x (Author: Lajox ; Email: lajox@19www.com)
 * version:	 1.0
 * Released: On 2009-12-11
 * Download: http://code.google.com/p/lajox
 */
/*********************************************************************************************************/

CKEDITOR.dialog.add("inserthtml",function(e){	
	return{
		title:e.lang.inserthtml.title,
		resizable : CKEDITOR.DIALOG_RESIZE_BOTH,
		minWidth:380,
		minHeight:220,
		onShow:function(){ 
		},
		onLoad:function(){ 
				dialog = this; 
				this.setupContent();
		},
		onOk:function(){
			var sInsert=this.getValueOf('info','insertcode_area');   
			if ( sInsert.length > 0 ) 
			e.insertHtml(sInsert); 
		},
		contents:[
			{	id:"info",
				name:'info',
				label:e.lang.inserthtml.commonTab,
				elements:[{
				 type:'vbox',
				 padding:0,
				 children:[
				  {type:'html',
				  html:'<span>'+e.lang.inserthtml.HelpInfo+'</span>'
				  },
				  { type:'textarea',
				    id:'insertcode_area',
					label:'',
					cols:60,
					rows:11
				  }]
				}]
			}
		]
	};});