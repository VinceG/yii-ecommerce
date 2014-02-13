/*
A Geshi plugin for CKEditor (3.x)
Ver 1.0

Created by PkLab.net starting from ckeditor-syntaxhighlight 
(http://www.ramble.in/ckeditor/syntaxhighlight/)
 
 For more information about installation and how to manage the code see
 http://www.pklab.net/index.php?id=350
 
It create an HTML code like following
<pre class="geshi:<language>;[line_num:false;]">
your source code
</pre>

Where
- <language> is one of Geshi supported language
- [line_num:false] (optional) specify Geshi line numbering should be used 
 
 INSTALLATION
 ------------
 1.Download Geshi source code (http://qbnz.com/highlighter/)
 2.Update your ckeditor configuration to use this plugin :
 
  config.extraPlugins = 'geshi';
  config.toolbar_Full.push(['Code']);
 
 3. Write a PHP code to extract <pre class="geshi:... from your document and manage it with Geshi

 $Rev: 124 $
 $Date: 2010-01-22 17:43:52 +0100 (ven, 22 gen 2010) $
*/
CKEDITOR.plugins.add('geshi',
{
	requires : [ 'dialog' ],
	lang : [ 'en' ],
	
	init : function(editor)
	{
		var pluginName = 'geshi';
		var command = editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName) );
		command.modes = { wysiwyg:1, source:1 };
		command.canUndo = false;

		editor.ui.addButton('Code',
		{
				label : editor.lang.geshi.title,
				command : pluginName,
				icon: this.path + 'images/geshi.png'
		});

		CKEDITOR.dialog.add(pluginName, this.path + 'dialogs/geshi.js' );
	}
});

